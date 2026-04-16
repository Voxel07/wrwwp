<?php
/**
 * Template Name: Forum
 *
 * The wpForo plugin manages its own JS, routing, and markup.
 * This page renders the [wpforo] shortcode directly — NOT via the React SPA.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
    <style>
        :root {
            --color-bg: #0c0f0f;
            --color-bg-dark: #1a2022;
            --color-border: #74808f;
            --color-text: #eef2f1;
            --color-muted: #8d9fa3;
            --color-accent: #b4c3c0;
        }
        * { box-sizing: border-box; }
        body {
            background: var(--color-bg);
            color: var(--color-text);
            font-family: 'Poppins', 'Roboto', sans-serif;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        .forum-sidebar {
            width: 250px;
            min-height: 100vh;
            background: var(--color-bg-dark);
            border-right: 1px solid var(--color-border);
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
        }
        .forum-sidebar a {
            color: var(--color-text);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }
        .forum-sidebar a:hover { background: rgba(255,255,255,0.06); }
        .forum-sidebar .site-title {
            color: var(--color-accent);
            font-weight: 700;
            font-size: 1.1rem;
            display: block;
            margin-bottom: 0.25rem;
        }
        .forum-sidebar .site-sub {
            color: var(--color-muted);
            font-size: 0.75rem;
            display: block;
            margin-bottom: 1.5rem;
        }
        .forum-sidebar hr { border-color: var(--color-border); margin: 1rem 0; }
        .forum-main {
            margin-left: 250px;
            padding: 2rem;
            flex: 1;
            min-height: 100vh;
        }
        @media (max-width: 768px) {
            .forum-sidebar { display: none; }
            .forum-main { margin-left: 0; }
        }
    </style>
</head>
<body>

<nav class="forum-sidebar">
    <a href="<?php echo esc_url(home_url('/home')); ?>" style="flex-direction:column;align-items:flex-start;padding-left:0;">
        <span class="site-title">Wild Rovers</span>
        <span class="site-sub">Württemberg</span>
    </a>
    <hr>
    <a href="<?php echo esc_url(home_url('/home')); ?>">🏠 Home</a>
    <a href="<?php echo esc_url(home_url('/team')); ?>">👥 Team</a>
    <a href="<?php echo esc_url(home_url('/events')); ?>">📅 Events</a>
    <a href="<?php echo esc_url(home_url('/regeln')); ?>">⚖️ Regeln</a>
    <a href="<?php echo esc_url(home_url('/sponsoren')); ?>">🤝 Sponsoren</a>
    <a href="<?php echo esc_url(home_url('/galerie')); ?>">🖼️ Galerie</a>
    <?php if (is_user_logged_in()) : ?>
    <hr>
    <small style="color:var(--color-muted);padding:0.5rem 0.75rem;font-weight:bold;text-transform:uppercase;font-size:0.7rem;">Mitglieder</small>
    <a href="<?php echo esc_url(home_url('/community')); ?>" style="color:var(--color-accent);font-weight:600;">💬 Forum</a>
    <a href="<?php echo esc_url(home_url('/announcements')); ?>">📣 Ankündigungen</a>
    <?php endif; ?>
    <?php if (current_user_can('edit_users')) : ?>
    <hr>
    <a href="<?php echo esc_url(home_url('/admin-overview')); ?>" style="color:#f28b82;">🔧 Admin</a>
    <?php endif; ?>
    <div style="flex:1;"></div>
    <hr>
    <?php if (is_user_logged_in()) :
        $cu = wp_get_current_user(); ?>
    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.5rem 0.75rem;">
        <?php echo get_avatar($cu->ID, 36, '', '', ['style' => 'border-radius:50%;']); ?>
        <span style="font-size:0.85rem;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo esc_html($cu->display_name); ?></span>
        <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" style="color:#f28b82;font-size:0.8rem;">↩</a>
    </div>
    <?php else : ?>
    <a href="<?php echo esc_url(wp_login_url(home_url('/community'))); ?>" style="background:#4a565b;justify-content:center;font-weight:600;">🔑 Login</a>
    <?php endif; ?>
</nav>

<main class="forum-main">
    <?php if (have_posts()) : while (have_posts()) : the_post();
        the_content();
    endwhile; endif; ?>
</main>

<?php wp_footer(); ?>
</body>
</html>
