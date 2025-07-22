<?php
declare(strict_types=1);

namespace JsonDecodeStream\Tests;

use FilesystemIterator;
use Iterator;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * @psal
 * @package JsonDecodeStream\Tests
 */
abstract class Test extends TestCase
{
    protected function getSampleFiles($subdirectory = '', $ext = 'json')
    {
        $subdirectory = trim($subdirectory, '/');
        $searchPath = __DIR__ . '/data/';
        if ($subdirectory) {
            $searchPath .= $subdirectory . '/';
        }

        /** @var Iterator|SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $searchPath,
                FilesystemIterator::SKIP_DOTS
            )
        );
        foreach ($files as $file) {
            if ($file->getExtension() !== $ext) {
                continue;
            }

            /** @noinspection PhpUnnecessaryLocalVariableInspection */
            $relativePathname = substr($file->getPathname(), strlen($searchPath));

            yield $relativePathname => [ $file->getPathname() ];
        }
    }
}
