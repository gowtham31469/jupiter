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
		 $id=$request->id;
		 $db=$request->db;
		
		 $create = DB::table($db)
					->select('*')
					->where('id',$id)
					->get();
		 $this->created_date = $create{0}->created_at;
		  
		 $company_id = Auth::guard('web_customer')->user()->company_id; 
		
		 $customer = DB::table("customers")
					->select('*')
					->where('id',$company_id)
					->get();
		 $customer_name = $customer{0}->short_name;
		  
		 $this->customer_name = $customer_name;
		
		 $this->template_name = $request->name;
		 $this->template_zone = $request->zone;
		  if($request->name == "TCL1-sticker")
		  {
			  $this->template_desc = "RATIO STICKER";
		  }
		  else if($request->name == "TCL2-sticker")
	      {
			   $this->template_desc = "RATIO BD STICKER"; 
		  }
		  else if($request->name == "TCL4-sticker")
	      {
			   $this->template_desc =  "SINGLES BD STICKER";
		  }
		  else if($request->name == "TCL5-sticker")
		  {
			   $this->template_desc =  "SINGLES BD STICKER";
		  }
		  else if($request->name == "THL1-sticker")
	      {
			    $this->template_desc = "RATIO STICKER";
		  }
		  else if($request->name == "THL3-sticker")
	      {
			   $this->template_desc = "SINGLES RATIO BD STICKER";
		  }
		  $this->tesco_po_number = $request->tesco_po_number;
		  $this->style = $request->style;
		  $this->tesco_description = $request->tesco_description;
		  
				 // Custom Header
		PDF::setHeaderCallback(function($pdf) {
			$pdf->SetY(0);
		$name=str_replace("-sticker", "", $this->template_name);
		$zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
						 $image_file= K_PATH_IMAGES.'logo_example.jpg';
					$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
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
    <image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
</svg>
<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO: '.$tesco_po_number.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE: '.$style.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF: '.$name.' - '.$zone.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE: '.$created_date.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">SIZE: 127x80mm</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION: '.$tesco_description.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO:</text>
</g>
</g>
</svg>';
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);

		
				});
		
				// Custom Footer
				PDF::setFooterCallback(function($pdf) {
					$tesco_po_number=$this->tesco_po_number;
					$customer_name=$this->customer_name;
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
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER: '.$customer_name.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO: '.$tesco_po_number.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY: </text>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY:</text>
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
				$i=0;
				$yy=60;
				$qr_y=67.5;
				$j=0;
				$qr_count=0;
				PDF::SetXY(10, 50);
		
				$qr_flag=$request->qr_flag;
				if($name == "TCL1-sticker")
				{
					$tcl1_id=$request->tcl1_id;

					$qr_code = DB::table('tesco_tcl1_orders_quanty')->where('tcl1_order_id',$tcl1_id)->select('*')->orderBy('id', 'asc')->get();
					$qr_code_details = DB::table('tesco_tcl1_orders')->where('id',$tcl1_id)->select('*')->orderBy('id', 'asc')->get();

				}
				else if($name == "TCL2-sticker")
				{
					$tcl2_id=$request->tcl2_id;

					$qr_code = DB::table('tesco_tcl2_orders_quanty')->where('tcl2_order_id',$tcl2_id)->select('*')->orderBy('id', 'asc')->get();
					$qr_code_details = DB::table('tesco_tcl2_orders')->where('id',$tcl2_id)->select('*')->orderBy('id', 'asc')->get();
				}
				else if($name == "TCL4-sticker")
				{
					$tcl4_id=$request->tcl4_id;

					$qr_code = DB::table('tesco_tcl4_orders_quanty')->where('tcl4_order_id',$tcl4_id)->select('*')->orderBy('id', 'asc')->get();
					$qr_code_details = DB::table('tesco_tcl4_orders')->where('id',$tcl4_id)->select('*')->orderBy('id', 'asc')->get();
				}
				else if($name == "TCL5-sticker")
				{
					$tcl5_id=$request->tcl5_id;

					$qr_code = DB::table('tesco_tcl5_orders_quanty')->where('tcl5_order_id',$tcl5_id)->select('*')->orderBy('id', 'asc')->get();
					$qr_code_details = DB::table('tesco_tcl5_orders')->where('id',$tcl5_id)->select('*')->orderBy('id', 'asc')->get();
				}
				else if($name == "THL1-sticker")
				{
					$thl1_id=$request->thl1_id;

					$qr_code = DB::table('tesco_thl1_orders_quanty')->where('thl1_order_id',$thl1_id)->select('*')->orderBy('id', 'asc')->get();
					$qr_code_details = DB::table('tesco_thl1_orders')->where('id',$thl1_id)->select('*')->orderBy('id', 'asc')->get();
				}
				else if($name == "THL3-sticker")
				{
					$thl3_id=$request->thl3_id;

					$qr_code = DB::table('tesco_thl3_orders_quanty')->where('thl3_order_id',$thl3_id)->select('*')->orderBy('id', 'asc')->get();
					$qr_code_details = DB::table('tesco_thl3_orders')->where('id',$thl3_id)->select('*')->orderBy('id', 'asc')->get();
				}
		
				foreach($svgarr as $value)
				{
					$svg=urldecode($value);
					PDF::SetFillColor(255,255,255);
					if($i == 2)
					{
						$yy=60;
						$qr_y=67.5;
						PDF::AddPage();
						$i = 0;
					}
					else if($i == 0)
					{
						$yy=60;
						$qr_y=67.5;
					}
					else
					{
						if($name == "TCL4-sticker")
						{
							//print_r($create{0}->template_id);
							if($create{0}->template_id == 28 || $create{0}->template_id == 31)
							{
								$yy += 77;
							}
							else
							{
								$yy += 100; 
							}
						}
						else if($name == "TCL5-sticker")
						{
							$yy += 77;
						}
						else if($name == "TCL1-sticker")
						{
								$yy += 77;
						}
						else if($name == "TCL2-sticker")
						{
								$yy += 77;
						}
						else
						{
						  $yy += 100;  
						}
						$qr_y+=100;
					}
					$j++;
			
					
					$svg=urldecode($value);
					file_put_contents(public_path('/storage/app/template/demo_'.$j.'.svg'), $svg);
					$html = '<img style="width:480px;height:302.36220472px;border:1px solid #333" src="'.public_path('/storage/app/template/demo_'.$j.'.svg').'">';
					PDF::writeHTMLCell(127, 80, 15, $yy+1, $html, '', 0, 0, false, 'L', false);
				
					if($qr_flag == 1)
			{
				// new style
				$style = array(
					'border' => false,
					'padding' => 0,
					'bgcolor' => false
				);
				if($name == "TCL1-sticker")
				{
					$lasttpn = substr($qr_code[$qr_count]->orms_tpn , -3);
					$qr_check = $qr_code_details{0}->po_number.",".$qr_code[$qr_count]->itf_barcode."/".$lasttpn.",".$qr_code[$qr_count]->orms_tpn;
					if($qr_check == $qr_code[$qr_count]->qr_code)
					{
						PDF::write2DBarcode($qr_code[$qr_count]->qr_code, 'QRCODE,Q', 88, $qr_y-1.5, 17, 17, $style, 'N');
		 			}
				}
				else if($name == "TCL2-sticker")
				{
					$lasttpn = substr($qr_code[$qr_count]->orms_tpn , -3);
					$qr_check = $qr_code_details{0}->po_number.",".$qr_code[$qr_count]->itf_barcode."/".$lasttpn.",".$qr_code[$qr_count]->orms_tpn;
					if($qr_check == $qr_code[$qr_count]->qr_code)
					{
						PDF::write2DBarcode($qr_code[$qr_count]->qr_code, 'QRCODE,Q', 89.5, $qr_y+1.5, 17, 17, $style, 'N');
					}
				}
				else if($name == "TCL4-sticker")
				{
					$lasttpn = substr($qr_code[$qr_count]->orms_tpn , -3);
					$qr_check = $qr_code_details{0}->po_number.",".$qr_code[$qr_count]->ean_barcode."/".$lasttpn.",".$qr_code[$qr_count]->orms_tpn;
					if($qr_check == $qr_code[$qr_count]->qr_code)
					{
						PDF::write2DBarcode($qr_code[$qr_count]->qr_code, 'QRCODE,Q', 89, $qr_y+1.5, 17, 17, $style, 'N');
					}
				}
				else if($name == "TCL5-sticker")
				{
					$lasttpn = substr($qr_code[$qr_count]->orms_tpn , -3);
					$qr_check = $qr_code_details{0}->po_number.",".$qr_code[$qr_count]->itf_barcode."/".$lasttpn.",".$qr_code[$qr_count]->orms_tpn;
					if($qr_check == $qr_code[$qr_count]->qr_code)
					{
						PDF::write2DBarcode($qr_code[$qr_count]->qr_code, 'QRCODE,Q', 89, $qr_y-1.5, 17, 17, $style, 'N');
					}
				}
				else if($name == "THL1-sticker")
				{
					$lasttpn = substr($qr_code[$qr_count]->orms_tpn , -3);
					$qr_check = $qr_code_details{0}->po_number.",".$qr_code[$qr_count]->itf_barcode."/".$lasttpn.",".$qr_code[$qr_count]->orms_tpn;
					if($qr_check == $qr_code[$qr_count]->qr_code)
					{
						PDF::write2DBarcode($qr_code[$qr_count]->qr_code, 'QRCODE,Q', 89, $qr_y-1.5, 17, 17, $style, 'N');
					}
				}
				else if($name == "THL3-sticker")
				{
					$lasttpn = substr($qr_code[$qr_count]->orms_tpn , -3);
					$qr_check = $qr_code_details{0}->po_number.",".$qr_code[$qr_count]->ean_barcode."/".$lasttpn.",".$qr_code[$qr_count]->orms_tpn;
					if($qr_check == $qr_code[$qr_count]->qr_code)
					{
						PDF::write2DBarcode($qr_code[$qr_count]->qr_code, 'QRCODE,Q', 90, $qr_y-2, 17, 17, $style, 'N');
					}
				}
				
			}
					
					$i++;
					$qr_count++;
					
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
		 $id=$request->id;
		 $db=$request->db;
		
		 $create = DB::table($db)
					->select('*')
					->where('id',$id)
					->get();
		 $this->created_date = $create{0}->created_at;
		
		
		$company_id = Auth::guard('web_customer')->user()->company_id; 
		
		 $customer = DB::table("customers")
					->select('*')
					->where('id',$company_id)
					->get();
		 $customer_name = $customer{0}->short_name;
		  
		 $this->customer_name = $customer_name;
		
		 $this->template_name = $request->name;
		 $this->template_zone = $request->zone;
		  if($request->name == "TSL1-sticker")
		  {
			  $this->template_desc = "RATIO POLYBAG STICKER";
		  }
		  else if($request->name == "TSL3-sticker")
	      {
			   $this->template_desc = "SINGLE POLYBAG STICKER "; 
		  }
          else if($request->name == "TSL2-sticker")
          {
               $this->template_desc = "SINGLE POLYBAG STICKER "; 
          }
		  $this->tesco_po_number = $request->tesco_po_number;
		  $this->style = $request->style;
		  $this->tesco_description = $request->tesco_description;
		  
				 // Custom Header
		PDF::setHeaderCallback(function($pdf) {
		$name=str_replace("-sticker", "", $this->template_name);
		$zone=$this->template_zone;
			$tesco_po_number=$this->tesco_po_number;
			$style=$this->style;
			$tesco_description=$this->template_desc;
			$date=date_create($this->created_date);
			$created_date =  date_format($date,"d.m.Y");
						 $image_file= K_PATH_IMAGES.'logo_example.jpg';
					$svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
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
    <image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
</svg>
<g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>

</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO: '.$tesco_po_number.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">STYLE: '.$style.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REF: '.$name.' - '.$zone.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE: '.$created_date.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">SIZE: 40x30mm</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION: '.$tesco_description.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO:</text>
</g>
</g>
</svg>';
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);

		
				});
		
				// Custom Footer
				PDF::setFooterCallback(function($pdf) {
		$tesco_po_number=$this->tesco_po_number;
					$customer_name=$this->customer_name;
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
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER: '.$customer_name.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO: '.$tesco_po_number.'</text>
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY: </text>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
</g>
<g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
<text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY:</text>
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
		$i=0;
		$yy=0;
		$j=1;
		PDF::SetXY(10, 50);
		foreach($svgarr as $value)
		{
			$svg=urldecode($value);
			PDF::SetFillColor(255,255,255);
			if($i == 3 || $i == 6 || $i == 9 || $i == 15 || $i == 18)
			{
				$xx=15;
				$yy+=50;
			}
			else if($i == 0)
			{
				$yy=50;
				$xx=15;
			}
			else if($i == 12)
			{
				PDF::AddPage();
				$xx=15;
				$yy=50;
			}
			else 
			{
				$xx+=70;
			}
			$j++;
			
			
			file_put_contents(public_path('/storage/app/template/demo_'.$j.'.svg'), $svg);
			// PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=127, $h=80, $link='', $align='', $palign='', $border=1, $fitonpage=false);
			$html = '<img style="width:151.18110236px;height:113.38582677px;border:1px solid #333" src="'.public_path('/storage/app/template/demo_'.$j.'.svg').'">';
			PDF::writeHTMLCell(42, 32, $xx, $yy, $html, '', 0, 0, false, 'L', false);
			
//			
//			
//			file_put_contents(public_path('/storage/app/template/demo_'.$j.'.svg'), $svg);
//			shell_exec("inkscape -z -e ".public_path('/storage/app/template/demo_'.$j.'.png')." -w 1024 -h 653 ".public_path('/storage/app/template/demo_'.$j.'.svg')."");
//			PDF::Image(public_path('/storage/app/template/demo_'.$j.'.png'), $xx, $yy, 40, 30, 'png', '', '', true, 300, '', false, false, 1, false, false, false);
			$i++;

		}
		
       
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
	} 
	
	
	public function proofDivTescoLabelos(Request $request)
	{

	   $data_back=explode(',_',$request->data_back);
	   $data_back_text=explode(',_',$request->data_back_text);
	   $data_front=explode(',_',$request->data_front);
	   $data_front_image=explode(',_',$request->data_front_image);
	   $svgStringBack=urldecode($data_back[0]);
	   $svgStringBackText=urldecode($data_back_text[0]);
	   $svgStringFrontImage=urldecode($data_front_image[0]);

	   $wash_astm = $request->wash_astm;
	   $wash_iso = $request->wash_iso;
	   $whit_astm = $request->whit_astm;
	   $whit_iso = $request->whit_iso;
	   $dry_astm = $request->dry_astm;
	   $dry_iso = $request->dry_iso;
	   $iron_astm = $request->iron_astm;
	   $iron_iso = $request->iron_iso;
	   $clean_astm = $request->clean_astm;
	   $clean_iso = $request->clean_iso;

	   $white_wash_astm = $request->white_wash_astm;
	   $white_wash_iso = $request->white_wash_iso;
	   $white_whit_astm = $request->white_whit_astm;
	   $white_whit_iso = $request->white_whit_iso;
	   $white_dry_astm = $request->white_dry_astm;
	   $white_dry_iso = $request->white_dry_iso;
	   $white_iron_astm = $request->white_iron_astm;
	   $white_iron_iso = $request->white_iron_iso;
	   $white_clean_astm = $request->white_clean_astm;
	   $white_clean_iso = $request->white_clean_iso;

	   define ('PDF_FONT_NAME_MAIN', 'arialuni');
	   define ('PDF_FONT_NAME_DATA', 'arialuni');
	   PDF::SetFont('arialuni');

	   $this->tesco_po_number = $request->tesco_po_number;
	   $this->style = $request->style;
	   $this->template_name = $request->template_name;
	   $this->created_date = $request->created_date;
	   $this->customer_name = $request->customer_name;

	   PDF::setHeaderCallback(function($pdf) {
		   $name=$this->template_name;
		   $zone="";
			 $tesco_po_number=$this->tesco_po_number;
			 $style=$this->style;
			 $tesco_description="";
			 $date=date_create($this->created_date);
			 $created_date =  date_format($date,"d.m.Y");
			 $image_file= K_PATH_IMAGES.'logo_example.jpg';
			 $svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
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
							 <image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
					 </svg>
					 <g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
					 <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Jupiter Garments Accessories</text>
   
					 </g>
					 <g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
					 <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					 <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="12" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
					 <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="12" font-weight="400" font-style="normal">REF : '.$name.'</text>
					 <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
					 <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">SIZE: 25x140mm</text>
					 <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : '.$tesco_description.'</text>
					 <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO:</text>
					 </g>
					 </g>
					 </svg>';
					 $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			 });
							 
			 // Custom Footer
			 PDF::setFooterCallback(function($pdf) {
			 $tesco_po_number=$this->tesco_po_number;
			 $customer_name=$this->customer_name;
			 // Position at 15 mm from bottom
			 $pdf->SetY(-50);
			 $pdf->SetX(9.5);
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
					 <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
					 <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
					 <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
					 </g>
					 <g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
					 <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
					 </g>
					 <g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
					 <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
					 </g>
					 </g>
					 </svg>';
					 
					  $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		 
		 });
	   
	   
		 
	   $i=0;
	   $xx=15;
	   foreach($data_front as $value)
	   {
		   
		   if($i == 6)
		   {
			   $i=0;
			   $xx=15;
		   }

		   if($i == 0)
		   {
			   PDF::AddPage();
		   }
		   
		   $svgString=urldecode($value);

		   if($request->template_name == "GCL25B/M13278G" || $request->template_name == "GCL25B/M13279G")
		   {
			   $svgStringBlack = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
			   viewBox="0 0 71.37 397.35" style="enable-background:new 0 0 71.37 397.35;" xml:space="preserve">
		  <rect x="0.25" y="0.25" style="fill-rule:evenodd;clip-rule:evenodd;" width="70.87" height="396.85"/>
		  <rect x="0.25" y="0.25" style="fill:none;stroke:#000000;stroke-width:0.5;stroke-miterlimit:2.6131;" width="70.87" height="396.85"/>
		  </svg>';
			   PDF::ImageSVG('@' . $svgStringBlack, $x=$xx, $y=80, $w=25.176, $h=141.008, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		   }
		   PDF::ImageSVG('@' . $svgString, $x=$xx, $y=80, $w=25.176, $h=141.008, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		   PDF::ImageSVG('@' . $svgStringFrontImage, $x=$xx, $y=80, $w=25.176, $h=141.008, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		   $xx+=30;
		   $i++;
	   }

	   if($i == 6)
	   {
		   $xx=15;
		   PDF::AddPage();
	   }

	   PDF::ImageSVG('@' . $svgStringBack, $x=$xx, $y=80, $w=25.176, $h=140.208, $link='', $align='', $palign='', $border=0, $fitonpage=false);
	   

	   PDF::ImageSVG('@' . $svgStringBackText, $x=$xx, $y=80, $w=25.176, $h=140.208, $link='', $align='', $palign='', $border=0, $fitonpage=false);

	   if($request->template_name == "GCL25B/M13278G" || $request->template_name == "GCL25B/M13279G")
	   {
		   PDF::ImageSVG($file=public_path($white_wash_astm), $x=$xx+1.5, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		   PDF::ImageSVG($file=public_path($white_wash_iso), $x=$xx+1.5, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
   
   
		   PDF::ImageSVG($file=public_path($white_whit_astm), $x=$xx+5.8, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		   PDF::ImageSVG($file=public_path($white_whit_iso), $x=$xx+5.8, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
   
		   PDF::ImageSVG($file=public_path($white_dry_astm), $x=$xx+10.5, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		   PDF::ImageSVG($file=public_path($white_dry_iso), $x=$xx+10.5, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
   
		   PDF::ImageSVG($file=public_path($white_iron_astm), $x=$xx+15.5, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		   PDF::ImageSVG($file=public_path($white_iron_iso), $x=$xx+15.5, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
   
		   PDF::ImageSVG($file=public_path($white_clean_astm), $x=$xx+20, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		   PDF::ImageSVG($file=public_path($white_clean_iso), $x=$xx+20, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
	   }
	   else
	   {
		   PDF::ImageSVG($file=public_path($wash_astm), $x=$xx+1.5, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		   PDF::ImageSVG($file=public_path($wash_iso), $x=$xx+1.5, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
   
   
		   PDF::ImageSVG($file=public_path($whit_astm), $x=$xx+5.8, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		   PDF::ImageSVG($file=public_path($whit_iso), $x=$xx+5.8, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
   
		   PDF::ImageSVG($file=public_path($dry_astm), $x=$xx+10.5, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		   PDF::ImageSVG($file=public_path($dry_iso), $x=$xx+10.5, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
   
		   PDF::ImageSVG($file=public_path($iron_astm), $x=$xx+15.5, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		   PDF::ImageSVG($file=public_path($iron_iso), $x=$xx+15.5, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
   
		   PDF::ImageSVG($file=public_path($clean_astm), $x=$xx+20, $y=94.5, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
		   PDF::ImageSVG($file=public_path($clean_iso), $x=$xx+20, $y=99, $w=4, $h=4, $link='', $align='', $palign='', $border=0, $fitonpage=false);
	   }

	   

	   PDF::Output( public_path('/storage/app/template/'.str_replace("/","_",$this->style).'_'.substr($request->template_name, strrpos($request->template_name, '/') + 1).'.pdf'), 'F');
       print_r(url('/storage/app/template/'.str_replace("/","_",$this->style).'_'.substr($request->template_name, strrpos($request->template_name, '/') + 1).'.pdf'));
	} 

	public function proofDivTescoTicketos(Request $request)
     {
        $data_back=explode(',_',$request->data_back);
        $data_front=explode(',_',$request->data_front);
        $size_category_fin=explode(',_',$request->size_category_fin);
        $size_comp_fin=explode(',_',$request->size_comp_fin);
        $product_desc_fin=explode(',_',$request->product_desc_fin);
        $wee_symbol_fin=explode(',_',$request->wee_symbol_fin);
        $ce_symbol_fin=explode(',_',$request->ce_symbol_fin);
        $woolmark_logo_file = $request->woolmark_logo_file;
        $tesco_size_department = $request->tesco_size_department;
        
        PDF::SetFont('fandfsansb');
        PDF::SetFont('fandfsans');
        PDF::SetFont('fandfsanslight');


        $this->tesco_po_number = $request->tesco_po_number;
        $this->style = $request->style;
        $this->template_name = $request->template_name;
        $this->created_date = $request->created_date;
        $this->customer_name = $request->customer_name;

        PDF::setHeaderCallback(function($pdf) {
            $name=$this->template_name;

            if($name == "GE/01/M13114C - Opt 1" || $name == "GE/01/M13114C - Opt 2" || $name == "GE/01/M13114C - Opt 3" || $name == "GE/01/M13114C - Opt 4")
            {
                $name = "GE/01/M13114C";
            }


            if($name == "FFC/01/M13304C")
            {
                $siz = "78x108mm";
            }
            else
            {
                $siz= "41x115.5mm";
            }

            $zone="";
              $tesco_po_number=$this->tesco_po_number;
              $style=$this->style;
              $tesco_description="";
              $date=date_create($this->created_date);
              $created_date =  date_format($date,"d.m.Y");
              $image_file= K_PATH_IMAGES.'logo_example.jpg';
              $svg='<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="188.244mm" height="50.1333mm" viewBox="0 0 1024 264" version="1.2" baseProfile="tiny">
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
                              <image xlink:href="'.public_path('/images/jupiter.jpg').'" x="174" y="66" height="65px" width="116px" style=""/>
                      </svg>
                      <g xmlns="http://www.w3.org/2000/svg" fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="35" font-style="normal" font-weight="700">
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="50" y="176" font-family="Times New Roman" font-size="30" font-weight="700" font-style="normal">Spectrra Prints</text>
    
                      </g>
                      <g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="507" y="127" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="90" font-family="Times New Roman" font-size="12" font-weight="400" font-style="normal">STYLE : '.$style.'</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="90" font-family="Times New Roman" font-size="12" font-weight="400" font-style="normal">REF : '.$name.'</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="671" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DATE : '.$created_date.'</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="129" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">SIZE: '.$siz.'</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="505" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">DESCRIPTION : </text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="826" y="168" font-family="Times New Roman" font-size="16" font-weight="400" font-style="normal">REV.NO:</text>
                      </g>
                      </g>
                      </svg>';
                      $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
              });
                              
              // Custom Footer
              PDF::setFooterCallback(function($pdf) {
              $tesco_po_number=$this->tesco_po_number;
              $customer_name=$this->customer_name;
              // Position at 15 mm from bottom
              $pdf->SetY(-50);
              $pdf->SetX(9.5);
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
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">CUSTOMER : '.$customer_name.'</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="41" y="152" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">PO NO : '.$tesco_po_number.'</text>
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="100" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">APPROVED BY : </text>
                      </g>
                      <g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="405" y="152" font-family="Times New Roman" font-size="23" font-weight="700" font-style="normal">PREPARED BY:</text>
                      </g>
                      <g fill="#000000" fill-opacity="1" stroke="#000000" stroke-opacity="1" stroke-width="1" stroke-linecap="square" stroke-linejoin="bevel" transform="matrix(1,0,0,1,0,0)" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">
                      <text fill="#000000" fill-opacity="1" stroke="none" xml:space="preserve" x="784" y="103" font-family="Times New Roman" font-size="23" font-weight="400" font-style="normal">COMMENTS IF ANY :</text>
                      </g>
                      </g>
                      </svg>';
                      
                       $pdf->ImageSVG('@' . $svg, '', '', '', '', $link='', $align='', $palign='', $border=0, $fitonpage=false);
          
          });

        $svgStringFront=urldecode($data_front[0]);

        $i=0;
        $j=0;
        $wri_x=-39;
        $xx=15;
        $first_time=0;
        $first_page=0;
		PDF::AddPage();

		$html_note_dept = '<h3 style="font-weight:bold">'.$tesco_size_department.'</h3>';
		PDF::writeHTMLCell($w=0, $h=0, $x=0, $y=50, $html_note_dept, $border=0, $ln=1, $fill=0, $reseth=true, $align='C', $autopadding=false);


		$html_note = '<h3 style="color:red">Note: DOP will change to the month of production.</h3>';
		PDF::writeHTMLCell($w=0, $h=0, $x=15, $y=260, $html_note, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=false);


		if($request->template_name == "FFC/01/M13304C")
        {
            PDF::ImageSVG('@' . $svgStringFront, $x=$xx, $y=80, $w=78, $h=108, $link='', $align='', $palign='', $border=0, $fitonpage=false);
            foreach($data_back as $key => $value)
            {
                if($first_page == 0)
                {
                    if($i == 1)
                    {
                        $i=0;
						PDF::AddPage();
						$html_note = '<h3 style="color:red">Note: DOP will change to the month of production.</h3>';
						PDF::writeHTMLCell($w=0, $h=0, $x=15, $y=260, $html_note, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=false);
                        $first_page++;
                    }
                }
                else
                {
                    if($i == 2)
                    {
                        $i=0;
						PDF::AddPage();
						$html_note = '<h3 style="color:red">Note: DOP will change to the month of production.</h3>';
						PDF::writeHTMLCell($w=0, $h=0, $x=15, $y=260, $html_note, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=false);
                    }
                }
                
    
                if($i == 0)
                {
                    $xx=15;
                }
                else
                {
                    $xx+=90;
                }
    
                if($first_time == 0)
                {
                    $xx+=90;
                }
                
    
                $svgString=urldecode($value);
    
                if($request->template_name == "FFC/01/M13304C")
                {
                    PDF::ImageSVG('@' . $svgString, $x=$xx, $y=80, $w=78, $h=108, $link='', $align='', $palign='', $border=0, $fitonpage=false); 
                }
                else
                {
                    PDF::ImageSVG('@' . $svgString, $x=$xx, $y=80, $w=41, $h=115.5, $link='', $align='', $palign='', $border=0, $fitonpage=false); 
                }
                
    
    
                PDF::ImageSVG($file=public_path($wee_symbol_fin[0]), $x=$xx+30, $y=81, $w=11, $h=11, $link='', $align='', $palign='', $border=0, $fitonpage=false);
    
                PDF::ImageSVG($file=public_path($woolmark_logo_file), $x=$xx+43, $y=97, $w=29, $h=29, $link='', $align='', $palign='', $border=0, $fitonpage=false);

                $i++;
                $j++;
                $first_time++;
            }
		}
		else
		{
			PDF::ImageSVG('@' . $svgStringFront, $x=$xx, $y=80, $w=41, $h=115.5, $link='', $align='', $palign='', $border=0, $fitonpage=false);

			$style6 = array('width' => '0.25', 'cap' => '', 'join' => '', 'dash' => '', 'color' => array(0.67,99, 0.56, 0));

            PDF::RoundedRect($xx, 80, 41, 115.5, 0.8, '1111', null, $style6);

        
			foreach($data_back as $key => $value)
			{
				if($first_page == 0)
				{
					if($i == 3)
					{
						$i=0;
						$wri_x=-129;
						PDF::AddPage();
						$html_note = '<h3 style="color:red">Note: DOP will change to the month of production.</h3>';
						PDF::writeHTMLCell($w=0, $h=0, $x=15, $y=260, $html_note, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=false);
						$first_page++;
					}
				}
				else
				{
					if($i == 4)
					{
						$i=0;
						$wri_x=-129;
						PDF::AddPage();
						$html_note = '<h3 style="color:red">Note: DOP will change to the month of production.</h3>';
						PDF::writeHTMLCell($w=0, $h=0, $x=15, $y=260, $html_note, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=false);
					}
				}
				
	
				if($i == 0)
				{
					$xx=15;
				}
				else
				{
					$xx+=45;
				}
	
				if($first_time == 0)
				{
					$xx+=45;
				}
	
				$siz_cat = explode(',',$size_category_fin[$j]);
	
				$size_comp = explode(',',$size_comp_fin[$j]);
	
				if($request->template_name != "FFC/01/M13020C" && $request->template_name != "GE/01/M13114C - Opt 1")
                {
                    if(count($siz_cat) <= 3)
                    {
                        $wri_y=105;
                    }
                    else if(count($siz_cat) == 4)
                    {
                        $wri_y=103;
                    }
                    else if(count($siz_cat) == 5)
                    {
                        $wri_y=101.5;
                    }
                }
                else
                {
                    if(count($siz_cat) <= 3)
                    {
                        $wri_y=103;
                    }
                    else if(count($siz_cat) == 4)
                    {
                        $wri_y=102;
                    }
                    else if(count($siz_cat) == 5)
                    {
                        $wri_y=101;
                    }
                }
				
	
				if($request->template_name == "FFK/01/M13021C" || $request->template_name == "FFB/01/M13023C" || $request->template_name == "GE/01/M13114C - Opt 2" || $request->template_name == "GE/01/M13114C - Opt 4" || $request->template_name == "FFC/01/M13020C" || $request->template_name == "GE/01/M13114C - Opt 1")
				{
					if($size_category_fin[$j] != "null")
					{
						foreach($siz_cat as $val_cat)
						{
							if (str_contains($val_cat, 'height')) {
								$val_cat = str_replace('height','<label style="font-family:FandFSans-Bold;font-weight:700; font-size:5px;">Height</label>',$val_cat);
							}
							
							if (str_contains($val_cat, 'weight')) {
								$val_cat = str_replace('weight','<label style="font-family:FandFSans-Bold;font-weight:700; font-size:5px;">Weight</label>',$val_cat);
							}
		
							if (str_contains($val_cat, 'chest')) {
								$val_cat = str_replace('chest','<label style="font-family:FandFSans-Bold;font-weight:700; font-size:5px;">Chest</label>',$val_cat);
							}
		
							if (str_contains($val_cat, 'waist')) {
								$val_cat = str_replace('waist','<label style="font-family:FandFSans-Bold;font-weight:700; font-size:5px;">Waist</label>',$val_cat);
							}
		
		
							foreach($size_comp as $string)
							{
								if($val_cat != null && $string != null)
								{
									if(strpos($val_cat, $string) !== false) 
									{
										$val_cat = str_replace($string,'<label style="font-family:FandFSans-Bold;font-weight:700; font-size:5px;">'.$string.'.</label>',$val_cat);
									}
								}
							}
		
							$html = '<p style="font-family:FandFSans-Regular; font-size:5px;">'.$val_cat.'</p>';
		
						  //  print_r($html);
			
							PDF::writeHTMLCell($w=0, $h=0, $x=$wri_x, $y=$wri_y, $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='C', $autopadding=false);
							$wri_y+=2;
						}
					}
	
					// if($request->template_name != "FFC/01/M13020C" && $request->template_name != "GE/01/M13114C - Opt 1")
                    // {
	
						$par_pro = json_decode($product_desc_fin[0]);
						
						if($size_category_fin[$j] != "null")
						{
							if(strlen($par_pro{0}->slovak." • ".$par_pro{0}->hungarian) > 64)
							{
								$fo_si = "4.5px";
								$let_spac = "-0.2px";
							}
							else
							{
								$fo_si = "5px";
								$let_spac = "0px";
							}
							$html = '<p style="font-family:FandFSans-Regular; font-size:'.$fo_si.';"><label  style="font-family:FandFSans-Bold; font-size:5px;">'.$par_pro{0}->english.'</label> • '.$par_pro{0}->czech.'</p>';
		
							//  print_r($html);
		
							PDF::writeHTMLCell($w=0, $h=0, $x=$wri_x+1, $y=111.5, $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='C', $autopadding=false);
		
							$html = '<p style="font-family:FandFSans-Regular;letter-spacing:'.$let_spac.'; font-size:'.$fo_si.';"><label  style="font-family:FandFSans-Regular; font-size:'.$fo_si.';">'.$par_pro{0}->slovak.'</label> • '.$par_pro{0}->hungarian.'</p>';
		
							//  print_r($html);
		
							PDF::writeHTMLCell($w=0, $h=0, $x=$wri_x+1, $y=113.5, $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='C', $autopadding=false);
						}
						else
						{
							if(strlen($par_pro{0}->slovak." • ".$par_pro{0}->hungarian) > 64)
							{
								$fo_si = "4.5px";
								$let_spac = "-0.2px";
							}
							else
							{
								$fo_si = "5px";
								$let_spac = "0px";
							}
							$html = '<p style="font-family:FandFSans-Regular; font-size:'.$fo_si.';"><label  style="font-family:FandFSans-Bold; font-size:5px;">'.$par_pro{0}->english.'</label> • '.$par_pro{0}->czech.'</p>';
		
							//  print_r($html);
		
							PDF::writeHTMLCell($w=0, $h=0, $x=$wri_x+1, $y=107, $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='C', $autopadding=false);
							
							$html = '<p style="font-family:FandFSans-Regular;letter-spacing:'.$let_spac.'; font-size:'.$fo_si.';"><label  style="font-family:FandFSans-Regular; font-size:'.$fo_si.';">'.$par_pro{0}->slovak.'</label> • '.$par_pro{0}->hungarian.'</p>';
		
							//  print_r($html);
		
							PDF::writeHTMLCell($w=0, $h=0, $x=$wri_x+1, $y=109, $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='C', $autopadding=false);
						}
					//}
				}
	
				
	
				$svgString=urldecode($value);
				PDF::ImageSVG('@' . $svgString, $x=$xx, $y=80, $w=41, $h=115.5, $link='', $align='', $palign='', $border=0, $fitonpage=false); 

				$style6 = array('width' => '0.25', 'cap' => '', 'join' => '', 'dash' => '', 'color' => array(0.67,99, 0.56, 0));

            	PDF::RoundedRect($xx, 80, 41, 115.5, 0.8, '1111', null, $style6);
	
	
			    PDF::ImageSVG($file=public_path($wee_symbol_fin[0]), $x=$xx+30, $y=81, $w=11, $h=11, $link='', $align='', $palign='', $border=0, $fitonpage=false);
	
				PDF::ImageSVG($file=public_path($woolmark_logo_file), $x=$xx+43, $y=97, $w=29, $h=29, $link='', $align='', $palign='', $border=0, $fitonpage=false);
	
				if($request->template_name == "FFB/01/M13023C")
				{
					if($ce_symbol_fin[0] == "true")
					{
						PDF::ImageSVG($file=public_path('/images/CE.svg'), $x=$xx+3, $y=83, $w=7, $h=7, $link='', $align='', $palign='', $border=0, $fitonpage=false);
					}
				}
			   
				
	
				$i++;
				$j++;
				$first_time++;
				$wri_x+=90;
			}
		}
        

        PDF::Output( public_path('/storage/app/template/'.substr($request->template_name, strrpos($request->template_name, '/') + 1).'_ID_'.$request->style.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.substr($request->template_name, strrpos($request->template_name, '/') + 1).'_ID_'.$request->style.'.pdf'));
        
	 }
	 
	 public function generateDivTescoTicketos(Request $request)
     {
        $data_back=explode(',_',$request->data_back);
        $data_front=explode(',_',$request->data_front);
        $size_category_fin=explode(',_',$request->size_category_fin);
        $size_comp_fin=explode(',_',$request->size_comp_fin);
        $product_desc_fin=explode(',_',$request->product_desc_fin);
        $wee_symbol_fin=explode(',_',$request->wee_symbol_fin);
        $ce_symbol_fin=explode(',_',$request->ce_symbol_fin);
        $woolmark_logo_file = $request->woolmark_logo_file;
        
        PDF::SetFont('fandfsansb');
        PDF::SetFont('fandfsans');
        PDF::SetFont('fandfsanslight');


        $svgStringFront=urldecode($data_front[0]);

        $i=0;
        $j=0;
        $wri_x=-39;
        $xx=15;
        $first_time=0;
        $first_page=0;
        PDF::AddPage();
        if($request->template_name == "FFC/01/M13304C")
        {
            PDF::ImageSVG('@' . $svgStringFront, $x=$xx, $y=80, $w=78, $h=108, $link='', $align='', $palign='', $border=0, $fitonpage=false);
            foreach($data_back as $key => $value)
            {
                if($first_page == 0)
                {
                    if($i == 1)
                    {
                        $i=0;
                        PDF::AddPage();
                        $first_page++;
                    }
                }
                else
                {
                    if($i == 2)
                    {
                        $i=0;
                        PDF::AddPage();
                    }
                }
                
    
                if($i == 0)
                {
                    $xx=15;
                }
                else
                {
                    $xx+=90;
                }
    
                if($first_time == 0)
                {
                    $xx+=90;
                }
                
    
                $svgString=urldecode($value);
    
                if($request->template_name == "FFC/01/M13304C")
                {
                    PDF::ImageSVG('@' . $svgString, $x=$xx, $y=80, $w=78, $h=108, $link='', $align='', $palign='', $border=0, $fitonpage=false); 
                }
                else
                {
                    PDF::ImageSVG('@' . $svgString, $x=$xx, $y=80, $w=41, $h=115.5, $link='', $align='', $palign='', $border=0, $fitonpage=false); 
                }
                
    
    
                PDF::ImageSVG($file=public_path($wee_symbol_fin[0]), $x=$xx+30, $y=81, $w=11, $h=11, $link='', $align='', $palign='', $border=0, $fitonpage=false);
    
                PDF::ImageSVG($file=public_path($woolmark_logo_file),$x=$xx+43, $y=97, $w=29, $h=29, $link='', $align='', $palign='', $border=0, $fitonpage=false);

                $i++;
                $j++;
                $first_time++;
            }
        }
        else
        {
            PDF::ImageSVG('@' . $svgStringFront, $x=$xx, $y=80, $w=41, $h=115.5, $link='', $align='', $palign='', $border=0, $fitonpage=false);

            $style6 = array('width' => '0.25', 'cap' => '', 'join' => '', 'dash' => '', 'color' => array(0.67,99, 0.56, 0));

            PDF::RoundedRect($xx, 80, 41, 115.5, 0.8, '1111', null, $style6);

        
            foreach($data_back as $key => $value)
            {
                if($first_page == 0)
                {
                    if($i == 3)
                    {
                        $i=0;
                        $wri_x=-129;
                        PDF::AddPage();
                        $first_page++;
                    }
                }
                else
                {
                    if($i == 4)
                    {
                        $i=0;
                        $wri_x=-129;
                        PDF::AddPage();
                    }
                }
                
    
                if($i == 0)
                {
                    $xx=15;
                }
                else
                {
                    $xx+=45;
                }
    
                if($first_time == 0)
                {
                    $xx+=45;
                }
    
                $siz_cat = explode(',',$size_category_fin[$j]);
    
                $size_comp = explode(',',$size_comp_fin[$j]);
    
                if($request->template_name != "FFC/01/M13020C" && $request->template_name != "GE/01/M13114C - Opt 1")
                {
                    if(count($siz_cat) <= 3)
                    {
                        $wri_y=105;
                    }
                    else if(count($siz_cat) == 4)
                    {
                        $wri_y=103;
                    }
                    else if(count($siz_cat) == 5)
                    {
                        $wri_y=101.5;
                    }
                }
                else
                {
                    if(count($siz_cat) <= 3)
                    {
                        $wri_y=103;
                    }
                    else if(count($siz_cat) == 4)
                    {
                        $wri_y=102;
                    }
                    else if(count($siz_cat) == 5)
                    {
                        $wri_y=101;
                    }
                }
                
    
                if($request->template_name == "FFK/01/M13021C" || $request->template_name == "FFB/01/M13023C" || $request->template_name == "GE/01/M13114C - Opt 2" || $request->template_name == "GE/01/M13114C - Opt 4" || $request->template_name == "FFC/01/M13020C" || $request->template_name == "GE/01/M13114C - Opt 1")
                {
                    if($size_category_fin[$j] != "null")
                    {
                        foreach($siz_cat as $val_cat)
                        {
                            if (str_contains($val_cat, 'height')) {
                                $val_cat = str_replace('height','<label style="font-family:FandFSans-Bold;font-weight:700; font-size:5px;">Height</label>',$val_cat);
                            }
                            
                            if (str_contains($val_cat, 'weight')) {
                                $val_cat = str_replace('weight','<label style="font-family:FandFSans-Bold;font-weight:700; font-size:5px;">Weight</label>',$val_cat);
                            }
        
                            if (str_contains($val_cat, 'chest')) {
                                $val_cat = str_replace('chest','<label style="font-family:FandFSans-Bold;font-weight:700; font-size:5px;">Chest</label>',$val_cat);
                            }
        
                            if (str_contains($val_cat, 'waist')) {
                                $val_cat = str_replace('waist','<label style="font-family:FandFSans-Bold;font-weight:700; font-size:5px;">Waist</label>',$val_cat);
                            }
        
        
                            foreach($size_comp as $string)
                            {
                                if($val_cat != null && $string != null)
                                {
                                    if(strpos($val_cat, $string) !== false) 
                                    {
                                        $val_cat = str_replace($string,'<label style="font-family:FandFSans-Bold;font-weight:700; font-size:5px;">'.$string.'.</label>',$val_cat);
                                    }
                                }
                            }
        
                            $html = '<p style="font-family:FandFSans-Regular; font-size:5px;">'.$val_cat.'</p>';
        
                          //  print_r($html);
            
                            PDF::writeHTMLCell($w=0, $h=0, $x=$wri_x, $y=$wri_y, $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='C', $autopadding=false);
                            $wri_y+=2;
                        }
                    }
	
					// if($request->template_name != "FFC/01/M13020C" && $request->template_name != "GE/01/M13114C - Opt 1")
                    // {
    
						$par_pro = json_decode($product_desc_fin[0]);
						
						if($size_category_fin[$j] != "null")
						{
							if(strlen($par_pro{0}->slovak." • ".$par_pro{0}->hungarian) > 64)
							{
								$fo_si = "4.5px";
								$let_spac = "-0.2px";
							}
							else
							{
								$fo_si = "5px";
								$let_spac = "0px";
							}
							$html = '<p style="font-family:FandFSans-Regular; font-size:'.$fo_si.';"><label  style="font-family:FandFSans-Bold; font-size:5px;">'.$par_pro{0}->english.'</label> • '.$par_pro{0}->czech.'</p>';
		
							//  print_r($html);
		
							PDF::writeHTMLCell($w=0, $h=0, $x=$wri_x+1, $y=111.5, $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='C', $autopadding=false);
		
							$html = '<p style="font-family:FandFSans-Regular;letter-spacing:'.$let_spac.'; font-size:'.$fo_si.';"><label  style="font-family:FandFSans-Regular; font-size:'.$fo_si.';">'.$par_pro{0}->slovak.'</label> • '.$par_pro{0}->hungarian.'</p>';
		
							//  print_r($html);
		
							PDF::writeHTMLCell($w=0, $h=0, $x=$wri_x+1, $y=113.5, $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='C', $autopadding=false);
						}
						else
						{
							if(strlen($par_pro{0}->slovak." • ".$par_pro{0}->hungarian) > 64)
							{
								$fo_si = "4.5px";
								$let_spac = "-0.2px";
							}
							else
							{
								$fo_si = "5px";
								$let_spac = "0px";
							}
							$html = '<p style="font-family:FandFSans-Regular; font-size:'.$fo_si.';"><label  style="font-family:FandFSans-Bold; font-size:5px;">'.$par_pro{0}->english.'</label> • '.$par_pro{0}->czech.'</p>';
		
							//  print_r($html);
		
							PDF::writeHTMLCell($w=0, $h=0, $x=$wri_x+1, $y=107, $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='C', $autopadding=false);
							
							$html = '<p style="font-family:FandFSans-Regular;letter-spacing:'.$let_spac.'; font-size:'.$fo_si.';"><label  style="font-family:FandFSans-Regular; font-size:'.$fo_si.';">'.$par_pro{0}->slovak.'</label> • '.$par_pro{0}->hungarian.'</p>';
		
							//  print_r($html);
		
							PDF::writeHTMLCell($w=0, $h=0, $x=$wri_x+1, $y=109, $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='C', $autopadding=false);
						}
					//}
                }
    
                
    
                $svgString=urldecode($value);
                PDF::ImageSVG('@' . $svgString, $x=$xx, $y=80, $w=41, $h=115.5, $link='', $align='', $palign='', $border=0, $fitonpage=false); 

                $style6 = array('width' => '0.25', 'cap' => '', 'join' => '', 'dash' => '', 'color' => array(0.67,99, 0.56, 0));

                PDF::RoundedRect($xx, 80, 41, 115.5, 0.8, '1111', null, $style6);
    
    
                PDF::ImageSVG($file=public_path($wee_symbol_fin[0]), $x=$xx+30, $y=81, $w=11, $h=11, $link='', $align='', $palign='', $border=0, $fitonpage=false);
    
                PDF::ImageSVG($file=public_path($woolmark_logo_file), $x=$xx+43, $y=97, $w=29, $h=29, $link='', $align='', $palign='', $border=0, $fitonpage=false);
    
                if($request->template_name == "FFB/01/M13023C")
                {
                    if($ce_symbol_fin[0] == "true")
                    {
                        PDF::ImageSVG($file=public_path('/images/CE.svg'), $x=$xx+3, $y=83, $w=7, $h=7, $link='', $align='', $palign='', $border=0, $fitonpage=false);
                    }
                }
               
                
    
                $i++;
                $j++;
                $first_time++;
                $wri_x+=90;
            }
        }
        

        PDF::Output( public_path('/storage/app/template/'.substr($request->template_name, strrpos($request->template_name, '/') + 1).'_ID_'.$request->style.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.substr($request->template_name, strrpos($request->template_name, '/') + 1).'_ID_'.$request->style.'.pdf'));
        
     }
}
