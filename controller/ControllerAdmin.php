<?php
require_once File::build_path(array('model', 'ModelUtilisateur.php'));
require_once File::build_path(array('model', 'ModelProduit.php'));
require_once File::build_path(array('model', 'ModelCategorie.php'));
require_once File::build_path(array('model', 'ModelCommande.php'));

class ControllerAdmin {

	protected static $object = 'admin';

	// Charge l'index de l'administration
	public static function index() {
		if(ControllerUtilisateur::isConnected()) {
			$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
			$powerNeeded = ($currentUser->getPower() == Conf::$power['admin']);

			$view = 'index';
			$pagetitle = 'So\'Cap - Administration';

			$nombreUsers = ModelUtilisateur::getNombreActifsAndValid();
			$nombreProduits = count(ModelProduit::selectAll());
			$nombreCommandes = count(ModelCommande::selectAll());
			$argentTotal = ModelCommande::getTotalMontant();

			require File::build_path(array('view', 'view.php'));
		} else {
			ModelUtilisateur::error('Vous ne pouvez pas accéder à cette page sans être connecté !');
		}
	}

	public static function listProduits() {
		$currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
		$powerNeeded = ($currentUser->getPower() == Conf::$power['admin']);
		
		if($powerNeeded) {
			if(isset($_POST['actionP']) && !empty($_POST['actionP'])) {
				switch ($_POST['actionP']) {
					case 'updateStockProduit':
						if(isset($_POST['idProduit'], $_POST['stock'])) {
							$idProduit = strip_tags($_POST['idProduit']);
							$stock = strip_tags($_POST['stock']);
							$produit = ModelProduit::select($idProduit);
							if($produit != false) {
								if(is_numeric($stock)) {
									if($stock >= 0) {
										$checkUpdateStock = $produit->updateStock($stock);
										if($checkUpdateStock) {
											$notif = '<div class="alert alert-success">Le stock pour ce produit a bien été mis à jour !</div>';
										} else {
											$notif = '<div class="alert alert-danger">Impossible de mettre à jour le stock pour ce produit !</div>';
										}
									} else {
										$notif = '<div class="alert alert-danger">Le stock doit être >= 0 !</div>';
									}
								} else {
									$notif = '<div class="alert alert-danger">Le stock doit être une valeur entière !</div>';
								}
							} else {
								$notif = '<div class="alert alert-danger">Le produit demandé n\'existe pas !</div>';
							}
						} else {
							$notif = '<div class="alert alert-danger">Merci de remplir correctement le formulaire !</div>';
						}
						break;
					default:
						break;
				}
			}
		}
		$tab_p = ModelProduit::selectAll();
		$view = 'listProduits';
		$pagetitle = 'So\'Cap - Administration - Liste des produits';
		require File::build_path(array('view', 'view.php'));
	}
}

?>