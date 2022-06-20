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
    background-color: #DCDCDC;
}
.single-th-row th {
    background-color: #FFFFFF;
}
.single-section td {
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
            <tr nobr="true">
                <th style="border-left:1px solid #6f7072;">CustName</th>
                <td  id="customer">{{ ucwords($customer->customer_name) }}</td>
                <th>Reference</th>
                <td id="reference">{{ $reference }}</td>
                <th width="10%">OrderType</th>
                <td width="15%" id="order_type">{{ $order_type }}</td>
                <th width="10%">Machine</th>
                <td width="15%" style="border-right:1px solid #6f7072;" id="machine">{{ $machine }}</td>
            </tr>
            <tr nobr="true">
                <th style="border-left:1px solid #6f7072;">Buyer</th>
                <td  id="buyer">{{ ucwords($buyer) }}</td>
                <th>Length MM</th>
                <td  id="length">{{ $length }}</td>
                <th width="10%">DispMode</th>
                <td width="15%"  id="disp_mode">{{ ucwords($disp_mode) }}</td>
                <th width="10%">FilmNum</th>
                <td width="15%" style="border-right:1px solid #6f7072;" id="film_no">{{ $film_no }}</td>
            </tr>
        </tbody>
    </table>
   
    <table cellspacing="0" cellpadding="3" style="table-layout: fixed;width: 100%;border-collapse: collapse;" nobr="true">
            <tr nobr="true">
                <th  style="border-left:1px solid #6f7072;">OrderDt</th>
                <td  id="order_date">{{ $order_date }}</td>
                <th>Width MM</th>
                <td  id="length">{{ $width }}</td>
                <th width="10%">RollWidth</th>
                <td width="15%" id="roll_width">{{ $roll_width }}</td>
                <th width="10%">TeethNum</th>
                <td width="15%" style="border-right:1px solid #6f7072;" id="teeth_no">{{ $teeth_no }}</td>
            </tr>
            <tr nobr="true">
                <th  style="border-left:1px solid #6f7072;">AW ApprDt</th>
                <td  id="aw_app_date">{{ $aw_app_date }}</td>
                <th>LengthSideDim</th>
                <td  id="length_side">{{ $length_side }}</td>
                <th width="10%">Excess %</th>
                <td width="15%" id="excess">{{ $excess }}</td>
                <th width="10%">FinishType</th>
                <td width="15%" style="border-right:1px solid #6f7072;" id="finish_type">{{ $finish_type }}</td>
            </tr>
            <tr nobr="true">
                <th  style="border-left:1px solid #6f7072;">CusDelDt</th>
                <td  id="cus_del_date">{{ $cus_del_date }}</td>
                <th>NoOfRepeats</th>
                <td  id="no_repeats">{{ $no_repeats }}</td>
                <th width="10%">Material</th>
                <td width="15%" id="material">{{ $material }}</td>
                <th width="10%"></th>
                <td width="15%" style="border-right:1px solid #6f7072;"></td>
            </tr>
            <tr nobr="true">
                <th  style="border-left:1px solid #6f7072;">ActDelDt</th>
                <td  id="act_del_date">{{ $act_del_date }}</td>
                <th>RepeatGap</th>
                <td id="repeat_gap">{{ $repeat_gap }}</td>
                <th width="10%"></th>
                <td width="15%"></td>
                <th width="10%"></th>
                <td width="15%" style="border-right:1px solid #6f7072;"></td>
            </tr>
            <tr nobr="true">
                <th  style="border-left:1px solid #6f7072;border-bottom:1px solid #6f7072;">PrintColors</th>
                <td  id="print_color" style="border-bottom:1px solid #6f7072;">{{ ucwords($print_color) }}</td>
                <th style="border-bottom:1px solid #6f7072;"></th>
                <td style="border-bottom:1px solid #6f7072;"></td>
                <th width="10%" style="border-bottom:1px solid #6f7072;"></th>
                <td width="15%" style="border-bottom:1px solid #6f7072;"></td>
                <th width="10%" style="border-bottom:1px solid #6f7072;"></th>
                <td width="15%" style="border-bottom:1px solid #6f7072;border-right:1px solid #6f7072;"></td>
            </tr>
    </table>

    <table>
        <tr>
            <td rowspan="1"></td>
        </tr>
    </table>

    @foreach ($final_json_obj as $template_type => $template_type_data)
            @if ($template_type == 'single')
                @if (!empty($template_type_data))
                    @foreach ($template_type_data as $table => $table_data)
                    <table cellspacing="0" cellpadding="1" border="0.2"  nobr="true" class="single-list-table" align="center">
                        <tbody id="single_list_table_body">
                            <tr nobr="true" class="single-section">
                                <th nobr="true"><strong>ID.NO</strong></th>
                                @foreach ($table_data['erp_id'] as $key => $value)
                                    <td colspan="2" nobr="true">{{ $value }}</td>
                                @endforeach
                                <td colspan="4" style="line-height:90px;" rowspan="6" nobr="true"></td>
                            </tr>
                            <tr class="single-section" nobr="true">
                                <th nobr="true"><strong>STYLE/UVC</strong></th>
                                @foreach ($table_data['style'] as $key => $value)
                                    <td colspan="2" nobr="true">{{ $value }}</td>
                                @endforeach
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
                            <tr class="single-section" nobr="true">
                                <th nobr="true"><strong>COLOR</strong></th>
                                @foreach ($table_data['color'] as $key => $value)
                                    <td colspan="2" nobr="true">{{ $value }}</td>
                                @endforeach
                            </tr>
                            <tr class="single-section" nobr="true">
                                <th nobr="true"><strong>COMPOSITION</strong></th>
                                @foreach ($table_data['compositions'] as $key => $value)
                                    <td colspan="2" nobr="true">{{ $value }}</td>
                                @endforeach
                            </tr>
                            <tr class="single-th-row" nobr="true">
                                <th nobr="true"><strong>SIZE/PRICE</strong></th>
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
    
 </body>
</html>