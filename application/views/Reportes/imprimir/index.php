<?php 
require __DIR__.'/vendor/autoload.php';
 
 use Spipu\Html2Pdf\Html2Pdf;

 $html2pdf=new Html2Pdf('P','A4','es','true','UTF-8');
 $html2pdf->writeHTML('<h1>Hola mundo!!! desde html2pdf</h1> <br> <h2>Más Informacion</h2>');
 $html2pdf->output('prueba.pdf');

 ?>
