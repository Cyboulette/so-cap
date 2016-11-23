<nav class="navbar navbar-default" id="navigationProduits">
	<div class="container-fluid">
			<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Afficher la navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="">Nos produits</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav boutonsHaut">
				<li class="active" data-tri="all"><a href="">Tous</a></li>
				<li data-tri="selection"><a href="">Notre sélection</a></li>
				<li class="dropdown">
					<a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Catégories <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<?=$displayCategories?>
					</ul>
				</li>
			</ul>
			<form class="navbar-form navbar-left" id="rechercheForm">
				<div class="form-group">
					<input type="text" class="form-control searchText" placeholder="Rechercher par nom">
				</div>
				<button type="submit" class="btn btn-default">Rechercher</button>
			</form>
		</div>
	</div>
</nav>

<div class="row displayProduits">
	<div class="container-fluid"><h4>Tous nos produits :</h4></div>
	<?php
	foreach ($tab_p as $p) {
		$idProduit = $p->get('idProduit');
		$label = strip_tags($p->get('label'));
		$categorieProduit = $p->get('categorieProduit'); // A gérer ?
		$prix = $p->get('prix');
		$stock = $p->getStock();
		$disabledAchat = ($p->getStock() == 0 ? 'btn-default disabled' : 'btn-success');
		$description = (!empty(strip_tags($p->get('description'))) ? strip_tags($p->get('description')) : 'Ce produit ne possède aucune description !');
	?>
		<div class="col-md-4" data-id="<?=$idProduit?>">
			<div class="produit">
				<div class="image">
					<?php 
						if(!empty($dataImages[$idProduit])) {
							echo '<img src="'.$dataImages[$idProduit][0]['url'].'" />';
						} else {
							echo '<div class="alert alert-info">Aucun visuel disponible pour ce produit</div>';
						}
					?>
				</div>

				<div class="title"><?=$description?></div>

				<p class="description"><?=$label?></p>

				<div class="details">
					<span class="prix">Au prix de <?=$prix?> €</span>
					<span class="stock">Reste : <b><?=$stock?></b> produit(s)</span>
				</div>

				<div class="buttons">
					<a href="?controller=produit&action=read&idProduit=<?=$idProduit?>" class="btn btn-primary btn-xs"><i class="fa fa-search" aria-hidden="true"></i> Voir le détail</a>
					<button class="actionBtnPanier btn <?=$disabledAchat?> btn-xs" data-action="addFromAjax" data-produit="<?=$idProduit?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Ajouter au panier</button>
				</div>
			</div>
		</div>
	<?php } ?>
</div>