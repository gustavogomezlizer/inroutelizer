<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function CREARINPUTTEXT($pId, $pName, $span, $placeholder, $pAtributos='', $requerido=0)
{
    $textolabel = $placeholder;
    if($requerido==1)
    {
        $textolabel = $placeholder.'*';
    }   

    $textolabel = strtoupper($textolabel); 

    $cadena = "<div class='form-group $span'>";    
    $cadena .= "<label for='$pId'>$textolabel</label>";
	$cadena .= "<input type='text' class='form-control input-sm' id='$pId' name='$pName' placeholder='$placeholder' $pAtributos>";    
    $cadena .= "</div>";
    echo $cadena;
}

function CREARINPUTFREE($pId, $pName, $span, $placeholder, $pType, $pLabel=1, $pAtributos='', $requerido=0)
{
    $textolabel = $placeholder;
    if($requerido==1)
    {
        $textolabel = $placeholder.'*';
    }   

    $textolabel = strtoupper($textolabel); 

    $cadena = "<div class='form-group $span'>";    
    $cadena .= ($pLabel==1) ? "<label for='$pId'>$textolabel</label>" : "";
    $cadena .= "<input type='$pType' class='form-control input-sm' id='$pId' name='$pName' placeholder='$placeholder' $pAtributos>";    
    $cadena .= "</div>";
    echo $cadena;
}

function CREARINPUTDATE($pId, $pName, $span, $placeholder, $pAtributos='', $requerido=0)
{
    $textolabel = $placeholder;
    if($requerido==1)
    {
        $textolabel = $placeholder.'*';
    }   

    $textolabel = strtoupper($textolabel); 

    $cadena = "<div class='form-group $span'>";    
    $cadena .= "<label for='$pId'>$textolabel</label>";
    $cadena .= "<input type='date' class='form-control input-sm' id='$pId' name='$pName' placeholder='$placeholder' $pAtributos>";    
    $cadena .= "</div>";
    echo $cadena;
}

function CREARINPUTTEXTGROUP($pId, $pName, $span, $placeholder, $pGro, $pAtributos='', $requerido=0)
{
    $textolabel = $placeholder;
    if($requerido==1)
    {
        $textolabel = $placeholder.'*';
    } 

    $textolabel = strtoupper($textolabel);   

    $cadena = "<div class='form-group $span'>";    
    $cadena .= "<label for='$pId'>$textolabel</label>";
    $cadena .= "<div class='input-group'>";
    $cadena .= "<input type='text' class='form-control input-sm' id='$pId' name='$pName' placeholder='$placeholder' $pAtributos>";    
    $cadena .= "<p class='input-group-addon'>$pGro</p>";
    $cadena .= "</div>";
    $cadena .= "</div>";
    echo $cadena;
}

function CREARINPUTTEXTGROUPLEFT($pId, $pName, $span, $placeholder, $pGro, $label=1, $pAtributos='', $requerido=0)
{
    $textolabel = $placeholder;
    if($requerido==1)
    {
        $textolabel = $placeholder.'*';
    } 

    $textolabel = strtoupper($textolabel);   

    $cadena = "<div class='form-group $span'>";    
    ($label==1) ? $cadena .= "<label for='$pId'>$textolabel</label>" : "";
    $cadena .= "<div class='input-group'>";    
    $cadena .= "<p class='input-group-addon'>$pGro</p>";
    $cadena .= "<input type='text' class='form-control input-sm' id='$pId' name='$pName' placeholder='$placeholder' $pAtributos>";
    $cadena .= "</div>";
    $cadena .= "</div>";
    echo $cadena;
}

function CREARINPUTFILE($pId, $pName, $span, $placeholder, $pAtributos='', $requerido=0)
{
    $textolabel = $placeholder;
    if($requerido==1)
    {
        $textolabel = $placeholder.'*';
    }

    $textolabel = strtoupper($textolabel);    

    $cadena = "<div class='form-group $span'>";
    $cadena .= "<label for='$pId'>$textolabel</label>";
    $cadena .= "<input type='file' class='form-control input-sm' id='$pId' name='$pName' $pAtributos>";
    $cadena .= "</div>";
    echo $cadena;
}

