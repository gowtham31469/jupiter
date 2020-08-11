<?php
namespace App\Classes;

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
    public $single_ref;
    public $act_excess;
    public $sticker_mtr;
    public $settings;
    public $total_mtrs;
    public $last_page_flag = false;


     
    public function setData($company, $workorder_id, $created_date, $article, $material, $compiled_by, $barcode_read, $operator, $checker, $authorized_by, $axicon_verifier, $approved_by, $packer, $approval_date, $roll_issued_by, $cutter, $cartons, $single_ref, $act_excess, $sticker_mtr, $settings, $total_mtrs){
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
        $this->single_ref      = $single_ref;
        $this->act_excess      = $act_excess;
        $this->sticker_mtr     = $sticker_mtr;
        $this->settings        = $settings;
        $this->total_mtrs      = $total_mtrs;
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

        // check if single artwork is combined with ratio artwork
        if (!empty(implode(',',$this->single_ref)))
        {
            // Position at 40 mm from bottom
            $this->SetY(-40);
        }
        else 
        {
            // Position at 32 mm from bottom
            $this->SetY(-32);
        }
        // Set font
        $this->SetFont('helvetica', '', 8);

        if (!empty(implode(',',$this->single_ref)))
        {
            $footer = '<table cellspacing="0" cellpadding="2" style="table-layout: fixed;border-collapse: collapse;" nobr="true">';
                            foreach ($this->single_ref as $key => $ref)
                            {
                                $footer .= '<tr nobr="true">
                                    <td>
                                        <label>ACT EXCESS % (<strong>'.$ref.'</strong>):</label>
                                        <span>'.$this->act_excess[$key].'</span> 
                                    </td>
                                    <td>
                                        <label>STKRS MTRS (<strong>'.$ref.'</strong>):</label>
                                        <span>'.$this->sticker_mtr[$key].'</span> 
                                    </td>
                                    <td>
                                        <label>SETTINGS (<strong>'.$ref.'</strong>) :</label>
                                        <span>'.$this->settings[$key].'</span> 
                                    </td>
                                    <td>
                                        <label>TOTAL MTRS (<strong>'.$ref.'</strong>):</label> 
                                        <span>'.$this->total_mtrs[$key].'</span> 
                                    </td>
                                </tr>';
                            }
            $footer .= '</table>';
        }
        else
        {
            $footer = '<table cellpadding="0" cellspacing="2"></table>';
        }
        
        $footer .= '<style>
                    td {
                        height: 12px;
                        text-align: left !important;
                    }

                    th {
                        height: 12px;
                        text-align: left !important;
                        background-color: #DCDCDC;
                    }
                    </style>
                    <table>
                        <tr class="blank_row">
                            <td rowspan="1"></td>
                        </tr>
                    </table>
                    <table cellspacing="0" cellpadding="3" style="table-layout: fixed;width: 100%;border-collapse: collapse;" nobr="true">
                            <tr nobr="true">
                                <th style="border:1px solid #6f7072;" width="11.5%"><strong>CompiledBy</strong></th>
                                <td  id="customer" style="border:1px solid #6f7072;" width="21.7%">'.$this->compiled_by.'</td>
                                <th style="border:1px solid #6f7072;" width="11.5%"><strong>BarcodeRead</strong></th>
                                <td id="style_no" style="border:1px solid #6f7072;" width="21.7%">'.$this->barcode_read.'</td>
                                <th style="border:1px solid #6f7072;" width="11.5%"><strong>Operator</strong></th>
                                <td  id="order_type" style="border:1px solid #6f7072;" width="21.7%">'.$this->operator.'</td>
                            </tr>
                            <tr nobr="true">
                                <th  style="border:1px solid #6f7072;" width="11.5%"><strong>Checker</strong></th>
                                <td  style="border:1px solid #6f7072;" width="21.7%">'.$this->checker.'</td>
                                <th style="border:1px solid #6f7072;" width="11.5%"><strong>AuthorizedBy</strong></th>
                                <td style="border:1px solid #6f7072;" width="21.7%">'.$this->authorized_by.'</td>
                                <th style="border:1px solid #6f7072;" width="11.5%"><strong>AxiconVerifier</strong></th>
                                <td style="border:1px solid #6f7072;" width="21.7%">'.$this->axicon_verifier.'</td>
                            </tr>
                            <tr nobr="true">
                                <th style="border:1px solid #6f7072;" width="11.5%"><strong>ApprovedBy</strong></th>
                                <td style="border:1px solid #6f7072;" width="21.7%">'.$this->approved_by.'</td>
                                <th style="border:1px solid #6f7072;" width="11.5%"><strong>Packer</strong></th>
                                <td style="border:1px solid #6f7072;" width="21.7%">'.$this->packer.'</td>
                                <th  style="border:1px solid #6f7072;" width="11.5%"><strong>ApprovalDt</strong></th>
                                <td  style="border:1px solid #6f7072;" width="21.7%">'.$this->approval_date.'</td>
                            </tr>
                            <tr nobr="true">
                                <th  style="border:1px solid #6f7072;" width="11.5%"><strong>RollIssuedBy</strong></th>
                                <td  style="border:1px solid #6f7072;" width="21.7%">'.$this->roll_issued_by.'</td>
                                <th style="border:1px solid #6f7072;" width="11.5%"><strong>Cutter</strong></th>
                                <td style="border:1px solid #6f7072;" width="21.7%">'.$this->cutter.'</td>
                                <th style="border:1px solid #6f7072;" width="11.5%"><strong>Cartons</strong></th>
                                <td style="border:1px solid #6f7072;" width="21.7%" >'.$this->cartons.'</td>
                            </tr>
                </table>
                <table>
                    <tr class="blank_row">
                        <td rowspan="1"></td>
                    </tr>
                </table>';
        /** Add footer in last page */
        if ($this->last_page_flag)
        {
            $this->writeHTML($footer, false, false, true, false, '');
            $this->Cell(0, 0, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

        }
        else 
        {
            $this->Cell(0, 70, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }
    
}