<?php
/**
 * Template Name: Team
 */
global $wrw_wp_data;
$wrw_wp_data = array('page' => 'team');

$users = get_users();
$roles = array(
    'vorstand' => 'Vorstand',
    'mitglied' => 'Mitglieder',
    'frischling' => 'Frischlinge'
);

$grouped_members = array(
    'vorstand' => array(),
    'mitglied' => array(),
    'frischling' => array()
);

// Prefetch all events for performance
$all_events = get_posts(array(
    'post_type' => 'wrw_event',
    'posts_per_page' => -1,
    'post_status' => 'publish'
));

foreach ($users as $user) {
    if ($user->ID <= 1) continue; // Optional skip admin if needed
    $matched_role = 'mitglied'; // fallback
    foreach ($user->roles as $role) {
        if (array_key_exists($role, $grouped_members)) {
            $matched_role = $role;
            break;
        }
    }
    if (array_key_exists($matched_role, $grouped_members)) {
        
        $birthday = get_user_meta($user->ID, 'wrw_birthday', true);
        $bday_is_today = false;
        $age = '';
        if ($birthday) {
            try {
                $bday_obj = new DateTime($birthday);
                $now = new DateTime();
                $bday_is_today = ($bday_obj->format('m-d') === wp_date('m-d'));
                $age = $now->diff($bday_obj)->y . ' Jahre alt';
            } catch (Exception $e) {}
        }

        $join_date = get_user_meta($user->ID, 'wrw_joined_date', true);
        $anchor = $join_date ? $join_date : $user->user_registered;
        $diff = (new DateTime())->diff(new DateTime($anchor));
        $months = $diff->m + ($diff->y * 12);
        $duration_str = 'Neu dabei';
        if ($months > 0) {
            if ($diff->y >= 1) $duration_str = "Dabei seit {$diff->y} Jahr" . ($diff->y > 1 ? 'en' : '') . ($diff->m > 0 ? " und {$diff->m} Monat" . ($diff->m > 1 ? 'en' : '') : '');
            else $duration_str = "Dabei seit {$diff->m} Monat" . ($diff->m > 1 ? 'en' : '');
        }

        $custom_avatar = get_user_meta($user->ID, 'wrw_profile_picture_url', true);
        $avatar = $custom_avatar ? esc_url($custom_avatar) : esc_url(get_avatar_url($user->ID, array('size' => 150)));

        $visited = 0;
        foreach ($all_events as $evt) {
            $parts = get_post_meta($evt->ID, '_wrw_event_participants', true);
            if (is_array($parts) && in_array($user->ID, $parts)) {
                $visited++;
            }
        }
        
        $mentor_name = null;
        $mentor_id = get_user_meta($user->ID, 'wrw_mentor_id', true);
        if ($mentor_id) {
            $mentor_obj = get_userdata($mentor_id);
            if ($mentor_obj) $mentor_name = rtrim(esc_html($mentor_obj->display_name));
        }

        $grouped_members[$matched_role][] = array(
            'id' => $user->ID,
            'name' => esc_html($user->display_name),
            'avatar' => $avatar,
            'ribbon' => esc_html(get_user_meta($user->ID, 'wrw_ribbon', true)),
            'phrase' => esc_html(get_user_meta($user->ID, 'wrw_phrase', true)),
            'birthday' => $birthday,
            'bdayIsToday' => $bday_is_today,
            'age' => $age,
            'duration' => $duration_str,
            'mentorName' => $mentor_name,
            'visitedOps' => $visited,
            'mentorOf' => array() // Will be filled below for mitglieds
        );
    }
}

// Compute mentored recruits
foreach ($grouped_members['mitglied'] as &$mit) {
    foreach ($grouped_members['frischling'] as $f) {
        $f_mentor_id = get_user_meta($f['id'], 'wrw_mentor_id', true);
        if ($f_mentor_id == $mit['id']) {
            $mit['mentorOf'][] = $f['name'];
        }
    }
}
unset($mit);

$wrw_wp_data['teamConfig'] = array(
    'roles' => $roles,
    'members' => $grouped_members
);

get_header(); ?>
<?php get_footer(); ?>
