var washcare = 0;
var mainlabel = 0;
var composition_percentage_global = 0;
var add_page_composition = 0;
var main_label = {};
var wash_label = {};
var wash_composition = {};
var deletemain = [];
var deletewash = [];

//$('body').css("background","#f3f3f3")
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
 * This Function is used to Show all the list of orders like label orders and sticker orders.
 *
 * @return View
 */

function orders() {
    $('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/orchestra/order-management/orders",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
                var path = base_url + "/admin/orchestra/order-management/orders"
                var newObj = data.sticker_orders.concat(data.label_orders);
                var search_string= localStorage.getItem("search_string");
                $('.main-content').html(table(newObj,path)); 
                $('.top-content').html("");
                if(search_string != null)
                {
                      $('#datatable-example-2').DataTable({
                          "pageLength": 25,
                          "oSearch": { "sSearch": search_string }
                      });
                }
                else
                {
                    $('#datatable-example-2').DataTable({
                        "pageLength": 25
                    });
                }
              
                $("#datatable-example-2_filter input").addClass('form-control input-sm');
                $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
                $('#datatable-example-2_length select').addClass('form-control input-sm');

        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
        }
    })
}

/**
 * This Function is used to Show all the list of order status like label order status and sticker order status.
 *
 * @return View
 */


function orderStatus() {
    $('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/order-management/order-status",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
            if (buyer == "orchestra")
            {
                //aswathy
                var path = base_url + "/admin/" + buyer + "/order-management/order-status";

                var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders.concat(data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_poly_bag_sticker_orders)))))));
                //based on path extra column will append in the datatable
                $('.main-content').html("<button style='margin-bottom:10px;' id='po-file-upload-button' class='btn btn-primary pull-right byn-sm' type='button'>Update PO</button>");
                	
				$('.main-content').append(table(newObj,path));
                $('.top-content').html("")
				
				var uniqueNames = [];
				for(i = 0; i< newObj.length; i++){    
					if(uniqueNames.indexOf(newObj[i].UVC) === -1){
						uniqueNames.push(newObj[i].UVC);        
					}        
				}
				var options="<option>Select UVC</option>";
				for(i = 0; i< uniqueNames.length; i++){    
					 options+="<option>"+uniqueNames[i]+"</option>";
				}
				
				//starts
				
					$('#po-file-upload-button').click(function () {
						
						$('#uvc_select_div').html('<label>Select UVC</label><select id="uvc_select" class="form-control">'+options+'</select>')
						$('#new_po_div_status').addClass('hidden');
								$('#new_po1_status').addClass('hidden');
								$('#map_po_status').addClass('hidden');
								$('#update_new_po_status').addClass('hidden');
								$('#update_po_status').addClass('hidden');
								$('#new_pos_status').addClass('hidden');
								$('#map_po_div_status').addClass('hidden');
								$('#new_po_no_status').val("")
								$('#new_po_file_status').val("")
								$('#exist_artwork_div_status').addClass('hidden')
						
						
						$('#uvc_select').change(function(){
							if($(this).val() != "Select UVC")
							{
								$('#new_po_div_status').addClass('hidden');
								$('#new_po1_status').addClass('hidden');
								$('#map_po_status').addClass('hidden');
								$('#update_new_po_status').addClass('hidden');
								$('#update_po_status').addClass('hidden');
								$('#new_pos_status').addClass('hidden');
								$('#map_po_div_status').addClass('hidden');
								$('#exist_artwork_div_status').removeClass('hidden')
								
								$.ajax({
									url: base_url + "/admin/" + buyer + "/order-management/get-existing-label-orders-po",
									type: "get",
									dataType: "json",
									data: {
										uvc: $('#uvc_select').val()
									},
									beforeSend: function () {

									},
									success: function (data) {
										console.log("ADSdasdsad")
										$('#po-file-upload-group').modal('show')
										$('#po-file-upload-group').find('#exist_artwork_div_status ul').html("");
										 var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders.concat(data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_poly_bag_sticker_orders)))))));
										
										var coun = 0;
										var options = "<option>Select PO</option>";
										$(newObj).each(function (index1, value1) {
											if (value1.po_number != "" && value1.po_number != null) {
												options += "<option>" + value1.po_number + "</option>";

												coun++;
											}
											
											$('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="'+value1.type+'-'+value1.id+'" data-company="'+value1.company_id+'" class="list-group-item">Z'+value1.TemplateName+" - "+value1.TemplateType+'</li>')
										});
										
										
										$('#po_select_div').html('<label>Select PO</label><select id="select_po_status" class="form-control">' + options + '</select>')
										if (coun == 0) {
											$('#map_po_status').addClass('hidden');
											$('#new_po1_status').addClass('hidden');
											$('#new_po_div_status').removeClass('hidden');
											$('#new_pos_status').removeClass('hidden');
										}
										else
										{
											$('#map_po_status').removeClass('hidden');
											$('#new_po1_status').removeClass('hidden');
											$('#new_pos_status').addClass('hidden');
										}

										//Start bootstrap checkbox 

										$('.list-group.checked-list-box .list-group-item').each(function () {

											// Settings
											var $widget = $(this),
												$checkbox = $('<input type="checkbox" class="hidden" />'),
												color = ($widget.data('color') ? $widget.data('color') : "primary"),
												style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
												settings = {
													on: {
														icon: 'glyphicon glyphicon-check'
													},
													off: {
														icon: 'glyphicon glyphicon-unchecked'
													}
												};

											$widget.css('cursor', 'pointer')
											$widget.append($checkbox);

											// Event Handlers
											$widget.on('click', function () {
												$checkbox.prop('checked', !$checkbox.is(':checked'));
												$checkbox.triggerHandler('change');
												updateDisplay();
											});
											$checkbox.on('change', function () {
												updateDisplay();
											});


											// Actions
											function updateDisplay() {
												var isChecked = $checkbox.is(':checked');

												// Set the button's state
												$widget.data('state', (isChecked) ? "on" : "off");

												// Set the button's icon
												$widget.find('.state-icon')
													.removeClass()
													.addClass('state-icon ' + settings[$widget.data('state')].icon);

												// Update the button's color
												if (isChecked) {
													$widget.addClass(style + color + ' active');
												} else {
													$widget.removeClass(style + color + ' active');
												}
											}

											// Initialization
											function init() {

												if ($widget.data('checked') == true) {
													$checkbox.prop('checked', !$checkbox.is(':checked'));
												}

												updateDisplay();

												// Inject the icon if applicable
												if ($widget.find('.state-icon').length == 0) {
													$widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
												}
											}
											init();
										});

										$('#get-checked-data').on('click', function (event) {
											event.preventDefault();
											var checkedItems = {},
												counter = 0;
											$("#check-list-box li.active").each(function (idx, li) {
												checkedItems[counter] = $(li).text();
												counter++;
											});
											$('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
										});
										
										
									//company wise
									$('.list-group-item').click(function(){
										$count_li=0;
										$('.list-group li').each(function(key,val){
											if($(this).hasClass('active'))
											{
												$count_li++;
											}
										});
										
										if($count_li > 0)
										{
											var company_id=$(this).data('company');
											$('.list-group li').each(function(key,val){
												if($(this).data('company') != company_id)
												{
													$(this).addClass('hidden');
												}
											})
										}
										else
										{
											$('.list-group li').each(function(key,val){
												$(this).removeClass('hidden');
											})
										}

									})


										//End bootstrap checkbox 

										
										$('#new_po1_status').click(function () {
					

					var sList = 0;
					$('#exist_artwork_div_status input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {

						$('#update_new_po_status').removeClass('hidden')
					$('#update_po_status').addClass('hidden')
						$('#new_pos_status').addClass('hidden')
						$('#new_po_div_status').removeClass('hidden')
						$('#map_po_div_status').addClass('hidden')
						$('#exist_artwork_div_status').removeClass('hidden')

					} else {
						$('#check_error_status').removeClass('hidden')
					}

				});
				$('#map_po_status').click(function () {
					
					var sList = 0;
					$('#exist_artwork_div_status input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {

						$('#update_po_status').removeClass('hidden')
					    $('#update_new_po_status').addClass('hidden')
						$('#new_pos_status').addClass('hidden')
						$('#map_po_div_status').removeClass('hidden')
						$('#new_po_div_status').addClass('hidden')
						$('#exist_artwork_div_status').removeClass('hidden')
						$('#check_error_status').addClass('hidden')

					} else {
						$('#check_error_status').removeClass('hidden')
					}
				});
										
										
				$('#update_new_po_status').click(function () {
					var sList = 0;
					$('#exist_artwork_div_status input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#check_error_status').addClass('hidden')
					if ($('#new_po_no_status').val() != "") {
						var a = [];
						$('#exist_artwork_div_status input[type=checkbox]').each(function () {
							if (this.checked) {
								a.push($(this).parent().attr('data-id'));
							}
						});
						var aa = a.join(',');
						var new_po_file = $('#new_po_file_status').prop('files')[0];
						var new_po_no = $('#new_po_no_status').val();
						var csrf_token = $('meta[name="csrf-token"]').attr('content');
						var form_data = new FormData();
						form_data.append('id', aa);
						form_data.append('po_file', new_po_file);
						form_data.append('po', new_po_no);
						form_data.append('_token', csrf_token);
						$.ajax({
							url: base_url + "/admin/" + buyer + "/order-management/update-label-orders-po",
							dataType: 'text',
							cache: false,
							contentType: false,
							processData: false,
							data: form_data,
							type: 'post',
							success: function (data) {
								//location.reload()
								$('#po-file-upload-group').modal('hide')
								$('.snackbar_span').html('PO Updated Successfully');
								showSnackbar();
							},
							error: function () {
								console.log('somethig is wrong, please try later');
							}

						});
					} else {
						$('#new_po_error_status').removeClass('hidden')
					}
					} else {
						$('#check_error_status').removeClass('hidden')
					}
				});
				
				
				$('#new_pos_status').click(function () {
					
					var sList = 0;
					$('#exist_artwork_div_status input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#check_error_status').addClass('hidden')
						if ($('#new_po_no_status').val() != "") {
							var a = [];
							$('#exist_artwork_div_status input[type=checkbox]').each(function () {
								if (this.checked) {
									a.push($(this).parent().attr('data-id'));
								}
							});
							var aa = a.join(',');
							var new_po_file = $('#new_po_file_status').prop('files')[0];
							var new_po_no = $('#new_po_no_status').val();
							var csrf_token = $('meta[name="csrf-token"]').attr('content');
							var form_data = new FormData();
							form_data.append('id', aa);
							form_data.append('po_file', new_po_file);
							form_data.append('po', new_po_no);
							form_data.append('_token', csrf_token);
							$.ajax({
								url: base_url + "/admin/" + buyer + "/order-management/update-label-orders-po",
								dataType: 'text',
								cache: false,
								contentType: false,
								processData: false,
								data: form_data,
								type: 'post',
								success: function (data) {
									//location.reload()
									$('#po-file-upload-group').modal('hide')
									$('.snackbar_span').html('PO Updated Successfully');
									showSnackbar();
								},
								error: function () {
									console.log('somethig is wrong, please try later');
								}

							});
						} else {
							$('#new_po_error_status').removeClass('hidden')
						}
					
					} else {
						$('#check_error_status').removeClass('hidden')
					}
				});

				$('#update_po_status').click(function () {
					var sList = 0;
					$('#exist_artwork_div_status input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#check_error_status').addClass('hidden')
					if ($('#new_po_no_status').val() != "") {
					if ($('#select_po_status').val() != "Select PO") {
						var a = [];
						$('#exist_artwork_div_status input[type=checkbox]').each(function () {
							if (this.checked) {
								a.push($(this).parent().attr('data-id'));
							}
						});
						var aa = a.join(',');
						var csrf_token = $('meta[name="csrf-token"]').attr('content');
						$.ajax({
							url: base_url + "/admin/" + buyer + "/order-management/map-label-orders-po",
							type: "Post",
							dataType: "json",
							data: {
								_token: csrf_token,
								id: aa,
								po: $('#select_po_status').val()
							},
							beforeSend: function () {

							},
							success: function (data) {
								$('#po-file-upload-group').modal('hide')
								$('.snackbar_span').html('PO Updated Successfully');
								showSnackbar();
							},
							error: function (data) {

							},
							complete: function () {
								$(".loader").hide();
							}
						})
					} else {
						$('#select_error_status').removeClass('hidden')
					}
					}
					}
						else
						{
							$('#check_error_status').removeClass('hidden')
						}

				});


									},
									error: function (data) {

									},
									complete: function () {
										$(".loader").hide();
									}
								})
							}
							else
							{
								$('#exist_artwork_div_status').addClass('hidden')
							}
							
						})
					});
				
				
				
				//ends
				
				
				
				
					
			
                var $tableSel = $('#datatable-example-2');
                $tableSel.DataTable({
                    "pageLength": 25,
                    initComplete: function () {
                        $('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first').append(' <button class="filterbtn" type="button" onClick="showFilter()"><i class="fa fa-filter" aria-hidden="true"></i></button>');
                        $('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first .dataTables_filter').css('display', 'inline-block');
                        $('.main-content > .dataTables_wrapper > .row > .col-sm-12:first').append('<div class="filter-window"><h5><strong>Order Status Filter</strong></h5> <div class="row"> <div class="col-md-3"><input type="text" class="form-control" style="width:100%" name="daterange" /></div><div class="col-md-3"><select class="selectpicker" id="supplierName"> <option disabled selected>Select Supplier</option></select></div><div class="col-md-3"><select class="selectpicker" id="uvcNumber"> <option disabled selected>Select UVC</option></select></div><div class="col-md-3"><select class="selectpicker" id="selectStatus"> <option disabled selected>Select Status</option> </select></div></div><div class="filter-actions"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="closeFilter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');

                        //For Custom Filters
                        this.api().columns().every(function (i) {
                            var column = this;
                            if (i === 0 || i === 4 || i === 8) {
                                var select;
                                switch (i) {
                                    case 0:
                                        select = $('#uvcNumber')
                                        break;
                                    case 4:
                                        select = $('#supplierName')
                                        break;
                                    case 8:
                                        select = $('#selectStatus')
                                        break;
                                }
                                select.on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );

                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });
                                select.append('<option value=""> </option>');
                                column.data().unique().sort().each(function (d, j) {
                                    cleanText = d.replace(/<\/?[^>]+(>|$)/g, "");
                                    select.append('<option value="' + cleanText + '">' + cleanText + '</option>')
                                });
                            }
                        });
                    }
                });

                $("#reset").on("click", function () {
                    $('#uvcNumber').val('').change();
                    $('#supplierName').val('').change();
                    $('#selectStatus').val('').change();
                    $.fn.dataTableExt.afnFiltering.length = 0;
                    $tableSel.dataTable().fnDraw();
                    $('input[name="daterange"]').val('Filter by Order Date');
                    $('input[name="daterange"]').datepicker("refresh");
                    $('.selectpicker').selectpicker('val', 1);
                    $('.selectpicker').selectpicker('refresh');

                });

                $('.selectpicker').selectpicker();


                $('input[name="daterange"]').daterangepicker({

                    },
                    function (start, end, label) {
					 $.fn.dataTableExt.afnFiltering.length = 0;
                    $tableSel.dataTable().fnDraw();
                        filterByDate(5, start, end); // We call our filter function
                        $tableSel.dataTable().fnDraw(); // Manually redraw the table after filtering
                    });

                $('input[name="daterange"]').val('Filter by Order Date');


                // Clear the filter. Unlike normal filters in Datatables,
                // custom filters need to be removed from the afnFiltering array.
                $('.cancelBtn').on('click', function (e) {
                    e.preventDefault();
                    $.fn.dataTableExt.afnFiltering.length = 0;
                    $tableSel.dataTable().fnDraw();
                });
                $('.pi-view').tooltip('show')
                $('.pi-view').tooltip('toggle')
                $('.po-view').tooltip('show')
                $('.po-view').tooltip('toggle')
                $("#datatable-example-2_filter input").addClass('form-control input-sm');
                $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
                $('#datatable-example-2_length select').addClass('form-control input-sm');
            }
            else {

            }
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
        }
    })
}

function showFilter() {
	$('.filter-window').toggle();
}

/* Our main filter function
 * We pass the column location, the start date, and the end date
 */
var filterByDate = function (column, startDate, endDate) {
	// Custom filter syntax requires pushing the new filter to the global filter array
	$.fn.dataTableExt.afnFiltering.push(
		function (oSettings, aData, iDataIndex) {
			
			var columnDate=aData[column].toString().split("-").reverse().join("-");
		
			var rowDate = normalizeDate(columnDate),
				start = normalizeDate(startDate),
				end = normalizeDate(endDate);

			// If our date from the row is between the start and end
			if (start <= rowDate && rowDate <= end) {
				return true;
			} else if (rowDate >= start && end === '' && start !== '') {
				return true;
			} else if (rowDate <= end && start === '' && end !== '') {
				return true;
			} else {
				return false;
			}
		}
	);
};

// converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220
var normalizeDate = function (dateString) {
	var date = new Date(dateString);
	var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
	return normalized;
}

/**
 * This Function is used to create table.
 * Generic Table View For all Lists
 * @return View
 */

function table(data,path){
    var th = "";
    var tr = "";
    var columnsIn = data[0];

    for (var key in columnsIn) {
        if (key != "id") {
            if (key == "SupplierName" || key == "Style_Name") {
                th += "<th>" + key + "</th>";
            } else if ((key != "pi_required")&&(key != "tableName")&&(key != "template_id")&&(key !="qntytable")) {
                th += "<th style='text-align: center;'>" + key + "</th>";
            }

        }
    }
    if (path==base_url + "/admin/orchestra/order-management/order-status")
    {
       th += "<th style='text-align: center;'>Actions</th>";
    }
    $.each(data, function (index, value) {

        var action_status = false;
        tr += '<tr class="trClass" id="' + value.id + '">';
        for (var key in columnsIn) {
            if (key != "id") {
                if (key == "Type") {
                    if (value[key] == "Null") {
                        var Isstd = "-";
                    } else {
                        var Isstd = value[key] == "0" ? "Solid" : "Standard";
                    }
                    tr += '<td align="center">' + Isstd + '</td>';

                } else if (key == "Disney") {
                    if (value[key] == "Null") {
                        var Disney = "-";
                    } else {
                        var Disney = value[key] === ("DI" || 1) ? "Yes" : "No";
                    }

                    tr += '<td align="center">' + Disney + '</td>';
                } else if (key == "UVC") {

					if($('.logo').text() == "Orders")
					{
						 tr += '<td align="center"><a href="#" onclick=order_details("' + value[key] + '","' + value.BuyerPONo + '")>' + value[key] + '</a></td>';
					}
					else
					{
						 tr += '<td align="center"><a href="#" onclick=artwork_modal("' + value[key] + '","' + value.BuyerPONo + '")>' + value[key] + '</a></td>';
					}
                } else if (key == "Status") {
                    var str=value[key] ;
                    $status=str.replace(/ /g, "");
                    
                    tr += '<td align="center"><span class="label label-' + $status + '">' + value[key] + '</span></td>';
                   
                }else if (key == "PI Status") {
                    var str=value[key] ;
                    if(str != null && str != "")
                    {
                       if (str=="PI Approved"){
                        	action_status = true;
                        }
                       $status=str.replace(/ /g, "");
                       $status_text='<span class="label label-' + $status + '">' + value[key] + '</span>';
                    }
                    else
                    {
                        //$status="status";
                        $status_text=" - ";
                    }
                    
                    
                    tr += '<td align="center">'+$status_text+'</td>';
                   
                }else if (key == "PO Status") {
                    var str=value[key] ;
                    if(str != null && str != "")
                    {
                        $status=str.replace(/ /g, "");
                        $status_text='<span class="label label-' + $status + '">' + value[key] + '</span>';
                    }
                    else
                    {
                        //$status="status";
                        $status_text=" - ";
                    }
                    
                    tr += '<td align="center">'+$status_text+'</td>';
                   
                } else if (key == "DeliveryDate") {
                    var str=value[key] ;
                    if(str != null && str != "" && str != "00-00-0000")
                    {
                        $status_text=str;
                    }
                    else
                    {
                        
                        $status_text=" - ";
                    }
                    
                    tr += '<td align="center">'+$status_text+'</td>';
                   
                } else if (key == "Order Status") {
                    var str=value[key] ;
                    if(str != null && str != "")
                    {
                        $order_status=str;
                    }
                    else
                    {
                        
                        $order_status=" - ";
                    }
                    
                    tr += '<td align="center">'+$order_status+'</td>';
                   
                } else {
                    if (key == "SupplierName" || key == "Style_Name"){
                        tr += '<td>' + value[key] + '</td>';
                    }
                    else if ((key != "pi_required")&&(key != "tableName")&&(key != "template_id")&&(key != "PONumber")&&(key !="qntytable")){
                        tr += '<td align="center">' + value[key] + '</td>';
                    }
                }
            }
            if (path==base_url + "/admin/orchestra/order-management/order-status")
            { 
                if (key == "PONumber")
                { 
                   if (value[key]==null || value[key]=="")
                   {
                        tr += '<td class="text-center"><div class="form-group has-feedback"><div class="input-group"><input type="text" name="po" id="po" class="form-control input-sm po" placeholder="Enter PO" maxlength="11"><span class="input-group-btn"><button type="submit" class="btn btn-sm btn-default po-submit" onclick=setPO(this,"'+value.id+'","'+value.tableName+'")><i class="fa fa-floppy-o" aria-hidden="true"></i></button></span></div></div></td>';
                   }
                   else
                   {
                        
                        tr += '<td class="text-center"><div class="form-group has-feedback"><div class="input-group"><input type="text" name="po" id="po" class="form-control input-sm po" value="'+value[key]+'"><span class="input-group-btn"><button type="submit" class="btn btn-sm btn-default po-submit" onclick=setPO(this,"'+value.id+'","'+value.tableName+'")><i class="fa fa-floppy-o" aria-hidden="true"></i></button></span></div></div></td>';
                   }
                      
                }
                if (key == "pi_required")
                {    
                    if (value[key]==1)
                    {    
                       tr += '<td align="center"><a href="javascript:void(0)" onclick=genarateOrchetraPI("'+value.id+'","'+value.tableName+'","'+value.UVC+'","'+action_status+'","'+value.BuyerPONo+'")><i class="fa fa-file-pdf-o fa-button pi-view filterbtn options" data-placement="top" title="Proforma Invoice"></i></a><a href="javascript:void(0)" ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order" onclick=showPOUpload("'+value.id+'","'+value.tableName+'","'+value.UVC+'","'+value.qntytable+'")></i></a></td>';
                    }
                    else
                    {
                        tr += '<td align="center"><a href="javascript:void(0)" style="margin-left:36px" onclick=showPOUpload("'+value.id+'","'+value.tableName+'","'+value.UVC+'","'+value.qntytable+'") ><i class="fa fa-file-powerpoint-o fa-button po-view filterbtn options" data-placement="top" title="Purchase Order"></i></a></td>';
                    }   
                }
            }
        }
              
        tr += '</tr>';
    })
    return '<table id="datatable-example-2" class="table table-hover table-striped table-bordered" style="width: 100%"><thead><tr>' + th + '</tr></thead><tbody>' + tr + '</tbody></table></div></div></div></div></div></div>';
}

/**
 * method to show the PO file upload window
 * @param  {[type]} id         [description]
 * @param  {[type]} table_name [description]
 * @param  {[type]} uvc        [description]
 * @param  {[type]} qntytable  [description]
 * @return {[type]}            [description]
 */
function showPOUpload(id,table_name,uvc,qntytable)
{
    if (po_file!= "null")
    {
        $('#po_upload').addClass('hidden')
        $('.upload-po').addClass('hidden')
        $('#po_file').removeClass('hidden')
    }
    else
    {
        $('#po_upload').removeClass('hidden')
        $('.upload-po').removeClass('hidden')
        $('#po_file').addClass('hidden')
    }
    var po_link = base_url+po_file
    $('#po_link').attr("href",po_link);
    $('#rowID').val(id);
    $('#tableName').val(tableName);
    $('#po-file-upload').modal('show')
}
/**
 * method pass the po number for
 * @param {[type]} obj         [description]
 * @param {[type]} uvc         [description]
 * @param {[type]} po          [description]
 * @param {[type]} template_id [description]
 * @param {[type]} table_name  [description]
 */
function setPO(obj,id,table_name)
{
   $('.po').css('border-color', '');
   var po_number  = $(obj).closest('tr').find('.po').val();
   var csrf_token = $('meta[name="csrf-token"]').attr('content');
   if (po_number!="")
   {
    $.ajax({
        url: base_url + "/admin/orchestra/order-management/set-po-number",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: table_name,
            po_number:po_number,
            id:id
        },
        beforeSend: function () {
            $(".loader").show();
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
               $('.snackbar_span').html('Purchase Order Number added Successfully');
               $(obj).closest('tr').find('.po').val(po_number);
               showSnackbar();
               if (po_number==""){
                 $(obj).closest('tr').find('.po').attr('disabled');
                 $(obj).closest('tr').find('.po').attr('placeholder', "Enter PO");
               }              
            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong');
               showSnackbar();
            }
        },
        error: function (data){
            console.log(data);
        },
        complete: function () {
            $(".loader").hide();
        }
    })
   }
   else{
    $(obj).closest('tr').find('.po').focus();
    $(obj).closest('tr').find('.po').css('border-color', '#F44336');
   }
   
}
/** [showSnackbar function] */
function showSnackbar() {
	try {
		// Get the snackbar DIV
		var x = document.getElementById("snackbar")

		// Add the "show" class to DIV
		x.className = "show";

		// After 3 seconds, remove the show class from DIV
		setTimeout(function () {
			x.className = x.className.replace("show", "");
		}, 3000);
	} catch (e) {
		console.log("Error:" + e)
	}
}

/**
 * This Function is used to select the order is label order or sticker order.
 * Depends upon the decision it will show label order details page or sticker details page. 
 * @return View
 */

function order_details(uvc, po) {

    if($('.logo').text() == "Orders")
    {
        localStorage.setItem("page_navigation", "orders");
    }
    else
    {
        localStorage.setItem("page_navigation", "order-status");
    }
    //localStorage.setItem("page_navigation", search_string);
    if (po != "-") {
        sticker_order_details(uvc, po)
    } else {
        label_order_details(uvc)
    }
}

/**
 * This Function is used to get sticker order details.
 *  
 * @return View
 */

function sticker_order_details(uvc, po) {

    var search_string = $('.dataTables_filter input').val();
    localStorage.setItem("search_string", search_string);
    
    var buyer = localStorage.getItem("buyer");
    var buyer_id = localStorage.getItem("buyer_id");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/order-management/sticker-order-details",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            po: po,
            uvc: uvc
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
			if(data.customer_active == "active")
			{
				$('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
				orchestra_sticker_order_details(buyer, buyer_id, uvc, data, po)
			}
			else
			{
				$('.snackbar_span').html('Please Add Customers...');
                showSnackbar();
			}
            
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
        }
    })
}

/**
 * This Function is used to show view of sticker order details page.
 *  
 * @return View
 */

