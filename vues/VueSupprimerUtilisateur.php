<?php
    // Projet TraceGPS - version web mobile
    // fichier : vues/VueSupprimerUtilisateur.php
    // Rôle : visualiser la demande de suppression d'un utilisateur
    // cette vue est appelée par le contôleur controleurs/CtrlSupprimerUtilisateur.php
    // Dernière mise à jour : 01/11/2021 par dP
?>
<!doctype html>
<html>
	<head>
		<?php include_once ('vues/head.php'); ?>
		
		<script>
			// associe une fonction à l'événement pageinit
			$(document).bind('pageinit', function() {
				<?php if ($typeMessage != '') { ?>
					// affiche la boîte de dialogue 'affichage_message'
					$.mobile.changePage('#affichage_message', {transition: "<?php echo $transition; ?>"});
				<?php } ?>
			} );
		</script>
	</head>
	 
	<body>
		<div data-role="page" id="page_principale">
			<div data-role="header" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $TITRE_APPLI; ?></h4>
				<a href="index.php?action=Menu" data-transition="<?php echo $transition; ?>">Retour menu</a>
			</div>
			
			<div data-role="content">
				<h4 style="text-align: center; margin-top: 10px; margin-bottom: 10px;">Supprimer un compte utilisateur</h4>
				<?php if ($typeMessage != 'information')    // si la suppression vient de se faire, ne pas afficher le formulaire
				{ ?>
    				<p>Vous souhaitez supprimer cet utilisateur :</p>
    				<p>Pseudo : <?php echo $unUtilisateur->getPseudo(); ?><br>
					Email : <?php echo $unUtilisateur->getAdrMail(); ?><br>
					N° tél : <?php echo $unUtilisateur->getNumTel(); ?><br>
					Date création : <?php echo $unUtilisateur->getDateCreation(); ?><br>
					<?php if ($unUtilisateur->getNbTraces() > 0) echo 'Date dernière trace : '. $unUtilisateur->getDateDerniereTrace() . '<br>';?>
					Nombre de traces : <?php echo $unUtilisateur->getNbTraces(); ?></p>
    				
    				<form action="index.php?action=SupprimerUtilisateur&pseudoUtilisateurASupprimer=<?php echo $pseudoUtilisateurASupprimer; ?>" method="post" data-ajax="false">
    					<p style="margin-top: 0px; margin-bottom: 0px;">
    						<a href="index.php?action=ChoisirUtilisateurASupprimer" data-role="button" data-mini="true" data-transition="<?php echo $transition; ?>">Choisir un autre utilisateur</a>
    					</p>
    					<div data-role="fieldcontain">
    						<input type="submit" name="btnSupprimerUtilisateur" id="btnSupprimerUtilisateur" value="Supprimer l'utilisateur" data-mini="true">
    					</div>
    				</form>
    				<?php 
				} ?>
			</div>
			
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $NOM_APPLI; ?></h4>
			</div>
		</div>
		
		<?php include_once ('vues/dialog_message.php'); ?>
		
	</body>
</html>