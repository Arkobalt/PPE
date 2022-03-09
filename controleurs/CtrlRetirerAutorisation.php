<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlRetirerAutorisation.php
// Rôle : traiter le retrait d'autorisation
// Dernière mise à jour : 01/11/2021 par dP

// on vérifie si le demandeur de cette action est bien authentifié
if ( $_SESSION['niveauConnexion'] == 0) {
    // si le demandeur n'est pas authentifié, il s'agit d'une tentative d'accès frauduleux
    // dans ce cas, on provoque une redirection vers la page de connexion
    header ("Location: index.php?action=Deconnecter");
}
else {
    if ( ! isset ($_GET ["pseudoARetirer"]) ) {
        // si le pseudo de l'utilisateur n'a pas été choisi, on redirige vers la page de choix de l'utilisateur
        header ("Location: index.php?action=ChoisirUtilisateurPourRetirerAutorisation");
    }
    else
    {
        // récupération du pseudo de l'utilisateur
        $pseudoDestinataire = $_GET ["pseudoARetirer"];
        
        if ( ! isset ($_POST ["btnRetirerAutorisation"]) ) {
            // si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
            $texteMessage = '';
            $message = '';
            $typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
            $themeFooter = $themeNormal;
            include_once ('vues/VueRetirerAutorisation.php');
        }
        else {
            // récupération des données postées
            if ( empty ($_POST ["txtTexteMessage"]) == true)  $texteMessage = "";  else   $texteMessage = $_POST ["txtTexteMessage"];
            
            // connexion du serveur web à la base MySQL
            include_once ('modele/DAO.class.php');
            $dao = new DAO();
            
            $utilisateurAutorisant = $dao->getUnUtilisateur($pseudo);
            $utilisateurDestinataire = $dao->getUnUtilisateur($pseudoDestinataire);
            $idAutorisant = $utilisateurAutorisant->getId();
            $idAutorise = $utilisateurDestinataire->getId();
            
            // suppression de l'autorisation
            $ok = $dao->supprimerUneAutorisation($idAutorisant, $idAutorise);
            if ( ! $ok ) {
                // si la suppression a échoué, réaffichage de la vue avec un message explicatif
                $message = "Erreur : problème lors de la suppression de l'autorisation.";
                $typeMessage = 'avertissement';
                $themeFooter = $themeProbleme;
                unset($dao);		// fermeture de la connexion à MySQL
                include_once ('vues/VueRetirerAutorisation.php');
            }
            else {
                if ($texteMessage == '') {
                    // la suppression a fonctionné mais l'utilisateur ne souhaite pas envoyer de courriel
                    $message = "Autorisation supprimée.";
                    $typeMessage = 'information';
                    $themeFooter = $themeNormal;
                    unset($dao);		// fermeture de la connexion à MySQL
                    include_once ('vues/VueRetirerAutorisation.php');
                }
                else {
                    // la suppression a fonctionné et l'utilisateur souhaite envoyer un courriel pour avertir l'intéressé
                    $adrMail = $utilisateurDestinataire->getAdrMail();
                    $sujetMail = "Suppression d'autorisation de la part d'un utilisateur du système TraceGPS";
                    $contenuMail = "Cher ou chère " . $pseudoDestinataire . "\n\n";
                    $contenuMail .= "L'utilisateur " . $utilisateurAutorisant->getPseudo() . " du système TraceGPS vous retire l'autorisation de suivre ses parcours.\n\n";
                    $contenuMail .= "Voici son message : " . $texteMessage . "\n\n";
                    $contenuMail .= "Cordialement.\n";
                    $contenuMail .= "L'administrateur du système TraceGPS";
                    
                    $ok = Outils::envoyerMail($adrMail, $sujetMail, $contenuMail, $ADR_MAIL_EMETTEUR);
                    if ( ! $ok ) {
                        // si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
                        $message = "Erreur : autorisation supprimée.<br>L'envoi du courriel de notification a rencontré un problème .";
                        $typeMessage = 'avertissement';
                        $themeFooter = $themeProbleme;
                        unset($dao);		// fermeture de la connexion à MySQL
                        include_once ('vues/VueRetirerAutorisation.php');
                    }
                    else {
                        // tout a fonctionné
                        $message = "Autorisation supprimée.<br>L'intéressé va recevoir un courriel de notification.";
                        $typeMessage = 'information';
                        $themeFooter = $themeNormal;
                        unset($dao);		// fermeture de la connexion à MySQL
                        include_once ('vues/VueRetirerAutorisation.php');
                    }
                }
            }
        }
    }
}