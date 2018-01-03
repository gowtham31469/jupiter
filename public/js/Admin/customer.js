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
          $('.main-content').html('<div class="loader"></div>');
          $('#edit_buyer').selectpicker('refresh');
          $('#buyer').selectpicker('refresh');
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
                var table = '<div class="col" id="customer-table"><table id="customers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Customer Name</th><th>Factory Code</th><th>Default Contact</th><th>Courier Choice</th><th>Account Number</th><th>TIN Number</th><th>GSTIN Number</th><th>PAN Number</th><th>Billing Address</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                $.each(data.customers, function(i, item){
                 var index = i+1;
                 table += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.company_name+'</td><td>'+item.factory_code+'</td><td><dl><dt>Contact Person:</dt><dd>'+item.contact_person+'</dd><dt>Contact Email:</dt><dd>'+item.contact_email+'</dd><dt>Contact Phone:</dt><dd>'+item.phone_no+'</dd></dl></td><td>'+item.courier_choice+'</td><td>'+item.account_no+'</td><td>'+item.tin_number+'</td><td>'+item.gstin_number+'</td><td>'+item.pan_number+'</td><td><dl><dt>Address:</dt><dd>'+item.address+','+item.country+'</dd><dt>Email:</dt><dd>'+item.email+'</dd><dt>Phone:</dt><dd>'+item.telephone+'</dd><dt>Fax:</dt><dd>'+item.fax+'</dd></dl></td>';
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
 * Adds a customer.
 * @author <praveen>
 */
