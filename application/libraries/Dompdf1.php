<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter PDF Library
 *
 * Generate PDF's in your CodeIgniter applications.
 *
 * @package			CodeIgniter
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Chris Harvey
 * @license			MIT License
 * @link			https://github.com/chrisnharvey/CodeIgniter-PDF-Generator-Library
 */
use Dompdf\Dompdf;
class Dompdf1
{
	
	public function generatePdf($content, $fileName){
        require 'vendor/autoload.php';
		$dompdf = new Dompdf();  
                $dompdf->set_base_path($_SERVER['SERVER_NAME'].'/assets/admin/sketch_custom/css/dompdf.css');
                $dompdf->loadHtml($content);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'landscape');
                
		// Render the HTML as PDF
		$dompdf->render();
                 
		$output = $dompdf->output();
                
    	if( file_put_contents($_SERVER['DOCUMENT_ROOT'].'/assets/pdf_for_mail_uploads/'.$fileName, $output) ){
    		return true;
    	}

		// Output the generated PDF to Browser
		// $output->stream();
	}
}
