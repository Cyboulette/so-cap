<h1>Voici la liste de toutes les livraisons :</h1>

<div class="row">
	<?php
		foreach($tab_l as $l){
			$idLivraison = $l->get('idLivraison');
			$idCommande = $l->get('idCommande');
			$date = $l->get('date');
			$etatCommande = $l->get('etatCommande');
			$modeLivraison = $l->get('modeLivraison');
		}
	?>

	<table class="table table-striped table-hover spacer">
		<thead>
			<tr>
				<th>idLivraison</th>
				<th>idCommande</th>
				<th>Date</th>
				<th>etatCommande</th>
				<th>modeLivraison</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?=$idLivraison?></td>
				<td><?=$idCommande?></td>
				<td><?=$date?></td>
				<td><?=$etatCommande?></td>
				<td><?=$modeLivraison?></td>
			</tr>
		</tbody>
	</table>
</div>