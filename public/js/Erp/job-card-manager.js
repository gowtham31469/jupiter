/**
 * ERP job card manager jquery/javascript file
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */

$(document).ready(function() {
	$('input[class="row-checkbox"]').prop('checked', false);

    $('#create_jobcard').trigger("reset");

	/** To refresh modal while hidden **/
	$('.modal').on('hidden.bs.modal', function (e) {
        $('#create_jobcard').trigger("reset");
        
        $('#finish_type, #teeth_no, #machine, #disp_mode').selectpicker('refresh');
	})

	// Checkbox click functionality
	$('.approved-order-list tr').click(function (event) {
        if (event.target.type !== 'checkbox') {
            $(':checkbox', this).trigger('click');
        }
    });

    /** Datatable filter script**/
	var listTable = $('#approved-order-list').dataTable({
		/** Table column filter functionality **/
		initComplete: function () {
			$('.main-content > #approved-orders-table > .dataTables_wrapper > .row > .col-sm-6 > #approved-order-list_filter:first').append(' <button class="filterbtn-erp" type="button" onClick="showFilter()" title="Filter" data-placement="bottom"><i class="fa fa-filter" aria-hidden="true"></i></button>');
			$('.main-content > #approved-orders-table > .dataTables_wrapper > .row > .col-sm-6 > #approved-order-list_filter:first .dataTables_filter').css('display', 'inline-block');
			$('.main-content > #approved-orders-table > .dataTables_wrapper > .row > .col-sm-12:first').prepend('<div class="filter-window-jobcard"><h5><strong>Jobcard Filter</strong></h5> <div class="row"> <div class="col-md-2"><select class="selectpicker" id="erp_id_filter" data-live-search="true"> <option disabled selected>SELECT AN ERP ID</option></select></div><div class="col-md-2"><select class="selectpicker" id="customer_name_filter"  data-live-search="true"> <option disabled selected>SELECT A CUSTOMER</option></select></div><div class="col-md-2"><select class="selectpicker" id="po_number_filter"  data-live-search="true"> <option disabled selected>SELECT A PO NUMBER</option></select></div><div class="col-md-2"><select class="selectpicker" id="style_number_filter"  data-live-search="true"> <option disabled selected>SELECT A STYLE NUMBER</option></select></div><div class="col-md-2"><select class="selectpicker" id="ref_number_filter"  data-live-search="true"> <option disabled selected>SELECT A REFERENCE</option></select></div><div class="col-md-2"><select class="selectpicker" id="delivery_date_filter"  data-live-search="true"> <option disabled selected>SELECT A DELIVERY DATE</option></select></div></div><div class="filter-actions-erp"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="close_filter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');

			//For Custom Filters
			this.api().columns().every( function (i) {
				var column = this;
				if(i === 1 || i === 2 || i === 4 || i === 6 || i === 7 || i === 8) {
					var select;
					switch(i) {
						case 1:
							select = $('#erp_id_filter')
							break;
						case 2:
							select = $('#customer_name_filter')
							break;
						case 4:
							select = $('#po_number_filter')
							break;
						case 6:
							select = $('#style_number_filter')
							break;
						case 7:
							select = $('#ref_number_filter')
							break;
						case 8:
							select = $('#delivery_date_filter')
							break;
					}
					select.on( 'change', function () {
						var val = $.fn.dataTable.util.escapeRegex(
							$(this).val()
						);
	
						column
							.search( val ? '^'+val+'$' : '', true, false )
							.draw();
					});
					select.append( '<option value=""> </option>' );
					column.data().unique().sort().each( function ( d, j ) {
						cleanText = d.replace(/<\/?[^>]+(>|$)/g, "");
						select.append('<option value="'+cleanText+'">'+cleanText+'</option>')
					});
				}
			});
		}
    } );
    
	/** Get an array of the TR nodes that are used in the table's body */
	allPages = listTable.fnGetNodes();

	$("#reset").on("click", function () {
		$('#erp_id_filter, #customer_name_filter, #po_number_filter, #style_number_filter, #ref_number_filter, #delivery_date_filter').val('');
		
		$.fn.dataTableExt.afnFiltering.length = 0;
        listTable.dataTable().fnDraw();
        
		$('#erp_id_filter, #customer_name_filter, #po_number_filter, #style_number_filter, #ref_number_filter, #delivery_date_filter').selectpicker('val', 1);
		$('#erp_id_filter, #customer_name_filter, #po_number_filter, #style_number_filter, #ref_number_filter, #delivery_date_filter').selectpicker('refresh');
       
	});

	$('.selectpicker').selectpicker();

	// Clear the filter. Unlike normal filters in Datatables,
	// custom filters need to be removed from the afnFiltering array.
	$('.cancelBtn').on('click', function(e){
		e.preventDefault();
		$.fn.dataTableExt.afnFiltering.length = 0;
		listTable.dataTable().fnDraw();
	});

    /** Create job card form validation **/
    $('#create_jobcard').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
            e.preventDefault();
            storeJobcard();
        }
    })
    
    // Datatables for tables
    $("#approved-order-list_filter input[type=search]").addClass('form-control');
    $('.dataTables_length select').addClass('form-control');
    $("#approved-order-list_filter input[type=search]").attr('placeholder', 'Data Search');

    $('#jobcard-list-table').DataTable({
        "paging": false,
        "searching": false,
        "bInfo" : false,
        "ordering": false
    });
    $("#jobcard-list-table_filter input").addClass('form-control input-sm');
    $("#jobcard-list-table_filter input").attr('placeholder', 'Data Search');
    $('#jobcard-list-table_length select').addClass('form-control input-sm');

    /** Get edit jobcard details on click**/
    $(document).on('click','.edit-jobcard',function(e){
        var id = $(this).closest('tr').attr('id');
        e.preventDefault();
        /* call getBuyer function from buyers.js*/
        getJobcardDetails(id);
    });
    /** edit job card form validation **/
    $('#edit_jobcard').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
            e.preventDefault();
            updateJobcard();
        }
    })

    /** Filter jobcard list form validation **/
    $('#jobcard_filter_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
            e.preventDefault();
            getJobcardsList();
        }
    })
});
/* End of document.ready() */

/**
 * Toggle show filter window
 *  @author <Praveen> [praveen@wizroots.com]
 */
function showFilter() {
    $('.filter-window-jobcard').toggle();
}

/**
 * Job card generation for JGA company
 * @author <Praveen> [praveen@wizroots.com]
 */
function generateJobcard(){
    if ($("input[class='row-checkbox']:checked", allPages).length === 0)
    {
        $('.snackbar_span').html("Select atleast one order.");
		showSnackbar();
    }
    else
    {
        // remove file_path from local-storage
        localStorage.removeItem('file_path');
        
        orders = [];
        /** [ Create array of orders selected ] */
        $('.row-checkbox', allPages).each(function (i, item) {
            if ($(this).is(':checked')) {
                orders.push($(this).closest('tr').attr('id')); 
            }
        });
        orders   = orders.join(',');

        ref_array = [];
        /** [ Create array of reference selected ] */
        $('.row-checkbox', allPages).each(function (i, item) {
            if ($(this).is(':checked')) {
                ref_array.push($(this).closest('tr').data('refname')); 
            }
        });
        ref_array   = ref_array.join(',');

        buyer_po_array = [];
        /** [ Create array of buyer po selected ] */
        $('.row-checkbox', allPages).each(function (i, item) {
            if ($(this).is(':checked')) {
                buyer_po_array.push($(this).closest('tr').data('buyer-po')); 
            }
        });
        buyer_po_array   = buyer_po_array.join(',');

        order_type_array = [];
        /** [ Create array of order type selected ] */
        $('.row-checkbox', allPages).each(function (i, item) {
            if ($(this).is(':checked')) {
                order_type_array.push($(this).closest('tr').data('order-type')); 
            }
        });
        order_type_array   = order_type_array.join(',');

        uuid_array = [];
        /** [ Create array of uuid selected ] */
        $('.row-checkbox', allPages).each(function (i, item) {
            if ($(this).is(':checked')) {
                uuid_array.push($(this).closest('tr').data('uuid')); 
            }
        });
        uuid_array   = uuid_array.join(',');

        $.ajax({
            url:base_url + "/erp/job-manager/get-jobcard-order-details",
            dataType:"json",
            method:"get",
            data:{orders:orders, ref_array:ref_array, buyer_po_array:buyer_po_array,order_type_array:order_type_array,uuid_array:uuid_array},
            tryCount : 0,
            retryLimit : 2,
            beforeSend: function () {
              $(".loader").show();
            },
            statusCode: {
                /** [Show response status based on HTTP status code] */
                200: function(data) {
                    
                    if(data.error === false)
                    {
                        if(data.template_data.company)
                        {
                            $('#jc_company_name').text(data.template_data.company.company)
                            $('#company_address').text(data.template_data.company.company_address)
                            $('#company_logo').attr('src', data.template_data.company.company_logo);
                            $('#jc_company_name').attr('data-company-id', data.template_data.company.company_id)
                        }

                        $('#work_order').text( data.template_data.workorder_id);

                        $('#today_date').text( data.template_data.today_date);

                        data.template_data.article.length > 0?$('#article').text(data.template_data.article):'';

                        $('#material').val(data.template_data.material)

                        if(data.template_data.customer)
                        {
                            $('#customer').text(data.template_data.customer.customer_name)
                            $('#customer').attr('data-customer-id', data.template_data.customer.customer_id)
                        }

                        if(data.template_data.buyer)
                        {
                            $('#buyer').text(data.template_data.buyer.buyer_name)
                            $('#buyer').attr('data-buyer-id', data.template_data.buyer.buyer_id)

                            // show tint color and customer po for orchestra jc modal
                            if(data.template_data.buyer.buyer_name.toLowerCase() === 'orchestra')
                            {
                                $(".common-data-div").removeClass("hidden");

                                if(data.template_data.tint_color.length === 1)
                                {
                                    $('#tint_color').val(data.template_data.tint_color)
                                }
                                else
                                {
                                    $('#tint_color').val('')
                                }
                            } 
                            else 
                            {
                                $(".common-data-div").addClass("hidden");
                            }
                        }

                        if(data.template_data.style_array.length === 1)
                        {
                            if(data.template_data.buyer.buyer_name.toLowerCase() === 'orchestra')
                            {
                                if(data.template_data.tint_color.length === 1)
                                {
                                    $('#style_no').val(data.template_data.style_array+" - "+data.template_data.color_code)
                                }
                                else
                                {
                                    $('#style_no').val(data.template_data.style_array)
                                }
                            } 
                            else 
                            {
                                $('#style_no').val(data.template_data.style_array)
                            }
                            
                        }
                        else
                        {
                            $('#style_no').val('')
                        }

                        if(data.template_data.refnum_array.length === 1)
                        {
                            $('#reference').val(data.template_data.refnum_array)
                        }
                        else
                        {
                            $('#reference').val('')
                        }
                        
                        if(data.template_data.ordertype_array.length === 1)
                        {
                           if(data.template_data.ordertype_array[0] === 'newOrder')
                           {
                             $('#order_type').text('Initial')
                           }
                           else if(data.template_data.ordertype_array[0] ===  'reOrder')
                           {
                             $('#order_type').text('ReOrder')
                           }
                           else if(data.template_data.ordertype_array[0] === 'repeatOrder')
                           {
                             $('#order_type').text('RepeatOrder')
                           }
                           else
                           {
                             $('#order_type').text('')
                           }
                        }
                        else 
                        {
                            $('#order_type').text('')
                        } 
                        
                        $('#order_date').text(data.template_data.order_date);  

                        if(data.template_data.length.length === 1)
                        {
                            $('#length').val(data.template_data.length)
                        }
                        else
                        {
                            $('#length').val('')
                        } 

                        if(data.template_data.width.length === 1)
                        {
                            $('#width').val(data.template_data.width)
                        }
                        else
                        {
                            $('#width').val('')
                        } 

                        if(data.template_data.color.length === 1)
                        {
                            $('#print_color').val(data.template_data.color)
                        }
                        else
                        {
                            $('#print_color').val('')
                        }

                        if(data.template_data.cus_del_date.length === 1)
                        {
                            $('#cus_del_date').val(data.template_data.cus_del_date)
                        }
                        else
                        {
                            $('#cus_del_date').val('')
                        }

                        if(data.template_data.customer_po_array.length === 1)
                        {
                            $('#customer_po').text(data.template_data.customer_po_array[0])
                        }
                        else
                        {
                            $('#customer_po').val('')
                        }

                        /** append erp_details_ids  as hidden */
                        var hidden_html = '';
                        $.each(data.template_data.order_detail_ids, function(index, erp_details_id){
                            hidden_html += '<div hidden class="erp-details-id">'+erp_details_id+'</div>'
                        });
                        $('.order-details-div').html(hidden_html);

                        /** Show ratio templates dynamic table */
                        var ratio_table_html = '';
                        if(data.template_data.ratio.length != 0)
                        {
                            $.each(data.template_data.ratio, function(key, template){
                                var total = 0;
                                ratio_table_html += '<table class="table table-bordered table-responsive ratio-list-table"  style="font-size: 12px;" id="ratio_list_table" data-ratio-ref-table="'+key+'"  border="0.2">\
                                                        <thead>\
                                                            <tr>\
                                                                <th>REF.NO</th>\
                                                                <th>PO.NO</th>\
                                                                <th>ID.NO</th>\
                                                                <th>OCN</th>\
                                                                <th>QNTY</th>\
                                                                <th>P.QNTY</th>\
                                                            </tr>\
                                                        </thead>\
                                                        <tbody>';
                                                        template.sort(function(a, b){
                                                            return a.qty_table_id - b.qty_table_id;
                                                        });
                                                        $.each(template, function(ratio_table_row, row){
                                                            if (row.hasOwnProperty('ratio_pack_type')) {
                                                                var ratio_pack_type = ' '+row.ratio_pack_type;
                                                            } else {
                                                                var ratio_pack_type = '';
                                                            }
                                                            total += parseInt(row.order_quantity);
                                                            let buyer_po_number = (row.buyer_po_number === null||"") ?'-':row.buyer_po_number;
                                                            let ocn_number = (row.ocn_number === null||"") ?'-':row.ocn_number;
                                                            ratio_table_html += '<tr class="tr-'+row.reference_name.replace(/ /g, '-')+'" id='+row.id+' data-template-id='+row.template_id+'>\
                                                            <td id="ratio_tr_refname_'+ratio_table_row+'">'+row.reference_name+''+ratio_pack_type+'</td>\
                                                            <td id="ratio_tr_buyer_po_'+ratio_table_row+'">'+buyer_po_number+'</td>\
                                                            <td id="ratio_tr_erp_id_'+ratio_table_row+'">'+row.erp_id+'</td>\
                                                            <td id="ratio_tr_ocn_'+ratio_table_row+'">'+ocn_number+'</td>\
                                                            <td id="ratio_tr_qty_'+ratio_table_row+'">'+row.order_quantity+'</td>\
                                                            <td></td>\
                                                            </tr>';
                                                            ratio_table_row++;
                                                        });
                                                        ratio_table_html += '<tr>\
                                                            <th class="highlight_row" colspan="4">Total</th>\
                                                            <td><strong>'+total+'</strong></td>\
                                                            <td></td>\
                                                        </tr>\
                                                    </tbody>\
                                                </table>';
                            });
                            $('.ratio-list-table-div').html(ratio_table_html);
                        }
                        else{
                            $('.ratio-list-table-div').html(ratio_table_html);
                        }

                        /** Show single templates dynamic table */
                        var single_table_html = '';
                        var meters_div_html = '';
                        if(data.template_data.single.length != 0)
                        {
                            var row = 0;
                            var table = 0;
                            $.each(data.template_data.single, function(key, erpid){   
                                single_table_html += '<table class="table table-bordered table-responsive single-list-table"  style="font-size: 12px;" id="single_list_table_'+table+'"  border="0.2">\
                                <tbody id="single_list_table_body"><tr>\
                                <th width="80px">ID.NO</th>';
                                $.each(erpid.order_data, function(index, common_data){
                                    single_table_html += '<td colspan="2" data-template-id="'+common_data.template_id+'" class="single-erpid-'+index+'">'+common_data.erp_id+'</td>';
                                });
                                var template_name = key.split("_");
                                single_table_html += '<td colspan="4" class="highlight_row" rowspan="3" data-single-ref-name ="'+template_name[0]+'">REFNUM  : '+template_name[0]+'</td></tr>\
                                                            <tr>\
                                                                <th width="80px">B.PO.NO</th>';
                                $.each(erpid.order_data, function(index, common_data){
                                    let buyer_po_no = (common_data.buyer_po_number === null||"") ?'-':common_data.buyer_po_number;
                                    single_table_html += '<td colspan="2" class="single-buyer-po-'+index+'">'+buyer_po_no+'</td>';
                                });
                                single_table_html += '</tr>\
                                                            <tr>\
                                                                <th width="80px">OCN</th>';
                                $.each(erpid.order_data, function(index, common_data){
                                    let ocn_no = (common_data.ocn_number === null||"") ?'-':common_data.ocn_number;
                                    single_table_html += '<td colspan="2" class="single-ocn-'+index+'">'+ocn_no+'</td>';
                                });

                                single_table_html += '</tr>\
                                                      <th width="80px">SIZE /PRICE</th>';
                                $.each(erpid.order_data, function(index, common_data){
                                    single_table_html += '<th>O.QNT</th>\
                                    <th>P.QNT</th>';
                                });
                                single_table_html += '<th>TOTAL</th>\
                                                                <th width="180px">EXCESS</th>\
                                                                <th>MTRS</th>\
                                                                <th>STOCK</th>\
                                                            </tr>';
                                var total_quantity_final = 0;
                                var sum = [];
                                $.each(erpid.size, function(index2, quantity_data){
                                    var total_qty_sizewise = 0;
                                    var size_text = index2.split("_");
                                    var col = 0;
                                    single_table_html += '<tr><td class="single-size-row">'+size_text[0]+'</td>';
                                    
                                    $.each(quantity_data, function(index3, qty){
                                        total_qty_sizewise += parseInt(qty);
                                        single_table_html +='<td class="quantity-'+col+' quantity-td" data-size-value="'+size_text[0]+'">'+parseInt(qty)+'</td><td></td>';
                                        /** Column wise total quantity calculation */
                                        sum[col] = parseInt(qty) + (sum[col] || 0);
                                        col++;
                                    });

                                    single_table_html += '<td class="total-quantity-class total-quantity-row-'+row+'"><strong>'+parseInt(total_qty_sizewise)+'</strong></td><td class="total-excess"><div class="form-group has-feedback"><input type="number" data-step-error="Decimal values not allowed." min="0" data-min-error="Value must be >= 0" class="form-control input-sm total-excess-row-table-'+table+'" id="total_excess_row_table_'+row+'" data-required-error="Mandatory Field." name="total_excess_row_table_'+row+'"" placeholder="Excess %" onkeyup="return totalExcess()" ><div class="help-block with-errors"></div></div></td><td></td><td></td></tr>';
                                
                                    total_quantity_final += parseInt(total_qty_sizewise);
                                    row++;
                                });

                                single_table_html += '<tr>\
                                                      <th class="highlight_row total-text" data-ref-name = "'+template_name[0].replace(/ |\//g, '_')+'">TOTAL</th>';
                                var total_col = 0;
                                $.each(erpid.order_data, function(index, common_data){
                                    single_table_html += '<td class="total-quantity-col-'+total_col+'"><strong>'+sum[total_col]+'</strong></td>\
                                                          <td></td>';
                                    total_col++;
                                });
                                
                                single_table_html += '<td><strong class="total-qty-'+template_name[0].replace(/ |\//g, '_')+'">'+parseInt(total_quantity_final)+'</strong></td>\
                                                        <td class="total-excess-col-table-'+table+' total-excess-'+template_name[0].replace(/ |\//g, '_')+'"></td>\
                                                        <td></td>\
                                                        <td></td>\
                                                    </tr>';
                                single_table_html += ' </tbody>\
                                                </table>';
                                table++;
                                /** Show actual excess % and meters div only once for each references */
                                if(template_name[1] === '0')
                                {
                                    meters_div_html +='<div class="col-md-12">\
                                                <div class="col-md-3">\
                                                    <label for="act_excess_'+template_name[0].replace(/ |\//g, '_')+'" >ACT EXCESS % ('+template_name[0]+'):</label>\
                                                    <span class="act-excess" data-act-excess-ref="'+template_name[0]+'" id="act_excess_'+template_name[0].replace(/ |\//g, '_')+'"></span>\
                                                </div>\
                                                <div class="col-md-3">\
                                                    <label for="sticker_mtr_'+template_name[0].replace(/ |\//g, '_')+'" >STKRS MTRS ('+template_name[0]+'):</label>\
                                                    <span class="sticker-mtrs" id="sticker_mtr_'+template_name[0].replace(/ |\//g, '_')+'"></span>\
                                                </div>\
                                                <div class="col-md-3">\
                                                <div class="form-group has-feedback">\
                                                    <label for="settings_'+template_name[0].replace(/ |\//g, '_')+'" >SETTINGS ('+template_name[0]+') :</label>\
                                                    <input type="number" min="0" data-step-error="Decimal values not allowed." data-min-error="Value must be >= 0" class="form-control input-sm settings"  name="settings_'+template_name[0].replace(/ |\//g, '_')+'" placeholder="SETTINGS" id="settings_'+template_name[0].replace(/ |\//g, '_')+'" onkeyup="return totalMeters()" max="99999"  data-max-error="Value should be <= 99999">\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                                </div>\
                                                <div class="col-md-3">\
                                                <label for="total_mtrs_'+template_name[0].replace(/ |\//g, '_')+'" >TOTAL MTRS ('+template_name[0]+'):</label>\
                                                <span id="total_mtrs_'+template_name[0].replace(/ |\//g, '_')+'" data-tem-ref-name="'+template_name[0].replace(/ |\//g, '_')+'" class="total-meters"></span>\
                                                </div>\
                                            </div>';
                                }
                            });
                                
                            $('.single-list-table-div').html(single_table_html);
                            $('.meter-calculation-div').html(meters_div_html);
                        } else {
                            $('.single-list-table-div').html(single_table_html);
                            $('.meter-calculation-div').html(meters_div_html);
                        }
                        
                    }
                    else
                    {
                        $('.snackbar_span').html("Something went wrong");
                        showSnackbar();
                    }
                       
                    
                    /** [ Backdrop for bootstrap modal ] */
                    $('#jobcardCreationModalCenter').modal({
                        backdrop: 'static',
                        keyboard: false
                    });

                    /** [ Validate create_jobcard form using bootstrap validator ] */
                    $('#create_jobcard').validator('update');
                    $('#jobcardCreationModalCenter').modal('show');
                    $('.datepicker').datepicker({
                        format: 'dd-mm-yyyy'
                    });
                    // enable print button (for old version browsers)
                    $('#jc_print_btn').prop('disabled', false);
                },
                406: function(data) {
                     $('.snackbar_span').html(data.responseJSON.message);
                     showSnackbar();
                },
                404: function(data) {
                    $('.snackbar_span').html(data.responseJSON.message);
                    showSnackbar();
               },
                400: function() {
                    $('.snackbar_span').html("Something went wrong");
                    showSnackbar();
                }
           },
            error: function(errorXHR, errorStatus, errorThrown) {
               this.tryCount++;
               if (this.tryCount <= this.retryLimit) {
                   $.ajax(this);
                   return;
               }
            },
            complete: function () {
                $(".loader").hide();
            }
        })
        
    }
}

/**
 * Multi checkbox selection and disable different customer/buyer checkbox
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function selectOrders(event)
{
    /** Table checkbox click functionality	**/
    let checkbox = $("input[class='row-checkbox']", allPages);
    let customer_name = $(event).data('customer-name');
    let buyer_name = $(event).data('buyer-name');
    let ischecked= $(event).is(':checked');
    let length= $(checkbox).filter(':checked').length;

    if(ischecked)
    {
        /** change table row background color	**/
        $(event).closest('tr').addClass("highlight_row");
        /** Make checkboxes disabled(having different customer and buyer names) **/
        $(checkbox).not("[data-customer-name='"+customer_name+"']").attr("disabled", "true");
        $(checkbox).not("[data-buyer-name='"+buyer_name+"']").attr("disabled", "true");
    }
    else
    {
        /** change table row background color	**/
        $(event).closest('tr').removeClass("highlight_row");
        /** Make checkboxes enabled iff all checkboxes unchecked **/
        if(length === 0)
         $(checkbox).not("[data-customer-name='"+customer_name+"']").removeAttr("disabled");
         $(checkbox).not("[data-buyer-name='"+buyer_name+"']").removeAttr("disabled");
    }
}

/** ---------------------- Excess calculation functions for create jobcard begins ----------------------**/
/**
 * Dynamic Excess calculation based on excess value entered.(for JGA company)
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function calculateExcess(evt)
{
    let item = $('.total-quantity-class');
    $.each(item, function(key, value){
        let total_excess = parseFloat($(value).text() * (evt.value*1/100)).toFixed(2);
        $('#total_excess_row_table_'+key).val(Math.round(total_excess));
        /** refresh excess values fields in single ref table */
        $('#total_excess_row_table_'+key).trigger('input');
    });
    totalExcess();
}

