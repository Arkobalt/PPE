<?php
// Projet TraceGPS - version web mobile
// fichier : vues/VueConsulterParcoursNonTermine.php
// Rôle : afficher la vue permettant de consulter un parcours non terminé
// cette vue est appelée par le contrôleur controleurs/CtrlConsulterParcours.php
// Dernière mise à jour : 01/11/2021 par dP

?>
<!doctype html>
<html>
	<head>
    	<?php include_once ('vues/head.php');

		if ($laTrace->getNombrePoints() > 0)
    	{ ?>
    		<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=<?php echo $CLE_API; ?>"></script>					
    		<script>
    			var compteur;		// durée en secondes entre 2 réactualisations de la page
    			var timer;			// pour l'appel périodique (toutes les 1 seconde) de la fonction decompter
    			
    			// dès que la page est chargée (événement onload), la fonction initialisations est exécutée
    			window.onload = initialisations;
    			
    			// la fonction initialisations appelée à la fin du chargement de la page
    			function initialisations() {
    				// initialise le compteur de secondes
    				compteur = <?php echo $FREQUENCE_AFFICHAGE; ?>;
    				
    				// affiche la durée restante avant le prochain envoi
    				document.getElementById("btnActualiser").innerHTML = "Actualiser (automatique dans " + compteur + " sec.)";
    				
    				// afficher la carte
    				afficherCarte();
    				
    	    		// démarrer l'appel périodique de la fonction decompter, avec un intervalle de 1 sec (1000 ms)
    	    		timer = window.setInterval("decompter()", 1000);
    			}

                function decompter() {
                	compteur--;
                	document.getElementById("btnActualiser").innerHTML = "Actualiser (automatique dans " + compteur + " sec.)";
                	if (compteur == 0)
                	{	clearInterval(timer);	// arrêter le timer
                		document.location.href="index.php?action=ConsulterParcours&pseudoUtilisateur=<?php echo $pseudoUtilisateur; ?>&idTraceAConsulter=<?php echo $idTraceAConsulter; ?>";
                	}
                }
    			
    			// la fonction afficherCarte permet d'afficher la carte
    			function afficherCarte() {
    				// calcul du centre du parcours
    				var leCentre = new google.maps.LatLng(<?php echo $leCentre->getLatitude(); ?>, <?php echo $leCentre->getLongitude(); ?>);
    		
    				// on initialise les options à utiliser (le zoom, le centrage ainsi que le type de carte)
    				// 		ROADMAP pour le plan
    				// 		SATELLITE pour les photos satellite ; 
    				// 		HYBRID pour afficher les photos satellites avec le plan superposé
    				// 		TERRAIN pour afficher les reliefs
    				var mesOptions = {
    					zoom: 14,
    					center: leCentre,
    					mapTypeId: google.maps.MapTypeId.HYBRID 
    				}
    		
    				// on initialise la carte que l'on va insérer dans la div "divCarte" avec les données de "mesOptions"
    				var laCarte = new google.maps.Map(document.getElementById("divCarte"), mesOptions);

    				// construction d'un tableau Javascript contenant les points de passage
					var lesPointsDePassage = [
    	    		<?php
    	    		$i = 1;
    	    		$nbMarqueurs = sizeof($laTrace->getLesPointsDeTrace());
    	    		foreach ($laTrace->getLesPointsDeTrace() as $unPoint) {
    	    		    $heure =  "Heure : " . substr($unPoint->getDateHeure(), 11, 8);
    	    		    echo "{ lat : " . $unPoint->getLatitude() . ", lon : " . $unPoint->getLongitude() . ", heure : '" . $heure . "'}\n";
    	    		    if ($i < $nbMarqueurs) echo ",";     // pas de "," après le dernier élément du tableau
    	    		    $i++;
		            }?>
		            ];
					// déclaration d'un tableau de marqueurs
					var lesMarqueurs = new Array();
		            // début de la boucle d'affichage des marqueurs
		            for (i = 0 ; i < lesPointsDePassage.length ; i++)
		            {	unPointDePassage = lesPointsDePassage[i];
		            	unPoint = new google.maps.LatLng(unPointDePassage.lat, unPointDePassage.lon);
		            	
		            	// choix de l'icône pour le marqueur
		            	var image = "./images/carre-rouge.png";
		            	if (i == 0 ) image = "./images/carre-rouge-depart.png";
		            	if (i == lesPointsDePassage.length - 1) image = "./images/drapeau.png";

		            	// affiche le marqueur numéro i
		            	lesMarqueurs[i] = new google.maps.Marker({
	    					position: unPoint,
	    					map: laCarte,
	    					title: unPointDePassage.heure,
	    					icon: image
	    				});
	    				
						// traitement particulier du premier point
		            	if (i == 0) {
		    				// on initialise un message pour le premier point
		    				var message = "<b>Départ : <?php echo $heureDebut; ?></b>";
		    				
		    				// on initialise la fenêtre d'affichage du message avec une grandeur maximum de 160
		    				var popupDepart = new google.maps.InfoWindow({
		    					content: message,
		    					maxWidth: 160
		    			  	});

		    				// on ajoute un listener au click sur le dernier marqueur créé afin d'y ouvrir "popupDepart" qui contient "message"
		    				lesMarqueurs[i].addListener('click', function() {
		    					popupDepart.open(laCarte, lesMarqueurs[i]);
		    				});

		    				// et on ouvre le popup au démarrage
		    				popupDepart.open(laCarte, lesMarqueurs[i]);
		            	}
		            					
						// traitement particulier du dernier point
		            	if (i == lesPointsDePassage.length - 1) {
		    				// on initialise un message pour le dernier point
	    					message = "<b>Dernière position connue :<br></b><br>";
		    				message += "Heure : <b>" + unPointDePassage.heure + "</b><br>";
		    				message += "Latitude : <b>" + unPointDePassage.lat + "</b><br>";
		    				message += "Longitude : <b>" + unPointDePassage.lon + "</b>";
		    				
		    				// on initialise la fenêtre d'affichage du message avec une grandeur maximum de 160
		    				var popupArrivee = new google.maps.InfoWindow({
		    					content: message,
		    					maxWidth: 160
		    			  	});

		    				// on ajoute un listener au click sur le dernier marqueur créé afin d'y ouvrir "popupArrivee" qui contient "message"
		    				lesMarqueurs[i].addListener('click', function() {
		    					popupArrivee.open(laCarte, lesMarqueurs[i]);
		    				});

		    				// et on ouvre le popup au démarrage
		    				popupArrivee.open(laCarte, lesMarqueurs[i]);
		            	}
		            } // fin de la boucle d'affichage des marqueurs
    			}	// fin fonction afficherCarte	
    		</script>
		<?php 
		} ?>
	</head>
	
	<body>
		<div data-role="page" id="page_principale">
			<div data-role="header" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $TITRE_APPLI; ?></h4>
				<a href="index.php?action=Menu" data-ajax="false" data-transition="<?php echo $transition; ?>">Retour menu</a>
			</div>
			
			<div data-role="content">
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Consulter un parcours de <?php echo $pseudoUtilisateur; ?></h4>
				<p>Parcours terminé : <b><?php if ($laTrace->getTerminee() == true) echo "oui"; else echo "non"; ?></b><br>
				Début le <b><?php echo $dateDebut; ?></b> à <b><?php echo $heureDebut; ?></b><br>
				Dernière localisation : <b><?php echo $heureDernierPoint; ?></b> (depuis <?php echo $tempsEcoule; ?>)<br>
				Distance (km): <b><?php echo number_format($laTrace->getDistanceTotale(), 1); ?></b><br>
				Durée totale (h:mn:sec) : <b><?php echo $laTrace->getDureeTotale(); ?></b><br>
				Vitesse moyenne (km/h) : <b><?php echo number_format($laTrace->getVitesseMoyenne(), 1); ?></b></p>
				
				<?php if ($laTrace->getNombrePoints() > 0)
				{ ?>
    				<p><b>Dernière position connue :</b><br>
    				Latitude : <b><?php echo $latitude; ?></b>&nbsp;&nbsp;
    				Longitude : <b><?php echo $longitude; ?></b></p>
				<?php 
				} ?>
				
				<div id="divCarte" style="margin: 0px auto 15px auto; width:300px; height: 300px" role="main" class="ui-content">
					Veuillez attendre le chargement de la page...
				</div>
    				
    			<?php if ($laTrace->getNombrePoints() > 0)
				{ ?>
    				<p style="margin-top: 0px; margin-bottom: 0px;">
    					<a href="index.php?action=ConsulterParcours&pseudoUtilisateur=<?php echo $pseudoUtilisateur; ?>&idTraceAConsulter=<?php echo $idTraceAConsulter; ?>" 
    					data-role="button" data-mini="true" data-ajax="false" data-transition="<?php echo $transition; ?>"><span id="btnActualiser">Actualiser</span></a>
    				</p>
				<?php 
				} ?>
							
				<p style="margin-top: 0px; margin-bottom: 0px;">
					<a href="index.php?action=ChoisirParcoursAConsulter&pseudoUtilisateur=<?php echo $pseudoUtilisateur; ?>&time=<?php echo time(); ?>" 
					data-role="button" data-mini="true" data-transition="<?php echo $transition; ?>">Autre parcours de <?php echo $pseudoUtilisateur; ?></a>
				</p>
			</div>
			
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $NOM_APPLI; ?></h4>
			</div>
		</div>
		
		<?php include_once ('vues/dialog_message.php'); ?>
		
	</body>
</html>