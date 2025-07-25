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
 * Represents a Photon Controller persistent disk resource.
 */
class PhotonPersistentDiskVolumeSource
{
    /**
     * @Kubernetes\Attribute("fsType")
     * @var string|null
     */
    protected $fsType = null;

    /**
     * @Kubernetes\Attribute("pdID",isRequired=true)
     * @var string
     */
    protected $pdID;

    /**
     * @param string $pdID
     */
    public function __construct(string $pdID)
    {
        $this->pdID = $pdID;
    }

    /**
     * fsType is the filesystem type to mount. Must be a filesystem type supported by the host operating
     * system. Ex. "ext4", "xfs", "ntfs". Implicitly inferred to be "ext4" if unspecified.
     */
    public function getFsType(): ?string
    {
        return $this->fsType;
    }

    /**
     * fsType is the filesystem type to mount. Must be a filesystem type supported by the host operating
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
     * pdID is the ID that identifies Photon Controller persistent disk
     */
    public function getPdID(): string
    {
        return $this->pdID;
    }

    /**
     * pdID is the ID that identifies Photon Controller persistent disk
     *
     * @return static
     */
    public function setPdID(string $pdID)
    {
        $this->pdID = $pdID;

        return $this;
    }
}
