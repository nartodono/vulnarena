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

use DateTimeInterface;
use K8s\Api\Model\ApiMachinery\Apis\Meta\v1\ManagedFieldsEntry;
use K8s\Api\Model\ApiMachinery\Apis\Meta\v1\ObjectMeta;
use K8s\Api\Model\ApiMachinery\Apis\Meta\v1\OwnerReference;
use K8s\Core\Annotation as Kubernetes;

/**
 * Event is a report of an event somewhere in the cluster.  Events have a limited retention time and
 * triggers and messages may evolve with time.  Event consumers should not rely on the timing of an
 * event with a given Reason reflecting a consistent underlying trigger, or the continued existence of
 * events with that Reason.  Events should be treated as informative, best-effort, supplemental data.
 *
 * @Kubernetes\Kind("Event",version="v1")
 * @Kubernetes\Operation("get",path="/api/v1/namespaces/{namespace}/events/{name}",response="static::class")
 * @Kubernetes\Operation("post",path="/api/v1/namespaces/{namespace}/events",body="model",response="static::class")
 * @Kubernetes\Operation("delete",path="/api/v1/namespaces/{namespace}/events/{name}",response="K8s\Api\Model\ApiMachinery\Apis\Meta\v1\Status")
 * @Kubernetes\Operation("watch",path="/api/v1/namespaces/{namespace}/events",response="K8s\Api\Model\ApiMachinery\Apis\Meta\v1\WatchEvent")
 * @Kubernetes\Operation("put",path="/api/v1/namespaces/{namespace}/events/{name}",body="model",response="static::class")
 * @Kubernetes\Operation("deletecollection",path="/api/v1/namespaces/{namespace}/events",response="K8s\Api\Model\ApiMachinery\Apis\Meta\v1\Status")
 * @Kubernetes\Operation("watch-all",path="/api/v1/events",response="K8s\Api\Model\ApiMachinery\Apis\Meta\v1\WatchEvent")
 * @Kubernetes\Operation("patch",path="/api/v1/namespaces/{namespace}/events/{name}",body="patch",response="static::class")
 * @Kubernetes\Operation("list",path="/api/v1/namespaces/{namespace}/events",response="K8s\Api\Model\Api\Core\v1\EventList")
 * @Kubernetes\Operation("list-all",path="/api/v1/events",response="K8s\Api\Model\Api\Core\v1\EventList")
 */
class Event
{
    /**
     * @Kubernetes\Attribute("action")
     * @var string|null
     */
    protected $action = null;

    /**
     * @Kubernetes\Attribute("apiVersion")
     * @var string|null
     */
    protected $apiVersion = 'v1';

    /**
     * @Kubernetes\Attribute("count")
     * @var int|null
     */
    protected $count = null;

    /**
     * @Kubernetes\Attribute("eventTime",type="datetime")
     * @var DateTimeInterface|null
     */
    protected $eventTime = null;

    /**
     * @Kubernetes\Attribute("firstTimestamp",type="datetime")
     * @var DateTimeInterface|null
     */
    protected $firstTimestamp = null;

    /**
     * @Kubernetes\Attribute("involvedObject",type="model",model=ObjectReference::class,isRequired=true)
     * @var ObjectReference
     */
    protected $involvedObject;

    /**
     * @Kubernetes\Attribute("kind")
     * @var string|null
     */
    protected $kind = 'Event';

    /**
     * @Kubernetes\Attribute("lastTimestamp",type="datetime")
     * @var DateTimeInterface|null
     */
    protected $lastTimestamp = null;

    /**
     * @Kubernetes\Attribute("message")
     * @var string|null
     */
    protected $message = null;

    /**
     * @Kubernetes\Attribute("metadata",type="model",model=ObjectMeta::class,isRequired=true)
     * @var ObjectMeta
     */
    protected $metadata;

    /**
     * @Kubernetes\Attribute("reason")
     * @var string|null
     */
    protected $reason = null;

