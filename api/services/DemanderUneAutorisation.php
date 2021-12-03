<?php
// Projet TraceGPS - services web
// fichier :  api/services/CreerUnUtilisateur.php
// Dernière mise à jour : 3/7/2021 par dP

// Rôle : ce service permet à un utilisateur de se créer un compte
// Le service web doit recevoir 4 paramètres :
//     pseudo : le pseudo de l'utilisateur
//     adrMail : son adresse mail
//     numTel : son numéro de téléphone
//     lang : le langage du flux de données retourné ("xml" ou "json") ; "xml" par défaut si le paramètre est absent ou incorrect
// Le service retourne un flux de données XML ou JSON contenant un compte-rendu d'exécution

// Les paramètres doivent être passés par la méthode GET :
//     http://<hébergeur>/tracegps/api/CreerUnUtilisateur?pseudo=turlututu&adrMail=delasalle.sio.eleves@gmail.com&numTel=1122334455&lang=xml

// connexion du serveur web à la base MySQL
$dao = new DAO();

// Récupération des données transmises
$pseudo = ( empty($this->request['pseudo'])) ? "" : $this->request['pseudo'];
$mdp = ( empty($this->request['mdp'])) ? "" : $this->request['mdp'];
$pseudoDestinataire = ( empty($this->request['pseudoDestinataire'])) ? "" : $this->request['pseudoDestinataire'];
$texteMessage = ( empty($this->request['texteMessage'])) ? "" : $this->request['texteMessage'];
$nomPrenom = ( empty($this->request['nomPrenom'])) ? "" : $this->request['nomPrenom'];
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
    if ($pseudo == '' || $mdp == '' || $pseudoDestinataire == '' || $texteMessage == '' || $nomPrenom == '' ) {
        $msg = "Erreur : données incomplètes.";
        $code_reponse = 400;
    }
    else {
        if ( $dao->getNiveauConnexion($pseudo, $mdp) == 0) {
            $msg = "Erreur : authentification incorrecte.";
            $code_reponse = 400;
        }
        else {
            if ( !$dao->existePseudoUtilisateur($pseudoDestinataire)){
                $msg = "Erreur : pseudo utilisateur inexistant.";
                $code_reponse = 400;
            }else {
                global $ADR_MAIL_EMETTEUR;
                $demandeur = $dao->getUnUtilisateur($pseudo);
                $destinataire = $dao->getUnUtilisateur($pseudoDestinataire);
                $sujet = "Demande d'autorisation de la part d'un utilisateur du système TraceGPS";
                $contenuMail = "Cher ou chère " . $pseudoDestinataire . "\n\n";
                $contenuMail .= "Un utilisateur du système TraceGPS vous demande l'autorisation de suivre vos parcours.\n\n";
                $contenuMail .= "Voici les données le concernant.\n\n";
                $contenuMail .= "Son pseudo : ".$pseudo."\n";
                $contenuMail .= "Son adresse mail : ".$demandeur->getAdrMail()."\n";
                $contenuMail .= "Son numéro de téléphone : ".$demandeur->getNumTel()."\n";
                $contenuMail .= "Son nom et prénom : ".$nomPrenom."\n";
                $contenuMail .= "Son message : ".$texteMessage."\n";
                $contenuMail .= "Son pseudo : ".$pseudo."\n\n";
                $contenuMail .= "Pour accepter la demande, cliquer sur ce lien :\n";
                $contenuMail .= "http://localhost/ws-php-xxx/tracegps/api/ValiderDemandeAutorisation?a=".$mdp."&b=".$pseudoDestinataire."&c=".$pseudo."&d=1";
                $contenuMail .= "Pour refuser la demande, cliquer sur ce lien :\n";
                $contenuMail .= "http://localhost/ws-php-xxx/tracegps/api/ValiderDemandeAutorisation?a=".$mdp."&b=".$pseudoDestinataire."&c=".$pseudo."&d=0";
                $ok = Outils::envoyerMail($destinataire->getAdrMail(), $sujet, $contenuMail , $ADR_MAIL_EMETTEUR);
                if ( !$ok ) {
                    $msg = "Erreur : l'envoi du courriel de demande d'autorisation a rencontré un problème.";
                    $code_reponse = 500;
                }else {
                    $msg = $pseudoDestinataire." va recevoir un courriel avec votre demande.";
                    $code_reponse = 201;
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
    $donnees = creerFluxXML ($msg);
}
else {
    $content_type = "application/json; charset=utf-8";      // indique le format Json pour la réponse
    $donnees = creerFluxJSON ($msg);
}

// envoi de la réponse HTTP
$this->envoyerReponse($code_reponse, $content_type, $donnees);

// fin du programme (pour ne pas enchainer sur les 2 fonctions qui suivent)
exit;

// ================================================================================================

// création du flux XML en sortie
function creerFluxXML($msg)
{
    /* Exemple de code XML
     <?xml version="1.0" encoding="UTF-8"?>
     <!--Service web CreerUnUtilisateur - BTS SIO - Lycée De La Salle - Rennes-->
     <data>
     <reponse>Erreur : pseudo trop court (8 car minimum) ou déjà existant .</reponse>
     </data>
     */
    
    // crée une instance de DOMdocument (DOM : Document Object Model)
    $doc = new DOMDocument();
    
    // specifie la version et le type d'encodage
    $doc->version = '1.0';
    $doc->encoding = 'UTF-8';
    
    // crée un commentaire et l'encode en UTF-8
    $elt_commentaire = $doc->createComment('Service web CreerUnUtilisateur - BTS SIO - Lycée De La Salle - Rennes');
    // place ce commentaire à la racine du document XML
    $doc->appendChild($elt_commentaire);
    
    // crée l'élément 'data' à la racine du document XML
    $elt_data = $doc->createElement('data');
    $doc->appendChild($elt_data);
    
    // place l'élément 'reponse' juste après l'élément 'data'
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
     "reponse": "Erreur : pseudo trop court (8 car minimum) ou d\u00e9j\u00e0 existant."
     }
     }
     */
    
    // construction de l'élément "data"
    $elt_data = ["reponse" => $msg];
    
    // construction de la racine
    $elt_racine = ["data" => $elt_data];
    
    // retourne le contenu JSON (l'option JSON_PRETTY_PRINT gère les sauts de ligne et l'indentation)
    return json_encode($elt_racine, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

// ================================================================================================
?>