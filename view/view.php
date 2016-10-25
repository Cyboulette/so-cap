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
	    <nav class="navbar navbar-inverse navbar-fixed-top">
	      <div class="container">
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	            <span class="sr-only">Navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <a class="navbar-brand" href="index.php">So'Cap</a>
	        </div>
	        <div id="navbar" class="collapse navbar-collapse">
	          <ul class="nav navbar-nav">
	            <li <?php ControllerDefault::active('index.php') ?>><a href="index.php">Accueil</a></li>
	            <li <?php ControllerDefault::active('index.php?controller=produit&action=readAll') ?>><a href="index.php?controller=produit&action=readAll">Produits</a></li>
	            <li><a href="index.php?controller=commande&action=readAll">Commandes</a></li>
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>

	    <div class="container-fluid">
	    	<div class="row">
				<div class="col-sm-3 col-md-2 sidebar">
					<ul class="nav nav-sidebar">
						<li class="active"><a href="#">Test <span class="sr-only">(current)</span></a></li>
						<li><a href="#">Test #2</a></li>
					</ul>
				</div>

				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<?php
						$filepath = File::build_path(array("view", static::$object, "$view.php"));
						require $filepath;
					?>
				</div>
	    	</div>
		</div>

		<?php 
			require File::build_path(array("assets", "js", "js.php"));
		?>
	</body>
</html>