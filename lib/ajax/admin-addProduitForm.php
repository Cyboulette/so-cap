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
				if($idProduit == "null") {
					
					if(isset($_POST['dataPosted'])) {
						// Si jamais on récupère de la donnée postée précédemment (donc formulaire précédemment envoyé en erreur)
						// On la décode vu qu'elle était encodée en JSON.
						$dataPosted = json_decode($_POST['dataPosted']);
					}

					// On stocke des variables de value="" (pour l'HTML).
					$labelValue = (isset($dataPosted->label) ? strip_tags($dataPosted->label) : '');
					$descriptionValue = (isset($dataPosted->description) ? strip_tags($dataPosted->description) : '');
					$categorieValue = (isset($dataPosted->categorie) ? strip_tags($dataPosted->categorie) : '');
					$prixValue = (isset($dataPosted->prix) ? strip_tags($dataPosted->prix) : '');
					$stockValue = (isset($dataPosted->stock) ? strip_tags($dataPosted->stock) : '');
					$favoriValue = (isset($dataPosted->favori) ? strip_tags($dataPosted->favori) : '');

					$categories = ModelCategorie::selectAll();
					if($categories != false) {
						$displayCategories = '';
						foreach ($categories as $categorie) {
							$idCategorie = $categorie->get('idCategorie');
							$labelCategorie = $categorie->get('label');
							$selected = ($idCategorie == $categorieValue ? 'selected="selected"' : '');
							$displayCategories .= '<option '.$selected.' value="'.$idCategorie.'">'.$labelCategorie.'</option>';
						}

						$isFavoriYes = ($favoriValue == 1 && is_numeric($favoriValue)) ? 'checked' : '';
						$isFavoriNo = ($favoriValue == 0 && is_numeric($favoriValue)) ? 'checked' : '';

						$form = '<form method="POST" role="form">
							<div class="form-group">
								<label for="label">* Libellé du produit</label>
								<input type="text"  name="label" autocomplete="off" id="label" value="'.$labelValue.'" class="form-control" placeholder="Libellé du produit" />
							</div>

							<div class="form-group">
								<label for="categorie">* Catégorie du produit</label>
								<select id="categorie"  name="categorie" class="form-control">
									'.$displayCategories.'
								</select>
							</div>

							<div class="form-group">
								<label for="description">Description du produit</label>
								<textarea class="form-control" name="description" id="description" placeholder="Description du produit">'.$descriptionValue.'</textarea>
							</div>

							<div class="form-group">
								<label for="prix">* Prix du produit</label>
								<input type="number"  name="prix" min="0" step="any" autocomplete="off" id="prix" value="'.$prixValue.'" class="form-control" placeholder="Prix du produit" />
							</div>

							<div class="form-group">
								<label for="favoriOui">* Produit favori</label>
								<label class="radio-inline" for="favoriOui">
									<input  type="radio" name="favori" '.$isFavoriYes.' id="favoriOui" value="1"> Oui
								</label>
								<label class="radio-inline" for="favoriNon">
									<input  type="radio" name="favori" '.$isFavoriNo.' id="favoriNon" value="0"> Non
								</label>
							</div>

							<div class="form-group">
								<label for="stock">* Stock initial</label>
								<input type="number"  name="stock" min="0" step="1" autocomplete="off" id="stock" value="'.$stockValue.'" class="form-control" placeholder="Stock initial du produit" />
							</div>

							<input type="hidden" name="idProduit" value="null">
							<input type="hidden" name="actionP" value="addProduit">

							<div class="form-group">
								<button type="submit" class="btn btn-success">Ajouter</button>
								<button type="reset" class="btn btn-default">Réinitialiser le formulaire</button>
							</div>

							<div class="alert alert-info text-center">
								Tous les champs marqués d\'une * sont obligatoires <br/>
								En cas d\'erreur, le formulaire sera ré-rempli
							</div>
						</form>';
						$retour['result'] = true;
						$retour['message'] = $form;
					} else {
						$retour['result'] = false;
						$retour['message'] = '<div class="alert alert-danger">Aucune catégorie n\'existe pour le moment, veuillez en ajouter avant d\'ajouter des produits</div>';
					}
 				} else {
					$retour['result'] = false;
					$retour['message'] = '<div class="alert alert-danger">Ereur de transmission des données !</div>';
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