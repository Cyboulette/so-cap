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
}
?>