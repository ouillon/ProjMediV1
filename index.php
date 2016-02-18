<?php
/**
 * Point d'entrée de l'application
 * Ce script sert de point d'entrée à l'application
 * En prmier lieu on définit le la constante RACINE qui représente
 * la racine du site
 */
define('RACINE', __DIR__);
/**
 *  on crée une constante alias de la constante DIRECTORY_SEPARATOR
 * qui représente de séparateur de dossiers
 */

define('DS', DIRECTORY_SEPARATOR);
/**
 *  on va insérer le fichier qui va définir les constantes de dossiers
 */
include 'config' . DS . 'define.php';
/**
 * insertion des classes twig
 */
include (CONFIG_PATH . 'twigconfig.php');
// fin de la configuration de ma page
// on va tester si l'URL passe des données en GET
// si elle n'en passe pas, cela veut dire qu'on lance l'application

// On démarre la session
session_start();

$controller = 'login';   // controller login
$action = 'frmConnect'; // la fonction frmConnect du controller login

// on va charger le controlleur correspondant
include CONTROLLER_PATH.$controller."Controller.php";
// on va appeler la fonction dont le nom est la valeur de 
// la variable $action
$action();
// si $action vaut "frmConnect", on appellera la fonction frmConnect()  
?>            

</body>
</html>
