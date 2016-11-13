/**
	Pourquoi mettre des function() ? ->
		Si l'on effectue un appel ajax qui a besoin de ré-utiliser le code JS parent, celui-ci n'est plus "actif" il faut donc le "ré-armer" en ré-exécutant les functions.
		Il faut donc dans le code html de retour exécuter un <script>function();</script> pour que l'html de retour puisse utiliser la function();

	Pourquoi .unbind ? ->
		A cause du "problème" ci-dessus, si l'on rappelle le code en exécutant un <script>function();</script> et que cette fonction bindait déjà un évenement (click, submit, hover etc.), il sera
		bindé autant de fois que d'appel de celui-ci.
		Le unbind, permet d'annuler l'écoute des précédents évenèments et de redémarrer à 0.
**/

function triProduits() {
	$("#navigationProduits ul li a").unbind('click').on('click', function(e) {
		e.preventDefault();
		var datatri = $(this).parent().attr('data-tri');
		var isActive = $(this).parent().hasClass('active');
		if(datatri != undefined) {
			// Ajax
			if(datatri == "categorie") {
				var categorieID = $(this).parent().attr('data-categorie');
				var data = 'optionTri='+datatri+'&categorieID='+categorieID;
			} else {
				var data = 'optionTri='+datatri;
			}

			$.ajax({
				type: "POST",
				url: "lib/ajax/triProduits.php",
				data: data,
				dataType: 'json',
				success: function(retour) {
					if(isActive != true) {
						$("#navigationProduits .boutonsHaut li[class='active']").removeClass('active');
						if(datatri == "categorie") {
							$("#navigationProduits .boutonsHaut li[data-categorie='"+categorieID+"']").addClass('active');
						} else {
							$("#navigationProduits .boutonsHaut li[data-tri='"+datatri+"']").addClass('active');
						}
					}

					$(".displayProduits").html('<div class="loader"></div>');
					$(".loader").fadeOut("slow", function(){
						if(retour.result == true) {
							$(".displayProduits").html(retour.produits);
							$(".displayProduits").prepend('<div class="container-fluid"><h4>'+retour.title+'</h4></div>');
						} else {
							$(".displayProduits").html('<div class="container-fluid"><div class="alert alert-danger">Aucun produit ne correspond à vos critères de recherche</div></div>');
						}
					});
				},
				error: function(retour) {
					console.log(retour);
				}
			});
		}
	});
}

function rechercheForm() {
	$("#rechercheForm").unbind('submit').on('submit', function(e) {
		e.preventDefault();
		var dataSearch = $(".searchText").val();
		
		if(dataSearch.length > 0) {
			var data = 'optionTri=searchText&text='+encodeURIComponent(dataSearch);
			$.ajax({
				type: "POST",
				url: "lib/ajax/triProduits.php",
				data: data,
				dataType: 'json',
				success: function(retour) {
					$(".displayProduits").html('<div class="loader"></div>');
					$(".loader").fadeOut("slow", function(){
						if(retour.result == true) {
							$(".displayProduits").html(retour.produits);
							$(".displayProduits").prepend('<div class="container-fluid"><h4>'+retour.title+'</h4></div>');
						} else {
							$(".displayProduits").html('<div class="container-fluid"><div class="alert alert-danger">Aucun produit ne correspond à vos critères de recherche</div></div>');
						}
					});
				},
				error: function(retour) {
					console.log(retour);
				}
			});
		}
	});
}

function modalActions() {
	$('.actionBtnPanier').unbind('click').on('click', function(e) {
		e.preventDefault();

		var idProduit = $(this).parent().parent().attr('data-produit');
		var action = $(this).attr('data-action');

		var data = 'idProduit='+encodeURIComponent(idProduit);

		if(action == "changeQuantite") {
			var actualQuantite = parseInt($(this).text());
			var nouvelleQuantite = prompt("Entrez la quantité désirée", actualQuantite);
			if($.isNumeric(nouvelleQuantite) && nouvelleQuantite >= 0) {
				data += '&quantite='+encodeURIComponent(nouvelleQuantite);
			}
		} else if(action == "addFromAjax") {
			var nbProduitsPanierActuel = parseInt($('.nbProduitsPanier').text());
			var idProduit = $(this).attr('data-produit');
			var quantite = $('.quantite').val();

			if(typeof quantite == "undefined") {
				quantite = 1;
			}

			var data = 'idProduit='+encodeURIComponent(idProduit)+'&quantite='+encodeURIComponent(quantite);
		}

		$.ajax({
			type: "POST",
			url: "index.php?controller=panier&action="+action,
			data: data,
			dataType: 'json',
			success: function(retour) {
				$('.info').html(retour.message).fadeIn("slow");

				if(retour.result == true) {
					$('.nbProduitsPanier').addClass('nbProduitsPanierNew');
					$('.nbProduitsPanier').text(retour.nbProduits);
					$('#panier .modal-body').html(retour.nouveauPanier.message);
					if(retour.nbProduits == 0) {
						$('.goPaiement').hide();
						$('.clearPanier').hide();
					} else {
						$('.goPaiement').show();
						$('.clearPanier').show();
					}
				}

				setTimeout(function(){
					$('.nbProduitsPanier').removeClass('nbProduitsPanierNew');
					$('.info').fadeOut();
				}, 1000);
			},
			error: function(retour) {
				console.log(retour);
			}
		});
	});
}

function init() {
	triProduits();
	rechercheForm();
	modalActions();

	var nbProduitsPanierActuel = parseInt($('.nbProduitsPanier').text());
	if(nbProduitsPanierActuel > 0) {
		$('.goPaiement').show();
		$('.clearPanier').show();
	} else {
		$('.goPaiement').hide();
		$('.clearPanier').hide();
	}
}

$(function() {
	init();
});