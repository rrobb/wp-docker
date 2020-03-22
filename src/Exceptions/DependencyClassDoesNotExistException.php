<?php
declare(strict_types=1);

namespace WPSite\Container\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class DependencyClassDoesNotExistException
 * @author Rob Burgers <rob@endouble.com>
 * @package WPSite\Container\Exceptions
 */
class DependencyClassDoesNotExistException extends Exception implements ContainerExceptionInterface
{
    /**
     * DependencyClassDoesNotExistException constructor.
     * @param $dependency
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(
        $dependency,
        $code = 0,
        Exception $previous = null
    ) {
        $message = "{$dependency} does not exist";
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