function orchestra_sticker_order_details(buyer, buyer_id, uvc, data, po) {
   
    var tr = "";
    if (data.template_name == "Standard") {
		 var update_disable=0;
        var disabled = '';
        var ttotal = '';
        var thead = '<thead><tr><th>Size</th><th>Ratio</th><th>Barcode<br/><sub>(for parcel/carton & polybag)</sub></th></tr></thead>';
        $.each(data.orchestra_parcel_details, function (index, value) {
            tr += '<tr><td class="size">' + value.Size + '</td><td class="qty">' + value.Sum + '</td><td>' + data.orchestra_parcel_details[0].ParcelBarCode + '</td></tr>';

        });



        if (data.orchestra_standard_poly_bag_sticker_orders != "") {
           
            if (data.orchestra_standard_poly_bag_sticker_orders[0].height == null) {
                $height = "";
            } else {
                $height = data.orchestra_standard_poly_bag_sticker_orders[0].height;
            }
            $age = data.orchestra_standard_poly_bag_sticker_orders[0].group_id;
			var num = 600 * 395 * $height
            var val = num / (1000 * 1000 * 1000)
            $parcelVolume = val;
            $no_of_carton = data.orchestra_standard_poly_bag_sticker_orders[0].no_of_carton;
            $total_polybag = data.orchestra_standard_poly_bag_sticker_orders[0].total_polybag;
            $final_order_qty_sets = data.orchestra_standard_poly_bag_sticker_orders[0].final_order_qty_sets;
            $order_qty = data.orchestra_standard_poly_bag_sticker_orders[0].order_qty;
            $final_order_qty = data.orchestra_standard_poly_bag_sticker_orders[0].final_order_qty;
			update_disable=1;	
        } else {
            
            $height = "";
			$parcelVolume = "";
            $age = 0;
            $no_of_carton = "";
            $total_polybag = "";
            $final_order_qty_sets = "";
            $order_qty = data.orchestra_parcel_details[0].Sum;
            $final_order_qty = "";
        }
		
		if (data.orchestra_standard_poly_bag_sticker_orders != "") {
			update_disable=1;
		}

	
        var head_qty = "Quantity for Standard Stickers";
        var head_qty1 = "Standard Parcel/Carton Sticker";
        var standard_div = '<input type="hidden" id="carton_hidden" value="standard"><div class="form-group"><label>Total Polybags per carton: </label><input id="total_polybag" value="' + $total_polybag + '" onkeypress="return checkNumbersOnly(event)" type="text" placeholder="Total Polybags per carton" class="form-control input-sm"></div> <div class="form-group"><label>Final Order Qty including Wastage: </label><div class="form-group has-feedback has-success"><input required min="'+data.polybag_moq+'" id="final_order_qty_sets" value="' + $final_order_qty_sets + '" type="number" onkeypress="return checkNumbersOnly(event)" placeholder="" class="form-control input-sm"><div class="help-block with-errors"></div></div></div><hr><h4>Standard Polybag Sticker</h4><div class="form-group"><label>Order Quantity</label><input id="order_qty" value="' + $order_qty + '" onkeypress="return checkNumbersOnly(event)" type="text" class="form-control input-sm"></div><div class="form-group"><label>Final Order Qty including Wastage</label><div class="form-group has-feedback has-success"><input required min="'+data.carton_moq+'" id="final_order_qty" value="' + $final_order_qty + '" onkeypress="return checkNumbersOnly(event)" type="number" class="form-control input-sm"><div class="help-block with-errors"></div></div></div>';
		var po_file_upload_button="po-file-upload-button-standard";
		if(update_disable == 0)
		{
			var update_disabel_button = "display:none";
			var save_hide_button = "";
			var carton_print_hidden = "hidden";
			var polybag_print_hidden = "hidden";
		}
		else
		{
			var update_disabel_button = "";
			var save_hide_button = "hidden";
			var carton_print_hidden = "";
			var polybag_print_hidden = "";
		}
		//alert(save_hide_button)
    } else {
		var update_disable=0;
        $no_of_carton = "";
        var disabled = 'disabled="disabled"';
        var tpolytot = 0;
        var tcarttot = 0;
		var po_file_upload_button="po-file-upload-button-solid";
		
		
		
        var thead = '<thead><tr><th>Size</th><th>Barcode<br/>(for parcel/carton & polybag)</th><th>Item Qty</th><th>Polybag Sticker Qty</th><th>Parcel Sticker Qty</th></tr></thead>';
        $qu = 0;
        $.each(data.orchestra_parcel_details, function (index, value) {

            if (data.orchestra_solid_carton_sticker_orders != "") {
                $carton_count = data.orchestra_solid_carton_sticker_orders[0].qty[$qu].Sum;
            } else {
                $carton_count = "";
            }

            if (data.orchestra_solid_poly_bag_sticker_orders != "") {
                $poly_count = data.orchestra_solid_poly_bag_sticker_orders[0].qty[$qu].Sum;
            } else {
                $poly_count = "";
            }

            tr += '<tr><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="qty">' + value.Sum + '</td><td><input type="hidden" class="SKU_Code" value="' + value.SKU_Code + '"><div class="form-group has-feedback has-success"><input style="margin-bottom: 0px;" type="number" required min="'+data.polybag_moq+'" onkeypress="return checkorcMOQ(this)" value="' + $poly_count + '" class="form-control tot_qty_table_poly input-sm"><div class="help-block with-errors"></div></div></td><td><div class="form-group has-feedback has-success"><input  min="'+data.carton_moq+'" style="margin-bottom: 0px;" onkeypress="return checkorcMOQ(this)"  required type="number" value="' + $carton_count + '" class="form-control tot_qty_table_carton input-sm"><div class="help-block with-errors"></div></div></td></tr>';
            if($poly_count != "")
			{
				 tpolytot += parseInt($poly_count);
			}
			if($carton_count != "")
			{
				tcarttot += parseInt($carton_count);
			}
            $qu++;
        });

        var ttotal = '<td class="text-center final_solid_poly_qty">' + tpolytot + '</td><td class="text-center final_solid_carton_qty">' + tcarttot + '</td>';

        if (data.orchestra_solid_carton_sticker_orders != "") {
           
            if (data.orchestra_solid_carton_sticker_orders[0].height == null) {
                $height = "";
            } else {
                $height = data.orchestra_solid_carton_sticker_orders[0].height;
            }
            $age = data.orchestra_solid_poly_bag_sticker_orders[0].group_id;
            var num = 600 * 395 * $height
            var val = num / (1000 * 1000 * 1000)
            $parcelVolume = val;
			update_disable=1;
			$save_hide_button="hidden";

        } else {
            
            $height = "";
            $parcelVolume = "";
            $age = 0;
			$save_hide_button="";
        }
		if (data.orchestra_solid_poly_bag_sticker_orders != "") {
			update_disable=1;
		}

        var head_qty = "Quantity for Solid Size Stickers";
        var head_qty1 = "Solid Size Parcel/Carton Sticker";
        var standard_div = '<input type="hidden" id="carton_hidden" value="solid">';
		if(update_disable == 0)
		{
			var update_disabel_button = "display:none";
			var save_hide_button = "";
			var carton_print_hidden = "hidden";
			var polybag_print_hidden = "hidden";
		}
		else
		{
			var update_disabel_button = "";
			var save_hide_button = "hidden";
			var carton_print_hidden = "";
			var polybag_print_hidden = "";
		}
    }

    var option = "<option>Age Group</option>";
    $.each(data.orchestra_age_group, function (index, value) {
        option += "<option data-color=" + value.color + " value=" + value.id + " style='background-color:" + value.color + "'>" + value.age_group_name + "</option>";
    });
    
    if(localStorage.getItem("page_navigation") == "orders")
    {
        var onclick = "orders()";
    }
    else
    {
        var onclick = "orderStatus()";
    }
    $('.top-content').append('<form id="solid_carton_form"><h3><i class="ion-ios-browsers-outline"></i> Sticker Order Details for UVC ' + uvc + ' and PO ' + po + '</h3><hr/><h5><div style="margin-bottom: 10px;"><label style="color:red" class="age_group_error hidden">Please Select Age Group</label></div><span class="sticker-details">Supplier: <strong><span id="supplier">' + data.orchestra_parcel_details[0].SupplierName + '</span></strong> </span> <span class="sticker-details">Style: <strong><span id="style">' + data.orchestra_parcel_details[0].NomApple + '</span></strong></span><span class="sticker-details">Color: <strong><span id="color">' + data.orchestra_parcel_details[0].colour_Code + '</span></strong></span><span class="sticker-details" style="display: inline-flex; white-space: nowrap;">Age Group: <strong><span id="ageGroup">  <select class="form-control" style="margin-top: -9px; margin-left: 10px;" name="carlist" id="age" form="carform">' + option + '</select></span></strong> </span></h5><hr/><div class="row"><div class="col-md-8 border-right"> <h4>' + head_qty + '</h4><div class="table-responsive"><table class="table table-condensed table-bordered table-hover" id="solidform">' + thead + '<tbody id="solidformtbody">' + tr + '</tbody><tfoot><tr class="strong"><td class="text-right">Total</td><td colspan="2">' + data.orchestra_parcel_details[0].Sum + '</td>' + ttotal + '</tr></tfoot></table><div class="bottom-bar"><div class="modal-footer"> <button onclick="'+onclick+'" class="btn btn-danger pull-right btn-xs"><i class="fa fa-times" aria-hidden="true"></i>Close</button> <button type="submit" id="save_sticker_button" class="btn btn-xs btn-success '+save_hide_button+'"><i class="fa fa-floppy-o"></i>Save</button><button type="button" class="btn btn-success  pull-right btn-xs" data-toggle="modal" id="'+po_file_upload_button+'" style="'+update_disabel_button+'" >Update PO</button></div></div></form></div></div><div class="col-md-4"><h4>' + head_qty1 + '</h4><div class="row"><div class="col-md-6"><div class="form-group"> <label>Length</label><div class="input-group"><input type="text" disabled value="600" class="form-control input-sm" placeholder="Length" id="length"  onkeypress="return event.charCode >= 48 && event.charCode <= 57"><span class="input-group-addon">mm</span></div></div></div><div class="col-md-6"><div class="form-group"><label>Width</label><div class="input-group"><input type="text" disabled value="395" class="form-control input-sm" id="width" placeholder="Width" onkeypress="return event.charCode >= 48 && event.charCode <= 57"> <span class="input-group-addon">mm</span></div> </div></div><div class="col-md-6"> <div class="form-group"><label>Height <sub>(Specify only if needed)</sub></label> <div class="input-group"> <input type="text" value="' + $height + '" class="form-control input-sm" data-toggle="modal" data-target=".bs-example-modal-sm" data-backdrop="static" data-keyboard="false" placeholder="Height" id="modal_height" onkeypress="return checkNumbersOnly(event)"> <span class="input-group-addon">mm</span></div></div> </div><div class="col-md-6"><h5 style="padding-top: 25px;">Parcel Volume : <strong><span id="parcelVolume">' + $parcelVolume + '</span></strong></h5></div></div><div class="form-group"> <label>Number of Cartons </label> <div class="row"><div class="col-md-6" style="width: 130px;"><input type="text" value="" disabled="disabled" class="form-control input-sm" placeholder=""></div><span style="font-size: 24px;margin-left:-135px;">/</span> <div class="col-md-6" style="width: 130px;"><input ' + disabled + ' data-toggle="modal" data-target=".needed_modal" value="' + $no_of_carton + '" id="needed_modal" data-backdrop="static" data-keyboard="false" type="text" value="" class="form-control input-sm" placeholder="Optional"></div>  </div> <sub>(Specify only if needed)</sub></div>' + standard_div + ' </div></div>');
    $('#solid_carton_form');

    $('.main-content').html('<input type="hidden" value="' + data.std_carton_id + '" id="std_carton_id"><input type="hidden" value="' + data.std_poly_id + '" id="std_poly_id"><input type="hidden" value="' + data.carton_id + '" id="carton_id"><input type="hidden" value="' + data.poly_id + '" id="poly_id"><input type="hidden" value="' + data.carton_template_id + '" id="carton_template_id"><input type="hidden" value="' + data.polybag_template_id + '" id="polybag_template_id"><input type="hidden" value="' + uvc + '" id="uvc_id"><input type="hidden" value="' + data.orchestra_parcel_details[0].id + '" id="parcel_id"><input type="hidden" value="' + data.orchestra_parcel_details[0].Season_Code + '" id="season_code"><input type="hidden" value="' + po + '" id="po"><hr/><h4>Carton and Polybag Stickers</h4><div class="row"> <div class="col-md-4"> <div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"> <div class="card-title"> <p class="pull-right" style="cursor:pointer">Click to view</p> <h5>Carton Sticker</h5></div><div class="img_artwork"><object id="svg2" style="width:300px;height:200px; border:2px solid" class="center-block svg2" data="' + data.carton_template + '" type="image/svg+xml"></object></div></div> </div><div class="modal fade bs-example-modal-lg artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Carton Sticker</h4></div><div class="modal-body"><object id="svg3" style="width:575px;height:378;border: 2px #000 solid;" class="center-block" data="' + data.carton_template + '" type="image/svg+xml"></object></div><div id="svgdiv" style="display:none"><svg id="ean-13"></svg></div><div class="modal-footer"> <button type="button" class="btn btn-primary '+carton_print_hidden+'" id="carton_print_button" onclick=printDiv("svg3",' + data.orchestra_parcel_details[0].IsSTD + ',"'+uvc+'")>Print</button><button type="button" class="btn btn-default pull-right input-sm" data-dismiss="modal" aria-label="Close">Close</button></div> </div> </div> </div><div class="col-md-4"><div class="card add-card" data-toggle="modal" data-target=".bs-example-modal-lg2" data-backdrop="static" data-keyboard="false"><div class="card-title"><p class="pull-right" style="cursor:pointer">Click to view</p><h5>Polybag Sticker</h5></div><div class="img_artwork"><object id="svg1" class="center-block" style="width:300px;height:200px;border:2px solid" data="' + data.polybag_template + '" type="image/svg+xml"></object></div></div> </div><div class="modal fade bs-example-modal-lg2 artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Polybag Sticker</h4></div><div class="modal-body"><object id="svg4" class="center-block" style="width:500px;height:330px;border:2px solid" data="' + data.polybag_template + '" type="image/svg+xml"></object> </div><div class="modal-footer"><button type="button" class="btn btn-primary '+polybag_print_hidden+'" id="polybag_print_button" onclick=printDiv("svg4",' + data.orchestra_parcel_details[0].IsSTD + ',"'+uvc+'")>Print</button><button type="button" class="btn btn-default pull-right input-sm" data-dismiss="modal" aria-label="Close">Close</button></div></div></div></div> </div> </div> </div></div><div class="modal fade bs-example-modal-sm artworkCol" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-sm" role="document"><div class="modal-content"><div class="modal-header alert-danger"><button type="button" class="close" data-dismiss="modal" aria-label="Close" id="modal_close"><span aria-hidden="true">&times;</span></button><p>Use this column only if needed</p></div></div>')

    $('#solid_carton_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSolidStickers()
        }
    })
	
	
	$('.bs-example-modal-lg2').on('shown.bs.modal', function (e) {
	  	var mySVG1 = document.getElementById("svg4");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("round").style.fill = $('#age option:selected').data('color');
	})

	
	$('#po-file-upload-button-solid').click(function () {

		$('#new_po_div').addClass('hidden')
		$('#map_po_div').addClass('hidden')
		$('#exist_artwork_div').removeClass('hidden')
		$('#update_new_po').addClass('hidden')
		$('#new_po1').removeClass('hidden')
		$('#map_po').removeClass('hidden')

		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/get-existing-solid-sticker-orders-po",
			type: "get",
			dataType: "json",
			data: {
				uvc: $('#uvc_id').val()
			},
			beforeSend: function () {

			},
			success: function (data) {



				$('#po-file-upload-div').modal('show')
				$('#po-file-upload-div').find('#exist_artwork_div ul').html("");
				var newObj = data.orchestra_solid_carton_sticker_orders.concat(data.orchestra_solid_poly_bag_sticker_orders);
				var coun = 0;
				var options = "<option>Select PO</option>";
				$(newObj).each(function (index1, value1) {
					if (value1.po_number != "" && value1.po_number != null) {
						options += "<option>" + value1.po_number + "</option>";

						coun++;
					}
					$('#po-file-upload-div').find('#exist_artwork_div ul').append('<li data-id="'+value1.type+'-'+value1.id+'" data-company="'+value1.company_id+'" class="list-group-item">'+value1.TemplateName+" - "+value1.TemplateType+'</li>')
				});
				
				
				
				//Start bootstrap checkbox 
				
				$('.list-group.checked-list-box .list-group-item').each(function () {

					// Settings
					var $widget = $(this),
						$checkbox = $('<input type="checkbox" class="hidden" />'),
						color = ($widget.data('color') ? $widget.data('color') : "primary"),
						style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
						settings = {
							on: {
								icon: 'glyphicon glyphicon-check'
							},
							off: {
								icon: 'glyphicon glyphicon-unchecked'
							}
						};

					$widget.css('cursor', 'pointer')
					$widget.append($checkbox);

					// Event Handlers
					$widget.on('click', function () {
						$checkbox.prop('checked', !$checkbox.is(':checked'));
						$checkbox.triggerHandler('change');
						updateDisplay();
					});
					$checkbox.on('change', function () {
						updateDisplay();
					});


					// Actions
					function updateDisplay() {
						var isChecked = $checkbox.is(':checked');

						// Set the button's state
						$widget.data('state', (isChecked) ? "on" : "off");

						// Set the button's icon
						$widget.find('.state-icon')
							.removeClass()
							.addClass('state-icon ' + settings[$widget.data('state')].icon);

						// Update the button's color
						if (isChecked) {
							$widget.addClass(style + color + ' active');
						} else {
							$widget.removeClass(style + color + ' active');
						}
					}

					// Initialization
					function init() {

						if ($widget.data('checked') == true) {
							$checkbox.prop('checked', !$checkbox.is(':checked'));
						}

						updateDisplay();

						// Inject the icon if applicable
						if ($widget.find('.state-icon').length == 0) {
							$widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
						}
					}
					init();
				});

				$('#get-checked-data').on('click', function (event) {
					event.preventDefault();
					var checkedItems = {},
						counter = 0;
					$("#check-list-box li.active").each(function (idx, li) {
						checkedItems[counter] = $(li).text();
						counter++;
					});
					$('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
				});
				
				//company wise
				$('.list-group-item').click(function(){
					$count_li=0;
					$('.list-group li').each(function(key,val){
						if($(this).hasClass('active'))
						{
							$count_li++;
						}
					});

					if($count_li > 0)
					{
						var company_id=$(this).data('company');
						$('.list-group li').each(function(key,val){
							if($(this).data('company') != company_id)
							{
								$(this).addClass('hidden');
							}
						})
					}
					else
					{
						$('.list-group li').each(function(key,val){
							$(this).removeClass('hidden');
						})
					}
					
				})
				
				//End bootstrap checkbox 
				
		$('#selectpo').html('<label>Select PO</label><select id="select_po" class="form-control">' + options + '</select>')
				if (coun == 0) {
					$('#map_po').addClass('hidden');
					$('#new_po1').addClass('hidden');
					$('#new_po_div').removeClass('hidden');
					$('#update_new_po').removeClass('hidden');
				}
				
				$('#update_new_po').click(function () {
					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#check_error').addClass('hidden')
					if ($('#new_po_no').val() != "") {
						var a = [];
						$('#exist_artwork_div input[type=checkbox]').each(function () {
							if (this.checked) {
								a.push($(this).parent().attr('data-id'));
							}
						});
						var aa = a.join(',');
						var new_po_file = $('#new_po_file').prop('files')[0];
						var new_po_no = $('#new_po_no').val();
						var csrf_token = $('meta[name="csrf-token"]').attr('content');
						var form_data = new FormData();
						form_data.append('id', aa);
						form_data.append('po_file', new_po_file);
						form_data.append('po', new_po_no);
						form_data.append('_token', csrf_token);
						$.ajax({
							url: base_url + "/admin/" + buyer + "/order-management/update-label-orders-po",
							dataType: 'text',
							cache: false,
							contentType: false,
							processData: false,
							data: form_data,
							type: 'post',
							success: function (data) {
								//location.reload()
								$('#po-file-upload-div').modal('hide')
								$('.snackbar_span').html('PO Updated Successfully');
								showSnackbar();
							},
							error: function () {
								console.log('somethig is wrong, please try later');
							}

						});
					} else {
						$('#new_po_error').removeClass('hidden')
					}
					} else {
						$('#check_error').removeClass('hidden')
					}


				});

				$('#update_po').click(function () {
					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#check_error').addClass('hidden')
					if ($('#select_po').val() != "Select PO") {
						var a = [];
						$('#exist_artwork_div input[type=checkbox]').each(function () {
							if (this.checked) {
								a.push($(this).parent().attr('data-id'));
							}
						});
						var aa = a.join(',');
						var csrf_token = $('meta[name="csrf-token"]').attr('content');
						$.ajax({
							url: base_url + "/admin/" + buyer + "/order-management/map-label-orders-po",
							type: "Post",
							dataType: "json",
							data: {
								_token: csrf_token,
								id: aa,
								po: $('#select_po').val()
							},
							beforeSend: function () {

							},
							success: function (data) {
								$('#po-file-upload-div').modal('hide')
								$('.snackbar_span').html('PO Updated Successfully');
								showSnackbar();
							},
							error: function (data) {

							},
							complete: function () {
								$(".loader").hide();
							}
						})
					} else {
						$('#select_error').removeClass('hidden')
					}
					} else {
						$('#check_error').removeClass('hidden')
					}

				});
				$('#new_po1').click(function () {
					

					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#update_new_po').removeClass('hidden')
					$('#update_po').addClass('hidden')
						$('#new_po_div').removeClass('hidden')
						$('#map_po_div').addClass('hidden')
						$('#exist_artwork_div').removeClass('hidden')
					} else {
						$('#check_error').removeClass('hidden')
					}

				});
				$('#map_po').click(function () {
					
					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#update_po').removeClass('hidden')
					$('#update_new_po').addClass('hidden')
						$('#map_po_div').removeClass('hidden')
						$('#new_po_div').addClass('hidden')
						$('#exist_artwork_div').removeClass('hidden')
						$('#check_error').addClass('hidden')
					} else {
						$('#check_error').removeClass('hidden')
					}



				});
			},
			error: function (data) {

			},
			complete: function () {
				$(".loader").hide();
			}
		})
	});
	
	
	$('#po-file-upload-button-standard').click(function () {

		$('#new_po_div').addClass('hidden')
		$('#map_po_div').addClass('hidden')
		$('#exist_artwork_div').removeClass('hidden')
		$('#update_new_po').addClass('hidden')
		$('#new_po1').removeClass('hidden')
		$('#map_po').removeClass('hidden')

		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/get-existing-standard-sticker-orders-po",
			type: "get",
			dataType: "json",
			data: {
				uvc: $('#uvc_id').val()
			},
			beforeSend: function () {

			},
			success: function (data) {



				$('#po-file-upload-div').modal('show')
				$('#po-file-upload-div').find('#exist_artwork_div ul').html("");
				var newObj = data.orchestra_standard_carton_sticker_orders.concat(data.orchestra_standard_poly_bag_sticker_orders);
				var coun = 0;
				var options = "<option>Select PO</option>";
				$(newObj).each(function (index1, value1) {
					if (value1.po_number != "" && value1.po_number != null) {
						options += "<option>" + value1.po_number + "</option>";

						coun++;
					}
					$('#po-file-upload-div').find('#exist_artwork_div ul').append('<li data-id="'+value1.type+'-'+value1.id+'" data-company="'+value1.company_id+'" class="list-group-item">'+value1.TemplateName+" - "+value1.TemplateType+'</li>')
				});
				
				
				
				//Start bootstrap checkbox 
				
				$('.list-group.checked-list-box .list-group-item').each(function () {

					// Settings
					var $widget = $(this),
						$checkbox = $('<input type="checkbox" class="hidden" />'),
						color = ($widget.data('color') ? $widget.data('color') : "primary"),
						style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
						settings = {
							on: {
								icon: 'glyphicon glyphicon-check'
							},
							off: {
								icon: 'glyphicon glyphicon-unchecked'
							}
						};

					$widget.css('cursor', 'pointer')
					$widget.append($checkbox);

					// Event Handlers
					$widget.on('click', function () {
						$checkbox.prop('checked', !$checkbox.is(':checked'));
						$checkbox.triggerHandler('change');
						updateDisplay();
					});
					$checkbox.on('change', function () {
						updateDisplay();
					});


					// Actions
					function updateDisplay() {
						var isChecked = $checkbox.is(':checked');

						// Set the button's state
						$widget.data('state', (isChecked) ? "on" : "off");

						// Set the button's icon
						$widget.find('.state-icon')
							.removeClass()
							.addClass('state-icon ' + settings[$widget.data('state')].icon);

						// Update the button's color
						if (isChecked) {
							$widget.addClass(style + color + ' active');
						} else {
							$widget.removeClass(style + color + ' active');
						}
					}

					// Initialization
					function init() {

						if ($widget.data('checked') == true) {
							$checkbox.prop('checked', !$checkbox.is(':checked'));
						}

						updateDisplay();

						// Inject the icon if applicable
						if ($widget.find('.state-icon').length == 0) {
							$widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
						}
					}
					init();
				});

				$('#get-checked-data').on('click', function (event) {
					event.preventDefault();
					var checkedItems = {},
						counter = 0;
					$("#check-list-box li.active").each(function (idx, li) {
						checkedItems[counter] = $(li).text();
						counter++;
					});
					$('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
				});
				
				//company wise
				$('.list-group-item').click(function(){
					$count_li=0;
					$('.list-group li').each(function(key,val){
						if($(this).hasClass('active'))
						{
							$count_li++;
						}
					});

					if($count_li > 0)
					{
						var company_id=$(this).data('company');
						$('.list-group li').each(function(key,val){
							if($(this).data('company') != company_id)
							{
								$(this).addClass('hidden');
							}
						})
					}
					else
					{
						$('.list-group li').each(function(key,val){
							$(this).removeClass('hidden');
						})
					}
					
				})
				
				//End bootstrap checkbox 
				
		$('#selectpo').html('<label>Select PO</label><select id="select_po" class="form-control">' + options + '</select>')
				if (coun == 0) {
					$('#map_po').addClass('hidden');
					$('#new_po1').addClass('hidden');
					$('#new_po_div').removeClass('hidden');
					$('#update_new_po').removeClass('hidden');
				}
				
				$('#update_new_po').click(function () {
					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#check_error').addClass('hidden')
					if ($('#new_po_no').val() != "") {
						var a = [];
						$('#exist_artwork_div input[type=checkbox]').each(function () {
							if (this.checked) {
								a.push($(this).parent().attr('data-id'));
							}
						});
						var aa = a.join(',');
						var new_po_file = $('#new_po_file').prop('files')[0];
						var new_po_no = $('#new_po_no').val();
						var csrf_token = $('meta[name="csrf-token"]').attr('content');
						var form_data = new FormData();
						form_data.append('id', aa);
						form_data.append('po_file', new_po_file);
						form_data.append('po', new_po_no);
						form_data.append('_token', csrf_token);
						$.ajax({
							url: base_url + "/admin/" + buyer + "/order-management/update-label-orders-po",
							dataType: 'text',
							cache: false,
							contentType: false,
							processData: false,
							data: form_data,
							type: 'post',
							success: function (data) {
								$('#po-file-upload-div').modal('hide')
								$('.snackbar_span').html('PO Updated Successfully');
								showSnackbar();
							},
							error: function () {
								console.log('somethig is wrong, please try later');
							}

						});
					} else {
						$('#new_po_error').removeClass('hidden')
					}
					} else {
						$('#check_error').removeClass('hidden')
					}


				});

				$('#update_po').click(function () {
					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#check_error').addClass('hidden')
					if ($('#select_po').val() != "Select PO") {
						var a = [];
						$('#exist_artwork_div input[type=checkbox]').each(function () {
							if (this.checked) {
								a.push($(this).parent().attr('data-id'));
							}
						});
						var aa = a.join(',');
						var csrf_token = $('meta[name="csrf-token"]').attr('content');
						$.ajax({
							url: base_url + "/admin/" + buyer + "/order-management/map-label-orders-po",
							type: "Post",
							dataType: "json",
							data: {
								_token: csrf_token,
								id: aa,
								po: $('#select_po').val()
							},
							beforeSend: function () {

							},
							success: function (data) {
								$('#po-file-upload-div').modal('hide')
								$('.snackbar_span').html('PO Updated Successfully');
								showSnackbar();
							},
							error: function (data) {

							},
							complete: function () {
								$(".loader").hide();
							}
						})
					} else {
						$('#select_error').removeClass('hidden')
					}
					} else {
						$('#check_error').removeClass('hidden')
					}

				});
				$('#new_po1').click(function () {
					

					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#update_new_po').removeClass('hidden')
					$('#update_po').addClass('hidden')
						$('#new_po_div').removeClass('hidden')
						$('#map_po_div').addClass('hidden')
						$('#exist_artwork_div').removeClass('hidden')
					} else {
						$('#check_error').removeClass('hidden')
					}

				});
				$('#map_po').click(function () {
					
					var sList = 0;
					$('#exist_artwork_div input[type=checkbox]').each(function () {
						if (this.checked) {
							sList++;
						}
					});

					if (sList > 0) {
						$('#update_po').removeClass('hidden')
					$('#update_new_po').addClass('hidden')
						$('#map_po_div').removeClass('hidden')
						$('#new_po_div').addClass('hidden')
						$('#exist_artwork_div').removeClass('hidden')
						$('#check_error').addClass('hidden')
					} else {
						$('#check_error').removeClass('hidden')
					}



				});
			},
			error: function (data) {

			},
			complete: function () {
				$(".loader").hide();
			}
		})
	});


    $('.tot_qty_table_poly').keyup(function () {
        var tot_qty_table_poly_final = 0;
        $('#solidform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table_poly').val() != undefined && $(this).find('.tot_qty_table_poly').val() != "") {
                tot_qty_table_poly_final += parseInt($(this).find('.tot_qty_table_poly').val());
            }

        })
        $('.final_solid_poly_qty').text(tot_qty_table_poly_final);
    });

    $('.tot_qty_table_carton').keyup(function () {
        var tot_qty_table_carton_final = 0;
        $('#solidform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table_carton').val() != undefined && $(this).find('.tot_qty_table_carton').val() != "") {
                tot_qty_table_carton_final += parseInt($(this).find('.tot_qty_table_carton').val());
            }
        })
        $('.final_solid_carton_qty').text(tot_qty_table_carton_final);
    });

    barcode_carton('svg2', data)
    barcode_carton('svg3', data)
    barcode_polybag('svg1', data)
    barcode_polybag('svg4', data)
    $("#modal_height").keyup(function () {
        var num = $('#length').val() * $('#width').val() * this.value
        var val = num / (1000 * 1000 * 1000)
        $('#parcelVolume').text(val)
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("volume").innerHTML = val;
        svgDoc1.getElementById("height").innerHTML = this.value;
    });  
	$("#total_polybag").keyup(function () {
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("total_polybags_per_carton").innerHTML = this.value;
    });
	$("#needed_modal").keyup(function () {
        var svgDoc1;
        var mySVG1 = document.getElementById("svg2");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("no_of_cartons").innerHTML = this.value;
    });
    $("#modal_close").click(function () {
        $('#modal_height').removeAttr('data-target')
    });
    $(".modal_close1").click(function () {
        $('#needed_modal').removeAttr('data-target')
    });
    if ($age != 0) {
        $('#age').val($age);
        var mySVG1 = document.getElementById("svg1");
        var svgDoc1;
        mySVG1.addEventListener("load", function () {
            svgDoc1 = mySVG1.contentDocument;
            svgDoc1.getElementById("round").style.fill = $('#age option:selected').data('color');
        }, false);

        var mySVG = document.getElementById("svg4");
        var svgDoc;
        mySVG.addEventListener("load", function () {
            svgDoc = mySVG.contentDocument;
            svgDoc.getElementById("round").style.fill = $('#age option:selected').data('color');
        }, false);

    }


    $('#age').change(function () {
        $('#age_modal').modal("show");
        var mySVG1 = document.getElementById("svg1");
        svgDoc1 = mySVG1.contentDocument;
        svgDoc1.getElementById("round").style.fill = $('#age option:selected').data('color');
    });

}
/**
 * This Function is used Print Stickers.
 *  
 * @return Pdf
 */

function printDiv(data, isstd,uvc) {
	if (data == "svg4" && isstd == "0") {
		var a = document.getElementById(data);
		var svgDoc = a.contentDocument;
		var name=uvc+"-SOL-PS";
		var ajax_count = 0;
		var svgarr = [];
		var index_arr = [];
		$('#solidform tbody tr').each(function (key, val) {
			
			if(index_arr.includes($(this).find('.size').text()) != true)
			{
				if ($(this).find('.size').text() != "") {
						index_arr.push($(this).find('.size').text())
						var svgRoot = svgDoc.documentElement;
						var size = $(this).find('.size').text();
						var barcode = $(this).find('.barcode').text();
						var SKU_Code = $(this).find('.SKU_Code').val();
						svgDoc.getElementById("size").innerHTML = size;
						svgDoc.getElementById("parcel_code").innerHTML = SKU_Code;
						jsbarcodesolidpoly(barcode, 'barcode', svgDoc)
						var s = new XMLSerializer();
						var d = svgRoot;
						var str = s.serializeToString(d);
						var svg = encodeURI(str)
						svgarr.push(svg)
						
				}
			}
			ajax_count++;
			
		});
		
		if (ajax_count == document.getElementById("solidformtbody").rows.length) {
			var svg_fin = svgarr.join(',_');
			Popupsmall(svg_fin, name,svgDoc);
		}
		
		
	} else if (data == "svg4" && isstd == "1") {
		var a = document.getElementById(data);
		var svgDoc = a.contentDocument;
		var svgRoot = svgDoc.documentElement;
		var name=uvc+"-STD-PS";
		PopupTable(svgRoot,name);
	} else if (data == "svg3" && isstd == "1") {
		var a = document.getElementById(data);
		var svgDoc = a.contentDocument;
		var svgRoot = svgDoc.documentElement;
		var name=uvc+"-STD-CS";
		Popup(svgRoot,name);
	} else {
		var a = document.getElementById(data);
		var svgDoc = a.contentDocument;
		var name=uvc+"-SOL-CS";
		var ajax_count = 0;
		var svgarr = [];
		var index_arr = [];
		$('#solidform tbody tr').each(function (key, val) {
			if(index_arr.includes($(this).find('.size').text()) != true)
			{
				if ($(this).find('.size').text() != "") {
						index_arr.push($(this).find('.size').text())
						var svgRoot = svgDoc.documentElement;
						var size = $(this).find('.size').text();
						var barcode = $(this).find('.barcode').text();
						var SKU_Code = $(this).find('.SKU_Code').val();
						svgDoc.getElementById("size").innerHTML = size;
						svgDoc.getElementById("parcelcode").innerHTML = SKU_Code;
						jsbarcodesolidcartonA(barcode, 'barcode1', svgDoc)
						jsbarcodesolidcartonB(barcode, 'barcode2', svgDoc)

						var s = new XMLSerializer();
						var d = svgRoot;
						var str = s.serializeToString(d);
						var svg = encodeURI(str)
						svgarr.push(svg)
				}
			}
			ajax_count++;
		});
		
		if (ajax_count == document.getElementById("solidformtbody").rows.length) {
			var svg_fin = svgarr.join(',_');
			PopupArr(svg_fin, name,svgDoc);
		}
	}

}

/**
 * This Function is used Print Hang Tag .
 *  
 * @return Pdf
 */

