$(function () {
  var form = $("#spectrra_metadata_form");
  var chk = $("input.match-to-checkbox");
  var front_color_count = $("#front_color_count");
  var back_color_count = $("#back_color_count");
  var lamination = $("#lamination");
  var pasting = $("#pasting");
  var window_patch = $("#window_patch");
  var hole = $("#hole");
  var eyelet = $("#eyelet");
  var string_required = $("#string_required");

  //match to checkbox Change event
  chk.on("change", function (e) {
    checkboxValidator(chk, form, 1);
  });

  //front color count Change event
  front_color_count.on("change", function (e) {
    dynamicColorsDiv(e.target.value, "Front");
  });

  //back color count Change event
  back_color_count.on("change", function (e) {
    dynamicColorsDiv(e.target.value, "Back");
  });

  //lamination Change event
  lamination.on("change", function (e) {
    e.target.value === "yes"
      ? $(".lamination-spec-div").removeClass("hidden")
      : $(".lamination-spec-div").addClass("hidden");
  });

  //pasting Change event
  pasting.on("change", function (e) {
    e.target.value === "yes"
      ? $(".pasting-board-div").removeClass("hidden")
      : $(".pasting-board-div").addClass("hidden");
  });

  //window patch gsm Change event
  window_patch.on("change", function (e) {
    e.target.value === "yes"
      ? $(".window-patch-gsm-div").removeClass("hidden")
      : $(".window-patch-gsm-div").addClass("hidden");
  });

  //hole Change event
  hole.on("change", function (e) {
    e.target.value === "yes"
      ? $(".hole-size-div").removeClass("hidden")
      : $(".hole-size-div").addClass("hidden");
  });

  //eyelet Change event
  eyelet.on("change", function (e) {
    e.target.value === "yes"
      ? $(".eyelet-spec-div").removeClass("hidden")
      : $(".eyelet-spec-div").addClass("hidden");
  });

  //string  Change event
  string_required.on("change", function (e) {
    e.target.value === "yes"
      ? $(".string-spec-div").removeClass("hidden")
      : $(".string-spec-div").addClass("hidden");
  });

  //Form submit
  form.on("submit", function (e) {
    if (e.isDefaultPrevented()) {
    } else {
      e.preventDefault();
      storeSpectrraMetadata(form);
    }
  });

  /** To refresh modal and form data**/
  $('#spectrraMetadataModal').on('hidden.bs.modal', function () {
      $(this).find('form').trigger('reset');
  });
});

const checkboxValidator = (group, form, goal) => {
  var isChecked = false;
  var goal = goal || 1; //Default at least one checkbox

  if (group.filter(":checked").length >= goal) {
    isChecked = true;
  }

  group.not(":checked").prop("required", !isChecked);
  form.validator("destroy").validator();
};

const dynamicColorsDiv = (color_count, side) => {
  var lower_side = side.toLowerCase();
  var html = ``;

  for (let index = 0; index < color_count; index++) {
    html += `<div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="${lower_side}_color_${
      index + 1
    }" class="form_label">${side} Color ${index + 1} :</label>
                <input class="form-control input-sm" placeholder="${side} Color" name="${lower_side}_color_${
      index + 1
    }" type="text" value="" id="${lower_side}_color_${
      index + 1
    }" spellcheck="false" data-ms-editor="true">
                <div class="help-block with-errors"></div>
            </div>
        </div>`;
  }
  $(`.${lower_side}-colors-div`).html(html);
};

const spectrraMetadata = (obj) => {
  // spectrra prints id 2
  obj.value == 2
    ? $(".spectrra-metadata-btn").removeClass("hidden")
    : $(".spectrra-metadata-btn").addClass("hidden");
};

const getSpectrraErpMetadata = (temp_id) => {
  var buyer = localStorage.getItem("buyer");
  $(`.front-colors-div`).html('');
  $(`.back-colors-div`).html('');

  $.ajax({
    url: base_url + "/admin/template-management/get-spectrra-company-metadata",
    type: "GET",
    dataType: "json",
    data: {
      template_id: temp_id,
      buyer: buyer,
    },
    beforeSend: function () {
      $(".loader").show();
      $(".loader").css("margin-top", "12%");
    },
    success: function (data) {
      var board_category_options =
        '<option value="" selected="selected">-- Select Board Category --</option>';
      $.each(data.board_category, function (key, item) {
        board_category_options +=
          '<option value="' + key + '">' + item + "</option>";
      });
      $("#board_category").html(board_category_options);

      var boards_options =
        '<option value="" selected="selected">-- Select Board Name --</option>';
      $.each(data.boards, function (key, item) {
        boards_options += '<option value="' + key + '">' + item + "</option>";
      });
      $("#board_name").html(boards_options);
      $(".selectpicker").selectpicker("refresh");
    },
    error: function (data) {
      $(".snackbar_span").html("Something went wrong!!");
      showSnackbar();
    },
    complete: function () {
      $(".loader").hide();
      $(".loader").css("margin-top", "0%");
    },
  });

  $("#spectrraMetadataModal").modal("show");
  $("#spectrraMetadataModal").removeData(".modal");
};

const storeSpectrraMetadata = (form) => {
  console.log(form.serialize());
  // $.ajax({
  //     type: "post",
  //     url: base_url + "/admin/template-management/submit-spectrra-metadata",
  //     data: form.serialize(),
  //     headers: {
  //         'X-CSRF-TOKEN': csrf_token
  //     },
  //     dataType: "json",
  //     beforeSend: function () {
  //         $(".loader").show();
  //         $('.swing-ticket-order-submit-btn').prop('disabled', true);
  //     },
  //     statusCode: {
  //         200: function (response) {

  //                 $('#hidden_id').val(response.data.last_insertion);

  //                 $emptyQty = $('.tesco-quantity-field').filter((index, quantity) => quantity.value != '' );
  //                 $emptyBarcode = $('.swing-ticket-ean').filter((index, ean) => ean.value != '');

  //                 if($emptyQty.length == 0 || $emptyBarcode.length == 0){
  //                     $('.create-download-artwork').remove();
  //                 } else  {
  //                     $('.create-download-artwork').css('display','inline-block');
  //                 }

  //                 $('#result').html('<div class="alert alert-success alert-dismissible" id="alert_success"><strong>Success! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your order has been placed successfully</div>');

  //                 $('.order-cancel-btn').show();
  //                 $(window).scrollTop(0);
  //                 postSwingTicketOrderSubmit(user_type, response.data);
  //             },
  //             400: function (error) {
  //                 $('#result').html('<div class="alert alert-danger alert-dismissible"><strong>Error! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong !! Try again</div>');
  //                 $('.swing-ticket-order-submit-btn').prop('disabled', false);
  //             },
  //             422: function (errorXHR) {
  //                 var error_msg = ``;
  //                 $.each(errorXHR.responseJSON.errors, function (indexInArray, valueOfElement) {
  //                     error_msg += `<li>` + valueOfElement + `</li>`;
  //                 });
  //                 $('#result').html('<div class="alert alert-danger">' + error_msg + '</div>');
  //                 $('.swing-ticket-order-submit-btn').prop('disabled', false);
  //                 $(window).scrollTop(0);
  //             },
  //     },
  //     complete: function () {
  //         $(".loader").hide();
  //     }
  // });
}
