<?php 
	require_once File::build_path(array('view', 'admin', 'menu.php'));
?>

<div class="container-fluid">
	<?php 
		if(isset($notif)) {
			echo '<div class="info notif">'.$notif.'</div>';
		}
	?>
	<div class="info"></div>
	<div class="alert alert-info">Un produit en <b>favori</b> apparaîtra dans l'onglet "Notre sélection" des produits</div>
	<div class="row">
		<div class="table-responsive">
			<table class="table table-hover listProduitsTable">
			   <thead>
			       <tr>
			           <th>Produit n°</th>
			           <th>Nom</th>
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
						<td class="stock"><btn class="btn btn-xs btn-primary stockBtn" data-produit="<?=$idProduit?>"><?=$stock?>  <i class="fa fa-cog" aria-hidden="true"></i></btn></td>
						<td>
							<btn class="btn btn-xs btn-warning"><i class="fa fa-pencil" aria-hidden="true"></i> Editer</btn>
							<btn class="btn btn-xs btn-danger"><i class="fa fa-trash" aria-hidden="true"></i> Supprimer</btn>
						</td>
			       </tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="stockProduit" tabindex="-1" role="dialog" aria-labelledby="stockProduit">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Modifier le stock d'un produit</h4>
			</div>
			<div class="modal-body stock-form-content">
				<div class="loader"></div>
				<br/>
				<div class="text-center"><em>Chargement en cours</em></div>
			</div>
		</div>
	</div>
</div>