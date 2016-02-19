<?php
/**
 * Fonction qui va demander l'affichage de la vue correspondant à la fenêtre de login
 */
function frmConnect() {
    global $twig;
    $template = $twig->loadTemplate('login.html.twig');  
    $tablo=array();
    if (isset($_GET['mess'])) {

        $tablo["mess"] = $_GET['mess'];
    }
    echo $template->render($tablo);
}
/**
 * Vérification du login et password de l'utilisateur de l'application
 * cette fonction redirigera vers le dispatcher de l'application
 * en fonction du niveau d'autorisation de l'utilisateur de l'application * 
 */
function verifLogin() {
    try {
        // on va commencer par récupérer le login et le mdp saisis
        $login = filter_input(INPUT_POST, 'inputEmail', FILTER_SANITIZE_STRING);
        $pwd = filter_input(INPUT_POST, 'inputPassword', FILTER_SANITIZE_STRING);
        /* on va ouvrir la connexion
         * Comment ? 
         * On va créer un fichier connect.php dans le dossier core
         * Pourquoi dans le dossier core
         * Parce que la connexion à la BD est indépendnte de 
         * l'application et que je suis susceptible de me resservir
         * de ce dossier et de ses fichiers dans d'autres projets php
         */
        // on charge le fichier qui contient la fonction
        include CORE_PATH . 'connect.php';
        // on appelle la fonction
        $connect = connexion();
        // si tout se passe bien,on va aller vérifier que le login et le 
        // pwd sont bons
        // et récupérer le niveau de l'utilisateur pour afficher la suite
        // qui dépend de ce niveau
        /*
         * On va faire appel à la fonction authentification dans le fichier
         * connect.php qui acceptera en paramètre
         * - la connexion
         * -le login saisi
         * -les mot de passe saisi
         * 
         * et qui retournera le niveau de l'utilisateur
         * OU
         * une Exception que l'on aura déclenchée dans les cas suivants
         *  - utlisateur inexistant
         *  - mot de passe éronné
         */
        $niveau = authentification($connect, $login, $pwd);
        /*
         * Si on arrive à récupérer le niveau, on va garder le garder en 
         * variables de session avec le login de l'utilisaateur
         * OUPS ...
         * il faut s'assurer que la session est démarrer (session_start())
         * que l'on va mettre en haut du fichier index.php
         */
        $_SESSION['niveau'] = $niveau;
        $_SESSION['login'] = $login;
        // on va rediriger vers le controleur login
        // la méthode dispatch qui va tester le niveau 
        // et renvoyer la bonne action du bon controleur
        header('location:index.php?c=login&a=dispatch&niveau=' . $niveau);
    } catch (PDOException $ex) {
        // si on a une erreur  la connexion, on va
        // faire une redirection
        // et on va envoyer un message dans l'URL en GET
        // on va rappeler le formulaire de connexion
        // on va donc aller modifier la méthode frmConnect pour gérer
        // l'affichage du message
        header('location:index.php?c=login&a=frmConnect&mess=Erreur à la connexion'.$ex->getMessage());
    } catch (Exception $ex) {
        // on intercepte les autres exceptions notamment
        // celles qui viennent d'un mauvais login ou mdp
        // on va rediriger vers la page index qui affichera à nouveau la
        // fenêtre de login
        header('location:index.php?c=login&a=frmConnect&mess=' . $ex->getMessage());
    }
}
/**
 * en fonction du niveau d'autorisation de l'utilisateur, appel
 * du controlleur adéquate
 * @throws Exception si le niveau est inconnu de l'application
 */
function dispatch() {
    // on técupère la variabble stockant le niveau
    $niveau = filter_input(INPUT_GET, 'niveau', FILTER_SANITIZE_NUMBER_INT);
    // on aurait aussi pu passer par la varoable de session
    //$niveau=$_SESSION['niveau'];
    switch ($niveau) {
        case 1 : // administrateurs controlleur backend
            header('location:index.php?c=backend&a=menu');
            // on va écrire le controlleur backend.php
            break;
        case 2: // utilisateurs de l'application controlleur frontend
            header('location:index.php?c=frontend&a=menu');
            // on va écrire le controlleur frontend.php
            break;
        default:
            throw new Exception("Erreur de niveau");
    }
}
