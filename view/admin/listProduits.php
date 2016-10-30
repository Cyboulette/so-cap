<?php 
	require_once File::build_path(array('view', 'admin', 'menu.php'));
?>

<div class="container-fluid">
	<div class="info"></div>
	<div class="row">
		<table class="table table-hover listProduitsTable">
		   <thead>
		       <tr>
		           <th>Produit n°</th>
		           <th>Label</th>
		           <th>Catégorie</th>
		           <th>Prix</th>
		           <th>Favori</th>
		           <th>Stock</th>
		           <th>Action</th>
		       </tr>

		   </thead>
		      <tbody>

			<?php
			foreach ($tab_p as $p) { 
				$idProduit = $p->get('idProduit');
				$label = strip_tags($p->get('label'));
				$prix = $p->get('prix');
				$stock = $p->getStock();
				$categorieProduit = $p->get('categorieProduit');
				$categorieDetails = ModelCategorie::select($categorieProduit);
				$isFavori = $p->get('favorited');
				$favori = ($p->get('favorited') == 1 ? '<i class="fa fa-star" aria-hidden="true"></i>' : '<i class="fa fa-star-o" aria-hidden="true"></i>');
			?>		
		       <tr>
		           <td><?=$idProduit?></td>
		           <td><?=$label?></td>
		           <td><?=$categorieDetails->get('label')?></td>
		           <td><?=$prix?> €</td>
		           <td><btn class="btn btn-xs btn-primary favori" data-produit="<?=$idProduit?>" data-favori="<?=$isFavori?>"><?=$favori?></btn></td>
		           <td><?=$stock?></td>
		       </tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
</div>