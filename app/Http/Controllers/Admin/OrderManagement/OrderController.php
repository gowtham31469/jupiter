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
    public function printPdf(Request $request)
    {
      
        
//        $svg=$request->svg;
//        $html_content="hai";
//        PDF::SetTitle('sample pdf');
//        PDF::SetFont('calibrib');
//        PDF::AddPage(); public_path('/storage/app/template/std-carton-sticker-02.svg')
//        PDF::ImageSVG($file = url('/storage/app/template/std-carton-sticker-02.svg'), $x=12.5, $y=12.5, $w=145, $h=95, $link='', $align='', $palign='', $border=1, $fitonpage=false);
//        PDF::Output('solid.pdf', 'D');
        
        
        
        
        
             
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
//          $tbl = '<table  border="1" cellpadding="1" cellspacing="1">
//              <tr>
//                          <td>Size:</td>
//                          <td>2 ANS</td>
//                          <td>3 ANS</td>
//                          <td>4 ANS</td>
//                          <td>5 ANS</td>
//                          <td>6 ANS</td>
//                          <td>8 ANS</td>
//                          </tr>
//                      <tr>
//                          <td>ERCU</td>
//                          <td>2</td>
//                          <td>2</td>
//                          <td>2</td>
//                          <td>2</td>
//                          <td>3</td>
//                          <td>3</td>
//                          </tr>
//            </table>';
//        PDF::SetFillColor(255, 255, 255);
//        PDF::SetTextColor(0, 0, 0);
//
//        PDF::ImageSVG('@' . $svg, $x=12.5, $y=12.5, $w=145, $h=95, $link='', $align='', $palign='', $border=1, $fitonpage=false);
//        PDF::writeHTMLCell(147, '', '12', '100', $tbl, '', 1, 1, true, '', true);
//
//       
//        PDF::Output( public_path('/storage/app/template/solid1.pdf'), 'F');
//        print_r(url('/storage/app/template/solid1.pdf'));
        
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
}
