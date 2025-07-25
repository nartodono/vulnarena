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

namespace K8s\Api\Model\Api\Rbac\v1;

use K8s\Core\Annotation as Kubernetes;

/**
 * PolicyRule holds information that describes a policy rule, but does not contain information about
 * who the rule applies to or which namespace the rule applies to.
 */
class PolicyRule
{
    /**
     * @Kubernetes\Attribute("apiGroups")
     * @var string[]|null
     */
    protected $apiGroups = null;

    /**
     * @Kubernetes\Attribute("nonResourceURLs")
     * @var string[]|null
     */
    protected $nonResourceURLs = null;

    /**
     * @Kubernetes\Attribute("resourceNames")
     * @var string[]|null
     */
    protected $resourceNames = null;

    /**
     * @Kubernetes\Attribute("resources")
     * @var string[]|null
     */
    protected $resources = null;

    /**
     * @Kubernetes\Attribute("verbs",isRequired=true)
     * @var string[]
     */
    protected $verbs;

    /**
     * @param string[] $verbs
     */
    public function __construct(array $verbs)
    {
        $this->verbs = $verbs;
    }

    /**
     * APIGroups is the name of the APIGroup that contains the resources.  If multiple API groups are
     * specified, any action requested against one of the enumerated resources in any API group will be
     * allowed. "" represents the core API group and "*" represents all API groups.
     */
    public function getApiGroups(): ?array
    {
        return $this->apiGroups;
    }

    /**
     * APIGroups is the name of the APIGroup that contains the resources.  If multiple API groups are
     * specified, any action requested against one of the enumerated resources in any API group will be
     * allowed. "" represents the core API group and "*" represents all API groups.
     *
     * @return static
     */
    public function setApiGroups(array $apiGroups)
    {
        $this->apiGroups = $apiGroups;

        return $this;
    }

    /**
     * NonResourceURLs is a set of partial urls that a user should have access to.  *s are allowed, but
     * only as the full, final step in the path Since non-resource URLs are not namespaced, this field is
     * only applicable for ClusterRoles referenced from a ClusterRoleBinding. Rules can either apply to API
     * resources (such as "pods" or "secrets") or non-resource URL paths (such as "/api"),  but not both.
     */
    public function getNonResourceURLs(): ?array
    {
        return $this->nonResourceURLs;
    }

    /**
     * NonResourceURLs is a set of partial urls that a user should have access to.  *s are allowed, but
     * only as the full, final step in the path Since non-resource URLs are not namespaced, this field is
     * only applicable for ClusterRoles referenced from a ClusterRoleBinding. Rules can either apply to API
     * resources (such as "pods" or "secrets") or non-resource URL paths (such as "/api"),  but not both.
     *
     * @return static
     */
    public function setNonResourceURLs(array $nonResourceURLs)
    {
        $this->nonResourceURLs = $nonResourceURLs;

        return $this;
    }

    /**
     * ResourceNames is an optional white list of names that the rule applies to.  An empty set means that
     * everything is allowed.
     */
    public function getResourceNames(): ?array
    {
        return $this->resourceNames;
    }

    /**
     * ResourceNames is an optional white list of names that the rule applies to.  An empty set means that
     * everything is allowed.
     *
     * @return static
     */
    public function setResourceNames(array $resourceNames)
    {
        $this->resourceNames = $resourceNames;

        return $this;
    }

    /**
     * Resources is a list of resources this rule applies to. '*' represents all resources.
     */
    public function getResources(): ?array
    {
        return $this->resources;
    }

    /**
     * Resources is a list of resources this rule applies to. '*' represents all resources.
     *
     * @return static
     */
    public function setResources(array $resources)
    {
        $this->resources = $resources;

        return $this;
    }

    /**
     * Verbs is a list of Verbs that apply to ALL the ResourceKinds contained in this rule. '*' represents
     * all verbs.
     */
    public function getVerbs(): array
    {
        return $this->verbs;
    }

    /**
     * Verbs is a list of Verbs that apply to ALL the ResourceKinds contained in this rule. '*' represents
     * all verbs.
     *
     * @return static
     */
    public function setVerbs(array $verbs)
    {
        $this->verbs = $verbs;

        return $this;
    }
}
