<?php


namespace App\Controller;


use App\Library\API\TmdbApi;

class TmdbRequestsController extends AbstractController
{
    public function searchMovies($searchQueryGet = null, $pageQueryGet = null){
//        vd('$searchQueryGet = ' . $searchQueryGet, '$pageQueryGet = ' . $pageQueryGet);
        $MovieAPI = new TmdbApi("01caf40148572dc465c9503e59ded4bf");

        if($this->isPost()){
            $searchQueryPost = trim(htmlspecialchars($_POST['search']));
            $pageQueryPost = trim(htmlspecialchars($_POST['pageQuery']));
            $previousPage = $pageQueryPost - 1;
            $nextPage = $pageQueryPost + 1;

            $moviesSearchResults = $MovieAPI->searchMovie($searchQueryPost, $pageQueryPost);
//            vd($moviesSearchResults->results);

            echo $this->render('searchResults.twig', array('moviesSearchResults' => $moviesSearchResults, 'searchQuery' =>
                $searchQueryPost, 'previousPage' => $previousPage, 'nextPage' => $nextPage));

        }elseif ($searchQueryGet != null && $pageQueryGet != null){
//            vd("searchQuery = " . $searchQueryGet, 'pageQuery = ' . $pageQueryGet);
            $previousPage = $pageQueryGet - 1;
            $nextPage = $pageQueryGet + 1;

            $moviesSearchResults = $MovieAPI->searchMovie($searchQueryGet, $pageQueryGet);

            echo $this->render('searchResults.twig', array('moviesSearchResults' => $moviesSearchResults, 'searchQuery' => $searchQueryGet, 'previousPage' => $previousPage, 'nextPage' => $nextPage));
        }
    }

    public function movie($movieId){
        $MovieAPI = new TmdbApi("01caf40148572dc465c9503e59ded4bf");

        $infosMovie = $MovieAPI->getMoviesById($movieId);

        echo $this->render('movie.twig', array("movie" => $infosMovie, "classPage" => "moviePage"));
    }

}