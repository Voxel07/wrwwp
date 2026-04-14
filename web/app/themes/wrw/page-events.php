<?php
/**
 * Template Name: Events
 */
get_header(); ?>

<main class="site-main">
    <section class="section">
        <div class="container" style="max-width: 1200px; margin: 0 auto;">
            <h1 style="text-align: center; color: var(--color-accent-primary);">Einsatzplan & Events</h1>
            <p style="text-align: center; max-width: 800px; margin: 0 auto 3rem;">
                Hier findet ihr alle anstehenden und vergangenen Operationen. Klickt auf "Teilnehmen", um euch einzutragen.
            </p>
            
            <?php if (current_user_can('edit_posts')) : ?>
                <div style="background: var(--color-bg-darkest); padding: 2rem; border-radius: var(--radius-md); border: 1px dashed var(--color-accent-primary); margin-bottom: 3rem;">
                    <h3 style="margin-top:0; color: var(--color-accent-secondary);">📅 Neues Event erstellen (Admin)</h3>
                    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                        <input type="hidden" name="action" value="wrw_create_event">
                        <?php wp_nonce_field('create_event', 'wrw_event_nonce'); ?>
                        
                        <input type="text" name="event_name" placeholder="Event Name" required style="width: 100%; padding: 10px; margin-bottom: 10px; background: transparent; color: white; border: 1px solid var(--color-border); border-radius: 4px;">
                        <input type="date" name="event_date" required style="width: 48%; padding: 10px; margin-bottom: 10px; background: transparent; color: white; border: 1px solid var(--color-border); border-radius: 4px;">
                        <input type="text" name="event_location" placeholder="Ort / Gelände" required style="width: 48%; padding: 10px; margin-bottom: 10px; background: transparent; color: white; border: 1px solid var(--color-border); border-radius: 4px; float: right;">
                        <textarea name="event_description" rows="4" placeholder="Event Beschreibung..." required style="width: 100%; padding: 10px; margin-bottom: 15px; background: transparent; color: white; border: 1px solid var(--color-border); border-radius: 4px;"></textarea>
                        
                        <button type="submit" style="background: var(--color-accent-secondary); color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%;">Event Veröffentlichen</button>
                    </form>
                </div>
            <?php endif; ?>

            <div class="team-grid">
                <?php
                $events_query = new WP_Query(array(
                    'post_type' => 'wrw_event',
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'order' => 'DESC'
                ));

                if ($events_query->have_posts()) :
                    while ($events_query->have_posts()) : $events_query->the_post();
                        $event_id = get_the_ID();
                        $participants = get_post_meta($event_id, '_wrw_event_participants', true);
                        if (!is_array($participants)) $participants = array();
                        
                        $count = count($participants);
                        $current_user_id = get_current_user_id();
                        $is_registered = in_array($current_user_id, $participants);
                ?>
                        <article class="team-card" style="text-align: left; padding: 2rem; background: var(--color-bg-darkest); border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                            <h3 style="color: var(--color-accent-primary); margin-bottom: 0.5rem;"><?php the_title(); ?></h3>
                            <?php 
                            $evt_date = get_post_meta($event_id, 'wrw_event_date', true);
                            $evt_loc = get_post_meta($event_id, 'wrw_event_location', true);
                            if ($evt_date || $evt_loc) : 
                            ?>
                                <p style="font-size: 0.9rem; color: var(--color-accent-secondary); margin-bottom: 1rem; font-weight: bold;">
                                    <?php echo esc_html($evt_date ? date('d.m.Y', strtotime($evt_date)) : ''); ?> 
                                    <?php echo esc_html($evt_loc ? ' | ' . $evt_loc : ''); ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="content" style="color: var(--color-text-muted); margin-bottom: 2rem;">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <hr style="border: 0; border-top: 1px solid var(--color-border); margin-bottom: 1rem;">
                            <p style="font-weight: bold; font-size: 0.9rem; margin-bottom: 5px;">👥 <?php echo $count; ?> Teilnehmer:</p>
                            <?php if ($count > 0) : ?>
                                <div style="display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 15px;">
                                <?php foreach ($participants as $pid) : 
                                    $p_user = get_userdata($pid);
                                    if ($p_user) :
                                ?>
                                    <span style="background: rgba(255,255,255,0.1); padding: 3px 8px; border-radius: 12px; font-size: 0.8rem; color: #ccc;">
                                        <?php echo esc_html($p_user->display_name); ?>
                                    </span>
                                <?php endif; endforeach; ?>
                                </div>
                            <?php else : ?>
                                <p style="font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 15px;">Noch keine Anmeldungen.</p>
                            <?php endif; ?>
                            
                            <?php if (is_user_logged_in()) : ?>
                                <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST" style="margin-top: 1rem;">
                                    <input type="hidden" name="action" value="wrw_event_register">
                                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                                    <?php wp_nonce_field('register_event_' . $event_id, 'wrw_event_nonce'); ?>
                                    
                                    <?php if ($is_registered) : ?>
                                        <input type="hidden" name="registration_action" value="unregister">
                                        <button type="submit" style="background: transparent; color: var(--color-text-main); border: 1px solid var(--color-border); padding: 8px 16px; border-radius: 4px; cursor: pointer;">Absagen</button>
                                        <span style="color: green; margin-left: 10px; font-weight: bold;">✔ Dabei</span>
                                    <?php else : ?>
                                        <input type="hidden" name="registration_action" value="register">
                                        <button type="submit" style="background: var(--color-accent-secondary); color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: bold;">Teilnehmen</button>
                                    <?php endif; ?>
                                </form>
                            <?php else : ?>
                                <p style="margin-top: 1rem; font-size: 0.8rem; color: var(--color-text-muted);"><em>Logge dich ein um teilzunehmen.</em></p>
                            <?php endif; ?>
                        </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p style="text-align: center; grid-column: 1 / -1;">Aktuell keine Events geplant.</p>';
                endif;
                ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
