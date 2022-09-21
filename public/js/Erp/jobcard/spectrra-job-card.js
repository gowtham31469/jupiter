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

const getSpectrraOrderDetails = (checked_count) => {

  if(checked_count > 1) {
      $('.snackbar_span').html("Please select only a maximum of 1 orders.");
      showSnackbar();
      return false;
  }

  var buyerArray = [];
  var customerArray = [];
  var uuid_array = [];
  var erp_id_array = [];
  $('input[class="row-checkbox"]').each(function (i, item) {
    if ($(this).is(":checked")) {
      buyerArray.push($(this).data("buyer-name"));
      customerArray.push($(this).data("customer-name"));

      uuid_array.push($(this).data("uuid"));
      erp_id_array.push($(this).data("erp-id"));
    }
  });

  uuid_array = uuid_array.join(",");
  erp_id_array = erp_id_array.join(",");

  if ([...new Set(buyerArray)].length != 1) {
    $(".snackbar_span").html("Please select the same buyer orders.");
    showSnackbar();
    return false;
  }

  if ([...new Set(customerArray)].length != 1) {
    $(".snackbar_span").html("Please select the same customer orders.");
    showSnackbar();
    return false;
  }

  $.ajax({
    type: "get",
    url: "url",
    data: "data",
    dataType: "dataType",
    success: function (response) {
      
    }
  });

  $('#createSpectrraJCModal').modal({
      backdrop: 'static',
      keyboard: false
  });
  $('#create_spectrra_jc_form').validator('update');
  $('#createSpectrraJCModal').modal('show');
};
