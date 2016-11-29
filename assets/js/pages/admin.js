// Changer le code pour ne plus passer par lib/ajax

$(function(){
	$(".listProduitsTable").tablesorter({
		headers: {
			4: {
				sorter: false
			},
			6: {
				sorter: false
			}
		}
	});

	$(".listUsersTable").tablesorter({
		headers: {
			5: {
				sorter: false
			}
		}
	});
});

function changeFavori() {
	$(".favori").unbind('click').on('click', function(e) {
		e.preventDefault();
		var dataFavori = $(this).attr('data-favori');
		var dataProduit = $(this).parent().parent().attr('data-produit');
		// Ajax
		if(dataFavori == 1) {
			var data = 'idProduit='+dataProduit+'&favori=0';
		} else {
			var data = 'idProduit='+dataProduit+'&favori=1';
		}

		$.ajax({
			type: "POST",
			url: "index.php?controller=admin&action=changeFavori",
			data: data,
			dataType: 'json',
			success: function(retour) {
				$('.info').html(retour.message).fadeIn("slow");
				if(retour.result == true) {
					$('tr[data-produit="'+retour.idProduit+'"] td .favori').attr('data-favori', retour.newFavori);
					$('tr[data-produit="'+retour.idProduit+'"] td .favori').html(retour.newIcon);
				}
				setTimeout(function(){
					$('.info').fadeOut();
				}, 1000);
			},
			error: function(retour) {
				console.log(retour);
			}
		});
	});
}

function actionBtn() {
	$(".actionBtn").unbind('click').on("click", function(e){
		e.preventDefault();
		var action = $(this).attr('data-action');

		if(action != undefined) {
			// Gestion des produits
			var idProduit = $(this).parent().parent().attr('data-produit');
			var dataToPost = 'idProduit='+idProduit;
			if(action == "stockForm") {
				var titleModal = "Modifier le stock d'un produit";
			} else if(action == "editForm") {
				var titleModal = "Modifier un produit";
			} else if(action == "deleteForm") {
				var titleModal = "Supprimer un produit";
			} else if(action == "addProduitForm") {
				var titleModal = "Ajouter un produit";
				if(typeof dataPosted !== 'undefined') {
					// Si jamais on a déjà tenté d'envoyer le formulaire mais qu'il y avait une erreur on renvoit les données
					// On les encode en JSON pour pouvoir les transmettre correctement et en sécurité !
					dataToPost = 'idProduit=null'+"&dataPosted="+JSON.stringify(dataPosted);
				} else {
					// Sinon notre variable ne bouge pas
					dataToPost = 'idProduit=null';
				}
				// idProduit doit valoir null pour vérifier l'intégrité des données du côté du PHP (au cas ou un malin s'amsuserait à modifier le form)
			} else if(action == "manageCategForm") {
				var titleModal = "Gérer les catégories";
				dataToPost = 'idCategorie=null';
			} else if(action == "editCategorieForm") {
				var idCategorie = $(this).parent().parent().attr('data-categorie');
				var titleModal = "Editer une catégorie";
				dataToPost = 'idCategorie='+encodeURIComponent(idCategorie);
			} else if(action == "deleteCategorieForm") {
				var idCategorie = $(this).parent().parent().attr('data-categorie');
				var titleModal = "Supprimer une catégorie";
				dataToPost = 'idCategorie='+encodeURIComponent(idCategorie);
			} else if(action == "imagesForm") {
				var titleModal = "Gérer les visuels";
			} else if(action == "deleteImageForm") {
				var titleModal = "Supprimer un visuel";
				var idProduit = $(this).parent().parent().attr('data-produit');
				var idVisuel = $(this).parent().parent().attr('data-visuel');
				dataToPost = 'idProduit='+encodeURIComponent(idProduit)+'&idVisuel='+encodeURIComponent(idVisuel);

			// Gestion des utilisateurs
			} else if(action == "manageUserForm") {
				var typeAction = $(this).attr('data-type');
				if(typeAction == "edit") {
					var titleModal = "Editer un utilisateur";
					var idUser = $(this).parent().parent().attr('data-user');
					var adddData = null;
				} else if(typeAction == "add") {
					var titleModal = "Ajouter un utilisateur";
					var idUser = null;
					if(typeof dataPosted !== 'undefined') {
						// Si jamais on a déjà tenté d'envoyer le formulaire mais qu'il y avait une erreur on renvoit les données
						// On les encode en JSON pour pouvoir les transmettre correctement et en sécurité !
						var adddData = "&dataPosted="+JSON.stringify(dataPosted);
					}
				}
				var dataToPost = 'typeAction='+encodeURIComponent(typeAction)+'&idUser='+encodeURIComponent(idUser)+adddData;
			} else if(action == "deleteUserForm") {
				var titleModal = "Supprimer un utilisateur";
				var idUser = $(this).parent().parent().attr('data-user');
				var dataToPost = 'idUser='+idUser;
 			} else if(action == "manageRangsForm") {
				var titleModal = "Gérer les rangs";
				var dataToPost = 'idRang=null';
 			} else if(action == "editRangForm") {
				var idRang = $(this).parent().parent().attr('data-rang');
				var titleModal = "Editer un rang";
				var dataToPost = 'idRang='+encodeURIComponent(idRang);
			} else {
				action = null;
			}

			$(".modal-form-content").html('<div class="loader"></div><br/><div class="text-center"><em>Chargement en cours</em></div>');
			$("#modalAction .modal-title").html(titleModal);
			if(action != "editCategorieForm" && action != "deleteCategorieForm" && action != "deleteImageForm" && action != "editRangForm") {
				$('#modalAction').modal('toggle');
			}

			$.ajax({
				type: "POST",
				url: 'index.php?controller=admin&action='+action,
				data: dataToPost,
				dataType: 'json',
				success: function(retour) {
					console.log(retour);
					$(".modal-form-content").html(retour.message);
				},
				error: function(retour) {
					console.log(retour);
				}
			});
		}
	});
}

function init() {
	actionBtn();
	changeFavori();
}

init();