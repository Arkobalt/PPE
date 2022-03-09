<?php
// Projet TraceGPS - version web mobile
// fichier : index.php
// Rôle : analyser toutes les demandes (appels de page ou traitements de formulaires) et activer le contrôleur chargé de traiter l'action demandée
// Dernière mise à jour : 01/11/2021 par dP

// Ce fichier est appelé par tous les liens internes, et par la validation de tous les formulaires
// il est appelé avec un paramètre action qui peut prendre les valeurs suivantes :

//    index.php?action=Connecter                    : pour afficher la page de connexion
//    index.php?action=CreerUtilisateur             : pour afficher la page de création de compte
//    index.php?action=DemanderMdp                  : pour afficher la page "mot de passe oublié"
//    index.php?action=Menu                         : pour afficher le menu
//    index.php?action=ConsulterListeUtilisateurs   : pour afficher la page de consultation des utilisateurs
//    index.php?action=ChangerDeMdp                 : pour afficher la page de changement de mot de passe
//    index.php?action=DemanderAutorisation         : pour afficher la page de demande d'autorisation
//    index.php?action=RetirerAutorisation          : pour afficher la page de suppression d'autorisation
//    index.php?action=ConsulterParcours            : pour afficher la page de consultation de parcours
//    index.php?action=SupprimerParcours            : pour afficher la page de suppression de parcours
//    index.php?action=TesterGeolocalisation        : pour afficher la page de test de la géolocalisation

// il faut être administrateur pour les 2 actions suivantes :
//    index.php?action=CreerAdministrateur    : pour afficher la page de création d'un administrateur 
//    index.php?action=SupprimerUtilisateur   : pour afficher la page de suppression d'un utilisateur 

session_start();		// permet d'utiliser des variables de session

// inclusion des paramètres de l'application
include_once ('modele/parametres.php');
include_once ('modele/Outils.class.php');
// choix des styles graphiques
$version = "1.4.5";			// choix de la version de JQuery Mobile (voir fichier head.php) : 1.2.0,  1.2.1,  1.3.2,  1.4.5
$themeNormal = "a";			// thème de base
$themeProbleme = "b";		// thème utilisé pour afficher un message en cas de problème
$transition ="flip";		// transition lors des changements de page (pop, flip, fade, turn, flow, slidefade, slide, slideup, slidedown)

// on vérifie le paramètre action de l'URL
if ( ! isset ($_GET['action']) == true)  $action = '';  else   $action = $_GET['action'];

// lors d'une première connexion, ou après une déconnexion, on initialise à vide les variables de session
if ($action == '' || $action == 'Deconnecter')
{	unset ($_SESSION['pseudo']);
	unset ($_SESSION['mdp']);
	unset ($_SESSION['niveauConnexion']);
	unset ($_SESSION['afficherMdp']);
}

// tests des variables de session
if ( ! isset ($_SESSION['pseudo']) == true)  $pseudo = '';  else  $pseudo = $_SESSION['pseudo'];
if ( ! isset ($_SESSION['mdp']) == true)  $mdp = '';  else  $mdp = $_SESSION['mdp'];
if ( ! isset ($_SESSION['niveauConnexion']) == true)  $niveauConnexion = 0;  else  $niveauConnexion = $_SESSION['niveauConnexion'];

// pour mémoriser le choix d'afficher en clair (ou pas) le mot de passe :
if ( isset ($_SESSION['afficherMdp']) == false)  $afficherMdp = 'off';  else  $afficherMdp = $_SESSION['afficherMdp'];

// si l'utilisateur n'est pas encore identifié, il sera automatiquement redirigé vers le contrôleur d'authentification
// (sauf s'il veut créer un compte ou bien se faire envoyer son mot de passe qu'il a oublié)
if ($pseudo == '' && $action != 'CreerUtilisateur' && $action != 'DemanderMdp') $action = 'Connecter';

switch($action){
	case 'Connecter': {
		include_once ('controleurs/CtrlConnecter.php'); break;
	}
	case 'CreerUtilisateur': {
	    include_once ('controleurs/CtrlCreerUtilisateur.php'); break;
	}
	case 'DemanderMdp': {
	    include_once ('controleurs/CtrlDemanderMdp.php'); break;
	}
	case 'Menu': {
		include_once ('controleurs/CtrlMenu.php'); break;
	}
	case 'ConsulterListeUtilisateurs': {
	    include_once ('controleurs/CtrlConsulterListeUtilisateurs.php'); break;
	}
	case 'ChangerDeMdp': {
	    include_once ('controleurs/CtrlChangerDeMdp.php'); break;
	}
	case 'TesterGeolocalisation': {
	    include_once ('controleurs/CtrlTesterGeolocalisation.php'); break;
	}
	
	case 'ChoisirUtilisateurPourDemanderAutorisation': {
       include_once ('controleurs/CtrlChoisirUtilisateurPourDemanderAutorisation.php'); break;
	}
	case 'DemanderAutorisation': {
        include_once ('controleurs/CtrlDemanderAutorisation.php'); break;
	}
	
	
	case 'ChoisirUtilisateurPourRetirerAutorisation': {
		include_once ('controleurs/CtrlChoisirUtilisateurPourRetirerAutorisation.php'); break;
	}
	case 'RetirerAutorisation': {
        include_once ('controleurs/CtrlRetirerAutorisation.php'); break;
	}

	
	case 'DemarrerEnregistrementParcours': {
	    include_once ('controleurs/CtrlDemarrerEnregistrementParcours.php'); break;
	}
	case 'EnvoyerPosition': {
	    include_once ('controleurs/CtrlEnvoyerPosition.php'); break;
	}
	case 'ArreterEnregistrementParcours': {
	    include_once ('controleurs/CtrlArreterEnregistrementParcours.php'); break;
	}
	
	
	case 'ChoisirUtilisateurPourConsulterParcours': {
		include_once ('controleurs/CtrlChoisirUtilisateurPourConsulterParcours.php'); break;
	}
	case 'ChoisirParcoursAConsulter': {
	    include_once ('controleurs/CtrlChoisirParcoursAConsulter.php'); break;
	}
	case 'ConsulterParcours': {
	    include_once ('controleurs/CtrlConsulterParcours.php'); break;
	}
	
	
	case 'ChoisirParcoursASupprimer': {
		include_once ('controleurs/CtrlChoisirParcoursASupprimer.php'); break;
	}
	case 'SupprimerParcours': {
        include_once ('controleurs/CtrlSupprimerParcours.php'); break;
	}
	
	
	case 'CreerAdministrateur': {
		include_once ('controleurs/CtrlCreerAdministrateur.php'); break;
	}
	
	
	case 'ChoisirUtilisateurASupprimer': {
	    include_once ('controleurs/CtrlChoisirUtilisateurASupprimer.php'); break;
	}
	case 'SupprimerUtilisateur': {
		include_once ('controleurs/CtrlSupprimerUtilisateur.php'); break;
	}
	
	
	default : {
		// toute autre tentative est automatiquement redirigée vers le contrôleur d'authentification
		include_once ('controleurs/CtrlConnecter.php'); break;
	}
}