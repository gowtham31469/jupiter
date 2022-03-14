var isSubmitting = false;

$(document).on('click','.edit-tesco-wee-symbol',function(e){
   var id = $(this).closest('tr').attr('id');
   e.preventDefault();
   getTescoWeeSymbolById(id);
});


$('#add_wee_symbol_form').validator().on('submit', function (e) {
 if (e.isDefaultPrevented())
 {

 }
 else
 {
    e.preventDefault();
    
    var wee_symbol_file = $(this).find("input[name='wee_symbol']")[0].files[0];
    var wee_symbol_text = $(this).find("input[name='wee_symbol_text']").val();

    addWeeSymbol(wee_symbol_file, wee_symbol_text);
 }
})

$('#edit_wee_symbol_form').validator().on('submit', function (e) {
 if (e.isDefaultPrevented())
 {

 }
 else
 {
    e.preventDefault();
    
    var wee_symbol_file = $(this).find("input[name='edit_wee_symbol']")[0].files[0];
    var wee_symbol_text = $(this).find("input[name='edit_wee_symbol_text']").val();
    var id = $(this).find("input[name='edit_wee_symbol_id']").val();

    editWeeSymbol(id,wee_symbol_file, wee_symbol_text);
 }
})

$(document).on('click', '#make_wee_symbol_enabled', function(e) {
   var id = $(this).closest('tr').attr('id');
   
   enableWeeSymbol(id,csrf_token)
});

$(document).on('click', '#make_wee_symbol_disabled', function(e) {
   var id = $(this).closest('tr').attr('id');
   
   disableWeeSymbol(id,csrf_token)
});

function weeSymbols()
{
    try{
    $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.tesco-sidebar').show();
    $('.orchestra-sidebar').hide();
    
    
    $.ajax({
        url: base_url + "/admin/tesco/settings/tesco-wee-symbols",
        type: "POST",
        dataType: "json",
        data: {
            _token: csrf_token
        },
        tryCount : 0,
        retryLimit : 2,
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
                var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addWeeSymbolModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Wee Symbol</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Wee Symbols</h3></div><table id="tesco-wee-symbols-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Symbol</th><th>Wee Symbol Text</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
               $.each(data.tesco_wee_symbols, function(i, item){
                var index = i+1;
                tesco_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td class="td_astms_symbol"><img width="30px" src="' + item.wee_symbol + '"></td><td>'+item.wee_symbol_text+'</td>';
                 if(item.is_enabled)
                 {
                  tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
                 }
                 else
                 {
                  tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                 }
                 if(item.is_enabled)
                 {
                  tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_wee_symbol_disabled" data-placement="left"></i></a>';
                 }
                 else
                 {
                  tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_wee_symbol_enabled" data-placement="left"></i></a>';
                 }


                tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-wee-symbol filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
               });
               tesco_metadata += '</tbody></table></div>';
                $('.content').html(tesco_metadata)
                $('.edit-tesco-season_codes').tooltip('show')
                $('.edit-tesco-season_codes').tooltip('toggle')
                $('.status').tooltip('show')
                $('.status').tooltip('toggle')
                $('#tesco-brands-list-table').DataTable();
                $("#tesco-brands-list-table_filter input").addClass('form-control input-sm');
                $("#tesco-brands-list-table_filter input").attr('placeholder', 'Data Search');
                $('#tesco-brands-list-table_length select').addClass('form-control input-sm');

        },
        error: function(errorXHR, errorStatus, errorThrown) {
          this.tryCount++;
          if (this.tryCount <= this.retryLimit) {
              $.ajax(this);
              return;
          }
        },
        complete: function () {
            $(".loader").hide();
        }
    })
    } catch (e) {
     console.log("Error:"+e)
   }
}



function addWeeSymbol(wee_symbol_file, wee_symbol_text) {
  try {
      
      var form_data = new FormData();

      form_data.append('wee_symbol_file', wee_symbol_file);
      form_data.append('wee_symbol_text', wee_symbol_text);
      form_data.append('_token', csrf_token);

      $.ajax({
          url: base_url + "/admin/tesco/settings/add-tesco-wee-symbols",
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
                  $('.snackbar_span').html('Wee Symbol Added Successfully');
                  showSnackbar();
                  $('#addWeeSymbolModal').modal('hide');
                  weeSymbols();
              },
              422: function() {
                  $('.hidden-error').html('<div class="alert alert-warning">\
                      Invalid File Format\
                    </div>')
              }
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      });
  } catch (e) {
      console.log("Error:" + e)
  }
}



function editWeeSymbol(id,wee_symbol_file, wee_symbol_text) {
  try {
      
      var form_data = new FormData();

      form_data.append('id', id);
      form_data.append('wee_symbol_file', wee_symbol_file);
      form_data.append('wee_symbol_text', wee_symbol_text);
      form_data.append('_token', csrf_token);

      $.ajax({
          url: base_url + "/admin/tesco/settings/update-tesco-wee-symbol",
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
                  $('.snackbar_span').html('Wee Symbol Updated Successfully');
                  showSnackbar();
                  $('#editWeeSymbolModal').modal('hide');
                  weeSymbols();
              },
              422: function() {
                  $('.hidden-error').html('<div class="alert alert-warning">\
                      Invalid File Format\
                    </div>')
              }
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      });
  } catch (e) {
      console.log("Error:" + e)
  }
}



function getTescoWeeSymbolById(id) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/settings/get-tesco-wee-symbol",
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
              $('#edit_wee_symbol_id').val(data.tesco_wee_symbol[0].id);
              $('#edit_wee_symbol_text').val(data.tesco_wee_symbol[0].wee_symbol_text);
              $('#wee_symbol_image').attr('src',data.tesco_wee_symbol[0].wee_symbol);

              $('#editWeeSymbolModal').modal({
                  backdrop: 'static',
                  keyboard: false
              })
              $('#edit_wee_symbol_form').validator('validate');
              $('#editWeeSymbolModal').modal('show');
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
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
function enableWeeSymbol(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/settings/make-tesco-wee-symbol-enabled",
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
                  $('.snackbar_span').html('Wee Symbol enabled ');
                  showSnackbar();
              }
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
            weeSymbols();
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
function disableWeeSymbol(id, csrf_token) {
    try {
        if (isSubmitting) {
            return;
        }
        isSubmitting = true;
        $.ajax({
            url: base_url + "/admin/tesco/settings/make-tesco-wee-symbol-disabled",
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
                    $('.snackbar_span').html('Wee Symbol disabled ');
                    showSnackbar();
                }
            },
            error: function(errorXHR, errorStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
            },
            complete: function() {
                weeSymbols();
                $(".loader").hide();
            }
        })
    } catch (e) {
        console.log("Error:" + e)
    }
}