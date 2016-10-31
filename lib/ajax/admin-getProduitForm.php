<?php 
	session_start(); // Sinon on a pas accès aux sessions
	
	// On est dans l'ajax, il n'a pas accès à l'auto-loader.
	require_once '../File.php';
	require_once File::build_path(array('model', 'ModelProduit.php'));
	require_once File::build_path(array('model', 'ModelCategorie.php'));
	require_once File::build_path(array('controller', 'ControllerProduit.php'));
	require_once File::build_path(array('controller', 'ControllerUtilisateur.php'));


	$retour = array(); //Tableau de retour
	if(ControllerUtilisateur::isConnected()) {
		$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
		if($currentUser->getPower() == Conf::$power['admin']) {
			if(isset($_POST['idProduit'])) {
				$idProduit = strip_tags($_POST['idProduit']);
				$produit = ModelProduit::select($idProduit);

				if($produit != false) {
					$categories = ModelCategorie::selectAll();
					if($categories != false) {
						$displayCategories = '';
						foreach ($categories as $categorie) {
							$idCategorie = $categorie->get('idCategorie');
							$labelCategorie = $categorie->get('label');
							$selected = ($idCategorie == $produit->get('categorieProduit') ? 'selected="selected"' : '');
							$displayCategories .= '<option '.$selected.' value="'.$idCategorie.'">'.$labelCategorie.'</option>';
						}
					}
					$form = '<form method="POST" role="form">
						<div class="form-group">
							<label for="idProduit">ID du produit</label>
							<input type="text" autocomplete="off" id="idProduit" class="form-control" value="'.$produit->get('idProduit').'" placeholder="ID du produit" disabled="yes" />
						</div>

						<div class="form-group">
							<label for="label">Libellé du produit</label>
							<input type="text" name="label" autocomplete="off" id="label" class="form-control" value="'.$produit->get('label').'" placeholder="Libellé du produit" />
						</div>

						<div class="form-group">
							<label for="categorie">Catégorie du produit</label>
							<select id="categorie" name="categorie" class="form-control">
								'.$displayCategories.'
							</select>
						</div>

						<div class="form-group">
							<label for="description">Description du produit</label>
							<textarea class="form-control" name="description" id="description" placeholder="Description du produit">'.$produit->get('description').'</textarea>
						</div>

						<div class="form-group">
							<label for="prix">Prix du produit</label>
							<input type="number" name="prix" min="0" step="any" autocomplete="off" id="prix" class="form-control" value="'.$produit->get('prix').'" placeholder="Prix du produit" />
						</div>

						<input type="hidden" name="idProduit" value="'.$produit->get('idProduit').'">
						<input type="hidden" name="actionP" value="updateProduit">

						<div class="form-group">
							<button type="submit" class="btn btn-success">Modifier</button>
						</div>

						<div class="alert alert-info text-center">
							Pour gérer le fait que ce produit apraisse ou non en "sélection" sur le site, utilisez le bouton <b>Favori (<i class="fa fa-star-o" aria-hidden="true"></i>/<i class="fa fa-star" aria-hidden="true"></i>)</b> dans la liste des produits
						</div>

						<div class="alert alert-info text-center">
							Pour gérer le stock de ce produit, utilisez le bouton <b>Stock (<i class="fa fa-gear" aria-hidden="true"></i>)</b> dans la liste des produits
						</div>
					</form>';
					$retour['result'] = true;
					$retour['message'] = $form;
 				} else {
					$retour['result'] = false;
					$retour['message'] = '<div class="alert alert-danger">Le produit n\'existe pas !</div>';
				}
			} else {
				$retour['result'] = false;
				$retour['message'] = '<div class="alert alert-danger">Vous n\'avez pas envoyé correctement les données !</div>';
			}
		} else {
			$retour['result'] = false;
			$retour['message'] = '<div class="alert alert-danger">Vous n\'avez pas les droits nécessaires pour accéder à cette page !</div>';
		}
	} else {
		$retour['result'] = false;
		$retour['message'] = '<div class="alert alert-danger">Vous devez être connecté pour accéder à cette page !</div>';
	}
	echo json_encode($retour);
?>