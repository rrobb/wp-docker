<?php

/**
 * Plugin Name
 *
 * @package           RegisterCustomTypes
 * @author            robburgers@gmail.com <Rob Burgers>
 * @copyright         2020 Rob Burgers
 *
 * @wordpress-plugin
 * Plugin Name: Register Custom Types
 * Description: Handles registering of custom post types and taxonomies and setting up relations between them.
 * Version: 1.0.0
 * Requires PHP: 7.2
 * Author: robburgers@gmail.com <Rob Burgers>
 */
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use RegisterCustomTypes\SetupRelations;

define('PROJECT_ROOT', dirname(ABSPATH, 2));

function registerCustom($customDir)
{
    if (!file_exists($customDir)) {
        return;
    }
    $dir = new DirectoryIterator($customDir);
    foreach ($dir as $fileInfo) {
        if (
            $fileInfo->isFile() &&
            $fileInfo->getExtension() === 'php'
        ) {
            require $fileInfo->getPathname();
        }
    }
}

if (isset($_ENV['PROJECT_CUSTOM_TYPES_DIR'])) {
    registerCustom(PROJECT_ROOT . '/' . $_ENV['PROJECT_CUSTOM_TYPES_DIR']);
}
new SetupRelations();