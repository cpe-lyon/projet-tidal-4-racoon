<?php
// On charge la classe App
// Elle va permettre de charger les autres classes nécessaire et
// de mettre en place les bases qui seront utilisé
require_once './App.php';

use App\App;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


// On instancie la classe App
$App = new App();


/*
 * EXEMPLE D'UTILISATION DE TWIG
 *
 * Il faut d'abord loader le template
 * Et au moment du rendu on peut lui passer les paramètres
 */
try {
    // On charge le template (dans un try pour éviter les crash)
    $template = $App->getTwig()->load('home.tpl.html');
} catch (LoaderError|RuntimeError|SyntaxError $e) {
    echo $e->getMessage();
    exit();
}

// On affiche le template (avec les paramètres)
echo $template->render([
    'title' => 'Home',
    'name' => 'John Doe',
    'age' => 30,
    'items' => [
        'item1',
        'item2',
        'item3',
    ],
]);

