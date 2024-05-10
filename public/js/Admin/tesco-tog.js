var isSubmitting = false;


$('#add-tog-form').validator().on('submit', function (e) {
  if (e.isDefaultPrevented()){
  }else{
     e.preventDefault();
     addTog();
  }
})

$(document).on('click','.edit-tesco-tog',function(e){
   var id = $(this).closest('tr').attr('id');
   e.preventDefault();
   gettescoTogById(id);
});

$('#edit-tog-form').validator().on('submit', function (e) {
 if (e.isDefaultPrevented()){
 }else{
    e.preventDefault();
    updateTog();
 }
})

$(document).on('click', '#enableTog', function(e) {
   var id = $(this).closest('tr').attr('id');
   
   enableTog(id,csrf_token)
});

$(document).on('click', '#disableTog', function(e) {
   var id = $(this).closest('tr').attr('id');
   
   disableTog(id,csrf_token)
});

function tescoTog() {
  try {
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.orchestra-sidebar').hide();
      $('.tesco-sidebar').show();
      
      $.ajax({
          url: base_url + "/admin/tesco/settings/tesco-tog",
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
            '<div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTogModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Tog </a></div></div>'+
            '<div class="template-block"><div class="top-content"><h3 class="content-heading"> Tog </h3></div>'+
            '<table id="tesco-components-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead>'+
            '<tr><th>Sl. No</th><th>Tog</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
            $.each(data.tesco_togs, function(i, item) {
              var index = i + 1;
              tesco_metadata += '<tr id="' + item.id + '">'+
              '<td>' + index + '</td>'+
              '<td>' + item.tog_value + '</td>';
              if (item.is_enabled) {
                tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
              } else {
                tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
              }
              if (item.is_enabled) {
                tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="disableTog" data-placement="left"></i></a>';
              } else {
                tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="enableTog" data-placement="left"></i></a>';
              }
              tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-tog filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
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
function addTog() {
  try {
    var tog_value = $('#tog_value').val();

    $.ajax({
          url: base_url + "/admin/tesco/add-tesco-tog",
          method: 'POST',
          data: {
            _token: csrf_token,
            tog_value: tog_value,
          },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Tog Added Successfully");
                  showSnackbar();
                  $('#addTogModal').modal('hide');
                  tescoTog()
              },
              208: function() {
                  $('#tog_error').removeClass('hidden');
                  $('#tog_error').removeAttr('style');
                  $('#tog_error').html('Tog value already exists.')
                  $("#tog_error").addClass("alert alert-warning");
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
function gettescoTogById(id) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/get-tesco-tog",
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
              $('#edit_tesco_tog_id').val(data.tog[0].id);
              $('#edit_tog_value').val(data.tog[0].tog_value);
              $('#editTogModal').modal({
                  backdrop: 'static',
                  keyboard: false
              })
              $('#edit-tog-form').validator('validate');
              $('#editTogModal').modal('show');
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
function updateTog() {
  try {
      var id = $('#edit_tesco_tog_id').val();
      var edit_tog_value = $('#edit_tog_value').val();
      
      $.ajax({
          url: base_url + "/admin/tesco/update-tesco-tog",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
              $('#tog_error').addClass('hidden');
          },
          method: "put",
          data: { 
            _token: csrf_token,
            id : id,
            tog_value: edit_tog_value,
          },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Tog Updated Successfully");
                  showSnackbar();
                  $('#editTogModal').modal('hide');
                  
                  tescoTog()
              },
              208: function() {
                  $('#tog_error').removeClass('hidden');
                  $('#tog_error').removeAttr('style');
                  $('#tog_error').html('Tog already exists.')
                  $("#tog_error").addClass("alert alert-warning");
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
function enableTog(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/make-tesco-tog-enabled",
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
                  $('.snackbar_span').html('Tog enabled ');
                  showSnackbar();
              }
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              console.log(errorThrown);
          },
          complete: function() {
            tescoTog();
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
function disableTog(id, csrf_token) {
    try {
        if (isSubmitting) {
            return;
        }
        isSubmitting = true;
        $.ajax({
            url: base_url + "/admin/tesco/make-tesco-tog-disabled",
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
                    $('.snackbar_span').html('Tog disabled ');
                    showSnackbar();
                }
            },
            error: function(errorXHR, errorStatus, errorThrown) {
                console.log(errorThrown);
            },
            complete: function() {
                tescoTog();
                $(".loader").hide();
            }
        })
    } catch (e) {
        console.log("Error:" + e)
    }
}