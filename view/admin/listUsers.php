<?php 
	require_once File::build_path(array('view', 'admin', 'menu.php'));
?>

<div class="container-fluid">
	<?php 
		if(isset($notif)) {
			echo '<div class="info notif">'.$notif.'</div>';
		}
	?>
	<div class="info"></div>

	<div class="form-group text-center">
		<btn class="btn btn-success btn-xs actionBtn" data-action="addUserForm"><i class="fa fa-plus" aria-hidden="true"></i> Ajouter un utilisateur</btn>
		<btn class="btn btn-warning btn-xs actionBtn" data-action="manageRangsForm"><i class="fa fa-cog" aria-hidden="true"></i> Gérer les rangs</btn>
		<a href="index.php?controller=admin&action=users" class="btn btn-xs btn-primary"><i class="fa fa-refresh" aria-hidden="true"></i> Rafraichir</a>
	</div>
	<div class="row">
		<div class="table-responsive">
			<table class="table table-hover listUsersTable">
				<thead>
					<tr>
						<th>ID Utilisateur</th>
						<th>Prénom</th>
						<th>Nom</th>
						<th>E-mail</th>
						<th>Rang</th>
						<th>Action</th>
					</tr>
				</thead>
	      		<tbody>
				<?php
				// $dataPosted correspond aux données envoyées dans un formulaire, très utile en cas d'erreur.
				if(isset($dataPosted)) {
					echo $dataPosted;
				}

				foreach ($tab_u as $u) {
					$idUser = $u->get('idUtilisateur');
					$prenom = $u->get('prenom');
					$nom = $u->get('nom');
					$email = $u->get('email');
					$rang = ModelRang::select($u->get('rang'));
				?>
	       		<tr data-user="<?=$idUser?>">
					<td><?=$idUser?></td>
					<td><?=$prenom?></td>
					<td><?=$nom?></td>
					<td><?=$email?></td>
					<!-- Oui c'est pas joli de mettre du CSS directement comme ça, mais c'est la seule solution pour afficher une couleur différente en fonction de celle stockée en BDD !! -->
					<td><span class="label" style="background-color: <?=$rang->get('color')?>"><?=$rang->get('label')?></span></td>
					<td>
						<btn class="btn btn-xs btn-warning actionBtn" data-action="editUserForm"><i class="fa fa-pencil" aria-hidden="true"></i> Editer</btn>
						<btn class="btn btn-xs btn-danger actionBtn" data-action="deleteUserForm"><i class="fa fa-trash" aria-hidden="true"></i> Supprimer</btn>
					</td>
	       		</tr>
			<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="modalAction" tabindex="-1" role="dialog" aria-labelledby="modalAction">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Chargement ...</h4>
			</div>
			<div class="modal-body modal-form-content">
				<div class="loader"></div>
				<br/>
				<div class="text-center"><em>Chargement en cours</em></div>
			</div>
		</div>
	</div>
</div>