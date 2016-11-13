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
			var idProduit = $(this).parent().parent().attr('data-produit');
			var dataToPost = 'idProduit='+idProduit;
			if(action == "stockForm") {
				//var urlToPost  = "lib/ajax/admin-getStockProduit.php";
				var titleModal = "Modifier le stock d'un produit";
			} else if(action == "editForm") {
				//var urlToPost = "lib/ajax/admin-getProduitForm.php";
				var titleModal = "Modifier un produit";
			} else if(action == "deleteForm") {
				//var urlToPost = "lib/ajax/admin-deleteProduitForm.php";
				var titleModal = "Supprimer un produit";
			} else if(action == "addProduitForm") {
				//var urlToPost = "lib/ajax/admin-addProduitForm.php";
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
			} else if(action == "manageCateg") {
				var titleModal = "Gérer les catégories";
				//var urlToPost = "lib/ajax/admin-listCategories.php";
				dataToPost = 'idCategorie=null';
			} else if(action == "editCategorie") {
				var idCategorie = $(this).parent().parent().attr('data-categorie');
				var titleModal = "Editer une catégorie";
				//var urlToPost = "lib/ajax/admin-getCategorieForm.php";
				dataToPost = 'idCategorie='+encodeURIComponent(idCategorie);
			} else if(action == "deleteCategorie") {
				var idCategorie = $(this).parent().parent().attr('data-categorie');
				var titleModal = "Supprimer une catégorie";
				//var urlToPost = "lib/ajax/admin-deleteCategorie.php";
				dataToPost = 'idCategorie='+encodeURIComponent(idCategorie);
			} else {
				action = null;
			}
			
			$(".modal-form-content").html('<div class="loader"></div><br/><div class="text-center"><em>Chargement en cours</em></div>');
			$("#modalProduit .modal-title").html(titleModal);
			if(action != "editCategorie" && action != "deleteCategorie") {
				$('#modalProduit').modal('toggle');
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