<?php
    // Projet TraceGPS - version web mobile
    // fichier : vues/VueMenu.php
    // Rôle : visualiser le menu de l'utilisateur ou de l'administrateur
    // cette vue est appelée par le contrôleur controleurs/CtrlMenu.php
    // la barre d'entête possède un lien de déconnexion permettant de retourner à la page de connexion
    // Dernière mise à jour : 01/11/2021 par dP
?>
<!doctype html>
<html>
	<head>
		<?php include_once ('vues/head.php'); ?>
	</head> 
	<body>
		<div data-role="page">
			<div data-role="header" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $TITRE_APPLI; ?></h4>
				<a href="index.php?action=Connecter" data-ajax="false" data-transition="<?php echo $transition; ?>">Déconnexion</a>
			</div>
			<div data-role="content">
				<h4 style="text-align: center; margin-top: 20px; margin-bottom: 20px;">Utilisateur : <?php echo $pseudo; ?></h4>
				<ul data-role="listview" data-inset="true">
					<li><a href="index.php?action=ChoisirUtilisateurPourDemanderAutorisation" data-transition="<?php echo $transition; ?>">Demander une autorisation</a></li>
					<li><a href="index.php?action=ChoisirUtilisateurPourRetirerAutorisation" data-transition="<?php echo $transition; ?>">Retirer une autorisation</a></li>
					<li><a href="index.php?action=DemarrerEnregistrementParcours" data-ajax="false" data-transition="<?php echo $transition; ?>">Démarrer l'enregistrement d'un parcours</a></li>
					<li><a href="index.php?action=ChoisirUtilisateurPourConsulterParcours" data-transition="<?php echo $transition; ?>">Consulter un parcours</a></li>
					<li><a href="index.php?action=ChoisirParcoursASupprimer" data-transition="<?php echo $transition; ?>">Supprimer un de mes parcours</a></li>
					<li><a href="index.php?action=ChangerDeMdp" data-ajax="false" data-transition="<?php echo $transition; ?>">Changer de mot de passe</a></li>
					<li><a href="index.php?action=TesterGeolocalisation" data-ajax="false" data-transition="<?php echo $transition; ?>">Tester la géolocalisation</a></li>
					<?php if ( $niveauConnexion == 2 ) {
						// le menu administrateur possède 2 options supplémentaires ?>
						<li><a href="index.php?action=ConsulterListeUtilisateurs" data-transition="<?php echo $transition; ?>">Liste des utilisateurs</a></li>
						<li><a href="index.php?action=ChoisirUtilisateurASupprimer" data-transition="<?php echo $transition; ?>">Supprimer un utilisateur</a></li>
					<?php } ?>
				</ul>
			</div>
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $NOM_APPLI; ?></h4>
			</div>
		</div>
	</body>
</html>