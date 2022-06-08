/**
 * Function for uploading combined po files
 * @author    <praveen>
 */
uploadCombinedPO = (user_type, formdata, templateName) => {
     try {
         $.ajax({
             url: base_url + `/${user_type}/tesco/upload-tesco-combined-po-file`,
             type: 'POST',
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             data: formdata,
             tryCount: 0,
             retryLimit: 2,
             dataType: 'JSON',
             async: true,
             cache: false,
             contentType: false,
             processData: false,
             beforeSend: function () {
                 $('.po-submit-btn').prop('disabled', true);
             },
             statusCode: {
                 200: function (data) {
                     // enable submit button after po upload
                     $('.po-submit-btn').prop('disabled', false);

                     /** [ Disable already uploaded templates checkbox] */
                     $.each(templateName, function (index, value) {
                         $('input[type="checkbox"]#' + value).prop('checked', false)
                         $('input[type="checkbox"]#' + value).prop('disabled', true)
                         $('.' + value + '-po-div').show();
                         $('#' + value + '-uploaded-po').text("PO Number : " + data.po_number);
                     });

                     /** [ Rename button and hide form fields if Po uploaded for all templates ] */
                     if ($('.template-checkbox1').filter(':not(:disabled)').length === 0) {
                         $('.po-submit-btn').text('Place Order');
                         $('.po-submit-btn').attr('data-dismiss', 'modal')
                         $('.upload-later').hide();
                         $('.upload-po-form').hide();
                     }
                     /** [ Show success alert] */
                     $("#success-alert").fadeTo(2000, 500).slideUp(500, function () {
                         $("#success-alert").slideUp(500);
                     });
                     /** Reset po upload form**/
                     $('#upload_combined_po_form').trigger('reset');
                 },
                 400: function () {
                     $('.snackbar_span').html("Something went wrong !. Try again");
                     showSnackbar();
                 }
             },
             error: function (errorXHR, errorStatus, errorThrown) {
                console.log(errorThrown);
             },
             complete: function () {}
         })
     } catch (e) {
         console.log("Error:" + e)
     }
 }

 /**
 * [cancel tesco orders]
 * @author <praveen>
 */
const cancelTescoOrder= (user_type) => {
	try {
		var array_list   = [];
		$('.table-id-hidden').each(function (i, table_id) {
			my_object    = new Object();
			var table_names = table_id.id.split("_");
			my_object.table = table_names[0];
			my_object.table_id = table_id.value;
			array_list.push(my_object);
		});

		$.ajax({
			url: base_url + `/${user_type}/tesco/cancel-tesco-order`,
			type: 'PUT',
			data: {
				_token: csrf_token,
				data_array:array_list
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {
				$('.snackbar_span').html('Order Cancelled Successfully');
				showSnackbar();

				$('#cancelOrderModal').modal('hide');
				$('.order-cancel-btn').remove();

			},
			error: function (errorXHR, errorStatus, errorThrown) {
				console.log(errorThrown);
			},
			complete: function () {
				$(".loader").hide();
				/** [To make top menu active] */
				$(function () {
					var url = window.location;
					$('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
					$('.dynamic-menu a').filter(function () {
						return this.href == url;
					}).parent('li').addClass('current-page').find('a').addClass('active');
				});
			}
		})
	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}

/**
 * Function to load the artworks for correspinding style number in view order page 
 * @param  {string} style     Style Number of the order
 * @param  {object} artwork_collection Collection of all orders
 */
loadArtworkNames = (artwork_collection_style) => {
    if(Object.keys(artwork_collection_style).length !== 0 && artwork_collection_style.constructor === Object) {
        $('.artwork_list').removeClass('hidden');
        var options="<option value=''>Search Artwork</option>";
        $.each(artwork_collection_style, function(po,templs) {
            var awArray = templs.split(",");

            var artworkID = [];
            var artworkName = [];
            var orderStatus, orderID;
            for (i = 0; i < awArray.length; i++) { 
                var templateInfo = awArray[i].split("__");
                artworkName.push(templateInfo[1]);
                artworkID.push(templateInfo[0]);
                orderStatus = templateInfo[2];
                orderID = templateInfo[3];
            }

            if (typeof artworkName !== 'undefined' && artworkName.length > 0 && typeof artworkID !== 'undefined' && artworkID.length > 0) {
                options+="<option data-icon='glyphicon glyphicon-stop "+orderStatus+"'  value='"+po+"' data-subtext='"+po+"' data-tempid='"+artworkID.join()+"' data-orderid='"+orderID+"'>"+artworkName.join()+"</option>";
                
            }
        });
        $('#artwork_list').html(options);
        $('#artwork_list').selectpicker('refresh');
    } else {
        throw "artwork_collection_empty";
    }
}

/**
 * AJAX method to fetch the order details.
 * @return {[type]} [description]
 */
viewOrder = (user_type, pNumber,aWork, style_number, zone, orderid) => {
    try {
        $.ajax({
            url: base_url + `/${user_type}/tesco/view-order`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $(".loader").show();
            },
            data: {'po_number':pNumber, 'artwork':aWork, 'style_number':style_number, "zone":zone, "orderid":orderid},
            tryCount: 0,
            retryLimit: 2,
            dataType: 'JSON',
            statusCode: {
                200: function(data) {
                    var node_ids = [];
                    var zone = [];
                    var type = [];
                    var group = [];
                    var nodes;
                    var template_zone;
                    var template_type;
                    $.each(data.result, function(index, object) {
                        if( object && object !== "null" && object !== "undefined" ) {
                            node_ids.push(object.template_id);
                            zone.push(object.zone);
                            type.push(object.template_type);
                            group.push(object.TemplateName);
                        }
                    });
                    if(node_ids.length > 0) {
                        nodes = node_ids.toString();
                    }
                    if(zone.length > 0 && $.unique(zone).length === 1) {
                        template_zone = zone[0];
                    }
                    if(type.length > 0 && $.unique(type).length === 1) {
                        template_type = type[0];
                    }
                    group = group.toString();                  
                    localStorage.setItem("templates", nodes);
                    localStorage.setItem("zone", template_zone);
                    localStorage.setItem("template type", template_type);
                    localStorage.setItem("template_group", group);
                    
                    if(data.result.hasOwnProperty('order_details')){
                        switch (data.result.order_details.type) {
                            case "tesco_washcare_orders":
                                renderLabelViewOrderPage(data.result, user_type);
                                break;
                            case "tesco_swingticket_orders":
                                renderSwingTicketViewOrderPage(data.result, user_type);
                                break;
                            default:
                                createOrders(data.result, "viewOrder");
                        } 
                    } else {
                        createOrders(data.result, "viewOrder");
                    }
                   
                    
                },
                400: function() {
                    $('.snackbar_span').html("Sorry!! Couldn't process your request. Please try again later.");
                    showSnackbar();
                }
            },
            error: function(errorXHR, errorStatus, errorThrown) {
                console.log(errorThrown);
            },
            complete: function() {
                $(".loader").hide();
            }
        })
    } catch (e) {
        console.error("Error:" + e)
    }
}