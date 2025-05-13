/**
 * Function to list Care Additional Instructions
 */
function tescoCareAdditionalInstructions() {
  try {
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.orchestra-sidebar').hide();
      $('.tesco-sidebar').show();
      
      $.ajax({
          url: base_url + "/admin/tesco/settings/get-care-additional-instructions",
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
            '<div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoCareInstructionModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Instruction </a></div></div>'+
            '<div class="template-block"><div class="top-content"><h3 class="content-heading"> Care Additional Instructions </h3></div>'+
            '<table id="tesco-care-instruction-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead>'+
            '<tr><th>Sl. No</th><th>English</th><th>Polish</th><th>Czech</th><th>Slovak</th><th>Hungarian</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
            $.each(data.tesco_care_instructions, function(i, item) {
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
                tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="disableTescoCareInstruction" data-placement="left"></i></a>';
              } else {
                tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="enableTescoCareInstruction" data-placement="left"></i></a>';
              }
              tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-care-instruction filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
            })
            tesco_metadata += '</tbody></table></div>';
            $('.content').html(tesco_metadata)
            $('#tesco-care-instruction-list-table').DataTable();
            $('.edit-tesco-care-instruction').tooltip('show')
            $('.edit-tesco-care-instruction').tooltip('toggle')
            $('.status').tooltip('show')
            $('.status').tooltip('toggle')
            $("#tesco-care-instruction-list-table_filter input").addClass('form-control input-sm');
            $("#tesco-care-instruction-list-table_filter input").attr('placeholder', 'Data Search');
            $('#tesco-care-instruction-list-table_length select').addClass('form-control input-sm');
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
 * Add Care Additional Instruction
 */
function addTescoCareInstruction() {
  try {
    var english = $('#care_instruction_english').val();
    var polish = $('#care_instruction_polish').val();
    var czech = $('#care_instruction_czech').val();
    var slovak = $('#care_instruction_slovak').val();
    var hungarian = $('#care_instruction_hungarian').val();
      
    $.ajax({
          url: base_url + "/admin/tesco/add-tesco-care-instruction",
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
              $('#care_instruction_error').addClass('hidden');
          },
          statusCode: {
              200: function(response) {
                  $('#addTescoCareInstructionModal').modal('hide');
                  $('.snackbar_span').html("Instruction Added Successfully");
                  showSnackbar();
                  tescoCareAdditionalInstructions();
              },
              208: function() {
                  $('#care_instruction_error').removeClass('hidden');
                  $('#care_instruction_error').removeAttr('style');
                  $('#care_instruction_error').html('Instruction[English] already exists.')
                  $("#care_instruction_error").addClass("alert alert-warning");
              },
              400: function() {
                  $('.snackbar_span').html("Something went wrong!!");
                  showSnackbar();
              }
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              console.log(errorThrown);
              $('#care_instruction_error').removeClass('hidden');
              $('#care_instruction_error').removeAttr('style');
              $('#care_instruction_error').html('An error occurred while saving. Please try again.')
              $("#care_instruction_error").addClass("alert alert-danger");
          },
          complete: function() {
              $(".loader").hide();
          }
    });
  } catch (e) {
      console.log("Error:" + e);
      $('#care_instruction_error').removeClass('hidden');
      $('#care_instruction_error').removeAttr('style');
      $('#care_instruction_error').html('An unexpected error occurred. Please try again.')
      $("#care_instruction_error").addClass("alert alert-danger");
  }
}

/**
 * Get Care Additional Instruction by ID for editing
 * @param id
 */
function getTescoCareInstructionById(id) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/get-tesco-care-instruction-details",
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
              $('#edit_tesco_care_instruction_id').val(id);
              $('#edit_care_instruction_english').val(data.tesco_care_instruction[0].english);
              $('#edit_care_instruction_polish').val(data.tesco_care_instruction[0].polish);
              $('#edit_care_instruction_czech').val(data.tesco_care_instruction[0].czech);
              $('#edit_care_instruction_slovak').val(data.tesco_care_instruction[0].slovak);
              $('#edit_care_instruction_hungarian').val(data.tesco_care_instruction[0].hungarian);
              $('#editTescoCareInstructionModal').modal({
                  backdrop: 'static',
                  keyboard: false
              })
              $('#edit-tesco-care-instruction-form').validator('validate');
              $('#editTescoCareInstructionModal').modal('show');
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
 * Update Care Additional Instruction
 */
function updateTescoCareInstruction() {
  try {
      var id = $('#edit_tesco_care_instruction_id').val();
      var english = $('#edit_care_instruction_english').val();
      var polish = $('#edit_care_instruction_polish').val();
      var czech = $('#edit_care_instruction_czech').val();
      var slovak = $('#edit_care_instruction_slovak').val();
      var hungarian = $('#edit_care_instruction_hungarian').val();
      
      $.ajax({
          url: base_url + "/admin/tesco/update-tesco-care-instruction-details",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
              $('#care_instruction_update_error').addClass('hidden');
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
              200: function(response) {
                  $('#editTescoCareInstructionModal').modal('hide');
                  $('.snackbar_span').html("Instruction Updated Successfully");
                  showSnackbar();
                  tescoCareAdditionalInstructions();
              },
              208: function() {
                  $('#care_instruction_update_error').removeClass('hidden');
                  $('#care_instruction_update_error').removeAttr('style');
                  $('#care_instruction_update_error').html('Instruction [English] already exists.')
                  $("#care_instruction_update_error").addClass("alert alert-warning");
              },
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              console.log(errorThrown);
              $('#care_instruction_update_error').removeClass('hidden');
              $('#care_instruction_update_error').removeAttr('style');
              $('#care_instruction_update_error').html('An error occurred while updating. Please try again.')
              $("#care_instruction_update_error").addClass("alert alert-danger");
          },
          complete: function() {
              $(".loader").hide();
          }
      });
  } catch (e) {
      console.log("Error:" + e);
      $('#care_instruction_update_error').removeClass('hidden');
      $('#care_instruction_update_error').removeAttr('style');
      $('#care_instruction_update_error').html('An unexpected error occurred. Please try again.')
      $("#care_instruction_update_error").addClass("alert alert-danger");
  }
}

/**
 * Enable Care Additional Instruction
 * @param id,csrf_token
 */
function enableTescoCareInstruction(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/enable-tesco-care-instruction",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { _token: csrf_token, id: id },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {
              200: function() {
                  isSubmitting = false;
                  $('.snackbar_span').html('Instruction is enabled ');
                  showSnackbar();
              }
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              console.log(errorThrown);
          },
          complete: function() {
            tescoCareAdditionalInstructions();
            $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Disable Care Additional Instruction
 * @param id,csrf_token
 */
function disableTescoCareInstruction(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/disable-tesco-care-instruction",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { _token: csrf_token, id: id },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {
              200: function() {
                  isSubmitting = false;
                  $('.snackbar_span').html('Instruction is disabled ');
                  showSnackbar();
              }
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              console.log(errorThrown);
          },
          complete: function() {
              tescoCareAdditionalInstructions();
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
} 