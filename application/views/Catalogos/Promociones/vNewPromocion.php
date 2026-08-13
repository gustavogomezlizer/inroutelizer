<?php 
$data['title']="LIZER Agregar Usuario";
$this->load->view("vHead",$data); 
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="page-header">
				<h1>
					<strong>In Route</strong> <i>Sofware de Venta</i>
					<small><i class="ace-icon fa fa-angle-double-right"></i>Catalogos / Promociones</small>
				</h1>
			</div><!-- /.page-header -->

			<div class="row">
				<div class="col-xs-12">
					<div class="row"><!--  empieza div.row de la tabla clientes -->
						<div class="col-xs-12">	<!--  empieza div.col-xs-12 de la tabla clientes -->
							<div class="col-md-12 col-xs-12 col-sm-12" align="right">
								<button id="btnGuardar1" class="btn btn-success btnGuardar">GUARDAR</button>
								<a href="<?php echo LINKPROYECTO('Promociones') ?>" class="btn btn-danger">REGRESAR</a>
							</div>
						</div>

						<div class="col-xs-12"><br></div>
						<div class="space-40"></div>

						<div class="col-xs-12" style="margin-bottom:10px;">

							<div class="row">

								<input id="txtId" type="hidden" value="0" name="id" />

								<div class="col-xs-3">
									<label for="cmbTipoPromocion">Tipo promoción: </label>

									<select name="tipopromocion" id="cmbTipoPromocion" class="form-control">
										<option selected value="0"> [SELECCIONE UN TIPO DE PROMOCIÓN]</option>
										<option value="sincargo">SIN CARGO</option>
										<option value="descuento">DESCUENTO</option>
										<!--<option value="combo">COMBO</option>-->
									</select>
								</div>
								<div class="col-xs-3">
									<label for="txtDescripcion">Codigo: </label>
									<input type="text" name="descripcion" id="txtDescripcion" class="form-control" />										
								</div>
								<div class="col-xs-4">
									<label for="cmbSucursales">Sucursales: </label><br/>
									<select multiple="" name="sucursales" id="cmbSucursales" class="select2 form-control" data-placeholder="Elige opcion">
										<?php foreach($listaSucursales->result() AS $item) { ?>
											<option value="<?php echo $item->id; ?>"><?php echo $item->sucursal; ?></option>
										<?php } ?>
									</select>
								</div>

							</div>

							<div class="row">
								<div class="col-xs-2">
									<label for="txtStatus">Activa: </label><br/>
									<input id="txtStatus" type="checkbox" class="form-control" checked />
								</div>
							</div>

						</div>

						<div class="col-xs-12">

							<div id="div_sincargo"> <!-- empieza div que contiene a la tabla -->
								<table id="tabla_sincargo" class="table table-striped table-bordered table-hover table-condensed">
									<thead>
										<tr>
											<th>Codigo</th>
											<th>Nombre</th>
											<th>Condición(piezas)</th>
											<th id="thTipoPromocion">Sin cargo</th>
											<th>idproducto</th>
											<th>condicion</th>
											<th>piezas</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>

							<div id="div_descuento"> <!-- empieza div que contiene a la tabla -->
								<table id="tabla_descuento" class="table table-striped table-bordered table-hover table-condensed">
									<thead>
										<tr>
											<th>Codigo</th>
											<th>Nombre</th>
											<th>Condición(piezas)</th>
											<th>Descuento(%)</th>
											<th>idproducto</th>
											<th>condicion</th>
											<th>piezas</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>

							<div id="div_combo"> <!-- empieza div que contiene a la tabla -->
								<table id="tabla_combo" class="table table-striped table-bordered table-hover table-condensed">
									<thead>
										<tr>
											<th>Codigo</th>
											<th>Nombre</th>
											<th>Condición</th>
											<th>Precio</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>

							<div id="div_productos" class="table-responsive" style="margin-top:10px;"> <!-- empieza div que contiene a la tabla -->
								<table id="tabla_productos" width="100%" class="table table-striped table-bordered table-hover table-condensed">
									<thead>
										<tr>
											<th width="10%">Codigo</th>
											<th width="20%">Nombre</th>
											<th width="10%">Clasificacion</th>
											<th width="10%">Precio</th>
										</tr>
									</thead>
									<tbody>												
									</tbody>
								</table>
							</div>

						</div><!-- /.row -->


					</div><!-- empieza div que contiene a la tabla -->
				</div><!--  termina div.col-xs-12 de la tabla clientes-->

				<div class="space-40"><br></div>
				<div class="col-md-12 col-xs-12 col-sm-12" align="center"><br>
					<button id="btnGuardar" class="btn btn-success btnGuardar">GUARDAR</button>								
				</div>
			</div><!--  termina div.row de la tabla clientes-->
		</div>		
	</div><!-- /.col -->
</div><!-- /.row -->

		<!--</div>
	</div>
</div>-->

<?php $this->load->view("vCopyright"); ?>

<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
	<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>

<?php $this->load->view("vFooter"); ?>
</body>
</html>

