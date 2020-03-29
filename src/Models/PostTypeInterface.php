<?php
declare(strict_types=1);

namespace WPSite\Models;

/**
 * Interface PostType
 * @package WPSite\Models
 */
interface PostTypeInterface
{
    /**
     * The actual name of the post type
     * @return string
     */
    public function getType(): string;

    /**
     * returns the arguments for the post type
     * @return array
     */
    public function getArgs(): array;
}
