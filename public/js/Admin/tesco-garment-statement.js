var isSubmitting = false;


$('#add-garment-statement-form').validator().on('submit', function (e) {
  if (e.isDefaultPrevented()){
  }else{
     e.preventDefault();
     addGarmentStatements();
  }
})

$(document).on('click','.edit-tesco-garment-statement',function(e){
   var id = $(this).closest('tr').attr('id');
   e.preventDefault();
   gettescoGarmentStatementsById(id);
});

$('#edit-garment-statement-form').validator().on('submit', function (e) {
 if (e.isDefaultPrevented()){
 }else{
    e.preventDefault();
    updateGarmentStatements();
 }
})

$(document).on('click', '#enableGarmentStatements', function(e) {
   var id = $(this).closest('tr').attr('id');
   
   enableGarmentStatements(id,csrf_token)
});

$(document).on('click', '#disableGarmentStatements', function(e) {
   var id = $(this).closest('tr').attr('id');
   
   disableGarmentStatements(id,csrf_token)
});

function tescoGarmentStatements() {
  try {
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.orchestra-sidebar').hide();
      $('.tesco-sidebar').show();
      
      $.ajax({
          url: base_url + "/admin/tesco/settings/tesco-garment-statement",
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
            '<div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addGarmentStatementsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Garment Statements </a></div></div>'+
            '<div class="template-block"><div class="top-content"><h3 class="content-heading"> Garment Statements </h3></div>'+
            '<table id="tesco-components-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead>'+
            '<tr><th>Sl. No</th><th>English</th><th>Polish</th><th>Czech</th><th>Slovak</th><th>Hungarian</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
            $.each(data.tesco_garment_statement, function(i, item) {
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
                tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="disableGarmentStatements" data-placement="left"></i></a>';
              } else {
                tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="enableGarmentStatements" data-placement="left"></i></a>';
              }
              tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-garment-statement filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
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
function addGarmentStatements() {
  try {
    var english = $('#garment_statement_english').val();
    var polish = $('#garment_statement_polish').val();
    var czech = $('#garment_statement_czech').val();
    var slovak = $('#garment_statement_slovak').val();
    var hungarian = $('#garment_statement_hungarian').val();

    $.ajax({
          url: base_url + "/admin/tesco/add-tesco-garment-statement",
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
                  $('.snackbar_span').html("Garment Statement Added Successfully");
                  showSnackbar();
                  $('#addGarmentStatementsModal').modal('hide');
                  tescoGarmentStatements()
              },
              208: function() {
                  $('#garment_statement_error').removeClass('hidden');
                  $('#garment_statement_error').removeAttr('style');
                  $('#garment_statement_error').html('Garment Statement[English] already exists.')
                  $("#garment_statement_error").addClass("alert alert-warning");
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
function gettescoGarmentStatementsById(id) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/get-tesco-garment-statement",
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
              $('#edit_tesco_garment_statement_id').val(data.garment_statement[0].id);
              $('#garment_statement_edit_english').val(data.garment_statement[0].english);
              $('#garment_statement_edit_polish').val(data.garment_statement[0].polish);
              $('#garment_statement_edit_czech').val(data.garment_statement[0].czech);
              $('#garment_statement_edit_slovak').val(data.garment_statement[0].slovak);
              $('#garment_statement_edit_hungarian').val(data.garment_statement[0].hungarian);
              $('#editGarmentStatementsModal').modal({
                  backdrop: 'static',
                  keyboard: false
              })
              $('#edit-garment-statement-form').validator('validate');
              $('#editGarmentStatementsModal').modal('show');
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
function updateGarmentStatements() {
  try {
      var id = $('#edit_tesco_garment_statement_id').val();
      var english = $('#garment_statement_edit_english').val();
      var polish = $('#garment_statement_edit_polish').val();
      var czech = $('#garment_statement_edit_czech').val();
      var slovak = $('#garment_statement_edit_slovak').val();
      var hungarian = $('#garment_statement_edit_hungarian').val();
      
      $.ajax({
          url: base_url + "/admin/tesco/update-tesco-garment-statement",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
              $('#garment_statement_error').addClass('hidden');
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
                  $('.snackbar_span').html("Garment Statement Updated Successfully");
                  showSnackbar();
                  $('#editGarmentStatementsModal').modal('hide');
                  
                  tescoGarmentStatements()
              },
              208: function() {
                  $('#garment_statement_error').removeClass('hidden');
                  $('#garment_statement_error').removeAttr('style');
                  $('#garment_statement_error').html('Garment Statement already exists.')
                  $("#garment_statement_error").addClass("alert alert-warning");
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
function enableGarmentStatements(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/make-tesco-garment-statement-enabled",
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
                  $('.snackbar_span').html('Garment Statement enabled ');
                  showSnackbar();
              }
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              console.log(errorThrown);
          },
          complete: function() {
            tescoGarmentStatements();
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
function disableGarmentStatements(id, csrf_token) {
    try {
        if (isSubmitting) {
            return;
        }
        isSubmitting = true;
        $.ajax({
            url: base_url + "/admin/tesco/make-tesco-garment-statement-disabled",
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
                    $('.snackbar_span').html('Garment Statement disabled ');
                    showSnackbar();
                }
            },
            error: function(errorXHR, errorStatus, errorThrown) {
                console.log(errorThrown);
            },
            complete: function() {
                tescoGarmentStatements();
                $(".loader").hide();
            }
        })
    } catch (e) {
        console.log("Error:" + e)
    }
}