storeSingleSticker = (user_type) => {

	var sticker_zero_qty = [];
	var sticker_qty = [];
	var template_data = JSON.parse(localStorage.getItem("data"));
	var zone = localStorage.getItem("zone").toLowerCase();

	template_data.forEach(element => {

		var sticker_single_qty = [];
		var sticker_name = element.template_name.replace(/\s/g, '');

		$('.count_' + sticker_name).each(function (i, item) {
			qty = $(item).val() === '' ? 0 : $(item).val();
			sticker_qty.push(qty);
			sticker_single_qty.push(qty);
		});

		let zero_qty = sticker_single_qty.every(e => e == 0);

		if (zero_qty) {
			sticker_zero_qty.push(element.template_name);
		}

	});

	let result = sticker_qty.every(e => e == 0);

	if (result) {
		$('.quantity-feedback').addClass('has-error has-danger');
		$('.quantity-feedback-err-msg').css('display', 'block');
		$('.quantity-field').prop('required', true);

		return false;
	}

	/** [Check if valid barcode entered] */
	if (barcode_checks === 0) {
		$('.quantity-feedback-err-msg').css('display', 'none');
		$('.quantity-field').prop('required', false);
		var msg = "";
		/** If artwork count array contains atleast one '0' OR empty value , Show confirmation modal  **/
		if (sticker_zero_qty.length > 0) {

			sticker_zero_qty.forEach(element => {
				msg += '<li style="margin-bottom: 10px;"><strong>' + element + '</strong></li>';
			});
			$('#body_msg').html(msg);

			/** [ Backdrop for bootstrap modal ] */
			$('#confirmOrder').modal({
				backdrop: 'static',
				keyboard: false
			});
			$('#confirm_btn').attr('onclick', 'submitSingleSticker("' + user_type + '")');
			$('#confirmOrder').modal('show');

		} else {
			/** Function to store single template */
			submitSingleSticker(user_type);
			if (user_type === 'admin') {
				var set_po_number = $('#tesco_po_number').val();
				var set_style = $('#tesco_' + zone + '_style').val();
				template_data.forEach((element, index) => {
					printDivTescoSet('tesco_svg' + index, element.template_name.replace(/\s/g, ''), set_po_number, set_style)
				});
			}
		}
	}
}

/**
 * [submitSingleSticker for storing tesco single Templates manual orders]
 * @author <praveen>
 */
