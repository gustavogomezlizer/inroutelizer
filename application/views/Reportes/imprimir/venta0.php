
 <form action="<?php echo CREPORTES('imprimirPedido2'); ?>" method="POST" id="miformulario">
 <input type="hidden" name="idpedido" value="<?php echo $idpedido; ?>">
 
  
  

 </form>
 <script>
 	 window.onload=function(){
                // Una vez cargada la página, el formulario se enviara automáticamente.
		document.forms["miformulario"].submit();
    }
 </script>

