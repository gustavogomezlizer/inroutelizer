<?php 
$data['title']="LIZER Reportes-Corte";
/*$usuario=str_replace("%20", " ", $usuario);

$usuario=str_replace("%C3%B1", "ñ", $usuario);*/
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu");
/*$usuario=str_replace(".COMACONTROL.", ",", $usuario);
$ruta=str_replace(".COMACONTROL.", ",", $ruta);
$sucursal=str_replace(".COMACONTROL.", ",", $sucursal);
$usuario=str_replace("%20"," ",$usuario);

$usuario=str_replace("%C3%B1","ñ",$usuario);
*/
$total=0;
$cantidad=0;
//$corte=VERIFICARPERFILFUNCION("Reportes","hacerCorte",$this->session->userdata('perfilLIZER'));
//print_r($listaSucursales->result()); ?>

			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Reportes / Pedidos - Realizar Corte
									
									
								</small>
							</h1>
							<?php //echo "<br>".$sucursal; ?>
						</div><!-- /.page-header -->
						<!-- <div class="row">
							<div class="col-xs-12">
								<h5><strong>Filtros</strong></h5>
							</div>
						</div> -->
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								
								<div class="row"><!--  empieza div.row de la tabla clientes -->
									
									
									<div class="col-xs-2"><label for="">Fecha de Corte:</label><br>
									<input type="date" class="form-control" id="txtFecha" value="<?php echo $fecha; ?>">

										
									</div>
									<div class="col-xs-2"><br>

										<button class="btn btn-primary btnAceptar" id="btnAceptar">Aceptar</button>
									</div>
									
									</div>
									
									<div class="row"><div class="col-xs-12"><hr></div></div>
									
								
