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

//phpinfo();
//die;

// ------------------ Route(URL, nomDuController, nomDeLaction)

$router->addRoute(new Route('/', 'main', 'home'));
$router->addRoute(new Route('/home', 'main', 'home'));
$router->addRoute(new Route('/dashboard', 'main', 'dashboard'));
$router->addRoute(new Route('/register', 'main', 'register'));
$router->addRoute(new Route('/login', 'main', 'login'));
$router->addRoute(new Route('/deconnexion', 'main', 'deconnexion'));

// requête API TMDB
$router->addRoute(new Route('/searchMovies', 'TmdbRequests', 'searchMovies'));
$router->addRoute(new Route('/searchMovies/(\w+)/(\d+)', 'TmdbRequests', 'searchMovies'));
//$router->addRoute(new Route('/searchMovies/(\d+)', 'TmdbRequests', 'searchMovies'));
//$router->addRoute(new Route('/searchMovies/(\w+)/(\d+)', 'TmdbRequests', 'searchMovies'));


$router->run();
