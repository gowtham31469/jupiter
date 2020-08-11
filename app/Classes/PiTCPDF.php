<?php
namespace App\Classes;

class PiTCPDF extends \TCPDF
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
        $this->SetX(17);
        // Set font
        $this->SetFont('helvetica', '', 8);
        $this->Image('././././'.$this->company->logo, 158, 8, 29, 9);
        if ($this->company->rev_number==0) {
            $header =  ' <table>
                    <tr class="blank_row">
                        <td rowsopan="1"></td>
                    </tr>
                </table>
                <table>
                  <tr nobr="true">
                    <td  width="400"><span style="font-size:9px;"><strong>'.$this->company->name.'</strong></span></td>
                  </tr>
                  <tr nobr="true">
                    <td  width="300"><span>'.$this->company->address.'</span></td>
                  </tr>
                  <tr nobr="true">
                    <td  width="320"><span>'.$this->company->office_phone.'</span><span>,'.$this->company->phone.'</span></td><td width="200"><span><strong>Date  : </strong>'.$this->company->pi_date.'</span></td>
                  </tr>
                  <tr rowspan="2" nobr="true">
                    <td  width="320"><span><strong>GSTIN  : </strong>'.$this->company->gstin.'</span></td>
                    <td  width="200"><span><strong>PI  : </strong>'.$this->company->pi_number.'</span></td>
                  </tr>
                </table>
                 <table>
                    <tr class="blank_row">
                        <td rowsopan="1"></td>
                    </tr>
                </table>';
        }else{
            $header =  ' <table>
                    <tr class="blank_row">
                        <td rowsopan="1"></td>
                    </tr>
                </table>
                <table>
                  <tr nobr="true">
                    <td  width="400"><span style="font-size:9px;"><strong>'.$this->company->name.'</strong></span></td>
                  </tr>
                  <tr nobr="true">
                    <td  width="300"><span>'.$this->company->address.'</span></td>
                  </tr>
                  <tr nobr="true">
                    <td  width="320"><span>'.$this->company->office_phone.'</span><span>,'.$this->company->phone.'</span></td><td width="200"><span><strong>Date  : </strong>'.$this->company->pi_date.'</span></td>
                  </tr>
                  <tr rowspan="2" nobr="true">
                    <td  width="320"><span><strong>GSTIN  : </strong>'.$this->company->gstin.'</span></td>
                    <td  width="200"><span><strong>PI  : </strong>'.$this->company->pi_number.'</span></td>
                  </tr>
                  <tr rowspan="2" nobr="true">
                    <td  width="320"></td>
                    <td  width="200"><span><strong>REV No.  : </strong>'.$this->company->rev_number.'</span></td>
                  </tr>
                </table>
                 <table>
                    <tr class="blank_row">
                        <td rowsopan="1"></td>
                    </tr>
                </table>';
        }
        
        $this->writeHTML($header, false, false, true, false, '');               
        $this->writeHTML("<hr>", false, false, true, false, '');
    }
	
}