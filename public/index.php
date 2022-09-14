<?php
// On charge la classe App
// Elle va permettre de charger les autres classes nécessaire et
// de mettre en place les bases qui seront utilisé
require_once './../Helper/App/Autoloader.php';


use Helper\App\App;


// On instancie la classe App
$App = new App();



$App->run();