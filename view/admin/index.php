<div class="container page">
	<?php 
		require_once File::build_path(array('view', 'admin', 'menu.php'));
	?>
	<div class="row statsAdmin">
		<div class="col-lg-12">
			<div class="col-lg-4">
				<div class="stats">
					<div class="icon">
						<i class="fa fa-users" aria-hidden="true"></i>
					</div>
					<h4><?=$nombreUsers?> utilisateur<?=($nombreUsers > 1 ? 's' : '')?> inscrit<?=($nombreUsers > 1 ? 's' : '')?> et valide<?=($nombreUsers > 1 ? 's' : '')?></h4>
					<div class="button">
						<a href="index.php?controller=admin&action=users" class="btn btn-success">Gérer les utilisateurs</a>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="stats">
					<div class="icon">
						<i class="fa fa-object-group" aria-hidden="true"></i>
					</div>
					<h4><?=$nombreProduits?> produit<?=($nombreProduits > 1 ? 's' : '')?> enregistré<?=($nombreProduits > 1 ? 's' : '')?></h4>
					<div class="button">
						<a href="index.php?controller=admin&action=produits" class="btn btn-success">Gérer les produits</a>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="stats">
					<div class="icon">
						<i class="fa fa-object-group" aria-hidden="true"></i>
					</div>
					<h4><?=$nombreCommandes?> commande<?=($nombreCommandes > 1 ? 's' : '')?> effectuée<?=($nombreCommandes > 1 ? 's' : '')?></h4>
					<div class="button">
						<a href="index.php?controller=admin&action=commandes" class="btn btn-success">Gérer les commandes</a>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="stats">
					<div class="icon">
						<i class="fa fa-credit-card" aria-hidden="true"></i>
					</div>
					<h4><?=$argentTotal?> € au total</h4>
				</div>
			</div>
		</div>
	</div>
</div>