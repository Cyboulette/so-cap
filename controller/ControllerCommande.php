<?php
require_once File::build_path(array('model', 'ModelCommande.php'));
require_once File::build_path(array('model', 'ModelUtilisateur.php'));

require_once File::build_path(array('model', 'ModelUtilisateur.php'));
//require_once File::build_path(array('controller', 'ControllerUtilisateur.php')); --> Devenu inutile grâce à l'auto-loader du routeur
require_once File::build_path(array('lib/fpdf', 'fpdf.php'));

class ControllerCommande {

	protected static $object = 'commande';

	public static function readAll() {
      $currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
		$tab_c = ModelCommande::selectCustom('idUtilisateur',$currentUser->get('idUtilisateur'));
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
         $currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
         $currentIdUser = $currentUser->get('idUtilisateur');
         if($c->get('idUtilisateur') == $currentIdUser && ControllerUtilisateur::isConnected()){
            $powerNeeded = true;
         } else {
            $powerNeeded = false;
         }
         require File::build_path(array('view', 'view.php'));
      }
   }

   public static function genererFacture(){
      if(isset($_GET['idCommande'])) {
         $c = ModelCommande::select($_GET['idCommande']);
      } else {
         $c = false;
      }
      // création du pdf
      define('EURO', chr(128));
      $pdf = new FPDF();
      $pdf->AddPage();
      $pdf->SetFont('Arial','B',12);
      if($c != false) { // si il y a une commande
         $produitsCommandes = $c->getProduits();
         $pdf->SetFillColor(200,154,91);
         $pdf->SetTextColor(255);
         $pdf->SetDrawColor(200,154,91);
         $pdf->Cell(60,15,"so'CAP ",1,0,'',true);
         $pdf->Ln();







         // début du tableau de facture
          // Couleurs, épaisseur du trait et police grasse
          $pdf->SetFillColor(200,154,91);
          $pdf->SetTextColor(255);
          $pdf->SetDrawColor(0,0,0);
          $pdf->SetLineWidth(.3);
          $pdf->SetFont('','B');
          // En-tête
              $pdf->Cell(30,7,"Quantité ",1,0,'',true);
              $pdf->Cell(60,7,"Nom ",1,0,'',true);
              $pdf->Cell(30,7,"Categorie",1,0,'',true);
              $pdf->Cell(30,7,"Prix",1,0,'',true); 
              $pdf->Cell(30,7,"Prix total",1,0,'',true);
          $pdf->Ln();
          // Restauration des couleurs et de la police
          $pdf->SetFillColor(224,235,255);
          $pdf->SetTextColor(0);
          $pdf->SetFont('');
          // Données
          $fill = false;


         foreach ($produitsCommandes as $pc) { 
            $idProduit = $pc->get('idProduit');
            $label = strip_tags($pc->get('label'));
            $categorieProduit = $pc->get('categorieProduit');
            $prix = $pc->get('prix');
            $quantite = $c->getNbProduits($idProduit);

            //début du pddf

            $pdf->Cell(30,6,$quantite[0],1);
            $pdf->Cell(60,6,$label,1);
            $pdf->Cell(30,6,$categorieProduit,1);
            $pdf->Cell(30,6,$prix." €",1);
            $pdf->Cell(30,6,$prix*$quantite[0],1);
            $pdf->Ln();
         }
      }

      $pdf->Output();
   }

   public static function createCommande() {
      if(controllerPanier::nombreProduits() > 0){
            $data = array(
		        'idCommande' => NULL,
		        'idUtilisateur' => _SESSION['idUser'],
		        'dateCommande' => now(),
		        'prixTotal' => 150,
			);
			$idCommande = ModelCommande::save($data,'id');
			$checkCommande = ModelCommande::select($idCommande);
			if($checkCommande != false){
		        foreach ($_SESSION['Panier'] as $key => $valeur) {
		            $produit = ModelProduit::select($key);
					$checkProduit = ModelProduit::select($key);
					if($checkProduit != false){

		            	$data = array(
				            'idCommande' => $idCommande,
				            'idProduit' => $key,
				            'quantite' => $valeur
						);
						$resultSave = ModelProduitcommandes::save($data);
					}
			}
	   	} else {
        	ControllerDefault::error('Vous n\'avez pas de produit' );
   		}
   }

}
?>