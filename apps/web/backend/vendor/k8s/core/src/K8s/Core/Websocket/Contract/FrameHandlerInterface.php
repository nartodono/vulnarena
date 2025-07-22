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

use K8s\Core\Websocket\Frame;

interface FrameHandlerInterface
{
    /**
     * Triggered on the initial connection
     */
    public function onConnect(WebsocketConnectionInterface $connection): void;

    /**
     * Triggered when the connection is closed.
     */
    public function onClose(): void;

    /**
     * Triggered when data is received on the connection.
     */
    public function onReceive(Frame $frame, WebsocketConnectionInterface $connection): void;

    /**
     * The sub-protocol used by the handler.
     */
    public function subprotocol(): string;
}
