<?php
declare(strict_types=1);

namespace WPSite\Services;

use ErrorException;
use WPSite\Hooks\HooksInterface;
use ReflectionClass;

/**
 * Class HooksService
 * Return an instance of a Hook class
 */
class HooksService
{
    /**
     * Array of Hook objects
     * @var array
     */
    private $hooks = [];

    /**
     * Register a hook for execution
     *
     * @param HooksInterface $hook
     * @return $this
     * @throws ErrorException
     * @throws \ReflectionException
     */
    public function register(HooksInterface $hook): self
    {
        $hookClassName = $this->getShortClassName($hook);

        if (!\in_array($hookClassName, $this->hooks, true)) {
            $this->hooks[$hookClassName] = $hook;
        } else {
            throw new ErrorException("Hook '$hook' has already been registered");
        }

        return $this;
    }

    /**
     * Go through all registered hooks and apply them
     * @throws ErrorException
     */
    public function applyAll(): void
    {
        foreach ($this->hooks as $hook) {
            $this->apply($hook);
        }
    }

    /**
     * Apply a Hooks actions and filters
     *
     * @param HooksInterface $hook
     * @throws ErrorException
     */
    public function apply(HooksInterface $hook): void
    {
        if (!\in_array($hook, $this->hooks, true)) {
            throw new ErrorException("Hook '$hook' has not been registered");
        }

        $hook->attach();
    }

    /**
     * Get the short class name for a Hook object
     *
     * @param HooksInterface $hook
     * @return string
     * @throws \ReflectionException
     */
    private function getShortClassName(HooksInterface $hook): string
    {
        $hookClassName = get_class($hook);
        $reflection = new ReflectionClass($hookClassName);
        return $reflection->getShortName();
    }
}
