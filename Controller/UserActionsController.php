<?php


namespace App\Controller;

use App\Model\Entity\Category;
use App\Model\Entity\MCUConnection;
use App\Model\Entity\Movie;
use App\Model\Manager\CategoryManager;
use App\Controller\TmdbRequestsTrait;

class UserActionsController extends AbstractController
{

    use TmdbRequestsTrait;

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function dashboard(){

        echo $this->render('dashboard.twig');
    }

    /**
     *
     */
    public function deconnexion(){
        session_destroy();
        $this->redirect('home');
    }

    public function createCategory(){
        $CategoryManager = new CategoryManager();

        if($this->isPost()){
            $newCategoryData = [
                'user_id' => trim(htmlspecialchars($_POST['userId'])),
                'nom' => trim(htmlspecialchars($_POST['nameCategory'])),
                'description' => trim(htmlspecialchars($_POST['descriptionCat'])),
                'font_color' => trim(htmlspecialchars($_POST['backgroundColor'])),
                'background_color' => trim(htmlspecialchars($_POST['fontColor']))
            ];

            $newCategory = new Category($newCategoryData);
//            vd($newCategory);

            if($newCategory->isValid() === true){
                $categoryCreation = $CategoryManager->create($newCategory);
                if($categoryCreation === true){
                    $this->addFlash('Votre catégorie "'  . $newCategory->getNom()
                        .  '" a bien été créé ! Vous pouvez dès à présent vous l\'utiliser.', 'success');
                    $this->redirect('categories');
                }else{
                    // Erreur au niveau de l'enregistrement dans la base (mail ou pseudo déjà utilisés)
                    $this->addFlash('Erreur : ' . $categoryCreation, 'danger');
                }

            }else{
                // On renvoie l'erreur retourner par le manager:
                $this->addFlash('Erreur : ' . $newCategory->isValid(), 'danger');
            }

        }

        $categories =  $CategoryManager->getCategories();

        echo $this->render('categories.twig', array('classPage' =>'categoriesPage', 'categories' => $categories));

    }

    public function categorySearchNewMovies($catId){
        $CategoryManager = new CategoryManager();
        $category =  $CategoryManager->getCategory($catId);
        $module = "categorySearch";
        $searchResult = $this->searchMovies();


        echo $this->render('category.twig', array('classPage' =>'categoryPage', 'category' => $category, 'module' => $module, 'moviesSearchResults' => $searchResult["moviesSearchResults"], 'searchQuery' => $searchResult["searchQuery"], 'previousPage' => $searchResult["previousPage"], 'nextPage' => $searchResult["nextPage"]));
    }

    public function addMoviesToCategory($catId, $movieId){
        $CategoryManager = new CategoryManager();
        $category =  $CategoryManager->getCategory($catId);
//        vd($category); // vérifier la nature de cette variable, objet?
        $module = "addMovie";
        $searchResult = $this->movie($movieId);

        if($this->isPost()){
//            vd($_POST, $_POST['movieId'], $_POST['movieTitle']);
            $newMovieData = [
                'movie_id' => trim(htmlspecialchars($_POST['movieId'])),
                'movie_title' => trim(htmlspecialchars($_POST['movieTitle'])),
            ];

            $newMovie = new Movie($newMovieData);

            $newMCUConnectionData = [
                'movie_id' => trim(htmlspecialchars($_POST['movieId'])),
                'category_id' => trim(htmlspecialchars($_POST['categoryId'])),
                'user_id' => trim(htmlspecialchars($_POST['userId'])),
                'justification_comment' => trim(htmlspecialchars($_POST['justificationText'])),
            ];

            $newMCUConnection = new MCUConnection($newMCUConnectionData);

            $MCUConnectionCreation = $CategoryManager->createMovieConnection($newMovie, $newMCUConnection);

            if($MCUConnectionCreation === true){
                $this->addFlash('Votre classement du film "'  . $newMCUConnection->getMovieId() .  '" dans la catégorie ' . $newMCUConnection->getCategoryId() . ' a bien été enregistré ! Merci pour votre contribution !', 'success');
                $this->redirect('categories');
            }else{
                // Erreur au niveau de l'enregistrement dans la base (mail ou pseudo déjà utilisés)
                $this->addFlash('Erreur : ' . $MCUConnectionCreation, 'danger');
            }
        }

        echo $this->render('category.twig', array('classPage' =>'categoryPage', 'category' => $category, 'module' => $module, 'movie' => $searchResult["movie"]));
    }

}









