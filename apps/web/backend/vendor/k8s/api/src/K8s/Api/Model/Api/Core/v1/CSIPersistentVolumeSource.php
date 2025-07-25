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
 * Represents storage that is managed by an external CSI volume driver (Beta feature)
 */
class CSIPersistentVolumeSource
{
    /**
     * @Kubernetes\Attribute("controllerExpandSecretRef",type="model",model=SecretReference::class)
     * @var SecretReference|null
     */
    protected $controllerExpandSecretRef = null;

    /**
     * @Kubernetes\Attribute("controllerPublishSecretRef",type="model",model=SecretReference::class)
     * @var SecretReference|null
     */
    protected $controllerPublishSecretRef = null;

    /**
     * @Kubernetes\Attribute("driver",isRequired=true)
     * @var string
     */
    protected $driver;

    /**
     * @Kubernetes\Attribute("fsType")
     * @var string|null
     */
    protected $fsType = null;

    /**
     * @Kubernetes\Attribute("nodeExpandSecretRef",type="model",model=SecretReference::class)
     * @var SecretReference|null
     */
    protected $nodeExpandSecretRef = null;

    /**
     * @Kubernetes\Attribute("nodePublishSecretRef",type="model",model=SecretReference::class)
     * @var SecretReference|null
     */
    protected $nodePublishSecretRef = null;

    /**
     * @Kubernetes\Attribute("nodeStageSecretRef",type="model",model=SecretReference::class)
     * @var SecretReference|null
     */
    protected $nodeStageSecretRef = null;

    /**
     * @Kubernetes\Attribute("readOnly")
     * @var bool|null
     */
    protected $readOnly = null;

    /**
     * @Kubernetes\Attribute("volumeAttributes")
     * @var string[]|null
     */
    protected $volumeAttributes = null;

    /**
     * @Kubernetes\Attribute("volumeHandle",isRequired=true)
     * @var string
     */
    protected $volumeHandle;

    /**
     * @param string $driver
     * @param string $volumeHandle
     */
    public function __construct(string $driver, string $volumeHandle)
    {
        $this->driver = $driver;
        $this->volumeHandle = $volumeHandle;
    }

    /**
     * controllerExpandSecretRef is a reference to the secret object containing sensitive information to
     * pass to the CSI driver to complete the CSI ControllerExpandVolume call. This is an beta field and
     * requires enabling ExpandCSIVolumes feature gate. This field is optional, and may be empty if no
     * secret is required. If the secret object contains more than one secret, all secrets are passed.
     */
    public function getControllerExpandSecretRef(): ?SecretReference
    {
        return $this->controllerExpandSecretRef;
    }

    /**
     * controllerExpandSecretRef is a reference to the secret object containing sensitive information to
     * pass to the CSI driver to complete the CSI ControllerExpandVolume call. This is an beta field and
     * requires enabling ExpandCSIVolumes feature gate. This field is optional, and may be empty if no
     * secret is required. If the secret object contains more than one secret, all secrets are passed.
     *
     * @return static
     */
    public function setControllerExpandSecretRef(SecretReference $controllerExpandSecretRef)
    {
        $this->controllerExpandSecretRef = $controllerExpandSecretRef;

        return $this;
    }

    /**
     * controllerPublishSecretRef is a reference to the secret object containing sensitive information to
     * pass to the CSI driver to complete the CSI ControllerPublishVolume and ControllerUnpublishVolume
     * calls. This field is optional, and may be empty if no secret is required. If the secret object
     * contains more than one secret, all secrets are passed.
     */
    public function getControllerPublishSecretRef(): ?SecretReference
    {
        return $this->controllerPublishSecretRef;
    }

    /**
     * controllerPublishSecretRef is a reference to the secret object containing sensitive information to
     * pass to the CSI driver to complete the CSI ControllerPublishVolume and ControllerUnpublishVolume
     * calls. This field is optional, and may be empty if no secret is required. If the secret object
     * contains more than one secret, all secrets are passed.
     *
     * @return static
     */
    public function setControllerPublishSecretRef(SecretReference $controllerPublishSecretRef)
    {
        $this->controllerPublishSecretRef = $controllerPublishSecretRef;

        return $this;
    }

