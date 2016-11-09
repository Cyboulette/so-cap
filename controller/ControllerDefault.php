<?php
require_once File::build_path(array('config', 'Conf.php'));
require_once File::build_path(array('model', 'ModelProduit.php'));

class ControllerDefault {

	protected static $object = 'default';

	public static function index() {
		unset($_SESSION['panier']);
		$view = 'index';
		$pagetitle = 'So\'Cap';
		$powerNeeded = true;
		require File::build_path(array('view', 'view.php'));
	}

	public static function error($error) {
		$displayError = $error;
		$view = 'error';
		$pagetitle= 'So\'Cap - Erreur';
		$powerNeeded = true;
		require File::build_path(array('view', 'view.php'));
	}

	public static function active($currentController, $currentAction) {
		$queryString = $_SERVER['QUERY_STRING'];
		if(!empty($currentAction)) {
			if(strpos($queryString, 'controller='.$currentController."&action=".$currentAction) !== false) {
				echo 'class="active"';
			}
		} else {
			if(strpos($queryString, 'controller='.$currentController) !== false) {
				echo 'class="active"';
			}
			if($currentController == "index" && empty($queryString)) {
				echo 'class="active"';
			}
		}
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
					</tr>
				</thead>
				<tbody>';
			$prixTotal = 0;
			$nbProduits = 0;

			foreach ($produitsPanier as $produitPanier) {
				$prodP = unserialize($produitPanier);
				$produit = ModelProduit::select($prodP['idProduit']);

				if($produit != false) {
					$prixUnité = $produit->get('prix');
					$prixTotalProd = $prodP['quantité'] * $prixUnité;
					$retour .= '<tr>
						<td>'.$prodP['idProduit'].'</td>
						<td>'.$prixUnité.'</td>
						<td>'.$prodP['quantité'].'</td>
						<td>'.$prixTotalProd.'</td>
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
}
?>