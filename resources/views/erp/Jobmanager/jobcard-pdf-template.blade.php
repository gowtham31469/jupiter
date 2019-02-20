<html>
<head>
</head>
<style>


td {
    height: 12px;
    text-align: left !important;
}

th {
    height: 12px;
    text-align: left !important;
    font-weight: bold;
    /*background-color: #D3D3D3;*/
    background-color: #DCDCDC;
}
.single-th-row th {
    background-color: #FFFFFF;
}
.single-section td {
    /*background-color: #D3D3D3;*/
    background-color:#DCDCDC;
}

.single-total td {
    height: 12px !important;
}

.space-td td {
    height: 2px !important;
}

.ratio-list-table tr,
.ratio-list-table th,
.ratio-list-table td,
.single-list-table tr,
.single-list-table th,
.single-list-table td {
    border-right-width: 1px !important;
    border-bottom-width: 1px !important;
    border-left-width: 1px !important;
    border-top-width: 1px !important;
    border-left: 1px solid #DDD !important;
    border-top: 1px solid #DDD !important;
    border-right: 1px solid #DDD !important;
    border-bottom: 1px solid #DDD !important;
    height: 30px !important;
    line-height: 30px !important;
    text-align: center !important;
    vertical-align: middle !important;
}
</style>
 <body>

    <table cellspacing="0" cellpadding="5" style="table-layout: fixed;width: 100%;border-collapse: collapse;" nobr="true">
        <tbody>
