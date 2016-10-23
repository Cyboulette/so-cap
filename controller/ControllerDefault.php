<?php
class ControllerDefault {

	protected static $object = 'default';

	public static function index() {
		$view = 'index';
		$pagetitle = 'So\'Cap';
		require File::build_path(array('view', 'view.php'));
	}

	public static function error($error) {
		$displayError = $error;
		$view = 'error';
		$pagetitle= 'So\'Cap - Erreur';
		require File::build_path(array('view', 'view.php'));
	}

	public static function active($url) {
		$urlToExplode = $_SERVER['REQUEST_URI'];
		$urlExploded = explode("/", $_SERVER['REQUEST_URI']);
		if($urlExploded[2] == $url) {
			echo 'class="active"';
		}
	}
}
?>