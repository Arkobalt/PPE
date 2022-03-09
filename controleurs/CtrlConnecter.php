<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlConnecter.php
// Rôle : traiter la demande de connexion d'un utilisateur
// Dernière mise à jour : 01/11/2021 par dP

// Ce contrôleur vérifie l'authentification de l'utilisateur
// si l'authentification est bonne, il affiche le menu utilisateur ou administrateur (vue VueMenu.php)
// si l'authentification est incorrecte, il réaffiche la page de connexion (vue VueConnecter.php)

// on teste si le terminal client est sous Android, pour lui proposer de télécharger l'application Android
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;
if ( $detect->isAndroidOS() ) $OS = "Android"; else $OS = "autre";

$divCreerCompteDepliee = false;		// indique si la division doit être dépliée à l'affichage de la vue
$divConnecterDepliee = true;		// indique si la division doit être dépliée à l'affichage de la vue
$divDemanderMdpDepliee = false;		// indique si la division doit être dépliée à l'affichage de la vue

if ( ! isset ($_POST ["btnConnecter"]) == true) {
	// si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
	$pseudo = '';
	$mdp = '';
	$afficherMdp = 'off';
	$niveauConnexion = 0;
	$message = '';
	$typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
	$themeFooter = $themeNormal;
	include_once ('vues/VueConnecter.php');
}
else {
	// récupération des données postées
	if ( empty ($_POST ["txtPseudo"]) == true)  $pseudo = "";  else   $pseudo = $_POST ["txtPseudo"];
	if ( empty ($_POST ["txtMdp"]) == true)  $mdp = "";  else   $mdp = $_POST ["txtMdp"];
	if ( empty ($_POST ["caseAfficherMdp"]) == true)  $afficherMdp = "off";  else   $afficherMdp = $_POST ["caseAfficherMdp"];
	
	if ($pseudo == '' || $mdp == '') {
		// si les données sont incomplètes, réaffichage de la vue avec un message explicatif
		$message = 'Erreur : données incomplètes.';
		$typeMessage = 'avertissement';
		$themeFooter = $themeProbleme;
		$niveauConnexion = '';
		include_once ('vues/VueConnecter.php');
	}
	else {
		// connexion du serveur web à la base MySQL
		include_once ('modele/DAO.class.php');
		$dao = new DAO();
		
		// test de l'authentification de l'utilisateur
		// la méthode getNiveauConnexion de la classe DAO retourne les valeurs 0 (non identifié) ou 1 (utilisateur) ou 2 (administrateur)
		$niveauConnexion = $dao->getNiveauConnexion($pseudo, sha1($mdp));

		$_SESSION['pseudo'] = $pseudo;
		$_SESSION['mdp'] = $mdp;
		$_SESSION['niveauConnexion'] = $niveauConnexion;
		$_SESSION['afficherMdp'] = $afficherMdp;
		
		unset($dao);		// fermeture de la connexion à MySQL
		
		if ( $niveauConnexion == 0 ) {
			// si l'authentification est incorrecte, retour à la vue de connexion
			$message = 'Erreur : authentification incorrecte.';
			$typeMessage = 'avertissement';
			$themeFooter = $themeProbleme;
			include_once ('vues/VueConnecter.php');
		}
		else {
			// si l'authentification est correcte, redirection vers la page de menu
			header ("Location: index.php?action=Menu");
		}
	}
}