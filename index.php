<?php 
	session_start(); // Session ici, car page parente de TOUTES
	$DS = DIRECTORY_SEPARATOR;
	$ROOT_FOLDER = __DIR__.$DS;
	require_once $ROOT_FOLDER.'lib/File.php';
	require_once File::build_path(array('controller', 'routeur.php'));

	// Pour inspiration design : http://seeninmovies.lukasbargoin.fr/index.php
?>