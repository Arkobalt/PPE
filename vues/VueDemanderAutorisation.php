<?php
// Projet TraceGPS - version web mobile
// fichier : vues/VueDemanderAutorisation.php
// Rôle : visualiser la demande d'autorisation
// cette vue est appelée par le contrôleur controleurs/CtrlDemanderAutorisation.php
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
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Demander une autorisation</h4>
				<?php if ($typeMessage != 'information')    // si la demande vient de se faire, ne pas afficher le formulaire
				{ ?>
    				<p>Vous demandez à <b><?php echo $pseudoDestinataire; ?></b> l'autorisation de consulter ses parcours. Adressez lui un message et indiquez vos noms et prénoms.</p>
    				<p>La demande lui sera immédiatement transmise et s'il accepte, vous serez averti par courriel.</p>
    				<form action="index.php?action=DemanderAutorisation&pseudoDestinataire=<?php echo $pseudoDestinataire; ?>" method="post" data-ajax="false">
    					<div data-role="fieldcontain" class="ui-hide-label">
    						<label for="txtTexteMessage">Message :</label>
    						<textarea rows="10" name="txtTexteMessage" id="txtTexteMessage" required placeholder="Votre message"><?php echo $texteMessage; ?></textarea>
    					</div>
    					<div data-role="fieldcontain" class="ui-hide-label">
    						<label for="txtNomPrenom">Nom et prénom :</label>
    						<input type="text" name="txtNomPrenom" id="txtNomPrenom" required placeholder="Vos nom et prénom" value="<?php echo $nomPrenom; ?>">
    					</div>
    					<p style="margin-top: 0px; margin-bottom: 0px;">
    							<a href="index.php?action=DemanderAutorisation" data-role="button" data-mini="true" data-transition="<?php echo $transition; ?>">Choisir un autre utilisateur</a>
    						</p>
    					<div data-role="fieldcontain">
    						<input type="submit" name="btnValiderDemande" id="btnValiderDemande" value="Valider ma demande" data-mini="true">
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