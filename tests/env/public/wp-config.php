<?php

use WebTheory\Zeref\Application;

require dirname(__DIR__) . '/env.php';
require dirname(__DIR__, 3) . '/vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);
define('APP_ROOT_DIR', dirname(__DIR__));
define('WEB_ROOT_DIRNAME', 'public');

# WP Core
define('WP_CORE_DIRNAME', 'wp');
define('WP_CORE_DIR', APP_ROOT_DIR . DS . WEB_ROOT_DIRNAME . DS . WP_CORE_DIRNAME);

# WP Content
define('WP_CONTENT_DIRNAME', 'app');
define('WP_CONTENT_DIR', APP_ROOT_DIR . DS . WEB_ROOT_DIRNAME . DS . WP_CONTENT_DIRNAME);

$app = new Application(dirname(__DIR__));
$app->bootstrap();


define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_HOST', getenv('DB_HOST'));

define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

define('WP_HOME', 'http://zeref.test');
define('WP_SITEURL', 'http://zeref.test/wp');

define('CONTENT_DIR', 'app');

define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);
define('SCRIPT_DEBUG', true);
define('WP_DISABLE_FATAL_ERROR_HANDLER', true);
define('SAVEQUERIES', true);

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . 'wp' . DIRECTORY_SEPARATOR);
}

$table_prefix = 'wp_';

require_once(ABSPATH . 'wp-settings.php');
