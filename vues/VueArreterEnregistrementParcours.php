<?php
// Projet TraceGPS - version web mobile
// fichier : vues/VueArreterEnregistrementParcours.php
// Rôle : afficher les données du parcours qui vient de se terminer
// cette vue est appelée par le contrôleur controleurs/CtrlArreterEnregistrementParcours.php
// Dernière mise à jour : 01/11/2021 par dP
?>
<!doctype html>
<html>
	<head>
    	<?php include_once ('vues/head.php');?>
	</head>
	
	<body>
		<div data-role="page" id="page_principale">
			<div data-role="header" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $TITRE_APPLI; ?></h4>
				<a href="index.php?action=Menu" data-transition="<?php echo $transition; ?>">Retour menu</a>
			</div>
			
			<div data-role="content">
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Fin de parcours</h4>
				<p>Parcours terminé : <b>oui</b><br>
				Date et heure début : <b><?php echo $laTrace->getDateHeureDebut(); ?></b><p>
				
				<p>Date et heure fin : <b><?php echo $laTrace->getDateHeureFin(); ?></b><br>
				Distance (km): <b><?php echo number_format($laTrace->getDistanceTotale(), 1); ?></b><br>
				Dénivelé positif (m) : <b><?php echo number_format($laTrace->getDenivelePositif(), 0); ?></b><br>
				Dénivelé négatif (m) : <b><?php echo number_format($laTrace->getDeniveleNegatif(), 0); ?></b><br>
				Durée totale (h:mn:sec) : <b><?php echo $laTrace->getDureeTotale(); ?></b><br>
				Vitesse moyenne (km/h) : <b><?php echo number_format($laTrace->getVitesseMoyenne(), 1); ?></b></p>

			</div>
			
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $NOM_APPLI; ?></h4>
			</div>
		</div>
		
		<?php include_once ('vues/dialog_message.php'); ?>
		
	</body>
</html>