submitSingleSticker = (user_type) => {
	try {

		var template_data = JSON.parse(localStorage.getItem("data"));
		const stickersList = template_data.map(template => template.template_name);

		if (user_type === 'admin') {
			tescoAdminOrderCommonData();
		} else {
			tescoOrderCommonData();
		}

		var orms_tpn = [];
		var ean_barcode = [];
		var itf_barcode = [];
		var case_size = [];
		var size = [];
		var sticker_count = {};
		var barcode_array = [];

		if (zone === 'CE') {

			if ($.inArray(stickersList[stickersList.length-1], itf_barcode_stickers) != -1) {
				$('.ce_ratio_itf').each(function (i, item) {
					itf_barcode[i] = $(item).val();
					barcode_array.push($(item).val());

				});
				itf_barcode = itf_barcode.join(',');
			} else if ($.inArray(stickersList[stickersList.length-1], ean_barcode_stickers) != -1) {
				$('.ce_single_ean').each(function (i, item) {
					ean_barcode[i] = $(item).val();
					barcode_array.push($(item).val());

				});
				ean_barcode = ean_barcode.join(',');
			} else {
				$('.ce_ratio_itf').each(function (i, item) {
					itf_barcode[i] = $(item).val();
				});
				itf_barcode = itf_barcode.join(',');

				$('.ce_single_ean').each(function (i, item) {
					ean_barcode[i] = $(item).val();
					barcode_array.push($(item).val());

				});
				ean_barcode = ean_barcode.join(',');
			}

			$('.ce_single_orms_tpn').each(function (i, item) {
				orms_tpn[i] = $(item).val();

			});
			orms_tpn = orms_tpn.join(',');

			$('.ce_single_case_size').each(function (i, item) {
				case_size[i] = $(item).val();

			});
			case_size = case_size.join(',');

			$('.ce_single_size').each(function (i, item) {
				size[i] = $(item).val() === '' ? 0 : $(item).val();

			});
			size = size.join(',');

		} else {
			if ($.inArray(stickersList[stickersList.length-1], itf_barcode_stickers) != -1) {
				$('.uk_ratio_itf').each(function (i, item) {
					itf_barcode[i] = $(item).val();

				});
				itf_barcode = itf_barcode.join(',');
			} else if ($.inArray(stickersList[stickersList.length-1], ean_barcode_stickers) != -1) {
				$('.uk_single_ean').each(function (i, item) {
					ean_barcode[i] = $(item).val();

				});
				ean_barcode = ean_barcode.join(',');
			} else {
				$('.uk_ratio_itf').each(function (i, item) {
					itf_barcode[i] = $(item).val();
				});
				itf_barcode = itf_barcode.join(',');

				$('.uk_single_ean').each(function (i, item) {
					ean_barcode[i] = $(item).val();

				});
				ean_barcode = ean_barcode.join(',');
			}

			$('.uk_single_tpnd').each(function (i, item) {
				orms_tpn[i] = $(item).val();

			});
			orms_tpn = orms_tpn.join(',');

			$('.uk_single_case_size').each(function (i, item) {
				case_size[i] = $(item).val();

			});
			case_size = case_size.join(',');

			$('.uk_single_size').each(function (i, item) {
				size[i] = $(item).val() === '' ? 0 : $(item).val();

			});
			size = size.join(',');
		}

		template_data.forEach(element => {
			var sticker_name_trimmed = element.template_name.replace(/\s/g, '');
			var quantity = [];
			$('.count_' + sticker_name_trimmed).each(function (i, item) {
				let value = $(item).val() === '' ? 0 : $(item).val();
				quantity.push(value)
			});

			sticker_count[element.template_name] = quantity;
		});

		$.ajax({
			url: base_url + "/" + user_type + "/tesco/store-single-sticker-orders",
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
				ean_barcode: ean_barcode,
				itf_barcode:itf_barcode,
				case_size: case_size,
				size: size,
				sticker_count: sticker_count,
				templates: data,
				ocn_no: ocn_no,
				full_description: full_description,
				created_by: created_by,
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
				$('.submit-btn').prop('disabled', true);
				$('#result').html('');
			},
			statusCode: {
				200: function (response) {
					/** Call  afterAdminOrderSubmitted() */
					var stl_no = (zone === "CE") ? ce_style : uk_style;

					if (user_type === 'admin') {
						afterAdminOrderSubmitted(response, po_number, stl_no)
					} else {
						afterOrderSubmitted(response, po_number, stl_no)
					}
				},
				400: function (error) {
					$('#result').html('<div class="alert alert-danger alert-dismissible"><strong>Error! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong !! Try again</div>');
					$('.submit-btn').prop('disabled', false);
				},
				409: function (response) {
					var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                             <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                              <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                <span aria-hidden="true">×</span>\
                              </button>\
							  </div>';

					$('#order-exists-alert').html(html_content);

					/** [ Show only alertbox if data already exists ] */
					$('.po-modal-content').hide();
					$('.close-btn').attr('data-dismiss', 'modal')
					$('#poUploadModal').modal('show');
					/** [ Backdrop for bootstrap modal ] */
					$('#poUploadModal').modal({
						backdrop: 'static',
						keyboard: false
					});
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

validateCaseSizeQty = (obj) => {
	if ($(obj).data("artwork") === 'TSL 2' && $(obj).parents().eq(1).siblings('td.case_size_td').find('.ce_single_case_size').val() === '1') {
		alert('You are not allowed to enter quantity of TSL 2 stickers. Because the Units Per Case value is 1.');
		obj.value = ''
	}

	if ($(obj).data("artwork") === 'TSL 3' && $(obj).parents().eq(1).siblings('td.case_size_td').find('.ce_single_case_size').val() > '1') {
		alert('You are not allowed to enter quantity of TSL 3 stickers. Because the Units Per Case value is greater than 1.');
		obj.value = ''
	}

}

clearQuantity = (obj) => {
	$(obj).parents().eq(1).siblings('td').find('.quantity-field').val('');
}