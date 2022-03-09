<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlSupprimerUtilisateur.php
// Rôle : traiter la demande de suppression d'un utilisateur
// Dernière mise à jour : 01/11/2021 par dP

// on vérifie si le demandeur de cette action a le niveau administrateur
if ($_SESSION['niveauConnexion'] != 2) {
	// si le demandeur n'a pas le niveau administrateur, il s'agit d'une tentative d'accès frauduleux
	// dans ce cas, on provoque une redirection vers la page de connexion
	header ("Location: index.php?action=Deconnecter");
}
else {
    if ( ! isset ($_GET ["pseudoUtilisateurASupprimer"]) ) {
        // si l'utilisateur à supprimer n'a pas été choisi, on redirige vers la page de choix de l'utilisateur
        header ("Location: index.php?action=ChoisirUtilisateurASupprimer");
    }
    else
    {   // récupération de l'id de l'utilisateur à supprimer
        $pseudoUtilisateurASupprimer = $_GET ["pseudoUtilisateurASupprimer"];

        // connexion du serveur web à la base MySQL
        include_once ('modele/DAO.class.php');
        $dao = new DAO();

        $unUtilisateur = $dao->getUnUtilisateur($pseudoUtilisateurASupprimer);
        
        if ( ! isset ($_POST ["btnSupprimerUtilisateur"]) ) {
            // si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
            $message = '';
            $typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
            $themeFooter = $themeNormal;
            include_once ('vues/VueSupprimerUtilisateur.php');
            unset($dao);		// fermeture de la connexion à MySQL
        }
        else {
            if ( ! $dao->existePseudoUtilisateur($pseudoUtilisateurASupprimer) ) {
                // si le pseudo n'existe pas, réaffichage de la vue
                $message = "Erreur : pseudo inexistant.";
                $typeMessage = 'avertissement';
                $themeFooter = $themeProbleme;
                unset($dao);		// fermeture de la connexion à MySQL
                include_once ('vues/VueSupprimerUtilisateur.php');
            }
            else {
                // si cet utilisateur possède encore des traces, sa suppression est refusée
                if ( $unUtilisateur->getNbTraces() > 0 ) {
                    $message = "Erreur : suppression impossible.<br>Cet utilisateur possède encore des traces.";
                    $typeMessage = 'avertissement';
                    $themeFooter = $themeProbleme;
                    unset($dao);		// fermeture de la connexion à MySQL
                    include_once ('vues/VueSupprimerUtilisateur.php');
                }
                else {
                    // suppression de l'utilisateur dans la BDD
                    $ok = $dao->supprimerUnUtilisateur($pseudoUtilisateurASupprimer);
                    if ( ! $ok ) {
                        // si la suppression a échoué, réaffichage de la vue avec un message explicatif
                        $message = "Erreur : problème lors de la suppression de l'utilisateur.";
                        $typeMessage = 'avertissement';
                        $themeFooter = $themeProbleme;
                        unset($dao);		// fermeture de la connexion à MySQL
                        include_once ('vues/VueSupprimerUtilisateur.php');
                    }
                    else {
                        // envoi d'un mail de confirmation de la suppression
                        $adrMail = $unUtilisateur->getAdrMail();
                        $sujet = "Suppression de votre compte dans le système TraceGPS";
                        $contenuMail = "Bonjour " . $pseudoUtilisateurASupprimer . "\n\nL'administrateur du service TraceGPS vient de supprimer votre compte utilisateur.";
                        
                        $ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
                        if ( ! $ok ) {
                            // si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
                            $message = "Suppression effectuée.<br>L'envoi du courriel à l'utilisateur a rencontré un problème.";
                            $typeMessage = 'avertissement';
                            $themeFooter = $themeProbleme;
                            unset($dao);		// fermeture de la connexion à MySQL
                            include_once ('vues/VueSupprimerUtilisateur.php');
                        }
                        else {
                            // tout a fonctionné
                            $message = "Suppression effectuée.<br>Un courriel va être envoyé à l'utilisateur.";
                            $typeMessage = 'information';
                            $themeFooter = $themeNormal;
                            unset($dao);		// fermeture de la connexion à MySQL
                            include_once ('vues/VueSupprimerUtilisateur.php');
                        }
                    }
                }
            }
		}
	}
}