function CREARBUTTON($pId, $pName, $span, $placeholder, $class="form-control input-sm btn btn-success")
{    
    $cadena = "<div class='form-group $span'>";
    $cadena .= "<label for='$pId'>&nbsp;</label>";
    $cadena .= "<button type='button' class='$class' id='$pId' name='$pName'>$placeholder</button>";
    $cadena .= "</div>";
    echo $cadena;
}

function CREARSELECT($pId, $pName, $span, $placeholder, $pDatos, $requerido=0)
{
    $textolabel = $placeholder;
    if($requerido==1)
    {
        $textolabel = $placeholder.'*';
    }

    $textolabel = strtoupper($textolabel);

    $cadena = "<div class='form-group $span $pId'>";
    $cadena .= "<label for='$pId'>$textolabel</label>";
    $cadena .= "<select class='form-control input-sm' id='$pId' name='$pName'>";

    if($pDatos != '')
    {
        $cadena .= "<option value='99999:SELECCIONE UN REGISTRO'>SELECCIONE UN REGISTRO</option>";
        foreach ($pDatos as $key => $value) {        
            $cadena .= "<option value='$key'>$value</option>";
        }
    }
    else
    {
        $cadena .= "<option value='99999:SELECCIONE UN REGISTRO'>SELECCIONE UN REGISTRO</option>";
    }           

    $cadena .= "</select>";
    $cadena .= "</div>";
    echo $cadena;
}

function CREARSELECTDATOSBD($pId, $pName, $span, $placeholder, $pDatos, $requerido=0)
{
    $textolabel = $placeholder;
    if($requerido==1)
    {
        $textolabel = $placeholder.'*';
    }

    $textolabel = strtoupper($textolabel);

    $cadena = "<div class='form-group $span $pId'>";
    $cadena .= "<label for='$pId'>$textolabel</label>";
    $cadena .= "<select class='form-control input-sm' id='$pId' name='$pName'>";

    if($pDatos != '')
    {
        foreach ($pDatos as $item) {        
            $cadena .= "<option value='$item->id'>$item->descripcion</option>";
        }
    }           

    $cadena .= "</select>";
    $cadena .= "</div>";
    echo $cadena;
}

function CREARSELECTDATOSBDTWOVALUES($pId, $pName, $span, $placeholder, $pDatos, $requerido=0)
{
    $textolabel = $placeholder;
    if($requerido==1)
    {
        $textolabel = $placeholder.'*';
    }

    $textolabel = strtoupper($textolabel);

    $cadena = "<div class='form-group $span $pId'>";
    $cadena .= "<label for='$pId'>$textolabel</label>";
    $cadena .= "<select class='form-control input-sm' id='$pId' name='$pName'>";

    if($pDatos != '')
    {
        $cadena .= "<option value='9999:SELECCIONE UN REGISTRO'>SELECCIONE UN REGISTRO</option>";
        foreach ($pDatos as $item) {        
            $cadena .= "<option value='$item->id:"."$item->descripcion'>$item->descripcion</option>";
        }
    }           

    $cadena .= "</select>";
    $cadena .= "</div>";
    echo $cadena;
}

function CREARSELECTESTADOS($pId, $pName, $span, $placeholder, $pDatos, $requerido=0)
{
    $textolabel = $placeholder;
    if($requerido==1)
    {
        $textolabel = $placeholder.'*';
    }

    $textolabel = strtoupper($textolabel);

    $cadena = "<div class='form-group $span $pId'>";
    $cadena .= "<label for='$pId'>$textolabel</label>";
    $cadena .= "<select class='form-control input-sm' id='$pId' name='$pName'>";


    $cadena .= "<option value='99999:SELECCIONE UN REGISTRO'>SELECCIONE UN REGISTRO</option>";
    foreach ($pDatos as $value) {        
        $cadena .= "<option value='$value->idEstado:"."$value->estado'>$value->estado</option>";
    }       

    $cadena .= "</select>";
    $cadena .= "</div>";
    echo $cadena;
}

function CREARCHECK($pId, $pName, $pLabel)
{    
    $cadena = "<div class='checkbox'>";
    $cadena .= "<label><input id='$pId' name='$pName' type='checkbox'>$pLabel</label>";
    $cadena .= "</div>";
    echo $cadena;
}