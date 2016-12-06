<div class="container-fluid">
	<h1>Passer une commande</h1>
	<div class="alert alert-info text-center">
		<b>Rappel de votre panier : <?=ControllerPanier::nombreProduits();?> produits pour un montant total de <?=ControllerPanier::getMontantTotal();?>€</b>
	</div>
	<div class="step">
		<h2><span class="fa-stack fa-lg"><i class="fa fa-circle-thin fa-stack-2x"></i><i class="fa fa-map-marker fa-stack-1x"></i></span>Renseignez votre adresse de livraison</h2>
		<form class="form" role="form" method="POST" action="index.php?controller=commande&action=validate">
			<div class="form-group">
				<label for="adresse">Adresse</label>
				<input type="text" class="form-control" id="adresse" name="adresse" required placeholder="Entrez l'adresse de livraison">
			</div>
			<div class="form-group">
				<label for="ville">Ville</label>
				<input type="text" class="form-control" id="ville" name="ville" required placeholder="Entrez la ville de l'adresse">
			</div>
			<div class="form-group">
				<label for="codepostal">Code postal</label>
				<input type="number" class="form-control" id="codepostal" required pattern="[0-9]{5}" name="codepostal" placeholder="Entrez le code postal de l'adresse">
			</div>
			<div class="form-group">
				<label for="Pays">Pays</label>
				<input type="text" class="form-control" value="France" id="Pays" required name="pays" placeholder="Entrez votre pays" readonly="readonly">
			</div>
			<div class="form-group">
				<div class="alert alert-warning">En validant votre commande, vous validez le paiement total de <b><?=ControllerPanier::getMontantTotal();?>€</b> et vous enclenchez le processus de livraison !</div>
			</div>
			<button type="submit" class="btn btn-success">Valider la commande</button>
		</form>
	</div>
</div>