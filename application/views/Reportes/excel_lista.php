<?php 
$data['title']="LIZER Reportes-Sellout";
$this->load->view("vHead", $data); 
?>
<?php $this->load->view("vMenu"); ?>

<div class="main-content">
    <div class="main-content-inner">
        <div class="page-content">

            <div class="page-header">
                <h1>
                    <strong>In Route</strong> <i>Sofware de Venta</i>
                    <small>
                        <i class="ace-icon fa fa-angle-double-right"></i>
                        Reportes / Sellout
                    </small>
                </h1>
            </div>

            <div align="right">
                <a href="<?php echo CREPORTES('Sellout') ?>" class="btn btn-success btn-sm">Crear nuevo</a>
            </div><br>

            <div class="row">
                <div class="table-responsive"> <!-- empieza div que contiene a la tabla -->
                    <table id="mytable" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Descripcion</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach($listado as $item) { ?>
                                <tr>
                                    <td><?php echo $item->fecha." ".$item->hora; ?></td>
                                    <td><?php echo $item->tipo; ?></td>
                                    <td><?php echo $item->descripcion; ?></td>
                                    <td>
                                        <button onclick="descargar('<?php echo $item->id; ?>')" class="btn btn-primary btn-sm">Descargar</button>
                                        <button onclick="sendmail('<?php echo $item->id; ?>')" class="btn btn-primary btn-sm">Enviar</button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>            
        </div>
    </div>
</div>

<div id="modal_send_email" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Enviar Sellout</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input id="txtIdSellout" class="form-control" type="hidden" placeholder="Correo" />
        <input id="txtEmail" class="form-control" type="text" placeholder="Correo" />
        <textarea id="txtMensaje" class="form-control" placeholder="mensaje"></textarea>
      </div>
      <div class="modal-footer">
        <button id="btnEnviar" type="button" class="btn btn-primary">Enviar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view("vCopyright"); ?>

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>

<?php $this->load->view("vFooter"); ?>

<script>

var myTable = $('#mytable').DataTable({
					"language": {
				            "url": "<?php echo RUTAFOLDERASSETS('json/datatablesspanish.json'); ?>"
				        },
                    "pageLength": -1,
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                    "order": [[0,"asc"]],
                });
                
    function descargar(val)
    {
        $.post("<?php echo CREPORTES() ?>" + "getSellout", {id: val } , function(data){
            var datos = JSON.parse(data);
            var sellout = datos.sellout;

            var link = document.createElement('a');
            link.download = 'sellout.txt';
            var blob = new Blob([replaceAll(sellout,"\n","\r\n")], {type: 'text/plain'});
            link.href = window.URL.createObjectURL(blob);
            link.click();

        });
    }

    function sendmail(val)
    {
        $("#txtIdSellout").val(val);
        $("#modal_send_email").modal("show");
    }

    $("#btnEnviar").on("click", function(){
        $.post("<?php echo CREPORTES() ?>" + "SendEmail", {id: $("#txtIdSellout").val(), correo: $("#txtEmail").val(), mensaje: $("#txtMensaje").val()  } , function(data){
            if(data.trim()=="1"){
                window.location.reload();
            }else{
                alert("ocurrio un error al enviar el correo");
            }
        });
    });

    function replaceAll(str, find, replace) {
    return str.replace(new RegExp(find, 'g'), replace);
    }

    $("#btnGuardar").on("click", function(){
        $.post("<?php echo CREPORTES() ?>" + "guardar_excel", {fecha: $("#txtFecha").val(), tipo: $("#cmbTipoDocumento").val(), sellout: $("#txtSellout").val() } , function(data){
            if(data.trim()==1){
                window.location.reload();
            }else{
                alert("ocurrio un error al guardar el archivo");
            }
        });
    });

</script>