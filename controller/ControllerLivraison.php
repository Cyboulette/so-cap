<?php
require_once File::build_path(array('model', 'ModelLivraison.php'));
//require_once File::build_path(array('controller', 'ControllerUtilisateur.php')); --> Devenu inutile grâce à l'auto-loader du routeur

class ControllerLivraison {

	protected static $object = 'livraison';

	public static function readAll() {
		$tab_l = ModelLivraison::selectAll();
		$view = 'list';
		$pagetitle= 'So\'Cap - Liste des livraisons';
      if(!empty($tab_l)) {
         require File::build_path(array('view', 'view.php'));
      } else {
         ModelLivraison::error("pas de livraiosn");
      }
	}

   public static function read() {
      if(isset($_GET['idLivraison'])) {
         $l = ModelLivraison::select($_GET['idLivraison']);
      } else {
         $l = false;
      }

      /*if($l != false) {
         $view = 'detail';
         $pagetitle= 'So\'Cap - Affichage d\'un produit';
         require File::build_path(array('view', 'view.php'));
      } else {
         ModelProduit::error("Ce produit n'est pas disponible");
      }*/
   }
}
?>