<?php
// Projet TraceGPS - version web mobile
// fichier : vues/VueRetirerAutorisation.php
// Rôle : afficher la vue permettant de retirer une autorisation
// cette vue est appelée par le contrôleur controleurs/CtrlRetirerAutorisation.php
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
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Retirer une autorisation</h4>
				<?php if ($typeMessage != 'information')    // si la suppression vient de se faire, ne pas afficher le formulaire
				{ ?>
    				<p>Vous souhaitez retirer à <b><?php echo $pseudoARetirer; ?></b> l'autorisation de consulter vos parcours.</p>
    				<p>Si vous souhaitez qu'il soit averti par courriel, vous pouvez saisir un message (en cas d'absence de message, aucun courriel ne sera envoyé).</p>
    				<form action="index.php?action=RetirerAutorisation&pseudoARetirer=<?php echo $pseudoARetirer; ?>" method="post" data-ajax="false">
    					<div data-role="fieldcontain" class="ui-hide-label">
    						<label for="txtTexteMessage">Message :</label>
    						<textarea rows="10" name="txtTexteMessage" id="txtTexteMessage" placeholder="Votre message"><?php echo $texteMessage; ?></textarea>
    					</div>

    					<div data-role="fieldcontain">
        					<p style="margin-top: 0px; margin-bottom: 0px;">
        						<a href="index.php?action=Menu" data-role="button" data-mini="true" data-transition="<?php echo $transition; ?>">Annuler et retourner au menu</a>
        					</p>
        					<p style="margin-top: 0px; margin-bottom: 0px;">
        						<a href="index.php?action=ChoisirUtilisateurPourRetirerAutorisation" data-role="button" data-mini="true" data-transition="<?php echo $transition; ?>">Choisir un autre utilisateur</a>
        					</p>
    						<input type="submit" name="btnRetirerAutorisation" id="btnRetirerAutorisation" data-mini="true" value="Retirer l'autorisation" data-mini="true">
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