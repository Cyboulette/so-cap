<?php 
	session_start(); // Sinon on a pas accès aux sessions
	
	// On est dans l'ajax, il n'a pas accès à l'auto-loader.
	require_once '../File.php';
	require_once File::build_path(array('model', 'ModelProduit.php'));
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
					$form = '<form method="POST" role="form">
						<div class="form-group">
							<label for="stockActual">Stock pour le produit <u>'.strip_tags($produit->get('label')).'</u></label>
							<input type="text" name="stock" autocomplete="off" id="stockActual" class="form-control" value="'.$produit->getStock().'" placeholder="Nombre de produits en stock" />
						</div>
						<input type="hidden" name="idProduit" value="'.$produit->get('idProduit').'">
						<input type="hidden" name="actionP" value="updateStockProduit">
						<button type="submit" class="btn btn-success">Modifier</button>
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