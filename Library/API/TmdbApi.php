<?php


namespace App\Library\API;


class TmdbApi extends Curl
{
    private $apiKey;

//"https://api.themoviedb.org/3/movie/latest?api_key=" + APIKEY
//    http://image.tmdb.org/t/p/
// 01caf40148572dc465c9503e59ded4bf
//"https://api.themoviedb.org/3/search/movie?api_key=" + APIKEY + "&language=en-US&query="+ userQuery +"&page=" + pageQuery + "&include_adult=false"

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Renvoie la liste des avis TmdbApi
     * @return array
     */
    public function getLatestMovies()
    {
        $response = $this->call(
            "https://api.themoviedb.org/3/movie/latest",
            'GET',
            [
                'api_key' => $this->apiKey,
            ]
        );

//        return isset($response->results) && is_array($response->results) ? $response->results : [];
        return isset($response->id)  ? $response->id : 691972;
    }

    public function getRandomMovies()
    {
        // To make sure I'll get 3 valid movies with posters I search for 21 random movies ids then on the 10 I get the
        // first 3 that effectively have a poster

        $randomMovies = array();
        for ($i = 0; $i <21; $i++){

            $randId = random_int ( 1 , $this->getLatestMovies());

            $response = $this->call(
                "https://api.themoviedb.org/3/movie/" . $randId,
                'GET',
                [
                    "api_key" => $this->apiKey,
                ]
            );

            if(isset($response->id) && isset($response->poster_path) && ($response->adult === false)){
                $randomMovies[] = $response;
            }

        }

        $my3Movies = array();
        foreach ($randomMovies as $movie){
//            vd($movie->poster_path);
            if ($movie->poster_path != null){
                $my3Movies[] = $movie;
            }
            if(count($my3Movies) == 3){
                break;
            }
        }

//        vd($my3Movies, $randomMovies);
        return $my3Movies;
    }

    public function searchMovie($userQuery, $pageQuery)
    {
        $response = $this->call(
            "https://api.themoviedb.org/3/search/movie",
            'GET',
            [
                "api_key" => $this->apiKey,
                "language" => "en-US",
                "query" => $userQuery,
                "page" => $pageQuery,
                "include_adult" => "false"
            ]
        );
        return $response;

    }

}
