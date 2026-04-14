<?php
/**
 * Template Name: Profile
 */
get_header();

if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(home_url('/profil')));
    exit;
}

$user_id = get_current_user_id();
$user = get_userdata($user_id);
?>
<main class="site-main">
    <section class="section">
        <div class="container" style="max-width: 800px; margin: 0 auto; background: var(--color-bg-darkest); padding: 3rem; border-radius: var(--radius-md); box-shadow: 0 4px 10px rgba(0,0,0,0.5);">
            <h1 style="text-align: center; color: var(--color-accent-primary);">Mein Profil</h1>
            <p style="text-align: center; color: var(--color-text-muted); margin-bottom: 3rem;">Hier kannst du deine Team-Präferenzen völlig unabhängig vom System-Backend verwalten.</p>
            
            <?php if (isset($_GET['updated']) && $_GET['updated'] == '1') : ?>
                <div style="background: #2E7D32; color: white; padding: 15px; margin-bottom: 2rem; border-radius: 4px; text-align: center; font-weight: bold; border: 1px solid #4CAF50;">
                    Profil erfolgreich aktualisiert.
                </div>
            <?php endif; ?>

            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST" enctype="multipart/form-data" style="display: grid; gap: 15px;">
                <input type="hidden" name="action" value="wrw_update_profile">
                <input type="hidden" name="MAX_FILE_SIZE" value="5000000" /> <!-- 5MB Drop Limit -->
                <?php wp_nonce_field('update_profile', 'wrw_profile_nonce'); ?>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="color: var(--color-text-muted); display: block; margin-bottom: 5px;">System Benutzername</label>
                        <input type="text" value="<?php echo esc_attr($user->user_login); ?>" disabled style="width: 100%; padding: 10px; background: rgba(255,255,255,0.05); color: #888; border: 1px solid var(--color-border); border-radius: 4px; cursor: not-allowed;">
                    </div>
                    <div>
                        <label style="color: var(--color-text-muted); display: block; margin-bottom: 5px;">Team Rang (Rolle)</label>
                        <?php
                        $role_name = 'Unbekannt';
                        if (in_array('vorstand', (array)$user->roles)) $role_name = 'Vorstand';
                        elseif (in_array('mitglied', (array)$user->roles)) $role_name = 'Mitglied';
                        elseif (in_array('frischling', (array)$user->roles)) $role_name = 'Frischling';
                        ?>
                        <input type="text" value="<?php echo esc_attr($role_name); ?>" disabled style="width: 100%; padding: 10px; background: rgba(255,255,255,0.05); color: var(--color-accent-secondary); font-weight: bold; border: 1px solid var(--color-border); border-radius: 4px; cursor: not-allowed;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="color: var(--color-text-muted); display: block; margin-bottom: 5px;">Vorname / Callsign</label>
                        <input type="text" name="first_name" value="<?php echo esc_attr($user->first_name); ?>" required style="width: 100%; padding: 10px; background: transparent; color: white; border: 1px solid var(--color-border); border-radius: 4px;">
                    </div>
                    <div>
                        <label style="color: var(--color-text-muted); display: block; margin-bottom: 5px;">Nachname</label>
                        <input type="text" name="last_name" value="<?php echo esc_attr($user->last_name); ?>" required style="width: 100%; padding: 10px; background: transparent; color: white; border: 1px solid var(--color-border); border-radius: 4px;">
                    </div>
                </div>

                <div>
                    <label style="color: var(--color-text-muted); display: block; margin-bottom: 5px;">Kontaktdaten (E-Mail)</label>
                    <input type="email" name="user_email" value="<?php echo esc_attr($user->user_email); ?>" required style="width: 100%; padding: 10px; background: transparent; color: white; border: 1px solid var(--color-border); border-radius: 4px;">
                </div>

                <div style="margin-top: 2rem; border-top: 1px dashed var(--color-border); padding-top: 2rem;">
                    <h3 style="color: var(--color-text-main); margin-top: 0;">Team-Card Felder</h3>
                    <p style="font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 1.5rem;">Diese Daten werden in deinem öffentlichen `<?php echo esc_url(home_url('/team')); ?>` Dossier angezeigt.</p>

                    <div style="margin-bottom: 15px;">
                        <label style="color: var(--color-text-muted); display: block; margin-bottom: 5px;">📷 Profilbild (Avatar) <span style="font-size:0.8rem;">(Lädt über Gravatar, falls leer)</span></label>
                        <input type="file" name="wrw_profile_picture" accept="image/*" style="width: 100%; padding: 10px; background: rgba(0,0,0,0.2); color: white; border: 1px dashed var(--color-border); border-radius: 4px;">
                        <?php 
                        $current_avatar = get_the_author_meta('wrw_profile_picture_url', $user_id);
                        if ($current_avatar) : ?>
                            <div style="margin-top: 10px; font-size: 0.8rem; color: var(--color-accent-secondary);">
                                ✓ Bild erfolgreich hinterlegt.
                            </div>
                        <?php endif; ?>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="color: var(--color-text-muted); display: block; margin-bottom: 5px;">🎂 Geburtstag (für automatisches Ausweisen des Alters)</label>
                        <input type="date" name="wrw_birthday" value="<?php echo esc_attr(get_the_author_meta('wrw_birthday', $user_id)); ?>" required style="width: 100%; padding: 10px; background: transparent; color: white; border: 1px solid var(--color-border); border-radius: 4px;">
                    </div>

                    <div>
                        <label style="color: var(--color-text-muted); display: block; margin-bottom: 5px;">Short Phrase (Persönliches Zitat / Motto)</label>
                        <input type="text" name="wrw_phrase" value="<?php echo esc_attr(get_the_author_meta('wrw_phrase', $user_id)); ?>" style="width: 100%; padding: 10px; background: transparent; color: white; border: 1px solid var(--color-border); border-radius: 4px;" placeholder="z.B. Hit it till it's dead...">
                    </div>
                    
                    <div style="margin-top: 15px;">
                        <label style="color: var(--color-text-muted); display: block; margin-bottom: 5px;">Benachrichtigungspräferenz (Für Ankündigungen)</label>
                        <select name="wrw_notification_pref" style="width: 100%; padding: 10px; background: var(--color-bg-dark); color: white; border: 1px solid var(--color-border); border-radius: 4px;">
                            <?php $pref = get_the_author_meta('wrw_notification_pref', $user_id); ?>
                            <option value="webhook" <?php selected($pref, 'webhook'); ?>>Instant Messenger (Webhook)</option>
                            <option value="mail" <?php selected($pref, 'mail'); ?>>E-Mail via WP-Mail</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 2rem; border-top: 1px solid var(--color-border); padding-top: 2rem;">
                    <h3 style="color: var(--color-accent-secondary); margin-top: 0;">📍 Meine Operationen</h3>
                    <p style="font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 1.5rem;">Hier siehst du alle besuchten Einsätze sortiert nach Jahr.</p>
                    <?php
                    $events_query = new WP_Query(array('post_type' => 'wrw_event', 'posts_per_page' => -1));
                    $attended = array();
                    if ($events_query->have_posts()) {
                        while ($events_query->have_posts()) {
                            $events_query->the_post();
                            $parts = get_post_meta(get_the_ID(), '_wrw_event_participants', true);
                            if (is_array($parts) && in_array($user_id, $parts)) {
                                $y = get_the_time('Y');
                                if (!isset($attended[$y])) $attended[$y] = array();
                                $attended[$y][] = get_the_title();
                            }
                        }
                        wp_reset_postdata();
                    }
                    if (empty($attended)) {
                        echo '<p style="color:#888; font-style: italic;">Bisher an keinen aufgezeichneten Operationen teilgenommen.</p>';
                    } else {
                        krsort($attended); // Newest year first
                        foreach ($attended as $year => $titles) {
                            echo '<div style="margin-bottom: 1rem; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: var(--radius-sm);">';
                            echo '<h4 style="color:white; margin-top:0; margin-bottom:0.5rem; border-bottom:1px solid var(--color-border); padding-bottom:5px;">' . esc_html($year) . '</h4>';
                            echo '<ul style="list-style-type:disc; padding-left:20px; color:var(--color-text-muted); margin-bottom:0;">';
                            foreach ($titles as $t) {
                                echo '<li>' . esc_html($t) . '</li>';
                            }
                            echo '</ul></div>';
                        }
                    }
                    ?>
                </div>
                
                <button type="submit" style="margin-top: 2rem; background: var(--color-accent-primary); color: white; border: none; padding: 15px 30px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 1.1rem; width: 100%;">Präferenzen Aktualisieren</button>
            </form>
        </div>
    </section>
</main>
<?php get_footer(); ?>
