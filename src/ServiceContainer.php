<?php
declare(strict_types=1);

namespace WPSite;

use Closure;
use WPSite\Container\Exceptions\DependencyClassDoesNotExistException;
use WPSite\Container\Exceptions\DependencyHasNoDefaultValueException;
use WPSite\Container\Exceptions\DependencyIsNotInstantiableException;
use WPSite\Container\Exceptions\DependencyNotRegisteredException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

/**
 * A psr-11 compliant container
 */
class ServiceContainer implements ContainerInterface
{
    /**
     * Registered dependencies
     * @var array
     */
    protected $instances = [];

    /**
     * Sets a dependency.
     * @example
     * $container->set(
     *     stdClass::class,
     *     function () {
     *         return new stdClass();
     *     }
     * );
     * @param $abstract
     * @param $concrete
     * @return void
     */
    public function set(
        string $abstract,
        $concrete = null
    ): void {
        $this->instances[$abstract] = $concrete ?? $abstract;
    }

    /**
     * Finds a dependency by its identifier and returns it.
     * @example $obj = $container->get(stdClass::class);
     * @param string $dependency Identifier of the dependency to look for.
     * @return mixed dependency.
     * @throws DependencyClassDoesNotExistException
     * @throws DependencyHasNoDefaultValueException
     * @throws DependencyIsNotInstantiableException
     * @throws DependencyNotRegisteredException
     * @throws ReflectionException
     */
    public function get($dependency)
    {
        if (!$this->has($dependency)) {
            throw new DependencyNotRegisteredException($dependency);
        }

        $entry = $this->instances[$dependency];
        if ($entry instanceof Closure) {
            // We use closures in order to enable factory composition
            return $entry($this);
        }

        return $this->asConcrete($entry);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     * `has($dependency)` returning true does not mean that `get($dependency)` will not throw an exception.
     * It does however mean that `get($dependency)` will not throw a `NotFoundExceptionInterface`.
     * @example $available = $container->has(stdClass::class);
     * @param string $dependency Identifier of the entry to look for.
     * @return boolean
     */
    public function has($dependency): bool
    {
        return isset($this->instances[$dependency]);
    }

    /**
     * Removes an entry from the container
     * @param string $dependency
     * @return void
     */
    public function unset($dependency): void
    {
        unset($this->instances[$dependency]);
    }

    /**
     * Returns an instance of the entry.
     * @param $entry
     * @return object the concrete entry.
     * @throws DependencyClassDoesNotExistException
     * @throws DependencyHasNoDefaultValueException
     * @throws DependencyIsNotInstantiableException
     * @throws DependencyNotRegisteredException
     * @throws ReflectionException
     */
    private function asConcrete($entry)
    {
        if (\is_object($entry)) {
            return $entry;
        }
        $resolved = [];
        $reflector = $this->getReflector($entry);
        $constructor = null;
        $parameters = [];
        if (!$reflector->isInstantiable()) {
            throw new DependencyIsNotInstantiableException($entry);
        }
        $constructor = $reflector->getConstructor();
        if ($constructor !== null) {
            $parameters = $constructor->getParameters();
        }

        if ($constructor === null || empty($parameters)) {
            // return new instance from class
            return $reflector->newInstance();
        }

        foreach ($parameters as $parameter) {
            $resolved[] = $this->resolveParameter($parameter);
        }

        // return new instance with dependencies resolved
        return $reflector->newInstanceArgs($resolved);
    }

    /**
     * Resolves the dependency's parameters
     * @param ReflectionParameter $parameter
     * @return mixed a resolved parameter
     * @throws DependencyClassDoesNotExistException
     * @throws DependencyHasNoDefaultValueException
     * @throws DependencyIsNotInstantiableException
     * @throws DependencyNotRegisteredException
     * @throws ReflectionException
     */
    private function resolveParameter(ReflectionParameter $parameter)
    {
        if ($parameter->getClass() !== null && $parameter->getType() !== null) { // The parameter is a class
            $typeName = (string)$parameter->getType();
            if (!$this->isUserDefined($parameter)) { // The parameter is not user defined

                $this->set($typeName); // Register it
            }

            return $this->get($typeName); // Instantiate it
        }

        if ($parameter->isDefaultValueAvailable()) { // Check if default value for a parameter is available

            return $parameter->getDefaultValue(); // Get default value of parameter
        }

        throw new DependencyHasNoDefaultValueException($parameter->name);
    }

    /**
     * Returns a ReflectionClass object representing the entry's class
     * @param $entry
     * @return ReflectionClass
     * @throws DependencyIsNotInstantiableException
     * @throws DependencyClassDoesNotExistException
     */
    private function getReflector($entry): ReflectionClass
    {
        try {
            $reflector = new ReflectionClass($entry);

            if (!$reflector->isInstantiable()) {
                throw new DependencyIsNotInstantiableException($entry);
            }

            return $reflector;
        } catch (ReflectionException $ex) {
            throw new DependencyClassDoesNotExistException($entry);
        }
    }

    /**
     * Checks if the dependency is an internal PHP class or a user defined one
     * @param ReflectionParameter $parameter
     * @return boolean
     */
    private function isUserDefined(ReflectionParameter $parameter): bool
    {
        if ($parameter->getType() === null || $parameter->getType()->isBuiltin()) {
            return false;
        }

        return $parameter->getClass()->isInternal() === false;
    }
}
