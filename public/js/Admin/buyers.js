/* Buyer & Merchandisers script */

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