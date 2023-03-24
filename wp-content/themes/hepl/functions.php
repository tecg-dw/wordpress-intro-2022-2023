<?php

// Disable Wordpress' default Gutenberg Editor:
add_filter('use_block_editor_for_post', '__return_false', 10);

// Register existing navigation menus
register_nav_menu('main', 'Navigation principale du site web (en-tête)');
register_nav_menu('footer', 'Navigation de pied de page');
register_nav_menu('social-media', 'Liens vers les réseaux sociaux');

// Custom function that returns a menu structure for given location
function hepl_get_menu(string $location, ?array $attributes = []): array
{
    // 1. Récupérer les liens en base de données pour la location $location
    $locations = get_nav_menu_locations();
    $menuId = $locations[$location];
    $items = wp_get_nav_menu_items($menuId);

    // 2. Formater les liens récupérés en objets qui contiennent les attributs suivants :
        // - href : l'URL complète pour ce lien
        // - label : le libellé affichable pour ce lien
    $links = [];
    
    foreach ($items as $item) {
        $link = new stdClass();
        $link->href = $item->url;
        $link->label = $item->title;

        foreach($attributes as $attribute) {
            $link->$attribute = get_field($attribute, $item->ID);
        }

        $links[] = $link;
    }

    // 3. Retourner l'ensemble des liens formatés en un seul tableau non-associatif
    return $links;
}

// Activer les images "thumbnail" sur nos posts
add_theme_support('post-thumbnails'); 
add_image_size('animal_thumbnail', 400, 400, true);

// Enregistrer un custom post type :
function hepl_register_custom_post_types()
{
    register_post_type('animal', [
        'label' => 'Animaux',
        'description' => 'Les animaux disponibles dans notre refuge.',
        'public' => true,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-pets', // https://developer.wordpress.org/resource/dashicons/#pets,
        'supports' => ['title','thumbnail'],
    ]);

    register_post_type('message', [
        'label' => 'Message de contact',
        'description' => 'Les messages envoyés via le formulaire de contact.',
        'public' => true,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-email', // https://developer.wordpress.org/resource/dashicons/#pets,
        'supports' => ['title','editor'],
    ]);
}

add_action('init', 'hepl_register_custom_post_types');

// Charger les traductions existantes
load_theme_textdomain('hepl', get_template_directory() . '/locales');

// Ajouter le système personnalisé de remplacement des variables dans les phrases traduisibles
// ex: $replacements = ['name' => 'Jean-Paul']
function __hepl(string $translation, array $replacements = [])
{
    // 1. Récupérer la traduction de la phrase présente dans $translation
    $base = __($translation, 'hepl');

    // 2. Remplacer toutes les occurrences des variables par leur valeur
    foreach ($replacements as $key => $value) {
        $variable = ':' . $key;
        $base = str_replace($variable, $value, $base);
    }

    // 3. Retourner la traduction complète.
    return $base;
}

// Gérer le formulaire de contact "custom"
// Inspiré de : https://wordpress.stackexchange.com/questions/319043/how-to-handle-a-custom-form-in-wordpress-to-submit-to-another-page

function hepl_validate_contact_form(array $data) : bool|array
{
    $errors = [];

    if(! strlen($data['lastname'] ?? null)) {
        $errors['lastname'] = 'Veuillez fournir votre nom de famille.';
    }

    if(! strlen($data['email'] ?? null)) {
        $errors['email'] = 'Veuillez fournir votre adresse mail.';
    } else if (! filter_var($data['email'] ?? null, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Votre adresse mail n\'est pas valide.';
    }

    return $errors ?: true;
}

function hepl_store_contact_form_message($firstname, $lastname, $email, $message)
{
    wp_insert_post([
        'post_type' => 'message',
        'post_status' => 'publish',
        'post_title' => $firstname . ' ' . $lastname . ' <' . $email . '>',
        'post_content' => $message,
    ]);
}

function hepl_send_contact_form_message($firstname, $lastname, $email, $message)
{
    wp_mail('toon@whitecube.be', $firstname . ' ' . $lastname . ' <' . $email . '>', $message);
}

function hepl_execute_contact_form()
{
    $previous = wp_get_referer();

    if(wp_verify_nonce($_POST['contact_nonce'] ?? null, 'hepl_contact_form') !== 1) {
        // C'est pas bon, on ne continue pas l'exécution du script.
        return;
    }

    if(is_array($errors = hepl_validate_contact_form($_POST))) {
        // La validation ne passe pas, il faut afficher une erreur à l'utilisateur.
        wp_safe_redirect($previous . '?' . http_build_query($errors));
        exit;
    }

    $firstname = sanitize_text_field($_POST['firstname'] ?? '');
    $lastname = sanitize_text_field($_POST['lastname'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    hepl_store_contact_form_message($firstname, $lastname, $email, $message);
    hepl_send_contact_form_message($firstname, $lastname, $email, $message);

    wp_safe_redirect($previous);
    exit;
}

add_action('admin_post_nopriv_hepl_contact_form', 'hepl_execute_contact_form');
add_action('admin_post_hepl_contact_form', 'hepl_execute_contact_form');
