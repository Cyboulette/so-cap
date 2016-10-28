<?php
	class Conf {

		// Configuration pour local chez Quentin
		static private $databases = array (
			'hostname' => 'localhost',
			'database' => 'railot',
			'login' => 'root',
			'password' => ''
		);

		
		//Configuration pour l'IUT
		/*static private $databases = array (
			'hostname' => 'infolimon',
			'database' => 'railot',
			'login' => 'railot',
			'password' => 'tomrailo'
		);*/

		static private $debug = true;

		static public $power = array(
			'admin' => 100,
			'membre' => 10,
			'visiteur' => 0
		);

		static public function getHostname() {
			return self::$databases['hostname'];
		}

		static public function getDatabase() {
			return self::$databases['database'];
		}

		static public function getLogin() {
			return self::$databases['login'];
		}

		static public function getPassword() {
			return self::$databases['password'];
		}

		static public function getDebug() {
			return self::$debug;
		}
	}
?>