    /**
     * @Kubernetes\Attribute("related",type="model",model=ObjectReference::class)
     * @var ObjectReference|null
     */
    protected $related = null;

    /**
     * @Kubernetes\Attribute("reportingComponent")
     * @var string|null
     */
    protected $reportingComponent = null;

    /**
     * @Kubernetes\Attribute("reportingInstance")
     * @var string|null
     */
    protected $reportingInstance = null;

    /**
     * @Kubernetes\Attribute("series",type="model",model=EventSeries::class)
     * @var EventSeries|null
     */
    protected $series = null;

    /**
     * @Kubernetes\Attribute("source",type="model",model=EventSource::class)
     * @var EventSource|null
     */
    protected $source = null;

    /**
     * @Kubernetes\Attribute("type")
     * @var string|null
     */
    protected $type = null;

    /**
     * @param ObjectReference $involvedObject
     */
    public function __construct(?string $name, ObjectReference $involvedObject)
    {
        $this->metadata = new ObjectMeta($name);
        $this->involvedObject = $involvedObject;
    }

    /**
     * Annotations is an unstructured key value map stored with a resource that may be set by external
     * tools to store and retrieve arbitrary metadata. They are not queryable and should be preserved when
     * modifying objects. More info: http://kubernetes.io/docs/user-guide/annotations
     */
    public function getAnnotations(): ?array
    {
        return $this->metadata->getAnnotations();
    }

    /**
     * Annotations is an unstructured key value map stored with a resource that may be set by external
     * tools to store and retrieve arbitrary metadata. They are not queryable and should be preserved when
     * modifying objects. More info: http://kubernetes.io/docs/user-guide/annotations
     *
     * @return static
     */
    public function setAnnotations(array $annotations)
    {
        $this->metadata->setAnnotations($annotations);

        return $this;
    }

    /**
     * CreationTimestamp is a timestamp representing the server time when this object was created. It is
     * not guaranteed to be set in happens-before order across separate operations. Clients may not set
     * this value. It is represented in RFC3339 form and is in UTC.
     *
     * Populated by the system. Read-only. Null for lists. More info:
     * https://git.k8s.io/community/contributors/devel/sig-architecture/api-conventions.md#metadata
     */
    public function getCreationTimestamp(): ?DateTimeInterface
    {
        return $this->metadata->getCreationTimestamp();
    }

    /**
     * Number of seconds allowed for this object to gracefully terminate before it will be removed from the
     * system. Only set when deletionTimestamp is also set. May only be shortened. Read-only.
     */
    public function getDeletionGracePeriodSeconds(): ?int
    {
        return $this->metadata->getDeletionGracePeriodSeconds();
    }

    /**
     * DeletionTimestamp is RFC 3339 date and time at which this resource will be deleted. This field is
     * set by the server when a graceful deletion is requested by the user, and is not directly settable by
     * a client. The resource is expected to be deleted (no longer visible from resource lists, and not
     * reachable by name) after the time in this field, once the finalizers list is empty. As long as the
     * finalizers list contains items, deletion is blocked. Once the deletionTimestamp is set, this value
     * may not be unset or be set further into the future, although it may be shortened or the resource may
     * be deleted prior to this time. For example, a user may request that a pod is deleted in 30 seconds.
     * The Kubelet will react by sending a graceful termination signal to the containers in the pod. After
     * that 30 seconds, the Kubelet will send a hard termination signal (SIGKILL) to the container and
     * after cleanup, remove the pod from the API. In the presence of network partitions, this object may
     * still exist after this timestamp, until an administrator or automated process can determine the
     * resource is fully terminated. If not set, graceful deletion of the object has not been requested.
     *
     * Populated by the system when a graceful deletion is requested. Read-only. More info:
     * https://git.k8s.io/community/contributors/devel/sig-architecture/api-conventions.md#metadata
     */
    public function getDeletionTimestamp(): ?DateTimeInterface
    {
        return $this->metadata->getDeletionTimestamp();
    }

