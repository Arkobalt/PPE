<?php
    // Projet TraceGPS - version web mobile
    // fichier : vues/VueDemanderMdp.php
    // Rôle : visualiser la vue de demande d'envoi d'un nouveau mot de passe
    // cette vue est appelée par le contrôleur controleurs/CtrlDemanderMdp.php
    // Dernière mise à jour : 01/11/2021 par dP
?>
<!doctype html>
<html>
	<head>
		<?php include_once ('vues/head.php'); ?>
		
		<script>
			<?php if ($typeMessage != '') { ?>
				// associe une fonction à l'événement pageinit
				$(document).bind('pageinit', function() {
					// affiche la boîte de dialogue 'affichage_message'
					$.mobile.changePage('#affichage_message', {transition: "<?php echo $transition; ?>"});
				} );
			<?php } ?>
		</script>
	</head>
	
	<body>
		<div data-role="page" id="page_principale">
			<div data-role="header" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $TITRE_APPLI; ?></h4>
				<a href="index.php?action=Connecter" data-ajax="false" data-transition="<?php echo $transition; ?>">Retour accueil</a>
			</div>
			<div data-role="content">
				<h4 style="text-align: center; margin-top: 10px; margin-bottom: 10px;">Demander un nouveau mot de passe</h4>
				<?php if ($typeMessage != 'information')    // si l'envoi vient de se faire, ne pas réafficher le formulaire
				{ ?>
    				<form name="form1" id="form1" action="index.php?action=DemanderMdp" data-ajax="false" method="post" data-transition="<?php echo $transition; ?>">
    					<p>Pour obtenir un nouveau mot de passe, indiquez votre pseudo.</p>
    					<div data-role="fieldcontain" class="ui-hide-label">
    						<label for="txtPseudo">Utilisateur :</label>
    						<input type="text" name="txtPseudo" id="txtPseudo" data-mini="true" placeholder="Mon pseudo" required value="<?php echo $pseudo; ?>" >
    					</div>
    					<div data-role="fieldcontain" class="ui-hide-label">
    						<label for="txtAdrMail">Adresse mail :</label>
    						<input type="email" name="txtAdrMail" id="txtAdrMail" required placeholder="Votre adresse mail (obligatoire)">
    					</div>
    					<div data-role="fieldcontain">
    						<input type="submit" name="demanderMdp" id="demanderMdp" value="M'envoyer un nouveau mot de passe">
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