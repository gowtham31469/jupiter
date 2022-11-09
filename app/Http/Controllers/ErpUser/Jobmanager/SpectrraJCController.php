<?php

namespace App\Http\Controllers\ErpUser\Jobmanager;

use View;
use Carbon\Carbon;
use App\ErpOrderDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Classes\JobcardIDGenerator;
use App\Http\Controllers\Controller;
use App\Classes\SpectrraTemplateMetadata;
use App\Classes\SpectrraCompanyTemplateData;
use App\Classes\SpectrraJobcardPDFHeaderFooter;
use App\Classes\StoreSpectrraJobcard;
use SnappyPDF;

class SpectrraJCController extends Controller
{
    private $erp_jc_status_id;
    private $order_ap_status_id;
    private $jobcard_id_gen_obj;
    private $current_date;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('erpuser');

        $role = ["NON-ARTWORK JOB CARD MANAGER", "JOB CARD MANAGER"];
        // $this->middleware('role_permission:'.$role);
        $this->middleware('role_permission:', ['only' => $role]);

        // get the current time
        $this->current_date = Carbon::now('Asia/Kolkata')->format('Y-m-d');

        // Get Erp status table id for 'Moved To JC Queue' status.
        $this->erp_jc_status_id = DB::table('erp_status')
            ->where('status_name', 'Moved To JC Queue')
            ->value('id');

        // Get order status table id for 'Order Approved' status.
        $this->order_ap_status_id = DB::table('orders_status')
            ->whereIn('status_name', ['Order Approved', 'Order Placed'])
            ->pluck('id')
            ->all();
        $this->jobcard_id_gen_obj = new JobcardIDGenerator();
    }

    public function getSpectrraOrderDetails(Request $request)
    {
        $get_spectrra_metadata = new SpectrraCompanyTemplateData();
        $jobcard_id_gen_obj = new JobcardIDGenerator();
        $spectrra_metadata = new SpectrraTemplateMetadata();

        $order_details = ErpOrderDetails::with('orders:id,po_no,erp_id,customer_id,buyer_id', 'company:id,name,address,logo')
        ->where([
            'erp_order_details.uuid' => $request['uuid_array'],
            'erp_order_details.status' => $this->erp_jc_status_id,
        ])
        ->whereIn('erp_order_details.order_status_id', $this->order_ap_status_id)
        ->select('erp_order_details.style_no', 'erp_order_details.po_verified_date', 'erp_order_details.order_date', 'erp_order_details.delivery_date', 'erp_order_details.erp_order_id', 'erp_order_details.template_id', 'erp_order_details.company_id', 'erp_order_details.reference_name', DB::raw('SUM(order_quantity) as total_qty'))
        ->groupBy('erp_order_details.style_no', 'erp_order_details.po_verified_date', 'erp_order_details.order_date', 'erp_order_details.delivery_date', 'erp_order_details.erp_order_id', 'erp_order_details.template_id', 'erp_order_details.company_id', 'erp_order_details.reference_name')
        ->first();

        $data['template_id'] = $order_details['template_id'];
        $data['buyer'] = strtolower($order_details['orders']['buyer']['buyer_name']);
        $temp_id_array = explode('|', $order_details['template_id']);
        $erp_array = explode('|', $order_details['orders']['id']);
        $buyer_item_table = $order_details['orders']['buyer']['item_master_table'];
        $buyer_template_table = $order_details['orders']['buyer']['template_table'];
        $order_details->po_verified_date = is_null($order_details['po_verified_date']) === true?$order_details['po_verified_date']:Carbon::parse($order_details['po_verified_date'])->format('d-m-Y');
        $order_details->order_date = is_null($order_details['order_date']) === true?$order_details['order_date']:Carbon::parse($order_details['order_date'])->format('d-m-Y');;
        $order_details->delivery_date = is_null($order_details['delivery_date']) === true?$order_details['delivery_date']:Carbon::parse($order_details['delivery_date'])->format('d-m-Y');;

        $spectrra_template_data = $get_spectrra_metadata->getSpectrraCompanyTemplateData($data);
        
        /** Get templates metadata details */
        $template_details = DB::table($buyer_template_table)
                ->select($buyer_item_table . '.item_name as article', $buyer_template_table.'.type as template_type', 'reports_template_level_name.name as name_in_reports')
                ->join($buyer_item_table, $buyer_item_table . '.id', $buyer_template_table . '.hierarchy_id')
                ->join('reports_template_level_name', 'reports_template_level_name' . '.id', $buyer_template_table . '.name_in_reports')
                ->whereIn($buyer_template_table . '.item_id', $temp_id_array)
                ->orderBy($buyer_template_table . '.id')
                ->first();

        // jobcard id format "Itemname(2)FinancialYearERPID(last 5)0001" where  and for old order "JGA-JC-1"
        $article_array = explode('|', $template_details->article);
        $workorder_id = $jobcard_id_gen_obj->generateJobcardID($article_array, $erp_array);

        return response()->json([
            'error' => false,
            'message' => 'success',
            'spectrra_template_data' => $spectrra_template_data,
            'order_details' => $order_details,
            'modal_title' => 'Create Jobcard',
            'workorder_id' => $workorder_id,
            'template_details' => $template_details,
            'today_date'       => Carbon::parse($this->current_date)->format('d-m-Y'),
            'board_category' => $spectrra_metadata->getBoardCategories(),
            'boards' => $spectrra_metadata->getBoards(),
            'color_match' => $spectrra_metadata->getColorMatches(),
        ], 200);
    }


    public function generateSpectrraJCPdf()
    {

        $pdf = new SpectrraJobcardPDFHeaderFooter(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setData(['company' => 'Spectrra Prints', "company_address" => "1-J-26, Jupiter Avenue, Anupparpalayam, Tirupur -641652", 'workorder_id' => 'CS22000010023', 'article' => 'Carton & Polybag Stickers', 'reference' => 'FFC/01/M13020C', 'created_date' => '08-09-2022', 'logo' => public_path('/storage/app/public/images/spectrra-logo.png')]);		
        $pdf->SetPrintHeader(true);
        $pdf->SetPrintFooter(true); 

        //margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        // /** Handle Page break */
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 40);

        // set image scale factor
        $pdf->setImageScale(1.8);

        // set font
        $pdf->SetFont('helvetica', '', 8);

        // add a page
        $pdf->AddPage('P', 'A4');

        // Print 'Pasting' text 90 degree rotated.
        $pdf->StartTransform();
        $pdf->Rotate(90);

        // Set some content to print
        $html = '<strong>Pasting</strong>';

        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell($w=0, $h=0, $x='-44', $y='96', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

        $pdf->StopTransform();

        // set some text to print
        $view = View::make('erp/Jobmanager/spectrra-jobcard-pdf-template');

        //append content to header
        $pdf->SetXY(13.4,25);
        $pdf->writeHTML($view->render());

        //Close and output PDF document
        ob_end_clean();
        // $file_path =  "/storage/app/public/jobcards/spectrra-test."."pdf";	
        // $pdf->IncludeJS("print();");
        return $pdf->Output( "spectrra-test.pdf", 'I');

        // return response()->json(['error'=> false,'message' =>'success', 'file_path' => $file_path],200);	
    }

    public function storeSpectrraJobcard(Request $request)
    {
        $store_jc = new StoreSpectrraJobcard();
        $store_jc->storeSpectrraJC($request);
        
    }
}
