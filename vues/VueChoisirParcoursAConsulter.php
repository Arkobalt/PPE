<?php
// Projet TraceGPS - version web mobile
// fichier : vues/VueChoisirParcoursAConsulter.php
// Rôle : éditer la liste des traces de l'utilisateur choisi pour en consulter une
// cette vue est appelée par le contôleur controleurs/CtrlChoisirParcoursAConsulter.php
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
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Consulter un parcours</h4>
				<h5 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Choisir le parcours à consulter</h5>
				<p style="text-align: center;"><?php echo $message; ?></p>
				<ul data-role="listview" style="margin-top: 5px;">
				<?php
				// Avec jQuery Mobile, les utilisateurs sont affichés à l'aide d'une liste <ul>
				// chaque utilisateur est affiché à l'aide d'un élément de liste <li>
				// chaque élément de liste <li> peut contenir des titres et des paragraphes

				foreach ($lesTraces as $uneTrace)
				{   $lien = "index.php?action=ConsulterParcours&pseudoUtilisateur=" . $pseudoUtilisateur . "&idTraceAConsulter=" . $uneTrace->getId();
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
				
				<p>&nbsp;</p>
				<p style="margin-top: 0px; margin-bottom: 0px;">
					<a href="index.php?action=ChoisirUtilisateurPourConsulterParcours" data-role="button" data-mini="true" data-transition="<?php echo $transition; ?>">Autre utilisateur</a>
				</p>

			</div>
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal;?>">
				<h4><?php echo $NOM_APPLI; ?></h4>
			</div>
		</div>
		
	</body>
</html>