function prueba2()
{
	alert("hola");
}

function AJAXRENOVADO(pDatos,pMethod,pResult, pAsyn, pUrl, pdone){
    $.ajax({
      data: {"datos" : pDatos},
      type: pMethod,
      dataType: pResult,
      async: pAsyn,
      url: pUrl,
    }).done(function( data, textStatus, jqXHR ) {
        pdone(data);
    }).fail(function( jqXHR, textStatus, errorThrown ) {
        //LOADERPAGE.hide();
    });
}

//########## AJAX SINCRONO, DE ESTA MANERA AJAX ESPERA A TENER UNA RESPUESTA PARA RETONARLA ################################
function ajaxInsert(pDatos, pController, pFormatoRespuesta)
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

//########## ESTA FUNCION RESETEA ELEMENTOS DE FORMULARIO  ################################
function resetForm(pForm)
{
	var inputType = '';      
	  
  	$(':input', pForm).each(function(){
	   var input = $(this);
	   if($(input).is('select'))
	   {
	      //input.val(1);          
	      //$(input)[0].selectedIndex = 0;	 	      
	      $('#'+$(input).attr("id")).prop('selectedIndex', 0).change()
	   }
	   else if($(input).is('input[type=text]') || $(input).is('input[type=number]'))
	   {
	      input.val("");                    
	   }
	   else if($(input).is('input:checkbox'))
	   {
	      inputType = 'checkbox';
	   }
	});
}


//########## ESTA FUNCION VALIDA LOS ELEMENTOS DE FORMULARIOS ################################
function validacionForm(pForm, pValidators)
{
	  var res = true;      
	  $(':input', pForm).each(function(index){  
	      var input = $(this);          
	        
	      if(pValidators.includes(input.attr('name')))
	      {
	          if(input.val() == "")
	          {                        
	              $(input).css('border-color', '#a94442');
	              res = false;
	              //alert(input.attr('name') + " Vacio");
	          }
	          else
	          {
	              $(input).css('border-color', '#67b168');
	          }
	      }          
	  });

	  return res;
}

//########## ESTA FUNCION SIRVE PARA HACER EL COLLASEP DE LOS PANELES  ################################
function encontrarDiv(pElemento, pId)
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
function ocultarPanel(pElemento, pContainer)
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

function mostrarAlert(pElement, pTipoAlert, pMensaje)
  {
        while(pElement.firstChild){
            pElement.removeChild(pElement.firstChild);
        }
        pElement.classList.remove("hideElements");
        var node = document.createElement("strong");
        var textnode = document.createTextNode(pMensaje);
        node.appendChild(textnode);
        pElement.html = "";
        if(pTipoAlert == "success")
        {      
          pElement.classList.remove("alert-warning");
          pElement.classList.add("alert-success");
          pElement.appendChild(node);    
        }
        else if(pTipoAlert == "warning")
        {
          pElement.classList.remove("alert-success");
          pElement.classList.add("alert-warning");
          pElement.appendChild(node);      
        }
  }

  function mostrarAlert2(pElement, pTipoAlert, pMensaje)
  {
        while(pElement.firstChild){
            pElement.removeChild(pElement.firstChild);
        }        
        
        pMensaje = pMensaje.split("|");
        pMensaje = pMensaje.slice(0, -1);        

    	var x = document.createElement("UL");        	

    	for(var item in pMensaje)
    	{
    		var y = document.createElement("LI");
	    	var t = document.createTextNode(pMensaje[item]);
	    	y.appendChild(t);
	    	x.appendChild(y);
    	}    	

        pElement.html = "";
        if(pTipoAlert == "success")
        {      
          pElement.classList.remove("alert-warning");
          pElement.classList.add("alert-success");
          pElement.appendChild(x);    
        }
        else if(pTipoAlert == "warning")
        {
          pElement.classList.remove("alert-success");
          pElement.classList.add("alert-warning");
          pElement.appendChild(x);      
        }
  }

  function onlyNumber(pElement)
	{
	    pElement.keypress(function (e) {
	        var regex = new RegExp('^\\d+$');
	        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
	        if (regex.test(str)) {
	            return true;
	        }
	        e.preventDefault();
	        return false;
	    });
	}

function onlyDecimal(pElement)
{
    pElement.keypress(function (e) {
        var regex = new RegExp(/^[-+]?\d*\.?\d*$/);
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if ( regex.test(str) ) {
            return true;
        }
        e.preventDefault();
        return false;
    });
}

