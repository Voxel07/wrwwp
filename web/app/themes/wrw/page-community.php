<?php
/**
 * Template Name: Forum
 *
 * The wpForo plugin manages its own JS, routing, and markup.
 * This page renders the [wpforo] shortcode directly — NOT via the React SPA's routing.
 * However, the React App STILL MOUNTS on #root to render the navigation (AppBar & Drawer).
 *
 * @package wrw
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php wp_head(); ?>
	<style>
		body {
			background: #0c0f0f;
			color: #eef2f1;
			font-family: "Poppins", "Roboto", "Helvetica", "Arial", sans-serif;
			margin: 0;
			display: flex;
			min-height: 100vh;
			flex-direction: column;
		}

		/* Provide space for the fixed React navigation elements */
		.wrw-main-wrapper {
			display: flex;
			flex-direction: column;
			flex: 1;
			margin-left: 250px; /* matches Drawer width */
			margin-top: 64px;   /* matches AppBar height */
			min-height: calc(100vh - 64px);
		}

		.wrw-main-content {
			flex-grow: 1;
			padding: 2rem;
		}

		.wrw-footer {
			padding: 2rem;
			text-align: center;
			border-top: 1px solid #74808f; /* matches theme.palette.divider */
			background: #0c0f0f;
			margin-top: auto;
		}

		.wrw-footer-text {
			font-size: 0.875rem;
			color: #8d9fa3; /* matches theme.palette.text.secondary */
		}

		/* Responsive: Match MUI's 'md' breakpoint (900px) */
		@media (max-width: 899.95px) {
			.wrw-main-wrapper {
				margin-left: 0; /* Drawer becomes hidden/temporary on mobile */
			}
		}
	</style>
	<?php
	$wrw_wp_data               = array( 'page' => 'forum' );
	$wrw_wp_data['isLoggedIn'] = is_user_logged_in();
	$wrw_wp_data['isAdmin']    = current_user_can( 'edit_users' );
	if ( is_user_logged_in() ) {
		$wrw_user            = wp_get_current_user();
		$wrw_wp_data['user'] = array(
			'id'     => $wrw_user->ID,
			'name'   => $wrw_user->display_name,
			'avatar' => get_avatar_url( $wrw_user->ID, array( 'size' => 45 ) ),
		);
	}
	$wrw_wp_data['urls'] = array(
		'home'          => esc_url( home_url( '/home' ) ),
		'team'          => esc_url( home_url( '/team' ) ),
		'events'        => esc_url( home_url( '/events' ) ),
		'regeln'        => esc_url( home_url( '/regeln' ) ),
		'sponsoren'     => esc_url( home_url( '/sponsoren' ) ),
		'galerie'       => esc_url( home_url( '/galerie' ) ),
		'forum'         => esc_url( home_url( '/community' ) ),
		'announcements' => esc_url( home_url( '/announcements' ) ),
		'admin'         => esc_url( home_url( '/admin-overview' ) ),
		'profil'        => esc_url( home_url( '/profil' ) ),
		'login'         => esc_url( wp_login_url( home_url( '/community' ) ) ),
		'logout'        => esc_url( wp_logout_url( home_url( '/' ) ) ),
	);
	?>
	<script>
		window.__WP_DATA__ = <?php echo wp_json_encode( $wrw_wp_data ); ?>;
	</script>
</head>
<body>

<!-- React will mount here and render ONLY the AppBar and Drawer because renderMainArea={false} -->
<div id="root"></div>

<!-- The actual PHP/wpForo content sits alongside React and handles its own layout -->
<div class="wrw-main-wrapper">
	<main class="wrw-main-content">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				the_content();
		endwhile;
endif;
		?>
	</main>

	<footer class="wrw-footer">
		<span class="wrw-footer-text">
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Wild Rovers Württemberg | Airsoft Stuttgart
		</span>
	</footer>
</div>

<?php wp_footer(); ?>
</body>
</html>
