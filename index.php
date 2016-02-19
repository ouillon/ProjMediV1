<?php
/**
 * Point d'entrée de l'application
 */
define('RACINE', __DIR__);

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

if (count($_GET) < 2) {
// on démarre l'appli et on va impose le controleur et l'action
    $controller = 'login';   // controller login
    $action = 'frmConnect'; // la fonction frmConnect du controller login
} 
else{
    // le controlleur et l'action ont été passés dans l'url
    $controller=filter_input(INPUT_GET,'c',FILTER_SANITIZE_STRING);
    $action=filter_input(INPUT_GET,'a',FILTER_SANITIZE_STRING);
}

// on va charger le controlleur correspondant
include CONTROLLER_PATH.$controller."Controller.php";
// on va appeler la fonction dont le nom est la valeur de 
// la variable $action
$action();
// si $action vaut "frmConnect", on appellera la fonction frmConnect()  
?>            

</body>
</html>
