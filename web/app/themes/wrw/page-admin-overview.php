<?php
/**
 * Template Name: Admin Roster
 */
get_header();

if (!is_user_logged_in() || !current_user_can('edit_users')) {
    wp_redirect(home_url('/team'));
    exit;
}

$users = get_users();
$mitglieder = get_users(array('role' => 'mitglied'));
?>
<main class="site-main">
    <section class="section">
        <div class="container" style="max-width: 1000px; margin: 0 auto;">
            <h1 style="text-align: center; color: var(--color-accent-primary);">Admin Roster Matrix</h1>
            <p style="text-align: center; color: var(--color-text-muted); margin-bottom: 3rem;">Schnelle Zuweisung von Rängen (Rollen) und Mentoren (für Frischlinge).</p>

            <?php if (isset($_GET['updated']) && $_GET['updated'] == '1') : ?>
                <div style="background: #2E7D32; color: white; padding: 15px; margin-bottom: 2rem; border-radius: 4px; text-align: center; font-weight: bold; border: 1px solid #4CAF50;">
                    Roster erfolgreich aktualisiert.
                </div>
            <?php endif; ?>

            <div style="background: var(--color-bg-darkest); padding: 2rem; border-radius: var(--radius-md); border: 1px solid var(--color-border); overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; color: white;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--color-border);">
                            <th style="padding: 10px;">Name (Callsign)</th>
                            <th style="padding: 10px;">Rang (Rolle)</th>
                            <th style="padding: 10px;">Mentor (📞 bei Frischlingen)</th>
                            <th style="padding: 10px;">Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u) : 
                            $curr_role = 'mitglied';
                            if (in_array('vorstand', (array)$u->roles)) $curr_role = 'vorstand';
                            elseif (in_array('frischling', (array)$u->roles)) $curr_role = 'frischling';
                            
                            $curr_mentor = get_the_author_meta('wrw_mentor_id', $u->ID);
                        ?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                                    <input type="hidden" name="action" value="wrw_update_user_admin">
                                    <input type="hidden" name="target_user_id" value="<?php echo $u->ID; ?>">
                                    <?php wp_nonce_field('update_user_admin_' . $u->ID, 'wrw_admin_nonce'); ?>
                                    
                                    <td style="padding: 10px;"><?php echo esc_html($u->display_name); ?></td>
                                    
                                    <td style="padding: 10px;">
                                        <select name="wrw_role" style="padding: 5px; background: var(--color-bg-dark); color: white; border: 1px solid var(--color-border); border-radius: 4px;">
                                            <option value="vorstand" <?php selected($curr_role, 'vorstand'); ?>>Vorstand</option>
                                            <option value="mitglied" <?php selected($curr_role, 'mitglied'); ?>>Mitglied</option>
                                            <option value="frischling" <?php selected($curr_role, 'frischling'); ?>>Frischling</option>
                                        </select>
                                    </td>

                                    <td style="padding: 10px;">
                                        <select name="wrw_mentor_id" style="padding: 5px; background: var(--color-bg-dark); color: white; border: 1px solid var(--color-border); border-radius: 4px; <?php echo ($curr_role !== 'frischling') ? 'opacity:0.3; pointer-events:none;' : ''; ?>">
                                            <option value="">-- Kein Mentor --</option>
                                            <?php foreach ($mitglieder as $m) : ?>
                                                <option value="<?php echo esc_attr($m->ID); ?>" <?php selected($curr_mentor, $m->ID); ?>><?php echo esc_html($m->display_name); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>

                                    <td style="padding: 10px;">
                                        <button type="submit" style="background: var(--color-accent-secondary); color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer; font-weight: bold;">Speichern</button>
                                    </td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
