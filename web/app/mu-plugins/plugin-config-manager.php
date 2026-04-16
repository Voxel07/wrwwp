<?php
/**
 * Plugin Name: Plugin Configuration Manager
 * Description: Programmatically overrides database options based on config/plugin-options.json
 * Version: 1.0.0
 * Author: Antigravity
 */

if (!defined('ABSPATH')) {
    exit;
}

// Locate the plugin-options.json file in the config/ directory
$plugin_config_file = dirname(dirname(ABSPATH)) . '/config/plugin-options.json';

if (file_exists($plugin_config_file)) {
    $plugin_config_content = file_get_contents($plugin_config_file);
    
    if (!empty($plugin_config_content)) {
        $plugin_config_options = json_decode($plugin_config_content, true);
        
        if (is_array($plugin_config_options)) {
            // Force strict overrides via filter and disable saving
            foreach ($plugin_config_options as $option_name => $option_value) {
                if ($option_name === '_comment') {
                    continue;
                }
                
                // 1. Force the value whenever it's read (bypasses DB select entirely)
                add_filter("pre_option_{$option_name}", function() use ($option_value) {
                    return $option_value;
                }, 99);

                // 2. Prevent the value from being overwritten in the database by the GUI
                add_filter("pre_update_option_{$option_name}", function($new_value, $old_value) use ($option_value) {
                    return $option_value; // Enforce our configured value
                }, 99, 2);
            }
        }
    }
}
