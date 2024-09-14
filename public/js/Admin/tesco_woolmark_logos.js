var isSubmitting = false;

$(document).on('click','.edit-tesco-woolmark-logo',function(e){
   var id = $(this).closest('tr').attr('id');
   e.preventDefault();
   getTescoWoolmarkLogoById(id);
});


$('#add_woolmark_logo_form').validator().on('submit', function (e) {
 if (e.isDefaultPrevented())
 {

 }
 else
 {
    e.preventDefault();
    
    var woolmark_symbol = $(this).find("input[name='woolmark_symbol']")[0].files[0];
    var woolmark_logo_text = $(this).find("input[name='woolmark_logo_text']").val();

    addWoolmarkLogo(woolmark_symbol, woolmark_logo_text);
 }
})

$('#edit_woolmark_logo_form').validator().on('submit', function (e) {
 if (e.isDefaultPrevented())
 {

 }
 else
 {
    e.preventDefault();
    
    var woolmark_logo = $(this).find("input[name='edit_woolmark_logo']")[0].files[0];
    var woolmark_logo_text = $(this).find("input[name='edit_woolmark_logo_text']").val();
    var id = $(this).find("input[name='edit_woolmark_logo_id']").val();

    editWoolmarkLogo(id,woolmark_logo, woolmark_logo_text);
 }
})

$(document).on('click', '#make_woolmark_logo_enabled', function(e) {
   var id = $(this).closest('tr').attr('id');
   
   enableWoolmarkLogo(id,csrf_token)
});

$(document).on('click', '#make_woolmark_logo_disabled', function(e) {
   var id = $(this).closest('tr').attr('id');
   
   disableWoolmarkLogo(id,csrf_token)
});

function woolMarkLogos()
{
    try{
    $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.tesco-sidebar').show();
    $('.orchestra-sidebar').hide();
    
    
    $.ajax({
        url: base_url + "/admin/tesco/settings/tesco-woolmark-logos",
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
                var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addWoolmArkModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Woolmark Logo</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Woolmark Logos</h3></div><table id="tesco-woolmark-logos-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Symbol</th><th>Wee Symbol Text</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
               $.each(data.tesco_woolmark_logos, function(i, item){
                var index = i+1;
                tesco_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td class="td_astms_symbol"><img width="30px" src="' + item.woolmark_logo + '"></td><td>'+item.woolmark_logo_text+'</td>';
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
                  tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_woolmark_logo_disabled" data-placement="left"></i></a>';
                 }
                 else
                 {
                  tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_woolmark_logo_enabled" data-placement="left"></i></a>';
                 }


                tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-woolmark-logo filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
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
            console.log(errorThrown);
        },
        complete: function () {
            $(".loader").hide();
        }
    })
    } catch (e) {
     console.log("Error:"+e)
   }
}



function addWoolmarkLogo(woolmark_symbol, woolmark_logo_text) {
  try {
      
      var form_data = new FormData();

      form_data.append('woolmark_logo', woolmark_symbol);
      form_data.append('woolmark_logo_text', woolmark_logo_text);
      form_data.append('_token', csrf_token);

      $.ajax({
          url: base_url + "/admin/tesco/settings/add-tesco-woolmark-logo",
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
                  $('.snackbar_span').html('Woolmark Logo Added Successfully');
                  showSnackbar();
                  $('#addWoolmArkModal').modal('hide');
                  woolMarkLogos();
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



function editWoolmarkLogo(id,woolmark_logo, woolmark_logo_text) {
  try {
      
      var form_data = new FormData();

      form_data.append('id', id);
      form_data.append('woolmark_logo', woolmark_logo);
      form_data.append('woolmark_logo_text', woolmark_logo_text);
      form_data.append('_token', csrf_token);

      $.ajax({
          url: base_url + "/admin/tesco/settings/update-tesco-woolmark-logo",
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
                  $('.snackbar_span').html('Woolmark Logo Updated Successfully');
                  showSnackbar();
                  $('#editWoolmarkModal').modal('hide');
                  woolMarkLogos();
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



function getTescoWoolmarkLogoById(id) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/settings/get-tesco-woolmark-logo",
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
              $('#edit_woolmark_logo_id').val(data.tesco_woolmark_log[0].id);
              $('#edit_woolmark_logo_text').val(data.tesco_woolmark_log[0].woolmark_logo_text);
              $('#woolmark_logo_image').attr('src',data.tesco_woolmark_log[0].woolmark_logo);

              $('#editWoolmarkModal').modal({
                  backdrop: 'static',
                  keyboard: false
              })
              $('#edit_woolmark_logo_form').validator('validate');
              $('#editWoolmarkModal').modal('show');
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
function enableWoolmarkLogo(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/settings/make-tesco-woolmark-logo-enabled",
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
                  $('.snackbar_span').html('Woolmark Logo enabled ');
                  showSnackbar();
              }
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              console.log(errorThrown);
          },
          complete: function() {
            woolMarkLogos();
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
function disableWoolmarkLogo(id, csrf_token) {
    try {
        if (isSubmitting) {
            return;
        }
        isSubmitting = true;
        $.ajax({
            url: base_url + "/admin/tesco/settings/make-tesco-woolmark-logo-disabled",
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
                    $('.snackbar_span').html('Woolmark Logo disabled ');
                    showSnackbar();
                }
            },
            error: function(errorXHR, errorStatus, errorThrown) {
                console.log(errorThrown);
            },
            complete: function() {
                woolMarkLogos();
                $(".loader").hide();
            }
        })
    } catch (e) {
        console.log("Error:" + e)
    }
}