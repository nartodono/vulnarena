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

namespace K8s\Api\Model\ApiMachinery\Apis\Meta\v1;

use DateTimeInterface;
use K8s\Core\Annotation as Kubernetes;

/**
 * ManagedFieldsEntry is a workflow-id, a FieldSet and the group version of the resource that the
 * fieldset applies to.
 */
class ManagedFieldsEntry
{
    /**
     * @Kubernetes\Attribute("apiVersion")
     * @var string|null
     */
    protected $apiVersion = '';

    /**
     * @Kubernetes\Attribute("fieldsType")
     * @var string|null
     */
    protected $fieldsType = null;

    /**
     * @Kubernetes\Attribute("fieldsV1")
     * @var object|null
     */
    protected $fieldsV1 = null;

    /**
     * @Kubernetes\Attribute("manager")
     * @var string|null
     */
    protected $manager = null;

    /**
     * @Kubernetes\Attribute("operation")
     * @var string|null
     */
    protected $operation = null;

    /**
     * @Kubernetes\Attribute("subresource")
     * @var string|null
     */
    protected $subresource = null;

    /**
     * @Kubernetes\Attribute("time",type="datetime")
     * @var DateTimeInterface|null
     */
    protected $time = null;

    /**
     * APIVersion defines the version of this resource that this field set applies to. The format is
     * "group/version" just like the top-level APIVersion field. It is necessary to track the version of a
     * field set because it cannot be automatically converted.
     */
    public function getApiVersion(): ?string
    {
        return $this->apiVersion;
    }

    /**
     * APIVersion defines the version of this resource that this field set applies to. The format is
     * "group/version" just like the top-level APIVersion field. It is necessary to track the version of a
     * field set because it cannot be automatically converted.
     *
     * @return static
     */
    public function setApiVersion(string $apiVersion)
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * FieldsType is the discriminator for the different fields format and version. There is currently only
     * one possible value: "FieldsV1"
     */
    public function getFieldsType(): ?string
    {
        return $this->fieldsType;
    }

    /**
     * FieldsType is the discriminator for the different fields format and version. There is currently only
     * one possible value: "FieldsV1"
     *
     * @return static
     */
    public function setFieldsType(string $fieldsType)
    {
        $this->fieldsType = $fieldsType;

        return $this;
    }

    /**
     * FieldsV1 holds the first JSON version format as described in the "FieldsV1" type.
     *
     * @return object
     */
    public function getFieldsV1()
    {
        return $this->fieldsV1;
    }

    /**
     * FieldsV1 holds the first JSON version format as described in the "FieldsV1" type.
     *
     * @param object $fieldsV1
     * @return static
     */
    public function setFieldsV1($fieldsV1)
    {
        $this->fieldsV1 = $fieldsV1;

        return $this;
    }

    /**
     * Manager is an identifier of the workflow managing these fields.
     */
    public function getManager(): ?string
    {
        return $this->manager;
    }

    /**
     * Manager is an identifier of the workflow managing these fields.
     *
     * @return static
     */
    public function setManager(string $manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Operation is the type of operation which lead to this ManagedFieldsEntry being created. The only
     * valid values for this field are 'Apply' and 'Update'.
     */
    public function getOperation(): ?string
    {
        return $this->operation;
    }

    /**
     * Operation is the type of operation which lead to this ManagedFieldsEntry being created. The only
     * valid values for this field are 'Apply' and 'Update'.
     *
     * @return static
     */
    public function setOperation(string $operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Subresource is the name of the subresource used to update that object, or empty string if the object
     * was updated through the main resource. The value of this field is used to distinguish between
     * managers, even if they share the same name. For example, a status update will be distinct from a
     * regular update using the same manager name. Note that the APIVersion field is not related to the
     * Subresource field and it always corresponds to the version of the main resource.
     */
    public function getSubresource(): ?string
    {
        return $this->subresource;
    }

    /**
     * Subresource is the name of the subresource used to update that object, or empty string if the object
     * was updated through the main resource. The value of this field is used to distinguish between
     * managers, even if they share the same name. For example, a status update will be distinct from a
     * regular update using the same manager name. Note that the APIVersion field is not related to the
     * Subresource field and it always corresponds to the version of the main resource.
     *
     * @return static
     */
    public function setSubresource(string $subresource)
    {
        $this->subresource = $subresource;

        return $this;
    }

    /**
     * Time is the timestamp of when the ManagedFields entry was added. The timestamp will also be updated
     * if a field is added, the manager changes any of the owned fields value or removes a field. The
     * timestamp does not update when a field is removed from the entry because another manager took it
     * over.
     */
    public function getTime(): ?DateTimeInterface
    {
        return $this->time;
    }

    /**
     * Time is the timestamp of when the ManagedFields entry was added. The timestamp will also be updated
     * if a field is added, the manager changes any of the owned fields value or removes a field. The
     * timestamp does not update when a field is removed from the entry because another manager took it
     * over.
     *
     * @return static
     */
    public function setTime(DateTimeInterface $time)
    {
        $this->time = $time;

        return $this;
    }
}
