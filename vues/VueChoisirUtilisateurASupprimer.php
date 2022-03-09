<?php
// Projet TraceGPS - version web mobile
// fichier : vues/VueChoisirUtilisateurASupprimer.php
// Rôle : éditer la liste des utilisateurs pour en supprimer un
// cette vue est appelée par le contôleur controleurs/CtrlChoisirUtilisateurASupprimer.php
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
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Supprimer un utilisateur</h4>
				<p style="text-align: center;"><?php echo $message; ?></p>
				<ul data-role="listview" style="margin-top: 5px;">
				<?php
				// Avec jQuery Mobile, les utilisateurs sont affichés à l'aide d'une liste <ul>
				// chaque utilisateur est affiché à l'aide d'un élément de liste <li>
				// chaque élément de liste <li> peut contenir des titres et des paragraphes

				foreach ($lesUtilisateurs as $unUtilisateur)
				{ 
				    $lien = "index.php?action=SupprimerUtilisateur&pseudoUtilisateurASupprimer=" . $unUtilisateur->getPseudo();
				    ?>
					<li><a href="<?php echo $lien; ?>" data-transition="<?php echo $transition; ?>">
					<h5>Pseudo : <?php echo $unUtilisateur->getPseudo(); ?></h5>
					<p>Email : <?php echo $unUtilisateur->getAdrMail(); ?><br>
					N° tél : <?php echo $unUtilisateur->getNumTel(); ?><br>
					Date création : <?php echo $unUtilisateur->getDateCreation(); ?><br>
					<?php if ($unUtilisateur->getNbTraces() > 0) echo 'Date dernière trace : '. $unUtilisateur->getDateDerniereTrace() . '<br>';?></p>
					<p class="ui-li-aside"><?php echo $unUtilisateur->getNbTraces(); ?> traces</p>
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