<!-- Modal -->
							<div id="modalDepurar" class="modal fade">
							          <div class="modal-dialog modal-sm">
							            <div class="modal-content">
							              <!-- dialog body -->
							              <div class="modal-header">
							              <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
							                  <h4>Corte de Pedidos.</h4>
							              
							                
							                
							                <div class="col-md-12 row">
							                       <h5>¿Hasta que fecha se hara el corte?</h5>
							                </div>
							               	<div class="col-md-12 row">
							               		<input type="date" name="txtFechaCorte" id="txtFechaCorte">
							               	</div>
							              </div>
							              <div class="modal-footer">
							                    
							                                      
							                    <button id="btnAceptarX" type="button" class="btn btn-success">REALIZAR</button><button  type="button" class="btn btn-danger" data-dismiss="modal">CERRAR</button>
							                </div>
							            </div>
							          </div>
							        </div>
								

							

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->


	<?php $this->load->view("vCopyright"); ?>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->
	<?php $this->load->view("vFooter"); ?>
		
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKYMP1l569OtfSqd4U2f_ysZuJHodabIU&region=GB"></script>
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			var cambio=0;
			var conroller="<?php echo CREPORTES(); ?>";
			
			function numberFormat(numero){
		        // Variable que contendra el resultado final
		        var resultado = "";
		 
		        // Si el numero empieza por el valor "-" (numero negativo)
		        if(numero[0]=="-")
		        {
		            // Cogemos el numero eliminando los posibles puntos que tenga, y sin
		            // el signo negativo
		            nuevoNumero=numero.substring(1);
		        }else{
		            // Cogemos el numero eliminando los posibles puntos que tenga
		            nuevoNumero=numero;
		        }
		// var nuevoNumero=numero;
		        // Si tiene decimales, se los quitamos al numero
		        if(numero.indexOf(".")>=0)
		            nuevoNumero=nuevoNumero.substring(0,nuevoNumero.indexOf("."));
		 
		        // Ponemos un punto cada 3 caracteres
		        for (var j, i = nuevoNumero.length - 1, j = 0; i >= 0; i--, j++)
		            resultado = nuevoNumero.charAt(i) + ((j > 0) && (j % 3 == 0)? ",": "") + resultado;
		 
		        // Si tiene decimales, se lo añadimos al numero una vez forateado con 
		        // los separadores de miles
		        if(numero.indexOf(".")>=0)
		            resultado+=numero.substring(numero.indexOf("."),numero.indexOf(".")+3);
		 
		        if(numero[0]=="-")
		        {
		            // Devolvemos el valor añadiendo al inicio el signo negativo
		            return "-"+resultado;
		        }else{
		            return resultado;
		        }
		    }
			
				
				
				
				
				/*termina la configuracion de #dinamyc-table*/
			
				/*empieza configuracion para ver mapas*/
				
				$(".verPedido1").click(function(event) {
					/* Act on the event */
					var id=$(this).attr("id").replace("VER1","");
					id=id.replace("VER2","");
					//$("#modalMapa").modal("show");
					var link="<?php echo CREPORTES(); ?>" + "verPedido/"+id;
				  	window.open(link,"_blank");
					//alert(id);
				});
				$(".cancelarPedido1").click(function(event) {
					/* Act on the event */
					var id=$(this).attr("id").replace("CANCEL1","");
					id=id.replace("CANCEL2","");
					//$("#modalMapa").modal("show");
					var link="<?php echo CREPORTES(); ?>" + "verPedido/"+id;
				  	$.post("<?php echo CREPORTES("eliminarPedido");?>", {id: id},function(data){ 
				  		window.location.href = "<?php echo CREPORTES(); ?>"+"listadoPedidos"+"/"+$("#txtFInicio").val()+"/"+$("#txtFFinal").val()+"/"+$("#cmbTipo").val()+"/"+$("#cmbUsuario").val()+"/"+$("#cmbSucursal").val();
				          });
						
				});
				$(".imprimirPedido1").click(function(event) {
					/* Act on the event */
					var id=$(this).attr("id").replace("IMPRIMIR1","");
					id=id.replace("IMPRIMIR2","");
					$(this).removeClass("red");
					$(this).addClass("green")
					//$("#modalMapa").modal("show");
					var link="<?php echo CREPORTES(); ?>" + "imprimirPedido/"+id;
				  	window.open(link,"_blank");
					//alert(id);
				});
					$("#btnAceptar").click(function(event) {
						/* Act on the event */
						//alert("Hola");
						$.post("<?php echo CREPORTES('hacerCorte');?>", {fecha: $("#txtFecha").val()},function(data){
											//alert(data);
											//alert(data);
										});
						//$("#modalDepurar").modal("hide");//
					});
					$(".btnActualizar").click(function(event) {
							/* Act on the event */
							location.reload();
						});
					$(".btnCorte").click(function(event) {
						/* Act on the event */
						$("#modalDepurar").modal("show");
					});
					$(".btnAcumulados").click(function(event) {
						/* Act on the event */
						//alert("Hola");
						$.post("<?php echo CREPORTES('leerAcumuladosJson');?>", {usuario: "usuario"},function(data){
											//alert(data);
											//alert(data);
										});

					/*	var link="<?php echo CREPORTES(); ?>" + "leerAcumuladosJson/";
				  			window.open(link,"_blank");*/
					});

				
			
			var myTable = 
				$('#dynamic-table')
				//.wrap("<div class='dataTables_borderWrap' />")   //if you are applying horizontal scrolling (sScrollX)
				.DataTable( {
					"language": {
				            "url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
				        },
				              "pageLength": -1,
				              "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
				              "order": [[0,"asc"]],
				              
				              
			    } );
			
				
				
				$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
				
				new $.fn.dataTable.Buttons( myTable, {
					buttons: [
					 /* {
						"extend": "colvis",
						"text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
						"className": "btn btn-white btn-primary btn-bold",
						columns: ':not(:first):not(:last)'
					  },*/
					  /*{
						"extend": "copy",
						"text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },*/
					  /*{
						"extend": "csv",
						"text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },*/

					  {
						"extend": "excel",
						"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Exportar a Excel Concentrado</span>",
						"className": "btn btn-white btn-primary btn-bold",
						"titleAttr": "EXCEL CONCENTRADO",
			            "title": 'Reporte de Pedidos - Concentrado',
			            "exportOptions": {
			                    columns: [ 0, 1, 2, 3, 4, 6, 7, 8 ]
			                }
					  }
					 /* {
						"extend": "pdf",
						"text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
						"className": "btn btn-white btn-primary btn-bold"
					  },*/
					  /*{
						"extend": "print",
						"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
						"className": "btn btn-white btn-primary btn-bold",
						autoPrint: false,
						message: 'This print was produced using the Print button for DataTables'
					  }	*/	  
					]
				} );
				myTable.buttons().container().appendTo( $('.tableTools-container') );
					$("#txtFInicio").change(function (event) {
						cambio=1;
						
					});
					$("#txtFFinal").change(function (event) {
						cambio=1;
					});
					$("#btnAplicar").click(function(event){
							var cadComa=/,/g;
							var tipo = $("#cmbTipo").val();

							var ruta=$("#cmbRuta").val().toString();
							 var usuario=$("#cmbUsuario").val().toString();
							 var sucursal=$("#cmbSucursal").val().toString();
							
					        var seleccionadosUsuario = usuario.replace(cadComa, "|");
					        var seleccionadosUsuario2 =  usuario.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosSucursal = sucursal.replace(cadComa, "|");
					        var seleccionadosSucursal2 =  sucursal.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosRuta = ruta.replace(cadComa, "|");
					        var seleccionadosRuta2 =  ruta.replace(cadComa, ".COMACONTROL.");
												$("#lblTotPedidos").html("<?php echo FORMATO_DINERO($total); ?>");
						                   		 $("#lblNumPedidos").html("<?php echo $cantidad; ?>");
					        $.post("<?php echo CREPORTES('postTotalesPedidos');?>", {usuario: usuario, sucursal: sucursal, tipo: tipo, fechaI: $("#txtFInicio").val(), fechaF: $("#txtFFinal").val(), ruta: ruta},function(data){
											//alert(data);
												var losdatos=data.split("-");
													$("#lblTotPedidos").html(losdatos[1]);
						                   		 $("#lblNumPedidos").html(losdatos[0]);
										});
						if(cambio==0){

							 
					       
							
							  if( tipo.includes("TODOS") ){
			                    myTable.column(1).search("").draw();
			                    
			                  }
			                  else
			                  {

			                    myTable.column(1).search(tipo, true, false ).draw();  
			                  }
			                 
			               if( seleccionadosUsuario.includes("TODOS") ){
			                    myTable.column(6).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(6).search(seleccionadosUsuario, true, false ).draw();  
			                  }
			                  if( seleccionadosSucursal.includes("TODOS") ){
			                    myTable.column(8).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(8).search(seleccionadosSucursal, true, false ).draw();  
			                  }
			                   if( seleccionadosRuta.includes("TODOS") ){
			                    myTable.column(7).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(7).search(seleccionadosRuta, true, false ).draw();  
			                  }
						}
						else{
							window.location.href = "<?php echo CREPORTES(); ?>"+"listadoPedidos"+"/"+$("#txtFInicio").val()+"/"+$("#txtFFinal").val()+"/"+$("#cmbTipo").val()+"/"+seleccionadosUsuario2+"/"+seleccionadosSucursal2;
						}
						
					});
				
				$(".btnSacarTabla").click(function(event) {
					var link="<?php echo CREPORTES(); ?>" + "verPedidos/"+$("#txtFInicio").val()+"/"+$("#txtFFinal").val();
				  		window.open(link,"_blank");
				});
				var elusuario="<?php echo $usuario; ?>";
							//alert(elusuario);
							var arrayUsuario=elusuario.split(",");

							for (var i = 0; i < arrayUsuario.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbUsuario option[value='"+arrayUsuario[i]+"']").attr("selected",true);
								$("#cmbUsuario").change();
							}
				
				window.onload = function(){
							var cadComa=/,/g;
							var laruta="<?php echo $ruta; ?>";
							var arrayRuta=laruta.split(",");

							for (var i = 0; i < arrayRuta.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbRuta option[value='"+arrayRuta[i]+"']").attr("selected",true);
								$("#cmbRuta").change();
							}
							
							var lasucursal="<?php echo $sucursal; ?>";
							var arraySucursal=lasucursal.split(",");

							for (var i = 0; i < arraySucursal.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbSucursal option[value='"+arraySucursal[i]+"']").attr("selected",true);
								$("#cmbSucursal").change();
							}
							
							//alert($("#cmbUsuario").val());
							var tipo =$("#cmbTipo").val();
							var ruta=$("#cmbRuta").val().toString();
							 var usuario=$("#cmbUsuario").val().toString();
							 var sucursal=$("#cmbSucursal").val().toString();
							
					        var seleccionadosUsuario = usuario.replace(cadComa, "|");
					        var seleccionadosUsuario2 =  usuario.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosSucursal = sucursal.replace(cadComa, "|");
					        var seleccionadosSucursal2 =  sucursal.replace(cadComa, ".COMACONTROL.");

					        var seleccionadosRuta = ruta.replace(cadComa, "|");
					        var seleccionadosRuta2 =  ruta.replace(cadComa, ".COMACONTROL.");
					        $("#lblTotPedidos").html("<?php echo FORMATO_DINERO($total); ?>");
						                   		 $("#lblNumPedidos").html("<?php echo $cantidad; ?>");
					        $.post("<?php echo CREPORTES('postTotalesPedidos');?>", {usuario: usuario, sucursal: sucursal, tipo: tipo, fechaI: $("#txtFInicio").val(), fechaF: $("#txtFFinal").val(), ruta: ruta},function(data){
											//alert(data);
												var losdatos=data.split("-");
													$("#lblTotPedidos").html(losdatos[1]);
						                   		 $("#lblNumPedidos").html(losdatos[0]);
										});
					        /*
					        var seleccionadosSucursal = replaceAll(sucursal, ",", "|");
					        var seleccionadosSucursal2 = replaceAll(sucursal, ",", ".COMACONTROL.");*/
							//alert(usuario);
							
							  if( tipo.includes("TODOS") ){
			                    myTable.column(1).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(1).search(tipo, true, false ).draw();  
			                  }
			                 
			               if( seleccionadosUsuario.includes("TODOS") ){
			                    myTable.column(6).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(6).search(seleccionadosUsuario, true, false ).draw();  
			                  }
			                  if( seleccionadosSucursal.includes("TODOS") ){
			                    myTable.column(8).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(8).search(seleccionadosSucursal, true, false ).draw();  
			                  }
			                  if( seleccionadosRuta.includes("TODOS") ){
			                    myTable.column(7).search("").draw();        
			                  }
			                  else
			                  {
			                    myTable.column(7).search(seleccionadosRuta, true, false ).draw();  
			                  }
				}
				
		</script>
			

	</body>
</html>
