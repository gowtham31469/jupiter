 var isSubmitting = false;
 /**
  * Makes a customer active.
  * @param id,csrf_token
  * @author <praveen>
  */
  function makeCustomerActive(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/customer/customer-management/make-customer-profile-active",
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
                        $('.snackbar_span').html('Customer is active now ');
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
                 location.reload()
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }

 /**
  * Makes a customer inactive.
  * @param id,csrf_token
  * @author <praveen>
  */
  function makeCustomerInactive(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/customer/customer-management/make-customer-profile-inactive",
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
                         $('.snackbar_span').html('Customer is inactive now ');
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
               	location.reload()
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }


 /**
  * Gets the customer by id for editing.
  * @param id
  * @author <praveen>
  */
   function getCustomer(id)
   {
    try{
         if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          var a = []
          $('#editContact').html('');
          $('#editbilladdress').html('');
          $('#edit_password').val('');
          $('.edit-customers-metadata').hide();
         $.ajax({
             url:base_url + "/customer/customer-management/get-customers-profile",
             dataType:"json",
             method:"get",
             data:{id:id},
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             success: function(data){

                 /** [ Populate values in form inputs ] */
                 isSubmitting = false;
                 $('#customer_edit_id').val(id);
				 
				  $('#customer_name').val(data.customers[0].name);
                  $('#customer_email').val(data.customers[0].email);
                  $('#customer_role').val(data.customers[0].role_id);
                  $('#customer_company').val(data.customers[0].company_id);
				 
				  $('#customer_company').prop('disabled',true)
				  $('#customer_role').prop('disabled',true)
				 
				  $('#customer_role').selectpicker('refresh');
                  $('#customer_company').selectpicker('refresh');
				 $('#customer_buyer').html('');
				 $(data.company_buyers).each(function(key,value){
									$('#customer_buyer').append('<option value='+value.buyer_id+'>'+value.buyer_name+'</option>')
				 });
				 $('#customer_buyer').selectpicker('refresh');
				 
				  var buyers_array = [];
                      /** [ Buyers multi selectpicker value populating ] */
                      $.each(data.buyers, function (key, item) {
                         $('#customer_buyer option[value=' + item.buyer_id + ']').prop('selected', true);
                         $('#customer_buyer').selectpicker('refresh');

                         /** [Push selected buyers into a array] */
                         $.each($("#customer_buyer option:selected"), function(){
                           buyers_array.push($(this).text());
                         });
						  
                         buyers = [];
                      });
				 
                 /** [ Backdrop for bootstrap modal ] */
                 $('#editCustomerModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_customer_form').validator('update');
                 $('#editCustomerModal').modal('show');
             },
             error: function(errorXHR, errorStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
             },
             complete: function () {
              $('#edit_customer_form').validator('validate');
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


/** Dynamically add/remove contact person & Billing Address in Customer Management jquery ends(for editing)**/
/**
  * To update customer details
  * @author <praveen>
  */
  function editCustomer()
  {
    try{
         
          var id                  =  $('#customer_edit_id').val();
          var customer_name       =  $('#customer_name').val();
          var customer_email      =  $('#customer_email').val();
          var customer_role       =  $('#customer_role').val();
          var customer_company    =  $('#customer_company').val();
        
          var buyers          = [];
          /** [ Create array of multi selected buyers ] */
          $.each($("#customer_buyer option:selected"), function(){
            buyers.push($(this).val());
          });


          /** [ Joining array values using some delimiters ] */   
          buyer            = buyers.join(',');
var csrf_token          =  $('meta[name="csrf-token"]').attr('content');
          $.ajax({
              url: base_url + "/customer/customer-management/update-customerProfile",
              method: 'PUT',
              data: {_token:csrf_token,id:id,buyer:buyer,customer_name:customer_name,customer_email:customer_email,customer_role:customer_role,customer_company:customer_company},
              tryCount : 0,
              retryLimit : 2,
              beforeSend: function () {
                $(".loader").show();
              },
              statusCode: {

                   /** [ Show response status based on HTTP status code ] */
                   200: function() {
                        $('.snackbar_span').html('Customer Updated Successfully');
                        showSnackbar();
                        $('#editCustomerModal').modal('hide');
                   },
                   409: function() {
                        $('#customer_edit_result').removeAttr('style');
                        $('#customer_edit_result').html('Email already registered.')
                        $("#customer_edit_result").addClass("alert alert-warning");
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
 * Adds a customer.
 * @author <praveen>
 */
function addCustomer()
{
  try{
          var customer_name       =  $('#customer_add_name').val();
          var customer_email      =  $('#customer_add_email').val();
          var customer_role       =  $('#customer_add_role').val();
          var customer_company    =  $('#customer_add_company').val();
	  
        var password            =  $('#password').val();
        var csrf_token          =  $('meta[name="csrf-token"]').attr('content');
        
          var buyers          = [];
          /** [ Create array of multi selected buyers ] */
          $.each($("#customer_add_buyer option:selected"), function(){
            buyers.push($(this).val());
          });


          /** [ Joining array values using some delimiters ] */   
          buyer = buyers.join(',');

        $.ajax({
            url: base_url + "/customer/customer-management/add-customerProfile",
            method: 'POST',
            data: {_token:csrf_token,password:password,buyer:buyer,customer_name:customer_name,customer_email:customer_email,customer_role:customer_role,customer_company:customer_company},
            tryCount : 0,
            retryLimit : 2,
            beforeSend: function () {
              $(".loader").show();
            },
            statusCode: {
 
                 /** [ Show response status based on HTTP status code ] */
                 200: function() {
                      $('.snackbar_span').html('Customer Added Successfully');
                      showSnackbar();
                      $('#addCustomerModal').modal('hide');
                      customersListView();
                 },
                 400: function() {
                      $('.snackbar_span').html('Something went wrong');
                      showSnackbar();
                 },
                 409: function() {
                      $('#customer_result').removeAttr('style');
                      $('#customer_result').html('Email already registered.')
                      $("#customer_result").addClass("alert alert-warning");
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
