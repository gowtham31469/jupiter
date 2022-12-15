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
          $('#create_spectrra_jc_form').validator('validate');
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
};

const renderSpectrraOrderData = (order_data) => {

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
  $('#sp_order_no').attr('data-erpid', order_data.order_details.erp_order_id);
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
  $('#material_hierarchy').text(order_data.template_details.article)
}

const storeSpectrraJobcard = (form) => {
    var form_obj = [{
        "company_id": $('#sp_jc_company_name').data("company-id"),
        "workorder_id": $('#sp_work_order').data("wo-id"),
        "today_date": $('#sp_today_date').data("today-date"),
        "article": $('#sp_article').data("article"),
        "erp_id": $('#sp_order_no').data("erpid"),
        "crud_operation_type": $('#crud_operation_type').text(),
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
      $('#create_spectrra_jc_btn').prop('disabled', true);
    },
    statusCode: {
      200: function (response) {
        var resp = JSON.stringify(response.file_path); 

        localStorage.setItem('file_path',response.file_path)

        if($('#crud_operation_type').text() == "created_at")
        {
          var isEdit        = false;
          $('.row-checkbox', allPages).each(function (i, item) {
          
              if ($(this).is(':checked')) {
                  refname       = $(this).closest('tr').data('refname'); 
                  id            = $(this).closest('tr').attr('id');
                  buyer_po_no   = $(this).closest('tr').data('buyer-po');
                  order_type    = $(this).closest('tr').data('order-type');
                  created_at    = $(this).closest('tr').data('created-at');
                  style_no      = $(this).closest('tr').data('style-no');
                  loop          = $(this).closest('tr').data('iteration');
                  template_name = $(this).closest('tr').data('template-name');
                  template_id = $(this).closest('tr').data('template-id');
                  disney = $(this).closest('tr').data('disney');
                  modal_name    = refname.replace(/ /g, '');

                  if(template_name == "tesco_swingticket_orders")
                  {
                      openmodaltescopreview(modal_name, loop, style_no, buyer_po_no, id, order_type, created_at, isEdit,'print_swing_tickets',template_name,template_id,disney,refname,$(this));
                  }
                  
              }
          });
        }
        else
        {
          var isEdit = "spectra_print";
          
          created_at = $('.edit-spectra-erp-details').data('created-at')  
          order_type = $('.edit-spectra-erp-details').data('order-type') 
          buyer_po_no= $('.edit-spectra-erp-details').data('buyer-po') 
          refname    = $('.edit-spectra-erp-details').data('ref-name') 
          id         = $('.edit-spectra-erp-details').data('erp-id')
          template_name = $('.edit-spectra-erp-details').data('template-name')
          template_id   = $('.edit-spectra-erp-details').data('template-id')
          disney 		  = "";
          style_no   = $('.edit-spectra-erp-details').data('style-no')
          modal_name = refname.replace(/ /g, '');

          if(template_name == "tesco_swingticket_orders_quanty")
          {
              openmodaltescopreview(modal_name, "loop", style_no, buyer_po_no, id, order_type, created_at, isEdit,'print_swing_tickets',"tesco_swingticket_orders",template_id,disney,refname,$(this));
          }
        }
        


        

        // window.open(
        //     base_url+JSON.parse(resp),
        //     '_blank' // <- This is what makes it open in a new window.
        // ); 
        // $('.snackbar_span').html("Jobcard Created Successfully");
        // showSnackbar();
        // $('.modal').modal('hide');
        // location.reload();
      },
      422: function (errorXHR) {
        var error_msg = ``;
        $.each(errorXHR.responseJSON.errors, function (indexInArray, valueOfElement) {
          error_msg += `<li>` + valueOfElement + `</li>`;
        });
        $('#spectrra_jobcard_result').html('<div class="alert alert-danger">' + error_msg + '</div>');
        $('#create_spectrra_jc_btn').prop('disabled', false);
        $(window).scrollTop(0);
      },
    },
    complete: function () {
      $(".loader").hide();
    }
  });
}

