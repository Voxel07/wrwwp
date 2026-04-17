<?php
/**
 * Template Name: Regeln
 *
 * @package wrw
 */

global $wrw_wp_data;
$wrw_wp_data = array( 'page' => 'regeln' );

$html_kurz = '<p>Parsedown missing or file not found.</p>';
$html_lang = '';

if ( class_exists( 'Parsedown' ) ) {
	$parsedown        = new Parsedown();
	$regeln_kurz_file = __DIR__ . '/regeln_kurz.md';
	if ( file_exists( $regeln_kurz_file ) ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$html_kurz = wp_kses_post( $parsedown->text( file_get_contents( $regeln_kurz_file ) ) );
	}

	$regeln_file = __DIR__ . '/regeln.md';
	if ( file_exists( $regeln_file ) ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$html_lang = wp_kses_post( $parsedown->text( file_get_contents( $regeln_file ) ) );
	}
}

$wrw_wp_data['rules'] = array(
	'short' => $html_kurz,
	'full'  => $html_lang,
);

get_header(); ?>
<?php
get_footer();
