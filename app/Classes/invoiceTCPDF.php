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
    protected $processId =0;
    protected $header ='';
    protected $footer ='';
    static $errorMsg ='';

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
        $this->SetY(5);
        $this->SetX(8);
        // Set font
        $this->SetFont('helvetica', '', 8);
        $this->Image('././././'.$this->company->logo, 160, 10, 30, 10);
        $header =  '<table>
                        <tr class="blank_row">
                            <td rowsopan="1"></td>
                        </tr>
                    </table>
                    <table>
                      <tr nobr="true">
                        <td width="440" rowsopan="3" style="border-bottom:1px solid black;">
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
        $this->writeHTML($header, false, false, true, false, '');       
        $this->writeHTMLCell($w='', $h='', $x='', $y='', $this->header, $border=0, $ln=0, $fill=0, $reseth=true, $align='L', $autopadding=true);

        $this->SetLineStyle( array('width'=>0.40,'color'=> array(0,0,0)));

        $this->Line(8,8, $this->getPageWidth()-8,8); 

        $this->Line($this->getPageWidth()-8,8, $this->getPageWidth()-8, $this->getPageHeight()-8);
        $this->Line(8, $this->getPageHeight()-8, $this->getPageWidth()-8, $this->getPageHeight()-8);
        $this->Line(8,8,8, $this->getPageHeight()-8);
        // Title
        
        // $this->writeHTML("<hr>", false, false, true, false, '');
    }

      /**
    * Overwrite Footer() method.
    * @public
    */
    public function Footer() {
        // Position at 35 mm from bottom
        $this->SetY(-31);
        $this->SetX(8);
        // Set font
        $this->SetFont('helvetica', '', 8);
        // $this->writeHTML("<hr>", false, false, true, false, '');
        
        $footer = ' <table>
                    <tr class="blank_row">
                        <td rowsopan="1"></td>
                    </tr>
                    </table>
                    <table>
                        <tr>
                            <td width="180" style="border-top:1px solid black;"></td>
                            <td width="140" style="border-top:1px solid black;"></td>
                            <td width="120" colspan="2" style="border-top:1px solid black;">
                            </td>
                        </tr>
                        <tr>
                            <td width="148.5" ></td>
                            <td width="140" ></td>
                            <td width="120" colspan="2" >
                                <span>For Jupiter Garments Accessories</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="2" >
                                
                            </td>
                            <td width="116.5">
                                <p>Recievers Signature </p>
                            </td>
                            <td width="100">
                                <p>Prepared By</p>
                            </td>
                            <td width="100">
                                <p>Verified By</p>
                            </td>
                            <td width="90" >
                                <p>Authorized Signatory</p>
                            </td>
                        </tr>
                        <tr>
                            <td width="148.5" ></td>
                            <td width="140"></td>
                            <td width="120" colspan="2">
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