 $(".cMiles").keyup(function(event) {
       var id=$(this).attr("id");

      var textbox=$("#"+id).val();
      var pos=textbox.indexOf(".");
      //alert(pos);
      if ((pos!=-1)){
        //alert("hola 1");
        var cadena=$("#"+id).val().split('.');
      var enteros=cadena[0];

      if (pos>0){
        var punto=".";
        var decimales=cadena[1];
      }
      else {
        //alert(pos);
        var punto=".";
        var decimales="";
      }
      }
      else {
        var cadena=$("#"+id).val();
      var enteros=cadena;
        var punto="";
        var decimales="";
      }
      
      var entrada = enteros.split(',').join('');
      
    entrada = entrada.split('').reverse();
      //alert(entrada);
    var salida = [];
    var aux = '';
    var largo=entrada.length;
    var bandera=0;
    var contador=1;
    var xxx=",";
    var residuo="";
    xxx="";
    for(i = 0; i < largo; i++){
      //alert(i);
      if (contador==3){
        xxx=xxx+entrada[i];
        salida.push(xxx);
        xxx="";
        contador=1;
        residuo="";
      }
      else {
        xxx=xxx+entrada[i];
        contador=contador+1;
        residuo=xxx;

      }

    }
    if (residuo!=""){
      salida.push(residuo);
    }
    textbox=salida.join(',').split("").reverse().join('');


       textbox=textbox+punto+decimales;

       $("#"+id).val(textbox);
    });

 function formatCMiles(id,valor){
  //alert("Id: "+id+" Valor: "+valor);

      var textbox=valor.toString();
     // alert(textbox);
      var pos=textbox.indexOf(".");
      //alert(pos);
      if ((pos!=-1)){
        
        var cadena=$("#"+id).val().split('.');
      var enteros=cadena[0];

      if (pos>0){
        var punto=".";
        var decimales=cadena[1];
      }
      else {
        //alert(pos);
        var punto=".";
        var decimales="";
      }
      }
      else {
        var cadena=$("#"+id).val();
      var enteros=cadena;
        var punto="";
        var decimales="";
      }
      
      var entrada = enteros.split(',').join('');
      
    entrada = entrada.split('').reverse();

    //alert("Entrada "+entrada);
    var salida = [];
    var aux = '';
    var largo=entrada.length;
    var bandera=0;
    var contador=1;
    var xxx=",";
    var residuo="";
    xxx="";
    for(i = 0; i < largo; i++){
      //alert(i);
      if (contador==3){
        xxx=xxx+entrada[i];
        salida.push(xxx);
        xxx="";
        contador=1;
        residuo="";
      }
      else {
        xxx=xxx+entrada[i];
        contador=contador+1;
        residuo=xxx;

      }

    }
    if (residuo!=""){
      salida.push(residuo);
    }
    textbox=salida.join(',').split("").reverse().join('');


       textbox=textbox+punto+decimales;

       $("#"+id).val(textbox);
 }

 function verificar(e,elID){
            var id=elID;
            //alert(elID);
            var textbox=$("#"+elID).val();
      var pos=textbox.indexOf(".");
      //alert(pos);
      if ((pos!=-1)){
        //alert("hola 1");
        var cadena=$("#"+elID).val().split('.');
      var enteros=cadena[0];

      if (pos>0){
        var punto=".";
        var decimales=cadena[1];
      }
      else {
        //alert(pos);
        var punto=".";
        var decimales="";
      }
      }
      else {
        var cadena=$("#"+elID).val();
      var enteros=cadena;
        var punto="";
        var decimales="";
      }
      
      var entrada = enteros.split(',').join('');
      
    entrada = entrada.split('').reverse();
      //alert(entrada);
    var salida = [];
    var aux = '';
    var largo=entrada.length;
    var bandera=0;
    var contador=1;
    var xxx=",";
    var residuo="";
    xxx="";
    for(i = 0; i < largo; i++){
      //alert(i);
      if (contador==3){
        xxx=xxx+entrada[i];
        salida.push(xxx);
        xxx="";
        contador=1;
        residuo="";
      }
      else {
        xxx=xxx+entrada[i];
        contador=contador+1;
        residuo=xxx;

      }

    }
    if (residuo!=""){
      salida.push(residuo);
    }
    textbox=salida.join(',').split("").reverse().join('');


       textbox=textbox+punto+decimales;

       $("#"+elID).val(textbox);
 }