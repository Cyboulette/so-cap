<?php
require_once 'Model.php';

class ModelUtilisateur extends Model {

	// Mettre en protected pour y avoir accès depuis Model
	protected $idUtilisateur;
	protected $email;
	protected $password;
	protected $prenom;
	protected $nom;
	protected $rang;

	protected static $tableName = 'utilisateurs'; // Correspond au nom de la table SQL (pratique si différent du nom de l'objet)
	protected static $object = 'utilisateur'; // Correspond au nom de l'objet à créer (ici produit)
	protected static $primary = 'idUtilisateur'; // Correspond à la clé primaire de la table (pratique pour faire un read())

	public function __construct($idUtilisateur = NULL, $email = NULL, $password = NULL, $prenom = NULL, $nom = NULL, $rang = NULL) {
	    if (!is_null($idUtilisateur) && !is_null($email) && !is_null($password) && !is_null($prenom) && !is_null($nom) && !is_null($rang)) {
	        $this->idUtilisateur = $idUtilisateur;
	        $this->email = $email;
	        $this->password = $password;
	        $this->prenom = $prenom;
	        $this->nom = $nom;
	        $this->rang = $rang;
	    }
	}

}

?>