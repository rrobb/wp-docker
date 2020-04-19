<?php

declare(strict_types=1);

use RegisterCustomTypes\CustomPostType;
use RegisterCustomTypes\CustomTaxonomy;

(new CustomPostType('story', 'Story', 'Stories'))
    ->addArgs(['supports' => ['author']])
    ->register();
(new CustomPostType('restaurant', 'Restaurant', 'Restaurants'))
    ->register();
(new CustomTaxonomy('country', 'Country', 'Countries'))
    ->register();