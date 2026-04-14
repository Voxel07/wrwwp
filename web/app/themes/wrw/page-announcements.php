<?php
/**
 * Template Name: Announcements
 */
get_header();

if (!is_user_logged_in()) {
    echo '<main class="site-main"><section class="section"><div class="container"><p style="text-align: center; padding: 5rem 0;">🔒 Bitte logge dich ein, um teamexterne Ankündigungen zu sehen.</p></div></section></main>';
    get_footer();
    exit;
}
?>
<main class="site-main">
    <section class="section">
        <div class="container" style="max-width: 900px; margin: 0 auto;">
            <h1 style="text-align: center; color: var(--color-accent-primary);">Verbands-Ankündigungen</h1>
            <p style="text-align: center; max-width: 800px; margin: 0 auto 3rem;">
                Interne Updates, Operationsbefehle und Statusberichte exklusiv für registrierte Mitglieder.
            </p>
            
            <?php if (current_user_can('edit_posts')) : ?>
                <div style="background: var(--color-bg-darkest); padding: 2rem; border-radius: var(--radius-md); border: 1px dashed var(--color-accent-primary); margin-bottom: 3rem;">
                    <h3 style="margin-top:0; color: var(--color-accent-secondary);">📣 Neue Ankündigung erstellen (Admin)</h3>
                    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                        <input type="hidden" name="action" value="wrw_create_announcement">
                        <?php wp_nonce_field('create_announcement', 'wrw_announcement_nonce'); ?>
                        
                        <input type="text" name="announcement_title" placeholder="Titel der Ankündigung" required style="width: 100%; padding: 10px; margin-bottom: 10px; background: transparent; color: white; border: 1px solid var(--color-border); border-radius: 4px;">
                        <textarea name="announcement_content" rows="4" placeholder="Nachricht, Briefing..." required style="width: 100%; padding: 10px; margin-bottom: 15px; background: transparent; color: white; border: 1px solid var(--color-border); border-radius: 4px;"></textarea>
                        
                        <button type="submit" style="background: var(--color-accent-secondary); color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%;">Veröffentlichen & Broadcast</button>
                    </form>
                    <!-- Simplistic Form Title & Text -->
                    <p style="margin-top: 1rem; font-size: 0.75rem; text-align: center; color: var(--color-text-muted);">
                        Admin Hinweis: Setze <code>WP_OPTION_WEBHOOK_URL</code> in der `.env`, damit die Broadcasts weitergeleitet werden!
                    </p>
                </div>
            <?php endif; ?>

            <div class="announcements-list">
                <?php
                $news = new WP_Query(array('post_type' => 'wrw_announcement', 'posts_per_page' => 20));
                if ($news->have_posts()) :
                    while ($news->have_posts()) : $news->the_post();
                ?>
                    <article style="background: var(--color-bg-dark); padding: 2rem; border-radius: var(--radius-md); border: 1px solid var(--color-border); margin-bottom: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                        <h2 style="margin-top: 0; color: var(--color-text-main); margin-bottom: 0.5rem;"><?php the_title(); ?></h2>
                        <div style="font-size: 0.8rem; color: var(--color-accent-secondary); margin-bottom: 1rem; font-weight: bold;">
                            🕒 Veröffentlicht am <?php echo get_the_date(); ?>
                        </div>
                        <div class="content" style="color: var(--color-text-main); line-height: 1.6;">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p style="text-align: center; color: var(--color-text-muted); background: var(--color-bg-darkest); padding: 2rem; border-radius: var(--radius-md);">Bisher keine Ankündigungen vorhanden.</p>';
                endif;
                ?>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>
