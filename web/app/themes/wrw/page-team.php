<?php
/**
 * Template Name: Team
 */
get_header();

// Fetch all WP Users
$users = get_users();

$roles = array(
    'vorstand' => 'Vorstand',
    'mitglied' => 'Mitglieder',
    'frischling' => 'Frischlinge'
);

// Group members by role
$grouped_members = array(
    'vorstand' => array(),
    'mitglied' => array(),
    'frischling' => array()
);

foreach ($users as $user) {
    $matched_role = 'mitglied'; // fallback
    foreach ($user->roles as $role) {
        if (array_key_exists($role, $grouped_members)) {
            $matched_role = $role;
            break;
        }
    }
    if (array_key_exists($matched_role, $grouped_members)) {
        $grouped_members[$matched_role][] = $user;
    }
}

// Prefetch all events for performance
$all_events = get_posts(array(
    'post_type' => 'wrw_event',
    'posts_per_page' => -1,
    'post_status' => 'publish'
));

function wrw_calculate_duration($join_date, $registered_date = null) {
    if (!$join_date && !$registered_date) return '';
    $anchor = $join_date ? $join_date : $registered_date;
    $joined = new DateTime($anchor);
    $now = new DateTime();
    $diff = $now->diff($joined);
    $months = $diff->m + ($diff->y * 12);
    if ($months < 1) return 'Neu dabei';
    if ($diff->y >= 1) return "Dabei seit {$diff->y} Jahr" . ($diff->y > 1 ? 'en' : '') . ($diff->m > 0 ? " und {$diff->m} Monat" . ($diff->m > 1 ? 'en' : '') : '');
    return "Dabei seit {$diff->m} Monat" . ($diff->m > 1 ? 'en' : '');
}

function wrw_calculate_age($birthday) {
    if (!$birthday) return '';
    $bday = new DateTime($birthday);
    $now = new DateTime();
    return $now->diff($bday)->y . ' Jahre alt';
}

function wrw_is_birthday($birthday) {
    if (!$birthday) return false;
    try {
        $bday = new DateTime($birthday);
        $now_md = wp_date('m-d');
        return ($bday->format('m-d') === $now_md);
    } catch (Exception $e) {
        return false;
    }
}
?>

<style>
.ribbon {
    position: absolute;
    top: 15px;
    right: -35px;
    background: var(--color-accent-secondary);
    color: #fff;
    padding: 5px 40px;
    font-size: 0.8rem;
    font-weight: bold;
    transform: rotate(45deg);
    box-shadow: 0 2px 4px rgba(0,0,0,0.5);
    z-index: 10;
}
.team-card {
    position: relative;
    overflow: hidden;
    transform-style: preserve-3d;
}
.birthday-cake {
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: 1.0rem;
    z-index: 10;
}
.member-duration {
    font-size: 0.8rem;
    color: var(--color-text-muted);
    margin-top: 10px;
    border-top: 1px solid var(--color-border);
    padding-top: 5px;
}
</style>

