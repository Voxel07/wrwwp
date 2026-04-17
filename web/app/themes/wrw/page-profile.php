<?php
/**
 * Template Name: Profile
 *
 * @package wrw
 */

global $wrw_wp_data;
$wrw_wp_data = array( 'page' => 'profil' );

if ( ! is_user_logged_in() ) {
	wp_safe_redirect( wp_login_url( home_url( '/profil' ) ) );
	exit;
}

$user_id       = get_current_user_id();
$user          = get_userdata( $user_id );
$admin_url     = esc_url( admin_url( 'admin-post.php' ) );
$profile_nonce = wp_create_nonce( 'update_profile' );

// Determine role label.
$role_label = 'Unbekannt';
if ( in_array( 'vorstand', (array) $user->roles, true ) ) {
	$role_label = 'Vorstand';
} elseif ( in_array( 'mitglied', (array) $user->roles, true ) ) {
	$role_label = 'Mitglied';
} elseif ( in_array( 'frischling', (array) $user->roles, true ) ) {
	$role_label = 'Frischling';
}

// Attended events.
$events_query = new WP_Query(
	array(
		'post_type'      => 'wrw_event',
		'posts_per_page' => -1,
	)
);
$attended     = array();
if ( $events_query->have_posts() ) {
	while ( $events_query->have_posts() ) {
		$events_query->the_post();
		$parts = get_post_meta( get_the_ID(), '_wrw_event_participants', true );
		if ( is_array( $parts ) && in_array( $user_id, $parts, true ) ) {
			$wrw_year = get_the_time( 'Y' );
			if ( ! isset( $attended[ $wrw_year ] ) ) {
				$attended[ $wrw_year ] = array();
			}
			$attended[ $wrw_year ][] = esc_html( get_the_title() );
		}
	}
	wp_reset_postdata();
}
krsort( $attended );

$wrw_wp_data['profileData']  = array(
	'userId'       => $user_id,
	'login'        => esc_html( $user->user_login ),
	'firstName'    => esc_html( $user->first_name ),
	'lastName'     => esc_html( $user->last_name ),
	'email'        => esc_html( $user->user_email ),
	'roleLabel'    => $role_label,
	'birthday'     => esc_html( get_user_meta( $user_id, 'wrw_birthday', true ) ),
	'phrase'       => esc_html( get_user_meta( $user_id, 'wrw_phrase', true ) ),
	'notifPref'    => esc_html( get_user_meta( $user_id, 'wrw_notification_pref', true ) ),
	'hasAvatar'    => (bool) get_user_meta( $user_id, 'wrw_profile_picture_url', true ),
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	'updatedParam' => isset( $_GET['updated'] ) && '1' === $_GET['updated'],
	'teamUrl'      => esc_url( home_url( '/team' ) ),
);
$wrw_wp_data['adminPostUrl'] = $admin_url;
$wrw_wp_data['profileNonce'] = $profile_nonce;
$wrw_wp_data['attendedOps']  = $attended;

get_header(); ?>
<?php
get_footer();
