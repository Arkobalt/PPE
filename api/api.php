<?php
// Projet TraceGPS - services web
// Fichier : api/api.php
// La classe Api hérite de la classe Rest (fichier api/rest.php)
// Dernière mise à jour : 17/9/2021 par dP

include_once ("rest.php");
include_once ('../modele/DAO.class.php');

class Api extends Rest
{   
    // Le constructeur
    public function __construct()
    {   parent::__construct();      // appel du constructeur de la classe parente
    }
    
    
    // Cette méthode traite l'action demandée dans l'URI
    public function traiterRequete()
    {   // récupère le contenu du paramètre action et supprime les "/"
        $action = ( empty($this->request['action'])) ? "" : $this->request['action'];
        $action = strtolower(trim(str_replace("/", "", $action)));
        
        switch ($action) {
            // services web fournis
            case "connecter" : {$this->connecter(); break;}
            case "changerdemdp" : {$this->changerDeMdp(); break;}
            case "creerunutilisateur" : {$this->creerUnUtilisateur(); break;}
            case "gettouslesutilisateurs" : {$this->getTousLesUtilisateurs(); break;}
            case "supprimerunutilisateur" : {$this->supprimerUnUtilisateur(); break;}
            case "validerdemandeautorisation" : {$this->validerDemandeAutorisation(); break;}
            
            // services web restant à développer
            case "demandermdp" : {$this->demanderMdp(); break;}
            case "getlesparcoursdunutilisateur" : {$this->getLesParcoursDunUtilisateur(); break;}
            case "getlesutilisateursquejautorise" : {$this->getLesUtilisateursQueJautorise(); break;}
            case "getlesutilisateursquimautorisent" : {$this->getLesUtilisateursQuiMautorisent(); break;}
            case "getunparcoursetsespoints" : {$this->getUnParcoursEtSesPoints(); break;}
            case "retireruneautorisation" : {$this->retirerUneAutorisation(); break;}
            case "supprimerunparcours" : {$this->supprimerUnParcours(); break;}
            case "demanderuneautorisation" : {$this->demanderUneAutorisation(); break;}
            case "demarrerenregistrementparcours" : {$this->demarrerEnregistrementParcours(); break;}
            case "envoyerposition" : {$this->envoyerPosition(); break;}
            case "arreterenregistrementparcours" : {$this->arreterEnregistrementParcours(); break;}
            
            // l'action demandée n'existe pas, la réponse est 404 ("Page not found") et aucune donnée n'est envoyée
            default : {
                $code_reponse = 404;            
                $donnees = '';
                $content_type = "application/json; charset=utf-8";      // indique le format Json pour la réponse
                $this->envoyerReponse($code_reponse, $content_type, $donnees);    // envoi de la réponse HTTP
                break;
            }  
        } 
    } // fin de la fonction traiterRequete
    
    // services web fournis ===========================================================================================
    
    // Ce service permet permet à un utilisateur de s'authentifier
    private function connecter()
    {   include_once ("services/Connecter.php");
    }
    
    // Ce service permet permet à un utilisateur de changer son mot de passe
    private function changerDeMdp()
    {   include_once ("services/ChangerDeMdp.php");
    }
    
    // Ce service permet permet à un utilisateur de se créer un compte
    private function creerUnUtilisateur()
    {   include_once ("services/CreerUnUtilisateur.php");
    }
    
    // Ce service permet à un utilisateur authentifié d'obtenir la liste de tous les utilisateurs (de niveau 1)
    private function getTousLesUtilisateurs()
    {   include_once ("services/GetTousLesUtilisateurs.php");
    }
    
    // Ce service permet à un administrateur de supprimer un utilisateur (à condition qu'il ne possède aucune trace enregistrée)
    private function supprimerUnUtilisateur()
    {   include_once ("services/SupprimerUnUtilisateur.php");
    }
    
    // Ce service permet à un utilisateur destinataire d'accepter ou de rejeter une demande d'autorisation provenant d'un utilisateur demandeur
    private function validerDemandeAutorisation()
    {   include_once ("services/ValiderDemandeAutorisation.php");
    }
    
    // services web restant à développer ==============================================================================
    
    // Ce service génère un nouveau mot de passe, l'enregistre en sha1 et l'envoie par mail à l'utilisateur
    private function demanderMdp()
    {   include_once ("services/DemanderMdp.php");
    }
    
    // Ce service permet à un utilisateur d'obtenir la liste de ses parcours ou la liste des parcours d'un utilisateur qui l'autorise
    private function getLesParcoursDunUtilisateur()
    {   include_once ("services/GetLesParcoursDunUtilisateur.php");
    }
    
    // Ce service permet à un utilisateur d'obtenir la liste des utilisateurs qu'il autorise à consulter ses parcours
    private function getLesUtilisateursQueJautorise()
    {   include_once ("services/GetLesUtilisateursQueJautorise.php");
    }
    
    // Ce service permet à un utilisateur d'obtenir la liste des utilisateurs qui l'autorisent à consulter leurs parcours
    private function getLesUtilisateursQuiMautorisent()
    {   include_once ("services/GetLesUtilisateursQuiMautorisent.php");
    }
    
    // Ce service permet à un utilisateur d'obtenir le détail d'un de ses parcours ou d'un parcours d'un membre qui l'autorise
    private function getunparcoursetsespoints()
    {   include_once ("services/getunparcoursetsespoints.php");
    }
    
    // Ce service permet à un utilisateur de supprimer une autorisation qu'il avait accordée à un autre utilisateur
    private function retirerUneAutorisation()
    {   include_once ("services/RetirerUneAutorisation.php");
    }
    
    // Ce service permet à un utilisateur de supprimer un de ses parcours 
    private function supprimerUnParcours()
    {   include_once ("services/SupprimerUnParcours.php");
    }
    
    // Ce service permet à un utilisateur de demander une autorisation à un autre utilisateur
    private function demanderUneAutorisation()
    {   include_once ("services/DemanderUneAutorisation.php");
    }
    
    // Ce service permet à un utilisateur de démarrer l'enregistrement d'un parcours
    private function demarrerEnregistrementParcours()
    {   include_once ("services/DemarrerEnregistrementParcours.php");
    }
    
    // Ce service permet à un utilisateur authentifié d'envoyer sa position
    private function envoyerPosition()
    {   include_once ("services/EnvoyerPosition.php");
    }
    
    // Ce service permet à un utilisateur de terminer l'enregistrement de son parcours
    private function arreterEnregistrementParcours()
    {   include_once ("services/ArreterEnregistrementParcours.php");
    }
} // fin de la classe Api

// Traitement de la requête HTTP
$api = new Api;
$api->traiterRequete();