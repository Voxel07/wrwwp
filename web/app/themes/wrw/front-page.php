<?php
/**
 * The front page template file.
 *
 * @package wrw
 */

global $wrw_wp_data;
$wrw_wp_data = array( 'page' => 'home' );

// 1. Immich Kiosk URL
$wrw_wp_data['kioskUrl'] = esc_url( get_option( 'immich_kiosk_url', '' ) );

// 2. Upcoming events
$events_data  = array();
$events_query = new WP_Query(
	array(
		'post_type'      => 'wrw_event',
		'posts_per_page' => 3,
		'post_status'    => 'publish',
		'order'          => 'DESC',
	)
);

if ( $events_query->have_posts() ) {
	while ( $events_query->have_posts() ) {
		$events_query->the_post();
		$evt_date      = get_post_meta( get_the_ID(), 'wrw_event_date', true );
		$evt_loc       = get_post_meta( get_the_ID(), 'wrw_event_location', true );
		$events_data[] = array(
			'title'     => esc_html( get_the_title() ),
			'permalink' => esc_url( get_permalink() ),
			'date'      => esc_html( $evt_date ? gmdate( 'd.m.Y', strtotime( $evt_date ) ) : '' ),
			'location'  => esc_html( $evt_loc ),
			'excerpt'   => wp_kses_post( get_the_excerpt() ), // Excerpt may contain simple HTML, React will parse it safely if we want, or strip it.
		);
	}
	wp_reset_postdata();
}
$wrw_wp_data['events'] = $events_data;

// 3. Fields data
$fields_data = array();
$fields_file = get_template_directory() . '/fields.json';
if ( file_exists( $fields_file ) ) {
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$fields_json = json_decode( file_get_contents( $fields_file ), true );
	if ( $fields_json && is_array( $fields_json ) ) {
		// Sanitize fields data.
		foreach ( $fields_json as $field ) {
			$fields_data[] = array(
				'title'       => esc_html( $field['title'] ),
				'description' => esc_html( $field['description'] ),
				'image'       => esc_url( $field['image'] ?? '' ),
				'url'         => esc_url( $field['url'] ?? '' ),
			);
		}
	}
}
$wrw_wp_data['fields'] = $fields_data;

// 4. History text from history.md
$history_html = '';
$history_file = get_template_directory() . '/history.md';
if ( file_exists( $history_file ) ) {
	// Parsedown is available via Composer.
	if ( class_exists( 'Parsedown' ) ) {
		$pd = new Parsedown();
		$pd->setSafeMode( true );
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$raw = file_get_contents( $history_file );
		// Convert ALL CAPS section headers (e.g. "DIE GESCHICHTE:") to h3.
		$raw          = preg_replace( '/^([A-ZÄÖÜ\s]+):$/m', '### $1', $raw );
		$history_html = wp_kses_post( $pd->text( $raw ) );
	} else {
		// Fallback: plain text with <br> line breaks.
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$history_html = nl2br( esc_html( file_get_contents( $history_file ) ) );
	}
}
$wrw_wp_data['historyHtml'] = $history_html;

get_header(); ?>
<?php
get_footer();
