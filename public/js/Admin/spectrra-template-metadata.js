$(function () {
  var form = $("#spectrra_metadata_form");
  var front_color_count = $("#front_color_count");
  var back_color_count = $("#back_color_count");
  var lamination = $("#lamination");
  var pasting = $("#pasting");
  var window_patch = $("#window_patch");
  var hole = $("#hole");
  var eyelet = $("#eyelet");
  var string_required = $("#string_required");

  //front color count Change event
  front_color_count.on("change", function (e) {
    dynamicColorsDiv(e.target.value, "Front", []);
  });

  //back color count Change event
  back_color_count.on("change", function (e) {
    dynamicColorsDiv(e.target.value, "Back", []);
  });

  //lamination Change event
  lamination.on("change", function (e) {
    e.target.value === "1"
      ? $(".lamination-spec-div").removeClass("hidden")
      : $(".lamination-spec-div").addClass("hidden");
  });

  //pasting Change event
  pasting.on("change", function (e) {
    e.target.value === "1"
      ? $(".pasting-board-div").removeClass("hidden")
      : $(".pasting-board-div").addClass("hidden");
  });

  //window patch gsm Change event
  window_patch.on("change", function (e) {
    e.target.value === "1"
      ? $(".window-patch-gsm-div").removeClass("hidden")
      : $(".window-patch-gsm-div").addClass("hidden");
  });

  //hole Change event
  hole.on("change", function (e) {
    e.target.value === "1"
      ? $(".hole-size-div").removeClass("hidden")
      : $(".hole-size-div").addClass("hidden");
  });

  //eyelet Change event
  eyelet.on("change", function (e) {
    e.target.value === "1"
      ? $(".eyelet-spec-div").removeClass("hidden")
      : $(".eyelet-spec-div").addClass("hidden");
  });

  //string  Change event
  string_required.on("change", function (e) {
    e.target.value === "1"
      ? $(".string-spec-div").removeClass("hidden")
      : $(".string-spec-div").addClass("hidden");
  });

  //Form submit
  form.validator().on('submit', function (e) {
    if (e.isDefaultPrevented()) {
    } else {
      e.preventDefault();
      storeSpectrraMetadata(form);
    }
  });

  /** To refresh modal and form data**/
  $('#spectrraMetadataModal').on('hidden.bs.modal', function () {
      $(this).find('form').trigger('reset');
      $('#spectrra_template_result').html('');
  });
});

const dynamicColorsDiv = (color_count, side, color_data) => {
  var lower_side = side.toLowerCase();
  var side_text = `${lower_side}_color_text`;
  var html = ``;

  for (let index = 0; index < color_count; index++) {
    html += `<div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="${lower_side}_color_${
      index + 1
    }" class="form_label">${side} Color ${index + 1} :</label>
                <input class="form-control input-sm" placeholder="${side} Color" name="${lower_side}_color_${
      index + 1
    }" type="text" value="${color_data.length > 0 ? color_data[index][side_text]:``}" id="${lower_side}_color_${
      index + 1
    }" spellcheck="false" data-ms-editor="true">
                <div class="help-block with-errors"></div>
            </div>
        </div>`;
  }
  $(`.${lower_side}-colors-div`).html(html);
};

const spectrraMetadata = (company) => {
  // spectrra prints id 2
  company == 2
    ? $(".spectrra-metadata-btn").removeClass("hidden")
    : $(".spectrra-metadata-btn").addClass("hidden");
};

const toggleDisabledSpectrraBtn = (company) => {
  // spectrra prints id 2
  company == 2
    ? $(".spectrra-metadata-btn").prop("disabled", false)
    : $(".spectrra-metadata-btn").prop("disabled", true);
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
      board_category_dropdown(data.board_category);
      boards_dropdown(data.boards);
      color_match_dropdown(data.color_match);
      $('#template_id_spectrra').val(temp_id);
      $('#buyer_spectrra').val(buyer);
      if(!$.isEmptyObject(data.spectrra_template_data)) render_spectrra_template_data(data.spectrra_template_data);
      $(".selectpicker").selectpicker("refresh");
    },
    error: function (data) {
      $(".snackbar_span").html("Something went wrong!!");
      showSnackbar();
    },
    complete: function () {
      $(".loader").hide();
      $(".loader").css("margin-top", "0%");
      
      $("#spectrraMetadataModal").modal("show");
      $('.label-order-submit-btn').prop('disabled', false);
    },
  });

};

const board_category_dropdown = (board_category) => {
  var board_category_options =
    '<option value="" selected="selected">-- Select Board Category --</option>';
  $.each(board_category, function (key, item) {
    board_category_options +=
      '<option value="' + key + '">' + item + "</option>";
  });
  $("#board_category").html(board_category_options);
}

