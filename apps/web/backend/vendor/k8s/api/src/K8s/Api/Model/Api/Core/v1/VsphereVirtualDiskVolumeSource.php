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
 * Represents a vSphere volume resource.
 */
class VsphereVirtualDiskVolumeSource
{
    /**
     * @Kubernetes\Attribute("fsType")
     * @var string|null
     */
    protected $fsType = null;

    /**
     * @Kubernetes\Attribute("storagePolicyID")
     * @var string|null
     */
    protected $storagePolicyID = null;

    /**
     * @Kubernetes\Attribute("storagePolicyName")
     * @var string|null
     */
    protected $storagePolicyName = null;

    /**
     * @Kubernetes\Attribute("volumePath",isRequired=true)
     * @var string
     */
    protected $volumePath;

    /**
     * @param string $volumePath
     */
    public function __construct(string $volumePath)
    {
        $this->volumePath = $volumePath;
    }

    /**
     * fsType is filesystem type to mount. Must be a filesystem type supported by the host operating
     * system. Ex. "ext4", "xfs", "ntfs". Implicitly inferred to be "ext4" if unspecified.
     */
    public function getFsType(): ?string
    {
        return $this->fsType;
    }

    /**
     * fsType is filesystem type to mount. Must be a filesystem type supported by the host operating
     * system. Ex. "ext4", "xfs", "ntfs". Implicitly inferred to be "ext4" if unspecified.
     *
     * @return static
     */
    public function setFsType(string $fsType)
    {
        $this->fsType = $fsType;

        return $this;
    }

    /**
     * storagePolicyID is the storage Policy Based Management (SPBM) profile ID associated with the
     * StoragePolicyName.
     */
    public function getStoragePolicyID(): ?string
    {
        return $this->storagePolicyID;
    }

    /**
     * storagePolicyID is the storage Policy Based Management (SPBM) profile ID associated with the
     * StoragePolicyName.
     *
     * @return static
     */
    public function setStoragePolicyID(string $storagePolicyID)
    {
        $this->storagePolicyID = $storagePolicyID;

        return $this;
    }

    /**
     * storagePolicyName is the storage Policy Based Management (SPBM) profile name.
     */
    public function getStoragePolicyName(): ?string
    {
        return $this->storagePolicyName;
    }

    /**
     * storagePolicyName is the storage Policy Based Management (SPBM) profile name.
     *
     * @return static
     */
    public function setStoragePolicyName(string $storagePolicyName)
    {
        $this->storagePolicyName = $storagePolicyName;

        return $this;
    }

    /**
     * volumePath is the path that identifies vSphere volume vmdk
     */
    public function getVolumePath(): string
    {
        return $this->volumePath;
    }

    /**
     * volumePath is the path that identifies vSphere volume vmdk
     *
     * @return static
     */
    public function setVolumePath(string $volumePath)
    {
        $this->volumePath = $volumePath;

        return $this;
    }
}
