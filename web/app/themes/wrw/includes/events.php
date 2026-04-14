<?php
// Register Events Custom Post Type
function wrw_register_events_cpt() {
    $labels = array(
        'name'               => 'Events',
        'singular_name'      => 'Event',
        'menu_name'          => 'Events',
        'name_admin_bar'     => 'Event',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Event',
        'new_item'           => 'New Event',
        'edit_item'          => 'Edit Event',
        'view_item'          => 'View Event',
        'all_items'          => 'All Events',
        'search_items'       => 'Search Events',
        'parent_item_colon'  => 'Parent Events:',
        'not_found'          => 'No events found.',
        'not_found_in_trash' => 'No events found in Trash.'
    );

    $args = array(
        'labels'             => $labels,
        'description'        => 'Custom Post Type for Airsoft Team Events.',
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'event'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'       => true
    );

    register_post_type('wrw_event', $args);
}
add_action('init', 'wrw_register_events_cpt');

// Handle Event Registration
function wrw_handle_event_registration() {
    if (!is_user_logged_in() || !isset($_POST['event_id']) || !isset($_POST['wrw_event_nonce'])) {
        wp_redirect(home_url('/events'));
        exit;
    }

    if (!wp_verify_nonce($_POST['wrw_event_nonce'], 'register_event_' . $_POST['event_id'])) {
        wp_die('Security check failed.');
    }

    $event_id = intval($_POST['event_id']);
    $user_id = get_current_user_id();
    $action = $_POST['registration_action']; // 'register' or 'unregister'

    $participants = get_post_meta($event_id, '_wrw_event_participants', true);
    if (!is_array($participants)) {
        $participants = array();
    }

    if ($action === 'register' && !in_array($user_id, $participants)) {
        $participants[] = $user_id;
    } elseif ($action === 'unregister' && in_array($user_id, $participants)) {
        $participants = array_diff($participants, array($user_id));
    }

    update_post_meta($event_id, '_wrw_event_participants', $participants);

    wp_redirect(get_permalink($event_id)); // Redirect back to event or events page if permalink isn't used
    // Actuall lets redirect back to /events 
    wp_redirect(home_url('/events'));
    exit;
}
add_action('admin_post_wrw_event_register', 'wrw_handle_event_registration');
add_action('admin_post_nopriv_wrw_event_register', 'wrw_handle_event_registration');

// Handle Frontend Event Creation
function wrw_handle_event_creation() {
    if (!is_user_logged_in() || !current_user_can('edit_posts')) {
        wp_die('Unauthorized');
    }
    if (!isset($_POST['wrw_event_nonce']) || !wp_verify_nonce($_POST['wrw_event_nonce'], 'create_event')) {
        wp_die('Security check failed.');
    }

    $title = sanitize_text_field($_POST['event_name']);
    $content = wp_kses_post($_POST['event_description']);
    $date = sanitize_text_field($_POST['event_date']);
    $location = sanitize_text_field($_POST['event_location']);

    $post_id = wp_insert_post(array(
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => 'publish',
        'post_type'    => 'wrw_event'
    ));

    if (!is_wp_error($post_id)) {
        update_post_meta($post_id, 'wrw_event_date', $date);
        update_post_meta($post_id, 'wrw_event_location', $location);
    }
    wp_redirect(home_url('/events'));
    exit;
}
add_action('admin_post_wrw_create_event', 'wrw_handle_event_creation');
