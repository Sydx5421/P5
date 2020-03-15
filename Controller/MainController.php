<?php

namespace App\Controller;

class MainController extends AbstractController
{
    public function home(){

        echo $this->twig->render('home.twig');
    }

    public function test(){

        echo $this->twig->render('test.twig');
    }

    public function contact(){
        echo $this->twig->render('contact.twig');
    }


}
