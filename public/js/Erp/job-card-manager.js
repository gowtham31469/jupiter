/**
 * ERP job card manager jquery/javascript file
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
var csrf_token = $('meta[name="csrf-token"]').attr('content');
$(document).ready(function() {
    var base_url = "<?php echo  url('/') ?>";
	$('input[class="row-checkbox"]').prop('checked', false);

	$('#create_jobcard').trigger("reset");

	/** To refresh modal while hidden **/
	$('.modal').on('hidden.bs.modal', function (e) {
 	  $('#create_jobcard').trigger("reset");
	})

	/** [ Make companies card active ] */
	$('div.buyers-card').click(function(){

		  $('div.buyers-card').removeClass("active");
		  $(this).addClass("active");
		  $('#hidden-buyer-div').hide();

		  /* Save selected buyer into localdb */
	      if ($('.buyers-card.active-buyer.active').length != 0) 
	      {
         	var company_id = $('.buyers-card.active-buyer.active').data("value"); 
		  	var company_name = $('.buyers-card.active-buyer.active').attr("id");
         	 $('.selected-buyer').text('Selected Company : '+$('.buyers-card.active-buyer.active').data("name"))
          	$('#buyerSelectionModalCenter').modal('hide');
		  
			$.ajax({
				url: base_url + "/erp/po-manager/set_company_session",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					company_id: company_id,
					company_name:company_name
				},
				beforeSend: function() {
					location.reload();
				},
				success: function(data) {
				},
				error: function(data) {

				},
				complete: function() {

				}
			})
	      }
	  
	});
	
	/** [ Show already selected(active) buyer on clicking buyer menu] */
	$('.company-nav-menu').on('click', function() {
	    $("#buyerSelectionModalCenter").modal("show");
	});

	if($('#company_name').val() != null && $('#company_name').val() != "")
	{
		$('#'+$('#company_name').val()).addClass('active');
	}
	else
	{
		$('div.buyers-card').first().addClass("active");
	}
   
    $('.selected-buyer').text('Selected Company : '+$('.buyers-card.active-buyer.active').data("name"))
	
	$('.company-nav-menu').html($('.company-nav-div').html());

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
				if(i === 1 || i === 2 || i === 4 || i === 5 || i === 6 || i === 7) {
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
						case 5:
							select = $('#style_number_filter')
							break;
						case 6:
							select = $('#ref_number_filter')
							break;
						case 7:
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
	$("#approved-order-list_filter input[type=search]").addClass('form-control');
	$('.dataTables_length select').addClass('form-control');
    $("#approved-order-list_filter input[type=search]").attr('placeholder', 'Data Search');

    /** Create job card form validation **/
    $('#create_jobcard').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           var formData = $('#create_jobcard').serialize();
           storeJobcard(formData);
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
            url:base_url + "/erp/get-jobcard-order-details",
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

                        $.each(data.template_data.material, function(key, item){
                            $('#material').text(item)
                            $('#material').attr('data-material-id', key)
                        });

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
                            $('#style_no').text(data.template_data.style_array)
                        }
                        else
                        {
                            $('#style_no').text('')
                        }

                        if(data.template_data.refnum_array.length === 1)
                        {
                            $('#reference').text(data.template_data.refnum_array)
                        }
                        else
                        {
                            $('#reference').text('')
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
                            $('#length').text(data.template_data.length)
                        }
                        else
                        {
                            $('#length').text('')
                        } 

                        if(data.template_data.width.length === 1)
                        {
                            $('#width').text(data.template_data.width)
                        }
                        else
                        {
                            $('#width').text('')
                        } 

                        if(data.template_data.color.length === 1)
                        {
                            $('#print_color').text(data.template_data.color)
                        }
                        else
                        {
                            $('#print_color').text('')
                        }

                        /** Show ratio templates dynamic table */
                        var ratio_table_html = '';
                        if(data.template_data.ratio.length != 0)
                        {
                            $.each(data.template_data.ratio, function(key, template){
                                var total = 0;
                                ratio_table_html += '<table class="table table-bordered table-responsive ratio-list-table"  style="font-size: 12px;" id="ratio_list_table" data-ratio-ref-table="'+key+'">\
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
                                single_table_html += '<table class="table table-bordered table-responsive single-list-table"  style="font-size: 12px;" id="single_list_table_'+table+'">\
                                <tbody id="single_list_table_body"><tr>\
                                <th width="80px">ID.NO</th>';
                                $.each(erpid.order_data, function(index, common_data){
                                    single_table_html += '<td colspan="2" data-template-id="'+common_data.template_id+'" class="single-erpid-'+index+'">'+common_data.erp_id+'</td>';
                                });
                                var template_name = key.split("_");
                                single_table_html += '<td colspan="5" class="highlight_row" rowspan="3" data-single-ref-name ="'+template_name[0]+'">REFNUM  : '+template_name[0]+'</td></tr>\
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
                                        sum[col] = qty + (sum[col] || 0);
                                        col++;
                                    });

                                    single_table_html += '<td class="total-quantity-class total-quantity-row-'+row+'"><strong>'+parseInt(total_qty_sizewise)+'</strong></td><td class="total-excess"><div class="form-group has-feedback"><input type="number" step="0.01" data-step-error="Max 2 decimal places allowed." min="0" data-min-error="Value must be >= 0" class="form-control input-sm total-excess-row-table-'+table+'" required="" id="total_excess_row_table_'+row+'" data-required-error="Mandatory Field." name="total_excess_row_table_'+row+'"" placeholder="Excess %" onkeyup="return totalExcess()" ><div class="help-block with-errors"></div></div></td><td></td><td></td></tr>';
                                
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
                                                        <label for="sticker_mtr_'+template_name[0].replace(' ', '_')+'" >MTRS FOR STICKERS ('+template_name[0]+'):</label>\
                                                        <span class="sticker-mtrs" id="sticker_mtr_'+template_name[0].replace(' ', '_')+'"></span>\
                                                    </div>\
                                                    <div class="col-md-3">\
                                                    <div class="form-group has-feedback form-inline">\
                                                        <label for="settings_'+template_name[0].replace(' ', '_')+'" >SETTINGS ('+template_name[0]+') :</label>\
                                                        <input type="number" step="0.01" min="1" data-step-error="Max 2 decimal places allowed." data-min-error="Value must be >= 1" class="form-control input-sm settings" required="" name="settings_'+template_name[0].replace(' ', '_')+'" placeholder="SETTINGS" id="settings_'+template_name[0].replace(' ', '_')+'" onkeyup="return totalMeters()">\
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
                            $('#excess').prop('required', true);
                            $('#repeat_gap').prop('required', true);
                            $('#roll_width').prop('required', true);
                            $('#length_side').prop('required', true);
                            $('#no_sticker').prop('required', true);
                        } else {
                            $('.single-list-table-div').html(single_table_html);
                            $('.meter-calculation-div').html(meters_div_html);
                            /** Toggle mandatory/optional fields */
                            $('#excess').prop('required', false);
                            $('#repeat_gap').prop('required', false);
                            $('#roll_width').prop('required', false);
                            $('#length_side').prop('required', false);
                            $('#no_sticker').prop('required', false);
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
/**
 * Dynamic Excess calculation based on excess value entered.
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function calculateExcess(evt)
{
    let item = $('.total-quantity-class');
    $.each(item, function(key, value){
        let total_excess = parseFloat($(value).text() * (evt.value*1/100)).toFixed(2);
        $('#total_excess_row_table_'+key).val(total_excess);
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
                total_excess_final += parseFloat(excess);
            }
        });
        $('.total-excess-col-table-'+table).html('<strong>'+total_excess_final.toFixed(2)+'</strong>');

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
                total_excess += parseFloat($(this).text());
            }
        });
        if (!isNaN(total_qty) && !isNaN(total_excess)) {
            act_excess = parseFloat((total_excess/total_qty)*100).toFixed(2);
        }
        $('#act_excess_'+ref_name).text(act_excess)
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
            mtrs =  parseFloat((parseInt(total_qty)/parseInt(no_of_repeats)) * ((parseInt(length_side_dimension) + parseInt(repeat_gap))/1000)).toFixed(2);
        }
        
        $('#sticker_mtr_'+ref_name).text(mtrs)
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
           var total_mtr = parseFloat(settings) + parseFloat(mtrs_for_stickers);
        }
       $('#total_mtrs_'+ref_name).text(total_mtr.toFixed(2));
    });
}

/**
 * Store jobcard
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
function storeJobcard(formData)
{
    var company_id      = $('#jc_company_name').data('company-id');
    var workorder_id    = $('#work_order').text() === ''?null:$('#work_order').text();
    var created_date    = $('#today_date').text() === ''?null:$('#today_date').text();
    var article         = $('#article').text() === ''?null:$('#article').text();
    var material        = $('#material').text() === ''?null:$('#material').text();
    var customer_id     = $('#customer').data('customer-id');
    var style_no        = $('#style_no').text() === ''?null:$('#style_no').text();
    var order_type      = $('#order_type').text() === ''?null:$('#order_type').text();
    var buyer_id        = $('#buyer').data('buyer-id');
    var reference       = $('#reference').text() === ''?null:$('#reference').text();
    var disp_mode       = $('#disp_mode').val();
    var order_date      = $('#order_date').text() === ''?null:$('#order_date').text();
    var length          = $('#length').text() === ''?null:$('#length').text();
    var repeat_gap      = $('#repeat_gap').val();
    var machine         = $('#machine').val();
    var cus_del_date    = $('#cus_del_date').val();
    var roll_width      = $('#roll_width').val();
    var width           = $('#width').text() === ''?null:$('#width').text();
    var film_no         = $('#film_no').val();
    var act_del_date    = $('#act_del_date').val();
    var length_side     = $('#length_side').val();
    var excess          = $('#excess').val();
    var teeth_no        = $('#teeth_no').val();
    var inv_no          = $('#inv_no').val();
    var no_sticker      = $('#no_sticker').val();
    var finish_type     = $('#finish_type').val();
    var print_color     = $('#print_color').text() === ''?null:$('#print_color').text();
    var compiled_by     = $('#compiled_by').val();
    var barcode_read    = $('#barcode_read').val();
    var operator        = $('#operator').val();
    var checker         = $('#checker').val();
    var authorized_by   = $('#author').val();
    var axicon_verifier = $('#axicon_verifier').val();
    var approved_by     = $('#approved_by').val();
    var packer          = $('#packer').val();
    var approval_date   = $('#approval_date').val();
    var roll_issued_by  = $('#roll_issued_by').val();
    var cutter          = $('#cutter').val();
    var cartons         = $('#cartons').val();

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
        console.log(size_object_final)
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
        url: base_url + "/erp/store-jobcard-details",
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
                $('.snackbar_span').html("Jobcard Created Successfully");
                showSnackbar();
                $('#jobcardCreationModalCenter').modal('hide');
                location.reload();
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


