<?php get_header(); ?>
<?php if(have_posts()): while(have_posts()): the_post(); ?>
    <div class="hero">
        <h2 class="hero__title"><?= get_the_title(); ?></h2>
        <p>Scrollez pour découvrir mon site</p>
    </div>
    <main class="page">
        <div class="page__content">
            <?php the_content(); ?>
        </div>
        <section class="page__animals animals">
            <h2 class="animals__title">Nos derniers pensionnaires</h2>
            <div class="animals__container">
                <?php 
                // Faire une requête en DB pour récupérer 4 animaux
                $animals = new WP_Query([
                    'post_type' => 'animal',
                    'posts_per_page' => 4
                ]);
                // Lancer la boucle pour afficher chaque animal
                if($animals->have_posts()): while($animals->have_posts()): $animals->the_post();
                ?>
                <article class="animal">
                    <a href="<?= get_the_permalink(); ?>" class="animal__link">
                        <span class="sro">Découvrir <?= get_the_title(); ?></span>
                    </a>
                    <div class="animal__card">
                        <div class="animal__details">
                            <h3 class="animal__name"><?= get_the_title(); ?></h3>
                            <dl class="animal__attributes">
                                <dt class="sro">Espèce</dt>
                                <dd class="animal__attribute"><?= get_field('species'); ?></dd>
                                <dt class="sro">Âge</dt>
                                <dd class="animal__attribute"><?= get_field('age'); ?></dd>
                                <dt class="sro">Sexe</dt>
                                <dd class="animal__attribute"><?= get_field('gender'); ?></dd>
                            </dl>
                        </div>
                        <figure class="animal__fig">
                            <?= get_the_post_thumbnail(null, 'animal_thumbnail', ['class' => 'animal__img']); ?>
                        </figure>
                    </div>
                </article>
                <?php endwhile; else: ?>
                <p class="animals__empty">Nous n'avons pas d'animaux pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>