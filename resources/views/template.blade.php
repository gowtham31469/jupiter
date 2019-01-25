<!DOCTYPE html >
<html>
<head>

<style>
   body{
   	font-size: 10px;
   }

   .companyDetails{
   	font-size: 8px !important;
   	margin:0px;
   }
   .tabl-design{
    text-align: center !important;
   }
   </style>
</head>
<body>

<table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td>
                    <table>
                        <tr>
                            <td class="companyDetails">
                                <p><strong>{{$company_details->name}}</strong></p>
                                <p>{{$company_details->address}}</p>
                                <p>{{$company_details->office_phone}}, {{$company_details->phone}}</p>
                                <p><strong>GSTIN  : </strong>{{$company_details->gstin}}</p>
                            </td>

 							<td style="text-align: right;" class="companyDetails">
                                
                                <img src="{{$company_details->logo}}" style="height: 62px;margin-top: 30px;"/>
                            </td>

                        </tr>
                    </table>
                </td>
            </tr>
            <hr>
            <tr class="information">
                <td>
                    <table>
                        <tr> 
                            <td class="companyDetails">
                                <span>Buyer</span>
                                <p><strong>{{$inv_details[0]['company_name']}}</strong></p>
                                <p>{{$bill_to->address}}</p>
                                <p>GSTN : {{$inv_details[0]['gstin_number']}}</p>
                            </td>
 							<td style="text-align: right;" class="companyDetails">
                               
                                <p><strong>Date : </strong>{{$inv_details[0]['inv_date']}}</p>
                                <p><strong>Invoice No :  </strong>{{$inv_details[0]['invoice_id']}}</p>
                                <p><strong>Disp thru : </strong>{{$inv_details[0]['delvry_thru']}}</p>
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
		<table class="tabl-design" cellpadding="0" cellspacing="0" border="0.2"><thead ><tr><th><strong>Sno</strong></th><th><strong>Order No</strong></th><th><strong>PO No</strong></th><th><strong>Style</strong></th><th><strong>OCN</strong></th><th><strong>Ref</strong></th><th><strong>Size</strong></th><th><strong>Tax (%)</strong></th><th><strong>Quantity</strong></th><th><strong>Rate(INR)</strong></th><th><strong>Amount (INR)</strong></th></tr></thead><tbody>
                    @php 
                    	$totalQnty=0;
                    	$totalpendingQnty=0;
                    	$totalDevlryQnty = 0;
                    	$totalAmnt = 0;
                        $i = 0;
                        $size = '-';
                    @endphp
                    @foreach($inv_details as $data)
                    <tr>                    
	                    <td>{{++$i}}</td>
                        <td>{{$data['id']}}</td>
	                    <td>{{$data['po_no']}}</td>
	                    <td>{{$data['style_no']}}</td>
	                    <td>{{$data['ocn_number']}}</td>
	                    <td>{{$data['reference_name']}}</td>
                        @php
                            $size = $data['size']=="null"?'-':$data['size'];
                        @endphp
                        <td>{{$size}}</td>
	                    <td>{{$data['tax']}}</td>
	                    <td>{{$data['delvry_qnty']}}</td>
	                    <td>{{$data['rate']}}</td>
	                    <td>{{number_format((float)$data['delvry_qnty'] * $data['rate'], 2, '.', '') }}</td>
                    </tr>
	                    @php 
	                    	$totalDevlryQnty += $data['delvry_qnty'];
	                    	$totalAmnt += ($data['delvry_qnty'] * $data['rate']);
	                    @endphp
                    @endforeach	

                    <tr>
                    	<td colspan="8">Total</td>
                    	<td>{{$totalDevlryQnty}}</td>
                    	<td></td>
                    	<td>{{number_format((float)$totalAmnt, 2, '.', '') }}</td>
                    </tr>
        </tbody>
        </table>
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
            <td></td>
                <td style="text-align: center;">
                    <table cellpadding="0" cellspacing="0" border="0.5"><thead><tr><th><strong>Tax</strong></th><th><strong>Base value (INR)</strong></th><th><strong>Amount (INR)</strong></th></tr></thead><tbody>                 
                            @php 
                                $totalTax = 0;
                            @endphp
                            @foreach($uniqueTaxes as $tax=>$amnt)               
                                @if($inv_details[0]['place_of_supply']==1)
                                    <tr>
                                        <td>CGST ({{$tax/2}}%)</td><td>{{$amnt}}</td><td>{{number_format((float)(($tax/2)/100)*$amnt, 2, '.', '') }}</td>
                                    </tr>
                                    <tr>
                                        <td>SGST ({{$tax/2}}%)</td><td>{{$amnt}}</td><td>{{number_format((float)(($tax/2)/100)*$amnt, 2, '.', '') }}</td>
                                    </tr>
                                @elseif($inv_details[0]['place_of_supply']==2)
                                     <tr>
                                      <td>IGST ({{$tax}}%)</td><td>{{$amnt}}</td><td>{{number_format((float)(($tax)/100)*$amnt, 2, '.', '') }}</td>
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
                            <tr>
                                <td colspan="2"><strong>SubTotal</strong></td>
                                <td>{{number_format((float)$totalTax, 2, '.', '')}}</td>
                            </tr>
                            @if($eChargesArr)
                            <table cellpadding="0" cellspacing="0" border="0.5"><thead><tr><th colspan="2"><strong>Description</strong></th><th><strong>Amount (INR)</strong></th></tr></thead><tbody> 
                                   @php 
                                        $totalExc = 0;
                                    @endphp
                                    @foreach($eChargesArr as $key=>$vals)               
                                    <tr>
                                        <td colspan="2">{{$vals->ext_chrg_name}}</td>
                                        <td>{{number_format((float)$vals->amount, 2, '.', '')}}</td>
                                    </tr>
                                    @php 
                                        $totalExc += $vals->amount;
                                    @endphp
                                    @endforeach 
                                    <tr>
                                        <td colspan="2"><strong>SubTotal</strong></td>
                                        <td>{{number_format((float)$totalExc, 2, '.', '')}}</td>
                                    </tr>  
                                </tbody>
                            </table>
                            @endif
                            <tr>
                                <td colspan="2"><strong>Round Off</strong></td>
                                <td><strong>{{number_format((float)$inv_details[0]['round_off_amnt'], 2, '.', '') }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>Grand Total</strong></td>
                                <td><strong>{{number_format((float)$inv_details[0]['grand_total'], 2, '.', '')}}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    
                </td>
            </tr>
            <tr class="information">
                <td>
                    <table>
                        <tr>
                            <td class="companyDetails">
                                <p><strong>Credit Terms </strong>{{$inv_details[0]['credit_limit']}}</p>
                                <p><strong>E-Way Bill No </strong>{{$eway_no}}</p>
                                <p></p>
                                <strong>Amount in words </strong><p>{{$amntInwords}}</p>
                                <strong>Delivery at :</strong>
                                <div>{{$inv_details[0]['address']}}
                                <p>{{$inv_details[0]['email']}}</p><p>{{$inv_details[0]['pincode']}}</p>
                                <p>{{$inv_details[0]['telephone']}}</p></div>
                            </td>
                        </tr>
                    </table>
                </td>
              <!--   <td style="text-align: right;">
                    <table cellpadding="0" cellspacing="0" border="0.5"><thead><tr><th>Tax (%)</th><th>Base value (₹)</th><th>Amount (₹)</th></tr></thead><tbody>
                        <tr>
                            <td>
                                
                            </td>

 							<td>
                                
                            </td>
                            <td>
                                
                            </td>

                        </tr>
                        </tbody>
                    </table>
                </td> -->
            </tr>
            <tr class="payment">
               <td >
                   
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <p>For Jupiter Garments Accessories</p>
                </td>
            </tr>
            <tr>
                <td class="companyDetails">
                    <p>Recievers Signature </p>
                </td>
                <td class="companyDetails">
                    <p>Prepared By</p>
                </td>
                <td class="companyDetails">
                    <p>Verified By</p>
                </td>
                <td class="companyDetails">
                    <p>Authorized Signatory</p>
                </td>
            </tr>
        </table>
</body>
</html>