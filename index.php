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
//$router->addRoute(new Route('/notFound', 'main', 'notFound'));
$router->addRoute(new Route('/home', 'main', 'home'));
$router->addRoute(new Route('/simpleMovieSearch', 'main', 'simpleMovieSearch'));
$router->addRoute(new Route('/simpleMovieSearch/(\w+)/(\d+)', 'main', 'simpleMovieSearch'));
$router->addRoute(new Route('/register', 'main', 'register'));
$router->addRoute(new Route('/login', 'main', 'login'));
$router->addRoute(new Route('/categories', 'main', 'categories'));
$router->addRoute(new Route('/category/(\d+)', 'main', 'category'));
$router->addRoute(new Route('/category/(\d+)/(\w+)', 'main', 'category'));
$router->addRoute(new Route('/movie/(\d+)', 'main', 'movie'));
$router->addRoute(new Route('/movie/(\d+)/cat/(\d+)', 'main', 'movie'));

//$router->addRoute(new Route('/categoryFilms', 'main', 'categoryFilms'));

// ------------------- Actions réservées à l'utilisateur connecté
$router->addRoute(new Route('/dashboard/(\d+)', 'User', 'dashboard'));
$router->addRoute(new Route('/deconnexion', 'User', 'deconnexion'));
$router->addRoute(new Route('/editComment/(\d+)', 'User', 'editComment'));
$router->addRoute(new Route('/deleteConnection/(\d+)', 'User', 'deleteConnection'));
$router->addRoute(new Route('/createCategory', 'User', 'createCategory'));
$router->addRoute(new Route('/categorySearchNewMovies/(\d+)', 'User', 'categorySearchNewMovies'));
$router->addRoute(new Route('/addMoviesToCategory/(\d+)/(\d+)', 'User', 'addMoviesToCategory'));


// ----------------------------- requête API TMDB
//$router->addRoute(new Route('/searchMovies', 'TmdbRequests', 'searchMovies'));
//$router->addRoute(new Route('/searchMovies/(\w+)/(\d+)', 'TmdbRequests', 'searchMovies'));

//------------------------------ action réservées à l'admin :
$router->addRoute(new Route('/adminActionComment', 'admin', 'adminActionComment'));



$router->run();
