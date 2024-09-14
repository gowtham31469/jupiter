var isSubmitting = false;


$('#add-mm-product-benefits-form').validator().on('submit', function (e) {
  if (e.isDefaultPrevented()){
  }else{
     e.preventDefault();
     addMMProductBenefits();
  }
})

$(document).on('click','.edit-tesco-mm-product-benefits',function(e){
   var id = $(this).closest('tr').attr('id');
   e.preventDefault();
   gettescoMMProductBenefitsById(id);
});

$('#edit-mm-product-benefits-form').validator().on('submit', function (e) {
 if (e.isDefaultPrevented()){
 }else{
    e.preventDefault();
    updateMMProductBenefits();
 }
})

$(document).on('click', '#enableMMProductBenefits', function(e) {
   var id = $(this).closest('tr').attr('id');
   
   enableMMProductBenefits(id,csrf_token)
});

$(document).on('click', '#disableMMProductBenefits', function(e) {
   var id = $(this).closest('tr').attr('id');
   
   disableMMProductBenefits(id,csrf_token)
});

function tescoMMProductBenefits() {
  try {
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.orchestra-sidebar').hide();
      $('.tesco-sidebar').show();
      
      $.ajax({
          url: base_url + "/admin/tesco/settings/tesco-mm-product-benefits",
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
            '<div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addMMProductBenefitsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add MM Product Benefits </a></div></div>'+
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
                tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="disableMMProductBenefits" data-placement="left"></i></a>';
              } else {
                tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="enableMMProductBenefits" data-placement="left"></i></a>';
              }
              tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-mm-product-benefits filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
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
function addMMProductBenefits() {
  try {
    var english = $('#mm_benefits_english').val();
    var polish = $('#mm_benefits_polish').val();
    var czech = $('#mm_benefits_czech').val();
    var slovak = $('#mm_benefits_slovak').val();
    var hungarian = $('#mm_benefits_hungarian').val();
    var template_id = $('#template_id_mm').val();
      

    $.ajax({
          url: base_url + "/admin/tesco/add-tesco-mm-product-benefits",
          method: 'POST',
          data: {
            _token: csrf_token,
            english: english,
            polish: polish,
            czech: czech,
            slovak: slovak,
            hungarian: hungarian,
            template_id: template_id
          },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("MM Product Benefits Added Successfully");
                  showSnackbar();
                  $('#addMMProductBenefitsModal').modal('hide');
                  tescoMMProductBenefits()
              },
              208: function() {
                  $('#benefits_error').removeClass('hidden');
                  $('#benefits_error').removeAttr('style');
                  $('#benefits_error').html('MM Product Benefits[English] already exists.')
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
function gettescoMMProductBenefitsById(id) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/get-tesco-mm-product-benefits",
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
              $('#edit_tesco_mm_product_benefits_id').val(data.product_benefits[0].id);
              $('#mm_benefits_edit_english').val(data.product_benefits[0].english);
              $('#mm_benefits_edit_polish').val(data.product_benefits[0].polish);
              $('#mm_benefits_edit_czech').val(data.product_benefits[0].czech);
              $('#mm_benefits_edit_slovak').val(data.product_benefits[0].slovak);
              $('#mm_benefits_edit_hungarian').val(data.product_benefits[0].hungarian);
              $("#edit_template_id_mm").val(data.product_benefits[0].template_id).change();
              $('#editMMProductBenefitsModal').modal({
                  backdrop: 'static',
                  keyboard: false
              })
              $('#edit-mm-product-benefits-form').validator('validate');
              $('#editMMProductBenefitsModal').modal('show');
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
function updateMMProductBenefits() {
  try {
      var id = $('#edit_tesco_mm_product_benefits_id').val();
      var english = $('#mm_benefits_edit_english').val();
      var polish = $('#mm_benefits_edit_polish').val();
      var czech = $('#mm_benefits_edit_czech').val();
      var slovak = $('#mm_benefits_edit_slovak').val();
      var hungarian = $('#mm_benefits_edit_hungarian').val();
      var template_id = $('#edit_template_id_mm').val();
      
      $.ajax({
          url: base_url + "/admin/tesco/update-tesco-mm-product-benefits",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
              $('#mm_product_benefits_error').addClass('hidden');
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
            template_id: template_id
          },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("MM Product Benefits Updated Successfully");
                  showSnackbar();
                  $('#editMMProductBenefitsModal').modal('hide');
                  
                  tescoMMProductBenefits()
              },
              208: function() {
                  $('#mm_product_benefits_error').removeClass('hidden');
                  $('#mm_product_benefits_error').removeAttr('style');
                  $('#mm_product_benefits_error').html('MM Product Benefits already exists.')
                  $("#mm_product_benefits_error").addClass("alert alert-warning");
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
function enableMMProductBenefits(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/make-tesco-mm-product-benefits-enabled",
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
            tescoMMProductBenefits();
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
function disableMMProductBenefits(id, csrf_token) {
    try {
        if (isSubmitting) {
            return;
        }
        isSubmitting = true;
        $.ajax({
            url: base_url + "/admin/tesco/make-tesco-mm-product-benefits-disabled",
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
                tescoMMProductBenefits();
                $(".loader").hide();
            }
        })
    } catch (e) {
        console.log("Error:" + e)
    }
}