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
                                <p>Buyer</p>
                                <p><strong>{{$arr[0]['company_name']}}</strong></p>
                                <p>{{$bill_to->address}}</p>
                                <p>GSTN : {{$arr[0]['gstin_number']}}</p>
                            </td>
 							<td style="text-align: right;" class="companyDetails">
                               
                                <p><strong>Date : </strong>{{$invoiceDate}}</p>
                                <p><strong>Invoice No :  </strong>{{$invoiceNo}}</p>
                                <p><strong>Disp thru : </strong>{{$ship_to->address}}</p>
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
                    @foreach($arr as $data)
                    <tr>                    
	                    <td>{{++$i}}</td>
                        <td>{{$data['id']}}</td>
	                    <td>{{$data['po_no']}}</td>
	                    <td>{{$data['style_no']}}</td>
	                    <td>{{$data['ocn_number']}}</td>
	                    <td>{{$data['reference_name']}}</td>
                        @php
                            $size = $data['size']==""?'-':$data['size'];
                        @endphp
                        <td>{{$size}}</td>
	                    <td>{{$data['tax']}}</td>
	                    <td>{{$data['delvry_qnty']}}</td>
	                    <td>{{$data['rate']}}</td>
	                    <td>{{$data['delvry_qnty'] * $data['rate']}}</td>
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
                    	<td>{{$totalAmnt}}</td>
                    </tr>
        </tbody>
        </table>
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
            <td></td>
                <td style="text-align: center;">
                    <table cellpadding="0" cellspacing="0" border="0.5"><thead><tr><th>Tax (%)</th><th>Base value (INR)</th><th>Amount (INR)</th></tr></thead><tbody>
                   
                        @foreach($tax_amnt as $key=>$tax)
	                        @if ($place_of_supply ==1)
		                        <tr>
			                        <td>CGST ({{$key/2}})</td>
			                        <td>{{$tax}}</td>
			                        <td>{{(($key/2)/100) * $tax}}</td>
		                        </tr>
		                        <tr>
			                        <td>SGST ({{$key/2}})</td>
			                        <td>{{$tax}}</td>
			                        <td>{{(($key/2)/100) * $tax}}</td>
		                        </tr>
	                        @elseif($place_of_supply==2)
		                        <tr>
		                        	<td>IGST ()</td>
			                        <td></td>
			                        <td></td>
		                        </tr>
		                    @else
		                        <tr>
		                        	<td>Tax ()</td>
			                        <td></td>
			                        <td></td>
		                        </tr>
	                        @endif
                        @endforeach
                        	<tr>
	                        	<td colspan="2"><strong>SubTotal</strong></td>
	                        	<td><strong>{{$taxTotal}}</strong></td>
                        	</tr>
                            <tr>
                                <td colspan="2"><strong>Round Off</strong></td>
                                <td><strong>{{$roundOff}}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>Grand Total</strong></td>
                                <td><strong>{{$grandTotal}}</strong></td>
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
                                <p><strong>Credit Terms </strong>{{$creditTerms}}</p>
                                <p><strong>E-Way Bill No </strong>{{$ewayNo}}</p>
                                <p></p>
                                <p><strong>Amount in words</strong>{{$amntInwords}}</p>
                                <p><strong>Delivery at</strong>{{$creditTerms}}</p>
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