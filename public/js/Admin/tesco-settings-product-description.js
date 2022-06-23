var isSubmitting = false;

$(document).on('click','.edit-tesco-product-description',function(e){
   var id = $(this).closest('tr').attr('id');
   e.preventDefault();
   getTescoProductDescriptionById(id);
});

$('#edit-product-description-form').validator().on('submit', function (e) {
 if (e.isDefaultPrevented()){
 }else{
    e.preventDefault();
    updateProductDescription();
 }
})

$(document).on('click', '#enableProductDescription', function(e) {
   var id = $(this).closest('tr').attr('id');
   
   enableProductDescription(id,csrf_token)
});

$(document).on('click', '#disableProductDescription', function(e) {
   var id = $(this).closest('tr').attr('id');
   
   disableProductDescription(id,csrf_token)
});

function tescoProductDescriptions() {
  try {
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.orchestra-sidebar').hide();
      $('.tesco-sidebar').show();
      
      $.ajax({
          url: base_url + "/admin/tesco/settings/tesco-product-description",
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
            '<div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addProductDescriptionModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Product Description </a></div></div>'+
            '<div class="template-block"><div class="top-content"><h3 class="content-heading"> Product Description </h3></div>'+
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
                tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="disableProductDescription" data-placement="left"></i></a>';
              } else {
                tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="enableProductDescription" data-placement="left"></i></a>';
              }
              tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-product-description filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
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
function addProductDescription() {
  try {
    var english = $('#desc_english').val();
    var polish = $('#desc_polish').val();
    var czech = $('#desc_czech').val();
    var slovak = $('#desc_slovak').val();
    var hungarian = $('#desc_hungarian').val();
      

    $.ajax({
          url: base_url + "/admin/tesco/add-tesco-product-description",
          method: 'POST',
          data: {
            _token: csrf_token,
            english: english,
            polish: polish,
            czech: czech,
            slovak: slovak,
            hungarian: hungarian,
          },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Product Description Added Successfully");
                  showSnackbar();
                  $('#addProductDescriptionModal').modal('hide');
                  tescoProductDescriptions()
              },
              208: function() {
                  $('#components_error').removeClass('hidden');
                  $('#components_error').removeAttr('style');
                  $('#components_error').html('Product Description[English] already excists.')
                  $("#components_error").addClass("alert alert-warning");
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
function getTescoProductDescriptionById(id) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/get-tesco-product-description",
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
              $('#edit_tesco_product_description_id').val(data.product_description[0].id);
              $('#desc_edit_english').val(data.product_description[0].english);
              $('#desc_edit_polish').val(data.product_description[0].polish);
              $('#desc_edit_czech').val(data.product_description[0].czech);
              $('#desc_edit_slovak').val(data.product_description[0].slovak);
              $('#desc_edit_hungarian').val(data.product_description[0].hungarian);
              $('#editProductDescriptionModal').modal({
                  backdrop: 'static',
                  keyboard: false
              })
              $('#edit-product-description-form').validator('validate');
              $('#editProductDescriptionModal').modal('show');
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
function updateProductDescription() {
  try {
      var id = $('#edit_tesco_product_description_id').val();
      var english = $('#desc_edit_english').val();
      var polish = $('#desc_edit_polish').val();
      var czech = $('#desc_edit_czech').val();
      var slovak = $('#desc_edit_slovak').val();
      var hungarian = $('#desc_edit_hungarian').val();
      
      $.ajax({
          url: base_url + "/admin/tesco/update-tesco-product-description",
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
          },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Product Description Updated Successfully");
                  showSnackbar();
                  $('#editProductDescriptionModal').modal('hide');
                  tescoProductDescriptions()
              },
              208: function() {
                  $('#product_description_error').removeClass('hidden');
                  $('#product_description_error').removeAttr('style');
                  $('#product_description_error').html('Product Description already excists.')
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
function enableProductDescription(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/make-tesco-product-description-enabled",
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
                  $('.snackbar_span').html('ProductDescription enabled ');
                  showSnackbar();
              }
          },
          error: function(errorXHR, errorStatus, errorThrown) {
            console.log(errorThrown);
          },
          complete: function() {
            tescoProductDescriptions();
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
function disableProductDescription(id, csrf_token) {
    try {
        if (isSubmitting) {
            return;
        }
        isSubmitting = true;
        $.ajax({
            url: base_url + "/admin/tesco/make-tesco-product-description-disabled",
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
                    $('.snackbar_span').html('ProductDescription disabled ');
                    showSnackbar();
                }
            },
            error: function(errorXHR, errorStatus, errorThrown) {
                console.log(errorThrown);
            },
            complete: function() {
                tescoProductDescriptions();
                $(".loader").hide();
            }
        })
    } catch (e) {
        console.log("Error:" + e)
    }
}