    /**
     * Must be empty before the object is deleted from the registry. Each entry is an identifier for the
     * responsible component that will remove the entry from the list. If the deletionTimestamp of the
     * object is non-nil, entries in this list can only be removed. Finalizers may be processed and removed
     * in any order.  Order is NOT enforced because it introduces significant risk of stuck finalizers.
     * finalizers is a shared field, any actor with permission can reorder it. If the finalizer list is
     * processed in order, then this can lead to a situation in which the component responsible for the
     * first finalizer in the list is waiting for a signal (field value, external system, or other)
     * produced by a component responsible for a finalizer later in the list, resulting in a deadlock.
     * Without enforced ordering finalizers are free to order amongst themselves and are not vulnerable to
     * ordering changes in the list.
     */
    public function getFinalizers(): ?array
    {
        return $this->metadata->getFinalizers();
    }

    /**
     * Must be empty before the object is deleted from the registry. Each entry is an identifier for the
     * responsible component that will remove the entry from the list. If the deletionTimestamp of the
     * object is non-nil, entries in this list can only be removed. Finalizers may be processed and removed
     * in any order.  Order is NOT enforced because it introduces significant risk of stuck finalizers.
     * finalizers is a shared field, any actor with permission can reorder it. If the finalizer list is
     * processed in order, then this can lead to a situation in which the component responsible for the
     * first finalizer in the list is waiting for a signal (field value, external system, or other)
     * produced by a component responsible for a finalizer later in the list, resulting in a deadlock.
     * Without enforced ordering finalizers are free to order amongst themselves and are not vulnerable to
     * ordering changes in the list.
     *
     * @return static
     */
    public function setFinalizers(array $finalizers)
    {
        $this->metadata->setFinalizers($finalizers);

        return $this;
    }

    /**
     * GenerateName is an optional prefix, used by the server, to generate a unique name ONLY IF the Name
     * field has not been provided. If this field is used, the name returned to the client will be
     * different than the name passed. This value will also be combined with a unique suffix. The provided
     * value has the same validation rules as the Name field, and may be truncated by the length of the
     * suffix required to make the value unique on the server.
     *
     * If this field is specified and the generated name exists, the server will return a 409.
     *
     * Applied only if Name is not specified. More info:
     * https://git.k8s.io/community/contributors/devel/sig-architecture/api-conventions.md#idempotency
     */
    public function getGenerateName(): ?string
    {
        return $this->metadata->getGenerateName();
    }

    /**
     * GenerateName is an optional prefix, used by the server, to generate a unique name ONLY IF the Name
     * field has not been provided. If this field is used, the name returned to the client will be
     * different than the name passed. This value will also be combined with a unique suffix. The provided
     * value has the same validation rules as the Name field, and may be truncated by the length of the
     * suffix required to make the value unique on the server.
     *
     * If this field is specified and the generated name exists, the server will return a 409.
     *
     * Applied only if Name is not specified. More info:
     * https://git.k8s.io/community/contributors/devel/sig-architecture/api-conventions.md#idempotency
     *
     * @return static
     */
    public function setGenerateName(string $generateName)
    {
        $this->metadata->setGenerateName($generateName);

        return $this;
    }

    /**
     * A sequence number representing a specific generation of the desired state. Populated by the system.
     * Read-only.
     */
    public function getGeneration(): ?int
    {
        return $this->metadata->getGeneration();
    }

    /**
     * Map of string keys and values that can be used to organize and categorize (scope and select)
     * objects. May match selectors of replication controllers and services. More info:
     * http://kubernetes.io/docs/user-guide/labels
     */
    public function getLabels(): ?array
    {
        return $this->metadata->getLabels();
    }

