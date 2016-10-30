<?php 
	session_start(); // Sinon on a pas accès aux sessions

	require_once '../File.php';
	require_once File::build_path(array('model', 'ModelProduit.php'));
	require_once File::build_path(array('controller', 'ControllerProduit.php'));

	$retour = array(); //Tableau de retour
	if(ControllerUtilisateur::isConnected()) {
		$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
		if($currentUser->getPower() == Conf::$power['admin']) {
			if(isset($_POST['idProduit'],$_POST['favori'])) {
				$idProduit = strip_tags($_POST['idProduit']);
				$newFavori = strip_tags($_POST['favori']);
				$produit = ModelProduit::select($idProduit);

				if($produit != false) {
					if($produit->get('favorited') != $newFavori) {
						$data = array(
							'favorited' => $newFavori
						);
						$checkUpdate = $produit->updateFavori($newFavori);
						if($checkUpdate) {
							$retour['result'] = true;
							$retour['idProduit'] = $produit->get('idProduit');
							$retour['newFavori'] = $newFavori;
							if($newFavori == 1) {
								$retour['newIcon'] = '<i class="fa fa-star" aria-hidden="true"></i>';
							} else {
								$retour['newIcon'] = '<i class="fa fa-star-o" aria-hidden="true"></i>';
							}
							$retour['message'] = '<div class="alert alert-success">Favori mis à jour pour le produit selectionné !</div>';
						} else {
							$retour['result'] = false;
							$retour['message'] = '<div class="alert alert-danger">Impossible de mettre à jour le produit !</div>';
						}
					} else {
						$retour['result'] = false;
						$retour['message'] = '<div class="alert alert-danger">Le produit est déjà dans l\'état souhaité !</div>';
					}
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