<?php

namespace App\Model\Manager;

use App\Model\Entity\User;
use App\Model\Manager\AbstractManager;

require_once('AbstractManager.php');

class UserManager extends AbstractManager
{
    public function register(User $user){

        $db = $this->dbConnect();

        // checking that the email is not already used :
        $req_email = $db->prepare("SELECT * FROM users WHERE email = ?");
        $reqExec_email = $req_email->execute(array($user->getEmail()));
        if($reqExec_email === true){
            return "Cet email est déjà utilisé par un autre utilisateur, veuillez en choisir un autre.";
        }

        // checking that the pseudo is not already taken :
        $req_pseudo = $db->prepare("SELECT * FROM users WHERE pseudo = ?");
        $reqExec_pseudo = $req_pseudo->execute(array($user->getPseudo()));
        if($reqExec_pseudo === true){
            return "Ce pseudo est déjà utilisé par un autre utilisateur, veuillez en choisir un autre.";
        }

        // Now that we checked that the pseudo and email of the new user are not already taken, we can proceed with the
        // registration.

        $req = $db->prepare('INSERT INTO users(pseudo, password, email) VALUES(:pseudo, :password, :email)');

        $req->bindValue(':pseudo', $user->getPseudo());
        $req->bindValue(':password', $user->getPassword());
        $req->bindValue(':email', $user->getEmail());
        $reqExec = $req->execute();

        return $reqExec;

    }

    public function login($login, $password){
        $db = $this->dbConnect();
        // connexion attempt via pseudo
        $req_pseudo = $db->prepare("SELECT * FROM users WHERE pseudo = ? AND password = ?");
        $reqExec_pseudo = $req_pseudo->execute(array($login, $password));

        if($req_pseudo->rowCount() == 1){
//            $user = $req_pseudo->fetchObject('User');
//            vd('connecté via pseudo', $req_pseudo->fetchObject());
            return $req_pseudo->fetchObject();
        }else{
            // connexion attempt via email
            $req_email = $db->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
            $reqExec_email = $req_email->execute(array($login, $password));

            if($req_email->rowCount() == 1) {
//                vd('connecté via email', $req_email->fetchObject());
                return $req_email->fetchObject();
            }else{
                vd($login, $password, $reqExec_pseudo, $reqExec_email, $req_email->rowCount(), "Nom d'utilisateur ou mot de passe incorrect");
    //            $error = "Nom d'utilisateur o mot de passe incorrect";
                return "Login ou mot de passe incorrect";
            }
        }
    }

}