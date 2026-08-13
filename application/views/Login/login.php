<?php 
$data['title']="LIZER Principal";
$this->load->view("vHead",$data); ?>


	<body class="login-layout blur-login">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">																	
								<img src="<?php echo LOGOPRINCIPAL() ?>" alt="" width="270" height="200">
							</div>

							<div class="space-30"><br></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main center">
											<h4 class="header blue blur bigger">
												<i class="ace-icon fa fa-users blue"></i>
												Iniciar Sesion.
											</h4>

											<div class="space-6"></div>

											<form id="form_login" method="post" action="<?php echo LINKPROYECTO('VerificacionUsuario'); ?>">
												<fieldset>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control login" placeholder="ID. Empresa" id="txtEmpresa" name="empresa" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control login" placeholder="Usuario" id="txtUsuario" name="usuario" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control login" placeholder="Clave" id="txtClave" name="clave" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

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
								<div id="forgot-box" class="forgot-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-key"></i>
												Retrieve Password
											</h4>

											<div class="space-6"></div>
											<p>
												Enter your email and to receive instructions
											</p>

											<form>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" placeholder="Email" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>

													<div class="clearfix">
														<button type="button" class="width-35 pull-right btn btn-sm btn-danger">
															<i class="ace-icon fa fa-lightbulb-o"></i>
															<span class="bigger-110">Send Me!</span>
														</button>
													</div>
												</fieldset>
											</form>
										</div><!-- /.widget-main -->

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												Back to login
												<i class="ace-icon fa fa-arrow-right"></i>
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.forgot-box -->

								<div id="signup-box" class="signup-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header green lighter bigger">
												<i class="ace-icon fa fa-users blue"></i>
												New User Registration
											</h4>

											<div class="space-6"></div>
											<p> Enter your details to begin: </p>

											<form>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="email" class="form-control" placeholder="Email" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" placeholder="Username" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Password" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>

													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Repeat password" />
															<i class="ace-icon fa fa-retweet"></i>
														</span>
													</label>

													<label class="block">
														<input type="checkbox" class="ace" />
														<span class="lbl">
															I accept the
															<a href="#">User Agreement</a>
														</span>
													</label>

													<div class="space-24"></div>

													<div class="clearfix">
														<button type="reset" class="width-30 pull-left btn btn-sm">
															<i class="ace-icon fa fa-refresh"></i>
															<span class="bigger-110">Reset</span>
														</button>

														<button type="button" class="width-65 pull-right btn btn-sm btn-success">
															<span class="bigger-110">Register</span>

															<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
														</button>
													</div>
												</fieldset>
											</form>
										</div>

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												<i class="ace-icon fa fa-arrow-left"></i>
												Back to login
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.signup-box -->
							</div><!-- /.position-relative -->
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->
		<div id="myModalAviso" class="modal fade">
		          <div class="modal-dialog modal-sm">
		            <div class="modal-content">
		              <!-- dialog body -->
		              <div class="modal-header">
		              <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
		                  <h4>ERROR DE INICIO DE SESION.</h4>
		              </div>
		              <div class="modal-body">
		                
		                
		                <div class="col-md-12 row">
		                       <h5>USUARIO O CLAVE INCORRECTOS.</h5>
		                </div>
		                <div class="space-40"></div>
		              </div>
		              <div class="modal-footer">
		                    
		                                      
		                    <button id="btnAceptarX" type="button" class="btn btn-danger">ACEPTAR</button>
		                </div>
		            </div>
		          </div>
		        </div>
		        <div id="myModalClave" class="modal fade">
		          <div class="modal-dialog modal-sm">
		            <div class="modal-content">
		              <!-- dialog body -->
		              <div class="modal-header">
		              <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
		                  <h3>PRIMER INICIO DE SESION.</h3>
		              </div>
		              <div class="modal-body">
		                
		                
		                <div class="col-md-12 row">
		                       <h5>CAMBIA LA CLAVE DE ACCESO.</h5>
		                </div>
		                <div class="col-md-12 row">
		                	<input type="text" id="txtClave1" class="form-control">
		                	<!-- <input type="text" id="txtClave2" class="form-control"> -->
		                </div>
		                <div class="space-40"></div>
		              </div>
		              <div class="modal-footer">
		                    
		                                      
		                    <button id="btnAceptarClave" type="button" class="btn btn-success">ACEPTAR</button>
		                </div>
		            </div>
		          </div>
		        </div>
		<!-- basic scripts -->

		<!--[if !IE]> -->
	
		<?php $this->load->view("vFooter"); ?>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			
			
			
			
			//you don't need this, just used for changing background
			jQuery(function($) {

			$("#btnAceptar").click(function(event) {
				inicarSesion();
            });

			$("#txtEmpresa").keypress(function(event) {
			 	if(event.which == 13) {
					inicarSesion();
		        }
			});

			$("#txtUsuario").keypress(function(event) {
			 	if(event.which == 13) {
					inicarSesion();
		        }
			});

			$("#txtClave").keypress(function(event) {
			 	if(event.which == 13) {
					inicarSesion();
		        }
			});

			function inicarSesion()
			{
				var usuario = $("#txtUsuario").val();
				var clave = $("#txtClave").val();
				var empresa = $("#txtEmpresa").val();
				if(empresa==""){
					alert("Escriba el id de empresa");
					return;
				}else if(usuario==""){
					alert("Escriba un usuario");
					return;
				}else if(clave==""){
					alert("Escriba una contraseña");
					return;
				}else{
					$("#form_login").submit();
				}
			}

			function login(){
				var resultado="";
			 	$.post("<?php echo CHOME('inicioLogin');?>", {usuario: $("#txtUsuario").val(),clave: $("#txtClave").val()},function(data){  
               		resultado=data;               		
				 	if(data=="0"){
				 		$("#myModalAviso").modal("show");
				 	}
				 	if(data=="2"){
				 		$("#myModalClave").modal("show");
				 	}
				 	if(data=="1"){
				 		window.location.href="<?php echo CHOME(); ?>";
				 	}
			
			 });
			}
			$("#btnAceptarClave").click(function(event) {
				/* Act on the event */
				var user=$("#txtUsuario").val();
				var clave1=$("#txtClave1").val();
				$.post("<?php echo CHOME('cambiarClave');?>", {user:user,clave1:clave1},function(data){  
               			//alert(data);
               			$("#myModalClave").modal("hide");
               			window.reload();
              		});	

	 });
			
			 $("#btnAceptarX").click(function(event) {
			 		/* Act on the event */
			 		$("#myModalAviso").modal("hide");

			 	});
			 $(".login").keypress(function(event) {
			 	/* Act on the event */
			 	if(event.which == 13) {
		          login();
		        }
			 });

			}); /*jquery*/

		</script>
	</body>
</html>
