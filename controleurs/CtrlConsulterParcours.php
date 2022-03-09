<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlConsulterParcours.php
// Rôle : préparer l'affichage du parcours choisi parcours
// Dernière mise à jour : 01/11/2021 par dP

// pour obliger la page à se recharger
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
header('Content-Tranfer-Encoding: none');
header('Expires: 0');

// on vérifie si le demandeur de cette action est bien authentifié
if ( $_SESSION['niveauConnexion'] == 0) {
    // si le demandeur n'est pas authentifié, il s'agit d'une tentative d'accès frauduleux
    // dans ce cas, on provoque une redirection vers la page de connexion
    header ("Location: index.php?action=Deconnecter");
}
else {
    if ( ! isset ($_GET ["idTraceAConsulter"]) ) {
        // si la trace à supprimer n'a pas été choisie, on redirige vers la page de choix du parcours
        header ("Location: index.php?action=ChoisirParcoursAConsulter");
    }
    else {
        // récupération du pseudo de l'utilisateur choisi
        $pseudoUtilisateur = $_GET ["pseudoUtilisateur"];
        // récupération de l'id de la trace à consulter
        $idTraceAConsulter = $_GET ["idTraceAConsulter"];
        
        // connexion du serveur web à la base MySQL
        include_once ('modele/DAO.class.php');
        $dao = new DAO();
        $laTrace = $dao->getUneTrace($idTraceAConsulter);
        $leCentre = $laTrace->getCentre();
        $terminee = $laTrace->getTerminee();
        
        $dateHeureDebut = $laTrace->getDateHeureDebut();
        $dateDebut = substr($dateHeureDebut, 8, 2) . "/" . substr($dateHeureDebut, 5, 2) . "/" . substr($dateHeureDebut, 0, 4);
        $heureDebut = substr($dateHeureDebut, 11, 8);
        
        if ($terminee == true) {
            $dateHeureFin = $laTrace->getDateHeureFin();
            $dateFin = substr($dateHeureFin, 8, 2) . "/" . substr($dateHeureFin, 5, 2) . "/" . substr($dateHeureFin, 0, 4);
            $heureFin = substr($dateHeureFin, 11, 8);
        }
        
        // si la trace n'est pas terminée et comporte des points, on mémorise la dernière position connue
        if ($terminee == false && $laTrace->getNombrePoints() > 0)
        {   $lesPointsDeTrace = $laTrace->getLesPointsDeTrace();
            $dernierPoint = $lesPointsDeTrace[sizeof($lesPointsDeTrace)-1];
            $heureDernierPoint = substr($dernierPoint->getDateHeure(), 11, 8);
            // calcul du temps écoulé depuis le dernier point de passage
            $duree = time() - strtotime($dernierPoint->getDateHeure());
            $jours = $duree / 86400;
            $duree = $duree % 86400;
            $heures = $duree / 3600;
            $duree = $duree % 3600;
            $minutes = $duree / 60;
            $secondes = $duree % 60;
            $tempsEcoule = sprintf("%02d",$jours) . " j " . sprintf("%02d",$heures) . " h " . sprintf("%02d",$minutes) . " mn";
            $latitude = $dernierPoint->getLatitude();
            $longitude = $dernierPoint->getLongitude();        
        }
        
        $message = '';
        $typeMessage = '';
        $themeFooter = $themeNormal;
        
        if ($terminee == true) {
            include_once ('vues/VueConsulterParcoursTermine.php');
        } else {
            include_once ('vues/VueConsulterParcoursNonTermine.php');
        }
        unset($dao);		// fermeture de la connexion à MySQL
    }
}