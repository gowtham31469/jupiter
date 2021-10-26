/**
 * [saveSingleStickers for drafting tesco single Templates manual orders]
 * @author <praveen>
 */
saveSingleStickers = (user_type) => {
	try {

        var template_data = JSON.parse(localStorage.getItem("data"));

		if (user_type === 'admin') {
			tescoAdminOrderCommonData();
		} else {
			tescoOrderCommonData();
		}
		
        var sticker_count = {};
		var orms_tpn = [];
		var barcode = [];
		var case_size = [];
		var size = [];
		var is_insert = $('.save-btn').data('insert');
		var qr_code = [];
		var barcode_array = [];

        if (zone === 'CE') {

            if ($.inArray(template_data[0].template_name, itf_barcode_stickers) != -1) {
				$('.ce_ratio_itf').each(function (i, item) {
					barcode[i] = $(item).val();
					barcode_array.push($(item).val());

				});
				barcode = barcode.join(',');
			} else {
				$('.ce_single_ean').each(function (i, item) {
					barcode[i] = $(item).val();
					barcode_array.push($(item).val());

				});
				barcode = barcode.join(',');
			}
    
            $('.ce_single_orms_tpn').each(function (i, item) {
                orms_tpn[i] = $(item).val();
    
                // qr code format : {Buyer PO No,Barcode/last 3 digits of tpnd no,tpnd no}
                qr_code[i] = po_number+','+barcode_array[i]+'/'+($(item).val().substr($(item).val().length - 3))+','+$(item).val(); 
    
            });
            orms_tpn = orms_tpn.join(',');
            qr_code = qr_code.join('|');
    
            $('.ce_single_case_size').each(function (i, item) {
                case_size[i] = $(item).val() === '' ? 0 : $(item).val();
    
            });
            case_size = case_size.join(',');
    
            $('.ce_single_size').each(function (i, item) {
                size[i] = $(item).val();
    
            });
            size = size.join(',');
        } else {

            if ($.inArray(template_data[0].template_name, ean_barcode_stickers) != -1) {
				$('.uk_single_ean').each(function (i, item) {
					barcode[i] = $(item).val();

				});
				barcode = barcode.join(',');
			} else {
				$('.uk_ratio_itf').each(function (i, item) {
					barcode[i] = $(item).val();

				});
				barcode = barcode.join(',');
			}

            $('.uk_single_tpnd').each(function (i, item) {
                orms_tpn[i] = $(item).val();
    
            });
            orms_tpn = orms_tpn.join(',');
    
            $('.uk_single_case_size').each(function (i, item) {
                case_size[i] = $(item).val() === '' ? 0 : $(item).val();
    
            });
            case_size = case_size.join(',');
    
            $('.uk_single_size').each(function (i, item) {
                size[i] = $(item).val();
    
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
			url: base_url + "/"+user_type+"/tesco/save-single-sticker-orders",
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
				sticker_count: sticker_count,
				templates: data,
				is_insert: is_insert,
				ocn_no: ocn_no,
				full_description:full_description,
				qr_code: qr_code,
				created_by: created_by,
			},
			tryCount: 0,
			retryLimit: 2,
			dataType: "json",
			beforeSend: function () {
				$(".loader").show();
			},
            statusCode: {
				200: function (response) {
					$('.snackbar_span').html('Saved Successfully');
                    showSnackbar();

                    $('.save-btn').data('insert','false');

                    var div_html='';
                    /** [ store last updated id as hidden input , for updating] */
                    $.each(response.last_insertions, function (index, value) {
                        div_html += '<input class="table-id-hidden" id="'+index.replace(' ','')+'_id" name="'+index.replace(' ','')+'_id" type="hidden" value="'+value+'">';
                    });
                    $('.invisible-div').html(div_html);
				},
				400: function (error) {
					$('#result').html('<div class="alert alert-danger alert-dismissible"><strong>Error! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong !! Try again</div>');
				},
				409: function (response) {
					var html_content = '<div class="alert alert-warning alert-dismissible" role="alert">\
                             <strong class="alert-heading">Whoops! An order has been already placed using this PO number and Style number.</strong>\
                              <button type="button" class="close close-btn" data-dismiss="alert" aria-label="Close">\
                                <span aria-hidden="true">×</span>\
                              </button>\
							  </div>';

                    $('#order-exists-msg').html(html_content);
                    $('#warningModal').modal('show');
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
		console.error("Error:" + e)
	}
}