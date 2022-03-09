<?php
// Projet TraceGPS - version web mobile
// fichier : vues/VueCreerAdministrateur.php
// Rôle : visualiser la demande de création d'un nouvel administrateur
// cette vue est appelée par le contrôleur controleurs/CtrlCreerAdministrateur.php
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
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Créer un compte administrateur</h4>
				<form action="index.php?action=CreerAdministrateur" method="post" data-ajax="false">
					<div data-role="fieldcontain" class="ui-hide-label">
						<label for="txtPseudo">Administrateur :</label>
						<input type="text" name="txtPseudo" id="txtPseudo" required placeholder="Pseudo (au moins 8 caractères)" value="<?php echo $pseudo; ?>">
					</div>
					<div data-role="fieldcontain" class="ui-hide-label">
						<label for="txtAdrMail">Adresse mail :</label>
						<input type="email" name="txtAdrMail" id="txtAdrMail" required placeholder="Adresse mail (obligatoire)" value="<?php echo $adrMail; ?>">
					</div>
					<div data-role="fieldcontain" class="ui-hide-label">
						<label for="txtNumTel">N° de téléphone :</label>
						<input type="text" name="txtNumTel" id="txtNumTel" required placeholder="N° de téléphone (facultatif)" value="<?php echo $numTel; ?>">
					</div>
					<div data-role="fieldcontain">
						<input type="submit" name="btnCreerAdministrateur" id="btnCreerAdministrateur" value="Créer le compte" data-mini="true">
					</div>
				</form>

				<?php if($debug == true) {
					// en mise au point, on peut afficher certaines variables dans la page
					echo "<p>pseudo = " . $pseudo . "</p>";
					echo "<p>adrMail = " . $adrMail . "</p>";
					echo "<p>numTel = " . $numTel . "</p>";
				} ?>
				
			</div>
			
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $NOM_APPLI; ?></h4>
			</div>
		</div>
		
		<?php include_once ('vues/dialog_message.php'); ?>
		
	</body>
</html>