<?php

namespace App\Controller;

use App\Library\API\TmdbApi;
use App\Model\Entity\Category;
use App\Model\Entity\User;
use App\Model\Manager\CategoryManager;
use App\Model\Manager\McuManager;
use App\Model\Manager\UserManager;

class MainController extends AbstractController
{
    use TmdbRequestsTrait;


    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function home(){
        $TmdbApi = new TmdbApi("01caf40148572dc465c9503e59ded4bf");
        $randMovies =  $TmdbApi->getRandomMovies();

        $randMovie1 = $randMovies[0];
        $randMovie2 = $randMovies[1];
        $randMovie3 = $randMovies[2];

        echo $this->render('home.twig', array('randMovies' => $randMovies) );

    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function register(){
        $UserManager = new UserManager();

        if($this->isPost()){
            $pseudo = trim(htmlspecialchars($_POST['pseudo']));
            $password = trim(htmlspecialchars($_POST['password']));
            $confirm_password = trim(htmlspecialchars($_POST['confirm_password']));
            $email = trim(htmlspecialchars($_POST['email']));

            $newUserData = [
                'pseudo' => $pseudo,
                'password' => $password,
                'confirmPassword' => $confirm_password,
                'email' => $email
            ];

            $newUser = new User($newUserData);

            if($newUser->isValid() === true){
                $userRegistration = $UserManager->register($newUser);
                if($userRegistration === true){
                    $this->addFlash('Votre compte a bien été créé ! Bienvenue sur Cinemood ' . $newUser->getPseudo()
                        . '. Vous pouvez dès à présent vous connecter.', 'success');
                    $this->redirect('home');
                }else{
                    // Erreur au niveau de l'enregistrement dans la base (mail ou pseudo déjà utilisés)
                    $this->addFlash('Erreur : ' . $userRegistration, 'danger');
                }
            }else{
                // Erreurs au niveau de la validation du formulaire d'inscription (mal rempli)
                $this->addFlash('Erreur : ' . $newUser->isValid(), 'danger');
            }
        }
            echo $this->render('register.twig');
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function login(){
        $UserManager = new UserManager();

        if($this->isPost()){

            if(isset($_POST['email_or_pseudo']) && isset($_POST['password'])){
//                vd('!', $this->isPost(), (isset($_POST['email_or_pseudo']) && isset($_POST['password'])), (!empty
//                    ($_POST['email_or_pseudo']) && !empty($_POST['password'])));
                if(!empty($_POST['email_or_pseudo']) && !empty($_POST['password'])){
                    $password = htmlspecialchars($_POST['password']);
                    $login = htmlspecialchars($_POST['email_or_pseudo']);

                    $userLogin = $UserManager->login($login, $password);
//                    vd($userLogin);
                    if(is_object($userLogin)){
                        $userLogged = new User($userLogin);
                        if($userLogged->getIsAdmin() == 1){
                            $_SESSION['admin'] = true;
                            $this->isAdmin = true;
                        }
//                        vd($userLogged->getIsAdmin(), $this->isAdmin);
                        $_SESSION['user'] = $userLogin;
                        $this->userConnected = true;
//                        vd($_SESSION['user']->id, $_SESSION['user']);
                        $this->addFlash('Bienvenue ' . $userLogged->getPseudo());
                        $this->redirect('dashboard/' . $userLogged->getId());
                        exit();
                    }else{
                        $this->addFlash($userLogin);
                        $this->redirect('home' );
                    }
                }else{
                    $this->addFlash('Veuillez remplir tous les champs afin de pouvoir vous connecter.');
                    $this->redirect('home');
                }
            }
        }
        $this->redirect('home');
    }

    public function simpleMovieSearch($searchQueryGet = null, $pageQueryGet = null){
        $searchResult = $this->searchMovies($searchQueryGet, $pageQueryGet);
//        vd($searchResult["searchQuery"]);

        echo $this->render('searchResults.twig', array('moviesSearchResults' => $searchResult["moviesSearchResults"], 'searchQuery' => $searchResult["searchQuery"], 'previousPage' => $searchResult["previousPage"], 'nextPage' => $searchResult["nextPage"]));

    }


    public function categories(){
        $CategoryManager = new CategoryManager();
        $categories =  $CategoryManager->getCategories();

        echo $this->render('categories.twig', array('classPage' =>'categoriesPage', 'categories' => $categories));
    }

    public function category($categoryId, $search=null){
        $CategoryManager = new CategoryManager();
        $category =  $CategoryManager->getCategory($categoryId);

        $movieList = $CategoryManager->getCategoryMovieList($categoryId);

        $module = "categoryFilms";

        if($search != null){
//            vd($search);
            $module = "categorySearch";
        }

        echo $this->render('category.twig', array('classPage' =>'categoryPage', 'category' => $category, 'module'
        => $module, 'movieList' => $movieList));
    }

    public function movie($movieId, $categoryId = null){
//        vd($movieId, $categoryId );
        $MovieAPI = new TmdbApi("01caf40148572dc465c9503e59ded4bf");
        $infosMovie = $MovieAPI->getMoviesById($movieId);

        $CategoryManager = new CategoryManager();
        $McuManager = new McuManager();
        $category =  $CategoryManager->getCategory($categoryId);

        if($categoryId === null ){
            echo $this->render('movie.twig', array("movie" => $infosMovie, "classPage" => "moviePage"));
            die;
        }else{
            // récupérer les comentaires pour cette catégory et ce film
            $mcuList = $McuManager->getAllCommentsForMC($movieId, $categoryId);
//            vd($mcuList);

            echo $this->render('movie.twig', array("movie" => $infosMovie, "classPage" => "moviePage", "category" =>
                $category->getNom(), "mcuList" => $mcuList));
        }
    }

    public function categoryFilms(){
//        vd('est-ce quon rentre ici?');
        $this->render('categoryFilms');
    }

}
