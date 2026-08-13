<?php 
require 'assets/html2pdf/vendor/autoload.php';
 
 use Spipu\Html2Pdf\Html2Pdf;
 
//recoger contenido de otro fichero

	ob_start();
 require_once 'venta_view.php';
 $html=ob_get_clean();

 $html = preg_replace('/>\s+</', '><', $html);

 $html2pdf = new Html2Pdf('P','A4','es','true','UTF-8');
 $html2pdf->writeHTML($html);
 $html2pdf->output('venta.pdf');


 ?>

