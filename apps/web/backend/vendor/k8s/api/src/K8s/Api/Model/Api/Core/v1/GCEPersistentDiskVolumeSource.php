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
 * Represents a Persistent Disk resource in Google Compute Engine.
 *
 * A GCE PD must exist before mounting to a container. The disk must also be in the same GCE project
 * and zone as the kubelet. A GCE PD can only be mounted as read/write once or read-only many times.
 * GCE PDs support ownership management and SELinux relabeling.
 */
class GCEPersistentDiskVolumeSource
{
    /**
     * @Kubernetes\Attribute("fsType")
     * @var string|null
     */
    protected $fsType = null;

    /**
     * @Kubernetes\Attribute("partition")
     * @var int|null
     */
    protected $partition = null;

    /**
     * @Kubernetes\Attribute("pdName",isRequired=true)
     * @var string
     */
    protected $pdName;

    /**
     * @Kubernetes\Attribute("readOnly")
     * @var bool|null
     */
    protected $readOnly = null;

    /**
     * @param string $pdName
     */
    public function __construct(string $pdName)
    {
        $this->pdName = $pdName;
    }

    /**
     * fsType is filesystem type of the volume that you want to mount. Tip: Ensure that the filesystem type
     * is supported by the host operating system. Examples: "ext4", "xfs", "ntfs". Implicitly inferred to
     * be "ext4" if unspecified. More info:
     * https://kubernetes.io/docs/concepts/storage/volumes#gcepersistentdisk
     */
    public function getFsType(): ?string
    {
        return $this->fsType;
    }

    /**
     * fsType is filesystem type of the volume that you want to mount. Tip: Ensure that the filesystem type
     * is supported by the host operating system. Examples: "ext4", "xfs", "ntfs". Implicitly inferred to
     * be "ext4" if unspecified. More info:
     * https://kubernetes.io/docs/concepts/storage/volumes#gcepersistentdisk
     *
     * @return static
     */
    public function setFsType(string $fsType)
    {
        $this->fsType = $fsType;

        return $this;
    }

    /**
     * partition is the partition in the volume that you want to mount. If omitted, the default is to mount
     * by volume name. Examples: For volume /dev/sda1, you specify the partition as "1". Similarly, the
     * volume partition for /dev/sda is "0" (or you can leave the property empty). More info:
     * https://kubernetes.io/docs/concepts/storage/volumes#gcepersistentdisk
     */
    public function getPartition(): ?int
    {
        return $this->partition;
    }

    /**
     * partition is the partition in the volume that you want to mount. If omitted, the default is to mount
     * by volume name. Examples: For volume /dev/sda1, you specify the partition as "1". Similarly, the
     * volume partition for /dev/sda is "0" (or you can leave the property empty). More info:
     * https://kubernetes.io/docs/concepts/storage/volumes#gcepersistentdisk
     *
     * @return static
     */
    public function setPartition(int $partition)
    {
        $this->partition = $partition;

        return $this;
    }

    /**
     * pdName is unique name of the PD resource in GCE. Used to identify the disk in GCE. More info:
     * https://kubernetes.io/docs/concepts/storage/volumes#gcepersistentdisk
     */
    public function getPdName(): string
    {
        return $this->pdName;
    }

    /**
     * pdName is unique name of the PD resource in GCE. Used to identify the disk in GCE. More info:
     * https://kubernetes.io/docs/concepts/storage/volumes#gcepersistentdisk
     *
     * @return static
     */
    public function setPdName(string $pdName)
    {
        $this->pdName = $pdName;

        return $this;
    }

    /**
     * readOnly here will force the ReadOnly setting in VolumeMounts. Defaults to false. More info:
     * https://kubernetes.io/docs/concepts/storage/volumes#gcepersistentdisk
     */
    public function isReadOnly(): ?bool
    {
        return $this->readOnly;
    }

    /**
     * readOnly here will force the ReadOnly setting in VolumeMounts. Defaults to false. More info:
     * https://kubernetes.io/docs/concepts/storage/volumes#gcepersistentdisk
     *
     * @return static
     */
    public function setIsReadOnly(bool $readOnly)
    {
        $this->readOnly = $readOnly;

        return $this;
    }
}
