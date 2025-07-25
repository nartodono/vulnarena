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

use DateTimeInterface;
use K8s\Core\Annotation as Kubernetes;

/**
 * ContainerStateTerminated is a terminated state of a container.
 */
class ContainerStateTerminated
{
    /**
     * @Kubernetes\Attribute("containerID")
     * @var string|null
     */
    protected $containerID = null;

    /**
     * @Kubernetes\Attribute("exitCode",isRequired=true)
     * @var int
     */
    protected $exitCode;

    /**
     * @Kubernetes\Attribute("finishedAt",type="datetime")
     * @var DateTimeInterface|null
     */
    protected $finishedAt = null;

    /**
     * @Kubernetes\Attribute("message")
     * @var string|null
     */
    protected $message = null;

    /**
     * @Kubernetes\Attribute("reason")
     * @var string|null
     */
    protected $reason = null;

    /**
     * @Kubernetes\Attribute("signal")
     * @var int|null
     */
    protected $signal = null;

    /**
     * @Kubernetes\Attribute("startedAt",type="datetime")
     * @var DateTimeInterface|null
     */
    protected $startedAt = null;

    /**
     * @param int $exitCode
     */
    public function __construct(int $exitCode)
    {
        $this->exitCode = $exitCode;
    }

    /**
     * Container's ID in the format '<type>://<container_id>'
     */
    public function getContainerID(): ?string
    {
        return $this->containerID;
    }

    /**
     * Container's ID in the format '<type>://<container_id>'
     *
     * @return static
     */
    public function setContainerID(string $containerID)
    {
        $this->containerID = $containerID;

        return $this;
    }

    /**
     * Exit status from the last termination of the container
     */
    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    /**
     * Exit status from the last termination of the container
     *
     * @return static
     */
    public function setExitCode(int $exitCode)
    {
        $this->exitCode = $exitCode;

        return $this;
    }

    /**
     * Time at which the container last terminated
     */
    public function getFinishedAt(): ?DateTimeInterface
    {
        return $this->finishedAt;
    }

    /**
     * Time at which the container last terminated
     *
     * @return static
     */
    public function setFinishedAt(DateTimeInterface $finishedAt)
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * Message regarding the last termination of the container
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Message regarding the last termination of the container
     *
     * @return static
     */
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * (brief) reason from the last termination of the container
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * (brief) reason from the last termination of the container
     *
     * @return static
     */
    public function setReason(string $reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Signal from the last termination of the container
     */
    public function getSignal(): ?int
    {
        return $this->signal;
    }

    /**
     * Signal from the last termination of the container
     *
     * @return static
     */
    public function setSignal(int $signal)
    {
        $this->signal = $signal;

        return $this;
    }

    /**
     * Time at which previous execution of the container started
     */
    public function getStartedAt(): ?DateTimeInterface
    {
        return $this->startedAt;
    }

    /**
     * Time at which previous execution of the container started
     *
     * @return static
     */
    public function setStartedAt(DateTimeInterface $startedAt)
    {
        $this->startedAt = $startedAt;

        return $this;
    }
}
