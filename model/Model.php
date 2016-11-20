<?php
	require_once File::build_path(array('config', 'Conf.php'));

	class Model {
		public static $pdo;
		
		public static function Init() {
			//Récupération des valeurs depuis la classe Conf.
			$hostname = Conf::getHostname();
			$database_name = Conf::getDatabase();
			$login = Conf::getLogin();
			$password = Conf::getPassword();

			try {
				self::$pdo = new PDO("mysql:host=$hostname;dbname=$database_name",$login,$password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
				self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch(PDOException $e) {
				if (Conf::getDebug()) {
					echo $e->getMessage();
				} else {
					echo "Une erreur est survenue ! Merci de réessayer plus tard";
				}
				die();
			}
		}

		public static function selectAll() {
			$table_name = static::$tableName;
			$class_name = 'Model'.ucfirst(static::$object);
			try {
			  $sql = "SELECT * FROM `".$table_name."`";
			  $rep = Model::$pdo->query($sql);
			  $rep->setFetchMode(PDO::FETCH_CLASS, $class_name);
			  $results = $rep->fetchAll();
			  return $results;
			} catch(PDOException $e) {
			  if (Conf::getDebug()) {
			    echo $e->getMessage();
			  } else {
			    echo 'Une erreur est survenue <a href="index.php"> retour a la page d\'accueil </a>';
			  }
			  die();
			}
		}

		/*
			Fonction générique
			$data = les colonnes de la table avec les valeurs associées
			$typeReturn = NULL par défaut, ou id, si id, retourne le dernier id ajouté
		*/
		public static function save($data, $typeReturn = NULL) {
			try {
				$sql = 'INSERT INTO `'.static::$tableName.'` VALUES (';

				foreach ($data as $key => $value) {
					$sql .= ':'.$key.',';
				}

				$sql = substr($sql, 0, -1);
				$sql .= ')';

				$add = Model::$pdo->prepare($sql);
				$add->execute($data);
				if($typeReturn == 'id') {
					$lastId = Model::$pdo->lastInsertId();
					return $lastId;
				} else {
					return true;
				}
			} catch(PDOException $e) {
				if (Conf::getDebug()) {
					echo $e->getMessage();
				}
				return false;
				die();
			}
		}

		public static function select($data) {
			$table_name = static::$tableName;
			$class_name = 'Model'.ucfirst(static::$object);
			$primary_key = static::$primary;
			try {
				$sql = "SELECT * from `".$table_name."` WHERE `".$primary_key."` = :".$primary_key."";
				$req_generique = Model::$pdo->prepare($sql);

				$values = array(
					$primary_key => htmlspecialchars($data)
				);

				$req_generique->execute($values);
				$req_generique->setFetchMode(PDO::FETCH_CLASS, $class_name);
				$tab_gen = $req_generique->fetchAll();

				if(empty($tab_gen)) {
					return false;
				} else {
					return $tab_gen[0];
				}
			} catch(PDOException $e) {
				if (Conf::getDebug()) {
					echo $e->getMessage();
				} else {
					echo 'Une erreur est survenue <a href="index.php"> retour a la page d\'accueil </a>';
				}
				die();
			}
		}

		public static function selectCustom($cle, $data) {
			$table_name = static::$tableName;
			$class_name = 'Model'.ucfirst(static::$object);
			try {
				$sql = "SELECT * from `".$table_name."` WHERE `".$cle."` = :".$cle."";
				$req_generique = Model::$pdo->prepare($sql);

				$values = array(
					$cle => $data
				);

				$req_generique->execute($values);
				$req_generique->setFetchMode(PDO::FETCH_CLASS, $class_name);
				$tab_gen = $req_generique->fetchAll();
				return $tab_gen;
			} catch(PDOException $e) {
				if (Conf::getDebug()) {
					echo $e->getMessage();
				} else {
					echo 'Une erreur est survenue <a href="index.php"> retour a la page d\'accueil </a>';
				}
				die();
			}
		}

		public static function delete($data) {
			$table_name = static::$tableName;
			$primary_key = static::$primary;
			try {
			  $sql = "DELETE FROM `".$table_name."` WHERE `".$primary_key."` = :".$primary_key."";
			  $rep = Model::$pdo->prepare($sql);
			  $values = array(
			  	$primary_key => htmlspecialchars($data)
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

		// Factoriser le get et le set dans Model.php
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

		//Gestion des erreurs pour tous les modèles !!
		public static function error($error) {
			$displayError = $error;
			$view = 'error';
			$pagetitle= 'So\'Cap - Erreur';
			$powerNeeded = true;
			require File::build_path(array('view', 'view.php'));
		}
	}

	Model::Init();
?>