<?php
require_once './App.php';

use App\App;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


$App = new App();


try {
    $template = $App->getTwig()->load('home.tpl.html');
} catch (LoaderError|RuntimeError|SyntaxError $e) {
    echo $e->getMessage();
    exit();
}

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

