<?php


namespace App\Model\Manager;

use App\Model\Entity\Category;
use App\Model\Entity\Movie;
use App\Model\Manager\AbstractManager;
use App\Model\Entity\MCUConnection;

class McuManager extends AbstractManager
{

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


    public function getMcuMovie($movieId, $categoryId){
        $db = $this->dbConnect();
//        $req = $db->prepare("SELECT * FROM mcu_connection WHERE movie_id = ? AND category_id = ? ");
        $req = $db->prepare("SELECT mcu.id, mcu.user_id, mcu.justification_comment, mcu.creation_date, u.pseudo
                                        FROM mcu_connection mcu
                                        INNER JOIN users u
                                        ON mcu.user_id = u.id
                                        WHERE movie_id = ? AND category_id = ? ");
        $req->execute(array($movieId, $categoryId));

        $mcuList = [];

        while($mcuElt = $req->fetchObject('App\Model\Entity\MCUConnection')){
            $mcuList[] = $mcuElt;
        }
        $req->closeCursor();

        if($mcuList == null){
            return "Aucun commentaire justifiant le classement de ce film dans cette catégorie.";
        }
        return $mcuList;

    }

    public function deleteComment($mcuId){
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE  mcu_connection set justification_comment = NULL WHERE id = ?');
        $req->execute(array($mcuId));

        return $req;
    }

    public function getMcuFromUser($userId){
        $db = $this->dbConnect();
        $req = $db->prepare("SELECT mcu.id, mcu.movie_id, mcu.category_id, mcu.user_id,  mcu.justification_comment, mcu.creation_date, cat.nom, cat.font_color, cat.background_color, m.title, m.poster_path
                                        FROM (mcu_connection mcu
                                        INNER JOIN movies m
                                        ON (mcu.movie_id = m.id))
                                        INNER JOIN categories cat
                                        ON (mcu.category_id = cat.id)
                                        WHERE mcu.user_id = ?  ");
        $req->execute(array($userId));

        $mcuList = [];

        while($mcuElt = $req->fetchObject('App\Model\Entity\MCUConnection')){
            $mcuList[] = $mcuElt;
        }
        $req->closeCursor();

        if($mcuList == null){
            return "Vous n'avez établie aucune connexion entre films et catégories pour l'instant.";
        }
        return $mcuList;
    }
}