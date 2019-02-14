/**
  * Function to ERP Settings home page
  * @author <Praveen>
  */
var isSubmitting = false;
 function dispatchMode() {
    try{
         $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
         $('.erp-sidebar-main').show();
         $('.li-setting').removeClass('active');
         $('.dispatchmodeli').addClass('active');
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


  function machines() {
    try{
         $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
         $('.erp-sidebar-main').show();
         $('.li-setting').removeClass('active');
         $('.machineli').addClass('active');
        $.ajax({
            url: base_url + "/erp/settings/machine",
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
                    var html_content = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addERPMachineModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Machine</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Machines</h3></div><table id="machine-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Machine</th><th>Description</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                    $.each(data.erp_machines, function(i, item){
                        var index = i+1;
                        html_content += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.machine+'</td><td>'+item.description+'</td>';
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
                             html_content += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_machine_disabled" data-placement="left"></i></a>';
                        }
                        else
                        {
                             html_content += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_machine_enabled" data-placement="left"></i></a>';
                        }
                        
                        html_content += '<a href="#"><i class="fa fa-pencil fa-button edit-machine filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                    });
                    html_content += '</tbody></table></div>';
                        $('.inner-content').html(html_content)
                        $('.edit-machine-mode').tooltip('show')
                        $('.edit-machine-mode').tooltip('toggle')
                        $('#machine-list-table').DataTable();
                        $("#machine-list-table_filter input").addClass('form-control input-sm');
                        $("#machine-list-table_filter input").attr('placeholder', 'Data Search');
                        $('#machine-list-table_length select').addClass('form-control input-sm');
    
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


  function teethNo() {
    try{
         $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
         $('.erp-sidebar-main').show();
         $('.li-setting').removeClass('active');
         $('.teethnoli').addClass('active');
        $.ajax({
            url: base_url + "/erp/settings/teethno",
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
                    var html_content = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addERPTeethNoModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Teeth Number</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Teeth Number</h3></div><table id="teethno-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Teeth Number</th><th>Description</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                    $.each(data.erp_teeth_no, function(i, item){
                        var index = i+1;
                        html_content += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.teeth_no+'</td><td>'+item.description+'</td>';
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
                             html_content += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_teethno_disabled" data-placement="left"></i></a>';
                        }
                        else
                        {
                             html_content += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_teethno_enabled" data-placement="left"></i></a>';
                        }
                        
                        html_content += '<a href="#"><i class="fa fa-pencil fa-button edit-teethno filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                    });
                    html_content += '</tbody></table></div>';
                        $('.inner-content').html(html_content)
                        $('.edit-teethno-mode').tooltip('show')
                        $('.edit-teethno-mode').tooltip('toggle')
                        $('#teethno-list-table').DataTable();
                        $("#teethno-list-table_filter input").addClass('form-control input-sm');
                        $("#teethno-list-table_filter input").attr('placeholder', 'Data Search');
                        $('#teethno-list-table_length select').addClass('form-control input-sm');
    
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


  function finishType() {
    try{
         $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
         $('.erp-sidebar-main').show();
         $('.li-setting').removeClass('active');
         $('.finishtypeli').addClass('active');
        $.ajax({
            url: base_url + "/erp/settings/finishtype",
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
                    var html_content = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addERPFinishTypeModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Finish Type</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Finish Type</h3></div><table id="finishtype-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Finish Type</th><th>Description</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                    $.each(data.erp_finish_type, function(i, item){
                        var index = i+1;
                        html_content += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.finish_type+'</td><td>'+item.description+'</td>';
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
                             html_content += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_finishtype_disabled" data-placement="left"></i></a>';
                        }
                        else
                        {
                             html_content += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_finishtype_enabled" data-placement="left"></i></a>';
                        }
                        
                        html_content += '<a href="#"><i class="fa fa-pencil fa-button edit-finishtype filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                    });
                    html_content += '</tbody></table></div>';
                        $('.inner-content').html(html_content)
                        $('.edit-finishtype-mode').tooltip('show')
                        $('.edit-finishtype-mode').tooltip('toggle')
                        $('#finishtype-list-table').DataTable();
                        $("#finishtype-list-table_filter input").addClass('form-control input-sm');
                        $("#finishtype-list-table_filter input").attr('placeholder', 'Data Search');
                        $('#finishtype-list-table_length select').addClass('form-control input-sm');
    
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
  * Adds a Machine.
  * @author <gowtham>
  */
 function addMachine()
 {
    try{
         var machine    = $('#machine').val();
         var description  = $('#machine_desc').val();

         $.ajax({
             url: base_url + "/erp/settings/add-machine",
             method: 'POST',
             data: {_token:csrf_token,description:description,machine:machine},
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Machine Added Successfully");
                       showSnackbar();
                       $('#addERPMachineModal').modal('hide');
                       machines()
                  },
                  208: function() {
                       $('#machine_result').removeAttr('style');
                       $('#machine_result').html('Machine already exists.')
                       $("#machine_result").addClass("alert alert-warning");
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
  * Adds a Teeth Number.
  * @author <gowtham>
  */
 function addTeethNo()
 {
    try{
         var teethno    = $('#teethno').val();
         var description  = $('#teethno_desc').val();

         $.ajax({
             url: base_url + "/erp/settings/add-teethno",
             method: 'POST',
             data: {_token:csrf_token,description:description,teeth_no:teethno},
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Teeth Number Added Successfully");
                       showSnackbar();
                       $('#addERPTeethNoModal').modal('hide');
                       teethNo()
                  },
                  208: function() {
                       $('#teethno_result').removeAttr('style');
                       $('#teethno_result').html('Teeth Number already exists.')
                       $("#teethno_result").addClass("alert alert-warning");
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
  * Adds a Finish Tyoe.
  * @author <gowtham>
  */
 function addFinishType()
 {
    try{
         var finishtype    = $('#finishtype').val();
         var description  = $('#finishtype_desc').val();

         $.ajax({
             url: base_url + "/erp/settings/add-finishtype",
             method: 'POST',
             data: {_token:csrf_token,description:description,finish_type:finishtype},
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Teeth Number Added Successfully");
                       showSnackbar();
                       $('#addERPFinishTypeModal').modal('hide');
                       finishType()
                  },
                  208: function() {
                       $('#finishtype_result').removeAttr('style');
                       $('#finishtype_result').html('Teeth Number already exists.')
                       $("#finishtype_result").addClass("alert alert-warning");
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
   * Function for get tesco brands details for editing
   * @param id
   * @author <gowtham>
   *
   */
  function getMachineById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/erp/settings/get-machine-by-id",
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
                 $('#edit_machine_id').val(id);
                 $('#edit_machine').val(data.erp_machines[0].machine);
                 $('#edit_machine_desc').val(data.erp_machines[0].description);
                 $('#editERPMachineModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_machine_form').validator('validate');
                 $('#editERPMachineModal').modal('show');
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
   * Function for get teeth number for editing
   * @param id
   * @author <gowtham>
   *
   */
  function getTeethNoById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/erp/settings/get-teethno-by-id",
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
                 $('#edit_teethno_id').val(id);
                 $('#edit_teethno').val(data.erp_teeth_no[0].teeth_no);
                 $('#edit_teethno_desc').val(data.erp_teeth_no[0].description);
                 $('#editERPTeethNoModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_teethno_form').validator('validate');
                 $('#editERPTeethNoModal').modal('show');
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
   * Function for get finish type for editing
   * @param id
   * @author <gowtham>
   *
   */
  function getFinishTypeById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/erp/settings/get-finishtype-by-id",
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
                 $('#edit_finishtype_id').val(id);
                 $('#edit_finishtype').val(data.erp_finish_type[0].finish_type);
                 $('#edit_finishtype_desc').val(data.erp_finish_type[0].description);
                 $('#editERPFinishTypeModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_finishtype_form').validator('validate');
                 $('#editERPFinishTypeModal').modal('show');
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
  * Function for edit machine
  * @author <gowtham>
  */
 function editMachine()
 {
  try{
       var id            = $('#edit_machine_id').val();
       var edit_machine = $('#edit_machine').val();
       var description   = $('#edit_machine_desc').val();

       $.ajax({
           url:base_url + "/erp/settings/update-machine-by-id",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
           },
           method:"put",
           data:{_token:csrf_token,id:id,machine:edit_machine,description:description},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Machine Updated Successfully");
                       showSnackbar();
                       $('#editERPMachineModal').modal('hide');
                       machines()
                  },
                208: function() {
                     $('#edit_machine_result').removeAttr('style');
                     $('#edit_machine_result').html('Machine already exists.')
                     $("#edit_machine_result").addClass("alert alert-warning");
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
  * Function for edit teeth no
  * @author <gowtham>
  */
 function editTeethNo()
 {
  try{
       var id            = $('#edit_teethno_id').val();
       var edit_teethno = $('#edit_teethno').val();
       var description   = $('#edit_teethno_desc').val();

       $.ajax({
           url:base_url + "/erp/settings/update-teethno-by-id",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
           },
           method:"put",
           data:{_token:csrf_token,id:id,teeth_no:edit_teethno,description:description},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Teeth No Updated Successfully");
                       showSnackbar();
                       $('#editERPTeethNoModal').modal('hide');
                       teethNo()
                  },
                208: function() {
                     $('#edit_teethno_result').removeAttr('style');
                     $('#edit_teethno_result').html('Teeth No already exists.')
                     $("#edit_teethno_result").addClass("alert alert-warning");
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
  * Function for edit finish type
  * @author <gowtham>
  */
 function editFinishType()
 {
  try{
       var id            = $('#edit_finishtype_id').val();
       var edit_finishtype = $('#edit_finishtype').val();
       var description   = $('#edit_finishtype_desc').val();

       $.ajax({
           url:base_url + "/erp/settings/update-finishtype-by-id",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
           },
           method:"put",
           data:{_token:csrf_token,id:id,finish_type:edit_finishtype,description:description},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Finish Type Updated Successfully");
                       showSnackbar();
                       $('#editERPFinishTypeModal').modal('hide');
                       finishType()
                  },
                208: function() {
                     $('#edit_finishtype_result').removeAttr('style');
                     $('#edit_finishtype_result').html('Finish Type already exists.')
                     $("#edit_finishtype_result").addClass("alert alert-warning");
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
  * Makes a Dispatch Mode Enabled.
  * @param id
  * @author <Praveen>
  */
 function makeMachineEnabled(id)
 {
  try{
        if(isSubmitting) 
        {
            return;
        }
        isSubmitting = true;
        $.ajax({
             url:base_url + "/erp/settings/make-machine-enabled",
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
                      $('.snackbar_span').html('Machine is enabled ');
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
               machines();
               $(".loader").hide();
             }
         })
  } catch (e) {
   console.log("Error:"+e)
  }
 }


/**
  * Makes a Teeth No Enabled.
  * @param id
  * @author <Praveen>
  */
 function makeTeethNoEnabled(id)
 {
  try{
        if(isSubmitting) 
        {
            return;
        }
        isSubmitting = true;
        $.ajax({
             url:base_url + "/erp/settings/make-teethno-enabled",
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
                      $('.snackbar_span').html('Teeth No is enabled ');
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
               teethNo();
               $(".loader").hide();
             }
         })
  } catch (e) {
   console.log("Error:"+e)
  }
 }
/**
  * Makes a Finish Type Enabled.
  * @param id
  * @author <Praveen>
  */
 function makeFinishTypeEnabled(id)
 {
  try{
        if(isSubmitting) 
        {
            return;
        }
        isSubmitting = true;
        $.ajax({
             url:base_url + "/erp/settings/make-finishtype-enabled",
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
                      $('.snackbar_span').html('Finish Type is enabled ');
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
               finishType();
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

/**
* Makes a Dispatch Mode Diabled
* @param id
* @author <gowtham>
*/
function makeMachineDisabled(id)
 {
  try{
        if(isSubmitting) 
        {
            return;
        }
        isSubmitting = true;
        $.ajax({
             url:base_url + "/erp/settings/make-machine-disabled",
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
                       $('.snackbar_span').html('Machine is disabled ');
                       showSnackbar();
                  },
                 208: function() {
                      isSubmitting = false;
                      $('.snackbar_span').html("This action can't be completed because machine is in use.")
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
               machines();
               $(".loader").hide();
             }
         })
  } catch (e) {
   console.log("Error:"+e)
  }
 }
/**
* Makes a Teeth No Diabled
* @param id
* @author <gowtham>
*/
function makeTeethNoDisabled(id)
 {
  try{
        if(isSubmitting) 
        {
            return;
        }
        isSubmitting = true;
        $.ajax({
             url:base_url + "/erp/settings/make-teethno-disabled",
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
                       $('.snackbar_span').html('Teeth No is disabled ');
                       showSnackbar();
                  },
                 208: function() {
                      isSubmitting = false;
                      $('.snackbar_span').html("This action can't be completed because teeth no is in use.")
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
               teethNo();
               $(".loader").hide();
             }
         })
  } catch (e) {
   console.log("Error:"+e)
  }
 }
/**
* Makes a Finish type Diabled
* @param id
* @author <gowtham>
*/
function makeFinishTypeDisabled(id)
 {
  try{
        if(isSubmitting) 
        {
            return;
        }
        isSubmitting = true;
        $.ajax({
             url:base_url + "/erp/settings/make-finishtype-disabled",
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
                       $('.snackbar_span').html('Finish Type is disabled ');
                       showSnackbar();
                  },
                 208: function() {
                      isSubmitting = false;
                      $('.snackbar_span').html("This action can't be completed because finish type is in use.")
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
               finishType();
               $(".loader").hide();
             }
         })
  } catch (e) {
   console.log("Error:"+e)
  }
 }