    /**
     * Map of string keys and values that can be used to organize and categorize (scope and select)
     * objects. May match selectors of replication controllers and services. More info:
     * http://kubernetes.io/docs/user-guide/labels
     *
     * @return static
     */
    public function setLabels(array $labels)
    {
        $this->metadata->setLabels($labels);

        return $this;
    }

    /**
     * ManagedFields maps workflow-id and version to the set of fields that are managed by that workflow.
     * This is mostly for internal housekeeping, and users typically shouldn't need to set or understand
     * this field. A workflow can be the user's name, a controller's name, or the name of a specific apply
     * path like "ci-cd". The set of fields is always in the version that the workflow used when modifying
     * the object.
     *
     * @return iterable|ManagedFieldsEntry[]
     */
    public function getManagedFields(): ?iterable
    {
        return $this->metadata->getManagedFields();
    }

    /**
     * ManagedFields maps workflow-id and version to the set of fields that are managed by that workflow.
     * This is mostly for internal housekeeping, and users typically shouldn't need to set or understand
     * this field. A workflow can be the user's name, a controller's name, or the name of a specific apply
     * path like "ci-cd". The set of fields is always in the version that the workflow used when modifying
     * the object.
     *
     * @return static
     */
    public function setManagedFields(iterable $managedFields)
    {
        $this->metadata->setManagedFields($managedFields);

        return $this;
    }

    /**
     * @return static
     */
    public function addManagedFields(ManagedFieldsEntry $managedField)
    {
        $this->metadata->addManagedFields($managedField);

        return $this;
    }

    /**
     * Name must be unique within a namespace. Is required when creating resources, although some resources
     * may allow a client to request the generation of an appropriate name automatically. Name is primarily
     * intended for creation idempotence and configuration definition. Cannot be updated. More info:
     * http://kubernetes.io/docs/user-guide/identifiers#names
     */
    public function getName(): ?string
    {
        return $this->metadata->getName();
    }

    /**
     * Name must be unique within a namespace. Is required when creating resources, although some resources
     * may allow a client to request the generation of an appropriate name automatically. Name is primarily
     * intended for creation idempotence and configuration definition. Cannot be updated. More info:
     * http://kubernetes.io/docs/user-guide/identifiers#names
     *
     * @return static
     */
    public function setName(string $name)
    {
        $this->metadata->setName($name);

        return $this;
    }

    /**
     * Namespace defines the space within which each name must be unique. An empty namespace is equivalent
     * to the "default" namespace, but "default" is the canonical representation. Not all objects are
     * required to be scoped to a namespace - the value of this field for those objects will be empty.
     *
     * Must be a DNS_LABEL. Cannot be updated. More info: http://kubernetes.io/docs/user-guide/namespaces
     */
    public function getNamespace(): ?string
    {
        return $this->metadata->getNamespace();
    }

    /**
     * Namespace defines the space within which each name must be unique. An empty namespace is equivalent
     * to the "default" namespace, but "default" is the canonical representation. Not all objects are
     * required to be scoped to a namespace - the value of this field for those objects will be empty.
     *
     * Must be a DNS_LABEL. Cannot be updated. More info: http://kubernetes.io/docs/user-guide/namespaces
     *
     * @return static
     */
    public function setNamespace(string $namespace)
    {
        $this->metadata->setNamespace($namespace);

        return $this;
    }

    /**
     * List of objects depended by this object. If ALL objects in the list have been deleted, this object
     * will be garbage collected. If this object is managed by a controller, then an entry in this list
     * will point to this controller, with the controller field set to true. There cannot be more than one
     * managing controller.
     *
     * @return iterable|OwnerReference[]
     */
    public function getOwnerReferences(): ?iterable
    {
        return $this->metadata->getOwnerReferences();
    }

    /**
     * List of objects depended by this object. If ALL objects in the list have been deleted, this object
     * will be garbage collected. If this object is managed by a controller, then an entry in this list
     * will point to this controller, with the controller field set to true. There cannot be more than one
     * managing controller.
     *
     * @return static
     */
    public function setOwnerReferences(iterable $ownerReferences)
    {
        $this->metadata->setOwnerReferences($ownerReferences);

        return $this;
    }

