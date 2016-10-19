<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title><?php echo $pagetitle; ?></title>
	</head>

	<body>
		<p style="border: 1px solid black;text-align:center;padding-right:1em;">
		  <a href="index.php">Accueil</a> |
		  <a href="index.php?controller=produit&action=readAll">Voir tous les produits</a> |
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