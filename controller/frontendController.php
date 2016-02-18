<?php

/**
 * affichera le menu général de l'application frontend
 */
function menu() {
// rien à faire d'autre que d'appeler la vue de menu
// qui s'appelle frontendmenu.php dans le dossier views
// il faut créer cette vue
    global $twig;
    $template = $twig->loadTemplate('menu.html.twig');
    $entry=array("lien"=>"index.php?c=frontend&a=creerClient", "value"=>"création d'un client");
    $menuEntry=array(
        array("lien"=>"index.php?c=frontend&a=creerClient", "valeur"=>"création d'un client"),
        array("lien"=>"index.php?c=frontend&a=creerCommande", "valeur"=>"créer une commande")
    );
    $tablo = array("menuEntrys" => $menuEntry);   
    echo $template->render($tablo);
    
}
/**
 * cette fonction doit
 *  - se connecter à la bd
 *  - aller chercher les clients pour remplir la liste déroulante
 *  - afficher la vue creercommande.html.twig
 
 */
function creerCommande() {
    /*
    
     */
    include CORE_PATH . 'connect.php';
    include MODEL_PATH . 'clientRepository.php';
    $connect = connexion();
    $clients = trouveClients($connect);
     global $twig;
    $template = $twig->loadTemplate('creerCommande.html.twig');

    $tablo = array("clients" => $clients);
    if (isset($_GET['mess'])) {
        $tablo['mess']= $_GET['mess'];
    } 
    echo $template->render($tablo);
}

/**
* cette fonction va récupérer les données du formulaire
* et elle va appeler la fonction enregistrercommande
* du model clientRepository qui va appeler la procédure stockée
* si ça se passe bien , on revient sur cet ecran avec un message 
* "commande enregistrée"
* si ça se passe mal, on interceptera un exception PDO
* et on reviendra sur l'écran de saisie de commande 
* en affichant le message
 */
function enrCommande() {
 
    include CORE_PATH . 'connect.php';
    include MODEL_PATH . 'clientRepository.php';

// on récupère les infos du formulaire
    $idClient = filter_input(INPUT_POST, 'listeclients', FILTER_SANITIZE_NUMBER_INT);
    $dateCde = $_POST['zs_date'];
    try {
// avant de continuer, on va aller creer la fonction
// enregistrercommande dans le model clientRepository
        $connect = connexion();
        enregistrerCommande($connect, $idClient, $dateCde);
        $mess = "commande enregistrée";
    } catch (PDOException $ex) {

        $mess = retourneErreurOracle($ex);
    } finally {
        header("location:index.php?c=frontend&a=creerCommande&mess=" . $mess);
    }
}
