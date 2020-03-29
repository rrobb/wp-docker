<?php

namespace MUPlugins;

use DirectoryIterator;
use MUPlugins\RegisterCustomTypes\SetupRelations;

function registerCustom($customDir)
{
    if (file_exists($customDir)) {
        $dir = new DirectoryIterator($customDir);
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                require $fileinfo->getPathname();
            }
        }
    }
}

registerCustom(__DIR__ . '/RegisterCustomTypes/postType');
registerCustom(__DIR__ . '/RegisterCustomTypes/taxonomies');

new SetupRelations();