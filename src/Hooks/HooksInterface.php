<?php
declare(strict_types=1);

namespace WPSite\Hooks;

/**
 * Interface HookInterface
 * @package WPSite\Hooks
 */
interface HooksInterface
{
    /**
     * Attach all action and filters that are defined in the Hook class
     */
    public function attach();
}
