window.onload=initAll;

function initAll(){
	var menu=document.getElementById("menu");
	
	var menuLinks=menu.getElementsByTagName("a");
	
	for (var i=0;i<menuLinks.length;i++){
		if(menuLinks[i].className.indexOf("menuLink")>-1){
			menuLinks[i].onclick=toggleMenu;
		}
	}
}

function toggleMenu(){

	var comienzoNombreMenu=this.href.lastIndexOf("/")+1;
	var finalNombreMenu=this.href.lastIndexOf(".");
	var nombreMenu=this.href.substring(comienzoNombreMenu,finalNombreMenu);
		
	var esteMenu=document.getElementById(nombreMenu).style;
	
	if (esteMenu.display=="block"){
		esteMenu.display="none";
	}
	else{
		esteMenu.display="block";	
	}
	
	return false;
	
}