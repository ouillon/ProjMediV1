<?php

function menu() {
    global $twig;
    $template = $twig->loadTemplate('menu.html.twig');
    $entry = array("lien" => "index.php?c=frontend&a=creerClient", "value" => "création d'un client");
    $menuEntry = array(
        array("lien" => "index.php?c=backend&a=creerUtilisateur", "valeur" => "création d'un utilisateur"),
        array("lien" => "index.php?c=backend&a=modifierUtilisateur", "valeur" => "Modifier un Utilisateur")
    );
    $tablo = array("menuEntrys" => $menuEntry);
    echo $template->render($tablo);
}

function creerUtilisateur() {
    global $twig;
    $template = $twig->loadTemplate('todo.html.twig');
    echo $template->render();
}

function modifierUtilisateur() {
    global $twig;
    $template = $twig->loadTemplate('todo.html.twig');
    echo $template->render();
}
