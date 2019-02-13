/**
 * ERP job card manager jquery/javascript file
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
var csrf_token = $('meta[name="csrf-token"]').attr('content');
var base_url = "<?php echo  url('/') ?>";
$(document).ready(function() {
	$('input[class="row-checkbox"]').prop('checked', false);

	$('#create_jobcard').trigger("reset");

	/** To refresh modal while hidden **/
	$('.modal').on('hidden.bs.modal', function (e) {
 	  $('#create_jobcard').trigger("reset");
	})

	// Checkbox click functionality
	$('.approved-order-list tr').click(function (event) {
        if (event.target.type !== 'checkbox') {
            $(':checkbox', this).trigger('click');
        }
    });

    /** Datatable filter script**/
	var listTable = $('#approved-order-list').dataTable({
		stateSave: true,
		/** Table column filter functionality **/
		initComplete: function () {
			$('.main-content > #approved-orders-table > .dataTables_wrapper > .row > .col-sm-6 > #approved-order-list_filter:first').append(' <button class="filterbtn" type="button" onClick="showFilter()" title="Filter" data-placement="bottom"><i class="fa fa-filter" aria-hidden="true"></i></button>');
			$('.main-content > #approved-orders-table > .dataTables_wrapper > .row > .col-sm-6 > #approved-order-list_filter:first .dataTables_filter').css('display', 'inline-block');
			$('.main-content > #approved-orders-table > .dataTables_wrapper > .row > .col-sm-12:first').prepend('<div class="filter-window"><h5><strong>Jobcard Filter</strong></h5> <div class="row"> <div class="col-md-2"><select class="selectpicker" id="erp_id_filter"> <option disabled selected>SELECT AN ERP ID</option></select></div><div class="col-md-2"><select class="selectpicker" id="customer_name_filter"> <option disabled selected>SELECT A CUSTOMER</option></select></div><div class="col-md-2"><select class="selectpicker" id="po_number_filter"> <option disabled selected>SELECT A PO NUMBER</option></select></div><div class="col-md-2"><select class="selectpicker" id="style_number_filter"> <option disabled selected>SELECT A STYLE NUMBER</option></select></div><div class="col-md-2"><select class="selectpicker" id="ref_number_filter"> <option disabled selected>SELECT A REFERENCE</option></select></div><div class="col-md-2"><select class="selectpicker" id="delivery_date_filter"> <option disabled selected>SELECT A DELIVERY DATE</option></select></div></div><div class="filter-actions"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="close_filter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');

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
		$('#erp_id_filter').val('').change();
		$('#customer_name_filter').val('').change();
		$('#po_number_filter').val('').change();
		$('#style_number_filter').val('').change();
		$('#ref_number_filter').val('').change();
		$('#delivery_date_filter').val('').change();
		$.fn.dataTableExt.afnFiltering.length = 0;
		listTable.dataTable().fnDraw();
		$('.selectpicker').selectpicker('val', 1);
        $('.selectpicker').selectpicker('refresh');
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

    $('#jobcard-list-table').DataTable();
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
});
/* End of document.ready() */

/**
 * method to check the input value is a number or not
 * @param  input data
 * @return {Boolean}
 */
function isNumber(evt) {
	try {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	} catch (e) {
		console.log("Error:" + e)
	}
}

/**  
 * Validation for numeric values with dot
 * @param   evt 
 * @return {Boolean} 
 * @author <praveen>    
 */
function isNumberKey(evt) {
	try {
		var charCode = (evt.which) ? evt.which : event.keyCode

		if (charCode == 46) {
			return true;
		}
		/** [ Checking key stroke values for numeric values ] */
		if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	} catch (e) {
		console.log("Error:" + e)
	}
}

/** [showSnackbar function]
 *  @author <Praveen> [praveen@wizroots.com]
 */
    function showSnackbar() {
    try {
        // Get the snackbar DIV
        let x = document.getElementById("snackbar")

        // Add the "show" class to DIV
        x.className = "show";

        // After 3 seconds, remove the show class from DIV
        setTimeout(function() {
            x.className = x.className.replace("show", "");
        }, 3000);
    } catch (e) {
        console.log("Error:" + e)
    }
}

/**
 * Toggle show filter window
 *  @author <Praveen> [praveen@wizroots.com]
 */
function showFilter() {
    $('.filter-window').toggle();
}

/**
 * Job card generation 
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
        $.ajax({
            url:base_url + "/erp/job-manager/get-jobcard-order-details",
            dataType:"json",
            method:"get",
            data:{orders:orders, ref_array:ref_array, buyer_po_array:buyer_po_array},
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

                        var workorder_id = data.template_data.company.company === 'Jupiter Garments Accessories'?'JGA-JC-':'SP-JC-';
                        $('#work_order').text( workorder_id+data.template_data.workorder_id);

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
                        }

                        if(data.template_data.style_array.length === 1)
                        {
                            $('#style_no').val(data.template_data.style_array)
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
                                                            total += parseInt(row.order_quantity);
                                                            ratio_table_html += '<tr class="tr-'+row.reference_name.replace(' ', '-')+'" id='+row.erp_order_details_id+' data-template-id='+row.template_id+'>\
                                                            <td id="ratio_tr_refname_'+ratio_table_row+'">'+row.reference_name+' '+row.ratio_pack_type+'</td>\
                                                            <td id="ratio_tr_buyer_po_'+ratio_table_row+'">'+row.buyer_po_number+'</td>\
                                                            <td id="ratio_tr_erp_id_'+ratio_table_row+'">'+row.erp_id+'</td>\
                                                            <td id="ratio_tr_ocn_'+ratio_table_row+'">'+row.ocn_number+'</td>\
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
                                    single_table_html += '<td colspan="2" class="single-buyer-po-'+index+'">'+common_data.buyer_po_number+'</td>';
                                });
                                single_table_html += '</tr>\
                                                            <tr>\
                                                                <th width="80px">OCN</th>';
                                $.each(erpid.order_data, function(index, common_data){
                                    single_table_html += '<td colspan="2" class="single-ocn-'+index+'">'+common_data.ocn_number+'</td>';
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

                                    single_table_html += '<td class="total-quantity-class total-quantity-row-'+row+'"><strong>'+parseInt(total_qty_sizewise)+'</strong></td><td class="total-excess"><div class="form-group has-feedback"><input type="number" data-step-error="Decimal values not allowed." min="0" data-min-error="Value must be >= 0" class="form-control input-sm total-excess-row-table-'+table+'" required="" id="total_excess_row_table_'+row+'" data-required-error="Mandatory Field." name="total_excess_row_table_'+row+'"" placeholder="Excess %" onkeyup="return totalExcess()" ><div class="help-block with-errors"></div></div></td><td></td><td></td></tr>';
                                
                                    total_quantity_final += parseInt(total_qty_sizewise);
                                    row++;
                                });

                                single_table_html += '<tr>\
                                                      <th class="highlight_row total-text" data-ref-name = "'+template_name[0].replace(' ', '_')+'">TOTAL</th>';
                                var total_col = 0;
                                $.each(erpid.order_data, function(index, common_data){
                                    single_table_html += '<td class="total-quantity-col-'+total_col+'"><strong>'+sum[total_col]+'</strong></td>\
                                                          <td></td>';
                                    total_col++;
                                });
                                
                                single_table_html += '<td><strong class="total-qty-'+template_name[0].replace(' ', '_')+'">'+parseInt(total_quantity_final)+'</strong></td>\
                                                        <td class="total-excess-col-table-'+table+' total-excess-'+template_name[0].replace(' ', '_')+'"></td>\
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
                                                    <label for="act_excess_'+template_name[0].replace(' ', '_')+'" >ACT EXCESS % ('+template_name[0]+'):</label>\
                                                    <span class="act-excess" data-act-excess-ref="'+template_name[0]+'" id="act_excess_'+template_name[0].replace(' ', '_')+'"></span>\
                                                </div>\
                                                <div class="col-md-3">\
                                                    <label for="sticker_mtr_'+template_name[0].replace(' ', '_')+'" >STKRS MTRS ('+template_name[0]+'):</label>\
                                                    <span class="sticker-mtrs" id="sticker_mtr_'+template_name[0].replace(' ', '_')+'"></span>\
                                                </div>\
                                                <div class="col-md-3">\
                                                <div class="form-group has-feedback form-inline">\
                                                    <label for="settings_'+template_name[0].replace(' ', '_')+'" >SETTINGS ('+template_name[0]+') :</label>\
                                                    <input type="number" min="1" data-step-error="Decimal values not allowed." data-min-error="Value must be >= 1" class="form-control input-sm settings" required="" name="settings_'+template_name[0].replace(' ', '_')+'" placeholder="SETTINGS" id="settings_'+template_name[0].replace(' ', '_')+'" onkeyup="return totalMeters()" max="99999"  data-max-error="Value should be <= 99999">\
                                                    <div class="help-block with-errors"></div>\
                                                </div>\
                                                </div>\
                                                <div class="col-md-3">\
                                                <label for="total_mtrs_'+template_name[0].replace(' ', '_')+'" >TOTAL MTRS ('+template_name[0]+'):</label>\
                                                <span id="total_mtrs_'+template_name[0].replace(' ', '_')+'" class="total-meters"></span>\
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
                    })

                    /** [ Validate create_jobcard form using bootstrap validator ] */
                    $('#create_jobcard').validator('update');
                    $('#jobcardCreationModalCenter').modal('show');
                    $('.datepicker').datepicker({
                        format: 'dd-mm-yyyy'
                    });
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
 * Dynamic Excess calculation based on excess value entered.
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function calculateExcess(evt)
{
    let item = $('.total-quantity-class');
    $.each(item, function(key, value){
        let total_excess = parseFloat($(value).text() * (evt.value*1/100));
        $('#total_excess_row_table_'+key).val(Math.round(total_excess));
        /** refresh excess values fields in single ref table */
        $('#total_excess_row_table_'+key).trigger('input');
    });
    totalExcess();
}

/**
 * Dynamic Total Excess calculation based on calculated excess value.
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
 * Dynamic Actual Excess calculation based on excess value and total quantity.
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
            act_excess = parseInt((total_excess/total_qty)*100);
        }
        $('#act_excess_'+ref_name).text(Math.round(act_excess))
    });
}

/**
 * Dynamic Mtrs for sticker calculation.
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
 * Dynamic Total Meters calculation.
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function totalMeters()
{
    $('.total-meters').each(function(){
       let total_mtrs_id = $(this).attr('id').split("_");
       let ref_name = total_mtrs_id[2]+'_'+total_mtrs_id[3];
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
 * Store jobcard
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
    var disp_mode       = $( '#disp_mode' ).val() === ''?null:$('#disp_mode').val();
    var order_date      = $( '#order_date' ).text() === ''?null:$('#order_date').text();
    var length          = $( '#length' ).val() === ''?null:$('#length').val();
    var repeat_gap      = $( '#repeat_gap' ).val() === ''?null:$( '#repeat_gap' ).val();
    var machine         = $( '#machine' ).val() === ''?null:$( '#machine' ).val();
    var cus_del_date    = $( '#cus_del_date' ).val() === ''?null:$( '#cus_del_date' ).val();
    var roll_width      = $( '#roll_width' ).val() === ''?null:$( '#roll_width' ).val();
    var width           = $( '#width' ).val() === ''?null:$('#width').val();
    var film_no         = $( '#film_no' ).val() === ''?null:$( '#film_no' ).val();
    var act_del_date    = $( '#act_del_date' ).val() === ''?null:$( '#act_del_date' ).val();
    var length_side     = $( '#length_side' ).val() === ''?null:$( '#length_side' ).val();
    var excess          = $( '#excess' ).val() === ''?null:$( '#excess' ).val();
    var teeth_no        = $( '#teeth_no' ).val() === ''?null:$( '#teeth_no' ).val();
    var inv_no          = $( '#inv_no' ).val() === ''?null:$( '#inv_no' ).val();
    var no_sticker      = $( '#no_sticker' ).val() === ''?null:$( '#no_sticker' ).val();
    var finish_type     = $( '#finish_type' ).val() === ''?null:$( '#finish_type' ).val();
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

    var final_json_obj =   {
        "ratio": {},
        "single": {}
          };  
    var ratio_object = {};
    /** Create ratio templates json object */
    $('.ratio-list-table').each(function (i, table) {
     var my_object    = new Object();
     var array_list   = [];
         ref_name     = $(this).data('ratio-ref-table').replace(' ', '_');

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
        single_ref_name    = $(table).find('tbody tr:eq(0) td:last-child').data('single-ref-name').replace(' ', '_');
        
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
        act_excess[i] = $(item).text();
        single_ref[i] = $(item).data('act-excess-ref');
    });
    act_excess = act_excess.join(',');
    single_ref = single_ref.join(',');

    var sticker_mtr = [];
    /** [ Create array of sticker-mtrs ] */
    $('.sticker-mtrs').each(function (i, item) {
        sticker_mtr[i] = $(item).text();

    });
    sticker_mtr = sticker_mtr.join(',');

    var settings = [];
    /** [ Create array of settings ] */
    $('.settings').each(function (i, item) {
        settings[i] = $(item).val();

    });
    settings = settings.join(',');

    var total_mtrs = [];
    /** [ Create array of total-meters ] */
    $('.total-meters').each(function (i, item) {
        total_mtrs[i] = $(item).text();

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
            act_del_date: act_del_date,
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
            selected_buyer_po_array:selected_buyer_po_array
        },
        tryCount: 0,
        retryLimit: 2,
        dataType: "json",
        beforeSend: function () {
            $(".loader").show();
        },
        statusCode: {
            200: function(data) {
                var resp = JSON.stringify(data.file_path);
                $('.snackbar_span').html("Jobcard Created Successfully");
                showSnackbar();
                $('#jobcardCreationModalCenter').modal('hide');
                location.reload();
                window.open(
                    base_url+JSON.parse(resp),
                    '_blank' // <- This is what makes it open in a new window.
                );
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
   * Function for get jobcard details for editing
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
                 $('#edit_act_del_date').val(data.jobcard_data.act_del_date);
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

                 var ratio_table = '';
                 $.each(JSON.parse(data.jobcard_data.template_quantity_json), function(template_type, template_type_data){
                    console.log(template_type)
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
                                            ratio_table += '<tr class="tr-'+table.replace('_', ' ')+'"  data-template-id="'+value["template_id"]+'">\
                                                                <td id="edit_ratio_tr_refname_'+key+'">'+value["reference_name"]+'</td>\
                                                                <td id="edit_ratio_tr_buyer_po_'+key+'">'+value["buyer_po_number"]+'</td>\
                                                                <td id="edit_ratio_tr_erp_id_'+key+'">'+value["erp_id"]+'</td>\
                                                                <td id="edit_ratio_tr_ocn_'+key+'">'+value["ocn_number"]+'</td>\
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
                    else
                    {
                        if (!($.isEmptyObject(template_type_data)))
                        {
                            var table_row = 0;
                            var table_count = 0;
                            var single_table = '';
                            $.each(template_type_data, function(table, table_data){
                                var template = table.split("_");
                                single_table += '<table class="table table-bordered table-responsive edit-single-list-table" style="font-size: 12px;" id="single_list_table_'+table_count+'" border="0.2">\
                                        <tbody id="single_list_table_body">\
                                        <tr>\
                                            <th width="80px">ID.NO</th>';
                                            $.each(table_data.erp_id, function(key, value){
                                                single_table += '<td colspan="2" data-template-id="'+table_data.temp_id[key]+'" class="single-erpid-'+key+'">'+value+'</td>';
                                            });
                                            single_table += '<td colspan="4" class="highlight_row" rowspan="3" data-single-ref-name="'+template[0]+' '+template[1]+'">REFNUM &nbsp;: '+template[0]+' '+template[1]+'</td>\
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
                                                        <input type="number" data-step-error="Decimal values not allowed." min="0" data-min-error="Value must be >= 0" class="form-control input-sm edit-total-excess-row-table-'+table_count+'" required="" id="edit_total_excess_row_table_'+table_row+'" data-required-error="Mandatory Field." name="edit_total_excess_row_table_'+table_row+'" "="" placeholder="Excess %" onkeyup="return editTotalExcess()" value="'+table_data.excess[size]+'">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>\
                                                <td></td>\
                                                <td></td>\
                                                </tr>';
                                          total_quantity_final += parseInt(total_qty_sizewise);
                                          total_excess_final   += parseFloat(table_data.excess[size]);
                                          table_row++;
                                        });
                                        single_table += '<tr>\
                                                            <th class="highlight_row edit-total-text" data-ref-name="'+template[0]+'_'+template[1]+'">TOTAL</th>';
                                        
                                        var qty_total_col = 0;
                                        $.each(table_data.erp_id, function(key, value){
                                            single_table += '<td class="edit-total-quantity-col-'+qty_total_col+'"><strong>'+qty_col_sum[qty_total_col]+'</strong></td>\
                                            <td></td>';
                                            qty_total_col++;
                                        });
                                                            
                                        single_table += '<td><strong class="edit-total-qty-'+template[0]+'_'+template[1]+'">'+parseInt(total_quantity_final)+'</strong></td>\
                                                <td class="edit-total-excess-col-table-'+table_count+' edit-total-excess-'+template[0]+'_'+template[1]+'">'+parseFloat(total_excess_final).toFixed(2)+'</td>\
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
                                                <label for="edit_act_excess_'+item.ref_name.replace(' ', '_')+'" >ACT EXCESS % ('+item.ref_name+'):</label>\
                                                <span class="edit-act-excess" data-act-excess-ref="'+item.ref_name+'" id="edit_act_excess_'+item.ref_name.replace(' ', '_')+'">'+item.actual_excess+'</span>\
                                            </div>\
                                            <div class="col-md-3">\
                                                <label for="edit_sticker_mtr_'+item.ref_name.replace(' ', '_')+'" >STKRS MTRS ('+item.ref_name+'):</label>\
                                                <span class="edit-sticker-mtrs" id="edit_sticker_mtr_'+item.ref_name.replace(' ', '_')+'">'+item.mtrs_for_stickers+'</span>\
                                            </div>\
                                            <div class="col-md-3">\
                                            <div class="form-group has-feedback form-inline">\
                                                <label for="edit_settings_'+item.ref_name.replace(' ', '_')+'" >SETTINGS ('+item.ref_name+') :</label>\
                                                <input type="number" step="0.01" min="1" data-step-error="Max 2 decimal places allowed." data-min-error="Value must be >= 1" max="99999" data-max-error="Value should be <= 99999" class="form-control input-sm edit-settings" required="" name="edit_settings_'+item.ref_name.replace(' ', '_')+'" placeholder="SETTINGS" id="edit_settings_'+item.ref_name.replace(' ', '_')+'" onkeyup="return totalMetersEdit()" value="'+item.settings+'" >\
                                                <div class="help-block with-errors"></div>\
                                            </div>\
                                            </div>\
                                            <div class="col-md-3">\
                                            <label for="edit_total_mtrs_'+item.ref_name.replace(' ', '_')+'" >TOTAL MTRS ('+item.ref_name+'):</label>\
                                            <span id="edit_total_mtrs_'+item.ref_name.replace(' ', '_')+'" class="edit-total-meters">'+item.total_mtrs+'</span>\
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
                    
                /** [ Backdrop for bootstrap modal ] */
                $('#jobcardUpdationModalCenter').modal({
                    backdrop: 'static',
                    keyboard: false
                })

                /** [ Validate create_jobcard form using bootstrap validator ] */
                $('#edit_jobcard').validator('update');
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
 * Dynamic Excess calculation based on excess value entered.
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
 * Dynamic Total Excess calculation based on calculated excess value.
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
 * Dynamic Actual Excess calculation based on excess value and total quantity.
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
            act_excess = parseInt((total_excess/total_qty)*100).toFixed(2);
        }
        $('#edit_act_excess_'+ref_name).text(Math.round(act_excess));
    });
}

/**
 * Dynamic Mtrs for sticker calculation.
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
 * Dynamic Total Meters calculation.
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function totalMetersEdit()
{
    $('.edit-total-meters').each(function(){
       let total_mtrs_id = $(this).attr('id').split("_");
       let ref_name = total_mtrs_id[3]+'_'+total_mtrs_id[4];
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
 * Update jobcard
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
    var disp_mode       = $( '#edit_disp_mode' ).val() === ''?null:$('#edit_disp_mode').val();
    var order_date      = $( '#edit_order_date' ).text() === ''?null:$('#edit_order_date').text();
    var length          = $( '#edit_length' ).val() === ''?null:$('#edit_length').val();
    var repeat_gap      = $( '#edit_repeat_gap' ).val() === ''?null:$( '#edit_repeat_gap' ).val();
    var machine         = $( '#edit_machine' ).val() === ''?null:$( '#edit_machine' ).val();
    var cus_del_date    = $( '#edit_cus_del_date' ).val() === ''?null:$( '#edit_cus_del_date' ).val();
    var roll_width      = $( '#edit_roll_width' ).val() === ''?null:$( '#edit_roll_width' ).val();
    var width           = $( '#edit_width' ).val() === ''?null:$('#edit_width').val();
    var film_no         = $( '#edit_film_no' ).val() === ''?null:$( '#edit_film_no' ).val();
    var act_del_date    = $( '#edit_act_del_date' ).val() === ''?null:$( '#edit_act_del_date' ).val();
    var length_side     = $( '#edit_length_side' ).val() === ''?null:$( '#edit_length_side' ).val();
    var excess          = $( '#edit_excess' ).val() === ''?null:$( '#edit_excess' ).val();
    var teeth_no        = $( '#edit_teeth_no' ).val() === ''?null:$( '#edit_teeth_no' ).val();
    var inv_no          = $( '#edit_inv_no' ).val() === ''?null:$( '#edit_inv_no' ).val();
    var no_sticker      = $( '#edit_no_sticker' ).val() === ''?null:$( '#edit_no_sticker' ).val();
    var finish_type     = $( '#edit_finish_type' ).val() === ''?null:$( '#edit_finish_type' ).val();
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
    var jobcard_id      = $( '#jobcard_id' ).val();

    var final_json_obj =   {
        "ratio": {},
        "single": {}
          };  
    var ratio_object = {};
    /** Create ratio templates json object */
    $('.edit-ratio-list-table').each(function (i, table) {
     var my_object    = new Object();
     var array_list   = [];
         ref_name     = $(this).data('ratio-ref-table').replace(' ', '_');

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
        single_ref_name    = $(table).find('tbody tr:eq(0) td:last-child').data('single-ref-name').replace(' ', '_');
        
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
        act_excess[i] = $(item).text();
        single_ref[i] = $(item).data('act-excess-ref');
    });
    act_excess = act_excess.join(',');
    single_ref = single_ref.join(',');

    var sticker_mtr = [];
    /** [ Create array of sticker-mtrs ] */
    $('.edit-sticker-mtrs').each(function (i, item) {
        sticker_mtr[i] = $(item).text();

    });
    sticker_mtr = sticker_mtr.join(',');

    var settings = [];
    /** [ Create array of settings ] */
    $('.edit-settings').each(function (i, item) {
        settings[i] = $(item).val();

    });
    settings = settings.join(',');

    var total_mtrs = [];
    /** [ Create array of total-meters ] */
    $('.edit-total-meters').each(function (i, item) {
        total_mtrs[i] = $(item).text();

    });
    total_mtrs = total_mtrs.join(',');

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
            act_del_date: act_del_date,
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
            jobcard_id:jobcard_id
        },
        tryCount: 0,
        retryLimit: 2,
        dataType: "json",
        beforeSend: function () {
            $(".loader").show();
        },
        statusCode: {
            200: function(data) {
                console.log(data)
                var resp = JSON.stringify(data.file_path);
                $('.snackbar_span').html("Jobcard Updated Successfully");
                showSnackbar();
                $('#jobcardUpdationModalCenter').modal('hide');
                location.reload();
                window.open(
                    base_url+JSON.parse(resp),
                    '_blank' // <- This is what makes it open in a new window.
                );
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

