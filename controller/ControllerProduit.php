<?php
require_once File::build_path(array('model', 'ModelProduit.php'));
require_once File::build_path(array('model', 'ModelCategorie.php'));
// require_once File::build_path(array('controller', 'ControllerUtilisateur.php')); --> Devenu inutile grâce à l'auto-loader du routeur

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
         $powerNeeded = true;
         require File::build_path(array('view', 'view.php'));
      } else {
         ControllerDefault::error("Nous ne possédons aucun produit");
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
         
         if(!empty(strip_tags($p->get('description')))) {
            $description = strip_tags($p->get('description'));
         } else {
            $description = 'Ce produit ne possède aucune description !';
         }

         $view = 'detail';
         $pagetitle= 'So\'Cap - Affichage d\'un produit';
         $powerNeeded = true;
         require File::build_path(array('view', 'view.php'));
      } else {
         ControllerDefault::error("Ce produit n'est pas disponible");
      }
   }
   
}
?>