/**
 * Dynamic Total Excess calculation based on calculated excess value.(for JGA company)
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function totalExcess()
{
    var table = 0;
    $('.single-list-table').each(function () {
        var total_excess_final = 0;
        $(this).find('.total-excess-row-table-'+table).each(function () {
            var excess = $(this).val();
            if (!isNaN(excess) && excess.length !== 0) {
                total_excess_final += parseInt(excess);
            }
        });
        $('.total-excess-col-table-'+table).html('<strong>'+Math.round(total_excess_final)+'</strong>');

        table++;
    });
    actualExcess()
}

/**
 * Dynamic Actual Excess calculation based on excess value and total quantity.(for JGA company)
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function actualExcess()
{
    $('.total-text').each(function(){
        let ref_name              = $(this).data('ref-name');
        let total_qty             = 0;
        let total_excess          = 0;
        let act_excess            = 0;

        $('.total-qty-'+ref_name).each(function(){
            if (!isNaN(total_qty) && total_qty.length !== 0) {
                total_qty += parseInt($(this).text());
            }
        });
        $('.total-excess-'+ref_name).each(function(){
            if (!isNaN(total_excess) && total_excess.length !== 0) {
                total_excess += parseInt($(this).text());
            }
        });
        if (!isNaN(total_qty) && !isNaN(total_excess)) {
            act_excess = parseFloat((total_excess/total_qty)*100).toFixed(2);
        }
        $('#act_excess_'+ref_name).text(Math.round(act_excess))
    });
}

/**
 * Dynamic Mtrs for sticker calculation.(for JGA company)
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function mtrsForStickers()
{
    var length_side_dimension = $('#length_side').val() === ''?0:$('#length_side').val();
    $('.total-text').each(function(){
        let ref_name              = $(this).data('ref-name');
        let total_qty             = 0;
        let mtrs                  = 0;
        let no_of_repeats         = $('#no_sticker').val() === ''?0:$('#no_sticker').val();
        let repeat_gap            = $('#repeat_gap').val() === ''?0:$('#repeat_gap').val();

        $('.total-qty-'+ref_name).each(function(){
            if (!isNaN(total_qty) && total_qty.length !== 0) {
                total_qty += parseInt($(this).text());
            }
        });

        if(!isNaN(total_qty) && !isNaN(length_side_dimension) && !isNaN(no_of_repeats) && !isNaN(repeat_gap) && no_of_repeats != 0 && repeat_gap != 0)
        {
            mtrs =  parseFloat((parseInt(total_qty)/parseInt(no_of_repeats)) * ((parseFloat(length_side_dimension) + parseInt(repeat_gap))/1000)).toFixed(2);
        }
        
        $('#sticker_mtr_'+ref_name).text(Math.round(mtrs))
        totalMeters()
    });
}

/**
 * Dynamic Total Meters calculation.(for JGA company)
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function totalMeters()
{
    $('.total-meters').each(function(){
       let ref_name = $(this).attr('data-tem-ref-name');
       let mtrs_for_stickers = $('#sticker_mtr_'+ref_name).text() === ''?0:$('#sticker_mtr_'+ref_name).text();
       let settings = $('#settings_'+ref_name).val() === ''?0:$('#settings_'+ref_name).val();
       if (!isNaN(settings) && !isNaN(mtrs_for_stickers)) {
           var total_mtr = parseInt(settings) + parseInt(mtrs_for_stickers);
        }
       $('#total_mtrs_'+ref_name).text(Math.round(total_mtr));
    });
}
/** ----------------- Excess calculation functions for create jobcard ends ----------------------**/
/**
 * Store jobcard(for JGA company)
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function storeJobcard()
{
    var company_id      = $( '#jc_company_name' ).data('company-id');
    var workorder_id    = $( '#work_order' ).text() === ''?null:$('#work_order').text();
    var created_date    = $( '#today_date' ).text() === ''?null:$('#today_date').text();
    var article         = $( '#article' ).text() === ''?null:$('#article').text();
    var material        = $( '#material' ).val() === ''?null:$('#material').val();
    var customer_id     = $( '#customer' ).data('customer-id');
    var style_no        = $( '#style_no' ).val() === ''?null:$('#style_no').val();
    var order_type      = $( '#order_type' ).text() === ''?null:$('#order_type').text();
    var buyer_id        = $( '#buyer' ).data('buyer-id');
    var reference       = $( '#reference' ).val() === ''?null:$('#reference').val();
    var disp_mode       = $( '#disp_mode option:selected' ).val() === ''?null:$('#disp_mode option:selected').val();
    var order_date      = $( '#order_date' ).text() === ''?null:$('#order_date').text();
    var length          = $( '#length' ).val() === ''?null:$('#length').val();
    var repeat_gap      = $( '#repeat_gap' ).val() === ''?null:$( '#repeat_gap' ).val();
    var machine         = $( '#machine option:selected' ).val() === ''?null:$( '#machine option:selected' ).val();
    var cus_del_date    = $( '#cus_del_date' ).val() === ''?null:$( '#cus_del_date' ).val();
    var roll_width      = $( '#roll_width' ).val() === ''?null:$( '#roll_width' ).val();
    var width           = $( '#width' ).val() === ''?null:$('#width').val();
    var film_no         = $( '#film_no' ).val() === ''?null:$( '#film_no' ).val();
    var invoice_date    = $( '#invoice_date' ).val() === ''?null:$( '#invoice_date' ).val();
    var length_side     = $( '#length_side' ).val() === ''?null:$( '#length_side' ).val();
    var excess          = $( '#excess' ).val() === ''?null:$( '#excess' ).val();
    var teeth_no        = $( '#teeth_no option:selected' ).val() === ''?null:$( '#teeth_no option:selected' ).val();
    var inv_no          = $( '#inv_no' ).val() === ''?null:$( '#inv_no' ).val();
    var no_sticker      = $( '#no_sticker' ).val() === ''?null:$( '#no_sticker' ).val();
    var finish_type     = $( '#finish_type option:selected' ).val() === ''?null:$( '#finish_type option:selected' ).val();
    var print_color     = $( '#print_color' ).val() === ''?null:$('#print_color').val();
    var compiled_by     = $( '#compiled_by' ).val() === ''?null:$('#compiled_by').val();
    var barcode_read    = $( '#barcode_read' ).val() === ''?null:$('#barcode_read').val();
    var operator        = $( '#operator' ).val() === ''?null:$('#operator').val();
    var checker         = $( '#checker' ).val() === ''?null:$('#checker').val();
    var authorized_by   = $( '#author' ).val() === ''?null:$('#author').val();
    var axicon_verifier = $( '#axicon_verifier' ).val() === ''?null:$('#axicon_verifier').val();
    var approved_by     = $( '#approved_by' ).val() === ''?null:$('#approved_by').val();
    var packer          = $( '#packer' ).val() === ''?null:$('#packer').val();
    var approval_date   = $( '#approval_date' ).val() === ''?null:$('#approval_date').val();
    var roll_issued_by  = $( '#roll_issued_by' ).val() === ''?null:$('#roll_issued_by').val();
    var cutter          = $( '#cutter' ).val() === ''?null:$('#cutter').val();
    var cartons         = $( '#cartons' ).val() === ''?null:$('#cartons').val();
    var tint_color      = $( '#tint_color' ).val() === ''?null:$('#tint_color').val();
    var customer_po     = $( '#customer_po' ).text() === ''?null:$('#customer_po').text();

    var final_json_obj =   {
        "ratio": {},
        "single": {},
        "erp_order_details" : []
          };  
    var ratio_object = {};
    /** Create ratio templates json object */
    $('.ratio-list-table').each(function (i, table) {
     var my_object    = new Object();
     var array_list   = [];
        ref_name     = $(this).data('ratio-ref-table').replace(/ |\//g, '_');

      $(table).find('tbody tr:not(:last)').each(function (index, row) {
        my_object = {}; 
        my_object.reference_name  = $(row).find ( "#ratio_tr_refname_"+index ).text();
        my_object.erp_id          = $(row).find ( "#ratio_tr_erp_id_"+index ).text();
        my_object.buyer_po_number = $(row).find ( "#ratio_tr_buyer_po_"+index ).text();
        my_object.ocn_number      = $(row).find ( "#ratio_tr_ocn_"+index ).text();
        my_object.order_quantity  = $(row).find ( "#ratio_tr_qty_"+index ).text();
        my_object.template_id     = $(row).data ( 'template-id' );

        array_list.push(my_object);
        index++;
        ratio_object[ref_name] = array_list;
      });
      // pushing ratio templates array into json object
      final_json_obj['ratio'] = ratio_object;
    });
    
    
    var single_ref_obj = {};
    
    /** Create single templates json object */
    $('.single-list-table').each(function (index, table) {
        var single_obj = {
            "erp_id": [],
            "buyer_po": [],
            "ocn": [],
            "temp_id": [],
            "size": [],
            "total": [],
            "excess": []
              };
        var erp_id_object  = {};
        var erp_id_array   = [];
        var po_object      = {};
        var po_array       = [];
        var ocn_object     = {};
        var ocn_array      = [];
        var temp_id_object = {};
        var temp_id_array  = [];
        single_ref_name    = $(table).find('tbody tr:eq(0) td:last-child').data('single-ref-name').replace(/ |\//g, '_');
        
        // create erp_id array
        $(table).find('tbody tr:eq(0) td:not(:last)').each(function (i, item) {
            erp_id_object = {};
            erp_id_object = item.innerHTML;
            erp_id_array.push(erp_id_object);

            temp_id_object = {};
            temp_id_object = $(item).data('template-id');
            temp_id_array.push(temp_id_object);
        });
        
        single_obj['erp_id']  = erp_id_array;
        single_obj['temp_id'] = temp_id_array;
        // create po number array
        $(table).find('tbody tr:eq(1) td').each(function (i, item) {
            po_object = {};
            po_object = item.innerHTML;
            po_array.push(po_object);
        });
        single_obj['buyer_po'] = po_array;

        // create ocn number array
        $(table).find('tbody tr:eq(2) td').each(function (i, item) {
            ocn_object = {};
            ocn_object = item.innerHTML;
            ocn_array.push(ocn_object);
        });
        single_obj['ocn'] = ocn_array;

        var size_object_final = {};
        var total_qty_object_final = {};
        var excess_object_final = {};
        // create size, qty and excess json object
        $(table).find('tbody tr:gt(3):not(:last-child)').each(function (i, item) {
            
            var size_text  = $(item).find('.single-size-row').text();
            var size_array      = [];
            $(item).find('.quantity-td').each(function (index, row) {
                size_object = {};
                size_object = row.innerHTML;
                size_array.push(size_object);
            });
            size_object_final[size_text] = size_array;

            $(item).find('.total-quantity-class strong').each(function (index1, total_qty) {
                total_qty_object = {};
                total_qty_object = total_qty.innerHTML;
            });
            total_qty_object_final[size_text] = total_qty_object;

            $(item).find('.total-excess div input').each(function (index2, excess) {
                excess_object = {};
                excess_object = excess.value;
            });
            excess_object_final[size_text] = excess_object;

        });
        single_obj['size'] = size_object_final;
        single_obj['total'] = total_qty_object_final;
        single_obj['excess'] = excess_object_final;
        
        single_ref_obj[single_ref_name+'_'+index] = single_obj;
        // pushing single templates array into json object
        final_json_obj['single'] = single_ref_obj;
    });
    
    var single_ref = [];
    var act_excess = [];
    /** [ Create array of act-excess ] */
    $('.act-excess').each(function (i, item) {
        act_excess[i] = $(item).text() === ''?0:$(item).text();
        single_ref[i] = $(item).data('act-excess-ref');
    });
    act_excess = act_excess.join(',');
    single_ref = single_ref.join(',');

    var sticker_mtr = [];
    /** [ Create array of sticker-mtrs ] */
    $('.sticker-mtrs').each(function (i, item) {
        sticker_mtr[i] = $(item).text() === ''?0:$(item).text();

    });
    sticker_mtr = sticker_mtr.join(',');

    var settings = [];
    /** [ Create array of settings ] */
    $('.settings').each(function (i, item) {
        settings[i] = $(item).val() === ''?0:$(item).val();

    });
    settings = settings.join(',');

    var total_mtrs = [];
    /** [ Create array of total-meters ] */
    $('.total-meters').each(function (i, item) {
        total_mtrs[i] = $(item).text() === ''?0:$(item).text();

    });
    total_mtrs = total_mtrs.join(',');

    erp_orders_ids = [];
    /** [ Create array of orders selected ] */
    $('.row-checkbox', allPages).each(function (i, item) {
        if ($(this).is(':checked')) {
            erp_orders_ids.push($(this).closest('tr').attr('id')); 
        }
    });

    erp_orders_ids   = erp_orders_ids.join(',');

    selected_ref_array = [];
    /** [ Create array of reference selected ] */
    $('.row-checkbox', allPages).each(function (i, item) {
        if ($(this).is(':checked')) {
            selected_ref_array.push($(this).closest('tr').data('refname')); 
        }
    });
    selected_ref_array   = selected_ref_array.join(',');

    selected_buyer_po_array = [];
    /** [ Create array of buyer po selected ] */
    $('.row-checkbox', allPages).each(function (i, item) {
        if ($(this).is(':checked')) {
            selected_buyer_po_array.push($(this).closest('tr').data('buyer-po')); 
        }
    });
    selected_buyer_po_array   = selected_buyer_po_array.join(',');

    order_type_array = [];
    /** [ Create array of order type selected ] */
    $('.row-checkbox', allPages).each(function (i, item) {
        if ($(this).is(':checked')) {
            order_type_array.push($(this).closest('tr').data('order-type')); 
        }
    });
    order_type_array   = order_type_array.join(',');

    uuid_array = [];
    /** [ Create array of uuid selected ] */
    $('.row-checkbox', allPages).each(function (i, item) {
        if ($(this).is(':checked')) {
            uuid_array.push($(this).closest('tr').data('uuid')); 
        }
    });
    uuid_array   = uuid_array.join(',');
    
    jsonArray = [];
    /** [ Create json object of selected orders ] */
    $('.row-checkbox', allPages).each(function (i, item) {
        erp_order_details_obj = {};
        
        if ($(this).is(':checked')) {
            erp_order_details_obj.created_at = $(this).closest('tr').data('created-at');
            erp_order_details_obj.order_type = $(this).closest('tr').data('order-type');
            erp_order_details_obj.buyer_po = $(this).closest('tr').data('buyer-po');
            erp_order_details_obj.refname = $(this).closest('tr').data('refname');
            erp_order_details_obj.erp_id = $(this).closest('tr').attr('id');
            erp_order_details_obj.style_no = $(this).closest('tr').data('style-no');
            erp_order_details_obj.temp_file = $(this).closest('tr').data('template-file');
            erp_order_details_obj.temp_id = $(this).closest('tr').data('template-id');
            erp_order_details_obj.temp_name = $(this).closest('tr').data('template-name');
            erp_order_details_obj.disney = $(this).closest('tr').data('disney');
            erp_order_details_obj.frontPath = $(this).closest('tr').data('front-path');
            erp_order_details_obj.backPath = $(this).closest('tr').data('back-path');
            erp_order_details_obj.middlePath = $(this).closest('tr').data('middle-path');
            erp_order_details_obj.uuid = $(this).closest('tr').data('uuid');

            jsonArray.push(erp_order_details_obj);
        }
    });

    final_json_obj['erp_order_details'] = jsonArray;

    $.ajax({
        url: base_url + "/erp/job-manager/store-jobcard-details",
        type: 'POST',
        data: {
            _token: csrf_token,
            company_id: company_id,
            workorder_id: workorder_id,
            created_date: created_date,
            article: article,
            material: material,
            customer_id: customer_id,
            style_no: style_no,
            order_type: order_type,
            buyer_id: buyer_id,
            reference: reference,
            disp_mode: disp_mode,
            order_date: order_date,
            length: length,
            repeat_gap: repeat_gap,
            machine: machine,
            cus_del_date: cus_del_date,
            roll_width: roll_width,
            width: width,
            film_no: film_no,
            invoice_date: invoice_date,
            length_side: length_side,
            excess: excess,
            teeth_no: teeth_no,
            inv_no: inv_no,
            no_sticker: no_sticker,
            finish_type: finish_type,
            print_color:print_color,
            compiled_by:compiled_by,
            barcode_read:barcode_read,
            operator:operator,
            checker:checker,
            authorized_by:authorized_by,
            axicon_verifier:axicon_verifier,
            approved_by:approved_by,
            packer:packer,
            approval_date:approval_date,
            roll_issued_by:roll_issued_by,
            cutter:cutter,
            cartons:cartons,
            final_json_obj:JSON.stringify(final_json_obj),
            single_ref:single_ref,
            act_excess:act_excess,
            sticker_mtr:sticker_mtr,
            settings:settings,
            total_mtrs:total_mtrs,
            erp_orders_ids:erp_orders_ids,
            selected_ref_array:selected_ref_array,
            selected_buyer_po_array:selected_buyer_po_array,
            order_type_array:order_type_array,
            uuid_array:uuid_array,
            tint_color:tint_color,
            customer_po:customer_po
        },
        tryCount: 0,
        retryLimit: 2,
        async: false,
        dataType: "json",
        beforeSend: function () {
            $(".loader").show();
            $('#jc_print_btn').prop('disabled', true);
        },
        statusCode: {
            200: function(data) {

                /** [ Store file path into localstorage ] */
                localStorage.setItem('file_path',data.file_path)
                // generate and attach artworks with jobcard
                if(data.buyer_name.toLowerCase() === 'tesco')
                {
                    var isEdit        = false;
                    $('.row-checkbox', allPages).each(function (i, item) {
                    
                        if ($(this).is(':checked')) {
                            refname       = $(this).closest('tr').data('refname'); 
                            id            = $(this).closest('tr').attr('id');
                            buyer_po_no   = $(this).closest('tr').data('buyer-po');
                            order_type    = $(this).closest('tr').data('order-type');
                            created_at    = $(this).closest('tr').data('created-at');
                            style_no      = $(this).closest('tr').data('style-no');
                            loop          = $(this).closest('tr').data('iteration');
							template_name = $(this).closest('tr').data('template-name');
							template_id = $(this).closest('tr').data('template-id');
							disney = $(this).closest('tr').data('disney');
                            modal_name    = refname.replace(/ /g, '');

                            openmodaltescopreview(modal_name, loop, style_no, buyer_po_no, id, order_type, created_at, isEdit,'print',template_name,template_id,disney,refname);
                        }
                    });
                }
                else 
                {
					
					var isEdit        = false;
                    $('.row-checkbox', allPages).each(function (i, item) {
                    
                        if ($(this).is(':checked')) {
                            refname       = $(this).closest('tr').data('refname'); 
                            id            = $(this).closest('tr').attr('id');
                            buyer_po_no   = $(this).closest('tr').data('buyer-po');
                            order_type    = $(this).closest('tr').data('order-type');
                            created_at    = $(this).closest('tr').data('created-at');
                            style_no      = $(this).closest('tr').data('style-no');
                            loop          = $(this).closest('tr').data('iteration');
							template_name = $(this).closest('tr').data('template-name');
							template_id = $(this).closest('tr').data('template-id');
							disney = $(this).closest('tr').data('disney');
                            modal_name    = refname.replace(/ /g, '');

                            openmodaltescopreview(refname, loop, style_no, buyer_po_no, id, order_type, created_at, isEdit,'print_orchestra',template_name,template_id,disney,refname);
                        }
                    });
					
//                    var resp = JSON.stringify(data.file_path); 
//                    window.open(
//                        base_url+JSON.parse(resp),
//                        '_blank' // <- This is what makes it open in a new window.
//                    ); 
//                    $('.snackbar_span').html("Jobcard Created Successfully");
//                    showSnackbar();
//                    $('.modal').modal('hide');
//                    location.reload();
                }
              
            },
            400: function() {
                $('.snackbar_span').html("Parameter is missing !!!");
                showSnackbar();
            },
       },
        error: function (errorXHR, errorStatus, errorThrown) {
            this.tryCount++;
            if (this.tryCount <= this.retryLimit) {
                $.ajax(this);
                return;
            }
        },
        complete: function () {
            $(".loader").hide();
        }
    })
}

/**
   * Function for get jobcard details for editing(for JGA company)
   * @param id
   * @author <praveen>
   *
   */
  function getJobcardDetails(id)
  {
    try{
         $.ajax({
             url:base_url + "/erp/job-manager/jobcard-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
               // remove file_path from local-storage
               localStorage.removeItem('file_path');
             },
             success: function(data){
                 $('.edit-ratio-list-table-div').html('');
                 $('.edit-single-list-table-div').html('');
                 //hidden jobcard id for edit functionality
                 $('#jobcard_id').val(id);
                 $('#edit_jc_company_name').text(data.jobcard_data.company);
                 $('#edit_company_address').text(data.jobcard_data.company_address);
                 $('#edit_company_logo').attr('src', data.jobcard_data.company_logo);
                 $('#edit_jc_company_name').attr('data-company-id', data.jobcard_data.company_id);
                 $('#edit_work_order').text(data.jobcard_data.work_order_id);
                 $('#edit_today_date').text(data.jobcard_data.created_date);
                 $('#edit_article').text(data.jobcard_data.article);
                 $('#edit_material').val(data.jobcard_data.material);
                 $('#edit_customer').text(data.jobcard_data.customer);
                 $('#edit_customer').attr('data-customer-id', data.jobcard_data.customer_id);
                 $('#edit_buyer').text(data.jobcard_data.buyer_name);
                 $('#edit_buyer').attr('data-buyer-id', data.jobcard_data.buyer_id);
                 $('#edit_style_no').val(data.jobcard_data.style_number);
                 $('#edit_reference').val(data.jobcard_data.reference);
                 $('#edit_order_type').text(data.jobcard_data.order_type);
                 $('#edit_disp_mode').val(data.jobcard_data.dispatch_mode);
                 $('#edit_order_date').text(data.jobcard_data.order_date);
                 $('#edit_length').val(data.jobcard_data.length);
                 $('#edit_repeat_gap').val(data.jobcard_data.repeat_gap);
                 $('#edit_machine').val(data.jobcard_data.machine);
                 $('#edit_cus_del_date').val(data.jobcard_data.customer_del_date);
                 $('#edit_width').val(data.jobcard_data.width);
                 $('#edit_roll_width').val(data.jobcard_data.roll_width);
                 $('#edit_film_no').val(data.jobcard_data.film_no);
                 $('#edit_invoice_date').val(data.jobcard_data.invoice_date);
                 $('#edit_length_side').val(data.jobcard_data.length_side);
                 $('#edit_excess').val(data.jobcard_data.excess_percentage);
                 $('#edit_teeth_no').val(data.jobcard_data.teeth_no);
                 $('#edit_inv_no').val(data.jobcard_data.invoice_num);
                 $('#edit_no_sticker').val(data.jobcard_data.no_of_stickers);
                 $('#edit_finish_type').val(data.jobcard_data.finish_type);
                 $('#edit_print_color').val(data.jobcard_data.print_color);
                 $('#edit_compiled_by').val(data.jobcard_data.compiled_by);
                 $('#edit_barcode_read').val(data.jobcard_data.barcode_read);
                 $('#edit_operator').val(data.jobcard_data.operator);
                 $('#edit_checker').val(data.jobcard_data.checker);
                 $('#edit_author').val(data.jobcard_data.authorized_by);
                 $('#edit_axicon_verifier').val(data.jobcard_data.axicon_verifier);
                 $('#edit_approved_by').val(data.jobcard_data.approved_by);
                 $('#edit_packer').val(data.jobcard_data.packer);
                 $('#edit_approval_date').val(data.jobcard_data.approval_date);
                 $('#edit_roll_issued_by').val(data.jobcard_data.roll_issued_by);
                 $('#edit_cutter').val(data.jobcard_data.cutter);
                 $('#edit_cartons').val(data.jobcard_data.cartons);
                 $('#edit_tint_color').val(data.jobcard_data.tint_color);
                 $('#edit_customer_po').text(data.jobcard_data.customer_po_num);

                  // show tint color and customer po for orchestra jc modal
                  if(data.jobcard_data.buyer_name.toLowerCase() === 'orchestra')
                  {
                      $(".edit-common-data-div").removeClass("hidden");
                  } 
                  else 
                  {
                      $(".edit-common-data-div").addClass("hidden");
                  }

                 /** [ Refresh  bootstrap selectpicker ] */
                 $('#edit_finish_type').selectpicker('refresh');
                 $('#edit_teeth_no').selectpicker('refresh');
                 $('#edit_machine').selectpicker('refresh');
                 $('#edit_disp_mode').selectpicker('refresh');
                 var ratio_table = '';
                 $.each(JSON.parse(data.jobcard_data.template_quantity_json), function(template_type, template_type_data){
                    if(template_type === 'ratio')
                    {
                        if (!($.isEmptyObject(template_type_data)))
                        {
                            $.each(template_type_data, function(table, table_data){
                                ratio_table += '<table class="table table-bordered table-responsive edit-ratio-list-table"  style="font-size: 12px;" id="ratio_list_table" data-ratio-ref-table="'+table.replace('_', ' ')+'"  border="0.2">\
                                    <thead style="width:fixed;">\
                                        <tr>\
                                            <th><strong>REF.NO</strong></th>\
                                            <th><strong>PO.NO</strong></th>\
                                            <th><strong>ID.NO</strong></th>\
                                            <th><strong>OCN</strong></th>\
                                            <th><strong>QNTY</strong></th>\
                                            <th><strong>P.QNTY</strong></th>\
                                        </tr>\
                                    </thead>\
                                    <tbody>';
                                        var ratio_total = 0;
                                        $.each(table_data, function(key, value){
                                            let buyer_po = (value["buyer_po_number"] === null||"") ?'-':value["buyer_po_number"];
                                            let ocn_num = (value["ocn_number"] === null||"") ?'-':value["ocn_number"];
                                            ratio_table += '<tr class="tr-'+table.replace('_', ' ')+'"  data-template-id="'+value["template_id"]+'">\
                                                                <td id="edit_ratio_tr_refname_'+key+'">'+value["reference_name"]+'</td>\
                                                                <td id="edit_ratio_tr_buyer_po_'+key+'">'+buyer_po+'</td>\
                                                                <td id="edit_ratio_tr_erp_id_'+key+'">'+value["erp_id"]+'</td>\
                                                                <td id="edit_ratio_tr_ocn_'+key+'">'+ocn_num+'</td>\
                                                                <td id="edit_ratio_tr_qty_'+key+'">'+value["order_quantity"]+'</td>\
                                                                <td></td>\
                                                            </tr>';

                                          ratio_total += parseInt(value["order_quantity"]);
                                        });
                                        ratio_table += '<tr>\
                                            <th colspan="4" class="highlight_row"><strong>Total</strong></th>\
                                            <td><strong>'+ratio_total+'</strong></td>\
                                            <td></td>\
                                        </tr>\
                                    </tbody>\
                                </table>';
                            });
                         $('.edit-ratio-list-table-div').html(ratio_table);

                        }
                    }
                    else if(template_type === 'single')
                    {
                        if (!($.isEmptyObject(template_type_data)))
                        {
                            var table_row = 0;
                            var table_count = 0;
                            var single_table = '';
                            $.each(template_type_data, function(table, table_data){
                                var lastIndex = table.lastIndexOf("_");

                                var template = table.substring(0, lastIndex).replace(',','_');
                                console.log(template.replace(/ |\//g, '_'));
                                //var template = table.split("_");
                                
                                single_table += '<table class="table table-bordered table-responsive edit-single-list-table" style="font-size: 12px;" id="single_list_table_'+table_count+'" border="0.2">\
                                        <tbody id="single_list_table_body">\
                                        <tr>\
                                            <th width="80px">ID.NO</th>';
                                            $.each(table_data.erp_id, function(key, value){
                                                single_table += '<td colspan="2" data-template-id="'+table_data.temp_id[key]+'" class="single-erpid-'+key+'">'+value+'</td>';
                                            });
                                            single_table += '<td colspan="4" class="highlight_row" rowspan="3" data-single-ref-name="'+template.replace('_',' ')+'">REFNUM &nbsp;: '+template.replace('_',' ')+'</td>\
                                        </tr>\
                                        <tr>\
                                            <th width="80px">B.PO.NO</th>';
                                            $.each(table_data.buyer_po, function(key, value){
                                                single_table += '<td colspan="2" class="single-buyer-po-'+key+'">'+value+'</td>';
                                            });
                                            single_table += '</tr>\
                                        <tr>\
                                            <th width="80px">OCN</th>';
                                            $.each(table_data.ocn, function(key, value){
                                                single_table += '<td colspan="2" class="single-ocn-'+key+'">'+value+'</td>';
                                            });
                                            single_table += '</tr>\
                                        <tr>\
                                          <th width="80px"><strong>SIZE /PRICE</strong></th>';
                                        $.each(table_data.erp_id, function(key, value){
                                            single_table += '<th><strong>O.QNT</strong></th>\
                                                             <th><strong>P.QNT</strong></th>';
                                        });
                                        single_table += '<th><strong>TOTAL</strong></th>\
                                                         <th width="180px"><strong>EXCESS</strong></th>\
                                                         <th><strong>MTRS</strong></th>\
                                                         <th><strong>STOCK</strong></th></tr>';
                                                         
                                        var total_quantity_final = 0;
                                        var total_excess_final = 0;
                                        var qty_col_sum = [];
                                        $.each(table_data.size, function(size, quantities){
                                            var total_qty_sizewise = 0;
                                            var col = 0;
                                            single_table += '<tr>\
                                                <td class="edit-single-size-row">'+size+'</td>';
                                                $.each(quantities, function(key, qty){
                                                    single_table += '<td class="quantity-'+key+' edit-quantity-td" data-size-value="'+size+'">'+qty+'</td>\
                                                            <td></td>';
                                                    total_qty_sizewise += parseInt(qty);
                                                    /** Column wise total quantity calculation */
                                                    qty_col_sum[col] = parseInt(qty) + (qty_col_sum[col] || 0);
                                                    col++;
                                                });
                                                single_table += '<td class="edit-total-quantity-class total-quantity-row-'+table_row+'"><strong>'+parseInt(total_qty_sizewise)+'</strong></td>\
                                                <td class="edit-total-excess">\
                                                    <div class="form-group has-feedback">\
                                                        <input type="number" data-step-error="Decimal values not allowed." min="0" data-min-error="Value must be >= 0" class="form-control input-sm edit-total-excess-row-table-'+table_count+'"  id="edit_total_excess_row_table_'+table_row+'" data-required-error="Mandatory Field." name="edit_total_excess_row_table_'+table_row+'" "="" placeholder="Excess %" onkeyup="return editTotalExcess()" value="'+table_data.excess[size]+'">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>\
                                                <td></td>\
                                                <td></td>\
                                                </tr>';
                                          total_quantity_final += parseInt(total_qty_sizewise);
                                          total_excess_final   += parseInt(table_data.excess[size]);
                                          table_row++;
                                        });
                                        single_table += '<tr>\
                                                            <th class="highlight_row edit-total-text" data-ref-name="'+template.replace(/ |\//g, '_')+'">TOTAL</th>';
                                        
                                        var qty_total_col = 0;
                                        $.each(table_data.erp_id, function(key, value){
                                            single_table += '<td class="edit-total-quantity-col-'+qty_total_col+'"><strong>'+qty_col_sum[qty_total_col]+'</strong></td>\
                                            <td></td>';
                                            qty_total_col++;
                                        });
                                                            
                                        single_table += '<td><strong class="edit-total-qty-'+template.replace(/ |\//g, '_')+'">'+parseInt(total_quantity_final)+'</strong></td>\
                                                <td class="edit-total-excess-col-table-'+table_count+' edit-total-excess-'+template.replace(/ |\//g, '_')+'"><strong>'+Math.round(isNaN(total_excess_final) ? '0' : total_excess_final)+'</strong></td>\
                                                <td></td>\
                                                <td></td>\
                                            </tr>\
                                    </tbody>\
                                </table>';
                                table_count++;
                            });
                        $('.edit-single-list-table-div').html(single_table);
                        }
                    }
                    
                 });

                // excess, meters and settings calculation values.
                edit_meters_div_html = '';
                if(data.jobcard_data.additional_details.length > 0)
                {
                    $.each(data.jobcard_data.additional_details, function(key, item){
                        edit_meters_div_html +='<div class="col-md-12">\
                                            <div class="col-md-3">\
                                                <label for="edit_act_excess_'+item.ref_name.replace(/ |\//g, '_')+'" >ACT EXCESS % ('+item.ref_name+'):</label>\
                                                <span class="edit-act-excess" data-act-excess-ref="'+item.ref_name+'" id="edit_act_excess_'+item.ref_name.replace(/ |\//g, '_')+'">'+item.actual_excess+'</span>\
                                            </div>\
                                            <div class="col-md-3">\
                                                <label for="edit_sticker_mtr_'+item.ref_name.replace(/ |\//g, '_')+'" >STKRS MTRS ('+item.ref_name+'):</label>\
                                                <span class="edit-sticker-mtrs" id="edit_sticker_mtr_'+item.ref_name.replace(/ |\//g, '_')+'">'+item.mtrs_for_stickers+'</span>\
                                            </div>\
                                            <div class="col-md-3">\
                                            <div class="form-group has-feedback">\
                                                <label for="edit_settings_'+item.ref_name.replace(/ |\//g, '_')+'" >SETTINGS ('+item.ref_name+') :</label>\
                                                <input type="number" min="0" data-step-error="Decimal values not allowed." data-min-error="Value must be >= 0" max="99999" data-max-error="Value should be <= 99999" class="form-control input-sm edit-settings" name="edit_settings_'+item.ref_name.replace(/ |\//g, '_')+'" placeholder="SETTINGS" id="edit_settings_'+item.ref_name.replace(/ |\//g, '_')+'" onkeyup="return totalMetersEdit()" value="'+item.settings+'" >\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                            </div>\
                                            <div class="col-md-3">\
                                            <label for="edit_total_mtrs_'+item.ref_name.replace(/ |\//g, '_')+'" >TOTAL MTRS ('+item.ref_name+'):</label>\
                                            <span id="edit_total_mtrs_'+item.ref_name.replace(/ |\//g, '_')+'" data-edit-tem-ref-name="'+item.ref_name.replace(/ |\//g, '_')+'" class="edit-total-meters">'+item.total_mtrs+'</span>\
                                            </div>\
                                        </div>';
                    });
                   
                    $('.edit-meter-calculation-div').html(edit_meters_div_html);
                }  
                else
                {
                    edit_meters_div_html = '';
                    $('.edit-meter-calculation-div').html(edit_meters_div_html);
                    
                } 
                
                var erp_details_div_html = '';
                $.each(JSON.parse(data.jobcard_data.template_quantity_json), function(index, artwork_data){ 
                 if(index != "ratio" && index != "single")
                 {
                    if(index === "erp_order_details")
                    {
                        if(artwork_data.length > 0)
                        {
                            var incr = 0;
                            // hidden erp_order_details data.
                            $.each(artwork_data, function(index, erp_data){
								
								if(erp_data.temp_name == "hang_tag")
								{
									erp_details_div_html += '<div hidden class="edit-erp-details" data-erp-id="'+erp_data.erp_id+'" data-ref-name="'+erp_data.refname+'" data-order-type="'+erp_data.order_type+'" data-buyer-po="'+erp_data.buyer_po+'" data-created-at="'+erp_data.created_at+'"  data-style-no="'+erp_data.style_no+'" data-template-file="'+erp_data.temp_file+'" data-template-id="'+erp_data.temp_id+'" data-front-path="'+erp_data.frontPath+'" data-back-path="'+erp_data.backPath+'" data-template-name="'+erp_data.temp_name+'" data-disney="'+erp_data.disney+'" data-uuid="'+erp_data.uuid+'"></div><object style="opacity:0;width:0px;height:0px" id="orchestra_modal_front'+incr+'" class="center-block tesco_svg'+incr+'" data="'+erp_data.frontPath+'" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="orchestra_modal_back'+incr+'" class="center-block tesco_svg'+incr+'" data="'+erp_data.backPath+'" type="image/svg+xml"></object>'
								}
								else if(erp_data.temp_name == "wash_care")
								{
									erp_details_div_html += '<div hidden class="edit-erp-details" data-erp-id="'+erp_data.erp_id+'" data-ref-name="'+erp_data.refname+'" data-order-type="'+erp_data.order_type+'" data-buyer-po="'+erp_data.buyer_po+'" data-created-at="'+erp_data.created_at+'"  data-style-no="'+erp_data.style_no+'" data-template-file="'+erp_data.temp_file+'" data-template-id="'+erp_data.temp_id+'" data-template-name="'+erp_data.temp_name+'" data-front-path="'+erp_data.frontPath+'" data-back-path="'+erp_data.backPath+'" data-middle-path="'+erp_data.middlePath+'" data-disney="'+erp_data.disney+'" data-uuid="'+erp_data.uuid+'"></div><object style="opacity:0;width:0px;height:0px" id="washcare_front'+incr+'" class="center-block tesco_svg'+incr+'" data="'+erp_data.frontPath+'" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="washcare_back'+incr+'" class="center-block tesco_svg'+incr+'" data="'+erp_data.backPath+'" type="image/svg+xml"></object><object style="opacity:0;width:0px;height:0px" id="washcare_middle'+incr+'" class="center-block tesco_svg'+incr+'" data="'+erp_data.middlePath+'" type="image/svg+xml"></object>'
								}
								else if(erp_data.temp_name == "stickers")
								{
									erp_details_div_html += '<div hidden class="edit-erp-details" data-erp-id="'+erp_data.erp_id+'" data-ref-name="'+erp_data.refname+'" data-order-type="'+erp_data.order_type+'" data-buyer-po="'+erp_data.buyer_po+'" data-created-at="'+erp_data.created_at+'"  data-style-no="'+erp_data.style_no+'" data-template-file="'+erp_data.temp_file+'" data-template-id="'+erp_data.temp_id+'" data-template-name="'+erp_data.temp_name+'" data-disney="'+erp_data.disney+'" data-uuid="'+erp_data.uuid+'"></div><object style="opacity:0;width:0px;height:0px" id="orchestra_svg_modal'+incr+'" class="center-block tesco_svg'+incr+'" data="'+erp_data.temp_file+'" type="image/svg+xml"></object>'
                                }
                                else if(erp_data.temp_name == "tesco_care_labels")
                                {
                                    var temp_fi = erp_data.temp_file.split(',');
                                    erp_details_div_html += '<div hidden class="edit-erp-details" data-erp-id="'+erp_data.erp_id+'" data-ref-name="'+erp_data.refname+'" data-order-type="'+erp_data.order_type+'" data-buyer-po="'+erp_data.buyer_po+'" data-created-at="'+erp_data.created_at+'"  data-style-no="'+erp_data.style_no+'" data-template-file="'+erp_data.temp_file+'" data-template-id="'+erp_data.temp_id+'" data-template-name="'+erp_data.temp_name+'" data-uuid="'+erp_data.uuid+'"></div>   <object style="opacity:0;width:0px;height:0px" id="tesco_svg_modal'+incr+'" class="center-block tesco_svg'+incr+'" data="'+temp_fi[0]+'" type="image/svg+xml"></object>  <object style="opacity:0;width:0px;height:0px" id="tesco_svg_back_modal'+incr+'" class="center-block tesco_svg'+incr+'" data="'+temp_fi[1]+'" type="image/svg+xml"></object>  <object style="opacity:0;width:0px;height:0px" id="tesco_svg_back_text_modal'+incr+'" class="center-block tesco_svg'+incr+'" data="'+temp_fi[2]+'" type="image/svg+xml"></object>  <object style="opacity:0;width:0px;height:0px" id="tesco_svg_front_modal'+incr+'" class="center-block tesco_svg'+incr+'" data="'+temp_fi[3]+'" type="image/svg+xml"></object>'
                                }
                                else
                                {
                                    erp_details_div_html += '<div hidden class="edit-erp-details" data-erp-id="'+erp_data.erp_id+'" data-ref-name="'+erp_data.refname+'" data-order-type="'+erp_data.order_type+'" data-buyer-po="'+erp_data.buyer_po+'" data-created-at="'+erp_data.created_at+'"  data-style-no="'+erp_data.style_no+'" data-template-file="'+erp_data.temp_file+'" data-uuid="'+erp_data.uuid+'"></div><object style="opacity:0;width:0px;height:0px" id="tesco_svg_modal'+incr+'" class="center-block tesco_svg'+incr+'" data="'+erp_data.temp_file+'" type="image/svg+xml"></object>'
                                }
                                
                                incr++;
                            });
                            
                            $('.edit-order-details-div').html(erp_details_div_html);
                        }
                    }
                    
                 }
                });
                    
                /** [ Backdrop for bootstrap modal ] */
                $('#jobcardUpdationModalCenter').modal({
                    backdrop: 'static',
                    keyboard: false
                })

                /** [ Validate edit_jobcard form using bootstrap validator ] */
                $('#edit_jobcard').validator('validate');
                $('#jobcardUpdationModalCenter').modal('show');
                $('.datepicker').datepicker({
                    format: 'dd-mm-yyyy'
                });
             },
             error: function(errorXHR, errorStatus, errorThrown) {
                  this.tryCount++;
                  if (this.tryCount <= this.retryLimit) {
                      $.ajax(this);
                      return;
                  }
              },
             complete: function () {
                 $(".loader").hide();
             }
         })
   } catch (e) {
     console.log("Error:"+e)
   }
  }

  /** ---------------------- Excess calculation functions for edit jobcard begins ----------------------**/
/**
 * Dynamic Excess calculation based on excess value entered.(for JGA company)
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function calculateExcessEdit(evt)
{
    let item = $('.edit-total-quantity-class');
    $.each(item, function(key, value){
        let total_excess = parseFloat($(value).text() * (evt.value*1/100)).toFixed(2);
        $('#edit_total_excess_row_table_'+key).val(Math.round(total_excess));
        /** refresh excess values fields in single ref table */
        $('#edit_total_excess_row_table_'+key).trigger('input');
    });
    editTotalExcess();
}

/**
 * Dynamic Total Excess calculation based on calculated excess value.(for JGA company)
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function editTotalExcess()
{
    var table = 0;
    $('.edit-single-list-table').each(function () {
        var total_excess_final = 0;
        $(this).find('.edit-total-excess-row-table-'+table).each(function () {
            var excess = $(this).val();
            if (!isNaN(excess) && excess.length !== 0) {
                total_excess_final += parseInt(excess);
            }
        });
        $('.edit-total-excess-col-table-'+table).html('<strong>'+Math.round(total_excess_final)+'</strong>');

        table++;
    });
    actualExcessEdit()
}

/**
 * Dynamic Actual Excess calculation based on excess value and total quantity.(for JGA company)
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function actualExcessEdit()
{
    $('.edit-total-text').each(function(){
        let ref_name              = $(this).data('ref-name');
        let total_qty             = 0;
        let total_excess          = 0;
        let act_excess            = 0;

        $('.edit-total-qty-'+ref_name).each(function(){
            if (!isNaN(total_qty) && total_qty.length !== 0) {
                total_qty += parseInt($(this).text());
            }
        });
        $('.edit-total-excess-'+ref_name).each(function(){
            if (!isNaN(total_excess) && total_excess.length !== 0) {
                total_excess += parseInt($(this).text());
            }
        });
        if (!isNaN(total_qty) && !isNaN(total_excess)) {
            act_excess = parseFloat((total_excess/total_qty)*100).toFixed(2);
        }
        $('#edit_act_excess_'+ref_name).text(Math.round(act_excess));
    });
}

/**
 * Dynamic Mtrs for sticker calculation.(for JGA company)
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function mtrsForStickersEdit()
{
    var length_side_dimension = $('#edit_length_side').val() === ''?0:$('#edit_length_side').val();
    $('.edit-total-text').each(function(){
        let ref_name              = $(this).data('ref-name');
        let total_qty             = 0;
        let mtrs                  = 0;
        let no_of_repeats         = $('#edit_no_sticker').val() === ''?0:$('#edit_no_sticker').val();
        let repeat_gap            = $('#edit_repeat_gap').val() === ''?0:$('#edit_repeat_gap').val();

        $('.edit-total-qty-'+ref_name).each(function(){
            if (!isNaN(total_qty) && total_qty.length !== 0) {
                total_qty += parseInt($(this).text());
            }
        });

        if(!isNaN(total_qty) && !isNaN(length_side_dimension) && !isNaN(no_of_repeats) && !isNaN(repeat_gap) && no_of_repeats != 0 && repeat_gap != 0)
        {
            mtrs =  parseFloat((parseInt(total_qty)/parseInt(no_of_repeats)) * ((parseFloat(length_side_dimension) + parseInt(repeat_gap))/1000)).toFixed(2);
        }
        
        $('#edit_sticker_mtr_'+ref_name).text(Math.round(mtrs));
        totalMetersEdit()
    });
}

/**
 * Dynamic Total Meters calculation.(for JGA company)
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function totalMetersEdit()
{
    $('.edit-total-meters').each(function(){
       let ref_name = $(this).attr('data-edit-tem-ref-name');
       let mtrs_for_stickers = $('#edit_sticker_mtr_'+ref_name).text() === ''?0:$('#edit_sticker_mtr_'+ref_name).text();
       let settings = $('#edit_settings_'+ref_name).val() === ''?0:$('#edit_settings_'+ref_name).val();
       if (!isNaN(settings) && !isNaN(mtrs_for_stickers)) {
           var total_mtr = parseInt(settings) + parseInt(mtrs_for_stickers);
        }
       $('#edit_total_mtrs_'+ref_name).text(Math.round(total_mtr));
    });
}
/** ----------------- Excess calculation functions for edit jobcard ends ----------------------**/

/**
 * Update jobcard(for JGA company)
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function updateJobcard(formData)
{
    var company_id      = $( '#edit_jc_company_name' ).data('company-id');
    var workorder_id    = $( '#edit_work_order' ).text() === ''?null:$('#edit_work_order').text();
    var created_date    = $( '#edit_today_date' ).text() === ''?null:$('#edit_today_date').text();
    var article         = $( '#edit_article' ).text() === ''?null:$('#edit_article').text();
    var material        = $( '#edit_material' ).val() === ''?null:$('#edit_material').val();
    var customer_id     = $( '#edit_customer' ).data('customer-id');
    var style_no        = $( '#edit_style_no' ).val() === ''?null:$('#edit_style_no').val();
    var order_type      = $( '#edit_order_type' ).text() === ''?null:$('#edit_order_type').text();
    var buyer_id        = $( '#edit_buyer' ).data('buyer-id');
    var reference       = $( '#edit_reference' ).val() === ''?null:$('#edit_reference').val();
    var disp_mode       = $( '#edit_disp_mode option:selected' ).val() === ''?null:$('#edit_disp_mode option:selected').val();
    var order_date      = $( '#edit_order_date' ).text() === ''?null:$('#edit_order_date').text();
    var length          = $( '#edit_length' ).val() === ''?null:$('#edit_length').val();
    var repeat_gap      = $( '#edit_repeat_gap' ).val() === ''?null:$( '#edit_repeat_gap' ).val();
    var machine         = $( '#edit_machine option:selected' ).val() === ''?null:$( '#edit_machine option:selected' ).val();
    var cus_del_date    = $( '#edit_cus_del_date' ).val() === ''?null:$( '#edit_cus_del_date' ).val();
    var roll_width      = $( '#edit_roll_width' ).val() === ''?null:$( '#edit_roll_width' ).val();
    var width           = $( '#edit_width' ).val() === ''?null:$('#edit_width').val();
    var film_no         = $( '#edit_film_no' ).val() === ''?null:$( '#edit_film_no' ).val();
    var invoice_date    = $( '#edit_invoice_date' ).val() === ''?null:$( '#edit_invoice_date' ).val();
    var length_side     = $( '#edit_length_side' ).val() === ''?null:$( '#edit_length_side' ).val();
    var excess          = $( '#edit_excess' ).val() === ''?null:$( '#edit_excess' ).val();
    var teeth_no        = $( '#edit_teeth_no option:selected' ).val() === ''?null:$( '#edit_teeth_no option:selected' ).val();
    var inv_no          = $( '#edit_inv_no' ).val() === ''?null:$( '#edit_inv_no' ).val();
    var no_sticker      = $( '#edit_no_sticker' ).val() === ''?null:$( '#edit_no_sticker' ).val();
    var finish_type     = $( '#edit_finish_type option:selected' ).val() === ''?null:$( '#edit_finish_type option:selected' ).val();
    var print_color     = $( '#edit_print_color' ).val() === ''?null:$('#edit_print_color').val();
    var compiled_by     = $( '#edit_compiled_by' ).val() === ''?null:$('#edit_compiled_by').val();
    var barcode_read    = $( '#edit_barcode_read' ).val() === ''?null:$('#edit_barcode_read').val();
    var operator        = $( '#edit_operator' ).val() === ''?null:$('#edit_operator').val();
    var checker         = $( '#edit_checker' ).val() === ''?null:$('#edit_checker').val();
    var authorized_by   = $( '#edit_author' ).val() === ''?null:$('#edit_author').val();
    var axicon_verifier = $( '#edit_axicon_verifier' ).val() === ''?null:$('#edit_axicon_verifier').val();
    var approved_by     = $( '#edit_approved_by' ).val() === ''?null:$('#edit_approved_by').val();
    var packer          = $( '#edit_packer' ).val() === ''?null:$('#edit_packer').val();
    var approval_date   = $( '#edit_approval_date' ).val() === ''?null:$('#edit_approval_date').val();
    var roll_issued_by  = $( '#edit_roll_issued_by' ).val() === ''?null:$('#edit_roll_issued_by').val();
    var cutter          = $( '#edit_cutter' ).val() === ''?null:$('#edit_cutter').val();
    var cartons         = $( '#edit_cartons' ).val() === ''?null:$('#edit_cartons').val();
    var tint_color      = $( '#edit_tint_color' ).val() === ''?null:$('#edit_tint_color').val();
    var customer_po     = $( '#edit_customer_po' ).text() === ''?null:$('#edit_customer_po').text();
    var jobcard_id      = $( '#jobcard_id' ).val();

    var final_json_obj =   {
        "ratio": {},
        "single": {},
        "erp_order_details" : []
          };   
    var ratio_object = {};
    /** Create ratio templates json object */
    $('.edit-ratio-list-table').each(function (i, table) {
     var my_object    = new Object();
     var array_list   = [];
         ref_name     = $(this).data('ratio-ref-table').replace(/ |\//g, '_');

      $(table).find('tbody tr:not(:last)').each(function (index, row) {
        my_object = {}; 
        my_object.reference_name  = $(row).find ( "#edit_ratio_tr_refname_"+index ).text();
        my_object.erp_id          = $(row).find ( "#edit_ratio_tr_erp_id_"+index ).text();
        my_object.buyer_po_number = $(row).find ( "#edit_ratio_tr_buyer_po_"+index ).text();
        my_object.ocn_number      = $(row).find ( "#edit_ratio_tr_ocn_"+index ).text();
        my_object.order_quantity  = $(row).find ( "#edit_ratio_tr_qty_"+index ).text();
        my_object.template_id     = $(row).data ( 'template-id' );

        array_list.push(my_object);
        index++;
        ratio_object[ref_name] = array_list;
      });
      // pushing ratio templates array into json object
      final_json_obj['ratio'] = ratio_object;
    });
    
    
    var single_ref_obj = {};
    
    /** Create single templates json object */
    $('.edit-single-list-table').each(function (index, table) {
        var single_obj = {
            "erp_id": [],
            "buyer_po": [],
            "ocn": [],
            "temp_id": [],
            "size": [],
            "total": [],
            "excess": []
              };
        var erp_id_object  = {};
        var erp_id_array   = [];
        var po_object      = {};
        var po_array       = [];
        var ocn_object     = {};
        var ocn_array      = [];
        var temp_id_object = {};
        var temp_id_array  = [];
        single_ref_name    = $(table).find('tbody tr:eq(0) td:last-child').data('single-ref-name').replace(/ |\//g, '_');
        
        // create erp_id array
        $(table).find('tbody tr:eq(0) td:not(:last)').each(function (i, item) {
            erp_id_object = {};
            erp_id_object = item.innerHTML;
            erp_id_array.push(erp_id_object);

            temp_id_object = {};
            temp_id_object = $(item).data('template-id');
            temp_id_array.push(temp_id_object);
        });
        
        single_obj['erp_id']  = erp_id_array;
        single_obj['temp_id'] = temp_id_array;
        // create po number array
        $(table).find('tbody tr:eq(1) td').each(function (i, item) {
            po_object = {};
            po_object = item.innerHTML;
            po_array.push(po_object);
        });
        single_obj['buyer_po'] = po_array;

        // create ocn number array
        $(table).find('tbody tr:eq(2) td').each(function (i, item) {
            ocn_object = {};
            ocn_object = item.innerHTML;
            ocn_array.push(ocn_object);
        });
        single_obj['ocn'] = ocn_array;

        var size_object_final = {};
        var total_qty_object_final = {};
        var excess_object_final = {};
        // create size, qty and excess json object
        $(table).find('tbody tr:gt(3):not(:last-child)').each(function (i, item) {
            
            var size_text  = $(item).find('.edit-single-size-row').text();
            var size_array      = [];
            $(item).find('.edit-quantity-td').each(function (index, row) {
                size_object = {};
                size_object = row.innerHTML;
                size_array.push(size_object);
            });
            size_object_final[size_text] = size_array;

            $(item).find('.edit-total-quantity-class strong').each(function (index1, total_qty) {
                total_qty_object = {};
                total_qty_object = total_qty.innerHTML;
            });
            total_qty_object_final[size_text] = total_qty_object;

            $(item).find('.edit-total-excess div input').each(function (index2, excess) {
                excess_object = {};
                excess_object = excess.value;
            });
            excess_object_final[size_text] = excess_object;

        });
        single_obj['size'] = size_object_final;
        single_obj['total'] = total_qty_object_final;
        single_obj['excess'] = excess_object_final;
        
        single_ref_obj[single_ref_name+'_'+index] = single_obj;
        // pushing single templates array into json object
        final_json_obj['single'] = single_ref_obj;
    });

    var single_ref = [];
    var act_excess = [];
    /** [ Create array of act-excess ] */
    $('.edit-act-excess').each(function (i, item) {
        act_excess[i] = $(item).text() === ''?0:$(item).text();
        single_ref[i] = $(item).data('act-excess-ref');
    });
    act_excess = act_excess.join(',');
    single_ref = single_ref.join(',');

    var sticker_mtr = [];
    /** [ Create array of sticker-mtrs ] */
    $('.edit-sticker-mtrs').each(function (i, item) {
        sticker_mtr[i] = $(item).text() === ''?0:$(item).text();

    });
    sticker_mtr = sticker_mtr.join(',');

    var settings = [];
    /** [ Create array of settings ] */
    $('.edit-settings').each(function (i, item) {
        settings[i] = $(item).val() === ''?0:$(item).val();

    });
    settings = settings.join(',');

    var total_mtrs = [];
    /** [ Create array of total-meters ] */
    $('.edit-total-meters').each(function (i, item) {
        total_mtrs[i] = $(item).text() === ''?0:$(item).text();

    });
    total_mtrs = total_mtrs.join(',');

    jsonArray = [];
    /** [ Create json object of jobcard-craeted orders ] */
    $('.edit-erp-details').each(function (i, item) {
        erp_order_details_obj = {};
        erp_order_details_obj.created_at = $(this).attr('data-created-at'); 
        erp_order_details_obj.order_type = $(this).attr('data-order-type'); 
        erp_order_details_obj.buyer_po   = $(this).attr('data-buyer-po'); 
        erp_order_details_obj.refname    = $(this).attr('data-ref-name'); 
        erp_order_details_obj.erp_id     = $(this).attr('data-erp-id');
        erp_order_details_obj.style_no   = $(this).attr('data-style-no');
        erp_order_details_obj.temp_file  = $(this).attr('data-template-file');
		erp_order_details_obj.temp_id 	 = $(this).attr('data-template-id');
		erp_order_details_obj.temp_name  = $(this).attr('data-template-name');
		erp_order_details_obj.disney 	 = $(this).attr('data-disney');
		erp_order_details_obj.frontPath  = $(this).attr('data-front-path');
		erp_order_details_obj.backPath   = $(this).attr('data-back-path');
		erp_order_details_obj.middlePath = $(this).attr('data-middle-path');
        erp_order_details_obj.uuid       = $(this).attr('data-uuid');

        jsonArray.push(erp_order_details_obj);
    });

    // pushing created_at array into json object
    final_json_obj['erp_order_details'] = jsonArray;

    $.ajax({
        url: base_url + "/erp/job-manager/update-jobcard-details",
        type: 'PUT',
        data: {
            _token: csrf_token,
            company_id: company_id,
            workorder_id: workorder_id,
            created_date: created_date,
            article: article,
            material: material,
            customer_id: customer_id,
            style_no: style_no,
            order_type: order_type,
            buyer_id: buyer_id,
            reference: reference,
            disp_mode: disp_mode,
            order_date: order_date,
            length: length,
            repeat_gap: repeat_gap,
            machine: machine,
            cus_del_date: cus_del_date,
            roll_width: roll_width,
            width: width,
            film_no: film_no,
            invoice_date: invoice_date,
            length_side: length_side,
            excess: excess,
            teeth_no: teeth_no,
            inv_no: inv_no,
            no_sticker: no_sticker,
            finish_type: finish_type,
            print_color:print_color,
            compiled_by:compiled_by,
            barcode_read:barcode_read,
            operator:operator,
            checker:checker,
            authorized_by:authorized_by,
            axicon_verifier:axicon_verifier,
            approved_by:approved_by,
            packer:packer,
            approval_date:approval_date,
            roll_issued_by:roll_issued_by,
            cutter:cutter,
            cartons:cartons,
            final_json_obj:JSON.stringify(final_json_obj),
            single_ref:single_ref,
            act_excess:act_excess,
            sticker_mtr:sticker_mtr,
            settings:settings,
            total_mtrs:total_mtrs,
            jobcard_id:jobcard_id,
            tint_color:tint_color,
            customer_po:customer_po
        },
        tryCount: 0,
        retryLimit: 2,
        dataType: "json",
        async: false,
        beforeSend: function () {
            $(".loader").show();
            $('#jc_print_btn').prop('disabled', true);
            
        },
        statusCode: {
            200: function(data) {
                /** [ Store file path into localstorage ] */
                localStorage.setItem('file_path',data.file_path)
                var loop = 0;

                // generate and attach artworks with jobcard
                if(data.buyer_name.toLowerCase() === 'tesco')
                {
                    var isEdit = true;
                    $('.edit-erp-details').each(function (i, item) {
                        created_at = $(this).attr('data-created-at'); 
                        order_type = $(this).attr('data-order-type'); 
                        buyer_po_no= $(this).attr('data-buyer-po'); 
                        refname    = $(this).attr('data-ref-name'); 
                        id         = $(this).attr('data-erp-id');
						template_name = $(this).attr('data-template-name');
						template_id   = $(this).attr('data-template-id');
						disney 		  = $(this).attr('data-disney');
                        style_no   = $(this).attr('data-style-no');
                        modal_name = refname.replace(/ /g, '');

                        openmodaltescopreview(modal_name, loop, style_no, buyer_po_no, id, order_type, created_at, isEdit,'print',template_name,template_id,disney,refname);
                        loop++;
                    });
                }
                else 
                {
					
					var isEdit        = true;
                    $('.edit-erp-details').each(function (i, item) {
                    
                            refname       = $(this).attr('data-ref-name'); 
                            id            = $(this).attr('data-erp-id');
                            buyer_po_no   = $(this).attr('data-buyer-po'); 
                            order_type    = $(this).attr('data-order-type'); 
                            created_at    = $(this).attr('data-created-at'); 
                            style_no      = $(this).attr('data-style-no');
							template_name = $(this).attr('data-template-name');
							template_id   = $(this).attr('data-template-id');
							disney 		  = $(this).attr('data-disney');
                            modal_name    = refname.replace(/ /g, '');

                            openmodaltescopreview(refname, loop, style_no, buyer_po_no, id, order_type, created_at, isEdit,'print_orchestra',template_name,template_id,disney,refname);
							loop++;
                    });
//                    var resp = JSON.stringify(data.file_path); 
//                    window.open(
//                        base_url+JSON.parse(resp),
//                        '_blank' // <- This is what makes it open in a new window.
//                    );  
//                    $('.snackbar_span').html("Jobcard Created Successfully");
//                    showSnackbar();
//                    $('.modal').modal('hide');
//                    location.reload();
                }
                
            },
            400: function() {
                $('.snackbar_span').html("Parameter is missing !!!");
                showSnackbar();
            },
       },
        error: function (errorXHR, errorStatus, errorThrown) {
            this.tryCount++;
            if (this.tryCount <= this.retryLimit) {
                $.ajax(this);
                return;
            }
        },
        complete: function () {
            $(".loader").hide();
        }
    })
}

/**
 * Get customers list based on buyer selection
 * @param buyer_id
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function getCustomersList(evt)
{
    try {
        let selected_buyer_id = evt.value;
        if (selected_buyer_id != '') 
        {
            $.ajax({
                url: base_url + "/erp/job-manager/get-customers-list",
                type: 'GET',
                data: {
                    selected_buyer_id: selected_buyer_id
                },
                tryCount: 0,
                retryLimit: 2,
                dataType: "json",
                beforeSend: function () {
                    $(".loader").show();
                },
                statusCode: {
                    200: function(data) {
                        var html = '<option value="">SELECT A CUSTOMER</option>';
                        /** [ customers list selectpicker value populating ] */
                        $.each(data.customers_list, function (key, item) {
                            html += '<option value="' + key + '">' + item + '</option>';
                        });
                        $('#customer_filter').html(html);
                        $('#customer_filter').selectpicker('refresh');
                    },
                    400: function() {
                        $('.snackbar_span').html("Parameter is missing !!!");
                        showSnackbar();
                    },
               },
                error: function (errorXHR, errorStatus, errorThrown) {
                    this.tryCount++;
                    if (this.tryCount <= this.retryLimit) {
                        $.ajax(this);
                        return;
                    }
                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
        else
        {
            $('#customer_filter').html('<option value="">SELECT A CUSTOMER</option>');
            $('#customer_filter').selectpicker('refresh');
            $('#jobcard_id_filter').html('<option value="">SELECT A JOBCARD ID</option>');
            $('#jobcard_id_filter').selectpicker('refresh');
        }
    } catch (e) {
        console.log("Error:"+e)
    }
}

