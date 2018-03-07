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
         tryCount : 0,
         retryLimit : 2,
         beforeSend: function () {
             $(".loader").show();
         },
         success: function (data) {
             var table = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="col" id="buyers-table"><table id="buyers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Buyer Name</th><th>Buyer Email</th><th>Buyer Phone</th><th>Buyer Address</th><th>Actions</th></tr></thead><tbody>';
             $.each(data.buyers, function(i, item){
              var index = i+1;
              table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.buyer_name+'</td><td>'+item.buyer_email+'</td><td>'+item.buyer_phone+'</td><td>'+item.buyer_address+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-buyer filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-buyer" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteBuyerModal" data-placement="left"></i></a></td></tr>';
             });
             table += '</tbody></table></div>';
              $('.main-content').html(table);

              /** [ Toggle bootstrap tooltip ] */
              $('.edit-buyer').tooltip('show')
              $('.edit-buyer').tooltip('toggle')
              $('.delete-buyer').tooltip('show')
              $('.delete-buyer').tooltip('toggle')

              /** [ Data Tabeles scripts ] */
              $('#buyers-list-table').DataTable();
              $("#buyers-list-table_filter input").addClass('form-control input-sm');
              $("#buyers-list-table_filter input").attr('placeholder', 'Data Search');
              $('#buyers-list-table_length select').addClass('form-control input-sm');
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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             statusCode: {
                  /** [Show response status based on HTTP status code] */
                  200: function() {
                       $('.snackbar_span').html("Buyer Added Successfully");
                       showSnackbar();
                       $('#addBuyerModal').modal('hide');
                       buyers();
                  },
                  400: function() {
                       $('.snackbar_span').html("Something went wrong!!");
                       showSnackbar();
                  },
                  409: function() {
                       $('#buyer_result').removeAttr('style');
                       $('#buyer_result').html('Email already registered.')
                       $("#buyer_result").addClass("alert alert-warning");
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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             success: function(data){
                 /** [Populate values in form inputs] */
                 isSubmitting = false;
                 $('#buyer_edit_id').val(id);
                 $('#edit_buyer_name').val(data.buyer[0].buyer_name);
                 $('#edit_buyer_email').val(data.buyer[0].buyer_email);
                 $('#edit_buyer_phone').val(data.buyer[0].buyer_phone);
                 $('#edit_buyer_address').val(data.buyer[0].buyer_address);

                 /** [ Backdrop for bootstrap modal ] */
                 $('#editBuyerModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })

                 /** [ Validate edit_buyer_form form using bootstrap validator ] */
                 $('#edit_buyer_form').validator('validate');
                 $('#editBuyerModal').modal('show');
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
           },
           method:"put",
           data:{_token:csrf_token,id:id,name:name,email:email,phone:phone,address:address},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {

                /** [Show response status based on HTTP status code] */
                200: function() {
                     $('.snackbar_span').html('Buyer Updated Successfully');
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
           },
           method:"delete",
           data:{_token:csrf_token,id:id},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {

                /** [Show response status based on HTTP status code] */
                200: function() {
                     $('.snackbar_span').html('Buyer deleted Successfully');
                     showSnackbar();
                     $('#deleteBuyerModal').modal('hide');
                     buyers();
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
               tryCount : 0,
               retryLimit : 2,
               beforeSend: function () {
                   $(".loader").show();
               },
               success: function (data) {
                   var table = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="col" id="merchandisers-table"><table id="merchandisers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Merchandiser Name</th><th>Merchandiser Email</th><th>Merchandiser Phone</th><th>Merchandiser Address</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.merchandisers, function(i, item){
                    var index = i+1;
                    table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.merchandiser_name+'</td><td>'+item.email+'</td><td>'+item.merchandiser_phone+'</td><td>'+item.merchandiser_address+'</td><td><a href="#"><i class="fa fa-pencil filterbtn options fa-button edit-merchandiser" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash filterbtn options fa-button delete-merchandiser" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteMerchandiserModal" data-placement="left"></i></a></td></tr>';
                   });
                   table += '</tbody></table></div>';
                    $('.main-content').html(table);

                    /** [ Toggle bootstrap tooltip ] */
                    $('.edit-merchandiser').tooltip('show')
                    $('.edit-merchandiser').tooltip('toggle')
                    $('.delete-merchandiser').tooltip('show')
                    $('.delete-merchandiser').tooltip('toggle')

                    /** [ data tables scripts ] */
                    $('#merchandisers-list-table').DataTable();
                    $("#merchandisers-list-table_filter input").addClass('form-control input-sm');
                    $("#merchandisers-list-table_filter input").attr('placeholder', 'Data Search');
                    $('#merchandisers-list-table_length select').addClass('form-control input-sm');
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
              tryCount : 0,
              retryLimit : 2,
              beforeSend: function () {
                $(".loader").show();
              },
              statusCode: {

                   /** [Show response status based on HTTP status code] */
                   200: function() {
                        $('.snackbar_span').html('Merchandiser Added Successfully');
                        showSnackbar();
                        $('#addMerchandiserModal').modal('hide');
                        merchandiser();
                   },
                   400: function() {
                        $('.snackbar_span').html("Something went wrong!!");
                        showSnackbar();
                   },
                   409: function() {
                        $('#add_merchandiser_result').removeAttr('style');
                        $('#add_merchandiser_result').html('Email already registered.')
                        $("#add_merchandiser_result").addClass("alert alert-warning");
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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             success: function(data){

                 /** [Populate values in form inputs] */
                 isSubmitting = false;
                 $('#merchandiser_edit_id').val(id);
                 $('#edit_merchandiser_name').val(data.merchandiser[0].merchandiser_name);
                 $('#edit_merchandiser_email').val(data.merchandiser[0].email);
                 $('#edit_merchandiser_phone').val(data.merchandiser[0].merchandiser_phone);
                 $('#edit_merchandiser_address').val(data.merchandiser[0].merchandiser_address);

                 /** [ Backdrop for bootstrap modal ] */
                 $('#editMerchandiserModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })

                 /** [ Validate edit_merchandiser_form form using bootstrap validator ] */
                 $('#edit_merchandiser_form').validator('validate');
                 $('#editMerchandiserModal').modal('show');
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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             method:"put",
             data:{_token:csrf_token,id:id,name:name,email:email,phone:phone,address:address},
             statusCode: {

                  /** [Show response status based on HTTP status code] */
                  200: function() {
                       $('.snackbar_span').html('Merchandiser Updated Successfully');
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
               tryCount : 0,
               retryLimit : 2,
               beforeSend: function () {
                 $(".loader").show();
               },
               method:"delete",
               data:{_token:csrf_token,id:id},
               statusCode: {

                    /** [Show response status based on HTTP status code] */
                    200: function() {
                         $('.snackbar_span').html('Merchandiser deleted Successfully');
                         showSnackbar();
                         $('#deleteMerchandiserModal').modal('hide');
                         merchandiser();
                    },
                    400: function() {
                         $('.snackbar_span').html('Something went wrong');
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