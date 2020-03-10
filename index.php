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

//chemin vers nos view
$templatePath = realpath(__DIR__.'/View');

$loader = new \Twig\Loader\FilesystemLoader($templatePath);
$twig = new \Twig\Environment($loader, ['cache'=>false]);

echo $twig->render('test.twig', ['coucou'=>'helloWorld']);
die; // juste pour l'exemple
// ------------------ Route(URL, nomDuController, nomDeLaction)

//$router->addRoute(new Route('/home', 'blog', 'home'));

//pages réserver à l'admin



$router->run();
