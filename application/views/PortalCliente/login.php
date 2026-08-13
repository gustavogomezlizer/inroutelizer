<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data);
?>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>


	<body class="login-layout blur-login">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">																	
								<img src="<?php echo LOGOPRINCIPAL() ?>" alt="" width="350" height="120">
							</div>

							<div class="space-30"><br></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main center">
											<h4 class="header blue blur bigger">
												<i class="ace-icon fa fa-users blue"></i>
												Acceso Portal Clientes
											</h4>

											<div class="space-6"></div>

											<form id="form_login" method="POST" action="<?php echo LINKPROYECTO('PortalCliente/validacion_cliente_login'); ?>">
												<fieldset>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control login" placeholder="ID. Cliente" id="txtIdCliente" name="idcliente" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<div id="mensaje"></div>

													<div class="space"></div>

													<div class="center">

														<button id="btnAceptar" type="button" class="btn btn-primary btn-block">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110">Entrar</span>
														</button> 
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>

											<div class="space-1"></div>

											
										</div><!-- /.widget-main -->
										
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

								<div class="center">
									<h6 class="blue" id="id-company-text">&copy; <?php echo GETSYSTEMNAME() ?></h6>
								</div>

							</div><!-- /.position-relative -->
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
	
		<?php $this->load->view("vFooter"); ?>

	</body>
</html>

<script type="text/javascript">

	$("#btnAceptar").on("click", function(){
		verificarCliente();
	});

	$("#txtIdCliente").keypress(function(e) {		
	    if (e.keyCode === 13) {
	    	e.preventDefault();
	        $("#btnAceptar").click();
	        return false;
	    }
	});

	function alert(pMensaje)
	{
		var texto = '<div class="alert alert-danger">' +
		'<i class="ace-icon fa fa-times"></i>' +
		'</button>' +
		'<strong>' +
		'<i class="ace-icon fa fa-times"></i>Mensaje: </strong>' + pMensaje
		'<br />' +
		'</div>';

		return texto;
	}

	function verificarCliente()
	{
		$("#mensaje").html("");
		$('#mensaje').addClass('loadingtable');
		var codigo = $("#txtIdCliente").val();

		if(codigo.trim() == "")
		{
			alert("Favor de capturar el id de cliente");
			return;
		}

		axios.get("<?php echo LINKPROYECTO('GetClienteByCodigo/'); ?>" + codigo, {
			responseType: 'json'
		})
	    .then(function(res) {
	    	if(res.data == null)
	    	{
	    		$('#mensaje').removeClass('loadingtable');
				$("#mensaje").html(alert("El Id de cliente ingresado no existe. Favor de verificar"));
	    	}
	    	else
	    	{
	    		$("#form_login").submit();
	    	}
	    })
	    .catch(function(err) {
	    	$('#mensaje').removeClass('loadingtable');
			$("#mensaje").html(alert(err));
	    })
	    .then(function() {
	    });	
	}
	
</script>