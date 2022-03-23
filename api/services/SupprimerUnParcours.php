<?php
// Projet TraceGPS - services web
// fichier : api/services/SupprimerUnParcours.php
// Dernière mise à jour : 3/7/2019 par Jim

// Rôle : ce service permet à un utilisateur de supprimer un de ses parcours 
// Le service web doit recevoir 4 paramètres :
//     pseudo : le pseudo de l'utilisateur qui demande à supprimer
//     mdp : le mot de passe hashé en sha1 de l'utilisateur qui demande à supprimer
//     idTrace : l'id de la trace à supprimer
//     lang : le langage du flux de données retourné ("xml" ou "json") ; "xml" par défaut si le paramètre est absent ou incorrect
// Le service retourne un flux de données XML ou JSON contenant un compte-rendu d'exécution

// Les paramètres doivent être passés par la méthode GET :
//     http://<hébergeur>/tracegps/api/SupprimerUnParcours?pseudo=europa&mdp=13e3668bbee30b004380052b086457b014504b3e&idTrace=25&lang=xml

// connexion du serveur web à la base MySQL
$dao = new DAO();
	
// Récupération des données transmises
$pseudo = ( empty($this->request['pseudo'])) ? "" : $this->request['pseudo'];
$mdpSha1 = ( empty($this->request['mdp'])) ? "" : $this->request['mdp'];
$idTrace = ( empty($this->request['idTrace'])) ? "" : $this->request['idTrace'];
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
    if ( $pseudo == "" || $mdpSha1 == "" || $idTrace == "" )
    {	$msg = "Erreur : données incomplètes.";
        $code_reponse = 400;
    }
    else
    {	if ( $dao->getNiveauConnexion($pseudo, $mdpSha1) == 0 )
        {   $msg = "Erreur : authentification incorrecte.";
            $code_reponse = 401;
        }
    	else 
    	{	// contrôle d'existence de idTrace
    	    $laTrace = $dao->getUneTrace($idTrace);
    	    if ($laTrace == null)
    	    {  $msg = "Erreur : parcours inexistant.";
    	       $code_reponse = 400;
    	    }
    	    else
    	    {   // récupération de l'id de l'utilisateur demandeur et du propriétaire du parcours
        	    $idDemandeur = $dao->getUnUtilisateur($pseudo)->getId();
        	    $idProprietaire = $laTrace->getIdUtilisateur();
        	    
        	    if ( $idDemandeur != $idProprietaire )
        	    {   $msg = "Erreur : vous n'êtes pas le propriétaire de ce parcours.";
                    $code_reponse = 401;
        	    }
                else
                {   // suppression du parcours
                    $ok = $dao->supprimerUneTrace($idTrace);
                    if ( ! $ok ) {
                        $msg = "Erreur : problème lors de la suppression du parcours.";
                        $code_reponse = 500;
                    }
                    else {
                        $msg = "Parcours supprimé.";
                        $code_reponse = 200;
                    }
                }
    	    }
    	}
    }
}
// ferme la connexion à MySQL
unset($dao);

// création du flux en sortie
if ($lang == "xml") {
    $content_type = "application/xml; charset=utf-8";      // indique le format XML pour la réponse
    $donnees = creerFluxXML($msg);
}
else {
    $content_type = "application/json; charset=utf-8";      // indique le format Json pour la réponse
    $donnees = creerFluxJSON($msg);
}

// envoi de la réponse HTTP
$this->envoyerReponse($code_reponse, $content_type, $donnees);

// fin du programme (pour ne pas enchainer sur les 2 fonctions qui suivent)
exit;

// ================================================================================================

// création du flux XML en sortie
function creerFluxXML($msg)
{	// crée une instance de DOMdocument (DOM : Document Object Model)
	$doc = new DOMDocument();
	
	// specifie la version et le type d'encodage
	$doc->version = '1.0';
	$doc->encoding = 'UTF-8';
	
	// crée un commentaire et l'encode en UTF-8
	$elt_commentaire = $doc->createComment('Service web SupprimerUnParcours - BTS SIO - Lycée De La Salle - Rennes');
	// place ce commentaire à la racine du document XML
	$doc->appendChild($elt_commentaire);
	
	// crée l'élément 'data' à la racine du document XML
	$elt_data = $doc->createElement('data');
	$doc->appendChild($elt_data);
	
	// place l'élément 'reponse' dans l'élément 'data'
	$elt_reponse = $doc->createElement('reponse', $msg);
	$elt_data->appendChild($elt_reponse);

	// Mise en forme finale
	$doc->formatOutput = true;
	
	// renvoie le contenu XML
	return $doc->saveXML();
}

// ================================================================================================

// création du flux JSON en sortie
function creerFluxJSON($msg)
{
    /* Exemple de code JSON
         {
             "data": {
                "reponse": "Erreur : authentification incorrecte."
             }
         }
     */
    
    // construction de l'élément "data"
    $elt_data = ["reponse" => $msg];
    
    // construction de la racine
    $elt_racine = ["data" => $elt_data];
    
    // retourne le contenu JSON (l'option JSON_PRETTY_PRINT gère les sauts de ligne et l'indentation)
    return json_encode($elt_racine, JSON_PRETTY_PRINT);
}

// ================================================================================================
?>
