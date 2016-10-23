<?php
require_once File::build_path(array('model', 'ModelProduit.php'));

class ControllerProduit {

	protected static $object = 'produit';

	public static function readAll() {
		$tab_p = ModelProduit::selectAll();
		$view = 'list';
		$pagetitle= 'So\'Cap - Liste des produits';
      if(!empty($tab_p)) {
         require File::build_path(array('view', 'view.php'));
      } else {
         ModelProduit::error("Nous ne possédons aucun produit");
      }
	}

   public static function read() {
      ModelProduit::error("Fonction en cours de développement ...");
   }
}
?>