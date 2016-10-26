<?php
require_once 'Model.php';

class ModelProduit extends Model {

	private $idProduit;
	private $label;
	private $categorieProduit;
	private $description;
	private $prix;
	private $favorited;

	protected static $tableName = 'produits'; // Correspond au nom de la table SQL (pratique si différent du nom de l'objet)
	protected static $object = 'produit'; // Correspond au nom de l'objet à créer (ici produit)
	protected static $primary = 'idProduit'; // Correspond à la clé primaire de la table (pratique pour faire un read())

	// Factoriser le get et le set dans Model.php ?
	// On va utiliser un getter générique et un setter générique, ce sera plus rapide et plus pratique
	public function get($nom_attribut) {
	    if (property_exists($this, $nom_attribut))
	        return $this->$nom_attribut;
	    return false;
	}

	public function set($nom_attribut, $valeur) {
	    if (property_exists($this, $nom_attribut))
	        $this->$nom_attribut = $valeur;
	    return false;
	}

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
			if (Conf::getDebug()) {
				echo $e->getMessage();
			} else {
				echo 'Une erreur est survenue <a href="index.php"> retour a la page d\'accueil </a>';
			}
			die();
		}
	}
}
?>