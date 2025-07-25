<?php

/**
 * This file was automatically generated by k8s/api-generator 0.12.0 for API version v1.25.16
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace K8s\Api\Model\Api\Certificates\v1;

use K8s\Core\Annotation as Kubernetes;

/**
 * CertificateSigningRequestSpec contains the certificate request.
 */
class CertificateSigningRequestSpec
{
    /**
     * @Kubernetes\Attribute("expirationSeconds")
     * @var int|null
     */
    protected $expirationSeconds = null;

    /**
     * @Kubernetes\Attribute("extra")
     * @var array|null
     */
    protected $extra = null;

    /**
     * @Kubernetes\Attribute("groups")
     * @var string[]|null
     */
    protected $groups = null;

    /**
     * @Kubernetes\Attribute("request",isRequired=true)
     * @var string
     */
    protected $request;

    /**
     * @Kubernetes\Attribute("signerName",isRequired=true)
     * @var string
     */
    protected $signerName;

    /**
     * @Kubernetes\Attribute("uid")
     * @var string|null
     */
    protected $uid = null;

    /**
     * @Kubernetes\Attribute("usages")
     * @var string[]|null
     */
    protected $usages = null;

    /**
     * @Kubernetes\Attribute("username")
     * @var string|null
     */
    protected $username = null;

    /**
     * @param string $request
     * @param string $signerName
     */
    public function __construct(string $request, string $signerName)
    {
        $this->request = $request;
        $this->signerName = $signerName;
    }

    /**
     * expirationSeconds is the requested duration of validity of the issued certificate. The certificate
     * signer may issue a certificate with a different validity duration so a client must check the delta
     * between the notBefore and and notAfter fields in the issued certificate to determine the actual
     * duration.
     *
     * The v1.22+ in-tree implementations of the well-known Kubernetes signers will honor this field as
     * long as the requested duration is not greater than the maximum duration they will honor per the
     * --cluster-signing-duration CLI flag to the Kubernetes controller manager.
     *
     * Certificate signers may not honor this field for various reasons:
     *
     *   1. Old signer that is unaware of the field (such as the in-tree
     *      implementations prior to v1.22)
     *   2. Signer whose configured maximum is shorter than the requested duration
     *   3. Signer whose configured minimum is longer than the requested duration
     *
     * The minimum valid value for expirationSeconds is 600, i.e. 10 minutes.
     */
    public function getExpirationSeconds(): ?int
    {
        return $this->expirationSeconds;
    }

    /**
     * expirationSeconds is the requested duration of validity of the issued certificate. The certificate
     * signer may issue a certificate with a different validity duration so a client must check the delta
     * between the notBefore and and notAfter fields in the issued certificate to determine the actual
     * duration.
     *
     * The v1.22+ in-tree implementations of the well-known Kubernetes signers will honor this field as
     * long as the requested duration is not greater than the maximum duration they will honor per the
     * --cluster-signing-duration CLI flag to the Kubernetes controller manager.
     *
     * Certificate signers may not honor this field for various reasons:
     *
     *   1. Old signer that is unaware of the field (such as the in-tree
     *      implementations prior to v1.22)
     *   2. Signer whose configured maximum is shorter than the requested duration
     *   3. Signer whose configured minimum is longer than the requested duration
     *
     * The minimum valid value for expirationSeconds is 600, i.e. 10 minutes.
     *
     * @return static
     */
    public function setExpirationSeconds(int $expirationSeconds)
    {
        $this->expirationSeconds = $expirationSeconds;

        return $this;
    }

    /**
     * extra contains extra attributes of the user that created the CertificateSigningRequest. Populated by
     * the API server on creation and immutable.
     */
    public function getExtra(): ?array
    {
        return $this->extra;
    }

