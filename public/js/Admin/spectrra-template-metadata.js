$(function () {
  var form = $("#submit_spectrra_metadata_form");
  var chk = $("input.match-to-checkbox");
  var front_color_count = $("#front_color_count");
  var back_color_count = $("#back_color_count");

  //match to checkbox Change event
  chk.on("change", function (e) {
    checkboxValidator(chk, form, 1);
  });

  //front color count Change event
  front_color_count.on("change", function (e) {
    dynamicColorsDiv(e.target.value, 'Front');
  });

  //back color count Change event
  back_color_count.on("change", function (e) {
    dynamicColorsDiv(e.target.value, 'Back');
  });

  //Form submit
  form.on("submit", function (e) {
    e.preventDefault();
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
                <label for="${lower_side}_color_${index + 1}" class="form_label">${side} Color ${index + 1} :</label>
                <input class="form-control input-sm" placeholder="${side} Color" name="${lower_side}_color_${index + 1}" type="text" value="" id="${lower_side}_color_${index + 1}" spellcheck="false" data-ms-editor="true">
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
    success: function (data) {},
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
