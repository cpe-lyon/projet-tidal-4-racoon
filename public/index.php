<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;


$loader = new FilesystemLoader('./../templates');
$twig = new Environment($loader, [
    'cache' => false,//'./../cache',
    'debug' => true,
]);

    $template = $twig->load('home.tpl.html');

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

