<?php
require_once __DIR__ . "/../../middleware/securityHeader.php";
require_once __DIR__ . "/../../middleware/middleware.php";
$middleware = new middleware;

if(!$middleware->isPostMethod()){
    $middleware->errorResponse(405,"Only POST method is allowed");
    exit;
}

if(!$middleware->isAuthCheck()){
    $middleware->errorResponse(401, "Unauthorized");
    exit;
}

if($middleware->isSessionExpired()){
    $middleware->errorResponse(401, "Session expired");
    exit;
}

if(!$middleware->isCsrf()){
    $middleware->errorResponse(403, "Invalid CSRF token");
    exit;
}

$db = require_once __DIR__ . "/../../database/db.php";
$user_id = $_SESSION['user_id'];

$query = "SELECT lab_id, subdomain, pod_name, isactive, createtime, expiredtime FROM lab WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$row = $result->fetch_assoc();
$stmt->close();

if($row && $row['isactive']){
    $middleware->errorResponse(400, "Lab is already running in $row[subdomain]");
    exit;
}

// create lab
require __DIR__ . '/../../vendor/autoload.php';
use K8s\Api\Model\Api\Core\v1\Pod;
use K8s\Client\K8sFactory;

// Ambil token
$token = trim(file_get_contents('/var/run/secrets/kubernetes.io/serviceaccount/token'));

$kubeconfig = <<<YAML
apiVersion: v1
kind: Config
clusters:
- cluster:
    certificate-authority: /var/run/secrets/kubernetes.io/serviceaccount/ca.crt
    server: https://kubernetes.default.svc
  name: in-cluster
contexts:
- context:
    cluster: in-cluster
    user: in-cluster
  name: in-cluster
current-context: in-cluster
users:
- name: in-cluster
  user:
    token: "$token"
YAML;

// Pakai fungsi loadFromKubeConfigData (PASTIKAN ini yg dipakai!)
$k8s = (new K8sFactory())->loadFromKubeConfigData($kubeconfig);

//generate lab_id
do {
  $labId = bin2hex(random_bytes(5)); 

  $query = "SELECT lab_id FROM lab WHERE lab_id = ? AND isactive = 1 LIMIT 1";
  $stmt = $db->prepare($query);
  $stmt->bind_param("s", $labId);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();

} while ($result->num_rows !== 0);

$subdomain = "lab-{$labId}.vulnarena.space";
$labName = "lab-{$labId}";
$podName = "{$labName}-app";
$namespace = 'vulnarenalab';


// deployment manifest
$deploymentManifest = [
    'apiVersion' => 'apps/v1',
    'kind' => 'Deployment',
    'metadata' => [
        'name' => "{$labName}-deployment",  
        'namespace' => $namespace,
        'labels' => [
            'app' => $podName
        ]
    ],
    'spec' => [
        'replicas' => 1,
        'selector' => [
            'matchLabels' => [
                'app' => $podName
            ]
        ],
        'template' => [
            'metadata' => [
                'labels' => [
                    'app' => $podName
                ]
            ],
            'spec' => [
                'containers' => [
                    [
                        'name' => "lab",
                        'image' => "vulnarena-lab:latest", // ganti sesuai nama image di k8s node/registry 
                        'imagePullPolicy' => 'IfNotPresent',
                        'ports' => [
                            ['containerPort' => 80]
                        ],
                        'resources' => [
                            'requests' => [
                                'cpu' => '100m',
                                'memory' => '128Mi'
                            ],
                            'limits' => [
                                'cpu' => '400m',
                                'memory' => '512Mi'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

// service manifest
$serviceManifest = [
    'apiVersion' => 'v1',
    'kind' => 'Service',
    'metadata' => [
        'name' => "{$labName}-svc", 
        'namespace' => $namespace, 
        'labels' => [
            'app' => $podName, 
        ]
    ],
    'spec' => [
        'selector' => [
            'app' => $podName
        ],
        'ports' => [
            [
                'protocol' => 'TCP',
                'port' => 80,
                'targetPort' => 80
            ]
        ],
        'type' => 'ClusterIP'
    ]
];

// ingress manifest
$ingressManifest = [
    'apiVersion' => 'networking.k8s.io/v1',
    'kind' => 'Ingress',
    'metadata' => [
        'name' => "{$labName}-ingress", 
        'namespace' => $namespace,
        'labels' => [
            'app' => $podName,
        ],
        'annotations' => [
            // Optional: setting untuk nginx ingress
            'nginx.ingress.kubernetes.io/rewrite-target' => '/',
        ]
    ],
    'spec' => [
        'ingressClassName' => 'nginx',
        'rules' => [
            [
                'host' => $subdomain, 
                'http' => [
                    'paths' => [
                        [
                            'path' => '/',
                            'pathType' => 'Prefix',
                            'backend' => [
                                'service' => [
                                    'name' => "{$labName}-svc",
                                    'port' => [
                                        'number' => 80
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

// create lab
try {
    $deployment = $k8s->create($k8s->newKind($deploymentManifest));
    // var_dump($deployment);

    $service = $k8s->create($k8s->newKind($serviceManifest));
    // var_dump($service);

    $ingress = $k8s->create($k8s->newKind($ingressManifest));
    // var_dump($ingress);
} catch (Exception $e) {
    error_log('K8s resource creation failed: ' . $e->getMessage());
    $middleware->errorResponse(500, "Failed to create lab resources: " . $e->getMessage());
}

$createtime = time();
$expiredtime = time() + 1805; // lab time 1800 + 5 sec as grace period during creation
$isActive = 1;

// update status to DB
$query = "UPDATE lab SET lab_id = ?, subdomain = ?, pod_name = ?, createtime = ?, expiredtime = ?, isactive = ? WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("sssiiii", $labId, $subdomain, $podName, $createtime, $expiredtime, $isActive, $user_id);
$stmt->execute();
$stmt->close();

echo json_encode([
    'success' => true,
    'message' => 'Lab created successfully',
    'data' => [
        'subdomain' => $subdomain,
        'createtime' => $createtime,
        'expiredtime' => $expiredtime
    ]
]);

exit;
?>