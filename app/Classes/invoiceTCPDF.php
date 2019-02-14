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

class invoiceTCPDF extends \TCPDF
{
    public $company;

    public function setCompanyData($company_details){
        $this->company         = $company_details;

    }
    /**
    * Overwrite Header() method.
    * @public
    */
    public function Header() {
        $this->SetTopMargin(45);
        // Position at 5 mm from top
        $this->SetY(6);
        // Set font
        $this->SetFont('helvetica', '', 8);
        $this->Image('././././'.$this->company->logo, 160, 10, 35, 15);
    $header =  ' <table>
                    <tr class="blank_row">
                        <td rowsopan="1"></td>
                    </tr>
                </table>
                <table>
                  <tr nobr="true">
                    <td rowsopan="3">
                        <span style="font-size:9px;"><strong>'.$this->company->name.'</strong></span><br>   
                        <span>'.$this->company->address.'</span><br>
                        <span>'.$this->company->office_phone.'</span><span>,'.$this->company->phone.'</span><br>
                        <span><strong>GSTIN  : </strong>'.$this->company->gstin.'</span>
                        </td>
                    </tr>
                </table>
                 <table>
                    <tr class="blank_row">
                        <td rowsopan="1"></td>
                    </tr>
                </table>';
        // Title
        $this->writeHTML($header, false, false, true, false, '');
        $this->writeHTML("<hr>", false, false, true, false, '');
    }

      /**
    * Overwrite Footer() method.
    * @public
    */
    public function Footer() {
        // Position at 35 mm from bottom
        $this->SetY(-25);
        // Set font
        $this->SetFont('helvetica', '', 8);
        $this->writeHTML("<hr>", false, false, true, false, '');
        $footer = ' <table>
                    <tr class="blank_row">
                        <td rowsopan="1"></td>
                    </tr>
                    </table>
                    <table>
                            <tr>
                                <td width="140"></td>
                                <td width="150"></td>
                                <td width="150" colspan="2">
                                    <span>For Jupiter Garments Accessories</span>
                                </td>
                             </tr>
                            <tr>
                                <td width="120">
                                    <p>Recievers Signature </p>
                                </td>
                                <td width="100">
                                    <p>Prepared By</p>
                                </td>
                                <td width="100">
                                    <p>Verified By</p>
                                </td>
                                <td width="100">
                                    <p>Authorized Signatory</p>
                                </td>
                            </tr>
                        </table>
                    <table>
                        <tr class="blank_row">
                            <td rowsopan="1"></td>
                        </tr>
                    </table>';
        // Title
        $this->writeHTML($footer, false, false, true, false, '');

    }
	
}