const getSpectrraJobcardDetails = (jc_id) => {
  $.ajax({
      url:base_url + "/erp/spectrra-job-manager/get-spectrra-jobcard-details",
      dataType:"json",
      method:"get",
      data:{jc_id},
      beforeSend: function () {
        $(".loader").show();
      },
      success: function(order_data){
        board_category_dropdown(order_data.board_category);
        boards_dropdown(order_data.boards);
        color_match_dropdown(order_data.color_match);

        $('#sp_jc_company_name').text(order_data.order_details.company.name);
        $('#sp_company_address').text(order_data.order_details.company.address);
        $('#sp_company_logo').attr('src', order_data.order_details.company.logo);
        $('#sp_jc_company_name').attr('data-company-id', order_data.order_details.company.id);

        $('#sp_work_order').text(order_data.order_details.work_order_id);
        $('#sp_work_order').attr('data-wo-id', order_data.order_details.work_order_id);

        $('#sp_today_date').text(order_data.order_details.created_date);
        $('#sp_today_date').attr('data-today-date', order_data.order_details.created_date);
        $('#sp_article').text(order_data.order_details.article);
        $('#sp_article').attr('data-article', order_data.order_details.article);
        $('#sp_reference').val(order_data.order_details.reference);

        $('#sp_customer_name').text(order_data.order_details.customer_name);
        $('#sp_style_no').text(order_data.order_details.style_number);
        $('#sp_customer_po').text(order_data.order_details.customer_po_number);
        $('#sp_po_verify_date').text(order_data.order_details.po_verified_date);
        $('#sp_buyer_name').text(order_data.order_details.buyer_name);
        $('#sp_order_no').text(order_data.order_details.erp_order.erp_id);
        $('#sp_order_no').attr('data-erpid', order_data.order_details.erp_id);
        $('#sp_order_date').text(order_data.order_details.order_date);
        $('#sp_delivery_date').text(order_data.order_details.customer_del_date);
        $('#sp_order_qty').text(order_data.order_details.total_order_qty);

        $('#color_bar').val(order_data.order_details.color_bar).trigger('change');
        $('#print_spec_bar').val(order_data.order_details.print_spec_bar).trigger('change');
        $('#tone_bar').val(order_data.order_details.tone_bar).trigger('change');
        $('#back_colors').val(order_data.order_details.back_color);
        $('#board_category').val(order_data.order_details.board_category_id).trigger('change');
        $('#board_name').val(order_data.order_details.board_id).trigger('change');
        $('#fsc_non_fsc').val(order_data.order_details.fsc_non_fsc).trigger('change');
        $('#gsm_value').val(order_data.order_details.gsm_value);
        $('#thickness').val(order_data.order_details.thickness);
        $('#board_size_length').val(order_data.order_details.board_size_length);
        $('#board_size_width').val(order_data.order_details.board_size_width);
        $('#print_size_length').val(order_data.order_details.print_size_length);
        $('#print_size_width').val(order_data.order_details.print_size_width);
        $('#design_size_length').val(order_data.order_details.design_size_length);
        $('#design_size_width').val(order_data.order_details.design_size_width);
        $('#cut_as_1').val(order_data.order_details.cut_as.split("/")[0]);
        $('#cut_as_2').val(order_data.order_details.cut_as.split("/")[1]);
        $('#repeats').val(order_data.order_details.repeats);
        $('#die_no').val(order_data.order_details.die_no);
        $('#cutting').val(order_data.order_details.cutting).trigger('change');
        $('#printing').val(order_data.order_details.printing).trigger('change');
        $('#coating').val(order_data.order_details.coating).trigger('change');
        $('#uv').val(order_data.order_details.uv).trigger('change');
        $('#lamination_spec').val(order_data.order_details.lamination);
        $('#pasting_board').val(order_data.order_details.pasting_board).trigger('change');
        $('#pasting_gsm').val(order_data.order_details.pasting_gsm);
        $('#die_cutting').val(order_data.order_details.die_cutting);
        $('#window_patch').val(order_data.order_details.window_patch);
        $('#folder_gluer').val(order_data.order_details.folder_gluer).trigger('change');
        $('#embossing').val(order_data.order_details.embossing).trigger('change');
        $('#debossing').val(order_data.order_details.debossing).trigger('change');
        $('#perforation').val(order_data.order_details.perforation).trigger('change');
        $('#hole_size').val(order_data.order_details.hole_size);
        $('#eyelet_spec').val(order_data.order_details.eyelet);
        $('#string_spec').val(order_data.order_details.string_spec);
        $('#foil').val(order_data.order_details.foil).trigger('change');
        $('#front_colors').val(order_data.order_details.front_colors);

        $.each(order_data.order_details.color_matches, function (key, item) {
          $('#color_match option:contains(' + item.color_match_text + ')').prop('selected', true);
        });

        $temp = order_data.erp_order_details[0].template_file.split(",");

        $erp_details_div_html = '<div hidden class="edit-spectra-erp-details" data-erp-id="'+order_data.erp_order_details[0].erp_order_id+'" data-ref-name="'+order_data.erp_order_details[0].reference_name+'" data-template-id="'+order_data.erp_order_details[0].template_id+'" data-order-type="'+order_data.erp_order_details[0].order_type+'" data-template-name="'+order_data.erp_order_details[0].order_qnty_table_name+'" data-buyer-po="'+order_data.erp_order_details[0].buyer_po_number+'" data-created-at="'+order_data.erp_order_details[0].created_at+'"  data-style-no="'+order_data.erp_order_details[0].style_no+'" data-template-file="'+order_data.erp_order_details[0].template_file+'" data-uuid="'+order_data.erp_order_details[0].uuid+'"></div><object style="opacity:0;width:0px;height:0px" id="'+order_data.erp_order_details[0].reference_name+'_'+order_data.erp_order_details[0].template_id+'_modal" class="center-block tesco_svg" data="'+$temp[0]+'" type="image/svg+xml"></object> <object style="opacity:0;width:0px;height:0px" id="'+order_data.erp_order_details[0].reference_name+'_'+order_data.erp_order_details[0].template_id+'_back_modal" class="center-block tesco_svg" data="'+$temp[1]+'" type="image/svg+xml"></object>'
                               
                            
        $('.edit-spectra-order-details-div').html($erp_details_div_html);

        $('.selectpicker').selectpicker('refresh');
        // $('#nat_template_type').val(data.template_data.template_type[0]);
        $('.spectrra-create-jc-modal-title').text(order_data.modal_title);
        $('#crud_operation_type').text('updated_at');
        // $('#material_hierarchy').text(order_data.template_details.article)

        $('#createSpectrraJCModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#create_spectrra_jc_form').validator('validate');
        $('#createSpectrraJCModal').modal('show');
        // enable print button (for old version browsers)
        $('#jc_print_btn').prop('disabled', false);
      },
      error: function(errorXHR, errorStatus, errorThrown) {
        console.log(errorThrown);
      },
      complete: function () {
          $(".loader").hide();
      }
  })
}
