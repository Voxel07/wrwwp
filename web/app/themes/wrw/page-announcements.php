<?php
/**
 * Template Name: Announcements
 */
global $wrw_wp_data;
$wrw_wp_data = array('page' => 'announcements');
$wrw_wp_data['isLoggedIn'] = is_user_logged_in();

if (is_user_logged_in()) {
    $can_edit = current_user_can('edit_posts');
    $wrw_wp_data['canEditAnnouncements'] = $can_edit;
    $wrw_wp_data['adminPostUrl'] = esc_url(admin_url('admin-post.php'));
    $wrw_wp_data['nonceCreate'] = wp_create_nonce('create_announcement');

    $pd = class_exists('Parsedown') ? (new Parsedown())->setSafeMode(true) : null;

    $announcements_data = array();
    $news = new WP_Query(array('post_type' => 'wrw_announcement', 'posts_per_page' => 20, 'post_status' => 'publish'));
    if ($news->have_posts()) {
        while ($news->have_posts()) {
            $news->the_post();
            $post_id = get_the_ID();
            $raw_content = get_the_content();
            $rendered = $pd
                ? wp_kses_post($pd->text($raw_content))
                : wp_kses_post(apply_filters('the_content', $raw_content));
            $announcements_data[] = array(
                'id'         => $post_id,
                'title'      => esc_html(get_the_title()),
                'date'       => esc_html(get_the_date()),
                'content'    => $rendered,
                'rawContent' => esc_textarea($raw_content),
                'nonceEdit'  => $can_edit ? wp_create_nonce('edit_announcement_' . $post_id) : '',
            );
        }
        wp_reset_postdata();
    }
    $wrw_wp_data['announcements'] = $announcements_data;
} else {
    $wrw_wp_data['announcements'] = array();
}

get_header(); ?>
<?php get_footer(); ?>
