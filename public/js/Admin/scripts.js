function usersListView()
{
    $('.main-content').html('<div class="loader"></div>');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $(".sub-nav-list li a").removeClass("active");
    $('.users-list-view a').addClass("active");
    $('#add_user_form').trigger("reset");
    $.ajax({
        url: base_url + "/admin/user-management/users",
        type: "get",
        dataType:"json",
        data: {_token:csrf_token},
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
        },
        success: function (data) {
          $.ajax({
              url: base_url + "/admin/user-management/get-user-roles",
              method: 'get',
              success: function (data) {
                console.log(data.roles);
                var appenddata = "<option value = ''>-- Select User Role --</option>";
                $.each(data.roles, function (key, value) {

                appenddata += "<option value = '"+value.id+"'>" +value.user_role_name+" </option>";
                });
              $('#user_role').html(appenddata);
              },
              error: function (data) {

              },
              complete: function () {
              }
          })
          $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">User Management</a></li><li class="active">Users</li></ol><h3><i class="ion-ios-browsers-outline"></i>User Management</h3><div class="main-sub-menu"><div class="action-group pull-right"><a href="#" data-target="#addUserModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-person-add"></i>Add Users</a></div><ul class="sub-nav-list"><li><a href=""><i class="ion-grid"></i>Dashboard</a></li><li><a class="active" href="#" onclick="usersListView()"><i class="ion-ios-pricetag"></i>Users</a></li><li class="roles-list-view"><a class="" href="#" onclick="rolesListView()"><i class="ion-ios-pricetag"></i>Roles</a></li></ul><hr/></div>');
          var table = '<div class="col" id="user-table"><table id="users-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Name</th><th>Role</th><th>Email</th><th>Phone</th><th>Address</th><th>Actions</th></tr></thead><tbody>';
          $.each(data.users, function(i, item){
            table += '<tr id="'+item.id+'"><td>'+item.user_id+'</td><td>'+item.name+'</td><td><span class="badge badge-pill ">'+item.user_role_name+'</span></td><td>'+item.email+'</td><td>'+item.phone+'</td><td>'+item.address+'</td>';
            if(item.user_role_id != 1)
            {
              table += '<td><a href="#"><i class="ion-edit edit-user" title="Edit"></i></a><a href="#"><i class="ion-trash-a" data-userid="'+item.id+'" title="Delete"  data-target="#deleteUserModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"></i></a></td>'
            }
            else
            {
              table += '<td></td>';
            }
              table += '</tr>';
          });
             table += '</tbody></table></div>';
             $('.main-content').html(table);
             $('#users-list-table').DataTable();
             $("#users-list-table_filter input").addClass('form-control');
             $("#users-list-table_filter input").attr('placeholder', 'Data Search');

        },
        error: function (data) {

        },
        complete: function () {
          $(".loader").hide();
          $(".loader").css('margin-top','0%');
        }
    })
}
function rolesListView()
{
    $('.main-content').html('<div class="loader"></div>');
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $(".sub-nav-list li a").removeClass("active");
    $('.roles-list-view a').addClass("active");
    $('#add_user_form').trigger("reset");
    $.ajax({
        url: base_url + "/admin/user-management/roles",
        type: "get",
        dataType:"json",
        data: {_token:csrf_token},
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
        },
        success: function (data) {
          $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">User Management</a></li><li class="active">Roles</li></ol><h3><i class="ion-ios-browsers-outline"></i>User Management</h3><div class="main-sub-menu"><div class="action-group pull-right"><a href="#" data-target="#addRoleModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-person-add"></i>Add Roles</a></div><ul class="sub-nav-list"><li><a href=""><i class="ion-grid"></i>Dashboard</a></li><li><a href="#" onclick="usersListView()"><i class="ion-ios-pricetag"></i>Users</a></li><li class="roles-list-view"><a class="active" href="#" onclick="rolesListView()"><i class="ion-ios-pricetag"></i>Roles</a></li></ul><hr/></div>');
          var table = '<div class="col" id="user-table"><table id="roles-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Role Name</th><th>Description</th><th>Actions</th></tr></thead><tbody>';
          $.each(data.roles, function(i, item){
           table += '<tr id="'+item.id+'"><td>'+item.role_id+'</td><td><span class="badge badge-pill ">'+item.user_role_name+'</span></td><td>'+item.user_role_description+'</td>';
           if(item.id != 1)
           {
             table += '<td><a href="#"><i class="ion-edit edit-user-role" title="Edit"></i></a><a href="#"><i class="ion-trash-a" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteUserRoleModal" ></i></a></td>'
           }
           else
           {
             table += '<td></td>';
           }
           table += '</tr>';
          });

          table += '</tbody></table></div>';
          $('.main-content').html(table);
          $('#roles-list-table').DataTable();
          $("#roles-list-table_filter input").addClass('form-control');
          $("#roles-list-table_filter input").attr('placeholder', 'Data Search');
        },
        error: function (data) {

        },
        complete: function () {
          $(".loader").hide();
          $(".loader").css('margin-top','0%');
        }
    })
}
function addUser()
{
    var user_id    = $('#user_id').val();
    var name       = $('#name').val();
    var email      = $('#email').val();
    var phone      = $('#phone').val();
    var address    = $('#address').val();
    var p          = $('#password').val();
    var user_role  =  $('#user_role option:selected').val();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: base_url + "/admin/user-management/add-jga-user",
        method: 'POST',
        data: {_token:csrf_token,user_id:user_id,name:name,email:email,phone:phone,address:address,user_role:user_role,p:p},
        beforeSend: function () {
          $(".loader").show();
          $(".loader").css('margin-top','12%');
        },
        statusCode: {
             200: function() {
                  $('#result').removeAttr('style');
                  $('#result').html('User Added Successfully')
                  $("#result").addClass("alert alert-success");
                  $('#addUserModal').modal('hide');
                  rolesListView();
                  usersListView();
             },
             400: function() {
               $('#result').removeAttr('style');
               $('#result').html("Something went wrong!!")
               $("#result").addClass("alert alert-dander");
             },
             409: function() {
                  $('#result').removeAttr('style');
                  $('#result').html('Email already registered.')
                  $("#result").addClass("alert alert-warning");
             }
        },
        error: function (data) {

        },
        complete: function () {
          $(".loader").hide();
          $(".loader").css('margin-top','0%');
        }
    })
 }
 var isSubmitting = false;
 function getUser(id)
 {
   if(isSubmitting) {
        return;
    }
    isSubmitting = true;
   $.ajax({
       url:base_url + "/admin/user-management/get-user-details",
       dataType:"json",
       method:"get",
       data:{id:id},
       beforeSend: function () {
         $(".loader").show();
         $(".loader").css('margin-top','12%');
         $.ajax({
             url: base_url + "/admin/user-management/get-user-roles",
             method: 'get',
             success: function (data) {
               var appenddata = "<option value = ''>-- Select User Role --</option>";
                 $.each(data.roles, function (key, value) {

                 appenddata += "<option value = '"+value.id+"'>" +value.user_role_name+" </option>";
                 });
             $('#edit_user_role').html(appenddata);
             },
             error: function (data) {

             },
             complete: function () {
             }
         })
       },
       success: function(data){
           isSubmitting = false;
           $('#user_edit_id').val(id);
           $('#edit_user_id').val(data.user[0].user_id);
           $('#edit_name').val(data.user[0].name);
           $('#edit_email').val(data.user[0].email);
           $('#edit_phone').val(data.user[0].phone);
           $('#edit_address').val(data.user[0].address);
           $('#edit_user_role').val(data.user[0].user_role_id);
           $('#editUserModal').modal({
                backdrop: 'static',
                keyboard: false
           })
           $('#edit_user_form').validator('validate');
           $('#editUserModal').modal('show');
       },
       complete: function () {
         $(".loader").hide();
         $(".loader").css('margin-top','0%');
       }
   })
 }
 function editUser()
 {
   var id         = $('#user_edit_id').val();
   var name       = $('#edit_name').val();
   var email      = $('#edit_email').val();
   var phone      = $('#edit_phone').val();
   var address    = $('#edit_address').val();
   var user_role  =  $('#edit_user_role option:selected').val();
   var csrf_token = $('meta[name="csrf-token"]').attr('content');
   $.ajax({
       url:base_url + "/admin/user-management/update-user-details",
       dataType:"json",
       beforeSend: function () {
         $(".loader").show();
         $(".loader").css('margin-top','12%');
       },
       method:"put",
       data:{_token:csrf_token,id:id,name:name,email:email,phone:phone,address:address,user_role:user_role},
       statusCode: {
            200: function() {
                 $('#edit_result').html('User Updated Successfully')
                 $("#edit_result").addClass("alert alert-success");
                 $('#edit_result').removeAttr('style');
                 $('#editUserModal').modal('hide');
                 usersListView();
            },
            400: function() {
              $('#edit_result').removeAttr('style');
              $('#edit_result').html("Something went wrong!!")
              $("#edit_result").addClass("alert alert-dander");
            },
            409: function() {
                 $('#edit_result').removeAttr('style');
                 $('#edit_result').html('Email already registered.')
                 $("#edit_result").addClass("alert alert-warning");
            }
       },
       complete: function () {
         $(".loader").hide();
         $(".loader").css('margin-top','0%');
       }
   })
 }
 function deleteUser(id)
 {
   var csrf_token = $('meta[name="csrf-token"]').attr('content');
   $.ajax({
       url:base_url + "/admin/user-management/delete-user",
       dataType:"json",
       beforeSend: function () {
         $(".loader").show();
         $(".loader").css('margin-top','12%');
       },
       method:"delete",
       data:{_token:csrf_token,id:id},
       statusCode: {
            200: function() {
                 $('#delete_result').removeAttr('style');
                 $('#delete_result').html('User deleted Successfully')
                 $("#delete_result").addClass("alert alert-success");
                 $('#deleteUserModal').modal('hide');
                 usersListView();
            },
            400: function() {
              $('#delete_result').removeAttr('style');
              $('#delete_result').html("Something went wrong!!")
              $("#delete_result").addClass("alert alert-dander");
            }
       },
       complete: function () {
         $(".loader").hide();
         $(".loader").css('margin-top','0%');
       }
   })
 }
