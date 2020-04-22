<?php


namespace App\Model\Manager;

use App\Model\Entity\Category;
use App\Model\Entity\Movie;
use App\Model\Manager\AbstractManager;
use App\Model\Entity\MCUConnection;

class CategoryManager extends AbstractManager
{
    public function create(Category $category){
        $db = $this->dbConnect();

        // checking that the category does not already exist :
        $req_category = $db->prepare("SELECT 'nom' FROM categories WHERE nom = ?");
        $reqExec_category = $req_category->execute(array($category->getNom()));
        if($reqExec_category === true && $req_category->rowCount()){
            return"Cette categorie existe déjà";
        }

        // checking that the background and the font color are not the same !
        if($category->getFontColor() == $category->getBackgroundColor()){
            return"Choisissez une couleur de fond différente de celle de la police pour que le nom de votre catégorie soit lisible.";
        }

        // Enregistrement de  la catégorie
        $req = $db->prepare('INSERT INTO categories(user_id, nom, description, font_color, background_color) VALUES(:user_id, :nom, :description, :font_color, :background_color)');

        $req->bindValue(':user_id', $category->getUserId());
        $req->bindValue(':nom', $category->getNom());
        $req->bindValue(':description', $category->getDescription());
        $req->bindValue(':font_color', $category->getFontColor());
        $req->bindValue(':background_color', $category->getBackgroundColor());
        $reqExec = $req->execute();

        return $reqExec;

    }

    public function getCategories(){
        $db = $this->dbConnect();
        $req = $db->query("SELECT * FROM categories ORDER BY up_votes, nom");
        $categories = [];

        while($category = $req->fetchObject('App\Model\Entity\Category')){
            $categories[] = $category;
        }
        $req->closeCursor();

        if($categories == null){
            return "Aucunes catégories.";
        }
        return $categories;

    }

    public function getCategory($categoryId){
        $db = $this->dbConnect();
        $req = $db->prepare("SELECT * FROM categories WHERE id = ?");
        $req->execute(array($categoryId));

        if($req !== false){
            $category = $req->fetchObject('App\Model\Entity\Category');

            $req->closeCursor();
            return $category;
        }else {
            $error = $db->errorInfo()[2];
            return $error;
        }
    }

    public function doesMovieExist(Movie $movie){
        $req_movie = $this->db->prepare("SELECT 'id' FROM movies WHERE id = ?");
        $req_movie->execute(array($movie->getId()));
        // return boolean
        if($req_movie->rowCount() == 0){
            return false;
        }elseif($req_movie->rowCount() > 0){
            return true;
        }else{
            return "error " . $req_movie->errorInfo();
        }
    }

    public function createMovie(Movie $movie){
        //vérifier le nombre de lignes insérées > 0 pour être sur que l'insertion a bien marché et ne pas allé plus loin si ce n'est pas le cas
        $insert_movie = $this->db->prepare('INSERT INTO movies(id, title) VALUES(:id, :title)');

        $insert_movie->bindValue(':id', $movie->getId());
        $insert_movie->bindValue(':title', $movie->getTitle());

        $insert_movie_exec = $insert_movie->execute();
        if($insert_movie_exec == 1){
            return true;
        }else{
            $error_info = $insert_movie->errorInfo();
            return "erreur dans la création du film en base : " . $error_info[2];
        }
    }

    public function createMovieConnection(MCUConnection $newMCUConnection){
        $db = $this->dbConnect();

        $req = $db->prepare("INSERT INTO mcu_connection (movie_id, category_id, user_id, justification_comment) VALUES(:movie_id, :category_id, :user_id, :justification_comment)");

//        vd($db, $req, $newMCUConnection, $newMCUConnection->getMovieId());
        $req->bindValue(':movie_id', $newMCUConnection->getMovieId());
        $req->bindValue(':category_id', $newMCUConnection->getCategoryId());
        $req->bindValue(':user_id', $newMCUConnection->getUserId());
        $req->bindValue(':justification_comment', $newMCUConnection->getJustificationComment());

        $reqExec = $req->execute();

        return $reqExec;

    }

}