    /**
     * extra contains extra attributes of the user that created the CertificateSigningRequest. Populated by
     * the API server on creation and immutable.
     *
     * @return static
     */
    public function setExtra(array $extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * groups contains group membership of the user that created the CertificateSigningRequest. Populated
     * by the API server on creation and immutable.
     */
    public function getGroups(): ?array
    {
        return $this->groups;
    }

    /**
     * groups contains group membership of the user that created the CertificateSigningRequest. Populated
     * by the API server on creation and immutable.
     *
     * @return static
     */
    public function setGroups(array $groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * request contains an x509 certificate signing request encoded in a "CERTIFICATE REQUEST" PEM block.
     * When serialized as JSON or YAML, the data is additionally base64-encoded.
     */
    public function getRequest(): string
    {
        return $this->request;
    }

    /**
     * request contains an x509 certificate signing request encoded in a "CERTIFICATE REQUEST" PEM block.
     * When serialized as JSON or YAML, the data is additionally base64-encoded.
     *
     * @return static
     */
    public function setRequest(string $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * signerName indicates the requested signer, and is a qualified name.
     *
     * List/watch requests for CertificateSigningRequests can filter on this field using a
     * "spec.signerName=NAME" fieldSelector.
     *
     * Well-known Kubernetes signers are:
     *  1. "kubernetes.io/kube-apiserver-client": issues client certificates that can be used to
     * authenticate to kube-apiserver.
     *   Requests for this signer are never auto-approved by kube-controller-manager, can be issued by the
     * "csrsigning" controller in kube-controller-manager.
     *  2. "kubernetes.io/kube-apiserver-client-kubelet": issues client certificates that kubelets use to
     * authenticate to kube-apiserver.
     *   Requests for this signer can be auto-approved by the "csrapproving" controller in
     * kube-controller-manager, and can be issued by the "csrsigning" controller in
     * kube-controller-manager.
     *  3. "kubernetes.io/kubelet-serving" issues serving certificates that kubelets use to serve TLS
     * endpoints, which kube-apiserver can connect to securely.
     *   Requests for this signer are never auto-approved by kube-controller-manager, and can be issued by
     * the "csrsigning" controller in kube-controller-manager.
     *
     * More details are available at
     * https://k8s.io/docs/reference/access-authn-authz/certificate-signing-requests/#kubernetes-signers
     *
     * Custom signerNames can also be specified. The signer defines:
     *  1. Trust distribution: how trust (CA bundles) are distributed.
     *  2. Permitted subjects: and behavior when a disallowed subject is requested.
     *  3. Required, permitted, or forbidden x509 extensions in the request (including whether
     * subjectAltNames are allowed, which types, restrictions on allowed values) and behavior when a
     * disallowed extension is requested.
     *  4. Required, permitted, or forbidden key usages / extended key usages.
     *  5. Expiration/certificate lifetime: whether it is fixed by the signer, configurable by the admin.
     *  6. Whether or not requests for CA certificates are allowed.
     */
    public function getSignerName(): string
    {
        return $this->signerName;
    }

    /**
     * signerName indicates the requested signer, and is a qualified name.
     *
     * List/watch requests for CertificateSigningRequests can filter on this field using a
     * "spec.signerName=NAME" fieldSelector.
     *
     * Well-known Kubernetes signers are:
     *  1. "kubernetes.io/kube-apiserver-client": issues client certificates that can be used to
     * authenticate to kube-apiserver.
     *   Requests for this signer are never auto-approved by kube-controller-manager, can be issued by the
     * "csrsigning" controller in kube-controller-manager.
     *  2. "kubernetes.io/kube-apiserver-client-kubelet": issues client certificates that kubelets use to
     * authenticate to kube-apiserver.
     *   Requests for this signer can be auto-approved by the "csrapproving" controller in
     * kube-controller-manager, and can be issued by the "csrsigning" controller in
     * kube-controller-manager.
     *  3. "kubernetes.io/kubelet-serving" issues serving certificates that kubelets use to serve TLS
     * endpoints, which kube-apiserver can connect to securely.
     *   Requests for this signer are never auto-approved by kube-controller-manager, and can be issued by
     * the "csrsigning" controller in kube-controller-manager.
     *
     * More details are available at
     * https://k8s.io/docs/reference/access-authn-authz/certificate-signing-requests/#kubernetes-signers
     *
     * Custom signerNames can also be specified. The signer defines:
     *  1. Trust distribution: how trust (CA bundles) are distributed.
     *  2. Permitted subjects: and behavior when a disallowed subject is requested.
     *  3. Required, permitted, or forbidden x509 extensions in the request (including whether
     * subjectAltNames are allowed, which types, restrictions on allowed values) and behavior when a
     * disallowed extension is requested.
     *  4. Required, permitted, or forbidden key usages / extended key usages.
     *  5. Expiration/certificate lifetime: whether it is fixed by the signer, configurable by the admin.
     *  6. Whether or not requests for CA certificates are allowed.
     *
     * @return static
     */
    public function setSignerName(string $signerName)
    {
        $this->signerName = $signerName;

        return $this;
    }

    /**
     * uid contains the uid of the user that created the CertificateSigningRequest. Populated by the API
     * server on creation and immutable.
     */
    public function getUid(): ?string
    {
        return $this->uid;
    }

    /**
     * uid contains the uid of the user that created the CertificateSigningRequest. Populated by the API
     * server on creation and immutable.
     *
     * @return static
     */
    public function setUid(string $uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * usages specifies a set of key usages requested in the issued certificate.
     *
     * Requests for TLS client certificates typically request: "digital signature", "key encipherment",
     * "client auth".
     *
     * Requests for TLS serving certificates typically request: "key encipherment", "digital signature",
     * "server auth".
     *
     * Valid values are:
     *  "signing", "digital signature", "content commitment",
     *  "key encipherment", "key agreement", "data encipherment",
     *  "cert sign", "crl sign", "encipher only", "decipher only", "any",
     *  "server auth", "client auth",
     *  "code signing", "email protection", "s/mime",
     *  "ipsec end system", "ipsec tunnel", "ipsec user",
     *  "timestamping", "ocsp signing", "microsoft sgc", "netscape sgc"
     */
    public function getUsages(): ?array
    {
        return $this->usages;
    }

    /**
     * usages specifies a set of key usages requested in the issued certificate.
     *
     * Requests for TLS client certificates typically request: "digital signature", "key encipherment",
     * "client auth".
     *
     * Requests for TLS serving certificates typically request: "key encipherment", "digital signature",
     * "server auth".
     *
     * Valid values are:
     *  "signing", "digital signature", "content commitment",
     *  "key encipherment", "key agreement", "data encipherment",
     *  "cert sign", "crl sign", "encipher only", "decipher only", "any",
     *  "server auth", "client auth",
     *  "code signing", "email protection", "s/mime",
     *  "ipsec end system", "ipsec tunnel", "ipsec user",
     *  "timestamping", "ocsp signing", "microsoft sgc", "netscape sgc"
     *
     * @return static
     */
    public function setUsages(array $usages)
    {
        $this->usages = $usages;

        return $this;
    }

    /**
     * username contains the name of the user that created the CertificateSigningRequest. Populated by the
     * API server on creation and immutable.
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * username contains the name of the user that created the CertificateSigningRequest. Populated by the
     * API server on creation and immutable.
     *
     * @return static
     */
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }
}