/**
 * Get jobcard id list based on buyer and customer selection
 * @param buyer_id, customer_id
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function getJobcardIDList(evt)
{
    try {
        let selected_customer_id = evt.value;
        let selected_buyer_id    = $('#buyer_filter option:selected').val();
        if (selected_customer_id != '') 
        {
            $.ajax({
                url: base_url + "/erp/job-manager/get-jobcard-id-list",
                type: 'GET',
                data: {
                    selected_customer_id: selected_customer_id,
                    selected_buyer_id:selected_buyer_id
                },
                tryCount: 0,
                retryLimit: 2,
                dataType: "json",
                beforeSend: function () {
                    $(".loader").show();
                },
                statusCode: {
                    200: function(data) {
                        var html = '<option value="">SELECT A JOBCARD ID</option>';
                        /** [ jobcard id list selectpicker value populating ] */
                        $.each(data.jobcard_id_list, function (key, item) {
                            html += '<option value="' + key + '">' + item + '</option>';
                        });
                        $('#jobcard_id_filter').html(html);
                        $('#jobcard_id_filter').selectpicker('refresh');
                    },
                    400: function() {
                        $('.snackbar_span').html("Parameter is missing !!!");
                        showSnackbar();
                    },
               },
                error: function (errorXHR, errorStatus, errorThrown) {
                    this.tryCount++;
                    if (this.tryCount <= this.retryLimit) {
                        $.ajax(this);
                        return;
                    }
                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
        else
        {
            $('#jobcard_id_filter').html('<option value="">SELECT A JOBCARD ID</option>');
            $('#jobcard_id_filter').selectpicker('refresh');
        }
    } catch (e) {
        console.log("Error:"+e)
    }
}
/**
 * Get jobcard list based on buyer,customer and jobcard id selection
 * @param buyer_id, customer_id and jobcard_id
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */

