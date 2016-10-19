<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title><?php echo $pagetitle; ?></title>
	</head>

	<body>
		<p style="border: 1px solid black;text-align:center;padding-right:1em;">
		  <a href="index.php">Accueil</a> |
		  <a href="index.php?controller=voiture&action=readAll">Voir toutes les voitures</a> |
		  <a href="index.php?controller=voiture&action=create">Créer une voiture</a> |
		  <a href="index.php?controller=utilisateur&action=readAll">Utilisateurs</a> |
		  <a href="index.php?controller=trajet&action=readAll">Trajets</a>
		</p>

		<?php
			$filepath = File::build_path(array("view", static::$object, "$view.php"));
			require $filepath;
		?>
		
		<p style="border: 1px solid black;text-align:center;padding-right:1em;">
		  Site de covoiturage de Quentin
		</p>
	</body>
</html>