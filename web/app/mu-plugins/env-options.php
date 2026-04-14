<?php
/*
Plugin Name: Dynamic .env Options
Description: Inherently maps any `.env` variable prefixed with WP_OPTION_ securely onto native database calls.
Version: 1.0.0
*/

if (!defined('ABSPATH')) {
    exit;
}

$all_envs = array_merge($_SERVER, $_ENV);
foreach ($all_envs as $env_key => $env_value) {
    if (strpos((string)$env_key, 'WP_OPTION_') === 0) {
        $option_name = strtolower(substr($env_key, 10));
        add_filter("pre_option_{$option_name}", function() use ($env_value) {
            return $env_value;
        });
    }
}
