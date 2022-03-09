<?php
    // Projet TraceGPS - version web mobile
    // fichier : vues/VueChangerDeMdp.php
    // Rôle : visualiser la demande de changement de mot de passe
    // cette vue est appelée par le contrôleur controleurs/CtrlChangerDeMdp.php
// Dernière mise à jour : 01/11/2021 par dP
	
	// pour obliger la page à se recharger
	/*
	header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
	header('Pragma: no-cache');
	header('Content-Tranfer-Encoding: none');
	header('Expires: 0');
	*/
?>
<!doctype html>
<html>
	<head>
		<?php include_once ('vues/head.php'); ?>
		
		<script>
				// associe une fonction à l'événement pageinit
				$(document).bind('pageinit', function() {
					// l'événement "click" de la case à cocher "caseAfficherMdp" est associé à la fonction "afficherMdp"
					$('#caseAfficherMdp').click( afficherMdp );
				
					<?php if ($typeMessage != '') { ?>
						// affiche la boîte de dialogue 'affichage_message'
						$.mobile.changePage('#affichage_message', {transition: "<?php echo $transition; ?>"});
					<?php } ?>
				} );
			
			
			// associe une fonction à l'événement click sur la case à cocher 'caseAfficherMdp'
			$('#caseAfficherMdp').live('click', function() {
				if ($('#caseAfficherMdp').attr('checked') == true) {
					('#txtNouveauMdp').attr('type', 'text');
					('#txtNouveauMdp').input('refresh');
					('#txtConfirmationMdp').attr('type', 'text');
					('#txtConfirmationMdp').input('refresh');
					//window.alert('true');
				}
				else {
					('#txtNouveauMdp').attr('type', 'password');
					('#txtNouveauMdp').input('refresh');
					('#txtConfirmationMdp').attr('type', 'password');
					('#txtConfirmationMdp').input('refresh');
					//window.alert('false');
				};
			} );

			// selon l'état de la case, le type des zones de saisie est "text" ou "password"
			// Fonction jQuery
			function afficherMdp() {
				// tester si la case est cochée
				if ( $("#caseAfficherMdp").is(":checked") ) {
					// les 2 zones passent en <input type="text">
					$('#txtNouveauMdp').attr('type', 'text');
					$('#txtConfirmationMdp').attr('type', 'text');
				}
				else {
					// les 2 zones passent en <input type="password">
					$('#txtNouveauMdp').attr('type', 'password');
					$('#txtConfirmationMdp').attr('type', 'password');
				};
			}

			// selon l'état de la case, le type des zones de saisie est "text" ou "password"
			// fonction JavaScript	
			function afficherMdp2()
			{	if (document.getElementById("caseAfficherMdp").checked == true)
				{	document.getElementById("txtNouveauMdp").type="text";
					document.getElementById("txtConfirmationMdp").type="text";
					// window.alert('true');
				}
				else
				{	document.getElementById("txtNouveauMdp").type="password";
					document.getElementById("txtConfirmationMdp").type="password";
					// window.alert('false');
				}
			};
		</script>
	</head> 
	<body>
		<div data-role="page" id="page_principale">
			<div data-role="header" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $TITRE_APPLI; ?></h4>
				<a href="index.php?action=Menu" data-transition="<?php echo $transition; ?>">Retour menu</a>
			</div>
			<div data-role="content">
				<h4 style="text-align: center; margin-top: 10px; margin-bottom: 10px;">Changer de mot de passe</h4>
				<?php if ($typeMessage != 'information')    // si l'envoi vient de se faire, ne pas réafficher le formulaire
				{ ?>
    				<form action="index.php?action=ChangerDeMdp" method="post" data-ajax="false">
    					<div data-role="fieldcontain">
    						<label for="txtNouveauMdp">Nouveau mot de passe :</label>
    						<input type="<?php if ($afficherMdp == 'off') echo 'password'; else echo 'text'; ?>" name="txtNouveauMdp" id="txtNouveauMdp" 
    							placeholder="Mon nouveau mot de passe" required value="<?php echo $nouveauMdp; ?>">
    					</div>
    					<div data-role="fieldcontain">
    						<label for="txtConfirmationMdp">Confirmation nouveau mot de passe :</label>
    						<input type="<?php if ($afficherMdp == 'off') echo 'password'; else echo 'text'; ?>" name="txtConfirmationMdp" id="txtConfirmationMdp" 
    							placeholder="Confirmation de mon nouveau mot de passe" required value="<?php echo $confirmationMdp; ?>">
    					</div>
    					<div data-role="fieldcontain" data-type="horizontal" class="ui-hide-label">
    						<label for="caseAfficherMdp">Afficher le mot de passe en clair</label>
    						<input type="checkbox" name="caseAfficherMdp" id="caseAfficherMdp" data-mini="true" <?php if ($afficherMdp == 'on') echo 'checked'; ?>>
    					</div>
    					<div data-role="fieldcontain">
    						<input type="submit" name="btnChangerDeMdp" id="btnChangerDeMdp" value="Envoyer les données" data-mini="true">
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