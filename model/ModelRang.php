<?php
require_once 'Model.php';

class ModelRang extends Model {

	// Mettre en protected pour y avoir accès depuis Model
	protected $idRang;
	protected $label;
	protected $power;
	protected $color;

	protected static $tableName = 'rangs'; // Correspond au nom de la table SQL (pratique si différent du nom de l'objet)
	protected static $object = 'rang'; // Correspond au nom de l'objet à créer (ici produit)
	protected static $primary = 'idRang'; // Correspond à la clé primaire de la table (pratique pour faire un read())

	public function __construct($idRang = NULL, $label = NULL, $power = NULL, $color = NULL) {
	    if (!is_null($idRang) && !is_null($label) && !is_null($power) && !is_null($color)) {
	        $this->idRang = $idRang;
	        $this->label = $label;
	        $this->power = $power;
	        $this->color = $color;
	    }
	}
}

?>