    /**
     * @return static
     */
    public function addOwnerReferences(OwnerReference $ownerReference)
    {
        $this->metadata->addOwnerReferences($ownerReference);

        return $this;
    }

    /**
     * An opaque value that represents the internal version of this object that can be used by clients to
     * determine when objects have changed. May be used for optimistic concurrency, change detection, and
     * the watch operation on a resource or set of resources. Clients must treat these values as opaque and
     * passed unmodified back to the server. They may only be valid for a particular resource or set of
     * resources.
     *
     * Populated by the system. Read-only. Value must be treated as opaque by clients and . More info:
     * https://git.k8s.io/community/contributors/devel/sig-architecture/api-conventions.md#concurrency-control-and-consistency
     */
    public function getResourceVersion(): ?string
    {
        return $this->metadata->getResourceVersion();
    }

    /**
     * Deprecated: selfLink is a legacy read-only field that is no longer populated by the system.
     */
    public function getSelfLink(): ?string
    {
        return $this->metadata->getSelfLink();
    }

    /**
     * Deprecated: selfLink is a legacy read-only field that is no longer populated by the system.
     *
     * @return static
     */
    public function setSelfLink(string $selfLink)
    {
        $this->metadata->setSelfLink($selfLink);

        return $this;
    }

    /**
     * UID is the unique in time and space value for this object. It is typically generated by the server
     * on successful creation of a resource and is not allowed to change on PUT operations.
     *
     * Populated by the system. Read-only. More info: http://kubernetes.io/docs/user-guide/identifiers#uids
     */
    public function getUid(): ?string
    {
        return $this->metadata->getUid();
    }

