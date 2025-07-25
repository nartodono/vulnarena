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
 * ObjectMetricSource indicates how to scale on a metric describing a kubernetes object (for example,
 * hits-per-second on an Ingress object).
 */
class ObjectMetricSource
{
    /**
     * @Kubernetes\Attribute("describedObject",type="model",model=CrossVersionObjectReference::class,isRequired=true)
     * @var CrossVersionObjectReference
     */
    protected $describedObject;

    /**
     * @Kubernetes\Attribute("metric",type="model",model=MetricIdentifier::class,isRequired=true)
     * @var MetricIdentifier
     */
    protected $metric;

    /**
     * @Kubernetes\Attribute("target",type="model",model=MetricTarget::class,isRequired=true)
     * @var MetricTarget
     */
    protected $target;

    /**
     * @param CrossVersionObjectReference $describedObject
     * @param MetricIdentifier $metric
     * @param MetricTarget $target
     */
    public function __construct(CrossVersionObjectReference $describedObject, MetricIdentifier $metric, MetricTarget $target)
    {
        $this->describedObject = $describedObject;
        $this->metric = $metric;
        $this->target = $target;
    }

    public function getDescribedObject(): CrossVersionObjectReference
    {
        return $this->describedObject;
    }

    /**
     * @return static
     */
    public function setDescribedObject(CrossVersionObjectReference $describedObject)
    {
        $this->describedObject = $describedObject;

        return $this;
    }

    /**
     * metric identifies the target metric by name and selector
     */
    public function getMetric(): MetricIdentifier
    {
        return $this->metric;
    }

    /**
     * metric identifies the target metric by name and selector
     *
     * @return static
     */
    public function setMetric(MetricIdentifier $metric)
    {
        $this->metric = $metric;

        return $this;
    }

    /**
     * target specifies the target value for the given metric
     */
    public function getTarget(): MetricTarget
    {
        return $this->target;
    }

    /**
     * target specifies the target value for the given metric
     *
     * @return static
     */
    public function setTarget(MetricTarget $target)
    {
        $this->target = $target;

        return $this;
    }
}
