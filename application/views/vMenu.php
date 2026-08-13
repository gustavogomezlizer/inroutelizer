<?php 
	require_once ('Mobile_Detect.php');
	$detect = new Mobile_Detect();
 ?>
		<div id="navbar" class="navbar navbar-default ace-save-state">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<div class="navbar-header pull-left">
					<a href="<?php echo LINKPROYECTO('Principal') ?>" class="navbar-brand">
						<small>

							<img src="<?php echo LOGOPRINCIPAL(); ?>" alt="" width="40" height="30">
							<?php 
								if ($detect->isMobile()==false){
							 ?>
							<b><i> Software de Venta.</b></i>

							<?php } ?>
							
						</small>
					</a>
				</div>
				
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">
						<li class="light-blue dropdown-modal" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->session->userdata('nombre'); ?>">						
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">								
								<img class="nav-user-photo" src="<?php echo RUTAFOLDERASSETS("images/avatars/".$this->session->userdata("foto")); ?>" /> 
								<span class="user-info">
									<small>Bienvenido</small>
									<?php echo $this->session->userdata("nombre"); ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

							<?php 
								if(GETACCESO('Configurar','ConfiguracionSistema')==1){
									?>
								<!--<li>
									<a href="<?php echo CCONFIGURAR(); ?>">
										<i class="ace-icon fa fa-cog"></i>
										Configuraciones Sistema
									</a>
								</li>-->
									<?php 
								}
							 ?>
								<?php 
								if(GETACCESO('Configurar','Perfil')==1){
									?>
								<!--<li>
									<a href="">
										<i class="ace-icon fa fa-user"></i>
										Perfil
									</a>
								</li>-->
									<?php 
								}
							 ?>


								<li class="divider"></li>

								<li>
									<a href="<?php echo LINKPROYECTO("Salir"); ?>">
										<i class="ace-icon fa fa-power-off"></i>
										Cerrar Sesion
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>
			<body class="no-skin">


		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar                  responsive                    ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large btn btn-link" id="sidebar-shortcuts-large">
						<!--<a href="<?php echo LINKPROYECTO('Principal'); ?>" class="btn btn-success" title="Inicio">
							<i class="ace-icon fa fa-home"></i>
						</a>												

						<a href="<?php echo LINKPROYECTO('Usuarios'); ?>" class="btn btn-warning" title="Usuarios">
							<i class="ace-icon fa fa-users"></i>
						</a>

						<a href="<?php echo LINKPROYECTO('Clientes'); ?>" class="btn btn-danger" title="Clientes">
							<i class="ace-icon fa fa-shopping-bag"></i>
						</a>-->
						<p>
							<?php echo $this->session->userdata("perfil"); ?><br/>
							<small><?php echo $this->session->userdata("nombre"); ?></small>
						</p>
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">

					<?php 
						
						$listaMenu=FGETMODULOS();
						
						foreach ($listaMenu as $kMenu) {
							$idMenu=$kMenu->id;
							$listaSMenu=FGETSUBMODULOS($idMenu);
							$cuantos=$listaSMenu->num_rows();
							//echo $cuantos;
							if($listaSMenu->num_rows()!=0){
								if(GETACCESO($kMenu->controller,$kMenu->funcion)==1){
								?>
								<li class="">
										<a href="#" class="dropdown-toggle">
											<i class="menu-icon <?php echo $kMenu->icono; ?>"></i>
											<span class="menu-text">
												<?php echo $kMenu->descripcion; ?>
											</span>

											<b class="arrow fa fa-angle-down"></b>
										</a>

										<b class="arrow"></b>
										<ul class="submenu">
											

											<?php 
												foreach ($listaSMenu->result() as $kSMenu) {
													if(GETACCESO($kSMenu->controller,$kSMenu->funcion)==1){
													?>
													<li class="">
														<a href="<?php echo LINKPROYECTO($kSMenu->funcion); ?>">
															<i class="menu-icon <?php echo $kSMenu->icono; ?>"></i>
															<?php echo $kSMenu->descripcion; ?>
														</a>

														<b class="arrow"></b>
													</li>
													<?php 
													}
												}
												?>
											
										</ul>
								</li>
								<?php 
								}
							}
							else{
								if(GETACCESO($kMenu->controller,$kMenu->funcion)==1){
								?>
									<li class="">
										<a href="<?php echo LINKPROYECTO($kMenu->controller."/".$kMenu->funcion); ?>">
											<i class="menu-icon <?php echo $kMenu->icono; ?>"></i>
											<span class="menu-text"> <?php echo $kMenu->descripcion; ?></span>
										</a>

										<b class="arrow"></b>
									</li>						
								<?php 
								}
							}
						}

					 ?>

				</ul><!-- /.nav-list -->

				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>
