<?php
	require_once 'Model.php';
	require_once 'ModelProduit.php';

	class ModelCommande extends Model {

		private $idCommande;
		private $idUtilisateur;
		private $dateComande;
		private $prixTotal;

		protected static $object = 'commande';
		protected static $primary = 'idCommande';
		protected static $tableName = 'commandes';

		public function __construct($idCommande = NULL, $idUtilisateur = NULL, $dateCommande = NULL, $prixTotal = NULL) {
		    if (!is_null($idCommande) && !is_null($idUtilisateur) && !is_null($dateCommande) && !is_null($prixTotal)) {
		        $this->idCommande = $idCommande;
		        $this->idUtilisateur = $idUtilisateur;
		        $this->dateCommande = $dateCommande;
		        $this->prixTotal = $prixTotal;
		    }
		}

		public function get($nom_attribut){
			if (property_exists($this, $nom_attribut)){
				return $this->$nom_attribut;
			}
			return false;
		}

		public function set($nom_attribut, $modif){
			if (property_exists($this, $nom_attribut)){
				$this.$nom_attribut = $modif;
			}
			return false;

		}

		public function getProduits(){
			try {
				$sql = "SELECT * FROM `produitsCommandes` WHERE `idCommande` = :idCommande";
				$req_produits = Model::$pdo->prepare($sql);

				$values = array(
					'idCommande' => $this->idCommande
				);

				$req_produits->execute($values);
				$result = $req_produits->fetchAll();
				if(empty($result)) {
					return false;
				} else {
					$produitsCommandes = array();
					foreach ($result as $pc) {
						$product = ModelProduit::select($pc['idProduit']);
						if($product != false) {
							array_push($produitsCommandes, $product);
						}
					}
					return $produitsCommandes;
				}

			} catch(PDOException $e) {
				return false;
			}
		}

		public static function error($error) {
			$displayError = $error;
			$view = 'error';
			$pagetitle= 'So\'Cap - Erreur';
			require File::build_path(array('view', 'view.php'));
		}


	}
?>