function printHangDiv(data,disney) {

	var a = document.getElementById('svg_hang_front');
	var svgDoc = a.contentDocument;
	var svgRoot = svgDoc.documentElement;

	var a1 = document.getElementById(data);
	var svgDoc1 = a1.contentDocument;
	
	var svgRoot1 = svgDoc1.documentElement;
	
	var index_arr=[];
	var svgarr = [];
	var svgarr_front = [];
	var i=0;
	var ajax=0;
	$('#hang_tag_table tbody tr').each(function (key, val) {
		if(index_arr.includes($(this).find('.size').text()) != true)
		{
			if($(this).find('.size').text() != "")
				{
					var size_txt = $(this).find('.size').text();
					var uvc_tco = $(this).find('.uvc_tco').text();
					index_arr.push($(this).find('.size').text())
					var size = $(this).find('.size').text().split(" ")

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
					
					var code = $(this).find('.barcode').text();
					
					$.ajax({
						url: base_url + "/admin/order-management/get-tsl-size",
						type: 'GET',
						async: false,
						data: {
							size: $(this).find('.size').text(),
							uvc_tco:uvc_tco
						},
						tryCount: 0,
						retryLimit: 2,
						success: function (data) {
							//console.log(data)

							svgDoc1.getElementById("size").innerHTML = size[0];
							svgDoc1.getElementById("size_french").innerHTML = size[1];
							svgDoc1.getElementById("size_english").innerHTML = size1;
							svgDoc1.getElementById("uvc_tco").innerHTML = uvc_tco;
							
							svgDoc.getElementById("size").innerHTML = size[0];
							svgDoc.getElementById("size_french").innerHTML = size[1];
							svgDoc.getElementById("size_english").innerHTML = size1;
							
							if(data.opqt.length > 0)
							{
								$(data.opqt).each(function(index,value){
								switch(value.Currency)
								{
										case "EUR":
										var svg_price =	value.Price.split(',');
										var svg_club_price = value.Club_Price.split(',');
										svgDoc.getElementById("price1").innerHTML = svg_price[0];
										svgDoc.getElementById("price2").innerHTML = ","+svg_price[1];
										svgDoc.getElementById("club_price1").innerHTML = svg_club_price[0];
										svgDoc.getElementById("club_price2").innerHTML = ","+ 	svg_club_price[1];
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
								})
							}
							else
							{
								svgDoc.getElementById("price1").innerHTML = "";
								svgDoc.getElementById("price2").innerHTML = "";
								svgDoc.getElementById("club_price1").innerHTML = "";
								svgDoc.getElementById("club_price2").innerHTML = "";
								svgDoc1.getElementById("canada_price").innerHTML = "";
								svgDoc1.getElementById("canada_club_price").innerHTML = "";
								svgDoc1.getElementById("swiss_price").innerHTML = "";
								svgDoc1.getElementById("swiss_club_price").innerHTML = "";
								svgDoc1.getElementById("algeria_price").innerHTML = "";
								svgDoc1.getElementById("algeria_club_price").innerHTML = "";
								svgDoc1.getElementById("morocco_price").innerHTML = "";
								svgDoc1.getElementById("morroco_club_price").innerHTML = "";
								svgDoc1.getElementById("morris_price").innerHTML = "";
								svgDoc1.getElementById("morris_club_price").innerHTML = "";
								svgDoc1.getElementById("saudi_club_price").innerHTML = "";
								svgDoc1.getElementById("us_price").innerHTML = "";
								svgDoc1.getElementById("us_club_price").innerHTML = "";
							}
							
							
							
							if(data.size_chart.length > 0)
							{
								svgDoc1.getElementById("cm").innerHTML =  data.size_chart[0].size;
//								if(data.size_chart[0].size.length == 2)
//								{
//								   svgDoc1.getElementById("cm").setAttribute('transform', 'matrix(1.0057 0 0 1 106.096 52.0822)');
//								}
//								else
//								{
//								   svgDoc1.getElementById("cm").setAttribute('transform', 'matrix(1.0057 0 0 1 102.096 52.0822)');
//								}
								
							}
							else
							{
								svgDoc1.getElementById("cm").innerHTML = "";
							}
							var node = $("#tree1").jstree('get_selected', true)[0].text;
							//alert(node)
							if(disney != "DI")
							{
								jsbarcode_hang(code, "barcode1", svgDoc1)
								svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 10 50)');
							}
							else
							{
								
                               jsbarcode_hang_disney(code, "barcode1", svgDoc1)
					           if(node == "COD3")
							   {
									 svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 0 -17)');
								}
								else if(node == "DBB1" || node == "DBB2" || node == "DBB3")
								{
									svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 0 -20)');
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
					
				i++;	
				}
			
		}
	});
	
	var svg_fin = svgarr.join(',_');
	var svg_fin_front = svgarr_front.join(',_');
	
	if(i == ajax)
	{
		//PopupWash(svg_fin,svgRoot1_back,svgRoot1_middle, dataid,svgDoc1);
		
		PopupHang(svg_fin_front, svg_fin,svgDoc,svgDoc1,disney);
	}
	
	
}

/**
 * This Function is used Print Wash Care Labels.
 *  
 * @return Pdf
 */

function printWashDiv(data,data_back,data_middle,dataid) {
	var a1 = document.getElementById(data);
	var svgDoc1 = a1.contentDocument;
	var svgRoot1 = svgDoc1.documentElement;
	var index_arr=[];
	var svgarr = [];
	var i=0;
	var ajax=0;
		//alert('wash_care_label_table_'+dataid)
	$('#wash_care_label_table_'+dataid+' tbody tr').each(function (key, val) {
		if(index_arr.includes($(this).find('.size').text()) != true)
		{
			if($(this).find('.size').text() != "")
				{
					var size_txt = $(this).find('.size').text();
					var uvc_tco = $(this).find('.uvc_tco').text();
					index_arr.push($(this).find('.size').text())
					var size = $(this).find('.size').text().split(" ")

					if (size[1] == "ANS") {
						var size1 = "years";
					} else {
						var size1 = "months";
					}
					
					var code = $(this).find('.barcode').text();
					
					$.ajax({
						url: base_url + "/admin/order-management/get-tsl-size",
						type: 'GET',
						async: false,
						data: {
							size: $(this).find('.size').text()
						},
						tryCount: 0,
						retryLimit: 2,
						success: function (data) {
							//console.log(data)
							if(data.size_chart.length > 0 )
							{
								svgDoc1.getElementById("size_in_fr_en1").innerHTML = data.size_chart[0].size+" cm ";
								svgDoc1.getElementById("size_in_fr_en2").innerHTML = data.size_chart[0].size+" cm ";
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
							svgDoc1.getElementById("barcode").setAttribute('transform', 'matrix(0.8899 0 0 1.1092 -38 135) rotate(270)');
							
							
							jsbarcodewash(code, 'barcode', svgDoc1)


							var s = new XMLSerializer();
							var d = svgRoot1;
							var str = s.serializeToString(d);
							var svg = encodeURI(str)
							svgarr.push(svg)
							ajax++;
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
					
				i++;	
				}
			
		}
	})
	
	//alert(i)
	//console.log(index_arr)
	
	
	var a1_back = document.getElementById(data_back);
	var svgDoc1_back = a1_back.contentDocument;
	var svgRoot1_back = svgDoc1_back.documentElement;
	
	var a1_middle = document.getElementById(data_middle);
	var svgDoc1_middle = a1_middle.contentDocument;
	var svgRoot1_middle = svgDoc1_middle.documentElement;
	var svg_fin = svgarr.join(',_');
	
	if(i == ajax)
	{
		PopupWash(svg_fin,svgRoot1_back,svgRoot1_middle, dataid,svgDoc1);
	}
	
}

/**
 * This Function is used Print Special Tag .
 *  
 * @return Pdf
 */

function printSpecialDiv(data) {

	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	var svgRoot = svgDoc.documentElement;

	PopupSpecial(svgRoot);
}

/**
 * This Function is Sub Function of #printSpecialDiv.
 *  
 * @return Pdf
 */

function PopupSpecial(data) {
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/order-management/print-special-pdf",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg
		},
		async: false,
		success: function (data) {
			success = true;
			url = data;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

/**
 * This Function is Sub Function of #printWashDiv.
 *  
 * @return Pdf
 */

function PopupWash(data,data_back,data_middle,dataid,svgDoc1) {
	
    if (dataid != undefined) {
        dataid=dataid;
    }
    else
    {
        dataid="";
    }
      var s = new XMLSerializer();
//    var d = data;
//    var str = s.serializeToString(d);
//    var svg = encodeURI(str)
	
	
	var d_back = data_back;
    var str_back = s.serializeToString(d_back);
    var svg_back = encodeURI(str_back)
	
	var d_middle = data_middle;
    var str_middle = s.serializeToString(d_middle);
    var svg_middle = encodeURI(str_middle)
	
    var success = false;
    var url = "";
    var made_in_select = $('.made_in_select' + dataid + ' option:selected').attr('data-id');
    
    var care_instruction = $('.care_instruction_select' + dataid + ' option:selected');
        var care_instruction_select = [];
        $(care_instruction).each(function (index, brand) {
            care_instruction_select.push([$(this).val()]);
        });
        var care_instruction_select_fin = care_instruction_select.join(',');
    var wash_finals = localStorage.getItem('wash_composition_final'+dataid);
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/order-management/print-wash-pdf",
        type: "post",
        data: {
            _token: csrf_token,
            made_in_select:made_in_select,
            care_instruction_select_fin:care_instruction_select_fin,
            composition:wash_finals,
            svg: data,
            svg_back: svg_back,
            svg_middle: svg_middle,
            
        },
        async: false,
        success: function (data) {
			svgDoc1.getElementById("barcode").setAttribute('transform', 'matrix(0.8899 0 0 1.1092 -21 125.454) rotate(270)');
            success = true;
            url = data;
        },
        error: function (data) {

        },
    })
    if (success) { //AND THIS CHANGED
        window.open(url)
    }
}

/**
 * This Function is Sub Function of #printHangDiv.
 *  For print preview of 150 * 100 Hang Tag
 * @return Pdf
 */

function PopupHang(svgRoot, svgRoot1,svgDoc,svgDoc1,disney) {
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
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/order-management/print-hang-pdf",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svgRoot,
			svgRoot: svgRoot1
		},
		async: false,
		success: function (data) {
			if(disney != "DI")
			{
				svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 10.6793 50.8835)');
			}
			else
			{	var node = $("#tree1").jstree('get_selected', true)[0].text;
				 if(node == "COD3")
				 {
					 svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 10.6793 55.8835)');
				 }
			 	 else if(node == "DBB1" || node == "DBB2" || node == "DBB3")
				 {
					svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 10.6793 51.8835)');
				 }
				 else
				 {

					svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 10.6793 57.8835)');
				 }
			}
			
			success = true;
			url = data;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

/**
 * This Function is Sub Function of #printDiv.
 *  For print preview of 150 * 100 stickers
 * @return Pdf
 */

function Popup(data,name) {
	
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/order-management/print-pdf",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			name:name
		},
		async: false,
		success: function (data) {
			//window.location.href=data;
			success = true;
			url = data;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

/**
 * This Function is Sub Function of #printDiv.
 *  For print preview of 150 * 100 stickers
 * @return Pdf
 */

function PopupArr(data,name,svgDoc) {
	
//	var s = new XMLSerializer();
//	var d = data;
//	var str = s.serializeToString(d);
//	var svg = encodeURI(str)
	var success = false;
//	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/order-management/print-pdf-arr",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name
		},
		async: false,
		success: function (data) {
			//window.location.href=data;
			success = true;
			url = data;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

/**
 * This Function is Sub Function of #printDiv.
 * It is used to create dynamic table using tcpdf
 * @return Pdf
 */

function PopupTable(data,name) {
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	var size = [];
	var qty = [];
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);

	$('#solidform tbody tr').each(function () {
		size.push($(this).find('.size').text());
		qty.push($(this).find('.qty').text());
	})
	var sizefin = size.join(',')
	var qtyfin = qty.join(',')
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/order-management/print-pdf-table",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			size: sizefin,
			qty: qtyfin,
			name:name
		},
		async: false,
		success: function (data) {
			success = true;
			url = data;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

/**
 * This Function is Sub Function of #printDiv.
 *  For print preview of 100 * 70 stickers
 * @return Pdf
 */

function Popupsmall(data,name,svgDoc) {
//	var s = new XMLSerializer();
//	var d = data;
//	var str = s.serializeToString(d);
//	var svg = encodeURI(str)
	var success = false;
//	var url = "";
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/order-management/print-pdf-small",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name
		},
		async: false,
		success: function (data) {
			success = true;
			url = data;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

/**
 * This Function is used to create barcode for polybag stickers.
 *  
 * @return Pdf
 */

function barcode_polybag(id, data) {
	var mySVG = document.getElementById(id);
	var svgDoc;
	mySVG.addEventListener("load", function () {
		svgDoc = mySVG.contentDocument;
		var svgItem = svgDoc.getElementById("uvc_tco");
//alert(data.orchestra_parcel_details[0].ParcelCode);
		
		//svgDoc.getElementById("round").style.fill = $('#age').val();

		if (data.template_name == "Standard") {
			svgDoc.getElementById("parcel_code").innerHTML = data.orchestra_parcel_details[0].ParcelCode;

			$td_col = "";
			$td_size = "";
			$total = 0;
			var index_arr=[];
			$('#solidform tbody tr').each(function () {
				
			if(index_arr.includes($(this).find('.size').text()) != true)
			{
				if ($(this).find('.size').text() != "") {
						index_arr.push($(this).find('.size').text())
						$td_size += "<td>" + $(this).find('.size').text() + "</td>";
						$td_col += "<td>" + $(this).find('.qty').text() + "</td>";
						$total += parseInt($(this).find('.qty').text());
				}
			}
				


			})
			$tbl = '<tr><td>Size:</td>' + $td_size + '<td>Total</td></tr><tr> <td>ERCU</td>' + $td_col + '<td>' + $total + '</td></tr>';

			var code = data.orchestra_parcel_details[0].ParcelBarCode.toString();
			svgDoc.getElementById("theme").innerHTML = data.orchestra_parcel_details[0].Theme;
			svgDoc.getElementById("cartontable").innerHTML = $tbl;
			jsbarcodestdpoly(code, 'barcode', svgDoc)
		} else {
			svgDoc.getElementById("parcel_code").innerHTML = data.orchestra_parcel_details[0].UVC_TCO;
			var code = data.orchestra_parcel_details[0].BarcodeForPricesTags.toString();
			svgDoc.getElementById("size").innerHTML = data.orchestra_parcel_details[0].Size;
			svgDoc.getElementById("color").innerHTML = data.orchestra_parcel_details[0].colour_Code;
			jsbarcodesolidpoly(code, 'barcode', svgDoc)

		}
		
	}, false);

}

/**
 * This Function is used to create barcode for carton stickers.
 *  
 * @return Pdf
 */

function barcode_carton(id, data) {
	var mySVG = document.getElementById(id);
	var svgDoc;
	mySVG.addEventListener("load", function () {
		svgDoc = mySVG.contentDocument;
		var svgItem = svgDoc.getElementById("uvc_tco");
		svgDoc.getElementById("po").innerHTML = data.orchestra_parcel_details[0].BuyerPONo;
		svgDoc.getElementById("length").innerHTML = $('#length').val();
		svgDoc.getElementById("width").innerHTML = $('#width').val();
		svgDoc.getElementById("height").innerHTML = $('#modal_height').val();
		svgDoc.getElementById("volume").innerHTML = $('#parcelVolume').text();
		svgDoc.getElementById("theme").innerHTML = data.orchestra_parcel_details[0].Theme;
		
		svgDoc.getElementById("color").innerHTML = data.orchestra_parcel_details[0].colour_Code;

		if (data.template_name == "Standard") {
			var code = data.orchestra_parcel_details[0].ParcelBarCode.toString();
			svgDoc.getElementById("parcelcode").innerHTML = data.orchestra_parcel_details[0].ParcelCode;
			jsbarcodestdcartonA(code, 'barcode1', svgDoc)
			jsbarcodestdcartonB(code, 'barcode2', svgDoc)
			svgDoc.getElementById("total_polybags_per_carton").innerHTML = $('#total_polybag').val();
			svgDoc.getElementById("no_of_cartons").innerHTML = $('#needed_modal').val();
		} else {
			var code = data.orchestra_parcel_details[0].BarcodeForPricesTags.toString();
			svgDoc.getElementById("size").innerHTML = data.orchestra_parcel_details[0].Size;
			svgDoc.getElementById("parcelcode").innerHTML = data.orchestra_parcel_details[0].UVC_TCO;
			jsbarcodesolidcartonA(code, 'barcode1', svgDoc)
			jsbarcodesolidcartonB(code, 'barcode2', svgDoc)
		}

		
		


	}, false);

}

/**
 * This Function is used to get label order details.
 *  
 * @return View
 */

function label_order_details(uvc) {
    var search_string = $('.dataTables_filter input').val();
    localStorage.setItem("search_string", search_string);
 
    // var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
    // var buyer_id=$('.buyer-select').val()
    var buyer = localStorage.getItem("buyer");
    var buyer_id = localStorage.getItem("buyer_id");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/order-management/label-order-details",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            uvc: uvc
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
			
			if(data.customer_active == "active")
			{
				   $('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
			     localStorage.setItem("label_order_data", JSON.stringify(data));
            orchestra_label_order_details(buyer, buyer_id, uvc, data)
            hangtagmodal(data);
            specialtags(data);
            heatTransfer(data);
            mainlabels(data);
            sizelabels(data);
			var wcl_already_exists=[];
			var check_count=0;
			
            if (data.orchestra_wash_care_label_orders.length > 0) {
                $.each(data.orchestra_wash_care_label_orders, function (index, value) {
					
						if(wcl_already_exists.includes(value.color_code) == true)
						{
							var wash_enable = "display:none !important";
						}
						else
						{
							var wash_enable = "";
							wcl_already_exists.push(value.color_code);	
						}
						
                        if(data.orchestra_wash_care_label_orders != "")
                        {
                            var str=data.orchestra_wash_care_label_orders[index].status;
                            $wash_status=str.replace(/ /g, "");
                            $wash_status_text=str;
                        }
                        else
                        {
                            $wash_status="";
                        }
					//<p class="pull-right delete-wash' + index + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p>
                        $('#add_modal').append('<div class="col-md-4" ><input type="hidden" value="'+value.id +'" id="delete_id'+index+'"><div class="card add-card wash-card'+index+'" style="height: 280px;" data-toggle="modal" data-target=".washcare_label_' + index + '" data-backdrop="static" data-keyboard="false">  <span class="label label-'+$wash_status+' label-status">'+$wash_status_text+'</span>   <div class="card-title"><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + index + '>View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i> </p> <h5><b class="washlabelname' + index + '">' + value.template_name + " - Washcare Label" + '</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="washcare_label_card'+index+'" style="'+wash_enable+';cursor:pointer;display: inline-block;"><i class="fa fa-plus" aria-hidden="true"></i> Add washcare label</p><p id="clone_washcare_label_card'+index+'" style="display: inline-block;float: right;cursor:pointer"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
                        
					
    					$('#washcare_label_card'+index).on('click', function (e) {

        if ($('.washcarename').val() != "") {
          
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"> <span></span>   <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare,value.color_code);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare)

                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
				
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label added successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare,value.color_code,"new");
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
		
    });
                                 
    					$('#clone_washcare_label_card'+index).on('click', function (e) {

        if ($('.washcarename').val() != "") {
          
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"><span></span>    <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare,value.color_code);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
           		
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label cloned successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare,value.color_code,"new");
            $('#washcarelabelform'+washcare).find('#wcl_name'+washcare).val($('#washcarelabelform'+index).find('#wcl_name'+index).val())
           
            
            $('.washing_select'+washcare).selectpicker('val',$('.washing_select'+index).selectpicker('val'));
            $('.whitening_select'+washcare).selectpicker('val',$('.whitening_select'+index).selectpicker('val'));
            $('.dryer_select'+washcare).selectpicker('val',$('.dryer_select'+index).selectpicker('val'));
            $('.ironing_select'+washcare).selectpicker('val',$('.ironing_select'+index).selectpicker('val'));
            $('.cleaning_select'+washcare).selectpicker('val',$('.cleaning_select'+index).selectpicker('val'));
            $('.made_in_select'+washcare).selectpicker('val',$('.made_in_select'+index).selectpicker('val'));
			
			//$('#washcarelabelform' + washcare+' .washonesizein').val($('#washcarelabelform' + index+' .washonesizein').val())
			$('#washcarelabelform' + washcare+' .washonesizecm').val($('#washcarelabelform' + index+' .washonesizecm').val())
            $('.care_instruction_select'+washcare).selectpicker('val',$('.care_instruction_select'+index).selectpicker('val'));
            
            $('#composition_remove_qty'+washcare).html($('#composition_remove_qty'+index).html())
            
            $('#composition_remove_qty'+index+' tr').each(function (ind,val) {
                var values= $(this).find('select').find(':selected').val()
                var v= $(this).attr('id');
                $('#composition_remove_qty'+washcare+' tr').each(function (inds,vals) {
                    if($(this).attr('id') == v)
                    {
                        $(this).find('select').val(values)
                        $(this).find('a').removeAttr('onclick')
                        $(this).find('a').attr('onclick','deleteComposition(this,'+washcare+')')
                    }
                });
            }); 
            
            $('#washcarelabelform'+index+' table tbody tr').each(function (ind,val) {
                var values= $(this).find('.tot_qty_tablewash').val()
                var v= $(this).find('.uvc_tco').text();
                $('#washcarelabelform'+washcare+' table tbody tr').each(function (inds,vals) {
                    if($(this).find('.uvc_tco').text() == v)
                    {
                        $(this).find('.tot_qty_tablewash').val()
                        
                    }
                });
            });
            
            $('#washcarelabelform'+washcare+' table tbody').find('.tot_qty_tablewash_fin'+washcare).text($('#washcarelabelform'+index+' table tbody').find('.tot_qty_tablewash_fin'+index).text())
            var mySVG = document.getElementById("washcare_svg_front"+washcare);
            var svgDoc;
			
			var mySVG_back = document.getElementById("washcare_svg_back"+washcare);
            var svgDocBack;
			
            mySVG.addEventListener("load", function () {
                svgDoc=mySVG.contentDocument;
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('astm'));
                
                var text_for_mad_in = '<p>' + $('select.made_in_select' + washcare).find(':selected').data('french') + "-" + $('select.made_in_select' + washcare).find(':selected').data('english') + "-" + $('select.made_in_select' + washcare).find(':selected').data('spanish') + "-" + $('select.made_in_select' + washcare).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + washcare).find(':selected').data('italian') + "-" + $('select.made_in_select' + washcare).find(':selected').data('dutch') + "-" + $('select.made_in_select' + washcare).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('greek')  + '</p>';
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;
            });
			
            mySVG_back.addEventListener("load", function () {
				svgDocBack=mySVG_back.contentDocument;
				svgDocBack.getElementById("text_care_instruction").innerHTML = "";
                $('.care_instruction_select' + washcare + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek')  + '</p>';
                    svgDocBack.getElementById("text_care_instruction").innerHTML += value;
                });
                
                 svgDocBack.getElementById("parts_composition").innerHTML ="";
              $('#composition_remove_qty' + washcare + ' tr').each(function () {
                  if($(this).find('select').find(':selected').val() != "Select Option")
                  {
                       svgDocBack.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german') + "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek') + '</p>';
                  }
                 
                  var id=$(this).attr('id')
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                  $(compss).each(function (index1,value1) {
                      var english = value1.split('_');

                    svgDocBack.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
                });
              })
            $('.composition_error_hidden').addClass('hidden')
            var washcomp=[];
            $('#composition_remove_qty' + washcare + ' tr').each(function () {
                  var washcomp1=[];
                  var id=$(this).attr('id')
                  var value=$(this).find('select').find(':selected').val();
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                    $(compss).each(function (index1,value1){ 
                        var english = value1.split('_');
                        washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                    });
                washcomp.push(washcomp1)
            })
            var wash_finals = JSON.stringify(washcomp);
            localStorage.setItem('wash_composition_final'+washcare, wash_finals);
			});
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
        
    });
                        
                        $('.delete-wash' + index).on('click', function (e) {
                            deletewash.push(value.id)
                            localStorage.setItem("deletewash", JSON.stringify(deletewash));
                            removeWashLabel(index)
                            $('#washcarelabelform' + index).remove()
                            
                            $(this).parent().parent().parent().remove();


                        });
                        washcarelabels(data, index,value.color_code,value.card_flag);
						washcare++;
						check_count++;
                });
				
				if(check_count == data.orchestra_wash_care_label_orders.length)
				{
					//console.log(wcl_already_exists)
					 $(data.color_wcl).each(function (index1, value1) {
						// console.log(value1.color_code)
					 if(wcl_already_exists.includes(value1.Color_Code) != true)
					 {
						$wash_status = "";
						$wash_status_text = "";
						var wash_enable = "display:none !important";
						var wash_name = "";
						$('#add_modal').append('<div class="col-md-4"><input type="hidden"  id="delete_id'+washcare+'"> <div class="card add-card wash-card'+washcare+'" style="height: 280px;" data-toggle="modal" data-target=".wash_care_label_main" data-backdrop="static" data-keyboard="false"><span class="label label-' + $wash_status + ' label-status">' + $wash_status_text + '</span> <div class="card-title"> <p class="pull-right" id="clickme'+washcare+'" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b class="washlabelname'+washcare+'">' + wash_name + 'Washcare Label</b></h5> </div><div class="row"> <div class="col-md-6 col-md-offset-3"> <div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="washcare_label_card'+washcare+'" style="'+wash_enable+';cursor:pointer;display: inline-block;"><i class="fa fa-plus" aria-hidden="true"></i> Add washcare label</p><p id="clone_washcare_label_card'+washcare+'" style="'+wash_enable+';cursor:pointer;    display: inline-block;float: right;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
						var old_id=washcare;
						$('#washcare_label_card'+old_id).on('click', function (e) {

        if ($('.washcarename').val() != "") {
          
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"> <span></span>   <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare,value1.Color_Code);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
				
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label added successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare,value.color_code,"new");
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
		
    });
                                 
    					$('#clone_washcare_label_card'+old_id).on('click', function (e) {

        if ($('.washcarename').val() != "") {
        
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"><span></span>    <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare,value1.Color_Code);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
           		
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label cloned successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare,value1.Color_Code,"new");
            $('#washcarelabelform'+washcare).find('#wcl_name'+washcare).val($('#washcarelabelform'+old_id).find('#wcl_name'+old_id).val())
           
            
            $('.washing_select'+washcare).selectpicker('val',$('.washing_select'+old_id).selectpicker('val'));
            $('.whitening_select'+washcare).selectpicker('val',$('.whitening_select'+old_id).selectpicker('val'));
            $('.dryer_select'+washcare).selectpicker('val',$('.dryer_select'+old_id).selectpicker('val'));
            $('.ironing_select'+washcare).selectpicker('val',$('.ironing_select'+old_id).selectpicker('val'));
            $('.cleaning_select'+washcare).selectpicker('val',$('.cleaning_select'+old_id).selectpicker('val'));
            $('.made_in_select'+washcare).selectpicker('val',$('.made_in_select'+old_id).selectpicker('val'));
			
			//$('#washcarelabelform' + washcare+' .washonesizein').val($('#washcarelabelform' + old_id+' .washonesizein').val())
			$('#washcarelabelform' + washcare+' .washonesizecm').val($('#washcarelabelform' + old_id+' .washonesizecm').val())
            $('.care_instruction_select'+washcare).selectpicker('val',$('.care_instruction_select'+old_id).selectpicker('val'));
            
            $('#composition_remove_qty'+washcare).html($('#composition_remove_qty'+old_id).html())
            
            $('#composition_remove_qty'+old_id+' tr').each(function (ind,val) {
                var values= $(this).find('select').find(':selected').val()
                var v= $(this).attr('id');
                $('#composition_remove_qty'+washcare+' tr').each(function (inds,vals) {
                    if($(this).attr('id') == v)
                    {
                        $(this).find('select').val(values)
                        $(this).find('a').removeAttr('onclick')
                        $(this).find('a').attr('onclick','deleteComposition(this,'+washcare+')')
                    }
                });
            }); 
            
            $('#washcarelabelform'+old_id+' table tbody tr').each(function (ind,val) {
                var values= $(this).find('.tot_qty_tablewash').val()
                var v= $(this).find('.uvc_tco').text();
                $('#washcarelabelform'+washcare+' table tbody tr').each(function (inds,vals) {
                    if($(this).find('.uvc_tco').text() == v)
                    {
                        $(this).find('.tot_qty_tablewash').val()
                        
                    }
                });
            });
            
            $('#washcarelabelform'+washcare+' table tbody').find('.tot_qty_tablewash_fin'+washcare).text($('#washcarelabelform'+old_id+' table tbody').find('.tot_qty_tablewash_fin'+old_id).text())
            var mySVG = document.getElementById("washcare_svg_front"+washcare);
            var svgDoc;
			
			var mySVG_back = document.getElementById("washcare_svg_back"+washcare);
            var svgDocBack;
			
            mySVG.addEventListener("load", function () {
                svgDoc=mySVG.contentDocument;
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('astm'));
                
                var text_for_mad_in = '<p>' + $('select.made_in_select' + washcare).find(':selected').data('french') + "-" + $('select.made_in_select' + washcare).find(':selected').data('english') + "-" + $('select.made_in_select' + washcare).find(':selected').data('spanish') + "-" + $('select.made_in_select' + washcare).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + washcare).find(':selected').data('italian') + "-" + $('select.made_in_select' + washcare).find(':selected').data('dutch') + "-" + $('select.made_in_select' + washcare).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('greek')  + '</p>';
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;
            });
			
            mySVG_back.addEventListener("load", function () {
				svgDocBack=mySVG_back.contentDocument;
				svgDocBack.getElementById("text_care_instruction").innerHTML = "";
                $('.care_instruction_select' + washcare + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek')  + '</p>';
                    svgDocBack.getElementById("text_care_instruction").innerHTML += value;
                });
                
                 svgDocBack.getElementById("parts_composition").innerHTML ="";
              $('#composition_remove_qty' + washcare + ' tr').each(function () {
                  if($(this).find('select').find(':selected').val() != "Select Option")
                  {
                       svgDocBack.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german') + "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek') + '</p>';
                  }
                 
                  var id=$(this).attr('id')
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                  $(compss).each(function (index1,value1) {
                      var english = value1.split('_');

                    svgDocBack.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
                });
              })
            $('.composition_error_hidden').addClass('hidden')
            var washcomp=[];
            $('#composition_remove_qty' + washcare + ' tr').each(function () {
                  var washcomp1=[];
                  var id=$(this).attr('id')
                  var value=$(this).find('select').find(':selected').val();
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                    $(compss).each(function (index1,value1){ 
                        var english = value1.split('_');
                        washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                    });
                washcomp.push(washcomp1)
            })
            var wash_finals = JSON.stringify(washcomp);
            localStorage.setItem('wash_composition_final'+washcare, wash_finals);
			});
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
        
    });
                        
                        $('.delete-wash' + old_id).on('click', function (e) {
                            deletewash.push(value.id)
                            localStorage.setItem("deletewash", JSON.stringify(deletewash));
                            removeWashLabel(old_id)
                            $('#washcarelabelform' + old_id).remove()
                            
                            $(this).parent().parent().parent().remove();


                        });
						washcarelabels(data, old_id,value1.Color_Code);
						washcare++;
					 }
					});
				}
				
				
            } else {
               $(data.color_wcl).each(function (index1, value1) {
		
						if (data.orchestra_wash_care_label_orders != "") {
							var str = data.orchestra_wash_care_label_orders[0].status;
							$wash_status = str.replace(/ /g, "");
							$wash_status_text = data.orchestra_wash_care_label_orders[0].status;
							var wash_enable = "";
							var wash_name = data.orchestra_wash_care_label_orders[0].template_name + " - ";
							update_disable=1;
						} else {
							$wash_status = "";
							$wash_status_text = "";
							var wash_enable = "display:none !important";
							var wash_name = "";
						}
//						$(data.orchestra_wash_care_label_orders).each(function (index2, value2) {
//							if(value2.color_code == value1.Color_Code)
//							{
//								console.log("hai")
//							}
//
//						});

						$('#add_modal').append('<div class="col-md-4"> <input type="hidden"  id="delete_id'+washcare+'"><div class="card add-card wash-card'+washcare+'" style="height: 280px;" data-toggle="modal" data-target=".wash_care_label_main" data-backdrop="static" data-keyboard="false"><span class="label label-' + $wash_status + ' label-status">' + $wash_status_text + '</span> <div class="card-title"> <p class="pull-right" id="clickme'+washcare+'" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b class="washlabelname'+washcare+'">' + wash_name + 'Washcare Label</b></h5> </div><div class="row"> <div class="col-md-6 col-md-offset-3"> <div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="washcare_label_card'+washcare+'" style="'+wash_enable+';cursor:pointer;display: inline-block;"><i class="fa fa-plus" aria-hidden="true"></i> Add washcare label</p><p id="clone_washcare_label_card'+washcare+'" style="'+wash_enable+';cursor:pointer;    display: inline-block;float: right;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				   		var old_id=washcare;
				   		$('#washcare_label_card'+old_id).on('click', function (e) {

        if ($('.washcarename').val() != "") {
          
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"> <span></span>   <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare,value1.Color_Code);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
				
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label added successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare,value1.Color_Code,"new");
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
		
    });
                                 
    					$('#clone_washcare_label_card'+old_id).on('click', function (e) {

        if ($('.washcarename').val() != "") {
          
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"><span></span>    <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare,value1.Color_Code);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
           		
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label cloned successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare,value1.Color_Code,"new");
            $('#washcarelabelform'+washcare).find('#wcl_name'+washcare).val($('#washcarelabelform'+old_id).find('#wcl_name'+old_id).val())
           
            
            $('.washing_select'+washcare).selectpicker('val',$('.washing_select'+old_id).selectpicker('val'));
            $('.whitening_select'+washcare).selectpicker('val',$('.whitening_select'+old_id).selectpicker('val'));
            $('.dryer_select'+washcare).selectpicker('val',$('.dryer_select'+old_id).selectpicker('val'));
            $('.ironing_select'+washcare).selectpicker('val',$('.ironing_select'+old_id).selectpicker('val'));
            $('.cleaning_select'+washcare).selectpicker('val',$('.cleaning_select'+old_id).selectpicker('val'));
            $('.made_in_select'+washcare).selectpicker('val',$('.made_in_select'+old_id).selectpicker('val'));
			
			//$('#washcarelabelform' + washcare+' .washonesizein').val($('#washcarelabelform' + old_id+' .washonesizein').val())
			$('#washcarelabelform' + washcare+' .washonesizecm').val($('#washcarelabelform' + old_id+' .washonesizecm').val())
            $('.care_instruction_select'+washcare).selectpicker('val',$('.care_instruction_select'+old_id).selectpicker('val'));
            
            $('#composition_remove_qty'+washcare).html($('#composition_remove_qty'+old_id).html())
            
            $('#composition_remove_qty'+old_id+' tr').each(function (ind,val) {
                var values= $(this).find('select').find(':selected').val()
                var v= $(this).attr('id');
                $('#composition_remove_qty'+washcare+' tr').each(function (inds,vals) {
                    if($(this).attr('id') == v)
                    {
                        $(this).find('select').val(values)
                        $(this).find('a').removeAttr('onclick')
                        $(this).find('a').attr('onclick','deleteComposition(this,'+washcare+')')
                    }
                });
            }); 
            
            $('#washcarelabelform'+old_id+' table tbody tr').each(function (ind,val) {
                var values= $(this).find('.tot_qty_tablewash').val()
                var v= $(this).find('.uvc_tco').text();
                $('#washcarelabelform'+washcare+' table tbody tr').each(function (inds,vals) {
                    if($(this).find('.uvc_tco').text() == v)
                    {
                        $(this).find('.tot_qty_tablewash').val()
                        
                    }
                });
            });
            
            $('#washcarelabelform'+washcare+' table tbody').find('.tot_qty_tablewash_fin'+washcare).text($('#washcarelabelform'+old_id+' table tbody').find('.tot_qty_tablewash_fin'+old_id).text())
            var mySVG = document.getElementById("washcare_svg_front"+washcare);
            var svgDoc;
			
			var mySVG_back = document.getElementById("washcare_svg_back"+washcare);
            var svgDocBack;
			
            mySVG.addEventListener("load", function () {
                svgDoc=mySVG.contentDocument;
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('astm'));
                
                var text_for_mad_in = '<p>' + $('select.made_in_select' + washcare).find(':selected').data('french') + "-" + $('select.made_in_select' + washcare).find(':selected').data('english') + "-" + $('select.made_in_select' + washcare).find(':selected').data('spanish') + "-" + $('select.made_in_select' + washcare).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + washcare).find(':selected').data('italian') + "-" + $('select.made_in_select' + washcare).find(':selected').data('dutch') + "-" + $('select.made_in_select' + washcare).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('greek')  + '</p>';
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;
            });
			
            mySVG_back.addEventListener("load", function () {
				svgDocBack=mySVG_back.contentDocument;
				svgDocBack.getElementById("text_care_instruction").innerHTML = "";
                $('.care_instruction_select' + washcare + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek')  + '</p>';
                    svgDocBack.getElementById("text_care_instruction").innerHTML += value;
                });
                
                 svgDocBack.getElementById("parts_composition").innerHTML ="";
              $('#composition_remove_qty' + washcare + ' tr').each(function () {
                  if($(this).find('select').find(':selected').val() != "Select Option")
                  {
                       svgDocBack.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german') + "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek') + '</p>';
                  }
                 
                  var id=$(this).attr('id')
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                  $(compss).each(function (index1,value1) {
                      var english = value1.split('_');

                    svgDocBack.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
                });
              })
            $('.composition_error_hidden').addClass('hidden')
            var washcomp=[];
            $('#composition_remove_qty' + washcare + ' tr').each(function () {
                  var washcomp1=[];
                  var id=$(this).attr('id')
                  var value=$(this).find('select').find(':selected').val();
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                    $(compss).each(function (index1,value1){ 
                        var english = value1.split('_');
                        washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                    });
                washcomp.push(washcomp1)
            })
            var wash_finals = JSON.stringify(washcomp);
            localStorage.setItem('wash_composition_final'+washcare, wash_finals);
			});
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
        
    });
                        
                        $('.delete-wash' + old_id).on('click', function (e) {
                            deletewash.push(value.id)
                            localStorage.setItem("deletewash", JSON.stringify(deletewash));
                            removeWashLabel(old_id)
                            $('#washcarelabelform' + old_id).remove()
                            
                            $(this).parent().parent().parent().remove();


                        });
						washcarelabels(data, old_id,value1.Color_Code);
						washcare++;
					});
            }
			}
			else
		    {
			    $('.snackbar_span').html('Please Add Customers...');
                showSnackbar();
			}
          


            //  washcarelabels(data,"");

        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
        }
    })
}

