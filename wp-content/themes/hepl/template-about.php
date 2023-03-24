<?php /* Template Name: About page template */ ?>
<?php get_header(); ?>
<?php if(have_posts()): while(have_posts()): the_post(); ?>
    <main class="page">
        <div class="page__main"> 
            <h2 class="page__title"><?= get_the_title(); ?></h2>
            <div class="page__content">
                <?php the_content(); ?>
            </div>
        </div>
        <div class="page__form">
            <?= apply_filters('the_content', '[contact-form-7 id="56" title="Formulaire page de contact"]'); ?>
        </div>
        <aside class="page__aside profile">
            <h2 class="profile__name"><?= get_field('name'); ?></h2>
            <div class="profile__description"><?= get_field('description'); ?></div>
            <figure class="profile__picture">
                <?php $avatar = get_field('avatar'); ?>
                <img src="<?= $avatar['sizes']['medium_large']; ?>" alt="<?= $avatar['alt']; ?>" />
            </figure>
        </aside>
    </main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>