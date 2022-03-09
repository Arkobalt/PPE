<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlCreerUtilisateur.php
// Rôle : traiter la demande de création d'un nouvel utilisateur
// Dernière mise à jour : 01/11/2021 par dP

if ( ! isset ($_POST ["txtPseudo"]) && ! isset ($_POST ["txtAdrMail"]) && ! isset ($_POST ["txtNumTel"]) ) {
	// si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
	$pseudo = '';
	$adrMail = '';
	$numTel = '';
	$message = '';
	$typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
	$themeFooter = $themeNormal;
	include_once ('vues/VueCreerUtilisateur.php');
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
		$message = 'Erreur : données incomplètes ou incorrectes.';
		$typeMessage = 'avertissement';
		$themeFooter = $themeProbleme;
		include_once ('vues/VueCreerUtilisateur.php');
	}
	else {
		// connexion du serveur web à la base MySQL
		include_once ('modele/DAO.class.php');
		$dao = new DAO();
			
		if ( strlen($pseudo) < 8 || $dao->existePseudoUtilisateur($pseudo) ) {
			// si le pseudo est trop court ou existe déjà, réaffichage de la vue
			$message = "Erreur : pseudo trop court (8 car minimum) ou déjà existant.";
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			unset($dao);		// fermeture de la connexion à MySQL
			include_once ('vues/VueCreerUtilisateur.php');
		}
		else {
		    if ( Outils::estUneAdrMailValide($adrMail) == false || $dao->existeAdrMailUtilisateur($adrMail) ) {
		        // si l'adresse mail est incorrecte ou existe déjà, réaffichage de la vue
		        $message = "Erreur : adresse mail incorrecte ou déjà existante.";
		        $typeMessage = 'avertissement';
		        $themeFooter = $themeProbleme;
		        unset($dao);		// fermeture de la connexion à MySQL
		        include_once ('vues/VueCreerUtilisateur.php');
		    }
		    else {
    			// création d'un mot de passe aléatoire de 8 caractères
    			$password = Outils::creerMdp();
    			$niveau = 1;                                     // 1 = utilisateur normal
    			$dateCreation = date('Y-m-d H:i:s', time());     // date courante
    			$nbTraces = 0;
    			$dateDerniereTrace = null;
    			// enregistrement de l'utilisateur dans la BDD
    			$unUtilisateur = new Utilisateur(0, $pseudo, $password, $adrMail, $numTel, $niveau, $dateCreation, $nbTraces, $dateDerniereTrace);
    			$ok = $dao->creerUnUtilisateur($unUtilisateur);
    			if ( ! $ok ) {
    				// si l'enregistrement a échoué, réaffichage de la vue avec un message explicatif					
    				$message = "Erreur : problème lors de l'enregistrement.";
    				$typeMessage = 'avertissement';
    				$themeFooter = $themeProbleme;
    				unset($dao);		// fermeture de la connexion à MySQL
    				include_once ('vues/VueCreerUtilisateur.php');
    			}
    			else {
    				// envoi d'un mail de confirmation de l'enregistrement
    				$sujet = "Création de votre compte dans le système TraceGPS";
    				$contenuMail = "Vous venez de vous créer un compte utilisateur.\n\n";
    				$contenuMail .= "Les données enregistrées sont :\n\n";
    				$contenuMail .= "Votre pseudo : " . $pseudo . "\n";
    				$contenuMail .= "Votre mot de passe : " . $password . " (nous vous conseillons de le changer lors de la première connexion)\n";
    				$contenuMail .= "Votre numéro de téléphone : " . $numTel . "\n";
    					
    				$ok = Outils::envoyerMail($adrMail, $sujet, $contenuMail, $ADR_MAIL_EMETTEUR);
    				if ( ! $ok ) {
    					// si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
    					$message = "Enregistrement effectué.<br>L'envoi du courriel a rencontré un problème.";
    					$typeMessage = 'avertissement';
    					$themeFooter = $themeProbleme;
    					unset($dao);		// fermeture de la connexion à MySQL
    					include_once ('vues/VueCreerUtilisateur.php');
    				}
    				else {
    					// tout a fonctionné
    					$message = "Enregistrement effectué.<br>Vous allez recevoir un courriel avec votre mot de passe.";
    					$typeMessage = 'information';
    					$themeFooter = $themeNormal;
    					unset($dao);		// fermeture de la connexion à MySQL
    					include_once ('vues/VueCreerUtilisateur.php');
    				}
    			}
		    }
		}
	}
}

