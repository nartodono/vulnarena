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
 * Represents a cinder volume resource in Openstack. A Cinder volume must exist before mounting to a
 * container. The volume must also be in the same region as the kubelet. Cinder volumes support
 * ownership management and SELinux relabeling.
 */
class CinderPersistentVolumeSource
{
    /**
     * @Kubernetes\Attribute("fsType")
     * @var string|null
     */
    protected $fsType = null;

    /**
     * @Kubernetes\Attribute("readOnly")
     * @var bool|null
     */
    protected $readOnly = null;

    /**
     * @Kubernetes\Attribute("secretRef",type="model",model=SecretReference::class)
     * @var SecretReference|null
     */
    protected $secretRef = null;

    /**
     * @Kubernetes\Attribute("volumeID",isRequired=true)
     * @var string
     */
    protected $volumeID;

    /**
     * @param string $volumeID
     */
    public function __construct(string $volumeID)
    {
        $this->volumeID = $volumeID;
    }

    /**
     * fsType Filesystem type to mount. Must be a filesystem type supported by the host operating system.
     * Examples: "ext4", "xfs", "ntfs". Implicitly inferred to be "ext4" if unspecified. More info:
     * https://examples.k8s.io/mysql-cinder-pd/README.md
     */
    public function getFsType(): ?string
    {
        return $this->fsType;
    }

    /**
     * fsType Filesystem type to mount. Must be a filesystem type supported by the host operating system.
     * Examples: "ext4", "xfs", "ntfs". Implicitly inferred to be "ext4" if unspecified. More info:
     * https://examples.k8s.io/mysql-cinder-pd/README.md
     *
     * @return static
     */
    public function setFsType(string $fsType)
    {
        $this->fsType = $fsType;

        return $this;
    }

    /**
     * readOnly is Optional: Defaults to false (read/write). ReadOnly here will force the ReadOnly setting
     * in VolumeMounts. More info: https://examples.k8s.io/mysql-cinder-pd/README.md
     */
    public function isReadOnly(): ?bool
    {
        return $this->readOnly;
    }

    /**
     * readOnly is Optional: Defaults to false (read/write). ReadOnly here will force the ReadOnly setting
     * in VolumeMounts. More info: https://examples.k8s.io/mysql-cinder-pd/README.md
     *
     * @return static
     */
    public function setIsReadOnly(bool $readOnly)
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    /**
     * secretRef is Optional: points to a secret object containing parameters used to connect to OpenStack.
     */
    public function getSecretRef(): ?SecretReference
    {
        return $this->secretRef;
    }

    /**
     * secretRef is Optional: points to a secret object containing parameters used to connect to OpenStack.
     *
     * @return static
     */
    public function setSecretRef(SecretReference $secretRef)
    {
        $this->secretRef = $secretRef;

        return $this;
    }

    /**
     * volumeID used to identify the volume in cinder. More info:
     * https://examples.k8s.io/mysql-cinder-pd/README.md
     */
    public function getVolumeID(): string
    {
        return $this->volumeID;
    }

    /**
     * volumeID used to identify the volume in cinder. More info:
     * https://examples.k8s.io/mysql-cinder-pd/README.md
     *
     * @return static
     */
    public function setVolumeID(string $volumeID)
    {
        $this->volumeID = $volumeID;

        return $this;
    }
}
