<html>
<style>
    .main-table {
        border-collapse: collapse;
        padding: 5px 10px 10px 5px;
    }

    .table-name {
        font-weight: bold;
        text-decoration: underline;
    }

    .table-field-name {
        font-weight: bold;
    }

    .text-center {
        text-align: center !important;
        vertical-align: middle !important;
    }

    .design-details-table tr,
    .design-details-table td,
    .customer-details-table tr,
    .customer-details-table th,
    .customer-details-table td {

        height: 25px !important;
        line-height: 27.5px !important; /* 30px*/
        text-align: left !important;
        vertical-align: middle !important;
    }

    .design-details-table th {

        text-align: center !important;
        vertical-align: middle !important;
    }

    .for-production-table tr,
    .for-production-table th,
    .for-production-table td {

        height: 25px !important;
        line-height: 31px !important;
        text-align: left !important;
        vertical-align: middle !important;
    }

    .process-output-table tr,
    .process-output-table td {

        /* height: 25px !important; */
        line-height: 29px !important;
        text-align: left !important;
        vertical-align: middle !important;
    }

    .process-output-table th {
        text-align: center !important;
        vertical-align: middle !important;
    }

    .process-details-table tr,
    .process-details-table th,
    .process-details-table td {

        height: 25px !important;
        line-height: 31.6px !important;
        text-align: left !important;
        vertical-align: middle !important;
    }

    .dispatch-details-table tr,
    .dispatch-details-table th,
    .dispatch-details-table td {

        height: 25px !important;
        line-height: 36.8px !important;
        text-align: left !important;
        vertical-align: middle !important;
    }

    .top-left-border {
        border-top: 1px solid #6f7072;
        border-left: 1px solid #6f7072;
    }

    .top-left-right-border {
        border-top: 1px solid #6f7072;
        border-left: 1px solid #6f7072;
        border-right: 1px solid #6f7072;
    }

    .top-bottom-left-border {
        border-top: 1px solid #6f7072;
        border-bottom: 1px solid #6f7072;
        border-left: 1px solid #6f7072;
    }

    .top-bottom-left-right-border {
        border-top: 1px solid #6f7072;
        border-bottom: 1px solid #6f7072;
        border-left: 1px solid #6f7072;
        border-right: 1px solid #6f7072;
    }
</style>


