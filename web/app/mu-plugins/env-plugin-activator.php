<?php
/**
 * Plugin Name: Environment Plugin Activator
 * Description: Automatically activates plugins defined in WP_PLUGINS_ACTIVATE env var. Use 'all' to activate all wpackagist-plugin extensions from composer.json.
 * Version: 1.0.0
 * Author: Antigravity
 */

add_action('init', 'auto_activate_env_plugins');

function auto_activate_env_plugins() {
    if (!function_exists('is_blog_installed') || !is_blog_installed()) {
        return;
    }

    // Use PHP's built-in getenv() to read environment variables
    $env_plugins = getenv('WP_PLUGINS_ACTIVATE');
    if (!$env_plugins) {
        return;
    }

    $activated_hash = get_option('env_plugins_activated_hash');
    $current_hash = md5($env_plugins);
    
    if ($activated_hash === $current_hash) {
        return;
    }

    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $plugins_to_activate = [];

    if (strtolower(trim($env_plugins)) === 'all') {
        $composer_file = dirname(dirname(ABSPATH)) . '/composer.json';
        if (file_exists($composer_file)) {
            $composer_data = json_decode(file_get_contents($composer_file), true);
            if (!empty($composer_data['require'])) {
                foreach ($composer_data['require'] as $package => $version) {
                    if (strpos($package, 'wpackagist-plugin/') === 0) {
                        $plugins_to_activate[] = str_replace('wpackagist-plugin/', '', $package);
                    }
                }
            }
        }
    } else {
        $plugins_to_activate = array_filter(array_map('trim', explode(',', $env_plugins)));
    }

    if (empty($plugins_to_activate)) {
        return;
    }

    $installed_plugins = get_plugins();
    $active_plugins = (array) get_option('active_plugins', []);
    $updated = false;

    foreach ($plugins_to_activate as $slug) {
        foreach ($installed_plugins as $plugin_file => $plugin_data) {
            if (dirname($plugin_file) === $slug || $plugin_file === $slug . '.php') {
                if (!in_array($plugin_file, $active_plugins, true)) {
                    $active_plugins[] = $plugin_file;
                    $updated = true;
                }
                break;
            }
        }
    }

    if ($updated) {
        update_option('active_plugins', $active_plugins);
    }
    
    update_option('env_plugins_activated_hash', $current_hash);
}
