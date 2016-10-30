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
		
		$tab_p = ModelProduit::selectAll();
		$view = 'listProduits';
		$pagetitle = 'So\'Cap - Administration - Liste des produits';
		require File::build_path(array('view', 'view.php'));
	}
}

?>