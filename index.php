<?php 
	$DS = DIRECTORY_SEPARATOR;
	$ROOT_FOLDER = __DIR__.$DS;
	require_once $ROOT_FOLDER.'lib/File.php';
	require_once File::build_path(array('controller', 'routeur.php'));
?>