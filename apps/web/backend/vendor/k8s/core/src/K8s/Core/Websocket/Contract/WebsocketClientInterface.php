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

namespace K8s\Core\Websocket\Contract;

use K8s\Core\Exception\WebsocketException;
use Psr\Http\Message\RequestInterface;

interface WebsocketClientInterface
{
    /**
     * @throws WebsocketException
     */
    public function connect(RequestInterface $request, FrameHandlerInterface $payloadHandler): void;
}