<body>
    <table class="main-table" width="100%" cellspacing="0" cellpadding="10" border="0">
        <tr>
            <td colspan="1" rowspan="1">
                <p class="table-name text-center">DESIGN DETAILS</p>
                <table cellspacing="0" cellpadding="2" class="design-details-table">
                    <tr>
                        <th colspan="2" class="top-left-border table-field-name">ColorBar</th>
                        <th colspan="2" class="top-left-border table-field-name">Printspec Bar</th>
                        <th colspan="3" class="top-left-right-border table-field-name">20%50%70% ToneBar</th>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border" style="text-align:center !important">{{ $data['color_bar'] ? 'Yes' : 'No' }}</td>
                        <td colspan="2" class="top-left-border" style="text-align:center !important">{{ $data['print_spec_bar'] ? 'Yes' : 'No' }}</td>
                        <td colspan="3" class="top-left-right-border" style="text-align:center !important">{{ $data['tone_bar'] ? 'Yes' : 'No' }}</td>
                    </tr>
                        
                    <tr>
                        <td colspan="3" class="top-left-border table-field-name">&nbsp;&nbsp;Match To
                        <br>
                        </td>
                        
                        <td colspan="4" class="top-left-right-border" style="text-align:center !important">
                        @foreach ($color_match as $color_match)
                           
                           {{ $color_match['color_match_text'] }} @if ( ! $loop->last),@endif
                        
                        @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="top-left-border table-field-name">&nbsp;&nbsp;Front Colors
                        <br>
                        </td>
                        <td colspan="4" class="top-left-right-border" style="text-align:center !important">{{ $data['front_colors'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="top-left-border table-field-name">&nbsp;&nbsp;Back Colors</td>
                        <td colspan="4" class="top-left-right-border" style="text-align:center !important">{{ $data['back_color'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="top-left-border table-field-name">&nbsp;&nbsp;Board Categ.</td>
                        <td colspan="4" class="top-left-right-border" style="text-align:center !important">{{ $data['board_category_text'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="top-left-border table-field-name">&nbsp;&nbsp;Board Name</td>
                        <td colspan="4" class="top-left-right-border" style="text-align:center !important">{{ $data['board_text'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="top-left-border table-field-name">&nbsp;&nbsp;FSC / Non FSC</td>
                        <td colspan="4" class="top-left-right-border" style="text-align:center !important">{{ $data['fsc_non_fsc'] ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="top-left-border table-field-name">&nbsp;&nbsp;GSM/Thickness</td>
                        <td colspan="2" class="top-left-border" style="text-align:center !important">{{ $data['gsm_value'] }}</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['thickness'] }} &micro;</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="top-left-border table-field-name">&nbsp;&nbsp;Brd Size(in)</td>
                        <td colspan="2" class="top-left-border" style="text-align:center !important">{{ $data['board_size_length'] }}</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['board_size_width'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="top-left-border table-field-name">&nbsp;&nbsp;Print Size(in)</td>
                        <td colspan="2" class="top-left-border" style="text-align:center !important">{{ $data['print_size_length'] }}</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['print_size_width'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="top-left-border table-field-name">&nbsp;&nbsp;Design Size</td>
                        <td colspan="2" class="top-left-border" style="text-align:center !important">{{ $data['design_size_length'] }}</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['design_size_width'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="top-left-border table-field-name">&nbsp;&nbsp;Cut As</td>
                        <td colspan="1" class="top-left-border" style="text-align:center !important">{{ explode('/', $data['cut_as'])[0] }}</td>
                        <th colspan="2" class="top-left-border table-field-name">By</th>
                        <td colspan="1" class="top-left-right-border" style="text-align:center !important">{{ explode('/', $data['cut_as'])[1] }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="top-left-border table-field-name">&nbsp;&nbsp;Repeats</td>
                        <td colspan="4" class="top-left-right-border" style="text-align:center !important">{{ $data['repeats'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Old Plate</td>
                        <td colspan="1" class="top-left-border" style="text-align:center !important"></td>
                        <td colspan="4" class="top-left-right-border" style="text-align:center !important"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;New Plate</td>
                        <td colspan="1" class="top-left-border" style="text-align:center !important"></td>
                        <td colspan="4" class="top-left-right-border" style="text-align:center !important"></td>
                    </tr>
                    <tr>
                        <td colspan="7" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="top-bottom-left-border table-field-name">&nbsp;&nbsp;Die No.</td>
                        <td colspan="4" class="top-bottom-left-right-border" style="text-align:center !important">{{ $data['die_no'] }}</td>
                    </tr>
                </table>
            </td>
            <td colspan="1" rowspan="1">
                <p class="table-name text-center">PROCESS DETAILS</p>
                <table cellspacing="0" cellpadding="2" class="process-details-table">
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Cutting</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['cutting'] ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Printing</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['printing'] ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;UV</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['uv'] ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Coating</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['coating'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Lamination</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['lamination'] }}</td>
                    </tr>
                    <tr>
                        <th colspan="1" rowspan="2" class="top-left-border table-field-name"></th>
                        <th colspan="1" rowspan="1" class="top-left-border table-field-name">&nbsp;&nbsp;Board</th>
                        <th colspan="2" rowspan="1" class="top-left-border" style="text-align:center !important">{{ $data['pasting_board'] }}</th>
                        <th colspan="2" rowspan="1" class="top-left-border"></th>
                    </tr>
                    <tr>
                        <td colspan="1" rowspan="1" class="top-left-border table-field-name">&nbsp;&nbsp;GSM</td>
                        <td colspan="2" rowspan="1" class="top-left-border" style="text-align:center !important">{{ $data['pasting_gsm'] }}</td>
                        <td colspan="2" rowspan="1" class="top-left-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Window Patch</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['window_patch'] }} &micro;</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Folder Gluer</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['folder_gluer'] ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Die Cutting</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['die_cutting'] ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Embos/ Debos</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['embossing'] ? 'Yes' : 'No' }}/{{ $data['debossing'] ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Perforation</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['perforation'] ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Hole</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['hole_size'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Foil</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['foil'] ? 'Yes' : 'No' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;String</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['string_spec'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Eyelet</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['eyelet'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Thread</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Scoring</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-bottom-left-border"></td>
                        <td colspan="2" class="top-bottom-left-right-border"></td>
                    </tr>
                </table>
            </td>
            <td colspan="1" rowspan="1">
                <p class="table-name text-center">CUST DETAILS</p>
                <table cellspacing="0" cellpadding="2" class="customer-details-table">
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Cust</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['customer_name'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;StyleNum</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['style_number'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;PO No</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['customer_po_number'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;PO Dt</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ date('d-m-Y', strtotime($data['po_verified_date'])) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Buyer</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $data['buyer_name'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Order No</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $order_id }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Order Dt</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ date('d-m-Y', strtotime($data['order_date'])) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-bottom-left-border table-field-name">&nbsp;&nbsp;CustDelDt</td>
                        <td colspan="2" class="top-bottom-left-right-border" style="text-align:center !important">{{ date('d-m-Y', strtotime($data['customer_del_date'])) }}</td>
                    </tr>
                </table>
                <table>
                    <tr class="blank_row">
                        <td rowspan="1"></td>
                    </tr>
                    <tr class="blank_row">
                        <td rowspan="1"></td>
                    </tr>
                </table>
                <p class="table-name text-center">FOR PRODUCTION</p>
                <table cellspacing="0" cellpadding="2" class="for-production-table">
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Order Qnty</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important">{{ $total_qty }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Stock Avlb</td>
                        <td colspan="2" class="top-left-right-border" style="text-align:center !important"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Less Stk</td>
                        <td colspan="2" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Print Sheets</td>
                        <td colspan="2" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Excess</td>
                        <td colspan="2" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Setting</td>
                        <td colspan="2" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Total Sheets</td>
                        <td colspan="2" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Full Board</td>
                        <td colspan="2" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border"></td>
                        <td colspan="2" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-bottom-left-border"></td>
                        <td colspan="2" class="top-bottom-left-right-border"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="main-table">
        <tr>
            <td colspan="1" rowspan="1" width="70%">
                <p class="table-name text-center">PROCESS OUTPUT</p>
                <table cellspacing="0" cellpadding="2" class="process-output-table">
                    <tr>
                        <th colspan="1" class="top-left-border table-field-name">Process</th>
                        <th colspan="1" class="top-left-border table-field-name">Mc No</th>
                        <th colspan="1" class="top-left-border table-field-name">Good Sheet</th>
                        <th colspan="1" class="top-left-border table-field-name">Date</th>
                        <th colspan="1" class="top-left-right-border table-field-name">Sign</th>
                    </tr>
                    <tr>
                        <td colspan="1" class="top-left-border table-field-name">&nbsp;&nbsp;Cutting</td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="1" class="top-left-border table-field-name">&nbsp;&nbsp;Printing</td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="1" class="top-left-border table-field-name">&nbsp;&nbsp;Lamination</td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="1" class="top-left-border table-field-name">&nbsp;&nbsp;Pasting</td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="1" class="top-left-border table-field-name">&nbsp;&nbsp;Window <br>&nbsp;&nbsp;Patching</td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="1" class="top-left-border table-field-name">&nbsp;&nbsp;Folder Gluer</td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="1" class="top-left-border table-field-name">&nbsp;&nbsp;Die Cutting</td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-border"></td>
                        <td colspan="1" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="1" class="top-bottom-left-border table-field-name">&nbsp;&nbsp;UV</td>
                        <td colspan="1" class="top-bottom-left-border"></td>
                        <td colspan="1" class="top-bottom-left-border"></td>
                        <td colspan="1" class="top-bottom-left-border"></td>
                        <td colspan="1" class="top-bottom-left-right-border"></td>
                    </tr>
                </table>
            </td>

            <td colspan="1" rowspan="1" width="30%">
                <p class="table-name text-center">DISPATCH DETAILS</p>
                <table cellspacing="0" cellpadding="2" class="dispatch-details-table">
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;PackingDt</td>
                        <td colspan="2" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;InvNo</td>
                        <td colspan="2" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;InvDt</td>
                        <td colspan="2" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;DispDt</td>
                        <td colspan="2" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Qnty</td>
                        <td colspan="2" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;Weight</td>
                        <td colspan="2" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-left-border table-field-name">&nbsp;&nbsp;QC Sign</td>
                        <td colspan="2" class="top-left-right-border"></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="top-bottom-left-border table-field-name">&nbsp;&nbsp;Stock</td>
                        <td colspan="2" class="top-bottom-left-right-border"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>