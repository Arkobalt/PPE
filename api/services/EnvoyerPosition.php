<?php
// Projet TraceGPS - services web
// fichier : api/services/GetTousLesUtilisateurs.php
// Dernière mise à jour : 3/7/2021 par dP

// Rôle : ce service permet à un utilisateur authentifié d'obtenir la liste de tous les utilisateurs (de niveau 1)
// Le service web doit recevoir 3 paramètres :
//     pseudo : le pseudo de l'utilisateur
//     mdp : le mot de passe de l'utilisateur hashé en sha1
//     lang : le langage du flux de données retourné ("xml" ou "json") ; "xml" par défaut si le paramètre est absent ou incorrect
// Le service retourne un flux de données XML ou JSON contenant un compte-rendu d'exécution

// Les paramètres doivent être passés par la méthode GET :
//     http://<hébergeur>/tracegps/api/GetTousLesUtilisateurs?pseudo=callisto&mdp=13e3668bbee30b004380052b086457b014504b3e&lang=xml

// connexion du serveur web à la base MySQL
$dao = new DAO();

// Récupération des données transmises
$pseudo = ( empty($this->request['pseudo'])) ? "" : $this->request['pseudo'];
$mdpSha1 = ( empty($this->request['mdp'])) ? "" : $this->request['mdp'];
$idTrace = ( empty($this->request['idTrace'])) ? "" : $this->request['idTrace'];
$dateHeure = ( empty($this->request['dateHeure'])) ? "" : $this->request['dateHeure'];
$latitude = ( empty($this->request['latitude'])) ? "" : $this->request['latitude'];
$longitude = ( empty($this->request['longitude'])) ? "" : $this->request['longitude'];
$altitude = ( empty($this->request['altitude'])) ? "" : $this->request['altitude'];
$rythmeCardio = ( empty($this->request['rythmeCardio'])) ? "" : $this->request['rythmeCardio'];
$lang = ( empty($this->request['lang'])) ? "" : $this->request['lang'];
$lesPointsDeTraces = array();
$lesTraces = array();
$mesTraces = array();
// "xml" par défaut si le paramètre lang est absent ou incorrect
if ($lang != "json") $lang = "xml";

// La méthode HTTP utilisée doit être GET
if ($this->getMethodeRequete() != "GET")
{	$msg = "Erreur : méthode HTTP incorrecte.";
$code_reponse = 406;
}
else 
{
    // Les paramètres doivent être présents
    if ( $pseudo == "" || $mdpSha1 == "" )
    {	
        $msg = "Erreur : données incomplètes.";
        $code_reponse = 400;
    }
    else
    {	
        if ( $dao->getNiveauConnexion($pseudo, $mdpSha1) == 0 ) 
        {
            $msg = "Erreur : authentification incorrecte.";
            $code_reponse = 401;
        }
        else
        {
            if($idTrace == "" || $dateHeure == "" || $latitude == "" || $longitude == "" || $altitude == "" || $rythmeCardio == "")
            {	
                $msg = "Erreur : données incomplètes.";
                $code_reponse = 400;
            }
            else
            {
                $lesIds = array();
                $lesTraces = $dao->getToutesLesTraces();
                foreach ($lesTraces as $uneTrace)
                {
                    $id = $uneTrace->getId();
                    $lesIds[] = $id;
                }
                if(in_array($idTrace, $lesIds) == false)
                {
                    $msg = "Erreur : le numéro de trace n'existe pas.";
                    $code_reponse = 401;
                }
                else 
                {
                    $unUtilisateur = $dao->getUnUtilisateur($pseudo)->getId();
                    $mesIds = array();
                    $mesTraces = array();
                    $mesTraces = $dao->getLesTraces($unUtilisateur);
                    foreach ($mesTraces as $maTrace)
                    {
                        $id = $maTrace->getId();
                        $mesIds[] = $id;
                    }
                    if(in_array($idTrace, $mesIds) == false)
                    {
                        $msg = "Erreur : le numéro de trace ne correspond pas à cet utilisateur.";
                        $code_reponse = 402;
                    }
                    else 
                    {
                        $uneTrace = $dao->getUneTrace($idTrace);
                        if ($uneTrace->getTerminee() == 1)
                        {
                            $msg = "Erreur : la trace est déjà terminée.";
                            $code_reponse = 403;
                        }
                        else 
                        {   
                            $idPoint = $uneTrace->getNombrePoints() + 1;
                            $unPoint = new PointDeTrace($uneTrace->getId(),$idPoint,$latitude, $longitude, $altitude,$dateHeure,$rythmeCardio,0,0,0);
                            
                            if($dao->creerUnPointDeTrace($unPoint) == false)
                            {
                                $msg = "Erreur : problème lors de l'enregistrement du point.";
                                $code_reponse = 404;
                            }
                            else 
                            {
                                $msg = "Point créé.";
                                $code_reponse = 405;
                            }
                        }
                    }
                }
            }
        }
    }
}
// ferme la connexion à MySQL :
unset($dao);

