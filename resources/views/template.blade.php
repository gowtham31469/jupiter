<!DOCTYPE html>
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

        .thead-design {
            text-align: center !important;
        }

        .tdheight {
            height: 0px !important;
        }
    </style>
</head>

<body>
    <table cellpadding="0" cellspacing="0">
        <tr class="information">
            <td>
                <table cellpadding="1" cellspacing="0">
                    <tr>
                        <td width="170"><span> To</span></td>
                        <td width="160" style="font-size: 6px; border-left:1px solid black;border-right:1px solid black;"><span> Ship To</span></td>
                        <td width="108" style="border-bottom:1px solid black;" align="center" rowspan="2"><span style="line-height: -15px; font-size: 10px;"><strong>TAX INVOICE </strong></span></td>
                    </tr>
                    <tr>
                        <td width="170" style="font-size: 9px; "><span><strong>{{$inv_details[0]['company_name']}}</strong></span></td>
                        <td width="160" style="font-size: 9px; border-left:1px solid black;border-right:1px solid black;"><span><strong>{{$inv_details[0]['company_name']}}</strong></span></td>
                    </tr>
                    <tr>
                        <td width="170">
                            <p>{{$bill_to->address}},{{$bill_to->pincode}}</p>
                        </td>
                        <td width="160" style="border-left:1px solid black">
                            <p>{{$ship_to->address}},{{$ship_to->pincode}}</p>
                        </td>
                        <td width="40" style="border-left:1px solid black"><span> Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</span>
                            <p> Invoice No&nbsp;:</p>
                        </td>
                        <td width="68"><span> {{$inv_details[0]['inv_date']}}</span>
                            <p> {{$inv_details[0]['invoice_id']}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="border-left:1px solid black"></td>
                        <td style="border-left:1px solid black;"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="170" style="line-height: 1px;"><span>GSTIN : {{$inv_details[0]['gstin_number']}}</span></td>
                        <td width="160" style="line-height: 1px; border-left:1px solid black;"><span>GSTIN : {{$inv_details[0]['gstin_number']}}</span></td>
                        <td width="40" style="line-height: 1px;  border-left:1px solid black;"><br><span> Disp thru&nbsp;&nbsp;&nbsp; :</span></td>
                        <td width="68" style="line-height: 1px; "><span> {{$inv_details[0]['delvry_thru']}}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table cellpadding="1" cellspacing="0" class="thead-design">
                    <thead>
                        <tr>
                            <th style="border-bottom:1px solid black;text-align:left;border-top:1px solid black;" width="25"><strong>SNo</strong></th>
                            <th style="border-bottom:1px solid black;border-top:1px solid black;" width="60"><strong>PO No</strong></th>
                            <th style="border-bottom:1px solid black;border-top:1px solid black;" width="60"><strong>Style</strong></th>
                            <th style="border-top:1px solid black;border-bottom:1px solid black;" width="60"><strong>Buyer PO</strong></th>
                            <th style="border-bottom:1px solid black;border-top:1px solid black;" width="80"><strong>Reference</strong></th>
                            <th style="border-bottom:1px solid black;border-top:1px solid black;" width="40"><strong>Size</strong></th>
                            <th style="border-bottom:1px solid black;border-top:1px solid black;" width="30"><strong>Quantity</strong></th>
                            <th style="border-bottom:1px solid black;border-top:1px solid black;" width="40"><strong>Rate(INR)</strong></th>
                            <th style="border-bottom:1px solid black;border-top:1px solid black;" width="43"><strong>Amount(INR)</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 0;
                            $size = '-';
                            $itemName = "";
                            $ocn = "";
                            $hsnCode = "";
                            $orchColorCode = "";
                        @endphp

                        @foreach($inv_details as $data)
                            @if($itemName == $data['item_name'] && $hsnCode == $data['hsn_code'] && $ocn == $data['ocn_number'] && $orchColorCode == $data['color_code'])
                                <tr>
                                    <td style="text-align:left;" width="25">{{++$i}}</td>
                                    <td width="60">{{$data['po_no']}}</td>
                                    <td width="60">{{$data['style_no']}}</td>
                                    <td width="60">{{$data['buyer_po_number']}}</td>
                                    <td width="80">{{$data['reference_name']}}</td>
                                    @php
                                        $size = $data['size']=="null"?'-':$data['size'];
                                    @endphp
                                    <td width="50">{{$size}}</td>
                                    <td width="30">{{$data['delvry_qnty']}}</td>
                                    <td width="30">{{$data['rate']}}</td>
                                    <td width="43" style="text-align:right;">{{number_format((float)$data['delvry_qnty'] * $data['rate'], 2, '.', '') }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td width="437" colspan="9" class="tdheight"></td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="text-align:left;">
                                        <strong >
                                            <span>ITEM : </span>
                                            <span>{{$data['item_name']}},</span>
                                        </strong>
                                        <strong>
                                            <span> HSNCode :</span>
                                            <span> {{$data['hsn_code']}},</span>
                                        </strong>
                                        <strong>
                                            <span> OCN :</span>
                                            <span> {{$data['ocn_number']}}</span>
                                        </strong>

                                        @if (strtolower($data['buyer_name']) === 'orchestra')
                                            <strong>
                                                <span>, Color :</span>
                                                <span> {{$data['color_code']}}</span>
                                            </strong>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;" width="25">{{++$i}}</td>
                                    <td width="60">{{$data['po_no']}}</td>
                                    <td width="60">{{$data['style_no']}}</td>
                                    <td width="60">{{$data['buyer_po_number']}}</td>
                                    <td width="80">{{$data['reference_name']}}</td>
                                    @php
                                        $size = $data['size']=="null"?'-':$data['size'];
                                    @endphp
                                    <td width="50">{{$size}}</td>
                                    <td width="30">{{$data['delvry_qnty']}}</td>
                                    <td width="30">{{$data['rate']}}</td>
                                    <td width="43" style="text-align:right;">{{number_format((float)$data['delvry_qnty'] * $data['rate'], 2, '.', '') }}</td>
                                </tr>
                            @endif

                            @php
                                $itemName = $data['item_name'];
                                $hsnCode = $data['hsn_code']=="null"?'-':$data['hsn_code'];
                                $ocn = $data['ocn_number']=="null"?'-':$data['ocn_number'];
                                $orchColorCode = $data['color_code']=="null"?'-':$data['color_code'];
                            @endphp

                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
        
    </table>
</body>

</html>