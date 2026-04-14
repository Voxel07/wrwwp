<?php
/**
 * Functions and definitions
 */

function airsoft_theme_enqueue_styles() {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Roboto:wght@400;500&display=swap', false);
    wp_enqueue_style('airsoft-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));
    
    // Vanilla-tilt.js for 3D interactions
    wp_enqueue_script('vanilla-tilt', 'https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js', array(), '1.8.0', true);
}
add_action('wp_enqueue_scripts', 'airsoft_theme_enqueue_styles');

require_once get_template_directory() . '/includes/user-profiles.php';
require_once get_template_directory() . '/includes/events.php';
require_once get_template_directory() . '/includes/announcements.php';
require_once get_template_directory() . '/includes/frontend-profile.php';

function airsoft_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'airsoft-theme'),
    ));
}
add_action('after_setup_theme', 'airsoft_theme_setup');

// Auto-create pages and flush rewrites on theme activation (or init)
function wrw_setup_pages() {
    if (get_option('wrw_pages_created') !== 'rewrite6') {
        $pages = array(
            'team' => array('title' => 'Team', 'template' => 'page-team.php'),
            'regeln' => array('title' => 'Regeln', 'template' => 'page-regeln.php'),
            'sponsoren' => array('title' => 'Sponsoren', 'template' => 'page-sponsoren.php'),
            'galerie' => array('title' => 'Galerie', 'template' => 'page-galerie.php'),
            'events' => array('title' => 'Events', 'template' => 'page-events.php'),
            'announcements' => array('title' => 'Announcements', 'template' => 'page-announcements.php'),
            'profil' => array('title' => 'Profil', 'template' => 'page-profile.php'),
            'admin-overview' => array('title' => 'Admin Overview', 'template' => 'page-admin-overview.php'),
        );


        foreach ($pages as $slug => $data) {
            $page_check = get_page_by_path($slug);
            if (!isset($page_check->ID)) {
                $new_page_id = wp_insert_post(array(
                    'post_title' => $data['title'],
                    'post_name' => $slug,
                    'post_status' => 'publish',
                    'post_type' => 'page',
                ));
                if ($new_page_id && !is_wp_error($new_page_id)) {
                    update_post_meta($new_page_id, '_wp_page_template', $data['template']);
                }
            } else {
                update_post_meta($page_check->ID, '_wp_page_template', $data['template']);
            }
        }
        
        // Also set a static front page if not set
        $home_check = get_page_by_path('home');
        if (!isset($home_check->ID)) {
            $home_id = wp_insert_post(array(
                'post_title' => 'Home',
                'post_name' => 'home',
                'post_status' => 'publish',
                'post_type' => 'page',
            ));
            if ($home_id && !is_wp_error($home_id)) {
                update_post_meta($home_id, '_wp_page_template', 'front-page.php');
                update_option('show_on_front', 'page');
                update_option('page_on_front', $home_id);
            }
        }

        // Set permalink structure to post name
        update_option('permalink_structure', '/%postname%/');
        
        // Flush rewrite rules to avoid 404
        flush_rewrite_rules();
        
        // Auto-activate all securely pulled composer plugins once
        if ( ! function_exists( 'activate_plugin' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugins = get_plugins();
        foreach ($plugins as $plugin_path => $plugin_info) {
            if (!is_plugin_active($plugin_path)) {
                activate_plugin($plugin_path);
            }
        }
        
        // Register Native WP Roles for Team Mapping
        if (!get_role('vorstand')) add_role('vorstand', 'Vorstand', array('read' => true));
        if (!get_role('mitglied')) add_role('mitglied', 'Mitglied', array('read' => true));
        if (!get_role('frischling')) add_role('frischling', 'Frischling', array('read' => true));
        
        update_option('wrw_pages_created', 'rewrite6'); // Burst the check to force page generation
    }
}
add_action('init', 'wrw_setup_pages');