const boards_dropdown = (boards) => {
  var boards_options =
    '<option value="" selected="selected">-- Select Board Name --</option>';
  $.each(boards, function (key, item) {
    boards_options += '<option value="' + key + '">' + item + "</option>";
  });
  $("#board_name").html(boards_options);
}

const color_match_dropdown = (color_match) => {
  var color_match_options =``;
  $.each(color_match, function (key, item) {
    color_match_options += '<option value="' + key + '">' + item + "</option>";
  });
  $("#color_match").html(color_match_options);
}

const render_spectrra_template_data = (data) => {

  $('#template_id_spectrra').val(data.template_id);
  $('#buyer_spectrra').val(localStorage.getItem("buyer"));

  data.color_bar === 1 
  ? $('#color_bar_yes').prop('checked', true) 
  : $('#color_bar_no').prop('checked', true);

  data.print_spec_bar === 1 
  ? $('#print_spec_bar_yes').prop('checked', true) 
  : $('#print_spec_bar_no').prop('checked', true);

  data.tone_bar === 1 
  ? $('#tone_bar_yes').prop('checked', true) 
  : $('#tone_bar_no').prop('checked', true);

  $('#board_category').val(data.board_category_id);
  $('#board_name').val(data.board_id);
  $('#fsc_non_fsc').val(data.fsc_non_fsc);
  $('#gsm').val(data.gsm_value);
  $('#thickness').val(data.thickness);
  $('#board_size_length').val(data.board_size_length);
  $('#board_size_width').val(data.board_size_width);
  $('#print_size_length').val(data.print_size_length);
  $('#print_size_width').val(data.print_size_width);
  $('#design_size_length').val(data.design_size_length);
  $('#design_size_width').val(data.design_size_width);
  $('#cut_as_1').val(data.cut_as.split("/")[0]);
  $('#cut_as_2').val(data.cut_as.split("/")[1]);
  $('#front_color_count').val(data.front_color_count).trigger('change');
  $('#back_color_count').val(data.back_color_count).trigger('change');
  $('#repeats').val(data.repeats);
  $('#no_of_plates').val(data.no_of_plates);
  $('#cutting').val(data.cutting);
  $('#printing').val(data.printing);
  $('#coating').val(data.coating);
  $('#uv').val(data.uv);
  $('#lamination').val(data.lamination).trigger('change');
  $('#lamination_spec').val(data.lamination_spec);
  $('#pasting').val(data.pasting).trigger('change');
  $('#pasting_board').val(data.pasting_board);
  $('#pasting_gsm').val(data.pasting_gsm);
  $('#die_cutting').val(data.die_cutting);
  $('#window_patch').val(data.window_patch).trigger('change');
  $('#window_patch_film').val(data.window_patch_film);
  $('#folder_gluer').val(data.folder_gluer);
  $('#embossing').val(data.embossing);
  $('#debossing').val(data.debossing);
  $('#perforation').val(data.perforation);
  $('#hole').val(data.hole).trigger('change');
  $('#hole_size').val(data.hole_size);
  $('#eyelet').val(data.eyelet).trigger('change');
  $('#eyelet_spec').val(data.eyelet_spec);
  $('#string_required').val(data.string_required).trigger('change');
  $('#string_spec').val(data.string_spec);
  $('#foil').val(data.foil);

  $.each(data.color_matches, function(key, item) {
      $('#color_match option:contains('+ item.color_match_text +')').prop('selected', true);
  });

  dynamicColorsDiv(data.front_color_count, "Front", data.front_colors);
  dynamicColorsDiv(data.back_color_count, "Back", [{'back_color_text': data.back_color_text}]);
}

const storeSpectrraMetadata = (form) => {
  $.ajax({
    type: "post",
    url: base_url + "/admin/template-management/submit-spectrra-metadata",
    data: form.serialize(),
    headers: {
      'X-CSRF-TOKEN': csrf_token
    },
    dataType: "json",
    beforeSend: function () {
      $(".loader").show();
      $('#update_spectrra_metadata_btn').prop('disabled', true);
    },
    statusCode: {
      200: function (response) {
        $('#spectrra_template_result').html('<div class="alert alert-success alert-dismissible" id="alert_success"><strong>Success! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Template data updated successfully</div>');
      },
      400: function (error) {
        $('#spectrra_template_result').html('<div class="alert alert-danger alert-dismissible"><strong>Error! </strong> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong !! Try again</div>');
      },
      422: function (errorXHR) {
        var error_msg = ``;
        $.each(errorXHR.responseJSON.errors, function (indexInArray, valueOfElement) {
          error_msg += `<li>` + valueOfElement + `</li>`;
        });
        $('#spectrra_template_result').html('<div class="alert alert-danger">' + error_msg + '</div>');
      },
    },
    complete: function () {
      $(".loader").hide();
      $('#update_spectrra_metadata_btn').prop('disabled', false);
      $(window).scrollTop(0);
    }
  });
}
