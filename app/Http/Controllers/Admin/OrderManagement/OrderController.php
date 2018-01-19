<?php

namespace App\Http\Controllers\Admin\OrderManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Modules;
use Response;
use View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use PDF;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin/login');
    }

    public function dashboard()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {            
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(9);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            
            $buyers = DB::table('buyers')->select('*')->get();
            return view('Admin/Order Management/dashboard',['buyers' => $buyers]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }

    }
	
	 /**
	 * Show the Order dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	
    public function orders()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(9);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            
            $buyers = DB::table('buyers')->select('*')->get();
            return view('Admin/Order Management/orders',['buyers' => $buyers]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }

    }    
	
	 /**
	 * Show the Order Status dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	
    public function orderStatus()
    {
        $module_var = new Modules();
        $is_allowed = $module_var->permittedModules();
        if($is_allowed)
        {
            $get_main_module = $module_var->getMainModules();
            $get_sub_module = $module_var->getSubModules(9);
            View::composer('layouts.Admin.navbar', function($view) use ($get_main_module,$get_sub_module) {
                return $view->with(['modules' => $get_main_module,'sub_modules' => $get_sub_module]);
            });
            
            $buyers = DB::table('buyers')->select('*')->get();
            return view('Admin/Order Management/order-status',['buyers' => $buyers]);
        }
        else
        {
             return redirect('/admin/dashboard');
        }

    }    
	
	/**
	 * Print Preview For 150 * 100 Size Stickers
	 *
	 * @return pdf
	 */
	
    public function printPdf(Request $request)
    {
      
        
//        $svg=$request->svg;
//        $html_content="hai";
//        PDF::SetTitle('sample pdf');
//        PDF::SetFont('calibrib');
//        PDF::AddPage(); public_path('/storage/app/template/std-carton-sticker-02.svg')
//        PDF::ImageSVG($file = url('/storage/app/template/std-carton-sticker-02.svg'), $x=12.5, $y=12.5, $w=145, $h=95, $link='', $align='', $palign='', $border=1, $fitonpage=false);
//        PDF::Output('solid.pdf', 'D');
    
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
        PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, $w=145, $h=95, $link='', $align='', $palign='', $border=1, $fitonpage=false);
        PDF::Output( public_path('/storage/app/template/solid1.pdf'), 'F');
        print_r(url('/storage/app/template/solid1.pdf'));
    } 
	
	/**
	 * Print Preview For Hang Tag
	 *
	 * @return pdf
	 */
	
	public function printHangPdf(Request $request)
    {
        $svg=urldecode($request->svg); 
		$svg1=urldecode($request->svg1); 
        define ('PDF_FONT_NAME_MAIN', 'calibrib');
        define ('PDF_FONT_NAME_DATA', 'calibrib');
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
        PDF::AddPage();
		PDF::RoundedRect(10, 10, 61, 131, 0, '0000');
		//PDF::SetFillColor(255, 255, 255);
        //PDF::SetTextColor(0, 0, 0);
        PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, '55', '125', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
		PDF::RoundedRect(80, 10, 61, 131, 0, '0000');
		PDF::ImageSVG('@' . $svg1, $x=82.5, $y=12.5, '55', '125', $link='', $align='', $palign='', $border=0, $fitonpage=false);
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
	 * Print Preview For Standard Poly Bag Stickers
	 * It Contains Dynamic Table
	 * This Function is used to create dynamic table
	 * @return pdf
	 */
	
    public function printPdfTable(Request $request)
    {
      
        
//        $svg=$request->svg;
//        $html_content="hai";
//        PDF::SetTitle('sample pdf');
//        PDF::SetFont('calibrib');
//        PDF::AddPage(); public_path('/storage/app/template/std-carton-sticker-02.svg')
//        PDF::ImageSVG($file = url('/storage/app/template/std-carton-sticker-02.svg'), $x=12.5, $y=12.5, $w=145, $h=95, $link='', $align='', $palign='', $border=1, $fitonpage=false);
//        PDF::Output('solid.pdf', 'D');
    
        
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
        $td_size="";
        $td_col="";
        $total=0;
        $size=explode(',',$request->size);
        $qty=explode(',',$request->qty);
        $i=0;
        foreach($size as $val)
        {
            if($val != "")
            {
                $td_size.="<td>".$val."</td>";
                $td_col.="<td>".$qty[$i]."</td>";
            }
            $total+=$qty[$i];
            $i++;
        }
          $tbl = '<table  border="0.5" style="text-align:center">
              <tr>
                          <td>Size:</td>
                          '.$td_size.'
                          <td>Total</td>
                          </tr>
                      <tr>
                          <td>ERCU</td>
                          '.$td_col.'
                          <td>'.$total.'</td>
                          </tr>
            </table>';
        PDF::SetFillColor(255, 255, 255);
        PDF::SetTextColor(0, 0, 0);
        
        PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, $w=145, $h=95, $link='', $align='', $palign='', $border=1, $fitonpage=false);
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

       
        PDF::Output( public_path('/storage/app/template/solid1.pdf'), 'F');
        print_r(url('/storage/app/template/solid1.pdf'));
        
//            $svg=urldecode($request->svg);
//         define ('PDF_FONT_NAME_MAIN', 'calibrib');
//         define ('PDF_FONT_NAME_DATA', 'calibrib');
////          print_r($svg);
//        PDF::setImageScale(1);
//        PDF::setPrintHeader(false);
//        PDF::setPrintFooter(false);
//        PDF::SetFont('calibrib');
//        PDF::AddPage();
//        PDF::RoundedRect(10, 10, 150, 100, 0, '0000');
//        PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, $w=145, $h=95, $link='', $align='', $palign='', $border=1, $fitonpage=false);
//        PDF::Output( public_path('/storage/app/template/solid1.pdf'), 'F');
//        print_r(url('/storage/app/template/solid1.pdf'));
    }
	
	/**
	 * Print Preview For 100 * 70 Size Stickers
	 *
	 * @return pdf
	 */
	
     public function printPdfSmall(Request $request)
    {
      
        
        $svg=urldecode($request->svg);
        define ('PDF_FONT_NAME_MAIN', 'calibrib');
        define ('PDF_FONT_NAME_DATA', 'calibrib');
        PDF::setImageScale(1);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetFont('calibrib');
        PDF::AddPage();
        PDF::RoundedRect(10, 10, 100, 70, 0, '0000');
        PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, $w=95, $h=65, $link='', $align='', $palign='', $border=1, $fitonpage=false);
        PDF::Output( public_path('/storage/app/template/solid1.pdf'), 'F');
        print_r(url('/storage/app/template/solid1.pdf'));
    }
    /**
     * method to set the po number and pdf file for an order
     * @param po_number and pdf (pdf is optional)
     * @return \Illuminate\Http\Response
     */
    public function setCustomerPO(Request $request)
    {
        try 
        {
            $po_number = $request->po_number;
            $result = response()->json(['error'=> false,'message' =>'success'],200);    
        }
        catch (Exception $e)
        {
            $result = response()->json(['Message' =>$e->getMessage(),'Line' => $e->getLine(),'File' => $e->getFile()]);
        }
       
        return $result;
    }
}