function getJobcardsList()
{
    try {
        let selected_buyer_id    = $('#buyer_filter option:selected').val();
        let selected_customer_id = $('#customer_filter option:selected').val();
        let selected_jobcard_id  = $('#jobcard_id_filter option:selected').val();
        if (selected_customer_id != '') 
        {
            $.ajax({
                url: base_url + "/erp/job-manager/get-jobcards-list",
                type: 'GET',
                data: {
                    selected_customer_id: selected_customer_id,
                    selected_buyer_id:selected_buyer_id,
                    selected_jobcard_id:selected_jobcard_id
                },
                tryCount: 0,
                retryLimit: 2,
                dataType: "json",
                beforeSend: function () {
                    $(".loader").show();
                },
                statusCode: {
                    200: function(data) {
                        var html = '<tr id="'+data.jobcards_list.jobcard_id+'">\
                        <td>1</td>\
                        <td>'+data.jobcards_list.work_order_id+'</td>\
                        <td>'+data.jobcards_list.company_name+'</td>\
                        <td>'+data.jobcards_list.buyer_name+'</td>\
                        <td><a href="#"><i class="fa fa-pencil fa-button edit-jobcard filterbtn options" title="Edit Jobcard" data-placement="left"></i></a></td>\
                        </tr>';
                        $('#jobcard_list_table_body').html(html);            
                    },
                    400: function() {
                        $('.snackbar_span').html("Parameter is missing !!!");
                        showSnackbar();
                    },
               },
                error: function (errorXHR, errorStatus, errorThrown) {
                    this.tryCount++;
                    if (this.tryCount <= this.retryLimit) {
                        $.ajax(this);
                        return;
                    }
                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
        else
        {
            $('#jobcard_id_filter').html('<option value="">SELECT A JOBCARD ID</option>');
            $('#jobcard_id_filter').selectpicker('refresh');
        }
    } catch (e) {
        console.log("Error:"+e)
    }
}

/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */
function openmodaltescopreview(modal, loop, style_no,buyer_no, id, order_type, created_at, isEdit,status,type,template_id,disney,refname) 
{
	console.log("start")
	console.log(loop)
	console.log("end")
	switch(status) {
	  case "print_orchestra":
	    if(type == "hang_tag")
	    {
	    	printHangDivJCPreview('orchestra_modal_front'+ loop,'orchestra_modal_back'+ loop,disney,modal,style_no,buyer_no,id,order_type,created_at,status,"orchestra", isEdit)
	  	}
	  	else if(type == "wash_care")
	  	{
   			printWashDivJCPreview('washcare_front'+ loop,'washcare_back'+ loop,'washcare_middle'+loop,modal,style_no,buyer_no,id,order_type,created_at,status,"orchestra", isEdit)

	  	}
		else if(type == "heat_transfer")
	  	{
   			printHeatTransferJCPreview(style_no,buyer_no,id,order_type,created_at,status,"orchestra", isEdit,template_id,refname)

	  	}
		else if(type == "special_tag")
	  	{
   			printSpecialTagJCPreview(style_no,buyer_no,id,order_type,created_at,status,"orchestra", isEdit,template_id,refname)

	  	}
		else if(type == "main_label")
	  	{
   			printMainLabelJCPreview(style_no,buyer_no,id,order_type,created_at,status,"orchestra", isEdit,template_id,refname)

	  	}
		else if(type == "size_label")
	  	{
   			printSizeLabelJCPreview(style_no,buyer_no,id,order_type,created_at,status,"orchestra", isEdit,template_id,refname)

	  	}	
	  	else
	  	{

	   		genarateArtworkOrchestraJCPreview("orchestra_svg_modal" + loop, modal,style_no,buyer_no,id,order_type,created_at,status,"orchestra", isEdit);
	  
	  	}
		    break;

	  case "print":
			genarateArtworkPreview("tesco_svg_modal"+loop,modal,style_no,buyer_no,id,order_type,created_at, isEdit,type,"tesco_svg_back_modal"+loop,"tesco_svg_back_text_modal"+loop,"tesco_svg_front_modal"+loop);
	    break;

	  default:
	    // code block
	}

}

function printHeatTransferJCPreview(style_no,buyer_no,id,order_type,created_at,status,buyer, isEdit,template_id,refname)
{
	
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/print-heat-transfer",
		type: "post",
		data: {
		   _token: csrf_token,
			style_no:style_no,
			buyer_no:buyer_no,
			id:id,
			template_id:template_id,
			name:refname,
			created_at:created_at,
			db:"orchestra_heat_transfer_orders"
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {
			localStorage.setItem('file_path',localStorage.getItem('file_path')+','+data.file_path);

			// differentiate between edit and create functionality
			if(isEdit === false)
			{
				operation()
			}
			else
			{
				operationEdit()
			}

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}

function  printSpecialTagJCPreview(style_no,buyer_no,id,order_type,created_at,status,buyer, isEdit,template_id,refname)
{
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/print-special-tag",
		type: "post",
		data: {
		   _token: csrf_token,
			style_no:style_no,
			buyer_no:buyer_no,
			id:id,
			template_id:template_id,
			name:refname,
			created_at:created_at,
			db:"orchestra_special_tag_orders"
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {
			localStorage.setItem('file_path',localStorage.getItem('file_path')+','+data.file_path);

			// differentiate between edit and create functionality
			if(isEdit === false)
			{
				operation()
			}
			else
			{
				operationEdit()
			}

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}

function printMainLabelJCPreview(style_no,buyer_no,id,order_type,created_at,status,buyer, isEdit,template_id,refname)
{
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/print-main-label",
		type: "post",
		data: {
		   _token: csrf_token,
			style_no:style_no,
			buyer_no:buyer_no,
			id:id,
			template_id:template_id,
			name:refname,
			created_at:created_at,
			db:"orchestra_main_label_orders"
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {
			localStorage.setItem('file_path',localStorage.getItem('file_path')+','+data.file_path);

			// differentiate between edit and create functionality
			if(isEdit === false)
			{
				operation()
			}
			else
			{
				operationEdit()
			}

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}

function printSizeLabelJCPreview(style_no,buyer_no,id,order_type,created_at,status,buyer, isEdit,template_id,refname)
{
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/print-size-label",
		type: "post",
		data: {
		   _token: csrf_token,
			style_no:style_no,
			buyer_no:buyer_no,
			id:id,
			template_id:template_id,
			name:refname,
			created_at:created_at,
			db:"orchestra_size_label_orders"
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {
			localStorage.setItem('file_path',localStorage.getItem('file_path')+','+data.file_path);

			// differentiate between edit and create functionality
			if(isEdit === false)
			{
				operation()
			}
			else
			{
				operationEdit()
			}

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}

function genarateArtworkPreview(data, name,style_no,buyer_no,id,order_type,created_at, isEdit,table_name,data_ba,data_bate,data_fr) 
{
    var a = document.getElementById(data);
    var svgDoc = a.contentDocument;
    var name2=name;
    var name = name + "-sticker";
    if (name == "TSL1-sticker" || name == "TSL3-sticker") {
    var svgarr = [];
    var ajax_count = 0;
    var b = " ";
    var position = 3;
    var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();

    
        $.ajax({
            url: base_url + "/erp/job-manager/get-order-details",
            type: "Post",
            dataType: "json",
            async: false,
            data: {
                _token: csrf_token,
                ref_name: ref_name,
                style_no: style_no,
                buyer_no: buyer_no,
                id: id,
                created_at: created_at,
                order_type:order_type
            },
            beforeSend: function() {

            },
            success: function(data) {
                $(data.order_details).each(function (key, val) {
                    var svgRoot = svgDoc.documentElement;
                    var zone=val.zone;
                    localStorage.setItem("zone", zone);

                    if ((name == "TSL1-sticker" && zone == "CE")) {
                        svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + val.orms_tpn;
                    } else if ((name == "TSL3-sticker" && zone == "CE")) {
                        svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + val.orms_tpn;
                    } else if ((name == "TSL3-sticker" && zone == "UK")) {
                        svgDoc.getElementById("tpnd").innerHTML = "TPND: " + val.tpnd;
                    } else if ((name == "TSL1-sticker" && zone == "UK")) {
                        svgDoc.getElementById("tpnd").innerHTML = "TPND: " + val.tpnd;
                    }

                    if ((name == "TSL3-sticker")) {
                        if (zone == "CE") {
                            var barcode_change = val.ean_barcode
                        } else {
                            var barcode_change = val.ean_barcode
                        }
                        jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
                        svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
                    } else {
                        if (zone == "CE") {
                            var barcode_change = val.itf_barcode
                        } else {
                            var barcode_change = val.itf_barcode
                        }
                        jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
                        svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

                    }

                    if (zone == "CE") {
                        var size = val.size;
                    } else {
                        var size = val.size;
                    }

                    var description = val.short_description.replace(/&/g,'&#038;');
                    if ((name == "TSL3-sticker")) {
                        if (size != '') {
                            $.ajax({
                                url: base_url + "/erp/job-manager/tesco/get-tsl-size",
                                type: 'GET',
                                async: false,
                                data: {
                                    size: size,
                                    zone: zone
                                },
                                tryCount: 0,
                                retryLimit: 2,
                                success: function (data) {
                                        /** [ Dynamically change svg short description ] */
                                    if (description != '' && data.size_chart.length != 0) {
                                        var str = (description.toUpperCase()) + ' ' + data.size_chart[0].size;

                                        
                                        if (str.length > 24) {
                                            if (/\S/.test(str.charAt(24))) {
                                                //No Space
                                                if (str.indexOf(" ") == "-1") {
                                                    svgDoc.getElementById("description").innerHTML = str.substr(0, 24).toUpperCase();
                                                    svgDoc.getElementById("description1").innerHTML = str.substr(24).toUpperCase();
                                                } else {
                                                    svgDoc.getElementById("description").innerHTML = str.substr(0, str.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                                    svgDoc.getElementById("description1").innerHTML = str.substr(str.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                                }

                                            } else {
                                                //Space
                                                svgDoc.getElementById("description").innerHTML = str.substr(0, 24).toUpperCase();
                                                svgDoc.getElementById("description1").innerHTML = str.substr(24).toUpperCase();
                                            }
                                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
                                        } else {
                                            svgDoc.getElementById("description").innerHTML = str.toUpperCase();
                                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
                                            svgDoc.getElementById("description1").innerHTML = "";
                                        }
                                    } else if (data.size_chart.length != 0) {
                                        svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
                                        svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
                                        svgDoc.getElementById("description1").innerHTML = '';
                                    } else if (description != '') {
                                        if (description.length > 24) {
                                            if (/\S/.test(description.charAt(24))) {
                                                //No Space
                                                if (description.indexOf(" ") == "-1") {
                                                    svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                                    svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                                                } else {
                                                    svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                                    svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                                }

                                            } else {
                                                //Space
                                                svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                                svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                                            }
                                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
                                        } else {
                                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
                                            svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                                            svgDoc.getElementById("description1").innerHTML = "";
                                        }
                                    } else {
                                        svgDoc.getElementById("description").innerHTML = '';
                                        svgDoc.getElementById("description1").innerHTML = '';
                                    }
                                   var s = new XMLSerializer();
                            var d = svgRoot;
                            var str = s.serializeToString(d);

                            svgarr.push(str)
                                },
                                error: function (errorXHR, errorStatus, errorThrown) {
                                    this.tryCount++;
                                    if (this.tryCount <= this.retryLimit) {
                                        $.ajax(this);
                                        return;
                                    }
                                },
                                complete: function () {

                                }
                            })
                        } else {
                            /** [ Dynamically change svg short description ] */
                            if (description != '') {
                                
                                    if (description.length > 24) {
                                        if (/\S/.test(description.charAt(24))) {
                                            //No Space
                                            if (description.indexOf(" ") == "-1") {
                                                svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                                svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                                            } else {
                                                svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                                svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                            }

                                        } else {
                                            //Space
                                            svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                            svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                                        }
                                        svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
                                    } else {
                                        svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                                        svgDoc.getElementById("description1").innerHTML = "";
                                        svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
                                    }
                            } else {
                                svgDoc.getElementById("description").innerHTML = '';
                            }
                            var s = new XMLSerializer();
                            var d = svgRoot;
                            var str = s.serializeToString(d);

                            svgarr.push(str)

                        }
                    } else {
                        var description =val.short_description.replace(/&/g,'&#038;');
                        if (description.length > 24) {
                            if (/\S/.test(description.charAt(24))) {
                                //No Space
                                if (description.indexOf(" ") == "-1") {
                                    svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                    svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                                } else {
                                    svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                    svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
                                }

                            } else {
                                //Space
                                svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
                                svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
                            }
                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
                        } else {
                            svgDoc.getElementById("description").innerHTML = description.toUpperCase();
                            svgDoc.getElementById("description1").innerHTML = "";
                            svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
                        }
                        var s = new XMLSerializer();
                            var d = svgRoot;
                            var str = s.serializeToString(d);

                            svgarr.push(str)
                    }
                    ajax_count++;
                })
                if (ajax_count == data.order_details.length) {
                    var svg_fin = svgarr.join(',_');
                    var zone = localStorage.getItem("zone");
                    PDFTescoSmallPreview(svg_fin, name,zone,style_no,buyer_no,id,created_at, isEdit);
                }
            },
            error: function(data) {

            },
            complete: function() {

            }
        })

} else {

    if(table_name != "tesco_care_labels")
    {
        var svgarr = [];
	var qr_code_arr = [];
    var qr_code_check_arr = [];
    var ajax_count = 0;
    var b = " ";
    var position = 3;
    var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();
    
        $.ajax({
            url: base_url + "/erp/job-manager/get-order-details",
            type: "Post",
            dataType: "json",
            async: false,
            data: {
                _token: csrf_token,
                ref_name: ref_name,
                style_no: style_no,
                buyer_no: buyer_no,
                id: id,
                created_at: created_at,
                order_type:order_type
            },
            beforeSend: function() {

            },
            success: function(data) {
				var generate_data = data;
                $(data.order_details).each(function (key, val) {
                    var zone=val.zone;
                    localStorage.setItem("zone", zone);
                    var svgRoot = svgDoc.documentElement;
                                
                if (val.supplier_name != '') {
                    svgDoc.getElementById("supplier").innerHTML = val.supplier_name.toUpperCase().replace('&', '&amp;');
                } else {
                    svgDoc.getElementById("supplier").innerHTML = "";
                }
                                
                if (val.po_number != '') {
                    svgDoc.getElementById("po_number").innerHTML = val.po_number.toUpperCase();
                } else {
                    svgDoc.getElementById("po_number").innerHTML = '';
                }

                /** [ Dynamically change svg CE style reference number ] */
                if (val.ce_style_ref != '') {
                    svgDoc.getElementById("ce_style").innerHTML = val.ce_style_ref.toUpperCase();
                } else {
                    svgDoc.getElementById("ce_style").innerHTML = '';
                }
                /** [ Dynamically change svg UK style reference number ] */
                if (val.uk_style_ref != '') {
                    svgDoc.getElementById("uk_style").innerHTML = val.uk_style_ref.toUpperCase();
                } else {
                    svgDoc.getElementById("uk_style").innerHTML = '';
                }
                /** [ Dynamically change svg EQOS code ] */
                if (val.eqos_site_code != '') {
                    svgDoc.getElementById("eqos").innerHTML = val.eqos_site_code.toUpperCase();
                } else {
                    svgDoc.getElementById("eqos").innerHTML = '';
                }
                
                            
                                /** [ Dynamically change  svg department ] */
                if (val.department != '') {
                    /** [Give font-size for department] */
                    if (val.department.length < 17) {
                        svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
                    } else {
                        svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
                    }
                    /** [ Dynamically change svg department] */
                    svgDoc.getElementById("department").innerHTML = val.department.toUpperCase().replace('&', '&amp;');
                } else {
                    svgDoc.getElementById("department").innerHTML = '';
                }
                /** [ Dynamically change svg brand ] */
                if (val.brand != '') {
                    /** [Give font-size for brand] */
                    if (val.brand.length < 17) {
                        svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
                    } else {
                        svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
                    }
                    svgDoc.getElementById("brand").innerHTML = val.brand.toUpperCase().replace('&', '&amp;');
                } else {
                    svgDoc.getElementById("brand").innerHTML = '';
                }
                                
                                                
                var tag_at_source = val.tag_at_source == 0 ? "no" : "yes";
                var rfid = val.rfid_compliant == 0 ? "no" : "yes";
                                
                /** [ Dynamically svg RFID ] */
                if (rfid != '') {
                    svgDoc.getElementById("rfid").innerHTML = rfid.toUpperCase();
                } else {
                    svgDoc.getElementById("rfid").innerHTML = '';
                }
                /** [ Dynamically change svg tag at source ] */
                if (tag_at_source != '') {
                    svgDoc.getElementById("tag_at_source").innerHTML = tag_at_source.toUpperCase();
                } else {
                    svgDoc.getElementById("tag_at_source").innerHTML = '';
                }
                /** [ Dynamically change svg tag at source ] */
                if (val.season_code != '') {
                    svgDoc.getElementById("season_code").innerHTML = val.season_code.toUpperCase();
                } else {
                    svgDoc.getElementById("season_code").innerHTML = '';
                }
                /** [ Dynamically change svg manufacture date ] */
                if (val.manufacture_date != '') {
                    svgDoc.getElementById("manufactured_date").innerHTML = val.manufacture_date.toUpperCase();
                } else {
                    svgDoc.getElementById("manufactured_date").innerHTML = '';
                }
                                
                    if ((name == "TCL1-sticker" && zone == "CE") || (name == "TCL2-sticker" && zone == "CE") || (name == "TSL1-sticker" && zone == "CE") || (name == "THL1-sticker" && zone == "CE")) {
                        svgDoc.getElementById("pieces_in_ratio").innerHTML = val.cases_per_carton;

                        svgDoc.getElementById("tpnd").innerHTML = val.orms_tpn;
						if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
						{
							svgDoc.getElementById("tpnd_last_digit").innerHTML = val.orms_tpn.substr(val.orms_tpn.length - 3);
						}
                    } else if ((name == "TCL4-sticker" && zone == "CE") || (name == "THL3-sticker" && zone == "CE")) {
                        svgDoc.getElementById("unit_per_case").innerHTML = val.case_size;

                        svgDoc.getElementById("tpnd").innerHTML =val.orms_tpn;
						if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
						{
							svgDoc.getElementById("tpnd_last_digit").innerHTML = val.orms_tpn.substr(val.orms_tpn.length - 3);
						}
                    } else if ((name == "TCL4-sticker" && zone == "UK") || (name == "THL3-sticker" && zone == "UK")) {
                        svgDoc.getElementById("unit_per_case").innerHTML = val.case_size;

                        svgDoc.getElementById("tpnd").innerHTML = val.tpnd;
                    } else if ((name == "TCL1-sticker" && zone == "UK") || (name == "TCL2-sticker" && zone == "UK") || (name == "TSL1-sticker" && zone == "UK") || (name == "THL1-sticker" && zone == "UK")) {
                        svgDoc.getElementById("pieces_in_ratio").innerHTML = val.units_per_carton;

                        svgDoc.getElementById("tpnd").innerHTML = val.tpnd;
                    }
                    if ((name == "THL3-sticker")) {

                        if (zone == "CE") {
                            var barcode_change = val.ean_barcode
                        } else {
                            var barcode_change = val.ean_barcode
                        }
                        jsbarcodetescoean(barcode_change, 'barcode', svgDoc)
                        svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
                    } else if ((name == "TCL1-sticker") || (name == "THL1-sticker")) {

                        if (zone == "CE") {
                            var barcode_change = val.itf_barcode
                        } else {
                            var barcode_change = val.itf_barcode
                        }
                        jsbarcodetesco(barcode_change, 'barcode', svgDoc)
                        svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

                    }

                    if (zone == "CE") {
                        var size = val.size;
                    } else {
                        var size = val.size;
                    }

                    var description = val.short_description.replace(/&/g,'&#038;');
                    if ((name == "TCL4-sticker") || (name == "TSL3-sticker") || (name == "THL3-sticker")) {
                        if (size != '') {
                            $.ajax({
                                url: base_url + "/erp/job-manager/tesco/get-tsl-size",
                                type: 'GET',
                                async: false,
                                data: {
                                    size: size,
                                    zone: zone
                                },
                                tryCount: 0,
                                retryLimit: 2,
                                success: function (data) {
                                    /** [ Dynamically change svg short description ] */
                                    if (description != '' && data.size_chart.length != 0) {
								var desc_size = (description.toUpperCase()) + ' ' + data.size_chart[0].size;
								if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = oswordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
							} else if (description != '') {
								var desc_size = description.toUpperCase();
								
								if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = oswordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
								
							} else {
								svgDoc.getElementById("description").innerHTML = '';
							}
                                    var s = new XMLSerializer();
                                    var d = svgRoot;
                                    var str = s.serializeToString(d);
                                    svgarr.push(str)
									qr_code_arr.push(val.qr_code)
                                    qr_code_check_arr.push(val.buyer_po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)
                                },
                                error: function (errorXHR, errorStatus, errorThrown) {
                                    this.tryCount++;
                                    if (this.tryCount <= this.retryLimit) {
                                        $.ajax(this);
                                        return;
                                    }
                                },
                                complete: function () {

                                }
                            })
                        } else {
                            /** [ Dynamically change svg short description ] */
                           if (description != '') {
							if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
							{
								var desc_size = description.toUpperCase();
								if(desc_size.length <= 22)
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
									if(name2 === "THL3")
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");

									}
									else
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 150)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
									}
								}
								else
								{
									str = wordWrap(desc_size, 22);
									svgDoc.getElementById("description").innerHTML = str;

									if(name2 === "THL3")
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");

									}
									else
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 163)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
									}
								}
							}
							else
							{
								/** [ Dynamically change svg short description ] */
								svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							}					
						} else {
							svgDoc.getElementById("description").innerHTML = '';
						}
                            var s = new XMLSerializer();
                            var d = svgRoot;
                            var str = s.serializeToString(d);

                            svgarr.push(str)
							qr_code_arr.push(val.qr_code)
                            qr_code_check_arr.push(val.buyer_po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)

                        }
                    } else {
                       if (description != '') {
					if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
					{
						if(description.length <= 20)
						{
							svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							if (name2 === "TCL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 125)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 137)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 125)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 137)");
							}
							else if (name2 === "TCL2")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.87, 0, 0, 1, 91.1544, 150)");
								svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
							}
							else if(name2 === "THL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 125)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 137)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 125)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 137)");
							}
							
						}
						else
						{
							str = oswordWrap(description, 23);
							svgDoc.getElementById("description").innerHTML = str;
							if (name2 === "TCL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 133)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 133)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 146)");
							}
							else if (name2 === "TCL2")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
								svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.87, 0, 0, 1, 91.1544, 163)");
								svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
							}
							else if(name2 === "THL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
							}
						}
					}
					else
					{
						/** [ Dynamically change svg short description ] */
						svgDoc.getElementById("description").innerHTML = description.toUpperCase();
					}					
				} else {
					svgDoc.getElementById("description").innerHTML = '';
				}
                        var s = new XMLSerializer();
                        var d = svgRoot;
                        var str = s.serializeToString(d);

                        svgarr.push(str)
						qr_code_arr.push(val.qr_code)
                        qr_code_check_arr.push(val.buyer_po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)
                    }
                    ajax_count++;
                })
                if (ajax_count == data.order_details.length) {
                    var svg_fin = svgarr.join(',_');
					var qr_code_fin = qr_code_arr.join(',_');
                    var qr_code_check_fin = qr_code_check_arr.join(',_');
                    var zone = localStorage.getItem("zone");
                    PDFTescoPreview(svg_fin, name,zone,style_no,buyer_no,id,created_at, isEdit,generate_data.tesco_item_master[0].is_qrcode_required,qr_code_fin,qr_code_check_fin);
                }
            },
            error: function(data) {

            },
            complete: function() {

            }
        });
    }
    else
    {
        console.log("start:"+id)
            console.log(id)
            var a_back = document.getElementById(data_ba);
    var svgDoc_back = a_back.contentDocument;

    var a_back_text = document.getElementById(data_bate);
    var svgDoc_back_text = a_back_text.contentDocument;

    var a_front_text = document.getElementById(data_fr);
    var svgDoc_front_text = a_front_text.contentDocument;
    
            var svgarr = [];
            var svgarr_back = [];
            var svgarr_back_text = [];
            var svgarr_front = [];
            ajax_count=0;
            $.ajax({
				url: base_url + "/erp/job-manager/get-order-details",
                type: "Post",
                dataType: "json",
                data: {
                    _token: csrf_token,
                    name: name2,
                    id: id,
					table_name_print:"tesco_washcare_orders",
					ref_name: name2,
					style_no: style_no,
					buyer_no: buyer_no,
					created_at: created_at,
					order_type:order_type
                },
				beforeSend: function() {

				},
				success: function(data) {
                    
                    var svgRoot_back =svgDoc_back.documentElement;
                    var svgRoot_back_text =svgDoc_back_text.documentElement;
                    var svgRoot_front_text =svgDoc_front_text.documentElement;

                    if(data.order_details[0].bs_en_declaration == 0)
                    {
                        console.log("hai")
                        svgDoc_front_text.getElementById("sleeping_bag_name").setAttribute('style', "visibility:hidden");
                    }

                    if(data.order_details[0].baby_sleeping_bag_symbol == 0)
                    {
                        svgDoc_front_text.getElementById("tog_image").setAttribute('style', "visibility:hidden");
                    }

                    if(data.order_details[0].tog == "" || data.order_details[0].tog == null)
                    {
                        svgDoc_front_text.getElementById("tog_name").setAttribute('style', "visibility:hidden");
                    }

                    $(data.order_details).each(function (key, val) {
                        svgDoc.getElementById("po_value").innerHTML = "PO:"+val.po_number;
                        svgDoc.getElementById("sn_value").innerHTML = "SN:"+val.eqos_site_code;
                        svgDoc.getElementById("style_value").innerHTML = "STYLE:"+ val.uk_style_ref;
                        if(val.fabric_batch_code != null && val.fabric_batch_code != "")
                        {
                            svgDoc.getElementById("batch_code").innerHTML = val.fabric_batch_code;
                        }
                        else
                        {
                            //alert()
                            svgDoc.getElementById("po_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 103)");
                            svgDoc.getElementById("sn_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 110)");
                            svgDoc.getElementById("style_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 117)");
                            svgDoc.getElementById("batch_code").innerHTML = "";
                        }

                        if(val.internal_reference != "" && val.internal_reference != null)
                        {
                            svgDoc.getElementById("packaging_reference_number").innerHTML = val.internal_reference;
                        }
                        else
                        {
                            svgDoc.getElementById("packaging_reference_number").innerHTML = "";
                            svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 6.7049, 160.7462)");
                            
                        }
                        
                        if(val.tog != "" && val.tog != null)
                        {
                            svgDoc.getElementById("tog_value_1_").innerHTML = val.tog;
                        }
                        else
                        {
                            //console.log("hai")
                            svgDoc.getElementById("tog_value_1_").innerHTML = "";
                        }


                        var selected_secondary_sizes = JSON.parse(val.selected_secondary_sizes)
                        var selected_secondary_sizes_text = "";
                        console.log(selected_secondary_sizes)
                        $size_y=0;
                        $(selected_secondary_sizes).each(function(index,selected_secondary_sizes_val){
                            if(selected_secondary_sizes_val['id'] != "" && selected_secondary_sizes_val['id'] != null)
                            {
                                selected_secondary_sizes_text += '<tspan x="10" y="'+$size_y+'">'+selected_secondary_sizes_val['value']+'</tspan>'
                                $size_y += 6;
                            }
                        })
                        svgDoc.getElementById("height_text").innerHTML = selected_secondary_sizes_text;

                        


                        svgDoc.getElementById("uk_ean_barcode").innerHTML = val.uk_ean;
                        svgDoc.getElementById("ce_ean_barcode").innerHTML = val.ce_ean;
                        
                        if(val.uk_size == "tiny baby")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").innerHTML = "tiny baby";

                            var polish = data.tesco_tiny[0].polish
                            var czech = data.tesco_tiny[0].czech
                            var slovak = data.tesco_tiny[0].slovak
                            var hungarian = data.tesco_tiny[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish+" • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "Newborn")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").innerHTML = "Newborn";

                            var polish = data.tesco_nb[0].polish
                            var czech = data.tesco_nb[0].czech
                            var slovak = data.tesco_nb[0].slovak
                            var hungarian = data.tesco_nb[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish+" • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "up to 1M")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").innerHTML = "up to 1M";
                            
                            var polish = data.tesco_up1[0].polish
                            var czech = data.tesco_up1[0].czech
                            var slovak = data.tesco_up1[0].slovak
                            var hungarian = data.tesco_up1[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish+" • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "up to 3M")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").innerHTML = "up to 3M";
                            
                            var polish = data.tesco_up3[0].polish
                            var czech = data.tesco_up3[0].czech
                            var slovak = data.tesco_up3[0].slovak
                            var hungarian = data.tesco_up3[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish+" • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "One size")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").innerHTML = "One size";
                            
                            var polish = data.tesco_onesize[0].polish
                            var czech = data.tesco_onesize[0].czech.split(' ')
                            var slovak = data.tesco_onesize[0].slovak
                            var hungarian = data.tesco_onesize[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish+" • "+czech[0];
                            svgDoc.getElementById("small_translation2").innerHTML =czech[1]+ " • "+slovak+ " • "+hungarian;
                        }
                        else
                        {
                            svgDoc.getElementById("uk_size").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").removeAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                            svgDoc.getElementById("ce_size").innerHTML = val.ce_size;
                        }
                        svgDoc.getElementById("ce_style_number").innerHTML = val.ce_style_ref;
                        var country_of_origin_text = JSON.parse(val.country_of_origin_text)
                        svgDoc.getElementById("made_in_english").innerHTML = country_of_origin_text[0].english;
                        var svgRoot = svgDoc.documentElement;
                        var s = new XMLSerializer();
                        var d = svgRoot;
                        var str = s.serializeToString(d);
                        var svg = encodeURI(str)
                        svgarr.push(svg)
                        ajax_count++;
                    })

                    var country_of_origin_text = JSON.parse(data.order_details[0].country_of_origin_text)
                    svgDoc_back_text.getElementById("wyprod_text").innerHTML = '<tspan  x="2" y="0">'+country_of_origin_text[0].polish+'</tspan><tspan   x="2.53" y="5.94">'+country_of_origin_text[0].czech+'</tspan><tspan   x="2.53" y="12.88">'+country_of_origin_text[0].slovak+'</tspan><tspan  x="2" y="18.82">'+country_of_origin_text[0].hungarian+'</tspan>';

                    // svgDoc_back.getElementById("wash_iso").setAttribute('xlink:href',data.order_details[0].wash_iso);
                    // svgDoc_back.getElementById("clean_iso").setAttribute('xlink:href',data.order_details[0].clean_iso);
                    // svgDoc_back.getElementById("whit_iso").setAttribute('xlink:href',data.order_details[0].whit_iso);
                    // svgDoc_back.getElementById("iron_iso").setAttribute('xlink:href',data.order_details[0].iron_iso);
                    // svgDoc_back.getElementById("dry_iso").setAttribute('xlink:href',data.order_details[0].dry_iso);
                    // svgDoc_back.getElementById("wash_astm").setAttribute('xlink:href',data.order_details[0].wash_astm);
                    // svgDoc_back.getElementById("dry_astm").setAttribute('xlink:href',data.order_details[0].dry_astm);
                    // svgDoc_back.getElementById("whit_astm").setAttribute('xlink:href',data.order_details[0].whit_astm);
                    // svgDoc_back.getElementById("iron_astm").setAttribute('xlink:href',data.order_details[0].iron_astm);
                    // svgDoc_back.getElementById("clean_astm").setAttribute('xlink:href',data.order_details[0].clean_astm);

                    var additional_care_text_1 = JSON.parse(data.order_details[0].additional_care_text_1)
                    var additional_care_text_2 = JSON.parse(data.order_details[0].additional_care_text_2)
                    var additional_care_text_3 = JSON.parse(data.order_details[0].additional_care_text_3)
                    var degree =data.order_details[0].degree

                    var en_care_instructions = "";
                    if(additional_care_text_1 != "" && additional_care_text_1 != null)
                    {
                        en_care_instructions += additional_care_text_1[0].english
                    }
                    if(additional_care_text_2 != "" && additional_care_text_2 != null)
                    {
                        en_care_instructions += " / " + additional_care_text_2[0].english
                    }
                    if(additional_care_text_3 != "" && additional_care_text_3 != null)
                    {
                        en_care_instructions += " / " + additional_care_text_3[0].english
                    }
                    
                    if(degree >= 30)
                    {
                        en_care_instructions += " / " + data.degree_text[0].english
                    }
                    
                    $chunk_str = chunkSubstr(en_care_instructions, 28).split(/\r?\n/);

                    //console.log($chunk_str);
                    var chunks='<tspan  x="0" y="0">EN</tspan>';
                    $y=0;
                    $ind=1;
                    console.log("gy:"+$chunk_str.length)
                    $($chunk_str).each(function(index,chunk_val){
                        chunks += '<tspan  x="12" y="'+$y+'">'+chunk_val+'</tspan>';
                        if($chunk_str.length == $ind)
                        {
                            $y+=6;
                        }
                        else
                        {
                            $y+=6;
                        }
                        $ind++;
                        
                    })

                    var en_garment_component = "";
                    var pl_garment_component = "";
                    var cz_garment_component = "";
                    var sk_garment_component = "";
                    var hu_garment_component = "";
                    $(data.component).each(function(index,comp_val){
                        
                        var garment_component_text = JSON.parse(comp_val.garment_component_text)
                        if(index != 0)
                        {
                            en_garment_component += " / " +garment_component_text[0].english;
                            pl_garment_component += " / " +garment_component_text[0].polish;
                            cz_garment_component += " / " +garment_component_text[0].czech;
                            sk_garment_component += " / " +garment_component_text[0].slovak;
                            hu_garment_component += " / " +garment_component_text[0].hungarian;
                        }
                        else
                        {
                            en_garment_component += garment_component_text[0].english;
                            pl_garment_component += garment_component_text[0].polish;
                            cz_garment_component += garment_component_text[0].czech;
                            sk_garment_component += garment_component_text[0].slovak;
                            hu_garment_component += garment_component_text[0].hungarian;
                        }
                        
                        $(comp_val.fibre).each(function(index,fibre_val){
                            var fibre_text = JSON.parse(fibre_val.fibre_composition_text)
                            en_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].english;

                            pl_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].polish;

                            cz_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].czech;

                            sk_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].slovak;

                            hu_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].hungarian;
                        });
                    })

                    var exclusive_trims_text = JSON.parse(data.order_details[0].exclusive_trims_text)
                    var contains_non_textile_part_text = JSON.parse(data.order_details[0].contains_non_textile_part_text)
                    var kids_warning_text = JSON.parse(data.order_details[0].kids_warning_text)
                    var part_of_set_text = JSON.parse(data.order_details[0].part_of_set_text)
                    

                    if(exclusive_trims_text != "" && exclusive_trims_text != null)
                    {
                        en_garment_component += " / " + exclusive_trims_text[0].english
                        pl_garment_component += " / " + exclusive_trims_text[0].polish
                        cz_garment_component += " / " + exclusive_trims_text[0].czech
                        sk_garment_component += " / " + exclusive_trims_text[0].slovak
                        hu_garment_component += " / " + exclusive_trims_text[0].hungarian
                    }

                    if(contains_non_textile_part_text != "" && contains_non_textile_part_text != null)
                    {
                        en_garment_component += " / " + contains_non_textile_part_text[0].english
                        pl_garment_component += " / " + contains_non_textile_part_text[0].polish
                        cz_garment_component += " / " + contains_non_textile_part_text[0].czech
                        sk_garment_component += " / " + contains_non_textile_part_text[0].slovak
                        hu_garment_component += " / " + contains_non_textile_part_text[0].hungarian
                    }

                    if(kids_warning_text != "" && kids_warning_text != null)
                    {
                        en_garment_component += " / " + kids_warning_text[0].english
                        pl_garment_component += " / " + kids_warning_text[0].polish
                        cz_garment_component += " / " + kids_warning_text[0].czech
                        sk_garment_component += " / " + kids_warning_text[0].slovak
                        hu_garment_component += " / " + kids_warning_text[0].hungarian
                    }

                    if(additional_care_text_1 != "" && additional_care_text_1 != null)
                    {
                        console.log("Polish : "+additional_care_text_1[0].polish)
                        pl_garment_component += " / " + additional_care_text_1[0].polish
                        cz_garment_component += " / " + additional_care_text_1[0].czech
                        sk_garment_component += " / " + additional_care_text_1[0].slovak
                        hu_garment_component += " / " + additional_care_text_1[0].hungarian
                    }
                    if(additional_care_text_2 != "" && additional_care_text_2 != null)
                    {
                        pl_garment_component += " / " + additional_care_text_2[0].polish
                        cz_garment_component += " / " + additional_care_text_2[0].czech
                        sk_garment_component += " / " + additional_care_text_2[0].slovak
                        hu_garment_component += " / " + additional_care_text_2[0].hungarian
                    }
                    if(additional_care_text_3 != "" && additional_care_text_3 != null)
                    {
                        pl_garment_component += " / " + additional_care_text_3[0].polish
                        cz_garment_component += " / " + additional_care_text_3[0].czech
                        sk_garment_component += " / " + additional_care_text_3[0].slovak
                        hu_garment_component += " / " + additional_care_text_3[0].hungarian
                    }

                    if(part_of_set_text != "" && part_of_set_text != null)
                    {
                        en_garment_component += " / " + part_of_set_text[0].english
                        pl_garment_component += " / " + part_of_set_text[0].polish
                        cz_garment_component += " / " + part_of_set_text[0].czech
                        sk_garment_component += " / " + part_of_set_text[0].slovak
                        hu_garment_component += " / " + part_of_set_text[0].hungarian
                    }

                    if(degree >= 30)
                    {
                        pl_garment_component += " / " + data.degree_text[0].polish
                        cz_garment_component += " / " + data.degree_text[0].czech
                        sk_garment_component += " / " + data.degree_text[0].slovak
                        hu_garment_component += " / " + data.degree_text[0].hungarian
                    }

                    $garment_str = chunkSubstr(en_garment_component, 28).split(/\r?\n/);
                    $pl_garment_str = chunkSubstr(pl_garment_component, 28).split(/\r?\n/);
                    $cz_garment_str = chunkSubstr(cz_garment_component, 28).split(/\r?\n/);
                    $sk_garment_str = chunkSubstr(sk_garment_component, 28).split(/\r?\n/);
                    $hu_garment_str = chunkSubstr(hu_garment_component, 28).split(/\r?\n/);

                    chunks += '<tspan x="0" y="'+$y+'">EN</tspan>';

                    $ind_en=1;
                    $z=0;
                    $($garment_str).each(function(index,garment_val){
                        chunks += '<tspan x="12" y="'+$y+'">'+garment_val+'</tspan>';
                        if($garment_str.length == $ind_en)
                        {
                            $y+=6;
                        }
                        else
                        {
                            $y+=6;
                        }
                        $ind_en++;
                    })

                    chunks += '<tspan x="0" y="'+$y+'">PL</tspan>';
                    var new_chunks = '';
                    $($pl_garment_str).each(function(index,pl_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            new_chunks += '<tspan x="12" y="'+$z+'">'+pl_garment_val+'</tspan>';
                        }
                        else
                        {
                            chunks += '<tspan x="12" y="'+$y+'">'+pl_garment_val+'</tspan>';   
                        }
                        
                        $y+=6;
                        $z+=6;
                    })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">CZ</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">CZ</tspan>';
                    }

                    $($cz_garment_str).each(function(index,cz_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            new_chunks += '<tspan x="12" y="'+$z+'">'+cz_garment_val+'</tspan>';
                        }
                        else
                        {
                            chunks += '<tspan x="12" y="'+$y+'">'+cz_garment_val+'</tspan>';   
                        }
                        
                        $y+=6;
                        $z+=6;
                    })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">SK</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">SK</tspan>';
                    }

                    $($sk_garment_str).each(function(index,sk_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            new_chunks += '<tspan x="12" y="'+$z+'">'+sk_garment_val+'</tspan>';
                        }
                        else
                        {
                            chunks += '<tspan x="12" y="'+$y+'">'+sk_garment_val+'</tspan>';   
                        }
                        
                        $y+=6;
                        $z+=6;
                    })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">HU</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">HU</tspan>';
                    }

                    $($hu_garment_str).each(function(index,hu_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            new_chunks += '<tspan x="12" y="'+$z+'">'+hu_garment_val+'</tspan>';
                        }
                        else
                        {
                            chunks += '<tspan x="12" y="'+$y+'">'+hu_garment_val+'</tspan>';   
                        }
                        
                        $y+=6;
                        $z+=6;
                    })
                    
                    svgDoc_back_text.getElementById("wash_text").innerHTML = chunks;
                    svgDoc_back_text.getElementById("wash_text_2").innerHTML = new_chunks;


                    
                    // console.log(en_care_instructions)
                    // console.log(en_care_instructions)

                    var s_front = new XMLSerializer();
                    var d_front = svgRoot_front_text;
                    var str_front = s_front.serializeToString(d_front);
                    var svg_front = encodeURI(str_front)
                    svgarr_front.push(svg_front)
                    
                    var s_back = new XMLSerializer();
                    var d_back = svgRoot_back;
                    var str_back = s_back.serializeToString(d_back);
                    var svg_back = encodeURI(str_back)
                    svgarr_back.push(svg_back)

                    var s_back_text = new XMLSerializer();
                    var d_back_text = svgRoot_back_text;
                    var str_back_text = s_back_text.serializeToString(d_back_text);
                    var svg_back_text = encodeURI(str_back_text)
                    svgarr_back_text.push(svg_back_text)
                   
                    if (ajax_count == data.order_details.length) {
                        var svg_fin = svgarr.join(',_');
                        var svg_fin_back = svgarr_back.join(',_');
                        var svg_fin_back_text = svgarr_back_text.join(',_');
                        var svg_fin_front = svgarr_front.join(',_');
                        PopupTescoLabelsPrieview(svg_fin,svg_fin_back,svg_fin_back_text,svg_fin_front,data.order_details[0].po_number,data.order_details[0].uk_style_ref+"/"+data.order_details[0].ce_style_ref,data.tesco_item_master[0].item_name,data.order_details[0].created_at,data.order_details[0].customer_name,isEdit,data.order_details[0].wash_astm,data.order_details[0].wash_iso,data.order_details[0].whit_astm,data.order_details[0].whit_iso,data.order_details[0].dry_astm,data.order_details[0].dry_iso,data.order_details[0].iron_astm,data.order_details[0].iron_iso,data.order_details[0].clean_astm,data.order_details[0].clean_iso);
                    }
				
				},
				error: function(data) {

				},
				complete: function() {

				}
			});
    }
    
    
}


}



