$(function () {
  var form = $("#create_spectrra_jc_form");

  form.validator().on("submit", function (e) {
    if (e.isDefaultPrevented()) {
    } else {
      e.preventDefault();
        storeSpectrraJobcard(form);
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

  board_category_dropdown(order_data.board_category);
  boards_dropdown(order_data.boards);
  color_match_dropdown(order_data.color_match);

  $('#sp_jc_company_name').text(order_data.order_details.company.name);
  $('#sp_company_address').text(order_data.order_details.company.address);
  $('#sp_company_logo').attr('src', order_data.order_details.company.logo);
  $('#sp_jc_company_name').attr('data-company-id', order_data.order_details.company.id);
  $('#sp_work_order').text(order_data.workorder_id);
  $('#sp_work_order').attr('data-wo-id', order_data.workorder_id);

  $('#sp_today_date').text(order_data.today_date);
  $('#sp_today_date').attr('data-today-date', order_data.today_date);
  $('#sp_article').text(order_data.template_details.article);
  $('#sp_article').attr('data-article', order_data.template_details.article);
  $('#sp_reference').val(order_data.order_details.reference_name);

  $('#sp_customer_name').text(order_data.order_details.orders.customer.short_name);
  $('#sp_style_no').text(order_data.order_details.style_no);
  $('#sp_customer_po').text(order_data.order_details.orders.po_no);
  $('#sp_po_verify_date').text(order_data.order_details.po_verified_date);
  $('#sp_buyer_name').text(order_data.order_details.orders.buyer.buyer_name);
  $('#sp_order_no').text(order_data.order_details.orders.erp_id);
  $('#sp_order_no').attr('data-erpid', order_data.order_details.orders.erp_id);
  $('#sp_order_date').text(order_data.order_details.order_date);
  $('#sp_delivery_date').text(order_data.order_details.delivery_date);
  $('#sp_order_qty').text(order_data.order_details.total_qty);

  if ( order_data.spectrra_template_data != null ) {
    $('#color_bar').val(order_data.spectrra_template_data.color_bar);
    $('#print_spec_bar').val(order_data.spectrra_template_data.print_spec_bar);
    $('#tone_bar').val(order_data.spectrra_template_data.tone_bar);
    $('#back_colors').val(order_data.spectrra_template_data.back_color_text);
    $('#board_category').val(order_data.spectrra_template_data.board_category_id);
    $('#board_name').val(order_data.spectrra_template_data.board_id);
    $('#fsc_non_fsc').val(order_data.spectrra_template_data.fsc_non_fsc);
    $('#gsm_value').val(order_data.spectrra_template_data.gsm_value);
    $('#thickness').val(order_data.spectrra_template_data.thickness);
    $('#board_size_length').val(order_data.spectrra_template_data.board_size_length);
    $('#board_size_width').val(order_data.spectrra_template_data.board_size_width);
    $('#print_size_length').val(order_data.spectrra_template_data.print_size_length);
    $('#print_size_width').val(order_data.spectrra_template_data.print_size_width);
    $('#design_size_length').val(order_data.spectrra_template_data.design_size_length);
    $('#design_size_width').val(order_data.spectrra_template_data.design_size_width);
    $('#cut_as_1').val(order_data.spectrra_template_data.cut_as.split("/")[0]);
    $('#cut_as_2').val(order_data.spectrra_template_data.cut_as.split("/")[1]);
    $('#repeats').val(order_data.spectrra_template_data.repeats);
    $('#cutting').val(order_data.spectrra_template_data.cutting);
    $('#printing').val(order_data.spectrra_template_data.printing);
    $('#coating').val(order_data.spectrra_template_data.coating);
    $('#uv').val(order_data.spectrra_template_data.uv);
    $('#lamination_spec').val(order_data.spectrra_template_data.lamination_spec);
    $('#pasting_board').val(order_data.spectrra_template_data.pasting_board);
    $('#pasting_gsm').val(order_data.spectrra_template_data.pasting_gsm);
    $('#die_cutting').val(order_data.spectrra_template_data.die_cutting);
    $('#window_patch').val(order_data.spectrra_template_data.window_patch_film);
    $('#folder_gluer').val(order_data.spectrra_template_data.folder_gluer);
    $('#embossing').val(order_data.spectrra_template_data.embossing);
    $('#debossing').val(order_data.spectrra_template_data.debossing);
    $('#perforation').val(order_data.spectrra_template_data.perforation);
    $('#hole_size').val(order_data.spectrra_template_data.hole_size);
    $('#eyelet_spec').val(order_data.spectrra_template_data.eyelet_spec);
    $('#string_spec').val(order_data.spectrra_template_data.string_spec);
    $('#foil').val(order_data.spectrra_template_data.foil);

    $.each(order_data.spectrra_template_data.color_matches, function (key, item) {
      $('#color_match option:contains(' + item.color_match_text + ')').prop('selected', true);
    });

    var front_colors_string = '';
    $.each(order_data.spectrra_template_data.front_colors, function (key, item) {
      front_colors_string += `${item.front_color_text},`
    });
    $('#front_colors').val(front_colors_string.replace(/,\s*$/, ""));
  }


  $('.selectpicker').selectpicker('refresh');
  // $('#nat_template_type').val(data.template_data.template_type[0]);
  $('.spectrra-create-jc-modal-title').text(order_data.modal_title);
  $('#crud_operation_type').text('created_at');
  // $('#material_hierarchy').text(data.template_data.material_hierarchy)
}

const storeSpectrraJobcard = (form) => {
    var form_obj = [{
        "company_id": $('#sp_jc_company_name').data("company-id"),
        "workorder_id": $('#sp_work_order').data("wo-id"),
        "today_date": $('#sp_today_date').data("today-date"),
        "article": $('#sp_article').data("article"),
        "erp_id": $('#sp_order_no').data("erpid"),
    }];

    var formData = form.serialize() + '&' + $.param(form_obj[0]);

  $.ajax({
    type: "post",
    url: base_url + "/erp/spectrra-job-manager/store-spectrra-jobcard",
    data: formData,
    headers: {
      'X-CSRF-TOKEN': csrf_token
    },
    dataType: "json",
    beforeSend: function () {
      $(".loader").show();
      // $('#update_spectrra_metadata_btn').prop('disabled', true);
    },
    statusCode: {
      200: function (response) {
        
      },
      400: function (error) {
        
      },
      422: function (errorXHR) {
        var error_msg = ``;
        $.each(errorXHR.responseJSON.errors, function (indexInArray, valueOfElement) {
          error_msg += `<li>` + valueOfElement + `</li>`;
        });
        $('#spectrra_jobcard_result').html('<div class="alert alert-danger">' + error_msg + '</div>');
      },
    },
    complete: function () {
      $(".loader").hide();
      // $('#update_spectrra_metadata_btn').prop('disabled', false);
      $(window).scrollTop(0);
    }
  });
}
