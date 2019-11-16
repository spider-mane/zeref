<?php

/**
 *
 */
function env($name, $default = null)
{
    return Env::get($name) ?? $default;
}

/**
 *
 */
function deversion(string $version)
{
    return WP_ENV !== 'development' ? $version : microtime();
}
