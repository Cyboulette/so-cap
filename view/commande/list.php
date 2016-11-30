<h1>Voici la liste de toute vos commandes :</h1>

<div class="row">
<table class="table table-hover">
   <thead> <!-- En-tête du tableau -->
       <tr>
           <th>Commande n°</th>
           <th>Date de la commande</th>
           <th>Prix</th>
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
					<a href="?controller=commande&action=read&idCommande=<?=$idCommande?>" class="btn btn-primary btn-xs"><i class="fa fa-search" aria-hidden="true"></i>  Voir le détail</a>
					<a href="?controller=commande&action=genererFacture&idCommande=<?=$idCommande?>" class="btn btn-danger btn-xs"><i class="fa fa-file-text-o" aria-hidden="true"></i>  Editer une facture</a></td>
       </tr>
	<?php } ?>
	</tbody>
</table>
</div>