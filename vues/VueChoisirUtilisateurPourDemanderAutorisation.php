<?php
// Projet TraceGPS - version web mobile
// fichier : vues/VueChoisirUtilisateurPourDemanderAutorisation.php
// Rôle : éditer la liste des utilisateurs pour demander une autorisation
// cette vue est appelée par le contôleur controleurs/CtrlChoisirUtilisateurPourDemanderAutorisation.php
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
				<h4 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Demander une autorisation</h4>
				<h5 style="text-align: center; margin-top: 0px; margin-bottom: 0px;">Choisir l'utilisateur que je veux suivre</h5>
				<p style="text-align: center;"><?php echo $message; ?></p>
				<ul data-role="listview" style="margin-top: 5px;">
				<?php
				// Avec jQuery Mobile, les utilisateurs sont affichés à l'aide d'une liste <ul>
				// chaque utilisateur est affiché à l'aide d'un élément de liste <li>
				// chaque élément de liste <li> peut contenir des titres et des paragraphes

				foreach ($lesUtilisateurs as $unUtilisateur)
				{   $idAutorisant = $unUtilisateur->getId();
				    if ($dao->autoriseAConsulter($idAutorisant, $idDemandeur))
				        $lien = "#";
				    else
				        $lien = "index.php?action=DemanderAutorisation&pseudoDestinataire=" . $unUtilisateur->getPseudo();
				    if ($idAutorisant != $idDemandeur)
				    {
    				    ?>
    					<li><a href="<?php echo $lien; ?>" data-transition="<?php echo $transition; ?>">
    					<h5><?php echo $unUtilisateur->getPseudo(); ?></h5>
    					<?php if ($dao->autoriseAConsulter($idAutorisant, $idDemandeur)) echo '<p class="ui-li-aside">Autorisation déjà accordée.</p>';?>
    					</a></li>
    					<?php
				    }
				} ?>
				</ul>

			</div>
			<div data-role="footer" data-position="fixed" data-theme="<?php echo $themeNormal;?>">
				<h4><?php echo $NOM_APPLI; ?></h4>
			</div>
		</div>
		
	</body>
</html>