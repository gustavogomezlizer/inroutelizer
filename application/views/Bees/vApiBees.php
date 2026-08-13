<?php 
$data['title']="LIZER Reportes-Visitas";


$this->load->view("vHead",$data); ?>
<style>
	.tamano{
		width: 90% !important;
	}
</style>
<?php $this->load->view("vMenu"); ?>

			<div id="principal" class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						

						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Bees / APIs
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="col-md-12">

							<div class="col-md-12">
								<button id="btnClientes" class='btn-primary btn-lg' style='width:100%'>*Enviar Clientes (<?php echo $info->clientespendientes; ?> pendientes)</button>
							</div>

							<div class="col-md-12"><br/></div>

							<div class="col-md-12">
								<button id="btnRutas" class='btn-primary btn-lg' style='width:100%'>Enviar Rutas de Clientes</button>
							</div>

							<div class="col-md-12"><br/></div>

							<div class="col-md-12">
								<button id="btnVisitas" class='btn-primary btn-lg' style='width:100%'>Enviar Visitas de Rutas a Clientes de la Semana</button>
							</div>

							<div class="col-md-12"><br/></div>

							<div class="col-md-12">
								<button id="btnProductos" class='btn-primary btn-lg' style='width:100%'>*Enviar Productos (<?php echo $info->productospendientes; ?> pendientes)</button>
							</div>

							<div class="col-md-12"><br/></div>

							<div class="col-md-12">
								<button id="btnProductoAssort" class='btn-primary btn-lg' style='width:100%'>Enviar Surtido de Productos a Sucursales</button>
							</div>

							<div class="col-md-12"><br/></div>

							<div class="col-md-12">
								<button id="btnInventory" class='btn-primary btn-lg' style='width:100%'>Enviar Inventario de Productos a sucursales</button>
							</div>

							<div class="col-md-12"><br/></div>

							<div class="col-md-12">
								<button id="btnPrices" class='btn-primary btn-lg' style='width:100%'>Enviar Precios de Productos a sucursales</button>
							</div>

							<div class="col-md-12"><br/></div>

							<div class="col-md-12">
								<button id="btnCombos" class='btn-primary btn-lg' style='width:100%'>Enviar Combos Promocionales (<?php echo $info->combospendientes; ?> pendientes)</button>
							</div>

							<div class="col-md-12"><br/></div>

							<div class="col-md-12">
								<button id="btnOrdenes" class='btn-primary btn-lg' style='width:100%'>*Enviar Pedidos Non Bees (<?php echo $info->pedidospendientes; ?> pendientes)</button>
							</div>

							<div class="col-md-12"><br/></div>

							<div class="col-md-12">
								<button id="btnFacturas" class='btn-primary btn-lg' style='width:100%'>*Enviar Facturas de Pedidos</button>
							</div>
							
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->			

<?php $this->load->view("vCopyright"); ?>

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>

<?php $this->load->view("vFooter"); ?>

</body>
</html>
		
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script>

	$("#btnProductoAssort").on("click", function()
	{
		$('body').addClass('loadingtable');

		$.post("<?php echo LINKPROYECTO('TokenBees/postAssortment') ?>", function(data){
			
		}).always(function() {
			$('body').removeClass('loadingtable');
		});
	});

	$("#btnInventory").on("click", function()
	{
		$('body').addClass('loadingtable');

		$.post("<?php echo LINKPROYECTO('TokenBees/postInventory') ?>", function(data){
			
		}).always(function() {
			$('body').removeClass('loadingtable');
		});
	});
	
	$("#btnPrices").on("click", function()
	{
		$('body').addClass('loadingtable');

		$.post("<?php echo LINKPROYECTO('TokenBees/postPrice') ?>", function(data){
			
		}).always(function() {
			$('body').removeClass('loadingtable');
		});
	});

	$("#btnCombos").on("click", function()
	{
		$('body').addClass('loadingtable');

		$.post("<?php echo LINKPROYECTO('TokenBees/postComboAccount') ?>", function(data){
			
		}).always(function() {
			$('body').removeClass('loadingtable');
			location.reload();
		});
	});

	$("#btnRutas").on("click", function()
	{
		$('body').addClass('loadingtable');

		$.post("<?php echo LINKPROYECTO('TokenBees/postUcc') ?>", function(data){
			
		}).always(function() {
			$('body').removeClass('loadingtable');
		});
	});

	$("#btnVisitas").on("click", function()
	{
		$('body').addClass('loadingtable');

		$.post("<?php echo LINKPROYECTO('TokenBees/postVisits') ?>", function(data){
			
		}).always(function() {
			$('body').removeClass('loadingtable');
		});
	});

	$("#btnFacturas").on("click", function()
	{
		$('body').addClass('loadingtable');

		$.post("<?php echo LINKPROYECTO('TokenBees/postInvoice') ?>", function(data){
			
		}).always(function() {
			$('body').removeClass('loadingtable');
		});
	});

	$("#btnClientes").on("click", function()
	{
		$('body').addClass('loadingtable');

		$.post("<?php echo LINKPROYECTO('TokenBees/postMasivoAccount') ?>", function(data){
			
		}).always(function() {
			$('body').removeClass('loadingtable');
			location.reload();
		});
	});

	$("#btnProductos").on("click", function()
	{
		$('body').addClass('loadingtable');

		$.post("<?php echo LINKPROYECTO('TokenBees/postMasivoItem') ?>", function(data){
			
		}).always(function() {
			$('body').removeClass('loadingtable');
			location.reload();
		});
	});
	
</script>