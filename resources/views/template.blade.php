<!DOCTYPE html >
<html>
<head>

<style>

/*   .tabl-design{
    text-align: center !important;
   }*/
   td {
            height: 10px;
      }

    th {
        height: 15px;
        /*text-align: center !important;*/
    }

    table {
        table-layout: fixed;
        width: 100%;
        border-collapse: collapse;
    }
   </style>
</head>
<body>

<table cellpadding="0" cellspacing="0">
            <tr class="information">
                <td>
                    <table>
                        <tr> 
                            <td width="116">
                                <span style="font-size: 5px; height: 10px;">Buyer</span><br>
                                <span style="font-size: 9px;"><strong>{{$inv_details[0]['company_name']}}</strong></span>
                                <br>
                                <span>{{$bill_to->address}}</span><br>
                                <span>GSTN : {{$inv_details[0]['gstin_number']}}</span>
                            </td>
                            <td width="150"></td>
                            <td width="40"></td>
                            <td width="150">
                               
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
		<table class="tabl-design" cellpadding="0" cellspacing="0" border="0"><thead><tr><th><strong>Sno</strong></th><th><strong>PO No</strong></th><th><strong>Style</strong></th><th><strong>OCN</strong></th><th><strong>Ref</strong></th><th><strong>Size</strong></th><th><strong>Quantity</strong></th><th><strong>Rate(INR)</strong></th><th><strong>Amount (INR)</strong></th></tr></thead><tbody>
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
    	                        <td>{{++$i}}</td>
                                <!-- <td>{{$data['id']}}</td> -->
        	                    <td>{{$data['po_no']}}</td>
        	                    <td>{{$data['style_no']}}</td>
        	                    <td>{{$data['ocn_number']}}</td>
        	                    <td>{{$data['reference_name']}}</td>
                                @php
                                    $size = $data['size']=="null"?'-':$data['size'];
                                @endphp
                                <td>{{$size}}</td>
        	                    <!-- <td>{{$data['tax']}}</td> -->
        	                    <td>{{$data['delvry_qnty']}}</td>
        	                    <td>{{$data['rate']}}</td>
        	                    <td>{{number_format((float)$data['delvry_qnty'] * $data['rate'], 2, '.', '') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td></td><td colspan="12"><strong style="text-align:left;"><span>ITEM :  </span><span>{{$data['item_name']}}</span><span> HSNCode :</span><span> {{$data['hsn_code']}}</span></strong></td>
                        </tr>
                        <tr>                    
                                <td>{{++$i}}</td>
                                <!-- <td>{{$data['id']}}</td> -->
                                <td>{{$data['po_no']}}</td>
                                <td>{{$data['style_no']}}</td>
                                <td width="60">{{$data['ocn_number']}}</td>
                                <td>{{$data['reference_name']}}</td>
                                @php
                                    $size = $data['size']=="null"?'-':$data['size'];
                                @endphp
                                <td>{{$size}}</td>
                                <!-- <td>{{$data['tax']}}</td> -->
                                <td>{{$data['delvry_qnty']}}</td>
                                <td>{{$data['rate']}}</td>
                                <td>{{number_format((float)$data['delvry_qnty'] * $data['rate'], 2, '.', '') }}</td>
                        </tr>
                    
	                    @php 
                            $itemName = $data['item_name'];
	                    	$totalDevlryQnty += $data['delvry_qnty'];
	                    	$totalAmnt += ($data['delvry_qnty'] * $data['rate']);
	                    @endphp
                    @endif
                    @endforeach	
                    <tr>
                        <td colspan="11"></td>
                    </tr>
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
                                <span><strong>Credit Terms </strong> <span><strong>: </strong></span><span>{{$inv_details[0]['credit_limit']}}</span> </span>                            
                            </td>

                        </tr>
                         <tr>
                            <td>
                                <span><strong>E-Way Bill No : </strong></span><span>{{$eway_no}}</span>
                            </td>
                            <td>
                                <span>: </span><span>{{$eway_no}}</span>
                            
                            </td>
                        </tr>
                    </table>
                </td>
                <td></td>
                <td >
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
                    <table class="tabl-design" cellpadding="0" cellspacing="0" border="0">                
                              <tr>
                                <td style="text-align: left">Sub Total</td>
                                <td>{{$totalDevlryQnty}}</td>
                                <td>{{number_format((float)$totalAmnt, 2, '.', '') }}</td>
                            </tr>
                            @php 
                                $totalTax = 0;
                            @endphp
                            @foreach($uniqueTaxes as $tax=>$amnt)               
                                @if($inv_details[0]['place_of_supply']==1)
                                    <tr>
                                        <td colspan="2" style="text-align: left">CGST ({{$tax/2}}%)</td>
                                        <td>{{number_format((float)(($tax/2)/100)*$amnt, 2, '.', '') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="text-align: left">SGST ({{$tax/2}}%)</td>
                                        <td>{{number_format((float)(($tax/2)/100)*$amnt, 2, '.', '') }}</td>
                                    </tr>
                                @elseif($inv_details[0]['place_of_supply']==2)
                                     <tr>
                                      <td>IGST ({{$tax}}%)</td><td>{{number_format((float)(($tax)/100)*$amnt, 2, '.', '') }}</td>
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
                                <td colspan="2" style="text-align: left">{{$vals->ext_chrg_name}}</td>
                                <td>{{number_format((float)$vals->amount, 2, '.', '')}}</td>
                            </tr>
                            @php 
                                $totalExc += $vals->amount;
                            @endphp
                            @endforeach 
                            <tr>
                                <td colspan="2" style="text-align: left"><strong>Round Off</strong></td>
                                <td><strong>{{number_format((float)$inv_details[0]['round_off_amnt'], 2, '.', '') }}</strong></td>
                            </tr>
                    </tbody>
                    </table>
                    @endif
                </td>
            </tr>
            <tr class="information">
                <td>
                    <table>
                    <tr class="blank_row">
                        <td rowsopan="1"></td>
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
        <table>
             <tr>
                 <td width="155">          
                    <span><strong>Amount in words :</strong></span>
                    <br>
                    <span>{{$amntInwords}}</span>               
                </td>
                <td width="35">          
                </td>
                <td style="text-align: right">          
                      <span><strong>Total Amount</strong></span>             
                </td>
                <td  style="text-align: right">          
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
                <td>
                <div>
                    <span><strong>Delivery at :</strong></span><br>
                    <span>{{$inv_details[0]['address']}},</span><br>
                    <span>{{$inv_details[0]['pincode']}}</span>
                </div>
                </td>
            </tr>
        </table> 
</body>
</html>