<?php

/**
 * This file is part of the k8s/core library.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace K8s\Core\Annotation;

/**
 * @Annotation
 */
class Attribute
{
    /**
     * @Required
     * @var string
     */
    public $name;

    /**
     * @Enum({"model", "collection", "datetime"})
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $model;

    /**
     * @var bool
     */
    public $isRequired = false;
}
