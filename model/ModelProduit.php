<?php
require_once 'Model.php';

class ModelProduit extends Model {

	// Mettre en protected pour y avoir accès depuis Model
	protected $idProduit;
	protected $label;
	protected $categorieProduit;
	protected $description;
	protected $prix;
	protected $favorited;

	protected static $tableName = 'produits'; // Correspond au nom de la table SQL (pratique si différent du nom de l'objet)
	protected static $object = 'produit'; // Correspond au nom de l'objet à créer (ici produit)
	protected static $primary = 'idProduit'; // Correspond à la clé primaire de la table (pratique pour faire un read())

	// Le constructeur, qui peut accepter du NULL (dans le cas d'un FETCH::CLASS)
	// Constructeur différent dans chaque ModelPAGE.php
	public function __construct($idProduit = NULL, $label = NULL, $categorieProduit = NULL, $description = NULL, $prix = NULL, $favorited = NULL) {
	    if (!is_null($idProduit) && !is_null($label) && !is_null($categorieProduit) && !is_null($description) && !is_null($prix) && !is_null($favorited)) {
	        $this->idProduit = $idProduit;
	        $this->label = $label;
	        $this->categorieProduit = $categorieProduit;
	        $this->description = $description;
	        $this->prix = $prix;
	        $this->favorited = $favorited;
	    }
	}

	public function getStock() {
		try {
			$sql = "SELECT * FROM `stocks` WHERE `idProduit` = :idProduit";
			$req_stocks = Model::$pdo->prepare($sql);

			$values = array(
				'idProduit' => $this->idProduit
			);

			$req_stocks->execute($values);
			$result = $req_stocks->fetch();

			if(empty($result)) {
				// Le stock n'a pas été crée.
				return 0;
			} else {
				return $result['stockRestant'];
			}
		} catch(PDOException $e) {
			return 0;
		}
	}

	public function addImage($urlVisuel) {
		try {
			$sql = "INSERT INTO `visuelsProduits` VALUES (:idVisuel, :idProduit, :nomImage)";
			$addImage = Model::$pdo->prepare($sql);

			$values = array(
				'idVisuel' => NULL,
				'idProduit' => $this->idProduit,
				'nomImage' => $urlVisuel
			);

			$addImage->execute($values);
			return true;
		} catch(PDOException $e) {
			return false;
		}
	}

	public function getImages() {
		try {
			$sql = "SELECT * FROM `visuelsProduits` WHERE `idProduit` = :idProduit";
			$req_images = Model::$pdo->prepare($sql);

			$values = array(
				'idProduit' => $this->idProduit
			);

			$req_images->execute($values);
			$result = $req_images->fetchAll();

			if(empty($result)) {
				return false;
			} else {
				return $result;
			}

		} catch(PDOException $e) {
			return false;
		}
	}

	public static function getImage($idProduit, $idVisuel) {
		try {
			$sql = "SELECT * FROM `visuelsProduits` WHERE `idProduit` = :idProduit AND `idVisuel` = :idVisuel";
			$req_image = Model::$pdo->prepare($sql);

			$values = array(
				'idProduit' => $idProduit,
				'idVisuel' => $idVisuel
			);

			$req_image->execute($values);
			$result = $req_image->fetch();

			if(empty($result)) {
				return false;
			} else {
				return $result;
			}

		} catch(PDOException $e) {
			return false;
		}		
	}

	public static function deleteImage($idVisuel) {
		try {
		  $sql = "DELETE FROM `visuelsProduits` WHERE `idVisuel` = :idVisuel";
		  $rep = Model::$pdo->prepare($sql);
		  $values = array(
		  	'idVisuel' => strip_tags($idVisuel)
		  );
		  $rep->execute($values);
		  return true;
		} catch(PDOException $e) {
		  if (Conf::getDebug()) {
		    echo $e->getMessage();
		  }
		  return false;
		  die();
		}
	}

	public function getAllCategories() {
		try {
			$sql = "SELECT * FROM `categories`";
			$req_categ = Model::$pdo->prepare($sql);

			$req_categ->execute();
			$result = $req_categ->fetchAll();

			if(empty($result)) {
				return false;
			} else {
				return $result;
			}

		} catch(PDOException $e) {
			return false;
		}
	}

	public function update($data) {
		try {
			$sql = 'UPDATE `'.static::$tableName.'` SET label = :label, categorieProduit = :categorieProduit, description = :description, prix = :prix WHERE idProduit = :idProduit';
			$updateProduit = Model::$pdo->prepare($sql);
			$data = array(
				'label' => $data['label'],
				'categorieProduit' => $data['categorieProduit'],
				'description' => $data['description'],
				'prix' => $data['prix'],
				'idProduit' => $this->idProduit
			);
			$updateProduit->execute($data);
			return true;
		} catch(PDOException $e) {
			if(Conf::getDebug()) {
				echo $e->getMessage();
			}
			return false;
			die();
		}
	}

	public function updateStock($newStock) {
		try {

			$sql = "SELECT idProduit FROM `stocks` WHERE `idProduit` = :idProduit";
			$req_stocks = Model::$pdo->prepare($sql);

			$values = array(
				'idProduit' => $this->idProduit
			);

			$req_stocks->execute($values);
			$verifyStock = $req_stocks->fetch();

			if(empty($verifyStock)) {
				$sql = 'INSERT INTO `stocks` (idProduit, stockRestant) VALUES (:idProduit, :stockRestant)';
				$createStock = Model::$pdo->prepare($sql);
				$values = array(
					'idProduit' => $this->idProduit,
					'stockRestant' => $newStock
				);
				$createStock->execute($values);
				return true;
			} else {
				$sql = 'UPDATE `stocks` SET stockRestant = :stockRestant WHERE idProduit = :idProduit';
				$updateStock = Model::$pdo->prepare($sql);
				$data = array(
					'stockRestant' => $newStock,
					'idProduit' => $this->idProduit
				);
				$updateStock->execute($data);
				return true;
			}
		} catch(PDOException $e) {
			if(Conf::getDebug()) {
				echo $e->getMessage();
			}
			return false;
			die();
		}
	}

	public function updateFavori($newFavori) {
		try {
			$sql = 'UPDATE `'.static::$tableName.'` SET favorited = :favorited WHERE idProduit = :idProduit';
			$updateFavori = Model::$pdo->prepare($sql);
			$data = array(
				'favorited' => $newFavori,
				'idProduit' => $this->idProduit
			);
			$updateFavori->execute($data);
			return true;
		} catch(PDOException $e) {
			if(Conf::getDebug()) {
				echo $e->getMessage();
			}
			return false;
			die();
		}
	}

	public static function selectText($text) {
		$table_name = static::$tableName;
		$class_name = 'Model'.ucfirst(static::$object);
		try {
			$sql = "SELECT * from `".$table_name."` WHERE `label` LIKE :label";
			$req_texte = Model::$pdo->prepare($sql);

			$values = array(
				'label' => '%'.$text.'%'
			);

			$req_texte->execute($values);
			$req_texte->setFetchMode(PDO::FETCH_CLASS, $class_name);
			$tab_t = $req_texte->fetchAll();
			return $tab_t;
		} catch(PDOException $e) {
			if(Conf::getDebug()) {
				echo $e->getMessage();
			}
			return false;
			die();
		}
	}
	
}
?>