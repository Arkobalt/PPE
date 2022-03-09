<?php
// Projet TraceGPS - version web mobile
// fichier : vues/VueSupprimerParcours.php
// Rôle : afficher la vue permettant de supprimer un parcours
// cette vue est appelée par le contrôleur controleurs/CtrlSupprimerParcours.php
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
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Supprimer un de mes parcours</h4>
				<?php if ($typeMessage != 'information')    // si la suppression vient de se faire, ne pas afficher le formulaire
				{ ?>
    				<p>Vous souhaitez supprimer le parcours suivant :</p>
    				<p>Début : <b><?php echo $laTrace->getDateHeureDebut(); ?></b></p>
    				<p>Fin : <b><?php echo $laTrace->getDateHeureFin(); ?></b></p>
    				<p>Distance : <b><?php echo number_format($laTrace->getDistanceTotale(), 1); ?> km</b></p>
    				
    				<form action="index.php?action=SupprimerParcours&idTraceASupprimer=<?php echo $idTraceASupprimer; ?>" method="post" data-ajax="false">
    					<div data-role="fieldcontain">
    					<p style="margin-top: 0px; margin-bottom: 0px;">
    						<a href="index.php?action=Menu" data-role="button" data-mini="true" data-transition="<?php echo $transition; ?>">Annuler et retourner au menu</a>
    					</p>
    					<p style="margin-top: 0px; margin-bottom: 0px;">
        					<a href="index.php?action=ChoisirParcoursASupprimer" data-role="button" data-mini="true" data-transition="<?php echo $transition; ?>">Choisir un autre parcours</a>
        				</p>
    						<input type="submit" name="btnSupprimerParcours" id="btnSupprimerParcours" data-mini="true" value="Supprimer ce parcours" data-mini="true">
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