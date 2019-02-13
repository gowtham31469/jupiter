<html>
<head>
</head>
<style>

td {
    height: 25px;
    text-align: left !important;
}

th {
    height: 25px;
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
    line-height: 100%;
    vertical-align: middle !important;
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
    height: 35px;
    text-align: center !important;
    vertical-align: middle !important;
}
</style>
 <body>
    <table cellspacing="1" cellpadding="2" style="table-layout: fixed;width: 100%;border-collapse: collapse;" nobr="true">
        <tbody>
            <tr nobr="true">
                <th>CustName</th>
                <td id="customer">{{ ucwords($customer->customer_name) }}</td>
                <th>StyleNum</th>
                <td id="style_no">{{ $style_no }}</td>
                <th>OrderType</th>
                <td id="order_type">{{ $order_type }}</td>
                <th>Inv No</th>
                <td id="inv_no">{{ $inv_no }}</td>
            </tr>
            <tr nobr="true">
                <th>Buyer</th>
                <td id="buyer">{{ $buyer->buyer_name }}</td>
                <th>Reference</th>
                <td id="reference">{{ $reference }}</td>
                <th>DispMode</th>
                <td id="disp_mode">{{ ucwords($disp_mode) }}</td>
                <th>Inv Dt</th>
                <td id="inv_no">{{ $inv_no }}</td>
            </tr>
        </tbody>
    </table>  
   
    <table cellspacing="1" cellpadding="2" style="table-layout: fixed;width: 100%;border-collapse: collapse;" nobr="true">
            <tr nobr="true">
                <th>OrderDt</th>
                <td id="order_date">{{ $order_date }}</td>
                <th>Length MM</th>
                <td id="length">{{ $length }}</td>
                <th>RepeatGap</th>
                <td id="repeat_gap">{{ $repeat_gap }}</td>
                <th>Machine</th>
                <td id="machine">{{ $machine }}</td>
            </tr>
            <tr nobr="true">
                <th>CusDelDt</th>
                <td id="cus_del_date">{{ $cus_del_date }}</td>
                <th>Width MM</th>
                <td id="width">{{ $width }}</td>
                <th>RollWidth</th>
                <td id="roll_width">{{ $roll_width }}</td>
                <th>FilmNum</th>
                <td id="film_no">{{ $film_no }}</td>
            </tr>
            <tr nobr="true">
                <th>PrintColors</th>
                <td id="print_color">{{ ucwords($print_color) }}</td>
                <th>LengthSideDim</th>
                <td id="length_side">{{ $length_side }}</td>
                <th>Excess %</th>
                <td id="excess">{{ $excess }}</td>
                <th>TeethNum</th>
                <td id="teeth_no">{{ $teeth_no }}</td>
            </tr>
            <tr nobr="true">
                <th></th>
                <td id="print_color"></td>
                <th>NoOfSticker</th>
                <td id="no_sticker">{{ $no_sticker }}</td>
                <th>Material</th>
                <td id="material">{{ $material }}</td>
                <th>FinishType</th>
                <td id="finish_type">{{ $finish_type }}</td>
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
                        <table cellspacing="0" cellpadding="0" border="0.2"  nobr="true" class="single-list-table" align="center">
                        @php $ref_name = explode('_', $table); @endphp
                            <tbody id="single_list_table_body">
                                <tr nobr="true" class="single-section">
                                    <th><strong>ID.NO</strong></th>
                                    @foreach ($table_data['erp_id'] as $key => $value)
                                        <td colspan="2" >{{ $value }}</td>
                                    @endforeach
                                    <td colspan="4" style="line-height:650%;" rowspan="3" ><strong >REFNUM &nbsp;: {{ $ref_name[0].' '.$ref_name[1] }}</strong></td>
                                </tr>
                                <tr class="single-section" nobr="true">
                                    <th><strong>B.PO.NO</strong></th>
                                    @foreach ($table_data['buyer_po'] as $key => $value)
                                        <td colspan="2" >{{ $value }}</td>
                                    @endforeach
                                </tr>
                                <tr class="single-section" nobr="true">
                                    <th><strong>OCN</strong></th>
                                    @foreach ($table_data['ocn'] as $key => $value)
                                        <td colspan="2" >{{ $value }}</td>
                                    @endforeach
                                </tr>
                                <tr class="single-th-row" nobr="true">
                                @foreach ($table_data['size'] as $size => $quantities)
                                    <th><strong>SIZE /PRICE</strong></th>
                                    @foreach ($quantities as $key => $qty)
                                        <th><strong>O.QNT</strong></th>
                                        <th><strong>P.QNT</strong></th>
                                    @endforeach
                                    <th><strong>TOTAL</strong></th>
                                    <th><strong>EXCESS</strong></th>
                                    <th><strong>MTRS</strong></th>
                                    <th><strong>STOCK</strong></th>
                                @endforeach
                                </tr>
                                @php 
                                    $single_final_total  = 0; 
                                    $single_excess_total = 0;
                                    $sumArray = array();
                                @endphp
                                @foreach ($table_data['size'] as $size => $quantities)
                                    <tr nobr="true">
                                        <td class="single-size-row"> {{ $size }}</td>
                                        @foreach ($quantities as $key => $qty)
                                            <td>{{ $qty }}</td>
                                            <td></td>
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
                                        <td><strong>{{ $table_data['total'][$size] }}</strong></td>
                                        <td class="total-excess">{{ $table_data['excess'][$size] }}</td>
                                        <td></td>
                                        <td></td>
                                        @php 
                                            $single_final_total  = $single_final_total+$table_data['total'][$size];
                                            $single_excess_total = $single_excess_total+$table_data['excess'][$size];
                                        @endphp
                                    </tr>
                                @endforeach 
                                <tr nobr="true" class="single-total">
                                @foreach ($table_data['size'] as $size => $quantities)
                                    <td><strong>TOTAL</strong></td>
                                    @foreach ($quantities as $key => $qty)
                                        <td><strong>{{ $sumArray[$key] }}</strong></td>
                                        <td></td>
                                    @endforeach
                                    <td><strong>{{ $single_final_total }}</strong></td>
                                    <td><strong>{{ $single_excess_total }}</strong></td>
                                    <td></td>
                                    <td></td>
                                @endforeach
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