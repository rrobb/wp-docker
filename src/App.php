<?php
declare(strict_types=1);

namespace WPSite;

use Psr\Container\ContainerInterface;
use WPSite\ServiceContainer as Container;

/**
 * Class Kernel
 * @author Rob Burgers <robburgers@gmail.com>
 * @package WPSite
 */
class App
{
    private static $instance;
    private $container;
    private $configValues = [];

    public static function getInstance(?ContainerInterface $container = null): App
    {
        if (self::$instance === null) {

            if ($container === null) {
                $container = new Container();
            }
            self::$instance = new self($container);
        }

        return self::$instance;
    }

    private function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setConfig($key, $value): void
    {
        $this->configValues[$key] = $value;
    }

    public function loadConfig(array $configs): void
    {
        $this->configValues = array_merge($this->configValues, $configs);
    }

    public function getConfig($key)
    {
        if (array_key_exists($key, $this->configValues)) {
            return $this->configValues[$key];
        }
        return false;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
