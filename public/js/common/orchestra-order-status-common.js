
const orchestraPOUpload = (obj, id, po_file, tableName, uvc,user_type) => {

    //var buyer = $(obj).parent().attr('data-buyer');
    $('#po-file-upload-group').modal("show")
		$('#new_po_div_status').removeClass('hidden');
		$('#new_po1_status').addClass('hidden');
		$('#map_po_status').addClass('hidden');
		$('#update_new_po_status').removeClass('hidden');
		$('#update_po_status').addClass('hidden');
		$('#new_pos_status').addClass('hidden');
		$('#map_po_div_status').addClass('hidden');
		$('#exist_artwork_div_status').removeClass('hidden')
		$('#new_po_no_status').val("")
        $('#new_po_file_status').val("")
		$('#new_po_error_status').addClass('hidden')
		$('#new_po_file_error_status').addClass('hidden')
        $('#new_po_error_length_status').addClass('hidden');
        $('#uvc_selection').selectpicker('val', uvc);
        $('#uvc_selection').selectpicker('refresh');
        
		$.ajax({
			url: base_url + "/"+user_type+"/orchestra/order-management/get-existing-label-orders-status-po",
			type: "get",
			dataType: "json",
			data: {
				uvc: uvc
			},
			beforeSend: function () {

			},
			success: function (data) {
				$('#check_error_status').addClass('hidden')
				$('#new_po_error_status').addClass('hidden')
				$('#new_po_file_error_status').addClass('hidden')
				$('#new_po_error_length_status').addClass('hidden');
                $('#uvc_selection').attr('disabled',true)
				$('#po-file-upload-group').find('#exist_artwork_div_status ul').html("");
				var newObj = [...data.orchestra_hang_tag_orders, ...data.orchestra_special_tag_orders, ...data.orchestra_main_label_orders, ...data.orchestra_wash_care_label_orders, ...data.orchestra_solid_carton_sticker_orders, ...data.orchestra_solid_poly_bag_sticker_orders, ...data.orchestra_standard_carton_sticker_orders, ...data.orchestra_standard_poly_bag_sticker_orders, ...data.orchestra_size_label_orders, ...data.orchestra_heat_transfer_orders];
				var coun = 0;
				var options = "<option>Select PO</option>";
				var unique_po = []
				var old_po = []
                var new_po_div_status = 0;
                var count_li = 0;
				$(newObj).each(function (index1, value1) {
                
					if (value1.po_number != "" && value1.po_number != null) {
							
						if(old_po.includes(value1.po_number) != true)
						{
							old_po.push(value1.po_number)
							options += "<option data-id='"+value1.po_file+"'>" + value1.po_number + "</option>";
						}
						//old_po = value1.po_number;
						coun++;
						$('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="'+value1.tableName+'-'+value1.id+'" data-company="'+value1.company_id+'" class="check-list">'+value1.color_code+' '+value1.TemplateName+'</li>')
                         new_po_div_status++;
                         count_li++;
					}
					else {
                        $('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="'+value1.tableName+'-'+value1.id+'" data-company="'+value1.company_id+'" class="list-group-item">'+value1.color_code+' '+value1.TemplateName+'</li>')
					}
					
                });

                
                if(newObj.length == count_li)
                {
                    $('#new_po_div_status').addClass('hidden')
                    $('#update_new_po_status').addClass('hidden')
                }
				
				
				
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
                    if($(this).data('id') == tableName+'-'+id)
                    {
                        $checkbox.prop('checked', !$checkbox.is(':checked'));
                        $checkbox.triggerHandler('change');
                        updateDisplay();
                    }


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
                                    url: base_url + "/"+user_type+"/orchestra/order-management/update-label-orders-po",
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

			},
			error: function (data) {

			},
			complete: function () {
				$(".loader").hide();
			}
		})
}

/**
 * [orchestraPOUpload for customer po file upload]
 * @param  {[type]} obj [description]
 * @param  {[type]} id  [description]
 * @return {[type]}     [description]
 */
const orchestraPOUploaded = (obj, id, po_file, tableName, uvc, poNumber,user_type) => {
    
    $.ajax({
        url: base_url + "/"+user_type+"/orchestra/get-erp-status",
        type: 'get',
        data: {
            table_name: tableName,
            id: id,
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
            // erp_details array null , if order is not moved to erp tables
            // only editable, if order status is "Hold For JC Queue" OR "Moved To ERP"
            if (data.erp_details[0] != null) {
                if (data.erp_details[0].status_name !== 'Hold For JC Queue' && data.erp_details[0].status_name !== 'Moved To ERP') {
                    $('.update-po-file').hide();

                    $('#error_msg_span').html('ERP Order Status : <strong>' + data.erp_details[0].status_name + '</strong>. You are not permitted to upload any more po files for this order.')
                } else {
                    $('.update-po-file').show();
                    $('#error_msg_span').html('');
                }
            } else {
                $('.update-po-file').show();
                $('#error_msg_span').html('');
            }
        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            if (po_file === "null") {
                $('#po_upload').removeClass('hidden')
                $('.upload-po').removeClass('hidden')
                $('#po_file').addClass('hidden')
            } else {
                $('#po_upload').addClass('hidden')
                $('.upload-po').addClass('hidden')
                $('#po_file').removeClass('hidden')
            }
            var po_link = base_url + po_file
            $('#po_link').attr("href", po_link);
            $('#rowID').val(id);
            $('#tableName').val(tableName);
            $('#buyer_id').val(localStorage.getItem("buyer_id"));
            $('#po-file-upload').modal('show')
            $('#po_number_updation').val(poNumber)
        }
    })
}