<?php 
$data['title']="Proyección Nomina";
$this->load->view("vHead", $data); ?>
<?php $this->load->view("vMenu");?>

			<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Administración / Control de Gastos
								</small>
							</h1>
							
						</div><!-- /.page-header -->
						<div class="row">
							<div class="col-md-12">

									<div class="col-md-2">
										<label for="txtPeriodo">Periodo(AAAAMM)</label>
										<input id="txtPeriodo" type="input" class="form-control" value="<?php echo $periodo; ?>" />
									</div>
									
									<div class="col-md-2">
										<label for="">Sucursal</label>
										<select name="cmbSucursal" id="cmbSucursal"  class="form-control">
											<?php if(ISMULTISUCURSAL()) { ?>
												<option value="0" >TODAS</option>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
													<option value="<?php echo $item->id; ?>" ><?php echo $item->sucursal; ?></option>
												<?php } ?>
											<?php } else { ?>
												<?php foreach (GETLISTASUCURSALES() as $item) { ?>
													<?php if(GETSUCURSAL()==$item->id) { ?>
														<option value=<?php echo $item->id; ?>><?php echo $item->sucursal; ?></option>
													<?php } ?>
												<?php } ?>
											<?php } ?>
										</select>
									</div>

									<div class="col-md-2"><label for="">Negocio</label><br>
										<select name="cmbNegocio" id="cmbNegocio">
											<option value="0">Todos</option>
											<?php foreach($proveedores as $proveedor) { ?>
												<option value="<?php echo $proveedor->id; ?>"><?php echo $proveedor->nombre; ?></option>
											<?php } ?>
										</select>
									</div>

									<div class="col-md-4" align="right"><br/>
										<button id="btnAplicar" class="btn btn-primary">Aplicar</button>
										<button class="btn btn-success btnActualizar">Actualizar</button>
									</div>

									<div class="col-md-12"><hr></div>
									
									<div class="row">
									
									<div class="row">

										<div class="row">										

											<div class="col-md-12">

												<div class="clearfix col-md-12" align="right">
													<div class="pull-right tableTools-container"></div>
												</div>

											</div>
											</div>
											</div>

										<div class="row col-md-12">
											<br/>
											<div class="table-responsive">
												<table id="table_categorias" width="100%" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<th>Sucursal</th>
															<th>Concepto</th>
															<th>Importe</th>
														</tr>
													</thead>

													<tbody>
													</tbody>
													
													<tfoot>
														<tr>
															<td colspan="2">Total:</td>
															<td align="right"><b id="lblTotal">$0.00</b></td>
														</tr>
													</tfoot>

												</table>
											</div>
										</div>

										<div align="center"><br/>
											<button id="btnGuardar" class="btn btn-success">Guardar</button>
										</div>
								</div>
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

<?php $this->load->view("vFooter"); ?>

	</body>
</html>
		
<script>

	var i_sucursal=0, i_ruta=1, i_categoria=2, i_objetivo=3, i_venta1=4, i_alcance1=5, i_venta2=6, i_alcance2=7,i_pago_categoria=8,i_total=9  /*, i_alcancegap=8, i_objetivodiario=9*/;
	var CARGAR_BOTONES_TABLA = "0";
	var items = [];

	function formatMoney(n, c, d, t) {
		var c = isNaN(c = Math.abs(c)) ? 2 : c,
			d = d == undefined ? "." : d,
			t = t == undefined ? "," : t,
			s = n < 0 ? "-" : "",
			i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
			j = (j = i.length) > 3 ? j % 3 : 0;

		return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	};

	window.onload = function()
	{
		
	}

	$("#btnAplicar").on("click", function(){

		if($("#cmbSucursal").val()==0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar una sucursal", "alert alert-warning");
		}
		else if($("#cmbNegocio").val()==0)
		{
			dialogAvisoGlobal.show("Favor de seleccionar un negocio", "alert alert-warning");
		}
		else
		{
			cargarTablaProductos($("#txtPeriodo").val(), $("#cmbSucursal").val(), $("#cmbNegocio").val());
		}
	});

	$("#btnGuardar").on("click", function(){
		$.post("<?php echo LINKPROYECTO('Administracion/saveGastoSucursal') ?>", {items}, function(data)
		{
			$("#btnAplicar").click();
		}).always(function() {
		});
	});

	function cargarTablaProductos(pPeriodo, pSucursal, pNegocio)
	{		
		$('#table_categorias').addClass('loadingtable');

		if(pSucursal==null) pSucursal = "0";

		items = [];

		$.post("<?php echo LINKPROYECTO('Administracion/getListadoGastosSucursal') ?>", {periodo:pPeriodo, sucursal:pSucursal, negocio:pNegocio}, function(data)
		{
			var datos = JSON.parse(data);
			if(datos.length > 0)
			{
				items = datos;				
			}
			else
			{
				
			}

		}).always(function() {
			renderItemsTable();
			$('#table_categorias').removeClass('loadingtable');
		});
	}

	function changeRangeName(id, e)
	{
		var index = items.findIndex((obj => obj.gasto_id == id));

		items[index].importe = e.value;
    }

	function renderItemsTable()
	{
		let dollarUS = Intl.NumberFormat("en-US", {
			style: "currency",
			currency: "USD",
			decimal: 2
		});

		var cadena = "";
		var total = 0;

		for(var x in items)
		{
			var importe = dollarUS.format(parseFloat(items[x].importe)).replace("$", "");

			cadena = cadena + "<tr>";
			cadena = cadena + "<td>" + items[x].sucursal + "</td>";
			cadena = cadena + "<td>" + items[x].descripcion + "</td>";
			cadena = cadena + "<td align='right'>$<input type='text' onchange='changeRangeName(" + items[x].gasto_id + ", this)' value='" + importe + "'</td>";
			cadena = cadena + "</tr>";

			total = total + parseFloat(items[x].importe);
		}

		console.log(items);

		$("#lblTotal").text(dollarUS.format(parseFloat(total)));
		$("#table_categorias tbody").html(cadena);
		//$("#lblTotal").text("Total: " + dollarUS.format(total));
	}
</script>