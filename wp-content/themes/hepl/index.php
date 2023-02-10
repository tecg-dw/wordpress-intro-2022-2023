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
    </main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>