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

$(".favori").on('click', function(e) {
	e.preventDefault();
	var dataFavori = $(this).attr('data-favori');
	var dataProduit = $(this).attr('data-produit');
	// Ajax
	if(dataFavori == 1) {
		var data = 'idProduit='+dataProduit+'&favori=0';
	} else {
		var data = 'idProduit='+dataProduit+'&favori=1';
	}

	$.ajax({
		type: "POST",
		url: "lib/ajax/admin-favori.php",
		data: data,
		dataType: 'json',
		success: function(retour) {
			console.log(retour);
			$('.info').html(retour.message).fadeIn("slow");
			if(retour.result == true) {
				$('.favori[data-produit="'+retour.idProduit+'"]').attr('data-favori', retour.newFavori);
				$('.favori[data-produit="'+retour.idProduit+'"]').html(retour.newIcon);
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

$(".stockBtn, .editBtn, .deleteBtn").on("click", function(e){
	e.preventDefault();
	var action = $(this).attr('data-action');

	if(action != undefined) {
		var idProduit = $(this).attr('data-produit');
		if(action == "stockForm") {
			var urlToPost  = "lib/ajax/admin-getStockProduit.php";
			var titleModal = "Modifier le stock d'un produit";
		} else if(action == "editForm") {
			var urlToPost = "lib/ajax/admin-getProduitForm.php";
			var titleModal = "Modifier un produit";
		} else if(action == "deleteForm") {
			var urlToPost = "lib/ajax/admin-deleteProduitForm.php";
			var titleModal = "Supprimer un produit";
		} else {
			urlToPost = null;
		}
		$(".modal-form-content").html('<div class="loader"></div><br/><div class="text-center"><em>Chargement en cours</em></div>');
		$("#modalProduit .modal-title").html(titleModal);
		$('#modalProduit').modal('toggle');

		$.ajax({
			type: "POST",
			url: urlToPost,
			data: 'idProduit='+idProduit,
			dataType: 'json',
			success: function(retour) {
				$(".modal-form-content").html(retour.message);
			}
		});
	}
});