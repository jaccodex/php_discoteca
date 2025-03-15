$(document).ready(function(){

	$('table.actualizaciones a').mouseover(function(){

			var ancho = $(this).width();
			var posicion =$(this).position();
			var newPosicion = posicion.left + ancho + 2;
	
			$(this).append('<div id="tipContainer"></div>');
			
			var contenido = '<div id="tipContent">';
			contenido+='<b>Id.Disco:</b>' + $(this).attr('rel') + '<br/>';
			contenido+='<b>posicion:</b>' + newPosicion;
			contenido+='</div>';
			
			
			$('#tipContainer').html(contenido);
			//$('#tipContainer').css('left',ancho+'px');
			$('#tipContainer').css('left', newPosicion + 'px');
			
			});

	

	$('a').mouseout(function(){
								
			$('#tipContainer').remove();
			
			});

});