<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlChoisirUtilisateurPourConsulterParcours.php
// Rôle : préparer la liste des utilisateurs m'autorisant à consulter leurs parcours
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
    
    // récupération de la liste des utilisateurs autorisant à l'aide de la méthode getLesUtilisateursAutorisant de la classe DAO
    $lesUtilisateurs = $dao->getLesUtilisateursAutorisant($idUtilisateurConsulte);
    
    // mémorisation du nombre d'utilisateurs autorisés
    $nbReponses = sizeof($lesUtilisateurs);
    
    // préparation d'un message précédant la liste
    if ($nbReponses == 0) {
        $message = "Aucune autorisation accordée !";
    }
    else {
        $message = $nbReponses . " utilisateur(s) m'autorise(nt).";
    }
    
    // affichage de la vue
    include_once ('vues/VueChoisirUtilisateurPourConsulterParcours.php');
    
    unset($dao);		// fermeture de la connexion à MySQL
}