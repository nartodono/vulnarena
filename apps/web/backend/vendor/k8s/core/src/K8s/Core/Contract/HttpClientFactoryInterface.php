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

use Psr\Http\Client\ClientInterface;

interface HttpClientFactoryInterface
{
    /**
     * Make an instance of the Http Client based on a specific Kubernetes context configuration.
     *
     * @param bool $isStreaming Whether or not this client is intended for a streaming API call.
     */
    public function makeClient(ContextConfigInterface $fullContext, bool $isStreaming): ClientInterface;
}
