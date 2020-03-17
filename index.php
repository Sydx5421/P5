<?php
session_start();
// Affichage des erreurs à retirer
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
//-------------
use App\Library\Autoloader;
use App\Library\RouterPOO;
use App\Library\Route;

require 'Library\Autoloader.php';
require 'Library\functions.php';
Autoloader::register();
require 'vendor/autoload.php';
$router = new RouterPOO();

// ------------------ Route(URL, nomDuController, nomDeLaction)

//$router->addRoute(new Route('/home', 'blog', 'home'));
$router->addRoute(new Route('/home', 'main', 'home'));
$router->addRoute(new Route('/register', 'main', 'register'));
$router->addRoute(new Route('/test', 'main', 'test'));
$router->addRoute(new Route('/contact', 'main', 'contact'));


$router->run();
