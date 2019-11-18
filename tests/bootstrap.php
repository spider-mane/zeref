<?php

# Use themes
define('WP_USE_THEMES', true);

# Base
define('DS', DIRECTORY_SEPARATOR);
define('APP_ROOT_DIR', __DIR__ . '/env');
define('WEB_ROOT_DIRNAME', 'public');

# WP Core
define('WP_CORE_DIRNAME', 'wp');
define('WP_CORE_DIR', APP_ROOT_DIR . DS . WEB_ROOT_DIRNAME . DS . WP_CORE_DIRNAME);

# WP Content
define('WP_CONTENT_DIRNAME', 'app');
define('WP_CONTENT_DIR', APP_ROOT_DIR . DS . WEB_ROOT_DIRNAME . DS . WP_CONTENT_DIRNAME);

require dirname(__DIR__) . '/vendor/autoload.php';
