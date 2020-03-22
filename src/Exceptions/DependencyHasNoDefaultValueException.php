<?php
declare(strict_types=1);

namespace WPSite\Container\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class DependencyHasNoDefaultValueException
 * @author Rob Burgers <rob@endouble.com>
 * @package WPSite\Container\Exceptions
 */
class DependencyHasNoDefaultValueException extends Exception implements ContainerExceptionInterface
{
    /**
     * DependencyHasNoDefaultValueException constructor.
     * @param $dependency
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(
        $dependency,
        $code = 0,
        Exception $previous = null
    ) {
        $message = "Dependency {$dependency} can't be instantiated and yet has no default value";
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
