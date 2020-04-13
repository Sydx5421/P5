<?php


namespace App\Controller;


class UserActionsController extends AbstractController
{

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