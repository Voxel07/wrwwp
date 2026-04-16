<?php
/**
 * Template Name: Galerie
 */
global $wrw_wp_data;
$wrw_wp_data = array('page' => 'galerie');

// Required for gallery components
$wrw_wp_data['isLoggedIn'] = is_user_logged_in();
$wrw_wp_data['proxyUrl'] = esc_url(get_option('immich_proxy_url', ''));
$wrw_wp_data['dropUrl'] = esc_url(get_option('immich_drop_url', ''));

get_header(); ?>
<?php get_footer(); ?>
