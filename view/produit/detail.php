<?php 
$idProduit = $p->get('idProduit');
$label = strip_tags($p->get('label'));
$categorieProduit = $p->get('categorieProduit');
$prix = $p->get('prix');
$disabledAchat = ($p->getStock() == 0 ? 'btn-default disabled' : 'btn-success');
$stockMin = ($p->getStock() == 0 ? '0' : '1');

?>
<h1>Détail du produit <u><?=$label?></u> :</h1>

<div class="row">
	<div class="produit noHover">
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

		<div class="description"><em><?=$description?></em></div>

		<div class="details">
			<span class="prix label label-primary">Prix : <?=$prix?> <i class="fa fa-eur" aria-hidden="true"></i></span>
				<br/>
			<span class="stock label <?=$levelLabelStock?>">Reste : <b><?=$stock?></b> produit(s)</span>
		</div>

		<div class="buttons">
			<form method="post" action="index.php?controller=produit&action=addCart">
				<div class="input-group quantity">
					<div class="input-group-btn">
						<button type="button" class="btn btn-default btn-sm btn-number" disabled="disabled" data-type="minus" data-field="quant[1]">
							<span class="glyphicon glyphicon-minus"></span>
						</button>
					</div>
					<input type="text" name="quant[1]" class="form-control input-sm input-number" readonly="readonly" value="<?=$stockMin?>" min="1" max="<?=$stock?>">
					<div class="input-group-btn">
						<button type="button" class="btn btn-default btn-sm btn-number" data-type="plus" data-field="quant[1]">
							<span class="glyphicon glyphicon-plus"></span>
						</button>
						<button type="submit" class="btn <?=$disabledAchat?> btn-sm"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Ajouter au panier</button>
					</div>
				</div>
				<input type="hidden" name="idProduit" value="<?=$idProduit?>" />
			</form>
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