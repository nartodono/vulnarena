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

/**
 * CustomResourceValidation is a list of validation methods for CustomResources.
 */
class CustomResourceValidation
{
    /**
     * @Kubernetes\Attribute("openAPIV3Schema",type="model",model=JSONSchemaProps::class)
     * @var JSONSchemaProps|null
     */
    protected $openAPIV3Schema = null;

    /**
     * @param JSONSchemaProps|null $openAPIV3Schema
     */
    public function __construct(?JSONSchemaProps $openAPIV3Schema = null)
    {
        $this->openAPIV3Schema = $openAPIV3Schema;
    }

    /**
     * openAPIV3Schema is the OpenAPI v3 schema to use for validation and pruning.
     */
    public function getOpenAPIV3Schema(): ?JSONSchemaProps
    {
        return $this->openAPIV3Schema;
    }

    /**
     * openAPIV3Schema is the OpenAPI v3 schema to use for validation and pruning.
     *
     * @return static
     */
    public function setOpenAPIV3Schema(JSONSchemaProps $openAPIV3Schema)
    {
        $this->openAPIV3Schema = $openAPIV3Schema;

        return $this;
    }
}
