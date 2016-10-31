<?php
require_once File::build_path(array('model', 'ModelCommande.php'));
//require_once File::build_path(array('controller', 'ControllerUtilisateur.php')); --> Devenu inutile grâce à l'auto-loader du routeur

class ControllerCommande {

	protected static $object = 'commande';

	public static function readAll() {
		$tab_c = ModelCommande::selectAll();
      $view = 'list';
		$pagetitle= 'So\'Cap - Liste des commandes';
      $powerNeeded = ControllerUtilisateur::isConnected();
      require File::build_path(array('view', 'view.php'));
      
	}

   public static function read() {
      if(isset($_GET['idCommande'])) {
         $c = ModelCommande::select($_GET['idCommande']);
      } else {
         $c = false;
      }
      if($c != false) {
         $pagetitle= 'So\'Cap - Affichage d\'une commande';
         $view = 'detail';
         $produitsCommandes = $c->getProduits();
         $powerNeeded = ControllerUtilisateur::isConnected();
         require File::build_path(array('view', 'view.php'));
      }
   }
}
?>