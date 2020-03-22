<?php
declare(strict_types=1);

namespace WPSite\Http\Controllers;

use WPSite\Http\Controller;
use Timber\Timber;


/**
 * Class FrontPage
 * @package Endouble\Controller
 */
class FrontPage extends Controller
{
    /**
     * Renders the front page.
     */
    public function render(): void
    {
        $post = Timber::get_post();

        $data = [
            'post' => $post,
            'criticalStylesheet' => 'home',
        ];

        $this->renderPage('/pages/front-page', $data);
    }
}
