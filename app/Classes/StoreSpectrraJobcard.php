<?php

namespace App\Classes;
use DB;
use View;
use Carbon\Carbon;
use App\SpectrraJobcardMainTable;
use App\Classes\JobcardIDGenerator;
use App\SpectrraJobcardMatchToTable;
use App\Classes\SpectrraJCErpOrderData;
use App\Classes\UpdateCreateJobcardMainTable;
use App\Classes\SpectrraJobcardPDFHeaderFooter;

/**
 * Class to store spectrra prints jobcard data
 * @author [Praveen] <[praveen@wizroots.com]>
 */
class StoreSpectrraJobcard
{
	private $jobcard_id_gen_obj;
    private $spectrra_erp_data;
    private $current_timestamp;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        $this->crud_jc_obj     = new UpdateCreateJobcardMainTable();
        $this->jobcard_id_gen_obj = new JobcardIDGenerator();
        $this->spectrra_erp_data = new SpectrraJCErpOrderData();
        $this->current_timestamp = Carbon::now('Asia/Kolkata')->toDateTimeString();
    }

    public function storeSpectrraJC($request)
    {

        $article_array   = explode(', ', $request['article']);
        $erp_order_data = $this->spectrra_erp_data->getSpectrraERPOrderData($request['erp_id']);
       
        // jobcard id format "Itemname(2)FinancialYearERPID(last 5)0001" where  and for old order "JGA-JC-1"
        $workorder_id = $request['crud_operation_type'] === 'created_at'?$this->jobcard_id_gen_obj->generateJobcardID($article_array, [$request['erp_id']]):$request['workorder_id'];

        $store_jobcard_id = $this->updateCreateSpectrraJC($request, $workorder_id, $this->current_timestamp, $request['crud_operation_type'], $erp_order_data);
        
        /** Store jobcard match to details**/
        if($store_jobcard_id == ''){
            return response()->json(['error'=> true,'message' =>'Error'],400);
        }

        $color_match_array = [];
        for ($i=0; $i < sizeof($request['color_match']); $i++) { 
            $color_match_array[] = array(
                'spectrra_jobcard_main_id' => $store_jobcard_id->id,
                'color_match_text' => DB::table('color_match')->where('id', $request['color_match'][$i])->value('name')
            );
        }
        
        SpectrraJobcardMatchToTable::where('spectrra_jobcard_main_id', $store_jobcard_id->id)->delete();
        SpectrraJobcardMatchToTable::insert($color_match_array);

        /** Update jobcard created status erp order details table */
        // if($request['crud_operation_type'] === 'created_at'){
        //     $crud_jc_obj->updateJCDataHistory($erp_orders_ids, $uuid, $workorder_id, $this->current_timestamp,$buyer, $request);
        // }

        return $this->generateSpectrraJCPdf($request, $workorder_id, $erp_order_data, $store_jobcard_id->id);

    }

    public function generateSpectrraJCPdf($request, $workorder_id, $erp_order_data, $spectrra_last_insert)
    {

        $pdf = new SpectrraJobcardPDFHeaderFooter(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setData(['company' => $erp_order_data->company->name, "company_address" => $erp_order_data->company->address, 'workorder_id' => $workorder_id, 'article' => $request['article'], 'reference' => $request['sp_reference'], 'created_date' => $request['today_date'], 'logo' => public_path($erp_order_data->company->logo)]);		
        $pdf->SetPrintHeader(true);
        $pdf->SetPrintFooter(true); 

        //margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        /** Handle Page break */
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
        $file_path =  "/storage/app/public/jobcards/".$workorder_id."."."pdf";	
        $pdf->IncludeJS("print();");
        $pdf->Output( public_path($file_path), 'F');

        DB::table('spectrra_jobcard_main_tables')->where('id', $spectrra_last_insert)
        ->update([
            'file_path'   => $file_path,
            'updated_at'  => $this->current_timestamp
        ]);
        // return $pdf->Output( "spectrra-test.pdf", 'I');

        return $file_path;	
    }

    public function updateCreateSpectrraJC($request, $workorder_id, $current_timestamp, $crud_type, $erp_order_data)
    {
        
        $po_verified_date      = $erp_order_data->po_verified_date == '' ? NULL : Carbon::parse($erp_order_data->po_verified_date, 'Asia/Kolkata')->toDateString();

        $order_date    = $erp_order_data->order_date == '' ? NULL : Carbon::parse($erp_order_data->order_date, 'Asia/Kolkata')->toDateString();

        $customer_del_date    = $erp_order_data->delivery_date == '' ? NULL : Carbon::parse($erp_order_data->delivery_date, 'Asia/Kolkata')->toDateString();

        $board_text = DB::table('boards')->where('id', $request['board_name'])->value('name');
        $board_category_text = DB::table('board_category')->where('id', $request['board_category'])->value('name');

        return SpectrraJobcardMainTable::updateOrCreate(
            [   'work_order_id' => $workorder_id,],
            [  
                'company_id'                => $erp_order_data->company_id,          
                'customer_name'             => $erp_order_data->orders->customer->short_name, 
                'buyer_name'                => $erp_order_data->orders->buyer->buyer_name, 
                'created_date'              => Carbon::now('Asia/Kolkata')->toDateString(), 
                'article'                   => $request['article'],
                'style_number'              => $erp_order_data->style_no,
                'reference'                 => $request['sp_reference'],
                'order_date'                => $order_date, 
                'customer_del_date'         => $customer_del_date,
                'customer_po_number'        => $erp_order_data->orders->po_no,
                'erp_id'                    => $request['erp_id'],
                'po_verified_date'          => $po_verified_date,
                'color_bar'                 => $request['color_bar'],
                'print_spec_bar'            => $request['print_spec_bar'],
                'tone_bar'                  => $request['tone_bar'],
                'front_colors'              => $request['front_colors'],
                'back_color'                => $request['back_colors'],
                'board_category_id'         => $request['board_category'],
                'board_category_text'       => $board_category_text,
                'board_id'                  => $request['board_name'],
                'board_text'                => $board_text,
                'fsc_non_fsc'               => $request['fsc_non_fsc'],
                'gsm_value'                 => $request['gsm_value'],
                'thickness'                 => $request['thickness'],
                'board_size_length'         => $request['board_size_length'],
                'board_size_width'          => $request['board_size_width'],
                'print_size_length'         => $request['print_size_length'],
                'print_size_width'          => $request['print_size_width'],
                'design_size_length'        => $request['design_size_length'],
                'design_size_width'         => $request['design_size_width'],
                'cut_as'                    => $request['cut_as_1'].'/'.$request['cut_as_2'],
                'repeats'                   => $request['repeats'],
                'die_no'                    => $request['die_no'],
                'cutting'                   => $request['cutting'],
                'printing'                  => $request['printing'],
                'coating'                   => $request['coating'],
                'uv'                        => $request['uv'],
                'lamination'                => $request['lamination_spec'],
                'pasting_board'             => $request['pasting_board'],
                'pasting_gsm'               => $request['pasting_gsm'],
                'die_cutting'               => $request['die_cutting'],
                'window_patch'              => $request['window_patch'],
                'folder_gluer'              => $request['folder_gluer'],
                'embossing'                 => $request['embossing'],
                'debossing'                 => $request['debossing'],
                'perforation'               => $request['perforation'],
                'hole_size'                 => $request['hole_size'],
                'eyelet'                    => $request['eyelet_spec'],
                'string_spec'               => $request['string_spec'],
                'foil'                      => $request['foil'],
                'is_non_artwork_template'   => $erp_order_data->orders->buyer->artwork_flag,
                $crud_type                  => $current_timestamp,
            ]
        );
    }
}
