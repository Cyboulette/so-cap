<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $pagetitle; ?></title>

		<!-- Bootstrap -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/css/style.css" rel="stylesheet">
		<link href="assets/css/style-new.css" rel="stylesheet">
		<link href="assets/css/font-awesome.min.css" rel="stylesheet">
		<link href="assets/css/style_tablesorter.css" rel="stylesheet">
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
	            	// Ici on a un bel exemple de la fonction autoload du routeur.
	            	// On a besoin de déterminer si un user est connecté et pour cela la méthode statique est dans le Controller.
	            	// Sauf qu'ici il n'est pas inclus de base, en utilisant ControllerUtilisateur, cela appelle l'autoload et donc auto-charge le fichier.
					if(!ControllerUtilisateur::isConnected()) {
						$currentUser = null;
	            ?>
	            	<li <?php ControllerDefault::active('utilisateur', 'connect'); ?>><a href="index.php?controller=utilisateur&action=connect">Connexion</a></li>
	            	<li <?php ControllerDefault::active('utilisateur', 'register'); ?>><a href="index.php?controller=utilisateur&action=register">Inscription</a></li>
	            <?php } else { 
	            	$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
	            ?>
	            	<li <?php ControllerDefault::active('commande', ''); ?>><a href="index.php?controller=commande&action=readAll">Vos commandes</a></li>
	            	<li <?php ControllerDefault::active('utilisateur', 'profil'); ?>><a href="index.php?controller=utilisateur&action=profil">Mon Profil</a></li>
	            	<li <?php ControllerDefault::active('utilisateur', 'disconnect'); ?>><a href="index.php?controller=utilisateur&action=disconnect">Déconnexion</a></li>
	            	<?php if($currentUser->getPower() == Conf::$power['admin']) { ?>
	            		<li <?php ControllerDefault::active('admin', ''); ?>><a href="index.php?controller=admin&action=index">Administration</a></li>
	            	<?php } ?>
	            <?php } ?>
	            	<li><a href="#" data-toggle="modal" data-target="#panier" class="panier"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Panier
	            	<span class="label label-success label-xs nbProduitsPanier"><?=ControllerPanier::nombreProduits();?></span></a></li>
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>

		<?php
			if(isset($powerNeeded) && $powerNeeded == true) {
	 			if($view != 'index') {
					echo '<div class="container page">';
				}

				if(isset($notif)) {
					echo '<div class="info notif">'.$notif.'</div>';
				}
				echo '<div class="info"></div>';
					$filepath = File::build_path(array("view", static::$object, "$view.php"));
					require $filepath;
				if($view != 'index') {
					echo '</div>';
				}
			} else {
				echo '<div class="container page"><div class="alert alert-danger">Vous ne possédez pas les droits pour accéder à cette page</div></div>';
			}
		?>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="assets/js/jquery.matchHeight.js"></script>
		<script src="assets/js/jquery.tablesorter.min.js"></script>
		<script src="assets/js/jquery.metadata.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<?php 
			// JS pour chaque objet/controller/view
			if(file_exists("assets/js/pages/".static::$object.".js")) {
				echo '<script src="assets/js/pages/'.static::$object.'.js"></script>';
			}
		?>
		<script src="assets/js/script.js"></script>

		<footer class="footer">
			<div class="container">
				<p class="text-muted">So'Cap 2016-2017 - Tous droits réservés.</p>
			</div>
		</footer>

		<!-- Modal -->
		<div class="modal fade" id="panier" tabindex="-1" role="dialog" aria-labelledby="monPanier">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="monPanier">Panier actuel</h4>
		      </div>
		      <div class="modal-body">
		      	<?php 
		      		$panier = ControllerPanier::afficherPanier();
		      		echo $panier['message'];
		      	?>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
		        <?php if($panier['nbProduits'] > 0) { ?>
		        	<button type="button" class="btn btn-primary">Passer au paiement >></button>
		        <?php } ?>
		      </div>
		    </div>
		  </div>
		</div>
	</body>
</html>