$(document).ready(function(){


$('#buscar').hide();

$('#banda').bind('keyup', function(){
	
	var data = 'banda='+$(this).val();
	
	$.ajax({
	type:"POST",
	url:"DisBusqueda_ajax.php",
	data:data,
	success:function(html) {
		$('.respuesta').html(html);
		$('.respuesta tr').bind('click', 
			  function(){
						$('input#id_grupo').attr('value', $(this).find('td.id_busqueda').text());
						$('input#banda').attr('value', $(this).find('td.banda_busqueda').text());
						});
		}
	});

	});

});