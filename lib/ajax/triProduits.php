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
			$retour['title'] = 'Tous nos produits :';
		} elseif($optionTri == 'selection') {
			$tab_p = ModelProduit::selectCustom('favorited', '1');
			$retour['title'] = 'Produits sélectionnés par notre équipe :';
		} elseif($optionTri == 'categorie') {
			if(isset($_POST['categorieID']) && !empty($_POST['categorieID'])) {
				$categorieID = strip_tags($_POST['categorieID']);
				$categorieActual = ModelCategorie::select($categorieID);
				$retour['title'] = 'Produits de la catégorie :';
				if($categorieActual == false) {
					$tab_p = false;
				} else {
					$tab_p = ModelProduit::selectCustom('categorieProduit', $categorieID);
					$retour['title'] = 'Produits de la catégorie <u>'.$categorieActual->get('label').'</u> :';
				}
			} else {
				$tab_p = false;
			}
		} elseif($optionTri == 'searchText') {
			if(isset($_POST['text']) && !empty($_POST['text'])) {
				$text = strip_tags($_POST['text']);
				$retour['title'] = 'Recherche par nom de produit : ';
				$tab_p = ModelProduit::selectText($text);
			} else {
				$tab_p = false;
			}
		} else {
			$tab_p = false;
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