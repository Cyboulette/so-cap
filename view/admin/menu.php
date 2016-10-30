<nav class="navbar navbar-default">
	<div class="container-fluid">
			<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Afficher la navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="">Administration</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav boutonsHaut">
				<li <?php ControllerDefault::active('admin', 'index'); ?>><a href="index.php?controller=admin&action=index">Accueil</a></li>
				<li <?php ControllerDefault::active('admin', 'listProduits'); ?>><a href="index.php?controller=admin&action=listProduits">Produits</a></li>
				<li <?php ControllerDefault::active('admin', 'listUsers'); ?>><a href="index.php?controller=admin&action=listUsers">Utilisateurs</a></li>
				<li <?php ControllerDefault::active('admin', 'listCommandes'); ?>><a href="index.php?controller=admin&action=listCommandes">Commandes</a></li>
			</ul>
		</div>
	</div>
</nav>