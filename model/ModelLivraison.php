<?php 
	require_once 'Model.php';
	
	class ModelLivraison extends Model{
		
		private $idLivraison;
		private $idCommande;
		private $date;
		private $etatCommande;
		private $modeLivraison;

		protected static $tableName = 'livraisons';
		protected static $object = 'livraison';
		protected static $primary='idLivraison';

		public function __construct($idLivraison = NULL, $idCommande = NULL, $date = NULL, $etatCommande = NULL, $modeLivraison = NULL) {
		    if (!is_null($idLivraison) && !is_null($idCommande) && !is_null($date) && !is_null($etatCommande) && !is_null($modeLivraison)) {
		        $this->idLivraison = $idLivraison;
		        $this->idCommande = $idCommande;
		        $this->date = $date;
		        $this->etatCommande = $etatCommande;
		        $this->modeLivraison = $modeLivraison;
		    }
		}

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

		public function getModeLivraison(){
			$sql = "SELECT * FROM 'modelivraisons' WHERE 'idModeLivraison' = :idModeLivraison";
			$req_livr = Model::$pdo->prepare($sql);
			$values = array('idModeLivraison' => $this->idModeLivraison);
			$req_livr->execute($values);
			$res = $req_livr->fetch();
			return $res['label'];
		}

	}
?>