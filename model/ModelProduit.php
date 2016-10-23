<?php
require_once 'Model.php';

class ModelProduit extends Model {

	private $idProduit;
	private $label;
	private $categorieProduit;
	private $description;
	private $prix;

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
	public function __construct($idProduit = NULL, $label = NULL, $categorieProduit = NULL, $description = NULL, $prix = NULL) {
	    if (!is_null($idProduit) && !is_null($label) && !is_null($categorieProduit) && !is_null($description) && !is_null($prix)) {
	        $this->idProduit = $idProduit;
	        $this->label = $label;
	        $this->categorieProduit = $categorieProduit;
	        $this->description = $description;
	        $this->prix = $prix;
	    }
	}
}
?>