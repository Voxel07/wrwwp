<?php get_header(); ?>

<main class="site-main">
    <section class="hero-section" style="background: transparent;">
        <div class="container">
            <h2 class="hero-title">Willkommen bei den<br>Wild Rovers Württemberg</h2>
            <p class="hero-subtitle">Airsoft Team aus Stuttgart und Umgebung. Disziplin, Taktik, und Kameradschaft.</p>
            <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
                <a href="/regeln" class="btn-primary">Regeln</a>
                <button id="formbricks-join-btn" class="btn-primary" style="background-color: var(--color-accent-secondary);">Join the Team</button>
            </div>
        </div>
    </section>

    <section class="section history-section">
        <div class="container" style="max-width: 900px; text-align: center;">
            <h2 style="color: var(--color-accent-primary); margin-bottom: 1rem;">Die Geschichte</h2>
            <p style="margin-bottom: 1.5rem; color: var(--color-text-main);">
                Das Team Wild Rovers Württemberg gibt es jetzt schon seit Mitte 2006 damals als TSAT – BW gegründet. Unter diesem Namen habt ihr uns bestimmt auch schonmal angetroffen. Nach einem Jahrzehnt Airsoft auf den verschiedensten Events und Spielfeldern hat das sich das Team stark verändert. Auf der einen Seite ist das Team stark gewachsen und hat viele junge Mitglieder gewonnen. Auf der anderen Seite hat ein Großteil des Gründungsteams sich anderen Hobbys zugewendet. Um nicht in der Vergangenheit hängenzubleiben, wurde es Zeit, den alten Relikten Lebewohl zu sagen.
            </p>
            <p style="margin-bottom: 2.5rem; color: var(--color-text-main);">
                Ein neuer Name, ein neues Logo und eine Fusion mit unseren langjährigen Freunden und Partnerteam Legion Esslingen 1 später, waren die Wild Rovers geboren.
            </p>
            
            <h2 style="color: var(--color-accent-primary); margin-bottom: 1rem;">Aktuelles</h2>
            <p style="margin-bottom: 1.5rem; color: var(--color-text-main);">
                Aktuell haben wir 30 Mitglieder und 3 Frischlinge (Anwärter). Wir kommen aus den unterschiedlichsten Ecken aus Deutschland. Der Hauptteil des Teams ist aber im Großraum Esslingen / Stuttgart zu finden. In den letzten Jahren haben wir viele neue junge Mitglieder dazugewonnen, dennoch ist von 18 bis 34 Jahren alles dabei.
            </p>
            <p style="color: var(--color-text-main);">
                Solltet ihr ein Team suchen oder ihr wollt uns näher kennenlernen, findet ihr alle weiteren Informationen unter dem Reiter Infos/Regeln.
            </p>
        </div>
    </section>
    <section class="section">
        <div class="container" style="max-width: 1500px;">
            <h2 style="text-align: center; color: var(--color-accent-primary); margin-bottom: 2rem;">Zufällige Impressionen</h2>
            <?php $kiosk_url = get_option('immich_kiosk_url', ''); ?>
            <div style="width: 100%; height: 60vh; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--color-border); background: var(--color-bg-darkest); box-shadow: 0 5px 15px rgba(0,0,0,0.5);">
                <iframe src="<?php echo esc_url($kiosk_url); ?>" 
                        style="width: 100%; height: 100%; border: none;"
                        allowfullscreen
                        title="Immich Kiosk Random Viewer"></iframe>
            </div>
        </div>
    </section>

    <section class="section events-section">
        <div class="container" style="text-align: center;">
            <h2 style="color: var(--color-accent-primary); margin-bottom: 2rem;">Anstehende Events</h2>
            
            <div class="team-grid" style="margin-bottom: 3rem;">
                <?php
                $events_query = new WP_Query(array(
                    'post_type' => 'wrw_event',
                    'posts_per_page' => 3,
                    'post_status' => 'publish',
                    'order' => 'DESC'
                ));

                if ($events_query->have_posts()) :
                    while ($events_query->have_posts()) : $events_query->the_post();
                        $evt_date = get_post_meta(get_the_ID(), 'wrw_event_date', true);
                        $evt_loc = get_post_meta(get_the_ID(), 'wrw_event_location', true);
                ?>
                        <article class="team-card" style="text-align: left; padding: 2rem; background: var(--color-bg-panel); border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                            <h3 style="color: var(--color-text-main); margin-bottom: 0.5rem; font-size: 1.2rem;"><a href="<?php the_permalink(); ?>" style="color: inherit;"><?php the_title(); ?></a></h3>
                            <?php if ($evt_date || $evt_loc) : ?>
                                <p style="font-size: 0.85rem; color: var(--color-accent-secondary); margin-bottom: 1rem; font-weight: bold;">
                                    <?php echo esc_html($evt_date ? date('d.m.Y', strtotime($evt_date)) : ''); ?> 
                                    <?php echo esc_html($evt_loc ? ' | ' . $evt_loc : ''); ?>
                                </p>
                            <?php endif; ?>
                            <div class="content" style="color: var(--color-text-muted); font-size: 0.9rem;">
                                <?php the_excerpt(); ?>
                            </div>
                        </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p style="grid-column: 1 / -1; color: var(--color-text-muted);">Aktuell keine Events geplant.</p>';
                endif;
                ?>
            </div>

            <a href="/events" class="btn-primary">Alle Events anzeigen</a>
        </div>
    </section>
    <section class="section fields-section">
        <div class="container" style="text-align: center;">
            <h2 style="color: var(--color-accent-primary); margin-bottom: 2rem;">Unsere Spielfelder & Hauptevents</h2>
            <div class="team-grid">
                <?php
                $fields_file = get_template_directory() . '/fields.json';
                if (file_exists($fields_file)) {
                    $fields = json_decode(file_get_contents($fields_file), true);
                    if ($fields && is_array($fields)) {
                        foreach ($fields as $field) {
                            ?>
                            <article class="team-card" style="text-align: left; padding: 0; background: var(--color-bg-panel); border: 1px solid var(--color-border); border-radius: var(--radius-md); overflow: hidden; display: flex; flex-direction: column;">
                                <?php if (!empty($field['image'])) : ?>
                                    <div style="width: 100%; height: 200px; overflow: hidden; position: relative;">
                                        <img src="<?php echo esc_url($field['image']); ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                        <div style="position: absolute; inset: 0; background: linear-gradient(0deg, rgba(12, 15, 15, 0.9) 0%, rgba(12, 15, 15, 0) 50%);"></div>
                                    </div>
                                <?php endif; ?>
                                <div style="padding: 1.5rem; flex-grow: 1;">
                                    <h3 style="color: var(--color-text-main); margin-bottom: 0.5rem; font-size: 1.25rem;"><?php echo esc_html($field['title']); ?></h3>
                                    <p style="color: var(--color-text-muted); font-size: 0.9rem; margin-bottom: 0;">
                                        <?php echo esc_html($field['description']); ?>
                                    </p>
                                </div>
                            </article>
                            <?php
                        }
                    }
                }
                ?>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
