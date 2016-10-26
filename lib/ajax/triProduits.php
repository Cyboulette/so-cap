<?php 
	require_once '../File.php';
	require_once File::build_path(array('model', 'ModelProduit.php'));
	require_once File::build_path(array('controller', 'ControllerProduit.php'));

	$retour = array(); //Tableau de retour
	if(isset($_POST['optionTri']) && !empty($_POST['optionTri'])) {
		$optionTri = strip_tags($_POST['optionTri']);
		$produitsFinaux = array();
		if($optionTri == 'all') {
			$tab_p = ModelProduit::selectAll();
		} elseif($optionTri == 'selection') {
			$tab_p = ModelProduit::selectCustom('favorited', '1');
		}

		if($tab_p != false) {
			foreach ($tab_p as $p) {
				$idProduit = $p->get('idProduit');
				$label = strip_tags($p->get('label'));
				$categorieProduit = $p->get('categorieProduit'); // A gérer ?
				$description = strip_tags($p->get('description'));
				$prix = $p->get('prix');
				$stock = $p->getStock();
				$disabledAchat = ($p->getStock() == 0 ? 'btn-default disabled' : 'btn-success');
				$data = '<div class="col-md-4" data-id="'.$idProduit.'">
				<div class="produit">
				<div class="image">
				<img src="assets/images/no_visu.png" />
				</div>

				<div class="title">'.$label.'</div>

				<p class="description">'.$description.'</p>

				<div class="details">
				<span class="prix">Au prix de '.$prix.' €</span>
				<span class="stock">Reste : <b>'.$stock.'</b> produit(s)</span>
				</div>

				<div class="buttons">
				<a href="?controller=produit&action=read&idProduit='.$idProduit.'" class="btn btn-primary btn-xs"><i class="fa fa-search" aria-hidden="true"></i> Voir le détail</a>
				<a href="index.php?controller=produit&action=addCart&idProduit='.$idProduit.'" class="btn '.$disabledAchat.' btn-xs"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Ajouter au panier</a>
				</div>
				</div>
				</div>';
				array_push($produitsFinaux, $data);
			}
			$retour['produits'] = $produitsFinaux;
			$retour['result'] = true;
		} else {
			$retour['result'] = false;
		}
	} else {
		$retour['result'] = false;
	}


	echo json_encode($retour);
?>