/**
 * This Function is Sub Function of #label_order_details.
 * To integrate Wash care Information
 * @return Wash care View
 */

function washcarelabels(data, dataid,color_code,new_flag) {
    var washing_option = "<option value=''>Select Option</option>";
    var whitening_option = "<option value=''>Select Option</option>";
    var dryer_option = "<option value=''>Select Option</option>";
    var ironing_option = "<option value=''>Select Option</option>";
    var cleaning_option = "<option value=''>Select Option</option>";
    var made_option = "<option value=''>Select Option</option>";
    var instruction_option = "";
    var parts_option = "<option>Select Option</option>";
    var composition_option = "<option>Select Option</option>";


    $.each(data.orchestra_countries, function (index, value) {
        made_option += "<option data-id='" + value.id + "'  data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_care_instruction, function (index, value) {
        instruction_option += "<option data-id='" + value.id + "' data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german  + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_parts, function (index, value) {
        parts_option += "<option data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_composition, function (index, value) {
        composition_option += "<option data-french='" + value.french + "' data-english='" + value.english + "' data-spanish='" + value.spanish + "' data-portuguese='" + value.portuguese + "' data-italian='" + value.italian + "' data-dutch='" + value.dutch + "' data-german='" + value.german  + "' data-arabic='" + value.arabic + "' data-greek='" + value.greek + "' value=" + value.id + ">" + value.english + "</option>";
    });
    $.each(data.orchestra_care_symbols, function (index, value) {
        if (value.category_id == 1) {
            washing_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 2) {
            whitening_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + "  value=" + value.id + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 3) {
            dryer_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 4) {
            ironing_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        } else if (value.category_id == 5) {
            cleaning_option += "<option data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + value.care_instruction + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + value.care_instruction + "</option>";
        }

    });
	
	if(new_flag != "new" && new_flag != undefined)
	{
		var card_flag = new_flag;
	}
	else
	{
		var card_flag = "card_"+dataid;
	}

	

    var tr = "";
    $qu = 0;
    var ttots = 0;
	var totaloq = 0;
    if (data.orchestra_wash_care_label_orders != "") {
        if (dataid != "") {
            dataid1 = dataid;
        } else {
            dataid1 = 0;
        }
        if (data.orchestra_wash_care_label_orders.length > dataid1) {
			$time_flag = data.orchestra_wash_care_label_orders[dataid1].card_flag;
            $exist_id = data.orchestra_wash_care_label_orders[dataid1].id;
			
			if(data.barcode[0].BuyerPONo == data.orchestra_wash_care_label_orders[dataid1].po)
			{
				if(data.orchestra_wash_care_label_orders[dataid1].merchandiser_comment != "")
				{
					$comment = "<h4>Comments :</h4><p id='comment_section_"+dataid1+"'>"+data.orchestra_wash_care_label_orders[dataid1].merchandiser_comment+"</p><hr>";
				}
				else
				{
					$comment = "";
				}
			}
			else
			{
				$comment = "";
			}
        
            
            var str=data.orchestra_wash_care_label_orders[dataid1].status;
			if(data.customers[0].factory_code == "HF-FREEUSD")
		    {
				if(data.barcode[0].BuyerPONo == data.orchestra_wash_care_label_orders[dataid1].po)
				{
					$save_button_class = "hidden";
					$print_button_class = "";
					$disable_option_select = "disabled";
					$disable_qty_select = "disabled";
				}
				else
				{
					$save_button_class = "";
					$print_button_class = "hidden";
					$disable_option_select = "disabled";
					$disable_qty_select = "";
				}
		    }
			else
			{
				$save_button_class = "hidden";
				$print_button_class = "";
				$disable_option_select = "disabled";
				$disable_qty_select = "";
			}
			
			//$save_button_class = "hidden";
            $status=str.replace(/ /g, "");
            $status_text=str;
            $wcl_name=data.orchestra_wash_care_label_orders[dataid1].template_name;
			$template_id = data.orchestra_wash_care_label_orders[dataid1].template_id;
			
			//$print_button_class = "";
        } else {
            $time_flag = Date.now();
            $exist_id = "";
            $comment = "";
            $status="";
            $status_text="";
            $wcl_name="";
			$template_id = "122";
			$print_button_class = "hidden";
			$save_button_class = "";
			$disable_option_select = "";
			$disable_qty_select = "";
        }

    } else {
		
		$time_flag = Date.now();
        
        $exist_id = "";
        $comment = "";
        $status="";
        $status_text="";
        $wcl_name="";
		$template_id = "122";
		$print_button_class = "hidden";
		$save_button_class = "";
		$disable_option_select = "";
		$disable_qty_select = "";
    }
	var arr=[];
	var options="";
	var input_hidden="";
	$.each(data.barcode, function (index, value) {
		
	   if(arr.includes(value.BuyerPONo) == false)
		{
			arr.push(value.BuyerPONo)
			options += "<option>" + value.BuyerPONo + "</option>";
			input_hidden += '<input type="hidden" value="" id="'+value.BuyerPONo +'_'+ dataid + '">';
		}
	});
	//alert(data.customers[0].factory_code);
   if(data.customers[0].factory_code == "HF-FREEUSD")
   {
   		var po_select = '<select style="width: 135px;display: inline-block;margin-left: 15px;" id="po_select'+dataid+'" class="form-control">' + options + '</select>';
   }
   else
   {
	  	var po_select = '';
   }
	
    $.each(data.order_details, function (index, value) {
        if(color_code == value.Color_Code)
		{
				if (data.orchestra_wash_care_label_orders != "") {
					if (dataid != "") {
						dataid1 = dataid;
					} else {
						dataid1 = 0;
					}
					if (data.orchestra_wash_care_label_orders.length > dataid1) {
						
					   if(data.customers[0].factory_code == "HF-FREEUSD")
					   {
						   if(data.barcode[0].BuyerPONo == data.orchestra_wash_care_label_orders[dataid1].po)
							{
								$wash_label_count = data.orchestra_wash_care_label_orders[dataid1]['qty'][$qu].quantity;
							}
							else
							{
								$wash_label_count = "";
							}
					   }
					   else
					   {
						   $wash_label_count = data.orchestra_wash_care_label_orders[dataid1]['qty'][$qu].quantity;
						   
					   }
						
						
					} else {
						$wash_label_count = "";
					}

				} else {
					$wash_label_count = "";
				}
			   tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + (value.Size).replace("ANS", '') + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)" type="number" min="'+data.wash_moq+'" data-required-error="Required" '+ $disable_qty_select +' class="form-control input-sm tot_qty_tablewash" value="' + $wash_label_count + '" required placeholder="WCL Count"><div class="help-block with-errors"></div></div></td></tr>';
			 	$qu++;
			    ttots += parseInt($wash_label_count);
				totaloq += parseInt(value.Quantity);
		}

    });
	
	if(data.order_details[0].Size == "TAILLE UNIQUE")
	{
		if (dataid != "") {
            dataid1 = dataid;
        } else {
            dataid1 = 0;
        }
		if (data.orchestra_wash_care_label_orders != "") {
			 if (data.orchestra_wash_care_label_orders.length > dataid1) {
                $wash_cm = data.orchestra_wash_care_label_orders[dataid1].cm;
				$wash_in = data.orchestra_wash_care_label_orders[dataid1].in;
            } else {
                $wash_cm = ""; 
				$wash_in = "";
            }
			
            
        } else {
            $wash_cm = "";
			$wash_in = "";
        }
		var sizebox='<div class="form-group has-feedback"><label>Enter Size in Cm</label><input onkeypress="return checkNumbersOnly(event)" type="text" maxlength="3" class="form-control input-sm washonesizecm" value="'+$wash_cm+'" required="" placeholder="Enter Size in Cm"><div class="help-block with-errors"></div></div>';
	}
	else
	{
		var sizebox="";
	}
	
    if(isNaN(ttots))
        {
            ttots=0;
        }
    else
        {
            ttots=ttots;
        }
	//alert(data.order_details[0].Licence_Code)
	if(data.order_details[0].Licence_Code === 'DI')
	{
		//<label id="hang_template_error" class="hidden" style="color:red">Please Select the Template</label>  <div><input type="text" id="tree_wash'+dataid+'_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div id="tree_wash'+dataid+'"></div>
		var treestruct='';
		var disney_flag="1";
	}
	else
	{
		var treestruct="";
		var disney_flag="0";
	}
	
    $('.top-content').append('<form id="washcarelabelform' + dataid + '" data-toggle= "validator"><input type="hidden" value="' + $exist_id + '" id="exist_id' + dataid + '"><input type="hidden" value="' + color_code + '" id="color_id' + dataid + '"><input type="hidden" value="' + card_flag + '" id="card_flag' + dataid + '"><input type="hidden" value="' + $time_flag + '" id="time_flag' + dataid + '"><input type="hidden" value="' + $disable_option_select + '" id="composition_flag' + dataid + '">'+input_hidden+'<div class="left-panelorc col-md-8"> <div class="div-max"> <div class="col-md-3"> <h4>Options</h4> <hr><div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">WCL Name</label><input type="text" data-required-error="Required" '+$disable_option_select+' class="form-control input-sm" required="" placeholder="WCL Name" value="' + $wcl_name + '" id="wcl_name' + dataid + '"><div class="help-block with-errors"></div></div>  <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Washing</label><select '+$disable_option_select+' data-required-error="Required" data-live-search="true" class="form-control selectpicker input-sm washing_select' + dataid + '" required>' + washing_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Whitening</label><select '+$disable_option_select+' data-required-error="Required" data-live-search="true" class="form-control selectpicker input-sm whitening_select' + dataid + '" required>' + whitening_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Dryer</label><select '+$disable_option_select+' data-required-error="Required" data-live-search="true" class="form-control selectpicker input-sm dryer_select' + dataid + '" required>' + dryer_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Ironing</label><select '+$disable_option_select+' data-required-error="Required" data-live-search="true" class="form-control selectpicker input-sm ironing_select' + dataid + '" required>' + ironing_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Cleaning</label><select '+$disable_option_select+' data-required-error="Required" data-live-search="true" class="form-control selectpicker input-sm cleaning_select' + dataid + '" required>' + cleaning_option + '</select><div class="help-block with-errors"></div></div>'+sizebox+'  </div></div> <div class="col-md-5"> <h4>Art Work</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span> <hr> <div class="section-1 sections"> <div class="sample-artwork"><object style="height: calc( 100vh - 125px );"  id="washcare_svg_front' + dataid + '" data="' + data.orchestra_wash_template_front + '" type="image/svg+xml"></object> <object style="height: calc( 100vh - 125px );margin-left: 10px;"  id="washcare_svg_back' + dataid + '"  data="' + data.orchestra_wash_template_back + '" type="image/svg+xml"></object><object style="height: calc( 100vh - 125px );width: 145px;margin-left: 10px;"  id="washcare_svg_middle' + dataid + '" data="' + data.orchestra_wash_template_middle + '" type="image/svg+xml"></object></div><input type="hidden" value="' + disney_flag + '" id="wash_label_id"></div> </div> <div class="col-md-4 " style="padding-left:0px"> <h4>Options</h4> <hr> <div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Made In</label><select '+$disable_option_select+' data-required-error="Required" data-live-search="true" class="form-control selectpicker input-sm made_in_select' + dataid + '" required>' + made_option + '</select><div class="help-block with-errors"></div></div> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Care Instructions</label><select '+$disable_option_select+' data-required-error="Required" data-live-search="true" class="form-control selectpicker input-sm care_instruction_select' + dataid + '" required multiple>' + instruction_option + '</select><div class="help-block with-errors"></div></div> <div class="section-1 sections"> <h5>Compositions</h5> <select '+$disable_option_select+' class="selectpicker composition_select' + dataid + '" data-live-search="true">  ' + composition_option + '  </select><p class="hidden" style="color:red;float:right" id="composition_error_required' + dataid + '">Please Select Composition</p> </div> <h5>Percentage</h5> <input  '+$disable_option_select+' type="number" min="1" max="100" class="form-control input-sm composition_percent' + dataid + '"><p class="hidden" style="color:red;text-align:right" id="percentage_error_required' + dataid + '">Please Enter Percentage</p>  <button '+$disable_option_select+' type="button" class="btn  btn-primary pull-right input-sm add_to_list' + dataid + '" onclick="add_to_list(' + dataid + ')">Add To List</button> <table class="table table-striped table-hover table-condensed"><thead><th>Composition</th><th>Quantity</th></thead><tbody id="composition_table_qty' + dataid + '"></tbody></table><p id="wash_composition_error' + dataid + '" class="hidden" style="color:red;text-align: right;margin-right: 10px;">Please Select composition</p><p id="wash_composition_page_error' + dataid + '" class="hidden" style="color:red;text-align: right;margin-right: 10px;">Please Add Composition to Page</p><div style="height: 30px;"><button class="btn  btn-primary pull-right input-sm add_to_composition' + dataid + '"  type="button" disabled onclick="add_to_composition(' + dataid + ')">Add To Composition</button></div><div><h4>Compositions :</h4><div><table class="table table-striped table-hover table-condensed"><thead><tr><th>Composition Name</th><th>Parts</th><th>Actions</th></tr></thead><tbody id="composition_remove_qty' + dataid + '"></tbody></table><div><p class="hidden composition_error_hidden" style="color:red;text-align: right;margin-right: 10px;">Please select parts</p></div><div><button '+$disable_option_select+' class="btn  btn-primary pull-right input-sm add_to_page' + dataid + '"  style="position:relative;" type="button" onclick="add_to_page(' + dataid + ')">Add To Page</button></div></div></div><div class="parts_none'+dataid+'" style="display:none"><select>' + parts_option + '</select></div> </div> </div> </div></div> <div class="right-panel col-md-4"> <div class="div-max scroll"> <h4 style="display: inline-block;">Quantity Info for <span class="washlabelnameqty' + dataid + '"> WashCare Label</span></h4> '+po_select+' <hr> <table class="table table-striped table-hover table-condensed table-responsive qty-table-modal" id="wash_care_label_table_'+dataid+'" style="font-size:11px"> <thead> <tr> <th>UVC TCO#</th> <th>Size in ANS</th> <th>Barcode</th> <th>Qty</th> <th><input type="text" class="form-control input-sm extra_percentagewash" '+$disable_qty_select+' onkeypress="return checkNumbersOnly(event)" placeholder="Extra Qty %" style="float:left;height:20px;"></th> </tr></thead> <tbody style="font-size: 11px">' + tr + '  <tfoot><tr class="strong"> <td class="text-right" colspan="3">Total:</td><td>' + totaloq + '</td><td class="text-center tot_qty_tablewash_fin' + dataid + '">' + ttots + '</td></tr> </tfoot></tbody></table><hr>'+ $comment +' <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_wash_button_'+dataid+'" style="margin-left:10px">Save</button> <button class="btn btn-default pull-right input-sm" id="close" type="button">Close</button> <button type="button" class="btn btn-primary '+$print_button_class+'" id="print_wash_button_'+dataid+'" onclick=printWashDiv("washcare_svg_front'+dataid+'","washcare_svg_back'+dataid+'","washcare_svg_middle'+dataid+'","'+dataid+'")>Print</button></div> </div>  <div class="backdrop"></div></form>');
   //$('.left').removeClass('disabled')
	
	if(new_flag != "new")
	{
		if (data.orchestra_wash_care_label_orders_po != "") {
		$(data.orchestra_wash_care_label_orders_po).each(function (keys, vals) {
			if(vals.card_flag == $("#card_flag"+dataid).val())
			{
				$('#'+vals.po+'_'+dataid).val("1");
			}
            
        })
		}
	}
	
	

    $('#washcarelabelform' + dataid + ' .tot_qty_tablewash').keyup(function () {
        var tot_qty_tablewash_carton_final = 0;
        $('#washcarelabelform' + dataid + ' tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablewash').val() != undefined) {
                tot_qty_tablewash_carton_final += parseInt($(this).find('.tot_qty_tablewash').val());
            }
        })
        $('#washcarelabelform' + dataid + ' .tot_qty_tablewash_fin'+ dataid).text(tot_qty_tablewash_carton_final);
    });
	
	treestructurewash(data.treeviewwash,dataid, "tree_wash"+dataid, $template_id,data);
	
    $('#washcarelabelform' + dataid).validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setWashCareLabelStatus(dataid)
        }
    })
	
//	$('#washcarelabelform' + dataid+' .washonesizein').keyup(function () {
//		
//		//var inches=$(this).val();
//		var cm=$('#washcarelabelform' + dataid+' .washonesizecm').val();
//        var mySVG = document.getElementById("washcare_svg_front" + dataid);
//		var svgDoc;
//		svgDoc = mySVG.contentDocument;
//		svgDoc.getElementById("size_in_fr_en1").innerHTML = cm+"cm";
//		svgDoc.getElementById("size_in_fr_en2").innerHTML = cm+"cm";
//
//		
//    });
	$('#washcarelabelform' + dataid+' .washonesizecm').keyup(function () {
		//alert(dataid)
		var cm=$(this).val();
		//var inches=$('#washcarelabelform' + dataid+' .washonesizein').val();
        var mySVG = document.getElementById("washcare_svg_front" + dataid);
		var svgDoc;
		svgDoc = mySVG.contentDocument;
		svgDoc.getElementById("size_in_fr_en1").innerHTML = cm+"cm";
		svgDoc.getElementById("size_in_fr_en2").innerHTML = cm+"cm";
		
    });
	
	$('#po_select'+ dataid).change(function(){
		var po = $(this).val();
		var parcel_id = $('#parcel_id').val();
		var season_code = $('#season_code').val();
		var uvc_id = $('#uvc_id').val();
		var card_flag = $('#time_flag' + dataid).val();
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/po_select_wcl",
			type: "post",
			data: {
				_token: csrf_token,
				po: po,
				parcel_id:parcel_id,
				season_code:season_code,
				uvc_id:uvc_id,
				card_flag:card_flag,
			},
            dataType: "json",
			success: function (data) {
				if($('#'+po+'_'+dataid).val() == 1)
				{
					$('#save_wash_button_'+dataid).addClass('hidden')
					$('#print_wash_button_'+dataid).removeClass('hidden')
					$("#wash_care_label_table_"+dataid+" :input").prop("disabled", true);
					$i=0;
					$qty=0;
					$('#wash_care_label_table_'+dataid+' tbody tr').each(function () {
						$(this).find('.tot_qty_tablewash').val(data.orchestra_wash_care_label_orders_qty[$i].quantity)
						$qty+=data.orchestra_wash_care_label_orders_qty[$i].quantity;
						$i++;
					});

					$('.extra_percentagewash').val("")
					$('.tot_qty_tablewash_fin'+ dataid).text($qty)
					if(data.orchestra_wash_care_label_orders[0].merchandiser_comment != "" && data.orchestra_wash_care_label_orders[0].merchandiser_comment != null)
					{
						$('#comment_section_'+dataid).text(data.orchestra_wash_care_label_orders[0].merchandiser_comment)
					}
					else
					{
						$('#comment_section_'+dataid).text("null")
					}
					

				}
				else
				{
					$('#save_wash_button_'+dataid).removeClass('hidden')
					$('#print_wash_button_'+dataid).addClass('hidden')
					$("#wash_care_label_table_"+dataid+" :input").prop("disabled", false);
					$('#wash_care_label_table_'+dataid+' tbody tr').each(function () {
						$(this).find('.tot_qty_tablewash').val("")
					});

					$('.extra_percentagewash').val("")
					$('.tot_qty_tablewash_fin'+ dataid).text(0)
					$('#comment_section_'+dataid).text("null")
				}
				
			},
			error: function (data) {

			},
		})
		
		
		});
	
    $('.care_instruction_select'+dataid).parent().removeClass('has-error')
    $('.help-block').html('')
    $('.extra_percentagewash').keyup(function () {
		
        
        var tot_qty_tablewash_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablewash').val(tot_qty);
                if ($(this).find('.tot_qty_tablewash').val() != undefined) {
                    tot_qty_tablewash_carton_final += parseInt($(this).find('.tot_qty_tablewash').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablewash_fin'+ dataid).text(tot_qty_tablewash_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablewash').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablewash_fin'+ dataid).text("0");
        }

    })

    selectpicker()

    var date = new Date();
    date.setDate(date.getDate() + 7);

    var dateMsg = (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();

    $("#clickme" + dataid).click(function () {
        
       if( $('.merchandisers option:selected').val() != "")
       {
            composition_percentage_global = 0;
		    if($('#composition_flag'+ dataid).val() != "disabled")
			{
				$('.composition_select' + dataid).prop('disabled', false)
				$('.composition_percent' + dataid).removeAttr('disabled')
            	$('.add_to_list' + dataid).removeAttr('disabled')
			}
            $('.parts_select' + dataid).prop('disabled', false)
            $(".parts_select" + dataid).val('');
            $('.parts_select' + dataid).selectpicker('refresh');
            $(".composition_select" + dataid).val('');
            $('.composition_select' + dataid).selectpicker('refresh');
            $(".composition_percent" + dataid).val('');
            $('#composition_table_qty' + dataid).html("")
            

            //$('.washlabelnameqty' + dataid).text($('.washlabelname' + dataid).text())
            $("#washcarelabelform" + dataid + " .backdrop").toggleClass('active');
            $("#washcarelabelform" + dataid + " .right-panel").toggleClass('active');
            $('#washcarelabelform' + dataid + ' .scalable').toggleClass('active');
            $("#washcarelabelform" + dataid + " .left-panelorc").toggleClass('active');
            $('.merchandiser_error').addClass('hidden')
       }
       else
       {
                $('.snackbar_span').html('Please Select Merchandiser');
                showSnackbar();
       }
		//alert()
		$('.tot_qty_tablewash').parent().removeClass('has-error').removeClass('has-danger')
        
    })

    $(".backdrop, #close").click(function () {
        $("#washcarelabelform" + dataid + " .left-panelorc").removeClass('active');
        $("#washcarelabelform" + dataid + " .right-panel").removeClass('active');
        $("#washcarelabelform" + dataid + " .backdrop").removeClass('active');
        $('#washcarelabelform' + dataid + ' .scalable').removeClass('active');
    })
	
	var mySVG_back = document.getElementById("washcare_svg_back" + dataid);
    var svgDoc_back;
    mySVG_back.addEventListener("load", function () {
        svgDoc_back = mySVG_back.contentDocument;
	
	if (data.orchestra_wash_care_label_orders != "") {
            if (dataid != "") {
                dataid1 = dataid;
            } else {
                dataid1 = 0;
            }

            if (data.orchestra_wash_care_label_orders.length > dataid1) {
                $('.care_instruction_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].care_instruction);
                svgDoc_back.getElementById("text_care_instruction").innerHTML = "";
                $('.care_instruction_select' + dataid + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german') + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek') + '</p>';
                    svgDoc_back.getElementById("text_care_instruction").innerHTML += value;
                });


               

                var page_id = 0;
                //var comps="";
                $.each(data.orchestra_wash_care_label_orders[dataid1].composition, function (index, value) {
                    if (value.page_id != page_id) {
                        var comps="";
                        $.each(data.orchestra_wash_care_label_orders[dataid1].composition, function (index1, value1) {
                            if(value.page_id == value1.page_id)
                            {
                                if(comps != "")
                                {
                                    comps+=","+value1.french+"_"+value1.english+"_"+value1.spanish+"_"+value1.portuguese+"_"+value1.italian+"_"+value1.dutch+"_"+value1.german+"_"+value1.arabic+"_"+value1.greek+"_"+value1.percentage;
                                }
                                else
                                {
                                    comps+=value1.french+"_"+value1.english+"_"+value1.spanish+"_"+value1.portuguese+"_"+value1.italian+"_"+value1.dutch+"_"+value1.german+"_"+value1.arabic+"_"+value1.greek+"_"+value1.percentage;
                                }
                                
                            }
                            
                        });
                        
                        add_page_composition += 1;
                        $('#composition_remove_qty' + dataid).append('<tr id="' + add_page_composition + '"><td>Composition ' + add_page_composition + '</td><td><input type="hidden" value="'+comps+'" class="compositions_hidden"><select class="select_parts'+add_page_composition+'">' + parts_option + '</select></td><td><a style="cursor:pointer" onclick="deleteComposition(this,' + dataid + ')"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>')
                        
                        if(value.parts_id != "")
                        {
                            $('.select_parts'+add_page_composition).val(value.parts_id)
                        }
                        
                        
                        var washcomp=[];
                        $('#composition_remove_qty' + dataid + ' tr').each(function () {
                              var washcomp1=[];
                              var id=$(this).attr('id')
                              var value=$(this).find('select').find(':selected').val();
                              var compo =$(this).find('.compositions_hidden').val();
                              var compss = compo.split(',');
                                $(compss).each(function (index1,value1){ 
                                    var english = value1.split('_');
                                    washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                                });
                            washcomp.push(washcomp1)
                        })
                        
                        
                        var wash_finals = JSON.stringify(washcomp);
                        localStorage.setItem('wash_composition_final'+dataid, wash_finals);
                        
                        if (value.parts_english != null) {
                            svgDoc_back.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + add_page_composition + '" id="composition_' + add_page_composition + '">' + value.parts_french + "-" + value.parts_english + "-" + value.parts_spanish + "-" + value.parts_portuguese + "-" + value.parts_italian + "-" + value.parts_dutch + "-" + value.parts_german + "-" + value.parts_arabic + "-" + value.parts_greek + '</p>';
                        }
                        
                    }
                    svgDoc_back.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + add_page_composition + '" id="composition_' + add_page_composition + '">' + value.percentage + "% " + value.french + "-" + value.english + "-" + value.spanish + "-" + value.portuguese + "-" + value.italian + "-" + value.dutch + "-" + value.german  + "-" + value.arabic + "-" + value.greek + '</p>';

                    page_id = value.page_id;

                });
            }
		
			svgDoc_back.getElementById("product_family").innerHTML = data.product_family.length > 0 ? data.product_family[0].arabic : "";

        }
		
		 $('.care_instruction_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.care_instruction_select' + dataid + ' option:selected').val() != "") {
                svgDoc_back.getElementById("text_care_instruction").innerHTML = "";
                $('.care_instruction_select' + dataid + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek') + '</p>';
                    svgDoc_back.getElementById("text_care_instruction").innerHTML += value;
                });


            } else {

            }


        });
		
		}, false);

    var mySVG = document.getElementById("washcare_svg_front" + dataid);
    var svgDoc;
    mySVG.addEventListener("load", function () {
        svgDoc = mySVG.contentDocument;

        //$('.washing_select'+dataid+' option[data-id="1"]').prop('selected', true)

        if (data.orchestra_wash_care_label_orders != "") {
            if (dataid != "") {
                dataid1 = dataid;
            } else {
                dataid1 = 0;
            }

            if (data.orchestra_wash_care_label_orders.length > dataid1) {
                $('.made_in_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].made_in_id);
                var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" + $('select.made_in_select' + dataid).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + dataid).find(':selected').data('italian') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('greek') + '</p>';
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;
				
                $('.washing_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].washing_id);

                $('.merchandisers').selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].merchandisers_id);

                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('astm'));
                $('.whitening_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].whitening_id);
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('astm'));
                $('.dryer_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].dryer_id);
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('astm'));
                $('.ironing_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].ironing_id);
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('astm'));
                $('.cleaning_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].cleaning_id);
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('astm'));

                
            }

        }


        $('.washing_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.washing_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', "");
            }


        });
        $('.whitening_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.whitening_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', "");
            }
        });
        $('.dryer_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.dryer_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', "");
            }

        });
        $('.ironing_select' + dataid + '.bootstrap-select').change(function () {
            if ($('.ironing_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', "");
            }

        });
        $('.cleaning_select' + dataid + '.bootstrap-select').change(function () {
            if ($('.cleaning_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', "");
            }

        });
        $('.made_in_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.made_in_select' + dataid + ' option:selected').val() != "") {

                var text_for_mad_in =  $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" + $('select.made_in_select' + dataid).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + dataid).find(':selected').data('italian') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('greek');
				//console.log(text_for_mad_in);
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;

            } else {

            }


        });

       


        var size = data.order_details[0].Size.split(" ")

        if (size[1] == "ANS") {
            var size1 = "years";
        } else {
            var size1 = "months";
        }
        svgDoc.getElementById("parcel_code1").innerHTML = data.order_details[0].UVC_TCO;
        //svgDoc.getElementById("product_family").innerHTML = data.product_family[0].arabic;
        svgDoc.getElementById("parcel_code2").innerHTML = data.order_details[0].UVC_TCO;
		
        //alert(data.order_details[0].Size)
		
		if(data.order_details[0].Size == "TAILLE UNIQUE")
		{
			//var inches=$('#washcarelabelform' + dataid+' .washonesizein').val()
			var cm=$('#washcarelabelform' + dataid+' .washonesizecm').val()
			
			svgDoc.getElementById("size_in_fr_en1").innerHTML = cm+"cm";
        svgDoc.getElementById("size_in_fr_en2").innerHTML = cm+"cm";
			svgDoc.getElementById("size1").innerHTML ="TU";
			svgDoc.getElementById("size2").innerHTML ="TU";
		}
		else if(data.order_details[0].Size == "TAILLE L")
		{
			svgDoc.getElementById("size1").innerHTML = "TAILLE L";
			svgDoc.getElementById("size2").innerHTML = "TAILLE L";
			svgDoc.getElementById("size_in_fr_en1").innerHTML = "";
			svgDoc.getElementById("size_in_fr_en2").innerHTML = "";
		}
		else
		{
			svgDoc.getElementById("size_in_fr_en1").innerHTML = data.order_details[0].cm + "cm";
        svgDoc.getElementById("size_in_fr_en2").innerHTML = data.order_details[0].cm + "cm";
			  svgDoc.getElementById("size1").innerHTML = data.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
			  svgDoc.getElementById("size2").innerHTML = data.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
		}
      


        var code = data.order_details[0].BarcodeForPricesTags.toString();
        jsbarcodewash(code, 'barcode', svgDoc)
    }, false);
}


