<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= get_bloginfo('name'); ?></title>
</head>
<body>
    <header class="header">
        <h1 class="header__sitename"><?= get_bloginfo('name'); ?></h1>
        <p class="header__tagline"><?= get_bloginfo('description'); ?></p>
    </header>
    <nav class="nav">
        <?php foreach(hepl_get_menu('main') as $link): ?>
            <a href="<?= $link->href; ?>" class="nav__link">
                <span class="nav__label"><?= $link->label; ?></span>
            </a>
        <?php endforeach; ?>
    </nav>