<?php

namespace App\Controller;

use App\Model\Entity\User;
use App\Model\Manager\UserManager;

class MainController extends AbstractController
{
    public function home(){

        echo $this->twig->render('home.twig');
    }
    public function register(){
        $UserManager = new UserManager();

        if($this->isPost()){

            $pseudo = trim(htmlspecialchars($_POST['pseudo']));
            $password = trim(htmlspecialchars($_POST['password']));
            $confirm_password = trim(htmlspecialchars($_POST['confirm_password']));
            $email = trim(htmlspecialchars($_POST['email']));

            $newUser = new User($pseudo, $password, $confirm_password, $email);
            if($newUser->isValid() == true){
                $userRegistration = $UserManager->register($newUser);
                if($userRegistration == true){
                    vd('votre compte a bien été créé !');
                }else{
                    // afficher un message d'erreur
                }
            }else{
                // afficher l'erreur dans un message flash
            }
        }
            echo $this->twig->render('register.twig');
    }

    public function test(){

        echo $this->twig->render('test.twig');
    }

    public function contact(){
        echo $this->twig->render('contact.twig');
    }


}
