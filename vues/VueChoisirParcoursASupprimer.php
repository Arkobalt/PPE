<?php
// Projet TraceGPS - version web mobile
// fichier : vues/VueChoisirParcoursASupprimer.php
// Rôle : éditer la liste des traces de l'utilisateur pour en supprimer une
// cette vue est appelée par le contôleur controleurs/CtrlChoisirParcoursASupprimer.php
// Dernière mise à jour : 01/11/2021 par dP
?>
<!doctype html>
<html>
	<head>
		<?php include_once ('vues/head.php'); ?>
	</head>
	 
	<body>
		<div data-role="page">
			<div data-role="header" data-theme="<?php echo $themeNormal; ?>">
				<h4><?php echo $TITRE_APPLI; ?></h4>
				<a href="index.php?action=Menu" data-transition="<?php echo $transition; ?>">Retour menu</a>
			</div>
			<div data-role="content">
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Supprimer un de mes parcours</h4>
				<h5 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Choisir le parcours à supprimer</h5>
				<p style="text-align: center;"><?php echo $message; ?></p>
				<ul data-role="listview" style="margin-top: 5px;">
				<?php
				// Avec jQuery Mobile, les utilisateurs sont affichés à l'aide d'une liste <ul>
				// chaque utilisateur est affiché à l'aide d'un élément de liste <li>
				// chaque élément de liste <li> peut contenir des titres et des paragraphes

				foreach ($lesTraces as $uneTrace)
				{   $lien = "index.php?action=SupprimerParcours&idTraceASupprimer=" . $uneTrace->getId();
				    ?>
					<li><a href="<?php echo $lien; ?>" data-ajax="false" data-transition="<?php echo $transition; ?>">
					<h5>Date : <?php echo substr($uneTrace->getDateHeureDebut(), 0, 10); ?></h5>
					<p>Début : <?php echo substr($uneTrace->getDateHeureDebut(), 11, 8); ?>
					<?php if ($uneTrace->getTerminee() == true) 
					{?>
						<br>Fin : <?php echo substr($uneTrace->getDateHeureFin(), 11, 8); ?>
						<br>Distance : <?php echo number_format($uneTrace->getDistanceTotale(), 1) . " km"; ?></p>
					<?php
                    }
					else
					{ ?>
						<br>Distance : <?php echo number_format($uneTrace->getDistanceTotale(), 1) . " km"; ?></p>
						<h5 class="ui-li-aside">Non terminé</h5>
					<?php
				    } ?>						
					</a></li>
					<?php
				} ?>
				</ul>

			</div>
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal;?>">
				<h4><?php echo $NOM_APPLI; ?></h4>
			</div>
		</div>
		
	</body>
</html>