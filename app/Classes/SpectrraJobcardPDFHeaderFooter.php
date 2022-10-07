<?php
namespace App\Classes;

class SpectrraJobcardPDFHeaderFooter extends \TCPDF
{
    // public $company;
    // public $workorder_id;
    // public $created_date;
    // public $article;
    // public $compiled_by;
    // public $barcode_read;
    // public $operator;
    // public $checker;
    // public $authorized_by;
    // public $axicon_verifier;
    // public $approved_by;
    // public $packer;
    // public $approval_date;
    // public $roll_issued_by;
    // public $cutter;
    // public $cartons;
    // public $single_ref;
    // public $act_excess;
    // public $sticker_mtr;
    // public $settings;
    // public $total_mtrs;
    // public $last_page_flag = false;
    // public $inv_no;
    // public $invoice_date;

     
    public function setData(){
        // $this->company         = $company;
        // $this->workorder_id    = $workorder_id;
        // $this->created_date    = $created_date;
        // $this->article         = $article;
        // $this->compiled_by     = $compiled_by;
        // $this->barcode_read    = $barcode_read;
        // $this->operator        = $operator;
        // $this->checker         = $checker;
        // $this->authorized_by   = $authorized_by;
        // $this->axicon_verifier = $axicon_verifier;
        // $this->approved_by     = $approved_by;
        // $this->packer          = $packer;
        // $this->approval_date   = $approval_date;
        // $this->roll_issued_by  = $roll_issued_by;
        // $this->cutter          = $cutter;
        // $this->cartons         = $cartons;
        // $this->single_ref      = $single_ref;
        // $this->act_excess      = $act_excess;
        // $this->sticker_mtr     = $sticker_mtr;
        // $this->settings        = $settings;
        // $this->total_mtrs      = $total_mtrs;
        // $this->inv_no          = $inv_no;
        // $this->invoice_date    = $invoice_date;
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
        $this->SetTopMargin(30);
        // Position at 5 mm from top
        $this->SetY(10);
        // Set font
        $this->SetFont('helvetica', '', 8);
        $header = '<table width="100%" cellspacing="0" cellpadding="5" border="0">
                        <tr nobr="true">
                            <td width="35%" style="border-top:1px solid #6f7072;border-left:1px solid #6f7072;border-bottom:1px solid #6f7072;font-size:18px;"><strong id="jc_company_name" >######</strong>
                                <span>######</span>
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
                                        <th width="32%"><strong>WO No</strong></th>
                                        <td width="5%"><strong>:</strong></td>
                                        <td width="63%" id="work_order" style="text-align:left;font-weight: bold">######</td>
                                    </tr>
                                    <tr  width="100%">
                                        <th  width="32%"><strong>ARTICLE</strong></th>
                                        <td  width="5%"><strong>:</strong></td>
                                        <td width="63%" id="article" style="text-align:left;font-weight: bold">######</td>
                                    </tr>
                                    <tr  width="100%">
                                        <th  width="32%"><strong>REFERENCE</strong></th>
                                        <td  width="5%"><strong>:</strong></td>
                                        <td width="63%" id="reference" style="text-align:left;font-weight: bold">######</td>
                                    </tr>
                                    <tr  width="100%">
                                        <th  width="32%"><strong>DATE</strong></th>
                                        <td  width="5%"><strong>:</strong></td>
                                        <td width="63%" id="today_date" style="text-align:left;font-weight: bold">######</td>
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

        // Position at 32 mm from bottom
        $this->SetY(-5);
        // Set font
        $this->SetFont('helvetica', '', 8);

        $footer = '<table cellpadding="0" cellspacing="2"></table>';
        
        /** Add footer in last page */
        $this->writeHTML($footer, false, false, true, false, '');
        $this->Cell(0, 0, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

    }
    
}