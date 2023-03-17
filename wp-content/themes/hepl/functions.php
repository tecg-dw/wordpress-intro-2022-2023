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

