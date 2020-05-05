<?php

use WebTheory\Zeref\Accessors\Config;
use WebTheory\Zeref\Application;

require dirname(__DIR__) . '/env.php';
require dirname(__DIR__, 3) . '/vendor/autoload.php';

$app = new Application(dirname(__DIR__));
$app->bootstrap();

defined('ABSPATH') || define('ABSPATH', dirname(__FILE__) . 'wp' . DIRECTORY_SEPARATOR);

$table_prefix = Config::get('wp.table_prefix');

require_once(ABSPATH . 'wp-settings.php');
