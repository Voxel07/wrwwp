<?php
require_once __DIR__ . '/wp-config.php';
var_dump(array_keys($_ENV));
var_dump(array_keys($_SERVER));
var_dump(getenv('WP_OPTION_IMMICH_PROXY_URL'));
var_dump(\Roots\env('WP_OPTION_IMMICH_PROXY_URL'));
