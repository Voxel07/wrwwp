<?php
function wrw_handle_frontend_profile() {
    if (!is_user_logged_in()) {
        wp_die('Unauthorized');
    }
    if (!isset($_POST['wrw_profile_nonce']) || !wp_verify_nonce($_POST['wrw_profile_nonce'], 'update_profile')) {
        wp_die('Security check failed.');
    }

    $user_id = get_current_user_id();

    // Standard fields cleanly extracted from HTTP POST explicitly checking native elements
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['user_email']);
    
    // Custom meta payloads
    $birthday = sanitize_text_field($_POST['wrw_birthday']);
    $phrase = sanitize_text_field($_POST['wrw_phrase']);
    $notification_pref = sanitize_text_field($_POST['wrw_notification_pref']);

    $user_data = wp_update_user(array(
        'ID' => $user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'user_email' => $email
    ));

    // Handle Potential Avatar Upload
    if (!empty($_FILES['wrw_profile_picture']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $uploaded_file = $_FILES['wrw_profile_picture'];
        $upload_overrides = array('test_form' => false);

        $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {
            // Successfully uploaded, save direct URL onto the User Meta
            update_user_meta($user_id, 'wrw_profile_picture_url', $movefile['url']);
        }
    }

    if (!is_wp_error($user_data)) {
        update_user_meta($user_id, 'wrw_birthday', $birthday);
        update_user_meta($user_id, 'wrw_phrase', $phrase);
        update_user_meta($user_id, 'wrw_notification_pref', $notification_pref);
    }

    wp_redirect(add_query_arg('updated', '1', home_url('/profil')));
    exit;
}
add_action('admin_post_wrw_update_profile', 'wrw_handle_frontend_profile');

// Admin Overview Processing for Mass Role/Mentor Mapping
function wrw_handle_admin_overview() {
    if (!is_user_logged_in() || !current_user_can('edit_users')) {
        wp_die('Unauthorized');
    }
    
    $target_user_id = intval($_POST['target_user_id']);
    if (!isset($_POST['wrw_admin_nonce']) || !wp_verify_nonce($_POST['wrw_admin_nonce'], 'update_user_admin_' . $target_user_id)) {
        wp_die('Security check failed.');
    }

    $new_role = sanitize_text_field($_POST['wrw_role']);
    $new_mentor = sanitize_text_field($_POST['wrw_mentor_id']);

    // Map strictly to WordPress Role arrays natively
    $user = new WP_User($target_user_id);
    $user->set_role($new_role);

    // Securely tie mentorship logic explicitly to Frischlinge
    if ($new_role === 'frischling') {
        update_user_meta($target_user_id, 'wrw_mentor_id', $new_mentor);
    } else {
        delete_user_meta($target_user_id, 'wrw_mentor_id');
    }

    wp_redirect(add_query_arg('updated', '1', home_url('/admin-overview')));
    exit;
}
add_action('admin_post_wrw_update_user_admin', 'wrw_handle_admin_overview');
