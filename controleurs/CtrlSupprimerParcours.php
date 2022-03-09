<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlSupprimerParcours.php
// Rôle : traiter la suppression de parcours
// Dernière mise à jour : 01/11/2021 par dP

// on vérifie si le demandeur de cette action est bien authentifié
if ( $_SESSION['niveauConnexion'] == 0) {
    // si le demandeur n'est pas authentifié, il s'agit d'une tentative d'accès frauduleux
    // dans ce cas, on provoque une redirection vers la page de connexion
    header ("Location: index.php?action=Deconnecter");
}
else {
    if ( ! isset ($_GET ["idTraceASupprimer"]) ) {
        // si la trace à supprimer n'a pas été choisie, on redirige vers la page de choix du parcours
        header ("Location: index.php?action=ChoisirParcoursASupprimer");
    }
    else
    {
        // récupération de l'id de la trace à supprimer
        $idTraceASupprimer = $_GET ["idTraceASupprimer"];
        
        // connexion du serveur web à la base MySQL
        include_once ('modele/DAO.class.php');
        $dao = new DAO();
        $laTrace = $dao->getUneTrace($idTraceASupprimer);
        
        if ( ! isset ($_POST ["btnSupprimerParcours"]) ) {
            // si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
            $message = '';
            $typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
            $themeFooter = $themeNormal;
            include_once ('vues/VueSupprimerParcours.php');
            unset($dao);		// fermeture de la connexion à MySQL
        }
        else {
            // suppression du parcours
            $ok = $dao->supprimerUneTrace($idTraceASupprimer);
            if ( ! $ok ) {
                // si la suppression a échoué, réaffichage de la vue avec un message explicatif
                $message = "Erreur : problème lors de la suppression du parcours.";
                $typeMessage = 'avertissement';
                $themeFooter = $themeProbleme;
                include_once ('vues/VueSupprimerParcours.php');
                unset($dao);		// fermeture de la connexion à MySQL
            }
            else {
                // tout a fonctionné
                $message = "Parcours supprimé.";
                $typeMessage = 'information';
                $themeFooter = $themeNormal;
                include_once ('vues/VueSupprimerParcours.php');
                unset($dao);		// fermeture de la connexion à MySQL
            }
        }
    }
}