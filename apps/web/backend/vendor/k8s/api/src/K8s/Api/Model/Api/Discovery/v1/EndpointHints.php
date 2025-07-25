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

namespace K8s\Api\Model\Api\Discovery\v1;

use K8s\Core\Annotation as Kubernetes;
use K8s\Core\Collection;

/**
 * EndpointHints provides hints describing how an endpoint should be consumed.
 */
class EndpointHints
{
    /**
     * @Kubernetes\Attribute("forZones",type="collection",model=ForZone::class)
     * @var iterable|ForZone[]|null
     */
    protected $forZones = null;

    /**
     * @param iterable|ForZone[] $forZones
     */
    public function __construct(iterable $forZones = [])
    {
        $this->forZones = new Collection($forZones);
    }

    /**
     * forZones indicates the zone(s) this endpoint should be consumed by to enable topology aware routing.
     *
     * @return iterable|ForZone[]
     */
    public function getForZones(): ?iterable
    {
        return $this->forZones;
    }

    /**
     * forZones indicates the zone(s) this endpoint should be consumed by to enable topology aware routing.
     *
     * @return static
     */
    public function setForZones(iterable $forZones)
    {
        $this->forZones = $forZones;

        return $this;
    }

    /**
     * @return static
     */
    public function addForZones(ForZone $forZone)
    {
        if (!$this->forZones) {
            $this->forZones = new Collection();
        }
        $this->forZones[] = $forZone;

        return $this;
    }
}
