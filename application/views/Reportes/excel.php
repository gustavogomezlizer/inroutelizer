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
            <?php //echo "<br>".$sucursal; ?>
        </div>

        <div class="row">
            <div class="col-xs-2">
                <div class="form-group">
                    <label>Fecha:</label>
                    <input class="form-control" type="date" id="txtFecha" name="fecha" value="<?php echo date("Y-m-d") ?>"/>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <label for="form-field-select-3">Tipo documento:</label>
                    <select class="chosen-select form-control" id="cmbTipoDocumento" data-placeholder="Choose a State...">
                        <option value="0">Seleccione un tipo de documento</option>
                        <?php foreach($tipodocumento as $item) { ?>
                            <option value="<?php echo $item->id ?>"><?php echo $item->tipodocumento ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="form-group">
                    <label>Descripción:</label>
                    <input class="form-control" type="text" id="txtDescripcion" name="descripcion"/>
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group">
                    <label>Seleccionar archivo:</label>
                    <input class="form-control" type="file" id="txtExcel" name="archivoexcel" disabled/>
                </div>
            </div>            
            <div class="col-xs-1">
                <div class="form-group">
                    <br>
                    <button class="btn btn-primary btn-sm" id="btnGuardar" style="display:none">Guardar</button>
                    <!--<label id="lblProgress">0</label>-->
                </div>
            </div>
        </div>

        <div class="row">
            <div id="message" class="alert alert-danger" role="alert" style="display:none"></div>
        </div>

        <textarea id="txtSellout" style="display:none"></textarea>

        <div class="table-responsive">
            <table id="mitabla" class="table table-sm table-striped table-bordered table-hover">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>


        <!--<?php /*print_r($header); ?>
            <table  border="1">
            <?php foreach ($header as $key => $value) { ?>
                <tr>
                    <?php foreach ($value as $dt) { ?>			
                        <td><?php echo $dt; ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>

            <?php foreach ($values as $key => $value) { ?>
                <tr>
                    <?php foreach ($value as $dt) { ?>			
                        <td><?php echo $dt; ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>

            </table>

            <?php $texto = ""; foreach ($values as $key => $value) { ?>        
                <?php foreach ($value as $dt) { ?>
                    <?php $texto = $texto.$dt.'|'; ?>
                <?php } ?>
            <?php } ?>

            <textarea><?php echo $texto */?></textarea>-->
        </div>
    </div>
</div>

<?php $this->load->view("vCopyright"); ?>

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>

<?php $this->load->view("vFooter"); ?>
<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/jszip.js"></script>
<script>

    $("#btnGuardar").on("click", function(){
        $('#mitabla').addClass('loadingtable');
        $.post("<?php echo CREPORTES() ?>" + "guardar_excel", 
            {
                fecha: $("#txtFecha").val(), 
                tipo: $("#cmbTipoDocumento").val(), 
                sellout: $("#txtSellout").val(),
                descripcion: $("#txtDescripcion").val()
            } , function(data){
            if(data.trim()==1){
                window.location = "<?php echo CREPORTES('ListadoSellout') ?>";
            }else{
                alert("ocurrio un error al guardar el archivo");
            }
        })
        .always(function(){
            $('#mitabla').removeClass('loadingtable');
        });
    });

    $('#txtExcel').ace_file_input({
        no_file:'Sin archivo...',
        btn_choose:'Seleccionar',
        btn_change:'Cambiar',
        droppable:false,
        onchange:null,
        thumbnail:false //| true | large
        //whitelist:'gif|png|jpg|jpeg'
        //blacklist:'exe|php'
        //onchange:''
        //
    });

    $(".remove").on("click", function(){
        $("#mitabla thead").html("");
        $("#mitabla tbody").html("");
        $("#txtSellout").val("");
        $("#message").hide();
        $("#btnGuardar").hide();
    });

    $("#txtExcel").on("change", function(){
        $("#mitabla thead").html("");
        $("#mitabla tbody").html("");
        $("#txtSellout").val("");
        $("#message").hide();
        $("#btnGuardar").hide();

        if($("#cmbTipoDocumento").val()==1){
            pruebaexcel();
            //upload_file_ventas("txtExcel");
            //upload_file_ventas();
        }else{
            upload_file_inventarios("txtExcel");
        }
    });

    $("#cmbTipoDocumento").on("change", function(){
        $("#mitabla thead").html("");
        $("#mitabla tbody").html("");
        $("#txtSellout").val("");
        $('#txtExcel').val("");
        $("#btnGuardar").hide();
        if($(this).val()=="0"){
            $('#txtExcel').prop("disabled", true);
        }else{
            $('#txtExcel').prop("disabled", false);
        }
    });
    
    function _(el)
    {
        return document.getElementById(el);
    }

    function uploadFile2(elemeto)
    {
        $('#mitabla').addClass('loadingtable');

        var file = _(elemeto).files;
        var CONTADOR = file.length;

        if(CONTADOR == 0)
        {
            return;
            window.location.reload();
        }

        var formdata = new FormData();
        formdata.append("archivo", file[0]);
        formdata.append("tipodocumento", $("#cmbTipoDocumento").val());
        var ajax = new XMLHttpRequest();            
        
        ajax.open("POST", "<?php echo CREPORTES() ?>" + "convertir_excel", true);
        ajax.send(formdata);

        ajax.onreadystatechange = function (aEvt) {
            if (ajax.readyState == 4) {
                if(ajax.status == 200)
                {
                    var datos = JSON.parse(ajax.responseText);
                    console.log(datos);
                    var error = datos.error;
                    var message = datos.message;

                    if(error)
                    {
                        $("#message").html(message);
                        $("#message").show();
                    }
                    else
                    {
                        var header = datos.header[1];
                        var headers = "<tr>";
                        var sellout = "";
                        for(var x in header)
                        {
                            headers = headers + "<td>" + header[x].toUpperCase() + "</td>"                        
                        }

                        headers = headers + "</tr>";

                        $("#mitabla thead").append(headers);

                        var values = "";

                        for(var y in datos.values)
                        {
                            var value = datos.values[y];
                            var linea_sellout = "";
                            values = values + "<tr>";
                            for(var x in value)
                            {
                                values = values + "<td>" + value[x] + "</td>";
                                linea_sellout = linea_sellout + value[x] + "|";
                            }

                            linea_sellout = linea_sellout.substring(0, linea_sellout.length - 1) + '\n';
                            sellout = sellout + linea_sellout;
                            values = values + "</tr>";
                        }                    

                        sellout = sellout.substring(0, sellout.length - 1);

                        $("#mitabla tbody").append(values);
                        $("#txtSellout").val(sellout);

                        $("#btnGuardar").show();
                    }
                }
                else
                {
                    $("#message").html("<h3>Ocurrio un error al cargar el archivo</h3>");
                    $("#message").show();
                }
            }

            $('#mitabla').removeClass('loadingtable');
        };        

        /*for(var x=0; x<file.length; x++)
        {            
            var formdata = new FormData();
            formdata.append("archivo", file[x]);            
            formdata.append("idOrden", pIdOrden);
            formdata.append("descripcion", pDescripcion);
            var ajax = new XMLHttpRequest();            
            
            ajax.open("POST", "<?php echo CREPORTES() ?>" + "saveArchivoOrden", true);
            ajax.send(formdata);            
            ajax.onprogress = SubidaImagen;

            ajax.onreadystatechange = function (aEvt) {
                if (ajax.readyState == 4) {
                    if(ajax.status == 200)
                        window.location.reload();
                    else
                        window.location.reload();
                }
            };
            PORCENTAJE = Math.trunc(100/CONTADOR);
        }*/
    }

    /*function upload_file_ventas()
    {
        $('#mitabla').addClass('loadingtable');

        var fileUpload = $("#txtExcel")[0];

        var rows;
         
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
        if (regex.test(fileUpload.value.toLowerCase())) 
        {
            if (typeof (FileReader) != "undefined") 
            {
                var reader = new FileReader();
 
                //For Browsers other than IE.
                if (reader.readAsBinaryString) 
                {
                    reader.onload = function (e) 
                    {
                        rows = ProcessExcel(e.target.result);
                        
                        var formdata = new FormData();
                        //formdata.append("archivo", file[0]);
                        formdata.append("archivo", rows);
                        formdata.append("tipodocumento", $("#cmbTipoDocumento").val());
                        formdata.append("fecha", $("#txtFecha").val());
                        var ajax = new XMLHttpRequest();            
                        
                        //ajax.timeout = 30000;
                        ajax.open("POST", "<?php echo CREPORTES() ?>" + "convertir_excel_ventas_2", true);
                        ajax.upload.onprogress = function (event) {
                            if (event.lengthComputable)
                            {
                                var percentComplete = (event.loaded / event.total) * 100;
                                $("#lblProgress").text(percentComplete);
                            }
                            else 
                            {
                                console.log("Unable to compute progress information since the total size is unknown");
                            }
                        };
                        ajax.send(formdata);

                        ajax.onreadystatechange = function (aEvt) {
                            if (ajax.readyState == 4) {
                                if(ajax.status == 200)
                                {
                                    var datos = JSON.parse(ajax.responseText);
                                    var error = datos.error;
                                    var message = datos.message;
                                    var data = datos.data;

                                    if(error)
                                    {
                                        $("#message").html(message);
                                        $("#message").show();
                                    }
                                    else
                                    {
                                        var headers = "<tr>" +
                                        "<td>NO.PEDIDO</td>" +
                                        "<td>NO.FACTURA</td>" +
                                        "<td>FECHA</td>" +
                                        "<td>NO.SUCURSAL</td>" +
                                        "<td>VENDEDOR</td>" +
                                        "<td>NO.CLIENTE</td>" +
                                        "<td>CODIGO BARRAS</td>" +
                                        "<td>NO.UNIDADES</td>" +
                                        "<td>VENTA PESOS</td>" +
                                        "<td>PRODUCTO</td>" +
                                        "</tr>";

                                        $("#mitabla thead").append(headers);

                                        var sellout = "";
                                        var values = "";

                                        for(var x in data)
                                        {
                                            var row = data[x];
                                            var linea_sellout = "";

                                            values = values + "<tr>";
                                            values = values + "<td>" + row.nopedido + "</td>";
                                            values = values + "<td>" + row.nofactura + "</td>";
                                            values = values + "<td>" + row.fecha + "</td>";
                                            values = values + "<td>" + row.nosucursal + "</td>";
                                            values = values + "<td>" + row.vendedor + "</td>";
                                            values = values + "<td>" + row.nocliente + "</td>";
                                            values = values + "<td>" + row.cb + "</td>";
                                            values = values + "<td>" + row.nounidades + "</td>";
                                            values = values + "<td>" + row.ventapesos + "</td>";
                                            values = values + "<td>" + row.producto.replace("_x000D_","") + "</td>";
                                            values = values + "</tr>";

                                            linea_sellout = linea_sellout + (row.nopedido + "|" + row.nofactura + "|" + row.fecha + "|" + row.nosucursal + "|" + row.vendedor + "|" + 
                                            row.nocliente + "|" + row.cb + "|" + row.nounidades + "|" + row.ventapesos + "|" + row.producto.replace("_x000D_","")) + '\n';
                                            //linea_sellout = linea_sellout.substring(0, linea_sellout.length - 1) + '\n';                            
                                            sellout = sellout + linea_sellout;
                                        }                    

                                        sellout = sellout.substring(0, sellout.length - 1);
                                        console.log(sellout);

                                        $("#mitabla tbody").append(values);
                                        $("#txtSellout").val(sellout);

                                        $("#btnGuardar").show();
                                    }
                                }
                                else
                                {
                                    $("#message").html("<h3>Ocurrio un error al cargar el archivo</h3>");
                                    $("#message").show();
                                }
                            }

                            $('#mitabla').removeClass('loadingtable');
                        };
                    };

                    reader.readAsBinaryString(fileUpload.files[0]);
                } 
                else 
                {
                    //For IE Browser.
                    reader.onload = function (e) {
                        var data = "";
                        var bytes = new Uint8Array(e.target.result);
                        for (var i = 0; i < bytes.byteLength; i++) {
                            data += String.fromCharCode(bytes[i]);
                        }

                        rows = ProcessExcel(data);
                        console.log("aqui2");
                    };
                    reader.readAsArrayBuffer(fileUpload.files[0]);
                }
            } 
            else 
            {                
                alert("This browser does not support HTML5.");
            }
        } 
        else 
        {
            alert("Please upload a valid Excel file.");
        }        
    }*/

    function upload_file_inventarios(elemeto)
    {
        $('#mitabla').addClass('loadingtable');

        var file = _(elemeto).files;
        var CONTADOR = file.length;

        if(CONTADOR == 0)
        {
            return;
            window.location.reload();
        }

        var formdata = new FormData();
        formdata.append("archivo", file[0]);
        formdata.append("tipodocumento", $("#cmbTipoDocumento").val());
        formdata.append("fecha", $("#txtFecha").val());
        var ajax = new XMLHttpRequest();            
        
        ajax.open("POST", "<?php echo CREPORTES() ?>" + "convertir_excel_inventarios", true);
        ajax.send(formdata);

        ajax.onreadystatechange = function (aEvt) {
            if (ajax.readyState == 4) {
                if(ajax.status == 200)
                {
                    var datos = JSON.parse(ajax.responseText);
                    var error = datos.error;
                    var message = datos.message;
                    var data = datos.data;

                    if(error)
                    {
                        $("#message").html(message);
                        $("#message").show();
                    }
                    else
                    {
                        var headers = "<tr>" +
                        "<td>FECHA</td>" +
                        "<td>SUCURSAL</td>" +
                        "<td>CB</td>" +
                        "<td>UNIDADES</td>" +
                        "<td>VALOR INVENTARIO</td>" +
                        "<td>DESCRIPCION</td>" +
                        "</tr>";

                        $("#mitabla thead").append(headers);

                        var sellout = "";
                        var values = "";

                        for(var x in data)
                        {
                            var row = data[x];
                            var linea_sellout = "";

                            values = values + "<tr>";
                            values = values + "<td>" + row.fecha + "</td>";
                            values = values + "<td>" + row.sucursal + "</td>";
                            values = values + "<td>" + row.codigobarra + "</td>";
                            values = values + "<td>" + row.unidades + "</td>";
                            values = values + "<td>" + row.valorinventario + "</td>";
                            values = values + "<td>" + row.descripcion.replace("_x000D_","") + "</td>";
                            values = values + "</tr>";

                            linea_sellout = linea_sellout + (row.fecha + "|" + row.sucursal + "|" + row.codigobarra + "|" + row.unidades + "|" + row.valorinventario + "|" + row.descripcion.replace("_x000D_","")) + '\n';
                            //linea_sellout = linea_sellout.substring(0, linea_sellout.length - 1) + '\n';                            
                            sellout = sellout + linea_sellout;
                        }                    

                        sellout = sellout.substring(0, sellout.length - 1);
                        console.log(sellout);

                        $("#mitabla tbody").append(values);
                        $("#txtSellout").val(sellout);

                        $("#btnGuardar").show();

                        $('#mitabla').removeClass('loadingtable');
                    }
                }
                else
                {
                    $('#mitabla').removeClass('loadingtable');
                    $("#message").html("<h3>Ocurrio un error al cargar el archivo</h3>");
                    $("#message").show();
                }
            }
        };
    }

    function convert_fecha(pFecha){
        if(pFecha == null || pFecha == ""){
            return "00000000";
        }else{

            pFecha = pFecha.split('/');
            /*var newdate = pFecha.split("/").reverse().join("-");

            var today = new Date(newdate).toLocaleDateString('es-mx', {
                day : 'numeric',
                month : 'numeric',
                year : 'numeric'
            }).split("/");

            console.log(today);*/

            var fecha = "";
            var dia = pFecha[0];
            var mes = pFecha[1];
            if(dia.length == 1){
                dia = "0" + dia;
            }
            if(mes.length == 1){
                mes = "0" + mes;
            }

            fecha = pFecha[2] + mes + dia;
            return fecha;
        }        
    }

    function  pruebaexcel() {
        $('#mitabla').addClass('loadingtable');
        var fileUpload = $("#txtExcel")[0];

        var rows;
        var datos_productos;
        var datos_usuarios;

        $.get("<?php echo LINKPROYECTO('ListadoUsuariosJson') ?>", function(data){
            datos_usuarios = JSON.parse(data);

            $.get("<?php echo LINKPROYECTO('ListadoProductosJson') ?>", function(data){
                datos_productos = JSON.parse(data);

                //Validate whether File is valid Excel file.
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
                if (regex.test(fileUpload.value.toLowerCase())) 
                {
                    if (typeof (FileReader) != "undefined") 
                    {
                        var reader = new FileReader();
        
                        //For Browsers other than IE.
                        if (reader.readAsBinaryString) 
                        {
                            reader.onload = function (e) 
                            {
                                rows = ProcessExcel(e.target.result);
                                console.log("aqui1");

                                var headers = "<tr>" +
                                "<td>NO.PEDIDO</td>" +
                                "<td>NO.FACTURA</td>" +
                                "<td>FECHA</td>" +
                                "<td>NO.SUCURSAL</td>" +
                                "<td>VENDEDOR</td>" +
                                "<td>NO.CLIENTE</td>" +
                                "<td>CODIGO BARRAS</td>" +
                                "<td>NO.UNIDADES</td>" +
                                "<td>VENTA PESOS</td>" +
                                "<td>PRODUCTO</td>" +
                                "</tr>";

                                $("#mitabla thead").append(headers);

                                var sellout = "";
                                var values = "";

                                for(var x in rows)
                                {
                                    var row = rows[x];
                                    var linea_sellout = "";
                                    
                                    //if(!row["Categoria"].includes("CHOCOLATE IMPULSO"))
                                    if(1==1)
                                    {
                                        var codigobarras = "SINCODIGO";
                                        for(var producto in datos_productos){
                                            if(datos_productos[producto].codigo == row["Código de producto"]){
                                                codigobarras = datos_productos[producto].codigobarras;
                                                break;
                                            }
                                        }

                                        var nombreusuario = "SINUSUARIO";
                                        for(var usuario in datos_usuarios){
                                            if(datos_usuarios[usuario].id == row["ID Vendedor"]){
                                                nombreusuario = datos_usuarios[usuario].usuario;
                                                break;
                                            }
                                        }

                                        //var newdate = row["Fecha"].split("/").reverse().join("");
                                        var newdate = convert_fecha(row["Fecha"]);

                                        values = values + "<tr>";
                                        values = values + "<td>" + row["ID Pedido"] + "</td>";
                                        values = values + "<td>" + row["No factura"] + "</td>";
                                        values = values + "<td>" + newdate + "</td>";
                                        values = values + "<td>" + row["Clave sucursal"] + "</td>";
                                        values = values + "<td>" + nombreusuario + "</td>";
                                        values = values + "<td>" + row["Código Cliente"] + "</td>";
                                        values = values + "<td>" + codigobarras + "</td>";
                                        values = values + "<td>" + row["Cantidad"] + "</td>";
                                        values = values + "<td>" + row["Total Producto"] + "</td>";
                                        values = values + "<td>" + row["Producto"].replace("_x000D_","") + "</td>";
                                        values = values + "</tr>";

                                        linea_sellout = 
                                        linea_sellout + 
                                        (row["ID Pedido"]+ "|" + row["No factura"] + "|" + newdate + "|" + row["Clave sucursal"] + "|" + 
                                        nombreusuario + "|" + row["Código Cliente"] + "|" + codigobarras + "|" + row["Cantidad"] + "|" + row["Total Producto"] + "|" + row["Producto"].replace("_x000D_","")
                                        ) + '\n';

                                        sellout = sellout + linea_sellout;
                                    }                            
                                }                    

                                sellout = sellout.substring(0, sellout.length - 1);

                                $("#mitabla tbody").append(values);
                                $("#txtSellout").val(sellout);
                                $('#mitabla').removeClass('loadingtable');

                                $("#btnGuardar").show();
                            };

                            reader.readAsBinaryString(fileUpload.files[0]);                        
                        } 
                        else 
                        {
                            //For IE Browser.
                            reader.onload = function (e) {
                                var data = "";
                                var bytes = new Uint8Array(e.target.result);
                                for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                                }

                                rows = ProcessExcel(data);
                                console.log("aqui2");
                            };
                            reader.readAsArrayBuffer(fileUpload.files[0]);
                        }
                    } 
                    else 
                    {                
                        alert("This browser does not support HTML5.");
                        $('#mitabla').removeClass('loadingtable');
                    }
                } 
                else 
                {
                    alert("Please upload a valid Excel file.");
                    $('#mitabla').removeClass('loadingtable');
                }
            });
        });

        //return rows;
    };

    function ProcessExcel(data) {
        //Read the Excel File data.
        var workbook = XLSX.read(data, {
            type: 'binary'
        });
 
        //Fetch the name of First Sheet.
        var firstSheet = workbook.SheetNames[3];
 
        //Read all rows from First Sheet into an JSON array.
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
        return excelRows;
    };
</script>