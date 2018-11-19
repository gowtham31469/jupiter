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
   </style>
</head>
<body>

<table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td>
                    <table>
                        <tr>
                            <td class="companyDetails">
                                <p>{{$company_details->name}}</p>
                                <p>{{$company_details->address}}</p>
                                <p>{{$company_details->office_phone}}, {{$company_details->phone}}</p>
                                <p>{{$company_details->gstin}}</p>
                            </td>

 							<td style="text-align: right;" class="companyDetails">
                                
                                <img src="{{$company_details->logo}}" style="height: 30px"/>
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
                                <p>Customer Details : </p>
                                <p><strong>Billing Address:</strong></p>
                                <p>{{$arr[0]['company_name']}}</p>
                                <p>GSTN : {{$arr[0]['gstin_number']}}</p>
                                <p>{{$bill_to->address}}</p>
                            </td>
 							<td style="text-align: right;" class="companyDetails">
                                <p><strong>Shipping Address:</strong></p>
                                <p>{{$arr[0]['company_name']}}</p>
                                <p>{{$ship_to->address}}</p>
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
		<table cellpadding="0" cellspacing="0" border="0.5"><thead><tr><th>Order No</th><th>PO No</th><th>Style</th><th>OCN</th><th>Item</th><th>Ref</th><th>Size</th><th>HSN</th><th>Tax (%)</th><th>Order Qty</th><th>Pending Qty</th><th>Del Qty</th><th>Rate (₹)</th><th>Amount (₹)</th></tr></thead><tbody>
                    @php 
                    	$totalQnty=0;
                    	$totalpendingQnty=0;
                    	$totalDevlryQnty = 0;
                    	$totalAmnt = 0;
                    @endphp
                    @foreach($arr as $data)
                    <tr>                    
	                    <td>{{$data['id']}}</td>
	                    <td>{{$data['po_no']}}</td>
	                    <td>{{$data['style_no']}}</td>
	                    <td>{{$data['ocn_number']}}</td>
	                    <td>{{$data['reference_name']}}</td>
	                    <td>{{$data['reference_name']}}</td>
	                    <td>{{$data['size']}}</td>
	                    <td>{{$data['hsn_code']}}</td>
	                    <td>{{$data['tax']}}</td>
	                    <td>{{$data['order_quantity']}}</td>
	                    <td>{{$data['order_quantity'] - $data['delvry_qnty']}}</td>
	                    <td>{{$data['delvry_qnty']}}</td>
	                    <td>{{$data['rate']}}</td>
	                    <td>{{$data['delvry_qnty'] * $data['rate']}}</td>
                    </tr>
	                    @php 
	                    	$totalQnty += $data['order_quantity'];
	                    	$totalpendingQnty += ($data['order_quantity']-$data['delvry_qnty']);
	                    	$totalDevlryQnty += $data['delvry_qnty'];
	                    	$totalAmnt += ($data['delvry_qnty'] * $data['rate']);
	                    @endphp
                    @endforeach	

                    <tr>
                    	<td colspan="9">Total</td>
                    	<td>{{$totalQnty}}</td>
                    	<td>{{$totalpendingQnty}}</td>
                    	<td>{{$totalDevlryQnty}}</td>
                    	<td></td>
                    	<td>{{$totalAmnt}}</td>
                    </tr>
        </tbody>
        </table>

        <table cellpadding="0" cellspacing="0">
            <tr class="top">
            <td></td>
                <td style="text-align: right;">
                    <table cellpadding="0" cellspacing="0" border="0.5"><thead><tr><th>Tax (%)</th><th>Base value (₹)</th><th>Amount (₹)</th></tr></thead><tbody>
                   
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
	                        	<td><strong></strong></td>
                        	</tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="information">
             <td></td>
                <td style="text-align: right;">
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
                </td>
            </tr>
            <tr class="payment">
               <td >
                   
                </td>
            </tr>
        </table>



</body>

</html>