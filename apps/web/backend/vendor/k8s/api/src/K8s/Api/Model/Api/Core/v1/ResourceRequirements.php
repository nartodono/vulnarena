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
 * ResourceRequirements describes the compute resource requirements.
 */
class ResourceRequirements
{
    /**
     * @Kubernetes\Attribute("limits")
     * @var object[]|null
     */
    protected $limits = null;

    /**
     * @Kubernetes\Attribute("requests")
     * @var object[]|null
     */
    protected $requests = null;

    /**
     * @param object[]|null $limits
     * @param object[]|null $requests
     */
    public function __construct(?array $limits = null, ?array $requests = null)
    {
        $this->limits = $limits;
        $this->requests = $requests;
    }

    /**
     * Limits describes the maximum amount of compute resources allowed. More info:
     * https://kubernetes.io/docs/concepts/configuration/manage-resources-containers/
     */
    public function getLimits(): ?array
    {
        return $this->limits;
    }

    /**
     * Limits describes the maximum amount of compute resources allowed. More info:
     * https://kubernetes.io/docs/concepts/configuration/manage-resources-containers/
     *
     * @return static
     */
    public function setLimits(array $limits)
    {
        $this->limits = $limits;

        return $this;
    }

    /**
     * Requests describes the minimum amount of compute resources required. If Requests is omitted for a
     * container, it defaults to Limits if that is explicitly specified, otherwise to an
     * implementation-defined value. More info:
     * https://kubernetes.io/docs/concepts/configuration/manage-resources-containers/
     */
    public function getRequests(): ?array
    {
        return $this->requests;
    }

    /**
     * Requests describes the minimum amount of compute resources required. If Requests is omitted for a
     * container, it defaults to Limits if that is explicitly specified, otherwise to an
     * implementation-defined value. More info:
     * https://kubernetes.io/docs/concepts/configuration/manage-resources-containers/
     *
     * @return static
     */
    public function setRequests(array $requests)
    {
        $this->requests = $requests;

        return $this;
    }
}
