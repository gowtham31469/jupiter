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
             var table = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="col" id="buyers-table"><table id="buyers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Buyer Name</th><th>Buyer Email</th><th>Buyer Phone</th><th>Buyer Address</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
             $.each(data.buyers, function(i, item){
              var index = i+1;
              table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.buyer_name+'</td><td>'+item.buyer_email+'</td><td>'+item.buyer_phone+'</td><td>'+item.buyer_address+'</td>';
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
               table += '<a href="#"><i class="fa fa-pencil fa-button edit-buyer filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
             });
             table += '</tbody></table></div>';
              $('.main-content').html(table);

              /** [ Toggle bootstrap tooltip ] */
              $('.edit-buyer').tooltip('show')
              $('.edit-buyer').tooltip('toggle')
              $('.status').tooltip('show')
              $('.status').tooltip('toggle')

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
         var name       = toTitleCase($('#buyer_name').val());
         var email      = $('#buyer_email').val();
         var phone      = $('#buyer_phone').val();
         var address    = toTitleCase($('#buyer_address').val().replace(/,/g, ", "));
         
         var image      = $('#image')[0].files[0];
         var form_data = new FormData();

          form_data.append('name', name);
          form_data.append('email', email);
          form_data.append('phone', phone);
          form_data.append('address', address);
          form_data.append('image', image);
          form_data.append('_token', csrf_token);
         $.ajax({
             url: base_url + "/admin/buyer-management/add-jga-buyer",
             type: "POST",
             data: form_data,
             cache: false,
             contentType: false,
             processData: false,
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
                  },
                  422: function() {
                       $('#buyer_result').removeAttr('style');
                       $('#buyer_result').html('Only images are accepted & image size must be less than 2 MB.')
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
  var imgName = '';
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
                 imgName = data.buyer[0].logo;
                 /** [Populate values in form inputs] */
                 isSubmitting = false;
                 $('#buyer_edit_id').val(id);
                 $('#edit_buyer_name').val(data.buyer[0].buyer_name);
                 $('#edit_buyer_email').val(data.buyer[0].buyer_email);
                 $('#edit_buyer_phone').val(data.buyer[0].buyer_phone);
                 $('#edit_buyer_address').val(data.buyer[0].buyer_address);
                 $('#image_name').attr('src', data.buyer[0].logo);
                 $('#image_name').css({"height": "100px","width": "140px","margin-top": "16px"});

                 /** [ Backdrop for bootstrap modal ] */
                 $('#editBuyerModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })

                 /** [ Validate edit_buyer_form form using bootstrap validator ] */
                 $('#edit_buyer_form').validator('update');
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
       var name       = toTitleCase($('#edit_buyer_name').val());
       var phone      = $('#edit_buyer_phone').val();
       var email      = $('#edit_buyer_email').val();
       var address    = toTitleCase($('#edit_buyer_address').val().replace(/,/g, ", "));
       
       var edit_image = $('#edit_image')[0].files[0];
       var form_data  = new FormData();

        form_data.append('name', name);
        form_data.append('email', email);
        form_data.append('phone', phone);
        form_data.append('address', address);
        form_data.append('edit_image', edit_image);
        form_data.append('_token', csrf_token);
        form_data.append('id', id);
        form_data.append('imgName', imgName);
       $.ajax({
           url:base_url + "/admin/buyer-management/update-buyer-details",
           type: "POST",
           data: form_data,
           cache: false,
           contentType: false,
           processData: false,
           tryCount : 0,
           retryLimit : 2,
           beforeSend: function () {
             $(".loader").show();
           },
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
                },
                422: function() {
                     $('#edit_buyer_result').removeAttr('style');
                     $('#edit_buyer_result').html('Only images are accepted & image size must be less than 2 MB.')
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
   * Function for merchandiser page
   * @author <praveen>
   */
  function merchandiser()
  {
    try{
          
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
                   var table = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="col" id="merchandisers-table"><table id="merchandisers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Merchandiser Name</th><th>Merchandiser Email</th><th>Merchandiser Phone</th><th>Merchandiser Address</th><th>Buyer Name</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.merchandisers, function(i, item){
                    var index = i+1;
                    table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.merchandiser_name+'</td><td>'+item.email+'</td><td>'+item.merchandiser_phone+'</td><td>'+item.merchandiser_address+'</td><td>'+item.buyer_name+'</td>';
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
                     table += '<a href="#"><i class="fa fa-pencil filterbtn options fa-button edit-merchandiser" title="Edit" data-placement="left"></i></a></td></tr>';
                   });
                   table += '</tbody></table></div>';
                    $('.main-content').html(table);

                    /** [ Toggle bootstrap tooltip ] */
                    $('.edit-merchandiser').tooltip('show')
                    $('.edit-merchandiser').tooltip('toggle')
                    $('.status').tooltip('show')
                    $('.status').tooltip('toggle')

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
          var name                   = toTitleCase($('#merchandiser_name').val());
          var email                  = $('#merchandiser_email').val();
          var password               = $('#merchandiser_password').val();
          var phone                  = $('#merchandiser_phone').val();
          var address                = toTitleCase($('#merchandiser_address').val().replace(/,/g, ", "));
          var merchandiser_buyer     = $('#merchandiser_buyer').val();
          var csrf_token             = $('meta[name="csrf-token"]').attr('content');

          $.ajax({
              url: base_url + "/admin/buyer-management/add-merchandiser",
              method: 'POST',
              data: {_token:csrf_token,name:name,email:email,phone:phone,address:address,p:password,merchandiser_buyer:merchandiser_buyer},
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
                 $('#edit_merchandiser_buyer').val(data.merchandiser[0].buyer_id);

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
         var name       = toTitleCase($('#edit_merchandiser_name').val());
         var phone      = $('#edit_merchandiser_phone').val();
         var email      = $('#edit_merchandiser_email').val();
         var address    = toTitleCase($('#edit_merchandiser_address').val().replace(/,/g, ", "));
         var buyer      = $('#edit_merchandiser_buyer').val();

         
         $.ajax({
             url:base_url + "/admin/buyer-management/update-merchandiser-details",
             dataType:"json",
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             method:"put",
             data:{_token:csrf_token,id:id,name:name,email:email,phone:phone,address:address,buyer:buyer},
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
   * Makes a buyer active.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeBuyerActive(id,csrf_token)
    {
     try{
           if(isSubmitting) 
           {
               return;
           }
           isSubmitting = true;
           $.ajax({
                url:base_url + "/admin/buyer-management/make-buyer-active",
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
                         $('.snackbar_span').html('Buyer is active now ');
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
                  buyers();
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
   * Makes a Buyer inactive.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeBuyerInactive(id,csrf_token)
    {
     try{
           if(isSubmitting) 
           {
               return;
           }
           isSubmitting = true;
           $.ajax({
                url:base_url + "/admin/buyer-management/make-buyer-inactive",
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
                          $('.snackbar_span').html('Buyer is inactive now ');
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
                  buyers();
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
   * Makes a merchandiser active.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeMerchandiserActive(id,csrf_token)
    {
     try{
           if(isSubmitting) 
           {
               return;
           }
           isSubmitting = true;
           $.ajax({
                url:base_url + "/admin/buyer-management/make-merchandiser-active",
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
                         $('.snackbar_span').html('Merchandiser is active now ');
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
                  merchandiser();
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
   * Makes a Merchandiser inactive.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeMerchandiserInactive(id,csrf_token)
    {
     try{
           if(isSubmitting) 
           {
               return;
           }
           isSubmitting = true;
           $.ajax({
                url:base_url + "/admin/buyer-management/make-merchandiser-inactive",
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
                          $('.snackbar_span').html('Merchandiser is inactive now ');
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
                  merchandiser();
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