<script type="text/javascript">

	window.onload = function()
	{
		<?php if($opcion=="editar" || $opcion=="ver") { ?>					
		<?php } ?>
	}

	$('.select2').css('width','600px').select2({allowClear:false})
	$('#select2-multiple-style .btn').on('click', function(e){
		var target = $(this).find('input[type=radio]');
		var which = parseInt(target.val());
		if(which == 2) $('.select2').addClass('tag-input-style');
			else $('.select2').removeClass('tag-input-style');
	});

	var myTable = $('#tabla_productos').DataTable({
		"language": {
			"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
		},
	});

	var table_sincargo = $('#tabla_sincargo').DataTable({
		"language": {
			"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
		},
		"searching": false,
		"paging": false,
	});

	var table_descuento = $('#tabla_descuento').DataTable({
		"language": {
			"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
		},
		"searching": false,
		"paging": false,
	});

	var table_combo = $('#tabla_combo').DataTable({
		"language": {
			"url": "<?php echo RUTAFOLDERASSETS("json/datatablesspanish.json"); ?>"
		},
		"searching": false,
		"paging": false,
	});

	cargarTablaProductos();

	//$("#div_sincargo").hide();
	$("#div_descuento").hide();
	$("#div_combo").hide();

	function cargarTablaProductos()
	{
		$('#tabla_productos').addClass('loadingtable');
		$('#tabla_productos tbody').html("");

		$.post("<?php echo LINKPROYECTO('ListadoProductosJsonByStatatus') ?>", {status: "1"}, function(data)
		{
			var datos = JSON.parse(data);						
			if(datos.length > 0)
			{
				myTable.destroy();
				myTable = $('#tabla_productos').DataTable({
					"language": {
						"url": "<?php echo RUTAFOLDERASSETS('json/datatables-spanish.json'); ?>"
					},
					"pageLength": 10,
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
					"order": [[0,"asc"]],
					"aaData": datos,
					"columns": [
						{ "data": "codigo" },
						{ "data": "nombre" },
						{ "data": "nombre_clasificacion" },
						{ "data": "precio_format" },
					],
				});
			}
			else
			{
				myTable.clear().draw();
			}
		}).always(function() {
			$('#tabla_productos').removeClass('loadingtable');
		});
	}

	$("#cmbTipoPromocion").on("change", function(){
		var valor = $(this).val();

		table_sincargo.rows().remove().draw();
		table_descuento.rows().remove().draw();
		table_combo.rows().remove().draw();

		if(valor=="sincargo")
		{
			$("#thTipoPromocion").text("Sin cargo");

			/*$('#div_sincargo').show();
			$('#div_descuento').hide();
			$('#div_combo').hide();

			$('#tabla_sincargo').css("width", "100%");*/
		}
		else if(valor=="descuento")
		{
			$("#thTipoPromocion").text("Descuento(%)");

			/*$('#div_descuento').show();
			$("#div_sincargo").hide();
			$('#div_combo').hide();

			$('#tabla_descuento').css("width", "100%");*/
		}
		else if(valor=="combo")
		{
			$("#thTipoPromocion").text("Precio($)");

			/*$('#div_combo').show();
			$("#div_sincargo").hide();
			$('#div_descuento').hide();

			$('#tabla_combo').css("width", "100%");*/
		}
		else
		{
			/*$("#div_sincargo").hide();
			$('#div_descuento').hide();
			$('#div_combo').hide();*/
		}
	});

	$('#tabla_productos tbody').on('dblclick', 'tr', function () {
		var source = myTable.row(this);
		//table_sincargo.row.add(source.data()).draw();
		//source.remove().draw ();

		var valor = $("#cmbTipoPromocion").val();

		if(valor=="0")
		{
			dialogAvisoGlobal.show("Favor de seleccionar un tipo de promoción", "alert alert-warning");
			return;
		}

		//if(valor=="sincargo")
		if(1==1)
		{
			var input_piezas = "<input name='txtPiezas' type='number' value='1' />";

			if(valor=="descuento"){
				input_piezas = "<input name='txtPiezas' type='number' value='1' readonly />"
			}

			table_sincargo.row.add({
				"0":source.data()["codigo"],
				"1":source.data()["nombre"],
				"2":input_piezas,
				"3":"<input name='txtCantidad' type='number' value='1' />",
				"4":source.data()["id"],
				"5":"1",
				"6":"1",
			}).draw();
		}
		else if(valor=="descuento")
		{			
			table_descuento.row.add({
				"0":source.data()["codigo"],
				"1":source.data()["nombre"],
				"2":"<input type='number' value='1' />",
				"3":"<input type='number' value='1' />%",
				"4":source.data()["id"],
				"5":"0",
				"6":"1",
			}).draw();
		}
		else if(valor=="combo")
		{			
			table_combo.row.add({
				"0":source.data()["codigo"],
				"1":source.data()["nombre"],
				"2":"<select> <option selected>SI</option> </select>",
				"3":"$<input type='number' value='1' />",				
			}).draw();
		}
		else
		{
			dialogAvisoGlobal.show("Favor de seleccionar un tipo de promoción", "alert alert-warning");
		}		
	});

	/*$('#tabla_sincargo tbody').on( 'change', 'select', function () {        
        var index = table_sincargo.row( $(this).parents('tr') ).index();        

        if($(this).attr('name')=="txtPiezas"){
			table_sincargo.cell(index, 5).data($(this).val());
        }
    });*/

	$('#tabla_sincargo tbody').on( 'change', 'input', function () {        
        var index = table_sincargo.row( $(this).parents('tr') ).index();

		if($(this).attr('name')=="txtPiezas"){
			table_sincargo.cell(index, 5).data($(this).val());
        }

        if($(this).attr('name')=="txtCantidad"){
			table_sincargo.cell(index, 6).data($(this).val());
        }
    });

	$('#tabla_sincargo').on('dblclick', 'tbody tr', function (){
    	table_sincargo.row(this).remove().draw();
	});

	$('#tabla_descuento').on('dblclick', 'tbody tr', function (){
    	table_descuento.row(this).remove().draw();
	});

	$('#tabla_combo').on('dblclick', 'tbody tr', function (){
    	table_combo.row(this).remove().draw();
	});

	<?php if($opcion=="editar") { ?>
		cargarDatosFormulario();
	<?php } else if($opcion=="ver") { ?>
		cargarDatosFormulario();
		disabledFormulario();
		$(".btnGuardar").hide();
	<?php } ?>

	$(".btnGuardar").click(function(event) {

		var tipopromocion = $("#cmbTipoPromocion").val();
		var descripcion = $("#txtDescripcion").val();
		var sucursales = $("#cmbSucursales").val();
		var status = $('#txtStatus').is(":checked") ? 1 : 0;

		console.log(JSON.stringify($('#tabla_sincargo').DataTable().rows().data().toArray()));

		if(tipopromocion=="0"){
			dialogAvisoGlobal.show("Favor de seleccionar un tipo de promoción", "alert alert-warning");
			$("#cmbTipoPromocion").focus();
		}else if(descripcion==""){
			dialogAvisoGlobal.show("Favor de escribir un codigo de promoción", "alert alert-warning");
			$("#txtDescripcion").focus();
		}else if(sucursales==null){
			dialogAvisoGlobal.show("Favor de seleccionar al menos una sucursal donde se aplicará la promoción", "alert alert-warning");
			$("#cmbSucursales").focus();
		}else{

			$.post("<?php echo LINKPROYECTO('GuardarPromocion') ?>", 
				{
					id: $("#txtId").val(),
					tipo: tipopromocion,
					codigo: descripcion,
					sucursales: sucursales.toString(),
					status: status,
					detalle: JSON.stringify($('#tabla_sincargo').DataTable().rows().data().toArray())
				}, 
			function(data){
				if(data.trim()=="existe"){
					dialogAvisoGlobal.show("El codigo de promoción ingresado ya esta registrado en otra promocion", "alert alert-warning");
				}
				else if( parseFloat(data.trim())>0 ){
					dialogAvisoGlobal.show("Promoción guardada correctamente", "alert alert-success");
					window.location = "<?php echo LINKPROYECTO('Promociones') ?>";
				}else{
					dialogAvisoGlobal.show("Ocurrio un error al guardar la promoción", "alert alert-danger");
				}
			});
		}

	});

	<?php if($opcion=="editar" || $opcion=="ver") { ?>
		function cargarDatosFormulario()
		{
			$("#txtId").val("<?php echo $promocion->id; ?>");
			$("#txtDescripcion").val("<?php echo $promocion->codigo; ?>");
			//$("#cmbSucursales").val("<?php echo $promocion->sucursales; ?>");
			$("#cmbTipoPromocion").val("<?php echo $promocion->tipo; ?>");
			$("#txtStatus").prop("checked", "<?php echo (($promocion->status==1) ? true : false); ?>");

			var values = "<?php echo $promocion->sucursales2; ?>";			
			var multi = document.getElementById('cmbSucursales');

			multi.value = null; // Reset pre-selected options (just in case)
			var multiLen = multi.options.length;
			for (var i = 0; i < multiLen; i++) {
				if(values.includes(multi.options[i].text))
				{
					multi.options[i].selected = true;
				}
			}

			$("#cmbSucursales").trigger('change');

			<?php foreach($detalle as $item) { ?>
				table_sincargo.row.add({
					"0":"<?php echo $item->codigoproducto ?>",
					"1":"<?php echo $item->codigoproducto ?>",
					"2":"<input name='txtPiezas' type='number' value='<?php echo $item->condicion ?>' />",
					"3":"<input name='txtCantidad' type='number' value='<?php echo $item->promocion ?>' />",
					"4":"<?php echo $item->idproducto ?>",
					"5":"<?php echo $item->condicion ?>",
					"6":"<?php echo $item->promocion ?>",
				}).draw();
			<?php } ?>
		}
	<?php } ?>

	function disabledFormulario()
	{
		$("#txtId").prop("disabled", true);
		$("#txtDescripcion").prop("disabled", true);		
		$("#cmbTipoPromocion").prop("disabled", true);
		$("#cmbSucursales").prop("disabled", true);
		$("#txtStatus").prop("disabled", true);
		$("#div_productos").hide();
	}

</script>