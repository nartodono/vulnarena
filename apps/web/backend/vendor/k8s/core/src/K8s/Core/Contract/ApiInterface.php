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

namespace K8s\Core\Contract;

use K8s\Core\Exception\HttpException;
use K8s\Core\Exception\WebsocketException;

interface ApiInterface
{
    /**
     * Execute an HTTP based request against the API.
     *
     * @return mixed
     * @throws HttpException
     */
    public function executeHttp(string $uri, string $action, array $options);

    /**
     * Execute a websocket based request against the API.
     *
     * @param callable|object $handler
     * @throws WebsocketException
     */
    public function executeWebsocket(string $uri, string $type, $handler): void;

    /**
     * Given a URI path from kubernetes, the parameters, and the query options, form the complete path.
     */
    public function makeUri(string $uri, array $parameters, array $query = [], ?string $namespace = null): string;
}
