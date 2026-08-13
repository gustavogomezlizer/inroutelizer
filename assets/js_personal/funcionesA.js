function prueba2()
{
	alert("hola");
}

//########## AJAX SINCRONO, DE ESTA MANERA AJAX ESPERA A TENER UNA RESPUESTA PARA RETONARLA ################################
function ajaxInsert_A(pDatos, pController, pFormatoRespuesta)
{
	 var res = "";
	  $.ajax({
	          data: {"datos" : pDatos},
	          type: "POST",
	          dataType: pFormatoRespuesta,
	          async: false,
	          url: pController,
	  }).done(function( data, textStatus, jqXHR ) {
	      res = data;      
	   }).fail(function( jqXHR, textStatus, errorThrown ) {
	       res = textStatus;       
	  });

	   return res;   
}





//########## ESTA FUNCION SIRVE PARA HACER EL COLLASEP DE LOS PANELES  ################################
function encontrarDiv_A(pElemento, pId)
{
	var pos = 0;
	var elementos = [];
	elementos[pos] = pId;

	pElemento.each(function(index, el) {		
		var e = $(this).find(".panel-body");			
		var id = $(this).attr("id")
		var i = $(this).find(".minimizar");

		if(id != undefined)
		{						
			if(id != pId)			
			{											
				if (i.children().hasClass("fa-minus")) 
				{
					i.children(".fa-minus").removeClass("fa-minus").addClass("fa-plus");					
					e.slideUp();
				}				
			}				
		}				
	});
}

//########## ESTA FUNCION OCULTA/MUESTRA EL PANEL AL HACER CLICK ################################
function ocultarPanel_A(pElemento, pContainer)
{
	pElemento.on('click', '.minimizar', function() {									

		encontrarDiv(pContainer, pElemento.attr("id"));	

		var panel = $(this).parent().parent().parent().find('[class*="panel-body"]');		
		if ($(this).children().hasClass("fa-plus")) 
		{				
			$(this).children(".fa-plus").removeClass("fa-plus").addClass("fa-minus");
			panel.slideDown();
		}
		else
		{
			$(this).children(".fa-minus").removeClass("fa-minus").addClass("fa-plus");
			panel.slideUp();
		}						
	});
}

//########## ESTA FUNCION RETORNA UN STRING A FORMATO MONEDA  ################################
var formatter = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'USD',
  minimumFractionDigits: 2,
  // the default value for minimumFractionDigits depends on the currency
  // and is usually already 2
});


function soloNumerosC1DecComa(e,elID)
    {
        // capturamos la tecla pulsada

        var teclaPulsada=window.event ? window.event.keyCode:e.which;
 
        // capturamos el contenido del input
        var valor=document.getElementById(elID).value;
        var total=valor.length;
        
        var punto=valor.indexOf(".");
        //alert("Total: " +total+ " Punto: "+punto);
        var pos=total-punto;

        // 45 = tecla simbolo menos (-)
        // Si el usuario pulsa la tecla menos, y no se ha pulsado anteriormente
        // Modificamos el contenido del mismo añadiendo el simbolo menos al
        // inicio
/*        if(teclaPulsada==45 && valor.indexOf("-")==-1)
        {
            document.getElementById("inputNumero").value="-"+valor;
        }*/
 
        // 13 = tecla enter
        // 46 = tecla punto (.)
        // Si el usuario pulsa la tecla enter o el punto y no hay ningun otro
        // punto
        //if(teclaPulsada==13 || (teclaPulsada==46 && valor.indexOf(".")==-1))
        if(teclaPulsada==46 && valor.indexOf(".")==-1 )
        {
            return true;
        }
        if(teclaPulsada==44)
        {
            return true;
        }
        if(teclaPulsada==127){
            return true;
        }
         if(teclaPulsada==37){
            return true;
        }
         if(teclaPulsada==39){
            return true;
        }
        if (valor.indexOf(".")==-1){
            return /\d/.test(String.fromCharCode(teclaPulsada));
        }
        else {
            if ((/\d/.test(String.fromCharCode(teclaPulsada))) && (pos<2))
            {
                return true;
            }
            else {
                return false;
            }
         }
 
        // devolvemos true o false dependiendo de si es numerico o no
        //return /\d/.test(String.fromCharCode(teclaPulsada));
    }

    function soloNumerosC2DecComa(e,elID)
    {
        // capturamos la tecla pulsada

        var teclaPulsada=window.event ? window.event.keyCode:e.which;
 
        // capturamos el contenido del input
        var valor=document.getElementById(elID).value;
        var total=valor.length;
        
        var punto=valor.indexOf(".");
        //alert("Total: " +total+ " Punto: "+punto);
        var pos=total-punto;

        // 45 = tecla simbolo menos (-)
        // Si el usuario pulsa la tecla menos, y no se ha pulsado anteriormente
        // Modificamos el contenido del mismo añadiendo el simbolo menos al
        // inicio
/*        if(teclaPulsada==45 && valor.indexOf("-")==-1)
        {
            document.getElementById("inputNumero").value="-"+valor;
        }*/
 
        // 13 = tecla enter
        // 46 = tecla punto (.)
        // Si el usuario pulsa la tecla enter o el punto y no hay ningun otro
        // punto
        //if(teclaPulsada==13 || (teclaPulsada==46 && valor.indexOf(".")==-1))
        if(teclaPulsada==46 && valor.indexOf(".")==-1)
        {
            return true;
        }
        if(teclaPulsada==44)
        {
            return true;
        }
                if(teclaPulsada==127){
            return true;
        }
         if(teclaPulsada==37){
            return true;
        }
         if(teclaPulsada==39){
            return true;
        }
        if (valor.indexOf(".")==-1){
            return /\d/.test(String.fromCharCode(teclaPulsada));
        }
        else {
            if ((/\d/.test(String.fromCharCode(teclaPulsada))) && (pos<3))
            {
                return true;
            }
            else {
                return false;
            }
         }
 
        // devolvemos true o false dependiendo de si es numerico o no
        //return /\d/.test(String.fromCharCode(teclaPulsada));
    }
