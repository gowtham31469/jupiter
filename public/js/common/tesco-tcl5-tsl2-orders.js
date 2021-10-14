

function storeTCL5TSL2(sticker_name, user_type) {

	var sticker_qty = [];
	
    $('.count_'+sticker_name.replace(/\s/g, '')).each(function (i, item) {
        sticker_qty[i] = $(item).val() === '' ? 0 : $(item).val();

    });

    /** [ Create single array of atrworks, for validation ] */
    var empty_count = 0;
    var l = sticker_qty.length;
    for (var i = 0; i < l; i++) {
        if (sticker_qty[i] === 0 || sticker_qty[i] === '0' || sticker_qty[i] === '00' || sticker_qty[i] === '000' || sticker_qty[i] === '0000') {
            empty_count += 1;
        }
    }

    /** [ Join artworks count array using ' , '] */
    sticker_qty = sticker_qty.join(',');

    /** [Check if valid barcode entered] */
    if (barcode_checks === 0) {
        /** [ Check if count of empty quantity in the array is equal to array length ] */
        if (empty_count === l) {
            /** All quantity value fields empty - Show custom validation message and functionality  */
            $('.quantity-feedback').addClass('has-error has-danger');
            $('.quantity-feedback-err-msg').css('display', 'block');
            $('.quantity-field').prop('required', true)
        } else {
            /** All quantity value fields empty - hide custom validation message and functionality  */
            $('.quantity-feedback-err-msg').css('display', 'none');
            $('.quantity-field').prop('required', false)
            /** If artwork count array contains atleast one '0' OR empty value , Show confirmation modal  **/

            if (empty_count > 0) {
                $('#body_msg').html(msg);
                /** [ Backdrop for bootstrap modal ] */
                $('#confirmOrder').modal({
                    backdrop: 'static',
                    keyboard: false
                })
                $('#confirm_btn').attr('onclick', 'storeSingleTCL5TSL2(this, sticker_name, user_type);');
                $('#confirmOrder').modal('show');
            } else {
                /** Function to store single TCL5, TSL2 template */
                storeSingleTCL5TSL2(sticker_name, user_type);
                var set_po_number 		 = $('#tesco_po_number').val();
                var set_style 		 = $('#tesco_ce_style').val();
                printDivTescoSet('tesco_svg0',sticker_name.replace(/\s/g, ''),set_po_number,set_style)
            }
        }
    }
}

/**
 * [storeSingleTCL5TSL2 for storing tesco single TCL5 and TSL2 Templates manual orders]
 * @author <praveen>
 */
 function storeSingleTCL5TSL2(sticker_name, user_type) {
	try {

		user_type = user_type.toLowerCase();
		if( user_type === 'admin'){
			tescoAdminOrderCommonData();
		} else {
			tescoOrderCommonData();
		}

		var orms_tpn = [];
		var barcode = [];
		var case_size = [];
		var size = [];
		var sricker_count = [];
		var is_insert = $('.save-btn').data('insert');
		var sticker_last_id = $('#'+sticker_name.replace(/\s/g, '')+'_id').val();
		var qr_code = [];
		var barcode_array = [];

		$('.ce_ratio_itf').each(function (i, item) {
			barcode[i] = $(item).val();
			barcode_array.push($(item).val()); 

		});
		barcode = barcode.join(',');

		$('.ce_single_orms_tpn').each(function (i, item) {
			orms_tpn[i] = $(item).val();

			// qr code format : {Buyer PO No,Barcode/last 3 digits of tpnd no,tpnd no}
			qr_code[i] = po_number+','+barcode_array[i]+'/'+($(item).val().substr($(item).val().length - 3))+','+$(item).val(); 

		});
		orms_tpn = orms_tpn.join(',');
		qr_code = qr_code.join('|');

		$('.ce_single_case_size').each(function (i, item) {
			case_size[i] = $(item).val();

		});
		case_size = case_size.join(',');

		$('.ce_single_size').each(function (i, item) {
			size[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		size = size.join(',');

		$('.count_'+sticker_name.replace(/\s/g, '')).each(function (i, item) {
			sricker_count[i] = $(item).val() === '' ? 0 : $(item).val();

		});
		sricker_count = sricker_count.join(',');

		$.ajax({
			url: base_url + "/"+user_type+"/tesco/store-single-sticker-orders",
			type: 'POST',
			data: {
				_token: csrf_token,
				po_number: po_number,
				uk_style: uk_style,
				ce_style: ce_style,
				season_code: season_code,
				description: description,
				eqos: eqos,
				manufacture_date: manufacture_date,
				department: department,
				brand: brand,
				supplier: supplier,
				rfid: rfid,
				tag_at_source: tag_at_source,
				zone: zone,
				type: type,
				orms_tpn: orms_tpn,
				barcode: barcode,
				case_size: case_size,
				size: size,
				sricker_count: sricker_count,
				templates: data,
				is_insert: is_insert,
				sticker_last_id: sticker_last_id,
				ocn_no: ocn_no,
				full_description:full_description,
				qr_code:qr_code,
				created_by:created_by,
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
				$('.submit-btn').prop('disabled', true);
			},
			statusCode: {
                200: function (response) {
                        /** Call  afterAdminOrderSubmitted() */
						var stl_no =  (zone=="CE")? ce_style : uk_style;

						if( user_type === 'admin'){
							afterAdminOrderSubmitted(data,po_number,stl_no)
						} else {
							afterOrderSubmitted(data,po_number,stl_no)
						}
                    },
                    400: function (error) {
                        $('#result').html('<div class="alert alert-danger alert-dismissible"><strong>Error! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong !! Try again</div>');
                        $('.submit-btn').prop('disabled', false);
                    },
                    422: function (errorXHR) {
                        var error_msg = ``;
                        $.each(errorXHR.responseJSON.errors, function (indexInArray, valueOfElement) {
                            error_msg += `<li>` + valueOfElement + `</li>`;
                        });
                        $('#result').html('<div class="alert alert-danger">' + error_msg + '</div>');
                        $('.submit-btn').prop('disabled', false);
                        $(window).scrollTop(0);
                    }
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
		console.log("Error:" + e)
	}
}