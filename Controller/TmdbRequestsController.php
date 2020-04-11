<?php


namespace App\Controller;


use App\Library\API\TmdbApi;

class TmdbRequestsController extends AbstractController
{
    public function searchMovies($searchQuery = null, $pageQuery = null){

        $test = new TmdbApi("01caf40148572dc465c9503e59ded4bf");
//        $test->getLatestMovies();
        $test->getRandomMovies();

//        if($pageQuery!= null){
//            vd("searchQuery = " . $searchQuery, 'pageQuery = ' . $pageQuery);
//        }
//        if($searchQuery!= null){
//            vd("searchQuery = " . $searchQuery, 'pageQuery = ' . $pageQuery);
//        }

        if($this->isPost()){
            $searchQuery = trim(htmlspecialchars($_POST['search']));
            $pageQuery = trim(htmlspecialchars($_POST['pageQuery']));
            $previousPage = $pageQuery - 1;
            $nextPage = $pageQuery + 1;

            echo $this->render('searchResults.twig', array('searchQuery' => $searchQuery, 'pageQuery' => $pageQuery, 'previousPage' => $previousPage, 'nextPage' => $nextPage));

        }elseif ($searchQuery != null && $pageQuery != null){
//            vd("searchQuery = " . $searchQuery, 'pageQuery = ' . $pageQuery);
            $previousPage = $pageQuery - 1;
            $nextPage = $pageQuery + 1;

            echo $this->render('searchResults.twig', array('searchQuery' => $searchQuery, 'pageQuery' => $pageQuery, 'previousPage' => $previousPage, 'nextPage' => $nextPage));
        }

    }



}