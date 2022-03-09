<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlDemanderAutorisation.php
// Rôle : traiter la demande de d'autorisation
// Dernière mise à jour : 01/11/2021 par dP

// on vérifie si le demandeur de cette action est bien authentifié
if ( $_SESSION['niveauConnexion'] == 0) {
    // si le demandeur n'est pas authentifié, il s'agit d'une tentative d'accès frauduleux
    // dans ce cas, on provoque une redirection vers la page de connexion
    header ("Location: index.php?action=Deconnecter");
}
else {
    if ( ! isset ($_GET ["pseudoDestinataire"]) ) {
        // si le destinataire n'a pas été choisi, on redirige vers la page de choix du destinataire
        header ("Location: index.php?action=ChoisirUtilisateurPourDemanderAutorisation");
    }
    else
    {
        // récupération du pseudo du destinataire de la demande
        $pseudoDestinataire = $_GET ["pseudoDestinataire"];
        
        if ( ! isset ($_POST ["txtTexteMessage"]) && ! isset ($_POST ["txtNomPrenom"]) ) {
            // si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
            $texteMessage = '';
            $nomPrenom = '';
            $message = '';
            $typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
            $themeFooter = $themeNormal;
            include_once ('vues/VueDemanderAutorisation.php');
        }
        else {
            // récupération des données postées
            if ( empty ($_POST ["txtTexteMessage"]) == true)  $texteMessage = "";  else   $texteMessage = $_POST ["txtTexteMessage"];
            if ( empty ($_POST ["txtNomPrenom"]) == true)  $nomPrenom = "";  else   $nomPrenom = $_POST ["txtNomPrenom"];
            
            if ( $texteMessage == "" || $nomPrenom == "" ) {
                // si les données sont incomplètes, réaffichage de la vue avec un message explicatif
                $message = 'Erreur : données incomplètes.';
                $typeMessage = 'avertissement';
                $themeFooter = $themeProbleme;
                include_once ('vues/VueDemanderAutorisation.php');
            }
            else {
                // connexion du serveur web à la base MySQL
                include_once ('modele/DAO.class.php');
                $dao = new DAO();
                $utilisateurDemandeur = $dao->getUnUtilisateur($pseudo);
                
                // envoi d'un mail de confirmation de l'enregistrement
                $utilisateurDestinataire = $dao->getUnUtilisateur($pseudoDestinataire);
                $adrMail = $utilisateurDestinataire->getAdrMail();
                $sujetMail = "Demande d'autorisation de la part d'un utilisateur du système TraceGPS";
                $contenuMail = "Cher ou chère " . $pseudoDestinataire . "\n\n";
                $contenuMail .= "Un utilisateur du système TraceGPS vous demande l'autorisation de suivre vos parcours.\n\n";
                $contenuMail .= "Voici les données le concernant :\n\n";
                $contenuMail .= "Son pseudo : " . $utilisateurDemandeur->getPseudo() . "\n";
                $contenuMail .= "Son adresse mail : " . $utilisateurDemandeur->getAdrMail() . "\n";
                $contenuMail .= "Son numéro de téléphone : " . $utilisateurDemandeur->getNumTel() . "\n";
                $contenuMail .= "Son nom et prénom : " . $nomPrenom . "\n";
                $contenuMail .= "Son message : " . $texteMessage . "\n\n";
                
                $contenuMail .= "Pour accepter la demande, cliquez sur ce lien :\n";
                $contenuMail .= $ADR_SERVICE_WEB . "ValiderDemandeAutorisation.php?a=" . $utilisateurDestinataire->getMdpSha1();
                $contenuMail .= "&b=" . $utilisateurDestinataire->getPseudo() . "&c=" . $utilisateurDemandeur->getPseudo() . "&d=1";
                $contenuMail .= "\n\n";
                $contenuMail .= "Pour rejeter la demande, cliquez sur ce lien :\n";
                $contenuMail .= $ADR_SERVICE_WEB . "ValiderDemandeAutorisation.php?a=" . $utilisateurDestinataire->getMdpSha1();
                $contenuMail .= "&b=" . $utilisateurDestinataire->getPseudo() . "&c=" . $utilisateurDemandeur->getPseudo() . "&d=0";
                
                $ok = Outils::envoyerMail($adrMail, $sujetMail, $contenuMail, $ADR_MAIL_EMETTEUR);
                if ( ! $ok ) {
                    // si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
                    $message = "Erreur : l'envoi du courriel de demande d'autorisation a rencontré un problème.";
                    $typeMessage = 'avertissement';
                    $themeFooter = $themeProbleme;
                    unset($dao);		// fermeture de la connexion à MySQL
                    include_once ('vues/VueDemanderAutorisation.php');
                }
                else {
                    // tout a fonctionné
                    $message = $pseudoDestinataire . " va recevoir un courriel avec votre demande.";
                    $typeMessage = 'information';
                    $themeFooter = $themeNormal;
                    unset($dao);		// fermeture de la connexion à MySQL
                    include_once ('vues/VueDemanderAutorisation.php');
                }
            }
        }
    }
}