function PopupTescoLabelsPrieview(data_front,data_back,data_back_text,data_front_image,po_number,style,template_name,created_at,customer_name,isEdit,wash_astm,wash_iso,whit_astm,whit_iso,dry_astm,dry_iso,iron_astm,iron_iso,clean_astm,clean_iso) {
	
	var url = "";
	
	$.ajax({
		url: base_url + "/erp/job-manager/tesco/proof-div-tescolabelos",
		type: "post",
		data: {
			_token: csrf_token,
			data_front: data_front,
            data_back: data_back,
            data_back_text: data_back_text,
            data_front_image: data_front_image,
            tesco_po_number:po_number,
            style:style,
            template_name:template_name,
            created_date:created_at,
            customer_name:customer_name,
            wash_astm:wash_astm,
            wash_iso:wash_iso,
            whit_astm:whit_astm,
            whit_iso:whit_iso,
            dry_astm:dry_astm,
            dry_iso:dry_iso,
            iron_astm:iron_astm,
            iron_iso:iron_iso,
            clean_astm:clean_astm,
            clean_iso:clean_iso
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {

			localStorage.setItem('file_path',localStorage.getItem('file_path')+','+data.file_path);

            // differentiate between edit and create functionality
            if(isEdit === false)
            {
                operation()
            }
            else
            {
                operationEdit()
            }

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}
 
/** Get order details for artwork attachment ( while edit jobcard functionality ) */



/**
* This Function is Sub Function of #printDivTesco.
*  For print preview of stickers
* @return Pdf
*/

function PDFTescoPreview(data, name,zone,style_no,buyer_no,id,created_at, isEdit,qr_flag,qr_code_fin,qr_code_check_fin) {
	if(name == "TCL1-sticker")
	{
		var db = "tesco_tcl1_orders";
	}
	else if(name == "TCL2-sticker")
	{
		var db = "tesco_tcl2_orders";
	}
	else if(name == "TCL4-sticker")
	{
		var db = "tesco_tcl4_orders";
	}
	else if(name == "THL1-sticker")
	{
		var db = "tesco_thl1_orders";
	}
	else if(name == "THL3-sticker")
	{
		var db = "tesco_thl3_orders";
	}
$.ajax({
    url: base_url + "/erp/job-manager/tesco/print-div-tesco",
    type: "post",
    data: {
       _token: csrf_token,
		svg: data,
		name:name,
		zone:zone,
		style:style_no,
        qr_code_fin: qr_code_fin,
        qr_code_check_fin:qr_code_check_fin,
		tesco_po_number:buyer_no,
		id:id,
		created_at:created_at,
		qr_flag:qr_flag,
		db:db
    },
    tryCount: 0,
    retryLimit: 2,
    async: false,
    success: function (data) {

        localStorage.setItem('file_path',localStorage.getItem('file_path')+','+data.file_path);

        // differentiate between edit and create functionality
        if(isEdit === false)
        {
            operation()
        }
        else
        {
            operationEdit()
        }

    },
    error: function (errorXHR, errorStatus, errorThrown) {
        this.tryCount++;
        if (this.tryCount <= this.retryLimit) {
            $.ajax(this);
            return;
        }
    },
})
}
/**
* This Function is Sub Function of #printDivTesco.
*  For print preview of small stickers
* @return Pdf
*/

function PDFTescoSmallPreview(data, name,zone,style_no,buyer_no,id,created_at, isEdit) {
	
	if(name == "TCL1-sticker")
	{
		var db = "tesco_tcl1_orders";
	}
	else if(name == "TCL2-sticker")
	{
		var db = "tesco_tcl2_orders";
	}
	else if(name == "TCL4-sticker")
	{
		var db = "tesco_tcl4_orders";
	}
	else if(name == "THL1-sticker")
	{
		var db = "tesco_thl1_orders";
	}
	else if(name == "THL3-sticker")
	{
		var db = "tesco_thl3_orders";
	}
	
$.ajax({
    url: base_url + "/erp/job-manager/tesco/print-div-tesco-small",
    type: "post",
    data: {
       _token: csrf_token,
		svg: data,
		name:name,
		zone:zone,
		style:style_no,
		tesco_po_number:buyer_no,
		id:id,
		created_at:created_at,
		db:db
    },
    tryCount: 0,
    retryLimit: 2,
    async: false,
    success: function (data) {
        localStorage.setItem('file_path',localStorage.getItem('file_path')+','+data.file_path);
        
        // differentiate between edit and create functionality
        if(isEdit === false)
        {
            operation()
        }
        else
        {
            operationEdit()
        }

    },
    error: function (errorXHR, errorStatus, errorThrown) {
        this.tryCount++;
        if (this.tryCount <= this.retryLimit) {
            $.ajax(this);
            return;
        }
    },
})
}


/**
* This Function is used to invoke JSBarcode.
* 
* @return barcode
*/
function jsbarcodetesco(code, id, svgDoc) {

JsBarcode("#ean-13", code, {
    format: "ITF14",
    width: 3.28148,
    height: 202,
    fontSize: 30,
    flat: true,
    displayValue: false
});
var svg = document.getElementById('svgdiv').querySelector('svg'),
    canvas = document.createElement('canvas'),
    body = document.getElementsByTagName('body')[0],
    canvasTmp,
    svg_xml,
    ctx,
    img;
// if our "svg" doesn't have 'xmlns' attribute we must add it
//    svg.setAttribute('width', '188.931023622px');
svg.setAttribute('font-size', '30px');
svg.setAttribute('font-family', 'frankgothcnbt');

svg.setAttribute('width', '408.900px');
svg.setAttribute('height', '328.02px');



//svg.removeAttribute('version');
//svg.removeAttribute('viewBox');
//	svg.removeAttribute('x');
//	svg.removeAttribute('y');
//svg.setAttribute('viewBox','0 ,0 ,463 ,222');

canvas.id = 'canvas';
canvas.width = svg.getBoundingClientRect().width;
canvas.height = svg.getBoundingClientRect().height;

body.appendChild(canvas);
canvasTmp = document.getElementById('canvas');

/* Time to play ;) */

// https://developer.mozilla.org/en/XMLSerializer
svg_xml = (new XMLSerializer()).serializeToString(svg);
ctx = canvasTmp.getContext('2d');

// JavaScript (HTML) image
img = new Image();

img.onload = function () {
    ctx.drawImage(img, 0, 0);
};

// img.src - contains base64 URI from svg
// https://developer.mozilla.org/en/DOM/window.btoa
img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);



//remove temporary canvas
canvasTmp.remove();
svgDoc.getElementById(id).innerHTML = svg_xml;
}

/**
* This Function is used to invoke Ean 13 JSBarcode.
* 
* @return barcode
*/
function jsbarcodetescoean(code, id, svgDoc) {
JsBarcode("#ean-13", code, {
    format: "ean13",
    width: 3.9260,
    height: 170,
    fontSize: 30,
    flat: true,
    displayValue: false
});
var svg = document.getElementById('svgdiv').querySelector('svg'),
    canvas = document.createElement('canvas'),
    body = document.getElementsByTagName('body')[0],
    canvasTmp,
    svg_xml,
    ctx,
    img;
// if our "svg" doesn't have 'xmlns' attribute we must add it
//    svg.setAttribute('width', '188.931023622px');
svg.setAttribute('font-size', '50px');
svg.setAttribute('font-family', 'frankgothcnbt');

svg.setAttribute('width', '412.25px');
svg.setAttribute('height', '378.02px');

canvas.id = 'canvas';
canvas.width = svg.getBoundingClientRect().width;
canvas.height = svg.getBoundingClientRect().height;

body.appendChild(canvas);
canvasTmp = document.getElementById('canvas');

/* Time to play ;) */

// https://developer.mozilla.org/en/XMLSerializer
svg_xml = (new XMLSerializer()).serializeToString(svg);
ctx = canvasTmp.getContext('2d');

// JavaScript (HTML) image
img = new Image();

img.onload = function () {
    ctx.drawImage(img, 0, 0);
};

// img.src - contains base64 URI from svg
// https://developer.mozilla.org/en/DOM/window.btoa
img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);


//remove temporary canvas
canvasTmp.remove();

svgDoc.getElementById(id).innerHTML = svg_xml;
}
/**
* This Function is used to invoke Ean 13 JSBarcode.
* 
* @return barcode
*/
function jsbarcodetescotslean(code, id, svgDoc) {
JsBarcode("#ean-13", code, {
    format: "ean13",
    width: 3.9260,
    height: 167,
    fontSize: 30,
    flat: true,
    displayValue: false
});
var svg = document.getElementById('svgdiv').querySelector('svg'),
    canvas = document.createElement('canvas'),
    body = document.getElementsByTagName('body')[0],
    canvasTmp,
    svg_xml,
    ctx,
    img;
// if our "svg" doesn't have 'xmlns' attribute we must add it
//    svg.setAttribute('width', '188.931023622px');
svg.setAttribute('font-size', '50px');
svg.setAttribute('font-family', 'frankgothcnbt');

svg.setAttribute('width', '419.075px');
svg.setAttribute('height', '275.02px');

canvas.id = 'canvas';
canvas.width = svg.getBoundingClientRect().width;
canvas.height = svg.getBoundingClientRect().height;

body.appendChild(canvas);
canvasTmp = document.getElementById('canvas');

/* Time to play ;) */

// https://developer.mozilla.org/en/XMLSerializer
svg_xml = (new XMLSerializer()).serializeToString(svg);
ctx = canvasTmp.getContext('2d');

// JavaScript (HTML) image
img = new Image();

img.onload = function () {
    ctx.drawImage(img, 0, 0);
};

// img.src - contains base64 URI from svg
// https://developer.mozilla.org/en/DOM/window.btoa
img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);


//remove temporary canvas
canvasTmp.remove();

svgDoc.getElementById(id).innerHTML = svg_xml;
}

/**
* This Function is used to invoke JSBarcode.
* 
* @return barcode 
*/


function jsbarcodetescotsl(code, id, svgDoc) {
JsBarcode("#ean-13", code, {
    format: "ITF14",
    width: 3.28148,
    height: 199,
    fontSize: 30,
    flat: true,
    displayValue: false
});
var svg = document.getElementById('svgdiv').querySelector('svg'),
    canvas = document.createElement('canvas'),
    body = document.getElementsByTagName('body')[0],
    canvasTmp,
    svg_xml,
    ctx,
    img;
// if our "svg" doesn't have 'xmlns' attribute we must add it
//    svg.setAttribute('width', '188.931023622px');
svg.setAttribute('font-size', '30px');
svg.setAttribute('font-family', 'frankgothcnbt');

svg.setAttribute('width', '415.100px');
svg.setAttribute('height', '328.02px');

canvas.id = 'canvas';
canvas.width = svg.getBoundingClientRect().width;
canvas.height = svg.getBoundingClientRect().height;
body.appendChild(canvas);
canvasTmp = document.getElementById('canvas');
/* Time to play ;) */
// https://developer.mozilla.org/en/XMLSerializer
svg_xml = (new XMLSerializer()).serializeToString(svg);
ctx = canvasTmp.getContext('2d');
// JavaScript (HTML) image
img = new Image();
img.onload = function () {
    ctx.drawImage(img, 0, 0);
};

// img.src - contains base64 URI from svg
// https://developer.mozilla.org/en/DOM/window.btoa
img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

//remove temporary canvas
canvasTmp.remove();

svgDoc.getElementById(id).innerHTML = svg_xml;
}
/**
 * Check if loop execution completed or not (for create jobcard)
 * @author praveen
 */
var operationsCompleted = 0;
function operation() {
    ++operationsCompleted;
    if (operationsCompleted === $('[class="row-checkbox"]:checked').length) after_forloop(); 
}

/**
 * Check if loop execution completed or not(for edit jobcard)
 * @author praveen
 */
var isOperationsCompleted = 0;
function operationEdit() {
    ++isOperationsCompleted;
    if (isOperationsCompleted === $('.edit-erp-details').length) after_forloop(); 
}

/**
 * Call after loop execution completed
 * @author praveen
 */
function after_forloop()
{
    $.ajax({
        url: base_url + "/erp/job-manager/pdf-merge",
        type: 'post',
        data: {
            _token: csrf_token,
            path:localStorage.getItem('file_path')
        },
        tryCount: 0,
        retryLimit: 2,
        async: false,
        dataType: "json",
        beforeSend: function () {
            $(".loader").show();
        },
        statusCode: {
            200: function(data) {     
                var resp = JSON.stringify(data.file_path);
                $('.snackbar_span').html("Jobcard Created Successfully");
                showSnackbar();
                $('.modal').modal('hide');
                location.reload();
                window.open(
                    base_url+JSON.parse(resp),
                    '_blank' // <- This is what makes it open in a new window.
                );   
            },
            400: function() {
              
            },
       },
        error: function (errorXHR, errorStatus, errorThrown) {
            this.tryCount++;
            if (this.tryCount <= this.retryLimit) {
                $.ajax(this);
                return;
            }
        },
        complete: function () {
            $(".loader").hide();
        }
    })
}




function printHangDivJCPreview(front,back,disney,name,style_no,buyer_no,id,order_type,created_at,status,buyer,isEdit) {
	var a = document.getElementById(front)
	var svgDoc = a.contentDocument;
	var svgRoot = svgDoc.documentElement;

	var a1 = document.getElementById(back)
	var svgDoc1 = a1.contentDocument;
	var svgRoot1 = svgDoc1.documentElement;
	console.log(svgDoc1)
	var index_arr=[];
	var svgarr = [];
	var svgarr_front = [];
	var i=0;
	var ajax=0;

	$.ajax({
				url: base_url + "/erp/job-manager/get-order-details",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					ref_name: name,
					style_no: style_no,
					buyer_no: buyer_no,
					id: id,
					created_at: created_at,
					order_type:order_type,
					buyer:buyer,
				},
				beforeSend: function() {

				},
				success: function(data) {
					console.log("-------")
					console.log(data)
						console.log("-------")
					details = data.order_details[0].hangtagqty;
					var node = name;
					$.each(details, function(index, val) {
						
							var size_txt = val.size;
							var uvc_tco = val.uvc_tco;
							var size = val.size.split(" ");
						
							if (size[1] == "ANS") {
								if(size_txt.split(' ')[0] == 1)
								{
									var size1 = "YEAR";
								}
								else
								{
									var size1 = "YEARS";
								}

							} else {
								if(size_txt.split(' ')[0] == 1)
								{
									var size1 = "MONTH";
								}
								else
								{
									var size1 = "MONTHS";
								}
							}
						
							var code = val.barcode;
						
						
							if(size[0] == "TAILLE")
							{
								svgDoc1.getElementById("size").innerHTML = "";
								svgDoc1.getElementById("size_french").innerHTML = "";
								svgDoc1.getElementById("size_english").innerHTML = "";
								svgDoc1.getElementById("uvc_tco").innerHTML = uvc_tco;
								svgDoc1.getElementById("size_list").innerHTML = "TAILLE UNIQUE";
								svgDoc1.getElementById("cm").innerHTML = "ONE SIZE";
								
								svgDoc1.getElementById("tu_one_size").removeAttribute("visibility");
								svgDoc.getElementById("size").innerHTML = "";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
								svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
							}
							else
							{
								if(val.cm != null && val.cm !=null)
								{
									svgDoc1.getElementById("cm").innerHTML =  val.cm+" CM";
									
								}
								else
								{
									svgDoc1.getElementById("cm").innerHTML = "";
								}

								svgDoc1.getElementById("size").innerHTML = size[0];
								svgDoc1.getElementById("size_french").innerHTML = size[1];
								svgDoc1.getElementById("size_english").innerHTML = size1;
								svgDoc1.getElementById("uvc_tco").innerHTML = uvc_tco;
								
								svgDoc.getElementById("size").innerHTML = size[0];
								if(size[0].length == 1)
								{
									svgDoc.getElementById("size").setAttribute('transform', 'matrix(1.0074 0 0 1 21.0324 291.266)');
									
								}
								else
								{
									svgDoc.getElementById("size").setAttribute('transform', 'matrix(1.0074 0 0 1 16.0324 291.266)');
								}
								svgDoc.getElementById("size_french").innerHTML = size[1];
								svgDoc.getElementById("size_english").innerHTML = size1;
							}
						

							$.each(val.prices, function(index,value){
								switch(value.Currency)
								{
										case "EUR":
										if(value.Price != "" && value.Price != null)
										{
											var svg_price =	value.Price.split(',');
											var svg_club_price = value.Club_Price.split(',');
											
											svgDoc.getElementById("price1").innerHTML = svg_price[0];
											svgDoc.getElementById("price2").innerHTML = ","+svg_price[1];
											svgDoc.getElementById("club_price1").innerHTML = svg_club_price[0];
											svgDoc.getElementById("club_price2").innerHTML = ","+ 	svg_club_price[1];	
										}
										else
										{
											svgDoc.getElementById("price1").innerHTML = "";
											svgDoc.getElementById("price2").innerHTML = "";
											svgDoc.getElementById("club_price1").innerHTML = "";
											svgDoc.getElementById("club_price2").innerHTML = "";	
										}
										break;
										case "CAD":
										svgDoc1.getElementById("canada_price").innerHTML = value.Price;
										svgDoc1.getElementById("canada_club_price").innerHTML = value.Club_Price;
										break;
										case "CHF":
										svgDoc1.getElementById("swiss_price").innerHTML = value.Price;
										svgDoc1.getElementById("swiss_club_price").innerHTML = value.Club_Price;
										break;
										case "DZD":
										svgDoc1.getElementById("algeria_price").innerHTML = value.Price;
										svgDoc1.getElementById("algeria_club_price").innerHTML = value.Club_Price;
										break;
										case "MAD":
										svgDoc1.getElementById("morocco_price").innerHTML = value.Price;
										svgDoc1.getElementById("morroco_club_price").innerHTML = value.Club_Price;
										break;
										case "MUR":
										svgDoc1.getElementById("morris_price").innerHTML = value.Price;
										svgDoc1.getElementById("morris_club_price").innerHTML = value.Club_Price;
										break;
										case "SAR":
										svgDoc1.getElementById("saudi_club_price").innerHTML = value.Price;
										break;
										case "USD":
										svgDoc1.getElementById("us_price").innerHTML = value.Price;
										svgDoc1.getElementById("us_club_price").innerHTML = value.Club_Price;
										break;
										default:
										console.log("default");
										break;
									}
							});
							

							if(disney != "DI")
							{
								jsbarcode_hangJCPreview(code, "barcode1", svgDoc1)
								svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 10 50)');
							}
							else
							{
								
                               jsbarcode_hang_disneyJCPreview(code, "barcode1", svgDoc1)
								if(node == "COD2")
								{
						   			svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 -5 -15)');
								}
								else if(node == "DBB1")
								{
									svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 0 -20)');
									//svgDoc1.getElementById("cm").setAttribute('transform', 'matrix(1.0057 0 0 1 92.096 47.0822)');
								}
								else
								{
									svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 0 -15)');
								}
							}
						
							var s = new XMLSerializer();
							var d = svgRoot1;
							var str = s.serializeToString(d);
							var svg = encodeURI(str)
							svgarr.push(svg)
							
							var s_front = new XMLSerializer();
							var d_front = svgRoot;
							var str_front = s_front.serializeToString(d_front);
							var svg_front = encodeURI(str_front)
							svgarr_front.push(svg_front)
							
							ajax++;
					});

					if(details.length == ajax)
					{

						var svg_fin = svgarr.join(',_');
						var svg_fin_front = svgarr_front.join(',_');
						//PopupWashJCPreview(svg_fin,svgRoot1_back,svgRoot1_middle, dataid,svgDoc1);
						
						PopupHangJCPreview(svg_fin_front, svg_fin,svgDoc,svgDoc1,disney,isEdit,name,id,style_no,buyer_no,created_at);
					}
				},
			});



	

	
	
}



