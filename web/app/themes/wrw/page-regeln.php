<?php
/**
 * Template Name: Regeln
 */
get_header(); ?>

<main class="site-main">
    <section class="section">
        <div class="container" style="max-width: 900px; margin: 0 auto; background: var(--color-bg-darkest); padding: 3rem; border-radius: var(--radius-lg); box-shadow: 0 4px 10px rgba(0,0,0,0.5);">
            <h1 style="text-align: center; color: var(--color-accent-primary); margin-bottom: 2.5rem; font-size: 2.5rem; text-transform: uppercase;">Infos & Regeln</h1>
            <?php
            $regeln_kurz_file = __DIR__ . '/regeln_kurz.md';
            $regeln_file = __DIR__ . '/regeln.md';
            if (class_exists('Parsedown')) {
                $Parsedown = new Parsedown();
                echo '<div class="accordion">';
                if (file_exists($regeln_kurz_file)) {
                    echo '<div class="accordion-item">';
                    echo '<div class="accordion-header" style="color: var(--color-text-main);" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === \'block\' ? \'none\' : \'block\';">Die Kurzfassung <span>▼</span></div>';
                    echo '<div class="accordion-content" style="display: block;">';
                    echo '<div class="markdown-content" style="color: var(--color-text-main); line-height: 1.6;">';
                    echo $Parsedown->text(file_get_contents($regeln_kurz_file));
                    echo '</div></div></div>';
                }
                
                if (file_exists($regeln_file)) {
                    echo '<div class="accordion-item">';
                    echo '<div class="accordion-header" style="color: var(--color-text-main);" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === \'block\' ? \'none\' : \'block\';">Satzung <span>▼</span></div>';
                    echo '<div class="accordion-content">';
                    echo '<div class="markdown-content" style="color: var(--color-text-main); line-height: 1.6;">';
                    echo $Parsedown->text(file_get_contents($regeln_file));
                    echo '</div></div></div>';
                }
                echo '</div>';
            } else {
                echo '<h1 style="text-align: center; color: var(--color-accent-primary);">Regelwerk / ROE</h1>';
                echo '<p style="text-align: center;">System Error: Parsedown fehlt und wird für die Darstellung benötigt.</p>';
            }
            ?>
        </div>
    </section>
</main>

<style>
.markdown-content h1, .markdown-content h2, .markdown-content h3 {
    color: var(--color-accent-primary);
    margin-top: 2rem;
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--color-border);
    padding-bottom: 0.5rem;
}
.markdown-content p {
    margin-bottom: 1rem;
}
.markdown-content ul, .markdown-content ol {
    margin-bottom: 1.5rem;
    list-style-position: inside;
}
.markdown-content li {
    margin-bottom: 0.5rem;
}
</style>

<?php get_footer(); ?>
