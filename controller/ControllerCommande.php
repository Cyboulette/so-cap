<?php
require_once File::build_path(array('model', 'ModelCommande.php'));

class ControllerCommande {

	protected static $object = 'commande';

	public static function readAll() {
		$tab_c = ModelCommande::selectAll();
      $view = 'list';
		$pagetitle= 'So\'Cap - Liste des commande';
      require File::build_path(array('view', 'view.php'));
      
	}

   public static function read() {
      if(isset($_GET['idCommande'])) {
         $p = ModelComande::select($_GET['idCommande']);
      } else {
         $p = false;
      }

      if($p != false) {
         $pagetitle= 'So\'Cap - Affichage d\'une commande';
         require File::build_path(array('view', 'view.php'));
      } else {
         ModelProduit::error("Cette commande n'éxiste pas");
      }
   }

   public static function addCart() {
      if(isset($_GET['idProduit'])) {
         $produit = ModelProduit::select($_GET['idProduit']);
         if($produit != false) {
            if($produit->getStock() != 0) {
               $view = 'addCart';
               $pagetitle= 'So\'Cap - Achat d\'un produit';
               require File::build_path(array('view', 'view.php'));               
            } else {
               ModelProduit::error("Impossible d'acheter ce produit, nous ne l'avons plus en stock");
            }
         } else {
            ModelProduit::error("Ce produit n'est pas disponible");
         }
      }
   }
}
?>