<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlArreterEnregistrementParcours.php
// Rôle : préparer l'affichage des données du parcours qui vient de se terminer
// Dernière mise à jour : 01/11/2021 par dP

// on vérifie si le demandeur de cette action est bien authentifié
if ( $_SESSION['niveauConnexion'] == 0) {
    // si le demandeur n'est pas authentifié, il s'agit d'une tentative d'accès frauduleux
    // dans ce cas, on provoque une redirection vers la page de connexion
    header ("Location: index.php?action=Deconnecter");
}
else 
{
    // récupération de l'id de la trace à consulter
    $idTraceAConsulter = $_SESSION['idTrace'];
    
    // connexion du serveur web à la base MySQL
    include_once ('modele/DAO.class.php');
    $dao = new DAO();
    $laTrace = $dao->getUneTrace($idTraceAConsulter);
    
    $message = '';
    $typeMessage = '';
    $themeFooter = $themeNormal;
    include_once ('vues/VueArreterEnregistrementParcours.php');
    unset($dao);		// fermeture de la connexion à MySQL
}