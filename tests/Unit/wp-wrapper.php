<?php /** @noinspection PhpUnusedParameterInspection */

use PHPUnit\Framework\Error\Error as ErrorAlias;

function add_action(string $action, callable $function)
{
    echo sprintf('Added action %s', $action);
    $function();
}

function get_taxonomies(array $args, string $output)
{
    return ['tax' => 'value'];
}

function get_post_types(array $args, string $output)
{
    return ['post' => 'value'];
}

function get_option(string $name)
{
    switch ($name) {
        case 'relations_section__taxonomy':
            return ['posttype' => 'value'];
            break;
    }
    /** @noinspection PhpParamsInspection */
    throw new ErrorAlias('Option not found', 1);
}

function register_taxonomy_for_object_type(string $taxonomy, string $postType)
{
    echo sprintf('register %s for %s', $taxonomy, $postType);
}

function post_type_exists(string $postType)
{
    return false;
}

function taxonomy_exists(string $postType)
{
    return false;
}

function register_post_type(string $slug, array $data)
{
    echo sprintf('Registered post type %s', $slug);
}

function register_taxonomy(string $slug, array $data)
{
    echo sprintf('Registered taxonomy %s', $slug);
}
