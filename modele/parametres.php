<?php
// Projet TraceGPS - version web mobile
// fichier : modele/parametres.php
// Rôle : inclure les paramètres de l'application (hébergement en localhost)
// Dernière mise à jour : 01/11/2021 par dP
// paramètres de connexion -----------------------------------------------------------------------------------
global $PARAM_HOTE, $PARAM_PORT, $PARAM_BDD, $PARAM_USER, $PARAM_PWD;
$PARAM_HOTE = "localhost"; // si le sgbd est sur la même machine que le serveur php
$PARAM_PORT = "3306"; // le port utilisé par le serveur MySql
$PARAM_BDD = "tracegps"; // nom de la base de données
$PARAM_USER = "tracegps"; // nom de l'utilisateur
$PARAM_PWD = "spgecart"; // son mot de passe
// Autres paramètres -----------------------------------------------------------------------------------------
global $TITRE_APPLI, $NOM_APPLI, $CLE_API, $FREQUENCE_AFFICHAGE, $ADR_MAIL_EMETTEUR,
$ADR_SERVICE_WEB;
// titre de l'application (en entête des vues)
$TITRE_APPLI = "TraceGPS";
// nom de l'application (en pied de page des vues)
$NOM_APPLI = "Suivi de parcours sportifs en extérieur";
// clé API pour utiliser Google Maps en JavaScript
$CLE_API = "AIzaSyAF3v68PJ6HRoAxgblEgPsURPJSTzSF5lc";
// valeur de la fréquence de réactualisation de l'affichage (en secondes) d'un parcours
$FREQUENCE_AFFICHAGE = 60; // 60 sec ou 1 mn
// adresse de l'émetteur lors d'un envoi de courriel
$ADR_MAIL_EMETTEUR = "delasalle.sio.crib@gmail.com";
// adresse de l'API web en localhost -----------------------------------------------------------------------
$ADR_SERVICE_WEB = "http://localhost/ws-php-cartron/tracegps/api/";
// adresse de l'API web chez OVH ---------------------------------------------------------------------------
//$ADR_SERVICE_WEB = "http://sio.lyceedelasalle.fr/tracegps/api/";
// ATTENTION : on ne met pas de balise de fin de script pour ne pas prendre le risque
// d'enregistrer d'espaces après la balise de fin de script !!!!!!!!!!!!
