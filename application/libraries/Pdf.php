<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  DOMPDF
* 
* Author: Jd Fiscus
* 	 	  jdfiscus@gmail.com
*         @iamfiscus
*          
*
* Origin API Class: http://code.google.com/p/dompdf/
* 
* Location: http://github.com/iamfiscus/Codeigniter-DOMPDF/
*          
* Created:  06.22.2010 
* 
* Description:  This is a Codeigniter library which allows you to convert HTML to PDF with the DOMPDF library
* 
*/
class Pdf {
		
	public function __construct() {
		
		require_once APPPATH.'libraries/dompdf/dompdf/autoload.inc.php';
		
		$pdf = new Dompdf\Dompdf();
		
		$CI =& get_instance();
		$CI->dompdf = $pdf;
		
	}
	
}