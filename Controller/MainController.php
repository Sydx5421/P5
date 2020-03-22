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

    public function login(){
        $UserManager = new UserManager();

        if($this->isPost()){
            if(isset($_POST['email_or_pseudo']) && isset($_POST['password'])){
                if(!empty($_POST['email_or_pseudo']) && !empty($_POST['password'])){
                    $password = htmlspecialchars($_POST['password']);
                    $login = htmlspecialchars($_POST['email_or_pseudo']);
//                    vd($_POST);
                    $userLogin = $UserManager->login($login, $password);
                    if(is_object($userLogin)){
                        $userLogged = new User($userLogin);
//                        $_SESSION['user'] = $userLogin;
                        vd('vous êtes connecté !', $userLogged);
                    }else{
                        vd($userLogin);
                        // afficher un message d'erreur
                    }
                }
            }else{
                $error = "Erreur";
            }
        }

        echo $this->twig->render('login.twig');

    }


    public function test(){

//        echo $this->twig->render('test.twig');
        echo phpinfo();
    }

    public function contact(){
        echo $this->twig->render('contact.twig');
    }


}
