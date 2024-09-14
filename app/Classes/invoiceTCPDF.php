<?php
namespace App\Classes;

class invoiceTCPDF extends \TCPDF
{
    public $company;
    private $inv_details;
    private $uniqueTaxes;
    private $extra_charges;
    private $amntInwords;
    private $eway_no;
    protected $processId =0;
    protected $header ='';
    protected $footer ='';
    static $errorMsg ='';
    public $last_page_flag = false;

    public function setCompanyData($company_details, $inv_details, $uniqueTaxes, $eway_no, $extra_charges, $amntInwords){
        $this->company      = $company_details;
        $this->inv_details  = $inv_details;
        $this->uniqueTaxes  = $uniqueTaxes;
        $this->eway_no      = $eway_no;
        $this->extra_charges      = $extra_charges;
        $this->amntInwords      = $amntInwords;

    }

    /**
     * Function to check last page , TCPDF method
     *
     * @return void
     */
    public function Close() {
        $this->last_page_flag = true;
        parent::Close();
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
        $this->Image('././././'.$this->company->logo, 160, 10, 20, 12);
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
        
    }

    /**
    * Overwrite Footer() method.
    * @public
    */
    public function Footer() {

        if(sizeof($this->extra_charges) === 0){
            $this->SetY(-60);
        } else if(sizeof($this->extra_charges) === 1){
            $this->SetY(-65);
        } else if(sizeof($this->extra_charges) === 2){
            $this->SetY(-70);
        } else if(sizeof($this->extra_charges) === 3){
            $this->SetY(-75);
        }

        $this->SetX(8);
        // Set font
        $this->SetFont('helvetica', '', 8);
        

        $totalDevlryQnty = 0;
        $totalAmnt = 0;
        foreach ($this->inv_details as $key => $value) {
            $totalDevlryQnty += $value['delvry_qnty'];
            $totalAmnt += ($value['delvry_qnty'] * $value['rate']);
        }

        $footer = '<table cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="440" colspan="2" style="border-top:1px solid black;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="50" rowspan="2">
                                            <span><strong>Credit Terms :</strong></span><br><br>
                                            <span><strong>E-Way Bill No:</strong></span>
                                        </td>
                                        <td width="232" rowspan="2">
                                            <span><strong>'.$this->inv_details[0]['credit_limit'].'</strong></span><br><br>
                                            <span><strong>'.$this->eway_no.'</strong></span>
                                        </td>
                                        <td width="100">
                                            <table class="tabl-design" cellpadding="1" cellspacing="0" border="0">
                                                <tr>
                                                    <td width="40"><strong>Sub Total</strong></td>
                                                    <td width="60" style="text-align:left;"><strong>'.$totalDevlryQnty.'</strong></td>
                                                    <td width="50" style="text-align:right;"><strong>'.number_format((float)$totalAmnt, 2, '.', '').'</strong></td>
                                                </tr>';

                                                foreach($this->uniqueTaxes as $tax => $amnt) {
                                                    switch ($this->inv_details[0]['place_of_supply']) {
                                                        case 1:
                                                            $tax = $tax/2;
                                                            $footer .=  '<tr>
                                                                            <td width="100" colspan="2">CGST @ '.$tax.'%</td>
                                                                            <td width="50" style="text-align: right;">'.number_format((float)($tax/100)*$amnt, 2, '.', '').'</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">SGST @ '.$tax.'%</td>
                                                                            <td style="text-align: right;">'.number_format((float)($tax/100)*$amnt, 2, '.', '').'</td>
                                                                        </tr>';
                                                            break;
                                                        case 2:
                                                            $footer .= '<tr>
                                                                            <td width="100">IGST @ '.$tax.'%</td>
                                                                            <td width="50" style="text-align: right;">'.number_format((float)(($tax)/100)*$amnt, 2, '.', '').'</td>
                                                                        </tr>';
                                                            break;
                                                        default:
                                                            $footer .= '<tr>
                                                                            <td width="100">Taxes @ '.$tax.'%</td>
                                                                            <td width="50" style="text-align: right;">'.number_format((float)(($tax)/100)*$amnt, 2, '.', '').'</td>
                                                                        </tr>';
                                                            break;
                                                    }
                                                }

                            $footer .= '</table>';
                                            if($this->extra_charges){
                                                $footer .= '<table class="tabl-design" cellpadding="1" cellspacing="0" border="0">';
                                                
                                                foreach($this->extra_charges as $key => $vals){
                                                    $footer .= '<tr>
                                                        <td width="120" colspan="2">'.$vals->ext_chrg_name.'</td>
                                                        <td width="30" style="text-align: right;">'.number_format((float)$vals->amount, 2, '.', '').'</td>
                                                    </tr>';
                                                }
                                                
                                                $footer .= '<tr>
                                                        <td width="130" colspan="2"><strong>Round Off</strong></td>
                                                        <td width="20" style="text-align: right;"><strong>'.number_format((float)$this->inv_details[0]['round_off_amnt'], 2, '.', '').'</strong></td>
                                                    </tr>
                                                    </tbody>
                                                </table>';
                                            }
                        $footer .= '</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="440" colspan="2" style="border-top:1px solid black;"></td>
                        </tr>
                        <tr>
                            <td>
                                <table cellpadding="1" cellspacing="0">
                                    <tr>
                                        <td width="306">
                                            <span>AMOUNT IN WORDS :</span><br>
                                            <span>'.$this->amntInwords.'</span>
                                        </td>
                                        <td width="90">
                                            <span><strong>Total Amount</strong></span>
                                        </td>
                                        <td width="40" style="text-align: right">
                                            <span><strong>'.number_format((float)$this->inv_details[0]['grand_total'], 2, '.', '').'</strong></span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    
                    <table>
                        <tr>
                            <td width="440" colspan="2" style="border-top:1px solid black;"></td>
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
                                <p>Receivers Signature </p>
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
                        
                    </table>';
        

        /** Add footer in last page */
        if ($this->last_page_flag)
        {
            $this->writeHTML($footer, false, false, true, false, '');
            // $this->Cell(0, 11, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

        }
        else 
        {
            // $this->Cell(0, 111, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }
}