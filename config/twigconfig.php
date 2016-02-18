<?php
/**
 * @author GVMT
 */
include_once(TWIG_PATH.'Autoload.php');
    
$loader = new Twig_Loader_Filesystem(VIEWS_PATH); // Dossier contenant les templates
$twig = new Twig_Environment($loader, array(
      'cache' => false
    ));
?>