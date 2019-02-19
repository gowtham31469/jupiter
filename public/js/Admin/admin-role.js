/* Admin Role Management script 
 * @author <praveen>
 */


/**
* Validation for numeric values
* @param  evt  
* @return {Boolean} 
* @author <praveen>
*/
function isNumber(evt) {
try{
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57))
  {
      return false;
  }
    return true;
} catch (e) {
 console.log("Error:"+e)
}
}

/**
* showSnackbar function
* @author    <praveen>
*/
function showSnackbar() {
try{
  /** [ Get the snackbar DIV ] */
  var x = document.getElementById("snackbar")

  /** [ Add the "show" class to DIV ] */
  x.className = "show";

  /** [  After 3 seconds, remove the show class from DIV ] */
  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
  } catch (e) {
   console.log("Error:"+e)
 }
}
/**
* Function to show all admin list
* @author <praveen>
*/
var isSubmitting = false;
function admin()
{
 var csrf_token = $('meta[name="csrf-token"]').attr('content');
 try {
  $.ajax({
      url: base_url + "/admin/admin-role-management/admin-list",
      type: "get",
      dataType:"json",
      data: {_token:csrf_token},
      tryCount : 0,
      retryLimit : 2,
      beforeSend: function () {
          $(".loader").show();
      },
      success: function (data) {
          var table = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="col" id="admin-table"><table id="admin-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
          $.each(data.admin, function(i, item){
           var index = i+1;
           table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.name+'</td><td>'+item.email+'</td><td>'+item.phone+'</td><td>'+item.address+'</td>';
           if(item.is_active === 1)
            {
             table += '<td><span class="label label-success">Active</span></td>';
            }
            else
            {
             table += '<td><span class="label label-danger">Inactive</span></td>';
            }
            if(item.is_active === 1)
            {
             table += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Inactive" id="make_inactive" data-placement="left"></i></a>';
            }
            else
            {
             table += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Active" id="make_active" data-placement="left"></i></a>';
            }
          });
          table += '</tbody></table></div>';
           $('.main-content').html(table);

           /** [ Toggle bootstrap tooltip ] */
           $('.status').tooltip('show')
           $('.status').tooltip('toggle')

           /** [ Data Tabeles scripts ] */
           $('#admin-list-table').DataTable();
           $("#admin-list-table_filter input").addClass('form-control input-sm');
           $("#admin-list-table_filter input").attr('placeholder', 'Data Search');
           $('#admin-list-table_length select').addClass('form-control input-sm');
      },
      error: function(errorXHR, errorStatus, errorThrown) {
         this.tryCount++;
         if (this.tryCount <= this.retryLimit) {
             $.ajax(this);
             return;
         }
      },
      complete: function () {
           /** [To make top menu active] */
           $(function () {
               var url = window.location;
               $('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
               $('.dynamic-menu a').filter(function () {
                   return this.href == url;
               }).parent('li').addClass('current-page').find('a').addClass('active');
           });
           $(".loader").hide();
      }
  })
} catch (e) {
  console.log("Error:"+e)
}
}
/**
* Adds a admin.
* @author <praveen>
*/
function addAdmin()
{
try{
      var name                   = toTitleCase($('#name').val());
      var email                  = $('#email').val();
      var password               = $('#password').val();
      var phone                  = $('#phone').val();
      var address                = toTitleCase($('#address').val().replace(/,/g, ", "));
      var csrf_token             = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
          url: base_url + "/admin/admin-role-management/add-new-admin",
          method: 'POST',
          data: {_token:csrf_token,name:name,email:email,phone:phone,address:address,p:password},
          tryCount : 0,
          retryLimit : 2,
          beforeSend: function () {
            $(".loader").show();
          },
          statusCode: {

               /** [Show response status based on HTTP status code] */
               200: function() {
                    $('.snackbar_span').html('Admin Added Successfully');
                    showSnackbar();
                    $('#addAdminModal').modal('hide');
                    admin();
               },
               400: function() {
                    $('.snackbar_span').html("Something went wrong!!");
                    showSnackbar();
               },
               409: function() {
                    $('#add_admin_result').removeAttr('style');
                    $('#add_admin_result').html('Email already registered.')
                    $("#add_admin_result").addClass("alert alert-warning");
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
                    /** [ To make top menu active ] */
                    $(function () {
                        var url = window.location;
                        $('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
                        $('.dynamic-menu a').filter(function () {
                            return this.href == url;
                        }).parent('li').addClass('current-page').find('a').addClass('active');
                    });
                    $(".loader").hide();
          }
      })
  } catch (e) {
 console.log("Error:"+e)
}
}
/**
* Makes a admin active.
* @param id,csrf_token
* @author <praveen>
*/
function makeAdminActive(id,csrf_token)
 {
  try{
        if(isSubmitting) 
        {
            return;
        }
        isSubmitting = true;
        $.ajax({
             url:base_url + "/admin/admin-role-management/make-admin-active",
             dataType:"json",
             beforeSend: function () {
               $(".loader").show();
             },
             method:"put",
             data:{_token:csrf_token,id:id},
             tryCount : 0,
             retryLimit : 2,
             statusCode: {

                  /** [ Show response status based on HTTP status code ] */
                  200: function() {
                      isSubmitting = false; 
                      $('.snackbar_span').html('Admin is active now ');
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
               admin();
               /** [ To make top menu active ] */
              $(function () {
                  var url = window.location;
                  $('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
                  $('.dynamic-menu a').filter(function () {
                      return this.href == url;
                  }).parent('li').addClass('current-page').find('a').addClass('active');
              });
               $(".loader").hide();
             }
         })
  } catch (e) {
   console.log("Error:"+e)
  }
 }

/**
* Makes a Admin inactive.
* @param id,csrf_token
* @author <praveen>
*/
function makeAdminInactive(id,csrf_token)
 {
  try{
        if(isSubmitting) 
        {
            return;
        }
        isSubmitting = true;
        $.ajax({
             url:base_url + "/admin/admin-role-management/make-admin-inactive",
             dataType:"json",
             beforeSend: function () {
               $(".loader").show();
             },
             method:"put",
             data:{_token:csrf_token,id:id},
             tryCount : 0,
             retryLimit : 2,
             statusCode: {

                  /** [ Show response status based on HTTP status code ] */
                  200: function() {
                       isSubmitting = false;
                       $('.snackbar_span').html('Admin is inactive now ');
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
               admin();
               /** [ To make top menu active ] */
                $(function () {
                    var url = window.location;
                    $('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
                    $('.dynamic-menu a').filter(function () {
                        return this.href == url;
                    }).parent('li').addClass('current-page').find('a').addClass('active');
                });
               $(".loader").hide();
             }
         })
  } catch (e) {
   console.log("Error:"+e)
  }
 }
