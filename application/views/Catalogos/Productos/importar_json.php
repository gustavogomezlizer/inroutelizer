<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Importar Productos JSON</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        body{
            background:#f5f5f5;
        }

        textarea{
            font-family: Consolas;
            font-size:14px;
        }

        #respuesta{
            white-space: pre-wrap;
        }
    </style>
</head>
<body>

<div class="container mt-5">

    <div class="card">

        <div class="card-header bg-primary text-white">
            <h4>Importar Productos desde JSON</h4>
        </div>

        <div class="card-body">

            <div class="form-group">

                <label>Pega aquí el JSON</label>

                <textarea
                    id="json"
                    class="form-control"
                    rows="20"
                    placeholder='[
{
    "codigo":"AI001",
    "nombre":"GERBER Durazno",
    "precio":11.80,
    "costo":10.24,
    "codigobarras":"7506475102476"
}
]'
                ></textarea>

            </div>

            <button
                class="btn btn-success"
                onclick="importar();">
                Importar
            </button>

            <hr>

            <div id="respuesta"></div>

        </div>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>

function importar(){

    let texto=$("#json").val();

    try{

        JSON.parse(texto);

    }catch(e){

        alert("El JSON no es válido");

        return;

    }

    $.ajax({

        url:"<?php echo LINKPROYECTO('Catalogos/sincronizarProductos'); ?>",

        type:"POST",

        data:{
            json:texto
        },

        beforeSend:function(){

            $("#respuesta").html("Procesando...");

        },

        success:function(r){

            $("#respuesta").html(r);

        },

        error:function(){

            $("#respuesta").html("Ocurrió un error.");

        }

    });

}

</script>

</body>
</html>