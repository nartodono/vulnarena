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
 * ContainerStatus contains details for the current status of this container.
 */
class ContainerStatus
{
    /**
     * @Kubernetes\Attribute("containerID")
     * @var string|null
     */
    protected $containerID = null;

    /**
     * @Kubernetes\Attribute("image",isRequired=true)
     * @var string
     */
    protected $image;

    /**
     * @Kubernetes\Attribute("imageID",isRequired=true)
     * @var string
     */
    protected $imageID;

    /**
     * @Kubernetes\Attribute("lastState",type="model",model=ContainerState::class)
     * @var ContainerState|null
     */
    protected $lastState = null;

    /**
     * @Kubernetes\Attribute("name",isRequired=true)
     * @var string
     */
    protected $name;

    /**
     * @Kubernetes\Attribute("ready",isRequired=true)
     * @var bool
     */
    protected $ready;

    /**
     * @Kubernetes\Attribute("restartCount",isRequired=true)
     * @var int
     */
    protected $restartCount;

    /**
     * @Kubernetes\Attribute("started")
     * @var bool|null
     */
    protected $started = null;

    /**
     * @Kubernetes\Attribute("state",type="model",model=ContainerState::class)
     * @var ContainerState|null
     */
    protected $state = null;

    /**
     * @param string $image
     * @param string $imageID
     * @param string $name
     * @param bool $ready
     * @param int $restartCount
     */
    public function __construct(string $image, string $imageID, string $name, bool $ready, int $restartCount)
    {
        $this->image = $image;
        $this->imageID = $imageID;
        $this->name = $name;
        $this->ready = $ready;
        $this->restartCount = $restartCount;
    }

    /**
     * Container's ID in the format '<type>://<container_id>'.
     */
    public function getContainerID(): ?string
    {
        return $this->containerID;
    }

    /**
     * Container's ID in the format '<type>://<container_id>'.
     *
     * @return static
     */
    public function setContainerID(string $containerID)
    {
        $this->containerID = $containerID;

        return $this;
    }

    /**
     * The image the container is running. More info:
     * https://kubernetes.io/docs/concepts/containers/images.
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * The image the container is running. More info:
     * https://kubernetes.io/docs/concepts/containers/images.
     *
     * @return static
     */
    public function setImage(string $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * ImageID of the container's image.
     */
    public function getImageID(): string
    {
        return $this->imageID;
    }

    /**
     * ImageID of the container's image.
     *
     * @return static
     */
    public function setImageID(string $imageID)
    {
        $this->imageID = $imageID;

        return $this;
    }

    /**
     * Details about the container's last termination condition.
     */
    public function getLastState(): ?ContainerState
    {
        return $this->lastState;
    }

    /**
     * Details about the container's last termination condition.
     *
     * @return static
     */
    public function setLastState(ContainerState $lastState)
    {
        $this->lastState = $lastState;

        return $this;
    }

    /**
     * This must be a DNS_LABEL. Each container in a pod must have a unique name. Cannot be updated.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * This must be a DNS_LABEL. Each container in a pod must have a unique name. Cannot be updated.
     *
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Specifies whether the container has passed its readiness probe.
     */
    public function isReady(): bool
    {
        return $this->ready;
    }

    /**
     * Specifies whether the container has passed its readiness probe.
     *
     * @return static
     */
    public function setIsReady(bool $ready)
    {
        $this->ready = $ready;

        return $this;
    }

    /**
     * The number of times the container has been restarted.
     */
    public function getRestartCount(): int
    {
        return $this->restartCount;
    }

    /**
     * The number of times the container has been restarted.
     *
     * @return static
     */
    public function setRestartCount(int $restartCount)
    {
        $this->restartCount = $restartCount;

        return $this;
    }

    /**
     * Specifies whether the container has passed its startup probe. Initialized as false, becomes true
     * after startupProbe is considered successful. Resets to false when the container is restarted, or if
     * kubelet loses state temporarily. Is always true when no startupProbe is defined.
     */
    public function isStarted(): ?bool
    {
        return $this->started;
    }

    /**
     * Specifies whether the container has passed its startup probe. Initialized as false, becomes true
     * after startupProbe is considered successful. Resets to false when the container is restarted, or if
     * kubelet loses state temporarily. Is always true when no startupProbe is defined.
     *
     * @return static
     */
    public function setIsStarted(bool $started)
    {
        $this->started = $started;

        return $this;
    }

    /**
     * Details about the container's current condition.
     */
    public function getState(): ?ContainerState
    {
        return $this->state;
    }

    /**
     * Details about the container's current condition.
     *
     * @return static
     */
    public function setState(ContainerState $state)
    {
        $this->state = $state;

        return $this;
    }
}
