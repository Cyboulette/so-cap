<?php
	if(isset($displayError) && !empty($displayError)) {
		echo '<h2>'.$error.'</h2>';
	} else {
		echo '<h2>Erreur inconnue</h2>';
	}
?>