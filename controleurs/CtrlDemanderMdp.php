<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlDemanderMdp.php
// Rôle : traiter la demande d'envoi d'un nouveau mot de passe
// Dernière mise à jour : 01/11/2021 par dP

if ( ! isset ($_POST ["txtPseudo"]) == true) {
	// si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
	$nom = '';
	$message = '';
	$typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
	$themeFooter = $themeNormal;
	include_once ('vues/VueDemanderMdp.php');
}
else {
	// récupération des données postées
    if ( empty ($_POST ["txtPseudo"]) == true)  $pseudo = "";  else   $pseudo = $_POST ["txtPseudo"];
	if ( empty ($_POST ["txtAdrMail"]) == true) $adrMail = ""; else $adrMail = $_POST ["txtAdrMail"];		
    if ($pseudo == '') {
		// si les données sont incomplètes, réaffichage de la vue avec un message explicatif
		$message = 'Erreur : données incomplètes.';
		$typeMessage = 'avertissement';
		$themeFooter = $themeProbleme;
		include_once ('vues/VueDemanderMdp.php');
	}
	else {
		// connexion du serveur web à la base MySQL
		include_once ('modele/DAO.class.php');
		$dao = new DAO();
		
		// test de l'existence de l'utilisateur
		if ( ! $dao->existePseudoUtilisateur($pseudo) )  {
			// si le login (pseudo ou adresse mail) n'existe pas, retour à la vue
			$message = "Erreur : pseudo  inexistant.";
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			unset($dao);		// fermeture de la connexion à MySQL
			include_once ('vues/VueDemanderMdp.php');
		}
		else{
		    // connexion du serveur web à la base MySQL
		    include_once ('modele/DAO.class.php');
		    $dao = new DAO();
		    include_once('modele/Outils.class.php');
		    if(Outils::estUneAdrMailValide($adrMail) == false){
		        // si l'adresse mail saisie n'est pas valide
		        $message = "L'adresse mail n'est pas valide !";
		        $typeMessage = 'avertissement';
		        $themeFooter = $themeProbleme;
		        include_once ('vues/VueDemanderMdp.php');
		}
		else{
		    if($adrMail != $dao->getUnUtilisateur($pseudo)->getAdrMail()){
		        // si l'adresse mail ne correspond pas à aucun utilisateur
		        $message = "L'adresse mail et l'utilisateur ne correspondent pas !";
		        $typeMessage = 'avertissement';
		        $themeFooter = $themeProbleme;
		        include_once ('vues/VueDemanderMdp.php');
		    }
		    else {
		        // génération d'un nouveau mot de passe
		        $nouveauMdp = Outils::creerMdp();
		        
		        // enregistre le nouveau mot de passe de l'utilisateur dans la bdd après l'avoir codé en SHA1
		        $ok = $dao->modifierMdpUtilisateur ($pseudo, $nouveauMdp);
		        if ( ! $ok ) {
		            // si l'enregistrement a échoué, réaffichage de la vue avec un message explicatif
		            $message = "Erreur : problème lors de l'enregistrement du mot de passe.";
		            $typeMessage = 'avertissement';
		            $themeFooter = $themeProbleme;
		            unset($dao);		// fermeture de la connexion à MySQL
		            include_once ('vues/VueDemanderMdp.php');
		        }
		        else {
		            // envoi d'un mail à l'utilisateur avec son nouveau mot de passe
		            $ok = $dao->envoyerMdp ($pseudo, $nouveauMdp);
		            if ( ! $ok ) {
		                // si l'envoi de mail a échoué, réaffichage de la vue avec un message explicatif
		                $message = "Enregistrement effectué.<br>L'envoi du courriel de confirmation a rencontré un problème.";
		                $typeMessage = 'avertissement';
		                $themeFooter = $themeProbleme;
		                unset($dao);		// fermeture de la connexion à MySQL
		                include_once ('vues/VueDemanderMdp.php');
		            }
		            else {
		                // tout a bien fonctionné
		                $message = "Vous allez recevoir un courriel avec votre nouveau mot de passe.";
		                $typeMessage = 'information';
		                $themeFooter = $themeNormal;
		                unset($dao);		// fermeture de la connexion à MySQL
		                include_once ('vues/VueDemanderMdp.php');
		            }
		        }
		    }
	 } 

		}

		}

	}