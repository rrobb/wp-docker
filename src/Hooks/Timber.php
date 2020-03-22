<?php
declare(strict_types=1);


namespace WPSite\Hooks;

use WPSite\Services\ManifestService;
use WPSite\Twig\CacheBustingExtension;
use WPSite\Twig\ManifestExtension;
use WPSite\Twig\ACFExtension;
use Twig\Environment;
use Timber\Timber as LibTimber;

/**
 * Class TimberHook
 *
 * Hooks into the Timber class
 * @package WPSite\Hooks
 */
class Timber implements HooksInterface
{
    /**
     * @var ManifestService
     */
    private $manifestService;

    /**
     * Constructor
     * @param ManifestService $manifestService
     */
    public function __construct(ManifestService $manifestService)
    {
        $this->manifestService = $manifestService;
    }

    /**
     * Adds filter that enables registering extensions
     */
    private function registerExtensions(): void
    {
        add_filter('get_twig', [$this, 'addExtensions']);
    }

    /**
     * Register common extensions with Timber
     *
     * @param Environment $twig
     * @return Environment
     */
    public function addExtensions(Environment $twig): Environment
    {
        $twig->addExtension(new ACFExtension());
        $twig->addExtension(new CacheBustingExtension($this->manifestService));
        $twig->addExtension(new ManifestExtension($this->manifestService));

        return $twig;
    }

    /**
     * Attach all action and filters that are defined in the Hook class
     */
    public function attach(): void
    {
        $this->registerExtensions();
    }

    /**
     * Adds extra template folders for Timber to look in when rendering templates
     * @param array $timberLocations
     */
    public function setTimberLocations(array $timberLocations): void
    {
        $locations = (array)LibTimber::$locations;

        foreach ($timberLocations as $timberLocation) {
            $locations[] = $timberLocation;
        }

        LibTimber::$locations = $locations;
    }
}
