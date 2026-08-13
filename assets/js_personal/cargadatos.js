function prueba()
{
	alert("hola");
}

function cargarInformacionCp(pCp, pEstados, pMunicipios, pCiudades, pColonias)
{
	var sendDatos = {"cp":pCp};
	var data = ajaxInsert(sendDatos, controllerCatalogos + "getInformacionByCp", "json");
	if(data.length>0){

		pEstados.html("");
		pMunicipios.html("");
		pCiudades.html("");		
		pColonias.html("");	

		//alert(data);

		for(var x=0; x<data.length; x++)
		{			
			var optionEstados = $('<option />');
			var optionMunicipios = $('<option />');
			var optionCiudades = $('<option />');
			var optionColonias = $('<option />');

			if(pEstados.filter(function() { return $(this).text() == data[x].estado.toUpperCase(); }).length==0)
        	{
        		optionEstados.attr('value', data[x].idEstado+':'+data[x].estado.toUpperCase()).text(data[x].estado.toUpperCase());
            	pEstados.append(optionEstados);
        	}

        	if(pMunicipios.filter(function() { return $(this).text() == data[x].municipio.toUpperCase(); }).length==0)
        	{
        		optionMunicipios.attr('value', data[x].idMunicipio+':'+data[x].municipio.toUpperCase()).text(data[x].municipio.toUpperCase());
            	pMunicipios.append(optionMunicipios);
        	}

        	if(pCiudades.filter(function() { return $(this).text() == data[x].ciudad.toUpperCase(); }).length==0)
        	{
        		optionCiudades.attr('value', data[x].idCiudad+':'+data[x].ciudad.toUpperCase()).text(data[x].ciudad.toUpperCase());
            	pCiudades.append(optionCiudades);
        	}

        	if(pColonias.filter(function() { return $(this).text() == data[x].asentamiento.toUpperCase(); }).length==0)
        	{
        		optionColonias.attr('value', data[x].idAsentamiento+':'+data[x].asentamiento2.toUpperCase()).text(data[x].asentamiento2.toUpperCase());
            	pColonias.append(optionColonias);
        	}
		}

		return true;

	}
	else
	{
		return false;
	}
}

//########## ESTA FUNCION CARGA LOS ESTADOS EN COMBOBOX ################################
function cargarEstados(pCombobox)
{	    	
	//var data = ajaxInsert(sendDatos, controllerCatalogos + "getMunicipios", "text");
	var data = ajaxInsert("", controllerCatalogos + "getEstados", "json");
	//pCombobox.load(controllerCatalogos + "getMunicipios/" + pIdEstado);
	pCombobox.html("");
	for(var x=0; x<data.length; x++)
	{
		var optionEstados = $('<option />');
		optionEstados.attr('value', data[x].idEstado+':'+data[x].estado.toUpperCase()).text(data[x].estado.toUpperCase());
        pCombobox.append(optionEstados);
	}
	/*var data = ajaxInsert(sendDatos, controllerCatalogos + "getMunicipios", "json");    
	if(data.length > 0)
	{
	    pCombobox.html("");
	    $(data).each(function(){                 
            var option = $('<option />');
            option.attr('value', this.idMunicipio+':'+this.municipio).text(this.municipio);

            pCombobox.append(option);
        });
	} */
}

//########## ESTA FUNCION CARGA LOS MUNICIPIOS EN COMBOBOX ################################
function cargarMunicipios(pIdEstado, pCombobox)
{
	pIdEstado = pIdEstado.split(":");   
    pIdEstado = pIdEstado[0];    
	var sendDatos = {"idEstado":pIdEstado};	
	//var data = ajaxInsert(sendDatos, controllerCatalogos + "getMunicipios", "text");
	var data = ajaxInsert(sendDatos, controllerCatalogos + "getMunicipios", "text");
	//pCombobox.load(controllerCatalogos + "getMunicipios/" + pIdEstado);

	pCombobox.html(data);	
	/*var data = ajaxInsert(sendDatos, controllerCatalogos + "getMunicipios", "json");    
	if(data.length > 0)
	{
	    pCombobox.html("");
	    $(data).each(function(){                 
            var option = $('<option />');
            option.attr('value', this.idMunicipio+':'+this.municipio).text(this.municipio);

            pCombobox.append(option);
        });
	} */
}

//########## ESTA FUNCION CARGA LAS CIUDADES EN COMBOBOX ################################
function cargarCiudades(pIdEstado, pIdMunicipio, pCombobox)
{	
	pIdEstado = pIdEstado.split(":");   
    pIdEstado = pIdEstado[0];

    pIdMunicipio = pIdMunicipio.split(":");   
    pIdMunicipio = pIdMunicipio[0];    

    //pCombobox.load(controllerCatalogos + "getCiudades/" + pIdEstado + "/" + pIdMunicipio);

	var sendDatos = {"idEstado":pIdEstado, "idMunicipio":pIdMunicipio};
	var data = ajaxInsert(sendDatos, controllerCatalogos + "getCiudades", "text");    
	pCombobox.html(data);
	/*if(data.length > 0)
	{
	    pCombobox.html("");
	    $(data).each(function(){                 
            var option = $('<option />');
            option.attr('value', this.idCiudad+':'+this.ciudad).text(this.ciudad);

            pCombobox.append(option);
        });
	} */
}

//########## ESTA FUNCION CARGA LAS COLONIAS EN COMBOBOX ################################
function cargarColonias(pIdEstado, pIdMunicipio, pIdCiudad, pCombobox)
{
	pIdEstado = pIdEstado.split(":");   
    pIdEstado = pIdEstado[0];

    pIdMunicipio = pIdMunicipio.split(":");   
    pIdMunicipio = pIdMunicipio[0];

    pIdCiudad = pIdCiudad.split(":");   
    pIdCiudad = pIdCiudad[0];

	var sendDatos = {"idEstado":pIdEstado, "idMunicipio":pIdMunicipio, "idCiudad":pIdCiudad};

	var data = ajaxInsert(sendDatos, controllerCatalogos + "getColonias", "text");    
	pCombobox.html(data);
	/*if(data.length > 0)
	{
	    pCombobox.html("");	    
	    $(data).each(function(){	            
            var option = $('<option />');
            option.attr('value', this.id+':'+this.asentamiento).text(this.asentamiento);

            pCombobox.append(option);
        });        	    
	} */
}

//########## ESTA FUNCION DEVUELVE EL PRECIO DEL MATERIAL SEGUN SU ID ################################
function getPrecioMaterial(pId)
{
	var sendDatos = {"id":pId};

	var data = ajaxInsert(sendDatos, controllerCatalogos + "getInfoMaterial", "json");    
	if(data != undefined)
	{
	    return data["precio"];
	} 
	else
	{
		return 0;
	}
}