<?php
declare(strict_types=1);

namespace WPSite\Http\Controllers;

use Timber\Timber;
use WPSite\Http\Controller;


/**
 * Class FrontPage
 * @package WPSite\Controller
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
