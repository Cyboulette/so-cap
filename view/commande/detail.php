  
  <?php
    //===================== valeurs de la commande ====================

    $idCommande = $c->get('idCommande');
    $dateCommande = $c->get('dateCommande');
    $date = new DateTime($dateCommande);
    $prixTotal = $c->get('prixTotal');

    if(empty($produitsCommandes)){
    	echo "Cette commande na pas de Produit";
    }


    //===================== début du tableau ====================
  ?>

	<h1>Commande n° <?=$idCommande?></h1>

<table class="table table-hover">
   <thead> <!-- En-tête du tableau -->
       <tr>
       	   <th>Produit</th>
           <th>N°</th>
           <th>Categorie produit</th>
           <th>Prix</th>
           <th>Quantité</th>
       </tr>

   </thead>
      <tbody>

	<?php
	//===================== valeurs des produits ====================
	foreach ($produitsCommandes as $pc) { 
		$idProduit = $pc->get('idProduit');
		$label = strip_tags($pc->get('label'));
		$categorieProduit = $pc->get('categorieProduit');
		$prix = $pc->get('prix');
		$imagesProduit = $pc->getImages();

	//===================== construction du tableau ====================
	?>		
       <tr>

           <td>
           		<img src="assets/images/produits/<?=$imagesProduit[0]['nomImage']?>" alt="Visuel d'un produit" height="10%" width="10%" class="img-thumbnail"/>
           		<a href="?controller=produit&action=read&idProduit=<?=$idProduit?>"><?=$label?></a>
           		</td>
           <td><?=$idProduit?></td>
           <td><?=$categorieProduit?></td>
           <td><?=$prix?></td>
           <td>x</td>
       </tr>
	<?php } ?>
	</tbody>
</table>


	<div class="row">
		  <div class="col-md-4"></div>
		  <div class="col-md-4">
		  		<div class="buttons">
					<a href="?controller=commande&action=genererFacture&idCommande=<?=$idCommande?>" class="btn btn-danger btn-xs"><i class="fa fa-file-text-o" aria-hidden="true"></i>  Editer une facture</a></td>
				</div>
		  </div>
		  <div class="col-md-4"></div>
	</div>
