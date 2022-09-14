<?php
// On charge la classe App
// Elle va permettre de charger les autres classes nécessaire et
// de mettre en place les bases qui seront utilisé
require_once './../Helper/App/Autoloader.php';


use Helper\App\App;
use Helper\App\DB;
use Helper\Twig\Page;


// On instancie la classe App
$App = new App();
$Context = new DB();

/*
 * EXEMPLE D'UTILISATION DE TWIG
 *
 * Il faut d'abord loader le template
 * Et au moment du rendu on peut lui passer les paramètres
 */
$params = [
    'title' => 'Home',
    'name' => 'John Doe',
    'items' => [
        'item1',
        'item2',
        'item3',
    ],
];

$page = new Page('home.tpl.twig', $params);
$page->addParam('age', 30);

// On affiche le template (avec les paramètres).
echo $page->display();

$🤣 = $Context->getKeyword(5);
var_dump($🤣);


$🤔 = $Context->getKeywords();
var_dump($🤔);


