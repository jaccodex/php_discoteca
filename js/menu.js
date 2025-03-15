$(document).ready(function(){

$('#menu h3').css('cursor','pointer');

$('#menu h3').next('ul').hide();

$('#menu h3').removeClass('desplegado');
$('#menu h3').addClass('plegado');

$('#menu li.actual').parent().show();//mostrar el ul de la opcion activa

$('#menu li.actual').parent().prev('h3')
.removeClass('plegado')
.addClass('desplegado');//el h3 de la opcion activa pasa de plegado a desplegado

//$('#menu li.actual').parent().children('h3').show();

$('#menu h3').click(		
		function(){
			if($(this).attr('class')=='plegado')
			{
				$(this).next('ul').slideDown();
			}
			else
			{
				$(this).next('ul').slideUp();
			}

			$(this).toggleClass('desplegado');
			$(this).toggleClass('plegado');
			
		});

});