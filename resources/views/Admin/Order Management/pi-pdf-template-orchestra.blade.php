<!DOCTYPE html >
<html>
<head>

<style>
   td {
         height: 12px;  
      }

    th {
        height: 9px;
    }

    table {
        table-layout: fixed;
        width: 100%;
        border-collapse: collapse;
    }
    .thead-design{
        text-align: center !important;
    }
    .tdheight{
         height: 5px !important;
    }
   </style>
</head>
    <body>
        <table cellpadding="1" cellspacing="0">
            <tr>
                <td><span><strong>Customer Details</strong></span></td>
            </tr>
            <tr>
                <td width="150"><h3><strong>Billing Address</strong></h3></td>
                <td width="140"></td>
                <td width="140"><h3><strong>Shipping Address</strong></h3></td>
            </tr>
            <tr>
                <td width="150">{{$bank_details->company_name}}</td>
                <td width="140"></td>
                <td width="140s">{{$bank_details->company_name}}</td>
            </tr>
            <tr>
                <td width="150">{{$bill_to->address}}-{{$bill_to->pincode}}</td>
                <td width="140"></td>
                <td width="140">{{$ship_to->address}}-{{$ship_to->pincode}}</td>
            </tr>
            <tr>
                <td width="150">GSTIN : {{$bank_details->gstin_number}}</td>
                <td width="140"></td>
                <td width="140"></td>
            </tr>
            <tr>
                <td width="300"><span></span></td>
            </tr>
        </table>
        <p><strong>ITEM DESCRIPTION & DETAILS :</strong></p>
        <table cellpadding="1" cellspacing="0" border="0.2" class="thead-design" ><thead><tr><th><strong>S.No.</strong></th><th><strong>UVC</strong></th><th><strong>Po.No.</strong></th><th><strong>Ref</strong></th><th><strong>Size</strong></th><th><strong>Quantity</strong></th><th><strong>Unit Cost</strong></th><th><strong>Tax</strong></th><th><strong>Total</strong></th></tr></thead><tbody>
        
            @php 
                $totalAmnt = 0;
                $i = 0;
                $size = '-';
                $noPrice = 0;
                $unit_price = 0;
                $taxamnt = 0;
                $amnt = 0;
                $totaltax = 0;
                $totalcgst = 0;
                $totalsgst = 0;
                $totaligst = 0;
                $billamnt = 0;
                $totalqnty = 0;
                $amount = 0;
                $tax = 0;
                $subtotal = 0;
            @endphp
            @foreach($item_details as $data)
                @php
                    foreach ($price_list as $val){
                        if($data->item_id == $val->item_id){
                            if($data->quantity <= $val->volume_to) {
                                $unit_price = $val->price;
                                $tax = $val->tax;
                            }
                        }
                    }
                    if($unit_price === 0){
                        $noPrice++;
                    }
                    $taxamnt = ($data->quantity*$unit_price)*($tax/100);
                    $amnt =  ($data->quantity*$unit_price) + $taxamnt ;
                    $buyer_po = $data->po_no === ('' || null)?'-':$data->po_no;
                @endphp
                <tr>                    
                    <td>{{++$i}}</td>
                    <td>{{$data->uvc}}</td> 
                    <td>{{$buyer_po}}</td>
                    <td>{{$data->ref_no}}</td>
                    <td>{{$data->size}}</td>
                    <td>{{$data->quantity}}</td>
                    <td>{{$unit_price}}</td>
                    <td>{{$tax}}</td>
                    <td>{{number_format((float)$amnt, 2, '.', '')}}</td>
                </tr>
            @php 
                $totalqnty += $data->quantity;
                $subtotal += $data->quantity*$unit_price;
                $billamnt+=$amnt;
                $totaltax+=($tax);
                if ($bank_details->place_of_supply==1){
                    $totalcgst+=((($tax)/2)/100)*($data->quantity*$unit_price);
                    $totalsgst+=((($tax)/2)/100)*($data->quantity*$unit_price);
                }
                else if ($bank_details->place_of_supply==2){
                    $totaligst+= ((($tax))/100)*($data->quantity*$unit_price);
                }
                else{
                    $amount +=(($tax)/100)* ($data->quantity*$unit_price);
                }
            @endphp
            @endforeach        
        </tbody>
        </table>
        <table cellpadding="0" cellspacing="0">
        <tr>
            <td></td>
        </tr>
        </table>
        <table>
            <tr>
                <td width="140"></td>
                <td width="142"></td>
                <td width="65"><strong>Total Quantity</strong></td>
                <td width="80" style="text-align:center;"><strong>{{$totalqnty}}</strong></td>
            </tr>
        </table>
        <hr>
        <table>
            <tr>
                <td width="140"></td>
                <td width="140"></td>
                <td width="50"><strong>Sub-Total</strong></td>
                <td width="70" style="text-align:right;"><strong>INR {{number_format((float)$subtotal, 2, '.', '')}}</strong></td>
            </tr>
        </table>
        <hr>
        <table>
            @if($bank_details->place_of_supply==1)
                <tr>
                    <td width="140"></td>
                    <td width="140"></td>
                    <td width="40"><strong>CGST</strong></td>
                    <td width="80" style="text-align:right;"><strong>INR {{number_format((float)$totalcgst, 2, '.', '')}}</strong></td>
                </tr>
                <tr>
                    <td width="140"></td>
                    <td width="140"></td>
                    <td width="40"><strong>SGST</strong></td>
                    <td width="80" style="text-align:right;"><strong>INR {{number_format((float)$totalsgst, 2, '.', '')}}</strong></td>
                </tr>
            @elseif($bank_details->place_of_supply==2)
                <tr>
                    <td width="140"></td>
                    <td width="140"></td>
                    <td width="40"><strong>IGST</strong></td>
                    <td width="80" style="text-align:right;"><strong>INR {{number_format((float)$totaligst, 2, '.', '')}}</strong></td>
                </tr>
            @else
                <tr>
                    <td width="140"></td>
                    <td width="140"></td>
                    <td width="40"><strong>Taxes</strong></td>
                    <td width="80" style="text-align:right;"><strong>INR {{number_format((float)$amount, 2, '.', '')}}</strong></td>
                </tr>
            @endif
        </table>
        <hr>
        <table>
            <tr>
                <td width="140"></td><td width="140"></td><td width="45"><strong>Bill Amount</strong></td>
                <td width="75" style="text-align:right;"><strong>INR {{number_format((float)$billamnt, 2, '.', '')}}</strong></td>
            </tr>
            <tr>
                <td width="90"></td><td width="90"></td><td width="220" style="text-align:right;">{{$amtText}}</td>
            </tr>
            
        </table>
        <hr>
        <table cellpadding="0" cellspacing="0">
        <tr>
            <td></td>
        </tr>
        </table>
        <table>
            <tr>
                <td>Payment Terms</td><td>{{$bank_details->payment_terms}}</td><td></td><td></td>
            </tr>
            <tr>
                <td>Freight Type</td><td>{{$bank_details->freight_terms}}</td><td></td><td></td>
            </tr>
            <tr>
                <td><strong>BANK DETAILS: </strong></td>
            </tr>
            <tr>
                <td>BANK</td><td>{{$company_details->bank}}</td>
            </tr>
            <tr>
                <td>A/C No</td><td>{{$company_details->account_no}}</td>
            </tr>
            <tr>
                <td>A/C NAME</td><td>{{$company_details->account_holder}}</td>
            </tr>
            <tr>
                <td>IFSC CODE</td><td>{{$company_details->ifsc_code}}</td>
            </tr>
        </table>
    </body>
</html>