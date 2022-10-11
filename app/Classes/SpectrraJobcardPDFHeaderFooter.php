<?php
namespace App\Classes;

class SpectrraJobcardPDFHeaderFooter extends \TCPDF
{
    public $company;
    public $workorder_id;
    public $created_date;
    public $article;
    public $reference;
    public $company_address;
    public $logo;
     
    public function setData($data){
        $this->company         = $data['company'];
        $this->company_address         = $data['company_address'];
        $this->workorder_id    = $data['workorder_id'];
        $this->created_date    = $data['created_date'];
        $this->article         = $data['article'];
        $this->reference     = $data['reference'];
        $this->logo     = $data['logo'];
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
        // Position at 10 mm from top
        $this->SetY(10);

        $this->Image($this->logo, 17, 10.7, 20, 15, '', '', '', true, 150, '', false, false, 0, true, false, false);
        // Set font
        $this->SetFont('helvetica', '', 8);
        $header = '<table width="100%" cellspacing="0" cellpadding="10" border="0">
                        <tr nobr="true">
                            <td width="12%"  style="border-top: 1px solid #6f7072;border-bottom: 1px solid #6f7072;border-left: 1px solid #6f7072;font-size:18px;">
                            </td>
                            <td width="35%" style="border-top:1px solid #6f7072;border-bottom:1px solid #6f7072;font-size:18px;"><strong id="jc_company_name" >'.$this->company.'</strong>&nbsp;<br><span style="text-align:left;">'.$this->company_address.'</span>
                            </td>
                            <td width="8%" style="border-top:1px solid #6f7072;border-bottom:1px solid #6f7072;"></td>
                            <td width="45%" style="border-top:1px solid #6f7072;border-right:1px solid #6f7072;border-bottom:1px solid #6f7072;">
                            <table cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr width="100%">
                                        <th width="30%"><strong>WORKORDER</strong></th>
                                        <td width="5%"><strong>:</strong></td>
                                        <td width="65%" id="work_order" style="text-align:left;font-weight: bold">'.$this->workorder_id.'</td>
                                    </tr>
                                    <tr  width="100%">
                                        <th  width="30%"><strong>ARTICLE</strong></th>
                                        <td  width="5%"><strong>:</strong></td>
                                        <td width="65%" id="article" style="text-align:left;font-weight: bold">'.$this->article.'</td>
                                    </tr>
                                    <tr  width="100%">
                                        <th  width="30%"><strong>REFERENCE</strong></th>
                                        <td  width="5%"><strong>:</strong></td>
                                        <td width="65%" id="reference" style="text-align:left;font-weight: bold">'.$this->reference.'</td>
                                    </tr>
                                    <tr  width="100%">
                                        <th  width="30%"><strong>DATE</strong></th>
                                        <td  width="5%"><strong>:</strong></td>
                                        <td width="65%" id="today_date" style="text-align:left;font-weight: bold">'.$this->created_date.'</td>
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

        // Position at -5 mm from bottom
        $this->SetY(-5);
        // Set font
        $this->SetFont('helvetica', '', 8);

        $footer = '<table cellpadding="0" cellspacing="1"></table>';
        
        /** Add footer in last page */
        $this->writeHTML($footer, false, false, true, false, '');
        $this->Cell(0, 0, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

    }
    
}