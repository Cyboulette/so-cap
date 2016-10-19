<?php
	require_once 'Model.php';

	class ModelCommande extends Model {

		private idCommande;
		private idUtilisateur;
		private dateComande;
		private prixTotal;

		protect static $object = 'commande';
		protect static $primary = $idCommande;


		public function __construct($idCommande = NULL, $idUtilisateur = NULL, $dateCommande = NULL, $prixTotal = NULL) {
		    if (!is_null($idCommande) && !is_null($idUtilisateur) && !is_null($dateCommande) && !is_null($prixTotal)) {
		        $this->idCommande = $idCommande;
		        $this->idUtilisateur = $idUtilisateur;
		        $this->dateCommande = $dateCommande;
		        $this->prixTotal = $prixTotal;
		    }
		}

		public function get($nom_attribut){
			if property_exists(this, $nom_attribut){
				return this->$nom_attribut;
			}
			return false;
		}

		public function set($nom_attribut, $modif){
			if property_exists(this, $nom_attribut){
				this.$nom_attribut = $modif;
			}
			return false;

		}


		   public static function error($error) {
		      $displayError = $error;
		      $view = 'error';
		      $pagetitle= 'So\'Cap - Erreur';
		      require File::build_path(array('view', 'view.php'));
		   }


	}
>