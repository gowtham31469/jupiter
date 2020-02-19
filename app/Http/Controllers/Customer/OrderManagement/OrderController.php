<?php

namespace App\Http\Controllers\Customer\OrderManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use PDF;
use App\Classes\Dashboard;

class OrderController extends Controller
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
	 * Print Preview For 150 * 100 Size Stickers
	 *
	 * @return pdf
	 */
	
    public function printPdf(Request $request)
    {
		 $name=$request->name;
         $svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
        PDF::AddPage();
        PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
        PDF::RoundedRect(12.5, 12.5, 145, 95, 0, '0000');
		
		file_put_contents(public_path('/storage/app/template/demo.svg'), $svg);
					
		
		exec('inkscape -z -e '.public_path('/storage/app/template/out.png').' -w 1000 -h 1000 '.public_path('/storage/app/template/demo.svg'));
		
		$html = '<img style="width:520px;height:341;border:1px solid #333" src="'.public_path('/storage/app/template/out.png').'">';
		PDF::writeHTMLCell(147, 97, 11.5, 12.5, $html, '', 0, 0, false, 'L', false);
        //PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
	
	
	/**
	 * Print Preview For 150 * 100 Size Stickers
	 *
	 * @return pdf
	 */
	
    public function printPdfArr(Request $request)
    {
		 $name=$request->name;
		 $svgarr = explode(',_',$request->svg);
         //$svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
        PDF::setImageScale(1.66);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
		PDF::AddPage('P', 'A4');
		PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		PDF::RoundedRect(12.5, 12.5, 145, 95, 0, '0000');
		PDF::SetAutoPageBreak(TRUE);
		$i=0;
		$j=0;
		$count=0;
		$yy=12.5;
		foreach($svgarr as $value)
        {
			$svg=urldecode($value);
			
			if($i == 2)
			{
				PDF::AddPage('P', 'A4');
				PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
				$yy=12.5;
				PDF::RoundedRect(12.5, 12.5, 145, 95, 0, '0000');
				file_put_contents(public_path('/storage/app/template/demo'.$count.'.svg'), $svg);
					
		
				exec('inkscape -z -e '.public_path('/storage/app/template/out'.$count.'.png').' -w 1000 -h 1000 '.public_path('/storage/app/template/demo'.$count.'.svg'));

				$html = '<img style="width:684px;height:449px;" src="'.public_path('/storage/app/template/out'.$count.'.png').'">';
				PDF::writeHTMLCell(147, 97, 11.5, $yy, $html, '', 0, 0, false, 'L', false);
				//PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				$i = 0;
			}
			else if($i == 0)
			{
				PDF::RoundedRect(10, 160, 150, 100, 0, '0000');
				$yy=12.5;
				PDF::RoundedRect(12.5, 162.5, 145, 95, 0, '0000');
				file_put_contents(public_path('/storage/app/template/demo'.$count.'.svg'), $svg);
					
		
				exec('inkscape -z -e '.public_path('/storage/app/template/out'.$count.'.png').' -w 1000 -h 1000 '.public_path('/storage/app/template/demo'.$count.'.svg'));

				$html = '<img style="width:684px;height:449px;" src="'.public_path('/storage/app/template/out'.$count.'.png').'">';
				PDF::writeHTMLCell(147, 97, 11.5, $yy, $html, '', 0, 0, false, 'L', false);
				//PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				
			}
			else
			{
				PDF::RoundedRect(10, 160, 150, 100, 0, '0000');
				$yy += 150;
				PDF::RoundedRect(12.5, 162.5, 145, 95, 0, '0000');
				file_put_contents(public_path('/storage/app/template/demo'.$count.'.svg'), $svg);
					
		
				exec('inkscape -z -e '.public_path('/storage/app/template/out'.$count.'.png').' -w 1000 -h 1000 '.public_path('/storage/app/template/demo'.$count.'.svg'));

				$html = '<img style="width:684px;height:449px;" src="'.public_path('/storage/app/template/out'.$count.'.png').'">';
				PDF::writeHTMLCell(147, 97, 11.5, $yy, $html, '', 0, 0, false, 'L', false);
				
				//PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
			}
			$i++;
			$j++;
			$count++;
		 }
		 
		 PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
		 print_r(url('/storage/app/template/'.$name.'.pdf'));
    } 
	
	/**
	 * Print Preview For Hang Tag
	 *
	 * @return pdf
	 */
	
	public function printHangPdf(Request $request)
    {
        $svgarrfront=explode(',_',$request->svg); 
		//$svg1=urldecode($request->svg1); 
		$svgarr=explode(',_',$request->svgRoot); 
        define ('PDF_FONT_NAME_MAIN', 'calibrib');
        define ('PDF_FONT_NAME_DATA', 'calibrib');
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('alternategothic2bt');
        PDF::AddPage();
		//PDF::RoundedRect(10, 10, 61, 131, 0, '0000');
		//PDF::SetFillColor(255, 255, 255);
        //PDF::SetTextColor(0, 0, 0);
        //PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, '49', '117', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
        PDF::SetFont('arialbmt');
		$count=0;
		$xx=12.5;
		$i=0;
		$yy=12.5;
		$arr_mer=array_merge($svgarrfront,$svgarr);
		$fir=0;
		$sec = count($arr_mer) / 2;
		foreach($arr_mer as $value)
		{
			$svg=urldecode($value);
			//PDF::RoundedRect(80, 10, 61, 131, 0, '0000');
			//PDF::ImageSVG('@' . $svg,  $x=$xx, $y=12.5, '49', '117', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			
			
			if($count%2==0)  
			{  
				PDF::ImageSVG('@' . urldecode($arr_mer[$fir]),  $x=$xx, $y=$yy, '49', '117', $link='', $align='', $palign='', $border=0, $fitonpage=false);
				$fir++;
			}  
			else  
			{  
				PDF::ImageSVG('@' . urldecode($arr_mer[$sec]),  $x=$xx, $y=$yy, '49', '117', $link='', $align='', $palign='', $border=0, $fitonpage=false);
				$sec++;
			}   
			
			$xx += 65;
			$i++;
			
			if($i == 3)
			{
				//PDF::AddPage();
				//$i=0;
				$xx=12.5;
				$yy=150;
			}
			
			if($i == 6)
			{
				PDF::AddPage();
				$i=0;
				$xx=12.5;
				$yy=12.5;
			}
			$count++;
		}
		
        PDF::Output( public_path('/storage/app/template/solid1.pdf'), 'F');
        print_r(url('/storage/app/template/solid1.pdf'));
    } 	
	
	/**
	 * Print Preview For Special Tag
	 *
	 * @return pdf
	 */
	
	public function printSpecialPdf(Request $request)
    {
        $svg=urldecode($request->svg); 
        define ('PDF_FONT_NAME_MAIN', 'calibrib');
        define ('PDF_FONT_NAME_DATA', 'calibrib');
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
        PDF::AddPage();
        PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		//PDF::SetFillColor(255, 255, 255);
        //PDF::SetTextColor(0, 0, 0);
        PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, '145', '95', $link='', $align='', $palign='', $border=1, $fitonpage=false);
        PDF::Output( public_path('/storage/app/template/solid1.pdf'), 'F');
        print_r(url('/storage/app/template/solid1.pdf'));
    } 
	
	/**
	 * Print Preview For Wash Care Lables.
	 *
	 * @return pdf
	 */
	
	public function printWashPdf(Request $request)
    {
		$svgarr = explode(',_',$request->svg);
        //$svg=urldecode($request->svg);
		
		$made_in_select=$request->made_in_select;
		$orchestra_countries = DB::table('orchestra_countries')->select('*')->where('id',$made_in_select)->get();
		
		
		$care_instruction_select = explode(',',$request->care_instruction_select_fin); 
		
		$orchestra_care_instruction = DB::table('orchestra_care_instruction')->select('*')->whereIn('id',$care_instruction_select)->get();
		
		//print_r($orchestra_countries);
		
		//print_r($orchestra_countries);
		
        $svg=urldecode($request->svg); 
        $svg_back=urldecode($request->svg_back); 
        $svg_middle=urldecode($request->svg_middle); 
        define ('PDF_FONT_NAME_MAIN', 'calibri');
        define ('PDF_FONT_NAME_DATA', 'calibri');
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('tahoma', '',  1.5, '', true);
        PDF::AddPage();
        //PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
		//PDF::SetFillColor(255, 255, 255);
        //PDF::SetTextColor(0, 0, 0);
		
	
		$tbl='<p  style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri;line-height:1.35">Fabriqué en - Made in - <br> Fabricado en - <br>Fabricado em - <br>Fabbricato in - <br>Vervaardigd in  - <br>Hergestellt in -<br><span style="font-family:Tahoma">  ﺻﻨﻊ ب </span>-<br> παράγεται στην</p>';
		
		$tbl1='<p  style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri">'.$orchestra_countries{0}->french.' - '.$orchestra_countries{0}->english.' - '.$orchestra_countries{0}->spanish.' - '.$orchestra_countries{0}->portuguese.' - <br>'.$orchestra_countries{0}->italian.' - '.$orchestra_countries{0}->dutch.' - '.$orchestra_countries{0}->german.' - <br><span style="font-family:Tahoma">'.$orchestra_countries{0}->arabic.'</span> - '.$orchestra_countries{0}->greek.'</p>';
		$tbl2="";
		foreach($orchestra_care_instruction as $value)
		{
			$tbl2.='<p style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri;text-align:left">'.$value->french.' - '.$value->english.' - '.$value->spanish.' - '.$value->portuguese.' - '.$value->italian.' - '.$value->dutch.' - '.$value->german.' - <span style="font-family:Tahoma">'.$value->arabic.'</span> - '.$value->greek.'</p><br><br>';
		}
		
		//print_r($tbl2);
		$tbl3="";
		$composition = json_decode($request->composition);
		//print_r($composition);
		foreach($composition as $key => $value)
		{
			
			$express_value = explode('_',$value[0]);
			if($express_value[0] != "Select Option")
			{
					$exp_value = explode('_',$value[0]);
					$orchestra_parts = DB::table('orchestra_parts')->select('*')->where('id',$exp_value[0])->get();
				
					$tbl3.='<p style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%)">'.$orchestra_parts{0}->french.' - '.$orchestra_parts{0}->english.' - '.$orchestra_parts{0}->spanish.' - '.$orchestra_parts{0}->portuguese.' - '.$orchestra_parts{0}->italian.' - '.$orchestra_parts{0}->dutch.' - '.$orchestra_parts{0}->german.' - <span>'.$orchestra_parts{0}->arabic.'</span> - '.$orchestra_parts{0}->greek.'</p><br><br>';
			}
			foreach($value as $key1 => $value1)
            {
				$exp_val = explode('_',$value1);
				$orchestra_compositions = DB::table('orchestra_compositions')->select('*')->where('english',$exp_val[1])->get();
				
				$tbl3.='<p style="font-size:5px;color:cmyk(0%, 0%, 0%, 100%);font-family:Calibri">'.$exp_val[2].'% '.$orchestra_compositions{0}->french.' - '.$orchestra_compositions{0}->english.' - '.$orchestra_compositions{0}->spanish.' - '.$orchestra_compositions{0}->portuguese.' - '.$orchestra_compositions{0}->italian.' - '.$orchestra_compositions{0}->dutch.' - '.$orchestra_compositions{0}->german.' - <span style="font-family:Tahoma">'.$orchestra_compositions{0}->arabic.'</span> - '.$orchestra_compositions{0}->greek.'</p><br><br>';
			}
		}
		$xx=12.5;
		$i=0;
		foreach($svgarr as $value)
		{
			$svg=urldecode($value);
			PDF::writeHTMLCell('25.5', '40', $xx, $y=5, '<p style="font-size:12px">Back</p>', '', 0, 0, true, 'C', true);
        	PDF::ImageSVG('@' . $svg, $x=$xx, $y=12.5, '25.5', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
			PDF::SetFillColor(255, 255, 255);
			PDF::SetTextColor(0, 0, 0);
			PDF::writeHTMLCell('25.5', '40', $xx, '135', $tbl, '', 0, 0, true, 'C', true);
			PDF::writeHTMLCell('26', '40', $xx, '154.3', $tbl1, '', 0, 0, true, 'C', true);
			PDF::writeHTMLCell('25.5', '40', $xx, $y=180, '<p style="font-size:12px">Front</p>', '', 0, 0, true, 'C', true);
			$xx += 37.5;
			$i++;
			
			if($i == 5)
			{
				PDF::AddPage();
				$i=0;
				$xx=12.5;
			}
		}
		PDF::writeHTMLCell('25.5', '40', $xx, $y=5, '<p style="font-size:12px">Back</p>', '', 0, 0, true, 'C', true);
		PDF::ImageSVG('@' . $svg_back, $xx, $y=12.5, '25.5', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		PDF::writeHTMLCell('22', '40', $xx + 2.25, '25', $tbl2, '', 0, 0, true, 'C', true);
		PDF::writeHTMLCell('25', '40', $xx + 1, '110', $tbl3, '', 0, 0, true, 'C', true);
		PDF::writeHTMLCell('25.5', '40', $xx, $y=180, '<p style="font-size:12px">Front</p>', '', 0, 0, true, 'C', true);
		PDF::AddPage();
		PDF::ImageSVG('@' . $svg_middle, $x=50, $y=12.5, '55.5', '82.5', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		//print_r($i);
//		if($i == 0)
//		{
//			PDF::ImageSVG('@' . $svg_back, $x=12.5, $y=12.5, '25', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//      		PDF::ImageSVG('@' . $svg_middle, $x=50, $y=12.5, '55', '82.5', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//			PDF::writeHTMLCell('22', '40', '14', '25', $tbl2, '', 1, 1, true, 'C', true);
//			PDF::writeHTMLCell('25', '40', '13.5', '110', $tbl3, '', 1, 1, true, 'C', true);
//
//		}
//		else if($i == 1)
//		{
//			PDF::ImageSVG('@' . $svg_back, $x=$xx, $y=12.5, '25', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//      		PDF::ImageSVG('@' . $svg_middle, $x=$xx+37.5, $y=12.5, '55', '82.5', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//			PDF::writeHTMLCell('22', '40', $xx+1.5, '25', $tbl2, '', 1, 1, true, 'C', true);
//			PDF::writeHTMLCell('25', '40', $xx+0.5, '110', $tbl3, '', 1, 1, true, 'C', true);
//		}
//		else if($i == 2)
//		{
//			PDF::ImageSVG('@' . $svg_back, $x=$xx, $y=12.5, '25', '165', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//      		PDF::ImageSVG('@' . $svg_middle, $x=$xx+37.5, $y=12.5, '55', '82.5', $link='', $align='', $palign='', $border=0, $fitonpage=false);
//			PDF::writeHTMLCell('22', '40', $xx+1.5, '25', $tbl2, '', 1, 1, true, 'C', true);
//			PDF::writeHTMLCell('25', '40', $xx+0.5, '110', $tbl3, '', 1, 1, true, 'C', true);
//		}
		
      
		
//		PDF::writeHTMLCell('8', '10', '16.2', '61', $tbl, '', 1, 1, true, 'C', true);
		
	
        PDF::Output( public_path('/storage/app/template/solid1.pdf'), 'F');
        print_r(url('/storage/app/template/solid1.pdf'));
    } 
	
	/**
	 * Print Preview For Standard Poly Bag Stickers
	 * It Contains Dynamic Table
	 * This Function is used to create dynamic table
	 * @return pdf
	 */
	
    public function printPdfTable(Request $request)
    {
         $name=$request->name;
         $svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
		
		$orchestra_color = DB::table('orchestra_color_code')->select('*')->where('code',$color)->get();
		if(count($orchestra_color) > 0)
		{
			$color_code=$orchestra_color{0}->french;
		}
		else
		{
			$color_code="";
		}
		
//          print_r($svg);
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
        PDF::AddPage();
        PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
        PDF::RoundedRect(12.5, 12.5, 145, 95, 0, '0000');
        $td_size="";
        $td_col="";
        $total=0;
        $size=explode(',',$request->size);
        $qty=explode(',',$request->qty);
        $i=0;
		$index_arr=array();
        foreach($size as $val)
        {
			  if (!in_array($val, $index_arr)) 
			  { 
				   if($val != "")
					{
					    array_push($index_arr,$val);
						if(count($size) > 7)
						{
							$td_size.='<td style="font-size:11px;line-height:1.8"><br>'.$val.'</td>';
						}
					    else
						{
							$td_size.="<td><br><br>".$val."</td>";
						}
						$td_col.="<td>".$qty[$i]."</td>";
					} 
			  } 
           
            $total+=$qty[$i];
            $i++;
        }
          $tbl = '<table  border="0.5" style="text-align:center;">
		  			<tr style="line-height:0.53">
                          <td style="font-size:14px;text-align:center;line-height:0.45"><br><br>Size:</td>
                          '.$td_size.'
                          <td><br><br>Total<br></td>
                          </tr>
                      <tr>
                          <td style="font-size:7.5px;line-height:2">'.$color_code.'</td>
                          '.$td_col.'
                          <td>'.$total.'</td>
                          </tr>
            </table>';
        PDF::SetFillColor(255, 255, 255);
        PDF::SetTextColor(0, 0, 0);
		
		file_put_contents(public_path('/storage/app/template/demo.svg'), $svg);
					
		
		exec('inkscape -z -e '.public_path('/storage/app/template/out.png').' -w 1000 -h 1000 '.public_path('/storage/app/template/demo.svg'));
		
		$html = '<img style="width:520px;height:341;border:1px solid #333" src="'.public_path('/storage/app/template/out.png').'">';
		PDF::writeHTMLCell(147, 97, 11.5, 12.5, $html, '', 0, 0, false, 'L', false);
        
        //PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, $w=145, $h=95, $link='', $align='', $palign='', $border=0, $fitonpage=false);
        PDF::writeHTMLCell(147, '', '11.5', '55', $tbl, '', 1, 1, true, '', true);
        
  
        
        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'calibrib',
            'fontsize' => 12,
            'stretchtext' => 4
        );
         ///PDF::write1DBarcode('3531114195599', 'EAN13', '60', '72', '', 32, 0.45, $style, 'N');

       
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
        
    }
	
	/**
	 * Print Preview For 100 * 70 Size Stickers
	 *
	 * @return pdf
	 */
	
     public function printPdfSmall(Request $request)
    {
		 
		  $name=$request->name;
		 $svgarr = explode(',_',$request->svg);
         //$svg=urldecode($request->svg);
         define ('PDF_FONT_NAME_MAIN', 'calibrib');
         define ('PDF_FONT_NAME_DATA', 'calibrib');
//          print_r($svg);
        PDF::setImageScale(1.66);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
		PDF::AddPage('P', 'A4');
		PDF::RoundedRect(10, 10, 100, 70, 0, '0000');
		PDF::RoundedRect(12.5, 12.5, 95, 65, 0, '0000');
//		PDF::SetAutoPageBreak(TRUE);
		$i=0;
		$j=0;
		$yy=12.5;
		$count=0;
		foreach($svgarr as $value)
        {
			$svg=urldecode($value);
			
			if($i == 3)
			{
				PDF::AddPage('P', 'A4');
				PDF::RoundedRect(10, 10, 100, 70, 0, '0000');
				PDF::RoundedRect(12.5, 12.5, 95, 65, 0, '0000');
				$yy=12.5;
				//PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				file_put_contents(public_path('/storage/app/template/demo'.$count.'.svg'), $svg);
					
		
				exec('inkscape -z -e '.public_path('/storage/app/template/out'.$count.'.png').' -w 1000 -h 1000 '.public_path('/storage/app/template/demo'.$count.'.svg'));

				$html = '<img style="width:520px;height:341;" src="'.public_path('/storage/app/template/out'.$count.'.png').'">';
				PDF::writeHTMLCell(97, 67, 11.5, $yy, $html, '', 0, 0, false, 'L', false);
				$i = 0;
			}
			else  if($i == 0)
			{
				PDF::RoundedRect(10, 10, 100, 70, 0, '0000');
				PDF::RoundedRect(12.5, 12.5, 95, 65, 0, '0000');
				$yy=12.5;
				//PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				file_put_contents(public_path('/storage/app/template/demo'.$count.'.svg'), $svg);
					
		
				exec('inkscape -z -e '.public_path('/storage/app/template/out'.$count.'.png').' -w 1000 -h 1000 '.public_path('/storage/app/template/demo'.$count.'.svg'));

				$html = '<img style="width:520px;height:341;" src="'.public_path('/storage/app/template/out'.$count.'.png').'">';
				PDF::writeHTMLCell(97, 67, 11.5, $yy, $html, '', 0, 0, false, 'L', false);
				
			}
			else if($i == 1)
			{
				PDF::RoundedRect(10, 100, 100, 70, 0, '0000');
				PDF::RoundedRect(12.5, 102.5, 95, 65, 0, '0000');
				$yy += 90;
				//PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				file_put_contents(public_path('/storage/app/template/demo'.$count.'.svg'), $svg);
					
		
				exec('inkscape -z -e '.public_path('/storage/app/template/out'.$count.'.png').' -w 1000 -h 1000 '.public_path('/storage/app/template/demo'.$count.'.svg'));

				$html = '<img style="width:520px;height:341;" src="'.public_path('/storage/app/template/out'.$count.'.png').'">';
				PDF::writeHTMLCell(97, 67, 11.5, $yy, $html, '', 0, 0, false, 'L', false);
			}
			else
			{
				PDF::RoundedRect(10, 190, 100, 70, 0, '0000');
				PDF::RoundedRect(12.5, 192.5, 95, 65, 0, '0000');
				$yy += 90;
				//PDF::ImageSVG('@' . $svg, $x=12.5, $y=$yy, $w=95, $h=65, $link='', $align='', $palign='', $border=0, $fitonpage=false);
				file_put_contents(public_path('/storage/app/template/demo'.$count.'.svg'), $svg);
					
		
				exec('inkscape -z -e '.public_path('/storage/app/template/out'.$count.'.png').' -w 1000 -h 1000 '.public_path('/storage/app/template/demo'.$count.'.svg'));

				$html = '<img style="width:520px;height:341;" src="'.public_path('/storage/app/template/out'.$count.'.png').'">';
				PDF::writeHTMLCell(97, 67, 11.5, $yy, $html, '', 0, 0, false, 'L', false);
			}
			$i++;
			$j++;
			$count++;
		 }
        PDF::Output( public_path('/storage/app/template/'.$name.'.pdf'), 'F');
        print_r(url('/storage/app/template/'.$name.'.pdf'));
    }
	/**
     * Function to get tesco tsl template UK , CE size chart based on size entered.
     * @author <Praveen>
     * @param [string] $[size] [<Size entered>], [string] $[zone] [<zone selected>]
     * @return equivalent uk,ce size for size entered
     */
    public function getTescoTSLSizechart(Request $request)
    {
       try {
             
		   $size = $request['size'];
		   $uvc_tco = $request['uvc_tco'];

		   $size = DB::table('orchestra_size_chart')->select('cm as size')->where('age', 'ilike', $size)->where('is_enabled','=',1)->get();
		   
		   $orchestra_parcel_quantity_details = DB::table('orchestra_parcel_quantity_details')->select('*')->where('UVC_TCO', $uvc_tco)->get();
           
           return response()->json(['size_chart' => $size,'opqt' => $orchestra_parcel_quantity_details]);
		   
       } catch (\Exception $e) {
           return response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
       }
    }
}