<?php 
	session_start(); // Sinon on a pas accès aux sessions
	
	// On est dans l'ajax, il n'a pas accès à l'auto-loader.
	require_once '../File.php';
	require_once File::build_path(array('model', 'ModelCategorie.php'));
	require_once File::build_path(array('controller', 'ControllerUtilisateur.php'));


	$retour = array(); //Tableau de retour
	if(ControllerUtilisateur::isConnected()) {
		$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
		if($currentUser->getPower() == Conf::$power['admin']) {
			if(isset($_POST['idCategorie'])) {
				$idCategorie = strip_tags($_POST['idCategorie']);
				$categorie = ModelCategorie::select($idCategorie);

				if($categorie != false) {
					$form = '<form method="POST" role="form">
						<div class="alert alert-info text-center">
							Confirmez vous la suppression de la catégorie <b>'.strip_tags($categorie->get('label')).'</b> ?
						</div>

						<input type="hidden" name="idCategorie" value="'.$categorie->get('idCategorie').'">
						<input type="hidden" name="confirm" value="true">
						<input type="hidden" name="actionP" value="deleteCategorie">

						<div class="form-group">
							<button type="submit" class="btn btn-success">Confirmer</button>
							<button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Annuler">Annuler</button>
						</div>
					</form>';
					$retour['result'] = true;
					$retour['message'] = $form;
 				} else {
					$retour['result'] = false;
					$retour['message'] = '<div class="alert alert-danger">La catégorie n\'existe pas !</div>';
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