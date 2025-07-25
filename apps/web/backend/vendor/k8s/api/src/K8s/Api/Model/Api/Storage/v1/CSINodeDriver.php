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

namespace K8s\Api\Model\Api\Storage\v1;

use K8s\Core\Annotation as Kubernetes;

/**
 * CSINodeDriver holds information about the specification of one CSI driver installed on a node
 */
class CSINodeDriver
{
    /**
     * @Kubernetes\Attribute("allocatable",type="model",model=VolumeNodeResources::class)
     * @var VolumeNodeResources|null
     */
    protected $allocatable = null;

    /**
     * @Kubernetes\Attribute("name",isRequired=true)
     * @var string
     */
    protected $name;

    /**
     * @Kubernetes\Attribute("nodeID",isRequired=true)
     * @var string
     */
    protected $nodeID;

    /**
     * @Kubernetes\Attribute("topologyKeys")
     * @var string[]|null
     */
    protected $topologyKeys = null;

    /**
     * @param string $name
     * @param string $nodeID
     */
    public function __construct(string $name, string $nodeID)
    {
        $this->name = $name;
        $this->nodeID = $nodeID;
    }

    /**
     * allocatable represents the volume resources of a node that are available for scheduling. This field
     * is beta.
     */
    public function getAllocatable(): ?VolumeNodeResources
    {
        return $this->allocatable;
    }

    /**
     * allocatable represents the volume resources of a node that are available for scheduling. This field
     * is beta.
     *
     * @return static
     */
    public function setAllocatable(VolumeNodeResources $allocatable)
    {
        $this->allocatable = $allocatable;

        return $this;
    }

    /**
     * This is the name of the CSI driver that this object refers to. This MUST be the same name returned
     * by the CSI GetPluginName() call for that driver.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * This is the name of the CSI driver that this object refers to. This MUST be the same name returned
     * by the CSI GetPluginName() call for that driver.
     *
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * nodeID of the node from the driver point of view. This field enables Kubernetes to communicate with
     * storage systems that do not share the same nomenclature for nodes. For example, Kubernetes may refer
     * to a given node as "node1", but the storage system may refer to the same node as "nodeA". When
     * Kubernetes issues a command to the storage system to attach a volume to a specific node, it can use
     * this field to refer to the node name using the ID that the storage system will understand, e.g.
     * "nodeA" instead of "node1". This field is required.
     */
    public function getNodeID(): string
    {
        return $this->nodeID;
    }

    /**
     * nodeID of the node from the driver point of view. This field enables Kubernetes to communicate with
     * storage systems that do not share the same nomenclature for nodes. For example, Kubernetes may refer
     * to a given node as "node1", but the storage system may refer to the same node as "nodeA". When
     * Kubernetes issues a command to the storage system to attach a volume to a specific node, it can use
     * this field to refer to the node name using the ID that the storage system will understand, e.g.
     * "nodeA" instead of "node1". This field is required.
     *
     * @return static
     */
    public function setNodeID(string $nodeID)
    {
        $this->nodeID = $nodeID;

        return $this;
    }

    /**
     * topologyKeys is the list of keys supported by the driver. When a driver is initialized on a cluster,
     * it provides a set of topology keys that it understands (e.g. "company.com/zone",
     * "company.com/region"). When a driver is initialized on a node, it provides the same topology keys
     * along with values. Kubelet will expose these topology keys as labels on its own node object. When
     * Kubernetes does topology aware provisioning, it can use this list to determine which labels it
     * should retrieve from the node object and pass back to the driver. It is possible for different nodes
     * to use different topology keys. This can be empty if driver does not support topology.
     */
    public function getTopologyKeys(): ?array
    {
        return $this->topologyKeys;
    }

    /**
     * topologyKeys is the list of keys supported by the driver. When a driver is initialized on a cluster,
     * it provides a set of topology keys that it understands (e.g. "company.com/zone",
     * "company.com/region"). When a driver is initialized on a node, it provides the same topology keys
     * along with values. Kubelet will expose these topology keys as labels on its own node object. When
     * Kubernetes does topology aware provisioning, it can use this list to determine which labels it
     * should retrieve from the node object and pass back to the driver. It is possible for different nodes
     * to use different topology keys. This can be empty if driver does not support topology.
     *
     * @return static
     */
    public function setTopologyKeys(array $topologyKeys)
    {
        $this->topologyKeys = $topologyKeys;

        return $this;
    }
}
