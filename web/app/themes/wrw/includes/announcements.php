<?php
/**
 * Announcements Custom Post Type and Handlers.
 *
 * @package wrw
 */

/**
 * Register Announcements Custom Post Type.
 */
function wrw_register_announcements_cpt() {
	$args = array(
		'labels'       => array( 'name' => 'Announcements' ),
		'public'       => true,
		'has_archive'  => true,
		'supports'     => array( 'title', 'editor' ),
		'show_in_rest' => true,
	);
	register_post_type( 'wrw_announcement', $args );
}
add_action( 'init', 'wrw_register_announcements_cpt' );

/**
 * Handle Frontend Form Submission.
 */
function wrw_handle_announcement_submission() {
	if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) ) {
		wp_die( 'Unauthorized' );
	}
	if ( ! isset( $_POST['wrw_announcement_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['wrw_announcement_nonce'] ) ), 'create_announcement' ) ) {
		wp_die( 'Security check failed.' );
	}

	$title   = isset( $_POST['announcement_title'] ) ? sanitize_text_field( wp_unslash( $_POST['announcement_title'] ) ) : '';
	$content = isset( $_POST['announcement_content'] ) ? wp_kses_post( wp_unslash( $_POST['announcement_content'] ) ) : '';

	$post_id = wp_insert_post(
		array(
			'post_title'   => $title,
			'post_content' => $content,
			'post_status'  => 'publish',
			'post_type'    => 'wrw_announcement',
		)
	);

	if ( ! is_wp_error( $post_id ) ) {
		$users             = get_users();
		$webhook_triggered = false;
		$webhook_url       = get_option( 'webhook_url' );

		$message_text = 'Neue Ankündigung: ' . $title . "\nLink: " . get_permalink( $post_id );

		foreach ( $users as $u ) {
			$pref = get_user_meta( $u->ID, 'wrw_notification_pref', true );
			if ( 'mail' === $pref || empty( $pref ) ) {
				wp_mail( $u->user_email, 'WRW Ankündigung: ' . $title, $message_text );
			} elseif ( 'webhook' === $pref ) {
				$webhook_triggered = true;
			}
		}

		if ( $webhook_triggered && $webhook_url ) {
			wp_remote_post(
				$webhook_url,
				array(
					'headers'     => array( 'Content-Type' => 'application/json; charset=utf-8' ),
					'body'        => wp_json_encode(
						array(
							'text'    => $message_text,
							'content' => $message_text,
						)
					),
					'method'      => 'POST',
					'data_format' => 'body',
					'blocking'    => false,
				)
			);
		}
	}
	wp_safe_redirect( home_url( '/announcements' ) );
	exit;
}
add_action( 'admin_post_wrw_create_announcement', 'wrw_handle_announcement_submission' );

/**
 * Handle Edit Announcement Submission.
 */
function wrw_handle_announcement_edit() {
	if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) ) {
		wp_die( 'Unauthorized' );
	}

	$post_id = intval( $_POST['post_id'] ?? 0 );
	if ( ! $post_id ) {
		wp_die( 'Invalid post.' );
	}

	if ( ! isset( $_POST['wrw_announcement_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['wrw_announcement_nonce'] ) ), 'edit_announcement_' . $post_id ) ) {
		wp_die( 'Security check failed.' );
	}

	$title   = isset( $_POST['announcement_title'] ) ? sanitize_text_field( wp_unslash( $_POST['announcement_title'] ) ) : '';
	$content = isset( $_POST['announcement_content'] ) ? sanitize_textarea_field( wp_unslash( $_POST['announcement_content'] ) ) : ''; // store raw markdown.

	wp_update_post(
		array(
			'ID'           => $post_id,
			'post_title'   => $title,
			'post_content' => $content,
		)
	);

	wp_safe_redirect( home_url( '/announcements' ) );
	exit;
}
add_action( 'admin_post_wrw_edit_announcement', 'wrw_handle_announcement_edit' );
