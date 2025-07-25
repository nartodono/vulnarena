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

namespace K8s\Api\Model\Api\AutoScaling\v2beta2;

use K8s\Core\Annotation as Kubernetes;

/**
 * ResourceMetricStatus indicates the current value of a resource metric known to Kubernetes, as
 * specified in requests and limits, describing each pod in the current scale target (e.g. CPU or
 * memory).  Such metrics are built in to Kubernetes, and have special scaling options on top of those
 * available to normal per-pod metrics using the "pods" source.
 */
class ResourceMetricStatus
{
    /**
     * @Kubernetes\Attribute("current",type="model",model=MetricValueStatus::class,isRequired=true)
     * @var MetricValueStatus
     */
    protected $current;

    /**
     * @Kubernetes\Attribute("name",isRequired=true)
     * @var string
     */
    protected $name;

    /**
     * @param MetricValueStatus $current
     * @param string $name
     */
    public function __construct(MetricValueStatus $current, string $name)
    {
        $this->current = $current;
        $this->name = $name;
    }

    /**
     * current contains the current value for the given metric
     */
    public function getCurrent(): MetricValueStatus
    {
        return $this->current;
    }

    /**
     * current contains the current value for the given metric
     *
     * @return static
     */
    public function setCurrent(MetricValueStatus $current)
    {
        $this->current = $current;

        return $this;
    }

    /**
     * Name is the name of the resource in question.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Name is the name of the resource in question.
     *
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}
