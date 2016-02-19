<?php
/**
 * Va chercher les informations de connexion à la BD
 * Dans le fichier configBD.xml
 * @return \PDO ou Exception
 */
function connexion() {
    $params = new DOMDocument();
    $params->validateOnParse = true;
    $params->load(XML_PATH . 'configBD.xml');
    $serveur = $params->getElementByID(SERVEUR);
    $tns=$serveur->getElementsByTagName('chaine')->item(0)->nodeValue;
    //$db_username = $serveur->getElementsByTagName('inputEmail')->item(0)->nodeValue;
    //$db_password = $serveur->getElementsByTagName('inputPassword')->item(0)->nodeValue;

    $options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    try {
       // $conn = new PDO("oci:dbname=" . $tns, $db_username, $db_password, $options);
        $conn = new PDO('mysql:host=localhost;dbname=medicament', 'root', ''); 
        return $conn;
    } catch (PDOException $e) {
        echo ('toto'.$e->getMessage());
    }
}


/**
 * on va interroger la table utilisateur
 * et rechercher l'utilisateur qui a saisi son login
 * @param type $connect :objet PDO de connection à la BD
 * @param type $login : login saisi par l'utilisateur
 * @param type $pwd : mot de passe saisi par l'utilisateur
 * @return type : le niveau de l'utilisateur qui s'est authentifié
 * @throws Exception : si mauvais login ou mot de passe
 */
function authentification($connect, $login, $pwd){
    // 
    // Requête SQL 
    //$sql='select id, login, pwd, niveau from rochedemo.utilisateur where login=:plogin';
    $sql='select * from User where login=:plogin';
    // :plogin est le pparamètre
    $requete=$connect->prepare($sql);
    // on va binder le paramètre 
    $requete->bindValue(':plogin', $login, PDO::PARAM_STR);
    $requete->execute();
    /*
     * si la requête  ne ramène rien : mauvais login, on déclenche une exception
     * sinon
     * on vérifie le mdp
     *  si le mdp est bon on renvoie le niveau sinon
     *          on déclenche un exception
     */
    if($ligne = $requete->fetch(PDO::FETCH_ASSOC)){
        // il a trouvé le bon login
        // on va tester le mot de passe
        // il faut utiliser la fonction password_verify
        if(password_verify($pwd, $ligne['PWD'])){
            // le mot de passe est bon
            // on renvoie le niveau
            return $ligne['NIVEAU'];
        }
        else{
            // le mot de passe n'est pas bon
            // on renvoie une exception
            throw new Exception("mot de passe invalide");
        }
    }else{
        // le login n'est pas bon
        throw new Exception("Login incorrect");
    }
    // Attention à bien s'assurer que ces exceptions seront 
    // interceptées dans le script qui a fait l'appel de la fonction
    // c à dire dans la fonction verifLogin
}

/**
 * mise en forme d'une exception PDO provenant d'une base Oracle. 
 * @param type $e exception PDO provenant d'une base Oracle
 * @return type : code erreur et message provenant de la base de données
 */
function retourneErreurOracle($e) {
    $erreur = explode("ORA-", $e->getMessage());
    return $erreur[1];
}