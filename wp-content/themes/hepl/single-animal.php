<?php get_header(); ?>
<?php if(have_posts()): while(have_posts()): the_post(); ?>
    <main class="animal">
        <h1 class="animal__title"><?= get_the_title(); ?></h1>
        <dl class="animal__details">
            <dt class="anim__key"><?= __hepl('Espèce'); ?></dt>
            <dd class="animal__value"><?= __hepl(get_field('species')); ?></dd>
            <dt class="anim__key"><?= __hepl('Âge'); ?></dt>
            <dd class="animal__value"><?= get_field('age'); ?></dd>
            <dt class="anim__key"><?= __hepl('Sexe'); ?></dt>
            <dd class="animal__value"><?= __hepl(get_field('gender')); ?></dd>
        </dl>
    </main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>