function onlyLetter(pElement)
{
    pElement.keypress(function (e) {
        var regex = new RegExp(/^[a-zA-Z\s]*$/);
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if ( regex.test(str) ) {
            return true;
        }
        e.preventDefault();
        return false;
    });
}

    function onlyDecimales(e,elID, numDecimales)
    {
        // capturamos la tecla pulsada

        var teclaPulsada=window.event ? window.event.keyCode:e.which;

        // capturamos el contenido del input
        var valor=document.getElementById(elID).value;
        var total=valor.length;
        
        var punto=valor.indexOf(".");        
        var pos=total-punto;
        
        if(teclaPulsada==46 && valor.indexOf(".")==-1)
        {
            return true;
        }
        if (valor.indexOf(".")==-1){
            return /\d/.test(String.fromCharCode(teclaPulsada));
        }
        else {
            if ((/\d/.test(String.fromCharCode(teclaPulsada))) && (pos<(numDecimales+1)))
            {
                return true;
            }
            else {
                return false;
            }
         }
    }

    function isDecimalEvent(pElement, pNumDecimales)
    {
        var id = pElement.attr('id');        
        pElement.keypress(function(event){ 
            return onlyDecimales(event, id, pNumDecimales);
        });
    }

    function minmax(value, min, max) 
    {
        if(parseInt(value) < min || isNaN(parseInt(value))) 
            return 0; 
        else if(parseInt(value) > max) 
            return 100; 
        else return value;
    }

    function formatPhone(pElement)
    {
        pElement
        .on('keypress', function(e) {    
          var key = e.charCode || e.keyCode || 0;
          var phone = $(this);
          if (phone.val().length === 0) {
            phone.val(phone.val() + '(');
          }  
          // Auto-format- do not expose the mask as the user begins to type
          if (key !== 8 && key !== 9) {
            if (phone.val().length === 4) {
              phone.val(phone.val() + ')');
            }
            if (phone.val().length === 5) {
              phone.val(phone.val() + ' ');
            }
            if (phone.val().length === 9) {
              phone.val(phone.val() + '-');
            }
            if (phone.val().length >= 14) {
              phone.val(phone.val().slice(0, 13));
            }
          }

          // Allow numeric (and tab, backspace, delete) keys only
          return (key == 8 ||
            key == 9 ||
            key == 46 ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105));
        })

        .on('focus', function() {
          phone = $(this);

          if (phone.val().length === 0) {
            phone.val('(');
          } else {
            var val = phone.val();
            phone.val('').val(val); // Ensure cursor remains at the end
          }
        })

        .on('blur', function() {
          $phone = $(this);

          if ($phone.val() === '(') {
            $phone.val('');
          }
        });
    }

function onlyLetterAndNumbers(pElement)
{
    pElement.keypress(function (e) {
        var regex = new RegExp(/^[a-zA-Z\d]+$/);
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if ( regex.test(str) ) {
            return true;
        }
        e.preventDefault();
        return false;
    });
}

	function changeSelectAction(element, select)
    {        
        $("#" + element + " option").filter(function() {    
            return $(this).text() == select; 
        }).prop('selected', true).change();
    }

    function changeSelect(element, select)
    {              
        $("#" + element + " option").filter(function() {    
            return $(this).text() == select; 
        }).prop('selected', true);
    }
	
	function changeSelectByVal(element, select)
    {              
        $("#" + element).filter(function() { 
			var value = $(this).val();
			value = value.split(":");   
			value = value[0];
            alert(value);//return $(this).val() == select; 
        }).prop('selected', true);
    }

    function crearInputFile(pId, pName, span, placeholder, requerido=0)
    {
        var textolabel = placeholder;
        if(requerido==1)
        {
            textolabel = placeholder + '*';
        }

        textolabel = textolabel.toUpperCase();

        var cadena = "";
        cadena = "<div class='form-group " + span + "'>";
        cadena = cadena + "<label for='"+ pId + "'>" + textolabel + "</label>";
        cadena = cadena + "<input type='file' class='form-control input-sm' id='" + pId + "' name='" + pName + "'>";
        cadena = cadena + "</div>";

        return cadena;
    }
    function crearInputText(pId, pName, span, placeholder, pType, pAtributos='', requerido=0)
    {
        var textolabel = placeholder;
        if(requerido==1)
        {
            textolabel = placeholder + '*';
        }   

        textolabel = textolabel.toUpperCase();

        cadena = "<div class='form-group " + span + "'>";   
        cadena = cadena + "<label for='"+ pId + "'>" + textolabel + "</label>";
        cadena = cadena + "<input type='" + pType + "' class='form-control input-sm' id='" + pId + "' name='" + pName + "' placeholder='"+placeholder+"' "+pAtributos+">";    
        cadena = cadena + "</div>";
        return cadena;
    }

    function activaTab(tab){
            $('.nav-tabs a[href="#' + tab + '"]').tab('show');
    };

    var dialogAvisoGlobal = dialogAvisoGlobal || (function ($) {
    'use strict';
  // Creating modal dialog's DOM
    var $dialog = $(
    '<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
    '<div class="modal-dialog modal-m">' +
    '<div class="modal-content">' +
      '<div class="modal-header">' +
            '<h4 style="margin:0;">Mensaje del Sistema</h4>' +
            ' <button type="button" class="close" data-dismiss="modal" aria-label="Close"> ' +
                ' <span aria-hidden="true">&times;</span> ' +
            ' </button> ' +
        '</div>' +
      '<div class="modal-body">' +
        '<h5>Mensaje</h5>' +
      '</div>' +
      '<div class="modal-footer" align="center">' +
          ' <button type="button" class="btn btn-primary btn-block" data-dismiss="modal" aria-label="Close">CERRAR</button> ' +
      '</div>' +
    '</div></div></div>');

  return {
    show: function (message, bodyMessage, options) {
      // Assigning defaults
      if (typeof options === 'undefined') {
        options = {};
      }
      if (typeof message === 'undefined') {
        message = 'Loading';
      }
      var settings = $.extend({
        dialogSize: 'm',
        progressType: '',
        onHide: null // This callback runs after the dialog was hidden
      }, options);

      // Configuring dialog
      $dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
      $dialog.find('.progress-bar').attr('class', 'progress-bar');
      if (settings.progressType) {
        $dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
      }
      $dialog.find('h5').text(message);
      $dialog.find('.modal-content').attr('class', bodyMessage);
      //$dialog.find('h5').text(bodyMessage);
      // Adding callbacks
      if (typeof settings.onHide === 'function') {
        $dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
          settings.onHide.call($dialog);
        });
      }
      // Opening dialog
      $dialog.modal();
    },
    /**
     * Closes dialog
     */
    hide: function () {
      $dialog.modal('hide');
    }
  };

})(jQuery);