/**
 * This Function is used Print Wash Care Labels.
 *  
 * @return Pdf
 */

function printWashDivJCPreview(data,data_back,data_middle,name,style_no,buyer_no,id,order_type,created_at,status,buyer, isEdit) {

	var a1 = document.getElementById(data);
	var svgDoc1 = a1.contentDocument;
	var svgRoot1 = svgDoc1.documentElement;

	var a1_back = document.getElementById(data_back);
	var svgDoc1_back = a1_back.contentDocument;
	var svgRoot1_back = svgDoc1_back.documentElement;
	
	var a1_middle = document.getElementById(data_middle);
	var svgDoc1_middle = a1_middle.contentDocument;
	var svgRoot1_middle = svgDoc1_middle.documentElement;

	var index_arr=[];
	var svgarr = [];
	var svgarr_front = [];
	var i=0;
	var ajax=0;

	$.ajax({
				url: base_url + "/erp/job-manager/get-order-details",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					ref_name: name,
					style_no: style_no,
					buyer_no: buyer_no,
					id: id,
					created_at: created_at,
					order_type:order_type,
					buyer:buyer,
				},
				beforeSend: function() {

				},
				success: function(data) {
					
		
					details = data.order_details[0].washcarelabel_qty;
					Refname =data.reference_name;
					parentId= data.order_details[0].parentId;
					res = data.order_details[0] 	

					$.each(details, function(index, val) {
						
						var size_txt = val.size;
						var uvc_tco = val.uvc_tco;
						var size = val.size.split(" ")

						if (size[1] == "ANS") {
							var size1 = "years";
						} else {
							var size1 = "months";
						}

						var code = val.barcode;

						if(val.cm != null && val.cm != "")
						{
							svgDoc1.getElementById("size_in_fr_en1").innerHTML = val.cm+" cm ";
							svgDoc1.getElementById("size_in_fr_en2").innerHTML = val.cm+" cm ";
						}
						else
						{
							svgDoc1.getElementById("size_in_fr_en1").innerHTML = "";
							svgDoc1.getElementById("size_in_fr_en2").innerHTML = "";
						}

						svgDoc1.getElementById("size1").innerHTML = size_txt.replace(" ", "").toLowerCase() + "/" + size1;
						svgDoc1.getElementById("size2").innerHTML = size_txt.replace(" ", "").toLowerCase() + "/" + size1;
						svgDoc1.getElementById("parcel_code1").innerHTML = uvc_tco;
						svgDoc1.getElementById("parcel_code2").innerHTML = uvc_tco;
						svgDoc1.getElementById("barcode").setAttribute('transform', 'matrix(0.8899 0 0 1.1092 -40 135) rotate(270)');
						jsbarcodewashJCPreview(code, 'barcode', svgDoc1)

						svgDoc1.getElementById("cofreet_symbol1").setAttribute('xlink:href',res.cofreet_symbol1);
						svgDoc1.getElementById("cofreet_symbol2").setAttribute('xlink:href',res.cofreet_symbol2);
						svgDoc1.getElementById("cofreet_symbol3").setAttribute('xlink:href',res.cofreet_symbol3);
						svgDoc1.getElementById("cofreet_symbol4").setAttribute('xlink:href',res.cofreet_symbol4);
						svgDoc1.getElementById("cofreet_symbol5").setAttribute('xlink:href',res.cofreet_symbol5);

						svgDoc1.getElementById("astm_symbol1").setAttribute('xlink:href',res.astm_symbol1);
						svgDoc1.getElementById("astm_symbol2").setAttribute('xlink:href',res.astm_symbol2);
						svgDoc1.getElementById("astm_symbol3").setAttribute('xlink:href',res.astm_symbol3);
						svgDoc1.getElementById("astm_symbol4").setAttribute('xlink:href',res.astm_symbol4);
						svgDoc1.getElementById("astm_symbol5").setAttribute('xlink:href',res.astm_symbol5);


						var s = new XMLSerializer();
						var d = svgRoot1;
						var str = s.serializeToString(d);
						var svg = encodeURI(str)
						svgarr.push(svg)

						// var s_front = new XMLSerializer();
						// var d_front = svgRoot;
						// var str_front = s_front.serializeToString(d_front);
						// var svg_front = encodeURI(str_front)
						// svgarr_front.push(svg_front)

						ajax++;
					});

					if(details.length == ajax)
					{

						var svg_fin = svgarr.join(',_');
						// var svg_fin_front = svgarr_front.join(',_');
						
						// PopupHangJCPreview(svg_fin, svg_fin,svgDoc,svgDoc1,disney);
						PopupWashJCPreview(svg_fin,svgRoot1_back,svgRoot1_middle,svgDoc1,parentId,isEdit,name,id,style_no,buyer_no,created_at);
					}
				},
			});
	
	// if(i == ajax)
	// {
	// 	PopupWashJCPreview(svgRoot1,svgRoot1_back,svgRoot1_middle,svgDoc1);
	// }
	
}