//Esta funcion acepta numeros decimales valida que solo haya 1 punto
function soloNumerosC1Dec(e,elID)
    {
        // capturamos la tecla pulsada

        var teclaPulsada=window.event ? window.event.keyCode:e.which;
 
        // capturamos el contenido del input
        var valor=document.getElementById(elID).value;
        var total=valor.length;
        
        var punto=valor.indexOf(".");
        //alert("Total: " +total+ " Punto: "+punto);
        var pos=total-punto;

        // 45 = tecla simbolo menos (-)
        // Si el usuario pulsa la tecla menos, y no se ha pulsado anteriormente
        // Modificamos el contenido del mismo añadiendo el simbolo menos al
        // inicio
/*        if(teclaPulsada==45 && valor.indexOf("-")==-1)
        {
            document.getElementById("inputNumero").value="-"+valor;
        }*/
 
        // 13 = tecla enter
        // 46 = tecla punto (.)
        // Si el usuario pulsa la tecla enter o el punto y no hay ningun otro
        // punto
        //if(teclaPulsada==13 || (teclaPulsada==46 && valor.indexOf(".")==-1))
        if(teclaPulsada==46 && valor.indexOf(".")==-1)
        {
            return true;
        }
      if(teclaPulsada==127){
            return true;
        }
         if(teclaPulsada==37){
            return true;
        }
         if(teclaPulsada==39){
            return true;
        }
        if (valor.indexOf(".")==-1){
            return /\d/.test(String.fromCharCode(teclaPulsada));
        }
        else {
            if ((/\d/.test(String.fromCharCode(teclaPulsada))) && (pos<2))
            {
                return true;
            }
            else {
                return false;
            }
         }
 
        // devolvemos true o false dependiendo de si es numerico o no
        //return /\d/.test(String.fromCharCode(teclaPulsada));
    }
