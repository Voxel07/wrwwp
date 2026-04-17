<?php
/**
 * Template Name: Events
 *
 * @package wrw
 */

global $wrw_wp_data;
$wrw_wp_data = array( 'page' => 'events' );

$wrw_wp_data['canEditEvents']    = current_user_can( 'edit_posts' );
$wrw_wp_data['adminPostUrl']     = esc_url( admin_url( 'admin-post.php' ) );
$wrw_wp_data['nonceCreateEvent'] = wp_create_nonce( 'create_event' );
$wrw_wp_data['isLoggedIn']       = is_user_logged_in();
$current_user_id                 = get_current_user_id();

$events_data  = array();
$events_query = new WP_Query(
	array(
		'post_type'      => 'wrw_event',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'order'          => 'DESC',
	)
);

if ( $events_query->have_posts() ) {
	while ( $events_query->have_posts() ) {
		$events_query->the_post();
		$event_id = get_the_ID();

		$participants = get_post_meta( $event_id, '_wrw_event_participants', true );
		if ( ! is_array( $participants ) ) {
			$participants = array();
		}

		$participant_names = array();
		foreach ( $participants as $pid ) {
			$p_user = get_userdata( $pid );
			if ( $p_user ) {
				$participant_names[] = esc_html( $p_user->display_name );
			}
		}

		$evt_date = get_post_meta( $event_id, 'wrw_event_date', true );
		$evt_loc  = get_post_meta( $event_id, 'wrw_event_location', true );

		$events_data[] = array(
			'id'               => $event_id,
			'title'            => esc_html( get_the_title() ),
			'date'             => esc_html( $evt_date ? gmdate( 'd.m.Y', strtotime( $evt_date ) ) : '' ),
			'location'         => esc_html( $evt_loc ),
			'excerpt'          => wp_kses_post( get_the_excerpt() ),
			'participants'     => $participant_names,
			'participantCount' => count( $participants ),
			'isRegistered'     => in_array( $current_user_id, $participants, true ),
			'nonceRegister'    => wp_create_nonce( 'register_event_' . $event_id ),
		);
	}
	wp_reset_postdata();
}

$wrw_wp_data['eventList'] = $events_data;

get_header(); ?>
<?php
get_footer();
