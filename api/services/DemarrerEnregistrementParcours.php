<?php
// Projet TraceGPS - services web
// fichier : api/services/Connecter.php
// Dernière mise à jour : 3/7/2021 par dP

// Rôle : ce service permet à un utilisateur de s'authentifier
// Le service web doit recevoir 3 paramètres :
//     pseudo : le pseudo de l'utilisateur
//     mdp : le mot de passe hashé en sha1
//     lang : le langage du flux de données retourné ("xml" ou "json") ; "xml" par défaut si le paramètre est absent ou incorrect
// Le service retourne un flux de données XML ou JSON contenant un compte-rendu d'exécution

// Les paramètres doivent être passés par la méthode GET :
//     http://<hébergeur>/tracegps/api/Connecter?pseudo=europa&mdp=13e3668bbee30b004380052b086457b014504b3e&lang=xml

// Pour tester le service avec CURL :
// curl -i -X GET "http://localhost/ws-php-cartron/tracegps/api/Connecter?pseudo=europa&mdp=13e3668bbee30b004380052b086457b014504b3e&lang=json"
// curl -i -X GET "http://localhost/ws-php-cartron/tracegps/api/Connecter?pseudo=europa&mdp=13e3668bbee30b004380052b086457b014504b3e&lang=xml"
// curl -i -X POST "http://localhost/ws-php-cartron/tracegps/api/Connecter?pseudo=europa&mdp=13e3668bbee30b004380052b086457b014504b3e&lang=json"

// curl -i -X GET "http://sio.lyceedelasalle.fr/tracegps/api/Connecter?pseudo=europa&mdp=13e3668bbee30b004380052b086457b014504b3e&lang=json"
// curl -i -X GET "http://sio.lyceedelasalle.fr/tracegps/api/Connecterr?pseudo=europa&mdp=13e3668bbee30b004380052b086457b014504b3e&lang=json"
// curl -i -X GET "http://sio.lyceedelasalle.fr/tracegps/api/connecter?pseudo=europa&mdp=13e3668bbee30b004380052b086457b014504b3e&lang=json"

// connexion du serveur web à la base MySQL
$dao = new DAO();

// Récupération des données transmises
$pseudo = ( empty($this->request['pseudo'])) ? "" : $this->request['pseudo'];
$mdpSha1 = ( empty($this->request['mdp'])) ? "" : $this->request['mdp'];
$lang = ( empty($this->request['lang'])) ? "" : $this->request['lang'];

// "xml" par défaut si le paramètre lang est absent ou incorrect
if ($lang != "json") $lang = "xml";

// La méthode HTTP utilisée doit être GET
if ($this->getMethodeRequete() != "GET")
{	$msg = "Erreur : méthode HTTP incorrecte.";
$code_reponse = 406;
}
else {
    // Les paramètres doivent être présents
    if ( $pseudo == "" || $mdpSha1 == "" )
    {	$msg = "Erreur : données incomplètes.";
    $code_reponse = 400;
    }
    else
    {	
        if ($dao->getNiveauConnexion($pseudo, $mdpSha1) == 0){
            $msg = "Erreur : authentification incorrecte.";
            $code_reponse = 401;
        }else{
            date_default_timezone_set('Europe/Paris');
            $uneTrace = new Trace("", date('Y-m-d H:i:s'), null, 0, $dao->getUnUtilisateur($pseudo)->getId());
            $dao->creerUneTrace($uneTrace);
            $msg = "Trace créée.";
            $code_reponse = 200;
        }
    }
}
// ferme la connexion à MySQL :
unset($dao);

// création du flux en sortie
if ($lang == "xml") {
    $content_type = "application/xml; charset=utf-8";      // indique le format XML pour la réponse
    $donnees = creerFluxXML ($msg,$uneTrace);
}
else {
    $content_type = "application/json; charset=utf-8";      // indique le format Json pour la réponse
    $donnees = creerFluxJSON ($msg,$uneTrace);
}

// envoi de la réponse HTTP
$this->envoyerReponse($code_reponse, $content_type, $donnees);

// fin du programme (pour ne pas enchainer sur les 2 fonctions qui suivent)
exit;

