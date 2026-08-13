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
						<small><i class="ace-icon fa fa-angle-double-right"></i>Mi Empresa/Correos Sell Out</small>
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

							<form action="<?php echo CCONFIGURAR('saveCorreoSellout'); ?>" method="POST" enctype="multipart/form-data">

								<div class="col-md-12">
									<label for="txtemail">Correo</label>
									<input type="email" class="form-control" name="correo" id="txtemail" />
								</div>

								<div class="col-md-12" align="center"><br/>
									<button class="btn btn-primary">Agregar</button>
								</div>

							</form>
						</div>
						
						<div class="col-md-12"><br/>
							<table class="table table-striped table-sm">
									<tr>
										<th>Correos</th>
									</tr>
								<?php foreach($correos->result() as $item) { ?>
									<tr>
										<td><?php echo $item->correo; ?></td>
									</tr>
								<?php } ?>
							</table>
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
			

				$(".btnActualizar").click(function(event) {
					/* Act on the event */
					location.reload();
				});
				
			})

		</script>
	</body>
</html>