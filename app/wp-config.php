<?php

use Dotenv\Dotenv;

require_once dirname(__DIR__) . '/vendor/autoload.php';

(new Dotenv(dirname(__DIR__) . '/project'))->load();

define('WP_ENV', getenv('WP_ENV'));
define('WP_DEBUG', getenv('WP_DEBUG'));

define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_HOST', getenv('DB_HOST'));

define('DISABLE_WP_CRON', true);
define('WP_CONTENT_DIR', __DIR__ . '/content');
define('WP_CONTENT_URL', 'https://' . $_SERVER['HTTP_HOST'] . '/content');
define('PLUGINDIR', 'content/plugins');
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/wp/');
}
$table_prefix = 'nw_';

require ABSPATH . 'wp-settings.php';