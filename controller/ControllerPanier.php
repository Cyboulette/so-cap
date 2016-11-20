<?php
require_once File::build_path(array('model', 'ModelProduit.php'));

class ControllerPanier {

	protected static $object = 'produit';

	public function __construct() {
		if(!isset($_SESSION['panier'])) {
			$_SESSION['panier'] = array();
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

	public static function clearPanier() {
		$retour = array();
		if(isset($_SESSION['panier'])) {
			unset($_SESSION['panier']);
			$retour['result'] = true;
			$retour['nbProduits'] = self::nombreProduits();
			$retour['nouveauPanier'] = self::afficherPanier();
			$retour['message'] = '<div class="alert alert-success">Le panier a bien été vidé !</div>';
		} else {
			$retour['result'] = false;
			$retour['message'] = '<div class="alert alert-danger">Impossible de vider votre panier, il était déjà vide</div>';
		}
		echo json_encode($retour);
	}

	public static function removeProductPanier() {
		$retour = array();
		if(isset($_POST['idProduit'])) {
			$idProduit = $_POST['idProduit'];
			unset($_SESSION['panier'][$idProduit]);

			if(self::nombreProduits() == 0) {
				unset($_SESSION['panier']);
			}

			$retour['result'] = true;
			$retour['nbProduits'] = self::nombreProduits();
			$retour['nouveauPanier'] = self::afficherPanier();
			$retour['message'] = '<div class="alert alert-success">Le produit a bien été retiré du panier !</div>';
		} else {
			$retour['result'] = false;
			$retour['message'] = '<div class="alert alert-danger">Vous devez préciser un produit !</div>';
		}
		echo json_encode($retour);
	}

	public static function changeQuantite() {
		$retour = array();
		if(isset($_POST['idProduit'],$_POST['quantite'])) {
			$idProduit = $_POST['idProduit'];
			$quantite = $_POST['quantite'];
			$produit = ModelProduit::select($idProduit);

			if($produit != false) {
				if($produit->getStock() != 0) {
					if(is_numeric($quantite) && $quantite > 0) {
						$idProduit = $produit->get('idProduit');
						if(isset($_SESSION['panier'][$idProduit])) {
							if($quantite <= $produit->getStock()) {
								$_SESSION['panier'][$idProduit] = $quantite;
								$retour['result'] = true;
								$retour['nbProduits'] = self::nombreProduits();
								$retour['nouveauPanier'] = self::afficherPanier();
								$retour['message'] = '<div class="alert alert-success">La quantité a bien été mise à jour !</div>';
							} else {
								$retour['result'] = false;
								$retour['message'] = '<div class="alert alert-danger">Vous ne pouvez pas dépasser le stock maximal pour ce produit</div>';
							}
						} else {
							$retour['result'] = false;
							$retour['message'] = '<div class="alert alert-danger">Vous devez avoir le produit dans votre panier pour changer sa quantité !</div>';
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
			$retour['message'] = '<div class="alert alert-danger">Vous devez préciser un produit et une quantité !</div>';
		}

		echo json_encode($retour);
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

					$retour .= '<tr data-produit="'.$idProduit.'" data-stock="'.$produit->getStock().'">
						<td>'.$idProduit.'</td>
						<td><a href="index.php?controller=produit&action=read&idProduit='.$idProduit.'">'.$nomProduit.'</a></td>
						<td>'.$prixUnitaire.' €</td>
						<td><btn class="btn btn-xs btn-default actionBtnPanier" data-action="changeQuantite"><i class="fa fa-pencil" aria-hidden="true"></i> '.$quantiteReelle.'</btn></td>
						<td>'.$prixTotalProd.' €</td>
						<td><btn class="btn btn-xs btn-danger actionBtnPanier" data-action="removeProductPanier"><i class="fa fa-trash"></i> Supprimer</btn></td>
					</tr>';

					$prixTotal += $prixTotalProd;
					$nbProduits++;
				}
			}

			$retour .= '</tbody></table></div>
			<h3>Prix à payer : '.$prixTotal.' € TTC</h3>
			<script>var fn = window[\'modalActions\'];  if (typeof fn === \'function\') { modalActions(); }</script>';

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