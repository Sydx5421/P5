<?php

namespace App\Model\Manager;

use App\Model\Entity\User;
use App\Model\Manager\AbstractManager;

require_once('AbstractManager.php');

class UserManager extends AbstractManager
{
    public function register(User $user){

        $db = $this->dbConnect();

        $req = $db->prepare('INSERT INTO users(pseudo, password, email) VALUES(:pseudo, :password, :email)');

        $req->bindValue(':pseudo', $user->getPseudo());
        $req->bindValue(':password', $user->getPassword());
        $req->bindValue(':email', $user->getEmail());
        $reqExec = $req->execute();

        return $reqExec;

    }
}