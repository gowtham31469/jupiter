<html>
<head>
</head>
<style>

body{
    font-size: 12px;
}

.ratio-list-table,
.single-list-table{
    text-align: center !important;
}

.ratio-list-table td,
.single-list-table td {
    text-align: center !important;
}

td {
    height: 25px;
}

th {
    height: 25px;
    text-align: center !important;
}

table {
    table-layout: fixed;
    width: 100%;
    border-collapse: collapse;
}

.ratio-list-table, .single-list-table {
    padding: 10px;
    border: 1px dashed gray;
    display: table-cell;
    text-align: center;
    vertical-align: middle;
}
</style>
<body>
    <div class="container">
        <div class="row">
        <div class="col-md-12">
                <table cellspacing="3" cellpadding="3">
                    <tr >
                        <td>
                        <p><strong id="jc_company_name">Jupiter Garments Accessories</strong></p>
                        <p id="company_address">1-J-27-1&amp;3, Jupiter Avenue, Anupparpalayam, Tirupur -641632 </p>
                        </td>
                        <td style="text-align: right;">
                            <img src="images/logo.png" style="height: 62px;margin-top: 30px;"/>
                        </td>
                    </tr>
                </table>
        </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table cellspacing="3" cellpadding="3">
                    <tr>
                        <td>
                            <label for="work_order">WORKORDER :</label>
                            <span id="work_order">{{ $workorder_id }}</span>
                        </td>
                        <td style="text-align: right;">
                            <label for="today_date">DATE :</label>
                            <span id="today_date">{{ $created_date }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="article">ARTICLE :</label>
                            <span id="article">{{ $article }}</span>
                        </td>
                        <td style="text-align: right;">
                        <label for="material">MATERIAL :</label>
                        <span id="material">{{ $material }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table cellspacing="3" cellpadding="3">
                        <tr>
                            <td>
                            <label for="customer">CUST NAME :</label>
                            <span id="customer" >{{ $customer->customer_name }}</span>	
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
                        <tr>
                            <td>
                                    <label for="buyer">BUYER :</label>
                                    <span id="buyer" data-buyer-id="2">{{ $buyer->buyer_name }}</span>	
                            </td>
                            <td>
                                <label for="reference">REFERENCE :</label>
                                <span id="reference">{{ $reference }}</span>	
                            </td>
                            <td>
                                <label for="disp_mode">DISP MODE :</label>
                                <span id="disp_mode">{{ $disp_mode }}</span>
                            </td>
                        </tr>
                </table>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table cellspacing="3" cellpadding="3">
                    <tr>
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
                    <tr>
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
                    <tr>
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
                    <tr>
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
                    <tr>
                            <td>
                                <label for="print_color">PRINT COLORS :</label>
                                <span id="print_color">{{ $print_color }}</span>
                            </td>
                            <td></td>
                        </tr>
                </table>
            </div>
        </div>
        <hr>
        @foreach ($final_json_obj as $template_type => $template_type_data)
              @if ($template_type == 'ratio')
                  @if (!empty($template_type_data))
                      @foreach ($template_type_data as $table => $table_data)
                        <div class="row">
                            <div class="col-md-12 ratio-list-table-div">
                            <table class="table table-bordered table-responsive ratio-list-table" id="ratio_list_table" border="0.2">
                                <thead>
                                    <tr>
                                        <th>REF.NO</th>
                                        <th>PO.NO</th>
                                        <th>ID.NO</th>
                                        <th>OCN</th>
                                        <th>QNTY</th>
                                        <th>P.QNTY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $ratio_total = 0; ?>
                                    @foreach ($table_data as $key => $value)
                                        <tr>
                                        <td>{{ $value['reference_name'] }}</td>
                                        <td>{{ $value['buyer_po_number'] }}</td>
                                        <td>{{ $value['erp_id'] }}</td>
                                        <td>{{ $value['ocn_number'] }}</td>
                                        <td>{{ $value['order_quantity'] }}</td>
                                        <td></td>
                                        <?php $ratio_total = $ratio_total+$value['order_quantity']; ?>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <th colspan="4" style="background-color:#eee;">Total</th>
                                        <td><strong>{{ $ratio_total }}</strong></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    @endforeach
                  @endif
              @else
                   @if (!empty($template_type_data))
                      @foreach ($template_type_data as $table => $table_data)
                        <div class="row">
                            <div class="col-md-12 single-list-table-div">
                            <table class="table table-bordered table-responsive single-list-table" id="single_list_table_0" border="0.2">
                            <?php $ref_name = explode('_', $table); ?>
                                <tbody id="single_list_table_body">
                                    <tr>
                                        <th>ID.NO</th>
                                        @foreach ($table_data['erp_id'] as $key => $value)
                                            <td colspan="2" >{{ $value }}</td>
                                        @endforeach
                                        <td colspan="4" style="background-color:#eee;padding-top:20px;" rowspan="3" ><strong >REFNUM &nbsp;: {{ $ref_name[0].' '.$ref_name[1] }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>B.PO.NO</th>
                                        @foreach ($table_data['buyer_po'] as $key => $value)
                                            <td colspan="2" >{{ $value }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>OCN</th>
                                        @foreach ($table_data['ocn'] as $key => $value)
                                            <td colspan="2" >{{ $value }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                    @foreach ($table_data['size'] as $size => $quantities)
                                        <th>SIZE /PRICE</th>
                                        @foreach ($quantities as $key => $qty)
                                            <th>O.QNT</th>
                                            <th>P.QNT</th>
                                        @endforeach
                                        <th>TOTAL</th>
                                        <th>EXCESS</th>
                                        <th>MTRS</th>
                                        <th>STOCK</th>
                                    @endforeach
                                    </tr>
                                    @foreach ($table_data['size'] as $size => $quantities)
                                        <tr>
                                            <td class="single-size-row"> {{ $size }}</td>
                                            @foreach ($quantities as $key => $qty)
                                                <td>{{ $qty }}</td>
                                                <td></td>
                                            @endforeach
                                            <td class="total-quantity-class total-quantity-row-0"><strong>{{ $table_data['total'][$size] }}</strong></td>
                                            <td class="total-excess">{{ $table_data['excess'][$size] }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                    @foreach ($table_data['size'] as $size => $quantities)
                                        <th class=" total-text" style="background-color:#eee;">TOTAL</th>
                                        @foreach ($quantities as $key => $qty)
                                            <td class="total-quantity-col-0"><strong></strong></td>
                                            <td></td>
                                        @endforeach
                                        <td><strong class="total-qty-TCL_4">323</strong></td>
                                        <td class="total-excess-col-table-0 total-excess-TCL_4"><strong>168.00</strong></td>
                                        <td></td>
                                        <td></td>
                                    @endforeach
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    @endforeach
                  @endif  
              @endif
        @endforeach

        @if (!empty(implode(',',$single_ref)))
        <div class="row meter-calculation-div">
            <div class="col-md-12">
                <table cellpadding="3" cellspacing="3">
                    @foreach ($single_ref as $key => $ref)
                        <tr>
                            <td>
                                <label>ACT EXCESS % (<strong>{{ $ref }}</strong>):</label>
                                <span>{{ $act_excess[$key] }}</span> 
                            </td>
                            <td>
                                <label>MTRS FOR STICKERS (<strong>{{ $ref }}</strong>):</label>                                 <span>{{ $sticker_mtr[$key] }}</span> 
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
            </div>
        </div>
        @else
        <table cellpadding="3" cellspacing="3"></table>
        @endif
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table cellspacing="3" cellpadding="3">
                            <tr>
                                <td >
                                    <label for="compiled_by">COMPILED BY :</label>
                                    <span id="compiled_by">{{ $compiled_by }}</span> 
                                </td>
                                <td >
                                    <label for="barcode_read">BARCODE READ :</label>
                                    <span id="barcode_read">{{ $barcode_read }}</span> 
                                </td>
                                <td >
                                    <label for="operator">OPERATOR :</label>
                                    <span id="operator">{{ $operator }}</span>
                                </td>
                                <td >
                                    <label for="checker">CHECKER :</label>
                                    <span id="checker">{{ $checker }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td >
                                    <label for="author">AUTHORIZED BY :</label>
                                    <span id="author">{{ $authorized_by }}</span>
                                </td>
                                <td >
                                    <label for="axicon_verifier">AXICON VERIFIER :</label>
                                    <span id="axicon_verifier">{{ $axicon_verifier }}</span>
                                </td>
                                <td >
                                    <label for="approved_by">APPROVED BY :</label>
                                    <span id="approved_by">{{ $approved_by }}</span>
                                </td>
                                <td >
                                    <label for="packer">PACKER :</label>
                                    <span id="packer">{{ $packer }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td >
                                    <label for="approval_date">APPROVAL DT :</label>
                                    <span id="approval_date">{{ $approval_date }}</span>
                                </td>
                                <td >
                                    <label for="roll_issued_by">ROLL ISSUED BY :</label>
                                    <span id="roll_issued_by">{{ $roll_issued_by }}</span>
                                </td>
                                <td >
                                    <label for="cutter">CUTTER :</label>
                                    <span id="cutter">{{ $cutter }}</span>
                                </td>
                                <td >
                                    <label for="cartons">CARTONS :</label>
                                    <span id="cartons">{{ $cartons }}</span>
                                </td>
                            </tr>
                </table>
            </div>
        </div>
    </div>
 </body>
</html>