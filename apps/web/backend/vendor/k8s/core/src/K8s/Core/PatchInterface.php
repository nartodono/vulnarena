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

namespace K8s\Core;

interface PatchInterface
{
    /**
     * The array representation of the patch data to be sent.
     */
    public function toArray(): array;

    /**
     * The HTTP content-type specific to this patch.
     */
    public function getContentType(): string;
}
