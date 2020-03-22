<?php
declare(strict_types=1);

namespace WPSite\Http;

/**
 * The interface for the 'Controller' classes.
 */
interface ControllerInterface
{
    /**
     * Renders the full web page.
     *
     * @return void
     */
    public function render(): void;
}