function genarateArtworkOrchestraJCPreview(data, name,style_no,buyer_no,id,order_type,created_at,status,buyer,isEdit)
{
	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	// var name2=name;
	var zone = localStorage.getItem("zone");
	var svgarr = [];
	var ajax_count = 0;
	var name_count=0;
	// var b = " ";
	// var position = 5;
// console.log(name)
		// var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();
		$.ajax({
				url: base_url + "/erp/job-manager/get-order-details",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					ref_name: name,
					style_no: style_no,
					buyer_no: buyer_no,
					id: id,
					created_at: created_at,
					order_type:order_type,
					buyer:buyer,
				},
				beforeSend: function() {

				},
				success: function(data) {
				
					var svgRoot = svgDoc.documentElement;
					var zone= localStorage.getItem("zone");
					localStorage.setItem("zone", zone);

					details = data['order_details'][0];
					// console.log(details)
					switch(details.reference_name) 
					{


						// case "Orchestra WashCare":
						
						// 	printWashDivJC('washcare_svg_front1','washcare_svg_back1','washcare_svg_middle1',details['washcarelabel_qty']);

						// break

						case "Std Carton Stickers":
							$(details['stdcartonstickerqty']).each(function(index,value){
								svgDoc.getElementById("po").innerHTML = details.po;
								svgDoc.getElementById("parcelcode").innerHTML = details.parcelCode
								// svgDoc.getElementById("size").innerHTML = value.size
								svgDoc.getElementById("theme").innerHTML = details.theme
								

								// if(details.age_group!=null)
								// {
								// 	svgDoc.getElementById("color").innerHTML = details.age_group
								// }

								if(details.height != null )
								{
									var num = 600 * details.width * details.height
									var val = num / (1000 * 1000 * 1000)
									svgDoc.getElementById("height").innerHTML = details.height
									svgDoc.getElementById("volume").innerHTML = val.toFixed(3)
								}
								else{
									svgDoc.getElementById("volume").innerHTML = ''
									svgDoc.getElementById("height").innerHTML =""
								}
								
								if(details.width != null )
								{
									svgDoc.getElementById("width").innerHTML = details.width
								}
								else{
									svgDoc.getElementById("volume").innerHTML = ''
								}
								svgDoc.getElementById("color").innerHTML = details.color_code
								jsbarcodesolidcartonA(value.barcode, 'barcode1', svgDoc)
								jsbarcodesolidcartonB(value.barcode, 'barcode2', svgDoc)
								// var s = new XMLSerializer();
								// var d = svgRoot;
								// var str = s.serializeToString(d);
								// svgarr.push(str)
							})
							// var svg_fin = svgarr.join(',_');
							 
							  PopupOrchestraJCPreview(svgDoc,"STD-CARTON",isEdit,id,style_no,buyer_no,created_at); 
							
							

						break;


						case "Std Poly Stickers":
							$(details['stdpolystickerqty']).each(function(index,value){
                                console.log(value)
                                
                                if(details.group_id != 6)
								{
									svgDoc.getElementById("round").style.fill = details.age_group
									svgDoc.getElementById("pmm").setAttribute('visibility','hidden');
									svgDoc.getElementById("pmm").innerHTML = "";
								}
								else
								{
									svgDoc.getElementById("round").style.fill = "#FFFFFF"
									svgDoc.getElementById("pmm").removeAttribute('visibility','hidden');
									svgDoc.getElementById("pmm").innerHTML = "PMM";
                                }

								//svgDoc.getElementById("round").style.fill = details.age_group
								svgDoc.getElementById("parcel_code").innerHTML = details.parcelCode
								svgDoc.getElementById("theme").innerHTML = details.theme
								// svgDoc.getElementById("size").innerHTML = value.size
								// svgDoc.getElementById("color").innerHTML = details.color_code
								jsbarcodesolidpoly_orchestra(value.barcode, 'barcode', svgDoc)
								var s = new XMLSerializer();
								var d = svgRoot;
								var str = s.serializeToString(d);
								svgarr.push(str)
							})
							var svg_fin = svgarr.join(',_');
							PopupTableOrchestraJCPreview(svgDoc, "STD-POLY",details.sizeArr,details.qtyArr,details.sizBrkAr,isEdit,id,style_no,buyer_no,created_at);
						break;



						case "Solid Polybag Stickers":
							$(details['solidpolystickerqty']).each(function(index,value){

                                if(details.group_id != 6)
								{
									svgDoc.getElementById("round").style.fill = details.age_group
									svgDoc.getElementById("pmm").setAttribute('visibility','hidden');
									svgDoc.getElementById("pmm").innerHTML = "";
								}
								else
								{
									svgDoc.getElementById("round").style.fill = "#FFFFFF"
									svgDoc.getElementById("pmm").removeAttribute('visibility','hidden');
									svgDoc.getElementById("pmm").innerHTML = "PMM";
                                }

								//svgDoc.getElementById("round").style.fill = details.age_group
								svgDoc.getElementById("parcel_code").innerHTML = value.uvc_tco
								svgDoc.getElementById("size").innerHTML = value.size
								if(value.size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").style.fontSize = "15px"
									svgDoc.getElementById("size").setAttribute('transform','matrix(1 0 0 1 168.625 28.7874)');
									svgDoc.getElementById("color").setAttribute('transform','matrix(1 0 0 1 169.625 45.3324)');
								}
								svgDoc.getElementById("color").innerHTML = details.color_code
								jsbarcodesolidpoly_orchestra(value.barcode, 'barcode', svgDoc)
								var s = new XMLSerializer();
								var d = svgRoot;
								var str = s.serializeToString(d);
								svgarr.push(str)
							})
							var svg_fin = svgarr.join(',_');
							PopupOrchestrasmallJCPreview(svg_fin, "SOLID-POLY",svgDoc,isEdit,id,style_no,buyer_no,created_at);
						break;


						case "Solid Carton Stickers":
							$(details['solidcartonstickerqty']).each(function(index,value){
								svgDoc.getElementById("po").innerHTML = details.po;
								svgDoc.getElementById("parcelcode").innerHTML = value.uvc_tco
								svgDoc.getElementById("size").innerHTML = value.size
								svgDoc.getElementById("theme").innerHTML = details.theme
								

								if(details.age_group!=null)
								{
									svgDoc.getElementById("color").innerHTML = details.age_group
								}

								if(details.height!=null)
								{
									var num = 600 * details.width * details.height
									var val = num / (1000 * 1000 * 1000)
									svgDoc.getElementById("height").innerHTML = details.height
									svgDoc.getElementById("volume").innerHTML = val.toFixed(3)
								}
								else{
									svgDoc.getElementById("volume").innerHTML = ''
									svgDoc.getElementById("height").innerHTML =""
								}
								
								if(details.width != null )
								{
									svgDoc.getElementById("width").innerHTML = details.width
								}
								else{
									svgDoc.getElementById("volume").innerHTML = ''
								}
								if(value.size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = '<tspan xmlns="http://www.w3.org/2000/svg" dx="-12" dy="-5" text-anchor="start">TAILLE </tspan><tspan xmlns="http://www.w3.org/2000/svg" dx="-14" dy="9" text-anchor="middle">UNIQUE</tspan>';
								}
								svgDoc.getElementById("color").innerHTML = details.color_code
								jsbarcodesolidcartonA(value.barcode, 'barcode1', svgDoc)
								jsbarcodesolidcartonB(value.barcode, 'barcode2', svgDoc)
								var s = new XMLSerializer();
								var d = svgRoot;
								var str = s.serializeToString(d);
								svgarr.push(str)
							})
							var svg_fin = svgarr.join(',_');
							PopupArrOrchestraJCPreview(svg_fin, "SOLID-CARTON",svgDoc,isEdit,id,style_no,buyer_no,created_at);
						break;
						default:
						// code block
					}
				},
			});
}

