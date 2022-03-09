<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlChangerDeMdp.php
// Rôle : traiter la demande de changement de mot de passe
// Dernière mise à jour : 01/11/2021 par dP

// on vérifie si le demandeur de cette action est bien authentifié
if ( $_SESSION['niveauConnexion'] == 0) {
    // si le demandeur n'est pas authentifié, il s'agit d'une tentative d'accès frauduleux
    // dans ce cas, on provoque une redirection vers la page de connexion
    header ("Location: index.php?action=Deconnecter");
}
else {
    if ( ! isset ($_POST ["txtNouveauMdp"]) && ! isset ($_POST ["txtConfirmationMdp"]) ) {
        // si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
        $nouveauMdp = '';
        $confirmationMdp = '';
        $afficherMdp = 'off';
        $message = '';
        $typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
        $themeFooter = $themeNormal;
        include_once ('vues/VueChangerDeMdp.php');
    }
    else {
        // récupération des données postées
        if ( empty ($_POST ["txtNouveauMdp"]) == true)  $nouveauMdp = "";  else   $nouveauMdp = $_POST ["txtNouveauMdp"];
        if ( empty ($_POST ["txtConfirmationMdp"]) == true)  $confirmationMdp = "";  else   $confirmationMdp = $_POST ["txtConfirmationMdp"];
        if ( empty ($_POST ["caseAfficherMdp"]) == true)  $afficherMdp = 'off';  else   $afficherMdp = $_POST ["caseAfficherMdp"];
        
        if ( $nouveauMdp == "" || $confirmationMdp == "" ) {
            // si les données sont incomplètes, réaffichage de la vue avec un message explicatif
            $message = 'Données incomplètes !';
            $typeMessage = 'avertissement';
            $themeFooter = $themeProbleme;
            include_once ('vues/VueChangerDeMdp.php');
        }
        else {
            if ( strlen($nouveauMdp) < 8 ) {
                // si le mot de passe a moins de 8 caractères, réaffichage de la vue avec un message explicatif
                $message = 'Le mot de passe doit comporter au moins 8 caractères !';
                $typeMessage = 'avertissement';
                $themeFooter = $themeProbleme;
                include_once ('vues/VueChangerDeMdp.php');
            }
            else {
                if ( $nouveauMdp != $confirmationMdp ) {
                    // si le mot de passe et sa confirmation sont différents, réaffichage de la vue avec un message explicatif
                    $message = 'Le nouveau mot de passe et<br>sa confirmation sont différents !';
                    $typeMessage = 'avertissement';
                    $themeFooter = $themeProbleme;
                    include_once ('vues/VueChangerDeMdp.php');
                }
                else {
                    // connexion du serveur web à la base MySQL
                    include_once ('modele/DAO.class.php');
                    $dao = new DAO();
                    include_once('modele/Outils.class.php');
                    if(Outils::estUneAdrMailValide($adrMail) == false || $adrMail != $dao->getUnUtilisateur($pseudo)->getAdrMail()){
                        // si l'adresse mail saisie n'est pas valide
                        $message = "L'adresse mail n'est pas valide !";
                        $typeMessage = 'avertissement';
                        $themeFooter = $themeProbleme;
                        include_once ('vues/VueChangerDeMdp.php');
                    }
                    else {
                        
                        // enregistre le nouveau mot de passe de l'utilisateur dans la bdd après l'avoir codé en SHA1
                        $ok = $dao->modifierMdpUtilisateur ($pseudo, $nouveauMdp);
                        if ( ! $ok ) {
                            // si l'enregistrement a échoué, réaffichage de la vue avec un message explicatif
                            $message = "Problème lors de l'enregistrement du mot de passe !";
                            $typeMessage = 'avertissement';
                            $themeFooter = $themeProbleme;
                            unset($dao);		// fermeture de la connexion à MySQL
                            include_once ('vues/VueChangerDeMdp.php');
                        }
                        else {
                            // envoi d'un mail à l'utilisateur avec son nouveau mot de passe
                            $ok = $dao->envoyerMdp ($pseudo, $nouveauMdp);
                            if ( ! $ok ) {
                                // si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
                                $message = "Enregistrement effectué.<br>L'envoi du mail de confirmation a rencontré un problème.";
                                $typeMessage = 'avertissement';
                                $themeFooter = $themeProbleme;
                                unset($dao);		// fermeture de la connexion à MySQL
                                include_once ('vues/VueChangerDeMdp.php');
                            }
                            else {
                                // tout a bien fonctionné
                                $message = "Enregistrement effectué.<br>Vous allez recevoir un mail de confirmation.";
                                $typeMessage = 'information';
                                $themeFooter = $themeNormal;
                                unset($dao);		// fermeture de la connexion à MySQL
                                include_once ('vues/VueChangerDeMdp.php');
                            }
                        }
                    }
                }

            }
        }
    }
}