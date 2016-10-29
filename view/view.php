<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $pagetitle; ?></title>

		<!-- Bootstrap -->
		<?php 
			require File::build_path(array("assets", "css", "styles.php"));
		?>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
	    <nav class="navbar navbar-inverse navbar-fixed-top menuHaut">
	      <div class="container">
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	            <span class="sr-only">Navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <a class="navbar-brand visible-xs" href="index.php">So'Cap</a>
	        </div>
	        <div id="navbar" class="collapse navbar-collapse">
	          <ul class="nav navbar-nav">
	            <li class="logoBrand"><a href="index.php">So'CAP</a></li>
	            <li <?php ControllerDefault::active('index', ''); ?>><a href="index.php">Accueil</a></li>
	            <li <?php ControllerDefault::active('produit', ''); ?>><a href="index.php?controller=produit&action=readAll">Produits</a></li>
	            <?php 
	            	if(!isset($_SESSION['login'])) {
	            ?>
	            	<li <?php ControllerDefault::active('utilisateur', 'connect'); ?>><a href="index.php?controller=utilisateur&action=connect">Connexion</a></li>
	            	<li <?php ControllerDefault::active('utilisateur', 'register'); ?>><a href="index.php?controller=utilisateur&action=register">Inscription</a></li>
	            <?php } else { ?>
	            	<li <?php ControllerDefault::active('commande', ''); ?>><a href="index.php?controller=commande&action=readAll">Vos commandes</a></li>
	            	<li <?php ControllerDefault::active('utilisateur', 'profil'); ?>><a href="index.php?controller=utilisateur&action=profil">Mon Profil</a></li>
	            	<li <?php ControllerDefault::active('utilisateur', 'disconnect'); ?>><a href="index.php?controller=utilisateur&action=disconnect">Déconnexion</a></li>
	            <?php } ?>
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>

		<?php
			if(isset($powerNeeded) && $powerNeeded == true) {
	 			if($view != 'index') {
					echo '<div class="container page">';
				}
					$filepath = File::build_path(array("view", static::$object, "$view.php"));
					require $filepath;
				if($view != 'index') {
					echo '</div>';
				}
			} else {
				echo '<div class="container page"><div class="alert alert-danger">Vous ne possédez pas les droits pour accéder à cette page</div></div>';
			}
		?>

		<?php 
			require File::build_path(array("assets", "js", "js.php"));
		?>

		<footer class="footer">
			<div class="container">
				<p class="text-muted">So'Cap 2016-2017 - Tous droits réservés.</p>
			</div>
		</footer>
	</body>
</html>