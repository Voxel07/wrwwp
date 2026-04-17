<?php
/**
 * Security Hardening for WRW Theme
 */

// 1. Remove WordPress version info
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

// 2. Disable User Enumeration via REST API
add_filter('rest_endpoints', function($endpoints) {
    if (isset($endpoints['/wp/v2/users'])) {
        unset($endpoints['/wp/v2/users']);
    }
    if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
        unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    }
    return $endpoints;
});

// 3. Disable Author Archives to prevent Author ID scanning
add_action('template_redirect', function() {
    if (is_author()) {
        wp_safe_redirect(home_url(), 301);
        exit;
    }
});

// 4. Disable XML-RPC (common attack vector)
add_filter('xmlrpc_enabled', '__return_false');

// 5. Remove version strings from ALL scripts and styles to hide version footprints
function wrw_remove_wp_version_strings($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('script_loader_src', 'wrw_remove_wp_version_strings', 9999);
add_filter('style_loader_src', 'wrw_remove_wp_version_strings', 9999);

// 6. Block unauthorized access to REST API for non-logged in users (Optional but recommended)
/*
add_filter('rest_authentication_errors', function($result) {
    if (!empty($result)) {
        return $result;
    }
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 'Not logged in', array('status' => 401));
    }
    return $result;
});
*/
