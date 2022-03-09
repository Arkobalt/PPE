<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlEnvoyerPosition.php
// Rôle : préparer la vue d'envoi périodique de la position
// Dernière mise à jour : 01/11/2021 par dP

// on vérifie si le demandeur de cette action est bien authentifié
if ( $_SESSION['niveauConnexion'] == 0) {
    // si le demandeur n'est pas authentifié, il s'agit d'une tentative d'accès frauduleux
    // dans ce cas, on provoque une redirection vers la page de connexion
    header ("Location: index.php?action=Deconnecter");
}
else {
    if ( ! isset ($_POST ["txtLatitude"]) && ! isset ($_POST ["txtLongitude"]) && ! isset ($_POST ["txtAltitude"]) && ! isset ($_POST ["txtStop"]) ) {
        // si les données n'ont pas été postées, c'est le premier appel du formulaire : affichage de la vue sans message d'erreur
        $latitude = '';
        $longitude = '';
        $altitude = '0';
        $stop = '';
        $message = '';
        $typeMessage = '';			// 2 valeurs possibles : 'information' ou 'avertissement'
        $themeFooter = $themeNormal;
        include_once ('vues/VueEnvoyerPosition.php');
    }
    else {
        // récupération des données postées
        if ( empty ($_POST ["txtLatitude"]) == true)  $latitude = "";  else   $latitude = $_POST ["txtLatitude"];
        if ( empty ($_POST ["txtLongitude"]) == true)  $longitude = "";  else   $longitude = $_POST ["txtLongitude"];
        if ( empty ($_POST ["txtAltitude"]) == true)  $altitude = "0";  else   $altitude = $_POST ["txtAltitude"];
        if ( empty ($_POST ["txtStop"]) == true)  $stop = "";  else   $stop = $_POST ["txtStop"];

        // connexion du serveur web à la base MySQL
        include ('modele/DAO.class.php');
        $dao = new DAO();
        
        if (strtolower($stop) == 'stop') {
            // on termine la trace
            $ok =  $dao->terminerUneTrace($_SESSION['idTrace']);
            unset($dao);		// fermeture de la connexion à MySQL
            // redirection vers la page de fin de parcours
            header ("Location: index.php?action=ArreterEnregistrementParcours");
        }
        else {
            // créer et enregistrer le point
            $idTrace = $_SESSION['idTrace'];
            $_SESSION['idPoint']++;
            $idPoint = $_SESSION['idPoint'];
            $dateHeure = date('Y-m-d H:i:s', time());
            $rythmeCardio = 0;
            $tempsCumule = 0;
            $distanceCumulee = 0;
            $vitesse = 0;
            $unPoint = new PointDeTrace($idTrace, $idPoint, $latitude, $longitude, $altitude, $dateHeure, $rythmeCardio, $tempsCumule, $distanceCumulee, $vitesse);
            $ok = $dao->creerUnPointDeTrace($unPoint);
            
            unset($dao);		// fermeture de la connexion à MySQL
            include ('vues/VueEnvoyerPosition.php');
        }
    }
}