<?php
$this->load->view("vHead"); ?>
<?php $this->load->view("vMenu"); ?>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header">
							<h1>
								<strong>In Route</strong> <i>Sofware de Venta</i>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Dashboard Mejorado
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<!-- PAGE CONTENT BEGINS -->
							<div class="row">
								<div class="col-sm-12" align="center">
									<img src="<?php echo LOGOCLIENTE(); ?>" alt="" width="300" height="100">
								</div>
							</div>

							<div class="col-md-6">
								<div class="row">
									<div class="col-xs-12 col-sm-6">
										<div class="infobox infobox-green">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-dollar"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">$2,450,320</span>
												<div class="infobox-content">Total Ventas <span class="bigger-110">+15%</span></div>
											</div>
											<div class="stat stat-success">+15%</div>
										</div>
									</div>

									<div class="col-xs-12 col-sm-6">
										<div class="infobox infobox-blue">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-shopping-cart"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">1,245</span>
												<div class="infobox-content">Pedidos <span class="bigger-110">+8%</span></div>
											</div>
											<div class="stat stat-important">+8%</div>
										</div>
									</div>

									<div class="col-xs-12 col-sm-6">
										<div class="infobox infobox-purple">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-street-view"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">2,180</span>
												<div class="infobox-content">Visitas <span class="bigger-110">+12%</span></div>
											</div>
											<div class="stat stat-warning">+12%</div>
										</div>
									</div>

									<div class="col-xs-12 col-sm-6">
										<div class="infobox infobox-orange">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-users"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">487</span>
												<div class="infobox-content">Clientes <span class="bigger-110">+5%</span></div>
											</div>
											<div class="stat stat-danger">+5%</div>
										</div>
									</div>

									<div class="col-xs-12 col-sm-6">
										<div class="infobox infobox-red">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-truck"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">52</span>
												<div class="infobox-content">Rutas <span class="bigger-110">Estables</span></div>
											</div>
											<div class="stat stat-success">100%</div>
										</div>
									</div>

									<div class="col-xs-12 col-sm-6">
										<div class="infobox infobox-teal">
											<div class="infobox-icon">
												<i class="ace-icon fa fa-bar-chart"></i>
											</div>
											<div class="infobox-data">
												<span class="infobox-data-number">87.2%</span>
												<div class="infobox-content">Efectividad <span class="bigger-110">+3%</span></div>
											</div>
											<div class="stat stat-success">+3%</div>
										</div>
									</div>
								</div>
							</div>

							<div align="center" class="col-md-6">
								<div class="col-md-12">
									<div class="widget-box">
										<div class="widget-header widget-header-flat widget-header-small">
											<h5 class="widget-title">
												<i class="ace-icon fa fa-signal"></i>
												Visitas con Efectividad
											</h5>
										</div>
										<div class="widget-body">
											<div class="widget-main">
												<div id="piechart-placeholder"></div>
												<div class="hr hr8 hr-double"></div>
												<div class="clearfix">
													<div class="grid3">
														<span class="grey">
															<i class="ace-icon fa fa-street-view fa-2x blue"></i>
															&nbsp; Visitas
														</span>
														<h4 class="bigger pull-right">2,180</h4>
													</div>
													<div class="grid3">
														<span class="grey">
															<i class="ace-icon fa fa-dollar fa-2x green"></i>
															&nbsp; Pedidos
														</span>
														<h4 class="bigger pull-right">1,245</h4>
													</div>
													<div class="grid3">
														<span class="grey">
															<i class="ace-icon fa fa-arrow-circle-down fa-2x red"></i>
															&nbsp; Sin Pedidos
														</span>
														<h4 class="bigger pull-right">935</h4>
													</div>
												</div>
											</div><!-- /.widget-main -->
										</div><!-- /.widget-body -->
									</div><!-- /.widget-box -->
								</div><!-- /.col -->
							</div>
						</div><!-- /.row -->

						<!-- Nueva fila: Gráfico de ventas mensuales -->
						<div class="row">
							<div class="col-md-12">
								<div class="widget-box">
									<div class="widget-header widget-header-flat widget-header-small">
										<h5 class="widget-title">
											<i class="ace-icon fa fa-line-chart"></i>
											Ventas Mensuales (Últimos 12 Meses)
										</h5>
									</div>
									<div class="widget-body">
										<div class="widget-main">
											<div id="ventas-mensuales-chart" style="height: 300px;"></div>
											<div class="space-4"></div>
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>Mes</th>
														<th>Ventas</th>
													</tr>
												</thead>
												<tbody>
													<tr><td>Ene 2025</td><td>$185,000</td></tr>
													<tr><td>Feb 2025</td><td>$210,000</td></tr>
													<tr><td>Mar 2025</td><td>$195,000</td></tr>
													<tr><td>Abr 2025</td><td>$235,000</td></tr>
													<tr><td>May 2025</td><td>$265,000</td></tr>
													<tr><td>Jun 2025</td><td>$280,000</td></tr>
													<tr><td>Jul 2025</td><td>$295,000</td></tr>
													<tr><td>Ago 2025</td><td>$310,000</td></tr>
													<tr><td>Sep 2025</td><td>$325,000</td></tr>
													<tr><td>Oct 2025</td><td>$340,000</td></tr>
													<tr><td>Nov 2025</td><td>$355,000</td></tr>
													<tr><td>Dic 2025</td><td>$370,000</td></tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div><!-- /.row -->

						<!-- Nueva fila: Top Categorías y Top Rutas -->
						<div class="row">
							<div class="col-md-6">
								<div class="widget-box">
									<div class="widget-header widget-header-flat widget-header-small">
										<h5 class="widget-title">
											<i class="ace-icon fa fa-bar-chart"></i>
											Top 10 Categorías Más Vendidas
										</h5>
									</div>
									<div class="widget-body">
										<div class="widget-main">
											<div id="top-categorias-chart" style="height: 300px;"></div>
											<div class="space-4"></div>
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>Categoría</th>
														<th>Total Ventas</th>
													</tr>
												</thead>
												<tbody>
													<tr><td>Alimentos Infantiles</td><td>$125,000</td></tr>
													<tr><td>Bebidas Refrescantes</td><td>$98,000</td></tr>
													<tr><td>Lácteos</td><td>$87,500</td></tr>
													<tr><td>Carnes y Embutidos</td><td>$76,500</td></tr>
													<tr><td>Panadería y Pastelería</td><td>$65,400</td></tr>
													<tr><td>Frutas y Verduras</td><td>$54,300</td></tr>
													<tr><td>Snacks y Dulces</td><td>$43,200</td></tr>
													<tr><td>Productos de Limpieza</td><td>$32,100</td></tr>
													<tr><td>Artículos para el Hogar</td><td>$21,000</td></tr>
													<tr><td>Otros</td><td>$15,600</td></tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="widget-box">
									<div class="widget-header widget-header-flat widget-header-small">
										<h5 class="widget-title">
											<i class="ace-icon fa fa-bar-chart"></i>
											Top 10 Rutas Más Vendidas
										</h5>
									</div>
									<div class="widget-body">
										<div class="widget-main">
											<div id="top-rutas-chart" style="height: 300px;"></div>
											<div class="space-4"></div>
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>Ruta</th>
														<th>Total Ventas</th>
													</tr>
												</thead>
												<tbody>
													<tr><td>Ruta Centro Histórico</td><td>$145,000</td></tr>
													<tr><td>Ruta Norte Industrial</td><td>$132,000</td></tr>
													<tr><td>Ruta Sur Residencial</td><td>$118,000</td></tr>
													<tr><td>Ruta Este Comercial</td><td>$105,000</td></tr>
													<tr><td>Ruta Oeste Empresarial</td><td>$92,000</td></tr>
													<tr><td>Ruta Metropolitana A</td><td>$81,000</td></tr>
													<tr><td>Ruta Metropolitana B</td><td>$73,000</td></tr>
													<tr><td>Ruta Rural Norte</td><td>$65,000</td></tr>
													<tr><td>Ruta Rural Sur</td><td>$58,000</td></tr>
													<tr><td>Ruta Especializada</td><td>$51,000</td></tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div><!-- /.row -->

					</div><!-- /.page-content -->
				</div>
			</div>
		</div>

	<?php $this->load->view("vCopyright"); ?>

	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>

	<?php $this->load->view("vFooter"); ?>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
		// Datos de ejemplo para gráficos
		var ventasMensualesData = [
			[gd(2024, 1, 1), 150000],
			[gd(2024, 2, 1), 165000],
			[gd(2024, 3, 1), 180000],
			[gd(2024, 4, 1), 195000],
			[gd(2024, 5, 1), 210000],
			[gd(2024, 6, 1), 225000],
			[gd(2024, 7, 1), 240000],
			[gd(2024, 8, 1), 255000],
			[gd(2024, 9, 1), 270000],
			[gd(2024, 10, 1), 285000],
			[gd(2024, 11, 1), 300000],
			[gd(2024, 12, 1), 315000],
			[gd(2025, 1, 1), 185000],
			[gd(2025, 2, 1), 210000],
			[gd(2025, 3, 1), 195000],
			[gd(2025, 4, 1), 235000],
			[gd(2025, 5, 1), 265000],
			[gd(2025, 6, 1), 280000],
			[gd(2025, 7, 1), 295000],
			[gd(2025, 8, 1), 310000],
			[gd(2025, 9, 1), 325000],
			[gd(2025, 10, 1), 340000],
			[gd(2025, 11, 1), 355000],
			[gd(2025, 12, 1), 370000]
		];

		var topCategoriasData = [
			["Alimentos Infantiles", 125000],
			["Bebidas Refrescantes", 98000],
			["Lácteos", 87500],
			["Carnes y Embutidos", 76500],
			["Panadería y Pastelería", 65400],
			["Frutas y Verduras", 54300],
			["Snacks y Dulces", 43200],
			["Productos de Limpieza", 32100],
			["Artículos para el Hogar", 21000],
			["Otros", 15600]
		];

		var topRutasData = [
			["Ruta Centro Histórico", 145000],
			["Ruta Norte Industrial", 132000],
			["Ruta Sur Residencial", 118000],
			["Ruta Este Comercial", 105000],
			["Ruta Oeste Empresarial", 92000],
			["Ruta Metropolitana A", 81000],
			["Ruta Metropolitana B", 73000],
			["Ruta Rural Norte", 65000],
			["Ruta Rural Sur", 58000],
			["Ruta Especializada", 51000]
		];

		function gd(year, month, day) {
			return new Date(year, month - 1, day).getTime();
		}

		jQuery(function($) {
			// Gráfico de pie existente
			var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
			var data = [
				{ label: "Pedidos",  data: 1245, color: "#68BC31"},
				{ label: "Sin Pedidos",  data: 935, color: "#AF4E96"}
			];
			function drawPieChart(placeholder, data, position) {
				$.plot(placeholder, data, {
					series: {
						pie: {
							show: true,
							tilt:0.8,
							highlight: {
								opacity: 0.25
							},
							stroke: {
								color: '#fff',
								width: 2
							},
							startAngle: 2
						}
					},
					legend: {
						show: true,
						position: "ne",
						labelBoxBorderColor: null,
						margin:[-30,15]
					},
					grid: {
						hoverable: true,
						clickable: true
					}
				});
			}
			drawPieChart(placeholder, data);

			// Gráfico de ventas mensuales (líneas)
			var ventasOptions = {
				series: {
					lines: { show: true, lineWidth: 2 },
					points: { show: true, radius: 4 }
				},
				xaxis: {
					mode: "time",
					timeformat: "%b %Y",
					tickSize: [1, "month"]
				},
				yaxis: {
					tickFormatter: function(val, axis) {
						return "$" + (val / 1000).toFixed(0) + "k";
					}
				},
				grid: {
					hoverable: true,
					clickable: true,
					borderWidth: 1,
					borderColor: '#ccc',
					backgroundColor: { colors: ["#fff", "#f9f9f9"] }
				}
			};
			$.plot("#ventas-mensuales-chart", [ventasMensualesData], ventasOptions);

			// Gráfico de barras para top categorías
			var categoriasOptions = {
				series: {
					bars: {
						show: true,
						barWidth: 0.6,
						align: "center",
						fillColor: { colors: [{ opacity: 0.8 }, { opacity: 0.1 }] }
					}
				},
				xaxis: {
					mode: "categories",
					tickLength: 0
				},
				yaxis: {
					tickFormatter: function(val, axis) {
						return "$" + (val / 1000).toFixed(0) + "k";
					}
				},
				grid: {
					hoverable: true,
					borderWidth: 1,
					borderColor: '#ccc',
					backgroundColor: { colors: ["#fff", "#f9f9f9"] }
				}
			};
			$.plot("#top-categorias-chart", [topCategoriasData], categoriasOptions);

			// Gráfico de barras para top rutas
			var rutasOptions = {
				series: {
					bars: {
						show: true,
						barWidth: 0.6,
						align: "center",
						fillColor: { colors: [{ opacity: 0.8 }, { opacity: 0.1 }] }
					}
				},
				xaxis: {
					mode: "categories",
					tickLength: 0
				},
				yaxis: {
					tickFormatter: function(val, axis) {
						return "$" + (val / 1000).toFixed(0) + "k";
					}
				},
				grid: {
					hoverable: true,
					borderWidth: 1,
					borderColor: '#ccc',
					backgroundColor: { colors: ["#fff", "#f9f9f9"] }
				}
			};
			$.plot("#top-rutas-chart", [topRutasData], rutasOptions);
		});
	</script>
