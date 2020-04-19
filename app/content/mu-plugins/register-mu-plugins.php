<?php

declare(strict_types=1);

/**
 * Registers all available mu-plugins
 */
$registerMuPlugins = function () {
    foreach (new DirectoryIterator(__DIR__) as $dir) {
        if (
            $dir->isDot() ||
            !$dir->isDir() ||
            $dir->getPathname() === __DIR__
        ) {
            continue;
        }
        foreach (new DirectoryIterator($dir->getPathname()) as $fileInfo) {
            if (
                !$fileInfo->isFile() ||
                $fileInfo->getExtension() !== 'php'
            ) {
                continue;
            }
            if (
                $fileInfo->getBasename('.php') === $dir->getFilename() ||
                $fileInfo->getFilename() === 'acf.php'
            ) {
                /** @noinspection PhpIncludeInspection */
                require $fileInfo->getPathname();
            }
        }
    }
};

$registerMuPlugins();