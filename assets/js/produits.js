$("#navigationProduits ul li a").on('click', function(e) {
	e.preventDefault();
	var datatri = $(this).parent().attr('data-tri');
	var isActive = $(this).parent().hasClass('active');
	if(datatri != undefined) {
		// Ajax
		var data = 'optionTri='+datatri;
		$.ajax({
			type: "POST",
			url: "lib/ajax/triProduits.php",
			data: data,
			dataType: 'json',
			success: function(retour) {
				if(isActive != true) {
					$("#navigationProduits .boutonsHaut li[class='active']").removeClass('active');
					$("#navigationProduits .boutonsHaut li[data-tri='"+datatri+"']").addClass('active');
				}
				if(retour.result == true) {
					//console.log(retour.produits);
					$(".displayProduits").html(retour.produits);
				} else {
					$(".displayProduits").html('<div class="alert alert-danger">Aucun produit ne correspond à vos critères de recherche</div>');
				}
			},
			error: function(retour) {
				console.log(retour);
			}
		});
	}
});