/**
 * This Function is Sub Function of #washcarelabels.
 * To add a compoistion percent into list
 * @return composition list
 */

function add_to_list(dataid) {
	
	
	if (dataid === undefined) {
		dataid = "";
	} else {
		dataid = dataid;
	}

	if ($('.composition_select' + dataid + ' option:selected').val() != "Select Option" && $('.composition_percent' + dataid).val() != "") {

		composition_percentage_global += parseInt($('.composition_percent' + dataid).val());

		if (composition_percentage_global <= "100") {

			composition_percentage_global = composition_percentage_global;
			$('#composition_table_qty' + dataid).append("<tr><td class='comp'><input type='hidden' class='list_comp' value='" + $('.composition_select' + dataid + ' option:selected').attr('data-french') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-english') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-spanish') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-portuguese') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-italian') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-dutch') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-german') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-arabic') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-greek') + "_" + parseInt($('.composition_percent' + dataid).val()) + "'>" + $('.composition_select' + dataid + ' option:selected').text() + "</td><td class='percent'>" + parseInt($('.composition_percent' + dataid).val()) + "</td></tr>")
		} else {
			composition_percentage_global -= parseInt($('.composition_percent' + dataid).val());
		}

		$('.parts_select' + dataid).prop('disabled', 'true')
		$('#composition_error_required' + dataid).addClass('hidden')
		$('#percentage_error_required' + dataid).addClass('hidden')
	}
	else if ($('.composition_select' + dataid + ' option:selected').val() == "Select Option")
	{
		$('#composition_error_required' + dataid).removeClass('hidden')
		$('#percentage_error_required' + dataid).addClass('hidden')
		
	}
	else if($('.composition_percent' + dataid).val() == "")
	{
		$('#percentage_error_required' + dataid).removeClass('hidden')
		$('#composition_error_required' + dataid).addClass('hidden')
	}
	if (composition_percentage_global == "100") {
		$('.composition_select' + dataid).prop('disabled', 'true')
		$('.composition_percent' + dataid).prop('disabled', 'true')
		$('.add_to_list' + dataid).prop('disabled', 'true')
		$('.add_to_composition' + dataid).removeAttr('disabled')
	}
	$('#wash_composition_error' + dataid).addClass('hidden')

}

/**
 * This Function is Sub Function of #washcarelabels.
 * To add a compoistion into tables
 * @return view
 */

function add_to_composition(dataid) {
    add_page_composition += 1;
    $('#wash_composition_error' + dataid).addClass('hidden')
    if (dataid === undefined) {
        dataid = "";
    } else {
        dataid = dataid;
    }
    composition_percentage_global = 0;
    var mySVG = document.getElementById("washcare_svg_back" + dataid);
    var svgDoc = mySVG.contentDocument;
    var a = [];
    var com = [];
     //var com_ajax = [];
    $('#composition_table_qty' + dataid + ' tr').each(function () {
        var comp = $(this).find('.list_comp').val();
        var comp1 = comp.split('_');
        var percent = $(this).find('.percent').text();
        var parts = $('.parts_select' + dataid + ' option:selected').val()
        a.push(comp1[0]+"_"+comp1[1]+"_"+comp1[2]+"_"+comp1[3]+"_"+comp1[4]+"_"+comp1[5]+"_"+comp1[6]+"_"+comp1[7]+"_"+comp1[8]+"_"+comp1[9])
       
    })
    comps = a.join(',');
    if (localStorage.getItem('wash_composition') != null) {
        var str = localStorage.getItem('wash_composition');
        var n = str.includes('wash_composition' + dataid);
        if (n == false) {
            wash_composition['wash_composition' + dataid] = {};
        }
    } else {
        wash_composition['wash_composition' + dataid] = {};
    }

    wash_composition['wash_composition' + dataid][add_page_composition] = com;
    var wash_comp = JSON.stringify(wash_composition);
    localStorage.setItem('wash_composition', wash_comp);

    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    var buyer = localStorage.getItem("buyer");

            $('#composition_remove_qty' + dataid).append('<tr id="' + add_page_composition + '"><td>Composition ' + add_page_composition + '</td><td><input type="hidden" value="'+comps+'" class="compositions_hidden">'+$('.parts_none'+ dataid).html()+'</td><td><a style="cursor:pointer" onclick="deleteComposition(this,' + dataid + ')"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>')

            $(".add_to_list" + dataid).prop("disabled", false);
            $('.composition_select' + dataid).prop('disabled', false)
            $('.composition_percent' + dataid).prop('disabled', false)
            $('#composition_table_qty' + dataid).html("")
            $('.parts_select' + dataid).prop('disabled', false)
            $('.add_to_composition' + dataid).attr('disabled', 'disabled')
            $('.composition_percent' + dataid).val("");
            $(".composition_select" + dataid).val('');
            $('.composition_select' + dataid).selectpicker('refresh');
            $(".parts_select" + dataid).val('');
            $('.parts_select' + dataid).selectpicker('refresh');
			$('#wash_composition_error' + dataid).addClass('hidden')

}

/**
 * This Function is Sub Function of #washcarelabels.
 * To add a compoistion percent into artwork
 * @return view
 */

function add_to_page(dataid) {
  if (dataid === undefined) {
    dataid = "";
  } else {
    dataid = dataid;
  } 
    var mySVG = document.getElementById("washcare_svg_back" + dataid);
    var svgDoc = mySVG.contentDocument;
    
    var check=0;
    var check1=0;
     $('#composition_remove_qty' + dataid + ' tr').each(function () {
         
         if($(this).find('select').find(':selected').text() == "Select Option")
             {
                 check++;
             }
         check1++;
     });
    
    if(check1 <2 )
    {
        check=0;
    }
    
    if(check == 0 )
        {
              svgDoc.getElementById("parts_composition").innerHTML ="";
              $('#composition_remove_qty' + dataid + ' tr').each(function () {
                  if($(this).find('select').find(':selected').val() != "Select Option")
                  {
                       svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german') + "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek')  + '</p>';
                  }
                 
                  var id=$(this).attr('id')
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                  $(compss).each(function (index1,value1) {
                      var english = value1.split('_');
					  

                    svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
                });
              })
            $('.composition_error_hidden').addClass('hidden')
            var washcomp=[];
            $('#composition_remove_qty' + dataid + ' tr').each(function () {
                  var washcomp1=[];
                  var id=$(this).attr('id')
                  var value=$(this).find('select').find(':selected').val();
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                    $(compss).each(function (index1,value1){ 
                        var english = value1.split('_');
                        washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                    });
                washcomp.push(washcomp1)
            })
            var wash_finals = JSON.stringify(washcomp);
            localStorage.setItem('wash_composition_final'+dataid, wash_finals);
        }
    else
        {
            $('.composition_error_hidden').removeClass('hidden')
        }
		$('#wash_composition_page_error' + dataid).addClass('hidden')
}


/**
 * This Function is Sub Function of #washcarelabels.
 * To delete compoistion percent into artwork
 * @return view
 */

function deleteComposition(obj, dataid) {

	// add_page_composition-=1;
	if (dataid === undefined) {
		dataid = "";
	} else {
		dataid = dataid;
	}
	$(obj).parent().parent().remove();
	var classname = $(obj).parent().parent().attr('id');
	var mySVG = document.getElementById("washcare_svg_back" + dataid);
	var svgDoc = mySVG.contentDocument;
	var list = svgDoc.getElementsByClassName("composition_" + classname);
	for (var i = list.length - 1; 0 <= i; i--) {
		list[i].remove();
	}
	var wash = localStorage.getItem('wash_composition');
	var wash_fin = JSON.parse(wash);
	//alert(add_page_composition)
	delete wash_fin['wash_composition' + dataid][classname];
	delete wash_composition['wash_composition' + dataid][classname];
	var wash_final = JSON.stringify(wash_fin);
	localStorage.setItem('wash_composition', wash_final);
}


/**
 * This Function is Sub Function of #label_order_details.
 * To integrate Main Label Information
 * @return Main Label View
 */

function mainlabels(data) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_main_label_orders != "") {

      
        $template_id = data.orchestra_main_label_orders[0].template_id;
        $exist_id = data.orchestra_main_label_orders[0].id;
        
        var str=data.orchestra_main_label_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
    } else {
     
        $template_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
        $save_button_class="";
    }
    
    var option = "<option value=''>Select Option</option>";
	var ml="";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
	$c=0;
	$.each(data.color_wcl, function (index, value) {
		if (data.orchestra_main_label_orders != "") {
			$main_label_count=data.orchestra_main_label_orders_qty[$c].quantity;
		}else{
			 $main_label_count="";
		}
		
        ml += '<label for="edit_merchandiser_buyer" class="form_label">Final order qty for colour '+value.Color_Code+'</label><input class="form-control input-sm main_label_qty" value="' + $main_label_count + '" onkeypress="return checkorcMOQ(this)" placeholder="Main Label Count" type="number" required>';
		$c++;
    });
	
    $('.top-content').append('<form id="main_labelform" data-toggle= "validator"><div class="left-panelorc col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-5"><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="main_label_template_error" style="color:red" class="hidden">Please select the template</label>  <div><input type="text" id="treeml_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treeml" style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-7"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-4"> <div class="div-max"> <h4>Quantity Info for Main Label</h4><label style="color:#ff9800">This is only to place main label orders. Size label orders you want to place seperately</label> <hr> <div class="scroll"><div class="form-group has-feedback has-success">'+ml+'<div class="help-block with-errors"></div></div><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_main_label_button" style="margin-left:10px">Save</button> <button class="btn btn-default pull-right input-sm" type="button" id="main_labelclose">Close</button>  </div></div> </div>  <div class="backdrop"></div></form>');
	//$('.left').removeClass('disabled')
    $('#main_labelform .tot_qty_table').keyup(function () {
        var tot_qty_table_carton_final = 0;
        $('#main_labelform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table').val() != undefined) {
                tot_qty_table_carton_final += parseInt($(this).find('.tot_qty_table').val());
            }
        })
        $('#main_labelform .tot_qty_table_fin').text(tot_qty_table_carton_final);
    });

    $("#clickmemain_label").click(function () {
        // $('.sample-artwork').html("")
        $("#main_labelform .backdrop").toggleClass('active');
        $("#main_labelform .right-panel").toggleClass('active');
        $('#main_labelform .scalable').toggleClass('active');
        $("#main_labelform .left-panelorc").toggleClass('active');
    })

    $(".backdrop, #main_labelclose").click(function () {
        $("#main_labelform .left-panelorc").removeClass('active');
        $("#main_labelform .right-panel").removeClass('active');
        $("#main_labelform .backdrop").removeClass('active');
        $('#main_labelform .scalable').removeClass('active');
    })



    treestructuremain_label(data.treeviewmain_label, "treeml", $template_id,data.orchestra_main_label_orders);

    $('#main_labelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setMainLabelStatus(data.color_wcl)
        }
    })
}
/**
 * This Function is Sub Function of #label_order_details.
 * To integrate Main Label Information
 * @return Main Label View
 */

function sizelabels(data) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_size_label_orders != "") {

      
        $template_id = data.orchestra_size_label_orders[0].template_id;
        $exist_id = data.orchestra_size_label_orders[0].id;
        $size_label_count=data.orchestra_size_label_orders[0].final_qty;
        
        var str=data.orchestra_size_label_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
    } else {
     
        $template_id = "";
        $exist_id = "";
        $size_label_count="";
        $status="";
        $status_text="";
        $save_button_class="";
    }
    var ttots = 0;
    var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_size_label_orders != "") {

            $size_label_count = data.orchestra_size_label_orders_qty[$qu].quantity;
        } else {
            $size_label_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)"  type="number" class="form-control  input-sm tot_qty_tablesize" data-required-error="Required" value="' + $size_label_count + '" required placeholder="Size Label Count"><div class="help-block with-errors"></div></div></td></tr>'; 
        ttots += parseInt($size_label_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
	
	 if(isNaN(ttots))
        {
            ttots=0;
        }
    else
        {
            ttots=ttots;
        }
    
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.top-content').append('<form id="size_labelform" data-toggle= "validator"><div class="left-panelorc col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-5 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="size_label_template_error" style="color:red" class="hidden">Please select the template</label>  <div><input type="text" id="treesl_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treesl" style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-7"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Size Label</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="size_label_table"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty</th><th><label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentagesize" onkeypress="return checkNumbersOnly(event)"  style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots_sum + '</td><td class="text-center tot_qty_tablesize_fin">' + ttots + '</td></tr></tbody></table></div> <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_size_label_button" style="margin-left:10px">Save</button> <button class="btn btn-default pull-right input-sm" type="button" id="size_labelclose">Close</button></div> </div>  <div class="backdrop"></div></form>');
	//$('.left').removeClass('disabled')
    $('#size_labelform .tot_qty_tablesize').keyup(function () {
        var tot_qty_tablesize_carton_final = 0;
        $('#size_labelform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablesize').val() != undefined) {
                tot_qty_tablesize_carton_final += parseInt($(this).find('.tot_qty_tablesize').val());
            }
        })
        $('#size_labelform .tot_qty_tablesize_fin').text(tot_qty_tablesize_carton_final);
    });

	 $('.extra_percentagesize').keyup(function () {
		
        
        var tot_qty_tablesize_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablesize').val(tot_qty);
                if ($(this).find('.tot_qty_tablesize').val() != undefined) {
                    tot_qty_tablesize_carton_final += parseInt($(this).find('.tot_qty_tablesize').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablesize_fin').text(tot_qty_tablesize_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablesize').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablesize_fin').text("0");
        }

    })
	
	
    $("#clickmesize_label").click(function () {
        // $('.sample-artwork').html("")
        $("#size_labelform .backdrop").toggleClass('active');
        $("#size_labelform .right-panel").toggleClass('active');
        $('#size_labelform .scalable').toggleClass('active');
        $("#size_labelform .left-panelorc").toggleClass('active');
    })

    $(".backdrop, #size_labelclose").click(function () {
        $("#size_labelform .left-panelorc").removeClass('active');
        $("#size_labelform .right-panel").removeClass('active');
        $("#size_labelform .backdrop").removeClass('active');
        $('#size_labelform .scalable').removeClass('active');
    })



    treestructuresize_label(data.treeviewsize_label, "treesl", $template_id,data.orchestra_size_label_orders);

    $('#size_labelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSizeLabelStatus(data.color_wcl)
        }
    })
}
/**
 * This Function is Sub Function of #label_order_details.
 * To integrate Size Labels Information
 * @return Special Tag View
 */

function specialtags(data) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_special_tag_orders != "") {

      
        $template_id = data.orchestra_special_tag_orders[0].template_id;
        $exist_id = data.orchestra_special_tag_orders[0].id;
        
        var str=data.orchestra_special_tag_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
    } else {
     
        $template_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
        $save_button_class="";
    }
    var st="";
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
	$c=0;
	$.each(data.color_wcl, function (index, value) {
		if (data.orchestra_special_tag_orders != "") {
			$special_tag_count=data.orchestra_special_tag_orders_qty[$c].quantity;
		}else{
			 $special_tag_count="";
		}
		
        st += '<label for="edit_merchandiser_buyer" class="form_label">Final order qty for colour '+value.Color_Code+'</label><input class="form-control input-sm special_qty" value="' + $special_tag_count + '" onkeypress="return checkorcMOQ(this)" placeholder="Special Tag Count" type="number" required>';
		$c++;
    });
    $('.top-content').append('<form id="specialtagform" data-toggle= "validator"><div class="left-panelorc col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="special_template_error" style="color:red" class="hidden">Please select the template</label>  <div><input type="text" id="tree2_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree2" style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-4"> <div class="div-max"> <h4>Quantity Info for Special Tag</h4> <hr> <div class="scroll"><div class="form-group has-feedback has-success">'+st+'<div class="help-block with-errors"></div></div><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_special_button" style="margin-left:10px">Save</button> <button class="btn btn-default pull-right input-sm" type="button" id="specialclose">Close</button>  </div></div> </div>  <div class="backdrop"></div></form>');
	//$('.left').removeClass('disabled')
    $('#specialtagform .tot_qty_tablespecial').keyup(function () {
        var tot_qty_tablespecial_carton_final = 0;
        $('#specialtagform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablespecial').val() != undefined) {
                tot_qty_tablespecial_carton_final += parseInt($(this).find('.tot_qty_tablespecial').val());
            }
        })
        $('#specialtagform .tot_qty_tablespecial_fin').text(tot_qty_tablespecial_carton_final);
    });

    $("#clickmespecial").click(function () {
        // $('.sample-artwork').html("")
        $("#specialtagform .backdrop").toggleClass('active');
        $("#specialtagform .right-panel").toggleClass('active');
        $('#specialtagform .scalable').toggleClass('active');
        $("#specialtagform .left-panelorc").toggleClass('active');
    })

    $(".backdrop, #specialclose").click(function () {
        $("#specialtagform .left-panelorc").removeClass('active');
        $("#specialtagform .right-panel").removeClass('active');
        $("#specialtagform .backdrop").removeClass('active');
        $('#specialtagform .scalable').removeClass('active');
    })



    treestructurespecial(data.treeviewspecial, "tree2", $template_id,data.orchestra_special_tag_orders);

    $('#specialtagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSpecialTagStatus(data.color_wcl)
        }
    })
}
/**
 * This Function is Sub Function of #label_order_details.
 * To integrate heatTransfer Information
 * @return Special Tag View
 */

function heatTransfer(data) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_heat_transfer_orders != "") {

      
        $template_id = data.orchestra_heat_transfer_orders[0].template_id;
        $exist_id = data.orchestra_heat_transfer_orders[0].id;
        $heat_transfer_count=data.orchestra_heat_transfer_orders[0].final_qty;
        
        var str=data.orchestra_heat_transfer_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
    } else {
     
        $template_id = "";
        $exist_id = "";
        $heat_transfer_count="";
        $status="";
        $status_text="";
        $save_button_class="";
    }
    var ttots = 0;
	var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_heat_transfer_orders != "") {

            $heat_transfer_count = data.orchestra_heat_transfer_orders_qty[$qu].quantity;
        } else {
            $heat_transfer_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)"  type="number" class="form-control  input-sm tot_qty_tableheat" data-required-error="Required" value="' + $heat_transfer_count + '" required placeholder="HT Count"><div class="help-block with-errors"></div></div></td></tr>';
        ttots += parseInt($heat_transfer_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
	 if(isNaN(ttots))
        {
            ttots=0;
        }
    else
        {
            ttots=ttots;
        }
    
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.top-content').append('<form id="heat_transferform" data-toggle= "validator"><div class="left-panelorc col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="heat_transfer_template_error" style="color:red" class="hidden">Please select the template</label>  <div><input type="text" id="treeht_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treeht" style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Heat Transfer</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="heat_transfer_table"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty</th><th><label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentageheat" onkeypress="return checkNumbersOnly(event)"  style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots_sum + '</td><td class="text-center tot_qty_tableheat_fin">' + ttots + '</td></tr></tbody></table></div> <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_heat_transfer_button" style="margin-left:10px">Save</button> <button class="btn btn-default pull-right input-sm" type="button" id="heat_transferclose">Close</button></div> </div>  <div class="backdrop"></div></form>');
	//$('.left').removeClass('disabled')
    $('#heat_transferform .tot_qty_tableheat').keyup(function () {
        var tot_qty_tableheat_carton_final = 0;
        $('#heat_transferform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tableheat').val() != undefined) {
                tot_qty_tableheat_carton_final += parseInt($(this).find('.tot_qty_tableheat').val());
            }
        })
        $('#heat_transferform .tot_qty_tableheat_fin').text(tot_qty_tableheat_carton_final);
    });
	
	 $('.extra_percentageheat').keyup(function () {
		
        
        var tot_qty_tableheat_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tableheat').val(tot_qty);
                if ($(this).find('.tot_qty_tableheat').val() != undefined) {
                    tot_qty_tableheat_carton_final += parseInt($(this).find('.tot_qty_tableheat').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tableheat_fin').text(tot_qty_tableheat_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tableheat').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tableheat_fin').text("0");
        }

    })

    $("#clickmeheat_transfer").click(function () {
        // $('.sample-artwork').html("")
        $("#heat_transferform .backdrop").toggleClass('active');
        $("#heat_transferform .right-panel").toggleClass('active');
        $('#heat_transferform .scalable').toggleClass('active');
        $("#heat_transferform .left-panelorc").toggleClass('active');
    })

    $(".backdrop, #heat_transferclose").click(function () {
        $("#heat_transferform .left-panelorc").removeClass('active');
        $("#heat_transferform .right-panel").removeClass('active');
        $("#heat_transferform .backdrop").removeClass('active');
        $('#heat_transferform .scalable').removeClass('active');
    })



    treestructureheat_transfer(data.treeviewheat_transfer, "treeht", $template_id,data.orchestra_heat_transfer_orders);

    $('#heat_transferform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setheat_transferStatus(data.color_wcl)
        }
    })
}
/**
 * This Function is used to restrict alpha numeric and special characters.
 * 
 * @return boolean
 */

function checkNumbersOnly(e) {

	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		return false;
	}
}

/**
 * This Function is Sub Function of #label_order_details.
 * To integrate Hang Tag Information
 * @return Hang Tag View
 */

function hangtagmodal(data) {
    var order_details = data.order_details;
    var orchestra_hang_tag_orders_data = data.orchestra_hang_tag_orders;
    var tr = "";
    $qu = 0;
    if (data.orchestra_hang_tag_orders != "") {
       

        $template_front_id = data.orchestra_hang_tag_orders[0].template_front_id;
        $exist_id = data.orchestra_hang_tag_orders[0].id;
        
        var str=data.orchestra_hang_tag_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
		$print_button_class="";
		$save_button_class="hidden";
    } else {
       
        $template_front_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
		$print_button_class="hidden";
		$save_button_class="";
    }
    var ttots = 0;
    var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_hang_tag_orders != "") {

            $hang_tag_count = data.orchestra_hang_tag_orders_qty[$qu].quantity;
        } else {
            $hang_tag_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)"  type="number" class="form-control  input-sm tot_qty_tablehang" data-required-error="Required" value="' + $hang_tag_count + '" required placeholder="Hang Tag Count"><div class="help-block with-errors"></div></div></td></tr>';
        ttots += parseInt($hang_tag_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
	
	if(order_details[0].Size == "TAILLE UNIQUE")
	{
		if (data.orchestra_hang_tag_orders != "") {
            $hang_cm = data.orchestra_hang_tag_orders[0].cm;
			$hang_in = data.orchestra_hang_tag_orders[0].in;
        } else {
            $hang_cm = "";
			$hang_in = "";
        }
		var sizebox='<div class="form-group has-feedback"><label>Enter Size in Cm</label><input onkeypress="return checkNumbersOnly(event)" type="text" maxlength="3" class="form-control input-sm onesizecm" value="'+$hang_cm+'" required="" placeholder="Enter Size in Cm"><div class="help-block with-errors"></div></div>';
	}
	else
	{
		var sizebox="";
	}
    
    if(isNaN(ttots))
        {
            ttots=0;
        }
    else
        {
            ttots=ttots;
        }
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
    $('.top-content').append('<form id="hangtagform" data-toggle= "validator"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="left-panelorc col-md-8"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="hang_template_error" class="hidden" style="color:red">Please Select the Template</label>  <div><input type="text" id="tree1_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree1" style="height: calc(100vh - 260px);overflow-y: scroll;margin-top: 15px;"></div><div class="help-block with-errors"></div></div>'+sizebox+'</div><div class="col-md-4"><h4>Front</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr><div class="section-1 sections"><div class="sample-artwork" style="text-align:center"></div></div></div><div class="col-md-4"><h4>Back</h4><hr><div class="section-1 sections text-center" ><input type="hidden" value="' + data.orchestra_template_id + '" id="hang_tag_id"><div class="sample_back"></div><label style="margin-top:15px">Hang Tag Print Date : ' + data.order_details[0].HangTag_Print_Date + '</label><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div></div></div></div></div><div class="right-panel col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Hang Tag</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="hang_tag_table"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty</th><th><label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentagehang" onkeypress="return checkNumbersOnly(event)"  style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots_sum + '</td><td class="text-center tot_qty_tablehang_fin">' + ttots + '</td></tr></tbody></table></div> <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_hang_button" style="margin-left:10px">Save</button> <button class="btn btn-default pull-right input-sm" type="button" id="hangclose">Close</button><button type="button" class="btn btn-primary '+$print_button_class+'" id="print_hang_button" onclick=printHangDiv("svg2","'+data.order_details[0].Licence_Code+'")>Print</button> </div> </div>  <div class="backdrop"></div></form>');


//$('.left').removeClass('disabled')
    $('#hangtagform .tot_qty_tablehang').keyup(function () {
        var tot_qty_tablehang_carton_final = 0;
        $('#hangtagform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablehang').val() != undefined) {
                tot_qty_tablehang_carton_final += parseInt($(this).find('.tot_qty_tablehang').val());
            }
        })
        $('#hangtagform .tot_qty_tablehang_fin').text(tot_qty_tablehang_carton_final);
    });
	
	 $('.extra_percentagehang').keyup(function () {
		
        
        var tot_qty_tablehang_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablehang').val(tot_qty);
                if ($(this).find('.tot_qty_tablehang').val() != undefined) {
                    tot_qty_tablehang_carton_final += parseInt($(this).find('.tot_qty_tablehang').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablehang_fin').text(tot_qty_tablehang_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablehang').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablehang_fin').text("0");
        }

    })
	
