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
        <div class="page__form" style="margin: 5em 0; padding: 5em 0; border-top: 1px solid black; border-bottom: 1px solid black;">
            <form action="<?= esc_url(admin_url('admin-post.php')); ?>" method="POST" class="contact">
                <fieldset class="contact__info">
                    <div class="field">
                        <label for="firstname" class="field__label">Votre prénom</label>
                        <input type="text" name="firstname" id="firstname" class="field__input" />
                        <?php if($_GET['firstname'] ?? null): ?>
                            <p class="field__error" style="color:red"><?= $_GET['firstname']; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="field">
                        <label for="lastname" class="field__label">Votre nom</label>
                        <input type="text" name="lastname" id="lastname" class="field__input" />
                        <?php if($_GET['lastname'] ?? null): ?>
                            <p class="field__error" style="color:red"><?= $_GET['lastname']; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="field">
                        <label for="email" class="field__label">Votre adresse mail</label>
                        <input type="email" name="email" id="email" class="field__input" />
                        <?php if($_GET['email'] ?? null): ?>
                            <p class="field__error" style="color:red"><?= $_GET['email']; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="field">
                        <label for="message" class="field__label">Votre message</label>
                        <textarea name="message" id="message" cols="30" rows="10" class="field__textarea"></textarea>
                        <?php if($_GET['message'] ?? null): ?>
                            <p class="field__error" style="color:red"><?= $_GET['message']; ?></p>
                        <?php endif; ?>
                    </div>
                </fieldset>
                <div class="contact__footer">
                    <input type="hidden" name="action" value="hepl_contact_form" />
                    <input type="hidden" name="contact_nonce" value="<?= wp_create_nonce('hepl_contact_form'); ?>" />
                    <button class="contact__submit" type="submit">Envoyer</button>
                </div>
            </form>
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