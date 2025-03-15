$(document).ready(function(){

	//tratamiento de lista de temas
    $('#trackList').hide();
    $('#trackListHeader a').click(function(e){
        e.preventDefault();
        $('#trackList').toggle();
        
    });
	
	
	//tratamiento de cover	

	 $('a.linkThumbMini').fancybox({

		   // 'autoScale' : true,
			'type': 'image',
			'transitionIn' : 'elastic',
			'transitionOut' : 'elastic'
	});		   

	$(".link_mp3").click(function(e){
		e.preventDefault();
	
		$("#ventana_modal").html("<a id='close_modal' href='' title='Cerrar'></a>");
	
		populateModalMp3(this);
		$("#ventana_modal").show();
		$("#modal").show();
	});
	
	$(".link_ogg").click(function(e){
		e.preventDefault();
		
		$("#ventana_modal").html("<a id='close_modal' href='' title='Cerrar'></a>");
	
		populateModalOgg(this);
		$("#ventana_modal").show();
		$("#modal").show();
	});

	$("#close_modal").click(function(e){
		e.preventDefault();
	
		$("#ventana_modal").hide();
		$("#modal").hide();
	});

function populateModalMp3(link){
	var id_tema=$(link).attr("data-id_tema");
	//$("#ventana_modal").append("<p>el id del tema es " + id_tema + "</p>");
	var data={'id_tema':id_tema};
	//alert(id_tema);
	
	$.ajax({
	type:'POST',
	url:'mp3.php',
	data: data,
	success:function(response){
		$("#ventana_modal").append(response);
		}
	});	
	
};
	
function populateModalOgg(link){
	var id_tema=$(link).attr("data-id_tema");
	//$("#ventana_modal").append("<p>el id del tema es " + id_tema + "</p>");
	var data={'id_tema':id_tema};
	//alert(id_tema);

	$("#ventana_modal").html("<a id='close_modal' href='' title='Cerrar'></a>");

	$.ajax({
	type:'POST',
	url:'ogg.php',
	data: data,
	success:function(response){
	
		$("#ventana_modal").append(response);
		}
	});	
	
};	
	

});

