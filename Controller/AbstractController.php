<?php

namespace App\Controller;
//use App\vendor\twig\twig\lib\Twig\Extension\Twig_Extension_Session;
use App\vendor\twig\twig\lib\Twig\Extension\Session;

abstract class AbstractController
{
    protected $basePath;
    protected $isAdmin = false;

    //--- twig related vars ---
    protected $templatePath;
    protected $loader;
    protected $twig;
    protected $session;

    
    public function __construct() {
        // création dynamique du $basePath
        $contextDocumentRoot = $_SERVER["CONTEXT_DOCUMENT_ROOT"];      
        $rootDir = str_replace('\\', '/', realpath(__DIR__.'/../'));
        $relativeRootDir = str_replace($contextDocumentRoot, '', $rootDir);      
        
        $this->basePath =  $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . $relativeRootDir . '/';
        
        // isAdmin ?
//        if(isset($_SESSION['adminConnected']) && $_SESSION['adminConnected'] === true ){
//            $this->isAdmin = true;
//        }

        //configuration twig
        $this->templatePath = realpath(__DIR__.'/../View');
        $this->loader = new \Twig\Loader\FilesystemLoader($this->templatePath);
        $this->twig = new \Twig\Environment($this->loader, ['cache'=>false]);

        $this->twig->addExtension(new Session());

        // on récupère le tableau de session $session
        $this->session = $_SESSION;
    }

    /**
     * Cette methode est un racourcit de la méthode twig premettant de retourner les vues + elle est enrichie de
     * toutes les variables essentielles de l'application auxquelles on voudra avoir accès dans les vues (le basepath,
     * le tableau de session et le message flash)
     * @param $view
     * @param array $params
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function render($view, $params = array()){
        $app = new \stdClass();
        $app->basepath = $this->basePath;
        $app->session = $this->session;
        $app->flash = "";
        if(isset($_SESSION["message_flash"])){
            $app->flash = $_SESSION["message_flash"];
        }
        $app->isAdmin = $this->isAdmin;
        // On enrichi le tableau $params du sous-tableau app qui contien toutes les variables propre à l'application
        // auquelles ont voudra avoir accès facilement dans les vus twig
        $params['app'] = $app;

        // unset du message flash, pour qu'il ne s'affiche qu'une seul fois
        unset($_SESSION["message_flash"]);

        return $this->twig->render($view, $params);
    }

    /**
     * @param $url
     */
    protected function redirect($url){
        header("Location: $url");
        die;
    }

    /**
     * @param $message
     * @param string $type
     */
    protected function addFlash($message, $type = 'info'){
        $_SESSION["message_flash"] = [
           'message' => $message,
           'type' => 'alert-' . $type, //(permet d'exploité direct l'info comme class bootstrap)
        ];
    }

    /**
     * @return bool
     */
    protected function isPost(){
        return ($_SERVER['REQUEST_METHOD'] == 'POST');
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function notFound(){
        echo $this->render('notFound.twig');
    }
    
}