function PopupWashJCPreview(data,data_back,data_middle,svgDoc1,pid,isEdit,name,id,style_no,buyer_no,created_at) {

	var s = new XMLSerializer();
	// var d = data;
	// var str = s.serializeToString(d);
	// var svg = encodeURI(str)


	var d_back = data_back;
	var str_back = s.serializeToString(d_back);
	var svg_back = encodeURI(str_back)

	var d_middle = data_middle;
	var str_middle = s.serializeToString(d_middle);
	var svg_middle = encodeURI(str_middle)

    var success = false;
    var url = "";

    

    $.ajax({

        url: base_url + "/erp/job-manager/orchestra/print-wash-pdf",
        type: "post",
        data: {
            _token: csrf_token,
            svg: data,
            svg_back: svg_back,
			name:name,
            svg_middle: svg_middle,
            parentId : pid,
            id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
        },
        async: false,
        success: function (data) {
			localStorage.setItem('file_path',localStorage.getItem('file_path')+','+data.file_path);
        
			// differentiate between edit and create functionality
			if(isEdit === false)
			{
				operation()
			}
			else
			{
				operationEdit()
			}
        },
        error: function (data) {

        },
    })
    if (success) { //AND THIS CHANGED
        window.open(url)
    }
}


/**
 * This Function is used to invoke JSBarcode for Wash Care Labels.
 * 
 * @return barcode
 */

function jsbarcodewashJCPreview(code, id, svgDoc) {
    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 5.99,
        height: 290,
		lineColor: "cmyk(0%, 0%, 0%, 100%)",
        fontSize: 55
    });
    var svg = document.getElementById('svgdiv').querySelector('svg'),
        canvas = document.createElement('canvas'),
        body = document.getElementsByTagName('body')[0],
        canvasTmp,
        svg_xml,
        ctx,
        img;
    // if our "svg" doesn't have 'xmlns' attribute we must add it
    //    svg.setAttribute('width', '188.931023622px');
    svg.setAttribute('font-size', '70px');
    svg.setAttribute('font-family', 'ocr-b-10bt');
    svg.setAttribute('width', '92.1px');
    svg.setAttribute('height', '133px');
    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;

    body.appendChild(canvas);
    canvasTmp = document.getElementById('canvas');

    /* Time to play ;) */

    // https://developer.mozilla.org/en/XMLSerializer
    svg_xml = (new XMLSerializer()).serializeToString(svg);
    ctx = canvasTmp.getContext('2d');

    // JavaScript (HTML) image
    img = new Image();

    img.onload = function () {
        ctx.drawImage(img, 0, 0);
    };

    // https://developer.mozilla.org/en/DOM/window.btoa
    img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

    // img.src - contains base64 URI from svg

    //remove temporary canvas
    canvasTmp.remove();

    svgDoc.getElementById(id).innerHTML = svg_xml;
}
function PopupHangJCPreview(svgRoot, svgRoot1,svgDoc,svgDoc1,disney,isEdit,name,id,style_no,buyer_no,created_at) {
//	var s = new XMLSerializer();
//	var d = svgRoot;
//	var str = s.serializeToString(d);
//	var svg = encodeURI(str)

//	var s1 = new XMLSerializer();
//	var d1 = svgRoot1;
//	var str1 = s1.serializeToString(d1);
//	var svg1 = encodeURI(str1)

	var success = false;
	var url = "";
	
	$.ajax({
		url: base_url + "/erp/job-manager/print-hang-pdf",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svgRoot,
			name:name,
			svgRoot: svgRoot1,
            id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
		},
		async: false,
		success: function (data) {
			
			localStorage.setItem('file_path',localStorage.getItem('file_path')+','+data.file_path);
        
			// differentiate between edit and create functionality
			if(isEdit === false)
			{
				operation()
			}
			else
			{
				operationEdit()
			}
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

function PopupOrchestraJCPreview(data,name,isEdit,id,style_no,buyer_no,created_at) {
	
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/print-pdf",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			name:name,
			id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
		},
		async: false,
		success: function (data) {
			//window.location.href=data;
			localStorage.setItem('file_path',localStorage.getItem('file_path')+','+data.file_path);
        
			// differentiate between edit and create functionality
			if(isEdit === false)
			{
				operation()
			}
			else
			{
				operationEdit()
			}
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}



function PopupArrOrchestraJCPreview(data,name,svgDoc,isEdit,id,style_no,buyer_no,created_at) {
	
//	var s = new XMLSerializer();
//	var d = data;
//	var str = s.serializeToString(d);
//	var svg = encodeURI(str)
	var success = false;
//	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/print-pdf-arr",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name,
			id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
		},
		async: false,
		success: function (data) {
			localStorage.setItem('file_path',localStorage.getItem('file_path')+','+data.file_path);
        
			// differentiate between edit and create functionality
			if(isEdit === false)
			{
				operation()
			}
			else
			{
				operationEdit()
			}
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}



function PopupTableOrchestraJCPreview(data,name,size,qty,sizebrk,isEdit,id,style_no,buyer_no,created_at) {
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);d
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	// var size = [];
	// var qty = [];
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);

	// $('#solidform tbody tr').each(function () {
	// 	size.push($(this).find('.size').text());
	// 	qty.push($(this).find('.qty').text());
	// })
	// size=['3 ANS','4 ANS','5 ANS','6 ANS','8 ANS'];
	// qty=['3','4','5','6','8'];

	var sizefin = size.join(',')
	var qtyfin = qty.join(',')
	var sizebrkfin = sizebrk.join(',')
	
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/print-pdf-table",

		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			size: sizefin,
			qty: qtyfin,
			sizebrk: sizebrkfin,
			name:name,
			id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
		},
		async: false,
		success: function (data) {
			localStorage.setItem('file_path',localStorage.getItem('file_path')+','+data.file_path);
        
			// differentiate between edit and create functionality
			if(isEdit === false)
			{
				operation()
			}
			else
			{
				operationEdit()
			}
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}



function PopupOrchestrasmallJCPreview(data,name,svgDoc,isEdit,id,style_no,buyer_no,created_at) {
//	var s = new XMLSerializer();
//	var d = data;
//	var str = s.serializeToString(d);
//	var svg = encodeURI(str)
	var success = false;
//	var url = "";
	
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/print-pdf-small",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name,
			id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
		},
		async: false,
		success: function (data) {
			localStorage.setItem('file_path',localStorage.getItem('file_path')+','+data.file_path);
        
			// differentiate between edit and create functionality
			if(isEdit === false)
			{
				operation()
			}
			else
			{
				operationEdit()
			}
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}


function jsbarcode_hangJCPreview(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 5.99,
        height: 320,
		lineColor: "cmyk(0%, 0%, 0%, 100%)",
        fontSize: 55
    });
    var svg = document.getElementById('svgdiv').querySelector('svg'),
        canvas = document.createElement('canvas'),
        body = document.getElementsByTagName('body')[0],
        canvasTmp,
        svg_xml,
        ctx,
        img;
    // if our "svg" doesn't have 'xmlns' attribute we must add it
    //    svg.setAttribute('width', '188.931023622px');
    svg.setAttribute('font-size', '70px');
    svg.setAttribute('font-family', 'ocr-b-10bt');
    svg.setAttribute('width', '80px');
    svg.setAttribute('height', '100px');
    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;

	body.appendChild(canvas);
	canvasTmp = document.getElementById('canvas');

	/* Time to play ;) */

	// https://developer.mozilla.org/en/XMLSerializer
	svg_xml = (new XMLSerializer()).serializeToString(svg);
	ctx = canvasTmp.getContext('2d');

	// JavaScript (HTML) image
	img = new Image();

	img.onload = function () {
		ctx.drawImage(img, 0, 0);
	};

	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

	// img.src - contains base64 URI from svg

	//remove temporary canvas
	canvasTmp.remove();

	var allGs = document.getElementsByTagName('g');
	var firstG = allGs[0];
	var firstChild = firstG.childNodes[0];
	
	firstChild.setAttribute("x","20")
	
	var xchil = document.getElementById('svgdiv')

	svgDoc.getElementById(id).innerHTML = xchil.innerHTML;
}
/**
 * This Function is used to invoke Hang Tag JSBarcode.
 * 
 * @return barcode
 */

function jsbarcode_hang_disneyJCPreview(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 5.99,
        height: 286,
		lineColor: "cmyk(0%, 0%, 0%, 100%)",
        fontSize: 55
    });
    var svg = document.getElementById('svgdiv').querySelector('svg'),
        canvas = document.createElement('canvas'),
        body = document.getElementsByTagName('body')[0],
        canvasTmp,
        svg_xml,
        ctx,
        img;
    // if our "svg" doesn't have 'xmlns' attribute we must add it
    //    svg.setAttribute('width', '188.931023622px');
    svg.setAttribute('font-size', '70px');
    svg.setAttribute('font-family', 'ocr-b-10bt');
    svg.setAttribute('width', '88px');
    svg.setAttribute('height', '80px');
    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;

	body.appendChild(canvas);
	canvasTmp = document.getElementById('canvas');

	/* Time to play ;) */

	// https://developer.mozilla.org/en/XMLSerializer
	svg_xml = (new XMLSerializer()).serializeToString(svg);
	ctx = canvasTmp.getContext('2d');

	// JavaScript (HTML) image
	img = new Image();

	img.onload = function () {
		ctx.drawImage(img, 0, 0);
	};

	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

	// img.src - contains base64 URI from svg

	//remove temporary canvas
	canvasTmp.remove();

	var allGs = document.getElementsByTagName('g');
	var firstG = allGs[0];
	var firstChild = firstG.childNodes[0];
	
	firstChild.setAttribute("x","20")
	
	var xchil = document.getElementById('svgdiv')

	svgDoc.getElementById(id).innerHTML = xchil.innerHTML;
}
function oswordWrapJC(str, maxWidth) {
    var newLineStr = ""; done = false; res = '';
    do {                    
        found = false;
        // Inserts new line at first whitespace of the line
        for (i = maxWidth - 1; i >= 0; i--) {
            if (testWhiteJC(str.charAt(i))) {
                res = res + "<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;'  x='0' y='0'>" +[str.slice(0, i), newLineStr].join('') + "</tspan>";
                str = str.slice(i + 1);
                found = true;
                break;
            }
        }
        // Inserts new line at maxWidth position, the word is too long to wrap
        if (!found) {
            res +="<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;'>" + [str.slice(0, maxWidth), newLineStr].join('')+ "</tspan>";
            str = str.slice(maxWidth);
        }
        if (str.length < maxWidth)
            done = true;
    } while (!done);
    return res + "<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;' x='0' y='12'>"+str+"</tspan>";
}
function testWhiteJC(x) {
    var white = new RegExp(/^\s$/);
    return white.test(x.charAt(0));
}