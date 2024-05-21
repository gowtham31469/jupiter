var isSubmitting = false;


$('#add-product-benefits-form').validator().on('submit', function (e) {
  if (e.isDefaultPrevented()){
  }else{
   e.preventDefault();
   addProductBenefits();
 }
})

$(document).on('click','.edit-tesco-product-benefits',function(e){
 var id = $(this).closest('tr').attr('id');
 e.preventDefault();
 getTescoProductBenefitsById(id);
});

$('#edit-product-benefits-form').validator().on('submit', function (e) {
 if (e.isDefaultPrevented()){
 }else{
  e.preventDefault();
  updateProductBenefits();
}
})

$(document).on('click', '#enableProductBenefits', function(e) {
 var id = $(this).closest('tr').attr('id');

 enableProductBenefits(id,csrf_token)
});

$(document).on('click', '#disableProductBenefits', function(e) {
 var id = $(this).closest('tr').attr('id');

 disableProductBenefits(id,csrf_token)
});

function tescoProductBenefits() {
  try {
    $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.orchestra-sidebar').hide();
    $('.tesco-sidebar').show();

    $.ajax({
      url: base_url + "/admin/tesco/settings/tesco-product-benefits",
      type: "POST",
      dataType: "json",
      data: {
        _token: csrf_token
      },
      tryCount: 0,
      retryLimit: 2,
      beforeSend: function() {
        $(".loader").show();
      },
      success: function(data) {
        var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>'+
        '<div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" style="margin-left:3px;" data-target="#addProductBenefitsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Product Benefits </a></div></div>'+
        '<div class="template-block"><div class="top-content"><h3 class="content-heading"> Product Benefits </h3></div>'+
        '<table id="tesco-components-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead>'+
        '<tr><th>Sl. No</th><th>English</th><th>Polish</th><th>Czech</th><th>Slovak</th><th>Hungarian</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
        $.each(data.tesco_components, function(i, item) {
          var index = i + 1;
          tesco_metadata += '<tr id="' + item.id + '">'+
          '<td>' + index + '</td>'+
          '<td>' + item.english + '</td>'+
          '<td>' + item.polish + '</td>'+
          '<td>' + item.czech + '</td>'+
          '<td>' + item.slovak + '</td>'+
          '<td>' + item.hungarian + '</td>';
          if (item.is_enabled) {
            tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
          } else {
            tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
          }
          if (item.is_enabled) {
            tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="disableProductBenefits" data-placement="left"></i></a>';
          } else {
            tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="enableProductBenefits" data-placement="left"></i></a>';
          }
          tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-product-benefits filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
        })
        tesco_metadata += '</tbody></table></div>';
        $('.content').html(tesco_metadata)
        $('#tesco-components-list-table').DataTable();
        $('.edit-tesco-product-description').tooltip('show')
        $('.edit-tesco-product-description').tooltip('toggle')
        $('.status').tooltip('show')
        $('.status').tooltip('toggle')
        $("#tesco-components-list-table_filter input").addClass('form-control input-sm');
        $("#tesco-components-list-table_filter input").attr('placeholder', 'Data Search');
        $('#tesco-components-list-table_length select').addClass('form-control input-sm');
      },
      error: function(errorXHR, errorStatus, errorThrown) {
        console.log(errorThrown);
      },
      complete: function() {
        $(".loader").hide();
      }
    })
  } catch (e) {
    console.log("Error:" + e)
  }
}