//	$('#hangtagform .onesizein').keyup(function () {
//		
//		var inches=$(this).val();
//        var mySVG = document.getElementById("svg2");
//		var svgDoc;
//		svgDoc = mySVG.contentDocument;
//		svgDoc.getElementById("in").innerHTML = inches;
//
//		
//    });
	$('#hangtagform .onesizecm').keyup(function () {
		
		var cm=$(this).val();
        var mySVG = document.getElementById("svg2");
		var svgDoc;
		svgDoc = mySVG.contentDocument;
		svgDoc.getElementById("cm").innerHTML = cm;
		
    });

    $("#clickmehang").click(function () {
        // $('.sample-artwork').html("")
        $("#hangtagform .backdrop").toggleClass('active');
        $("#hangtagform .right-panel").toggleClass('active');
        $('#hangtagform .scalable').toggleClass('active');
        $("#hangtagform .left-panelorc").toggleClass('active');
    })

    $(".backdrop, #hangclose").click(function () {
        $("#hangtagform .left-panelorc").removeClass('active');
        $("#hangtagform .right-panel").removeClass('active');
        $("#hangtagform .backdrop").removeClass('active');
        $('#hangtagform .scalable').removeClass('active');
    })

    $('#tree1').jstree({
        'core': {
            'data': data.treefinalData
        },
        "types": {
            "default": {
                "icon": "fa fa-folder"
            }
        },
        "plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
    });
    $('#expand').bind("click", function () {
        $('#tree1').jstree("open_all");
    });
    $('#collapse').bind("click", function () {
        $('#tree1').jstree("close_all");
    });
    var to = false;
    $('#tree1_q').keyup(function () {
        if (to) {
            clearTimeout(to);
        }
        to = setTimeout(function () {
            var v = $('#tree1_q').val();
            $('#tree1').jstree(true).search(v);
        }, 250);
    });
    //Show Template when tree loads 
    $("#tree1").on("loaded.jstree", function (e, data) {
        $('#tree1').jstree(true).select_node($template_front_id);
        var ids = $(this).parent().parent().parent().parent().parent().attr('id');
        if ($template_front_id != "") {
            var buyer = localStorage.getItem("buyer");
            var template_id = $template_front_id;
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: base_url + "/admin/" + buyer + "/template-management/template-view",
                type: "Post",
                dataType: "json",
                data: {
                    template_id: template_id,
                    customer_id: $('#customer_id').val(),
                    _token: csrf_token
                },
                beforeSend: function () {
                    $(".loader").show();
                },
                success: function (data) {
					$('.tot_qty_tablehang').attr('min',data.moq)
					$('#hangtagform').validator('update')
                    if (data.treeviewData.length > 0) {
                        $('#' + ids).find('.sample-artwork').html('<object id="svg_hang_front" style="height: 486px;" class="center-block svg2" data="' + data.treeviewData[0].template_file + '" type="image/svg+xml"></object>')
                        $('#' + ids).find('.sample_back').html('<object class="center-block" id="svg2" style="height:486px" data="' + data.treeviewData[1].template_file + '" type="image/svg+xml"></object>')

                        if (data.treeviewData[0].disney != "DI") {
                            var mySVG_hang_disney = document.getElementById("svg_hang_front");
                            var svgDoc;
                            mySVG_hang_disney.addEventListener("load", function () {
                                svgDoc = mySVG_hang_disney.contentDocument;
                                var pri = order_details[0].PrixEurope.split(",")
								
								var size = order_details[0].Size.split(" ")
							
								if(order_details[0].Size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = "TU";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else if(order_details[0].Size == "TAILLE L")
								{
									svgDoc.getElementById("size").innerHTML = "TAILLE L";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else
								{
									//alert(size[0])
									svgDoc.getElementById("size").innerHTML = size[0];
									if (size[1] == "ANS") {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "years";

									} else {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "months";
									}
								}

                                if (pri.length == 1) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = innerHTML = order_details[0].PrixEurope;
                                    svgDoc.getElementById("price2").innerHTML = "";
                                } else if (pri.length == 2) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = innerHTML = pri[0];
                                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                                }
                                var pri1 = order_details[0].PrixClubEurope.toString().split(",")
                                if (pri1.length == 1) {
                                    svgDoc.getElementById("club_price1").innerHTML = order_details[0].PrixClubEurope;
                                    svgDoc.getElementById("club_price2").innerHTML = "";
                                } else if (pri1.length == 2) {
                                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                                }

                            }, false);
                        } else {
                            var mySVG_hang = document.getElementById("svg2");
                            var svgDoc;
                            mySVG_hang.addEventListener("load", function () {
                                svgDoc = mySVG_hang.contentDocument;
                                var pri = order_details[0].PrixEurope.split(",")
								
								var size = order_details[0].Size.split(" ")
							
								if(order_details[0].Size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = "TU";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else if(order_details[0].Size == "TAILLE L")
								{
									svgDoc.getElementById("size").innerHTML = "TAILLE L";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else
								{
									//alert(size[0])
									svgDoc.getElementById("size").innerHTML = size[0];
									if (size[1] == "ANS") {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "years";

									} else {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "months";
									}
								}
								
                                if (pri.length == 1) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = order_details[0].PrixEurope;
                                    svgDoc.getElementById("price2").innerHTML = "";
                                } else if (pri.length == 2) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = pri[0];
                                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                                }
                                var pri1 = order_details[0].PrixClubEurope.toString().split(",")
                                if (pri1.length == 1) {
                                    svgDoc.getElementById("club_price1").innerHTML = order_details[0].PrixClubEurope;
                                    svgDoc.getElementById("club_price2").innerHTML = "";
                                } else if (pri1.length == 2) {
                                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                                }

                            }, false);
                        }

                        var mySVG = document.getElementById("svg2");
                        var svgDoc;
                        mySVG.addEventListener("load", function () {
                            svgDoc = mySVG.contentDocument;
							
							svgDoc.getElementById("cm").innerHTML = $('.onesizecm').val();
							//svgDoc.getElementById("in").innerHTML = $('.onesizein').val();
							
                            var svgItem = svgDoc.getElementById("uvc_tco");
                            svgDoc.getElementById("algeria_club_price").innerHTML = order_details[0].Prix_Vente_ALGERIE != null ? order_details[0].Prix_Vente_ALGERIE.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("algeria_price").innerHTML = order_details[0].Prix_Club_ALGERIE != null ? order_details[0].Prix_Club_ALGERIE.toString().replace(".", ",") .replace(" ", ""): "";
                            svgDoc.getElementById("saudi_club_price").innerHTML = order_details[0].Prix_Vente_Arabie_Saoudite != null ? order_details[0].Prix_Vente_Arabie_Saoudite.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morroco_club_price").innerHTML = order_details[0].Prix_Vente_Maroc != null ? order_details[0].Prix_Vente_Maroc.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("swiss_club_price").innerHTML = order_details[0].Prix_Vente_Suisse != null ? order_details[0].Prix_Vente_Suisse.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("canada_club_price").innerHTML = order_details[0].Prix_Vente_Canada != null ? order_details[0].Prix_Vente_Canada.toString().replace(".", ",").replace(" ", "") : "";
                            //svgDoc.getElementById("turkey_price").innerHTML = order_details[0].Prix_Vente_Turquie != null ? order_details[0].Prix_Vente_Turquie.toString().replace(".", ",") : "";
                            svgDoc.getElementById("us_club_price").innerHTML = order_details[0].Prix_Vente_USA != null ? order_details[0].Prix_Vente_USA.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("us_price").innerHTML = order_details[0].Prix_Club_USA != null ? order_details[0].Prix_Club_USA.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morris_club_price").innerHTML = order_details[0].Prix_Vente_Ile_Maurice != null ? order_details[0].Prix_Vente_Ile_Maurice.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morris_price").innerHTML = order_details[0].Prix_Club_Ile_Maurice != null ? order_details[0].Prix_Club_Ile_Maurice.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morocco_price").innerHTML = order_details[0].Prix_Club_Maroc != null ? order_details[0].Prix_Club_Maroc.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("swiss_price").innerHTML = order_details[0].Prix_Club_Suisse != null ? order_details[0].Prix_Club_Suisse.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("canada_price").innerHTML = order_details[0].Prix_Club_Canada != null ? order_details[0].Prix_Club_Canada.toString().replace(".", ",").replace(" ", "") : "";
                           // svgDoc.getElementById("turkey_club_price").innerHTML = order_details[0].Prix_Club_Turquie != null ? order_details[0].Prix_Club_Turquie.toString().replace(".", ",") : "";
                            svgItem.innerHTML = order_details[0].UVC_TCO;
                            var size = order_details[0].Size.split(" ")
                            svgDoc.getElementById("theme").innerHTML = order_details[0].Theme;
							
							if(order_details[0].Size == "TAILLE UNIQUE")
							{
								svgDoc.getElementById("size").innerHTML = "TU";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
							}
							else if(order_details[0].Size == "TAILLE L")
							{
								svgDoc.getElementById("size").innerHTML = "TAILLE L";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
							}
							else
							{
								svgDoc.getElementById("size").innerHTML = size[0];
								
								svgDoc.getElementById("cm").innerHTML = order_details[0].cm;
								//svgDoc.getElementById("in").innerHTML = order_details[0].in;
								if (size[1] == "ANS") {
									svgDoc.getElementById("size_french").innerHTML = size[1];
									if(size[0] == 1)
									{
										svgDoc.getElementById("size_english").innerHTML = "YEAR";
									}
									else
									{
										svgDoc.getElementById("size_english").innerHTML = "YEARS";
									}
									
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-").split(',')[0];

								} else {
									svgDoc.getElementById("size_french").innerHTML = size[1];
									if(size[0] == 1)
									{
										svgDoc.getElementById("size_english").innerHTML = "MONTH";
									}
									else
									{
										svgDoc.getElementById("size_english").innerHTML = "MONTHS";
									}
									//alert(order_details[0].Age);
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-").split(',')[0]+"M";
								}
							}
                           if (data.treeviewData[0].disney != 1) {
							   var code = order_details[0].BarcodeForPricesTags.toString();
                               jsbarcode_hang(code, "barcode1", svgDoc)
						   }
						   else{
							   var code = order_details[0].BarcodeForPricesTags.toString();
                               jsbarcode_hang_disney(code, "barcode1", svgDoc)
						   }
                            


                        }, false);



                    } else {
                        $('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
						$('#' + ids).find('.sample_back').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
                    }
					
					if (orchestra_hang_tag_orders_data != "") {
						 $('#tree1 >ul > li').each( function() {
						   disable( 'tree1',this.id );        
						})
						$('.extra_percentagehang').attr('disabled','disabled')
						$('.tot_qty_tablehang').attr('disabled','disabled')
					}

                },
                error: function (data) {

                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
    });
    //Show Template when Click tree node 
    $('#tree1').on('activate_node.jstree', function (e, data) {
        var ids = $(this).parent().parent().parent().parent().parent().attr('id');
        if (data.instance.is_leaf(data.node)) {
            var buyer = localStorage.getItem("buyer");
            var template_id = data.node.id;
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: base_url + "/admin/" + buyer + "/template-management/template-view",
                type: "Post",
                dataType: "json",
                data: {
                    template_id: template_id,
                    customer_id: $('#customer_id').val(),
                    _token: csrf_token
                },
                beforeSend: function () {
                    $(".loader").show();
                },
                success: function (data) {
					$('.tot_qty_tablehang').attr('min',data.moq)
					$('#hangtagform').validator('update')
                    if (data.treeviewData.length > 0) {
                        $('#' + ids).find('.sample-artwork').html('<object id="svg_hang_front" style="height: 486px;" class="center-block svg2" data="' + data.treeviewData[0].template_file + '" type="image/svg+xml"></object>')
                        $('#' + ids).find('.sample_back').html('<object class="center-block" id="svg2" style="height:486px" data="' + data.treeviewData[1].template_file + '" type="image/svg+xml"></object>')

                        if (data.treeviewData[0].disney != "DI") {
                            var mySVG_hang_disney = document.getElementById("svg_hang_front");
                            var svgDoc;
                            mySVG_hang_disney.addEventListener("load", function () {
                                svgDoc = mySVG_hang_disney.contentDocument;
                                var pri = order_details[0].PrixEurope.split(",")
								
								var size = order_details[0].Size.split(" ")
							
								if(order_details[0].Size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = "TU";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else if(order_details[0].Size == "TAILLE L")
								{
									svgDoc.getElementById("size").innerHTML = "TAILLE L";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else
								{
									//alert(size[0])
									svgDoc.getElementById("size").innerHTML = size[0];
									if (size[1] == "ANS") {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "years";

									} else {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "months";
									}
								}

                                if (pri.length == 1) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = innerHTML = order_details[0].PrixEurope;
                                    svgDoc.getElementById("price2").innerHTML = "";
                                } else if (pri.length == 2) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = innerHTML = pri[0];
                                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                                }
                                var pri1 = order_details[0].PrixClubEurope.toString().split(",")
                                if (pri1.length == 1) {
                                    svgDoc.getElementById("club_price1").innerHTML = order_details[0].PrixClubEurope;
                                    svgDoc.getElementById("club_price2").innerHTML = "";
                                } else if (pri1.length == 2) {
                                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                                }

                            }, false);
                        } else {
                            var mySVG_hang = document.getElementById("svg2");
                            var svgDoc;
                            mySVG_hang.addEventListener("load", function () {
                                svgDoc = mySVG_hang.contentDocument;
                                var pri = order_details[0].PrixEurope.split(",")
								
								var size = order_details[0].Size.split(" ")
							
								if(order_details[0].Size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = "TU";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else if(order_details[0].Size == "TAILLE L")
								{
									svgDoc.getElementById("size").innerHTML = "TAILLE L";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else
								{
									//alert(size[0])
									svgDoc.getElementById("size").innerHTML = size[0];
									if (size[1] == "ANS") {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "years";

									} else {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "months";
									}
								}
								
                                if (pri.length == 1) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = order_details[0].PrixEurope;
                                    svgDoc.getElementById("price2").innerHTML = "";
                                } else if (pri.length == 2) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = pri[0];
                                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                                }
                                var pri1 = order_details[0].PrixClubEurope.toString().split(",")
                                if (pri1.length == 1) {
                                    svgDoc.getElementById("club_price1").innerHTML = order_details[0].PrixClubEurope;
                                    svgDoc.getElementById("club_price2").innerHTML = "";
                                } else if (pri1.length == 2) {
                                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                                }

                            }, false);
                        }

                        var mySVG = document.getElementById("svg2");
                        var svgDoc;
                        mySVG.addEventListener("load", function () {
                            svgDoc = mySVG.contentDocument;
							
							svgDoc.getElementById("cm").innerHTML = $('.onesizecm').val();
							//svgDoc.getElementById("in").innerHTML = $('.onesizein').val();
							
                            var svgItem = svgDoc.getElementById("uvc_tco");
                            svgDoc.getElementById("algeria_club_price").innerHTML = order_details[0].Prix_Vente_ALGERIE != null ? order_details[0].Prix_Vente_ALGERIE.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("algeria_price").innerHTML = order_details[0].Prix_Club_ALGERIE != null ? order_details[0].Prix_Club_ALGERIE.toString().replace(".", ",") .replace(" ", ""): "";
                            svgDoc.getElementById("saudi_club_price").innerHTML = order_details[0].Prix_Vente_Arabie_Saoudite != null ? order_details[0].Prix_Vente_Arabie_Saoudite.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morroco_club_price").innerHTML = order_details[0].Prix_Vente_Maroc != null ? order_details[0].Prix_Vente_Maroc.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("swiss_club_price").innerHTML = order_details[0].Prix_Vente_Suisse != null ? order_details[0].Prix_Vente_Suisse.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("canada_club_price").innerHTML = order_details[0].Prix_Vente_Canada != null ? order_details[0].Prix_Vente_Canada.toString().replace(".", ",").replace(" ", "") : "";
                            //svgDoc.getElementById("turkey_price").innerHTML = order_details[0].Prix_Vente_Turquie != null ? order_details[0].Prix_Vente_Turquie.toString().replace(".", ",") : "";
                            svgDoc.getElementById("us_club_price").innerHTML = order_details[0].Prix_Vente_USA != null ? order_details[0].Prix_Vente_USA.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("us_price").innerHTML = order_details[0].Prix_Club_USA != null ? order_details[0].Prix_Club_USA.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morris_club_price").innerHTML = order_details[0].Prix_Vente_Ile_Maurice != null ? order_details[0].Prix_Vente_Ile_Maurice.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morris_price").innerHTML = order_details[0].Prix_Club_Ile_Maurice != null ? order_details[0].Prix_Club_Ile_Maurice.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morocco_price").innerHTML = order_details[0].Prix_Club_Maroc != null ? order_details[0].Prix_Club_Maroc.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("swiss_price").innerHTML = order_details[0].Prix_Club_Suisse != null ? order_details[0].Prix_Club_Suisse.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("canada_price").innerHTML = order_details[0].Prix_Club_Canada != null ? order_details[0].Prix_Club_Canada.toString().replace(".", ",").replace(" ", "") : "";
                           // svgDoc.getElementById("turkey_club_price").innerHTML = order_details[0].Prix_Club_Turquie != null ? order_details[0].Prix_Club_Turquie.toString().replace(".", ",") : "";
                            svgItem.innerHTML = order_details[0].UVC_TCO;
                            var size = order_details[0].Size.split(" ")
                            svgDoc.getElementById("theme").innerHTML = order_details[0].Theme;
							
							if(order_details[0].Size == "TAILLE UNIQUE")
							{
								svgDoc.getElementById("size").innerHTML = "TU";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
							}
							else if(order_details[0].Size == "TAILLE L")
							{
								svgDoc.getElementById("size").innerHTML = "TAILLE L";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
							}
							else
							{
								svgDoc.getElementById("size").innerHTML = size[0];
								
								svgDoc.getElementById("cm").innerHTML = order_details[0].cm;
								//svgDoc.getElementById("in").innerHTML = order_details[0].in;
								if (size[1] == "ANS") {
									svgDoc.getElementById("size_french").innerHTML = size[1];
									if(size[0] == 1)
									{
										svgDoc.getElementById("size_english").innerHTML = "YEAR";
									}
									else
									{
										svgDoc.getElementById("size_english").innerHTML = "YEARS";
									}
									//alert(order_details[0].Age.match(/[0-9]+/g).toString());
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-").split(',')[0];

								} else {
									svgDoc.getElementById("size_french").innerHTML = size[1];
									if(size[0] == 1)
									{
										svgDoc.getElementById("size_english").innerHTML = "MONTH";
									}
									else
									{
										svgDoc.getElementById("size_english").innerHTML = "MONTHS";
									}
									//alert(order_details[0].Age.match(/[0-9]+/g).toString());
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-").split(',')[0]+"M";
								}
							}
                           if (data.treeviewData[0].disney != 1) {
							   var code = order_details[0].BarcodeForPricesTags.toString();
                               jsbarcode_hang(code, "barcode1", svgDoc)
						   }
						   else{
							   var code = order_details[0].BarcodeForPricesTags.toString();
                               jsbarcode_hang_disney(code, "barcode1", svgDoc)
						   }
                            


                        }, false);



                    } else {
                        $('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
						$('#' + ids).find('.sample_back').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
                    }

                },
                error: function (data) {

                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
    });

	
	
	
    $('#hangtagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setHangTagStatus(data.color_wcl)
        }
    })


    selectpicker()
    $('.template-select.bootstrap-select').on('changed.bs.select', function () {
        if ($('.template-select option:selected').val() != "") {
            $('.sample-artwork').html('<object id="svg1" data="' + $('.template-select option:selected').val() + '" style="width:233px;height:500px" type="image/svg+xml"></object>')
            var mySVG = document.getElementById("svg1");
            var svgDoc;
            mySVG.addEventListener("load", function () {
                svgDoc = mySVG.contentDocument;
                var pri = data.order_details[0].PrixEurope.split(",")

                if (pri.length == 1) {
                    var svgItem = svgDoc.getElementById("price1");
                    svgItem.innerHTML = innerHTML = data.order_details[0].PrixEurope;
                    svgDoc.getElementById("price2").innerHTML = "";
                } else if (pri.length == 2) {
                    var svgItem = svgDoc.getElementById("price1");
                    svgItem.innerHTML = innerHTML = pri[0];
                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                }
                var pri1 = data.order_details[0].PrixClubEurope.toString().split(",")
                if (pri1.length == 1) {
                    svgDoc.getElementById("club_price1").innerHTML = data.order_details[0].PrixClubEurope;
                    svgDoc.getElementById("club_price2").innerHTML = "";
                } else if (pri1.length == 2) {
                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                }

            }, false);
            $('.sample-artwork').css('background', '#fff');
        } else {
            $('.sample-artwork').html('');
            $('.sample-artwork').css('background', '#eee');
        }

    });
}

function disable(id,node_id) {
  var node = $("#"+id).jstree().get_node( node_id );
  $("#"+id).jstree().disable_node(node); 
  node.children.forEach( function(child_id) {            
    disable( id,child_id );
  })
} 
/**
 * This Function is Sub Function of #label_order_details.
 * To Create the cards in label order details page
 * @return View
 */

function orchestra_label_order_details(buyer, buyer_id, uvc, data) {

	var tr = "";
	var barcode = "";
	$.each(data.order_details, function (index, value) {
		tr += '<tr><td>' + value.UVC_TCO + '</td><td>' + value.Size + '</td><td>' + value.BarcodeForPricesTags + '</td><td>' + value.Sum + '</td><td><input type="text" class="form-control input-sm" placeholder="Hangtag Count"><td><input type="text" class="form-control input-sm" placeholder="Specialtag Count"><td><input type="text" class="form-control input-sm" placeholder="Main & Size label Count"></td><td><input type="text" class="form-control input-sm" placeholder="WCL Count"></td></tr>';
	});
	var arr=[];
	$.each(data.barcode, function (index, value) {
		if(arr.includes(value.BuyerPONo) == false)
		{
			arr.push(value.BuyerPONo)
			barcode += value.BuyerPONo + " | ";
		}
		
	});
	//    $('.top-content').html("<ol class='breadcrumb'><li><a href='#'>Dashboard</a></li><li><a href='/admin/order-management/'>Orders</a></li><li><a href='#'>Order List</a></li><li class='active'>Order Detail for UVC " + uvc + "</li></ol></div>")
	$('.top-content').append('<h3 class="content-heading">Order Detail for UVC ' + uvc + '</h3><h5 class="poc_number"><strong>POs for this UVC : </strong><span id="poNo">' + barcode + '  </span></h5>');

	var merchandisersoption = "<option value=''>Select Option</option>";
	var update_disable=0;

	$.each(data.merchandisers, function (index, value) {
		merchandisersoption += "<option value=" + value.id + ">" + value.merchandiser_name + "</option>";
	});
	if (data.orchestra_hang_tag_orders != "") {
		var str = data.orchestra_hang_tag_orders[0].status;
		$hang_status = str.replace(/ /g, "");
		$hang_status_text = data.orchestra_hang_tag_orders[0].status;
		update_disable=1;
	} else {
		$hang_status = "";
		$hang_status_text = "";
	}
	if (data.orchestra_special_tag_orders != "") {
		var str = data.orchestra_special_tag_orders[0].status;
		$special_status = str.replace(/ /g, "");
		$special_status_text = data.orchestra_special_tag_orders[0].status;
		update_disable=1;
	} else {
		$special_status = "";
		$special_status_text = "";
	}
	if (data.orchestra_heat_transfer_orders != "") {
		var str = data.orchestra_heat_transfer_orders[0].status;
		$heat_transfer_status = str.replace(/ /g, "");
		$heat_transfer_status_text = data.orchestra_heat_transfer_orders[0].status;
		update_disable=1;
	} else {
		$heat_transfer_status = "";
		$heat_transfer_status_text = "";
	}
	if (data.orchestra_main_label_orders != "") {
		var str = data.orchestra_main_label_orders[0].status;
		$main_label_status = str.replace(/ /g, "");
		$main_label_status_text = data.orchestra_main_label_orders[0].status;
		update_disable=1;
	} else {
		$main_label_status = "";
		$main_label_status_text = "";
	}
	if (data.orchestra_size_label_orders != "") {
		var str = data.orchestra_size_label_orders[0].status;
		$size_label_status = str.replace(/ /g, "");
		$size_label_status_text = data.orchestra_size_label_orders[0].status;
		update_disable=1;
	} else {
		$size_label_status = "";
		$size_label_status_text = "";
	}
	if (data.orchestra_wash_care_label_orders != "") {
		var str = data.orchestra_wash_care_label_orders[0].status;
		$wash_status = str.replace(/ /g, "");
		$wash_status_text = data.orchestra_wash_care_label_orders[0].status;
		var wash_enable = "";
		var wash_name = data.orchestra_wash_care_label_orders[0].template_name + " - ";
		update_disable=1;
	} else {
		$wash_status = "";
		$wash_status_text = "";
		var wash_enable = "display:none !important";
		var wash_name = "";
	}
//	if (data.orchestra_main_label_orders != "") {
//		var str = data.orchestra_main_label_orders[0].status;
//		$main_status = str.replace(/ /g, "");
//		$main_status_text = data.orchestra_main_label_orders[0].status;
//		var main_enable = "";
//		var main_name = data.orchestra_main_label_orders[0].template_name + " - ";
//		update_disable=1;
//	} else {
//		$main_status = "";
//		$main_status_text = "";
//		var main_enable = "display:none !important";
//		var main_name = "";
//	}

	if (localStorage.getItem("page_navigation") == "orders") {
		var onclick = "orders()";
	} else {
		var onclick = "orderStatus()";
	}
	if(update_disable == 0)
	{
		var update_disabel_button = "display:none";
	}
	else
	{
		var update_disabel_button = "";
	}
//alert(data.order_details[0].id)
	$('.main-content').html('<input type="hidden" value="' + data.order_details[0].UVC + '" id="uvc_id"><input type="hidden" value="' + data.order_details[0].id + '" id="parcel_id"><input type="hidden" value="' + data.order_details[0].Season_Code + '" id="season_code"><input type="hidden" value="'+data.customers[0].id+'" id="customer_id"><hr/><div class="col-md-3"><h4>Quantity And Artworks</h4></div><div class="form-group col-md-offset-9 has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Merchandiser</label><select data-live-search="true" class="form-control selectpicker input-sm merchandisers" required>' + merchandisersoption + '</select><div class="help-block with-errors"></div><label class="hidden merchandiser_error" style="color:red">Please Select Merchandiser</label></div><div class="col-md-4"><div class="card add-card hang-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"><span class="label label-' + $hang_status + ' label-status">' + $hang_status_text + '</span><div class="card-title"><p class="pull-right" id="clickmehang" style="cursor: pointer;">View &amp; Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p> <h5><b>Hangtag</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/ht_default.png" class="img-responsive center-block"></div></div><div class="col-md-6"><div class="text_artwork"></div></div></div></div></div><div class="col-md-4"><div class="card heat_transfer-card" style="min-height:279px"><span class="label label-' + $heat_transfer_status + ' label-status">' + $heat_transfer_status_text + '</span><div class="card-title"><p class="pull-right" id="clickmeheat_transfer" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b>Heat Transfer</b></h5></div><div class="row"><div class="col-md-10 col-md-offset-1"><div class="img_artwork"><img src="' +
		base_url + '/images/HT.jpg" style="width: 165px;" class="img-responsive center-block"></div></div></div></div></div><div class="col-md-4"><div class="card special-card" style="min-height:279px"><span class="label label-' + $special_status + ' label-status">' + $special_status_text + '</span><div class="card-title"><p class="pull-right" id="clickmespecial" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b>Specialtag</b></h5></div><div class="row"><div class="col-md-10 col-md-offset-1"><div class="img_artwork"><img src="' +
		base_url + '/images/st_default.png" class="img-responsive center-block"></div></div></div></div></div><div class="col-md-4"><div class="card main_label-card" style="min-height:279px"><span class="label label-' + $main_label_status + ' label-status">' + $main_label_status_text + '</span><div class="card-title"><p class="pull-right" id="clickmemain_label" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b>Main Label</b></h5></div><div class="row"><div class="col-md-10 col-md-offset-1"><div class="img_artwork"><img src="' +
		base_url + '/images/ML.png" class="img-responsive center-block"></div></div></div></div></div> <div class="col-md-4"><div class="card size_label-card" style="min-height:279px"><span class="label label-' + $size_label_status + ' label-status">' + $size_label_status_text + '</span><div class="card-title"><p class="pull-right" id="clickmesize_label" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b>Size Label</b></h5></div><div class="row"><div class="col-md-10 col-md-offset-1"><div class="img_artwork"><img src="' +
		base_url + '/images/SL.png" class="img-responsive center-block"></div></div></div></div></div><div id="add_modal"></div><div class="bottom-bar"><div class="modal-footer"><button onclick="' + onclick + '" class="btn btn-danger pull-right btn-xs"><i class="fa fa-times" aria-hidden="true"></i>Close</button><button data-toggle="modal" id="po-file-upload-button" style="'+update_disabel_button+'" class="btn btn-success pull-right btn-xs">Update PO</button> </div></div>');
	
	//Before add_modal
	
	//<div class="col-md-4"><div class="card add-card main-card" style="height: 280px;"><span class="label label-' + $main_status + ' label-status">' + $main_status_text + '</span><div class="card-title"><p class="pull-right" id="clickmemain" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b class="mainlabelname">' + main_name + 'Main & Size Label</b></h5> </div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/ml_default.png" class="img-responsive center-block"></div></div></div><p id="main_label_card" style="'+main_enable+';cursor:pointer"><i class="fa fa-plus" aria-hidden="true"></i> Add Main & Size Label</p></div></div></div>
	
	
	$('#po-file-upload-button').click(function () {
		$('#po-file-upload-group').modal("show")
		$('#new_po_div_status').addClass('hidden');
		$('#new_po1_status').addClass('hidden');
		$('#map_po_status').addClass('hidden');
		$('#update_new_po_status').addClass('hidden');
		$('#update_po_status').addClass('hidden');
		$('#new_pos_status').addClass('hidden');
		$('#map_po_div_status').addClass('hidden');
		$('#exist_artwork_div_status').removeClass('hidden')
		$('#new_po_no_status').val("")
        $('#new_po_file_status').val("")
		$('#new_po_error_status').addClass('hidden')
		$('#new_po_file_error_status').addClass('hidden')
		$('#new_po_error_length_status').addClass('hidden');
		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/get-existing-label-orders-po",
			type: "get",
			dataType: "json",
			data: {
				uvc: $('#uvc_id').val()
			},
			beforeSend: function () {

			},
			success: function (data) {



				$('#check_error_status').addClass('hidden')
				$('#new_po_error_status').addClass('hidden')
				$('#new_po_file_error_status').addClass('hidden')
				$('#new_po_error_length_status').addClass('hidden');
				$('#po-file-upload-group').find('#exist_artwork_div_status ul').html("");
				var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders)));
				var coun = 0;
				var options = "<option>Select PO</option>";
				var unique_po = []
				var old_po = []
				var new_po_div_status = 0;
				$(newObj).each(function (index1, value1) {
					if (value1.po_number != "" && value1.po_number != null) {
							
						if(old_po.includes(value1.po_number) != true)
						{
							old_po.push(value1.po_number)
							options += "<option data-id='"+value1.po_file+"'>" + value1.po_number + "</option>";
						}
						//old_po = value1.po_number;
						coun++;
						$('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="'+value1.type+'-'+value1.id+'" data-company="'+value1.company_id+'" class="check-list">'+value1.color_code+' '+value1.TemplateName+" - "+value1.TemplateType+'</li>')
                         new_po_div_status++;
					}
					else {
						$('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="'+value1.type+'-'+value1.id+'" data-company="'+value1.company_id+'" class="list-group-item">'+value1.color_code+' '+value1.TemplateName+" - "+value1.TemplateType+'</li>')
					}
					
				});
				
				
				
				//Start bootstrap checkbox 
				
				
				//list Item Hidden By Gowtham 
				$('.list-group.checked-list-box .list-group-item').each(function () {

					// Settings
					var $widget = $(this),
						$checkbox = $('<input type="checkbox" class="hidden" />'),
						color = ($widget.data('color') ? $widget.data('color') : "primary"),
						style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
						settings = {
							on: {
								icon: 'glyphicon glyphicon-check'
							},
							off: {
								icon: 'glyphicon glyphicon-unchecked'
							}
						};

					$widget.css('cursor', 'pointer')
					$widget.append($checkbox);

					// Event Handlers
					$widget.on('click', function () {
						$checkbox.prop('checked', !$checkbox.is(':checked'));
						$checkbox.triggerHandler('change');
						updateDisplay();
					});
					$checkbox.on('change', function () {
						updateDisplay();
					});


					// Actions
					function updateDisplay() {
						var isChecked = $checkbox.is(':checked');

						// Set the button's state
						$widget.data('state', (isChecked) ? "on" : "off");

						// Set the button's icon
						$widget.find('.state-icon')
							.removeClass()
							.addClass('state-icon ' + settings[$widget.data('state')].icon);

						// Update the button's color
						if (isChecked) {
							$widget.addClass(style + color + ' active');
						} else {
							$widget.removeClass(style + color + ' active');
						}
					}

					// Initialization
					function init() {

						if ($widget.data('checked') == true) {
							$checkbox.prop('checked', !$checkbox.is(':checked'));
						}

						updateDisplay();

						// Inject the icon if applicable
						if ($widget.find('.state-icon').length == 0) {
							$widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
						}
					}
					init();
				});

				$('#get-checked-data').on('click', function (event) {
					event.preventDefault();
					var checkedItems = {},
						counter = 0;
					$("#check-list-box li.active").each(function (idx, li) {
						checkedItems[counter] = $(li).text();
						counter++;
					});
					$('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
				});
				
				//company wise
				/* 
				list Item Hidden By Gowtham 
				$('.list-group-item').click(function(){
					$count_li=0;
					$('.list-group li').each(function(key,val){
						if($(this).hasClass('active'))
						{
							$count_li++;
						}
					});

					if($count_li > 0)
					{
						var company_id=$(this).data('company');
						$('.list-group li').each(function(key,val){
							if($(this).data('company') != company_id)
							{
								$(this).addClass('hidden');
							}
						})
					}
					else
					{
						$('.list-group li').each(function(key,val){
							$(this).removeClass('hidden');
						})
					}
					
				})*/
				
				//End bootstrap checkbox 
				
		   $('#po_select_div').html('<label>Select PO</label><select id="select_po_status" class="form-control" style="width:96%">' + options + '</select>')
                                        if (coun == 0) {
                                            $('#map_po_status').addClass('hidden');
                                            $('#new_po1_status').addClass('hidden');
                                            $('#new_po_div_status').removeClass('hidden');
                                            $('#new_pos_status').removeClass('hidden');
                                        } else {
                                            $('#map_po_status').removeClass('hidden');
                                            $('#new_po1_status').removeClass('hidden');
                                            $('#new_pos_status').addClass('hidden');
                                        }
                                        $('#new_po1_status').click(function () {
                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {

                                                $('#update_new_po_status').removeClass('hidden')
                                                $('#update_po_status').addClass('hidden')
                                                $('#new_pos_status').addClass('hidden')
                                                $('#new_po_div_status').removeClass('hidden')
                                                $('#map_po_div_status').addClass('hidden')
                                                $('#exist_artwork_div_status').removeClass('hidden')
                                                $('#check_error_status').addClass('hidden')

                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }

                                        });
                                        $('#map_po_status').click(function () {

                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {
                                                $('#update_po_status').removeClass('hidden')
                                                $('#update_new_po_status').addClass('hidden')
                                                $('#new_pos_status').addClass('hidden')
                                                $('#map_po_div_status').removeClass('hidden')
                                                $('#new_po_div_status').addClass('hidden')
                                                $('#exist_artwork_div_status').removeClass('hidden')
                                                $('#check_error_status').addClass('hidden')

                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }

                                        }); 
				
				
				$('#update_new_po_status').click(function () {

                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {
                                                $('#check_error_status').addClass('hidden')
                                                if ($('#new_po_no_status').val() != "" && $('#new_po_file_status').val() != "") {

                                                    if ($('#new_po_no_status').val().length > 9) {
                                                        $('#new_po_error_length_status').removeClass('hidden');
                                                        $('#new_po_error_status').addClass('hidden');
                                                        $('#new_po_file_error_status').addClass('hidden');
                                                    } else {
                                                        var a = [];
                                                        $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                            if (this.checked) {
                                                                a.push($(this).parent().attr('data-id'));
                                                            }
                                                        });
                                                        var aa = a.join(',');
                                                        var new_po_file = $('#new_po_file_status').prop('files')[0];
                                                        var new_po_no = $('#new_po_no_status').val();
                                                        var csrf_token = $('meta[name="csrf-token"]').attr('content');
                                                        var form_data = new FormData();
                                                        form_data.append('id', aa);
                                                        form_data.append('po_file', new_po_file);
                                                        form_data.append('po', new_po_no);
                                                        form_data.append('_token', csrf_token);
                                                        $.ajax({
                                                            url: base_url + "/admin/" + buyer + "/order-management/update-label-orders-po",
                                                            dataType: 'text',
                                                            cache: false,
                                                            contentType: false,
                                                            processData: false,
                                                            data: form_data,
                                                            type: 'post',
                                                            success: function (data) {
                                                                //tescoOrderStatus()
                                                                $('#po-file-upload-group').modal('hide')
                                                                $('.snackbar_span').html('PO Updated Successfully');
                                                                showSnackbar();
                                                            },
                                                            error: function () {
                                                                console.log('somethig is wrong, please try later');
                                                            }

                                                        });
                                                    }

                                                } else {
                                                    if ($('#new_po_no_status').val() == "" && $('#new_po_file_status').val() == "") {
                                                        $('#new_po_error_status').removeClass('hidden')
                                                        $('#new_po_file_error_status').removeClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    } else if ($('#new_po_file_status').val() == "") {
                                                        $('#new_po_file_error_status').removeClass('hidden')
                                                        $('#new_po_error_status').addClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    } else if ($('#new_po_no_status').val() == "") {
                                                        $('#new_po_error_status').removeClass('hidden')
                                                        $('#new_po_file_error_status').addClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    }

                                                }
                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }
                                        });

				
                                        $('#new_pos_status').click(function () {
                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {
                                                $('#check_error_status').addClass('hidden')
                                                if ($('#new_po_no_status').val() != "" && $('#new_po_file_status').val() != "") {
                                                    if ($('#new_po_no_status').val().length > 9) {
                                                        $('#new_po_error_length_status').removeClass('hidden');

                                                        $('#new_po_error_status').addClass('hidden');
                                                        $('#new_po_file_error_status').addClass('hidden');
                                                    } else {
                                                        var a = [];
                                                        $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                            if (this.checked) {
                                                                a.push($(this).parent().attr('data-id'));
                                                            }
                                                        });
                                                        var aa = a.join(',');
                                                        var new_po_file = $('#new_po_file_status').prop('files')[0];
                                                        var new_po_no = $('#new_po_no_status').val();
                                                        var csrf_token = $('meta[name="csrf-token"]').attr('content');
                                                        var form_data = new FormData();
                                                        form_data.append('id', aa);
                                                        form_data.append('po_file', new_po_file);
                                                        form_data.append('po', new_po_no);
                                                        form_data.append('_token', csrf_token);
                                                        $.ajax({
                                                           url: base_url + "/admin/" + buyer + "/order-management/update-label-orders-po",
                                                            dataType: 'text',
                                                            cache: false,
                                                            contentType: false,
                                                            processData: false,
                                                            data: form_data,
                                                            type: 'post',
                                                            success: function (data) {
                                                                //tescoOrderStatus()
                                                                $('#po-file-upload-group').modal('hide')
                                                                $('.snackbar_span').html('PO Updated Successfully');
                                                                showSnackbar();
                                                            },
                                                            error: function () {
                                                                console.log('somethig is wrong, please try later');
                                                            }

                                                        });
                                                    }

                                                } else {
                                                    if ($('#new_po_no_status').val() == "" && $('#new_po_file_status').val() == "") {
                                                        $('#new_po_error_status').removeClass('hidden')
                                                        $('#new_po_file_error_status').removeClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    } else if ($('#new_po_file_status').val() == "") {
                                                        $('#new_po_file_error_status').removeClass('hidden')
                                                        $('#new_po_error_status').addClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    } else if ($('#new_po_no_status').val() == "") {
                                                        $('#new_po_error_status').removeClass('hidden')
                                                        $('#new_po_file_error_status').addClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    }
                                                }

                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }
                                        });
				
                                        $('#update_po_status').click(function () {

                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {
                                                $('#check_error_status').addClass('hidden')

                                                if ($('#select_po_status').val() != "Select PO") {
                                                    var a = [];
                                                    $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                        if (this.checked) {
                                                            a.push($(this).parent().attr('data-id'));
                                                        }
                                                    });
                                                    var aa = a.join(',');
                                                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
													//alert($('#select_po_status option:selected').attr('data-id'))
                                                    $.ajax({
                                                        url: base_url + "/admin/" + buyer + "/order-management/map-label-orders-po",
                                                        type: "Post",
                                                        dataType: "json",
                                                        data: {
                                                            _token: csrf_token,
                                                            id: aa,
                                                            po: $('#select_po_status').val(),
															po_file:$('#select_po_status option:selected').attr('data-id')
                                                        },
                                                        beforeSend: function () {

                                                        },
                                                        success: function (data) {
                                                            //tescoOrderStatus()
                                                            $('#po-file-upload-group').modal('hide')
                                                            $('.snackbar_span').html('PO Updated Successfully');
                                                            showSnackbar();$('#po-file-upload-div').modal('hide')
								$('.snackbar_span').html('PO Updated Successfully');
								showSnackbar();
                                                        },
                                                        error: function (data) {

                                                        },
                                                        complete: function () {
                                                            $(".loader").hide();
                                                        }
                                                    })
                                                } else {
                                                    $('#select_error_status').removeClass('hidden')
                                                }
                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }

                                        });
			},
			error: function (data) {

			},
			complete: function () {
				$(".loader").hide();
			}
		})
	});

    $.fn.capitalize = function () {
        $.each(this, function () {
            this.value = this.value.replace(/\b[a-z]/gi, function ($0) {
                return $0.toUpperCase();
            });
            this.value = this.value.replace(/@([a-z])([^.]*\.[a-z])/gi, function ($0, $1) {
                console.info(arguments);
                return '@' + $0.toUpperCase() + $1.toLowerCase();
            });

        });
    }

    $("input").keyup(function () {
        $(this).capitalize();
    }).capitalize();

    $('#washcare_label_card').on('click', function (e) {

        if ($('.washcarename').val() != "") {
          
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"> <span></span>   <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
				
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label added successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare);
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
//        $('.extra_percentage').keyup(function () {
//            if ($(this).val() != "") {
//                var per = $(this).val();
//                $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
//                    var sum = $(this).find('.sum').text();
//                    var per_qty = (parseInt(sum) / 100) * parseInt(per);
//                    var tot_qty = parseInt(sum) + parseInt(per_qty);
//                    $(this).find('.tot_qty_table').val(tot_qty);
//                })
//            } else {
//                $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
//                    $(this).find('.tot_qty_table').val("");
//                })
//            }
//
//        })
		
    });
    
    /**
        Washcare Label Cloning
    */
    $('#clone_washcare_label_card').on('click', function (e) {

        if ($('.washcarename').val() != "") {
          
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false">  <span></span>  <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();
                });
          
				
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label cloned successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
				
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare);
            $('#washcarelabelform'+washcare).find('#wcl_name'+washcare).val($('#washcarelabelform').find('#wcl_name').val())
           
            
            $('.washing_select'+washcare).selectpicker('val',$('.washing_select').selectpicker('val'));
            $('.whitening_select'+washcare).selectpicker('val',$('.whitening_select').selectpicker('val'));
            $('.dryer_select'+washcare).selectpicker('val',$('.dryer_select').selectpicker('val'));
            $('.ironing_select'+washcare).selectpicker('val',$('.ironing_select').selectpicker('val'));
            $('.cleaning_select'+washcare).selectpicker('val',$('.cleaning_select').selectpicker('val'));
            $('.made_in_select'+washcare).selectpicker('val',$('.made_in_select').selectpicker('val'));
           
			//$('#washcarelabelform' + washcare+' .washonesizein').val($('#washcarelabelform .washonesizein').val())
			$('#washcarelabelform' + washcare+' .washonesizecm').val($('#washcarelabelform .washonesizecm').val())
			$('.care_instruction_select'+washcare).selectpicker('val',$('.care_instruction_select').selectpicker('val'));
            
            $('#composition_remove_qty'+washcare).html($('#composition_remove_qty').html())
            
            $('#composition_remove_qty tr').each(function (ind,val) {
                var values= $(this).find('select').find(':selected').val()
                var v= $(this).attr('id');
                $('#composition_remove_qty'+washcare+' tr').each(function (inds,vals) {
                    if($(this).attr('id') == v)
                    {
                        $(this).find('select').val(values)
                        $(this).find('a').removeAttr('onclick')
                        $(this).find('a').attr('onclick','deleteComposition(this,'+washcare+')')
                    }
                });
            }); 
            
            $('#washcarelabelform table tbody tr').each(function (ind,val) {
                var values= $(this).find('.tot_qty_tablewash').val()
                var v= $(this).find('.uvc_tco').text();
                $('#washcarelabelform'+washcare+' table tbody tr').each(function (inds,vals) {
                    if($(this).find('.uvc_tco').text() == v)
                    {
                        $(this).find('.tot_qty_tablewash').val()
                        
                    }
                });
            });
            
            $('#washcarelabelform'+washcare+' table tbody').find('.tot_qty_tablewash_fin'+washcare).text($('#washcarelabelform table tbody').find('.tot_qty_tablewash_fin').text())
            var mySVG = document.getElementById("washcare_svg_front"+washcare);
            var svgDoc;
            mySVG.addEventListener("load", function () {
                svgDoc=mySVG.contentDocument;
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('astm'));
                
                var text_for_mad_in = '<p>' + $('select.made_in_select' + washcare).find(':selected').data('french') + "-" + $('select.made_in_select' + washcare).find(':selected').data('english') + "-" + $('select.made_in_select' + washcare).find(':selected').data('spanish') + "-" + $('select.made_in_select' + washcare).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + washcare).find(':selected').data('italian') + "-" + $('select.made_in_select' + washcare).find(':selected').data('dutch') + "-" + $('select.made_in_select' + washcare).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('greek')  + '</p>';
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;
                
                svgDoc.getElementById("text_care_instruction").innerHTML = "";
                $('.care_instruction_select' + washcare + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek')  + '</p>';
                    svgDoc.getElementById("text_care_instruction").innerHTML += value;
                });
                
                 svgDoc.getElementById("parts_composition").innerHTML ="";
              $('#composition_remove_qty' + washcare + ' tr').each(function () {
                  if($(this).find('select').find(':selected').val() != "Select Option")
                  {
                       svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german') + "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek') + '</p>';
                  }
                 
                  var id=$(this).attr('id')
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                  $(compss).each(function (index1,value1) {
                      var english = value1.split('_');

                    svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
                });
              })
            $('.composition_error_hidden').addClass('hidden')
            var washcomp=[];
            $('#composition_remove_qty' + washcare + ' tr').each(function () {
                  var washcomp1=[];
                  var id=$(this).attr('id')
                  var value=$(this).find('select').find(':selected').val();
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                    $(compss).each(function (index1,value1){ 
                        var english = value1.split('_');
                        washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                    });
                washcomp.push(washcomp1)
            })
            var wash_finals = JSON.stringify(washcomp);
            localStorage.setItem('wash_composition_final'+washcare, wash_finals);
                
            });
            
            
            
            
            
            //$('#washcarelabelform'+washcare).find('.washing_select'+washcare).selectpicker('val','1')
            
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
       
    });


}

