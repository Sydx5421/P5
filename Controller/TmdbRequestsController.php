<?php


namespace App\Controller;


class TmdbRequestsController extends AbstractController
{
    public function searchMovies($searchQuery = null, $pageQuery = null){
//        vd("arrivé dans l'action searchMovies !");

        if($this->isPost()){
            $searchQuery = trim(htmlspecialchars($_POST['search']));
            $pageQuery = trim(htmlspecialchars($_POST['pageQuery']));
            $previousPage = $pageQuery - 1;
            $nextPage = $pageQuery + 1;

//            vd($pageQuery);

            echo $this->render('searchResults.twig', array('searchQuery' => $searchQuery, 'pageQuery' => $pageQuery, 'previousPage' => $previousPage, 'nextPage' => $nextPage));
        }elseif ($searchQuery != null && $pageQuery != null){
            //relancer l'API pour les résultats suivants ou précédants
        }

    }
}