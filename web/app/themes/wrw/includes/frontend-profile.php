<?php
/**
 * Frontend Profile Handlers.
 *
 * @package wrw
 */

/**
 * Handle Frontend Profile Update.
 */
function wrw_handle_frontend_profile() {
	if ( ! is_user_logged_in() ) {
		wp_die( 'Unauthorized' );
	}
	if ( ! isset( $_POST['wrw_profile_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['wrw_profile_nonce'] ) ), 'update_profile' ) ) {
		wp_die( 'Security check failed.' );
	}

	$user_id = get_current_user_id();

	// Standard fields cleanly extracted from HTTP POST explicitly checking native elements.
	$first_name = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
	$last_name  = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
	$email      = isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';

	// Custom meta payloads.
	$birthday          = isset( $_POST['wrw_birthday'] ) ? sanitize_text_field( wp_unslash( $_POST['wrw_birthday'] ) ) : '';
	$phrase            = isset( $_POST['wrw_phrase'] ) ? sanitize_text_field( wp_unslash( $_POST['wrw_phrase'] ) ) : '';
	$notification_pref = isset( $_POST['wrw_notification_pref'] ) ? sanitize_text_field( wp_unslash( $_POST['wrw_notification_pref'] ) ) : '';

	$user_data = wp_update_user(
		array(
			'ID'         => $user_id,
			'first_name' => $first_name,
			'last_name'  => $last_name,
			'user_email' => $email,
		)
	);

	// Handle Potential Avatar Upload.
	if ( ! empty( $_FILES['wrw_profile_picture']['name'] ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$uploaded_file    = $_FILES['wrw_profile_picture']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$upload_overrides = array( 'test_form' => false );

		$movefile = wp_handle_upload( $uploaded_file, $upload_overrides );

		if ( $movefile && ! isset( $movefile['error'] ) ) {
			// Successfully uploaded, save direct URL onto the User Meta.
			update_user_meta( $user_id, 'wrw_profile_picture_url', $movefile['url'] );
		}
	}

	if ( ! is_wp_error( $user_data ) ) {
		update_user_meta( $user_id, 'wrw_birthday', $birthday );
		update_user_meta( $user_id, 'wrw_phrase', $phrase );
		update_user_meta( $user_id, 'wrw_notification_pref', $notification_pref );
	}

	wp_safe_redirect( add_query_arg( 'updated', '1', home_url( '/profil' ) ) );
	exit;
}
add_action( 'admin_post_wrw_update_profile', 'wrw_handle_frontend_profile' );

/**
 * Admin Overview Processing for Mass Role/Mentor Mapping.
 */
function wrw_handle_admin_overview() {
	if ( ! is_user_logged_in() || ! current_user_can( 'edit_users' ) ) {
		wp_die( 'Unauthorized' );
	}

	$target_user_id = isset( $_POST['target_user_id'] ) ? intval( wp_unslash( $_POST['target_user_id'] ) ) : 0;
	if ( ! isset( $_POST['wrw_admin_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['wrw_admin_nonce'] ) ), 'update_user_admin_' . $target_user_id ) ) {
		wp_die( 'Security check failed.' );
	}

	$new_role   = isset( $_POST['wrw_role'] ) ? sanitize_text_field( wp_unslash( $_POST['wrw_role'] ) ) : '';
	$new_mentor = isset( $_POST['wrw_mentor_id'] ) ? sanitize_text_field( wp_unslash( $_POST['wrw_mentor_id'] ) ) : '';

	// Map strictly to WordPress Role arrays natively.
	$user = new WP_User( $target_user_id );
	$user->set_role( $new_role );

	// Securely tie mentorship logic explicitly to Frischlinge.
	if ( 'frischling' === $new_role ) {
		update_user_meta( $target_user_id, 'wrw_mentor_id', $new_mentor );
	} else {
		delete_user_meta( $target_user_id, 'wrw_mentor_id' );
	}

	wp_safe_redirect( add_query_arg( 'updated', '1', home_url( '/admin-overview' ) ) );
	exit;
}
add_action( 'admin_post_wrw_update_user_admin', 'wrw_handle_admin_overview' );