/**
 * This Function is used to invoke select picker.
 * 
 * @return select
 */

function selectpicker() {
	$('.selectpicker').selectpicker({
		style: 'btn-default',
		size: 4
	});

}

/**
 * This Function is used to invoke Jstree.
 * 
 * @return tree
 */

function treestructurespecial(data, tree, template_id,orchestra_special_tag_orders) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.special_qty').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}
					if (orchestra_special_tag_orders != "") {
						 $('#'+tree+' >ul > li').each( function() {
						   disable( tree,this.id );        
						})
						$('.special_qty').attr('disabled','disabled')
					}

				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.special_qty').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
/**
 * This Function is used to invoke Jstree For Heat Transfer.
 * 
 * @return tree
 */
function treestructureheat_transfer(data, tree, template_id,orchestra_heat_transfer_orders_data) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tableheat').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:300px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}
					
					if (orchestra_heat_transfer_orders_data != "") {
						 $('#'+tree+' >ul > li').each( function() {
						   disable( tree,this.id );        
						})
						$('.extra_percentageheat').attr('disabled','disabled')
						$('.tot_qty_tableheat').attr('disabled','disabled')
					}

				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tableheat').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:300px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
/**
 * This Function is used to invoke Jstree For Main Labels.
 * 
 * @return tree
 */
function treestructuremain_label(data, tree, template_id,orchestra_main_label_orders) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.main_label_qty').attr('min',data.moq)
					$('#main_labelform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:150px;object-fit:cover">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}
					
					if (orchestra_main_label_orders != "") {
						 $('#'+tree+' >ul > li').each( function() {
						   disable( tree,this.id );        
						})
						$('.main_label_qty').attr('disabled','disabled')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.main_label_qty').attr('min',data.moq)
					$('#main_labelform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
/**
 * This Function is used to invoke Jstree For Size Labels.
 * 
 * @return tree
 */
function treestructuresize_label(data, tree, template_id,orchestra_size_label_orders) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tablesize').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}
					
					if (orchestra_size_label_orders != "") {
						 $('#'+tree+' >ul > li').each( function() {
						   disable( tree,this.id );        
						})
						$('.extra_percentagesize').attr('disabled','disabled')
						$('.tot_qty_tablesize').attr('disabled','disabled')
					}

				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tablesize').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
/**
 * This Function is used to invoke Jstree.
 * 
 * @return tree
 */

function treestructuremain(data, tree, template_id) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<object id="svg2" style="max-height: 350px;" class="center-block svg2" data="' + data.treeviewData[0].template_file + '" type="image/svg+xml"></object>')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<object id="svg2" style="max-height: 350px;" class="center-block svg2" data="' + data.treeviewData[0].template_file + '" type="image/svg+xml"></object>')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
/**
 * This Function is used to invoke Jstree.
 * 
 * @return tree
 */

function treestructurewash(data,dataid, tree, template_id,datas) {
	
	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					if (data.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<object style="height: calc( 100vh - 125px );" id="washcare_svg_front'+dataid+'" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
						
							var mySVG = document.getElementById("washcare_svg_front"+dataid);
							var svgDoc;
							mySVG.addEventListener("load", function () {
								svgDoc = mySVG.contentDocument;
								
								
								var size = datas.order_details[0].Size.split(" ")

								if (size[1] == "ANS") {
									var size1 = "years";
								} else {
									var size1 = "months";
								}
								
								svgDoc.getElementById("parcel_code1").innerHTML = datas.order_details[0].UVC_TCO;
								svgDoc.getElementById("product_family").innerHTML = datas.product_family.length > 0 ? datas.product_family[0].arabic : "";
								svgDoc.getElementById("parcel_code2").innerHTML = datas.order_details[0].UVC_TCO;
								
								
		if(datas.order_details[0].Size == "TAILLE UNIQUE")
		{
			//var inches=$('#washcarelabelform' + dataid+' .washonesizein').val()
			var cm=$('#washcarelabelform' + dataid+' .washonesizecm').val()
			
			svgDoc.getElementById("size_in_fr_en1").innerHTML = cm+"cm";
        svgDoc.getElementById("size_in_fr_en2").innerHTML = cm+"cm";
			svgDoc.getElementById("size1").innerHTML ="TU";
			svgDoc.getElementById("size2").innerHTML ="TU";
		}
		else if(datas.order_details[0].Size == "TAILLE L")
		{
			svgDoc.getElementById("size1").innerHTML = "TAILLE L";
			svgDoc.getElementById("size2").innerHTML = "TAILLE L";
			svgDoc.getElementById("size_in_fr_en1").innerHTML = "";
			svgDoc.getElementById("size_in_fr_en2").innerHTML = "";
		}
		else
		{
			svgDoc.getElementById("size_in_fr_en1").innerHTML = datas.order_details[0].cm + "cm / " + datas.order_details[0].in + "in";
			svgDoc.getElementById("size_in_fr_en2").innerHTML = datas.order_details[0].cm + "cm / " + datas.order_details[0].in + "in";
			svgDoc.getElementById("size1").innerHTML = datas.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
			svgDoc.getElementById("size2").innerHTML = datas.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
		}
								

								var code = datas.order_details[0].BarcodeForPricesTags.toString();
								jsbarcodewash(code, 'barcode', svgDoc)
								
								//svgDoc.getElementById("size1").innerHTML = "text_for_mad_in";
								if ($('.washing_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.whitening_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.dryer_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.ironing_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.cleaning_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.made_in_select' + dataid + ' option:selected').val() != "") {
									 var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" + $('select.made_in_select' + dataid).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + dataid).find(':selected').data('italian') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german')  + "-" +
										$('select.made_in_select' + dataid).find(':selected').data('arabic') + "-" +
										$('select.made_in_select' + dataid).find(':selected').data('greek') + '</p>';
									svgDoc.getElementById("country").innerHTML = text_for_mad_in;
								}

									svgDoc.getElementById("text_care_instruction").innerHTML = "";
									$('.care_instruction_select' + dataid + ' option:selected').each(function () {
										var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german') + "-" +
											$(this).data('arabic') + "-" +
											$(this).data('greek')  + '</p>';
										svgDoc.getElementById("text_care_instruction").innerHTML += value;
									});

									 svgDoc.getElementById("parts_composition").innerHTML ="";
								  $('#composition_remove_qty' + dataid + ' tr').each(function () {
									  if($(this).find('select').find(':selected').val() != "Select Option")
									  {
										   svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german')+ "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek') + '</p>';
									  }

									  var id=$(this).attr('id')
									  var compo =$(this).find('.compositions_hidden').val();
									  var compss = compo.split(',');
									  $(compss).each(function (index1,value1) {
										  var english = value1.split('_');

										svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
									});
								  })
								  
        $('.washing_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.washing_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', "");
            }


        });
        $('.whitening_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.whitening_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', "");
            }
        });
        $('.dryer_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.dryer_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', "");
            }

        });
        $('.ironing_select' + dataid + '.bootstrap-select').change(function () {
            if ($('.ironing_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', "");
            }

        });
        $('.cleaning_select' + dataid + '.bootstrap-select').change(function () {
            if ($('.cleaning_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', "");
            }

        });
        $('.made_in_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.made_in_select' + dataid + ' option:selected').val() != "") {

                var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" + $('select.made_in_select' + dataid).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + dataid).find(':selected').data('italian') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german')  + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('greek')  + '</p>';
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;

            } else {

            }


        });

        $('.care_instruction_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.care_instruction_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("text_care_instruction").innerHTML = "";
                $('.care_instruction_select' + dataid + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german') + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek')  + '</p>';
                    svgDoc.getElementById("text_care_instruction").innerHTML += value;
                });


            } else {

            }


        });
							});
						
						
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: base_url + "/admin/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					
					if (data.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<object style="height: calc( 100vh - 125px );"  id="washcare_svg_front'+dataid+'"  class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
						
						
						var mySVG = document.getElementById("washcare_svg_front"+dataid);
							var svgDoc;
							mySVG.addEventListener("load", function () {
								svgDoc = mySVG.contentDocument;
								
								
								var size = datas.order_details[0].Size.split(" ")

								if (size[1] == "ANS") {
									var size1 = "years";
								} else {
									var size1 = "months";
								}
								
								svgDoc.getElementById("parcel_code1").innerHTML = datas.order_details[0].UVC_TCO;
								svgDoc.getElementById("product_family").innerHTML = datas.product_family.length > 0 ? datas.product_family[0].arabic : "";
								svgDoc.getElementById("parcel_code2").innerHTML = datas.order_details[0].UVC_TCO;
								if(datas.order_details[0].Size == "TAILLE UNIQUE")
								{
									//var inches=$('#washcarelabelform' + dataid+' .washonesizein').val()
									var cm=$('#washcarelabelform' + dataid+' .washonesizecm').val()

									svgDoc.getElementById("size_in_fr_en1").innerHTML = cm+"cm";
								svgDoc.getElementById("size_in_fr_en2").innerHTML = cm+"cm";
									svgDoc.getElementById("size1").innerHTML ="TU";
									svgDoc.getElementById("size2").innerHTML ="TU";
								}
								else if(datas.order_details[0].Size == "TAILLE L")
								{
									svgDoc.getElementById("size1").innerHTML = "TAILLE L";
									svgDoc.getElementById("size2").innerHTML = "TAILLE L";
									svgDoc.getElementById("size_in_fr_en1").innerHTML = "";
									svgDoc.getElementById("size_in_fr_en2").innerHTML = "";
								}
								else
								{
									svgDoc.getElementById("size_in_fr_en1").innerHTML = datas.order_details[0].cm + "cm";
									svgDoc.getElementById("size_in_fr_en2").innerHTML = datas.order_details[0].cm + "cm";
									svgDoc.getElementById("size1").innerHTML = datas.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
									svgDoc.getElementById("size2").innerHTML = datas.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
								}


								var code = datas.order_details[0].BarcodeForPricesTags.toString();
								jsbarcodewash(code, 'barcode', svgDoc)
								
								//svgDoc.getElementById("size1").innerHTML = "text_for_mad_in";
								if ($('.washing_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.whitening_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.dryer_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.ironing_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.cleaning_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.made_in_select' + dataid + ' option:selected').val() != "") {
									 var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" + $('select.made_in_select' + dataid).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + dataid).find(':selected').data('italian') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german') + "-" +
										$('select.made_in_select' + dataid).find(':selected').data('arabic') + "-" +
										$('select.made_in_select' + dataid).find(':selected').data('greek')  + '</p>';
									svgDoc.getElementById("country").innerHTML = text_for_mad_in;
								}

									svgDoc.getElementById("text_care_instruction").innerHTML = "";
									$('.care_instruction_select' + dataid + ' option:selected').each(function () {
										var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
											$(this).data('arabic') + "-" +
											$(this).data('greek') + '</p>';
										svgDoc.getElementById("text_care_instruction").innerHTML += value;
									});

									 svgDoc.getElementById("parts_composition").innerHTML ="";
								  $('#composition_remove_qty' + dataid + ' tr').each(function () {
									  if($(this).find('select').find(':selected').val() != "Select Option")
									  {
										   svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german')  + "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek')  + '</p>';
									  }

									  var id=$(this).attr('id')
									  var compo =$(this).find('.compositions_hidden').val();
									  var compss = compo.split(',');
									  $(compss).each(function (index1,value1) {
										  var english = value1.split('_');

										svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
									});
								  })
								  
        $('.washing_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.washing_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', "");
            }


        });
        $('.whitening_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.whitening_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', "");
            }
        });
        $('.dryer_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.dryer_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', "");
            }

        });
        $('.ironing_select' + dataid + '.bootstrap-select').change(function () {
            if ($('.ironing_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', "");
            }

        });
        $('.cleaning_select' + dataid + '.bootstrap-select').change(function () {
            if ($('.cleaning_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', "");
            }

        });
        $('.made_in_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.made_in_select' + dataid + ' option:selected').val() != "") {

                var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" + $('select.made_in_select' + dataid).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + dataid).find(':selected').data('italian') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german')  + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('greek')  + '</p>';
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;

            } else {

            }


        });

        $('.care_instruction_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.care_instruction_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("text_care_instruction").innerHTML = "";
                $('.care_instruction_select' + dataid + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek')  + '</p>';
                    svgDoc.getElementById("text_care_instruction").innerHTML += value;
                });


            } else {

            }


        });
							});
						
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}

