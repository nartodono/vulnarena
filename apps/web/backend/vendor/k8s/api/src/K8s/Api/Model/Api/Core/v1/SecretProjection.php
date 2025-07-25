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
use K8s\Core\Collection;

/**
 * Adapts a secret into a projected volume.
 *
 * The contents of the target Secret's Data field will be presented in a projected volume as files
 * using the keys in the Data field as the file names. Note that this is identical to a secret volume
 * source without the default mode.
 */
class SecretProjection
{
    /**
     * @Kubernetes\Attribute("items",type="collection",model=KeyToPath::class)
     * @var iterable|KeyToPath[]|null
     */
    protected $items = null;

    /**
     * @Kubernetes\Attribute("name")
     * @var string|null
     */
    protected $name = null;

    /**
     * @Kubernetes\Attribute("optional")
     * @var bool|null
     */
    protected $optional = null;

    /**
     * @param iterable|KeyToPath[] $items
     * @param string|null $name
     * @param bool|null $optional
     */
    public function __construct(iterable $items = [], ?string $name = null, ?bool $optional = null)
    {
        $this->items = new Collection($items);
        $this->name = $name;
        $this->optional = $optional;
    }

    /**
     * items if unspecified, each key-value pair in the Data field of the referenced Secret will be
     * projected into the volume as a file whose name is the key and content is the value. If specified,
     * the listed keys will be projected into the specified paths, and unlisted keys will not be present.
     * If a key is specified which is not present in the Secret, the volume setup will error unless it is
     * marked optional. Paths must be relative and may not contain the '..' path or start with '..'.
     *
     * @return iterable|KeyToPath[]
     */
    public function getItems(): ?iterable
    {
        return $this->items;
    }

    /**
     * items if unspecified, each key-value pair in the Data field of the referenced Secret will be
     * projected into the volume as a file whose name is the key and content is the value. If specified,
     * the listed keys will be projected into the specified paths, and unlisted keys will not be present.
     * If a key is specified which is not present in the Secret, the volume setup will error unless it is
     * marked optional. Paths must be relative and may not contain the '..' path or start with '..'.
     *
     * @return static
     */
    public function setItems(iterable $items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return static
     */
    public function addItems(KeyToPath $item)
    {
        if (!$this->items) {
            $this->items = new Collection();
        }
        $this->items[] = $item;

        return $this;
    }

    /**
     * Name of the referent. More info:
     * https://kubernetes.io/docs/concepts/overview/working-with-objects/names/#names
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Name of the referent. More info:
     * https://kubernetes.io/docs/concepts/overview/working-with-objects/names/#names
     *
     * @return static
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * optional field specify whether the Secret or its key must be defined
     */
    public function isOptional(): ?bool
    {
        return $this->optional;
    }

    /**
     * optional field specify whether the Secret or its key must be defined
     *
     * @return static
     */
    public function setIsOptional(bool $optional)
    {
        $this->optional = $optional;

        return $this;
    }
}