    /**
     * driver is the name of the driver to use for this volume. Required.
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * driver is the name of the driver to use for this volume. Required.
     *
     * @return static
     */
    public function setDriver(string $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * fsType to mount. Must be a filesystem type supported by the host operating system. Ex. "ext4",
     * "xfs", "ntfs".
     */
    public function getFsType(): ?string
    {
        return $this->fsType;
    }

    /**
     * fsType to mount. Must be a filesystem type supported by the host operating system. Ex. "ext4",
     * "xfs", "ntfs".
     *
     * @return static
     */
    public function setFsType(string $fsType)
    {
        $this->fsType = $fsType;

        return $this;
    }

    /**
     * nodeExpandSecretRef is a reference to the secret object containing sensitive information to pass to
     * the CSI driver to complete the CSI NodeExpandVolume call. This is an alpha field and requires
     * enabling CSINodeExpandSecret feature gate. This field is optional, may be omitted if no secret is
     * required. If the secret object contains more than one secret, all secrets are passed.
     */
    public function getNodeExpandSecretRef(): ?SecretReference
    {
        return $this->nodeExpandSecretRef;
    }

    /**
     * nodeExpandSecretRef is a reference to the secret object containing sensitive information to pass to
     * the CSI driver to complete the CSI NodeExpandVolume call. This is an alpha field and requires
     * enabling CSINodeExpandSecret feature gate. This field is optional, may be omitted if no secret is
     * required. If the secret object contains more than one secret, all secrets are passed.
     *
     * @return static
     */
    public function setNodeExpandSecretRef(SecretReference $nodeExpandSecretRef)
    {
        $this->nodeExpandSecretRef = $nodeExpandSecretRef;

        return $this;
    }

    /**
     * nodePublishSecretRef is a reference to the secret object containing sensitive information to pass to
     * the CSI driver to complete the CSI NodePublishVolume and NodeUnpublishVolume calls. This field is
     * optional, and may be empty if no secret is required. If the secret object contains more than one
     * secret, all secrets are passed.
     */
    public function getNodePublishSecretRef(): ?SecretReference
    {
        return $this->nodePublishSecretRef;
    }

    /**
     * nodePublishSecretRef is a reference to the secret object containing sensitive information to pass to
     * the CSI driver to complete the CSI NodePublishVolume and NodeUnpublishVolume calls. This field is
     * optional, and may be empty if no secret is required. If the secret object contains more than one
     * secret, all secrets are passed.
     *
     * @return static
     */
    public function setNodePublishSecretRef(SecretReference $nodePublishSecretRef)
    {
        $this->nodePublishSecretRef = $nodePublishSecretRef;

        return $this;
    }

    /**
     * nodeStageSecretRef is a reference to the secret object containing sensitive information to pass to
     * the CSI driver to complete the CSI NodeStageVolume and NodeStageVolume and NodeUnstageVolume calls.
     * This field is optional, and may be empty if no secret is required. If the secret object contains
     * more than one secret, all secrets are passed.
     */
    public function getNodeStageSecretRef(): ?SecretReference
    {
        return $this->nodeStageSecretRef;
    }

    /**
     * nodeStageSecretRef is a reference to the secret object containing sensitive information to pass to
     * the CSI driver to complete the CSI NodeStageVolume and NodeStageVolume and NodeUnstageVolume calls.
     * This field is optional, and may be empty if no secret is required. If the secret object contains
     * more than one secret, all secrets are passed.
     *
     * @return static
     */
    public function setNodeStageSecretRef(SecretReference $nodeStageSecretRef)
    {
        $this->nodeStageSecretRef = $nodeStageSecretRef;

        return $this;
    }

    /**
     * readOnly value to pass to ControllerPublishVolumeRequest. Defaults to false (read/write).
     */
    public function isReadOnly(): ?bool
    {
        return $this->readOnly;
    }

    /**
     * readOnly value to pass to ControllerPublishVolumeRequest. Defaults to false (read/write).
     *
     * @return static
     */
    public function setIsReadOnly(bool $readOnly)
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    /**
     * volumeAttributes of the volume to publish.
     */
    public function getVolumeAttributes(): ?array
    {
        return $this->volumeAttributes;
    }

    /**
     * volumeAttributes of the volume to publish.
     *
     * @return static
     */
    public function setVolumeAttributes(array $volumeAttributes)
    {
        $this->volumeAttributes = $volumeAttributes;

        return $this;
    }

    /**
     * volumeHandle is the unique volume name returned by the CSI volume plugin’s CreateVolume to refer
     * to the volume on all subsequent calls. Required.
     */
    public function getVolumeHandle(): string
    {
        return $this->volumeHandle;
    }

    /**
     * volumeHandle is the unique volume name returned by the CSI volume plugin’s CreateVolume to refer
     * to the volume on all subsequent calls. Required.
     *
     * @return static
     */
    public function setVolumeHandle(string $volumeHandle)
    {
        $this->volumeHandle = $volumeHandle;

        return $this;
    }
}
