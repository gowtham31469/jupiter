/**
 * Function for uploading combined po files
 * @author    <praveen>
 */
 const uploadCombinedPO = (user_type, formdata, templateName) => {
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
                 this.tryCount++;
                 if (this.tryCount <= this.retryLimit) {
                     $.ajax(this);
                     return;
                 }
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