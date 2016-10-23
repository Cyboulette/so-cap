<?php
foreach ($tab_p as $p) { 
	$idProduit = $p->get('idProduit');
	$label = strip_tags($p->get('label'));
	$categorieProduit = $p->get('categorieProduit'); // A gérer ?
	$description = strip_tags($p->get('description'));
	$prix = $p->get('prix');
?>
	<div class="produit" data-id="<?=$idProduit?>">
		Produit : <?=$label?> <br/>
		<p><?=$description?></p>
		Au prix de <?=$prix?> €
	</div>
	<hr/>
<?php } ?>