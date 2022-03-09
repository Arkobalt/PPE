<?php
// Projet TraceGPS - version web mobile
// fichier : vues/VueTesterGeolocalisation.php
// Rôle : afficher la vue de test de la géolocalisation
// cette vue est appelée par le contrôleur controleurs/CtrlTesterGeolocalisation.php
// Dernière mise à jour : 01/11/2021 par dP

// pour obliger la page à se recharger
// header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
// header('Pragma: no-cache');
// header('Content-Tranfer-Encoding: none');
// header('Expires: 0');
?>
<!doctype html>
<html>
	<head>
    	<?php include_once ('vues/head.php'); ?>
    
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=<?php echo $CLE_API; ?>"></script>
						
		<script>
			var latitude;
			var longitude;
			var altitude;
		
			// dès que la page est chargée (événement onload), la fonction getLocalisation est exécutée
			window.onload = getLocalisation;
			
			function getLocalisation() {
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(localiser, erreur, {enableHighAccuracy:true, timeout:10000, maximumAge:20000});
				}
				else {
					var msg = "Votre navigateur ne prend pas en charge la géolocalisation !";
					document.getElementById("txtMessage").innerHTML = msg;
				}
			}
						
			function localiser(position) {
				latitude = position.coords.latitude;
				longitude = position.coords.longitude;
				altitude = position.coords.altitude;
				if (altitude == null) altitude = '0';
				afficherCarte();
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
				document.getElementById("txtMessage").innerHTML = msg;
			}
		
			// Fonction qui affiche la carte
			function afficherCarte() {
				// on initialise la latitude et la longitude en fonction de la BDD
				var unPoint = new google.maps.LatLng(latitude, longitude);
		
				// on initialise les options à utiliser (le zoom, le centrage ainsi que le type de carte)
				// 		ROADMAP pour le plan
				// 		SATELLITE pour les photos satellite ; 
				// 		HYBRID pour afficher les photos satellites avec le plan superposé
				// 		TERRAIN pour afficher les reliefs
				var mesOptions = {
					zoom: 15,
					center: unPoint,
					mapTypeId: google.maps.MapTypeId.HYBRID 
				}
		
				// on initialise la carte que l'on va insérer dans la div "divCarte" avec les données de "mesOptions"
				var laCarte = new google.maps.Map(document.getElementById("divCarte"), mesOptions);
		
				// on initialise un message
				var message = "<b>Vous êtes ici :</b><br><br>";
				message += "Latitude : <b>" + latitude.toString().substring(0, 10) + "</b><br>";
				message += "Longitude : <b>" + longitude.toString().substring(0, 10) + "</b>";
		
				// on initialise la fenêtre d'affichage du message avec une grandeur maximum de 150
				var popup = new google.maps.InfoWindow({
					content: message,
					maxWidth: 150
			  	});

			  	// on initialise le marqueur rouge sur la carte qui se positionnera au niveau de la latitude et longitude initialisé auparavant
				var marqueur = new google.maps.Marker({
					position: unPoint,
					map: laCarte
				})
				
				// on ajoute un listener au click sur le marqueur créé afin d'y ouvrir "popup" qui contient "message"
				marqueur.addListener('click', function() {
					popup.open(laCarte, marqueur);
				});

			  	// et on ouvre le popup au démarrage
				popup.open(laCarte, marqueur);
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
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Tester la géolocalisation</h4>
				<span id="txtMessage">&nbsp;</span>
				<br>
				
				<div id="divCarte" style="margin: 0px auto 15px auto; width:300px; height: 300px" role="main" class="ui-content">
					Veuillez attendre le chargement de la page...
				</div>
			</div>
			
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $NOM_APPLI; ?></h4>
			</div>
		</div>
		
		<?php include_once ('vues/dialog_message.php'); ?>
		
	</body>
</html>