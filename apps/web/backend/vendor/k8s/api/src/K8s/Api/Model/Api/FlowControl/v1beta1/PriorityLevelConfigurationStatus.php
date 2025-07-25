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

namespace K8s\Api\Model\Api\FlowControl\v1beta1;

use K8s\Core\Annotation as Kubernetes;
use K8s\Core\Collection;

/**
 * PriorityLevelConfigurationStatus represents the current state of a "request-priority".
 */
class PriorityLevelConfigurationStatus
{
    /**
     * @Kubernetes\Attribute("conditions",type="collection",model=PriorityLevelConfigurationCondition::class)
     * @var iterable|PriorityLevelConfigurationCondition[]|null
     */
    protected $conditions = null;

    /**
     * @param iterable|PriorityLevelConfigurationCondition[] $conditions
     */
    public function __construct(iterable $conditions = [])
    {
        $this->conditions = new Collection($conditions);
    }

    /**
     * `conditions` is the current state of "request-priority".
     *
     * @return iterable|PriorityLevelConfigurationCondition[]
     */
    public function getConditions(): ?iterable
    {
        return $this->conditions;
    }

    /**
     * `conditions` is the current state of "request-priority".
     *
     * @return static
     */
    public function setConditions(iterable $conditions)
    {
        $this->conditions = $conditions;

        return $this;
    }

    /**
     * @return static
     */
    public function addConditions(PriorityLevelConfigurationCondition $condition)
    {
        if (!$this->conditions) {
            $this->conditions = new Collection();
        }
        $this->conditions[] = $condition;

        return $this;
    }
}
