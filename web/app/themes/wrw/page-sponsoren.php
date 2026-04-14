<?php
/**
 * Template Name: Sponsoren
 */
get_header(); ?>

<main class="site-main">
    <section class="section">
        <div class="container">
            <h1 style="text-align: center; color: var(--color-accent-primary);">Unsere Partner & Sponsoren</h1>
            <p style="text-align: center; max-width: 800px; margin: 0 auto 3rem;">
                Wir bedanken uns für den hervorragenden Support durch unsere Ausrüster und Partner in der Region Stuttgart.
            </p>

            <div class="sponsors-grid">
                <!-- Placeholder für Sponsoren-Logos (hier normaler Text/SVG für Demo-Zwecke) -->
                <div class="sponsor-logo">
                    <h3 style="color: var(--color-text-main);">TACTICAL<span style="color: var(--color-accent-primary);">GEAR</span></h3>
                </div>
                <div class="sponsor-logo">
                    <h3 style="color: var(--color-text-main);">BB<span style="color: var(--color-accent-secondary);">AMMO</span></h3>
                </div>
                <div class="sponsor-logo">
                    <h3 style="color: var(--color-text-main);">STUTTGART<span style="color: var(--color-accent-primary);">AIRSOFT</span></h3>
                </div>
                <div class="sponsor-logo">
                    <h3 style="color: var(--color-text-main);">CAMO<span style="color: var(--color-text-muted);">SUPPLY</span></h3>
                </div>
            </div>

            <div style="margin-top: 5rem;">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        the_content();
                    endwhile;
                endif;
                ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
