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
			if(isset($_POST['idCategorie'])) {
				$idCategorie = strip_tags($_POST['idCategorie']);
				if($idCategorie == "null") {

					$categories = ModelCategorie::selectAll();
					$formAdd = '<form method="POST" role="form">
							<div class="form-group">
								<label for="labelCategorie">Nom de la catégorie</label>
								<input id="labelCategorie" class="form-control" type="text" name="labelCategorie" placeholder="Nom de la catégorie" />
							</div>
							<div class="form-group">
								<input type="hidden" name="actionP" value="addCategorie">
								<button type="submit" class="btn btn-success">Ajouter</button>
							</div>
						</form>';
					if($categories != false) {
						$displayCategories = '';
						foreach ($categories as $categorie) {
							$idCategorie = $categorie->get('idCategorie');
							$labelCategorie = $categorie->get('label');
							$displayCategories .= '<option value="'.$idCategorie.'">'.$labelCategorie.'</option>';
						}

						$formTable = '<div class="table-responsive">
							<table class="table table-hover listProduitsTable">
								<thead>
									<tr>
										<th>ID</th>
										<th>Nom</th>
									</tr>
								</thead>
							</table>
						</div>';

						$retour['result'] = true;
						$retour['message'] = $formAdd."<hr/>".$formTable;
					} else {
						$retour['result'] = true;
						$retour['message'] = $formAdd;
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