<main class="site-main">
    <section class="section">
        <div class="container">
            <h1 style="text-align: center; color: var(--color-accent-primary);">Das Team</h1>
            <p style="text-align: center; max-width: 800px; margin: 0 auto 3rem;">
                Die Wild Rovers Württemberg sind ein engagiertes Airsoft-Team aus dem Großraum Stuttgart.
            </p>

            <?php foreach ($roles as $role_key => $role_title) : ?>
                <?php if (!empty($grouped_members[$role_key])) : ?>
                    <h2 style="color: var(--color-text-main); border-bottom: 2px solid var(--color-accent-primary); padding-bottom: 0.5rem; margin-top: 4rem;">
                        <?php echo esc_html($role_title); ?>
                    </h2>
                    <div class="team-grid">
                        <?php foreach ($grouped_members[$role_key] as $member) : 
                            $ribbon = get_the_author_meta('wrw_ribbon', $member->ID);
                            $joined_date = get_the_author_meta('wrw_joined_date', $member->ID);
                            $birthday = get_the_author_meta('wrw_birthday', $member->ID);
                            $phrase = get_the_author_meta('wrw_phrase', $member->ID);
                            $mentor_id = get_the_author_meta('wrw_mentor_id', $member->ID);
                            $avatar_url = get_avatar_url($member->ID, array('size' => 150));
                        ?>
                            <div class="team-card" data-tilt data-tilt-glare data-tilt-max-glare="0.5" data-tilt-max="15">
                                <?php if ($ribbon) : ?>
                                    <div class="ribbon"><?php echo esc_html($ribbon); ?></div>
                                <?php endif; ?>
                                
                                <?php if (wrw_is_birthday($birthday)) : ?>
                                    <div class="birthday-cake" title="Hat heute Geburtstag!">🎂</div>
                                <?php endif; ?>
                                
                                <div class="team-avatar" style="position: relative;">
                                    <?php 
                                    $custom_avatar = get_the_author_meta('wrw_profile_picture_url', $member->ID);
                                    if ($custom_avatar) : ?>
                                        <img src="<?php echo esc_url($custom_avatar); ?>" alt="<?php echo esc_attr($member->display_name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php elseif ($avatar_url) : ?>
                                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($member->display_name); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else : ?>
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-text-muted);"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <?php endif; ?>
                                </div>
                                
                                <h3><?php echo esc_html($member->display_name); ?></h3>
                                
                                <?php if ($role_key === 'frischling' && $mentor_id) : 
                                    $mentor_obj = get_userdata($mentor_id);
                                    if ($mentor_obj) :
                                ?>
                                    <p style="color: var(--color-accent-secondary); font-size: 0.8rem; font-weight: bold; margin-top: -5px; margin-bottom: 10px;">
                                        👉 Mentor: <?php echo esc_html($mentor_obj->display_name); ?>
                                    </p>
                                <?php endif; endif; ?>
                                
                                <?php if ($birthday) : ?>
                                    <p style="color: var(--color-accent-primary); font-size: 0.9rem; font-weight: bold;">
                                        <?php echo esc_html(wrw_calculate_age($birthday)); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ($phrase) : ?>
                                    <p style="color: var(--color-text-main); font-style: italic; margin-top: 0.5rem; font-size: 0.9rem;">
                                        "<?php echo esc_html($phrase); ?>"
                                    </p>
                                <?php endif; ?>
                                
                                <div class="member-duration">
                                    <?php if ($joined_date) : ?>
                                        <?php echo esc_html(wrw_calculate_duration($joined_date)); ?>
                                        <br><span style="font-size: 0.7rem; opacity: 0.5;">(Beigetreten: <?php echo date('d.m.Y', strtotime($joined_date)); ?>)</span>
                                    <?php else : ?>
                                        <?php echo esc_html(wrw_calculate_duration('', $member->user_registered)); ?>
                                        <br><span style="font-size: 0.7rem; opacity: 0.5;">(Erfasst: <?php echo wp_date('d.m.Y H:i', strtotime($member->user_registered)); ?> Uhr)</span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php
                                // Bidirectional Mentor check: Are there explicit Frischlinge assigned to this Mitglied?
                                if ($role_key === 'mitglied') {
                                    $mentored_recruits = array();
                                    if (!empty($grouped_members['frischling'])) {
                                        foreach ($grouped_members['frischling'] as $f) {
                                            if (get_the_author_meta('wrw_mentor_id', $f->ID) == $member->ID) {
                                                $mentored_recruits[] = $f->display_name;
                                            }
                                        }
                                    }
                                    if (!empty($mentored_recruits)) {
                                        echo '<div style="margin-top: 10px; padding: 10px; background: rgba(var(--color-accent-secondary-rgb, 100,200,100), 0.1); border-left: 3px solid var(--color-accent-secondary); border-radius: 0 4px 4px 0;">';
                                        echo '<p style="font-size: 0.8rem; font-weight: bold; margin: 0 0 5px 0; color: var(--color-text-main);">👨‍🏫 Mentoriert:</p>';
                                        echo '<ul style="margin: 0; padding-left: 15px; font-size: 0.8rem; color: var(--color-text-muted);">';
                                        foreach ($mentored_recruits as $m_name) {
                                            echo '<li>' . esc_html($m_name) . '</li>';
                                        }
                                        echo '</ul></div>';
                                    }
                                }
                                ?>
                                
                                <?php
                                $visited = 0;
                                foreach ($all_events as $evt) {
                                    $parts = get_post_meta($evt->ID, '_wrw_event_participants', true);
                                    if (is_array($parts) && in_array($member->ID, $parts)) {
                                        $visited++;
                                    }
                                }
                                if ($visited > 0) :
                                ?>
                                    <div style="margin-top: 10px; font-size: 0.8rem; background: rgba(0,0,0,0.3); padding: 5px; border-radius: 4px; border: 1px dashed var(--color-border);">
                                        🎖️ Operationen besucht: <span style="font-weight: bold; color: var(--color-accent-secondary);"><?php echo $visited; ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <?php if (empty($grouped_members['vorstand']) && empty($grouped_members['mitglied']) && empty($grouped_members['frischling'])) : ?>
                <p style="text-align: center; color: var(--color-text-muted); padding: 3rem;">Keine echten Benutzer gefunden. Admins: Bitte Benutzer im Dashboard anlegen!</p>
            <?php endif; ?>
            
        </div>
    </section>
</main>

<script>
    // Initialize VanillaTilt if not automatically initialized
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof VanillaTilt !== 'undefined') {
            VanillaTilt.init(document.querySelectorAll(".team-card"));
        }
    });
</script>

<?php get_footer(); ?>
