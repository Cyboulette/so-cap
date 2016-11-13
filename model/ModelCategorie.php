<?php
require_once 'Model.php';

class ModelCategorie extends Model {

	// Mettre en protected pour y avoir accès depuis Model
	protected $idCategorie;
	protected $label;

	protected static $tableName = 'categories'; // Correspond au nom de la table SQL (pratique si différent du nom de l'objet)
	protected static $object = 'categorie'; // Correspond au nom de l'objet à créer (ici produit)
	protected static $primary = 'idCategorie'; // Correspond à la clé primaire de la table (pratique pour faire un read())

	// Le constructeur, qui peut accepter du NULL (dans le cas d'un FETCH::CLASS)
	// Constructeur différent dans chaque ModelPAGE.php
	public function __construct($idCategorie = NULL, $label = NULL) {
	    if (!is_null($idCategorie) && !is_null($label)) {
	        $this->idCategorie = $idCategorie;
	        $this->label = $label;
	    }
	}

	public function save() {
		try {
			$sql = 'INSERT INTO `'.static::$tableName.'` (idCategorie, label) VALUES (NULL, :label)';
			$addCategorie = Model::$pdo->prepare($sql);

			$values = array(
				'label' => strip_tags($this->get('label'))
			);

			$addCategorie->execute($values);
			$lastId = Model::$pdo->lastInsertId();
			return $lastId;
		} catch(PDOException $e) {
			if (Conf::getDebug()) {
				echo $e->getMessage();
			}
			return false;
			die();
		}
	}

	public function update($data) {
		try {
			$sql = 'UPDATE `'.static::$tableName.'` SET label = :label WHERE idCategorie = :idCategorie';
			$updateCategorie = Model::$pdo->prepare($sql);
			$data = array(
				'label' => $data['label'],
				'idCategorie' => $this->idCategorie
			);
			$updateCategorie->execute($data);
			return true;
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