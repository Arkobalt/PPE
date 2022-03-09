<?php
// Projet TraceGPS - version web mobile
// fichier : vues/VueDemarrerEnregistrementParcours.php
// Rôle : afficher la vue de démarrage d'un parcours
// cette vue est appelée par le contrôleur controleurs/CtrlDemarrerEnregistrementParcours.php
// Dernière mise à jour : 01/11/2021 par dP
?>
<!doctype html>
<html>
	<head>
		<?php include_once ('vues/head.php'); ?>
		
		<script>
		
			// associe une fonction à l'événement pageinit
			$(document).bind('pageinit', function() {
				var activer1 = false;	// devient true quand la fréquence a été choisie
				var activer2 = false;	// devient true quand la position est connue
				
				// l'événement "click" des 3 boutons radios est associé à la fonction "activerBouton"
				$('#btnFrequenceMarche').click( activerBouton );
				$('#btnFrequenceFooting').click( activerBouton );
				$('#btnFrequenceVelo').click( activerBouton );
				
				// désactive le bouton au départ
				document.getElementById("btnDemarrer").disabled = true;
				// démarre la géolocalisation
				getLocalisation();
				
				<?php if ($typeMessage != '') { ?>
					// affiche la boîte de dialogue 'affichage_message'
					$.mobile.changePage('#affichage_message', {transition: "<?php echo $transition; ?>"});
				<?php } ?>
			} );

			function activerBouton() {
				activer1 = true;
				if (activer2 == true)	// si les 2 conditions sont true, le bouton se réactive
					document.getElementById("btnDemarrer").disabled = false;
			}
			
			function getLocalisation() {
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(localiser, erreur, {enableHighAccuracy:true, timeout:10000, maximumAge:10000});
				}
				else {
					var msg = "Votre navigateur ne prend pas en charge la géolocalisation !";
					$('#txtLatitude').attr('value', msg);
				}
			}
						
			function localiser(position) {
				var latitude = position.coords.latitude;
				var longitude = position.coords.longitude;
				var altitude = position.coords.altitude;
				if (altitude == null) altitude = '0';
				$('#txtLatitude').attr('value', latitude);
				$('#txtLongitude').attr('value', longitude);
				$('#txtAltitude').attr('value', altitude);
				// pour activer le bouton, il faut avoir choisi la fréquence et que la géolocalisation soit réalisée
				activer2 = true;
				if (activer1 == true)	// si les 2 conditions sont true, le bouton se réactive
					document.getElementById("btnDemarrer").disabled = false;
			}

			function erreur(error) {
				switch (error.code) {
					case error.UNKNOWN_ERROR:
						msg = "La géolocalisation a rencontré un problème !"; break;
					case error.PERMISSION_DENIED:
						msg = "Vous n'avez pas autorisé l\'accès à votre position !"; break;
					case error.POSITION_UNAVAILABLE:
						msg = "La localisation est impossible !"; break;
					case error.TIMEOUT:
						msg = "La géolocalisation a dépassé le temps normal !"; break;
				}
				$('#txtLatitude').attr('value', msg);
			}
		</script>
	</head>
	
	<body>
		<div data-role="page" id="page_principale">
			<div data-role="header" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $TITRE_APPLI; ?></h4>
				<a href="index.php?action=Menu" data-transition="<?php echo $transition; ?>">Retour menu</a>
			</div>
			
			<div data-role="content">
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Démarrer l'enregistrement d'un parcours</h4>

				<form action="index.php?action=DemarrerEnregistrementParcours" method="post" data-ajax="false">
					<div data-role="fieldcontain" class="ui-hide-label">
						<p>Pour pouvoir transmettre votre position, la géolocalisation doit être activée sur votre appareil.</p>
						<p>La géolocalisation étant consommatrice d'énergie, choisissez la fréquence d'envoi en fonction de votre vitesse moyenne de déplacement :</p>					
					
						<fieldset data-role="controlgroup">
							<legend>Fréquence d'envoi :</legend>
							<input type="radio" name="btnFrequence" id="btnFrequenceMarche" value="120" data-mini="true" <?php if ($frequence == "120") echo 'checked';?> >
							<label for="btnFrequenceMarche">Toutes les 2 mn (conseillé pour la marche)</label>
							<input type="radio" name="btnFrequence" id="btnFrequenceFooting" value="60" data-mini="true" <?php if ($frequence == "60") echo 'checked';?> >
							<label for="btnFrequenceFooting">Toutes les 1 mn (conseillé pour le footing)</label>
							<input type="radio" name="btnFrequence" id="btnFrequenceVelo" value="15" data-mini="true" <?php if ($frequence == "15") echo 'checked';?> >
							<label for="btnFrequenceVelo">Toutes les 15 sec (conseillé pour le vélo)</label>
						</fieldset>
						
					</div>
					
					<div data-role="fieldcontain" class="ui-hide-label">
						<p><b>Attention :</b> le bouton <i>Démarrer l'enregistrement</i> est désactivé tant que vous n'avez pas choisi la fréquence et tant que 
						l'appareil ne connaît pas votre position. 
						Attendez que celle-ci apparaisse dans les 3 zones suivantes et que le bouton se réactive</p>
						<label for="txtLatitude">Latitude :</label>
						<input type="text" name="txtLatitude" id="txtLatitude" readonly data-mini="true" placeholder="Latitude" value="<?php echo $latitude; ?>">

						<label for="txtLongitude">Longitude :</label>
						<input type="text" name="txtLongitude" id="txtLongitude" readonly="readonly" data-mini="true" placeholder="Longitude" value="<?php echo $longitude; ?>">
						
						<label for="txtAltitude">Altitude :</label>
						<input type="text" name="txtAltitude" id="txtAltitude" readonly="readonly" data-mini="true" placeholder="Altitude" value="<?php echo $altitude; ?>">
					</div>

					<div data-role="fieldcontain">
						<input type="submit" name="btnDemarrer" id="btnDemarrer" value="Démarrer l'enregistrement" data-mini="true">
					</div>
				</form>
				
			</div>
			
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $NOM_APPLI; ?></h4>
			</div>
		</div>
		
		<?php include_once ('vues/dialog_message.php'); ?>
		
	</body>
</html>