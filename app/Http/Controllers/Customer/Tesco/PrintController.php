<?php

namespace App\Http\Controllers\Customer\Tesco;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Classes\Modules;
use Response;
use View;
use DB;
use Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Config;
use PDF;
use Carbon\Carbon;

class PrintController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('customer_auth');
    }
	
 	/**
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
    public function printDivTesco(Request $request)
    {
		
		//		 // Custom Header
		//		PDF::setHeaderCallback(function($pdf) {
		//				 $image_file= K_PATH_IMAGES.'logo_example.jpg';
		//				 $pdf->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		//				// Set font
		//				$pdf->SetFont('frankgothcnbt');
		//				// Title
		//				$pdf->Cell(0, 15, 'Something new right here!!!', 0, false, 'C', 0, '', 0, false, 'M', 'M');
		//				$html = '<span color="red">red</span> <span color="green">green</span> <span color="blue">blue</span><br /><span color="red">red</span> <span color="green">green</span> <span color="blue">blue</span>';
		//				$pdf->writeHTMLCell(0, 0, '', '', $html, 'LRTB', 1, 0, true, 'L', true);
		//
		//		});
		//
		//		// Custom Footer
		//		PDF::setFooterCallback(function($pdf) {
		//
		//				// Position at 15 mm from bottom
		//				$pdf->SetY(-15);
		//				// Set font
		//				$pdf->SetFont('frankgothcnbt');
		//				// Page number
		//				$pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		//
		//		});
		$svgarr = explode(',_',$request->svg);
    	$name=$request->name;
        define ('PDF_FONT_NAME_MAIN', 'frankgothcnbt');
        define ('PDF_FONT_NAME_DATA', 'frankgothcnbt');
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('frankgothcnbt');
        PDF::AddPage();
        //PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		$yy=12.5;
		foreach($svgarr as $value)
		{
			$svg=urldecode($value);
			PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=127, $h=80, $link='', $align='', $palign='', $border=1, $fitonpage=false);
			$yy += 100;
		}
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
  
  /**
   * Print Preview For Stickers
   *
   * @return pdf
   */
  
    public function printDivTescoSmall(Request $request)
    {
		$svgarr = explode(',_',$request->svg);
		$name=$request->name;
        $svg=urldecode($request->svg);
        define ('PDF_FONT_NAME_MAIN', 'frankgothcnbt');
        define ('PDF_FONT_NAME_DATA', 'frankgothcnbt');
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('frankgothcnbt');
        PDF::AddPage();
        //PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		$yy=12.5;
		foreach($svgarr as $value)
		{
			$svg=urldecode($value);
			 PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=40, $h=30, $link='', $align='', $palign='', $border=1, $fitonpage=false);
			$yy += 50;
		}
		
       
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
}