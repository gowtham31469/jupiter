$(function () {
  var form = $("#create_spectrra_jc_form");

  form.validator().on("submit", function (e) {
    if (e.isDefaultPrevented()) {
    } else {
      e.preventDefault();
      alert();
      //   storeSpectrraMetadata(form);
    }
  });
});

const getSpectrraOrderDetails = (checked_count, erp_id_array, uuid_array) => {

  if(checked_count > 1) {
      $('.snackbar_span').html("Please select only a maximum of 1 orders.");
      showSnackbar();
      return false;
  }

  $.ajax({
    type: "get",
    url: base_url + "/erp/spectrra-job-manager/get-spectrra-order-details",
    data: {uuid_array, erp_id_array},
    dataType: "json",
    beforeSend: function () {
      $(".loader").show();
    },
    statusCode: {
        200: function(data) {
          renderSpectrraOrderData(data);

          $('#createSpectrraJCModal').modal({
              backdrop: 'static',
              keyboard: false
          });
          $('#create_spectrra_jc_form').validator('update');
          $('#createSpectrraJCModal').modal('show');
          // enable print button (for old version browsers)
          $('#jc_print_btn').prop('disabled', false);
          
        },
        400: function() {
            $('.snackbar_span').html("Parameter is missing !!!");
            showSnackbar();
        },
        422: function (errorXHR) {
            var error_msg = ``;
            $.each(errorXHR.responseJSON.errors, function (indexInArray, valueOfElement) {
                error_msg += `<li>` + valueOfElement + `</li>`;
            });
            $('#nat_result').html('<div class="alert alert-danger">' + error_msg + '</div>');
            $('#jc_print_btn').prop('disabled', false);
            $(window).scrollTop(0);
        }
    },
    error: function (errorXHR, errorStatus, errorThrown) {
        console.log(errorThrown);
    },
    complete: function () {
        $(".loader").hide();
    }
  });

  $('#createSpectrraJCModal').modal({
      backdrop: 'static',
      keyboard: false
  });
  $('#create_spectrra_jc_form').validator('update');
  $('#createSpectrraJCModal').modal('show');
};

const renderSpectrraOrderData = (order_data) => {
  console.log(order_data);
  // $('#nat_template_type').val(data.template_data.template_type[0]);
  $('.spectrra-create-jc-modal-title').text(order_data.modal_title);
  $('#crud_operation_type').text('created_at');
  // $('#material_hierarchy').text(data.template_data.material_hierarchy)
}
