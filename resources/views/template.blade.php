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
        border-bottom-width: 1px !important;
        /*border-left-width: 1px !important;*/
         border-top-width: 1px !important;
        /*border-left: 1px solid #DDD !important;*/
        border-top: 1px solid #eee !important;
        /*border-right: 1px solid #DDD !important;*/
        border-bottom: 1px solid #eee !important; 

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
                    <table>
                        <tr> 
                            <td width="3"></td>
                            <td width="210">
                                <p style="font-size: 5px;line-height: 1px;">Buyer</p>
                                <p style="font-size: 9px;line-height: 1px;"><strong>{{$inv_details[0]['company_name']}}</strong>
                                </p>
                                <p>{{$bill_to->address}}
                                <br>GSTN : {{$inv_details[0]['gstin_number']}}
                                </p>
                            </td>
                            <td width="80"></td>
                            <td width="20"></td>
                            <td width="100">
                               
                                <table>
                                    <tr>
                                        <td style="height: 10px;">
                                            <span><strong>TAX INVOICE </strong></span>
                                        </td>
                                    </tr>
                                    <tr class="blank_row">
                                        <td rowspan="3" width="38">
                                        <span><strong>Date</strong></span>
                                        <br>
                                        <span><strong>Invoice No</strong></span>
                                        <br>
                                        <span><strong>Disp thru</strong></span>
                                        </td>
                                        <td rowspan="3" width="8">
                                            <span>:</span><br>
                                            <span>:</span><br>
                                            <span>:</span>
                                        </td>
                                        <td rowspan="3" width="50">
                                            <span>{{$inv_details[0]['inv_date']}}</span><br>
                                            <span>{{$inv_details[0]['invoice_id']}}</span><br>
                                            <span>{{$inv_details[0]['delvry_thru']}}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="payment">
               <td >
                   
                </td>
            </tr>
        </table>
        <hr>
        <table>
            <tr class="blank_row">
                <td rowsopan="1"></td>
            </tr>
        </table>
		<table  align="center" width="100%" cellpadding="1" cellspacing="0" border="0" class="thead-design" ><thead><tr><th width="25"><strong>Sno</strong></th><th width="60"><strong>PO No</strong></th><th><strong>Style</strong></th><th width="60"><strong>OCN</strong></th><th width="40"><strong>Ref</strong></th><th width="40"><strong>Size</strong></th><th><strong>Quantity</strong></th><th width="40"><strong>Rate(INR)</strong></th><th width="42"><strong>Amount(INR)</strong></th></tr></thead><tbody>
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
    	                        <td width="25">{{++$i}}</td>
                                <td width="60">{{$data['po_no']}}</td>
        	                    <td>{{$data['style_no']}}</td>
        	                    <td width="60">{{$data['ocn_number']}}</td>
        	                    <td width="40">{{$data['reference_name']}}</td>
                                @php
                                    $size = $data['size']=="null"?'-':$data['size'];
                                @endphp
                                <td width="40">{{$size}}</td>
        	                    <td>{{$data['delvry_qnty']}}</td>
        	                    <td width="40">{{$data['rate']}}</td>
        	                    <td width="42" style="text-align:right;">{{number_format((float)$data['delvry_qnty'] * $data['rate'], 2, '.', '') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td width="25" class="tdheight"></td>
                        </tr>
                        <tr>
                            <td width="39"></td><td colspan="12"><strong style="text-align:left;"><span>ITEM :  </span><span>{{$data['item_name']}}</span><span> HSNCode :</span><span> {{$data['hsn_code']}}</span></strong></td>
                        </tr>
                        <tr>                    
                                <td width="25">{{++$i}}</td>
                                <!-- <td>{{$data['id']}}</td> -->
                                <td width="60">{{$data['po_no']}}</td>
                                <td>{{$data['style_no']}}</td>
                                <td width="60">{{$data['ocn_number']}}</td>
                                <td width="40">{{$data['reference_name']}}</td>
                                @php
                                    $size = $data['size']=="null"?'-':$data['size'];
                                @endphp
                                <td width="40">{{$size}}</td>
                                <!-- <td>{{$data['tax']}}</td> -->
                                <td>{{$data['delvry_qnty']}}</td>
                                <td width="40">{{$data['rate']}}</td>
                                <td width="42" style="text-align:right;">{{number_format((float)$data['delvry_qnty'] * $data['rate'], 2, '.', '') }}</td>
                        </tr>
                    @endif
                        @php 
                            $itemName = $data['item_name'];
                            $totalDevlryQnty += $data['delvry_qnty'];
                            $totalAmnt += ($data['delvry_qnty'] * $data['rate']);
                        @endphp
                    @endforeach	
            <!--         <tr>
                        <td colspan="11"></td>
                    </tr> -->
<!--                     <tr>
                    	<td colspan="8">Sub Total</td>
                    	<td>{{$totalDevlryQnty}}</td>
                    	<td></td>
                    	<td>{{number_format((float)$totalAmnt, 2, '.', '') }}</td>
                    </tr> -->
        </tbody>
        </table>
        <hr>
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
            <td> 
                <table>
                    <tr class="blank_row">
                        <td rowsopan="1"></td>
                    </tr>
                </table>
                <table>
                    <tr class="blank_row">
                        <td rowsopan="1"></td>
                    </tr>
                    </table>
                <table>
                        <tr>
                            <td>
                                <p><strong>Credit Terms </strong><span><strong>: </strong></span>
                                <span>{{$inv_details[0]['credit_limit']}}</span> </p>  
                                <p><strong>E-Way Bill No : </strong><span>{{$eway_no}}</span></p>                         
                            </td>

                        </tr>
                </table>
                </td>
                
                <td >
                    <table>
                    <tr class="blank_row">
                        <td rowsopan="1"></td>
                    </tr>
                    </table>
                    <table class="tabl-design" cellpadding="0" cellspacing="0" border="0">                
                            <tr>
                                <td width="81"><strong>Sub Total</strong></td>
                                <td width="78"><strong>{{$totalDevlryQnty}}</strong></td>
                                <td width="30" style="text-align:right;"><strong>{{number_format((float)$totalAmnt, 2, '.', '') }}</strong></td>
                            </tr>
                            @php 
                                $totalTax = 0;
                            @endphp
                            @foreach($uniqueTaxes as $tax=>$amnt)               
                                @if($inv_details[0]['place_of_supply']==1)
                                    <tr>
                                        <td width="169" colspan="2">CGST ({{$tax/2}}%)</td>
                                        <td width="80" >{{number_format((float)(($tax/2)/100)*$amnt, 2, '.', '') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">SGST ({{$tax/2}}%)</td>
                                        <td>{{number_format((float)(($tax/2)/100)*$amnt, 2, '.', '') }}</td>
                                    </tr>
                                @elseif($inv_details[0]['place_of_supply']==2)
                                     <tr>
                                      <td width="165.5">IGST ({{$tax}}%)</td>
                                      <td width="80">{{number_format((float)(($tax)/100)*$amnt, 2, '.', '') }}</td>
                                    </tr> 
                                @else
                                    <tr>
                                        <td>Taxes ({{$tax}}%)</td><td>{{$amnt}}</td><td>{{number_format((float)(($tax)/100)*$amnt, 2, '.', '') }}</td>
                                    </tr>
                                        
                                @endif
                                
                                @php 
                                    $totalTax += ($tax/100)*$amnt;
                                @endphp
                            @endforeach 
                        </tbody>
                    </table>
                     @if($eChargesArr)
                    <table class="tabl-design" cellpadding="0" cellspacing="0" border="0"> 
                           @php 
                                $totalExc = 0;
                            @endphp
                            @foreach($eChargesArr as $key=>$vals)               
                            <tr>
                                <td width="166" colspan="2">{{$vals->ext_chrg_name}}</td>
                                <td width="82">{{number_format((float)$vals->amount, 2, '.', '')}}</td>
                            </tr>
                            @php 
                                $totalExc += $vals->amount;
                            @endphp
                            @endforeach 
                            <tr>
                                <td width="176" colspan="2" ><strong>Round Off</strong></td>
                                <td width="200"><strong>{{number_format((float)$inv_details[0]['round_off_amnt'], 2, '.', '') }}</strong></td>
                            </tr>
                    </tbody>
                    </table>
                    @endif
                </td>
            </tr>
     <!--        <tr class="information">
                <td>
                    <table>
                    <tr class="blank_row">
                        <td rowsopan="1"></td>
                    </tr>
                    </table>
                </td>
            </tr> -->
            <tr class="payment">
               <td >
                   
                </td>
            </tr>
        </table>
        <hr>
        <table>
        <tr class="blank_row">
            <td rowsopan="1"></td>
        </tr>
        </table>
        <table>
             <tr>
                 <td width="198">          
                    <p><strong>Amount in words :</strong></p>
                    <p>{{$amntInwords}}</p>               
                </td>
                <td colspan="2" width="168">          
                      <span><strong>Total Amount</strong></span>             
                </td>
                <td  width="40">          
                    <span><strong>{{number_format((float)$inv_details[0]['grand_total'], 2, '.', '')}}</strong></span>
                </td>

            </tr>
        </table>
        <table>
        <tr class="blank_row">
            <td rowsopan="1"></td>
        </tr>
        </table>
        <hr>
        <table>
             <tr>
                <td width="200">
                    <p><strong>Delivery at :</strong></p>
                    <p>{{$inv_details[0]['address']}},{{$inv_details[0]['pincode']}}</p>
                </td>
            </tr>
        </table> 
</body>
</html>