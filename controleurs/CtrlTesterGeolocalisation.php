<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlTesterGeolocalisation.php
// Rôle : préparer la vue de test de la géolocalisation
// Dernière mise à jour : 01/11/2021 par dP

// on vérifie si le demandeur de cette action est bien authentifié
if ( $_SESSION['niveauConnexion'] == 0) {
    // si le demandeur n'est pas authentifié, il s'agit d'une tentative d'accès frauduleux
    // dans ce cas, on provoque une redirection vers la page de connexion
    header ("Location: index.php?action=Deconnecter");
}
else {
    // pour inclure la clé API de Google Maps
    include_once ('modele/DAO.class.php');
    include_once ('vues/VueTesterGeolocalisation.php');
}