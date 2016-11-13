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
				$categorieProduit = $p->get('categorieProduit');
				$description = (!empty(strip_tags($p->get('description'))) ? strip_tags($p->get('description')) : 'Ce produit ne possède aucune description !');
				$prix = $p->get('prix');
				$stock = $p->getStock();
				$disabledAchat = ($p->getStock() == 0 ? 'btn-default disabled' : 'btn-success');
				
	            $imagesProduit = $p->getImages();
	            $dataImage = '<div class="alert alert-info">Aucun visuel disponible pour ce produit</div>';
	            $order = 0;
	            if($imagesProduit != false) {
	               foreach ($imagesProduit as $imageProduit) {
	                  if($order < 1) { // Pour limiter à 1 image
	                     if(file_exists(File::build_path(array('assets/images/produits/', $imageProduit['nomImage'])))) {
	                        $dataImage = '<img src="assets/images/produits/'.$imageProduit['nomImage'].'" />';
	                        $order++;
	                     }
	                  }
	               }
	            }
				$data = '<div class="col-md-4" data-id="'.$idProduit.'">
				<div class="produit">
				<div class="image">
					'.$dataImage.'
				</div>

				<div class="title">'.$label.'</div>

				<p class="description">'.$description.'</p>

				<div class="details">
				<span class="prix">Au prix de '.$prix.' €</span>
				<span class="stock">Reste : <b>'.$stock.'</b> produit(s)</span>
				</div>

				<div class="buttons">
				<a href="?controller=produit&action=read&idProduit='.$idProduit.'" class="btn btn-primary btn-xs"><i class="fa fa-search" aria-hidden="true"></i> Voir le détail</a>
				<button class="actionBtnPanier btn '.$disabledAchat.' btn-xs" data-action="addFromAjax" data-produit="'.$idProduit.'"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Ajouter au panier</button>
				</div>
				</div>
				</div>';
				array_push($produitsFinaux, $data);
			}
			array_push($produitsFinaux, '<script>init();</script>');
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