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

namespace K8s\Api\Model\ApiExtensions\v1;

use K8s\Core\Annotation as Kubernetes;
use K8s\Core\Collection;

/**
 * CustomResourceDefinitionStatus indicates the state of the CustomResourceDefinition
 */
class CustomResourceDefinitionStatus
{
    /**
     * @Kubernetes\Attribute("acceptedNames",type="model",model=CustomResourceDefinitionNames::class)
     * @var CustomResourceDefinitionNames|null
     */
    protected $acceptedNames = null;

    /**
     * @Kubernetes\Attribute("conditions",type="collection",model=CustomResourceDefinitionCondition::class)
     * @var iterable|CustomResourceDefinitionCondition[]|null
     */
    protected $conditions = null;

    /**
     * @Kubernetes\Attribute("storedVersions")
     * @var string[]|null
     */
    protected $storedVersions = null;

    /**
     * @param CustomResourceDefinitionNames|null $acceptedNames
     * @param iterable|CustomResourceDefinitionCondition[] $conditions
     * @param string[]|null $storedVersions
     */
    public function __construct(?CustomResourceDefinitionNames $acceptedNames = null, iterable $conditions = [], ?array $storedVersions = null)
    {
        $this->acceptedNames = $acceptedNames;
        $this->conditions = new Collection($conditions);
        $this->storedVersions = $storedVersions;
    }

    /**
     * acceptedNames are the names that are actually being used to serve discovery. They may be different
     * than the names in spec.
     */
    public function getAcceptedNames(): ?CustomResourceDefinitionNames
    {
        return $this->acceptedNames;
    }

    /**
     * acceptedNames are the names that are actually being used to serve discovery. They may be different
     * than the names in spec.
     *
     * @return static
     */
    public function setAcceptedNames(CustomResourceDefinitionNames $acceptedNames)
    {
        $this->acceptedNames = $acceptedNames;

        return $this;
    }

    /**
     * conditions indicate state for particular aspects of a CustomResourceDefinition
     *
     * @return iterable|CustomResourceDefinitionCondition[]
     */
    public function getConditions(): ?iterable
    {
        return $this->conditions;
    }

    /**
     * conditions indicate state for particular aspects of a CustomResourceDefinition
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
    public function addConditions(CustomResourceDefinitionCondition $condition)
    {
        if (!$this->conditions) {
            $this->conditions = new Collection();
        }
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * storedVersions lists all versions of CustomResources that were ever persisted. Tracking these
     * versions allows a migration path for stored versions in etcd. The field is mutable so a migration
     * controller can finish a migration to another version (ensuring no old objects are left in storage),
     * and then remove the rest of the versions from this list. Versions may not be removed from
     * `spec.versions` while they exist in this list.
     */
    public function getStoredVersions(): ?array
    {
        return $this->storedVersions;
    }

    /**
     * storedVersions lists all versions of CustomResources that were ever persisted. Tracking these
     * versions allows a migration path for stored versions in etcd. The field is mutable so a migration
     * controller can finish a migration to another version (ensuring no old objects are left in storage),
     * and then remove the rest of the versions from this list. Versions may not be removed from
     * `spec.versions` while they exist in this list.
     *
     * @return static
     */
    public function setStoredVersions(array $storedVersions)
    {
        $this->storedVersions = $storedVersions;

        return $this;
    }
}
