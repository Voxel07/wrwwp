<?php
/**
 * Template Name: Admin Roster
 */
global $wrw_wp_data;
$wrw_wp_data = array('page' => 'admin-overview');

if (!is_user_logged_in() || !current_user_can('edit_users')) {
    wp_redirect(home_url('/team'));
    exit;
}

$wrw_wp_data['isAdmin'] = true;
$wrw_wp_data['adminPostUrl'] = esc_url(admin_url('admin-post.php'));

// Build roster users array for React
$roster_users = array();
foreach (get_users() as $u) {
    $curr_role = 'mitglied';
    if (in_array('vorstand', (array) $u->roles)) $curr_role = 'vorstand';
    elseif (in_array('frischling', (array) $u->roles)) $curr_role = 'frischling';

    $roster_users[] = array(
        'id'          => $u->ID,
        'displayName' => esc_html($u->display_name),
        'role'        => $curr_role,
        'mentorId'    => get_user_meta($u->ID, 'wrw_mentor_id', true),
        'nonce'       => wp_create_nonce('update_user_admin_' . $u->ID),
    );
}
$wrw_wp_data['rosterUsers'] = $roster_users;

// Build mitglieder list for mentor select
$mitglieder_list = array();
foreach (get_users(array('role' => 'mitglied')) as $m) {
    $mitglieder_list[] = array(
        'id'          => $m->ID,
        'displayName' => esc_html($m->display_name),
    );
}
$wrw_wp_data['mitglieder'] = $mitglieder_list;

get_header(); ?>
<?php get_footer(); ?>
