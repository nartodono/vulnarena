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
 * WebhookClientConfig contains the information to make a TLS connection with the webhook.
 */
class WebhookClientConfig
{
    /**
     * @Kubernetes\Attribute("caBundle")
     * @var string|null
     */
    protected $caBundle = null;

    /**
     * @Kubernetes\Attribute("service",type="model",model=ServiceReference::class)
     * @var ServiceReference|null
     */
    protected $service = null;

    /**
     * @Kubernetes\Attribute("url")
     * @var string|null
     */
    protected $url = null;

    /**
     * @param string|null $caBundle
     * @param ServiceReference|null $service
     * @param string|null $url
     */
    public function __construct(?string $caBundle = null, ?ServiceReference $service = null, ?string $url = null)
    {
        $this->caBundle = $caBundle;
        $this->service = $service;
        $this->url = $url;
    }

    /**
     * caBundle is a PEM encoded CA bundle which will be used to validate the webhook's server certificate.
     * If unspecified, system trust roots on the apiserver are used.
     */
    public function getCaBundle(): ?string
    {
        return $this->caBundle;
    }

    /**
     * caBundle is a PEM encoded CA bundle which will be used to validate the webhook's server certificate.
     * If unspecified, system trust roots on the apiserver are used.
     *
     * @return static
     */
    public function setCaBundle(string $caBundle)
    {
        $this->caBundle = $caBundle;

        return $this;
    }

    /**
     * service is a reference to the service for this webhook. Either service or url must be specified.
     *
     * If the webhook is running within the cluster, then you should use `service`.
     */
    public function getService(): ?ServiceReference
    {
        return $this->service;
    }

    /**
     * service is a reference to the service for this webhook. Either service or url must be specified.
     *
     * If the webhook is running within the cluster, then you should use `service`.
     *
     * @return static
     */
    public function setService(ServiceReference $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * url gives the location of the webhook, in standard URL form (`scheme://host:port/path`). Exactly one
     * of `url` or `service` must be specified.
     *
     * The `host` should not refer to a service running in the cluster; use the `service` field instead.
     * The host might be resolved via external DNS in some apiservers (e.g., `kube-apiserver` cannot
     * resolve in-cluster DNS as that would be a layering violation). `host` may also be an IP address.
     *
     * Please note that using `localhost` or `127.0.0.1` as a `host` is risky unless you take great care to
     * run this webhook on all hosts which run an apiserver which might need to make calls to this webhook.
     * Such installs are likely to be non-portable, i.e., not easy to turn up in a new cluster.
     *
     * The scheme must be "https"; the URL must begin with "https://".
     *
     * A path is optional, and if present may be any string permissible in a URL. You may use the path to
     * pass an arbitrary string to the webhook, for example, a cluster identifier.
     *
     * Attempting to use a user or basic auth e.g. "user:password@" is not allowed. Fragments ("#...") and
     * query parameters ("?...") are not allowed, either.
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * url gives the location of the webhook, in standard URL form (`scheme://host:port/path`). Exactly one
     * of `url` or `service` must be specified.
     *
     * The `host` should not refer to a service running in the cluster; use the `service` field instead.
     * The host might be resolved via external DNS in some apiservers (e.g., `kube-apiserver` cannot
     * resolve in-cluster DNS as that would be a layering violation). `host` may also be an IP address.
     *
     * Please note that using `localhost` or `127.0.0.1` as a `host` is risky unless you take great care to
     * run this webhook on all hosts which run an apiserver which might need to make calls to this webhook.
     * Such installs are likely to be non-portable, i.e., not easy to turn up in a new cluster.
     *
     * The scheme must be "https"; the URL must begin with "https://".
     *
     * A path is optional, and if present may be any string permissible in a URL. You may use the path to
     * pass an arbitrary string to the webhook, for example, a cluster identifier.
     *
     * Attempting to use a user or basic auth e.g. "user:password@" is not allowed. Fragments ("#...") and
     * query parameters ("?...") are not allowed, either.
     *
     * @return static
     */
    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }
}
