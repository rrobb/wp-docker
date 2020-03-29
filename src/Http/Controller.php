<?php
declare(strict_types=1);

namespace WPSite\Http;

use Psr\Container\ContainerInterface;
use Timber\FunctionWrapper;
use Timber\Timber;
use function function_exists;

/**
 * This is the abstract controller class. It contains methods for:
 * - Rendering templates
 */
abstract class Controller implements ControllerInterface
{
    /**
     * The (default) array of variables used in Timber/Twig templates
     * @var array
     */
    protected $timberContext;

    /** @var ContainerInterface */
    private $container;

    /**
     * Retrieve and set the value for $mappedBodyClass
     * @param $container ContainerInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        /** Load only the needed Timber context parts we actually use */
        $this->timberContext['wp_footer'] = new FunctionWrapper('wp_footer');
        $this->timberContext['wp_head'] = new FunctionWrapper('wp_head');
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Renders the full page.
     * @param string $template The path to the template.
     * @param array $data The array with data. Defaults to an empty array.
     */
    public function renderPage($template, array $data = []): void
    {
        $themeName = 'wp-site';
        $baseData = [
            'gtmTag' => function_exists('gtm4wp_get_the_gtm_tag') ? gtm4wp_get_the_gtm_tag() : '',
            'nonce' => wp_create_nonce('wp_rest'),
            'cssLoaded' => !empty($_COOKIE['fullCssLoaded']) ? filter_var($_COOKIE['fullCssLoaded'], FILTER_SANITIZE_STRING) : false,
            'fontsLoaded' => !empty($_COOKIE['fontsLoaded']) ? true : false,
            'currentLanguage' => $this->getCurrentLanguageSlug(),
            'imgDir' => sprintf('/content/themes/%s/assets/img/', $themeName),
            'pages' => $this->getPages(),
        ];

        $context = array_merge($this->timberContext, $baseData, $data);

        Timber::render($template . '.html.twig', $context);
    }

    /**
     * Get array of links of static pages
     * @return array
     */
    private function getPages(): array
    {
        return [
            'home' => __('homeLink', TRANSLATION_DOMAIN),
            'vacancyOverview' => __('vacancyOverviewLink', TRANSLATION_DOMAIN),
            'apply' => __('applyLink', TRANSLATION_DOMAIN),
        ];
    }

    /**
     * Get language slug based on the WPLANG option
     * @return string
     */
    private function getCurrentLanguageSlug(): string
    {
        $languages = [
            'nl_NL' => 'nl',
            'en_US' => 'en',
        ];

        return $languages[get_option('WPLANG')] ?? 'en';
    }

    /**
     * Get featured image
     * @param int $postId
     * @return array
     */
    public function getFeaturedImage(int $postId): array
    {
        $thumbnail = (int)get_post_thumbnail_id($postId) ?: null;

        return $this->getBannerImageArray($thumbnail);
    }

    /**
     * Get banner image array
     * @param int|null $thumbnailId
     * @return array
     */
    public function getBannerImageArray(?int $thumbnailId): array
    {
        if ($thumbnailId === null) {
            $thumbnailId = get_field('fallback_header_image', 'option') ?: null;
        }

        return [
            'xs' => wp_get_attachment_image_src($thumbnailId, 'banner_xs'),
            'sm' => wp_get_attachment_image_src($thumbnailId, 'banner_sm'),
            'md' => wp_get_attachment_image_src($thumbnailId, 'banner_md'),
            'lg' => wp_get_attachment_image_src($thumbnailId, 'banner_lg'),
        ];
    }
}
