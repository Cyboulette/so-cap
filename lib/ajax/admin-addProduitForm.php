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
					$categories = ModelCategorie::selectAll();
					if($categories != false) {
						$displayCategories = '';
						foreach ($categories as $categorie) {
							$idCategorie = $categorie->get('idCategorie');
							$labelCategorie = $categorie->get('label');
							$displayCategories .= '<option value="'.$idCategorie.'">'.$labelCategorie.'</option>';
						}
					}
					$form = '<form method="POST" role="form">
						<div class="form-group">
							<label for="label">* Libellé du produit</label>
							<input type="text" required name="label" autocomplete="off" id="label" class="form-control" placeholder="Libellé du produit" />
						</div>

						<div class="form-group">
							<label for="categorie">* Catégorie du produit</label>
							<select id="categorie" required name="categorie" class="form-control">
								'.$displayCategories.'
							</select>
						</div>

						<div class="form-group">
							<label for="description">Description du produit</label>
							<textarea class="form-control" name="description" id="description" placeholder="Description du produit"></textarea>
						</div>

						<div class="form-group">
							<label for="prix">* Prix du produit</label>
							<input type="number" required name="prix" min="0" step="any" autocomplete="off" id="prix" class="form-control" placeholder="Prix du produit" />
						</div>

						<div class="form-group">
							<label for="favoriOui">* Produit favori</label>
							<label class="radio-inline" for="favoriOui">
								<input required type="radio" name="favori" id="favoriOui" value="1"> Oui
							</label>
							<label class="radio-inline" for="favoriNon">
								<input required type="radio" name="favori" id="favoriNon" value="0"> Non
							</label>
						</div>

						<div class="form-group">
							<label for="stock">* Stock initial</label>
							<input type="number" required name="stock" min="0" step="1" autocomplete="off" id="stock" class="form-control" placeholder="Stock initial du produit" />
						</div>

						<input type="hidden" name="idProduit" value="null">
						<input type="hidden" name="actionP" value="addProduit">

						<div class="form-group">
							<button type="submit" class="btn btn-success">Ajouter</button>
						</div>

						<div class="alert alert-info text-center">
							Tous les champs marqués d\'une * sont obligatoires
						</div>
					</form>';
					$retour['result'] = true;
					$retour['message'] = $form;
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