function addCustomer()
{
  try{
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
        var pi_required     =  $('#pi_required').is(':checked')?1:0;

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

        var buyers = [];

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
        $.each($("#buyer option:selected"), function(){
          buyers.push($(this).val());
        });
        console.log(buyers)          
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
        buyer            = buyers.join(',');

        $.ajax({
            url: base_url + "/admin/customer-management/add-customer",
            method: 'POST',
            data: {_token:csrf_token,factory_code:factory_code,company_name:company_name,contact_person:contact_person,contact_phone:contact_phone,contact_email:contact_email,account_no:account_no,courier_choice:courier_choice,tin_number:tin_number,gstin_number:gstin_number,pan_number:pan_number,login_email:login_email,p:password,address:address,country:country,telephone:telephone,fax:fax,address_email:address_email,default_contact:default_contact,default_address:default_address,buyer:buyer,pi_required:pi_required},
            beforeSend: function () {
              $(".loader").show();
              $(".loader").css('margin-top','12%');
            },
            statusCode: {
                 200: function() {
                      $('.snackbar_span').html('Customer Added Successfully');
                      $('#snackbar').css('background-color','#1abb9c');
                      showSnackbar();
                      $('#addCustomerModal').modal('hide');
                      customersListView();
                 },
                 400: function() {
                      $('.snackbar_span').html('Something went wrong');
                      $('#snackbar').css('background-color','#F44336');
                      showSnackbar();
                 },
                 409: function() {
                      $('#customer_result').removeAttr('style');
                      $('#customer_result').html('Email already registered.')
                      $("#customer_result").addClass("alert alert-warning");
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
 /**  Validation for mobile number **/
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
    
/** Dynamically add/remove contact person & Billing Address in Customer Management jquery begins**/
 /**
  * Adds a contact person.
  * @author <praveen>
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
       } catch (e) {
       console.log("Error:"+e)
     }
   }
 /**
  * Removes a  contact person.
  *
  * @author <praveen>
  */
   function removeContact(rem) {
    try{
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
      } catch (e) {
       console.log("Error:"+e)
     }
   }
 /**
  * Adds an contact address.
  * @author <praveen>
  */
   function addAddress(rem,e) {
    try{
         e.preventDefault();
         var a = []
         var id = $(rem).attr("data-id");
         var val = parseInt(id) + 1;
         $('#addbilladdress').append('<div id="billaddress_' + val + '" class="addbilladdress"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="removeAddress(this)" title="Delete"></i><div type="button" class="accordion button-bottom active"><h4>Billing Address 1</h4></div><div id="billaddress' + val + '" class="panel" style="display: block;"><div class="form-group has-feedback"><label for="address' + val + '" class="form_label">Address</label><textarea class="form-control  address" required="" placeholder="Address" style="resize:none;" rows="3" name="address' + val + '" cols="50" id="address' + val + '"></textarea><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="country' + val + '" class="form_label">Country</label><input class="form-control input-sm country" placeholder="Country" required="" name="country' + val + '" value="" id="country' + val + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="telephone' + val + '" class="form_label">Phone</label><div class="input-group"><span class="input-group-addon input-sm " id="basic-addon1">+91</span><input class="form-control input-sm telephone" placeholder="Phone" required="" data-minlength="10" maxlength="10" onkeypress="return isNumber(event)" name="telephone' + val + '" value="" id="telephone' + val + '" type="text"></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="fax' + val + '" class="form_label">Fax</label><input class="form-control input-sm fax" placeholder="Fax" required="" onkeypress="return isNumber(event)" name="fax' + val + '" value="" id="fax' + val + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="address_email' + val + '" class="form_label">Email</label><div class="input-group"><span class="input-group-addon input-sm" id="basic-addon1">@</span><input class="form-control input-sm address_email" placeholder="Email" required="" name="address_email' + val + '" value="" id="address_email' + val + '" type="email"></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input default_address" type="checkbox" value="" name="default1" id="default_address' + val + '"> Make as default billing address</label></div></div></div>')
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
       } catch (e) {
     console.log("Error:"+e)
   }
   }
 /**
  * Removes an contact address.
  *
  * @author <praveen>
  */
   function removeAddress(rem) {
    try{
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
       } catch (e) {
      console.log("Error:"+e)
    }
   }
 /** Dynamically add/remove contact person & Billing Address in Customer Management jquery ends**/
 /**
  * To auto populate country
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
  * Edit auto-autopopulate country(In customer form)
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
              var value = $("#editcountry" + id).getSelectedItemData().code;
              $("#editcountry" + id).val(value).trigger("change");
            }
       }
      };
    $("#editcountry" + id).easyAutocomplete(options);
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
                 $(".loader").css('margin-top','12%');
               },
               method:"put",
               data:{_token:csrf_token,id:id},
               statusCode: {
                    200: function() {
                        isSubmitting = false; 
                        $('.snackbar_span').html('Customer is active now ');
                        $('#snackbar').css('background-color','#1abb9c');
                        showSnackbar();
                    }
               },
               error: function (data) {
                  console.log(data);
               },
               complete: function () {
                 customersListView();
                 $(".loader").hide();
                 $(".loader").css('margin-top','0%');
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
                 $(".loader").css('margin-top','12%');
               },
               method:"put",
               data:{_token:csrf_token,id:id},
               statusCode: {
                    200: function() {
                         isSubmitting = false;
                         $('.snackbar_span').html('Customer is inactive now ');
                         $('#snackbar').css('background-color','#1abb9c');
                         showSnackbar();
                    }
               },
               error: function (data) {
                  console.log(data);
               },
               complete: function () {
                 customersListView();
                 $(".loader").hide();
                 $(".loader").css('margin-top','0%');
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
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             success: function(data){
                 isSubmitting = false;
                 $('#customer_edit_id').val(id);
                 
                  $('#edit_factory_code').val(data.customers[0].factory_code);
                  $('#edit_company_name').val(data.customers[0].company_name);
                  $('#edit_courier_choice').val(data.customers[0].courier_choice);
                  $('#edit_account_no').val(data.customers[0].account_no);
                  $('#edit_tin_number').val(data.customers[0].tin_number);
                  $('#edit_gstin_number').val(data.customers[0].gstin_number);
                  $('#edit_pan_number').val(data.customers[0].pan_number);
                  $('#edit_login_email').val(data.customers[0].email);
                  if(data.customers[0].pi_required === 1)
                  {
                    $('#edit_pi_required').prop('checked', true);
                  }
                  else
                  {
                    $('#edit_pi_required').prop('checked', false);
                  }
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
                 

                 $.each(data.address,function(key,value){
                   key += 1;
                    var address_html = '<div edit="editbilladdress_' + key + '" class="editbilladdress">';
                    if(key != 1)
                    {
                      address_html += '<i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="editRemoveAddress(this)" title="Delete"></i>';
                    }
                    address_html += '<div type="button" class="accordion button-bottom active"><h4>Billing Address 1</h4></div><div id="editbilladdress' + key + '" class="panel" style="display: block;"><div class="form-group has-feedback"><label for="edit_address' + key + '" class="form_label">Address</label><textarea class="form-control edit_address" required="" placeholder="Address" style="resize:none;" rows="3" name="edit_address' + key + '" cols="50" id="edit_address' + key + '">'+value.address+'</textarea><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_country' + key + '" class="form_label">Country</label><input class="form-control input-sm edit_country" placeholder="Country" required="" name="edit_country' + key + '" value="'+value.country+'" id="edit_country' + key + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_telephone' + key + '" class="form_label">Phone</label><div class="input-group"><span class="input-group-addon input-sm " id="editbasic-addon1">+91</span><input class="form-control input-sm edit_telephone" placeholder="Phone" required="" data-minlength="10" maxlength="10" onkeypress="return isNumber(event)" name="edit_telephone' + key + '" value="'+value.telephone+'" id="edit_telephone' + key + '" type="text"></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_fax' + key + '" class="form_label">Fax</label><input class="form-control input-sm edit_fax" placeholder="Fax" required="" onkeypress="return isNumber(event)" name="edit_fax' + key + '" value="'+value.fax+'" id="edit_fax' + key + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_address_email' + key + '" class="form_label">Email</label><div class="input-group"><span class="input-group-addon input-sm" id="edit-basic-addon1">@</span><input class="form-control input-sm edit_address_email" placeholder="Email" required="" name="edit_address_email' + key + '" value="'+value.email+'" id="edit_address_email' + key + '" type="email"></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input edit_default_address" type="checkbox" value="" name="default1" id="edit_default_address' + key + '"> Make as default billing address</label></div></div></div>';
                      
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
                      $.each(data.buyers, function (key, item) {
                       $('#edit_buyer option[value=' + item.buyer_id + ']').prop('selected', true);
                       $('#edit_buyer').selectpicker('refresh');
                      });

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
                 
                 $.each(data.customers, function (key, value) {
                  $('#edit_factory_code').val(value.factory_code);
                  $('#edit_company_name').val(value.company_name);
                  $('#edit_courier_choice').val(value.courier_choice);
                  $('#edit_account_no').val(value.account_no);
                  $('#edit_tin_number').val(value.tin_number);
                  $('#edit_gstin_number').val(value.gstin_number);
                  $('#edit_pan_number').val(value.pan_number);
                  $('#edit_login_email').val(value.email);
                 });});

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
             error: function (data) {
                console.log(data);
             },
             complete: function () {
              $('#edit_customer_form').validator('validate');
               $(".loader").hide();
               $(".loader").css('margin-top','0%');
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
         $('#editbilladdress').append('<div id="editbilladdress_' + val + '" class="editbilladdress"><i class="ion-trash-a delete-user edit-delete" style="position: absolute;float: right;right: 16px;padding: 11px 15px;cursor:pointer" onclick="editRemoveAddress(this)" title="Delete"></i><div type="button" class="accordion button-bottom active"><h4>Billing Address 1</h4></div><div id="editbilladdress' + val + '" class="panel" style="display: block;"><div class="form-group has-feedback"><label for="address' + val + '" class="form_label">Address</label><textarea class="form-control  address" required="" placeholder="Address" style="resize:none;" rows="3" name="editaddress' + val + '" cols="50" id="editaddress' + val + '"></textarea><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="editcountry' + val + '" class="form_label">Country</label><input class="form-control input-sm country" placeholder="Country" required="" name="editcountry' + val + '" value="" id="editcountry' + val + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edittelephone' + val + '" class="form_label">Phone</label><div class="input-group"><span class="input-group-addon input-sm " id="basic-addon1">+91</span><input class="form-control input-sm telephone" placeholder="Phone" required="" data-minlength="10" maxlength="10" onkeypress="return isNumber(event)" name="edittelephone' + val + '" value="" id="edittelephone' + val + '" type="text"></div><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="editfax' + val + '" class="form_label">Fax</label><input class="form-control input-sm fax" placeholder="Fax" required="" onkeypress="return isNumber(event)" name="editfax' + val + '" value="" id="editfax' + val + '" type="text"><div class="help-block with-errors"></div></div><div class="form-group has-feedback"><label for="edit_address_email' + val + '" class="form_label">Email</label><div class="input-group"><span class="input-group-addon input-sm" id="editbasic-addon1">@</span><input class="form-control input-sm address_email" placeholder="Email" required="" name="address_email' + val + '" value="" id="edit_address_email' + val + '" type="email"></div><div class="help-block with-errors"></div></div><div class="form-check"><label class="form-check-label"><input class="form-check-input default_address" type="checkbox" value="" name="default1" id="edit_default_address' + val + '"> Make as default billing address</label></div></div></div>')
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
             $(this).find(".button-bottom").html("<h4>Billing Address " + i +"</h4>");
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
          var id              =  $('#customer_edit_id').val();
          var factory_code    =  $('#edit_factory_code').val();
          var company_name    =  $('#edit_company_name').val();
          var courier_choice  =  $('#edit_courier_choice').val();
          var account_no      =  $('#edit_account_no').val();
          var tin_number      =  $('#edit_tin_number').val();
          var gstin_number    =  $('#edit_gstin_number').val();
          var pan_number      =  $('#edit_pan_number').val();
          var login_email     =  $('#edit_login_email').val();
          var password        =  $('#edit_password').val();
          var csrf_token      =  $('meta[name="csrf-token"]').attr('content');
          var pi_required     =  $('#edit_pi_required').is(':checked')?1:0;

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

          var buyers          = [];

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
          $.each($("#edit_buyer option:selected"), function(){
            buyers.push($(this).val());
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
          buyer            = buyers.join(',');

          $.ajax({
              url: base_url + "/admin/customer-management/update-customer",
              method: 'PUT',
              data: {_token:csrf_token,factory_code:factory_code,company_name:company_name,contact_person:contact_person,contact_phone:contact_phone,contact_email:contact_email,account_no:account_no,courier_choice:courier_choice,tin_number:tin_number,gstin_number:gstin_number,pan_number:pan_number,login_email:login_email,address:address,country:country,telephone:telephone,fax:fax,address_email:address_email,default_contact:default_contact,default_address:default_address,id:id,buyer:buyer,p:password,pi_required:pi_required},
              beforeSend: function () {
                $(".loader").show();
                $(".loader").css('margin-top','12%');
              },
              statusCode: {
                   200: function() {
                        $('.snackbar_span').html('Customer Updated Successfully');
                        $('#snackbar').css('background-color','#1abb9c');
                        showSnackbar();
                        $('#editCustomerModal').modal('hide');
                        customersListView();
                   },
                   400: function() {
                        $('.snackbar_span').html('Something went wrong');
                        $('#snackbar').css('background-color','#F44336');
                        showSnackbar();
                   },
                   409: function() {
                        $('#customer_upadte_result').removeAttr('style');
                        $('#customer_upadte_result').html('Email already registered.')
                        $("#customer_upadte_result").addClass("alert alert-warning");
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
   * To delete customer
   * @param id
   * @author    <praveen>
   */
   function deleteCustomer(id)
   {
    try{
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
                       $('.snackbar_span').html('Customer deleted Successfully');
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#deleteCustomerModal').modal('hide');
                       customersListView();
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
