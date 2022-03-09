<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlChoisirParcoursAConsulter.php
// Rôle : préparer la liste des parcours d'un utilisateur pour en consulter un
// Dernière mise à jour : 01/11/2021 par dP

// on vérifie si le demandeur de cette action est bien authentifié
if ( $_SESSION['niveauConnexion'] == 0) {
    // si le demandeur n'est pas authentifié, il s'agit d'une tentative d'accès frauduleux
    // dans ce cas, on provoque une redirection vers la page de connexion
    header ("Location: index.php?action=Deconnecter");
}
else {
    if ( ! isset ($_GET ["pseudoUtilisateur"]) ) {
        // si l'utilisateur n'a pas été choisi, on redirige vers la page de choix de l'utilisateur
        header ("Location: index.php?action=ChoisirUtilisateurPourConsulterParcours");
    }
    else
    {   // connexion du serveur web à la base MySQL
        include_once ('modele/DAO.class.php');
        $dao = new DAO();

        // récupération du pseudo et de l'id de l'utilisateur
        $pseudoUtilisateur = $_GET ["pseudoUtilisateur"];
        $idUtilisateurConsulte = $dao->getUnUtilisateur($pseudoUtilisateur)->getid();
        
        // récupération de la liste des traces de l'utilisateur à l'aide de la méthode getLesTraces de la classe DAO
        $lesTraces = $dao->getLesTraces($idUtilisateurConsulte);
        
        // mémorisation du nombre de traces
        $nbReponses = sizeof($lesTraces);
        
        // préparation d'un message précédant la liste
        if ($nbReponses == 0) {
            $message = "Aucun parcours pour <b>" . $pseudoUtilisateur . "</b> !";
        }
        else {
            $message = $nbReponses . " parcours pour <b>" . $pseudoUtilisateur . "</b>.";
        }
        
        // affichage de la vue
        include_once ('vues/VueChoisirParcoursAConsulter.php');
        
        unset($dao);		// fermeture de la connexion à MySQL
    }
}