<h1>Voici la liste de tous nos produits :</h1>

<div class="row">
	<?php
	foreach ($tab_p as $p) { 
		$idProduit = $p->get('idProduit');
		$label = strip_tags($p->get('label'));
		$categorieProduit = $p->get('categorieProduit'); // A gérer ?
		$description = strip_tags($p->get('description'));
		$prix = $p->get('prix');
		$stock = $p->getStock();
	?>
		<div class="col-md-4" data-id="<?=$idProduit?>">
			<div class="produit">
				<div class="image">
					<img src="assets/images/no_visu.png" />
				</div>

				<div class="title"><?=$label?></div>

				<p class="description"><?=$description?></p>

				<div class="details">
					<span class="prix">Au prix de <?=$prix?> €</span>
					<span class="stock">Reste : <b><?=$stock?></b> produit(s)</span>
				</div>

				<div class="buttons">
					<a href="?controller=produit&action=read&idProduit=<?=$idProduit?>" class="btn btn-primary btn-xs"><i class="fa fa-search" aria-hidden="true"></i> Voir le détail</a>
					<a href="" class="btn btn-success btn-xs"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Ajouter au panier</a>
				</div>
			</div>
		</div>
	<?php } ?>
</div>