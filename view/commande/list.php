<h1>Voici la liste de toute vos commandes :</h1>

<div class="row">
<table class="table table-hover">
   <thead> <!-- En-tête du tableau -->
       <tr>
           <th>commande n°</th>
           <th>date de la commande</th>
           <th>prix</th>
       </tr>

   </thead>
      <tbody>

	<?php
	foreach ($tab_c as $c) { 
		$idCommande = $c->get('idCommande');
		$dateCommande = $c->get('dateCommande');
		$date = new DateTime($dateCommande);
		$prixTotal = $c->get('prixTotal');
	?>		
       <tr>
           <td><?=$idCommande?></td>
           <td><?=$date->format('d/m/Y H:i:s')?></td>
           <td><?=$prixTotal?></td>
           <td><div class="buttons">
					<a href="?controller=produit&action=read&idProduit=<?=$idProduit?>" class="btn btn-primary btn-xs"><i class="fa fa-search" aria-hidden="true"></i> Voir le détail</a></td>
       </tr>
	<?php } ?>
	</tbody>
</table>
</div>