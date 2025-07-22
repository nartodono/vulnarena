<?php

/**
 * This file is part of the k8s/client library.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace K8s\Client\Kind;

use K8s\Api\Service\Core\v1\PodExecOptionsService;
use K8s\Client\Exception\InvalidArgumentException;
use K8s\Client\Websocket\Contract\ContainerExecInterface;

class PodExecService
{
    use PodExecTrait;

    /**
     * @var PodExecOptionsService
     */
    private $service;

    /**
     * @var string
     */
    private $name;

    public function __construct(PodExecOptionsService $service, string $name, string $namespace)
    {
        $this->service = $service;
        $this->name = $name;
        $this->service->useNamespace($namespace);
    }

    /**
     * Command is the remote command to execute. argv array. Not executed within a shell.
     *
     * @param string|string[] $command
     */
    public function command($command): self
    {
        $this->options['command'] = $command;

        return $this;
    }

    /**
     * Executes the command with the given handler in the Pod.
     *
     * @param callable|ContainerExecInterface $handler
     */
    public function run($handler): void
    {
        if (!(is_callable($handler) || $handler instanceof ContainerExecInterface)) {
            throw new InvalidArgumentException(sprintf(
                'The handler for the command must be a callable or ContainerExecInterface instance. Got: %s',
                is_object($handler) ? get_class($handler) : gettype($handler)
            ));
        }

        $this->service->connectGetNamespacedPodExec(
            $this->name,
            $handler,
            $this->options
        );
    }
}
