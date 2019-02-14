/**
  * Function to ERP Settings home page
  * @author <Praveen>
  */
var isSubmitting = false;
 function dispatchMode() {
    try{
         $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
         $('.erp-sidebar-main').show();
      
        $.ajax({
            url: base_url + "/erp/settings/dispatch-mode",
            type: "GET",
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
                    var html_content = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addERPDispModeModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Dispatch Mode</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Dispatch Modes</h3></div><table id="dispatch-mode-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Dispatch Mode</th><th>Description</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                    $.each(data.erp_dispatch_mode, function(i, item){
                        var index = i+1;
                        html_content += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.disp_mode+'</td><td>'+item.description+'</td>';
                        if(item.is_enabled=== true)
                        {
                             html_content += '<td><span class="label label-success">Enabled</span></td>';
                        }
                        else
                        {
                             html_content += '<td><span class="label label-danger">Disabled</span></td>';
                        }
                        if(item.is_enabled=== true)
                        {
                             html_content += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_dispatch_mode_disabled" data-placement="left"></i></a>';
                        }
                        else
                        {
                             html_content += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_dispatch_mode_enabled" data-placement="left"></i></a>';
                        }
                        
                        html_content += '<a href="#"><i class="fa fa-pencil fa-button edit-dispatch-mode filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                    });
                    html_content += '</tbody></table></div>';
                        $('.inner-content').html(html_content)
                        $('.edit-dispatch-mode').tooltip('show')
                        $('.edit-dispatch-mode').tooltip('toggle')
                        $('#dispatch-mode-list-table').DataTable();
                        $("#dispatch-mode-list-table_filter input").addClass('form-control input-sm');
                        $("#dispatch-mode-list-table_filter input").attr('placeholder', 'Data Search');
                        $('#dispatch-mode-list-table_length select').addClass('form-control input-sm');
    
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

  /**
  * Adds a dispatch mode.
  * @author <Praveen>
  */
 function addDispatchMode()
 {
    try{
         var disp_mode    = $('#dispatch_mode').val();
         var description  = $('#dispatch_mode_desc').val();

         $.ajax({
             url: base_url + "/erp/settings/add-dispatch-mode",
             method: 'POST',
             data: {_token:csrf_token,description:description,disp_mode:disp_mode},
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Dispatch Mode Added Successfully");
                       showSnackbar();
                       $('#addERPDispModeModal').modal('hide');
                       dispatchMode()
                  },
                  208: function() {
                       $('#dispatch_mode_result').removeAttr('style');
                       $('#dispatch_mode_result').html('Dispatch Mode already exists.')
                       $("#dispatch_mode_result").addClass("alert alert-warning");
                  },
                  400: function() {
                       $('.snackbar_span').html("Something went wrong!!");
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
             complete: function () {
                 $(".loader").hide();
             }
         })
     } catch (e) {
     console.log("Error:"+e)
   }
  }

  
 /**
   * Function for get tesco brands details for editing
   * @param id
   * @author <Praveen>
   *
   */
  function getDispatchModeById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/erp/settings/get-dispatch-mode-by-id",
             dataType:"json",
             method:"get",
             data:{id:id},
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_dispatch_mode_id').val(id);
                 $('#edit_dispatch_mode').val(data.erp_dispatch_mode[0].disp_mode);
                 $('#edit_dispatch_mode_desc').val(data.erp_dispatch_mode[0].description);
                 $('#editERPDispModeModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_dispatch_mode_form').validator('validate');
                 $('#editERPDispModeModal').modal('show');
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

  /**
  * Function for edit dispatch mode
  * @author <Praveen>
  */
 function editDispatchMode()
 {
  try{
       var id            = $('#edit_dispatch_mode_id').val();
       var dispatch_mode = $('#edit_dispatch_mode').val();
       var description   = $('#edit_dispatch_mode_desc').val();

       $.ajax({
           url:base_url + "/erp/settings/update-dispatch-mode-by-id",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
           },
           method:"put",
           data:{_token:csrf_token,id:id,dispatch_mode:dispatch_mode,description:description},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Dispatch Mode Updated Successfully");
                       showSnackbar();
                       $('#editERPDispModeModal').modal('hide');
                       dispatchMode()
                  },
                208: function() {
                     $('#edit_dispatch_mode_result').removeAttr('style');
                     $('#edit_dispatch_mode_result').html('Dispatch Mode already exists.')
                     $("#edit_dispatch_mode_result").addClass("alert alert-warning");
                },
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
 /**
  * Makes a Dispatch Mode Enabled.
  * @param id
  * @author <Praveen>
  */
 function makeDispatchModeEnabled(id)
 {
  try{
        if(isSubmitting) 
        {
            return;
        }
        isSubmitting = true;
        $.ajax({
             url:base_url + "/erp/settings/make-dispatch-mode-enabled",
             dataType:"json",
             beforeSend: function () {
               $(".loader").show();
             },
             method:"put",
             data:{_token:csrf_token,id:id},
             tryCount : 0,
             retryLimit : 2,
             statusCode: {
                  /** [Show response status based on HTTP status code] */
                  200: function() {
                      isSubmitting = false; 
                      $('.snackbar_span').html('Dispatch Mode is enabled ');
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
             complete: function () {
               dispatchMode();
               $(".loader").hide();
             }
         })
  } catch (e) {
   console.log("Error:"+e)
  }
 }

/**
* Makes a Dispatch Mode Diabled
* @param id
* @author <Praveen>
*/
function makeDispatchModeDisabled(id)
 {
  try{
        if(isSubmitting) 
        {
            return;
        }
        isSubmitting = true;
        $.ajax({
             url:base_url + "/erp/settings/make-dispatch-mode-disabled",
             dataType:"json",
             beforeSend: function () {
               $(".loader").show();
             },
             method:"put",
             data:{_token:csrf_token,id:id},
             tryCount : 0,
             retryLimit : 2,
             statusCode: {
                  /** [Show response status based on HTTP status code] */
                  200: function() {
                       isSubmitting = false;
                       $('.snackbar_span').html('Dispatch Mode is disabled ');
                       showSnackbar();
                  },
                 208: function() {
                      isSubmitting = false;
                      $('.snackbar_span').html("This action can't be completed because dispatch mode is in use.")
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
             complete: function () {
               dispatchMode();
               $(".loader").hide();
             }
         })
  } catch (e) {
   console.log("Error:"+e)
  }
 }