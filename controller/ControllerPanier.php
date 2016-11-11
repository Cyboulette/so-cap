<?php
require_once File::build_path(array('model', 'ModelProduit.php'));

class ControllerPanier {

	protected static $object = 'produit';

	public function __construct() {
		if(!isset($_SESSION['panier'])) {
			$_SESSION['panier'] = array();
		}
	}

	public function add($idProduit, $quantite) {
		$produit = ModelProduit::select($idProduit);

		if($produit != false) {
			if($produit->getStock() != 0) {
				if(is_numeric($quantite) && $quantite > 0) {
					$idProduit = $produit->get('idProduit');

					if(isset($_SESSION['panier'][$idProduit])) {
						$quantiteTotale = $_SESSION['panier'][$idProduit] + $quantite;
					} else {
						$quantiteTotale = $quantite;
					}

					if($quantiteTotale <= $produit->getStock()) {
						$_SESSION['panier'][$idProduit] = $quantiteTotale;
						$view = 'addCart';
						$pagetitle= 'So\'Cap - Achat d\'un produit';
						$powerNeeded = true;
						require File::build_path(array('view', 'view.php'));
					} else {
						ModelProduit::error("Vous ne pouvez pas dépasser le stock maximal de produits");
					}
				} else {
					ModelProduit::error("La quantité doit être supérieure à 0");
				}
			} else {
				ModelProduit::error("Impossible d'ajouter ce produit au panier, nous ne l'avons plus en stock");
			}
		} else {
			ModelProduit::error("Ce produit n'est pas disponible à l'ajout dans votre panier !");
		}
	}

	public static function addFromAjax() {
		$retour = array();
		if(isset($_POST['idProduit'], $_POST['quantite'])) {
			$idProduit = strip_tags($_POST['idProduit']);
			$quantite = strip_tags($_POST['quantite']);

			$produit = ModelProduit::select($idProduit);
			if($produit != false) {
				if($produit->getStock() != 0) {
					if(is_numeric($quantite) && $quantite > 0) {
						$idProduit = $produit->get('idProduit');

						if(isset($_SESSION['panier'][$idProduit])) {
							$quantiteTotale = $_SESSION['panier'][$idProduit] + $quantite;
						} else {
							$quantiteTotale = $quantite;
						}

						if($quantiteTotale <= $produit->getStock()) {
							$_SESSION['panier'][$idProduit] = $quantiteTotale;
							$retour['result'] = true;
							$retour['nbProduits'] = self::nombreProduits();
							$retour['nouveauPanier'] = self::afficherPanier();
							$retour['message'] = '<div class="alert alert-success">Le produit a bien été ajouté à votre panier !</div>';
						} else {
							$retour['result'] = false;
							$retour['message'] = '<div class="alert alert-danger">Vous ne pouvez pas dépasser le stock maximal pour ce produit</div>';
						}
					} else {
						$retour['result'] = false;
						$retour['message'] = '<div class="alert alert-danger">La quantité doit être supérieure à 0</div>';
					}
				} else {
					$retour['result'] = false;
					$retour['message'] = '<div class="alert alert-danger">Impossible d\'ajouter ce produit au panier, nous ne l\'avons plus en stock</div>';
				}
			} else {
				$retour['result'] = false;
				$retour['message'] = '<div class="alert alert-danger">Le produit demandé n\'existe pas !</div>';
			}
		} else {
			$retour['result'] = false;
			$retour['message'] = '<div class="alert alert-danger">Vous devez préciser un produit et une quantité</div>';
		}
		echo json_encode($retour);
	}

	public static function remove() {
		$idProduit = $_GET['idProduit'];
		unset($_SESSION['panier'][$idProduit]);
		self::$object = 'default';
		$view = 'index';
		$pagetitle = 'So\'Cap';
		$powerNeeded = true;
		require File::build_path(array('view', 'view.php'));
	}

	public static function afficherPanier() {
		if(isset($_SESSION['panier'])) {
			$produitsPanier = $_SESSION['panier'];

			$retour = '<div class="table-responsive"><table class="table table-hover panierTable">
				<thead>
					<tr>
						<th>#</th>
						<th>Nom du produit</th>
						<th>Prix Unitaire</th>
						<th>Quantité</th>
						<th>Prix final</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>';
			$prixTotal = 0;
			$nbProduits = 0;

			foreach ($produitsPanier as $idP => $quantite) {
				$produit = ModelProduit::select($idP);

				if($produit != false) {
					$idProduit = $produit->get('idProduit');
					$prixUnitaire = $produit->get('prix');
					$nomProduit = strip_tags($produit->get('label'));

					if($quantite > $produit->getStock()) {
						$quantiteReelle = $produit->getStock();
						$_SESSION['panier'][$idProduit] = $quantiteReelle;
					} else {
						$quantiteReelle = $quantite;
					}

					$prixTotalProd = $quantiteReelle * $prixUnitaire;

					$retour .= '<tr>
						<td>'.$idProduit.'</td>
						<td>'.$nomProduit.'</td>
						<td>'.$prixUnitaire.' €</td>
						<td>'.$quantiteReelle.'</td>
						<td>'.$prixTotalProd.' €</td>
						<td><a href="index.php?controller=panier&action=remove&idProduit='.$idProduit.'" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Supprimer</a></td>
					</tr>';

					$prixTotal += $prixTotalProd;
					$nbProduits++;
				}
			}

			$retour .= '</tbody></table></div>
			<h3>Prix à payer : '.$prixTotal.' € TTC</h3>';

			return array(
				'nbProduits' => $nbProduits,
				'prixTotal' => $prixTotal,
				'message' => $retour
			);
		} else {
			return array(
				'nbProduits' => 0,
				'prixTotal' => 0,
				'message' => '<div class="alert alert-danger text-center">Aucun produit pour le moment</div>'
			);
		}
	}

	public static function nombreProduits() {
		if(isset($_SESSION['panier'])) {
			return array_sum($_SESSION['panier']);
		} else {
			return 0;
		}
	}
}
?>