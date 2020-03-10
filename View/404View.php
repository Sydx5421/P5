<?php $titleVue = "404 - Page introuvable"; ?>
<?php $pageClass = "notFound"?>
<?php ob_start(); ?>
<div class="text-center">
    <p>La page demandée n'existe pas ou plus.</p>
    <a class="nav-link" href="<?=$this->basePath?>home">Retour à l'accueil
        <span class="sr-only">(current)</span>
    </a>    
</div>
<?php $content = ob_get_clean();?>
<?php require('blogTemplate.php') ?>