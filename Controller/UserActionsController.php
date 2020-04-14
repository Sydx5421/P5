<?php


namespace App\Controller;

use App\Model\Entity\Category;
use App\Model\Manager\CategoryManager;

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

    public function createCategory(){
        $CategoryManager = new CategoryManager();

        if($this->isPost()){
            $newCategoryData = [
                'user_id' => trim(htmlspecialchars($_POST['userId'])),
                'nom' => trim(htmlspecialchars($_POST['nameCategory'])),
                'description' => trim(htmlspecialchars($_POST['descriptionCat'])),
                'font_color' => trim(htmlspecialchars($_POST['backgroundColor'])),
                'background_color' => trim(htmlspecialchars($_POST['fontColor']))
            ];

            $newCategory = new Category($newCategoryData);
//            vd($newCategory);

            if($newCategory->isValid() === true){
                $categoryCreation = $CategoryManager->create($newCategory);
                if($categoryCreation === true){
                    $this->addFlash('Votre catégorie "'  . $newCategory->getNom()
                        .  '" a bien été créé ! Vous pouvez dès à présent vous l\'utiliser.', 'success');
                    $this->redirect('categories');
                }else{
                    // Erreur au niveau de l'enregistrement dans la base (mail ou pseudo déjà utilisés)
                    $this->addFlash('Erreur : ' . $categoryCreation, 'danger');
                }

            }else{
                // On renvoie l'erreur retourner par le manager:
                $this->addFlash('Erreur : ' . $newCategory->isValid(), 'danger');
            }

        }

        $categories =  $CategoryManager->getCategories();

        echo $this->render('categories.twig', array('categories' => $categories));

    }
}








