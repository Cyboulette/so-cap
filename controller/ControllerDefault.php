<?php 
//require_once File::build_path(array('model', 'ModelUtilisateur.php'));

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
}
?>