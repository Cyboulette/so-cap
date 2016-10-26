<?php 
$idProduit = $p->get('idProduit');
$label = strip_tags($p->get('label'));
$categorieProduit = $p->get('categorieProduit');
$description = strip_tags($p->get('description'));
$prix = $p->get('prix');
$stock = $p->getStock();
$disabledAchat = ($p->getStock() == 0 ? 'btn-default disabled' : 'btn-success');

?>
<h1>Détail du produit <u><?=$label?></u> :</h1>

<div class="row">
	<div class="produit">
		<div class="image">
			<?php 
				if(empty($dataImages)) {
					echo '<div class="alert alert-info">Nous ne disposons d\'aucun visuel pour ce produit</div>';
				} else { ?>
					<div id="carousel-produits" class="carousel slide" data-ride="carousel">
						<ol class="carousel-indicators">
							<?php foreach ($dataImages as $image) { 
								$isActive = ($image['order'] == 0 ? 'class="active"' : '');
							?>
								<li data-target="#carousel-produits" data-slide-to="<?=$image['order']?>" <?=$isActive?>></li>
							<?php } ?>
						</ol>

						<div class="carousel-inner" role="listbox">
							<?php foreach ($dataImages as $image) { 
								$isActive = ($image['order'] == 0 ? 'class="item active"' : 'class="item"');
							?>
								<div <?=$isActive?>>
									<img src="<?=$image['url']?>" alt="Visuel d'un produit" />
								</div>
							<?php } ?>
						</div>

						<a class="left carousel-control" href="#carousel-produits" role="button" data-slide="prev">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Image précédente</span>
						</a>
						<a class="right carousel-control" href="#carousel-produits" role="button" data-slide="next">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Image suivante</span>
						</a>
					</div>
				<?php }
			?>
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
		</ul>
	</div>
</div>