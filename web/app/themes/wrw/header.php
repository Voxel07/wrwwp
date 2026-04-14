<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <style>
        .member-dropdown-container {
            position: relative;
            display: inline-block;
        }
        ul.mui-dropdown {
            display: none !important;
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--color-bg-darkest);
            border: 1px solid var(--color-border);
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.5);
            z-index: 1000;
            border-radius: var(--radius-sm, 4px);
            padding: 4px 0;
            margin-top: 5px;
            list-style: none !important;
            flex-direction: column !important;
        }
        ul.mui-dropdown.show-dropdown {
            display: flex !important;
        }
        .mui-dropdown li {
            display: block;
            margin: 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .mui-dropdown li:last-child {
            border-bottom: none;
        }
        .mui-dropdown li a {
            color: var(--color-text-main) !important;
            padding: 8px 12px;
            text-decoration: none;
            display: block;
            text-transform: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .mui-dropdown li a:hover {
            background-color: var(--color-bg-panel);
            color: var(--color-accent-primary) !important;
        }
        .show-dropdown {
            display: block !important;
        }
    </style>
</head>
<body <?php body_class(); ?>>
    <header class="site-header">
        <div class="container header-inner">
            <div class="site-logo" style="margin-bottom: 2rem; text-align: center;">
                <a href="<?php echo esc_url(home_url('/')); ?>" style="display: block;">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/WRW_LOGO_SMALL.png'); ?>" alt="Wild Rovers Württemberg Logo" style="max-width: 100%; height: auto;" />
                </a>
            </div>
            <nav class="main-nav">
                <style>
                    .nav-section { margin-bottom: 1.5rem; width: 100%; border-top: 1px solid var(--color-border); padding-top: 1rem; }
                    .nav-section:first-child { border-top: none; padding-top: 0; }
                    .nav-heading { font-size: 0.75rem; color: var(--color-text-muted); cursor: pointer; text-transform: uppercase; margin-bottom: 0.5rem; display: flex; justify-content: space-between; font-weight: 700; user-select: none; }
                    .nav-heading:hover { color: var(--color-text-main); }
                    .nav-links { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem; transition: max-height 0.3s ease; overflow: hidden; }
                    .nav-section.collapsed .nav-links { display: none; }
                </style>
                
                <div class="nav-section">
                    <div class="nav-heading" onclick="this.parentElement.classList.toggle('collapsed')">Allgemein <span>▼</span></div>
                    <ul class="nav-links">
                        <li><a href="<?php echo esc_url(home_url('/home')); ?>">Home</a></li>
                        <li><a href="<?php echo esc_url(home_url('/team')); ?>">Team</a></li>
                        <li><a href="<?php echo esc_url(home_url('/events')); ?>">Events</a></li>
                        <li><a href="<?php echo esc_url(home_url('/regeln')); ?>">Regeln</a></li>
                        <li><a href="<?php echo esc_url(home_url('/sponsoren')); ?>">Sponsoren</a></li>
                        <li><a href="<?php echo esc_url(home_url('/galerie')); ?>">Galerie</a></li>
                    </ul>
                </div>

                <?php if (is_user_logged_in()) : ?>
                <div class="nav-section">
                    <div class="nav-heading" onclick="this.parentElement.classList.toggle('collapsed')">Mitglieder <span>▼</span></div>
                    <ul class="nav-links">
                        <li><a href="<?php echo esc_url(home_url('/community')); ?>">Forum</a></li>
                        <li><a href="<?php echo esc_url(home_url('/announcements')); ?>">Ankündigungen</a></li>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if (current_user_can('edit_users')) : ?>
                <div class="nav-section">
                    <div class="nav-heading" onclick="this.parentElement.classList.toggle('collapsed')">Admin <span>▼</span></div>
                    <ul class="nav-links">
                        <li><a href="<?php echo esc_url(home_url('/admin-overview')); ?>" style="color:#eef2f1 !important; font-weight:bold;">Admin Overview</a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="top-user-bar" style="position: fixed; top: 1.5rem; right: 2rem; z-index: 1100; display: flex; align-items: center; gap: 1rem;">
        <?php if (is_user_logged_in()) : 
            $current_user = wp_get_current_user();
            $avatar_url = get_avatar_url($current_user->ID, array('size' => 45));
        ?>
            <a href="<?php echo esc_url(home_url('/profil')); ?>" title="Zum Profil" style="display: flex; align-items: center; text-decoration: none; transition: transform var(--transition-fast);">
                <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($current_user->display_name); ?>" style="width: 45px; height: 45px; border-radius: 50%; border: 2px solid var(--color-border);" />
            </a>
            <a href="<?php echo wp_logout_url(home_url('/')); ?>" class="btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem; background-color: var(--color-accent-secondary);">Logout</a>
        <?php else : ?>
            <a href="<?php echo esc_url(wp_login_url(home_url('/'))); ?>" class="btn-primary" style="padding: 0.5rem 1.5rem; font-size: 0.85rem;">Login</a>
        <?php endif; ?>
    </div>
