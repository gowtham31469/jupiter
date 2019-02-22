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
                            <td width="35%" style="border-top:1px solid #6f7072;border-left:1px solid #6f7072;border-bottom:1px solid #6f7072;font-size:18px;"><strong id="jc_company_name" >'.$this->company->company.'</strong>
                                <span>'.$this->company->company_address.'</span>
                            </td>
                            <td width="5%" style="border-top:1px solid #6f7072;border-bottom:1px solid #6f7072;"></td>
                            <td width="20%" style="border-top:1px solid #6f7072;border-bottom:1px solid #6f7072;">
                              <strong bgcolor="#DCDCDC" style="letter-spacing:+0.254mm;text-align:center; align:top;font-size:22px;">WORKORDER</strong>
                            </td>
                            <td width="5%" style="border-top:1px solid #6f7072;border-bottom:1px solid #6f7072;"></td>
                            <td width="35%" style="border-top:1px solid #6f7072;border-right:1px solid #6f7072;border-bottom:1px solid #6f7072;">
                            <table cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr width="100%">
                                        <th width="25%"><strong>WO No</strong></th>
                                        <td width="5%"><strong>:</strong></td>
                                        <td width="70%" id="work_order" style="text-align:left;font-weight: bold">'.$this->workorder_id.'</td>
                                    </tr>
                                    <tr  width="100%">
                                        <th  width="25%"><strong>ARTICLE</strong></th>
                                        <td  width="5%"><strong>:</strong></td>
                                        <td width="70%" id="article" style="text-align:left;font-weight: bold">'.$this->article.'</td>
                                    </tr>
                                    <tr  width="100%">
                                        <th  width="25%"><strong>DATE</strong></th>
                                        <td  width="5%"><strong>:</strong></td>
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
        
        $footer = '<style>

                    td {
                        height: 12px;
                        text-align: left !important;
                    }

                    th {
                        height: 12px;
                        text-align: left !important;
                        font-weight: bold;
                        /*background-color: #D3D3D3;*/
                        background-color: #DCDCDC;
                    }
                    </style>
                    <table>
                        <tr class="blank_row">
                            <td rowspan="1"></td>
                        </tr>
                        <tr class="blank_row">
                            <td rowspan="1"></td>
                        </tr>
                    </table>
                    <table cellspacing="0" cellpadding="3" style="table-layout: fixed;width: 100%;border-collapse: collapse;" nobr="true">
                            <tr nobr="true">
                                <th style="border:1px solid #6f7072;">CompiledBy</th>
                                <td  id="customer" style="border:1px solid #6f7072;">'.$this->compiled_by.'</td>
                                <th style="border:1px solid #6f7072;">BarcodeRead</th>
                                <td id="style_no" style="border:1px solid #6f7072;">'.$this->barcode_read.'</td>
                                <th style="border:1px solid #6f7072;">Operator</th>
                                <td  id="order_type" style="border:1px solid #6f7072;">'.$this->operator.'</td>
                                <th  style="border:1px solid #6f7072;">Checker</th>
                                <td  style="border:1px solid #6f7072;">'.$this->checker.'</td>
                            </tr>
                            <tr nobr="true">
                                <th style="border:1px solid #6f7072;">AuthorizedBy</th>
                                <td style="border:1px solid #6f7072;">'.$this->authorized_by.'</td>
                                <th style="border:1px solid #6f7072;">AxiconVerifier</th>
                                <td style="border:1px solid #6f7072;">'.$this->axicon_verifier.'</td>
                                <th style="border:1px solid #6f7072;">ApprovedBy</th>
                                <td style="border:1px solid #6f7072;">'.$this->approved_by.'</td>
                                <th style="border:1px solid #6f7072;">Packer</th>
                                <td style="border:1px solid #6f7072;">'.$this->packer.'</td>
                            </tr>
                            <tr nobr="true">
                                <th  style="border:1px solid #6f7072;">ApprovalDt</th>
                                <td  style="border:1px solid #6f7072;">'.$this->approval_date.'</td>
                                <th  style="border:1px solid #6f7072;">RollIssuedBy</th>
                                <td  style="border:1px solid #6f7072;">'.$this->roll_issued_by.'</td>
                                <th style="border:1px solid #6f7072;">Cutter</th>
                                <td style="border:1px solid #6f7072;">'.$this->cutter.'</td>
                                <th style="border:1px solid #6f7072;">Cartons</th>
                                <td style="border:1px solid #6f7072;" >'.$this->cartons.'</td>
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