<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlChoisirParcoursASupprimer.php
// Rôle : préparer la liste des parcours de l'utilisateur pour en supprimer
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
    
    // récupération de l'id de l'utilisateur
    $idUtilisateurConsulte = $dao->getUnUtilisateur($pseudo)->getId();
    
    // récupération de la liste des traces de l'utilisateur à l'aide de la méthode getLesTraces de la classe DAO
    $lesTraces = $dao->getLesTraces($idUtilisateurConsulte);
    
    // mémorisation du nombre de traces
    $nbReponses = sizeof($lesTraces);
    
    // préparation d'un message précédant la liste
    if ($nbReponses == 0) {
        $message = "Vous ne possédez aucun parcours !";
    }
    else {
        $message = "Vous possédez " . $nbReponses . " parcours.";
    }
    
    // affichage de la vue
    include_once ('vues/VueChoisirParcoursASupprimer.php');
    
    unset($dao);		// fermeture de la connexion à MySQL
}