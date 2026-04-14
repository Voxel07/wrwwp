<?php
/**
 * Template Name: Galerie
 */
get_header(); 

// Load configurable URLs via our .ENV overriding system or falback to localhost patterns
$proxy_url = get_option('immich_proxy_url', '');
$drop_url = get_option('immich_drop_url', '');

?>

<main class="site-main">
    <section class="section" style="padding-top: 2rem;">
        <div class="container" style="max-width: 1600px;">
            <h1 style="text-align: center; color: var(--color-accent-primary);">Einsatz-Galerie</h1>
            <p style="text-align: center; max-width: 800px; margin: 0 auto 1rem;">
                Impressionen und Bilder unserer vergangenen Operationen in einer großen Sammlung.
            </p>

            <div style="text-align: center; margin-bottom: 2rem;">
                <?php if (is_user_logged_in()) : ?>
                    <button onclick="document.getElementById('upload-container').classList.toggle('hidden')" style="background: var(--color-accent-secondary); color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                        📸 Neue Bilder Hochladen
                    </button>
                <?php else : ?>
                    <p style="color: var(--color-text-muted); font-style: italic; border: 1px solid var(--color-border); padding: 10px; display: inline-block; border-radius: 5px; background: rgba(0,0,0,0.2);">
                        🔒 Bitte im Forum / per OpenID einloggen, um Bilder hochzuladen.
                    </p>
                <?php endif; ?>
            </div>

            <?php if (is_user_logged_in()) : ?>
                <!-- Upload Drop Area (Hidden by default) -->
                <div id="upload-container" class="hidden" style="width: 100%; height: 500px; border-radius: var(--radius-md); overflow: hidden; border: 2px dashed var(--color-accent-primary); background: var(--color-bg-darkest); margin-bottom: 3rem;">
                    <iframe src="<?php echo esc_url($drop_url); ?>" 
                            style="width: 100%; height: 100%; border: none;"
                            allowfullscreen
                            title="Immich Drop Upload"></iframe>
                </div>
            <?php endif; ?>

            <h2 style="color: var(--color-text-main); border-bottom: 2px solid var(--color-accent-primary); padding-bottom: 0.5rem; margin-top: 2rem; margin-bottom: 1.5rem;">
                Galerie
            </h2>

            <!-- Albums Viewer -->
            <div style="width: 100%; height: 80vh; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--color-border); background: var(--color-bg-darkest);">
                <iframe src="<?php echo esc_url($proxy_url); ?>" 
                        style="width: 100%; height: 100%; border: none;"
                        allowfullscreen
                        title="Immich Public Proxy Gallery"></iframe>
            </div>
        </div>
    </section>
</main>

<style>
    .hidden { display: none !important; }
</style>

<?php get_footer(); ?>
