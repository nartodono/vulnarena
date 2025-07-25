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

namespace K8s\Api\Model\Api\Networking\v1;

use K8s\Core\Annotation as Kubernetes;

/**
 * IngressClassParametersReference identifies an API object. This can be used to specify a cluster or
 * namespace-scoped resource.
 */
class IngressClassParametersReference
{
    /**
     * @Kubernetes\Attribute("apiGroup")
     * @var string|null
     */
    protected $apiGroup = null;

    /**
     * @Kubernetes\Attribute("kind",isRequired=true)
     * @var string
     */
    protected $kind;

    /**
     * @Kubernetes\Attribute("name",isRequired=true)
     * @var string
     */
    protected $name;

    /**
     * @Kubernetes\Attribute("namespace")
     * @var string|null
     */
    protected $namespace = null;

    /**
     * @Kubernetes\Attribute("scope")
     * @var string|null
     */
    protected $scope = null;

    /**
     * @param string $kind
     * @param string $name
     */
    public function __construct(string $kind, string $name)
    {
        $this->kind = $kind;
        $this->name = $name;
    }

    /**
     * APIGroup is the group for the resource being referenced. If APIGroup is not specified, the specified
     * Kind must be in the core API group. For any other third-party types, APIGroup is required.
     */
    public function getApiGroup(): ?string
    {
        return $this->apiGroup;
    }

    /**
     * APIGroup is the group for the resource being referenced. If APIGroup is not specified, the specified
     * Kind must be in the core API group. For any other third-party types, APIGroup is required.
     *
     * @return static
     */
    public function setApiGroup(string $apiGroup)
    {
        $this->apiGroup = $apiGroup;

        return $this;
    }

    /**
     * Kind is the type of resource being referenced.
     */
    public function getKind(): string
    {
        return $this->kind;
    }

    /**
     * Kind is the type of resource being referenced.
     *
     * @return static
     */
    public function setKind(string $kind)
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * Name is the name of resource being referenced.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Name is the name of resource being referenced.
     *
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Namespace is the namespace of the resource being referenced. This field is required when scope is
     * set to "Namespace" and must be unset when scope is set to "Cluster".
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * Namespace is the namespace of the resource being referenced. This field is required when scope is
     * set to "Namespace" and must be unset when scope is set to "Cluster".
     *
     * @return static
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Scope represents if this refers to a cluster or namespace scoped resource. This may be set to
     * "Cluster" (default) or "Namespace".
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * Scope represents if this refers to a cluster or namespace scoped resource. This may be set to
     * "Cluster" (default) or "Namespace".
     *
     * @return static
     */
    public function setScope(string $scope)
    {
        $this->scope = $scope;

        return $this;
    }
}
