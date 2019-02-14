<?php
namespace App\Classes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use PDF;

class JobcardPDFHeaderFooter extends \TCPDF
{
    public $company;
    public $workorder_id;
    public $created_date;
    public $article;
    public $material;
    public $compiled_by;
    public $barcode_read;
    public $operator;
    public $checker;
    public $authorized_by;
    public $axicon_verifier;
    public $approved_by;
    public $packer;
    public $approval_date;
    public $roll_issued_by;
    public $cutter;
    public $cartons;
     
    public function setData($company, $workorder_id, $created_date, $article, $material, $compiled_by, $barcode_read, $operator, $checker, $authorized_by, $axicon_verifier, $approved_by, $packer, $approval_date, $roll_issued_by, $cutter, $cartons){
        $this->company         = $company;
        $this->workorder_id    = $workorder_id;
        $this->created_date    = $created_date;
        $this->article         = $article;
        $this->material        = $material;
        $this->compiled_by     = $compiled_by;
        $this->barcode_read    = $barcode_read;
        $this->operator        = $operator;
        $this->checker         = $checker;
        $this->authorized_by   = $authorized_by;
        $this->axicon_verifier = $axicon_verifier;
        $this->approved_by     = $approved_by;
        $this->packer          = $packer;
        $this->approval_date   = $approval_date;
        $this->roll_issued_by  = $roll_issued_by;
        $this->cutter          = $cutter;
        $this->cartons         = $cartons;
    }
    /**
    * Overwrite Header() method.
    * @public
    */
    public function Header() {
        $this->SetTopMargin(50);
        // Position at 5 mm from top
        $this->SetY(10);
        // header logo image
        // $this->Image('logo path', x-coordinate, y-coordinate, width, height);
        //$this->Image('././././'.$this->company->company_logo, 160, 10, 35, 15);
        // Set font
        $this->SetFont('helvetica', '', 8);
        $header = '<table width="100%" cellspacing="0" cellpadding="5" border="0">
                        <tr nobr="true">
                            <td width="35%" style="border-top:1px solid black;border-left:1px solid black;border-bottom:1px solid black;"><strong id="jc_company_name" >'.$this->company->company.'</strong>
                                <p id="company_address">'.$this->company->company_address.'</p>
                            </td>
                            <td width="5%" style="border-top:1px solid black;border-bottom:1px solid black;"></td>
                            <td width="20%" style="border-top:1px solid black;border-bottom:1px solid black;">
                              <strong bgcolor="#DCDCDC" style="letter-spacing:+0.254mm;text-align:center; align:top;font-size:22px;">WORKORDER</strong>
                            </td>
                            <td width="5%" style="border-top:1px solid black;border-bottom:1px solid black;"></td>
                            <td width="35%" style="border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;">
                            <table cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr width="100%">
                                        <th width="30%"><strong>WO No: </strong></th>
                                        <td width="70%" id="work_order" style="text-align:left;font-weight: bold">'.$this->workorder_id.'</td>
                                    </tr>
                                    <tr  width="100%">
                                        <th  width="30%"><strong>ARTICLE: </strong></th>
                                        <td width="70%" id="article" style="text-align:left;font-weight: bold">'.$this->article.'</td>
                                    </tr>
                                    <tr  width="100%">
                                        <th  width="30%"><strong>DATE: </strong></th>
                                        <td width="70%" id="today_date" style="text-align:left;font-weight: bold">'.$this->created_date.'</td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>
                        </tr>
                    </table>
                    <table>
                    <tr class="blank_row">
                        <td rowspan="1"></td>
                    </tr>
                </table>';
        // Title
        $this->writeHTML($header, false, false, true, false, '');
        //$this->writeHTML("<hr>", false, false, true, false, '');

    }

    /**
    * Overwrite Footer() method.
    * @public
    */
    public function Footer() {
        // Position at 25 mm from bottom
        $this->SetY(-30);
        // Set font
        $this->SetFont('helvetica', '', 8);
        $this->writeHTML("<hr>", false, false, true, false, '');
        $footer = '<table>
                        <tr class="blank_row" height="10">
                            <td rowspan="1"></td>
                        </tr>
                    </table>
                    <table cellspacing="0" cellpadding="2" style="table-layout: fixed;width: 100%;border-collapse: collapse;" nobr="true">
                            <tr nobr="true">
                                <td >
                                    <label for="compiled_by">COMPILED BY :</label>
                                    <span id="compiled_by">'.$this->compiled_by.'</span> 
                                </td>
                                <td >
                                    <label for="barcode_read">BARCODE READ :</label>
                                    <span id="barcode_read">'.$this->barcode_read.'</span> 
                                </td>
                                <td >
                                    <label for="operator">OPERATOR :</label>
                                    <span id="operator">'.$this->operator.'</span>
                                </td>
                                <td >
                                    <label for="checker">CHECKER :</label>
                                    <span id="checker">'.$this->checker.'</span>
                                </td>
                            </tr>
                            <tr nobr="true">
                                <td >
                                    <label for="author">AUTHORIZED BY :</label>
                                    <span id="author">'.$this->authorized_by.'</span>
                                </td>
                                <td >
                                    <label for="axicon_verifier">AXICON VERIFIER :</label>
                                    <span id="axicon_verifier">'.$this->axicon_verifier.'</span>
                                </td>
                                <td >
                                    <label for="approved_by">APPROVED BY :</label>
                                    <span id="approved_by">'.$this->approved_by.'</span>
                                </td>
                                <td >
                                    <label for="packer">PACKER :</label>
                                    <span id="packer">'.$this->packer.'</span>
                                </td>
                            </tr>
                            <tr nobr="true">
                                <td >
                                    <label for="approval_date">APPROVAL DT :</label>
                                    <span id="approval_date">'.$this->approval_date.'</span>
                                </td>
                                <td >
                                    <label for="roll_issued_by">ROLL ISSUED BY :</label>
                                    <span id="roll_issued_by">'.$this->roll_issued_by.'</span>
                                </td>
                                <td >
                                    <label for="cutter">CUTTER :</label>
                                    <span id="cutter">'.$this->cutter.'</span>
                                </td>
                                <td >
                                    <label for="cartons">CARTONS :</label>
                                    <span id="cartons">'.$this->cartons.'</span>
                                </td>
                            </tr>
                </table>
                <table>
                    <tr class="blank_row">
                        <td rowspan="1"></td>
                    </tr>
                </table>';
        // Title
        $this->writeHTML($footer, false, false, true, false, '');
    }
	
}