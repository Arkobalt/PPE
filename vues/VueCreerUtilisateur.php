<?php
    // Projet TraceGPS - version web mobile
    // fichier : vues/VueCreerUtilisateur.php
    // Rôle : visualiser la demande de création d'un nouvel utilisateur
    // cette vue est appelée par le contrôleur controleurs/CtrlCreerUtilisateur.php
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
				<a href="index.php?action=Connecter" data-ajax="false" data-transition="<?php echo $transition; ?>">Retour accueil</a>
			</div>
			
			<div data-role="content">
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Créer votre compte utilisateur</h4>
				<?php if ($typeMessage != 'information')    // si la création vient de se faire, ne pas réafficher le formulaire
				{ ?>
    				<form action="index.php?action=CreerUtilisateur" method="post" data-ajax="false">
    					<div data-role="fieldcontain" class="ui-hide-label">
    						<label for="txtPseudo">Utilisateur :</label>
    						<input type="text" name="txtPseudo" id="txtPseudo" required placeholder="Choisissez un pseudo (au moins 8 caractères)" value="<?php echo $pseudo; ?>">
    					</div>
    					<div data-role="fieldcontain" class="ui-hide-label">
    						<label for="txtAdrMail">Adresse mail :</label>
    						<input type="email" name="txtAdrMail" id="txtAdrMail" required placeholder="Votre adresse mail (obligatoire)" value="<?php echo $adrMail; ?>">
    					</div>
    					<div data-role="fieldcontain" class="ui-hide-label">
    						<label for="txtNumTel">N° de téléphone :</label>
    						<input type="text" name="txtNumTel" id="txtNumTel" required placeholder="Votre n° de téléphone (facultatif)" value="<?php echo $numTel; ?>">
    					</div>
    					<div data-role="fieldcontain">
    						<input type="submit" name="btnCrerUtilisateur" id="btnCrerUtilisateur" value="Créer votre compte" data-mini="true">
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