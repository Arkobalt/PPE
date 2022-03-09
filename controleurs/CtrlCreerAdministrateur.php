<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlCreerAdministrateur.php
// Rôle : traiter la demande de création d'un nouvel administrateur
// Dernière mise à jour : 01/11/2021 par dP

// on vérifie si le demandeur de cette action a le niveau administrateur
if ($_SESSION['niveauConnexion'] != 2) {
    // si l'utilisateur n'a pas le niveau administrateur, il s'agit d'une tentative d'accès frauduleux
    // dans ce cas, on provoque une redirection vers la page de connexion
    header ("Location: index.php?action=Deconnecter");
}
else {
    if ( ! isset ($_POST ["txtPseudo"]) && ! isset ($_POST ["txtAdrMail"]) && ! isset ($_POST ["txtNumTel"]) ) {
        // si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
        $pseudo = '';
        $adrMail = '';
        $numTel = '';
        $message = '';
        $typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
        $themeFooter = $themeNormal;
        include_once ('vues/VueCreerAdministrateur.php');
    }
    else {
        // récupération des données postées
        if ( empty ($_POST ["txtPseudo"]) == true)  $pseudo = "";  else   $pseudo = $_POST ["txtPseudo"];
        if ( empty ($_POST ["txtAdrMail"]) == true)  $adrMail = "";  else   $adrMail = $_POST ["txtAdrMail"];
        if ( empty ($_POST ["txtNumTel"]) == true)  $numTel = "";  else   $numTel = $_POST ["txtNumTel"];
        
        // inclusion de la classe Outils pour utiliser les méthodes statiques estUnNumTelValide, estUneAdrMailValide et creerMdp
        include_once ('modele/Outils.class.php');
        
        // le numéro de téléphone est facultatif mais doit être correct
        if ($pseudo == '' || $adrMail == '' || Outils::estUnNumTelValide($numTel) == false) {
            // si les données sont incorrectes ou incomplètes, réaffichage de la vue avec un message explicatif
            $message = 'Données incomplètes ou incorrectes !';
            $typeMessage = 'avertissement';
            $themeFooter = $themeProbleme;
            include_once ('vues/VueCreerAdministrateur.php');
        }
        else {
            // connexion du serveur web à la base MySQL
            include_once ('modele/DAO.class.php');
            $dao = new DAO();
            
            if ( strlen($pseudo) < 8 || $dao->existePseudoUtilisateur($pseudo) ) {
                // si le pseudo est trop court ou existe déjà, réaffichage de la vue
                $message = "Pseudo trop court (8 car minimum) ou déjà existant  !";
                $typeMessage = 'avertissement';
                $themeFooter = $themeProbleme;
                unset($dao);		// fermeture de la connexion à MySQL
                include_once ('vues/VueCreerAdministrateur.php');
            }
            else {
                if ( Outils::estUneAdrMailValide($adrMail) == false || $dao->existeAdrMailUtilisateur($adrMail) ) {
                    // si l'adresse mail est incorrecte ou existe déjà, réaffichage de la vue
                    $message = "Adresse mail incorrecte ou déjà existante !";
                    $typeMessage = 'avertissement';
                    $themeFooter = $themeProbleme;
                    unset($dao);		// fermeture de la connexion à MySQL
                    include_once ('vues/VueCreerAdministrateur.php');
                }
                else {
                    // création d'un mot de passe aléatoire de 8 caractères
                    $password = Outils::creerMdp();
                    $niveau = 2;                                    // 2 = administrateur
                    $dateCreation = date('Y-m-d H:i:s', time());    // date courante
                    $nbTraces = 0;
                    $dateDerniereTrace = null;
                    // enregistrement de l'utilisateur dans la BDD
                    $unUtilisateur = new Utilisateur(0, $pseudo, $password, $adrMail, $numTel, $niveau, $dateCreation, $nbTraces, $dateDerniereTrace);
                    $ok = $dao->creerUnUtilisateur($unUtilisateur);
                    if ( ! $ok ) {
                        // si l'enregistrement a échoué, réaffichage de la vue avec un message explicatif
                        $message = "Problème lors de l'enregistrement !";
                        $typeMessage = 'avertissement';
                        $themeFooter = $themeProbleme;
                        unset($dao);		// fermeture de la connexion à MySQL
                        include_once ('vues/VueCreerAdministrateur.php');
                    }
                    else {
                        // envoi d'un mail de confirmation de l'enregistrement
                        $sujet = "Création de votre compte administrateur dans le système TraceGPS";
                        $contenuMail = "L'administrateur du système TraceGPS vient de vous créer un compte administrateur.\n\n";
                        $contenuMail .= "Les données enregistrées sont :\n\n";
                        $contenuMail .= "Votre pseudo : " . $pseudo . "\n";
                        $contenuMail .= "Votre mot de passe : " . $password . " (nous vous conseillons de le changer lors de la première connexion)\n";
                        $contenuMail .= "Votre numéro de téléphone : " . $numTel . "\n";
                        
                        $ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
                        if ( ! $ok ) {
                            // si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
                            $message = "Administrateur créé.<br>L'envoi du courriel a rencontré un problème !";
                            $typeMessage = 'avertissement';
                            $themeFooter = $themeProbleme;
                            unset($dao);		// fermeture de la connexion à MySQL
                            include_once ('vues/VueCreerAdministrateur.php');
                        }
                        else {
                            // tout a fonctionné
                            $message = "Administrateur créé.<br>Un courriel va lui être envoyé avec son mot de passe.";
                            $typeMessage = 'information';
                            $themeFooter = $themeNormal;
                            unset($dao);		// fermeture de la connexion à MySQL
                            include_once ('vues/VueCreerAdministrateur.php');
                        }
                    }
                }
            }
        }
    }
}
