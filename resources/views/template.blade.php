<!DOCTYPE html >
<html>
<head>

<style>

  /* .tabl-design{
    text-align: center !important;
   }*/
   td {
            height: 12px;  
      }

    th {
        height: 9px;
        /*vertical-align: middle;*/
        /*width: 45px;*/
        /*text-align: center !important;*/
    }

    table {
        table-layout: fixed;
        width: 100%;
        border-collapse: collapse;
    }
    .thead-design{
        text-align: center !important;
    }

    .thead-design th{

        /*border-right-width: 1px !important;*/
        /*border-bottom: 1px !important;*/
        /*border-left-width: 1px !important;*/
         /*border-top: 1px !important;*/
        /*border-left: 1px solid #DDD !important;*/
        /*border-top: 1px solid #eee !important;*/
        /*border-right: 1px solid #DDD !important;*/
        /*border-bottom: 1px solid #eee !important; */
       /*border-bottom :1px solid black !important;*/

    }
    .tdheight{
         height: 5px !important;
    }
   </style>
</head>
<body>
        <table cellpadding="0" cellspacing="0">
            <tr class="information">
                <td>
                    <table cellpadding="1" cellspacing="0">
                        <tr>
                            <td width="180" style="font-size: 5px; border-right:1px solid black;"><span> Buyer</span></td>
                            <td width="150" style="font-size: 5px; border-left:1px solid black;border-right:1px solid black;"><span> Ship To</span></td>
                            <td width="108" style="border-bottom:1px solid black;" align="center" rowspan="2"><span><strong>TAX INVOICE </strong></span></td>
                        </tr>
                        <tr>
                            <td width="180" style="font-size: 9px; "><span ><strong>{{$inv_details[0]['company_name']}}</strong></span></td>
                            <td width="150" style="font-size: 9px; border-left:1px solid black;border-right:1px solid black;"><span ><strong>{{$inv_details[0]['company_name']}}</strong></span></td>
                        </tr>
                        <tr>
                            <td width="180"><p>{{$bill_to->address}} , {{$bill_to->pincode}}</p></td>
                            <td width="150"  style="border-left:1px solid black"><p>{{$ship_to->address}} ,{{$ship_to->pincode}}</p></td>
                            <td width="54" style="border-left:1px solid black"><span> Date</span><p> Invoice No</p></td>
                            <td width="54" style="border-left:1px solid black;"><span> {{$inv_details[0]['inv_date']}}</span><p> {{$inv_details[0]['invoice_id']}}</p></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="border-left:1px solid black"></td>
                            <td style="border-left:1px solid black;border-right:1px solid black"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="180" style="line-height: 1px; border-right:1px solid black;border-bottom:1px solid black;"><span>GSTN : {{$inv_details[0]['gstin_number']}}</span></td>
                            <td width="150" style="line-height: 1px; border-left:1px solid black;border-bottom:1px solid black;"><span>GSTN : {{$inv_details[0]['gstin_number']}}</span></td>
                            <td width="54" style="line-height: 1px;  border-left:1px solid black;border-bottom:1px solid black;"><br><span> Disp thru</span></td>
                            <td width="54" style="line-height: 1px;  border-left:1px solid black;border-bottom:1px solid black;"><span> {{$inv_details[0]['delvry_thru']}}</span></td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
        
        <table cellpadding="0" cellspacing="0">
            <tr>
                 <td>
                    <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td ></td>
                    </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table cellpadding="1" cellspacing="0" border="0" class="thead-design" ><thead><tr><th style="border-bottom:1px solid black;text-align:left;border-top:1px solid black;" width="25"><strong>Sno</strong></th><th style="border-bottom:1px solid black;border-top:1px solid black;" width="60"><strong>PO No</strong></th><th style="border-bottom:1px solid black;border-top:1px solid black;" width="62"><strong>Style</strong></th><th style="border-top:1px solid black;border-bottom:1px solid black;" width="60"><strong>ByrPo</strong></th><th style="border-bottom:1px solid black;border-top:1px solid black;" width="60"><strong>Ref</strong></th><th style="border-bottom:1px solid black;border-top:1px solid black;" width="45"><strong>Size</strong></th><th style="border-bottom:1px solid black;border-top:1px solid black;" width="40"><strong>Quantity</strong></th><th style="border-bottom:1px solid black;border-top:1px solid black;" width="40"><strong>Rate(INR)</strong></th><th style="border-bottom:1px solid black;border-top:1px solid black;" width="45"><strong>Amount(INR)</strong></th></tr></thead><tbody>
                    @php 
                        $totalQnty=0;
                        $totalpendingQnty=0;
                        $totalDevlryQnty = 0;
                        $totalAmnt = 0;
                        $i = 0;
                        $size = '-';
                        $itemName = "";
                    @endphp
                    @foreach($inv_details as $data)
                    @if($itemName == $data['item_name'])
                        
                        <tr>                    
                                <td style="text-align:left;" width="25">{{++$i}}</td>
                                <td width="60">{{$data['po_no']}}</td>
                                <td width="62">{{$data['style_no']}}</td>
                                <td width="60">{{$data['buyer_po_number']}}</td>
                                <td width="60">{{$data['reference_name']}}</td>
                                @php
                                    $size = $data['size']=="null"?'-':$data['size'];
                                @endphp
                                <td width="45">{{$size}}</td>
                                <td width="40">{{$data['delvry_qnty']}}</td>
                                <td width="40">{{$data['rate']}}</td>
                                <td width="45" style="text-align:right;">{{number_format((float)$data['delvry_qnty'] * $data['rate'], 2, '.', '') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td width="437" colspan="9" class="tdheight"></td>
                        </tr>
                        <tr>
                            <td  width="1"></td>
                            <td width="251" colspan="4"><strong style="text-align:left;"><span>ITEM :  </span><span>{{$data['item_name']}} ,</span><span> OCN :</span><span> {{$data['ocn_number']}}</span></strong></td>
                            <td style="text-align:right;" width="185" colspan="3"><strong><span> HSNCode :</span><span> {{$data['hsn_code']}}</span></strong></td>
                        </tr>
                        <tr>                    
                                <td style="text-align:left;" width="25">{{++$i}}</td>
                                <!-- <td>{{$data['id']}}</td> -->
                                <td width="60">{{$data['po_no']}}</td>
                                <td width="62">{{$data['style_no']}}</td>
                                <td width="60">{{$data['buyer_po_number']}}</td>
                                <td width="60">{{$data['reference_name']}}</td>
                                @php
                                    $size = $data['size']=="null"?'-':$data['size'];
                                @endphp
                                <td width="45">{{$size}}</td>
                                <!-- <td>{{$data['tax']}}</td> -->
                                <td width="40">{{$data['delvry_qnty']}}</td>
                                <td width="40">{{$data['rate']}}</td>
                                <td width="45" style="text-align:right;">{{number_format((float)$data['delvry_qnty'] * $data['rate'], 2, '.', '') }}</td>
                        </tr>
                    @endif
                        @php 
                            $itemName = $data['item_name'];
                            $totalDevlryQnty += $data['delvry_qnty'];
                            $totalAmnt += ($data['delvry_qnty'] * $data['rate']);
                        @endphp
                    @endforeach 
                    </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td></td>
                    </tr>
                    </table>
                </td>
            </tr>    
 
            <tr>
                <td>
                    <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="438" style="border-bottom:1px solid black;"></td>
                    </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="111" rowspan="2" style="border-bottom:1px solid black;">
                            <span><strong>Credit Terms :</strong></span><br><br>
                            <span><strong>E-Way Bill No:</strong></span>
                        </td>
                        <td width="111" rowspan="2" style="border-bottom:1px solid black;">
                            <span><strong>{{$inv_details[0]['credit_limit']}}</strong></span><br><br>
                            <span><strong>{{$eway_no}}</strong></span>   
                        </td>
                        <td width="111" style="border-bottom:1px solid black;">
                            <table class="tabl-design" cellpadding="1" cellspacing="0" border="0">             
                                    <tr>
                                        <td width="101"><strong>Sub Total</strong></td>
                                        <td width="61" style="text-align:left;"><strong>{{$totalDevlryQnty}}</strong></td>
                                        <td width="50" style="text-align:right;"><strong>{{number_format((float)$totalAmnt, 2, '.', '') }}</strong></td>
                                    </tr>
                                    @php 
                                        $totalTax = 0;
                                    @endphp
                                    @foreach($uniqueTaxes as $tax=>$amnt)               
                                        @if($inv_details[0]['place_of_supply']==1)
                                            <tr>
                                                <td width="162" colspan="2">CGST @ {{$tax/2}}%</td>
                                                <td width="50" style="text-align: right;">{{number_format((float)(($tax/2)/100)*$amnt, 2, '.', '') }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">SGST @ {{$tax/2}}%</td>
                                                <td style="text-align: right;">{{number_format((float)(($tax/2)/100)*$amnt, 2, '.', '') }}</td>
                                            </tr>
                                        @elseif($inv_details[0]['place_of_supply']==2)
                                             <tr>
                                              <td width="165.5">IGST @ {{$tax}}%</td>
                                              <td width="80" style="text-align: right;">{{number_format((float)(($tax)/100)*$amnt, 2, '.', '') }}</td>
                                            </tr> 
                                        @else
                                            <tr>
                                                <td width="165.5">Taxes @ {{$tax}}%</td>
                                                <td width="80" style="text-align: right;">{{$amnt}}</td><td>{{number_format((float)(($tax)/100)*$amnt, 2, '.', '') }}</td>
                                            </tr>
                                                
                                        @endif
                                        @php 
                                            $totalTax += ($tax/100)*$amnt;
                                        @endphp
                                    @endforeach 
                            
                            </table>
                            @if($eChargesArr)
                            <table class="tabl-design" cellpadding="1" cellspacing="0" border="0"> 
                                   @php 
                                        $totalExc = 0;
                                    @endphp
                                    @foreach($eChargesArr as $key=>$vals)               
                                    <tr>
                                        <td width="162" colspan="2">{{$vals->ext_chrg_name}}</td>
                                        <td width="50" style="text-align: right;">{{number_format((float)$vals->amount, 2, '.', '')}}</td>
                                    </tr>
                                    @php 
                                        $totalExc += $vals->amount;
                                    @endphp
                                    @endforeach 
                                    <tr>
                                        <td width="162" colspan="2" ><strong>Round Off</strong></td>
                                        <td width="50" style="text-align: right;"><strong>{{number_format((float)$inv_details[0]['round_off_amnt'], 2, '.', '') }}</strong></td>
                                    </tr>
                            </tbody>
                            </table>
                            @endif
                        </td>
                        <td style="border-bottom:1px solid black;">

                        </td>
                    </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <table cellpadding="1" cellspacing="0">
                    <tr>
                        <td width="308" style="border-bottom:1px solid black;">
                            <span>Amount in words :</span><br>
                            <span>{{$amntInwords}}</span>
                        </td>
                        <td width="80" style="border-bottom:1px solid black;">
                            <span><strong>Total Amount</strong></span>
                        </td>
                        <td width="50" style="border-bottom:1px solid black; text-align: right">
                            <span ><strong>{{number_format((float)$inv_details[0]['grand_total'], 2, '.', '')}}</strong></span>
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <table cellpadding="1" cellspacing="0">
                    <tr>
                        <td width="408">
                            <span>Delivery at :</span><br>
                            <span>{{$ship_to->address}},{{$ship_to->pincode}}</span>
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>
        </table>
</body>
</html>