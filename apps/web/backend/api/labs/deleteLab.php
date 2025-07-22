<!-- this code is executed by cronjob minikube that exist every 60 sec -->
<?php

require __DIR__ . '/../../vendor/autoload.php';
$db = require_once __DIR__ . "/../../database/db.php";

use K8s\Client\K8sFactory;
use K8s\Api\Model\Api\Apps\v1\Deployment;
use K8s\Api\Model\Api\Core\v1\Service;
use K8s\Api\Model\Api\Networking\V1\Ingress;

// Siapkan koneksi K8s in-cluster
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

$k8s = (new K8sFactory())->loadFromKubeConfigData($kubeconfig);
$namespace = 'vulnarenalab';

// Fungsi hapus resource
function deleteLabResources($k8s, string $labName, string $namespace): bool {
    try {
        // delete deployment
        $deployment = $k8s->newKind([
            'apiVersion' => 'apps/v1',
            'kind' => 'Deployment',
            'metadata' => [
                'name' => "{$labName}-deployment",
                'namespace' => $namespace
            ]
        ]);

        // delete service
        $service = $k8s->newKind([
            'apiVersion' => 'v1',
            'kind' => 'Service',
            'metadata' => [
                'name' => "{$labName}-svc",
                'namespace' => $namespace
            ]
        ]);

        // delete ingress
        $ingress = $k8s->newKind([
            'apiVersion' => 'networking.k8s.io/v1',
            'kind' => 'Ingress',
            'metadata' => [
                'name' => "{$labName}-ingress",
                'namespace' => $namespace
            ]
        ]);

        $k8s->delete($deployment);
        $k8s->delete($service);
        $k8s->delete($ingress);

        echo "[✓] Deleted all resources for {$labName}\n";
        return true;
    } catch (Exception $e) {
        echo "[✗] Failed deleting {$labName}: " . $e->getMessage() . "\n";
        return false;
    }
}

// Jalankan cleanup, update db
$now = time();
$query = "SELECT lab_id FROM lab WHERE isactive = 1 AND expiredtime < ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $now);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $labId = $row['lab_id'];
    $labName = "lab-{$labId}";
    echo "[>] Proses hapus untuk {$labName}\n";

    if (deleteLabResources($k8s, $labName, $namespace)) {
        $update = $db->prepare("UPDATE lab SET isactive = 0 WHERE lab_id = ?");
        $update->bind_param("s", $labId);
        $update->execute();
        $update->close();
        echo "[✓] DB updated: {$labId} diset nonaktif\n";
    } else {
        echo "[!] Gagal hapus {$labName}, DB tidak diubah\n";
    }
}

$stmt->close();
$db->close();

echo "[✔] Cleanup selesai.\n";
exit;
?>