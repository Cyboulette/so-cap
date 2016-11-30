<?php
require_once 'Model.php';
require_once 'ModelProduit.php';
require_once 'ModelCommande.php';

class ModelProduitscommande extends Model {

	// Mettre en protected pour y avoir accès depuis Model
	protected $idCommande;
	protected $idProduit;
	protected $quantite;

	protected static $object = 'commande';
	protected static $primary = 'idCommande';
	protected static $tableName = 'commandes';

	public function __construct($idCommande = NULL, $idProduit = NULL, $quantite = NULL) {
	    if (!is_null($idCommande) && !is_null($idProduit) && !is_null($quantite)) {
	        $this->idCommande = $idCommande;
	        $this->idProduit = $idProduit;
	        $this->quantite = $quantite;
	    }
	}