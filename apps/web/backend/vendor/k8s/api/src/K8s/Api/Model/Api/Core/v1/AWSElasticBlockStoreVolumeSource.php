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
 * Represents a Persistent Disk resource in AWS.
 *
 * An AWS EBS disk must exist before mounting to a container. The disk must also be in the same AWS
 * zone as the kubelet. An AWS EBS disk can only be mounted as read/write once. AWS EBS volumes support
 * ownership management and SELinux relabeling.
 */
class AWSElasticBlockStoreVolumeSource
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
     * @Kubernetes\Attribute("readOnly")
     * @var bool|null
     */
    protected $readOnly = null;

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
     * fsType is the filesystem type of the volume that you want to mount. Tip: Ensure that the filesystem
     * type is supported by the host operating system. Examples: "ext4", "xfs", "ntfs". Implicitly inferred
     * to be "ext4" if unspecified. More info:
     * https://kubernetes.io/docs/concepts/storage/volumes#awselasticblockstore
     */
    public function getFsType(): ?string
    {
        return $this->fsType;
    }

    /**
     * fsType is the filesystem type of the volume that you want to mount. Tip: Ensure that the filesystem
     * type is supported by the host operating system. Examples: "ext4", "xfs", "ntfs". Implicitly inferred
     * to be "ext4" if unspecified. More info:
     * https://kubernetes.io/docs/concepts/storage/volumes#awselasticblockstore
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
     * volume partition for /dev/sda is "0" (or you can leave the property empty).
     */
    public function getPartition(): ?int
    {
        return $this->partition;
    }

    /**
     * partition is the partition in the volume that you want to mount. If omitted, the default is to mount
     * by volume name. Examples: For volume /dev/sda1, you specify the partition as "1". Similarly, the
     * volume partition for /dev/sda is "0" (or you can leave the property empty).
     *
     * @return static
     */
    public function setPartition(int $partition)
    {
        $this->partition = $partition;

        return $this;
    }

    /**
     * readOnly value true will force the readOnly setting in VolumeMounts. More info:
     * https://kubernetes.io/docs/concepts/storage/volumes#awselasticblockstore
     */
    public function isReadOnly(): ?bool
    {
        return $this->readOnly;
    }

    /**
     * readOnly value true will force the readOnly setting in VolumeMounts. More info:
     * https://kubernetes.io/docs/concepts/storage/volumes#awselasticblockstore
     *
     * @return static
     */
    public function setIsReadOnly(bool $readOnly)
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    /**
     * volumeID is unique ID of the persistent disk resource in AWS (Amazon EBS volume). More info:
     * https://kubernetes.io/docs/concepts/storage/volumes#awselasticblockstore
     */
    public function getVolumeID(): string
    {
        return $this->volumeID;
    }

    /**
     * volumeID is unique ID of the persistent disk resource in AWS (Amazon EBS volume). More info:
     * https://kubernetes.io/docs/concepts/storage/volumes#awselasticblockstore
     *
     * @return static
     */
    public function setVolumeID(string $volumeID)
    {
        $this->volumeID = $volumeID;

        return $this;
    }
}
