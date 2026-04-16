<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php
    global $wrw_wp_data;
    if (!isset($wrw_wp_data)) {
        $wrw_wp_data = array('page' => 'unknown');
    }
    
    // Add global auth state to data
    $wrw_wp_data['isLoggedIn'] = is_user_logged_in();
    $wrw_wp_data['isAdmin'] = current_user_can('edit_users');
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $wrw_wp_data['user'] = array(
            'id' => $current_user->ID,
            'name' => $current_user->display_name,
            'avatar' => get_avatar_url($current_user->ID, array('size' => 45))
        );
    }
    
    // Add URLs
    $wrw_wp_data['urls'] = array(
        'home' => esc_url(home_url('/home')),
        'team' => esc_url(home_url('/team')),
        'events' => esc_url(home_url('/events')),
        'regeln' => esc_url(home_url('/regeln')),
        'sponsoren' => esc_url(home_url('/sponsoren')),
        'galerie' => esc_url(home_url('/galerie')),
        'forum' => esc_url(home_url('/community')),
        'announcements' => esc_url(home_url('/announcements')),
        'admin' => esc_url(home_url('/admin-overview')),
        'profil' => esc_url(home_url('/profil')),
        'login' => esc_url(wp_login_url(home_url('/'))),
        'logout' => esc_url(wp_logout_url(home_url('/')))
    );
    ?>
    <script>
        window.__WP_DATA__ = <?php echo wp_json_encode($wrw_wp_data); ?>;
    </script>
    <div id="root"></div>
