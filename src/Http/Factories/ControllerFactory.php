<?php
declare(strict_types=1);

namespace WPSite\Http\Factories;

use WPSite\Http\ControllerInterface;
use WPSite\App;
use Exception;
use WP_Post;

/**
 * This is the factory class for the 'Controller' classes.
 */
class ControllerFactory
{
    /**
     * Shared namespace for all controllers.
     *
     * @const string
     */
    private const CONTROLLER_NAMESPACE = 'Endouble\Http\Controllers';

    /**
     * Name of the request type for front page requests.
     *
     * @const string
     */
    private const REQUEST_TYPE_FRONT_PAGE = 'front_page';

    /**
     * Name of the request type for page not found requests.
     *
     * @const string
     */
    private const REQUEST_TYPE_PAGE_NOT_FOUND = 'page_not_found';

    /**
     * Array with the function names (without is_) from /wp/wp-includes/template-loader.php. These functions are
     * sorted by decreasing chance of being true, to minimize the number of iterations of loops over this array.
     *
     * @var array
     */
    private static $functions = [
        'single',
        'front_page',
        'home',
        'page',
        'archive',
        '404',
        'search',
        'feed',
        'category',
        'tag',
        'tax',
        'attachment',
        'author',
        'date',
        'day',
        'month',
        'time',
        'trackback',
        'year',
    ];

    /**
     * Create a new controller for the current request, and render the related view, then exit the script to prevent
     * WordPress from doing anything else.
     */
    public static function loadByTemplate(): void
    {
        try {
            $controllerName = self::checkWordpressFunctions(self::$functions);
            $controller = self::create($controllerName);
        } catch (Exception $e) {
            // Catches requests for which no controller was defined
            $controller = self::create(self::createPageNotFound());
        }

        $controller->render();

        exit;
    }

    /**
     * Create a new controller given the name of its class.
     *
     * @param string $controllerClass
     * @return ControllerInterface
     * @throws \UnexpectedValueException
     */
    private static function create($controllerClass): ControllerInterface
    {
        if (!class_exists($controllerClass)) {
            throw new \UnexpectedValueException("Controller '$controllerClass' is not defined.");
        }
        $container = App::getInstance()->getContainer();

        return new $controllerClass($container);
    }

    /**
     * Loop through the functions and check which applies
     ** @throws Exception
     * @param array $functions Array of wordpress functions (without 'is_') that can check which page we're on
     * @return string Controller name. Page not found controller is returned if no other controller was chosen.
     */
    private static function checkWordpressFunctions(array $functions): string
    {
        foreach ($functions as $function) {
            $controller = self::getControllerForWordpressFunction($function);
            if ($controller) {
                return $controller;
            }
        }

        // Catches unexpected requests (not resolved by any of the standard WordPress functions)
        // @todo Log unexpected request (all standard WordPress functions are false)
        return self::createPageNotFound();
    }

    /**
     * Checks if the given function is true (i.e. is_post_type_archive()) and if so, returns the controller name
     ** @throws Exception
     * @param string $function the function (without is_) to check which page we're on
     * @return string Controller name or empty string if function returns false
     */
    private static function getControllerForWordpressFunction($function): string
    {
        $wpFunction = 'is_' . $function;

        if ($wpFunction() === false) {
            return '';
        }

        if ($function === '404') {
            return self::createPageNotFound();
        }

        return self::createName($function);
    }

    /**
     * Create the name of the controller for page not found.
     * @throws Exception
     * @return string
     */
    private static function createPageNotFound(): string
    {
        return self::createName(self::REQUEST_TYPE_PAGE_NOT_FOUND);
    }

    /**
     * Creates a namespaced class name.
     * Checks if this is a post type page, and adds that to Controller name
     * i.e. post_type_archive for vacancies will give a controller_name: Vacancy\\PostTypeArchive
     * @throws Exception
     * @param  string $requestType
     * @return string
     */
    public static function createName($requestType): string
    {
        $controller = self::CONTROLLER_NAMESPACE;
        $requestsNotToBeHandledAsPosts = [self::REQUEST_TYPE_FRONT_PAGE, self::REQUEST_TYPE_PAGE_NOT_FOUND];
        $postType = self::getPostType();
        $postTypeCamelCased = self::toCamelCase($postType);
        $requestTypeCamelCased = self::toCamelCase($requestType);

        if ($postType && !in_array($requestType, $requestsNotToBeHandledAsPosts)) {
            $controllerBaseName = $controller . '\\' . $postTypeCamelCased;
            $genericController = $controllerBaseName . '\\' . $postTypeCamelCased;
            $requestController = $controllerBaseName . '\\' . $requestTypeCamelCased;
            $specificController = $controllerBaseName . '\\' . self::toCamelCase(self::getSpecificRequestType());

            // If the current request is for an archive page, WordPress still populates $wp_query['post'] with the most
            // recent post of the archive, but we don't want to use its specific controller.
            if (!is_archive() && class_exists($specificController)) {
                return $specificController;
            }

            if (class_exists($requestController)) {
                return $requestController;
            }

            if (class_exists($genericController)) {
                return $genericController;
            }
        }

        return $controller . '\\' . $requestTypeCamelCased;
    }

    /**
     * Return the request type linked to the current specific post (i.e. its slug).
     *
     * @return string
     */
    private static function getSpecificRequestType(): string
    {
        /** @var WP_Post $post */
        global $post;

        if ($post === null) {
            return '';
        }

        $controller = get_post_meta($post->ID, 'controller', true);
        return $controller ?: $post->post_name;
    }

    /**
     * Return the name of the post type of the current request, or null if no post type was found. This is not trivial
     * to obtain, because if a query has zero results, WordPress forgets which post type this is, so we have to check
     * into the current query if this page maybe actually really has a post type.
     *
     * @return null|string
     */
    private static function getPostType(): ?string
    {
        $postType = get_post_type();
        if ($postType) {
            return $postType;
        }

        global $wp_query;

        if ($wp_query instanceof \WP_Query && isset($wp_query->query['post_type'])  ) {
            $postType = $wp_query->query['post_type'];
        }

        return $postType ?: null;
    }

    /**
     * Convert a string to a camel-cased version of the same string, with the first letter capitalized. This method
     * will also remove all non alphanumeric characters (regarding them as spaces before capitalization).
     *
     * @param string $string
     * @return string
     * @throws Exception
     */
    private static function toCamelCase($string): string
    {
        $string = self::normalizeAsWords($string);
        $string = trim($string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);

        return $string;
    }

    /**
     * Normalize a string as a sequence of words, i.e. replacing all non alphanumeric characters with a space.
     *
     * @param string $string
     * @return mixed
     * @throws Exception
     */
    private static function normalizeAsWords($string): string
    {
        $string = preg_replace('/[^a-z0-9]+/i', ' ', $string);
        if ($string === null) {
            throw new Exception("Unable to normalize $string as words.");
        }

        return $string;
    }
}
