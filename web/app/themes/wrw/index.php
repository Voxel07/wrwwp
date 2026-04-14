<?php
/**
 * Fallback Template
 */
get_header(); ?>

<main class="site-main">
    <section class="section">
        <div class="container">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <?php the_title('<h1 class="entry-title" style="color: var(--color-accent-primary);">', '</h1>'); ?>
                        </header>

                        <div class="entry-content">
                            <?php
                            the_content();
                            ?>
                        </div>
                    </article>
                    <?php
                endwhile;
            else :
                ?>
                <p>No content found.</p>
                <?php
            endif;
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
