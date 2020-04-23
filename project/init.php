<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use WPSite\App;
use WPSite\Hooks\ACF as ACFHook;
use WPSite\Hooks\OptionPage as OptionPageHook;
use WPSite\Hooks\Timber as TimberHook;
use WPSite\ServiceContainer;
use WPSite\Services\HooksService;
use WPSite\Services\ManifestService;

$nwSrcPath = static function () {
    return defined('ENDB_SRC_PATH') ? ENDB_SRC_PATH : dirname(__DIR__, 2) . '/src';
};
$nwThemeDir = static function () {
    return defined('ENDB_THEME_DIR') ? ENDB_THEME_DIR : '';
};
global $kernel;
/** @var App $kernel */
$kernel = App::getInstance();
/** @var ServiceContainer $container */
$container = $kernel->getContainer();
// Set Hook classes dependencies
$container->set(ACFHook::class);
$container->set(
    ManifestService::class,
    new ManifestService($nwThemeDir())
);

$container->set(
    'WP',
    static function () {
        define('WP_USE_THEMES', true);
        require dirname(__DIR__) . '/app/wp/wp-blog-header.php';
    }
);
$container->get('WP');
$container->set(
    TimberHook::class,
    static function ($container) use ($nwSrcPath, $nwThemeDir) {
        static $timberHook = null;
        if ($timberHook === null) {
            $timberHook = new TimberHook($container->get(ManifestService::class));
            $timberHook->setTimberLocations(
                [
                    $nwSrcPath() . '/resources/views',
                    $nwThemeDir() . '/assets',
                ]
            );
        }

        return $timberHook;
    }
);
$container->set(
    HooksService::class,
    static function ($container) {
        static $hookerService = null;
        if ($hookerService === null) {
            $hookerService = new HooksService();
            $hookerService
                ->register($container->get(TimberHook::class))
                ->register($container->get(OptionPageHook::class))
                ->register($container->get(ACFHook::class));
        }

        return $hookerService;
    }
);
