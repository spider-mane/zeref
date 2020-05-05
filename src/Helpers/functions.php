<?php

/**
 *
 */
function env($name, $default = null)
{
    return Env::get($name) ?? $default;
}
