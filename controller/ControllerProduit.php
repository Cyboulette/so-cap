<?php
require_once File::build_path(array('model', 'ModelProduit.php'));
require_once File::build_path(array('model', 'ModelCategorie.php'));

class ControllerProduit {

	protected static $object = 'produit';

	public static function readAll() {
		$tab_p = ModelProduit::selectAll();
		$view = 'list';
		$pagetitle= 'So\'Cap - Liste des produits';

      $listCategories = ModelCategorie::selectAll();
      $displayCategories = '';
      foreach ($listCategories as $categorie) {
         $displayCategories .= '<li data-tri="categorie" data-categorie="'.$categorie->get('idCategorie').'"><a href="">'.strip_tags($categorie->get('label')).'</a></li>';
      }

      if(!empty($tab_p)) {
         $dataImages = array();
         $order = 0;
         foreach($tab_p as $p) {
            $imagesProduit = $p->getImages();
            if($imagesProduit != false) {
               foreach ($imagesProduit as $imageProduit) {
                  if($order < 1) {
                     if(file_exists(File::build_path(array('assets/images/produits/', $imageProduit['nomImage'])))) {
                        $dataImages[$imageProduit['idProduit']][$order] = array(
                           'url' => 'assets/images/produits/'.$imageProduit['nomImage']
                        );
                        $order++;
                     }
                  }
               }
            }
            $order = 0;
         }
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
         $stock = $p->getStock();
         if ($stock == 1) {
            $levelLabelStock = 'label-danger';
         } elseif($stock == 0) {
            $levelLabelStock = 'label-default';
         } elseif($stock <= 10) {
            $levelLabelStock = 'label-info';
         } else {
            $levelLabelStock = 'label-success';
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