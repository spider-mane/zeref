<?php

/**
 *
 */
function deversion(string $version)
{
    return WP_ENV !== 'development' ? $version : time();
}

/**
 * loads all php files in a directory of php based config files
 */
function load_config_dir(string $dir): array
{
    foreach (scandir($dir) as $data) {

        $path = "{$dir}/{$data}";

        if (is_dir($path)) {
            continue;
        }

        $data = pathinfo($path);

        if ('php' !== $data['extension']) continue;

        $data = $data['filename'];

        $info[$data] = require "{$dir}/{$data}.php";
    }

    return $info;
}
