<?php
// Projet TraceGPS - version web mobile
// fichier : vues/VueEnvoyerPosition.php
// Rôle : afficher la vue d'envoi périodique de la position
// cette vue est appelée par le contrôleur controleurs/CtrlEnvoyerPosition.php
// Dernière mise à jour : 01/11/2021 par dP

?>
<!doctype html>
<html>
	<head>
		<?php include_once ('vues/head.php'); ?>
		
		<script>
			var compteur;		// durée en secondes entre 2 envois de la position
			var timer;			// pour l'appel périodique (toutes les 1 seconde) de la fonction decompter
			
			// dès que la page est chargée (événement onload), la fonction initialisations est exécutée
			window.onload = initialisations;
			
			// la fonction initialisations appelée à la fin du chargement de la page
			function initialisations() {
				// initialise le compteur de secondes
				compteur = <?php echo $_SESSION['frequence']; ?>;
				
				// affiche la durée restante avant le prochain envoi
				document.getElementById("dureeRestante").innerHTML = compteur;
				
				// événement "onclick" du bouton "btnArreter" associé à la fonction "arreter"
				document.getElementById("btnArreter").onclick = arreter;
					
	    		// démarrer l'appel périodique de la fonction decompter, avec un intervalle de 1 sec (1000 ms)
	    		timer = window.setInterval("decompter()", 1000);
			}

            function decompter() {
            	compteur--;
            	document.getElementById("dureeRestante").innerHTML = compteur;
            	if (compteur == 0)
            	{	clearInterval(timer);	// arrêter le timer
            		getLocalisation();		// démarre la géolocalisation
            	}
            }
			
			function getLocalisation() {
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(localiser, erreur, {enableHighAccuracy:true, timeout:10000, maximumAge:20000});
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
				document.getElementById("frmEnvoyerPosition").submit();
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

			function arreter() {
				// si le mot 'stop' a bien été saisi, on valide le formulaire
				if (document.getElementById("txtStop").value.toLowerCase() == 'stop') {
					clearInterval(timer);										// arrêter le timer
					document.getElementById("frmEnvoyerPosition").submit();		// valider le formulaire
				}
				else {
					msg = "Pour arrêter de transmettre votre position, tapez le mot 'stop' dans la zone de texte et validez avec le bouton 'Arrêter l'enregistrement'.";
					alert (msg);
				}
				
			}
		</script>
	</head>
	
	<body>
		<div data-role="page" id="page_principale">
			<div data-role="header" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $TITRE_APPLI; ?></h4>
			</div>
			
			<div data-role="content">
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Envoyer ma position</h4>

				<form name="frmEnvoyerPosition" id="frmEnvoyerPosition" action="index.php?action=EnvoyerPosition" method="post" data-ajax="false">
					<div data-role="fieldcontain" class="ui-hide-label">
						<p>Prochaine géolocalisation dans <b><span id="dureeRestante"></span></b> secondes</p>					
					</div>
					
					<div data-role="fieldcontain" class="ui-hide-label">
						<label for="txtLatitude">Latitude :</label>
						<input type="text" name="txtLatitude" id="txtLatitude" readonly data-mini="true" placeholder="Latitude" value="<?php echo $latitude; ?>">

						<label for="txtLongitude">Longitude :</label>
						<input type="text" name="txtLongitude" id="txtLongitude" readonly="readonly" data-mini="true" placeholder="Longitude" value="<?php echo $longitude; ?>">
						
						<label for="txtAltitude">Altitude :</label>
						<input type="text" name="txtAltitude" id="txtAltitude" readonly="readonly" data-mini="true" placeholder="Altitude" value="<?php echo $altitude; ?>">

						<p><b>Conseil :</b> Pour diminuer la consommation d'énergie, pensez à baisser la luminosité de l'écran.</p>
						
						<p><b>Fin du parcours :</b> Pour arrêter de transmettre votre position, tapez le mot "stop" dans la zone de texte et validez avec le bouton <i>Arrêter l'enregistrement</i>.</p>

						<label for="txtStop">Tapez stop :</label>
						<input type="text" name="txtStop" id="txtStop" data-mini="true" placeholder="Tapez le mot 'stop' pour terminer le parcours" value="">
						
						<input type="button" name="btnArreter" id="btnArreter" value="Arrêter l'enregistrement" data-mini="true">
					</div>
				</form>
				
			</div>
			
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $NOM_APPLI; ?></h4>
			</div>
		</div>
		
	</body>
</html>