    /**
     * What action was taken/failed regarding to the Regarding object.
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * What action was taken/failed regarding to the Regarding object.
     *
     * @return static
     */
    public function setAction(string $action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * APIVersion defines the versioned schema of this representation of an object. Servers should convert
     * recognized schemas to the latest internal value, and may reject unrecognized values. More info:
     * https://git.k8s.io/community/contributors/devel/sig-architecture/api-conventions.md#resources
     */
    public function getApiVersion(): ?string
    {
        return $this->apiVersion;
    }

    /**
     * APIVersion defines the versioned schema of this representation of an object. Servers should convert
     * recognized schemas to the latest internal value, and may reject unrecognized values. More info:
     * https://git.k8s.io/community/contributors/devel/sig-architecture/api-conventions.md#resources
     *
     * @return static
     */
    public function setApiVersion(string $apiVersion)
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * The number of times this event has occurred.
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * The number of times this event has occurred.
     *
     * @return static
     */
    public function setCount(int $count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Time when this Event was first observed.
     */
    public function getEventTime(): ?DateTimeInterface
    {
        return $this->eventTime;
    }

    /**
     * Time when this Event was first observed.
     *
     * @return static
     */
    public function setEventTime(DateTimeInterface $eventTime)
    {
        $this->eventTime = $eventTime;

        return $this;
    }

    /**
     * The time at which the event was first recorded. (Time of server receipt is in TypeMeta.)
     */
    public function getFirstTimestamp(): ?DateTimeInterface
    {
        return $this->firstTimestamp;
    }

    /**
     * The time at which the event was first recorded. (Time of server receipt is in TypeMeta.)
     *
     * @return static
     */
    public function setFirstTimestamp(DateTimeInterface $firstTimestamp)
    {
        $this->firstTimestamp = $firstTimestamp;

        return $this;
    }

    /**
     * The object that this event is about.
     */
    public function getInvolvedObject(): ObjectReference
    {
        return $this->involvedObject;
    }

    /**
     * The object that this event is about.
     *
     * @return static
     */
    public function setInvolvedObject(ObjectReference $involvedObject)
    {
        $this->involvedObject = $involvedObject;

        return $this;
    }

    /**
     * Kind is a string value representing the REST resource this object represents. Servers may infer this
     * from the endpoint the client submits requests to. Cannot be updated. In CamelCase. More info:
     * https://git.k8s.io/community/contributors/devel/sig-architecture/api-conventions.md#types-kinds
     */
    public function getKind(): ?string
    {
        return $this->kind;
    }

    /**
     * Kind is a string value representing the REST resource this object represents. Servers may infer this
     * from the endpoint the client submits requests to. Cannot be updated. In CamelCase. More info:
     * https://git.k8s.io/community/contributors/devel/sig-architecture/api-conventions.md#types-kinds
     *
     * @return static
     */
    public function setKind(string $kind)
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * The time at which the most recent occurrence of this event was recorded.
     */
    public function getLastTimestamp(): ?DateTimeInterface
    {
        return $this->lastTimestamp;
    }

    /**
     * The time at which the most recent occurrence of this event was recorded.
     *
     * @return static
     */
    public function setLastTimestamp(DateTimeInterface $lastTimestamp)
    {
        $this->lastTimestamp = $lastTimestamp;

        return $this;
    }

    /**
     * A human-readable description of the status of this operation.
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * A human-readable description of the status of this operation.
     *
     * @return static
     */
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Standard object's metadata. More info:
     * https://git.k8s.io/community/contributors/devel/sig-architecture/api-conventions.md#metadata
     */
    public function getMetadata(): ObjectMeta
    {
        return $this->metadata;
    }

    /**
     * Standard object's metadata. More info:
     * https://git.k8s.io/community/contributors/devel/sig-architecture/api-conventions.md#metadata
     *
     * @return static
     */
    public function setMetadata(ObjectMeta $metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * This should be a short, machine understandable string that gives the reason for the transition into
     * the object's current status.
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * This should be a short, machine understandable string that gives the reason for the transition into
     * the object's current status.
     *
     * @return static
     */
    public function setReason(string $reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Optional secondary object for more complex actions.
     */
    public function getRelated(): ?ObjectReference
    {
        return $this->related;
    }

    /**
     * Optional secondary object for more complex actions.
     *
     * @return static
     */
    public function setRelated(ObjectReference $related)
    {
        $this->related = $related;

        return $this;
    }

    /**
     * Name of the controller that emitted this Event, e.g. `kubernetes.io/kubelet`.
     */
    public function getReportingComponent(): ?string
    {
        return $this->reportingComponent;
    }

    /**
     * Name of the controller that emitted this Event, e.g. `kubernetes.io/kubelet`.
     *
     * @return static
     */
    public function setReportingComponent(string $reportingComponent)
    {
        $this->reportingComponent = $reportingComponent;

        return $this;
    }

    /**
     * ID of the controller instance, e.g. `kubelet-xyzf`.
     */
    public function getReportingInstance(): ?string
    {
        return $this->reportingInstance;
    }

    /**
     * ID of the controller instance, e.g. `kubelet-xyzf`.
     *
     * @return static
     */
    public function setReportingInstance(string $reportingInstance)
    {
        $this->reportingInstance = $reportingInstance;

        return $this;
    }

    /**
     * Data about the Event series this event represents or nil if it's a singleton Event.
     */
    public function getSeries(): ?EventSeries
    {
        return $this->series;
    }

    /**
     * Data about the Event series this event represents or nil if it's a singleton Event.
     *
     * @return static
     */
    public function setSeries(EventSeries $series)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * The component reporting this event. Should be a short machine understandable string.
     */
    public function getSource(): ?EventSource
    {
        return $this->source;
    }

    /**
     * The component reporting this event. Should be a short machine understandable string.
     *
     * @return static
     */
    public function setSource(EventSource $source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Type of this event (Normal, Warning), new types could be added in the future
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Type of this event (Normal, Warning), new types could be added in the future
     *
     * @return static
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }
}
