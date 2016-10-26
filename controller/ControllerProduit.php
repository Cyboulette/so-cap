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
      if(isset($_GET['idProduit'])) {
         $p = ModelProduit::select($_GET['idProduit']);
      } else {
         $p = false;
      }

      if($p != false) {
         $imagesProduit = $p->getImages();
         $dataImages = array();
         $order = 0;
         if($imagesProduit != false) {
            foreach ($imagesProduit as $imageProduit) {
               if(file_exists(File::build_path(array('assets/images/produits/', $imageProduit['nomImage'])))) {
                  $dataImages[$imageProduit['idVisuel']] = array(
                     'order' => $order,
                     'url' => 'assets/images/produits/'.$imageProduit['nomImage']
                  );
                  $order++;
               }
            }
         }
         $view = 'detail';
         $pagetitle= 'So\'Cap - Affichage d\'un produit';
         require File::build_path(array('view', 'view.php'));
      } else {
         ModelProduit::error("Ce produit n'est pas disponible");
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