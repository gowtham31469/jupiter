<html>
<head>
</head>
<style>

td {
    height: 25px;
}

th {
    height: 25px;
    text-align: center !important;
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
}
</style>
 <body>
    <table cellspacing="0" cellpadding="2" style="table-layout: fixed;width: 100%;border-collapse: collapse;" nobr="true">
            <tr nobr="true">
                <td>
                    <label for="customer">CUST NAME :</label>
                    <span id="customer" >{{ ucwords($customer->customer_name) }}</span>	
                </td>
                <td>
                    <label for="style_no">STYLENUM :</label>
                    <span id="style_no">{{ $style_no }}</span>
                </td>
                <td>
                    <label for="order_type">ORDER TYPE :</label>
                    <span id="order_type">{{ $order_type }}</span>
                </td>
            </tr>
            <tr nobr="true">
                <td>
                    <label for="buyer">BUYER :</label>
                    <span id="buyer">{{ $buyer->buyer_name }}</span>	
                </td>
                <td>
                    <label for="reference">REFERENCE :</label>
                    <span id="reference">{{ $reference }}</span>	
                </td>
                <td>
                    <label for="disp_mode">DISP MODE :</label>
                    <span id="disp_mode">{{ ucwords($disp_mode) }}</span>
                </td>
            </tr>
    </table>

    <table>
        <tr>
            <td rowspan="1"></td>
        </tr>
    </table>
    <hr>
    <table>
        <tr>
            <td rowspan="1"></td>
        </tr>
    </table>
                
    <table cellspacing="0" cellpadding="2" style="table-layout: fixed;width: 100%;border-collapse: collapse;" nobr="true">
        <tr nobr="true">
            <td>
                <label for="order_date">ORDER DT :</label>
                <span id="order_date">{{ $order_date }}</span>
            </td>
            <td>
                <label for="length">LENGTH :</label>
                <span id="length">{{ $length }}</span>	
            </td>
            <td>
                <label for="repeat_gap">REPEAT GAP :</label>
                <span id="repeat_gap">{{ $repeat_gap }}</span>
            </td>
            <td>
                <label for="machine">MACHINE :</label>
                <span id="machine">{{ $machine }}</span>
            </td>
        </tr>
        <tr nobr="true">
            <td>
                <label for="cus_del_date">CUS D.DT :</label>
                <span id="cus_del_date">{{ $cus_del_date }}</span>
            </td>
            <td>
                <label for="width">WIDTH :</label>
                <span id="width">{{ $width }}</span>
            </td>
            <td>
                <label for="roll_width">ROLL WIDTH :</label>
                <span id="roll_width">{{ $roll_width }}</span>
            </td>
            <td>
                <label for="film_no">FILM NO :</label>
                <span id="film_no">{{ $film_no }}</span>
            </td>
        </tr>
        <tr nobr="true">
            <td>
                <label for="act_del_date">ACT D.DT :</label>
                <span id="act_del_date">{{ $act_del_date }}</span>
            </td>
            <td>
                <label for="length_side">LENGTH SIDE :</label>
                <span id="length_side">{{ $length_side }}</span>
            </td>
            <td>
                <label for="excess">EXCESS % :</label>
                <span id="excess">{{ $excess }}</span>
            </td>
            <td>
                <label for="teeth_no">TEETH NO :</label>
                <span id="teeth_no">{{ $teeth_no }}</span>
            </td>
        </tr>
        <tr nobr="true">
            <td>
                <label for="inv_no">INV NO :</label>
                <span id="inv_no">{{ $inv_no }}</span>
            </td>
            <td>
                <label for="no_sticker">NO OF STICKERS :</label>
                <span id="no_sticker">{{ $no_sticker }}</span>
            </td>
            <td>
                <label for="finish_type">FINISH TYPE :</label>
                <span id="finish_type">{{ $finish_type }}</span>
            </td>
            <td></td>
        </tr>
        <tr nobr="true">
            <td>
                <label for="print_color">PRINT COLORS :</label>
                <span id="print_color">{{ ucwords($print_color) }}</span>
            </td>
            <td></td>
        </tr>
    </table>

    <table>
        <tr>
            <td rowspan="1"></td>
        </tr>
    </table>
    <hr>
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
                                <th colspan="4" style="background-color:#eee;"><strong>Total</strong></th>
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
                        <table cellspacing="0" cellpadding="2" border="0.2"  nobr="true" class="single-list-table">
                        @php $ref_name = explode('_', $table); @endphp
                            <tbody id="single_list_table_body">
                                <tr nobr="true">
                                    <th><strong>ID.NO</strong></th>
                                    @foreach ($table_data['erp_id'] as $key => $value)
                                        <td colspan="2" >{{ $value }}</td>
                                    @endforeach
                                    <td colspan="4" style="background-color:#eee;padding-top:20px;" rowspan="3" ><strong >REFNUM &nbsp;: {{ $ref_name[0].' '.$ref_name[1] }}</strong></td>
                                </tr>
                                <tr nobr="true">
                                    <th><strong>B.PO.NO</strong></th>
                                    @foreach ($table_data['buyer_po'] as $key => $value)
                                        <td colspan="2" >{{ $value }}</td>
                                    @endforeach
                                </tr>
                                <tr nobr="true">
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
                                <tr nobr="true">
                                @foreach ($table_data['size'] as $size => $quantities)
                                    <th class="total-text" style="background-color:#eee;"><strong>TOTAL</strong></th>
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