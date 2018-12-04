/**
 * ERP job card manager jquery/javascript file
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
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
		  
	 		var csrf_token = $('meta[name="csrf-token"]').attr('content');
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
			$('.main-content > #approved-orders-table > .dataTables_wrapper > .row > .col-sm-12:first').prepend('<div class="filter-window"><h5><strong>Jobcard Filter</strong></h5> <div class="row"> <div class="col-md-2"><select class="selectpicker" id="erpID"> <option disabled selected>SELECT AN ERP ID</option></select></div><div class="col-md-2"><select class="selectpicker" id="customerName"> <option disabled selected>SELECT A CUSTOMER</option></select></div><div class="col-md-2"><select class="selectpicker" id="poNumber"> <option disabled selected>SELECT A PO NUMBER</option></select></div><div class="col-md-2"><select class="selectpicker" id="styleNumber"> <option disabled selected>SELECT A STYLE NUMBER</option></select></div><div class="col-md-2"><select class="selectpicker" id="refNumber"> <option disabled selected>SELECT A REFERENCE</option></select></div><div class="col-md-2"><select class="selectpicker" id="delDate"> <option disabled selected>SELECT A DELIVERY DATE</option></select></div></div><div class="filter-actions"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="closeFilter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');

			//For Custom Filters
			this.api().columns().every( function (i) {
				var column = this;
				if(i === 1 || i === 2 || i === 4 || i === 5 || i === 6 || i === 7) {
					var select;
					switch(i) {
						case 1:
							select = $('#erpID')
							break;
						case 2:
							select = $('#customerName')
							break;
						case 4:
							select = $('#poNumber')
							break;
						case 5:
							select = $('#styleNumber')
							break;
						case 6:
							select = $('#refNumber')
							break;
						case 7:
							select = $('#delDate')
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
		$('#erpID').val('').change();
		$('#customerName').val('').change();
		$('#poNumber').val('').change();
		$('#styleNumber').val('').change();
		$('#refNumber').val('').change();
		$('#delDate').val('').change();
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
});
/* End of document.ready() */

/** [showSnackbar function]
 *  @author <Praveen> [praveen@wizroots.com]
 */
    function showSnackbar() {
    try {
        // Get the snackbar DIV
        var x = document.getElementById("snackbar")

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
        $.ajax({
            url:base_url + "/erp/get-jobcard-order-details",
            dataType:"json",
            method:"get",
            data:{orders:orders},
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
                            $('#company-name').text(data.template_data.company.company)
                            $('#company-address').text(data.template_data.company.company_address)
                            $('#company-logo').attr('src', data.template_data.company.company_logo);
                            $('#company-name').attr('data-company-id', data.template_data.company.company_id)
                        }

                        var workorder_id = data.template_data.company.company === 'Jupiter Garments Accessories'?'JGA-JC-':'SP-JC-';
                        $('#work_order').text( workorder_id+data.template_data.workorder_id);

                        $('#today-date').text( data.template_data.today_date);

                        data.template_data.article.length > 0?$('#article').text(data.template_data.article):'';

                        $.each(data.template_data.material, function(key, item){
                            $('#material').text(item)
                            $('#material').attr('data-material-id', key)
                        });

                        if(data.template_data.customer)
                        {
                            $('#customer').text(data.template_data.customer.customer_name)
                            $('#customer').attr('data-cutomer-id', data.template_data.customer.cutomer_id)
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
                        if(data.template_data.ratio)
                        {
                            $.each(data.template_data.ratio, function(key, template){
                                var total = 0;
                                ratio_table_html += '<table class="table table-bordered table-responsive"  style="font-size: 12px;" id="ratio-list-table">\
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
                                                        $.each(template, function(index, row){
                                                            total += row.order_quantity;
                                                            ratio_table_html += '<tr id='+row.erp_order_details_id+'>\
                                                            <td>'+row.reference_name+' '+row.ratio_pack_type+'</td>\
                                                            <td>'+row.buyer_po_number+'</td>\
                                                            <td>'+row.erp_id+'</td>\
                                                            <td>'+row.ocn_number+'</td>\
                                                            <td>'+row.order_quantity+'</td>\
                                                            <td></td>\
                                                            </tr>';
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

                        /** Show single templates dynamic table */
                        var single_table_html = '';
                        if(data.template_data.single)
                        {
                            $.each(data.template_data.single, function(key, erpid){   
                                single_table_html += '<table class="table table-bordered table-responsive"  style="font-size: 12px;" id="single-list-table">\
                                <tbody><tr>\
                                <th>ID.NO</th>';
                                $.each(erpid.order_data, function(index, common_data){
                                    single_table_html += '<td colspan="2">'+common_data.erp_id+'</td>';
                                });
                                single_table_html += '<td colspan="5" class="highlight_row" rowspan="3">REFNUM  : '+key+'</td></tr>\
                                                            <tr>\
                                                                <th>B.PO.NO</th>';
                                $.each(erpid.order_data, function(index, common_data){
                                    single_table_html += '<td colspan="2">'+common_data.buyer_po_number+'</td>';
                                });
                                single_table_html += '</tr>\
                                                            <tr>\
                                                                <th>OCN</th>';
                                $.each(erpid.order_data, function(index, common_data){
                                    single_table_html += '<td colspan="2">'+common_data.ocn_number+'</td>';
                                });

                                single_table_html += '</tr>\
                                                      <th>SIZE /PRICE</th>';
                                $.each(erpid.order_data, function(index, common_data){
                                    single_table_html += '<th>O.QNT</th>\
                                    <th>P.QNT</th>';
                                });
                                single_table_html += '<th>TOTAL</th>\
                                                                <th>EXCESS</th>\
                                                                <th>MTRS</th>\
                                                                <th>STOCK</th>\
                                                            </tr>';
                                $.each(erpid.size, function(index2, quantity_data){
                                    var total_qty_sizewise = 0;
                                    single_table_html += '<tr><td>'+index2+'</td>';
                                    $.each(quantity_data, function(index3, qty){
                                        total_qty_sizewise += qty;
                                        single_table_html +='<td class="single-quantity quantity_'+index3+'">'+qty+'</td><td></td>';
                                    });
                                    single_table_html += '<td>'+total_qty_sizewise+'</td><td></td><td></td><td></td></tr>';
                                });
                                single_table_html += '<tr>\
                                                      <th class="highlight_row">TOTAL</th>';
                                $.each(erpid.order_data, function(index, common_data){
                                    single_table_html += '<td></td>\
                                                          <td></td>';
                                });
                                
                                single_table_html += '<td>75000</td>\
                                                        <td>3000</td>\
                                                        <td>0</td>\
                                                        <td>0</td>\
                                                    </tr>';
                                    single_table_html += ' </tbody>\
                                                </table>';
                                });
                                
                            $('.single-list-table-div').html(single_table_html);
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

                    /** [ Validate edit_merchandiser_form form using bootstrap validator ] */
                    $('#create_jobcard').validator('validate');
                    $('#jobcardCreationModalCenter').modal('show');
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
    var checkbox = $("input[class='row-checkbox']", allPages);
    var customer_name = $(event).data('customer-name');
    var buyer_name = $(event).data('buyer-name');
    var ischecked= $(event).is(':checked');
    var length= $(checkbox).filter(':checked').length;

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