/**
 * Adds tesco components.
 */
 function addProductBenefits() {
  try {
    var english = $('#benefits_english').val();
    var polish = $('#benefits_polish').val();
    var czech = $('#benefits_czech').val();
    var slovak = $('#benefits_slovak').val();
    var hungarian = $('#benefits_hungarian').val();
    var template_id = $('#template_id').val();

    $.ajax({
      url: base_url + "/admin/tesco/add-tesco-product-benefits",
      method: 'POST',
      data: {
        _token: csrf_token,
        english: english,
        polish: polish,
        czech: czech,
        slovak: slovak,
        hungarian: hungarian,
        template_id: template_id,
      },
      tryCount: 0,
      retryLimit: 2,
      beforeSend: function() {
        $(".loader").show();
      },
      statusCode: {

        /** [ Show response status based on HTTP status code ] */
        200: function() {
          $('.snackbar_span').html("Product Benefits Added Successfully");
          showSnackbar();
          $('#addProductBenefitsModal').modal('hide');
          tescoProductBenefits()
        },
        208: function() {
          $('#benefits_error').removeClass('hidden');
          $('#benefits_error').removeAttr('style');
          $('#benefits_error').html('Product Benefits[English] already exists.')
          $("#benefits_error").addClass("alert alert-warning");
        },
        400: function() {
          $('.snackbar_span').html("Something went wrong!!");
          showSnackbar();
        }
      },
      error: function(errorXHR, errorStatus, errorThrown) {
        console.log(errorThrown);
      },
      complete: function() {
        $(".loader").hide();
      }
    })
  } catch (e) {
    console.log("Error:" + e)
  }
}

/**
 * Function for get tesco Components details for editing
 * @param id
 */
 function getTescoProductBenefitsById(id) {
  try {
    if (isSubmitting) {
      return;
    }
    isSubmitting = true;
    $.ajax({
      url: base_url + "/admin/tesco/get-tesco-product-benefits",
      dataType: "json",
      method: "get",
      data: { id: id },
      tryCount: 0,
      retryLimit: 2,
      beforeSend: function() {
        $(".loader").show();
      },
      success: function(data) {
        isSubmitting = false;

        console.log(data)
        $('#edit_tesco_product_benefits_id').val(data.product_benefits[0].id);
        $('#benefits_edit_english').val(data.product_benefits[0].english);
        $('#benefits_edit_polish').val(data.product_benefits[0].polish);
        $('#benefits_edit_czech').val(data.product_benefits[0].czech);
        $('#benefits_edit_slovak').val(data.product_benefits[0].slovak);
        $('#benefits_edit_hungarian').val(data.product_benefits[0].hungarian);
        $("#edit_template_id").val(data.product_benefits[0].template_id).change();
        $('#editProductBenefitsModal').modal({
          backdrop: 'static',
          keyboard: false
        })
        $('#edit-product-benefits-form').validator('validate');
        $('#editProductBenefitsModal').modal('show');
      },
      error: function(errorXHR, errorStatus, errorThrown) {
        console.log(errorThrown);
      },
      complete: function() {
        $(".loader").hide();
      }
    })
  } catch (e) {
    console.log("Error:" + e)
  }
}


/**
 * Function for edit Tesco Components instruction
 */
 function updateProductBenefits() {
  try {
    var id = $('#edit_tesco_product_benefits_id').val();
    var english = $('#benefits_edit_english').val();
    var polish = $('#benefits_edit_polish').val();
    var czech = $('#benefits_edit_czech').val();
    var slovak = $('#benefits_edit_slovak').val();
    var hungarian = $('#benefits_edit_hungarian').val();
    var template_id = $('#edit_template_id').val();

    $.ajax({
      url: base_url + "/admin/tesco/update-tesco-product-benefits",
      dataType: "json",
      beforeSend: function() {
        $(".loader").show();
        $('#product_description_error').addClass('hidden');
      },
      method: "put",
      data: { 
        _token: csrf_token,
        id : id,
        english: english,
        polish: polish,
        czech: czech,
        slovak: slovak,
        hungarian: hungarian,
        template_id:template_id
      },
      tryCount: 0,
      retryLimit: 2,
      statusCode: {

        /** [ Show response status based on HTTP status code ] */
        200: function() {
          $('.snackbar_span').html("Product Benefits Updated Successfully");
          showSnackbar();
          $('#editProductBenefitsModal').modal('hide');
          tescoProductBenefits()
        },
        208: function() {
          $('#product_description_error').removeClass('hidden');
          $('#product_description_error').removeAttr('style');
          $('#product_description_error').html('Product Benefits already exists.')
          $("#product_description_error").addClass("alert alert-warning");
        },
      },
      error: function(errorXHR, errorStatus, errorThrown) {
        console.log(errorThrown);
      },
      complete: function() {
        $(".loader").hide();
      }
    })
  } catch (e) {
    console.log("Error:" + e)
  }
}


