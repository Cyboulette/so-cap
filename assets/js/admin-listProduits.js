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
			$('.info').html(retour.message).fadeIn("slow", function(e){
				console.log("fade");
			});
			$('.favori[data-produit="'+retour.idProduit+'"]').attr('data-favori', retour.newFavori);
			$('.favori[data-produit="'+retour.idProduit+'"]').html(retour.newIcon);
			setTimeout(function(){
				$('.info').fadeOut();
			}, 1000);
		},
		error: function(retour) {
			console.log(retour);
		}
	});
});

$(".stock").on('click', function(e) {

});