<!--             <tr >
                <td colspan="8"  class="space-td" style="border-left:1px solid black;border-right:1px solid black;"></td>
            </tr> -->
            <tr nobr="true">
                <th style="border-left:1px solid black;">CustName</th>
                <td  id="customer">{{ ucwords($customer->customer_name) }}</td>
                <th>StyleNum</th>
                <td id="style_no">{{ $style_no }}</td>
                <th width="10%">OrderType</th>
                <td width="15%" id="order_type">{{ $order_type }}</td>
                <th width="10%">Inv No</th>
                <td width="15%" style="border-right:1px solid black;"id="inv_no">{{ $inv_no }}</td>
            </tr>
            <tr nobr="true">
                <th style="border-left:1px solid black;border-bottom:1px solid white;">Buyer</th>
                <td  style="border-bottom:1px solid white;"id="buyer">{{ $buyer->buyer_name }}</td>
                <th style="border-bottom:1px solid white;">Reference</th>
                <td style="border-bottom:1px solid white;" id="reference">{{ $reference }}</td>
                <th width="10%" style="border-bottom:1px solid white;">DispMode</th>
                <td width="15%" style="border-bottom:1px solid white;" id="disp_mode">{{ ucwords($disp_mode) }}</td>
                <th width="10%" style="border-bottom:1px solid white;">Inv Dt</th>
                <td width="15%" style="border-right:1px solid black;" id="invoice_date">{{ $invoice_date }}</td>

            </tr>
        </tbody>
    </table>

    <!-- <table>
        <tr>
            <td style="border-right:1px solid black;border-left:1px solid black;" rowspan="1"></td>
        </tr>
    </table> -->
   
    <table cellspacing="0" cellpadding="3" style="table-layout: fixed;width: 100%;border-collapse: collapse;" nobr="true">
            <tr nobr="true">
                <th  style="border-left:1px solid black;">OrderDt</th>
                <td  id="order_date">{{ $order_date }}</td>
                <th >Length MM</th>
                <td  id="length">{{ $length }}</td>
                <th width="10%">RepeatGap</th>
                <td width="15%" id="repeat_gap">{{ $repeat_gap }}</td>
                <th width="10%">Machine</th>
                <td width="15%" style="border-right:1px solid black;" id="machine">{{ $machine }}</td>
            </tr>
            <tr nobr="true">
                <th  style="border-left:1px solid black;">CusDelDt</th>
                <td  id="cus_del_date">{{ $cus_del_date }}</td>
                <th >Width MM</th>
                <td  id="width">{{ $width }}</td>
                <th width="10%">RollWidth</th>
                <td width="15%" id="roll_width">{{ $roll_width }}</td>
                <th width="10%">FilmNum</th>
                <td width="15%" style="border-right:1px solid black;" id="film_no">{{ $film_no }}</td>
            </tr>
            <tr nobr="true">
                <th  style="border-left:1px solid black;">PrintColors</th>
                <td  id="print_color">{{ ucwords($print_color) }}</td>
                <th >LengthSideDim</th>
                <td  id="length_side">{{ $length_side }}</td>
                <th width="10%">Excess %</th>
                <td width="15%" id="excess">{{ $excess }}</td>
                <th width="10%">TeethNum</th>
                <td width="15%" style="border-right:1px solid black;" id="teeth_no">{{ $teeth_no }}</td>
            </tr>
            <tr nobr="true">
                <th  style="border-left:1px solid black;border-bottom:1px solid black;"></th>
                <td  style="border-bottom:1px solid black;" id="print_color"></td>
                <th  style="border-bottom:1px solid black;">NoOfSticker</th>
                <td  style="border-bottom:1px solid black;" id="no_sticker">{{ $no_sticker }}</td>
                <th width="10%" style="border-bottom:1px solid black;">Material</th>
                <td width="15%" style="border-bottom:1px solid black;" id="material">{{ $material }}</td>
                <th width="10%" style="border-bottom:1px solid black;">FinishType</th>
                <td width="15%" style="border-right:1px solid black;border-bottom:1px solid black;" id="finish_type">{{ $finish_type }}</td>
            </tr>
    </table>

    <table>
        <tr>
            <td rowspan="1"></td>
        </tr>
    </table>

    @foreach ($final_json_obj as $template_type => $template_type_data)
            @if ($template_type == 'ratio')
                @if (!empty($template_type_data))
                    @foreach ($template_type_data as $table => $table_data)
                    <table cellspacing="0" cellpadding="2" border="0.2"  nobr="true" class="ratio-list-table">
                        <thead style="width:fixed;">
                            <tr nobr="true">
                                <th><strong>REF.NO</strong></th>
                                <th><strong>PO.NO</strong></th>
                                <th><strong>ID.NO</strong></th>
                                <th><strong>OCN</strong></th>
                                <th><strong>QNTY</strong></th>
                                <th><strong>P.QNTY</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $ratio_total = 0; @endphp
                            @foreach ($table_data as $key => $value)
                                <tr nobr="true">
                                <td>{{ $value['reference_name'] }}</td>
                                <td>{{ $value['buyer_po_number'] }}</td>
                                <td>{{ $value['erp_id'] }}</td>
                                <td>{{ $value['ocn_number'] }}</td>
                                <td>{{ $value['order_quantity'] }}</td>
                                <td></td>
                                @php $ratio_total = $ratio_total+$value['order_quantity']; @endphp
                            </tr>
                            @endforeach
                            <tr nobr="true">
                                <th colspan="4" style="background-color:#fff;"><strong>Total</strong></th>
                                <td><strong>{{ $ratio_total }}</strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <table>
                        <tr>
                            <td rowspan="1"></td>
                        </tr>
                    </table>
                @endforeach
                @endif
            @else
                @if (!empty($template_type_data))
                    @foreach ($template_type_data as $table => $table_data)
                        <table cellspacing="0" cellpadding="1" border="0.2"  nobr="true" class="single-list-table" align="center">
                        @php $ref_name = explode('_', $table); @endphp
                            <tbody id="single_list_table_body">
                                <tr nobr="true" class="single-section">
                                    <th nobr="true"><strong>ID.NO</strong></th>
                                    @foreach ($table_data['erp_id'] as $key => $value)
                                        <td colspan="2" nobr="true">{{ $value }}</td>
                                    @endforeach
                                    <td colspan="4" style="line-height:90px;" rowspan="3" nobr="true"><strong >REFNUM &nbsp;: {{ $ref_name[0].' '.$ref_name[1] }}</strong></td>
                                </tr>
                                <tr class="single-section" nobr="true">
                                    <th nobr="true"><strong>B.PO.NO</strong></th>
                                    @foreach ($table_data['buyer_po'] as $key => $value)
                                        <td colspan="2" nobr="true">{{ $value }}</td>
                                    @endforeach
                                </tr>
                                <tr class="single-section" nobr="true">
                                    <th nobr="true"><strong>OCN</strong></th>
                                    @foreach ($table_data['ocn'] as $key => $value)
                                        <td colspan="2" nobr="true">{{ $value }}</td>
                                    @endforeach
                                </tr>
                                <tr class="single-th-row" nobr="true">
                                    <!-- @php print_r(count($table_data['erp_id'])); @endphp -->
                                    <th nobr="true"><strong>SIZE</strong></th>
                                    @foreach ($table_data['erp_id'] as $key => $qty)
                                        <th nobr="true"><strong>O.QNT</strong></th>
                                        <th nobr="true"><strong>P.QNT</strong></th>
                                    @endforeach
                                    <th nobr="true"><strong>TOTAL</strong></th>
                                    <th nobr="true"><strong>EXCESS</strong></th>
                                    <th nobr="true"><strong>MTRS</strong></th>
                                    <th nobr="true"><strong>STOCK</strong></th>
                                </tr>
                                @php 
                                    $single_final_total  = 0; 
                                    $single_excess_total = 0;
                                    $sumArray = array();
                                @endphp
                                @foreach ($table_data['size'] as $size => $quantities)
                                    <tr nobr="true">
                                        <td class="single-size-row" nobr="true"> {{ $size }}</td>
                                        @foreach ($quantities as $key => $qty)
                                            <td nobr="true">{{ $qty }}</td>
                                            <td nobr="true"></td>
                                            @php 
                                                if (array_key_exists($key,$sumArray))
                                                {
                                                    $sumArray[$key] = $qty + $sumArray[$key];
                                                }
                                                else
                                                {
                                                    $sumArray[$key] = 0;
                                                    $sumArray[$key] = $qty + $sumArray[$key];
                                                }
                                            @endphp
                                        @endforeach
                                        <td nobr="true"><strong>{{ $table_data['total'][$size] }}</strong></td>
                                        <td nobr="true" class="total-excess">{{ $table_data['excess'][$size] }}</td>
                                        <td nobr="true"></td>
                                        <td nobr="true"></td>
                                        @php 
                                            $single_final_total  = $single_final_total+$table_data['total'][$size];
                                            $single_excess_total = $single_excess_total+$table_data['excess'][$size];
                                        @endphp
                                    </tr>
                                @endforeach 
                                <tr nobr="true" class="single-total">
                                <!-- @foreach ($table_data['size'] as $size => $quantities)
                                    <td><strong>TOTAL</strong></td>
                                    @foreach ($quantities as $key => $qty)
                                        <td><strong>{{ $sumArray[$key] }}</strong></td>
                                        <td></td>
                                    @endforeach
                                    <td><strong>{{ $single_final_total }}</strong></td>
                                    <td><strong>{{ $single_excess_total }}</strong></td>
                                    <td></td>
                                    <td></td>
                                @endforeach -->

                                    <td nobr="true"><strong>TOTAL</strong></td>
                                    @foreach ($sumArray as $key => $qty)
                                            <td><strong>{{ $qty }}</strong></td>
                                            <td></td>
                                    @endforeach
                                    <td nobr="true"><strong>{{ $single_final_total }}</strong></td>
                                    <td nobr="true"><strong>{{ $single_excess_total }}</strong></td>
                                    <td nobr="true"></td>
                                    <td nobr="true"></td>
                                </tr>
                            </tbody>
                        </table>
                        <table>
                            <tr nobr="true">
                                <td nobr="true" rowspan="1"></td>
                            </tr>
                        </table>
                @endforeach
                @endif  
            @endif
    @endforeach

    @if (!empty(implode(',',$single_ref)))
        <table cellspacing="0" cellpadding="2" style="table-layout: fixed;border-collapse: collapse;" nobr="true">
            @foreach ($single_ref as $key => $ref)
                <tr nobr="true">
                    <td>
                        <label>ACT EXCESS % (<strong>{{ $ref }}</strong>):</label>
                        <span>{{ $act_excess[$key] }}</span> 
                    </td>
                    <td>
                        <label>STKRS MTRS (<strong>{{ $ref }}</strong>):</label>                                 <span>{{ $sticker_mtr[$key] }}</span> 
                    </td>
                    <td>
                        <label>SETTINGS (<strong>{{ $ref }}</strong>) :</label>                                            <span>{{ $settings[$key] }}</span> 
                    </td>
                    <td>
                        <label>TOTAL MTRS (<strong>{{ $ref }}</strong>):</label>                                         <span>{{ $total_mtrs[$key] }}</span> 
                    </td>
                </tr>
            @endforeach
        </table>
    @else
    <table cellpadding="0" cellspacing="2"></table>
    @endif
 </body>
</html>