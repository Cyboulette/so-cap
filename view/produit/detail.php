<?php 
$idProduit = $p->get('idProduit');
$label = strip_tags($p->get('label'));
$categorieProduit = $p->get('categorieProduit'); // A gérer ?
$description = strip_tags($p->get('description'));
$prix = $p->get('prix');
$stock = $p->getStock();
$disabledAchat = ($p->getStock() == 0 ? 'btn-default disabled' : 'btn-success');

?>
<h1>Détail du produit <u><?=$label?></u> :</h1>

<div class="row">
	<div class="produit">
		<div class="image">
			<img src="assets/images/no_visu.png" />
			<img src="assets/images/no_visu.png" />
			<img src="assets/images/no_visu.png" />
		</div>


		<p class="description">Description : <?=$description?></p>

		<hr/>

		<div class="details">
			<span class="prix">Au prix de <?=$prix?> €</span>
			<span class="stock">Reste : <b><?=$stock?></b> produit(s)</span>
		</div>

		<div class="buttons">
			<a href="index.php?controller=produit&action=addCart&idProduit=<?=$idProduit?>" class="btn <?=$disabledAchat?> btn-xs"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Ajouter au panier</a>
		</div>
	</div>
	<br/>
	<div>
		<ul>
			<li>Faire un espace commentaire ?</li>
			<li>Faire un espace avis ?</li>
			<li>Slider pour les images ?</li>
		</ul>
	</div>
</div>