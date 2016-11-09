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

			$retour = '<table class="table table-hover">
				<thead>
					<tr>
						<th>Produit n°</th>
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
					$prixTotalProd = $quantite * $prixUnitaire;

					if($quantite > $produit->getStock()) {
						$quantiteReelle = $produit->getStock();
						$_SESSION['panier'][$idProduit] = $quantiteReelle;
					} else {
						$quantiteReelle = $quantite;
					}

					$retour .= '<tr>
						<td>'.$idProduit.'</td>
						<td>'.$prixUnitaire.' €</td>
						<td>'.$quantiteReelle.'</td>
						<td>'.$prixTotalProd.' €</td>
						<td><a href="index.php?controller=panier&action=remove&idProduit='.$idProduit.'" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Supprimer</a></td>
					</tr>';

					$prixTotal += $prixTotalProd;
					$nbProduits++;
				}
			}

			$retour .= '</tbody></table>
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