$("#age").change(function(){
    alert();
})

function addRole(permissions)
{
    var id   = $('#role_id').val();
    var name = $('#role_name').val();
    var desc = $('#role_description').val();
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/user-management/add-user-role",
        method: 'POST',
        data: {_token:csrf_token,permissions:permissions,role_id:id,role_name:name,role_desc:desc},
        beforeSend: function () {
          $(".loader").show();
          $(".loader").css('margin-top','12%');
        },
        statusCode: {
             200: function() {
                  $('#role_result').removeAttr('style');
                  $('#role_result').html('User Role Added Successfully')
                  $("#role_result").addClass("alert alert-success");
                  $('#addRoleModal').modal('hide');
                  usersListView();
                  rolesListView();
             }
        },
        error: function (data) {

        },
        complete: function () {
          $(".loader").hide();
          $(".loader").css('margin-top','0%');
        }
    })
 }
 function getUserRole(id)
 {
   if(isSubmitting) {
        return;
    }
    isSubmitting = true;
   $.ajax({
       url:base_url + "/admin/user-management/get-user-role-details",
       dataType:"json",
       method:"get",
       data:{id:id},
       beforeSend: function () {
         $(".loader").show();
         $(".loader").css('margin-top','12%');
       },
       success: function(data){
           isSubmitting = false;
           $('#user_edit_role_id').val(id);
           $('#edit_role_id').val(data.user_role[0].role_id);
           $('#edit_role_name').val(data.user_role[0].user_role_name);
           $('#edit_role_description').val(data.user_role[0].user_role_description);

           if (data.error == "false") {
               var parsedData = [];
               data.permissions.forEach(function (index) {
                   parsedData.push(index.module_id);
               })
               $('.role_permissions_div').find('input[type="checkbox"]').each(function () {
                   var permitted_mod = parseInt($(this).val());

                   if ($.inArray(permitted_mod, parsedData) != -1) {
                       $(this).prop('checked', true);
                   } else {
                       $(this).prop('checked', false);
                   }
               })
           } else {

           }
           $('#editRoleModal').modal({
                backdrop: 'static',
                keyboard: false
           })
           $('#edit_role_form').validator('validate');
           $('#editRoleModal').modal('show');
       },
       complete: function () {
         $(".loader").hide();
         $(".loader").css('margin-top','0%');
       }
   })
 }
 function updateRole(permissions)
 {
     var id         = $('#user_edit_role_id').val();
     var role_id    = $('#edit_role_id').val();
     var name       = $('#edit_role_name').val();
     var desc       = $('#edit_role_description').val();
     var csrf_token = $('meta[name="csrf-token"]').attr('content');
     $.ajax({
         url: base_url + "/admin/user-management/update-user-role",
         dataType:"json",
         method:"put",
         data: {_token:csrf_token,permissions:permissions,role_id:role_id,role_name:name,role_desc:desc,id:id},
         beforeSend: function () {
           $(".loader").show();
           $(".loader").css('margin-top','12%');
         },
         statusCode: {
              200: function() {
                   $('#edit_role_result').removeAttr('style');
                   $('#edit_role_result').html('User Role Updated Successfully')
                   $("#edit_role_result").addClass("alert alert-success");
                   $('#editRoleModal').modal('hide');
                   rolesListView();
              }
         },
         error: function (data) {

         },
         complete: function () {
           $(".loader").hide();
           $(".loader").css('margin-top','0%');
         }
     })
  }
  function deleteUserRole(id)
  {
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url:base_url + "/admin/user-management/delete-user-role",
        dataType:"json",
        beforeSend: function () {
          $(".loader").show();
          $(".loader").css('margin-top','12%');
        },
        method:"delete",
        data:{_token:csrf_token,id:id},
        statusCode: {
             200: function() {
                  $('#delete_user_role_result').removeAttr('style');
                  $('#delete_user_role_result').html('User role deleted Successfully')
                  $("#delete_user_role_result").addClass("alert alert-success");
                  $('#deleteUserRoleModal').modal('hide');
                  rolesListView();
             },
             400: function() {
               $('#delete_user_role_result').html("Something went wrong!!")
               $("#delete_user_role_result").addClass("alert alert-dander");
             }
        },
        complete: function () {
          $(".loader").hide();
          $(".loader").css('margin-top','0%');
        }
    })
  }
  /**
   * To show all customers list
   * @author <praveen>
   */
  function customersListView()
  {
      $('.main-sub-menu').removeClass('hidden');
      $( ".sub_modules a" ).removeClass( "active" )
       $('.sticker_orders a').addClass("active")
      $('.main-content').html('<div class="loader"></div>');
      var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/customer-management/customers-list",
          type: "Post",
          dataType:"json",
          data: {_token:csrf_token},
          beforeSend: function () {
              $(".loader").show();
              $(".loader").css('margin-top','12%');
          },
          success: function (data) {
            $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li class="active">Customers</li></ol>');
            var table = '<div class="col" id="customer-table"><table id="customers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Company Name</th><th>Factory Code</th><th>Default Contact</th><th>Courier Choice</th><th>Account Number</th><th>TIN Number</th><th>GSTIN Number</th><th>PAN Number</th><th>Billing Address</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
            $.each(data.customers, function(i, item){
             var index = i+1;
             table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.company_name+'</td><td>'+item.factory_code+'</td><td><dl><dt>Contact Person:</dt><dd>'+item.contact_person+'</dd><dt>Contact Email:</dt><dd>'+item.contact_email+'</dd><dt>Contact Phone:</dt><dd>'+item.phone_no+'</dd></dl></td><td>'+item.courier_choice+'</td><td>'+item.account_no+'</td><td>'+item.tin_number+'</td><td>'+item.gstin_number+'</td><td>'+item.pan_number+'</td><td><dl><dt>Address:</dt><dd>'+item.address+','+item.country+'</dd><dt>Email:</dt><dd>'+item.email+'</dd><dt>Phone:</dt><dd>'+item.telephone+'</dd><dt>Fax:</dt><dd>'+item.fax+'</dd></dl></td>';
               if(item.is_active === 1)
               {
                table += '<td><span class="badge badge-success">Active</span></td>';
               }
               else
               {
                table += '<td><span class="badge badge-danger">Inactive</span></td>';
               }
               if(item.is_active === 1)
               {
                table += '<td><a href="#"><i class="fa fa-times fa-lg fa-button" aria-hidden="true" title="Make Inactive" id="make_inactive"></i></a>';
               }
               else
               {
                table += '<td><a href="#"><i class="fa fa-check fa-lg fa-button" aria-hidden="true" title="Make Active" id="make_active"></i></a>';
               }
               table += '<a href="#"><i class="fa fa-pencil fa-lg edit-customer fa-button" title="Edit"></i></a><a href="#"><i class="fa fa-trash fa-lg fa-button" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteCustomerModal" ></i></a></td></tr>';
            });
            table += '</tbody></table></div>';
            $('.main-content').html(table);
            $('#customers-list-table').DataTable();
            $("#customers-list-table_filter input").addClass('form-control');
            $("#customers-list-table_filter input").attr('placeholder', 'Data Search');
            autopopulateCountry(0)
          },
          error: function (data) {

          },
          complete: function () {
              $(".loader").hide();
              $(".loader").css('margin-top','0%');
          }
      })
  }
  /**
   * Adds a customer.
   * @author <praveen>
   */
  function addCustomer()
  {
      var factory_code    =  $('#factory_code').val();
      var company_name    =  $('#company_name').val();
      var courier_choice  =  $('#courier_choice').val();
      var account_no      =  $('#account_no').val();
      var tin_number      =  $('#tin_number').val();
      var gstin_number    =  $('#gstin_number').val();
      var pan_number      =  $('#pan_number').val();
      var login_email     =  $('#login_email').val();
      var password        =  $('#password').val();
      var csrf_token      =  $('meta[name="csrf-token"]').attr('content');

      var contact_person  = [];
      var contact_phone   = [];
      var contact_email   = [];
      var default_contact = [];

      var address         = [];
      var country         = [];
      var telephone       = [];
      var fax             = [];
      var address_email   = [];
      var default_address = [];

      $('.contact_person').each(function(i, item)
      {
          contact_person[i]    = $(item).val();
          
      });
      $('.contact_phone').each(function(i, item)
      {
          contact_phone[i]    = $(item).val();
          
      });
      $('.contact_email').each(function(i, item)
      {
          contact_email[i]    = $(item).val();
          
      });
      $('.default_contact').each(function(i, item)
      {
          if ($(this).is(':checked'))
          {
            default_contact[i]    = 1;
          }
          else
          {
            default_contact[i]    = 0;
          }
          
      });
      $('.address').each(function(i, item)
      {
          address[i]    = $(item).val();
          
      });
      $('.country').each(function(i, item)
      {
          country[i]    = $(item).val();
          
      });
      $('.telephone').each(function(i, item)
      {
          telephone[i]    = $(item).val();
          
      });
      $('.fax').each(function(i, item)
      {
          fax[i]    = $(item).val();
          
      });
      $('.address_email').each(function(i, item)
      {
          address_email[i]    = $(item).val();
          
      });
      $('.default_address').each(function(i, item)
      {
        if ($(this).is(':checked'))
          {
            default_address[i]    = 1;
          }
          else
          {
            default_address[i]    = 0;
          }
          
      });
                
      contact_person   = contact_person.join(',');
      contact_phone    = contact_phone.join(',');
      contact_email    = contact_email.join(',');
      default_contact  = default_contact.join(',');
      address          = address.join('|');
      country          = country.join(',');
      telephone        = telephone.join(',');
      fax              = fax.join(',');
      address_email    = address_email.join(',');
      default_address  = default_address.join(',');

      $.ajax({
          url: base_url + "/admin/customer-management/add-customer",
          method: 'POST',
          data: {_token:csrf_token,factory_code:factory_code,company_name:company_name,contact_person:contact_person,contact_phone:contact_phone,contact_email:contact_email,account_no:account_no,courier_choice:courier_choice,tin_number:tin_number,gstin_number:gstin_number,pan_number:pan_number,login_email:login_email,p:password,address:address,country:country,telephone:telephone,fax:fax,address_email:address_email,default_contact:default_contact,default_address:default_address},
          beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
          },
          statusCode: {
               200: function() {
                    $('#customer_result').removeAttr('style');
                    $('#customer_result').html('Customer Added Successfully')
                    $("#customer_result").addClass("alert alert-success");
                    $('#addCustomerModal').modal('hide');
                    customersListView();
               },
               400: function() {
                    $('#customer_result').removeAttr('style');
                    $('#customer_result').html("Something went wrong!!")
                    $("#customer_result").addClass("alert alert-dander");
               },
               409: function() {
                    $('#customer_result').removeAttr('style');
                    $('#customer_result').html('Email already registered.')
                    $("#customer_result").addClass("alert alert-warning");
               }
          },
          error: function (data) {

          },
          complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top','0%');
          }
      })
   }
   /**
    * Function to show all buyers list
    * @author <praveen>
    */
   function buyers()
   {
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
           url: base_url + "/admin/buyer-management/buyers-list",
           type: "get",
           dataType:"json",
           data: {_token:csrf_token},
           beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
           },
           success: function (data) {
               $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/buyer-management">Dashboard</a></li><li class="active">Buyers</li></ol>');
               var table = '<div class="loader"></div><div class="col" id="buyers-table"><table id="buyers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Buyer Name</th><th>Buyer Email</th><th>Buyer Phone</th><th>Buyer Address</th><th>Actions</th></tr></thead><tbody>';
               $.each(data.buyers, function(i, item){
                var index = i+1;
                table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.buyer_name+'</td><td>'+item.buyer_email+'</td><td>'+item.buyer_phone+'</td><td>'+item.buyer_address+'</td><td><a href="#"><i class="fa fa-pencil fa-lg fa-button edit-buyer" title="Edit"></i></a><a href="#"><i class="fa fa-trash fa-lg fa-button" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteBuyerModal" ></i></a></td></tr>';
               });
               table += '</tbody></table></div>';
               $('.main-content').html(table);
                $('#buyers-list-table').DataTable();
                $("#buyers-list-table_filter input").addClass('form-control input-sm');
                $("#buyers-list-table_filter input").attr('placeholder', 'Data Search');
                 $('#buyers-list-table_length select').addClass('form-control input-sm');
           },
           error: function (data) {

           },
           complete: function () {
               $(".loader").hide();
               $(".loader").css('margin-top','0%');
           }
       })
   }
   /**
    * Adds a buyer.
    * @author <praveen>
    */
   function addBuyer()
   {
       var name       = $('#buyer_name').val();
       var email      = $('#buyer_email').val();
       var phone      = $('#buyer_phone').val();
       var address    = $('#buyer_address').val();
       var csrf_token = $('meta[name="csrf-token"]').attr('content');

       $.ajax({
           url: base_url + "/admin/buyer-management/add-jga-buyer",
           method: 'POST',
           data: {_token:csrf_token,name:name,email:email,phone:phone,address:address},
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           statusCode: {
                200: function() {
                     $('#buyer_result').removeAttr('style');
                     $('#buyer_result').html('Buyer Added Successfully')
                     $("#buyer_result").addClass("alert alert-success");
                     $('#addBuyerModal').modal('hide');
                     buyers();
                },
                400: function() {
                  $('#buyer_result').removeAttr('style');
                  $('#buyer_result').html("Something went wrong!!")
                  $("#buyer_result").addClass("alert alert-dander");
                },
                409: function() {
                     $('#buyer_result').removeAttr('style');
                     $('#buyer_result').html('Email already registered.')
                     $("#buyer_result").addClass("alert alert-warning");
                }
           },
           error: function (data) {

           },
           complete: function () {
             $(".loader").hide();
             $(".loader").css('margin-top','0%');
           }
       })
    }
    /**
     * Function for get buyers details for editing
     * @author <praveen>
     *
     */
   var isSubmitting = false;
   function getBuyer(id)
   {
     if(isSubmitting) {
          return;
      }
      isSubmitting = true;
     $.ajax({
         url:base_url + "/admin/buyer-management/get-buyer-details",
         dataType:"json",
         method:"get",
         data:{id:id},
         beforeSend: function () {
           $(".loader").show();
           $(".loader").css('margin-top','12%');
         },
         success: function(data){
             isSubmitting = false;
             $('#buyer_edit_id').val(id);
             $('#edit_buyer_name').val(data.buyer[0].buyer_name);
             $('#edit_buyer_email').val(data.buyer[0].buyer_email);
             $('#edit_buyer_phone').val(data.buyer[0].buyer_phone);
             $('#edit_buyer_address').val(data.buyer[0].buyer_address);
             $('#editBuyerModal').modal({
                  backdrop: 'static',
                  keyboard: false
             })
             $('#edit_buyer_form').validator('validate');
             $('#editBuyerModal').modal('show');
         },
         complete: function () {
           $(".loader").hide();
           $(".loader").css('margin-top','0%');
         }
     })
   }
   /**
    * Function for edit buyer
    * @author <praveen>
    */
   function editBuyer()
   {
     var id         = $('#buyer_edit_id').val();
     var name       = $('#edit_buyer_name').val();
     var phone      = $('#edit_buyer_phone').val();
     var email      = $('#edit_buyer_email').val();
     var address    = $('#edit_buyer_address').val();
     var csrf_token = $('meta[name="csrf-token"]').attr('content');
     $.ajax({
         url:base_url + "/admin/buyer-management/update-buyer-details",
         dataType:"json",
         beforeSend: function () {
           $(".loader").show();
           $(".loader").css('margin-top','12%');
         },
         method:"put",
         data:{_token:csrf_token,id:id,name:name,email:email,phone:phone,address:address},
         statusCode: {
              200: function() {
                   $('#edit_buyer_result').removeAttr('style');
                   $('#edit_buyer_result').html('Buyer Updated Successfully')
                   $("#edit_buyer_result").addClass("alert alert-success");
                   $('#editBuyerModal').modal('hide');
                   buyers();
              },
              400: function() {
                $('#edit_buyer_result').removeAttr('style');
                $('#edit_buyer_result').html("Something went wrong!!")
                $("#edit_buyer_result").addClass("alert alert-dander");
              },
              409: function() {
                   $('#edit_buyer_result').removeAttr('style');
                   $('#edit_buyer_result').html('Email already registered.')
                   $("#edit_buyer_result").addClass("alert alert-warning");
              }
         },
         complete: function () {
           $(".loader").hide();
           $(".loader").css('margin-top','0%');
         }
     })
   }
   /**
    * Function for delete buyer
    * @author <praveen>
    */
   function deleteBuyer(id)
   {
     var csrf_token = $('meta[name="csrf-token"]').attr('content');
     $.ajax({
         url:base_url + "/admin/buyer-management/delete-buyer",
         dataType:"json",
         beforeSend: function () {
           $(".loader").show();
           $(".loader").css('margin-top','12%');
         },
         method:"delete",
         data:{_token:csrf_token,id:id},
         statusCode: {
              200: function() {
                   $('#delete_buyer_result').removeAttr('style');
                   $('#delete_buyer_result').html('Buyer deleted Successfully')
                   $("#delete_buyer_result").addClass("alert alert-success");
                   $('#deleteBuyerModal').modal('hide');
                   buyers();
              },
              400: function() {
                $('#delete_buyer_result').removeAttr('style');
                $('#delete_buyer_result').html("Something went wrong!!")
                $("#delete_buyer_result").addClass("alert alert-dander");
              }
         },
         complete: function () {
           $(".loader").hide();
           $(".loader").css('margin-top','0%');
         }
     })
   }
   /**  Validation for mobile number **/
   function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
    {
        return false;
    }
      return true;
    }
    /**
     * Function for merchandiser page
     * @author <praveen>
     */
    function merchandiser()
    {
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
           url: base_url + "/admin/buyer-management/merchandiser-list",
           type: "get",
           dataType:"json",
           data: {_token:csrf_token},
           beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
               $.ajax({
                 url: base_url + "/admin/buyer-management/get-buyers",
                 method: 'get',
                 success: function (data) {
                   var appenddata = "<option value = ''>-- Select Buyers --</option>";
                     $.each(data.buyers, function (key, value) {

                     appenddata += "<option value = '"+value.id+"'>" +value.buyer_name+" </option>";
                     });
                 $('#merchandiser_buyer').html(appenddata);
                 var appenddata1 = "<option value = ''>-- Select Customers --</option>";
                   $.each(data.customers, function (key, value) {

                   appenddata1 += "<option value = '"+value.id+"'>" +value.company_name+" </option>";
                   });
               $('#merchandiser_supplier').html(appenddata1);
                 },
                 error: function (data) {

                 },
                 complete: function () {
                 }
             })
           },
           success: function (data) {
               $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li class="active">Merchandisers</li></ol>');
               var table = '<div class="loader"></div><div class="col" id="merchandisers-table"><table id="merchandisers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Merchandiser Name</th><th>Supplier Name</th><th>Buyer Name</th><th>Merchandiser Email</th><th>Merchandiser Phone</th><th>Merchandiser Address</th><th>Actions</th></tr></thead><tbody>';
               $.each(data.merchandisers, function(i, item){
                var index = i+1;
                table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.merchandiser_name+'</td><td>'+item.company_name+'</td><td>'+item.buyer_name+'</td><td>'+item.email+'</td><td>'+item.merchandiser_phone+'</td><td>'+item.merchandiser_address+'</td><td><a href="#"><i class="fa fa-pencil fa-lg fa-button edit-merchandiser" title="Edit"></i></a><a href="#"><i class="fa fa-trash fa-lg fa-button" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteMerchandiserModal" ></i></a></td></tr>';
               });
               table += '</tbody></table></div>';
               $('.main-content').html(table);
                $('#merchandisers-list-table').DataTable();
                $("#merchandisers-list-table_filter input").addClass('form-control input-sm');
                $("#merchandisers-list-table_filter input").attr('placeholder', 'Data Search');
                 $('#merchandisers-list-table_length select').addClass('form-control input-sm');
           },
           error: function (data) {

           },
           complete: function () {
               $(".loader").hide();
               $(".loader").css('margin-top','0%');
           }
       })
    }
    /**
     * Adds a merchandiser.
     * @author <praveen>
     */
    function addMerchandiser()
    {
        var name                   = $('#merchandiser_name').val();
        var email                  = $('#merchandiser_email').val();
        var password               = $('#merchandiser_password').val();
        var phone                  = $('#merchandiser_phone').val();
        var address                = $('#merchandiser_address').val();
        var merchandiser_buyer     = $('#merchandiser_buyer option:selected').val();
        var merchandiser_supplier  = $('#merchandiser_supplier option:selected').val();
        var csrf_token             = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: base_url + "/admin/buyer-management/add-merchandiser",
            method: 'POST',
            data: {_token:csrf_token,name:name,email:email,phone:phone,address:address,p:password,merchandiser_buyer:merchandiser_buyer,merchandiser_supplier:merchandiser_supplier},
            beforeSend: function () {
              $(".loader").show();
              $(".loader").css('margin-top','12%');
            },
            statusCode: {
                 200: function() {
                      $('#add_merchandiser_result').removeAttr('style');
                      $('#add_merchandiser_result').html('Merchandiser Added Successfully')
                      $("#add_merchandiser_result").addClass("alert alert-success");
                      $('#addMerchandiserModal').modal('hide');
                      merchandiser();
                 },
                 400: function() {
                   $('#add_merchandiser_result').removeAttr('style');
                   $('#add_merchandiser_result').html("Something went wrong!!")
                   $("#add_merchandiser_result").addClass("alert alert-dander");
                 },
                 409: function() {
                      $('#add_merchandiser_result').removeAttr('style');
                      $('#add_merchandiser_result').html('Email already registered.')
                      $("#add_merchandiser_result").addClass("alert alert-warning");
                 }
            },
            error: function (data) {

            },
            complete: function () {
              $(".loader").hide();
              $(".loader").css('margin-top','0%');
            }
        })
     }
     /**
      * To get all merchandiser list
      * @author <praveen>
      */
     var isSubmitting = false;
     function getMerchandiser(id)
     {
       if(isSubmitting) {
            return;
        }
        isSubmitting = true;
       $.ajax({
           url:base_url + "/admin/buyer-management/get-merchandiser-details",
           dataType:"json",
           method:"get",
           data:{id:id},
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
             $.ajax({
                 url: base_url + "/admin/buyer-management/get-buyers",
                 method: 'get',
                 success: function (data) {
                   var appenddata = "<option value = ''>-- Select Buyers --</option>";
                     $.each(data.buyers, function (key, value) {

                     appenddata += "<option value = '"+value.id+"'>" +value.buyer_name+" </option>";
                     });
                 $('#edit_merchandiser_buyer').html(appenddata);
                 var appenddata1 = "<option value = ''>-- Select Customers --</option>";
                   $.each(data.customers, function (key, value) {

                   appenddata1 += "<option value = '"+value.id+"'>" +value.company_name+" </option>";
                   });
               $('#edit_merchandiser_supplier').html(appenddata1);
                 },
                 error: function (data) {

                 },
                 complete: function () {
                 }
             })
           },
           success: function(data){
               isSubmitting = false;
               $('#merchandiser_edit_id').val(id);
               $('#edit_merchandiser_name').val(data.merchandiser[0].merchandiser_name);
               $('#edit_merchandiser_email').val(data.merchandiser[0].email);
               $('#edit_merchandiser_phone').val(data.merchandiser[0].merchandiser_phone);
               $('#edit_merchandiser_address').val(data.merchandiser[0].merchandiser_address);
               $('#edit_merchandiser_supplier').val(data.merchandiser[0].customer_id);
               $('#edit_merchandiser_buyer').val(data.merchandiser[0].buyer_id);
               $('#editMerchandiserModal').modal({
                    backdrop: 'static',
                    keyboard: false
               })
               $('#edit_merchandiser_form').validator('validate');
               $('#editMerchandiserModal').modal('show');
           },
           complete: function () {
             $(".loader").hide();
             $(".loader").css('margin-top','0%');
           }
       })
     }
     /**
      * Function to update merchandiser details
      * @author <praveen>
      */
     function editMerchandiser()
     {
       var id         = $('#merchandiser_edit_id').val();
       var name       = $('#edit_merchandiser_name').val();
       var phone      = $('#edit_merchandiser_phone').val();
       var email      = $('#edit_merchandiser_email').val();
       var address    = $('#edit_merchandiser_address').val();
       var supplier   = $('#edit_merchandiser_supplier').val();
       var buyer      = $('#edit_merchandiser_buyer').val();

       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/buyer-management/update-merchandiser-details",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data:{_token:csrf_token,id:id,name:name,email:email,phone:phone,address:address,supplier:supplier,buyer:buyer},
           statusCode: {
                200: function() {
                     $('#edit_merchandiser_result').removeAttr('style');
                     $('#edit_merchandiser_result').html('Merchandiser Updated Successfully')
                     $("#edit_merchandiser_result").addClass("alert alert-success");
                     $('#editMerchandiserModal').modal('hide');
                     merchandiser();
                },
                400: function() {
                  $('#edit_merchandiser_result').removeAttr('style');
                  $('#edit_merchandiser_result').html("Something went wrong!!")
                  $("#edit_merchandiser_result").addClass("alert alert-dander");
                },
                409: function() {
                     $('#edit_merchandiser_result').removeAttr('style');
                     $('#edit_merchandiser_result').html('Email already registered.')
                     $("#edit_merchandiser_result").addClass("alert alert-warning");
                }
           },
           complete: function () {
             $(".loader").hide();
             $(".loader").css('margin-top','0%');
           }
       })
     }
     /**
      * To delete merchandiser
      * @author <praveen>
      *
      */
     function deleteMerchandiser(id)
     {
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/buyer-management/delete-merchandiser",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"delete",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     $('#delete_merchandiser_result').removeAttr('style');
                     $('#delete_merchandiser_result').html('Merchandiser deleted Successfully')
                     $("#delete_merchandiser_result").addClass("alert alert-success");
                     $('#deleteMerchandiserModal').modal('hide');
                     merchandiser();
                },
                400: function() {
                  $('#delete_merchandiser_result').removeAttr('style');
                  $('#delete_merchandiser_result').html("Something went wrong!!")
                  $("#delete_merchandiser_result").addClass("alert alert-dander");
                }
           },
           complete: function () {
             $(".loader").hide();
             $(".loader").css('margin-top','0%');
           }
       })
     }
     /** Dynamically add/remove contact person & Billing Address in Customer Management jquery begins**/
     /**
      * Adds a contact person.
      * @author <praveen>
      */
     function addContact(rem,e) {
       e.preventDefault();
         var a = []
         var id = $(rem).attr("data-id");
         var val = parseInt(id) + 1;
         $('#addcontact').append('<div id="contact_' + val + '" class="addcontact"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="removeContact(this)" title="Delete"></i><div type="button" class="accordion button-bottom"><h4>Contact Person 1</h4></div><div id="contact' + val + '" class="panel"><div class="form-group has-feedback"><label for="contact_person' + val + '" class="form_label">Contact Person </label><input class="form-control input-sm contact_person" placeholder="Contact Person Name" required="" name="contact_person' + val + '" value="" id="contact_person' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="contact_phone' + val + '" class="form_label">Contact Phone No</label><div class="input-group"><span class="input-group-addon input-sm " id="basic-addon1">+91</span><input class="form-control input-sm contact_phone" placeholder="Contact Phone No" data-minlength="10" maxlength="10" required="" onkeypress="return isNumber(event)" name="contact_phone' + val + '" value="" id="contact_phone' + val + '" type="text"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="contact_email' + val + '" class="form_label">Contact Email</label><div class="input-group"><span class="input-group-addon input-sm" id="basic-addon1">@</span><input class="form-control input-sm contact_email" placeholder="Contact Email" required="" name="contact_email' + val + '" value="" id="contact_email' + val + '" type="email"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input default_contact" type="checkbox" value="" name="default" id="default_contact' + val + '"> Make as default contact person</label></div></div></div>')
         $('#add_customer_form').validator('update');
         
         $(rem).attr("data-id", val);
         var j = 1;
         $('#addcontact .addcontact').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Contact Person " + j +"</h4>");
             a.push($(this).attr('id'));
             j++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $(':checkbox').on('change',function(){
           var th = $(this), name = th.prop('name');
           if(th.is(':checked')){
               $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
            }
      });
     }
     /**
      * Removes a  contact person.
      *
      * @author <praveen>
      */
     function removeContact(rem) {
         var a = []
         var al = $(rem).parent().attr('id');
         $(rem).parent().remove();
         var i = 1;
         $('#addcontact .addcontact').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Contact Person " + i +"</h4>");
             a.push($(this).attr('id'));
             i++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $('#add_customer_form').validator('update');
     }
     /**
      * Adds an contact address.
      * @author <praveen>
      */
     function addAddress(rem,e) {
       e.preventDefault();
         var a = []
         var id = $(rem).attr("data-id");
         var val = parseInt(id) + 1;
         $('#addbilladdress').append('<div id="billaddress_' + val + '" class="addbilladdress"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="removeAddress(this)" title="Delete"></i><div type="button" class="accordion button-bottom active"><h4>Billing Address 1</h4></div><div id="billaddress' + val + '" class="panel" style="display: block;"><div class="form-group has-feedback"><label for="address' + val + '" class="form_label">Address</label><textarea class="form-control  address" required="" placeholder="Address" style="resize:none;" rows="3" name="address' + val + '" cols="50" id="address' + val + '"></textarea><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="country' + val + '" class="form_label">Country</label><input class="form-control input-sm country" placeholder="Country" required="" name="country' + val + '" value="" id="country' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="telephone' + val + '" class="form_label">Phone</label><div class="input-group"><span class="input-group-addon input-sm " id="basic-addon1">+91</span><input class="form-control input-sm telephone" placeholder="Phone" required="" data-minlength="10" maxlength="10" onkeypress="return isNumber(event)" name="telephone' + val + '" value="" id="telephone' + val + '" type="text"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="fax' + val + '" class="form_label">Fax</label><input class="form-control input-sm fax" placeholder="Fax" required="" onkeypress="return isNumber(event)" name="fax' + val + '" value="" id="fax' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="address_email' + val + '" class="form_label">Email</label><div class="input-group"><span class="input-group-addon input-sm" id="basic-addon1">@</span><input class="form-control input-sm address_email" placeholder="Email" required="" name="address_email' + val + '" value="" id="address_email' + val + '" type="email"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input default_address" type="checkbox" value="" name="default1" id="default_address' + val + '"> Make as default billing address</label></div></div></div>')
         $('#add_customer_form').validator('update');
         autopopulateCountry(val);
         
         $(rem).attr("data-id", val);
         var j = 1;
         $('#addbilladdress .addbilladdress').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Billing Address " + j +"</h4>");
             a.push($(this).attr('id'));
             j++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $(':checkbox').on('change',function(){
           var th = $(this), name = th.prop('name');
           if(th.is(':checked')){
               $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
            }
      });
     }
     /**
      * Removes an contact address.
      *
      * @author <praveen>
      */
     function removeAddress(rem) {
         var a = []
         var al = $(rem).parent().attr('id');
         $(rem).parent().remove();
         var i = 1;
         $('#addbilladdress .addbilladdress').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Billing Address " + i +"</h4>");
             a.push($(this).attr('id'));
             i++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $('#add_customer_form').validator('update');
     }
 /** Dynamically add/remove contact person & Billing Address in Customer Management jquery ends**/
 /**
  * To auto populate country
  *
  * @author <praveen>
  */
    function autopopulateCountry(id)
     {
            var options = {
            url: base_url + "/countries.json",

            getValue: "name",
            adjustWidth: false,
            template: {
              type: "description",
              fields: {
                description: "code"
              }
            },
            list: {
                showAnimation: {
                  type: "slide", //normal|slide|fade
                  time: 400,
                  callback: function() {}
                },

                hideAnimation: {
                  type: "slide", //normal|slide|fade
                  time: 400,
                  callback: function() {}
                },
                maxNumberOfElements: 10,
                 match: {
                  enabled: true
                },

                onClickEvent: function() {
                  var value = $("#country" + id).getSelectedItemData().code;
                  $("#country" + id).val(value).trigger("change");
                }
            }
          };
       $("#country" + id).easyAutocomplete(options);
     }
    /**
     * Edit auto-autopopulate country(In customer form)
     *
     * @author <praveen>
     */
     function editAutopopulateCountry(id)
     {
          var options = {
          url: base_url + "/countries.json",

          getValue: "name",
          adjustWidth: false,
          template: {
            type: "description",
            fields: {
              description: "code"
            }
          },
           
            list: {
                showAnimation: {
                  type: "slide", //normal|slide|fade
                  time: 400,
                  callback: function() {}
                },

                hideAnimation: {
                  type: "slide", //normal|slide|fade
                  time: 400,
                  callback: function() {}
                },
                maxNumberOfElements: 10,
                 match: {
                  enabled: true
                },

                onClickEvent: function() {
                  var value = $("#editcountry" + id).getSelectedItemData().code;
                  $("#editcountry" + id).val(value).trigger("change");
                }
           }
          };
       $("#editcountry" + id).easyAutocomplete(options);
     }
     /**
      * Makes a customer active.
      *
      * @author <praveen>
      */
    function makeCustomerActive(id,csrf_token)
     {
      $.ajax({
           url:base_url + "/admin/customer-management/make-customer-active",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     customersListView();
                }
           },
           complete: function () {
             $(".loader").hide();
             $(".loader").css('margin-top','0%');
           }
       })
     }
     /**
      * Makes a customer inactive.
      *
      * @author <praveen>
      */
    function makeCustomerInactive(id,csrf_token)
     {
      $.ajax({
           url:base_url + "/admin/customer-management/make-customer-inactive",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     customersListView();
                }
           },
           complete: function () {
             $(".loader").hide();
             $(".loader").css('margin-top','0%');
           }
       })
     }

     var isSubmitting = false;
     /**
      * Gets the customer.
      *
      * @author <praveen>
      */
     function getCustomer(id)
     {
       if(isSubmitting) 
        {
            return;
        }
        isSubmitting = true;
        var a = []
        $('#editContact').html('');
        $('#editbilladdress').html('');

       $.ajax({
           url:base_url + "/admin/customer-management/get-customers",
           dataType:"json",
           method:"get",
           data:{id:id},
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           success: function(data){
               isSubmitting = false;
               $('#customer_edit_id').val(id);
               $.each(data.customers, function (key, value) {
                $('#edit_factory_code').val(value.factory_code);
                $('#edit_company_name').val(value.company_name);
                $('#edit_courier_choice').val(value.courier_choice);
                $('#edit_account_no').val(value.account_no);
                $('#edit_tin_number').val(value.tin_number);
                $('#edit_gstin_number').val(value.gstin_number);
                $('#edit_pan_number').val(value.pan_number);
                $('#edit_login_email').val(value.email);
               });

               $.each(data.address,function(key,value){
                 key += 1;
                  var address_html = '<div edit="editbilladdress_' + key + '" class="editbilladdress">';
                  if(key != 1)
                  {
                    address_html += '<i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="editRemoveAddress(this)" title="Delete"></i>';
                  }
                  address_html += '<div type="button" class="accordion button-bottom active"><h4>Billing Address 1</h4></div><div id="editbilladdress' + key + '" class="panel" style="display: block;"><div class="form-group has-feedback"><label for="edit_address' + key + '" class="form_label">Address</label><textarea class="form-control edit_address" required="" placeholder="Address" style="resize:none;" rows="3" name="edit_address' + key + '" cols="50" id="edit_address' + key + '">'+value.address+'</textarea><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_country' + key + '" class="form_label">Country</label><input class="form-control input-sm edit_country" placeholder="Country" required="" name="edit_country' + key + '" value="'+value.country+'" id="edit_country' + key + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_telephone' + key + '" class="form_label">Phone</label><div class="input-group"><span class="input-group-addon input-sm " id="editbasic-addon1">+91</span><input class="form-control input-sm edit_telephone" placeholder="Phone" required="" data-minlength="10" maxlength="10" onkeypress="return isNumber(event)" name="edit_telephone' + key + '" value="'+value.telephone+'" id="edit_telephone' + key + '" type="text"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_fax' + key + '" class="form_label">Fax</label><input class="form-control input-sm edit_fax" placeholder="Fax" required="" onkeypress="return isNumber(event)" name="edit_fax' + key + '" value="'+value.fax+'" id="edit_fax' + key + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_address_email' + key + '" class="form_label">Email</label><div class="input-group"><span class="input-group-addon input-sm" id="edit-basic-addon1">@</span><input class="form-control input-sm edit_address_email" placeholder="Email" required="" name="edit_address_email' + key + '" value="'+value.email+'" id="edit_address_email' + key + '" type="email"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input edit_default_address" type="checkbox" value="" name="default1" id="edit_default_address' + key + '"> Make as default billing address</label></div></div></div>';
                    
                    $('#editbilladdress').append(address_html);

                    editAutopopulateCountry(key);
                    if(value.default_address === 1)
                    {
                      $('#edit_default_address'+ key).prop('checked', true);
                    }
                    else
                    {
                      $('#edit_default_address'+ key).prop('checked', false);
                    }

                     var j = 1;
                     $('#editbilladdress .editbilladdress').each(function(key, val) {
                         $(this).find(".button-bottom").html("<h4>Billing Address " + j +"</h4>");
                         a.push($(this).attr('id'));
                         j++;
                     })
                     var b = []
                     var b = a.join(',');
                     $('#hidden').val(b);
                     $(':checkbox').on('change',function(){
                       var th = $(this), name = th.prop('name');
                       if(th.is(':checked')){
                           $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
                        }
                  });
                $('#edit_address').attr("data-id", key);
               });

               $.each(data.contacts,function(key,value){
                 key += 1;
                  var contact_html = '<div id="edit_contact_' + key + '" class="editcontact">';
                  if(key != 1)
                  {
                    contact_html += '<i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="editRemoveContact(this)" title="Delete"></i>';
                  }
                  contact_html += '<div type="button" class="accordion button-bottom"><h4>Contact Person 1</h4></div><div id="edit_contact' + key + '" class="panel"><div class="form-group has-feedback"><label for="edit_contact_person' + key + '" class="form_label">Contact Person </label><input class="form-control input-sm edit_contact_person" placeholder="Contact Person Name" required="" name="edit_contact_person' + key + '" value="' + value.contact_person_name + '" id="edit_contact_person' + key + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_contact_phone' + key + '" class="form_label">Contact Phone No</label><div class="input-group"><span class="input-group-addon input-sm " id="edit-basic-addon1">+91</span><input class="form-control input-sm edit_contact_phone" placeholder="Contact Phone No" data-minlength="10" maxlength="10" required="" onkeypress="return isNumber(event)" name="edit_contact_phone' + key + '" value="' + value.contact_person_phone + '" id="edit_contact_phone' + key + '" type="text"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_contact_email' + key + '" class="form_label">Contact Email</label><div class="input-group"><span class="input-group-addon input-sm" id="edit-basic-addon1">@</span><input class="form-control input-sm edit_contact_email" placeholder="Contact Email" required="" name="edit_contact_email' + key + '" value="' + value.contact_person_email + '" id="edit_contact_email' + key + '" type="email"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input edit_default_contact" type="checkbox" value="" name="default" id="edit_default_contact' + key + '"> Make as default contact person</label></div></div></div>';
                    
                    $('#editContact').append(contact_html);

                    editAutopopulateCountry(key);
                    if(value.default_contact_person === 1)
                    {
                      $('#edit_default_contact'+ key).prop('checked', true);
                    }
                    else
                    {
                      $('#edit_default_contact'+ key).prop('checked', false);
                    }

                     var j = 1;
                     $('#editContact .editcontact').each(function(key, val) {
                         $(this).find(".button-bottom").html("<h4>Contact Person " + j +"</h4>");
                         a.push($(this).attr('id'));
                         j++;
                     })
                     var b = []
                     var b = a.join(',');
                     $('#hidden').val(b);
                     $(':checkbox').on('change',function(){
                       var th = $(this), name = th.prop('name');
                       if(th.is(':checked')){
                           $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
                        }
                  });
                $('#edit_contact').attr("data-id", key);
               });
               $('#editCustomerModal').modal({
                    backdrop: 'static',
                    keyboard: false
               })
               $('#edit_customer_form').validator('update');
               $('#editCustomerModal').modal('show');
           },
           complete: function () {
            $('#edit_customer_form').validator('validate');
             $(".loader").hide();
             $(".loader").css('margin-top','0%');
           }
       })
     }
     /** Dynamically add/remove contact person & Billing Address in Customer Management jquery begins(for editing)**/
      /**
       * To edit contact person
       *
       * @author <praveen>
       */
     function editAddContact(rem,e) {
       e.preventDefault();
         var a = []
         var id = $(rem).attr("data-id");
         var val = parseInt(id) + 1;
         $('#editContact').append('<div id="editcontact_' + val + '" class="editcontact"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="editRemoveContact(this)" title="Delete"></i><div type="button" class="accordion button-bottom"><h4>Contact Person 1</h4></div><div id="edit_contact_person' + val + '" class="panel"><div class="form-group has-feedback"><label for="edit_contact_person' + val + '" class="form_label">Contact Person </label><input class="form-control input-sm edit_contact_person" placeholder="Contact Person Name" required="" name="edit_contact_person' + val + '" value="" id="edit_contact_person' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_contact_phone' + val + '" class="form_label">Contact Phone No</label><div class="input-group"><span class="input-group-addon input-sm " id="edit-basic-addon1">+91</span><input class="form-control input-sm edit_contact_phone" placeholder="Contact Phone No" data-minlength="10" maxlength="10" required="" onkeypress="return isNumber(event)" name="edit_contact_phone' + val + '" value="" id="edit_contact_phone' + val + '" type="text"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_contact_email' + val + '" class="form_label">Contact Email</label><div class="input-group"><span class="input-group-addon input-sm" id="edit-basic-addon1">@</span><input class="form-control input-sm edit_contact_email" placeholder="Contact Email" required="" name="edit_contact_email' + val + '" value="" id="edit_contact_email' + val + '" type="email"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input edit_default_contact" type="checkbox" value="" name="default" id="edit_default_contact' + val + '"> Make as default contact person</label></div></div></div>')
         $('#edit_customer_form').validator('update');
         
         $(rem).attr("data-id", val);
         var j = 1;
         $('#editContact .editcontact').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Contact Person " + j +"</h4>");
             a.push($(this).attr('id'));
             j++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $(':checkbox').on('change',function(){
           var th = $(this), name = th.prop('name');
           if(th.is(':checked')){
               $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
            }
      });
     }
     /**
      * To remove contact person while editing
      *
      * @author <praveen>
      */
     function editRemoveContact(rem) {
         var a = []
         var al = $(rem).parent().attr('id');
         $(rem).parent().remove();
         var i = 1;
         $('#editContact .editcontact').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Contact Person " + i +"</h4>");
             a.push($(this).attr('id'));
             i++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $('#edit_customer_form').validator('update');
     }
     /**
      * To add contact address while editing
      *
      * @author <praveen>
      */
     function editAddAddress(rem,e) {
       e.preventDefault();
         var a = []
         var id = $(rem).attr("data-id");
         var val = parseInt(id) + 1;
         $('#editbilladdress').append('<div id="editbilladdress_' + val + '" class="editbilladdress"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="editRemoveAddress(this)" title="Delete"></i><div type="button" class="accordion button-bottom active"><h4>Billing Address 1</h4></div><div id="editbilladdress' + val + '" class="panel" style="display: block;"><div class="form-group has-feedback"><label for="address' + val + '" class="form_label">Address</label><textarea class="form-control  address" required="" placeholder="Address" style="resize:none;" rows="3" name="editaddress' + val + '" cols="50" id="editaddress' + val + '"></textarea><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="editcountry' + val + '" class="form_label">Country</label><input class="form-control input-sm country" placeholder="Country" required="" name="editcountry' + val + '" value="" id="editcountry' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edittelephone' + val + '" class="form_label">Phone</label><div class="input-group"><span class="input-group-addon input-sm " id="basic-addon1">+91</span><input class="form-control input-sm telephone" placeholder="Phone" required="" data-minlength="10" maxlength="10" onkeypress="return isNumber(event)" name="edittelephone' + val + '" value="" id="edittelephone' + val + '" type="text"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="editfax' + val + '" class="form_label">Fax</label><input class="form-control input-sm fax" placeholder="Fax" required="" onkeypress="return isNumber(event)" name="editfax' + val + '" value="" id="editfax' + val + '" type="text"><span class="glyphicon form-control-feedback"></span><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_address_email' + val + '" class="form_label">Email</label><div class="input-group"><span class="input-group-addon input-sm" id="editbasic-addon1">@</span><input class="form-control input-sm address_email" placeholder="Email" required="" name="address_email' + val + '" value="" id="edit_address_email' + val + '" type="email"><span class="glyphicon form-control-feedback"></span></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input default_address" type="checkbox" value="" name="default1" id="edit_default_address' + val + '"> Make as default billing address</label></div></div></div>')
         $('#edit_customer_form').validator('update');
         editAutopopulateCountry(val);
         
         $(rem).attr("data-id", val);
         var j = 1;
         $('#editbilladdress .editbilladdress').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Billing Address " + j +"</h4>");
             a.push($(this).attr('id'));
             j++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $(':checkbox').on('change',function(){
           var th = $(this), name = th.prop('name');
           if(th.is(':checked')){
               $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
            }
      });
     }
     /**
      * To remove contact address while editing
      *
      * @author <praveen>
      */
     function editRemoveAddress(rem) {
         var a = []
         var al = $(rem).parent().attr('id');
         $(rem).parent().remove();
         var i = 1;
         $('#editbilladdress .editbilladdress').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Billing Address " + i +"</h4>");
             a.push($(this).attr('id'));
             i++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $('#edit_customer_form').validator('update');
     }
    /** Dynamically add/remove contact person & Billing Address in Customer Management jquery ends(for editing)**/
    /**
      * To update customer details
      * @author <praveen>
      */
    function editCustomer()
  {
      var id              =  $('#customer_edit_id').val();
      var factory_code    =  $('#edit_factory_code').val();
      var company_name    =  $('#edit_company_name').val();
      var courier_choice  =  $('#edit_courier_choice').val();
      var account_no      =  $('#edit_account_no').val();
      var tin_number      =  $('#edit_tin_number').val();
      var gstin_number    =  $('#edit_gstin_number').val();
      var pan_number      =  $('#edit_pan_number').val();
      var login_email     =  $('#edit_login_email').val();
      var csrf_token      =  $('meta[name="csrf-token"]').attr('content');

      var contact_person  = [];
      var contact_phone   = [];
      var contact_email   = [];
      var default_contact = [];

      var address         = [];
      var country         = [];
      var telephone       = [];
      var fax             = [];
      var address_email   = [];
      var default_address = [];

      $('.edit_contact_person').each(function(i, item)
      {
          contact_person[i]    = $(item).val();
          
      });
      $('.edit_contact_phone').each(function(i, item)
      {
          contact_phone[i]    = $(item).val();
          
      });
      $('.edit_contact_email').each(function(i, item)
      {
          contact_email[i]    = $(item).val();
          
      });
      $('.edit_default_contact').each(function(i, item)
      {
          if ($(this).is(':checked'))
          {
            default_contact[i]    = 1;
          }
          else
          {
            default_contact[i]    = 0;
          }
          
      });
      $('.edit_address').each(function(i, item)
      {
          address[i]    = $(item).val();
          
      });
      $('.edit_country').each(function(i, item)
      {
          country[i]    = $(item).val();
          
      });
      $('.edit_telephone').each(function(i, item)
      {
          telephone[i]    = $(item).val();
          
      });
      $('.edit_fax').each(function(i, item)
      {
          fax[i]    = $(item).val();
          
      });
      $('.edit_address_email').each(function(i, item)
      {
          address_email[i]    = $(item).val();
          
      });
      $('.edit_default_address').each(function(i, item)
      {
        if ($(this).is(':checked'))
          {
            default_address[i]    = 1;
          }
          else
          {
            default_address[i]    = 0;
          }
          
      });
                
      contact_person   = contact_person.join(',');
      contact_phone    = contact_phone.join(',');
      contact_email    = contact_email.join(',');
      default_contact  = default_contact.join(',');
      address          = address.join('|');
      country          = country.join(',');
      telephone        = telephone.join(',');
      fax              = fax.join(',');
      address_email    = address_email.join(',');
      default_address  = default_address.join(',');

      $.ajax({
          url: base_url + "/admin/customer-management/update-customer",
          method: 'PUT',
          data: {_token:csrf_token,factory_code:factory_code,company_name:company_name,contact_person:contact_person,contact_phone:contact_phone,contact_email:contact_email,account_no:account_no,courier_choice:courier_choice,tin_number:tin_number,gstin_number:gstin_number,pan_number:pan_number,login_email:login_email,address:address,country:country,telephone:telephone,fax:fax,address_email:address_email,default_contact:default_contact,default_address:default_address,id:id},
          beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top','12%');
          },
          statusCode: {
               200: function() {
                    $('#customer_upadte_result').removeAttr('style');
                    $('#customer_upadte_result').html('Customer Updated Successfully')
                    $("#customer_upadte_result").addClass("alert alert-success");
                    $('#editCustomerModal').modal('hide');
                    customersListView();
               },
               400: function() {
                    $('#customer_upadte_result').removeAttr('style');
                    $('#customer_upadte_result').html("Something went wrong!!")
                    $("#customer_upadte_result").addClass("alert alert-dander");
               },
               409: function() {
                    $('#customer_upadte_result').removeAttr('style');
                    $('#customer_upadte_result').html('Email already registered.')
                    $("#customer_upadte_result").addClass("alert alert-warning");
               }
          },
          error: function (data) {

          },
          complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top','0%');
          }
      })
   }
    /**
     * To delete customer
     *
     * @author    <praveen>
     */
   function deleteCustomer(id)
   {
     var csrf_token = $('meta[name="csrf-token"]').attr('content');
     $.ajax({
         url:base_url + "/admin/customer-management/delete-customer",
         dataType:"json",
         beforeSend: function () {
           $(".loader").show();
           $(".loader").css('margin-top','12%');
         },
         method:"delete",
         data:{_token:csrf_token,id:id},
         statusCode: {
              200: function() {
                   $('#delete_result').removeAttr('style');
                   $('#delete_result').html('Customer deleted Successfully')
                   $("#delete_result").addClass("alert alert-success");
                   $('#deleteCustomerModal').modal('hide');
                   customersListView();
              },
              400: function() {
                $('#delete_result').removeAttr('style');
                $('#delete_result').html("Something went wrong!!")
                $("#delete_result").addClass("alert alert-dander");
              }
         },
         complete: function () {
           $(".loader").hide();
           $(".loader").css('margin-top','0%');
         }
     })
   }