var dialogAddCatalogo1 = dialogAddCatalogo1 || (function ($) {
    'use strict';

  // Creating modal dialog's DOM
    var $dialog = $(
    '<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
    '<div class="modal-dialog modal-m">' +
    '<div class="modal-content">' +
      '<div class="modal-header">' +          
          '<h4 class="modal-title">Mensaje del Sistema</h4>' +
          ' <button type="button" class="close" data-dismiss="modal" aria-label="Close"> ' +
              ' <span aria-hidden="true">&times;</span> ' +
          ' </button> ' +
      '</div>' +
      '<div class="modal-body">' +      
        '<label for="dialogAddCatalogo1txtDescripcion">Descripcion:</label>' +
        '<input type="text" class="form-control" id="dialogAddCatalogo1txtDescripcion">' +
      '</div>' +
      ' <div class="modal-footer"> ' +
        ' <button id="dialogAddCatalogo1btnGuardar" type="button" class="btn btn-primary">Guardar</button> ' +
        ' <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button> ' +
      ' </div> ' +
    '</div></div></div>');

  return {
    show: function (message, bodyMessage, options) {
      // Assigning defaults
      if (typeof options === 'undefined') {
        options = {};
      }
      if (typeof message === 'undefined') {
        message = 'Loading';
      }
      var settings = $.extend({
        dialogSize: 'm',
        progressType: '',
        onHide: null // This callback runs after the dialog was hidden
      }, options);

      // Configuring dialog
      $dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
      $dialog.find('.progress-bar').attr('class', 'progress-bar');
      if (settings.progressType) {
        $dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
      }
      $dialog.find('h4').text(message);
      $dialog.find('h5').text(bodyMessage);
      // Adding callbacks
      if (typeof settings.onHide === 'function') {
        $dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
          settings.onHide.call($dialog);
        });
      }
      // Opening dialog
      $dialog.modal();
    },
    /**
     * Closes dialog
     */
    hide: function () {
      $dialog.modal('hide');
    }
  };

})(jQuery);

var LOADERPAGE = LOADERPAGE || (function ($) {
    'use strict';

  // Creating modal dialog's DOM
  var $dialog = $(
    '<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
    '<div class="modal-dialog modal-m">' +
    '<div class="modal-content">' +
      '<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
      '<div class="modal-body">' +
        '<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>' +        
      '</div>' +      
    '</div></div></div>');

  return {
    /**
     * Opens our dialog
     * @param message Custom message
     * @param options Custom options:
     *          options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
     *          options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
     */
    show: function (message, options) {
      // Assigning defaults
      if (typeof options === 'undefined') {
        options = {};
      }
      if (typeof message === 'undefined') {
        message = 'Loading';
      }
      var settings = $.extend({
        dialogSize: 'm',
        progressType: '',
        onHide: null // This callback runs after the dialog was hidden
      }, options);

      // Configuring dialog
      $dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
      $dialog.find('.progress-bar').attr('class', 'progress-bar');
      if (settings.progressType) {
        $dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
      }
      $dialog.find('h3').text(message);
      // Adding callbacks
      if (typeof settings.onHide === 'function') {
        $dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
          settings.onHide.call($dialog);
        });
      }
      // Opening dialog
      $dialog.modal();
    },
    /**
     * Closes dialog
     */
    hide: function () {
      //$dialog.delay(1000).fadeOut(450);
      /*setTimeout(function(){
        $dialog.modal("hide");
      }, 1500);*/
      $dialog.modal('hide');
    }
  };

})(jQuery);

//########## ESTA FUNCION RETORNA UN STRING A FORMATO MONEDA  ################################
var formatter = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'USD',
  minimumFractionDigits: 2,
  // the default value for minimumFractionDigits depends on the currency
  // and is usually already 2
});

function DecimalesMiles(pElemento, pDecimals)
{
    $(pElemento).inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: pDecimals,
        autoGroup: true,
        prefix: '', //Space after $, this will not truncate the first character.
        rightAlign: true,
        oncleared: function () { self.Value(''); }
    });
}

function replaceAll(str, find, replace) {
        return str.replace(new RegExp(find, 'g'), replace);
}