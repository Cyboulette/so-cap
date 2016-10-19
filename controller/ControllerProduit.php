<?php 
require_once File::build_path(array('model', 'ModelProduit.php'));

class ControllerProduit {

	protected static $object = 'produits';

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
      if(isset($_GET['login'])) {
         $u = ModelProduit::select($_GET['login']);
      } else {
         $u = false;
      }

      if($u != false) {
         $view = 'detail';
         $pagetitle= 'Détail d\'une utilisateur';
         require File::build_path(array('view', 'view.php'));
      } else {
         ModelProduit::error("Cet utilisateur n'existe pas :'(");
      }
   }
}
?>