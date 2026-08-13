<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data); ?>
<?php $this->load->view("vMenu"); ?>

	<div class="main-content">
		<div class="main-content-inner">
			<div class="page-content">
						
				<div class="page-header">
					<h1>
						LIZER Sistema de Distribucion
						<small><i class="ace-icon fa fa-angle-double-right"></i>Mi Empresa/Datos Generales</small>
					</h1>
				</div><!-- /.page-header -->

				<div class="row">
					<div class="col-md-12">						

						<div class="col-md-12">

							<div class="clearfix">
								<div class="pull-right">
									<button class="btn btn-primary btnActualizar">Actualizar</button>
								</div>
							</div>

							<form action="<?php echo CCONFIGURAR('saveConfigurar'); ?>" method="POST" enctype="multipart/form-data">

								<div class="col-md-6">
									<label for="txtNombreComercial">NOMBRE COMERCIAL</label>
									<input type="text" class="form-control" name="txtNombreComercial" id="txtNombreComercial" value="<?php echo $datosConf->row()->nombrecorto; ?>" />
								</div>
								
								<div class="col-md-6">
									<label for="txtRs">RAZON SOCIAL</label>
									<input type="text" class="form-control" name="txtRs" id="txtRs" value="<?php echo $datosConf->row()->nombre; ?>" />
								</div>

								<div class="col-md-12">
									<label for="txtDomicilio">DOMICILIO</label>
									<input type="text" class="form-control" name="txtDomicilio" id="txtDomicilio" value="<?php echo $datosConf->row()->domicilio; ?>" />
								</div>

								<div class="col-md-4">
									<label for="txtTelefono">TELEFONO</label>
									<input type="text" class="form-control" name="txtTelefono" id="txtTelefono" value="<?php echo $datosConf->row()->telefono; ?>" />
								</div>

								<div class="col-md-8">
									<label for="txtCorreo">CORREO</label>
									<input type="text" class="form-control" name="txtCorreo" id="txtCorreo" value="<?php echo $datosConf->row()->correo; ?>" />
								</div>

								<div class="col-md-3">
									<label for="txtCorreo">UTILIZA IMPRESORA</label>
									<input type="checkbox" class="form-control" name="utiliza_impresora" id="chkUtilizaImpresora" <?php echo (($datosConf->row()->utiliza_impresora == 1) ? "checked" : ""); ?> /><br/><br/>
								</div>

								<div class="col-md-3">
									<label for="txtCorreo">VALIDACIÓN INVENTARIO</label>
									<input type="checkbox" class="form-control" name="validacion_inventario" id="chkValidacionInventario" <?php echo (($datosConf->row()->validacion_inventario == 1) ? "checked" : ""); ?> /><br/><br/>
								</div>

								<div class="col-md-12">
									<label for="">LOGOTIPO</label>
									<img src="<?php echo $datosConf->row()->logo; ?>" alt="" width="300" height="100" /><br/><br/><br/>
									<input multiple="" type="file" id="id-input-file-3" name="logo" />
								</div><br/>

								<div class="col-md-12" align="center">
									<button class="btn btn-primary">Guardar</button>
								</div>

							</form>
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

		<!-- basic scripts -->
	<?php $this->load->view("vFooter"); ?>
		
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKYMP1l569OtfSqd4U2f_ysZuJHodabIU&region=GB"></script>
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {

				$('#id-input-file-3').ace_file_input({
					style: 'well',
					btn_choose: 'Arrastra un archivo hasta aqui o haz click aqui',
					btn_change: null,
					no_icon: 'ace-icon fa fa-cloud-upload',
					droppable: true,
					thumbnail: 'large'//large | fit
					,
					allowExt: ["jpeg", "jpg", "png", "gif" , "bmp"],
					allowMime: ["image/jpg", "image/jpeg", "image/png", "image/gif", "image/bmp"],
					preview_error : function(filename, error_code) {
					}
			
				}).on('change', function(){
				});
			
			
				$(document).on('click', '#dynamic-table .dropdown-toggle', function(e) {
					e.stopImmediatePropagation();
					e.stopPropagation();
					e.preventDefault();
				});

				$(".btnActualizar").click(function(event) {
					/* Act on the event */
					location.reload();
				});
				
			})

		</script>
	</body>
</html>