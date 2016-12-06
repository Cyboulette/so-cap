<?php
require_once File::build_path(array('model', 'ModelCommande.php'));
require_once File::build_path(array('model', 'ModelUtilisateur.php'));
require_once File::build_path(array('model', 'ModelProduitsCommandes.php'));
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
      $pdf->SetDrawColor(0,0,0);
      $pdf->SetLineWidth(0.5);
      $pdf->Cell(60,15,"So'CAP",1,0,'C',true);
      $pdf->Ln();
      $pdf->Ln();
      $date = date('d/m/Y à H:m:i');
      $pdf->SetTextColor(0,0,255);
      $pdf->Write(5,'www.fpdf.org','http://www.fpdf.org');
      $pdf->Ln();

      // début du tableau de facture
      // Couleurs, épaisseur du trait et police grasse
      $pdf->SetFillColor(200,154,91);
      $pdf->SetTextColor(255);
      $pdf->SetDrawColor(0,0,0);
      $pdf->SetLineWidth(.3);
      $pdf->SetFont('','B');
      // En-tête
      $pdf->Cell(60,7,"Nom ",1,0,'',true);
      $pdf->Cell(45,7,"Quantité ",1,0,'',true);
      $pdf->Cell(45,7,"Prix unitaire",1,0,'',true); 
      $pdf->Cell(45,7,"Prix total",1,0,'',true);
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
        $prix = $pc->get('prix');
        $quantite = $c->getNbProduits($idProduit);

        //début du pddf
        $pdf->Cell(60,6,$label,1);
        $pdf->Cell(45,6,$quantite,1);
        $pdf->Cell(45,6,$prix." €",1);
        $pdf->Cell(45,6,$prix*$quantite,1);
        $pdf->Ln();
      }
    }

    $pdf->Output();
  }

  public static function createCommande() {
    $view = 'commande';
    $pagetitle = 'So\'Cap - Passer une commande';
    $powerNeeded = ControllerUtilisateur::isConnected();
    if(ControllerPanier::nombreProduits() > 0){
      require File::build_path(array('view', 'view.php'));
    } else {
      ControllerDefault::error('Vous devez posséder des produits afin de passer une commande !');
    }
  }

  public static function validate() {
    if(isset($_POST['adresse'], $_POST['ville'], $_POST['codepostal'], $_POST['pays'])) {
      $adresse = strip_tags($_POST['adresse']);
      $ville = strip_tags($_POST['ville']);
      $codepostal = strip_tags($_POST['codepostal']);
      $pays = strip_tags($_POST['pays']);
      if($pays == "France") { // Notre site ne gère que le pays "France"
        if(!empty($adresse) && !ctype_space($adresse)) {
          if(!empty($ville) && !ctype_space($ville)) {
            if(!empty($codepostal) && !ctype_space($codepostal)) {
              if(preg_match('#^[0-9]{5}$#',$codepostal)) {
                if(ControllerPanier::nombreProduits() > 0){
                  $data = array(
                    'idCommande' => NULL,
                    'idUtilisateur' => $_SESSION['idUser'],
                    'dateCommande' => date("Y-m-d H:i:s"),
                    'prixTotal' => ControllerPanier::getMontantTotal(),
                    'adresse' => $adresse,
                    'ville' => $ville,
                    'codePostal' => $codepostal,
                    'pays' => $pays
                  );
                  $produitsPaniers = $_SESSION['panier']; //On enregistre le panier dans une variable pour éviter qu'il change entre temps.
                  $idCommande = ModelCommande::save($data, 'id');
                  $checkCommande = ModelCommande::select($idCommande);

                  if($checkCommande != false) {
                    foreach ($produitsPaniers as $idP => $quantite) {
                      $produit = ModelProduit::select($idP);
                      $checkProduit = ModelProduit::select($idP);
                      if($checkProduit != false){
                        $data = array(
                        'idCommande' => $idCommande,
                        'idProduit' => $idP,
                        'quantite' => $quantite
                        );
                        $resultSave = ModelProduitsCommandes::save($data);
                        if($resultSave != false) {
                          unset($_SESSION['panier']);
                          $message = '<div class="alert alert-success">Votre commande a bien été validée et payée ! Retrouvez la facture dans la liste ci-dessous !</div>';

                          $currentUser = ModelUtilisateur::selectCustom('idUtilisateur', $_SESSION['idUser'])[0];
                          $tab_c = ModelCommande::selectCustom('idUtilisateur',$currentUser->get('idUtilisateur'));
                          $view = 'list';
                          $pagetitle= 'So\'Cap - Liste des commandes';
                          $powerNeeded = ControllerUtilisateur::isConnected();
                          require File::build_path(array('view', 'view.php'));
                        } else {
                          ControllerDefault::error('Impossible d\'enregistrer votre commande, veuillez nous contacter !');
                        }
                      }
                    }
                  } else {
                    ControllerDefault::error('La commande n\'a pas pu être crée, veuillez nous contacter !');
                  }
                } else {
                  ControllerDefault::error('Désolé, mais votre panier est vide ! Impossible de créer une commande');
                }
              } else {
                ControllerDefault::error('Votre code postal n\'est pas un code postal français valide !');
              }
            } else {
              ControllerDefault::error('Vous devez préciser un code postal !');
            }
          } else {
            ControllerDefault::error('Vous devez préciser une ville !');
          }
        } else {
          ControllerDefault::error('Vous devez préciser votre adresse !');
        }
      } else {
        ControllerDefault::error('Vous devez sélectionner France comme pays !');
      }
    } else {
      ControllerDefault::error('Vous devez remplir le formulaire de valdiation de commande avec tous les champs présents !');
    }
  }

}
?>