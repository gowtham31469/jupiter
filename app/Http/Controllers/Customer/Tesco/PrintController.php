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
		
		 $this->template_name = $request->name;
		 $this->template_zone = $request->zone;
		  
		  $this->tesco_po_number = $request->tesco_po_number;
		  $this->style = $request->style;
		  $this->tesco_description = $request->tesco_description;
		  
				 // Custom Header
		PDF::setHeaderCallback(function($pdf) {
		$name=str_replace("-sticker", "", $this->template_name);
		$zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->tesco_description;
			
						 $image_file= K_PATH_IMAGES.'logo_example.jpg';
					$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="70.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
<title>Qt Svg Document</title>
<desc>Generated with Qt</desc>
<defs>
</defs>
<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
</g>
<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
</g>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
    <image xlink:href="/var/www/html/jupiter/public/images/logo.png" x="174" y="66" height="65px" width="116px" style=""/>
</svg>
<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO. : '.$tesco_po_number.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Style: '.$style.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF: '.$name.' - '.$zone.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Date: 03.05.2018</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 127x80mm</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Description: '.$tesco_description.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Rev.no:</text>
</g>
</g>
</svg>';
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);

		
				});
		
				// Custom Footer
				PDF::setFooterCallback(function($pdf) {
		
						// Position at 15 mm from bottom
						$pdf->SetY(-50);
					    $pdf->SetX(9.5);
						// Set font
						//$pdf->SetFont('frankgothcnbt');
						// Page number
					$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
<title>Qt Svg Document</title>
<desc>Generated with Qt</desc>
<defs>
</defs>
<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
</g>
<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">Customer: </text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO No. :460-00060</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">Approved by: </text>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">Comments if any:</text>
</g>
</g>
</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
				});
				$svgarr = explode(',_',$request->svg);
				$name=$request->name;
				PDF::setImageScale(1);
				PDF::SetFont('frankgothcnbt');
				PDF::AddPage();
				$i=1;
				$yy=0;
				$j=1;
				PDF::SetXY(10, 50);
				foreach($svgarr as $value)
				{
					$svg=urldecode($value);
					PDF::SetFillColor(255,255,255);

					PDF::SetMargins(PDF_MARGIN_LEFT, 70, PDF_MARGIN_RIGHT);
					PDF::AddPage();
					PDF::setPage($j, true);
					$yy=80;
					$j++;
					
					 file_put_contents(public_path('/storage/app/template/demo_'.$j.'.svg'), $svg);
					 exec("/usr/bin/convert ".public_path('/storage/app/template/demo_'.$j.'.svg')." ".public_path('/storage/app/template/demo_'.$j.'.png')."");

					 PDF::Image(public_path('/storage/app/template/demo_'.$j.'.png'), 15, $yy, 127, 80, 'png', '', '', true, 300, '', false, false, 1, false, false, false);
					
					//PDF::Image($value, 15, $yy, 127, 80, 'png', '', '', true, 300, '', false, false, 1, false, false, false);
					$i++;
					
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
		 $this->template_name = $request->name;
		 $this->template_zone = $request->zone;
		  
		  $this->tesco_po_number = $request->tesco_po_number;
		  $this->style = $request->style;
		  $this->tesco_description = $request->tesco_description;
		  
				 // Custom Header
		PDF::setHeaderCallback(function($pdf) {
		$name=str_replace("-sticker", "", $this->template_name);
		$zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->tesco_description;
			
						 $image_file= K_PATH_IMAGES.'logo_example.jpg';
					$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="70.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
<title>Qt Svg Document</title>
<desc>Generated with Qt</desc>
<defs>
</defs>
<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1024,0 L1024,264 L0,264 L0,0"/>
</g>
<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal" style="&#10;    /* border-radius: 27px; */&#10;">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L666,65 L666,66 L499,66 L499,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,65 L500,65 L500,144 L499,144 L499,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L821,65 L821,66 L665,66 L665,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L969,65 L969,66 L820,66 L820,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,65 L969,65 L969,105 L968,105 L968,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,104 L969,104 L969,144 L968,144 L968,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,182 L821,182 L821,183 L499,183 L499,182"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L500,143 L500,183 L499,183 L499,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,182 L969,182 L969,183 L820,183 L820,182"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M968,143 L969,143 L969,183 L968,183 L968,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L666,143 L666,144 L499,144 L499,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,144 L665,144 L665,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,65 L666,65 L666,105 L665,105 L665,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,65 L821,65 L821,105 L820,105 L820,65"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L821,104 L821,105 L665,105 L665,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M665,104 L666,104 L666,143 L665,143 L665,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,143 L820,143 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L969,104 L969,105 L820,105 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,104 L821,104 L821,144 L820,144 L820,104"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M499,143 L821,143 L821,144 L499,144 L499,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L969,143 L969,144 L820,144 L820,143"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M820,143 L821,143 L821,183 L820,183 L820,143"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L1016,212 L1016,213 L8,213 L8,212"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,213 L1016,213 L1016,214 L8,214 L8,213"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,212 L8,214 L9,213 L9,212 L8,212"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,213 L1015,214 L1016,214 L1016,212 L1015,213"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L1016,216 L1016,217 L8,217 L8,216"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,217 L1016,217 L1016,218 L8,218 L8,217"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,216 L8,218 L9,217 L9,216 L8,216"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1015,217 L1015,218 L1016,218 L1016,216 L1015,217"/>
</g>
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="56cm" height="37cm" version="1.1">
    <image xlink:href="/var/www/html/jupiter/public/images/logo.png" x="174" y="66" height="65px" width="116px" style=""/>
</svg>
<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO. : '.$tesco_po_number.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Style: '.$style.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF: '.$name.' - '.$zone.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Date: 03.05.2018</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Size: 40x30mm</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Description: '.$tesco_description.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">Rev.no:</text>
</g>
</g>
</svg>';
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);

		
				});
		
				// Custom Footer
				PDF::setFooterCallback(function($pdf) {
		
						// Position at 15 mm from bottom
						$pdf->SetY(-50);
					    $pdf->SetX(9.5);
						// Set font
						//$pdf->SetFont('frankgothcnbt');
						// Page number
					$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188mm" height="65.2667mm" viewBox="0 0 1360 168" version="1.2" baseProfile="tiny">
<title>Qt Svg Document</title>
<desc>Generated with Qt</desc>
<defs>
</defs>
<g fill="none" stroke="black" stroke-width="1" fill-rule="evenodd" stroke-linecap="square" stroke-linejoin="bevel">
<g fill="#ffffff" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M0,0 L1360,0 L1360,168 L0,168 L0,0"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L1352,52 L1352,53 L8,53 L8,52"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,53 L1352,53 L1352,54 L8,54 L8,53"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,52 L8,54 L9,53 L9,52 L8,52"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,53 L1351,54 L1352,54 L1352,52 L1351,53"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L1352,56 L1352,57 L8,57 L8,56"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,57 L1352,57 L1352,58 L8,58 L8,57"/>
</g>
<g fill="#9a9a9a" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M8,56 L8,58 L9,57 L9,56 L8,56"/>
</g>
<g fill="#eeeeee" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M1351,57 L1351,58 L1352,58 L1352,56 L1351,57"/>
</g>
<g fill="#000000" fill-opacity="1" stroke="none" transform="matrix(1,0,0,1,0,0)" font-family="Helvetica" font-size="12" font-weight="400" font-style="normal">
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M385,79 L386,79 L386,157 L385,157 L385,79"/>
<path vector-effect="non-scaling-stroke" fill-rule="evenodd" d="M764,79 L765,79 L765,157 L764,157 L764,79"/>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">Customer: </text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO No. :460-00060</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">Approved by: </text>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">Comments if any:</text>
</g>
</g>
</svg>';
					
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
				});
		$svgarr = explode(',_',$request->svg);
		$name=$request->name;
        $svg=urldecode($request->svg);
        PDF::setImageScale(1);
        PDF::SetFont('frankgothcnbt');
        PDF::AddPage();
        //PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		//		$yy=12.5;
		//		foreach($svgarr as $value)
		//		{
		//			$svg=urldecode($value);
		//			 PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=40, $h=30, $link='', $align='', $palign='', $border=1, $fitonpage=false);
		//			$yy += 50;
		//		}
		//		
		//		
		$i=1;
		$yy=0;
		$j=1;
		PDF::SetXY(10, 50);
		foreach($svgarr as $value)
		{
			$svg=urldecode($value);
			PDF::SetFillColor(255,255,255);

			PDF::SetMargins(PDF_MARGIN_LEFT, 70, PDF_MARGIN_RIGHT);
			PDF::AddPage();
			PDF::setPage($j, true);
			$yy=80;
			$j++;
			 file_put_contents(public_path('/storage/app/template/demo_'.$j.'.svg'), $svg);
			 exec("/usr/bin/convert ".public_path('/storage/app/template/demo_'.$j.'.svg')." ".public_path('/storage/app/template/demo_'.$j.'.png')."");
			
			PDF::Image(public_path('/storage/app/template/demo_'.$j.'.png'), 15, $yy, 40, 30, 'png', '', '', true, 300, '', false, false, 1, false, false, false);
				//PDF::Image($value, 15, $yy, 40, 30, 'png', '', '', true, 300, '', false, false, 1, false, false, false);
			$i++;

		}
		
       
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
}