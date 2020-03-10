<?php

namespace App\Controller;
 
abstract class AbstractController
{
    protected $basePath;
    protected $isAdmin = false;
    
    public function __construct() {
        // création dynamique du $basePath
        $contextDocumentRoot = $_SERVER["CONTEXT_DOCUMENT_ROOT"];      
        $rootDir = str_replace('\\', '/', realpath(__DIR__.'/../'));
        $relativeRootDir = str_replace($contextDocumentRoot, '', $rootDir);      
        
        $this->basePath =  $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . $relativeRootDir . '/';
        
        // isAdmin ?
        if(isset($_SESSION['adminConnected']) && $_SESSION['adminConnected'] === true ){
            $this->isAdmin = true;             
        }
        
    }
    
    
    protected function addFlash($message, $type = 'info', $redirect = false){
        $_SESSION["message_flash"] = [
           'message' => $message,
           'type' => 'alert-' . $type, //(permet d'exploité direct l'info comme class bootstrap)
           'redirect' => $redirect
        ];
        
    }
    
    protected function isPost(){
        return ($_SERVER['REQUEST_METHOD'] == 'POST');
    }
    
    public function notFound(){
        require 'View/404View.php';   
    }
    
}
