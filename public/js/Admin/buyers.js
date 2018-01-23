/* Buyer & Merchandisers script 
 * @author <praveen>
 */

 /**
  * Function to show all buyers list
  * @author <praveen>
  */
 var isSubmitting = false;
 function buyers()
 {
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    try {
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
             var table = '<div class="loader"></div><div class="col" id="buyers-table"><table id="buyers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Buyer Name</th><th>Buyer Email</th><th>Buyer Phone</th><th>Buyer Address</th><th>Actions</th></tr></thead><tbody>';
             $.each(data.buyers, function(i, item){
              var index = i+1;
              table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.buyer_name+'</td><td>'+item.buyer_email+'</td><td>'+item.buyer_phone+'</td><td>'+item.buyer_address+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-buyer filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-buyer" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteBuyerModal" data-placement="left"></i></a></td></tr>';
             });
             table += '</tbody></table></div>';
              $('.main-content').html(table);
              $('.edit-buyer').tooltip('show')
              $('.edit-buyer').tooltip('toggle')
              $('.delete-buyer').tooltip('show')
              $('.delete-buyer').tooltip('toggle')
              $('#buyers-list-table').DataTable();
              $("#buyers-list-table_filter input").addClass('form-control input-sm');
              $("#buyers-list-table_filter input").attr('placeholder', 'Data Search');
              $('#buyers-list-table_length select').addClass('form-control input-sm');
         },
         error: function (data) {
            console.log(data);
         },
         complete: function () {
              $(".loader").hide();
              $(".loader").css('margin-top','0%');
         }
     })
   } catch (e) {
     console.log("Error:"+e)
   }
 }
 /**
  * Adds a buyer.
  * @author <praveen>
  */
 function addBuyer()
 {
    try{
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
                       $('.snackbar_span').html("Buyer Added Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#addBuyerModal').modal('hide');
                       buyers();
                  },
                  400: function() {
                       $('.snackbar_span').html("Something went wrong!!");
                       $('#snackbar').css('background-color','#F44336');
                       showSnackbar();
                  },
                  409: function() {
                       $('#buyer_result').removeAttr('style');
                       $('#buyer_result').html('Email already registered.')
                       $("#buyer_result").addClass("alert alert-warning");
                  }
             },
             error: function (data) {
                console.log(data);
             },
             complete: function () {
                       $(".loader").hide();
                       $(".loader").css('margin-top','0%');
             }
         })
     } catch (e) {
     console.log("Error:"+e)
   }
  }
  /**
   * Function for get buyers details for editing
   * @param id
   * @author <praveen>
   *
   */
  
  function getBuyer(id)
  {
    try{
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
             error: function (data) {
                  console.log(data);
             },
             complete: function () {
                 $(".loader").hide();
                 $(".loader").css('margin-top','0%');
             }
         })
   } catch (e) {
     console.log("Error:"+e)
   }
  }
 /**
  * Function for edit buyer
  * @author <praveen>
  */
 function editBuyer()
 {
  try{
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
                     $('.snackbar_span').html('Buyer Updated Successfully');
                     $('#snackbar').css('background-color','#1abb9c');
                     showSnackbar();
                     $('#editBuyerModal').modal('hide');
                     buyers();
                },
                409: function() {
                     $('#edit_buyer_result').removeAttr('style');
                     $('#edit_buyer_result').html('Email already registered.')
                     $("#edit_buyer_result").addClass("alert alert-warning");
                }
           },
           error: function (data) {
                console.log(data);
           },
           complete: function () {
                     $(".loader").hide();
                     $(".loader").css('margin-top','0%');
           }
       })
   } catch (e) {
     console.log("Error:"+e)
   }
 }
 /**
  * Function for delete buyer
  * @param id
  * @author <praveen>
  */
 function deleteBuyer(id)
 {
  try{
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
                     $('.snackbar_span').html('Buyer deleted Successfully');
                     $('#snackbar').css('background-color','#1abb9c');
                     showSnackbar();
                     $('#deleteBuyerModal').modal('hide');
                     buyers();
                },
                400: function() {
                     $('.snackbar_span').html("Something went wrong!!");
                     $('#snackbar').css('background-color','#F44336');
                     showSnackbar();
                }
           },
           error: function (data) {
                console.log(data);
           },
           complete: function () {
                    $(".loader").hide();
                    $(".loader").css('margin-top','0%');
           }
       })
   } catch (e) {
     console.log("Error:"+e)
   }
 }

 /**
   * Function for merchandiser page
   * @author <praveen>
   */
  function merchandiser()
  {
    try{
          var csrf_token = $('meta[name="csrf-token"]').attr('content');
          $.ajax({
               url: base_url + "/admin/buyer-management/merchandiser-list",
               type: "get",
               dataType:"json",
               data: {_token:csrf_token},
               beforeSend: function () {
                   $(".loader").show();
                   $(".loader").css('margin-top','12%');
               },
               success: function (data) {
                   var table = '<div class="loader"></div><div class="col" id="merchandisers-table"><table id="merchandisers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Merchandiser Name</th><th>Merchandiser Email</th><th>Merchandiser Phone</th><th>Merchandiser Address</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.merchandisers, function(i, item){
                    var index = i+1;
                    table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.merchandiser_name+'</td><td>'+item.email+'</td><td>'+item.merchandiser_phone+'</td><td>'+item.merchandiser_address+'</td><td><a href="#"><i class="fa fa-pencil filterbtn options fa-button edit-merchandiser" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash filterbtn options fa-button delete-merchandiser" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteMerchandiserModal" data-placement="left"></i></a></td></tr>';
                   });
                   table += '</tbody></table></div>';
                    $('.main-content').html(table);
                    $('.edit-merchandiser').tooltip('show')
                    $('.edit-merchandiser').tooltip('toggle')
                    $('.delete-merchandiser').tooltip('show')
                    $('.delete-merchandiser').tooltip('toggle')
                    $('#merchandisers-list-table').DataTable();
                    $("#merchandisers-list-table_filter input").addClass('form-control input-sm');
                    $("#merchandisers-list-table_filter input").attr('placeholder', 'Data Search');
                    $('#merchandisers-list-table_length select').addClass('form-control input-sm');
               },
               error: function (data) {
                  console.log(data);
               },
               complete: function () {
                    $(".loader").hide();
                    $(".loader").css('margin-top','0%');
               }
           })
    } catch (e) {
     console.log("Error:"+e)
   }
  }
  /**
   * Adds a merchandiser.
   * @author <praveen>
   */
  function addMerchandiser()
  {
    try{
          var name                   = $('#merchandiser_name').val();
          var email                  = $('#merchandiser_email').val();
          var password               = $('#merchandiser_password').val();
          var phone                  = $('#merchandiser_phone').val();
          var address                = $('#merchandiser_address').val();
          var csrf_token             = $('meta[name="csrf-token"]').attr('content');

          $.ajax({
              url: base_url + "/admin/buyer-management/add-merchandiser",
              method: 'POST',
              data: {_token:csrf_token,name:name,email:email,phone:phone,address:address,p:password},
              beforeSend: function () {
                $(".loader").show();
                $(".loader").css('margin-top','12%');
              },
              statusCode: {
                   200: function() {
                        $('.snackbar_span').html('Merchandiser Added Successfully');
                        $('#snackbar').css('background-color','#1abb9c');
                        showSnackbar();
                        $('#addMerchandiserModal').modal('hide');
                        merchandiser();
                   },
                   400: function() {
                        $('.snackbar_span').html("Something went wrong!!");
                        $('#snackbar').css('background-color','#F44336');
                        showSnackbar();
                   },
                   409: function() {
                        $('#add_merchandiser_result').removeAttr('style');
                        $('#add_merchandiser_result').html('Email already registered.')
                        $("#add_merchandiser_result").addClass("alert alert-warning");
                   }
              },
              error: function (data) {
                console.log(data);
              },
              complete: function () {
                        $(".loader").hide();
                        $(".loader").css('margin-top','0%');
              }
          })
      } catch (e) {
     console.log("Error:"+e)
   }
   }
   /**
    * To get merchandiser details for editing
    * @param id
    * @author <praveen>
    */
   function getMerchandiser(id)
   {
    try{
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
             },
             success: function(data){
                 isSubmitting = false;
                 $('#merchandiser_edit_id').val(id);
                 $('#edit_merchandiser_name').val(data.merchandiser[0].merchandiser_name);
                 $('#edit_merchandiser_email').val(data.merchandiser[0].email);
                 $('#edit_merchandiser_phone').val(data.merchandiser[0].merchandiser_phone);
                 $('#edit_merchandiser_address').val(data.merchandiser[0].merchandiser_address);
                 $('#editMerchandiserModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_merchandiser_form').validator('validate');
                 $('#editMerchandiserModal').modal('show');
             },
             error: function (data) {
                console.log(data);
             },
             complete: function () {
                 $(".loader").hide();
                    $(".loader").css('margin-top','0%');
             }
         })
     } catch (e) {
     console.log("Error:"+e)
   }
   }
   /**
    * Function to update merchandiser details
    * @author <praveen>
    */
   function editMerchandiser()
   {
    try{
         var id         = $('#merchandiser_edit_id').val();
         var name       = $('#edit_merchandiser_name').val();
         var phone      = $('#edit_merchandiser_phone').val();
         var email      = $('#edit_merchandiser_email').val();
         var address    = $('#edit_merchandiser_address').val();

         var csrf_token = $('meta[name="csrf-token"]').attr('content');
         $.ajax({
             url:base_url + "/admin/buyer-management/update-merchandiser-details",
             dataType:"json",
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             method:"put",
             data:{_token:csrf_token,id:id,name:name,email:email,phone:phone,address:address},
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html('Merchandiser Updated Successfully');
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#editMerchandiserModal').modal('hide');
                       merchandiser();
                  },
                  409: function() {
                       $('#edit_merchandiser_result').removeAttr('style');
                       $('#edit_merchandiser_result').html('Email already registered.')
                       $("#edit_merchandiser_result").addClass("alert alert-warning");
                  }
             },
             error: function (data) {
                console.log(data);
             },
             complete: function () {
                       $(".loader").hide();
                       $(".loader").css('margin-top','0%');
             }
         })
     } catch (e) {
     console.log("Error:"+e)
   }
   }
   /**
    * To delete merchandiser
    * @param id
    * @author <praveen>
    *
    */
   function deleteMerchandiser(id)
   {
    try{
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
                         $('.snackbar_span').html('Merchandiser deleted Successfully');
                         $('#snackbar').css('background-color','#1abb9c');
                         showSnackbar();
                         $('#deleteMerchandiserModal').modal('hide');
                         merchandiser();
                    },
                    400: function() {
                         $('.snackbar_span').html('Something went wrong');
                         $('#snackbar').css('background-color','#F44336');
                         showSnackbar();
                    }
               },
               error: function (data) {
                  console.log(data);
               },
               complete: function () {
                         $(".loader").hide();
                         $(".loader").css('margin-top','0%');
               }
           })
     } catch (e) {
     console.log("Error:"+e)
   }
   }
 /**  Validation for numeric values **/
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
  /** [showSnackbar function] */
  function showSnackbar() {
    try{
      // Get the snackbar DIV
      var x = document.getElementById("snackbar")

      // Add the "show" class to DIV
      x.className = "show";

      // After 3 seconds, remove the show class from DIV
      setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
      } catch (e) {
       console.log("Error:"+e)
     }
  }