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
        $this->SetY(1);
        // Set font
       $this->SetFont('helvetica', '', 8);
        $header = '<div class="row">
                    <div class="col-md-12">
                        <table>
                            <tr>
                                <td>
                                    <p style="font-size: 9px"><strong>'.$this->company->name.'</strong></p>
                                    <p>'.$this->company->address.'</p>
                                    <p>'.$this->company->office_phone.','.$this->company->phone.'</p>
                                    <p><strong>GSTIN  : </strong>'.$this->company->gstin.'</p>
                                </td>

                                <td style="text-align: right;">
                                    
                                    <img src="'.$this->company->logo.'" style="height: 62px;width:150px;"/>
                                </td>

                            </tr>
                        </table>
                    </div>
                    </div>';
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
        $footer = '<div class="row">
                        <div class="col-md-12">
                         <table>
                                <tr>
                                    <td width="116"></td>
                                    <td width="150"></td>
                                    <td width="150" colspan="2">
                                        <span>For Jupiter Garments Accessories</span>
                                    </td>
                                 </tr>
                                <tr>
                                    <td width="100">
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
                        </div>
                    </div>';
        // Title
        $this->writeHTML($footer, false, false, true, false, '');

    }
	
}