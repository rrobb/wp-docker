<?php
declare(strict_types=1);

namespace WPSite\Container\Exceptions;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class DependencyNotRegisteredException
 * @author Rob Burgers <rob@endouble.com>
 * @package WPSite\Container\Exceptions
 */
class DependencyNotRegisteredException extends Exception implements NotFoundExceptionInterface
{
    /**
     * DependencyNotRegisteredException constructor.
     * @param $dependency
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(
        $dependency,
        $code = 0,
        Exception $previous = null
    ) {
        $message = "Dependency {$dependency} is not registered";
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
