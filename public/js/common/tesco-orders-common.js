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