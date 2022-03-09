<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlConsulterListeUtilisateurs.php
// Rôle : traiter la demande de consultation de la liste des utilisateurs
// Dernière mise à jour : 01/11/2021 par dP

// on vérifie si le demandeur de cette action est bien authentifié
if ( $_SESSION['niveauConnexion'] == 0) {
    // si le demandeur n'est pas authentifié, il s'agit d'une tentative d'accès frauduleux
    // dans ce cas, on provoque une redirection vers la page de connexion
    header ("Location: index.php?action=Deconnecter");
}
else {
    // connexion du serveur web à la base MySQL
    include_once ('modele/DAO.class.php');
    $dao = new DAO();
    
    // récupération de la liste des utilisateurs à l'aide de la méthode getTousLesUtilisateurs de la classe DAO
    $lesUtilisateurs = $dao->getTousLesUtilisateurs();
    
    // mémorisation du nombre d'utilisateurs
    $nbReponses = sizeof($lesUtilisateurs);
    
    // préparation d'un message précédent la liste
    if ($nbReponses == 0) {
        $message = "Aucun utilisateur !";
    }
    else {
        $message = $nbReponses . " utilisateurs inscrits.";
    }
    
    unset($dao);		// fermeture de la connexion à MySQL
    
    // affichage de la vue
    include_once ('vues/VueConsulterListeUtilisateurs.php');
}