/**
 * Makes a Components Enabled.
 * @param id,csrf_token
 */
 function enableProductBenefits(id, csrf_token) {
  try {
    if (isSubmitting) {
      return;
    }
    isSubmitting = true;
    $.ajax({
      url: base_url + "/admin/tesco/make-tesco-product-benefits-enabled",
      dataType: "json",
      beforeSend: function() {
        $(".loader").show();
      },
      method: "put",
      data: { _token: csrf_token, id: id },
      tryCount: 0,
      retryLimit: 2,
      statusCode: {
        /** [Show response status based on HTTP status code] */
        200: function() {
          isSubmitting = false;
          $('.snackbar_span').html('Product Benefits enabled ');
          showSnackbar();
        }
      },
      error: function(errorXHR, errorStatus, errorThrown) {
        console.log(errorThrown);
      },
      complete: function() {
        tescoProductBenefits();
        $(".loader").hide();
      }
    })
  } catch (e) {
    console.log("Error:" + e)
  }
}


/**
 * Makes a Components Diabled
 * @param id,csrf_token
 */
 function disableProductBenefits(id, csrf_token) {
  try {
    if (isSubmitting) {
      return;
    }
    isSubmitting = true;
    $.ajax({
      url: base_url + "/admin/tesco/make-tesco-product-benefits-disabled",
      dataType: "json",
      beforeSend: function() {
        $(".loader").show();
      },
      method: "put",
      data: { _token: csrf_token, id: id },
      tryCount: 0,
      retryLimit: 2,
      statusCode: {
        /** [Show response status based on HTTP status code] */
        200: function() {
          isSubmitting = false;
          $('.snackbar_span').html('Product benefits disabled ');
          showSnackbar();
        }
      },
      error: function(errorXHR, errorStatus, errorThrown) {
        console.log(errorThrown);
      },
      complete: function() {
        tescoProductBenefits();
        $(".loader").hide();
      }
    })
  } catch (e) {
    console.log("Error:" + e)
  }
}


$('#upload_product_benefit_excel').validator().on('submit', function (e) {
 if (e.isDefaultPrevented())
 {

 }
 else
 {
  e.preventDefault();

  var excel_file = $(this).find("input[name='product_benefit_excel']")[0].files[0];
  var template_id = $("#product_benefit_excel_template").val();

  uploadProductBenefit(template_id, excel_file);
}
})


function uploadProductBenefit(template_id, excel_file) {
  try {

    var form_data = new FormData();

    form_data.append('template_id', template_id);
    form_data.append('excel_file', excel_file);
    form_data.append('_token', csrf_token);

    $.ajax({
      url: base_url + "/admin/tesco/upload-product-benefits",
      type: "POST",
      data: form_data,
      cache: false,
      contentType: false,
      processData: false,
      tryCount: 0,
      retryLimit: 2,
      beforeSend: function() {
        $(".loader").show();
      },
      statusCode: {
        /** [ Show response status based on HTTP status code ] */
        200: function() {
          $('.snackbar_span').html('Excel file uploaded successfully');
          showSnackbar();
          $('#addWeeSymbolModal').modal('hide');
          tescoProductBenefits();
        },
        422: function() {
          $('.hidden-error').html('<div class="alert alert-warning">\
            Invalid File Format\
            </div>')
        }
      },
      error: function(errorXHR, errorStatus, errorThrown) {
        console.log(errorThrown);
      },
      complete: function() {
        $(".loader").hide();
      }
    });
  } catch (e) {
    console.log("Error:" + e)
  }
}