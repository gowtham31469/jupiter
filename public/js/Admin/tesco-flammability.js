var isSubmitting = false;


$('#add-flammability-form').validator().on('submit', function (e) {
  if (e.isDefaultPrevented()){
  }else{
     e.preventDefault();
     addFlammability();
  }
})

$(document).on('click','.edit-tesco-flammability',function(e){
   var id = $(this).closest('tr').attr('id');
   e.preventDefault();
   gettescoFlammabilityById(id);
});

$('#edit-flammability-form').validator().on('submit', function (e) {
 if (e.isDefaultPrevented()){
 }else{
    e.preventDefault();
    updateFlammability();
 }
})

$(document).on('click', '#enableFlammability', function(e) {
   var id = $(this).closest('tr').attr('id');
   
   enableFlammability(id,csrf_token)
});

$(document).on('click', '#disableFlammability', function(e) {
   var id = $(this).closest('tr').attr('id');
   
   disableFlammability(id,csrf_token)
});

function tescoFlammability() {
  try {
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.orchestra-sidebar').hide();
      $('.tesco-sidebar').show();
      
      $.ajax({
          url: base_url + "/admin/tesco/settings/tesco-flammability",
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
            '<div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addFlammabilityModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Flammability </a></div></div>'+
            '<div class="template-block"><div class="top-content"><h3 class="content-heading"> Flammability </h3></div>'+
            '<table id="tesco-components-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead>'+
            '<tr><th>Sl. No</th><th>Flammability Text</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
            $.each(data.tesco_flammability, function(i, item) {
              var index = i + 1;
              tesco_metadata += '<tr id="' + item.id + '">'+
              '<td>' + index + '</td>'+
              '<td>' + item.flammability_text + '</td>';
              if (item.is_enabled) {
                tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
              } else {
                tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
              }
              if (item.is_enabled) {
                tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="disableFlammability" data-placement="left"></i></a>';
              } else {
                tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="enableFlammability" data-placement="left"></i></a>';
              }
              tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-flammability filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
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
 * Adds tesco flammability.
 */
function addFlammability() {
  try {
    var flammability_text = $('#flammability_text').val();

    $.ajax({
          url: base_url + "/admin/tesco/add-tesco-flammability",
          method: 'POST',
          data: {
            _token: csrf_token,
            flammability_text: flammability_text,
          },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Flammability Added Successfully");
                  showSnackbar();
                  $('#addFlammabilityModal').modal('hide');
                  tescoFlammability()
              },
              208: function() {
                  $('#flammability_error').removeClass('hidden');
                  $('#flammability_error').removeAttr('style');
                  $('#flammability_error').html('Flammability already exists.')
                  $("#flammability_error").addClass("alert alert-warning");
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
 * Function for get tesco flammability details for editing
 * @param id
 */
function gettescoFlammabilityById(id) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/get-tesco-flammability",
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
              $('#edit_tesco_flammability_id').val(data.flammability[0].id);
              $('#edit_flammability_text').val(data.flammability[0].flammability_text);
              $('#editFlammabilityModal').modal({
                  backdrop: 'static',
                  keyboard: false
              })
              $('#edit-flammability-form').validator('validate');
              $('#editFlammabilityModal').modal('show');
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
function updateFlammability() {
  try {
      var id = $('#edit_tesco_flammability_id').val();
      var flammability_text = $('#edit_flammability_text').val();
      
      $.ajax({
          url: base_url + "/admin/tesco/update-tesco-flammability",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
              $('#flammability_error').addClass('hidden');
          },
          method: "put",
          data: { 
            _token: csrf_token,
            id : id,
            flammability_text: flammability_text,
          },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Flammability Updated Successfully");
                  showSnackbar();
                  $('#editFlammabilityModal').modal('hide');
                  
                  tescoFlammability()
              },
              208: function() {
                  $('#flammability_error').removeClass('hidden');
                  $('#flammability_error').removeAttr('style');
                  $('#flammability_error').html('Flammability already exists.')
                  $("#flammability_error").addClass("alert alert-warning");
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
function enableFlammability(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/make-tesco-flammability-enabled",
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
                  $('.snackbar_span').html('Flammability enabled ');
                  showSnackbar();
              }
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              console.log(errorThrown);
          },
          complete: function() {
            tescoFlammability();
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
function disableFlammability(id, csrf_token) {
    try {
        if (isSubmitting) {
            return;
        }
        isSubmitting = true;
        $.ajax({
            url: base_url + "/admin/tesco/make-tesco-flammability-disabled",
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
                    $('.snackbar_span').html('Flammability disabled ');
                    showSnackbar();
                }
            },
            error: function(errorXHR, errorStatus, errorThrown) {
                console.log(errorThrown);
            },
            complete: function() {
                tescoFlammability();
                $(".loader").hide();
            }
        })
    } catch (e) {
        console.log("Error:" + e)
    }
}