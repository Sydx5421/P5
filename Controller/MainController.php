<?php

namespace App\Controller;

use App\Library\API\TmdbApi;
use App\Model\Entity\User;
use App\Model\Manager\UserManager;

class MainController extends AbstractController
{
    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function home(){
        $TmdbApi = new TmdbApi("01caf40148572dc465c9503e59ded4bf");
        $randMovies =  $TmdbApi->getRandomMovies();

        $randMovie1 = $randMovies[0];
        $randMovie2 = $randMovies[1];
        $randMovie3 = $randMovies[2];

        echo $this->render('home.twig', array('randMovies' => $randMovies) );

    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function register(){
        $UserManager = new UserManager();

        if($this->isPost()){
            $pseudo = trim(htmlspecialchars($_POST['pseudo']));
            $password = trim(htmlspecialchars($_POST['password']));
            $confirm_password = trim(htmlspecialchars($_POST['confirm_password']));
            $email = trim(htmlspecialchars($_POST['email']));

            $newUserData = [
                'pseudo' => $pseudo,
                'password' => $password,
                'confirmPassword' => $confirm_password,
                'email' => $email
            ];

            $newUser = new User($newUserData);

            if($newUser->isValid() === true){
                $userRegistration = $UserManager->register($newUser);
                if($userRegistration === true){
                    $this->addFlash('Votre compte a bien été créé ! Bienvenue sur Cinemood ' . $newUser->getPseudo()
                        . '. Vous pouvez dès à présent vous connecter.', 'success');
                    $this->redirect('home');
                }else{
                    // Erreur au niveau de l'enregistrement dans la base (mail ou pseudo déjà utilisés)
                    $this->addFlash('Erreur : ' . $userRegistration, 'danger');
                }
            }else{
                // Erreurs au niveau de la validation du formulaire d'inscription (mal rempli)
                $this->addFlash('Erreur : ' . $newUser->isValid(), 'danger');
            }
        }
            echo $this->render('register.twig');
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function login(){
        $UserManager = new UserManager();

        if($this->isPost()){

            if(isset($_POST['email_or_pseudo']) && isset($_POST['password'])){
//                vd('!', $this->isPost(), (isset($_POST['email_or_pseudo']) && isset($_POST['password'])), (!empty
//                    ($_POST['email_or_pseudo']) && !empty($_POST['password'])));
                if(!empty($_POST['email_or_pseudo']) && !empty($_POST['password'])){
                    $password = htmlspecialchars($_POST['password']);
                    $login = htmlspecialchars($_POST['email_or_pseudo']);

                    $userLogin = $UserManager->login($login, $password);

                    if(is_object($userLogin)){
                        $userLogged = new User($userLogin);
                        $_SESSION['user'] = $userLogin;
                        $this->addFlash('Bienvenue ' . $userLogged->getPseudo());
                        $this->redirect('dashboard');
                        exit();
                    }else{
                        $this->addFlash($userLogin);
                        $this->redirect('dashboard');
                    }
                }else{
                    $this->addFlash('Veuillez remplir tous les champs afin de pouvoir vous connecter.');
                    $this->redirect('dashboard');
                }
            }

        }
        $this->redirect('home');
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function dashboard(){

        echo $this->render('dashboard.twig');
    }

    /**
     *
     */
    public function deconnexion(){
        session_destroy();
        $this->redirect('home');
    }


}
