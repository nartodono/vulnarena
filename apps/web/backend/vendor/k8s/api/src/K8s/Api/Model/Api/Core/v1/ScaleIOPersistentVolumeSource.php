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

namespace K8s\Api\Model\Api\Core\v1;

use K8s\Core\Annotation as Kubernetes;

/**
 * ScaleIOPersistentVolumeSource represents a persistent ScaleIO volume
 */
class ScaleIOPersistentVolumeSource
{
    /**
     * @Kubernetes\Attribute("fsType")
     * @var string|null
     */
    protected $fsType = null;

    /**
     * @Kubernetes\Attribute("gateway",isRequired=true)
     * @var string
     */
    protected $gateway;

    /**
     * @Kubernetes\Attribute("protectionDomain")
     * @var string|null
     */
    protected $protectionDomain = null;

    /**
     * @Kubernetes\Attribute("readOnly")
     * @var bool|null
     */
    protected $readOnly = null;

    /**
     * @Kubernetes\Attribute("secretRef",type="model",model=SecretReference::class,isRequired=true)
     * @var SecretReference
     */
    protected $secretRef;

    /**
     * @Kubernetes\Attribute("sslEnabled")
     * @var bool|null
     */
    protected $sslEnabled = null;

    /**
     * @Kubernetes\Attribute("storageMode")
     * @var string|null
     */
    protected $storageMode = null;

    /**
     * @Kubernetes\Attribute("storagePool")
     * @var string|null
     */
    protected $storagePool = null;

    /**
     * @Kubernetes\Attribute("system",isRequired=true)
     * @var string
     */
    protected $system;

    /**
     * @Kubernetes\Attribute("volumeName")
     * @var string|null
     */
    protected $volumeName = null;

    /**
     * @param string $gateway
     * @param SecretReference $secretRef
     * @param string $system
     */
    public function __construct(string $gateway, SecretReference $secretRef, string $system)
    {
        $this->gateway = $gateway;
        $this->secretRef = $secretRef;
        $this->system = $system;
    }

    /**
     * fsType is the filesystem type to mount. Must be a filesystem type supported by the host operating
     * system. Ex. "ext4", "xfs", "ntfs". Default is "xfs"
     */
    public function getFsType(): ?string
    {
        return $this->fsType;
    }

    /**
     * fsType is the filesystem type to mount. Must be a filesystem type supported by the host operating
     * system. Ex. "ext4", "xfs", "ntfs". Default is "xfs"
     *
     * @return static
     */
    public function setFsType(string $fsType)
    {
        $this->fsType = $fsType;

        return $this;
    }

    /**
     * gateway is the host address of the ScaleIO API Gateway.
     */
    public function getGateway(): string
    {
        return $this->gateway;
    }

    /**
     * gateway is the host address of the ScaleIO API Gateway.
     *
     * @return static
     */
    public function setGateway(string $gateway)
    {
        $this->gateway = $gateway;

        return $this;
    }

    /**
     * protectionDomain is the name of the ScaleIO Protection Domain for the configured storage.
     */
    public function getProtectionDomain(): ?string
    {
        return $this->protectionDomain;
    }

    /**
     * protectionDomain is the name of the ScaleIO Protection Domain for the configured storage.
     *
     * @return static
     */
    public function setProtectionDomain(string $protectionDomain)
    {
        $this->protectionDomain = $protectionDomain;

        return $this;
    }

    /**
     * readOnly defaults to false (read/write). ReadOnly here will force the ReadOnly setting in
     * VolumeMounts.
     */
    public function isReadOnly(): ?bool
    {
        return $this->readOnly;
    }

    /**
     * readOnly defaults to false (read/write). ReadOnly here will force the ReadOnly setting in
     * VolumeMounts.
     *
     * @return static
     */
    public function setIsReadOnly(bool $readOnly)
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    /**
     * secretRef references to the secret for ScaleIO user and other sensitive information. If this is not
     * provided, Login operation will fail.
     */
    public function getSecretRef(): SecretReference
    {
        return $this->secretRef;
    }

    /**
     * secretRef references to the secret for ScaleIO user and other sensitive information. If this is not
     * provided, Login operation will fail.
     *
     * @return static
     */
    public function setSecretRef(SecretReference $secretRef)
    {
        $this->secretRef = $secretRef;

        return $this;
    }

    /**
     * sslEnabled is the flag to enable/disable SSL communication with Gateway, default false
     */
    public function isSslEnabled(): ?bool
    {
        return $this->sslEnabled;
    }

    /**
     * sslEnabled is the flag to enable/disable SSL communication with Gateway, default false
     *
     * @return static
     */
    public function setIsSslEnabled(bool $sslEnabled)
    {
        $this->sslEnabled = $sslEnabled;

        return $this;
    }

    /**
     * storageMode indicates whether the storage for a volume should be ThickProvisioned or
     * ThinProvisioned. Default is ThinProvisioned.
     */
    public function getStorageMode(): ?string
    {
        return $this->storageMode;
    }

    /**
     * storageMode indicates whether the storage for a volume should be ThickProvisioned or
     * ThinProvisioned. Default is ThinProvisioned.
     *
     * @return static
     */
    public function setStorageMode(string $storageMode)
    {
        $this->storageMode = $storageMode;

        return $this;
    }

    /**
     * storagePool is the ScaleIO Storage Pool associated with the protection domain.
     */
    public function getStoragePool(): ?string
    {
        return $this->storagePool;
    }

    /**
     * storagePool is the ScaleIO Storage Pool associated with the protection domain.
     *
     * @return static
     */
    public function setStoragePool(string $storagePool)
    {
        $this->storagePool = $storagePool;

        return $this;
    }

    /**
     * system is the name of the storage system as configured in ScaleIO.
     */
    public function getSystem(): string
    {
        return $this->system;
    }

    /**
     * system is the name of the storage system as configured in ScaleIO.
     *
     * @return static
     */
    public function setSystem(string $system)
    {
        $this->system = $system;

        return $this;
    }

    /**
     * volumeName is the name of a volume already created in the ScaleIO system that is associated with
     * this volume source.
     */
    public function getVolumeName(): ?string
    {
        return $this->volumeName;
    }

    /**
     * volumeName is the name of a volume already created in the ScaleIO system that is associated with
     * this volume source.
     *
     * @return static
     */
    public function setVolumeName(string $volumeName)
    {
        $this->volumeName = $volumeName;

        return $this;
    }
}
