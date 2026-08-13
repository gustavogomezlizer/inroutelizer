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
									Principal
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
								
								<div align="center" class="col-md-12">
									
									<div class="col-md-12 infobox infobox-green">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-dollar"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number">
												<?php echo FORMATO_DINERO($datosPedidos->row()->totalpedidos); ?>
											</span>
											<div class="infobox-content">Pedidos <div class="badge badge-primary"><?php echo $datosPedidos->row()->cuantospedidos; ?></div></div>
										</div>
										
									</div>
								
									<div class="col-md-12 infobox infobox-blue">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-street-view"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo $visitas; ?></span>
											<div class="infobox-content">Visitas</div>
										</div>
									</div>

									<div class="col-md-12 infobox infobox-pink">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-truck"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo $rutas; ?></span>
											<div class="infobox-content">Rutas</div>
										</div>											
									</div>
								
									<div class="col-md-12 infobox infobox-black">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-users"></i>
										</div>

										<div class="infobox-data">
											<span class="infobox-data-number"><?php echo $clientes; ?></span>
											<div class="infobox-content">Clientes </div>
										</div>
										
									</div>

									<div class="col-md-12 infobox infobox-grey">
										<div class="infobox-icon">
											<i class="ace-icon fa fa-bar-chart"></i>
										</div>

										<div class="infobox-data">
											<?php 
											if($datosPedidos->row()->cuantospedidos!=0 AND $visitas!=0){
												$porcentaje=$visitas/100;
												$efectividad=FORMATO_PORCENTAJEDEC($datosPedidos->row()->cuantospedidos/$porcentaje);
												$cPedidos=$datosPedidos->row()->cuantospedidos;
											}
											else{
													$porcentaje=0;
												$efectividad=0;
												$cPedidos=0;
											}
											?>
											<span class="infobox-data-number"><?php echo $efectividad; ?></span>
											<div class="infobox-content">Efectividad <div class="badge badge-primary"><?php echo $cPedidos; ?>/<?php echo $visitas; ?></div></div>
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
												Visitas con Efectividad.
											</h5>

											<div class="widget-toolbar no-border">
												<div>
													
												</div>
											</div>
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
														<h4 class="bigger pull-right"><?php echo $visitas; ?></h4>
													</div>

													<div class="grid3">
														<span class="grey">
															<i class="ace-icon fa fa-dollar fa-2x green"></i>
															&nbsp; Pedidos
														</span>
														<h4 class="bigger pull-right"><?php echo $ventaregistrada; ?></h4>
													</div>

													<div class="grid3">
														<span class="grey">
															<i class="ace-icon fa fa-arrow-circle-down fa-2x red"></i>
															&nbsp; Sin Pedidos
														</span>
														<h4 class="bigger pull-right"><?php echo $nopedidos; ?></h4>
													</div>
												</div>
											</div><!-- /.widget-main -->
										</div><!-- /.widget-body -->
									</div><!-- /.widget-box -->
								</div><!-- /.col -->
							</div>
							
						</div><!-- /.col -->
					</div><!-- /.row -->
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
		function crearGraficoPay(){
				var visitadosinventa=<?php echo $visitadosinventa; ?>;
				var ventaregistrada=<?php echo $ventaregistrada; ?>;
				var yateniaproducto=<?php echo $yateniaproducto; ?>;
				var contactonoencontrado=<?php echo $contactonoencontrado; ?>;
				var tiendacerrada=<?php echo $tiendacerrada; ?>;
				var noteniadinero=<?php echo $noteniadinero; ?>;
				var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
			  var data = [
				{ label: "Visitado sin Venta",  data: visitadosinventa, color: "#57B3D9"},
				{ label: "Venta registrada",  data: ventaregistrada, color: "#04601C"},
				{ label: "Ya tenia producto",  data: yateniaproducto, color: "#C7E524"},
				{ label: "Contacto no encontrado",  data: contactonoencontrado, color: "#D528B6"},
				{ label: "Tienda cerrada",  data: tiendacerrada, color: "#CC8C11"},
				{ label: "No tenia dinero",  data: noteniadinero, color: "#B70B17"}
				
			  ]
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
						position: position || "ne", 
						labelBoxBorderColor: null,
						margin:[-30,15]
					}
					,
					grid: {
						hoverable: true,
						clickable: true
					}
				 })
			 }
			 drawPieChart(placeholder, data);
			
			 /**
			 we saved the drawing function and the data to redraw with different position later when switching to RTL mode dynamically
			 so that's not needed actually.
			 */
			 placeholder.data('chart', data);
			 placeholder.data('draw', drawPieChart);
			
			
			  //pie chart tooltip example
			  var $tooltip = $("<div class='tooltip top in'><div class='tooltip-inner'></div></div>").hide().appendTo('body');
			  var previousPoint = null;
			
			  placeholder.on('plothover', function (event, pos, item) {
				if(item) {
					if (previousPoint != item.seriesIndex) {
						previousPoint = item.seriesIndex;
						var tip = item.series['label'] + " : " + numberFormat(item.series['percent']+'')+'%';
						$tooltip.show().children(0).text(tip);
					}
					$tooltip.css({top:pos.pageY + 10, left:pos.pageX + 10});
				} else {
					$tooltip.hide();
					previousPoint = null;
				}
				
			 });
			}
			jQuery(function($) {
				$('.easy-pie-chart.percentage').each(function(){
					var $box = $(this).closest('.infobox');
					var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
					var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
					var size = parseInt($(this).data('size')) || 50;
					$(this).easyPieChart({
						barColor: barColor,
						trackColor: trackColor,
						scaleColor: false,
						lineCap: 'butt',
						lineWidth: parseInt(size/10),
						animate: ace.vars['old_ie'] ? false : 1000,
						size: size
					});
				})
			
				$('.sparkline').each(function(){
					var $box = $(this).closest('.infobox');
					var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
					$(this).sparkline('html',
									 {
										tagValuesAttribute:'data-values',
										type: 'bar',
										barColor: barColor ,
										chartRangeMin:$(this).data('min') || 0
									 });
				});
			
			
			  //flot chart resize plugin, somehow manipulates default browser resize event to optimize it!
			  //but sometimes it brings up errors with normal resize event handlers
			  $.resize.throttleWindow = false;
			
			 crearGraficoPay();
			
				/////////////////////////////////////
				$(document).one('ajaxloadstart.page', function(e) {
					$tooltip.remove();
				});
			
			
			
			
				var d1 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.5) {
					d1.push([i, Math.sin(i)]);
				}
			
				var d2 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.5) {
					d2.push([i, Math.cos(i)]);
				}
			
				var d3 = [];
				for (var i = 0; i < Math.PI * 2; i += 0.2) {
					d3.push([i, Math.tan(i)]);
				}
				
			
				var sales_charts = $('#sales-charts').css({'width':'100%' , 'height':'220px'});
				$.plot("#sales-charts", [
					{ label: "Domains", data: d1 },
					{ label: "Hosting", data: d2 },
					{ label: "Services", data: d3 }
				], {
					hoverable: true,
					shadowSize: 0,
					series: {
						lines: { show: true },
						points: { show: true }
					},
					xaxis: {
						tickLength: 0
					},
					yaxis: {
						ticks: 10,
						min: -2,
						max: 2,
						tickDecimals: 3
					},
					grid: {
						backgroundColor: { colors: [ "#fff", "#fff" ] },
						borderWidth: 1,
						borderColor:'#555'
					}
				});
			
			
				$('#recent-box [data-rel="tooltip"]').tooltip({placement: tooltip_placement});
				function tooltip_placement(context, source) {
					var $source = $(source);
					var $parent = $source.closest('.tab-content')
					var off1 = $parent.offset();
					var w1 = $parent.width();
			
					var off2 = $source.offset();
					//var w2 = $source.width();
			
					if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
					return 'left';
				}
			
			
				$('.dialogs,.comments').ace_scroll({
					size: 300
			    });
				
				
				//Android's default browser somehow is confused when tapping on label which will lead to dragging the task
				//so disable dragging when clicking on label
				var agent = navigator.userAgent.toLowerCase();
				if(ace.vars['touch'] && ace.vars['android']) {
				  $('#tasks').on('touchstart', function(e){
					var li = $(e.target).closest('#tasks li');
					if(li.length == 0)return;
					var label = li.find('label.inline').get(0);
					if(label == e.target || $.contains(label, e.target)) e.stopImmediatePropagation() ;
				  });
				}
			
				$('#tasks').sortable({
					opacity:0.8,
					revert:true,
					forceHelperSize:true,
					placeholder: 'draggable-placeholder',
					forcePlaceholderSize:true,
					tolerance:'pointer',
					stop: function( event, ui ) {
						//just for Chrome!!!! so that dropdowns on items don't appear below other items after being moved
						$(ui.item).css('z-index', 'auto');
					}
					}
				);
				$('#tasks').disableSelection();
				$('#tasks input:checkbox').removeAttr('checked').on('click', function(){
					if(this.checked) $(this).closest('li').addClass('selected');
					else $(this).closest('li').removeClass('selected');
				});
			
			
				//show the dropdowns on top or bottom depending on window height and menu position
				$('#task-tab .dropdown-hover').on('mouseenter', function(e) {
					var offset = $(this).offset();
			
					var $w = $(window)
					if (offset.top > $w.scrollTop() + $w.innerHeight() - 100) 
						$(this).addClass('dropup');
					else $(this).removeClass('dropup');
				});
			
			})
				var elusuario="<?php echo $usuario; ?>";
							//alert(elusuario);
							var arrayUsuario=elusuario.split(",");

							for (var i = 0; i < arrayUsuario.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbUsuario option[value='"+arrayUsuario[i]+"']").attr("selected",true);
								$("#cmbUsuario").change();
							}
				var laruta="<?php echo $ruta; ?>";
							//alert(elusuario);
							var arrayRuta=laruta.split(",");

							for (var i = 0; i < arrayRuta.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbRuta option[value='"+arrayRuta[i]+"']").attr("selected",true);
								$("#cmbRuta").change();
							}
				var lasucursal="<?php echo $ruta; ?>";
							//alert(elusuario);
							var arraySucursal=lasucursal.split(",");

							for (var i = 0; i < arraySucursal.length; i++) {
								//alert(arrayRuta[i]);
								$("#cmbSucursal option[value='"+arraySucursal[i]+"']").attr("selected",true);
								$("#cmbSucursal").change();
							}
		</script>
	</body>
</html>