function soloNumerosC2Dec(e,elID)
    {
        // capturamos la tecla pulsada

        var teclaPulsada=window.event ? window.event.keyCode:e.which;
 
        // capturamos el contenido del input
        var valor=document.getElementById(elID).value;
        var total=valor.length;
        
        var punto=valor.indexOf(".");
        //alert("Total: " +total+ " Punto: "+punto);
        var pos=total-punto;

        // 45 = tecla simbolo menos (-)
        // Si el usuario pulsa la tecla menos, y no se ha pulsado anteriormente
        // Modificamos el contenido del mismo añadiendo el simbolo menos al
        // inicio
/*        if(teclaPulsada==45 && valor.indexOf("-")==-1)
        {
            document.getElementById("inputNumero").value="-"+valor;
        }*/
 
        // 13 = tecla enter
        // 46 = tecla punto (.)
        // Si el usuario pulsa la tecla enter o el punto y no hay ningun otro
        // punto
        //if(teclaPulsada==13 || (teclaPulsada==46 && valor.indexOf(".")==-1))
        if(teclaPulsada==46 && valor.indexOf(".")==-1)
        {
            return true;
        }
      if(teclaPulsada==127){
            return true;
        }
         if(teclaPulsada==37){
            return true;
        }
         if(teclaPulsada==39){
            return true;
        }
        if (valor.indexOf(".")==-1){
            return /\d/.test(String.fromCharCode(teclaPulsada));
        }
        else {
            if ((/\d/.test(String.fromCharCode(teclaPulsada))) && (pos<3))
            {
                return true;
            }
            else {
                return false;
            }
         }
 
        // devolvemos true o false dependiendo de si es numerico o no
        //return /\d/.test(String.fromCharCode(teclaPulsada));
    }
    function soloNumerosC4Dec(e,elID)
    {
        // capturamos la tecla pulsada
        
        var teclaPulsada=window.event ? window.event.keyCode:e.which;
 
        // capturamos el contenido del input
        var valor=document.getElementById(elID).value;
        var total=valor.length;
        
        var punto=valor.indexOf(".");
        //alert("Total: " +total+ " Punto: "+punto);
        var pos=total-punto;

        // 45 = tecla simbolo menos (-)
        // Si el usuario pulsa la tecla menos, y no se ha pulsado anteriormente
        // Modificamos el contenido del mismo añadiendo el simbolo menos al
        // inicio
/*        if(teclaPulsada==45 && valor.indexOf("-")==-1)
        {
            document.getElementById("inputNumero").value="-"+valor;
        }*/
 
        // 13 = tecla enter
        // 46 = tecla punto (.)
        // Si el usuario pulsa la tecla enter o el punto y no hay ningun otro
        // punto
        //if(teclaPulsada==13 || (teclaPulsada==46 && valor.indexOf(".")==-1))
        if(teclaPulsada==46 && valor.indexOf(".")==-1)
        {
            return true;
        }
    if(teclaPulsada==127){
            return true;
        }
         if(teclaPulsada==37){
            return true;
        }
         if(teclaPulsada==39){
            return true;
        }
        if (valor.indexOf(".")==-1){
            return /\d/.test(String.fromCharCode(teclaPulsada));
        }
        else {
            if ((/\d/.test(String.fromCharCode(teclaPulsada))) && (pos<5))
            {
                return true;
            }
            else {
                return false;
            }
         }
 
        // devolvemos true o false dependiendo de si es numerico o no
        //return /\d/.test(String.fromCharCode(teclaPulsada));
    }
    function soloNumerosC3Dec(e,elID)
    {
        // capturamos la tecla pulsada
        var teclaPulsada=window.event ? window.event.keyCode:e.which;
 
        // capturamos el contenido del input
        var valor=document.getElementById(elID).value;
        var total=valor.length;
        
        var punto=valor.indexOf(".");
        //alert("Total: " +total+ " Punto: "+punto);
        var pos=total-punto;

        // 45 = tecla simbolo menos (-)
        // Si el usuario pulsa la tecla menos, y no se ha pulsado anteriormente
        // Modificamos el contenido del mismo añadiendo el simbolo menos al
        // inicio
/*        if(teclaPulsada==45 && valor.indexOf("-")==-1)
        {
            document.getElementById("inputNumero").value="-"+valor;
        }*/
 
        // 13 = tecla enter
        // 46 = tecla punto (.)
        // Si el usuario pulsa la tecla enter o el punto y no hay ningun otro
        // punto
        //if(teclaPulsada==13 || (teclaPulsada==46 && valor.indexOf(".")==-1))
        if(teclaPulsada==46 && valor.indexOf(".")==-1)
        {
            return true;
        }
     if(teclaPulsada==127){
            return true;
        }
         if(teclaPulsada==37){
            return true;
        }
         if(teclaPulsada==39){
            return true;
        }
        if (valor.indexOf(".")==-1){
            return /\d/.test(String.fromCharCode(teclaPulsada));
        }
        else {
            if ((/\d/.test(String.fromCharCode(teclaPulsada))) && (pos<4))
            {
                return true;
            }
            else {
                return false;
            }
         }
 
        // devolvemos true o false dependiendo de si es numerico o no
        //return /\d/.test(String.fromCharCode(teclaPulsada));
    }
     
    
//Esta funcion acepta solo numeros
    function soloNumeros(e,eId)
    {
        // capturamos la tecla pulsada
        var teclaPulsada=window.event ? window.event.keyCode:e.which;
 
        // capturamos el contenido del input
        var valor=document.getElementById(eId).value;
         if(teclaPulsada==127){
            return true;
        }
         if(teclaPulsada==37){
            return true;
        }
         if(teclaPulsada==39){
            return true;
        }
        // 45 = tecla simbolo menos (-)
        // Si el usuario pulsa la tecla menos, y no se ha pulsado anteriormente
        // Modificamos el contenido del mismo añadiendo el simbolo menos al
        // inicio
/*        if(teclaPulsada==45 && valor.indexOf("-")==-1)
        {
            document.getElementById("inputNumero").value="-"+valor;
        }*/
 
        // 13 = tecla enter
        // 46 = tecla punto (.)
        // Si el usuario pulsa la tecla enter o el punto y no hay ningun otro
        // punto
        //if(teclaPulsada==13 || (teclaPulsada==46 && valor.indexOf(".")==-1))

 
        // devolvemos true o false dependiendo de si es numerico o no
        return /\d/.test(String.fromCharCode(teclaPulsada));
    }