// création du flux en sortie
if ($lang == "xml") {
    $content_type = "application/xml; charset=utf-8";      // indique le format XML pour la réponse
    $donnees = creerFluxXML($msg, $unPoint);
}
else {
    $content_type = "application/json; charset=utf-8";      // indique le format Json pour la réponse
    $donnees = creerFluxJSON($msg, $unPoint);
}

// envoi de la réponse HTTP
$this->envoyerReponse($code_reponse, $content_type, $donnees);
// fin du programme (pour ne pas enchainer sur les 2 fonctions qui suivent)
exit;

// ================================================================================================

// création du flux XML en sortie
function creerFluxXML($msg, $unPoint)
{
    /* Exemple de code XML
     <?xml version="1.0" encoding="UTF-8"?>
     <data>
         <reponse>Point créé.</reponse>
         <donnees>
            <id>6</id>
        </donnees>
     </data>
     */
    
    // crée une instance de DOMdocument (DOM : Document Object Model)
    $doc = new DOMDocument();
    
    // specifie la version et le type d'encodage
    $doc->version = '1.0';
    $doc->encoding = 'UTF-8';
    
    // crée l'élément 'data' à la racine du document XML
    $elt_data = $doc->createElement('data');
    $doc->appendChild($elt_data);
    
    // place l'élément 'reponse' dans l'élément 'data'
    $elt_reponse = $doc->createElement('reponse', $msg);
    $elt_data->appendChild($elt_reponse);
        
    // place l'élément 'donnees' dans l'élément 'data'
    $elt_donnees = $doc->createElement('donnees');
    $elt_data->appendChild($elt_donnees);
    // traitement des utilisateurs

    // crée les éléments enfants de l'élément 'utilisateur'
    $elt_id = $doc->createElement('id', $unPoint->getId());
    $elt_donnees->appendChild($elt_id);
    
    // Mise en forme finale
    $doc->formatOutput = true;
    
    // renvoie le contenu XML
    return $doc->saveXML();
}

// ================================================================================================

// création du flux JSON en sortie
function creerFluxJSON($msg, $unPoint)
{
    /* Exemple de code JSON
     {
        "data": {
        "reponse": "Point créé."
        "donnees": {
        "id": 7
        }
    }
}

     */
    
    
    if ($unPoint == NULL) {
        // construction de l'élément "data"
        $elt_data = ["reponse" => $msg];
    }
    else {
        	// crée une ligne dans le tableau
            $unObjetUtilisateur = array();
            $unObjetUtilisateur["id"] = $unPoint->getId();
            
       // construction de l'élément "data"
            $elt_data = ["reponse" => $msg, "donnees" => $unObjetUtilisateur];
    }
    
    // construction de la racine
    $elt_racine = ["data" => $elt_data];
    
    // retourne le contenu JSON (l'option JSON_PRETTY_PRINT gère les sauts de ligne et l'indentation)
    return json_encode($elt_racine, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

// ================================================================================================
?>