// ================================================================================================

// création du flux XML en sortie
function creerFluxXML($msg, $uneTrace)
{
    /* Exemple de code XML
     <?xml version="1.0" encoding="UTF-8"?>
     <!--Service web Connecter - BTS SIO - Lycée De La Salle - Rennes-->
     <data>
     <reponse>Erreur : données incomplètes.</reponse>
     </data>
     */
    
    // crée une instance de DOMdocument (DOM : Document Object Model)
    $doc = new DOMDocument();
    
    // specifie la version et le type d'encodage
    $doc->version = '1.0';
    $doc->encoding = 'UTF-8';
    
    // crée un commentaire et l'encode en UTF-8
    $elt_commentaire = $doc->createComment('Service web THE BIB doit le changer - BTS SIO - Lycée De La Salle - Rennes');
    // place ce commentaire à la racine du document XML
    $doc->appendChild($elt_commentaire);
    
    // crée l'élément 'data' à la racine du document XML
    $elt_data = $doc->createElement('data');
    $doc->appendChild($elt_data);
    
    // place l'élément 'reponse' juste après l'élément 'data'
    $elt_reponse = $doc->createElement('reponse', $msg);
    $elt_data->appendChild($elt_reponse);
    
    if ($uneTrace != null){
        // place l'élément 'donnees' dans l'élément 'data'
        $elt_donnees = $doc->createElement('donnees');
        $elt_data->appendChild($elt_donnees);
        
        // crée un élément vide 'trace'
        $elt_trace = $doc->createElement('trace');
        // place l'élément 'trace' dans l'élément 'donnees'
        $elt_donnees->appendChild($elt_trace);
        
        $elt_id = $doc->createElement('id', $uneTrace->getId());
        $elt_trace->appendChild($elt_id);
        $elt_dateHeureDebut = $doc->createElement('dateHeureDebut', $uneTrace->getDateHeureDebut());
        $elt_trace->appendChild($elt_dateHeureDebut);
        $elt_terminee = $doc->createElement('terminee', $uneTrace->getTerminee());
        $elt_trace->appendChild($elt_terminee);
        $elt_dateHeureFin = $doc->createElement('dateHeureFin', $uneTrace->getDateHeureFin());
        $elt_trace->appendChild($elt_dateHeureFin);
        $elt_idUtilisateur = $doc->createElement('idUtilisateur', $uneTrace->getIdUtilisateur());
        $elt_trace->appendChild($elt_idUtilisateur);
    }
    // Mise en forme finale
    $doc->formatOutput = true;
    
    // renvoie le contenu XML
    return $doc->saveXML();
}

// ================================================================================================

// création du flux JSON en sortie
function creerFluxJSON($msg, $uneTrace)
{
    /* Exemple de code JSON
     {
     "data":{
     "reponse": "authentification incorrecte."
     }
     }
     */
    
    // 2 notations possibles pour créer des tableaux associatifs (la deuxième est en commentaire)
    
    // construction de l'élément "data"
    if ($uneTrace == null) {
        // construction de l'élément "data"
        $elt_data = ["reponse" => $msg];
    }
    else {
        
        $unObjetTrace = array();
        $unObjetTrace["id"] = $uneTrace->getId();
        $unObjetTrace["dateHeureDebut"] = $uneTrace->getDateHeureDebut();
        $unObjetTrace["terminee"] = $uneTrace->getTerminee();
        $unObjetTrace["dateHeureFin"] = $uneTrace->getDateHeureFin();
        $unObjetTrace["idUtilisateur"] = $uneTrace->getIdUtilisateur();
        $unObjetTrace;
    }
    // construction de l'élément "donnees"
    $elt_donnees = ["trace" => $unObjetTrace];
    // construction de l'élément "data"
    $elt_data = ["reponse" => $msg,"donnees" => $elt_donnees];
    // construction de la racine
    $elt_racine = ["data" => $elt_data];
    //     $elt_racine = array("data" => $elt_data);
    
    // retourne le contenu JSON (l'option JSON_PRETTY_PRINT gère les sauts de ligne et l'indentation)
    return json_encode($elt_racine, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

// ================================================================================================
?>
