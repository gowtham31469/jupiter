var wash_care_count = 1;
var washcare = 0;
var mainlabel = 0;
var composition_percentage_global = 0;
var add_page_composition = 0;
var main_label = {};
var wash_label = {};
var wash_composition = {};
var deletemain = [];
var deletewash = [];

function label_order_details(uvc,user_type,season_code) {
    var search_string = $('.dataTables_filter input').val();
    localStorage.setItem("search_string", search_string);
    var buyer = localStorage.getItem("buyer");
    
    $.ajax({
        url: base_url + "/"+user_type+"/" + buyer + "/order-management/label-order-details",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            uvc: uvc,
            season_code:season_code
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
            if(data.customers.length > 0)
			{
                OrderDetailsCardView(uvc, data,user_type)
                hangtagmodal(data,uvc,user_type)
                heattransfermodal(data,user_type)
                specialtagmodal(data,user_type)
                mainlabelmodal(data,user_type)
                sizelabelmodal(data,user_type)
            }
            else
            {
                $('.snackbar_span').html('Company Details Missing, Please Contact Administrator.');
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

function OrderDetailsCardView(uvc, data,user_type)
{
    var buyer_po = "";
    var hang_tag_orders = data.orchestra_hang_tag_orders;
    var orchestra_heat_transfer_orders = data.orchestra_heat_transfer_orders;
	
	$.each(data.buyer_po, function (index,value) {
			buyer_po += value.BuyerPONo + " | ";
	});

	$('.top-content').append('<div class="top-content-new-div"><h3 class="content-heading">Order Detail for UVC ' + uvc + '</h3><h5 class="poc_number"><strong>POs for this UVC : </strong><span id="poNo">' + buyer_po + '  </span></h5></div>');


    var merchandisersoption = "<option value=''>Select Merchandiser</option>";
	var update_disable=0;

	$.each(data.merchandisers, function (index, value) {
		merchandisersoption += "<option value=" + value.id + ">" + value.merchandiser_name + "</option>";
	});

    if (hang_tag_orders != "") {
		var str = hang_tag_orders[0].status;
		$hang_status = str.replace(/ /g, "");
		$hang_status_text = hang_tag_orders[0].status;
		update_disable=1;
	} else {
		$hang_status = "";
		$hang_status_text = "";
	}

    if (orchestra_heat_transfer_orders != "") {
		var str = orchestra_heat_transfer_orders[0].status;
		$heat_transfer_status = str.replace(/ /g, "");
		$heat_transfer_status_text = orchestra_heat_transfer_orders[0].status;
		update_disable=1;
	} else {
		$heat_transfer_status = "";
		$heat_transfer_status_text = "";
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

    if (localStorage.getItem("page_navigation") == "orders") {
		var onclick = "orders('"+user_type+"')";
	} else {
		var onclick = "orderStatus('"+user_type+"')";
	}
    
	if(update_disable == 0)
	{
		var update_disabel_button = "display:none";
	}
	else
	{
		var update_disabel_button = "";
	}

    var wcl_grid = ""

    $(data.color_wcl).each(function (index1, value1) {
        wcl_grid += `
        <button class="btn btn-default wcl-card" data-color="`+value1.Color_Code+`" id="newWashCareLabel_`+value1.Color_Code+`"><i class="fa fa-plus" aria-hidden="true"></i> Add New - `+value1.Color_Code+`</button>`;
    });

    $('.main-content').html(`
    
        <input type="hidden" value="` + data.order_details[0].UVC + `" id="uvc_id"><input type="hidden" value="` + data.order_details[0].Theme + `" id="theme_label"><input type="hidden" value="` + data.order_details[0].id + `" id="parcel_id"><input type="hidden" value="` + data.order_details[0].Season_Code + `" id="season_code"><input type="hidden" value="` + data.customers[0].customer_id + `" id="customer_id">

        <h4 class="section-title">Artworks</h4>

        <div class="artworks-grid">
        <div class="artwork-wrapper">
        <div class="artwork-card hang-card">
        <div class="main-data"><span class="label label-` + $hang_status + `">` + $hang_status_text + `</span>
        <h3>Hang Tag</h3>
        <img src="` + base_url + `/images/ht_default.png" class="img-responsive"></div>
        <div class="actions">
        <ul>
        <li data-toggle="tooltip" data-placement="top" title="Edit" id="clickmehang" style="cursor: pointer;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</li>
        </ul>
        </div>
        
        </div>
        </div>

        <div class="artwork-wrapper">
        <div class="artwork-card heat_transfer-card">
        <div class="main-data"><span class="label label-` + $heat_transfer_status + `">` + $heat_transfer_status_text + `</span>
        <h3>Heat Transfer</h3>
        <img src="` + base_url + `/images/HT.jpg" class="img-responsive"></div>
        <div class="actions">
        <ul>
        <li data-toggle="tooltip" data-placement="top" title="Edit" id="clickmeheat_transfer" style="cursor: pointer;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</li>
        </ul>
        </div>
        </div>
        </div>

        <div class="artwork-wrapper">
        <div class="artwork-card special-card">
        <div class="main-data"><span class="label label-` + $special_status + `">` + $special_status_text + `</span>
        <h3>Special Tag</h3>
        <img src="` + base_url + `/images/st_default.png" class="img-responsive"></div>
        <div class="actions">
        <ul>
        <li data-toggle="tooltip" data-placement="top" title="Edit" id="clickmespecial" style="cursor: pointer;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</li>
        </ul>
        </div>
        </div>
        </div>

        <div class="artwork-wrapper">
        <div class="artwork-card main_label-card">
        <div class="main-data"><span class="label label-` + $main_label_status + `">` + $main_label_status_text + `</span>
        <h3>Main Label</h3>
        <img src="` + base_url + `/images/ML.png" class="img-responsive"></div>
        <div class="actions">
        <ul>
        <li data-toggle="tooltip" data-placement="top" title="Edit" id="clickmemain_label" style="cursor: pointer;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</li>
        </ul>
        </div>
        </div>
        </div>

        <div class="artwork-wrapper">
        <div class="artwork-card size_label-card">
        <div class="main-data"><span class="label label-` + $size_label_status + `">` + $size_label_status_text + `</span>
        <h3>Size Label</h3>
        <img src="` + base_url + `/images/SL.png" class="img-responsive"></div>
        <div class="actions">
        <ul>
        <li data-toggle="tooltip" data-placement="top" title="Edit" id="clickmesize_label" style="cursor: pointer;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</li>
        </ul>
        </div>
        </div>
        </div>

        <h4 class="section-title">Wash care labels</h4>
        <div class="row">
        <div class="col-md-3">
        <div class="form-group has-feedback has-success"><select data-live-search="true" class="form-control selectpicker input-sm merchandisers" required>` + merchandisersoption + `</select><div class="help-block with-errors"></div><label class="hidden merchandiser_error" style="color:red">Please Select Merchandiser</label>
        </div> 
        </div>
        <div class="col-md-9">
            `+wcl_grid+`
        </div>
        </div>
    
        <div class="artworks-grid" style="align-items: start; display: flex;">
        <div class="wcl_orders_card"></div>
        </div>

        <div class="bottom-bar"><div class="modal-footer"><button onclick="` + onclick + `" class="btn btn-danger pull-right btn-xs"><i class="fa fa-times" aria-hidden="true"></i>Close</button><button data-toggle="modal" id="po-file-upload-button" style="`+update_disabel_button+`" class="btn btn-success pull-right btn-xs">Update PO</button> </div></div>
    `);

    $(data.orchestra_wash_care_label_orders).each(function (index1, value1) {
        $('#po-file-upload-button').removeAttr("style")
        var str = value1.status;
        $wcl_status=str.replace(/ /g, "");
        $wcl_status_text=str;

        $('.wcl_orders_card').append(`<div class="artwork-wrapper">
        <div class="artwork-card wcl_status_`+wash_care_count+`" data-color="`+value1.color_code+`" id="oldWashCareLabel_`+value1.color_code+`">
        <div class="main-data">
        <span class="label label-` + $wcl_status + `">` + $wcl_status_text + `</span>
            <h3>`+value1.template_name+`</h3>
            <p>Color Code - `+value1.color_code+`</p>
            <img src="` + base_url + `/images/wcl_default.png" class="img-responsive">
            </div>
            <div class="actions">
            <ul>
                <li data-toggle="tooltip" data-placement="top" title="Edit" id="clickmewcl_`+wash_care_count+`" style="cursor: pointer;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</li>
                <li data-toggle="tooltip" data-placement="top" title="Clone" data-id="`+wash_care_count+`" data-color="`+value1.color_code+`" id="clone_washcare_label_card`+wash_care_count+`" style="cursor: pointer; margin-left:-3px"><i class="fa fa-clone" aria-hidden="true"></i> Clone</li>
            </ul>
            </div>
            </div>
      
    </div>`);

        localStorage.setItem('care_instruction_select'+wash_care_count, JSON.stringify(value1.care_instruction));

        washcarelabelmodal(value1, wash_care_count,value1.color_code,data.order_details[0].Activity_Label,data.order_details,data.orchestra_care_symbols,data.orchestra_composition,data.orchestra_countries,data.orchestra_care_instruction,data.orchestra_parts,data.treeviewwash_label,user_type,data.buyer_po)


        $('#clone_washcare_label_card'+wash_care_count).on('click', function (e) {

                clonewashcarelabels(wash_care_count, $(this).data('color'), user_type, data.order_details[0].Activity_Label,data.order_details,data.orchestra_care_symbols,data.orchestra_composition,data.orchestra_countries,data.orchestra_care_instruction,data.orchestra_parts,$(this).data('id'),data.treeviewwash_label,user_type,data.buyer_po);
         

        });
        
        wash_care_count++;        
    });    

    selectpicker()

    $('.wcl-card').click(function() {
        if( $('.merchandisers option:selected').val() != "")
        {
            var wcl_count = wash_care_count -1; 
            if($('.wcl_status_'+wcl_count+' span').text() == "New Order" || $('.wcl_status_'+wcl_count+' span').text() == "Clone Order")
            {
                $('.snackbar_span').html('Please place the order you have created before creating another order.');
                showSnackbar();
            }
            else
            {
                var color_code = $(this).data('color')
                $('.wcl_orders_card').append(`
                        <div class="artwork-wrapper">
                            <div class="artwork-card wcl_status_`+wash_care_count+`" data-color="`+color_code+`" id="newWashCareLabel_`+color_code+`">
                            <div class="main-data">
                                <span class="label label-Saved">New Order</span>
                                <h3 class="wcl_name_card_`+wash_care_count+`">Wash Care Label</h3>
                                <p>Color Code - `+color_code+`</p>
                                <img src="` + base_url + `/images/wcl_default.png" class="img-responsive">
                                </div>
                                <div class="actions">
                                <ul>
                                    <li data-toggle="tooltip" data-placement="top" title="Edit" id="clickmewcl_`+wash_care_count+`" style="cursor: pointer;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</li>
                                    <li data-toggle="tooltip" data-placement="top" title="Delete" data-id="`+wash_care_count+`" id="deletewcl_`+wash_care_count+`" style="cursor: pointer; margin-left:-3px"><i class="fa fa-trash" aria-hidden="true"></i> Delete</li>
                                    <li class="hidden" data-toggle="tooltip" data-placement="top"  data-id="`+wash_care_count+`" data-color="`+color_code+`" title="Clone" id="clone_washcare_label_card`+wash_care_count+`" style="cursor: pointer; margin-left:-3px"><i class="fa fa-clone" aria-hidden="true"></i> Clone</li>
                                </ul>
                                </div>
                                </div>
                          
                        </div>
                `)
    
                $('#deletewcl_' + wash_care_count).on('click', function (e) {
                    
                    $('#washcarelabelform' + $(this).data('id')).remove()
                        
                    $(this).parent().parent().parent().parent().remove();

                    wash_care_count--;
    
                });

                $('#clone_washcare_label_card'+wash_care_count).on('click', function (e) {

                    clonewashcarelabels(wash_care_count, $(this).data('color'), user_type, data.order_details[0].Activity_Label,data.order_details,data.orchestra_care_symbols,data.orchestra_composition,data.orchestra_countries,data.orchestra_care_instruction,data.orchestra_parts,$(this).data('id'),data.treeviewwash_label,user_type,data.buyer_po);
             
    
                });
    
                washcarelabelmodal("",wash_care_count,color_code,data.order_details[0].Activity_Label,data.order_details,data.orchestra_care_symbols,data.orchestra_composition,data.orchestra_countries,data.orchestra_care_instruction,data.orchestra_parts,data.treeviewwash_label,user_type,data.buyer_po)
    
                wash_care_count++;
    
    
                $('[data-toggle="tooltip"]').tooltip()
            }

        }
        else
        {
            $('.snackbar_span').html('Please Select Merchandiser');
            showSnackbar();
        }
        
    });

    if (data.orchestra_wash_care_label_orders.length > 0) {
        $('.merchandisers').selectpicker('val', data.orchestra_wash_care_label_orders[0].merchandisers_id);
        $('.merchandisers').attr('disabled','disabled')
    }

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
			url: base_url + "/"+user_type+"/" + buyer + "/order-management/get-existing-label-orders-po",
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
				var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_size_label_orders.concat(data.orchestra_heat_transfer_orders)))));
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
                                
                                var form_data = new FormData();
                                form_data.append('id', aa);
                                form_data.append('po_file', new_po_file);
                                form_data.append('po', new_po_no);
                                form_data.append('_token', csrf_token);
                                $.ajax({
                                    url: base_url + "/"+user_type+"/" + buyer + "/order-management/update-label-orders-po",
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: form_data,
                                    type: 'post',
                                    success: function (data) {
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
                                
                                var form_data = new FormData();
                                form_data.append('id', aa);
                                form_data.append('po_file', new_po_file);
                                form_data.append('po', new_po_no);
                                form_data.append('_token', csrf_token);
                                $.ajax({
                                    url: base_url + "/"+user_type+"/" + buyer + "/order-management/update-label-orders-po",
                                    dataType: 'text',
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: form_data,
                                    type: 'post',
                                    success: function (data) {
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
                            
                            //alert($('#select_po_status option:selected').attr('data-id'))
                            $.ajax({
                                url: base_url + "/"+user_type+"/" + buyer + "/order-management/map-label-orders-po",
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

    $('[data-toggle="tooltip"]').tooltip()
}

function hangtagmodal(data,uvc,user_type) {
    var order_details = data.order_details;
    var hang_tag_orders = data.orchestra_hang_tag_orders;
    var tr = "";
    $qu = 0;
    if (hang_tag_orders != "") {
       

        $template_front_id = hang_tag_orders[0].template_front_id;
        $exist_id = hang_tag_orders[0].id;
        
        var str=hang_tag_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
		$print_button_class="";
		$save_button_class="hidden";
		$ocn_number_hang = hang_tag_orders[0].ocn_number;
        var onclick = `onclick=printHangDivOs("svg_hang_front","svg2","`+uvc+`","","`+user_type+`","`+hang_tag_orders[0].id+`","orchestra_hang_tag_orders","proof")`;
    } else {
       
        $template_front_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
		$print_button_class="hidden";
		$save_button_class="";
		$ocn_number_hang="";
        var onclick = "";
    }
    var ttots = 0;
    var ttots_sum = 0;
    $.each(order_details, function (index, value) {
        if (hang_tag_orders != "") {
            $hang_tag_count = hang_tag_orders[$qu].quantity;
        } else {
            $hang_tag_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td style="display: flex;justify-content: center;"> <div class="form-group has-feedback has-success" style="width: 100%;"><input style="margin: 0 auto; width: 95px;" type="number" class="form-control  input-sm tot_qty_tablehang" data-required-error="Required" value="' + $hang_tag_count + '" required placeholder="Hang Tag Count"><div class="help-block with-errors min-div" style="font-size:10px"></div></div></td></tr>';
        ttots += parseInt($hang_tag_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
	
	if(order_details[0].Size == "TAILLE UNIQUE")
	{
		if (hang_tag_orders != "") {
            $hang_cm = hang_tag_orders[0].cm;
			$hang_in = hang_tag_orders[0].in;
        } else {
            $hang_cm = "";
			$hang_in = "";
        }
		var sizebox='';
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

    $('.top-content').append(`
        <form id="hangtagform" data-toggle="validator"><input type="hidden" value="` + $exist_id + `" id="exist_id">
            <div class="sample-front"></div>
            <div class="sample-back"></div>
            <div class="left-panelorc-hang col-md-4">
                <div class="div-max">
                    <div class="col-md-12">
                        <h4>Options</h4>
                        <hr>
                        <div class="form-group has-feedback has-success"><label for="ocn_number" class="form_label">OCN
                                Number</label><input type="text" value="`+ $ocn_number_hang +`" id="ocn_number_hang" value=""
                                class="form-control input-sm" placeholder="OCN Number"></div>
                        <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                class="form_label">Select Style</label><label id="hang_template_error" class="hidden"
                                style="color:red;display: inherit;">Please Select the Template</label>
                                <div style="margin-top: -45px;">
                                    <button type="button" style="background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="expand">Expand</button>
                                    <button type="button" class="btn btn-xs btn-success" style="float: right;background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" id="collapse">Collapse</button>
                                </div>
                            <div><input type="text" id="tree1_q" value="" class="form-control input-sm"
                                    placeholder="Search for Awesomeness"> </div>
                            <div class="scroll" id="tree1"
                                style="height: calc(100vh - 260px);overflow-y: scroll;margin-top: 15px;"></div>
                            <div class="help-block with-errors"></div>
                        </div>`+sizebox+`
                    </div>
                </div>
            </div>
            <div class="right-panel-hang col-md-8">
                <div class="div-max scroll">
                    <h4 style="display:inline">Quantity Info for Hang Tag</h4>
                    <span class="label label-` + $status + `" style="margin-left:10px;">` + $status_text + `</span>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-condensed qty-table-modal" id="hang_tag_table">
                            <thead>
                                <tr>
                                    <th>UVC TCO#</th>
                                    <th>Size</th>
                                    <th>Barcode</th>
                                    <th>Qty</th>
                                    <th style="display: flex;justify-content: center;"><label style="float: left; line-height: 2">Extra %:</label> <input class="form-control input-sm extra_percentagehang" type="text" onkeypress="return checkNumbersOnly(event)" style="width:60px; float: left; margin-left: 5px;"></th>
                                </tr>
                            </thead>
                            <tbody>` + tr + `<tr class="strong">
                                    <td class="text-right" colspan="3">Total:</td>
                                    <td>` + ttots_sum + `</td>
                                    <td class="text-center tot_qty_tablehang_fin">` + ttots + `</td>
                                </tr>
                            </tbody>
                        </table>
                    </div><button type="button" class="btn btn-danger hidden input-sm `+$print_button_class+`"
                        id="cancel_hang_button" style="float: right;margin-left:10px" onclick=cancelHangDiv()>Cancel</button>
                    <button class="btn btn-primary pull-right input-sm left `+$save_button_class+`" type="submit"
                        id="save_hang_button" style="margin-left:10px">Save</button> <button
                        class="btn btn-default pull-right input-sm" type="button" id="hangclose">Close</button><button
                        type="button" class="btn btn-primary `+$print_button_class+`" id="print_hang_button"
                        `+onclick+`>Print</button>
                </div>
            </div>
            <div class="backdrop"></div>
        </form>
    `);



//$('.left').removeClass('disabled')
    $('#hangtagform .tot_qty_tablehang').keyup(function () {
        var tot_qty_tablehang_carton_final = 0;
        $('#hangtagform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablehang').val() != undefined && $(this).find('.tot_qty_tablehang').val() != "") {
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

		$('#hangtagform').validator('validate')

    })
	
    $("#clickmehang").click(function () {
        // $('.sample-artwork').html("")
        $("#hangtagform .backdrop").toggleClass('active');
        $("#hangtagform .right-panel-hang").toggleClass('active');
        $('#hangtagform .scalable').toggleClass('active');
        $("#hangtagform .left-panelorc-hang").toggleClass('active');
    })

    $(".backdrop, #hangclose").click(function () {
        $("#hangtagform .left-panelorc-hang").removeClass('active');
        $("#hangtagform .right-panel-hang").removeClass('active');
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

    $("#tree1").on("loaded.jstree", function (e, data) {
		$('#tree1').jstree(true).select_node($template_front_id);
        if ($template_front_id != "") {
            var buyer = localStorage.getItem("buyer");
            var template_id = $template_front_id;
            
            $.ajax({
                url: base_url + "/"+user_type+"/" + buyer + "/template-management/template-view",
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
                        $('#hangtagform').find('.sample-front').html('<object id="svg_hang_front" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + data.treeviewData[0].template_file + '" type="image/svg+xml"></object>')
                        $('#hangtagform').find('.sample-back').html('<object id="svg2" style="opacity:0;width:0px;height:0px" data="' + data.treeviewData[1].template_file + '" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden;z-index: -1;position: absolute;"><svg id="ean-13"></svg></div>')
                    } 
					
					if (hang_tag_orders != "") {
                        $('#tree1 >ul > li').each( function() {
                            disable( 'tree1',this.id );        
                        })
                        $('.extra_percentagehang').attr('disabled','disabled')
                        $('.tot_qty_tablehang').attr('disabled','disabled')
                        $('#ocn_number_hang').attr('disabled','disabled')
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


    $('#tree1').on('activate_node.jstree', function (e, data) {
        if (data.instance.is_leaf(data.node)) {
            var buyer = localStorage.getItem("buyer");
            var template_id = data.node.id;
            $('#hang_template_error').addClass('hidden')
            
            $.ajax({
                url: base_url + "/"+user_type+"/" + buyer + "/template-management/template-view",
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
                        $('#hangtagform').find('.sample-front').html('<object id="svg_hang_front" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + data.treeviewData[0].template_file + '" type="image/svg+xml"></object>')
                        $('#hangtagform').find('.sample-back').html('<object id="svg2" style="opacity:0;width:0px;height:0px" data="' + data.treeviewData[1].template_file + '" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden;z-index: -1;position: absolute;"><svg id="ean-13"></svg></div>')
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
            setHangTagStatus(data.color_wcl,user_type,"create_order")
        }
    })
}

function heattransfermodal(data,user_type) {

    var order_details = data.order_details;
    var orchestra_heat_transfer_orders = data.orchestra_heat_transfer_orders;

    var tr = "";
    $qu = 0;
    if (orchestra_heat_transfer_orders != "") {

      
        $template_id = orchestra_heat_transfer_orders[0].template_id;
        $exist_id = orchestra_heat_transfer_orders[0].id;
        $heat_transfer_count=orchestra_heat_transfer_orders[0].final_qty;
        
        var str=orchestra_heat_transfer_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
		$cancel_button_class="";
		$ocn_number_heat = orchestra_heat_transfer_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $heat_transfer_count="";
        $status="";
        $status_text="";
        $save_button_class="";
		$cancel_button_class="hidden";
		$ocn_number_heat = "";
    }
    var ttots = 0;
	var ttots_sum = 0;
    $.each(order_details, function (index, value) {
        if (orchestra_heat_transfer_orders != "") {

            $heat_transfer_count = orchestra_heat_transfer_orders[$qu].quantity;
        } else {
            $heat_transfer_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td style="display: flex;justify-content: center;"> <div class="form-group has-feedback has-success" style="width: 100%;"><input type="number" style="margin: 0 auto; width: 95px;" class="form-control input-sm tot_qty_tableheat" data-required-error="Required" value="' + $heat_transfer_count + '" required placeholder="HT Count"><div class="help-block with-errors min-div" style="font-size:10px"></div></div></td></tr>';
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

    $('.top-content').append(`
                <form id="heat_transferform" data-toggle="validator">
                <div class="left-panel-heat col-md-7" id="HandTag"><input type="hidden" value="` + $exist_id + `" id="exist_id">
                    <div class="div-max">
                        <div class="col-md-6 ">
                            <h4>Options</h4>
                            <hr>
                            <div class="form-group has-feedback has-success"><label for="ocn_number" class="form_label">OCN
                                    Number</label><input type="text" id="ocn_number_heat" value="`+$ocn_number_heat+`"
                                    class="form-control input-sm" placeholder="OCN Number"></div>
                            <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                    class="form_label">Select Style</label><label id="heat_transfer_template_error"
                                    style="color:red;display: inherit;" class="hidden">Please select the template</label>
                                <div style="margin-top: -45px;">
                                    <button type="button" style="background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="expand_heat">Expand</button>
                                    <button type="button" class="btn btn-xs btn-success" style="float: right;background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" id="collapse_heat">Collapse</button>
                                </div>
                                <div><input type="text" id="treeht_q" value="" class="form-control input-sm"
                                        placeholder="Search for Awesomeness"> </div>
                                <div class="scroll" id="treeht"
                                    style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>ArtWork</h4><span class="label label-`+$status+`"
                                style="position: absolute;top: 30px;left: 15px;">`+$status_text+`</span>
                            <hr>
                            <div class="section-1 sections scroll">
                                <div class="sample-artwork" style="text-align:center"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right-panel-heat col-md-5">
                    <div class="div-max scroll">
                        <h4>Quantity Info for Heat Transfer</h4>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-condensed qty-table-modal" id="heat_transfer_table">
                                <thead>
                                    <tr>
                                        <th>UVC TCO#</th>
                                        <th>Size</th>
                                        <th>Barcode</th>
                                        <th>Qty</th>
                                        <th style="display: flex;justify-content: center;><label style="float: left; line-height: 2">Extra %:</label> <input type="text"
                                                class="form-control input-sm extra_percentageheat"
                                                onkeypress="return checkNumbersOnly(event)"
                                                style="width:60px; float: left; margin-left: 5px;"></th>
                                    </tr>
                                </thead>
                                <tbody>` + tr + `<tr class="strong">
                                        <td class="text-right" colspan="3">Total:</td>
                                        <td>` + ttots_sum + `</td>
                                        <td class="text-center tot_qty_tableheat_fin">` + ttots + `</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> <button class="btn btn-primary pull-right input-sm left `+$save_button_class+`" type="submit"
                            id="save_heat_transfer_button" style="margin-left:10px">Save</button> <button type="button"
                            class="btn btn-danger hidden input-sm `+$cancel_button_class+`" id="cancel_heat_button"
                            style="float: right;margin-left:10px" onclick=cancelHeatDiv()>Cancel</button><button
                            class="btn btn-default pull-right input-sm" type="button" id="heat_transferclose">Close</button>
                    </div>
                </div>
                <div class="backdrop"></div>
            </form>
    `);

	//$('.left').removeClass('disabled')
    $('#heat_transferform .tot_qty_tableheat').keyup(function () {
        var tot_qty_tableheat_carton_final = 0;
        $('#heat_transferform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tableheat').val() != undefined  && $(this).find('.tot_qty_tableheat').val() != "") {
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
        $('#heat_transferform').validator('validate')
    })

    $("#clickmeheat_transfer").click(function () {
        // $('.sample-artwork').html("")
        $("#heat_transferform .backdrop").toggleClass('active');
        $("#heat_transferform .right-panel-heat").toggleClass('active');
        $('#heat_transferform .scalable').toggleClass('active');
        $("#heat_transferform .left-panel-heat").toggleClass('active');
    })

    $(".backdrop, #heat_transferclose").click(function () {
        $("#heat_transferform .left-panel-heat").removeClass('active');
        $("#heat_transferform .right-panel-heat").removeClass('active');
        $("#heat_transferform .backdrop").removeClass('active');
        $('#heat_transferform .scalable').removeClass('active');
    })

    treestructureheat_transfer(data.treeviewheat_transfer, "treeht", $template_id,orchestra_heat_transfer_orders,user_type);

    $('#heat_transferform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setheat_transferStatus(data.color_wcl,user_type,"create_order")
        }
    })
}

function specialtagmodal(data,user_type) {

    var orchestra_special_tag_orders = data.orchestra_special_tag_orders;

    $qu = 0;
    if (orchestra_special_tag_orders != "") {

      
        $template_id = orchestra_special_tag_orders[0].template_id;
        $exist_id = orchestra_special_tag_orders[0].id;
        
        var str=orchestra_special_tag_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
        $cancel_button_class="";
		$ocn_number_special = orchestra_special_tag_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
        $save_button_class="";
        $cancel_button_class="hidden";
		$ocn_number_special = "";
    }
    var st="";
	$c=0;

	$.each(data.color_wcl, function (index, value) {
		if (orchestra_special_tag_orders != "") {
			$special_tag_count=orchestra_special_tag_orders[$c].quantity;
		}else{
			 $special_tag_count="";
		}
		
        st += '<label for="edit_merchandiser_buyer" class="form_label">Final order qty for colour '+value.Color_Code+'</label><input class="form-control input-sm special_qty" value="' + $special_tag_count + '" placeholder="Special Tag Count" type="number" required>';
		$c++;
    });
    
    $('.top-content').append('<form id="specialtagform" data-toggle= "validator"><div class="left-panelorc col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="ocn_number"class="form_label">OCN Number</label><input type="text" id="ocn_number_special" value="'+$ocn_number_special+'" class="form-control input-sm" placeholder="OCN Number"></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="special_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label> <div style="margin-top: -45px;"><button type="button" style="background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="expand_special">Expand</button><button type="button" class="btn btn-xs btn-success" style="float: right;background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" id="collapse_special">Collapse</button></div> <div><input type="text" id="tree2_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree2" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-4"> <div class="div-max"> <h4>Quantity Info for Special Tag</h4> <hr> <div class="scroll"><div class="form-group has-feedback has-success">'+st+'<div class="help-block with-errors" style="margin-bottom: 20px;"></div></div><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_special_button" style="margin-left:10px">Save</button> <button type="button" class="btn hidden input-sm btn-danger '+$cancel_button_class+'" id="cancel_special_button" style="float: right;margin-left: 10px;" onclick=cancelSpecialDiv()>Cancel</button><button class="btn btn-default pull-right input-sm" type="button" id="specialclose">Close</button>  </div></div> </div>  <div class="backdrop"></div></form>');
	//$('.left').removeClass('disabled')
    $('#specialtagform .tot_qty_tablespecial').keyup(function () {
        var tot_qty_tablespecial_carton_final = 0;
        $('#specialtagform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablespecial').val() != undefined && $(this).find('.tot_qty_tablespecial').val() != "") {
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

    treestructurespecial(data.treeviewspecial, "tree2", $template_id,orchestra_special_tag_orders,user_type);

    $('#specialtagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSpecialTagStatus(data.color_wcl,user_type,"create_order")
        }
    })
}

function mainlabelmodal(data,user_type) {

    var orchestra_main_label_orders = data.orchestra_main_label_orders;
    
    $qu = 0;
    if (orchestra_main_label_orders != "") {

      
        $template_id = orchestra_main_label_orders[0].template_id;
        $exist_id = orchestra_main_label_orders[0].id;
        
        var str=orchestra_main_label_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
        $cancel_button_class="";
		$ocn_number_main = orchestra_main_label_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
        $save_button_class="";
        $cancel_button_class="hidden";
		$ocn_number_main = "";
    }
    
	var ml="";
	$c=0;
	$.each(data.color_wcl, function (index, value) {
		if (orchestra_main_label_orders != "") {
			$main_label_count=orchestra_main_label_orders[$c].quantity;
		}else{
			 $main_label_count="";
		}
		
        ml += '<label for="edit_merchandiser_buyer" class="form_label">Final order qty for colour '+value.Color_Code+'</label><input class="form-control input-sm main_label_qty" value="' + $main_label_count + '" placeholder="Main Label Count" type="number" required>';
		$c++;
    });

    var button_tree = '<button type="button" style="background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="expand_main">Expand</button><button type="button" style="float:right;background-color:#263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="collapse_main">Collapse</button>';
	

    $('.top-content').append('<form id="main_labelform" data-toggle= "validator"><div class="left-panelorc col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-5"><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="ocn_number"class="form_label">OCN Number</label><input type="text" id="ocn_number_main" value="'+$ocn_number_main+'" class="form-control input-sm" placeholder="OCN Number"></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="main_label_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label> <div style="margin-top: -45px;"> '+button_tree+' </div> <div><input type="text" id="treeml_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treeml" style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-7"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-4"> <div class="div-max"> <h4>Quantity Info for Main Label</h4><label style="color:#ff9800">This is only to place main label orders. Size label orders you want to place seperately</label> <hr> <div class="scroll"><div class="form-group has-feedback has-success" style="margin-bottom: 20px;">'+ml+'<div class="help-block with-errors"></div></div><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_main_label_button" style="margin-left:10px">Save</button> <button type="button" class="btn btn-danger hidden input-sm '+$cancel_button_class+'" id="cancel_main_button" style="float: right;margin-left: 10px;" onclick=cancelMainDiv()>Cancel</button><button class="btn btn-default pull-right input-sm" type="button" id="main_labelclose">Close</button>  </div></div> </div>  <div class="backdrop"></div></form>');

	//$('.left').removeClass('disabled')
    $('#main_labelform .tot_qty_table').keyup(function () {
        var tot_qty_table_carton_final = 0;
        $('#main_labelform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table').val() != undefined && $(this).find('.tot_qty_table').val() != "") {
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

    treestructuremain_label(data.treeviewmain_label, "treeml", $template_id,orchestra_main_label_orders,user_type);

    $('#main_labelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setMainLabelStatus(data.color_wcl,user_type,"create_order")
        }
    })
}

function sizelabelmodal(data,user_type) {

    var orchestra_size_label_orders = data.orchestra_size_label_orders;

    var tr = "";
    $qu = 0;
    if (orchestra_size_label_orders != "") {

      
        $template_id = orchestra_size_label_orders[0].template_id;
        $exist_id = orchestra_size_label_orders[0].id;
        $size_label_count=orchestra_size_label_orders[0].final_qty;
        
        var str=orchestra_size_label_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
        $cancel_button_class="";
		$ocn_number_size = orchestra_size_label_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $size_label_count="";
        $status="";
        $status_text="";
        $save_button_class="";
        $cancel_button_class="hidden";
		$ocn_number_size = "";
    }
    var ttots = 0;
    var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (orchestra_size_label_orders != "") {

            $size_label_count = orchestra_size_label_orders[$qu].quantity;
        } else {
            $size_label_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td style="display: flex;justify-content: center;"> <div class="form-group has-feedback has-success" style="width: 100%;"><input style="margin: 0 auto; width: 95px;" type="number" class="form-control  input-sm tot_qty_tablesize" data-required-error="Required" value="' + $size_label_count + '" required placeholder="Size Label Count"><div class="help-block with-errors min-div" style="font-size:10px"></div></div></td></tr>'; 
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

    $('.top-content').append(`<form id="size_labelform" data-toggle="validator">
    <div class="left-panel-heat col-md-7" id="HandTag"><input type="hidden" value="` + $exist_id + `" id="exist_id">
        <div class="div-max">
            <div class="col-md-5 ">
                <h4>Options</h4>
                <hr>
                <div class="form-group has-feedback has-success"><label for="ocn_number" class="form_label">OCN
                        Number</label><input type="text" id="ocn_number_size" value="`+$ocn_number_size+`"
                        class="form-control input-sm" placeholder="OCN Number"></div>
                <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                        class="form_label">Select Style</label><label id="size_label_template_error"
                        style="color:red;display: inherit;" class="hidden">Please select the template</label>
                        
                        <div style="margin-top: -45px;">
                            <button type="button" style="background-color: #263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="expand_size">Expand</button>
                            <button type="button" style="float:right;background-color:#263238;border: #263238;margin-top: 45px;margin-bottom: 5px;" class="btn btn-xs btn-success" id="collapse_size">Collapse</button>
                        </div>

                    <div><input type="text" id="treesl_q" value="" class="form-control input-sm"
                            placeholder="Search for Awesomeness"> </div>
                    <div class="scroll" id="treesl"
                        style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div>
                    <div class="help-block with-errors"></div>
                </div>
            </div>
            <div class="col-md-7">
                <h4>ArtWork</h4><span class="label label-`+$status+`"
                    style="position: absolute;top: 30px;left: 15px;">`+$status_text+`</span>
                <hr>
                <div class="section-1 sections scroll">
                    <div class="sample-artwork" style="text-align:center"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="right-panel-heat col-md-5">
        <div class="div-max scroll">
            <h4>Quantity Info for Size Label</h4>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-condensed qty-table-modal" id="size_label_table">
                    <thead>
                        <tr>
                            <th>UVC TCO#</th>
                            <th>Size</th>
                            <th>Barcode</th>
                            <th>Qty</th>
                            <th style="display: flex;justify-content: center;><label style="float: left; line-height: 2">Extra %:</label> <input type="text"
                                    class="form-control input-sm extra_percentagesize"
                                    onkeypress="return checkNumbersOnly(event)"
                                    style="width:60px; float: left; margin-left: 5px;"></th>
                        </tr>
                    </thead>
                    <tbody>` + tr + `<tr class="strong">
                            <td class="text-right" colspan="3">Total:</td>
                            <td>` + ttots_sum + `</td>
                            <td class="text-center tot_qty_tablesize_fin">` + ttots + `</td>
                        </tr>
                    </tbody>
                </table>
            </div> <button class="btn btn-primary pull-right input-sm left `+$save_button_class+`" type="submit"
                id="save_size_label_button" style="margin-left:10px">Save</button> <button type="button"
                class="btn input-sm hidden btn-danger `+$cancel_button_class+`" id="cancel_size_button"
                style="float: right;margin-left: 10px;" onclick=cancelSizeDiv()>Cancel</button><button
                class="btn btn-default pull-right input-sm" type="button" id="size_labelclose">Close</button>
        </div>
    </div>
    <div class="backdrop"></div>
</form>`);

	//$('.left').removeClass('disabled')
    $('#size_labelform .tot_qty_tablesize').keyup(function () {
        var tot_qty_tablesize_carton_final = 0;
        $('#size_labelform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablesize').val() != undefined && $(this).find('.tot_qty_tablesize').val() != "") {
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

        $('#size_labelform').validator('validate')
    })
	
	
    $("#clickmesize_label").click(function () {
        // $('.sample-artwork').html("")
        $("#size_labelform .backdrop").toggleClass('active');
        $("#size_labelform .right-panel-heat").toggleClass('active');
        $('#size_labelform .scalable').toggleClass('active');
        $("#size_labelform .left-panel-heat").toggleClass('active');
    })

    $(".backdrop, #size_labelclose").click(function () {
        $("#size_labelform .left-panel-heat").removeClass('active');
        $("#size_labelform .right-panel-heat").removeClass('active');
        $("#size_labelform .backdrop").removeClass('active');
        $('#size_labelform .scalable').removeClass('active');
    })

    treestructuresize_label(data.treeviewsize_label, "treesl", $template_id,orchestra_size_label_orders,user_type);

    $('#size_labelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSizeLabelStatus(data.color_wcl,user_type,"create_order")
        }
    })
}

function washcarelabelmodal(data,index_wcl_count,color_code,activity_label,order_details,orchestra_care_symbols,orchestra_composition,orchestra_countries,orchestra_care_instruction,orchestra_parts,treeviewwash_label,user_type,buyer_po_array) {
    var washing_option = "<option value=''>Select Option</option>";
    var whitening_option = "<option value=''>Select Option</option>";
    var dryer_option = "<option value=''>Select Option</option>";
    var ironing_option = "<option value=''>Select Option</option>";
    var cleaning_option = "<option value=''>Select Option</option>";
    var made_option = "<option value=''>Select Option</option>";
    var instruction_option = "";
    var parts_option = "<option>Select Option</option>";
    var composition_option = "<option>Select Option</option>";

    $.each(orchestra_countries, function (index, value) {
        made_option += '<option data-id="' + value.id + '"  data-french="' + value.french + '" data-english="' + value.english + '" data-spanish="' + value.spanish + '" data-portuguese="' + value.portuguese + '" data-italian="' + value.italian + '" data-dutch="' + value.dutch + '" data-german="' + value.german + '" data-arabic="' + value.arabic + '" data-greek="' + value.greek + '" value="' + value.id + '">' + value.english + '</option>';
    });
    $.each(orchestra_care_instruction, function (index, value) {
		if(value.english.length > 31)
		{
			var txtEng = value.english.slice(0,30)+"...";
		}
		else
		{
			var txtEng = value.english;
		}
        instruction_option += '<option data-id="' + value.id + '" data-french="' + value.french + '" title="'+value.english+'" data-english="' + value.english + '" data-spanish="' + value.spanish + '" data-portuguese="' + value.portuguese + '" data-italian="' + value.italian + '" data-dutch="' + value.dutch + '" data-german="' + value.german  + '" data-arabic="' + value.arabic + '" data-greek="' + value.greek + '" value="' + value.id + '">' + txtEng + '</option>';
    });
    $.each(orchestra_parts, function (index, value) {
        parts_option += '<option data-french="' + value.french + '" data-english="' + value.english + '" data-spanish="' + value.spanish + '" data-portuguese="' + value.portuguese + '" data-italian="' + value.italian + '" data-dutch="' + value.dutch + '" data-german="' + value.german + '" data-arabic="' + value.arabic + '" data-greek="' + value.greek + '" value="' + value.id + '">' + value.english + '</option>';
    });
    $.each(orchestra_composition, function (index, value) {
        composition_option += '<option data-french="' + value.french + '" data-english="' + value.english + '" data-spanish="' + value.spanish + '" data-portuguese="' + value.portuguese + '" data-italian="' + value.italian + '" data-dutch="' + value.dutch + '" data-german="' + value.german  + '" data-arabic="' + value.arabic + '" data-greek="' + value.greek + '" value="' + value.id + '">' + value.english + '</option>';
    });
    $.each(orchestra_care_symbols, function (index, value) {
		//console.log(value.care_instruction.length)
		if(value.care_instruction.length > 15)
		{
			var carEng = value.care_instruction.slice(0,15)+"...";
		}
		else
		{
			var carEng = value.care_instruction;
		}
		
        if (value.category_id == 1) {
            washing_option += "<option title='"+value.care_instruction+"' data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + carEng + "</option>";
        } else if (value.category_id == 2) {
            whitening_option += "<option title='"+value.care_instruction+"' data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + "  value=" + value.id + ">" + carEng + "</option>";
        } else if (value.category_id == 3) {
            dryer_option += "<option title='"+value.care_instruction+"' data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + carEng + "</option>";
        } else if (value.category_id == 4) {
            ironing_option += "<option title='"+value.care_instruction+"' data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + carEng + "</option>";
        } else if (value.category_id == 5) {
            cleaning_option += "<option title='"+value.care_instruction+"' data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + carEng + "</option>";
        }

    });

    var tr = "";
    $qu = 0;
    if (data != "") {
        $template_id = data.template_id;
        $exist_id = data.id;
        $wcl_label_count=data.final_qty;
        
        var str=data.status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        if(data.merchandiser_comment != null && data.merchandiser_comment !="")
        {

            $comment = "<h4>Comments :</h4><p id='comment_section_"+index_wcl_count+"'>"+data.merchandiser_comment+"</p><hr>";
        }
        else
        {

            $comment = "<h4>Comments :</h4><p id='comment_section_"+index_wcl_count+"'>No Comments</p><hr>";
        }
        $save_button_class="hidden";
        $print_button_class = "";
        $cancel_button_class="";
		$ocn_number_wcl = data.ocn_number;
        $disable_option_select = "disabled";
        $wcl_name=data.template_name;
        $no_data_comp = "";

    } else {
     
        $template_id = "";
        $exist_id = "";
        $wcl_label_count="";
        $status="";
        $status_text="";
        $comment = "";
        $save_button_class="";
        $print_button_class = "hidden";
        $cancel_button_class="hidden";
		$ocn_number_wcl = "";
        $disable_option_select = "";
        $wcl_name="";
        $no_data_comp = `
        <tr id="no_data_comp`+index_wcl_count+`">
            <td colspan="3">No data Found</td>
        </tr>`;
    }
    var ttots = 0;
    var ttots_sum = 0;
    $.each(order_details, function (index, value) {
        if(color_code == value.Color_Code)
		{
            if (data != "") {
                $wcl_label_count = data.qty[$qu].quantity;
            } else {
                $wcl_label_count = "";
            }
            tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="color_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td style="display: flex;justify-content: center;"> <div class="form-group has-feedback has-success" style="width: 100%;"><input style="margin: 0 auto; width: 95px;" type="number" class="form-control  input-sm tot_qty_tablewcl" data-required-error="Required" value="' + $wcl_label_count + '" required placeholder="WCL Count"><div class="help-block with-errors min-div" style="font-size:10px"></div></div></td></tr>'; 
            ttots += parseInt($wcl_label_count);
            ttots_sum += parseInt(value.Sum);
            $qu++;
        }
    });
	
	if(isNaN(ttots))
    {
        ttots=0;
    }
    else
    {
        ttots=ttots;
    }


    if(treeviewwash_label[0].parent_id != "null")
    {
        var wcl_template = "<option value=''>Select Option</option>";

        $.each(treeviewwash_label, function (index, value) {
            wcl_template += '<option data-template="'+value.template_file+'" value="' + value.id + '">' + value.text + '</option>'
        });

        var wcl_template_div =`<div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                    class="form_label">Select Template</label><select `+$disable_option_select+`
                                    data-required-error="Required" data-live-search="true"
                                    class="form-control selectpicker input-sm wcl_template` + index_wcl_count + `" required>` +
                                    wcl_template + `</select>
                                <div class="help-block with-errors"></div>
                            </div>`
        var split_template = "";
        var wcl_sample_front = ""
        var wcl_sample_back = ""
        var wcl_sample_middle = ""
        var wcl_sample_static = ""
    }
    else
    {
        var wcl_template = "<option value=''>Select Option</option>";
        
        $.each(treeviewwash_label, function (index, value) {
            wcl_template += '<option data-template="'+value.template_file+'" value="' + value.id + '" selected>' + value.text + '</option>'
        });

        var wcl_template_div =`<div style="display:none" class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                class="form_label">Select Template</label><select `+$disable_option_select+`
                                data-required-error="Required" data-live-search="true"
                                class="form-control selectpicker input-sm wcl_template` + index_wcl_count + `" required>` +
                                wcl_template + `</select>
                            <div class="help-block with-errors"></div>
                        </div>`;
        var split_template = treeviewwash_label[0].template_file.split(',')

        var wcl_sample_front = '<object id="front_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template[0] + '" type="image/svg+xml"></object>'
        var wcl_sample_back = '<object id="back_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template[1] + '" type="image/svg+xml"></object>'
        var wcl_sample_middle = '<object id="middle_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template[2] + '" type="image/svg+xml"></object>'
        var wcl_sample_static = '<object id="static_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template[3] + '" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden;z-index: -1;position: absolute;"><svg id="ean-13"></svg></div>'

    }

    var buyer_po = "";
    
    $.each(buyer_po_array, function (index,value) {
            buyer_po += value.BuyerPONo + " | ";
    });

    $('.top-content').append(`
            <form id="washcarelabelform` + index_wcl_count + `" data-toggle="validator"><input type="hidden" value="` + $exist_id + `"
                id="exist_id` + index_wcl_count + `"><input type="hidden" value="` + color_code + `" id="color_id` + index_wcl_count + `">
                <div class="wcl-sample-front`+index_wcl_count+`">`+wcl_sample_front+`</div>
                <div class="wcl-sample-back`+index_wcl_count+`">`+wcl_sample_back+`</div>
                <div class="wcl-sample-middle`+index_wcl_count+`">`+wcl_sample_middle+`</div>
                <div class="wcl-sample-static`+index_wcl_count+`">`+wcl_sample_static+`</div>
                <div class="left-panel-heat col-md-7">
                <div class="div-max">
                    <div class="col-md-4">
                        <h4>Options</h4>
                        <hr>
                        <div class="scroll">
                            <div class="form-group has-feedback has-success"><label for="ocn_number" class="form_label">OCN Number</label>
                                <input type="text" id="ocn_number_wash` + index_wcl_count + `" `+$disable_option_select+` value="`+$ocn_number_wcl+`" class="form-control input-sm" placeholder="OCN Number">
                            </div>
                            <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                            class="form_label">WCL Name</label><input type="text"
                            data-required-error="Required" `+$disable_option_select+` class="form-control input-sm"
                            required="" placeholder="WCL Name" value="` + $wcl_name + `" id="wcl_name` + index_wcl_count + `">
                        <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                class="form_label">Select Washing</label><select `+$disable_option_select+`
                                data-required-error="Required" data-live-search="true"
                                class="form-control selectpicker input-sm washing_select` + index_wcl_count + `" required>` +
                                washing_option + `</select>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                class="form_label">Select Whitening</label><select `+$disable_option_select+`
                                data-required-error="Required" data-live-search="true"
                                class="form-control selectpicker input-sm whitening_select` + index_wcl_count + `" required>` +
                                whitening_option + `</select>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                class="form_label">Select Dryer</label><select `+$disable_option_select+`
                                data-required-error="Required" data-live-search="true"
                                class="form-control selectpicker input-sm dryer_select` + index_wcl_count + `" required>` +
                                dryer_option + `</select>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                class="form_label">Select Ironing</label><select `+$disable_option_select+`
                                data-required-error="Required" data-live-search="true"
                                class="form-control selectpicker input-sm ironing_select` + index_wcl_count + `" required>` +
                                ironing_option + `</select>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                                class="form_label">Select Cleaning</label><select `+$disable_option_select+`
                                data-required-error="Required" data-live-search="true"
                                class="form-control selectpicker input-sm cleaning_select` + index_wcl_count + `" required>` +
                                cleaning_option + `</select>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                            class="form_label">Select Made In</label><select `+$disable_option_select+`
                            data-required-error="Required" data-live-search="true"
                            class="form-control selectpicker input-sm made_in_select` + index_wcl_count + `" required>` +
                            made_option + `</select>
                        <div class="help-block with-errors"></div>
                    </div>
                    `+wcl_template_div+`
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4>Care Instructions & Composition</h4>
                        <span class="label right_wcl_status_` + index_wcl_count + ` label-` + $status + `" style="top:13px;right:15px;padding:3px 10px;position: absolute;">` + $status_text + `</span>
                        <hr>
                        <div class="scroll">
                        <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"
                            class="form_label">Select Care Instructions</label><select `+$disable_option_select+`
                            data-required-error="Required" data-live-search="true" data-done-button="true"
                            class="form-control selectpicker input-sm care_instruction_select` + index_wcl_count + `" required
                            multiple>` + instruction_option + `</select>
                        <div class="help-block with-errors"></div>
                    </div>
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-6">
                                    <label>Compositions</label> <select `+$disable_option_select+`
                                        class="selectpicker composition_select` + index_wcl_count + `" data-live-search="true"> ` +
                                        composition_option + ` </select>
                                    <p class="hidden" style="color:red;float:right" id="composition_error_required` + index_wcl_count + `">
                                        Please Select Composition</p>
                                </div>

                                <div class="col-md-3">
                                <label>Percentage</label> 
                                
                                <input `+$disable_option_select+` type="number" min="1" max="100" class="form-control input-sm composition_percent` + index_wcl_count + `">

                                <p class="hidden" style="color:red;text-align:right" id="percentage_error_required` + index_wcl_count + `">Please Enter Percentage</p> 
                                </div>
                                <div class="col-md-3" style="margin-top: 22px;">
                                <button `+$disable_option_select+` type="button"
                                class="btn  btn-primary pull-right input-sm add_to_list` + index_wcl_count + `"
                                onclick="add_to_list(` + index_wcl_count + `)">Add To List</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped table-hover table-condensed">
                                        <thead>
                                            <th>Composition</th>
                                            <th>Percentage</th>
                                            <th>Actions</th>
                                        </thead>
                                        <tbody id="composition_table_qty` + index_wcl_count + `">
                                        <tr id="no_data`+index_wcl_count+`">
                                            <td colspan="3">No data Found</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12" style="margin-top: 10px;margin-bottom: 10px;">
                                <button `+$disable_option_select+` type="button"
                                class="btn  btn-primary pull-right input-sm add_to_composition` + index_wcl_count + `"
                                onclick="add_to_composition(` + index_wcl_count + `)" disabled>Add To Composition Set</button>
                                <p id="wash_composition_error` + index_wcl_count + `" class="hidden" style="color:red;margin-right: 10px;">Please add to composition set</p>
                                </div>
                                <div class="col-md-12">
                                <hr>
                                <h4>Compositions :</h4>
                                    <table class="table table-bordered table-striped table-hover table-condensed">
                                        <thead>
                                            <th>Composition Name</th>
                                            <th>Parts</th>
                                            <th>Actions</th>
                                        </thead>
                                        <tbody id="composition_remove_qty` + index_wcl_count + `">
                                        `+$no_data_comp+`
                                        </tbody>
                                    </table>
                                    <div><p class="hidden composition_error_hidden" style="color:red;text-align: right;margin-right: 10px;">Please add parts to the composition</p></div>

                                    <div><button `+$disable_option_select+` class="btn  btn-primary pull-right input-sm add_to_page` + index_wcl_count + `"  style="position:relative;" type="button" onclick="add_parts(` + index_wcl_count + `)">Add Parts</button>
                                    <p id="wash_composition_page_error` + index_wcl_count + `" class="hidden" style="color:red;text-align: right;margin-right: 10px;">Please add composition and parts</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-panel-heat col-md-5">
                <div class="div-max scroll">
                <h4 style="display: inline-block;">Quantity Info for <span class="washlabelnameqty` + index_wcl_count + `"> WashCare
                    Label - </span> `+color_code+`</h4>
                    <h5 class="poc_number"><strong>POs for this UVC : </strong><span id="poNo">` + buyer_po + `  </span></h5>
                    <hr>
                    <table class="table table-bordered table-striped table-hover table-condensed table-responsive qty-table-modal" id="wash_care_label_table_`+index_wcl_count+`" style="font-size:11px">
                        <thead>
                        <tr>
                            <th>UVC TCO#</th>
                            <th>Size</th>
                            <th>Barcode</th>
                            <th>Qty</th>
                            <th style="display: flex;justify-content: center;><label style="float: left; line-height: 2">Extra %:</label> <input type="text"
                                    class="form-control input-sm extra_percentagewash` + index_wcl_count + `"
                                    onkeypress="return checkNumbersOnly(event)"
                                    style="width:60px; float: left; margin-left: 5px;"></th>
                        </tr>
                    </thead>
                    <tbody>` + tr + `<tr class="strong">
                            <td class="text-right" colspan="3">Total:</td>
                            <td>` + ttots_sum + `</td>
                            <td class="text-center tot_qty_tablewash_fin` + index_wcl_count + `">` + ttots + `</td>
                        </tr>
                    </tbody>
                    </table>
                    <hr>`+ $comment +` <button class="btn btn-primary pull-right input-sm left `+$save_button_class+`"
                        type="submit" id="save_wash_button_`+index_wcl_count+`" style="margin-left:10px">Save</button> <button
                        type="button" class="btn input-sm btn-danger `+$print_button_class+`" id="cancel_wash_button_`+index_wcl_count+`"
                        style="float: right;margin-left: 10px;"
                        onclick=cancelWashDiv("`+index_wcl_count+`","`+user_type+`")>Cancel</button><button
                        class="btn btn-default pull-right input-sm" id="wcl_labelclose_`+index_wcl_count+`" type="button">Close</button> <button
                        type="button" class="btn btn-primary `+$print_button_class+`" id="print_wash_button_`+index_wcl_count+`"
                        onclick=printWashDivOs("front_modal_`+index_wcl_count+`","back_modal_`+index_wcl_count+`","middle_modal_`+index_wcl_count+`","static_modal_`+index_wcl_count+`","`+ order_details[0].UVC+`","","admin","","orchestra_wash_care_label_orders","print","co","`+index_wcl_count+`")>Print</button>
                        <div class="parts_none`+index_wcl_count+`" style="display:none"><select class="form-control">` + parts_option + `</select></div>
                </div>
            </div>
            <div class="backdrop"></div>
        </form>
    `);

    $("#clickmewcl_"+index_wcl_count).click(function () {

        if( $('.merchandisers option:selected').val() != "")
        {
            $("#washcarelabelform"+index_wcl_count+" .backdrop").toggleClass('active');
            $("#washcarelabelform"+index_wcl_count+" .right-panel-heat").toggleClass('active');
            $("#washcarelabelform"+index_wcl_count+" .scalable").toggleClass('active');
            $("#washcarelabelform"+index_wcl_count+" .left-panel-heat").toggleClass('active');
        }
        else
        {
            $('.snackbar_span').html('Please Select Merchandiser');
            showSnackbar();
        }
        
    })

    $(".backdrop, #wcl_labelclose_"+index_wcl_count).click(function () {
        $("#washcarelabelform"+index_wcl_count+" .left-panel-heat").removeClass('active');
        $("#washcarelabelform"+index_wcl_count+" .right-panel-heat").removeClass('active');
        $("#washcarelabelform"+index_wcl_count+" .backdrop").removeClass('active');
        $("#washcarelabelform"+index_wcl_count+" .scalable").removeClass('active');
    })

    if (data != "") {
        $('.tot_qty_tablewcl').attr('disabled','disabled')
        $('.extra_percentagewash'+index_wcl_count).attr('disabled','disabled')

        $('.washing_select' + index_wcl_count).selectpicker('val', data.washing_id);
        $('.whitening_select' + index_wcl_count).selectpicker('val', data.whitening_id);
        $('.dryer_select' + index_wcl_count).selectpicker('val', data.dryer_id);
        $('.ironing_select' + index_wcl_count).selectpicker('val', data.ironing_id);
        $('.cleaning_select' + index_wcl_count).selectpicker('val', data.cleaning_id);
        $('.made_in_select' + index_wcl_count).selectpicker('val', data.made_in_id);
        $('.wcl_template' + index_wcl_count).selectpicker('val', data.template_id);
        $('.care_instruction_select' + index_wcl_count).selectpicker('val', data.care_instruction);


        var template_file_wcl = $('.wcl_template'+index_wcl_count).find(':selected').attr('data-template')
        var split_template_wcl = template_file_wcl.split(',')
        
        $('.wcl-sample-front'+index_wcl_count).html('<object id="front_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template_wcl[0] + '" type="image/svg+xml"></object>')
        $('.wcl-sample-back'+index_wcl_count).html('<object id="back_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template_wcl[1] + '" type="image/svg+xml"></object>')
        $('.wcl-sample-middle'+index_wcl_count).html('<object id="middle_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template_wcl[2] + '" type="image/svg+xml"></object>')
        $('.wcl-sample-static'+index_wcl_count).html('<object id="static_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template_wcl[3] + '" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden;z-index: -1;position: absolute;"><svg id="ean-13"></svg></div>')

        var s_no =1;
        var page_id = 0;

        $.each(data.composition, function (index, value) {
            if (value.page_id != page_id) {

                var comps="";
                var comps_info="<label style='font-size:12px;color:black'>Compositions : </label>";

                $.each(data.composition, function (index1, value1) {
                    if(value.page_id == value1.page_id)
                    {
                        if(comps != "")
                        {
                            comps+=","+value1.french+"_"+value1.english+"_"+value1.spanish+"_"+value1.portuguese+"_"+value1.italian+"_"+value1.dutch+"_"+value1.german+"_"+value1.arabic+"_"+value1.greek+"_"+value1.percentage;
                            comps_info += "<p style='color:black'>"+value1.english+" : "+value1.percentage+" % </p>";
                        }
                        else
                        {
                            comps+=value1.french+"_"+value1.english+"_"+value1.spanish+"_"+value1.portuguese+"_"+value1.italian+"_"+value1.dutch+"_"+value1.german+"_"+value1.arabic+"_"+value1.greek+"_"+value1.percentage;
                            comps_info += "<p style='color:black'>"+value1.english+" : "+value1.percentage+" %</p>";
                        }
                    }
                });
                
                add_page_composition += 1;

                $('#composition_remove_qty' + index_wcl_count).append('<tr id="' + add_page_composition + '"><td><label id="label_s_no_'+add_page_composition+'">'+ s_no +'</label>. Composition <i class="fa fa-info-circle" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="top" data-content="'+comps_info+'" style="cursor:pointer"></i></td><td><input type="hidden" value="'+comps+'" class="compositions_hidden"><select class="form-control select_parts'+add_page_composition+'">' + parts_option + '</select></td><td><a style="cursor:default;pointer-events:none" onclick="deleteComposition(this,' + index_wcl_count + ')"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>')
                
                if(value.parts_id != "")
                {
                    $('.select_parts'+add_page_composition).val(value.parts_id)
                    $('.select_parts'+add_page_composition).attr('disabled','disabled')
                }
                
                var washcomp=[];

                $('#composition_remove_qty' + index_wcl_count + ' tr').each(function () {
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
                localStorage.setItem('wash_composition_final'+index_wcl_count, wash_finals);
                s_no++;
            }
            page_id = value.page_id;
        });
        $('[data-toggle="popover"]').popover({
            html: true
        });
    }

    selectpicker()

    $('#washcarelabelform' + index_wcl_count + ' .tot_qty_tablewcl').keyup(function () {
        var tot_qty_tablewash_carton_final = 0;
        $('#washcarelabelform' + index_wcl_count + ' tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablewcl').val() != undefined) {
                tot_qty_tablewash_carton_final += parseInt($(this).find('.tot_qty_tablewcl').val());
            }
        })
        $('#washcarelabelform' + index_wcl_count + ' .tot_qty_tablewash_fin'+ index_wcl_count).text(tot_qty_tablewash_carton_final);
    });
    

    $('.extra_percentagewash'+index_wcl_count).keyup(function () {
        
        var tot_qty_tablewash_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablewcl').val(tot_qty);
                if ($(this).find('.tot_qty_tablewcl').val() != undefined) {
                    tot_qty_tablewash_carton_final += parseInt($(this).find('.tot_qty_tablewcl').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablewash_fin'+ index_wcl_count).text(tot_qty_tablewash_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablewcl').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablewash_fin'+ index_wcl_count).text("0");
        }

		$('#wash_care_label_table_'+index_wcl_count).validator('validate')
    })

    if(JSON.parse(localStorage.getItem('care_instruction_select'+index_wcl_count)) != null)
    {
        var care_instruction_select_option = JSON.parse(localStorage.getItem('care_instruction_select'+index_wcl_count))
    }
    else
    {
        var care_instruction_select_option = []
    }

    $('.care_instruction_select' + index_wcl_count + '.bootstrap-select').on('changed.bs.select', function (e, clickedIndex, newValue, oldValue) {

        var hidden_id = $(this).find('option').eq(clickedIndex).val();

        if(care_instruction_select_option.includes(hidden_id) != true)
        {
            care_instruction_select_option.push(hidden_id)
        }
        else
        {
            var index_cisop = care_instruction_select_option.indexOf(hidden_id);
            if (index_cisop !== -1) {
                care_instruction_select_option.splice(index_cisop, 1);
            }
        }
                                            
        localStorage.setItem('care_instruction_select'+index_wcl_count, JSON.stringify(care_instruction_select_option));
        
    });

    $('.wcl_template' + index_wcl_count).on('changed.bs.select', function (e, clickedIndex, newValue, oldValue) {
        

        var template_file_wcl = $(this).find(':selected').attr('data-template')
        var split_template_wcl = template_file_wcl.split(',')
        
        $('.wcl-sample-front'+index_wcl_count).html('<object id="front_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template_wcl[0] + '" type="image/svg+xml"></object>')
        $('.wcl-sample-back'+index_wcl_count).html('<object id="back_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template_wcl[1] + '" type="image/svg+xml"></object>')
        $('.wcl-sample-middle'+index_wcl_count).html('<object id="middle_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template_wcl[2] + '" type="image/svg+xml"></object>')
        $('.wcl-sample-static'+index_wcl_count).html('<object id="static_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template_wcl[3] + '" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden;z-index: -1;position: absolute;"><svg id="ean-13"></svg></div>')

    });
    

    $('#washcarelabelform' + index_wcl_count).validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setWashCareLabelStatus(index_wcl_count,user_type,"create_order")
        }
    })

}


function clonewashcarelabels(index_wcl_count,color_code,user_type,activity_label,order_details,orchestra_care_symbols,orchestra_composition,orchestra_countries,orchestra_care_instruction,orchestra_parts,index,treeviewwash_label,user_type,buyer_po_array) 
{

    var wcl_count = index_wcl_count -1; 

    if($('.wcl_status_'+wcl_count+' span').text() == "New Order" || $('.wcl_status_'+wcl_count+' span').text() == "Clone Order")
    {
        $('.snackbar_span').html('Please place the order you have created before creating another order.');
        showSnackbar();
    }
    else
    {
        localStorage.setItem('care_instruction_select'+index_wcl_count, localStorage.getItem('care_instruction_select'+index));
        $('.wcl_orders_card').append(`
                <div class="artwork-wrapper">
                    <div class="artwork-card wcl_status_`+index_wcl_count+`" data-color="`+color_code+`" id="cloneWashCareLabel_`+color_code+`">
                    <div class="main-data">
                        <span class="label label-Cloned">Clone Order</span>
                        <h3 class="wcl_name_card_`+index_wcl_count+`">Wash Care Label</h3>
                        <p>Color Code - `+color_code+`</p>
                        <img src="` + base_url + `/images/wcl_default.png" class="img-responsive">
                        </div>
                        <div class="actions">
                        <ul>
                            <li data-toggle="tooltip" data-placement="top" title="Edit" id="clickmewcl_`+index_wcl_count+`" style="cursor: pointer;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</li>
                            <li data-toggle="tooltip" data-placement="top" title="Delete" data-id="`+index_wcl_count+`" id="deletewcl_`+index_wcl_count+`" style="cursor: pointer; margin-left:-3px"><i class="fa fa-trash" aria-hidden="true"></i> Delete</li>
                            <li class="hidden" data-toggle="tooltip" data-id="`+index_wcl_count+`" data-color="`+color_code+`"  data-placement="top" title="Clone" id="clone_washcare_label_card`+index_wcl_count+`" style="cursor: pointer; margin-left:-3px"><i class="fa fa-clone" aria-hidden="true"></i> Clone</li>
                        </ul>
                        </div>
                        </div>
                    
                </div>
        `)

        $('#deletewcl_' + index_wcl_count).on('click', function (e) {
            
            $('#washcarelabelform' + $(this).data('id')).remove()
                
            $(this).parent().parent().parent().parent().remove();

            wash_care_count--;

        });

        washcarelabelmodal("",index_wcl_count,color_code,activity_label,order_details,orchestra_care_symbols,orchestra_composition,orchestra_countries,orchestra_care_instruction,orchestra_parts,treeviewwash_label,user_type,buyer_po_array)

        $('#washcarelabelform'+index_wcl_count).find('#wcl_name'+index_wcl_count).val($('#washcarelabelform'+index).find('#wcl_name'+index).val())
        $('#washcarelabelform'+index_wcl_count).find('#ocn_number_wash'+index_wcl_count).val($('#washcarelabelform'+index).find('#ocn_number_wash'+index).val())
        
        
        $('.washing_select'+index_wcl_count).selectpicker('val',$('.washing_select'+index).selectpicker('val'));
        $('.whitening_select'+index_wcl_count).selectpicker('val',$('.whitening_select'+index).selectpicker('val'));
        $('.dryer_select'+index_wcl_count).selectpicker('val',$('.dryer_select'+index).selectpicker('val'));
        $('.ironing_select'+index_wcl_count).selectpicker('val',$('.ironing_select'+index).selectpicker('val'));
        $('.cleaning_select'+index_wcl_count).selectpicker('val',$('.cleaning_select'+index).selectpicker('val'));
        $('.made_in_select'+index_wcl_count).selectpicker('val',$('.made_in_select'+index).selectpicker('val'));
        $('.wcl_template'+index_wcl_count).selectpicker('val',$('.wcl_template'+index).selectpicker('val'));

        var template_file_wcl = $('.wcl_template'+index).find(':selected').attr('data-template')
        var split_template_wcl = template_file_wcl.split(',')
        
        $('.wcl-sample-front'+index_wcl_count).html('<object id="front_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template_wcl[0] + '" type="image/svg+xml"></object>')
        $('.wcl-sample-back'+index_wcl_count).html('<object id="back_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template_wcl[1] + '" type="image/svg+xml"></object>')
        $('.wcl-sample-middle'+index_wcl_count).html('<object id="middle_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template_wcl[2] + '" type="image/svg+xml"></object>')
        $('.wcl-sample-static'+index_wcl_count).html('<object id="static_modal_'+index_wcl_count+'" style="opacity:0;width:0px;height:0px" class="center-block svg2" data="' + split_template_wcl[3] + '" type="image/svg+xml"></object><div id="svgdiv" style="visibility:hidden;z-index: -1;position: absolute;"><svg id="ean-13"></svg></div>')

        $('.care_instruction_select'+index_wcl_count).selectpicker('val',$('.care_instruction_select'+index).selectpicker('val'));

        $('#composition_remove_qty'+index_wcl_count).html($('#composition_remove_qty'+index).html())

        $('.add_to_composition'+index_wcl_count).prop('disabled',true)

        $('#composition_remove_qty'+index+' tr').each(function (ind,val) {
            var values= $(this).find('select').find(':selected').val()
            var v= $(this).attr('id');
            $('#composition_remove_qty'+index_wcl_count+' tr').each(function (inds,vals) {
                if($(this).attr('id') == v)
                {
                    $(this).find('select').val(values)
                    $(this).find('select').removeAttr('disabled')
                    $(this).find('a').removeAttr('onclick')
                    $(this).find('a').removeAttr('style')
                    $(this).find('a').css('cursor','pointer')
                    $(this).find('a').attr('onclick','deleteComposition(this,'+index_wcl_count+')')
                }
            });
        }); 
        
        $('#washcarelabelform'+index+' table tbody tr').each(function (ind,val) {
            var values= $(this).find('.tot_qty_tablewcl').val()
            var v= $(this).find('.uvc_tco').text();
            $('#washcarelabelform'+index_wcl_count+' table tbody tr').each(function (inds,vals) {
                if($(this).find('.uvc_tco').text() == v)
                {
                    $(this).find('.tot_qty_tablewcl').val(values)
                    
                }
            });
        });

        $('#washcarelabelform'+index_wcl_count+' table tbody').find('.tot_qty_tablewash_fin'+index_wcl_count).text($('#washcarelabelform'+index+' table tbody').find('.tot_qty_tablewash_fin'+index).text())

        var washcomp=[];
        $('#composition_remove_qty' + index_wcl_count + ' tr').each(function () {
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

        localStorage.setItem('wash_composition_final'+index_wcl_count, wash_finals);

        $('[data-toggle="tooltip"]').tooltip()

        $('[data-toggle="popover"]').popover({
            html: true
        });

        $('#clone_washcare_label_card'+index_wcl_count).on('click', function (e) {

            clonewashcarelabels(wash_care_count, $(this).data('color'), user_type,activity_label, order_details, orchestra_care_symbols, orchestra_composition, orchestra_countries, orchestra_care_instruction, orchestra_parts, $(this).data('id'),treeviewwash_label,user_type,buyer_po_array);

        });
        
        $('#washcarelabelform'+index_wcl_count).validator('validate')


        wash_care_count++;   
    }

}

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
			$('#composition_table_qty' + dataid).append(`<tr><td class="comp"><input type="hidden" class="list_comp" value="` + $(".composition_select" + dataid + " option:selected").attr("data-french") + `_` + $(".composition_select" + dataid + " option:selected").attr("data-english") + `_` + $(".composition_select" + dataid + " option:selected").attr("data-spanish") + `_` + $(".composition_select" + dataid + " option:selected").attr("data-portuguese") + `_` + $(".composition_select" + dataid + " option:selected").attr("data-italian") + `_` + $(".composition_select" + dataid + " option:selected").attr("data-dutch") + `_` + $(".composition_select" + dataid + " option:selected").attr("data-german") + `_` + $(".composition_select" + dataid + " option:selected").attr("data-arabic") + `_` + $(".composition_select" + dataid + " option:selected").attr("data-greek") + `_` + parseInt($(".composition_percent" + dataid).val()) + `">` + $(".composition_select" + dataid + " option:selected").text() + `</td><td class="percent">` + parseInt($(".composition_percent" + dataid).val()) + `</td><td><a style="cursor:pointer" onclick="deleteCompPer(this,'` + dataid + `')"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>`)

            if($('#composition_table_qty' + dataid).find('#no_data'+ dataid).html() != "")
            {
                $('#composition_table_qty' + dataid).find('#no_data'+ dataid).remove()
            }

		} else {
			composition_percentage_global -= parseInt($('.composition_percent' + dataid).val());
            $('.snackbar_span').html('Percentage should not be greater than 100.');
            showSnackbar();
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

    if($('#composition_remove_qty' + dataid).find('#no_data_comp'+ dataid).html() != "")
    {
        $('#composition_remove_qty' + dataid).find('#no_data_comp'+ dataid).remove()
    }
    
    composition_percentage_global = 0;
    var a = [];

    var comp_wash_final = JSON.parse(localStorage.getItem('wash_composition_final'+dataid));

    if(comp_wash_final == null)
    {
        comp_wash_final = []
    }

    var comp_split_final = [];
    var comps_info="<label style='font-size:12px;color:black'>Compositions : </label>";

    $('#composition_table_qty' + dataid + ' tr').each(function () {
        var comp = $(this).find('.list_comp').val();
        var comp1 = comp.split('_');
        a.push(comp1[0]+"_"+comp1[1]+"_"+comp1[2]+"_"+comp1[3]+"_"+comp1[4]+"_"+comp1[5]+"_"+comp1[6]+"_"+comp1[7]+"_"+comp1[8]+"_"+comp1[9])
        comps_info += "<p style='color:black'>"+comp1[1]+" : "+comp1[9]+" % </p>";
        comp_split_final.push("Select Option_"+comp1[1]+"_"+comp1[9])
    })

    comp_wash_final.push(comp_split_final)

    localStorage.setItem('wash_composition_final'+dataid, JSON.stringify(comp_wash_final));

    comps = a.join(',');

    if($('#composition_remove_qty'+dataid+' tr:last td:first label').text() != null && $('#composition_remove_qty'+dataid+' tr:last td:first label').text() != "")
    {
        var s_no = parseInt($('#composition_remove_qty'+dataid+' tr:last td:first label').text()) + 1;
    }
    else
    {
        var s_no = 1;
    }


    $('#composition_remove_qty' + dataid).append('<tr id="' + add_page_composition + '"><td><label id="label_s_no_'+add_page_composition+'">'+ s_no +'</label>. Composition <i class="fa fa-info-circle" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="top" data-content="'+comps_info+'" style="cursor:pointer"></i></td><td><input type="hidden" value="'+comps+'" class="compositions_hidden">'+$('.parts_none'+ dataid).html()+'</td><td><a style="cursor:pointer" onclick="deleteComposition(this,' + dataid + ')"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>')

    $('#composition_table_qty' + dataid).html(`<tr id="no_data`+dataid+`"><td colspan="3">No data Found</td></tr>`)

    $('[data-toggle="popover"]').popover({
        html: true
    });

    $(".add_to_list" + dataid).prop("disabled", false);
    $('.composition_select' + dataid).prop('disabled', false)
    $('.composition_percent' + dataid).prop('disabled', false)
    $('.parts_select' + dataid).prop('disabled', false)
    $('.add_to_composition' + dataid).attr('disabled', 'disabled')
    $('.composition_percent' + dataid).val("");
    $(".composition_select" + dataid).val('');
    $('.composition_select' + dataid).selectpicker('refresh');
    $(".parts_select" + dataid).val('');
    $('.parts_select' + dataid).selectpicker('refresh');
    $('#wash_composition_error' + dataid).addClass('hidden')

}


function add_parts(dataid) {

    if (dataid === undefined) {
      dataid = "";
    } else {
      dataid = dataid;
    } 
    
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

function deleteComposition(obj, dataid) {

	$(obj).parent().parent().remove();

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
    
    if($('#composition_remove_qty' + dataid).find('tr').html() == undefined)
    {
        $('#composition_remove_qty' + dataid).html(`<tr id="no_data_comp`+dataid+`"><td colspan="3">No data Found</td></tr>`)
    }
}

function deleteCompPer(obj, dataid)
{
	$(obj).parent().parent().remove();

    composition_percentage_global -= parseInt($(obj).parent().parent().find('.percent').text());

    if($('#composition_table_qty' + dataid).find('tr').html() == undefined)
    {
        $('#composition_table_qty' + dataid).html(`<tr id="no_data`+dataid+`"><td colspan="3">No data Found</td></tr>`)
    }


    if (composition_percentage_global != "100" && composition_percentage_global != 100) {
		$('.composition_select' + dataid).prop('disabled', false)
		$('.composition_percent' + dataid).prop('disabled', false)
		$('.add_to_list' + dataid).prop('disabled', false)
		$('.add_to_composition' + dataid).attr('disabled','disabled')
	}
    
}

/**
 * This Function is used to set wash care labels in local storage.
 * 
 * @return void
 */

 function setWashCareLabelStatus(dataid,user_type,page_name) {

    if ($('#washcarelabelform' + dataid).find('#exist_id' + dataid).val() != "") {
       var value = $('#washcarelabelform' + dataid).find('#exist_id' + dataid).val();
    } else {
        var value = "null";
    }

    var wash_lab = JSON.stringify(wash_label);
    var i = 0
    $('#composition_remove_qty' + dataid + ' tr').each(function (key, val) {
        if($(this).children('td:first').text() != "No data Found")
        {
            i++;
        }
    })
    if (i != 0) {
        if( localStorage.getItem("wash_composition_final"+ dataid) != null)
        {
            $('#wash_composition_error' + dataid).addClass('hidden')
            $('#wash_composition_page_error' + dataid).addClass('hidden')
            localStorage.setItem("wash_label", wash_lab);
            setWashLabel('washcarelabelform' + dataid, dataid, value,user_type,page_name)
            
        }
        else {
            $('#wash_composition_page_error' + dataid).removeClass('hidden')
        }
        
    } else {
        $('#wash_composition_error' + dataid).removeClass('hidden')
    }


}

function setWashLabel(index, dataid, id,user_type,page_name) {

    
    $('.merchandisers').attr('disabled','disabled')
	$('.washlabelname'+dataid).text($('#wcl_name' + dataid).val()+" - Washcare Label");
    var check=0;
    var check1=0;

    var composition = JSON.parse(localStorage.getItem('wash_composition_final'+dataid));

    $(composition).each(function (index,value) {

            var trim_option = value[0].split("_");

            if(trim_option[0] == 'Select Option')
            {
                check++;
            }
            
            check1++;

    });


    if(check1 < 2 )
    {
        check=0;
    }

    if(check == 0 )
    {

        if(page_name == "os")
        {
            $('#save_wash_button').attr('disabled','disabled')
        }
        else
        {
            $('#save_wash_button_'+dataid).attr('disabled','disabled')
        }
        
        var composition = localStorage.getItem('wash_composition');

        var wash_fins = JSON.parse(composition);
        var wash_finals = localStorage.getItem('wash_composition_final'+dataid);

        
        var merchandisers = $('.merchandisers option:selected').val();
      	
		var template_front_id = $('.wcl_template' + dataid+' option:selected').val();
		var po_select 	  = $('#po_select' + dataid).val();
		$('#'+po_select+'_'+dataid).val("1");
        var template_name = $('#wcl_name' + dataid).val();
        var ocn_number = $('#ocn_number_wash' + dataid).val();
        var card_flag = $('#card_flag' + dataid).val();
        var time_flag = $('#time_flag' + dataid).val();
        var washing_select = $('.washing_select' + dataid + ' option:selected').attr('data-id');
        var whitening_select = $('.whitening_select' + dataid + ' option:selected').attr('data-id');
        var dryer_select = $('.dryer_select' + dataid + ' option:selected').attr('data-id');
        var ironing_select = $('.ironing_select' + dataid + ' option:selected').attr('data-id');
        var cleaning_select = $('.cleaning_select' + dataid + ' option:selected').attr('data-id');
        var made_in_select = $('.made_in_select' + dataid + ' option:selected').attr('data-id');

        var care_insrtuction = localStorage.getItem('care_instruction_select'+dataid)

        var care_instruction_select = JSON.parse(care_insrtuction);
        var care_instruction_select_fin = care_instruction_select.join(',');

        //template_front_id = template_id[0].id;
        var tot_qty_tablewash = [];
        var uvc_tco = [];
        var size = [];
        var barcode = [];
        $('#' + index + ' tbody tr').each(function () {
            if ($(this).find('.tot_qty_tablewcl').val() != undefined) {
                tot_qty_tablewash.push($(this).find('.tot_qty_tablewcl').val())
                uvc_tco.push($(this).find('.uvc_tco').text())
                size.push($(this).find('.size').text())
                barcode.push($(this).find('.barcode').text())
            }

        })
        tot_qty_tablewash_fin = tot_qty_tablewash.join(',');
        uvc_tco_fin = uvc_tco.join(',');
        size_fin = size.join(',');
        barcode_fin = barcode.join(',');
        var buyer = localStorage.getItem("buyer");
        
        $.ajax({
            url: base_url + "/"+user_type+"/" + buyer + "/order-management/set_wash_label",
            type: "Post",
            data: {
                
                template_front_id: template_front_id,
                id: id,
                template_name: template_name,
                washing_select: washing_select,
                whitening_select: whitening_select,
                ocn_number:ocn_number,
                po_select: po_select,
				//in:washonesizein,
				//cm:washonesizecm,
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
                barcode: barcode_fin,
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
				
				$('#'+po_select+'_'+dataid).attr("data-exist",parseInt(data.trim()));
				$('#exist_id'+dataid).val(parseInt(data.trim()))

                $('.wcl_name_card_'+dataid).text(template_name)
                $('#deletewcl_'+dataid).addClass("hidden")
                $('#clone_washcare_label_card'+dataid).removeClass("hidden")

                
				
				$('#print_wash_button_'+dataid).removeClass('hidden')
				$('#cancel_wash_button_'+dataid).removeClass('hidden')
				$('#save_wash_button_'+dataid).addClass('hidden')
				$('#delete_id'+dataid).val(data)
				$('.delete-wash'+dataid).addClass('hidden')
				$('#clickme'+dataid).html('View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i>')
				$('#wcl_name'+dataid).attr('disabled','disabled')
				$('#ocn_number_wash'+dataid).attr('disabled','disabled')
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
                

                $("#washcarelabelform" + dataid + " .left-panel-heat").removeClass('active');
                $("#washcarelabelform" + dataid + " .right-panel-heat").removeClass('active');
                $("#washcarelabelform" + dataid + " .backdrop").removeClass('active');
                $('#washcarelabelform' + dataid + ' .scalable').removeClass('active');
                $('#washcarelabelform' + dataid + ' .right_wcl_status_'+dataid).text('Buyer Approval Pending')
                $('#washcarelabelform' + dataid + ' .right_wcl_status_'+dataid).addClass('label label-BuyerApprovalPending')
                $('.wcl_status_'+dataid+' span').text('Buyer Approval Pending')
                $('.wcl_status_'+dataid+' span').addClass('label label-BuyerApprovalPending')

                if(page_name == "os")
                {
                    $('#snackbar').css('background-color', '#00b6ff');
                    $('.snackbar_span').html('Order updated successfully');
                    showSnackbar();
                    location.reload()
                }
                else
                {
                    $('#snackbar').css('background-color', '#00b6ff');
                    $('.snackbar_span').html('Order created successfully');
                    showSnackbar();
                }
                
            },
            error: function (data) {

            }
        })
        $('.composition_error_hidden').addClass('hidden')
        $('#washcare_label_card'+dataid).css('display','inline-block') 
		$('#washcare_label_card'+dataid).removeClass('hidden')
		$('#clone_washcare_label_card'+dataid).removeClass('hidden')
		$('.wash-card'+dataid+' span').text('Buyer Approval Pending')
		$('.wash-card'+dataid+' span').addClass('label label-BuyerApprovalPending')
		$('.wash-card'+dataid+' span').removeClass('label-OrderRejected')
		$('#po-file-upload-button').css('display','block')
    }
    else
    {
        $('.composition_error_hidden').removeClass('hidden')
    }
    

}

function selectpicker() {
	$('.selectpicker').selectpicker({
		style: 'btn-default',
		size: 4
	});

}

function printHangDivOs(front,back,uvc,po,user_type,tableID,tableName,art_type) {

	var a = document.getElementById(front);
	var svgDoc = a.contentDocument;
	var svgRoot = svgDoc.documentElement;

	var a1 = document.getElementById(back);
	var svgDoc1 = a1.contentDocument;
	
	var svgRoot1 = svgDoc1.documentElement;


    if(user_type == "admin")
    {
        var buyer_url = base_url + "/admin/orchestra/get-order-details";
    }
    else if(user_type == "customer")
    {
        var buyer_url = base_url + "/customer/orchestra/get-order-details";
    }
    else if(user_type == "erp")
    {
        var buyer_url = base_url + "/erp/job-manager/get-order-details";
    }
	
	var svgarr = [];
	var svgarr_front = [];
	var i=0;
	var ajax=0;

    $.ajax({
        url: buyer_url,
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_id: tableID,
            table_name: tableName,
            style_no: uvc,
            buyer_no: po
        },
        beforeSend: function() {

        },
        success: function(data) {

            var disney = data.orchestra_hang_tag_orders[0].Licence_Code;

            var node = data.orchestra_hang_tag_orders[0].template_name;

            svgDoc1.getElementById("theme").innerHTML = data.orchestra_hang_tag_orders[0].theme_reference;

            $(data.orchestra_hang_tag_orders).each(function(index,value){
                var size_txt = value.size;
                var uvc_tco = value.uvc_tco;
                var size = size_txt.split(" ")

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
                
                var code = value.barcode;
                
                $.ajax({
                    url: base_url + "/"+user_type+"/order-management/get-tsl-size",
                    type: 'GET',
                    async: false,
                    data: {
                        size: size_txt,
                        uvc_tco:uvc_tco
                    },
                    tryCount: 0,
                    retryLimit: 2,
                    success: function (data) {
                        if(size[0] == "TAILLE")
                        {
                            svgDoc1.getElementById("size").innerHTML = "";
                            svgDoc1.getElementById("size_french").innerHTML = "";
                            svgDoc1.getElementById("size_english").innerHTML = "";
                            svgDoc1.getElementById("uvc_tco").innerHTML = uvc_tco;
                            svgDoc1.getElementById("size_list").innerHTML = "TAILLE UNIQUE";
                            svgDoc1.getElementById("cm").innerHTML = "ONE SIZE";
                            svgDoc1.getElementById("cm").setAttribute('font-size', '6px');
                            
                            svgDoc1.getElementById("tu_one_size").removeAttribute("visibility");
                            svgDoc.getElementById("size").innerHTML = "";
                            svgDoc.getElementById("size_french").innerHTML = "";
                            svgDoc.getElementById("size_english").innerHTML = "";
                            svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
                            svgDoc.getElementById("one_size").removeAttribute("visibility");
                        }
                        else
                        {
                            if(data.size_chart.length > 0)
                            {
                                svgDoc1.getElementById("cm").innerHTML =  data.size_chart[0].size+" CM";
                            }
                            else
                            {
                                svgDoc1.getElementById("cm").innerHTML = "";
                            }

                            svgDoc1.getElementById("size").innerHTML = size[0];
                            svgDoc1.getElementById("size_french").innerHTML = size[1];
                            svgDoc1.getElementById("size_english").innerHTML = size1;
                            svgDoc1.getElementById("uvc_tco").innerHTML = uvc_tco;svgDoc1.getElementById("cm").setAttribute('font-size', '7.7px');
                            
                            svgDoc.getElementById("size").innerHTML = size[0];
                            if(size[0].length == 1)
                            {
                                svgDoc.getElementById("size").setAttribute('transform', 'matrix(1.0074 0 0 1 14 297.5785)');
                                svgDoc.getElementById("size_french").setAttribute('transform', 'matrix(1.0074 0 0 1 23.4422 291.033)');
                                svgDoc.getElementById("size_english").setAttribute('transform', 'matrix(1.0074 0 0 1 23.4422 297.5544)');
                                
                            }
                            else
                            {
                                svgDoc.getElementById("size").setAttribute('transform', 'matrix(1.0074 0 0 1 13 297.5785)');
                                svgDoc1.getElementById("size").setAttribute('transform', 'matrix(1.0074 0 0 1 92 36.4142)');
                                svgDoc.getElementById("size_french").setAttribute('transform', 'matrix(1.0074 0 0 1 30 291.033)');
                                svgDoc.getElementById("size_english").setAttribute('transform', 'matrix(1.0074 0 0 1 30 297.5544)');
                            }
                            svgDoc.getElementById("size_french").innerHTML = size[1];
                            svgDoc.getElementById("size_english").innerHTML = size1;
                        }
                        
                        
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
                                    // svgDoc1.getElementById("canada_price").innerHTML = value.Price;
                                    // svgDoc1.getElementById("canada_club_price").innerHTML = value.Club_Price;
                                    break;
                                    case "CHF":
                                    svgDoc1.getElementById("swiss_price").innerHTML = value.Price;
                                    svgDoc1.getElementById("swiss_club_price").innerHTML = value.Club_Price;
                                    break;
                                    case "DZD":
                                    // svgDoc1.getElementById("algeria_price").innerHTML = value.Price;
                                    // svgDoc1.getElementById("algeria_club_price").innerHTML = value.Club_Price;
                                    break;
                                    case "MAD":
                                    svgDoc1.getElementById("morocco_price").innerHTML = value.Price;
                                    svgDoc1.getElementById("morroco_club_price").innerHTML = value.Club_Price;
                                    break;
                                    case "MUR":
                                    // svgDoc1.getElementById("morris_price").innerHTML = value.Price;
                                    // svgDoc1.getElementById("morris_club_price").innerHTML = value.Club_Price;
                                    break;
                                    case "SAR":
                                    // svgDoc1.getElementById("saudi_club_price").innerHTML = value.Price;
                                    break;
                                    case "USD":
                                    // svgDoc1.getElementById("us_price").innerHTML = value.Price;
                                    // svgDoc1.getElementById("us_club_price").innerHTML = value.Club_Price;
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
                            // svgDoc1.getElementById("canada_price").innerHTML = "";
                            // svgDoc1.getElementById("canada_club_price").innerHTML = "";
                            svgDoc1.getElementById("swiss_price").innerHTML = "";
                            svgDoc1.getElementById("swiss_club_price").innerHTML = "";
                            // svgDoc1.getElementById("algeria_price").innerHTML = "";
                            // svgDoc1.getElementById("algeria_club_price").innerHTML = "";
                            svgDoc1.getElementById("morocco_price").innerHTML = "";
                            svgDoc1.getElementById("morroco_club_price").innerHTML = "";
                            // svgDoc1.getElementById("morris_price").innerHTML = "";
                            // svgDoc1.getElementById("morris_club_price").innerHTML = "";
                            // svgDoc1.getElementById("saudi_club_price").innerHTML = "";
                            // svgDoc1.getElementById("us_price").innerHTML = "";
                            // svgDoc1.getElementById("us_club_price").innerHTML = "";
                        }
        
                        //alert(node)
                        if(disney != "DI")
                        {
                            jsbarcode_hang(code, "barcode1", svgDoc1)
                            svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 27 45)');
                        }
                        else
                        {
                            
                           jsbarcode_hang_disney(code, "barcode1", svgDoc1)
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
                    },
                    error: function (errorXHR, errorStatus, errorThrown) {
                        console.log(errorThrown);
                    },
                    complete: function () {

                    }
                })
                
            i++;	
            });

            var svg_fin = svgarr.join(',_');
            var svg_fin_front = svgarr_front.join(',_');
            
            if(i == ajax)
            {
                PopupHangOs(svg_fin_front, svg_fin,svgDoc1,disney,node,uvc,user_type);
            }
        },
        error: function(data) {

        },
        complete: function() {

        }
    });	
	
}

function PopupHangOs(svgRoot, svgRoot1,svgDoc1,disney,node,uvc,user_type) {
    
    var success = false;
    var url = "";
    
    $.ajax({
        url: base_url + "/"+user_type+"/order-management/print-hang-pdf",
        type: "post",
        data: {
            _token: csrf_token,
            svg: svgRoot,
            svgRoot: svgRoot1,
            template_name : node,
            uvc : uvc
        },
        async: false,
        success: function (data) {
            if(disney != "DI")
            {
                svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 10.6793 50.8835)');
            }
            else
            {	var node = $("#tree1").jstree('get_selected', true)[0].text;
                    if(node == "COD2")
                    {
                        svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 32.6793 139.8835)');
                    }
                    else if(node == "DBB1")
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
 * This Function is used to invoke Jstree.
 * 
 * @return tree
 */

 function treestructurespecial(data, tree, template_id,orchestra_special_tag_orders,user_type) {

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
	$('#expand_special').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse_special').bind("click", function () {
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
			
			$.ajax({
				url: base_url + "/"+user_type+"/" + buyer + "/template-management/template-view",
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
						$('#ocn_number_special').attr('disabled','disabled')
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
			
			$.ajax({
				url: base_url + "/"+user_type+"/" + buyer + "/template-management/template-view",
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
function treestructureheat_transfer(data, tree, template_id,orchestra_heat_transfer_orders_data,user_type) {

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
	$('#expand_heat').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse_heat').bind("click", function () {
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
			
			$.ajax({
				url: base_url + "/"+user_type+"/" + buyer + "/template-management/template-view",
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
						$('#ocn_number_heat').attr('disabled','disabled')
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
			
			$.ajax({
				url: base_url + "/"+user_type+"/" + buyer + "/template-management/template-view",
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
function treestructuremain_label(data, tree, template_id,orchestra_main_label_orders,user_type) {

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
	$('#expand_main').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse_main').bind("click", function () {
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
			
			$.ajax({
				url: base_url + "/"+user_type+"/" + buyer + "/template-management/template-view",
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
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}
					
					if (orchestra_main_label_orders != "") {
						 $('#'+tree+' >ul > li').each( function() {
						   disable( tree,this.id );        
						})
						$('.main_label_qty').attr('disabled','disabled')
						$('#ocn_number_main').attr('disabled','disabled')
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
			
			$.ajax({
				url: base_url + "/"+user_type+"/" + buyer + "/template-management/template-view",
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
function treestructuresize_label(data, tree, template_id,orchestra_size_label_orders,user_type) {

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
	$('#expand_size').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse_size').bind("click", function () {
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
			
			$.ajax({
				url: base_url + "/"+user_type+"/" + buyer + "/template-management/template-view",
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
						$('#ocn_number_size').attr('disabled','disabled')
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
			
			$.ajax({
				url: base_url + "/"+user_type+"/" + buyer + "/template-management/template-view",
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

function cancelWashDiv(dataid,user_type){
	
	var id 	  = $('#washcarelabelform'+dataid).find('#exist_id'+dataid).val();
	
	$.ajax({
		url: base_url + "/"+user_type+"/orchestra/order-management/remove_wcl",
		type: "Post",
		data: {
			id: id,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {

            if(data.orchestra_wcl != null)
            {
                $('.merchandisers').selectpicker('val', '');
                $('.merchandisers').removeAttr('disabled');
            }

			if(data.update_po_disable == 0)
			{
				$('#po-file-upload-button').css('display','none')
			}
			
			$('#washcarelabelform' + dataid).remove()
                
            $('.wcl_status_' + dataid).parent().remove();
			
		},
		error: function (data) {

		}
	})
}

function setHangTagStatus(colour,user_type,page_name) {

	$('#save_hang_button').attr('disabled','disabled')

    if ($('#hangtagform').find('#exist_id').val() != "") {
        var exist_id = $('#hangtagform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }

    var template_id = $('#hangtagform').find('#tree1').jstree('get_selected', true);
    if (template_id != "") {
        
        localStorage.setItem("hang_tag", exist_id);
        setHangTag(localStorage.getItem("hang_tag"),colour,user_type)
        $('#hang_template_error').addClass('hidden')

		

		$("#hangtagform .left-panelorc-hang").removeClass('active');
		$("#hangtagform .right-panel-hang").removeClass('active');
		$("#hangtagform .backdrop").removeClass('active');
		$('#hangtagform .scalable').removeClass('active');

        if(page_name == "os")
        {
            $('#snackbar').css('background-color', '#00b6ff');
            $('.snackbar_span').html('Order updated successfully');
            showSnackbar();
            location.reload()
        }
        else
        {
            $('.hang-card span').text('Order Approved')
            $('.hang-card span').addClass('label label-OrderApproved')
            $('.right-panel-hang span').text('Order Approved')
            $('.right-panel-hang span').addClass('label label-OrderApproved')
            $('#snackbar').css('background-color', '#00b6ff');
            $('.snackbar_span').html('Order created successfully');
            showSnackbar();
        }

    } else {
        $('#hang_template_error').removeClass('hidden')
    }
}

function setHangTag(id,colour,user_type) {
	
    $('#save_hang_button').attr('disabled','disabled')

	var template_id = $('#hangtagform').find('#tree1').jstree('get_selected', true);
	var template_front_id = template_id[0].id;
	var template_back_id = template_id[0].id;
	var ocn_number = $('#ocn_number_hang').val();
	var theme_label = $('#theme_label').val();
	var tot_qty_tablehang = [];
	var uvc_tco = [];
	var size = [];
	var size_id = [];
	var main_id = [];
	var barcode = [];
	$('#hangtagform tbody tr').each(function () {
		if ($(this).find('.tot_qty_tablehang').val() != undefined) {
			tot_qty_tablehang.push($(this).find('.tot_qty_tablehang').val())
			uvc_tco.push($(this).find('.uvc_tco').text())
			barcode.push($(this).find('.barcode').text())
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
	barcode_fin = barcode.join(',');
	var buyer = localStorage.getItem("buyer");
	var uvc = $('#uvc_id').val();
	var parcel_id = $('#parcel_id').val();
	var season_code = $('#season_code').val();
	
	$.ajax({
		url: base_url + "/"+user_type+"/" + buyer + "/order-management/set_hang_tag",
		type: "Post",
		data: {
			
			template_front_id: template_front_id,
			template_back_id: template_back_id,
			tot_qty_table: tot_qty_tablehang_fin,
			ocn_number:ocn_number,
			id: id,
			theme_label:theme_label,
			colour: colour,
			size_id: size_id_fin,
			main_id: main_id_fin,
			uvc_tco: uvc_tco_fin,
			barcode: barcode_fin,
			uvc: uvc,
			parcel_id: parcel_id,
			season_code: season_code,
			size: size_fin,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {

			var onclick = `printHangDivOs("svg_hang_front","svg2","`+uvc+`","","`+user_type+`","`+data.id+`","orchestra_hang_tag_orders","proof")`;
			
			$('#print_hang_button').attr('onclick',onclick)

			$('#hangtagform').find('#exist_id').val(data.id)
			$('#print_hang_button').removeClass('hidden')
			$('#save_hang_button').addClass('hidden')
			$('#tree1 >ul > li').each( function() {
			   disable( 'tree1',this.id );        
			})
			$('.extra_percentagehang').attr('disabled','disabled')
			$('.tot_qty_tablehang').attr('disabled','disabled')
            $('#ocn_number_hang').attr('disabled','disabled')
			$('#po-file-upload-button').css('display','block')

		},
		error: function (data) {

		}
	})
		
}

/**
 * This Function is used to set special tag in local storage.
 * 
 * @return void
 */

 function setSpecialTagStatus(colour,user_type,page_name) {

    if ($('#specialtagform').find('#exist_id').val() != "") {
        var exist_id = $('#specialtagform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#specialtagform').find('#tree2').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("special_tag", exist_id);
        setSpecialTag(localStorage.getItem("special_tag"),colour,user_type)
        $('#special_template_error').addClass('hidden')
        $("#specialtagform .backdrop").removeClass('active');
        $('#specialtagform .scalable').removeClass('active');

        if(page_name == "os")
        {
            $('#snackbar').css('background-color', '#00b6ff');
            $('.snackbar_span').html('Order updated successfully');
            showSnackbar();
            $("#specialtagform .left-panelos").removeClass('active');
            $("#specialtagform .right-panelos").removeClass('active');
            location.reload()
        }
        else
        {
            $("#specialtagform .left-panelorc").removeClass('active');
            $("#specialtagform .right-panel").removeClass('active');
            $('#snackbar').css('background-color', '#00b6ff');
            $('.snackbar_span').html('Order created successfully');
            showSnackbar();
        }
    } else {
        $('#special_template_error').removeClass('hidden')
    }
}

/**
 * This Function is used to set Heat Transfer in local storage.
 * 
 * @return void
 */

function setheat_transferStatus(colour,user_type,page_name) {

    if ($('#heat_transferform').find('#exist_id').val() != "") {
        var exist_id = $('#heat_transferform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#heat_transferform').find('#treeht').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("heat_transfer", exist_id);
        setHeatTransfer(localStorage.getItem("heat_transfer"),colour,user_type)
        $('#heat_transfer_template_error').addClass('hidden')
        $("#heat_transferform .backdrop").removeClass('active');
        $('#heat_transferform .scalable').removeClass('active');
        if(page_name == "os")
        {
            $('#snackbar').css('background-color', '#00b6ff');
            $('.snackbar_span').html('Order updated successfully');
            showSnackbar();
            $("#heat_transferform .left-panelos").removeClass('active');
            $("#heat_transferform .right-panelos").removeClass('active');
            location.reload()
        }
        else
        {
            $("#heat_transferform .left-panel-heat").removeClass('active');
            $("#heat_transferform .right-panel-heat").removeClass('active');
            $('#snackbar').css('background-color', '#00b6ff');
            $('.snackbar_span').html('Order created successfully');
            showSnackbar();
        }
    } else {
        $('#heat_transfer_template_error').removeClass('hidden')
    }
}
/**
 * This Function is used to set Main Label in local storage.
 * 
 * @return void
 */

function setMainLabelStatus(colour,user_type,page_name) {

    if ($('#main_labelform').find('#exist_id').val() != "") {
        var exist_id = $('#main_labelform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#main_labelform').find('#treeml').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("main_label", exist_id);
        setMainLabel(localStorage.getItem("main_label"),colour,user_type)
        $('#main_label_template_error').addClass('hidden')
        $("#main_labelform .backdrop").removeClass('active');
        $('#main_labelform .scalable').removeClass('active');
        if(page_name == "os")
        {
            $('#snackbar').css('background-color', '#00b6ff');
            $('.snackbar_span').html('Order updated successfully');
            showSnackbar();
            $("#main_labelform .left-panelos").removeClass('active');
            $("#main_labelform .right-panelos").removeClass('active');
            location.reload()
        }
        else
        {
            $("#main_labelform .left-panelorc").removeClass('active');
            $("#main_labelform .right-panel").removeClass('active');
            $('#snackbar').css('background-color', '#00b6ff');
            $('.snackbar_span').html('Order created successfully');
            showSnackbar();
        }
    } else {
        $('#main_label_template_error').removeClass('hidden')
    }
}
/**
 * This Function is used to set Size Labels in local storage.
 * 
 * @return void
 */

function setSizeLabelStatus(colour,user_type,page_name) {

    if ($('#size_labelform').find('#exist_id').val() != "") {
        var exist_id = $('#size_labelform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#size_labelform').find('#treesl').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("size_label", exist_id);
        setSizeLabel(localStorage.getItem("size_label"),colour,user_type)
        $('#size_label_template_error').addClass('hidden')
        $("#size_labelform .backdrop").removeClass('active');
        $('#size_labelform .scalable').removeClass('active');
        if(page_name == "os")
        {
            $('#snackbar').css('background-color', '#00b6ff');
            $('.snackbar_span').html('Order updated successfully');
            showSnackbar();
            $("#size_labelform .left-panelos").removeClass('active');
            $("#size_labelform .right-panelos").removeClass('active');
            location.reload()
        }
        else
        {
            $("#size_labelform .left-panel-heat").removeClass('active');
            $("#size_labelform .right-panel-heat").removeClass('active');
            $('#snackbar').css('background-color', '#00b6ff');
            $('.snackbar_span').html('Order created successfully');
            showSnackbar();
        }
    } else {
        $('#size_label_template_error').removeClass('hidden')
    }
}

/**
 * This Function is used to set special tag in db.
 * 
 * @return void
 */


function setSpecialTag(id,colour,user_type) {

    $('#save_special_button').attr('disabled','disabled')
	var myElements = $(".special_qty");
    var special_qty = [];
    for (var i=0;i<myElements.length;i++) {
        special_qty.push(myElements.eq(i).val());
    }
    
    var template_id = $('#specialtagform').find('#tree2').jstree('get_selected', true);
    template_front_id = template_id[0].id;
	var ocn_number = $('#ocn_number_special').val();
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
    
    $.ajax({
        url: base_url + "/"+user_type+"/" + buyer + "/order-management/set_special_tag",
        type: "Post",
        data: {
            
            template_front_id: template_front_id,
            id: id,
            tot_qty_table: special_qty,
			ocn_number:ocn_number,
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
			$('#specialtagform').find('#exist_id').val(data.id)
			$('#save_special_button').addClass('hidden')
			//$('#cancel_special_button').removeClass('hidden');
			$('#tree2 >ul > li').each( function() {
			   disable( 'tree2',this.id );        
			})
			$('.special_qty').attr('disabled','disabled')
        },
        error: function (data) {

        }
    })
	$('.special-card span').text('Order Approved')
	$('.special-card span').addClass('label label-OrderApproved')
	$('#po-file-upload-button').css('display','block')
}
/**
 * This Function is used to set HeatTransfer in db.
 * 
 * @return void
 */


function setHeatTransfer(id,colour,user_type) {

    $('#save_heat_transfer_button').attr('disabled','disabled')
  
    var template_id = $('#heat_transferform').find('#treeht').jstree('get_selected', true);
	template_id = template_id[0].id;
	var ocn_number = $('#ocn_number_heat').val();
	
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
	
	$.ajax({
		url: base_url + "/"+user_type+"/" + buyer + "/order-management/set_heat_transfer",
		type: "Post",
		data: {
			
			template_id: template_id,
			tot_qty_table: tot_qty_tableheat_fin,
			ocn_number:ocn_number,
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
			$('#heat_transferform').find('#exist_id').val(data.id)
			$('#save_heat_transfer_button').addClass('hidden')
			//$('#cancel_heat_button').removeClass('hidden');
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
		$('.heat_transfer-card span').addClass('label label-OrderApproved')
		$('#po-file-upload-button').css('display','block')
}
/**
 * This Function is used to set Main Label in db.
 * 
 * @return void
 */


function setMainLabel(id,colour,user_type) {

    $('#save_main_label_button').attr('disabled','disabled')
  
	var myElements = $(".main_label_qty");
    var main_label_qty = [];
    for (var i=0;i<myElements.length;i++) {
        main_label_qty.push(myElements.eq(i).val());
    }
	
    
    var template_id = $('#main_labelform').find('#treeml').jstree('get_selected', true);
    template_id = template_id[0].id;
	var ocn_number = $('#ocn_number_main').val();
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
    
    $.ajax({
        url: base_url + "/"+user_type+"/" + buyer + "/order-management/set_main_label",
        type: "Post",
        data: {
            
            template_id: template_id,
            id: id,
            tot_qty_table: main_label_qty,
			ocn_number:ocn_number,
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
			$('#main_labelform').find('#exist_id').val(data.id)
			$('#save_main_label_button').addClass('hidden')
			//$('#cancel_main_button').removeClass('hidden');
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
	$('.main_label-card span').addClass('label label-OrderApproved')
	$('#po-file-upload-button').css('display','block')
}
/**
 * This Function is used to set Size Label in db.
 * 
 * @return void
 */


function setSizeLabel(id,colour,user_type) {    

    $('#save_size_label_button').attr('disabled','disabled')
    
    var template_id = $('#size_labelform').find('#treesl').jstree('get_selected', true);
	template_id = template_id[0].id;
	var ocn_number = $('#ocn_number_size').val();
	
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
	
	$.ajax({
		url: base_url + "/"+user_type+"/" + buyer + "/order-management/set_size_label",
		type: "Post",
		data: {
			
			template_id: template_id,
			tot_qty_table: tot_qty_tablesize_fin,
			ocn_number:ocn_number,
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
			$('#size_labelform').find('#exist_id').val(data.id)
			$('#save_size_label_button').addClass('hidden')
			//$('#cancel_size_button').removeClass('hidden');
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
		$('.size_label-card span').addClass('label label-OrderApproved')
		$('#po-file-upload-button').css('display','block')
}

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
 * This Function is used to restrict alpha numeric and special characters.
 * 
 * @return boolean
 */
function checkNumbersOnly(e) {

	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		return false;
	}
}

function disable(id,node_id) {
  var node = $("#"+id).jstree().get_node( node_id );
  $("#"+id).jstree().disable_node(node); 
  node.children.forEach( function(child_id) {            
    disable( id,child_id );
  })
} 

function enable(id,node_id) {
  var node = $("#"+id).jstree().get_node( node_id );
  $("#"+id).jstree().enable_node(node); 
  node.children.forEach( function(child_id) {            
    enable( id,child_id );
  })
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
	
	//console.log(svg_xml)
	
	var allGs = document.getElementsByTagName('g');
	var firstG = allGs[0];
	var firstChild = firstG.childNodes[0];
	
	firstChild.setAttribute("x","20")
	
	var xchil = document.getElementById('svgdiv')

	svgDoc.getElementById(id).innerHTML = xchil.innerHTML;
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