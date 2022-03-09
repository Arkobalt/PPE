<?php
// Projet TraceGPS - version web mobile
// fichier : controleurs/CtrlMenu.php
// Rôle : traiter la demande d'accès au menu
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
    if ( Outils::estUnMdpValide( $_SESSION['mdp']) ){
        // affiche la vue
        include_once ('vues/VueMenu.php');
    }else{
        // affiche la vue
        $message = 'Pour des raisons de sécurité, nous vous invitons à changer votre mot de passe. Le nouveau mot de passe doit comporter au moins 8 caractères, dont au moins une lettre minuscule, une lettre majuscule et un chiffre !';
        $typeMessage = 'avertissement';
        $themeFooter = $themeProbleme;
        $nouveauMdp = $_SESSION['mdp'];
        $confirmationMdp = $_SESSION['mdp'];
        include_once ('vues/VueChangerDeMdp.php');
    }
}