/*User Management script*/
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