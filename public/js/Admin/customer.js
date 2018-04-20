/* Customer management script
 * @author <praveen>
 */
/**
 * To show all customers list
 * @author <praveen>
 */
 var isSubmitting = false;
  function customersListView()
  {
    try{
          $('.main-sub-menu').removeClass('hidden');
          $( ".sub_modules a" ).removeClass( "active" )
          $('.sticker_orders a').addClass("active")
          $('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');

          /** Refresh bootstrap selectpicker **/
          $('#payment_terms').selectpicker('refresh');
          $('#edit_payment_terms').selectpicker('refresh');
          $('#place_of_supply').selectpicker('refresh');
          $('#edit_place_of_supply').selectpicker('refresh');
          $('#edit_buyer').selectpicker('refresh');
          $('#buyer').selectpicker('refresh');
          var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
          var csrf_token = $('meta[name="csrf-token"]').attr('content');
          $.ajax({
              url: base_url + "/admin/customer-management/customers-list",
              type: "Post",
              dataType:"json",
              data: {_token:csrf_token},
              tryCount : 0,
              retryLimit : 2,
              beforeSend: function () {
                  $(".loader").show();
              },
              success: function (data) {
                var table = '<div class="col" id="customer-table"><table id="customers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Customer Name</th><th>Factory Code</th><th>Default Contact</th><th>Courier Choice</th><th>TIN Number</th><th>GSTIN Number</th><th>PAN Number</th><th>Billing Address</th><th>Shipping Address</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                $.each(data.customers, function(i, item){
                 var index = i+1;
                 table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.company_name+'</td><td>'+item.factory_code+'</td><td><dl><dt>Contact Person:</dt><dd>'+item.contact_person+'</dd><dt>Contact Email:</dt><dd>'+item.contact_email+'</dd><dt>Contact Phone:</dt><dd>'+item.phone_no+'</dd></dl></td><td>'+item.courier_choice+'</td><td>'+item.tin_number+'</td><td>'+item.gstin_number+'</td><td>'+item.pan_number+'</td><td><dl><dt>Address:</dt><dd>'+item.bill_address+','+item.bill_country+'</dd><dt>Email:</dt><dd>'+item.bill_email+'</dd><dt>Phone:</dt><dd>'+item.bill_telephone+'</dd><dt>Fax:</dt><dd>'+item.bill_fax+'</dd></dl></td><td><dl><dt>Address:</dt><dd>'+item.ship_address+','+item.ship_country+'</dd><dt>Email:</dt><dd>'+item.ship_email+'</dd><dt>Phone:</dt><dd>'+item.ship_telephone+'</dd><dt>Fax:</dt><dd>'+item.ship_fax+'</dd></dl></td>';
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
                    table += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button" aria-hidden="true" title="Make Inactive" id="make_inactive" data-placement="left"></i></a>';
                   }
                   else
                   {
                    table += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button" aria-hidden="true" title="Make Active" id="make_active" data-placement="left"></i></a>';
                   }
                   table += '<a href="#"><i class="fa fa-pencil filterbtn options edit-customer fa-button" title="Edit" data-placement="left"></i></a></td></tr>';
                });
                table += '</tbody></table></div>';
                $('.main-content').html(table);

                /** [ Toggle bootstrap tooltip ] */
                $('.edit-customer').tooltip('show')
                $('.edit-customer').tooltip('toggle')
                $('.delete-customer').tooltip('show')
                $('.delete-customer').tooltip('toggle')
                $('.status').tooltip('show')
                $('.status').tooltip('toggle')

                /** [ Data Tabeles scripts ] */
                $('#customers-list-table').DataTable();
                $("#customers-list-table_filter input").addClass('form-control');
                $("#customers-list-table_filter input").attr('placeholder', 'Data Search');
                $('.dataTables_length select').addClass('form-control');

                /** [ Call autopopulateCountry function for auto-populating country in textbox  ] */
                autopopulateCountry(0)
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
        var factory_code        =  $('#factory_code').val();
        var company_name        =  $('#company_name').val();
        var courier_choice      =  $('#courier_choice').val();
        var tin_number          =  $('#tin_number').val();
        var gstin_number        =  $('#gstin_number').val();
        var pan_number          =  $('#pan_number').val();
        var login_email         =  $('#login_email').val();
        var password            =  $('#password').val();
        var csrf_token          =  $('meta[name="csrf-token"]').attr('content');
        var pi_required         =  $('#pi_required').is(':checked')?1:0;
        var payment_term        =  $("#payment_terms option:selected").val();
        var place_of_supply     =  $("#place_of_supply option:selected").val();
        var po_required         =  $('#po_required').is(':checked')?1:0;
        var delivery_date_req   =  $('#delivery_date_required').is(':checked')?1:0;
        var freight             =  $("#freight option:selected").val();
        var day_count           =  $('#day_count').val();
        var short_name          =  $('#short_name').val();

        if($('#freight option:selected').text() === "Nominated Forwarded")
        {
          var forwarder = $("#forwarder option:selected").val();
        }
        else
        {
          var forwarder = '';
        }

        /** @type {Array} [ initializing empty arrays ] */
        var contact_person  = [];
        var contact_phone   = [];
        var contact_email   = [];
        var default_contact = [];
        var address         = [];
        var country         = [];
        var telephone       = [];
        var fax             = [];
        var address_email   = [];
        var billing_address = [];
        var shipping_address= [];
        var pincode         = [];

        var buyers = [];
        
        /** [ Create array of contact person for multiple contact details ] */
        $('.contact_person').each(function(i, item)
        {
            contact_person[i]    = $(item).val();
            
        });

        /** [ Create array of contact phone for multiple contact details ] */
        $('.contact_phone').each(function(i, item)
        {
            contact_phone[i]    = $(item).val();
            
        });

        /** [ Create array of contact email for multiple contact details ] */
        $('.contact_email').each(function(i, item)
        {
            contact_email[i]    = $(item).val();
            
        });

        /** [ Create array of default contact  or not (checkbox values) ] */
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

        /** [Create array of multiple address for address ] */
        $('.address').each(function(i, item)
        {
            address[i]    = $(item).val();
            
        });

        /** [ Create array of multiple country for address ] */
        $('.country').each(function(i, item)
        {
            country[i]    = $(item).val();
            
        });

        /** [ Create array of multiple telephone for address ] */
        $('.telephone').each(function(i, item)
        {
            telephone[i]    = $(item).val();
            
        });

        /** [ Create array of multiple fax for address ] */
        $('.fax').each(function(i, item)
        {
            fax[i]    = $(item).val();
            
        });
        $('.address_email').each(function(i, item)
        {
            address_email[i]    = $(item).val();
            
        });

        /** [ Create array of billing address or not (checkbox values) ] */
        $('.default_address').each(function(i, item)
        {
          if ($(this).is(':checked'))
            {
              billing_address[i]    = 1;
            }
            else
            {
              billing_address[i]    = 0;
            }
            
        });

        /** [ Create array of shipping address or not (checkbox values) ] */
        $('.shipping_address').each(function(i, item)
        {
          if ($(this).is(':checked'))
            {
              shipping_address[i]    = 1;
            }
            else
            {
              shipping_address[i]    = 0;
            }
            
        });

        /** [Create array of multiple pincode for address ] */
        $('.pincode').each(function(i, item)
        {
            pincode[i]    = $(item).val();
            
        });

        /** [ Create array of multi selected buyers ] */
        $.each($("#buyer option:selected"), function(){
          buyers.push($(this).val());
        });

        /** [ Joining array values using some delimiters ] */
        contact_person   = contact_person.join(',');
        contact_phone    = contact_phone.join(',');
        contact_email    = contact_email.join(',');
        default_contact  = default_contact.join(',');
        address          = address.join('|');
        country          = country.join(',');
        telephone        = telephone.join(',');
        fax              = fax.join(',');
        address_email    = address_email.join(',');
        billing_address  = billing_address.join(',');
        shipping_address = shipping_address.join(',');
        buyer            = buyers.join(',');
        pincode          = pincode.join(',');

        $.ajax({
            url: base_url + "/admin/customer-management/add-customer",
            method: 'POST',
            data: {_token:csrf_token,factory_code:factory_code,company_name:company_name,contact_person:contact_person,contact_phone:contact_phone,contact_email:contact_email,courier_choice:courier_choice,tin_number:tin_number,gstin_number:gstin_number,pan_number:pan_number,login_email:login_email,p:password,address:address,country:country,telephone:telephone,fax:fax,address_email:address_email,default_contact:default_contact,billing_address:billing_address,buyer:buyer,pi_required:pi_required,shipping_address:shipping_address,payment_term:payment_term,place_of_supply:place_of_supply,po_required:po_required,delivery_date_req:delivery_date_req,freight:freight,forwarder:forwarder,day_count:day_count,short_name:short_name,pincode:pincode},
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

 /**  
  * Validation for numeric values
  * @param   evt 
  * @return {Boolean}
  * @author <praveen>
  */
 function isNumber(evt) {
  try{
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode; 

        /** [ Checking key stroke values for numeric values ] */
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
  * Validation for numeric values with dot
  * @param   evt 
  * @return {Boolean} 
  * @author <praveen>    
  */
 function isNumberKey(evt){
  try{
       var charCode = (evt.which) ? evt.which : event.keyCode

       if (charCode == 46)
       {
           var inputValue = $("#inputfield").val()
           if (inputValue.indexOf('.') < 1)
           {
               return true;
           }
           return false;
       }
       /** [ Checking key stroke values for numeric values ] */
       if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
       {
           return false;
       }
       return true;
    } catch (e) {
     console.log("Error:"+e)
   }
  }   

/** Dynamically add/remove contact person & Billing Address in Customer Management jquery begins**/
 /**
  * Adds a contact person forms dynamically on button click.
  * @author <praveen>
  * @param   rem,e (this pointer , event)
  */
   function addContact(rem,e) {
    try{
          e.preventDefault(); 
             var a = [] 
             var id = $(rem).attr("data-id"); 
             var val = parseInt(id) + 1; 
             $('#addcontact').append('<div id="contact_' + val + '" class="addcontact"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="removeContact(this)" title="Delete"></i><div type="button" class="accordion button-bottom"><h4>Contact Person 1</h4></div><div id="contact' + val + '" class="panel"><div class="form-group has-feedback"><label for="contact_person' + val + '" class="form_label">Contact Person </label><input class="form-control input-sm contact_person" placeholder="Contact Person Name" required="" name="contact_person' + val + '" value="" id="contact_person' + val + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="contact_phone' + val + '" class="form_label">Contact Phone No</label><div class="input-group"><span class="input-group-addon input-sm " id="basic-addon1">+91</span><input class="form-control input-sm contact_phone" placeholder="Contact Phone No" data-minlength="10" maxlength="10" required="" onkeypress="return isNumber(event)" name="contact_phone' + val + '" value="" id="contact_phone' + val + '" type="text"></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="contact_email' + val + '" class="form_label">Contact Email</label><div class="input-group"><span class="input-group-addon input-sm" id="basic-addon1">@</span><input class="form-control input-sm contact_email" placeholder="Contact Email" required="" name="contact_email' + val + '" value="" id="contact_email' + val + '" type="email"></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input default_contact" type="checkbox" value="" name="default" id="default_contact' + val + '"> Make as default contact person</label></div></div></div>') 
             $('#add_customer_form').validator('update'); 
              
             $(rem).attr("data-id", val); 

             /** [ Create dynamic add address button ] */
             var j = 1; 
             $('#addcontact .addcontact').each(function(key, val) { 
                 $(this).find(".button-bottom").html("<h4>Contact Person " + j +"</h4>"); 
                 a.push($(this).attr('id')); 
                 j++; 
             }) 

             /** [ Make only 1 default contact person selected ] */
             var b = a.join(','); 
             $('#hidden').val(b); 
             $(':checkbox').on('change',function(){ 
               var th = $(this), name = th.prop('name'); 
               if(th.is(':checked')){ 
                   $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false); 
                } 
          });
       } catch (e) {
       console.log("Error:"+e)
     }
   }

 /**
  * Removes a  contact person form dynamically on button click.
  *
  * @author <praveen>
  * @param   rem (this pointer)
  */
   function removeContact(rem) {
    try{
         var a = []
         var al = $(rem).parent().attr('id');
         $(rem).parent().remove();

         /** [ To generate dynamic contact person title ] */
         var i = 1;
         $('#addcontact .addcontact').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Contact Person " + i +"</h4>");
             a.push($(this).attr('id'));
             i++;
         })
         var b = a.join(',');
         $('#hidden').val(b);

         /** [ updating add customer form valiadtion] */
         $('#add_customer_form').validator('update');
      } catch (e) {
       console.log("Error:"+e)
     }
   }

 /**
  * Adds an contact address form dynamically on button click.
  * @author <praveen>
  * @param   rem,e (this pointer , event)
  */
   function addAddress(rem,e) {
    try{
         e.preventDefault();
         var a = []
         var id = $(rem).attr("data-id");
         var val = parseInt(id) + 1;
         $('#addbilladdress').append('<div id="billaddress_' + val + '" class="addbilladdress"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="removeAddress(this)" title="Delete"></i><div type="button" class="accordion button-bottom active"><h4>Address 1</h4></div><div id="billaddress' + val + '" class="panel" style="display: block;"><div class="form-group has-feedback"><label for="address' + val + '" class="form_label">Address</label><textarea class="form-control  address" required="" placeholder="Address" style="resize:none;" rows="3" name="address' + val + '" cols="50" id="address' + val + '"></textarea><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="pincode' + val + '" class="form_label">Pincode</label><input class="form-control input-sm pincode" placeholder="Pincode" required="" data-minlength="6" maxlength="6" onkeypress="return isNumber(event)" name="pincode' + val + '" value="" id="pincode' + val + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="country' + val + '" class="form_label">Country</label><input class="form-control input-sm country" placeholder="Country" required="" name="country' + val + '" value="" id="country' + val + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="telephone' + val + '" class="form_label">Phone</label><div class="input-group"><span class="input-group-addon input-sm " id="basic-addon1">+91</span><input class="form-control input-sm telephone" placeholder="Phone" required="" data-minlength="10" maxlength="10" onkeypress="return isNumber(event)" name="telephone' + val + '" value="" id="telephone' + val + '" type="text"></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="fax' + val + '" class="form_label">Fax</label><input class="form-control input-sm fax" placeholder="Fax" onkeypress="return isNumber(event)" name="fax' + val + '" value="" id="fax' + val + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="address_email' + val + '" class="form_label">Email</label><div class="input-group"><span class="input-group-addon input-sm" id="basic-addon1">@</span><input class="form-control input-sm address_email" placeholder="Email" required="" name="address_email' + val + '" value="" id="address_email' + val + '" type="email"></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input default_address" type="checkbox" value="" name="default1" id="default_address' + val + '"> Make as default billing address</label></div><div class="form-check"><label class="form-check-label"><input class="form-check-input shipping_address" type="checkbox" value="" name="defaultShip1" id="shipping_address' + val + '"> Make as default shipping address</label></div></div></div>')
         $('#add_customer_form').validator('update');
         autopopulateCountry(val);
         
         $(rem).attr("data-id", val);

         /** [ Create dynamic add address button ] */
         var j = 1;
         $('#addbilladdress .addbilladdress').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Address " + j +"</h4>");
             a.push($(this).attr('id'));
             j++;
         })

         /** [ Make only 1 default bill address, shipping address selected ] */
         var b = a.join(',');
         $('#hidden').val(b);
         $(':checkbox').on('change',function(){
           var th = $(this), name = th.prop('name');
           if(th.is(':checked')){
               $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
            }
          });
       } catch (e) {
     console.log("Error:"+e)
   }
   }

 /**
  * Removes an contact address form dynamically on button click.
  *
  * @author <praveen>
  * @param   rem (this pointer)
  */
   function removeAddress(rem) {
    try{
         var a = []
         var al = $(rem).parent().attr('id');
         $(rem).parent().remove();

         /** [ To generate dynamic contact person title ] */
         var i = 1;
         $('#addbilladdress .addbilladdress').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Address " + i +"</h4>");
             a.push($(this).attr('id'));
             i++;
         })
         var b = a.join(',');
         $('#hidden').val(b);

         /** [ updating add customer form valiadtion ] */
         $('#add_customer_form').validator('update');
       } catch (e) {
      console.log("Error:"+e)
    }
   }

 /** Dynamically add/remove contact person & Billing Address in Customer Management jquery ends**/
 /**
  * To auto populate country from stored json file
  * @param id
  * @author <praveen>
  */
   function autopopulateCountry(id)
   {
    try{
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
     } catch (e) {
     console.log("Error:"+e)
    }
   }

 /**
  * Edit auto-autopopulate country(In customer form)  from stored json file
  * @param id
  * @author <praveen>
  */
 function editAutopopulateCountry(id)
 {
  try{
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
              var value = $("#edit_country" + id).getSelectedItemData().code;
              $("#edit_country" + id).val(value).trigger("change");
            }
       }
      };
    $("#edit_country" + id).easyAutocomplete(options);
   } catch (e) {
     console.log("Error:"+e)
   }
 }

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
               url:base_url + "/admin/customer-management/make-customer-active",
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
                 customersListView();
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
               url:base_url + "/admin/customer-management/make-customer-inactive",
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
                 customersListView();
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
         $.ajax({
             url:base_url + "/admin/customer-management/get-customers",
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
                 
                  $('#edit_factory_code').val(data.customers[0].factory_code);
                  $('#edit_company_name').val(data.customers[0].company_name);
                  $('#edit_courier_choice').val(data.customers[0].courier_choice);
                  $('#edit_tin_number').val(data.customers[0].tin_number);
                  $('#edit_gstin_number').val(data.customers[0].gstin_number);
                  $('#edit_pan_number').val(data.customers[0].pan_number);
                  $('#edit_login_email').val(data.customers[0].email);
                  $('#edit_place_of_supply').val(data.customers[0].place_of_supply);
                  $('#edit_payment_terms').val(data.customers[0].payment_terms);
                  $('#edit_freight').val(data.customers[0].freight_type);
                  $('#edit_forwarder').val(data.customers[0].nominated_forwarder);
                  $('#edit_day_count').val(data.customers[0].delivery_day_count);
                  $('#edit_short_name').val(data.customers[0].short_name);
                  
                  /** [ Refresh  bootstrap selectpicker ] */
                  $('#edit_place_of_supply').selectpicker('refresh');
                  $('#edit_payment_terms').selectpicker('refresh');
                  $('#edit_freight').selectpicker('refresh');
                  $('#edit_forwarder').selectpicker('refresh');

                  /** [ PI required field checked or not checkbox ] */
                  if(data.customers[0].pi_required === 1)
                  {
                    $('#edit_pi_required').prop('checked', true);

                    /** [ Show PO required checkbox ] */
                    $('div.edit-po-required-div').show();

                    /** [ PO required field checked or not checkbox ] */
                    if(data.customers[0].po_required === 1)
                    {

                      $('#edit_po_required').prop('checked', true);
                    }
                    else
                    {
                      $('#edit_po_required').prop('checked', false);
                    }
                  }
                  else
                  {
                    /** [ Hide PO required checkbox ] */
                    $('div.edit-po-required-div').hide();
                    $('#edit_pi_required').prop('checked', false);
                  }

                  /** [ if already password entered,Hide password input field. Othewise show ] */
                  if(data.customers[0].password === 1)
                   {
                    $('.edit_pwd').addClass('hidden');
                    $('#edit_password').prop('required',false)
                   }
                   else
                   {
                    $('.edit_pwd').removeClass('hidden');
                    $('#edit_password').prop('required',true)
                    $('#edit_password').attr('minlength',6)
                   }
                     /** [ Delivery date mandatory checkbox  checked or not  ] */
                     if(data.customers[0].delivery_date_required === 1)
                      {
                        $('#edit_delivery_date_required').prop('checked', true);
                      }
                      else
                      {
                        $('#edit_delivery_date_required').prop('checked', false);
                      }
                 /** Hide and show forwarder div based on freight type */
                 if(data.customers[0].freight_type === 3)
                  {
                    $('.edit_forwarder_div').show(); 
                  }
                  else
                  {
                    $('.edit_forwarder_div').hide(); 
                  }
                 
                 /** [ Create dyanmic address form ] */
                 $.each(data.address,function(key,value){
                   key += 1;
                    var address_html = '<div edit="editbilladdress_' + key + '" class="editbilladdress">';
                    if(key != 1)
                    {
                      address_html += '<i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="editRemoveAddress(this)" title="Delete"></i>';
                    }
                    address_html += '<div type="button" class="accordion button-bottom active"><h4>Address 1</h4></div><div id="editbilladdress' + key + '" class="panel" style="display: block;"><div class="form-group has-feedback"><label for="edit_address' + key + '" class="form_label">Address</label><textarea class="form-control edit_address" required="" placeholder="Address" style="resize:none;" rows="3" name="edit_address' + key + '" cols="50" id="edit_address' + key + '">'+value.address+'</textarea><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_pincode' + key + '" class="form_label">Pincode</label><input class="form-control input-sm edit_pincode" placeholder="Pincode" required="" data-minlength="6" maxlength="6" onkeypress="return isNumber(event)" name="edit_pincode' + key + '" value="'+value.pincode+'" id="edit_pincode' + key + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_country' + key + '" class="form_label">Country</label><input class="form-control input-sm edit_country" placeholder="Country" required="" name="edit_country' + key + '" value="'+value.country+'" id="edit_country' + key + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_telephone' + key + '" class="form_label">Phone</label><div class="input-group"><span class="input-group-addon input-sm " id="editbasic-addon1">+91</span><input class="form-control input-sm edit_telephone" placeholder="Phone" required="" data-minlength="10" maxlength="10" onkeypress="return isNumber(event)" name="edit_telephone' + key + '" value="'+value.telephone+'" id="edit_telephone' + key + '" type="text"></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_fax' + key + '" class="form_label">Fax</label><input class="form-control input-sm edit_fax" placeholder="Fax" onkeypress="return isNumber(event)" name="edit_fax' + key + '" value="'+value.fax+'" id="edit_fax' + key + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_address_email' + key + '" class="form_label">Email</label><div class="input-group"><span class="input-group-addon input-sm" id="edit-basic-addon1">@</span><input class="form-control input-sm edit_address_email" placeholder="Email" required="" name="edit_address_email' + key + '" value="'+value.email+'" id="edit_address_email' + key + '" type="email"></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input edit_default_address" type="checkbox" value="" name="default1" id="edit_default_address' + key + '"> Make as default billing address</label></div><div class="form-check"><label class="form-check-label"><input class="form-check-input edit_shipping_address" type="checkbox" value="" name="defaultShip1" id="edit_shipping_address' + key + '"> Make as default shipping address</label></div></div></div>';
                      
                      $('#editbilladdress').append(address_html);

                      editAutopopulateCountry(key);

                      /** [ billing address or not (checkbox) ] */
                      if(value.is_billing_address === 1)
                      {
                        $('#edit_default_address'+ key).prop('checked', true);
                      }
                      else
                      {
                        $('#edit_default_address'+ key).prop('checked', false);
                      }

                      /** [ Shipping address or not (checkbox) ] */
                      if(value.is_shipping_address === 1)
                      {
                        $('#edit_shipping_address'+ key).prop('checked', true);
                      }
                      else
                      {
                        $('#edit_shipping_address'+ key).prop('checked', false);
                      }

                      /** [ Buyers multi selectpicker value populating ] */
                      $.each(data.buyers, function (key, item) {
                       $('#edit_buyer option[value=' + item.buyer_id + ']').prop('selected', true);
                       $('#edit_buyer').selectpicker('refresh');
                      });
                     
                     /** [ Create dynamic add address button ] */
                     var j = 1;
                     $('#editbilladdress .editbilladdress').each(function(key, val) {
                         $(this).find(".button-bottom").html("<h4>Address " + j +"</h4>");
                         a.push($(this).attr('id'));
                         j++;
                     })

                     /** [ Make only 1 default bill address, shipping address selected ] */
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
               });
                
                /** [ Create dyanmic contact person form ] */
                 $.each(data.contacts,function(key,value){
                   key += 1;
                    var contact_html = '<div id="edit_contact_' + key + '" class="editcontact">';
                    if(key != 1)
                    {
                      contact_html += '<i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="editRemoveContact(this)" title="Delete"></i>';
                    }
                    contact_html += '<div type="button" class="accordion button-bottom"><h4>Contact Person 1</h4></div><div id="edit_contact' + key + '" class="panel"><div class="form-group has-feedback"><label for="edit_contact_person' + key + '" class="form_label">Contact Person </label><input class="form-control input-sm edit_contact_person" placeholder="Contact Person Name" required="" name="edit_contact_person' + key + '" value="' + value.contact_person_name + '" id="edit_contact_person' + key + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_contact_phone' + key + '" class="form_label">Contact Phone No</label><div class="input-group"><span class="input-group-addon input-sm " id="edit-basic-addon1">+91</span><input class="form-control input-sm edit_contact_phone" placeholder="Contact Phone No" data-minlength="10" maxlength="10" required="" onkeypress="return isNumber(event)" name="edit_contact_phone' + key + '" value="' + value.contact_person_phone + '" id="edit_contact_phone' + key + '" type="text"></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_contact_email' + key + '" class="form_label">Contact Email</label><div class="input-group"><span class="input-group-addon input-sm" id="edit-basic-addon1">@</span><input class="form-control input-sm edit_contact_email" placeholder="Contact Email" required="" name="edit_contact_email' + key + '" value="' + value.contact_person_email + '" id="edit_contact_email' + key + '" type="email"></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input edit_default_contact" type="checkbox" value="" name="default" id="edit_default_contact' + key + '"> Make as default contact person</label></div></div></div>';
                      
                      $('#editContact').append(contact_html);

                      editAutopopulateCountry(key);

                       /** [ default contact person check box ] */
                      if(value.default_contact_person === 1)
                      {
                        $('#edit_default_contact'+ key).prop('checked', true);
                      }
                      else
                      {
                        $('#edit_default_contact'+ key).prop('checked', false);
                      }

                      /** [ Create dynamic add address button ] */
                       var j = 1;
                       $('#editContact .editcontact').each(function(key, val) {
                           $(this).find(".button-bottom").html("<h4>Contact Person " + j +"</h4>");
                           a.push($(this).attr('id'));
                           j++;
                       })

                       /** [ Make only 1 default contact person selected ] */
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

 /** Dynamically add/remove contact person & Billing Address in Customer Management jquery begins(for editing)**/
  /**
   * To edit contact person
   *
   * @author <praveen>
   */
   function editAddContact(rem,e) {
    try{
         e.preventDefault();
         var a = []
         var id = $(rem).attr("data-id");
         var val = parseInt(id) + 1;
         $('#editContact').append('<div id="editcontact_' + val + '" class="editcontact"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="editRemoveContact(this)" title="Delete"></i><div type="button" class="accordion button-bottom"><h4>Contact Person 1</h4></div><div id="edit_contact_person' + val + '" class="panel"><div class="form-group has-feedback"><label for="edit_contact_person' + val + '" class="form_label">Contact Person </label><input class="form-control input-sm edit_contact_person" placeholder="Contact Person Name" required="" name="edit_contact_person' + val + '" value="" id="edit_contact_person' + val + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_contact_phone' + val + '" class="form_label">Contact Phone No</label><div class="input-group"><span class="input-group-addon input-sm " id="edit-basic-addon1">+91</span><input class="form-control input-sm edit_contact_phone" placeholder="Contact Phone No" data-minlength="10" maxlength="10" required="" onkeypress="return isNumber(event)" name="edit_contact_phone' + val + '" value="" id="edit_contact_phone' + val + '" type="text"></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_contact_email' + val + '" class="form_label">Contact Email</label><div class="input-group"><span class="input-group-addon input-sm" id="edit-basic-addon1">@</span><input class="form-control input-sm edit_contact_email" placeholder="Contact Email" required="" name="edit_contact_email' + val + '" value="" id="edit_contact_email' + val + '" type="email"></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input edit_default_contact" type="checkbox" value="" name="default" id="edit_default_contact' + val + '"> Make as default contact person</label></div></div></div>')
         $('#edit_customer_form').validator('update');
         
         $(rem).attr("data-id", val);

         /** [ Create dynamic add address button ] */
         var j = 1;
         $('#editContact .editcontact').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Contact Person " + j +"</h4>");
             a.push($(this).attr('id'));
             j++;
         })

         /** [ Make only 1 default contact person selected ] */
         var b = a.join(',');
         $('#hidden').val(b);
         $(':checkbox').on('change',function(){
           var th = $(this), name = th.prop('name');
           if(th.is(':checked')){
               $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
            }
          });
       } catch (e) {
           console.log("Error:"+e)
      }
   }

 /**
  * To remove contact person while editing
  *
  * @author <praveen>
  */
   function editRemoveContact(rem) {
    try{
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
       } catch (e) {
           console.log("Error:"+e)
      }
   }

 /**
  * To add contact address while editing
  *
  * @author <praveen>
  */
   function editAddAddress(rem,e) {
    try{
         e.preventDefault();
         var a = []
         var id = $(rem).attr("data-id");
         var val = parseInt(id) + 1;
         $('#editbilladdress').append('<div id="editbilladdress_' + val + '" class="editbilladdress"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="editRemoveAddress(this)" title="Delete"></i><div type="button" class="accordion button-bottom active"><h4>Address 1</h4></div><div id="editbilladdress' + val + '" class="panel" style="display: block;"><div class="form-group has-feedback"><label for="edit_address' + val + '" class="form_label">Address</label><textarea class="form-control edit_address" required="" placeholder="Address" style="resize:none;" rows="3" name="edit_address' + val + '" cols="50" id="edit_address' + val + '"></textarea><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_pincode' + val + '" class="form_label">Pincode</label><input class="form-control input-sm edit_pincode" placeholder="Pincode" required="" data-minlength="6" maxlength="6" onkeypress="return isNumber(event)" name="edit_pincode' + val + '" value="" id="edit_pincode' + val + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_country' + val + '" class="form_label">Country</label><input class="form-control input-sm edit_country" placeholder="Country" required="" name="edit_country' + val + '" value="" id="edit_country' + val + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_telephone' + val + '" class="form_label">Phone</label><div class="input-group"><span class="input-group-addon input-sm " id="basic-addon1">+91</span><input class="form-control input-sm edit_telephone" placeholder="Phone" required="" data-minlength="10" maxlength="10" onkeypress="return isNumber(event)" name="edit_telephone' + val + '" value="" id="edit_telephone' + val + '" type="text"></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_fax' + val + '" class="form_label">Fax</label><input class="form-control input-sm edit_fax" placeholder="Fax" onkeypress="return isNumber(event)" name="edit_fax' + val + '" value="" id="edit_fax' + val + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_address_email' + val + '" class="form_label">Email</label><div class="input-group"><span class="input-group-addon input-sm" id="editbasic-addon1">@</span><input class="form-control input-sm edit_address_email" placeholder="Email" required="" name="edit_address_email' + val + '" value="" id="edit_address_email' + val + '" type="email"></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input edit_default_address" type="checkbox" value="" name="default1" id="edit_default_address' + val + '"> Make as default billing address</label></div><div class="form-check"><label class="form-check-label"><input class="form-check-input edit_shipping_address" type="checkbox" value="" name="defaultShip1" id="edit_shipping_address' + val + '"> Make as default shipping address</label></div></div></div>')
         $('#edit_customer_form').validator('update');
         editAutopopulateCountry(val);
         
         $(rem).attr("data-id", val);

         /** [ Create dynamic add address button ] */
         var j = 1;
         $('#editbilladdress .editbilladdress').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Address " + j +"</h4>");
             a.push($(this).attr('id'));
             j++;
         })

         /** [ Make only 1 default bill address, shipping address selected ] */
         var b = a.join(',');
         $('#hidden').val(b);
         $(':checkbox').on('change',function(){
           var th = $(this), name = th.prop('name');
           if(th.is(':checked')){
               $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
            }
          });
       } catch (e) {
           console.log("Error:"+e)
      }
   }

 /**
  * To remove contact address while editing
  *
  * @author <praveen>
  */
   function editRemoveAddress(rem) {
    try{
         var a = []
         var al = $(rem).parent().attr('id');
         $(rem).parent().remove();
         var i = 1;
         $('#editbilladdress .editbilladdress').each(function(key, val) {
             $(this).find(".button-bottom").html("<h4>Address " + i +"</h4>");
             a.push($(this).attr('id'));
             i++;
         })
         var b = a.join(',');
         $('#hidden').val(b);
         $('#edit_customer_form').validator('update');
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
          if($('#edit_freight option:selected').text() === "Nominated Forwarded")
          {
            var forwarder = $("#edit_forwarder option:selected").val();
          }
          else
          {
            var forwarder = '';
          }
          var id                  =  $('#customer_edit_id').val();
          var factory_code        =  $('#edit_factory_code').val();
          var company_name        =  $('#edit_company_name').val();
          var courier_choice      =  $('#edit_courier_choice').val();
          var tin_number          =  $('#edit_tin_number').val();
          var gstin_number        =  $('#edit_gstin_number').val();
          var pan_number          =  $('#edit_pan_number').val();
          var login_email         =  $('#edit_login_email').val();
          var password            =  $('#edit_password').val();
          var csrf_token          =  $('meta[name="csrf-token"]').attr('content');
          var pi_required         =  $('#edit_pi_required').is(':checked')?1:0;
          var payment_term        =  $("#edit_payment_terms option:selected").val();
          var place_of_supply     =  $("#edit_place_of_supply option:selected").val();
          var po_required         =  $('#edit_po_required').is(':checked')?1:0;
          var delivery_date_req   =  $('#edit_delivery_date_required').is(':checked')?1:0;
          var freight             =  $("#edit_freight option:selected").val();
          var day_count           =  $('#edit_day_count').val();
          var short_name           =  $('#edit_short_name').val();

          var contact_person  = [];
          var contact_phone   = [];
          var contact_email   = [];
          var default_contact = [];

          var address         = [];
          var country         = [];
          var telephone       = [];
          var fax             = [];
          var address_email   = [];
          var billing_address = [];
          var shipping_address = [];
          var buyers          = [];
          var pincode         = [];
          /** [ Create array of contact person for multiple contact details ] */
          $('.edit_contact_person').each(function(i, item)
          {
              contact_person[i]    = $(item).val();
              
          });

          /** [ Create array of contact phone for multiple contact details ] */
          $('.edit_contact_phone').each(function(i, item)
          {
              contact_phone[i]    = $(item).val();
              
          });

          /** [ Create array of contact email for multiple contact details ] */
          $('.edit_contact_email').each(function(i, item)
          {
              contact_email[i]    = $(item).val();
              
          });

          /** [ Create array of default contact  or not (checkbox values) ] */
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

          /** [ Create array of multiple address for address ] */
          $('.edit_address').each(function(i, item)
          {
              address[i]    = $(item).val();
              
          });

          /** [ Create array of multiple country for address ] */
          $('.edit_country').each(function(i, item)
          {
              country[i]    = $(item).val();
              
          });

          /** [ Create array of multiple telephone for address ] */  
          $('.edit_telephone').each(function(i, item)
          {
              telephone[i]    = $(item).val();
              
          });

          /** [ Create array of multiple fax for address ] */
          $('.edit_fax').each(function(i, item)
          {
              fax[i]    = $(item).val();
              
          });

          /** [ Create array of multiple email for address ] */
          $('.edit_address_email').each(function(i, item)
          {
              address_email[i]    = $(item).val();
              
          });

          /** [ Create array of billing address or not (checkbox values) ] */
          $('.edit_default_address').each(function(i, item)
          {
            if ($(this).is(':checked'))
              {
                billing_address[i]    = 1;
              }
              else
              {
                billing_address[i]    = 0;
              }
              
          });

          /** [ Create array of shipping address or not (checkbox values) ] */
          $('.edit_shipping_address').each(function(i, item)
          {
            if ($(this).is(':checked'))
              {
                shipping_address[i]    = 1;
              }
              else
              {
                shipping_address[i]    = 0;
              }
              
          });

          /** [ Create array of multiple pincode for address ] */
          $('.edit_pincode').each(function(i, item)
          {
              pincode[i]    = $(item).val();
              
          });

          /** [ Create array of multi selected buyers ] */
          $.each($("#edit_buyer option:selected"), function(){
            buyers.push($(this).val());
          });

          /** [ Joining array values using some delimiters ] */          
          contact_person   = contact_person.join(',');
          contact_phone    = contact_phone.join(',');
          contact_email    = contact_email.join(',');
          default_contact  = default_contact.join(',');
          address          = address.join('|');
          country          = country.join(',');
          telephone        = telephone.join(',');
          fax              = fax.join(',');
          address_email    = address_email.join(',');
          billing_address  = billing_address.join(',');
          shipping_address = shipping_address.join(',');
          buyer            = buyers.join(',');
          pincode          = pincode.join(',');

          $.ajax({
              url: base_url + "/admin/customer-management/update-customer",
              method: 'PUT',
              data: {_token:csrf_token,factory_code:factory_code,company_name:company_name,contact_person:contact_person,contact_phone:contact_phone,contact_email:contact_email,courier_choice:courier_choice,tin_number:tin_number,gstin_number:gstin_number,pan_number:pan_number,login_email:login_email,address:address,country:country,telephone:telephone,fax:fax,address_email:address_email,default_contact:default_contact,billing_address:billing_address,id:id,buyer:buyer,p:password,pi_required:pi_required,shipping_address:shipping_address,payment_term:payment_term,place_of_supply:place_of_supply,po_required:po_required,freight:freight,forwarder:forwarder,day_count:day_count,delivery_date_req:delivery_date_req,short_name:short_name,pincode:pincode},
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
                        customersListView();
                   },
                   409: function() {
                        $('#customer_upadte_result').removeAttr('style');
                        $('#customer_upadte_result').html('Email already registered.')
                        $("#customer_upadte_result").addClass("alert alert-warning");
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
