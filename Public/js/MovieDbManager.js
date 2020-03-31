

function MovieDbManager(){
    this.apiUrl = 'http://www.omdbapi.com/';
    const APIKEY = '01caf40148572dc465c9503e59ded4bf';
    const BASEURL = "http://image.tmdb.org/t/p/";

    var _this = this;

    this.testResponseAPi = function(response){
        console.log(response);
    }

    this.randomMovies = function(){
        var lastTmdbId = ajaxGet("https://api.themoviedb.org/3/movie/latest?api_key=" + APIKEY,function (response) {
            var lastMovie = JSON.parse(response);
            var lastMovieId = lastMovie.id;
            // console.log('Voici le dernier id de la base TDBM ' + lastMovieId);

            var randomMoviesDisplayElt = $(".randomMovie");
            // console.log(randomMoviesDisplayElt.length);

            var randomMoviesDisplayEltArray = Object.values(randomMoviesDisplayElt).slice(0, randomMoviesDisplayElt.length);
            // console.log(randomMoviesDisplayEltArray);

            randomMoviesDisplayEltArray.forEach(function(elt){
                var randomId = Math.floor(Math.random() * (lastMovieId - 1)) + 1;

                var isAdultMovie = true;
                var posterPath = null;
                // while (isAdultMovie === true || isThereAPoster == null) {
                    ajaxGet("http://api.themoviedb.org/3/movie/" + randomId + "?api_key=01caf40148572dc465c9503e59ded4bf", function (response) {
                        var movie = JSON.parse(response);
                        isAdultMovie = movie.adult;
                        posterPath = movie.poster_path;
                        var title = movie.title;
                        if (isAdultMovie != true && posterPath != null && title != null) {
                            $(elt).attr('src', BASEURL + 'w185' + posterPath);
                            $(elt).siblings("h2").text(title);
                        }
                        // console.log("inside WHILE isAdultMovie = " + isAdultMovie + " et isThereAPoster = " + isThereAPoster);
                    });
                // }
            });

            var randomId = Math.floor(Math.random() * (lastMovieId - 1)) + 1;
            // console.log(randomId);

            // if(isAdultMovie === true || isThereAPoster == null) {
            //     ajaxGet("http://api.themoviedb.org/3/movie/" + randomId + "?api_key=01caf40148572dc465c9503e59ded4bf", function (response) {
            //         var movie = JSON.parse(response);
            //         isAdultMovie = movie.adult;
            //         isThereAPoster = movie.poster_path;
            //         console.log("inside WHILE isAdultMovie = " + isAdultMovie + " et isThereAPoster = " + isThereAPoster);
            //     });
            // }
            // console.log("outside WHILE isAdultMovie = " + isAdultMovie + " et isThereAPoster = " + isThereAPoster);

        });
    }

    this.searchMovie = function () {

    }

}

var movieDbManager = new MovieDbManager()

movieDbManager.randomMovies();