<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Test de design</title>

		<!-- Bootstrap -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/css/style-test.css" rel="stylesheet">
		<link href="assets/css/font-awesome.min.css" rel="stylesheet">
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
	            <li><a href="index.php">Accueil</a></li>
	            <li><a href="index.php?controller=produit&action=readAll">Produits</a></li>
	            <li><a href="index.php?controller=commande&action=readAll">Commandes</a></li>
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>

	    <div class="container page">
	    	<section class="first-section">
	    		<h1>Achetez toutes vos capsules de café en ligne !</h1>
	    		<div class="row">
	    			<div class="col-lg-3 col-lg-offset-1 box">
	    				<div class="icon"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
	    				<div class="title">Livraison en 24h !</div>
	    				<p>
	    					Les frais sont offerts sur votre 1ère commande !
	    				</p>
	    				<div class="buttons">
	    					<a class="btn btn-success" href="">En profiter</a>
	    				</div>
	    			</div>
	    			<div class="col-lg-3 col-lg-offset-1 box">
	    				<div class="icon"><i class="fa fa-users" aria-hidden="true"></i></div>
	    				<div class="title">Un support de qualité !</div>
	    				<p>
	    					Notre meilleure équipe toujours là pour vous rendre service !
	    				</p>
	    				<div class="buttons">
	    					<a class="btn btn-success" href="">En profiter</a>
	    				</div>
	    			</div>
	    			<div class="col-lg-3 col-lg-offset-1 box">
	    				<div class="icon"><i class="fa fa-globe" aria-hidden="true"></i></div>
	    				<div class="title">Une expérience mondiale !</div>
	    				<p>
	    					Profitez de 20ans d'expérience dans ce domaine !
	    				</p>
	    				<div class="buttons">
	    					<a class="btn btn-success" href="">En profiter</a>
	    				</div>
	    			</div>
	    		</div>
	    	</section>
		</div>

    	<section class="last-products">
    		<h2>Découvrez nos derniers produits !</h2>
			<i class="fa fa-coffee bigCoffee" aria-hidden="true"></i>
    	</section>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/js/produits.js"></script>
	</body>
</html>