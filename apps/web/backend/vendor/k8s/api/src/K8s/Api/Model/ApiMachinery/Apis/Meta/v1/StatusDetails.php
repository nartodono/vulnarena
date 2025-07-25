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

namespace K8s\Api\Model\ApiMachinery\Apis\Meta\v1;

use K8s\Core\Annotation as Kubernetes;
use K8s\Core\Collection;

/**
 * StatusDetails is a set of additional properties that MAY be set by the server to provide additional
 * information about a response. The Reason field of a Status object defines what attributes will be
 * set. Clients must ignore fields that do not match the defined type of each attribute, and should
 * assume that any attribute may be empty, invalid, or under defined.
 */
class StatusDetails
{
    /**
     * @Kubernetes\Attribute("causes",type="collection",model=StatusCause::class)
     * @var iterable|StatusCause[]|null
     */
    protected $causes = null;

    /**
     * @Kubernetes\Attribute("group")
     * @var string|null
     */
    protected $group = null;

    /**
     * @Kubernetes\Attribute("kind")
     * @var string|null
     */
    protected $kind = null;

    /**
     * @Kubernetes\Attribute("name")
     * @var string|null
     */
    protected $name = null;

    /**
     * @Kubernetes\Attribute("retryAfterSeconds")
     * @var int|null
     */
    protected $retryAfterSeconds = null;

    /**
     * @Kubernetes\Attribute("uid")
     * @var string|null
     */
    protected $uid = null;

    /**
     * The Causes array includes more details associated with the StatusReason failure. Not all
     * StatusReasons may provide detailed causes.
     *
     * @return iterable|StatusCause[]
     */
    public function getCauses(): ?iterable
    {
        return $this->causes;
    }

    /**
     * The Causes array includes more details associated with the StatusReason failure. Not all
     * StatusReasons may provide detailed causes.
     *
     * @return static
     */
    public function setCauses(iterable $causes)
    {
        $this->causes = $causes;

        return $this;
    }

    /**
     * @return static
     */
    public function addCauses(StatusCause $cause)
    {
        if (!$this->causes) {
            $this->causes = new Collection();
        }
        $this->causes[] = $cause;

        return $this;
    }

    /**
     * The group attribute of the resource associated with the status StatusReason.
     */
    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * The group attribute of the resource associated with the status StatusReason.
     *
     * @return static
     */
    public function setGroup(string $group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * The kind attribute of the resource associated with the status StatusReason. On some operations may
     * differ from the requested resource Kind. More info:
     * https://git.k8s.io/community/contributors/devel/sig-architecture/api-conventions.md#types-kinds
     */
    public function getKind(): ?string
    {
        return $this->kind;
    }

    /**
     * The kind attribute of the resource associated with the status StatusReason. On some operations may
     * differ from the requested resource Kind. More info:
     * https://git.k8s.io/community/contributors/devel/sig-architecture/api-conventions.md#types-kinds
     *
     * @return static
     */
    public function setKind(string $kind)
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * The name attribute of the resource associated with the status StatusReason (when there is a single
     * name which can be described).
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * The name attribute of the resource associated with the status StatusReason (when there is a single
     * name which can be described).
     *
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * If specified, the time in seconds before the operation should be retried. Some errors may indicate
     * the client must take an alternate action - for those errors this field may indicate how long to wait
     * before taking the alternate action.
     */
    public function getRetryAfterSeconds(): ?int
    {
        return $this->retryAfterSeconds;
    }

    /**
     * If specified, the time in seconds before the operation should be retried. Some errors may indicate
     * the client must take an alternate action - for those errors this field may indicate how long to wait
     * before taking the alternate action.
     *
     * @return static
     */
    public function setRetryAfterSeconds(int $retryAfterSeconds)
    {
        $this->retryAfterSeconds = $retryAfterSeconds;

        return $this;
    }

    /**
     * UID of the resource. (when there is a single resource which can be described). More info:
     * http://kubernetes.io/docs/user-guide/identifiers#uids
     */
    public function getUid(): ?string
    {
        return $this->uid;
    }

    /**
     * UID of the resource. (when there is a single resource which can be described). More info:
     * http://kubernetes.io/docs/user-guide/identifiers#uids
     *
     * @return static
     */
    public function setUid(string $uid)
    {
        $this->uid = $uid;

        return $this;
    }
}