/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcode(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 5.99,
		height: 343,
		fontSize: 60
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
	svg.setAttribute('font-family', 'calibrib');

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
/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodesolidcartonA(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.4,
		height: 347,
		fontSize: 50
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
	svg.setAttribute('font-size', '55.75px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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

/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodestdcartonA(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.4,
		height: 355,
		fontSize: 50
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
	svg.setAttribute('font-size', '59.80px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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
/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodesolidcartonB(code, id, svgDoc) {	
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.5,
		height: 345,
		fontSize: 50
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
	svg.setAttribute('font-size', '55.75px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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

/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodestdcartonB(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.43,
		height: 349,
		fontSize: 50
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
	svg.setAttribute('font-size', '55px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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
/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodestdpoly(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width:6.595,
		height: 350,
		fontSize: 60
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
	svg.setAttribute('font-size', '64px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '12px');

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
/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodesolidpoly(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.7,
		height: 370,
		fontSize: 50
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
	svg.setAttribute('font-size', '55.50px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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

/**
 * This Function is used to invoke JSBarcode for Wash Care Labels.
 * 
 * @return barcode
 */

function jsbarcodewash(code, id, svgDoc) {
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

/**
 * This Function is used to invoke Hang Tag JSBarcode.
 * 
 * @return barcode
 */

function jsbarcode_hang_disney(code, id, svgDoc) {
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

	svgDoc.getElementById(id).innerHTML = svg_xml;
}
/**
 * This Function is used to invoke Hang Tag JSBarcode.
 * 
 * @return barcode
 */

function jsbarcode_hang(code, id, svgDoc) {
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

	svgDoc.getElementById(id).innerHTML = svg_xml;
}

/**
 * This Function is used to set hang tag in local storage.
 * 
 * @return void
 */

function setHangTagStatus(colour) {
    if ($('#hangtagform').find('#exist_id').val() != "") {
        var exist_id = $('#hangtagform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }

    var template_id = $('#hangtagform').find('#tree1').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("hang_tag", exist_id);
        setHangTag(localStorage.getItem("hang_tag"),colour)
        $('#hang_template_error').addClass('hidden')
        $("#hangtagform .left-panelorc").removeClass('active');
        $("#hangtagform .right-panel").removeClass('active');
        $("#hangtagform .backdrop").removeClass('active');
        $('#hangtagform .scalable').removeClass('active');
    } else {
        $('#hang_template_error').removeClass('hidden')
    }
}

/**
 * This Function is used to set special tag in local storage.
 * 
 * @return void
 */

function setSpecialTagStatus(colour) {

    if ($('#specialtagform').find('#exist_id').val() != "") {
        var exist_id = $('#specialtagform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#specialtagform').find('#tree2').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("special_tag", exist_id);
        setSpecialTag(localStorage.getItem("special_tag"),colour)
        $('#special_template_error').addClass('hidden')
        $("#specialtagform .left-panelorc").removeClass('active');
        $("#specialtagform .right-panel").removeClass('active');
        $("#specialtagform .backdrop").removeClass('active');
        $('#specialtagform .scalable').removeClass('active');
    } else {
        $('#special_template_error').removeClass('hidden')
    }
}

/**
 * This Function is used to set Heat Transfer in local storage.
 * 
 * @return void
 */

function setheat_transferStatus(colour) {

    if ($('#heat_transferform').find('#exist_id').val() != "") {
        var exist_id = $('#heat_transferform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#heat_transferform').find('#treeht').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("heat_transfer", exist_id);
        setHeatTransfer(localStorage.getItem("heat_transfer"),colour)
        $('#heat_transfer_template_error').addClass('hidden')
        $("#heat_transferform .left-panelorc").removeClass('active');
        $("#heat_transferform .right-panel").removeClass('active');
        $("#heat_transferform .backdrop").removeClass('active');
        $('#heat_transferform .scalable').removeClass('active');
    } else {
        $('#heat_transfer_template_error').removeClass('hidden')
    }
}
/**
 * This Function is used to set Main Label in local storage.
 * 
 * @return void
 */

function setMainLabelStatus(colour) {

    if ($('#main_labelform').find('#exist_id').val() != "") {
        var exist_id = $('#main_labelform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#main_labelform').find('#treeml').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("main_label", exist_id);
        setMainLabel(localStorage.getItem("main_label"),colour)
        $('#main_label_template_error').addClass('hidden')
        $("#main_labelform .left-panelorc").removeClass('active');
        $("#main_labelform .right-panel").removeClass('active');
        $("#main_labelform .backdrop").removeClass('active');
        $('#main_labelform .scalable').removeClass('active');
    } else {
        $('#main_label_template_error').removeClass('hidden')
    }
}
/**
 * This Function is used to set Size Labels in local storage.
 * 
 * @return void
 */

function setSizeLabelStatus(colour) {

    if ($('#size_labelform').find('#exist_id').val() != "") {
        var exist_id = $('#size_labelform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#size_labelform').find('#treesl').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("size_label", exist_id);
        setSizeLabel(localStorage.getItem("size_label"),colour)
        $('#size_label_template_error').addClass('hidden')
        $("#size_labelform .left-panelorc").removeClass('active');
        $("#size_labelform .right-panel").removeClass('active');
        $("#size_labelform .backdrop").removeClass('active');
        $('#size_labelform .scalable').removeClass('active');
    } else {
        $('#size_label_template_error').removeClass('hidden')
    }
}

/**
 * This Function is used to set wash care labels in local storage.
 * 
 * @return void
 */

function setWashCareLabelStatus(dataid) {

    if ($('#washcarelabelform' + dataid).find('#exist_id' + dataid).val() != "") {
       var value = $('#washcarelabelform' + dataid).find('#exist_id' + dataid).val();
    } else {
        var value = "null";
    }

    var wash_lab = JSON.stringify(wash_label);
    var i = 0
    $('#composition_remove_qty' + dataid + ' tr').each(function (key, val) {
        i++;
    })
    if (i != 0) {
        if( localStorage.getItem("wash_composition_final"+ dataid) != null)
        {
            $('#wash_composition_error' + dataid).addClass('hidden')
            $('#wash_composition_page_error' + dataid).addClass('hidden')
            localStorage.setItem("wash_label", wash_lab);
            setWashLabel('washcarelabelform' + dataid, dataid, value)
            $("#washcarelabelform" + dataid + " .left-panelorc").removeClass('active');
            $("#washcarelabelform" + dataid + " .right-panel").removeClass('active');
            $("#washcarelabelform" + dataid + " .backdrop").removeClass('active');
            $('#washcarelabelform' + dataid + ' .scalable').removeClass('active');
        }
        else {
            $('#wash_composition_page_error' + dataid).removeClass('hidden')
        }
        
    } else {
        $('#wash_composition_error' + dataid).removeClass('hidden')
    }


}

/**
 * This Function is used to set hang tag in db.
 * 
 * @return void
 */

function setHangTag(id,colour) {
	
	var template_id = $('#hangtagform').find('#tree1').jstree('get_selected', true);
	template_front_id = template_id[0].id;
	var template_back_id = $('#hang_tag_id').val();
	var onesizecm = $('.onesizecm').val();
	//var onesizein = $('.onesizein').val();
	
	var tot_qty_tablehang = [];
	var uvc_tco = [];
	var size = [];
	var size_id = [];
	var main_id = [];
	$('#hangtagform tbody tr').each(function () {
		if ($(this).find('.tot_qty_tablehang').val() != undefined) {
			tot_qty_tablehang.push($(this).find('.tot_qty_tablehang').val())
			uvc_tco.push($(this).find('.uvc_tco').text())
			size.push($(this).find('.size').text())
			size_id.push($(this).find('.size_id').val())
			main_id.push($(this).find('.main_id').val())
		}

	})
	tot_qty_tablehang_fin = tot_qty_tablehang.join(',');
	uvc_tco_fin = uvc_tco.join(',');
	size_fin = size.join(',');
	size_id_fin = size_id.join(',');
	main_id_fin = main_id.join(',');
	var buyer = localStorage.getItem("buyer");
//	alert()
//	alert($('#parcel_id').val());
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/" + buyer + "/order-management/set_hang_tag",
		type: "Post",
		data: {
			
			template_front_id: template_front_id,
			template_back_id: template_back_id,
			tot_qty_table: tot_qty_tablehang_fin,
			id: id,
			cm:onesizecm,
			//in:onesizein,
			colour: colour,
			size_id: size_id_fin,
			main_id: main_id_fin,
			uvc_tco: uvc_tco_fin,
			uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
			size: size_fin,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			$('#print_hang_button').removeClass('hidden')
			$('#save_hang_button').addClass('hidden')
			$('#tree1 >ul > li').each( function() {
			   disable( 'tree1',this.id );        
			})
			$('.extra_percentagehang').attr('disabled','disabled')
			$('.tot_qty_tablehang').attr('disabled','disabled')
		},
		error: function (data) {

		}
	})
		$('.hang-card span').text('Order Approved')
		$('.hang-card span').addClass('label label-OrderApproved label-status')
		$('#po-file-upload-button').css('display','block')
}

/**
 * This Function is used to set special tag in db.
 * 
 * @return void
 */


function setSpecialTag(id,colour) {
	var myElements = $(".special_qty");
    var special_qty = [];
    for (var i=0;i<myElements.length;i++) {
        special_qty.push(myElements.eq(i).val());
    }
    
    var template_id = $('#specialtagform').find('#tree2').jstree('get_selected', true);
    template_front_id = template_id[0].id;
    var tot_qty_table = [];
    var uvc_tco = [];
    var size = [];
    $('#specialtagform tbody tr').each(function () {
        if ($(this).find('.tot_qty_table').val() != undefined) {
            tot_qty_table.push($(this).find('.tot_qty_table').val())
            uvc_tco.push($(this).find('.uvc_tco').text())
            size.push($(this).find('.size').text())
        }

    })
    tot_qty_table_fin = tot_qty_table.join(',');
    uvc_tco_fin = uvc_tco.join(',');
    size_fin = size.join(',');
    var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/order-management/set_special_tag",
        type: "Post",
        data: {
            
            template_front_id: template_front_id,
            id: id,
            tot_qty_table: special_qty,
            uvc_tco: uvc_tco_fin,
			colour: colour,
            uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
            size: size_fin,
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
			$('#save_special_button').addClass('hidden')
			$('#tree2 >ul > li').each( function() {
			   disable( 'tree2',this.id );        
			})
			$('.special_qty').attr('disabled','disabled')
        },
        error: function (data) {

        }
    })
	$('.special-card span').text('Order Approved')
	$('.special-card span').addClass('label label-OrderApproved label-status')
	$('#po-file-upload-button').css('display','block')
}
/**
 * This Function is used to set HeatTransfer in db.
 * 
 * @return void
 */


function setHeatTransfer(id,colour) {
  
    var template_id = $('#heat_transferform').find('#treeht').jstree('get_selected', true);
	console.log(template_id)
	template_id = template_id[0].id;
	
	var tot_qty_tableheat = [];
	var uvc_tco = [];
	var size = [];
	var size_id = [];
	var main_id = [];
	$('#heat_transferform tbody tr').each(function () {
		if ($(this).find('.tot_qty_tableheat').val() != undefined) {
			tot_qty_tableheat.push($(this).find('.tot_qty_tableheat').val())
			uvc_tco.push($(this).find('.uvc_tco').text())
			size.push($(this).find('.size').text())
			size_id.push($(this).find('.size_id').val())
			main_id.push($(this).find('.main_id').val())
		}

	})
	tot_qty_tableheat_fin = tot_qty_tableheat.join(',');
	uvc_tco_fin = uvc_tco.join(',');
	size_fin = size.join(',');
	size_id_fin = size_id.join(',');
	main_id_fin = main_id.join(',');
	var buyer = localStorage.getItem("buyer");
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/" + buyer + "/order-management/set_heat_transfer",
		type: "Post",
		data: {
			
			template_id: template_id,
			tot_qty_table: tot_qty_tableheat_fin,
			id: id,
			colour: colour,
			size_id: size_id_fin,
			main_id: main_id_fin,
			uvc_tco: uvc_tco_fin,
			uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
			size: size_fin,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			$('#save_heat_transfer_button').addClass('hidden')
			$('#treeht >ul > li').each( function() {
			   disable( 'treeht',this.id );        
			})
			$('.extra_percentageheat').attr('disabled','disabled')
			$('.tot_qty_tableheat').attr('disabled','disabled')
		},
		error: function (data) {

		}
	})
		$('.heat_transfer-card span').text('Order Approved')
		$('.heat_transfer-card span').addClass('label label-OrderApproved label-status')
		$('#po-file-upload-button').css('display','block')
}
/**
 * This Function is used to set Main Label in db.
 * 
 * @return void
 */


function setMainLabel(id,colour) {
  
	var myElements = $(".main_label_qty");
    var main_label_qty = [];
    for (var i=0;i<myElements.length;i++) {
        main_label_qty.push(myElements.eq(i).val());
    }
	
    
    var template_id = $('#main_labelform').find('#treeml').jstree('get_selected', true);
    template_id = template_id[0].id;
    var tot_qty_table = [];
    var uvc_tco = [];
    var size = [];
    $('#main_labelform tbody tr').each(function () {
        if ($(this).find('.tot_qty_table').val() != undefined) {
            tot_qty_table.push($(this).find('.tot_qty_table').val())
            uvc_tco.push($(this).find('.uvc_tco').text())
            size.push($(this).find('.size').text())
        }

    })
    tot_qty_table_fin = tot_qty_table.join(',');
    uvc_tco_fin = uvc_tco.join(',');
    size_fin = size.join(',');
    var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/order-management/set_main_label",
        type: "Post",
        data: {
            
            template_id: template_id,
            id: id,
            tot_qty_table: main_label_qty,
            uvc_tco: uvc_tco_fin,
            colour: colour,
            uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
            size: size_fin,
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
			$('#save_main_label_button').addClass('hidden')
			$('.snackbar_span').html('This is only for to place main label. Size label you want to place seperately');
			showSnackbar();
			$('#treeml >ul > li').each( function() {
			   disable( 'treeml',this.id );        
			})
			$('.main_label_qty').attr('disabled','disabled')
        },
        error: function (data) {

        }
    })
	$('.main_label-card span').text('Order Approved')
	$('.main_label-card span').addClass('label label-OrderApproved label-status')
	$('#po-file-upload-button').css('display','block')
}
/**
 * This Function is used to set Size Label in db.
 * 
 * @return void
 */


function setSizeLabel(id,colour) {
  
    var template_id = $('#size_labelform').find('#treesl').jstree('get_selected', true);
	console.log(template_id)
	template_id = template_id[0].id;
	
	var tot_qty_tablesize = [];
	var uvc_tco = [];
	var size = [];
	var size_id = [];
	var main_id = [];
	$('#size_labelform tbody tr').each(function () {
		if ($(this).find('.tot_qty_tablesize').val() != undefined) {
			tot_qty_tablesize.push($(this).find('.tot_qty_tablesize').val())
			uvc_tco.push($(this).find('.uvc_tco').text())
			size.push($(this).find('.size').text())
			size_id.push($(this).find('.size_id').val())
			main_id.push($(this).find('.main_id').val())
		}

	})
	tot_qty_tablesize_fin = tot_qty_tablesize.join(',');
	uvc_tco_fin = uvc_tco.join(',');
	size_fin = size.join(',');
	size_id_fin = size_id.join(',');
	main_id_fin = main_id.join(',');
	var buyer = localStorage.getItem("buyer");
	var csrf_token = $('meta[name="csrf-token"]').attr('content');
	$.ajax({
		url: base_url + "/admin/" + buyer + "/order-management/set_size_label",
		type: "Post",
		data: {
			
			template_id: template_id,
			tot_qty_table: tot_qty_tablesize_fin,
			id: id,
			colour: colour,
			size_id: size_id_fin,
			main_id: main_id_fin,
			uvc_tco: uvc_tco_fin,
			uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
			size: size_fin,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			$('#save_size_label_button').addClass('hidden')
			$('#treesl >ul > li').each( function() {
			   disable( 'treesl',this.id );        
			})
			$('.extra_percentagesize').attr('disabled','disabled')
			$('.tot_qty_tablesize').attr('disabled','disabled')
		},
		error: function (data) {

		}
	})
		$('.size_label-card span').text('Order Approved')
		$('.size_label-card span').addClass('label label-OrderApproved label-status')
		$('#po-file-upload-button').css('display','block')
}


/**
 * This Function is used to delete wash care labels from db.
 * 
 * @return void
 */

function removeWashLabel(dataid) {
	var id=$('#delete_id'+dataid).val()	
	if(id != null && id !="")
	{
		var buyer = localStorage.getItem("buyer");
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/remove_wash_label",
			type: "Post",
			data: {
				id: id,
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {

			},
			error: function (data) {

			}
		})
	}
}

/**
 * This Function is used to set wash care labels to db.
 * 
 * @return void
 */

function setWashLabel(index, dataid, id) {
	$('.washlabelname'+dataid).text($('#wcl_name' + dataid).val()+" - Washcare Label");
    var check=0;
    var check1=0;
    $('#composition_remove_qty' + dataid + ' tr').each(function () {
         
         if($(this).find('select' + dataid).find(':selected').text() == "Select Option")
             {
                 check++;
             }
         check1++;
    });
    if(check1 <2 )
    {
        check=0;
    }
    if(check == 0 )
    {
        var composition = localStorage.getItem('wash_composition');

        var wash_fins = JSON.parse(composition);
        var wash_finals = localStorage.getItem('wash_composition_final'+dataid);

        
        var merchandisers = $('.merchandisers option:selected').val();
      	
		
		//alert($('#wash_label_id').val())
        if($('#wash_label_id').val() != 0)
		{
			//var template_id = $('#' + index).find('#tree_wash' + dataid).jstree('get_selected', true);
			template_front_id = 5;
		}
		else
		{
			template_front_id=4;
		}
		var washonesizecm = $('#washcarelabelform' + dataid+' .washonesizecm').val();
		//var washonesizein = $('#washcarelabelform' + dataid+' .washonesizein').val();
		var po_select 	  = $('#po_select' + dataid).val();
		$('#'+po_select+'_'+dataid).val("1");
        var template_name = $('#wcl_name' + dataid).val();
        var card_flag = $('#card_flag' + dataid).val();
        var time_flag = $('#time_flag' + dataid).val();
        var washing_select = $('.washing_select' + dataid + ' option:selected').attr('data-id');
        var whitening_select = $('.whitening_select' + dataid + ' option:selected').attr('data-id');
        var dryer_select = $('.dryer_select' + dataid + ' option:selected').attr('data-id');
        var ironing_select = $('.ironing_select' + dataid + ' option:selected').attr('data-id');
        var cleaning_select = $('.cleaning_select' + dataid + ' option:selected').attr('data-id');
        var made_in_select = $('.made_in_select' + dataid + ' option:selected').attr('data-id');
        // var care_instruction_select = $('.care_instruction_select'+dataid+' option:selected').val();

        var care_instruction = $('.care_instruction_select' + dataid + ' option:selected');
        var care_instruction_select = [];
        $(care_instruction).each(function (index, brand) {
            care_instruction_select.push([$(this).val()]);
        });
        var care_instruction_select_fin = care_instruction_select.join(',');

        //template_front_id = template_id[0].id;
        var tot_qty_tablewash = [];
        var uvc_tco = [];
        var size = [];
        $('#' + index + ' tbody tr').each(function () {
            if ($(this).find('.tot_qty_tablewash').val() != undefined) {
                tot_qty_tablewash.push($(this).find('.tot_qty_tablewash').val())
                uvc_tco.push($(this).find('.uvc_tco').text())
                size.push($(this).find('.size').text())
            }

        })
        tot_qty_tablewash_fin = tot_qty_tablewash.join(',');
        uvc_tco_fin = uvc_tco.join(',');
        size_fin = size.join(',');
        var buyer = localStorage.getItem("buyer");
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: base_url + "/admin/" + buyer + "/order-management/set_wash_label",
            type: "Post",
            data: {
                
                template_front_id: template_front_id,
                id: id,
                template_name: template_name,
                washing_select: washing_select,
                whitening_select: whitening_select,
                po_select: po_select,
				//in:washonesizein,
				cm:washonesizecm,
                dryer_select: dryer_select,
                ironing_select: ironing_select,
                card_flag: time_flag,
                cleaning_select: cleaning_select,
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
                made_in_select: made_in_select,
                care_instruction_select: care_instruction_select_fin,
                composition: wash_finals,
                tot_qty_table: tot_qty_tablewash_fin,
                uvc_tco: uvc_tco_fin,
				color_code: $('#color_id'+dataid).val(),
                merchandisers: merchandisers,
                uvc: $('#uvc_id').val(),
                size: size_fin,
                _token: csrf_token
            },
            beforeSend: function () {
                $(".loader").show();
            },
            success: function (data) {
				$('#print_wash_button_'+dataid).removeClass('hidden')
				$('#save_wash_button_'+dataid).addClass('hidden')
				$('#delete_id'+dataid).val(data)
				$('.delete-wash'+dataid).addClass('hidden')
				$('#clickme'+dataid).html('View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i>')
				$('#wcl_name'+dataid).attr('disabled','disabled')
				$('.washing_select'+dataid).attr('disabled','disabled')
				$('.washing_select'+dataid).css("background-color","#eee")
				$('.whitening_select'+dataid).attr('disabled','disabled')
				$('.dryer_select'+dataid).attr('disabled','disabled')
				$('.ironing_select'+dataid).attr('disabled','disabled')
				$('.cleaning_select'+dataid).attr('disabled','disabled')
				$('.made_in_select'+dataid).attr('disabled','disabled')
				$('.care_instruction_select'+dataid).attr('disabled','disabled')
				$('.composition_select'+dataid).attr('disabled','disabled')
				$('.composition_percent'+dataid).attr('disabled','disabled')
				$('.add_to_list'+dataid).attr('disabled','disabled')
				$('.add_to_page'+dataid).attr('disabled','disabled')
				$('#composition_flag'+dataid).val('disabled')
				
				$("#wash_care_label_table_"+dataid+" :input").prop("disabled", true);
				//alert(dataid)
				
            },
            error: function (data) {

            }
        })
        $('.composition_error_hidden').addClass('hidden')
        $('#washcare_label_card'+dataid).css('display','inline-block') 
		$('#clone_washcare_label_card'+dataid).css('display','inline-block')
		$('.wash-card'+dataid+' span').text('Buyer Approval Pending')
		$('.wash-card'+dataid+' span').addClass('label label-BuyerApprovalPending label-status')
		$('.wash-card'+dataid+' span').removeClass('label-OrderRejected')
		$('#po-file-upload-button').css('display','block')
    }
    else
    {
        $('.composition_error_hidden').removeClass('hidden')
    }
    

}

/**
 * This Function is used to place the order.
 * 
 * @return void
 */

function save_label_orders() {
	var finish = 0;
	if (localStorage.getItem("wash_label") != null) {

        if ($('.merchandisers').find("option:selected").val() != "") {
            var wash_json = JSON.parse(localStorage.getItem("wash_label"));

            $.each(wash_json, function (index, value) {
                var data_array = index.split('washcarelabelform');
                setWashLabel(index, data_array[1], value)
            });

        } else {
            return false;


        }
        finish++;
        //setMainLabel()
    } else {
        finish++;
    }
    if (localStorage.getItem("hang_tag") != null) {
        setHangTag(localStorage.getItem("hang_tag"))
        finish++;
    } else {
        finish++;
    }
    if (localStorage.getItem("special_tag") != null) {
        setSpecialTag(localStorage.getItem("special_tag"))
        finish++;
    } else {
        finish++;
    }
    if (localStorage.getItem("main_label") != null) {
        var main_json = JSON.parse(localStorage.getItem("main_label"));

        $.each(main_json, function (index, value) {
            var data_array = index.split('mainlabelsform');
            setMainLabel(index, data_array[1], value)
        });
        finish++;
        //setMainLabel()
    } else {
        finish++;
    }
    if (localStorage.getItem("deletemain") != null) {
        var del_main_json = JSON.parse(localStorage.getItem("deletemain"));

        $.each(del_main_json, function (index, value) {
            removeMainLabel(value)
        });
        finish++;
    } else {
        finish++;
    }
    if (localStorage.getItem("deletewash") != null) {
        var del_wash_json = JSON.parse(localStorage.getItem("deletewash"));

        $.each(del_wash_json, function (index, value) {
            removeWashLabel(value)
        });
        finish++;

    } else {
        finish++;
    }
    if (finish == 6) {
        location.reload();
    }
    //location.reload();

}

/**
 * This Function is used to set Solid and Standard Stickers to db.
 * 
 * @return void
 */

function setSolidStickers() {
	
	if($('#age').val() != "Age Group"){
		$('.age_group_error').addClass('hidden')
	if ($('#carton_hidden').val() == "standard") {
		var buyer = localStorage.getItem("buyer");
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/set_standard_stickers",
			type: "Post",
			data: {
				uvc: $('#uvc_id').val(),
				carton_template_id: $('#carton_template_id').val(),
				polybag_template_id: $('#polybag_template_id').val(),
				height: $('#modal_height').val(),
				no_of_carton: $('#no_of_carton').val(),
				total_polybag: $('#total_polybag').val(),
				final_order_qty_sets: $('#final_order_qty_sets').val(),
				order_qty: $('#order_qty').val(),
				final_order_qty: $('#final_order_qty').val(),
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
				age: $('#age').val(),
				carton_id: $('#std_carton_id').val(),
				poly_id: $('#std_poly_id').val(),
				no_of_carton: $('#needed_modal').val(),
				po: $('#po').val(),
				//size: size_fin,
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$('#save_sticker_button').addClass('hidden')
				$('#po-file-upload-button-standard').css('display','block')
				$('#polybag_print_button').removeClass('hidden')
				$('#carton_print_button').removeClass('hidden')
			},
			error: function (data) {

			}
		})
	} else {
		var tot_qty_table_poly = [];
		var tot_qty_carton = [];
		var size = [];

		
		$('#solidform tbody tr').each(function () {
			if ($(this).find('.tot_qty_table_poly').val() != undefined) {
				tot_qty_table_poly.push($(this).find('.tot_qty_table_poly').val());
			}
			if ($(this).find('.tot_qty_table_carton').val() != undefined) {
				tot_qty_carton.push($(this).find('.tot_qty_table_carton').val());
			}
			size.push($(this).find('.size').text());

		})
		tot_qty_table_poly_fin = tot_qty_table_poly.join(',');
		tot_qty_carton_fin = tot_qty_carton.join(',');
		size_fin = size.join(',');

		var buyer = localStorage.getItem("buyer");
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			url: base_url + "/admin/" + buyer + "/order-management/set_solid_stickers",
			type: "Post",
			data: {
				
				// template_front_id: template_front_id,
				tot_qty_poly: tot_qty_table_poly_fin,
				tot_qty_carton: tot_qty_carton_fin,
				size: size_fin,
				uvc: $('#uvc_id').val(),
				carton_template_id: $('#carton_template_id').val(),
				polybag_template_id: $('#polybag_template_id').val(),
				height: $('#modal_height').val(),
				age: $('#age').val(),
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
				carton_id: $('#carton_id').val(),
				poly_id: $('#poly_id').val(),
				po: $('#po').val(),
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$('#save_sticker_button').addClass('hidden')
				$('#po-file-upload-button-solid').css('display','block')
				$('#polybag_print_button').removeClass('hidden')
				$('#carton_print_button').removeClass('hidden')
			},
			error: function (data) {

			}
		})
	}
	}
	else{
		$('.snackbar_span').html('Please Select Age Group');
		showSnackbar();
		//$('.age_group_error').removeClass('hidden')
	}

}
/**
 * This Function is used to clone wash care labels.
 * 
 * @return void
 */
function clonewashcarelabels(index,color_code) 
{
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"><span></span>    <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')

				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare,color_code);
				});
		 
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
           		
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label cloned successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare,color_code,"new");
            $('#washcarelabelform'+washcare).find('#wcl_name'+washcare).val($('#washcarelabelform'+index).find('#wcl_name'+index).val())
           
            
            $('.washing_select'+washcare).selectpicker('val',$('.washing_select'+index).selectpicker('val'));
            $('.whitening_select'+washcare).selectpicker('val',$('.whitening_select'+index).selectpicker('val'));
            $('.dryer_select'+washcare).selectpicker('val',$('.dryer_select'+index).selectpicker('val'));
            $('.ironing_select'+washcare).selectpicker('val',$('.ironing_select'+index).selectpicker('val'));
            $('.cleaning_select'+washcare).selectpicker('val',$('.cleaning_select'+index).selectpicker('val'));
            $('.made_in_select'+washcare).selectpicker('val',$('.made_in_select'+index).selectpicker('val'));
	
			//$('#washcarelabelform' + washcare+' .washonesizein').val($('#washcarelabelform' + index+' .washonesizein').val())
			$('#washcarelabelform' + washcare+' .washonesizecm').val($('#washcarelabelform' + index+' .washonesizecm').val())
			
			
	
	
	
            $('.care_instruction_select'+washcare).selectpicker('val',$('.care_instruction_select'+index).selectpicker('val'));
            
            $('#composition_remove_qty'+washcare).html($('#composition_remove_qty'+index).html())
            
            $('#composition_remove_qty'+index+' tr').each(function (ind,val) {
                var values= $(this).find('select').find(':selected').val()
                var v= $(this).attr('id');
                $('#composition_remove_qty'+washcare+' tr').each(function (inds,vals) {
                    if($(this).attr('id') == v)
                    {
                        $(this).find('select').val(values)
                        $(this).find('a').removeAttr('onclick')
                        $(this).find('a').attr('onclick','deleteComposition(this,'+washcare+')')
                    }
                });
            }); 
            
            $('#washcarelabelform'+index+' table tbody tr').each(function (ind,val) {
                var values= $(this).find('.tot_qty_tablewash').val()
                var v= $(this).find('.uvc_tco').text();
                $('#washcarelabelform'+washcare+' table tbody tr').each(function (inds,vals) {
                    if($(this).find('.uvc_tco').text() == v)
                    {
                        $(this).find('.tot_qty_tablewash').val()
                        
                    }
                });
            });
            
            $('#washcarelabelform'+washcare+' table tbody').find('.tot_qty_tablewash_fin'+washcare).text($('#washcarelabelform'+index+' table tbody').find('.tot_qty_tablewash_fin'+index).text())
            var mySVG = document.getElementById("washcare_svg_front"+washcare);
            var svgDoc;
	
	
			var mySVG_back = document.getElementById("washcare_svg_back"+washcare);
            var svgDocBack;
			
            mySVG.addEventListener("load", function () {
                svgDoc=mySVG.contentDocument;
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('astm'));
                
                var text_for_mad_in = '<p>' + $('select.made_in_select' + washcare).find(':selected').data('french') + "-" + $('select.made_in_select' + washcare).find(':selected').data('english') + "-" + $('select.made_in_select' + washcare).find(':selected').data('spanish') + "-" + $('select.made_in_select' + washcare).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + washcare).find(':selected').data('italian') + "-" + $('select.made_in_select' + washcare).find(':selected').data('dutch') + "-" + $('select.made_in_select' + washcare).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('greek')  + '</p>';
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;
                
            });
	
	  mySVG_back.addEventListener("load", function () {
				svgDocBack=mySVG_back.contentDocument;
				svgDocBack.getElementById("text_care_instruction").innerHTML = "";
                $('.care_instruction_select' + washcare + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek')  + '</p>';
                    svgDocBack.getElementById("text_care_instruction").innerHTML += value;
                });
                
                 svgDocBack.getElementById("parts_composition").innerHTML ="";
              $('#composition_remove_qty' + washcare + ' tr').each(function () {
                  if($(this).find('select').find(':selected').val() != "Select Option")
                  {
                       svgDocBack.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german') + "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek') + '</p>';
                  }
                 
                  var id=$(this).attr('id')
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                  $(compss).each(function (index1,value1) {
                      var english = value1.split('_');

                    svgDocBack.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
                });
              })
            $('.composition_error_hidden').addClass('hidden')
            var washcomp=[];
            $('#composition_remove_qty' + washcare + ' tr').each(function () {
                  var washcomp1=[];
                  var id=$(this).attr('id')
                  var value=$(this).find('select').find(':selected').val();
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                    $(compss).each(function (index1,value1){ 
                        var english = value1.split('_');
                        washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                    });
                washcomp.push(washcomp1)
            })
            var wash_finals = JSON.stringify(washcomp);
            localStorage.setItem('wash_composition_final'+washcare, wash_finals);
			});
}
function artwork_modal(uvc,po)
{
	alert(uvc)
	alert(po)
}

function wordWrap(str, maxWidth) {
    var newLineStr = ""; done = false; res = '';
    do {                    
        found = false;
        // Inserts new line at first whitespace of the line
        for (i = maxWidth - 1; i >= 0; i--) {
            if (testWhite(str.charAt(i))) {
				
				var arabic = /[\u0600-\u06FF]/;
//				var split_text = [str.slice(0, i), newLineStr].join('').split(' - ')
//				$(split_text).each(function(index,value){
//					if(arabic.test(value) == true)
//					{
//						"<tspan>"+value+"</tspan>"
//					}
//				})
				
				if(arabic.test([str.slice(0, i), newLineStr].join('')) == true)
				{
					var join_text="";
					var split_text = [str.slice(0, i), newLineStr].join('').split(' - ')
					$(split_text).each(function(index,value){
						if(arabic.test(value) == true)
						{
							join_text += "<tspan style='font-family:Dejavu Sans' text-anchor='start'> - "+value+"</tspan>";
						}
						else
						{
							join_text += "<tspan text-anchor='end'  x='0' dy='7'> - "+value+"</tspan>";
						}
					})
					res = res + join_text;
				}
				else
				{
					res = res + "<tspan  x='0' dy='7'>" +[str.slice(0, i), newLineStr].join('') + "</tspan>";
				}
				
                
                str = str.slice(i + 1);
                found = true;
                break;
            }
        }
        // Inserts new line at maxWidth position, the word is too long to wrap
        if (!found) {
            res +="<tspan>" + [str.slice(0, maxWidth), newLineStr].join('')+ "</tspan>";
            str = str.slice(maxWidth);
        }

        if (str.length < maxWidth)
            done = true;
    } while (!done);
	
	if(arabic.test(str) == true)
	{
		var join_text="";
		var split_text = str.split(' - ')
		$(split_text).each(function(index,value){
			if(arabic.test(value) == true)
			{
				join_text += "<tspan dy='7' x='0' text-anchor='end' style='font-family:Dejavu Sans'> - "+value+" </tspan>";
			}
			else
			{
				join_text += "<tspan text-anchor='start'  x='0'> "+value+"</tspan>";
			}
		})
		return res + join_text;
	}
	else
	{
		return res + "<tspan  x='0' dy='7'>" + str + "</tspan>";
	}

}

function testWhite(x) {
    var white = new RegExp(/^\s$/);
    return white.test(x.charAt(0));
}
/**
 * [MOQ validation function]
 * @author <praveen>
 */
function checkMOQ(obj) {
	try {
		//alert()
			var moq =$(obj).attr("min"); 
			$(obj).data("min-error","Qty should be >= "+moq); 
	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}
function checkorcMOQ(obj) {
	try {
			var moq =$(obj).attr("min"); 
			$(obj).data("min-error","Qty should be >= "+moq); 
	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}