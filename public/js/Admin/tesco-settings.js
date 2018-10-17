/* Settings script 
 * @author <Sumanth>
 */

var isSubmitting = false;
/**  
 * Validation for numeric values *
 * @param    evt 
 * @return {Boolean}
 * @author <Sumanth>
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
 * Validation for numeric values with dot*
 * @param  evt
 * @return {Boolean}
 * @author <Sumanth>
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
       if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
       {
           return false;
       }
       return true;
    } catch (e) {
     console.log("Error:"+e)
   }
  }

/**
  * Function to Tesco Settings home page
  * @author <Sumanth>
  */
function tescoBrands() {
  try{
    $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.tesco-sidebar').show();
    $('.orchestra-sidebar').hide();
    
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/tesco/settings/brands",
        type: "POST",
        dataType: "json",
        data: {
            _token: csrf_token
        },
        tryCount : 0,
        retryLimit : 2,
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
                var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoBrandsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Brand</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Brands</h3></div><table id="tesco-brands-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Brand Name</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
	             $.each(data.tesco_brands, function(i, item){
	              var index = i+1;
	              tesco_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.brand+'</td>';
                 if(item.is_enabled === 1)
                 {
                  tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
                 }
                 else
                 {
                  tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                 }
                 if(item.is_enabled === 1)
                 {
                  tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_brand_disabled" data-placement="left"></i></a>';
                 }
                 else
                 {
                  tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_brand_enabled" data-placement="left"></i></a>';
                 }


                tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-brands filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
	             });
	             tesco_metadata += '</tbody></table></div>';
	              $('.content').html(tesco_metadata)
                $('.edit-tesco-brands').tooltip('show')
                $('.edit-tesco-brands').tooltip('toggle')
                $('.status').tooltip('show')
                $('.status').tooltip('toggle')
	              $('#tesco-brands-list-table').DataTable();
	              $("#tesco-brands-list-table_filter input").addClass('form-control input-sm');
	              $("#tesco-brands-list-table_filter input").attr('placeholder', 'Data Search');
	              $('#tesco-brands-list-table_length select').addClass('form-control input-sm');

        },
        error: function(errorXHR, errorStatus, errorThrown) {
          this.tryCount++;
          if (this.tryCount <= this.retryLimit) {
              $.ajax(this);
              return;
          }
        },
        complete: function () {
            $(".loader").hide();
        }
    })
    } catch (e) {
     console.log("Error:"+e)
   }
}

/**
  * [showSnackbar function]
  * @author    <Sumanth>
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
  * Adds a tesco brands.
  * @author <Sumanth>
  */
 function addTescoBrands()
 {
    try{
         var name       = $('#brand_name').val();
         var csrf_token = $('meta[name="csrf-token"]').attr('content');

         $.ajax({
             url: base_url + "/admin/tesco/add-tesco-brands",
             method: 'POST',
             data: {_token:csrf_token,name:name},
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Brand Added Successfully");
                       showSnackbar();
                       $('#addTescoBrandsModal').modal('hide');
                       tescoBrands()
                  },
                  208: function() {
                       $('#tesco_brand_result').removeAttr('style');
                       $('#tesco_brand_result').html('Brand already exists.')
                       $("#tesco_brand_result").addClass("alert alert-warning");
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
                       $(".loader").hide();
             }
         })
     } catch (e) {
     console.log("Error:"+e)
   }
  }

  /**
   * Function for get tesco brands details for editing
   * @param id
   * @author <Sumanth>
   *
   */
  function getTescoBrandById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/tesco/get-tesco-brands-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_tesco_brands_id').val(id);
                 $('#edit_brand_name').val(data.tesco_brands[0].brand);
                 $('#editTescoBrandsModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_tesco_brands_form').validator('validate');
                 $('#editTescoBrandsModal').modal('show');
             },
             error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
             },
             complete: function () {
                 $(".loader").hide();
             }
         })
   } catch (e) {
     console.log("Error:"+e)
   }
  }

  /**
  * Function for edit tesco brands details
  * @author <Sumanth>
  */
 function editTescoBrands()
 {
  try{
       var id         = $('#edit_tesco_brands_id').val();
       var name       = $('#edit_brand_name').val();
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/tesco/update-tesco-brands",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
           },
           method:"put",
           data:{_token:csrf_token,id:id,name:name},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Brand Updated Successfully");
                       showSnackbar();
                       $('#editTescoBrandsModal').modal('hide');
                       tescoBrands()
                  },
                208: function() {
                     $('#edit_tesco_brand_result').removeAttr('style');
                     $('#edit_tesco_brand_result').html('Brand already exists.')
                     $("#edit_tesco_brand_result").addClass("alert alert-warning");
                },
           },
           error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
            },
           complete: function () {
                     $(".loader").hide();
           }
       })
   } catch (e) {
     console.log("Error:"+e)
   }
 }

  /**
  * Makes a Brand Enabled.
  * @param id,csrf_token
  * @author <Sumanth>
  */
  function makeBrandEnabled(id,csrf_token)
  {
   try{
         if(isSubmitting) 
         {
             return;
         }
         isSubmitting = true;
         $.ajax({
              url:base_url + "/admin/tesco/make-brand-enabled",
              dataType:"json",
              beforeSend: function () {
                $(".loader").show();
              },
              method:"put",
              data:{_token:csrf_token,id:id},
              tryCount : 0,
              retryLimit : 2,
              statusCode: {
                   /** [Show response status based on HTTP status code] */
                   200: function() {
                       isSubmitting = false; 
                       $('.snackbar_span').html('Brand is enabled ');
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
                tescoBrands();
                $(".loader").hide();
              }
          })
   } catch (e) {
    console.log("Error:"+e)
   }
  }

/**
 * Makes a Brand Diabled
 * @param id,csrf_token
 * @author <Sumanth>
 */
 function makeBrandDisabled(id,csrf_token)
  {
   try{
         if(isSubmitting) 
         {
             return;
         }
         isSubmitting = true;
         $.ajax({
              url:base_url + "/admin/tesco/make-brand-disabled",
              dataType:"json",
              beforeSend: function () {
                $(".loader").show();
              },
              method:"put",
              data:{_token:csrf_token,id:id},
              tryCount : 0,
              retryLimit : 2,
              statusCode: {
                   /** [Show response status based on HTTP status code] */
                   200: function() {
                        isSubmitting = false;
                        $('.snackbar_span').html('Brand is disabled ');
                        showSnackbar();
                   },
                  208: function() {
                       isSubmitting = false;
                       $('.snackbar_span').html("This action can't be completed because brand is in use.")
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
                tescoBrands();
                $(".loader").hide();
              }
          })
   } catch (e) {
    console.log("Error:"+e)
   }
  }

  /**
  * Function to Tesco Department home page
  * @author <Sumanth>
  */
function tescoDepartment() {
    try{
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.tesco-sidebar').show();
      $('.orchestra-sidebar').hide();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/tesco/settings/departments",
          type: "POST",
          dataType: "json",
          data: {
              _token: csrf_token
          },
          tryCount : 0,
          retryLimit : 2,
          beforeSend: function () {
              $(".loader").show();
          },
          success: function (data) {
                  var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoDepartmentsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Department</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Departments</h3></div><table id="tesco-departments-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Department Name</th><th>Pantone</th><th>RBG</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.tesco_department, function(i, item){
                    var index = i+1;
                    var pantone = item.pantone === null?'-':item.pantone;
                    var rbg = item.rbg === null?'-':item.rbg;
                    tesco_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.department+'</td><td>'+pantone+'</td><td>'+rbg+'</td>';
                    if(item.is_enabled === 1)
                    {
                     tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
                    }
                    else
                    {
                     tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                    }
                    if(item.is_enabled === 1)
                    {
                     tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_department_disabled" data-placement="left"></i></a>';
                    }
                    else
                    {
                     tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_department_enabled" data-placement="left"></i></a>';
                    }


                    tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-departments filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                   });
                   tesco_metadata += '</tbody></table></div>';
                   $('.content').html(tesco_metadata)
                   $('.edit-tesco-departments').tooltip('show')
                   $('.edit-tesco-departments').tooltip('toggle')
                   $('.status').tooltip('show')
                   $('.status').tooltip('toggle')
                   $('#tesco-departments-list-table').DataTable();
                   $("#tesco-departments-list-table_filter input").addClass('form-control input-sm');
                   $("#tesco-departments-list-table_filter input").attr('placeholder', 'Data Search');
                   $('#tesco-departments-list-table_length select').addClass('form-control input-sm');
  
          },
          error: function(errorXHR, errorStatus, errorThrown) {
            this.tryCount++;
            if (this.tryCount <= this.retryLimit) {
                $.ajax(this);
                return;
            }
          },
          complete: function () {
              $(".loader").hide();
          }
      })
      } catch (e) {
       console.log("Error:"+e)
     }
}

/**
  * Adds a tesco Department.
  * @author <Sumanth>
  */
  function addTescoDepartments()
  {
     try{
          var department       = $('#department_name').val();
          var pantone       = $('#pantone').val();
          var rbg = $('#rbg').val();
          var csrf_token = $('meta[name="csrf-token"]').attr('content');
 
          $.ajax({
              url: base_url + "/admin/tesco/add-tesco-departments",
              method: 'POST',
              data: {_token:csrf_token,department:department,pantone:pantone,rbg:rbg},
              tryCount : 0,
              retryLimit : 2,
              beforeSend: function () {
                $(".loader").show();
              },
              statusCode: {
                   200: function() {
                        $('.snackbar_span').html("Department Added Successfully");
                        showSnackbar();
                        $('#addTescoDepartmentsModal').modal('hide');
                        tescoDepartment()
                   },
                   208: function() {
                        $('#tesco_department_result').removeAttr('style');
                        $('#tesco_department_result').html('Department already exists.')
                        $("#tesco_department_result").addClass("alert alert-warning");
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
                        $(".loader").hide();
              }
          })
      } catch (e) {
      console.log("Error:"+e)
    }
   }

/**
   * Function for get tesco department details for editing
   * @param id
   * @author <Sumanth>
   *
   */
  function getTescoDepartmentsById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/tesco/get-tesco-departments-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_tesco_departments_id').val(id);
                 $('#edit_department_name').val(data.tesco_departments[0].department);
                 $('#edit_pantone_name').val(data.tesco_departments[0].pantone);
                 $('#edit_rbg_name').val(data.tesco_departments[0].rbg);

                 $('#editTescoDepartmentsModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_tesco_departments_form').validator('validate');
                 $('#editTescoDepartmentsModal').modal('show');
             },
             error: function(errorXHR, errorStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
              },
             complete: function () {
                 $(".loader").hide();
             }
         })
   } catch (e) {
     console.log("Error:"+e)
   }
  }

  /**
  * Function for edit tesco department details
  * @author <Sumanth>
  */
 function editTescoDepartments()
 {
  try{
       var id         = $('#edit_tesco_departments_id').val();
       var department       = $('#edit_department_name').val();
       var pantone = $('#edit_pantone_name').val();
       var rbg = $('#edit_rbg_name').val();
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/tesco/update-tesco-departments",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
           },
           method:"put",
           data:{_token:csrf_token,id:id,department:department,pantone:pantone,rbg:rbg},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Department Updated Successfully");
                       showSnackbar();
                       $('#editTescoDepartmentsModal').modal('hide');
                       tescoDepartment()
                  },
                208: function() {
                     $('#edit_tesco_department_result').removeAttr('style');
                     $('#edit_tesco_department_result').html('Department already exists.')
                     $("#edit_tesco_department_result").addClass("alert alert-warning");
                },
           },
           error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
            },
           complete: function () {
                     $(".loader").hide();
           }
       })
   } catch (e) {
     console.log("Error:"+e)
   }
 }
   /**
   * Makes a Department Enabled.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeDepartmentEnabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/tesco/make-departments-enabled",
               dataType:"json",
               beforeSend: function () {
                 $(".loader").show();
               },
               method:"put",
               data:{_token:csrf_token,id:id},
               tryCount : 0,
               retryLimit : 2,
               statusCode: {
                    /** [Show response status based on HTTP status code] */
                    200: function() {
                        isSubmitting = false; 
                        $('.snackbar_span').html('Department is enabled ');
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
                 tescoDepartment();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }

 /**
  * Makes a Department Diabled
  * @param id,csrf_token
  * @author <praveen>
  */
  function makeDepartmentDisabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/tesco/make-departments-disabled",
               dataType:"json",
               beforeSend: function () {
                 $(".loader").show();
               },
               method:"put",
               data:{_token:csrf_token,id:id},
               tryCount : 0,
               retryLimit : 2,
               statusCode: {
                    /** [Show response status based on HTTP status code] */
                    200: function() {
                         isSubmitting = false;
                         $('.snackbar_span').html('Department is disabled ');
                         showSnackbar();
                    },
                    208: function() {
                        isSubmitting = false;
                         $('.snackbar_span').html("This action can't be completed because department is in use.")
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
                 tescoDepartment();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }
  /**
  * Function to Tesco Supplier Settings home page
  * @author <praveen>
  */
function tescoSuppliers() {
    try{
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.tesco-sidebar').show();
      $('.orchestra-sidebar').hide();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/tesco/settings/suppliers",
          type: "POST",
          dataType: "json",
          data: {
              _token: csrf_token
          },
          tryCount : 0,
          retryLimit : 2,
          beforeSend: function () {
              $(".loader").show();
          },
          success: function (data) {
                  var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoSuppliersModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Customer</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Customers</h3></div><table id="tesco-suppliers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Customer Name</th><th>Customer Code</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.tesco_suppliers, function(i, item){
                    var index = i+1;
                    tesco_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.supplier_name+'</td><td>'+item.supplier_code+'</td>';
                  if(item.is_enabled === 1)
                     {
                      tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
                     }
                     else
                     {
                      tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                     }
                     if(item.is_enabled === 1)
                     {
                      tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_supplier_disabled" data-placement="left"></i></a>';
                     }
                     else
                     {
                      tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_supplier_enabled" data-placement="left"></i></a>';
                     }
  
  
                  tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-suppliers filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                   });
                   tesco_metadata += '</tbody></table></div>';
                   $('.content').html(tesco_metadata)
                   $('.edit-tesco-suppliers').tooltip('show')
                   $('.edit-tesco-suppliers').tooltip('toggle')
                   $('.status').tooltip('show')
                   $('.status').tooltip('toggle')
                   $('#tesco-suppliers-list-table').DataTable();
                   $("#tesco-suppliers-list-table_filter input").addClass('form-control input-sm');
                   $("#tesco-suppliers-list-table_filter input").attr('placeholder', 'Data Search');
                   $('#tesco-suppliers-list-table_length select').addClass('form-control input-sm');
  
          },
          error: function(errorXHR, errorStatus, errorThrown) {
            this.tryCount++;
            if (this.tryCount <= this.retryLimit) {
                $.ajax(this);
                return;
            }
          },
          complete: function () {
              $(".loader").hide();
          }
      })
      } catch (e) {
       console.log("Error:"+e)
     }
  }

  /**
  * Adds a tesco suppliers.
  * @author <Sumanth>
  */
  function addTescoSuppliers()
  {
     try{
          var supplier_name      = $('#supplier_name').val();
          var supplier_code       = $('#supplier_code').val();
          var csrf_token = $('meta[name="csrf-token"]').attr('content');
 
          $.ajax({
              url: base_url + "/admin/tesco/add-tesco-suppliers",
              method: 'POST',
              data: {_token:csrf_token,supplier_name:supplier_name,supplier_code:supplier_code},
              tryCount : 0,
              retryLimit : 2,
              beforeSend: function () {
                $(".loader").show();
              },
              statusCode: {
                   200: function() {
                        $('.snackbar_span').html("Customer Added Successfully");
                        showSnackbar();
                        $('#addTescoSuppliersModal').modal('hide');
                        tescoSuppliers()
                   },
                   208: function() {
                        $('#supplier_result').removeAttr('style');
                        $('#supplier_result').html('Customer already exists.')
                        $("#supplier_result").addClass("alert alert-warning");
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
                        $(".loader").hide();
              }
          })
      } catch (e) {
      console.log("Error:"+e)
    }
   }

   /**
   * Function for get tesco suppliers details for editing
   * @param id
   * @author <Sumanth>
   *
   */
  function getTescoSuppliersById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/tesco/get-tesco-suppliers-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_tesco_suppliers_id').val(id);
                 $('#edit_supplier_name').val(data.tesco_suppliers[0].supplier_name);
                 $('#edit_supplier_code').val(data.tesco_suppliers[0].supplier_code);
        
                 $('#editTescoSuppliersModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_tesco_suppliers_form').validator('validate');
                 $('#editTescoSuppliersModal').modal('show');
             },
             error: function(errorXHR, errorStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
              },
             complete: function () {
                 $(".loader").hide();
             }
         })
   } catch (e) {
     console.log("Error:"+e)
   }
  }

  /**
  * Function for edit tesco department details
  * @author <Sumanth>
  */
 function editTescoSuppliers()
 {
  try{
       var id         = $('#edit_tesco_suppliers_id').val();
       var supplier_name       = $('#edit_supplier_name').val();
       var supplier_code = $('#edit_supplier_code').val();

       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/tesco/update-tesco-suppliers",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
           },
           method:"put",
           data:{_token:csrf_token,id:id,supplier_name:supplier_name,supplier_code:supplier_code},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Customer Updated Successfully");
                       showSnackbar();
                       $('#editTescoSuppliersModal').modal('hide');
                       tescoSuppliers()
                  },
                  208: function() {
                       $('#edit_supplier_result').removeAttr('style');
                       $('#edit_supplier_result').html('Customer already exists.')
                       $("#edit_supplier_result").addClass("alert alert-warning");
                  },
           },
           error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
            },
           complete: function () {
               $(".loader").hide();
           }
       })
   } catch (e) {
     console.log("Error:"+e)
   }
 }

  /**
  * Makes a Supplier Enabled.
  * @param id,csrf_token
  * @author <Sumanth>
  */
  function makeSupplierEnabled(id,csrf_token)
  {
   try{
         if(isSubmitting) 
         {
             return;
         }
         isSubmitting = true;
         $.ajax({
              url:base_url + "/admin/tesco/make-supplier-enabled",
              dataType:"json",
              beforeSend: function () {
                $(".loader").show();
              },
              method:"put",
              data:{_token:csrf_token,id:id},
              tryCount : 0,
              retryLimit : 2,
              statusCode: {
                   /** [Show response status based on HTTP status code] */
                   200: function() {
                       isSubmitting = false; 
                       $('.snackbar_span').html('Customer is enabled ');
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
                tescoSuppliers();
                $(".loader").hide();
              }
          })
   } catch (e) {
    console.log("Error:"+e)
   }
  }

/**
 * Makes a Supplier Diabled
 * @param id,csrf_token
 * @author <Sumanth>
 */
 function makeSupplierDisabled(id,csrf_token)
  {
   try{
         if(isSubmitting) 
         {
             return;
         }
         isSubmitting = true;
         $.ajax({
              url:base_url + "/admin/tesco/make-supplier-disabled",
              dataType:"json",
              beforeSend: function () {
                $(".loader").show();
              },
              method:"put",
              data:{_token:csrf_token,id:id},
              tryCount : 0,
              retryLimit : 2,
              statusCode: {
                   /** [Show response status based on HTTP status code] */
                   200: function() {
                        isSubmitting = false;
                        $('.snackbar_span').html('Customer is disabled ');
                        showSnackbar();
                   },
                   208: function() {
                        isSubmitting = false;
                        $('.snackbar_span').html("This action can't be completed because customer is linked to some company.");
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
                tescoSuppliers();
                $(".loader").hide();
              }
          })
   } catch (e) {
    console.log("Error:"+e)
   }
  }

/**
  * Function to Tesco Size Chart Settings home page
  * @author <praveen>
  */
function tescoSizeChart() {
    try{
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.tesco-sidebar').show();
      $('.orchestra-sidebar').hide();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/tesco/settings/sizechart",
          type: "POST",
          dataType: "json",
          data: {
              _token: csrf_token
          },
          tryCount : 0,
          retryLimit : 2,
          beforeSend: function () {
              $(".loader").show();
          },
          success: function (data) {
                  var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoSizeChartsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add SizeChart</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">SizeCharts</h3></div><table id="tesco-sizecharts-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>PO Size</th><th>UK</th><th>CE</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.tesco_sizecharts, function(i, item){
                    var index = i+1;
                    tesco_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.po_size+'</td><td>'+item.uk+'<td>'+item.ce+'</td>';
                    if(item.is_enabled === 1)
                    {
                     tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
                    }
                    else
                    {
                     tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                    }
                    if(item.is_enabled === 1)
                    {
                     tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_sizechart_disabled" data-placement="left"></i></a>';
                    }
                    else
                    {
                     tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_sizechart_enabled" data-placement="left"></i></a>';
                    }
  
  
                   tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-sizecharts filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                   });
                   tesco_metadata += '</tbody></table></div>';
                   $('.content').html(tesco_metadata)
                   $('.edit-tesco-sizecharts').tooltip('show')
                   $('.edit-tesco-sizecharts').tooltip('toggle')
                   $('.status').tooltip('show')
                   $('.status').tooltip('toggle')
                   $('#tesco-sizecharts-list-table').DataTable();
                   $("#tesco-sizecharts-list-table_filter input").addClass('form-control input-sm');
                   $("#tesco-sizecharts-list-table_filter input").attr('placeholder', 'Data Search');
                   $('#tesco-sizecharts-list-table_length select').addClass('form-control input-sm');
  
          },
          error: function(errorXHR, errorStatus, errorThrown) {
            this.tryCount++;
            if (this.tryCount <= this.retryLimit) {
                $.ajax(this);
                return;
            }
          },
          complete: function () {
              $(".loader").hide();
          }
      })
      } catch (e) {
       console.log("Error:"+e)
     }
  }

/**
  * Adds a tesco sizechart.
  * @author <praveen>
  */
  function addTescoSizeCharts()
  {
     try{
          var po_size      = $('#po_size').val();
          var uk       = $('#uk').val();
          var ce = $('#ce').val();
          var csrf_token = $('meta[name="csrf-token"]').attr('content');
 
          $.ajax({
              url: base_url + "/admin/tesco/add-tesco-size-charts",
              method: 'POST',
              data: {_token:csrf_token,po_size:po_size,uk:uk,ce:ce},
              tryCount : 0,
              retryLimit : 2,
              beforeSend: function () {
                $(".loader").show();
              },
              statusCode: {
                   200: function() {
                        $('.snackbar_span').html("Size Chart Added Successfully");
                        showSnackbar();
                        $('#addTescoSizeChartsModal').modal('hide');
                        tescoSizeChart()
                   },
                   208: function() {
                        $('#tesco_sizechart_result').removeAttr('style');
                        $('#tesco_sizechart_result').html('Size Chart already exists.')
                        $("#tesco_sizechart_result").addClass("alert alert-warning");
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
                        $(".loader").hide();
              }
          })
      } catch (e) {
      console.log("Error:"+e)
    }
   }

 /**
   * Function for get tesco Size Chart details for editing
   * @param id
   * @author <praveen>
   *
   */
  function getTescoSizeChartsById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/tesco/get-tesco-Sizecharts-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_tesco_size_charts_id').val(id);
                 $('#edit_po_size').val(data.tesco_sizecharts[0].po_size);
                 $('#edit_uk').val(data.tesco_sizecharts[0].uk);
                 $('#edit_ce').val(data.tesco_sizecharts[0].ce);
        
                 $('#editTescoSizeChartsModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_tesco_size_charts_form').validator('validate');
                 $('#editTescoSizeChartsModal').modal('show');
             },
             error: function(errorXHR, errorStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
              },
             complete: function () {
                 $(".loader").hide();
             }
         })
   } catch (e) {
     console.log("Error:"+e)
   }
  }
  /**
  * Function for edit tesco size chart details
  * @author <praveen>
  */
 function editTescoSizeChart()
 {
  try{
       var id         = $('#edit_tesco_size_charts_id').val();
       var po_size       = $('#edit_po_size').val();
       var uk = $('#edit_uk').val();
       var ce = $('#edit_ce').val();

       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/tesco/update-tesco-sizecharts",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
           },
           method:"put",
           data:{_token:csrf_token,id:id,po_size:po_size,uk:uk,ce:ce},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Size Chart Updated Successfully");
                       showSnackbar();
                       $('#editTescoSizeChartsModal').modal('hide');
                       tescoSizeChart()
                },
                208: function() {
                     $('#edit_tesco_sizechart_result').removeAttr('style');
                     $('#edit_tesco_sizechart_result').html('Size Chart already exists.')
                     $("#edit_tesco_sizechart_result").addClass("alert alert-warning");
                },
           },
           error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
            },
           complete: function () {
                     $(".loader").hide();
           }
       })
   } catch (e) {
     console.log("Error:"+e)
   }
 }
   /**
   * Makes a SizeChart Enabled.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeSizeChartEnabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/tesco/make-sizechart-enabled",
               dataType:"json",
               beforeSend: function () {
                 $(".loader").show();
               },
               method:"put",
               data:{_token:csrf_token,id:id},
               tryCount : 0,
               retryLimit : 2,
               statusCode: {
                    /** [Show response status based on HTTP status code] */
                    200: function() {
                        isSubmitting = false; 
                        $('.snackbar_span').html('Size Chart is enabled ');
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
                 tescoSizeChart();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }

 /**
  * Makes a SizeChart Diabled
  * @param id,csrf_token
  * @author <praveen>
  */
  function makeSizeChartDisabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/tesco/make-sizechart-disabled",
               dataType:"json",
               beforeSend: function () {
                 $(".loader").show();
               },
               method:"put",
               data:{_token:csrf_token,id:id},
               tryCount : 0,
               retryLimit : 2,
               statusCode: {
                    /** [Show response status based on HTTP status code] */
                    200: function() {
                         isSubmitting = false;
                         $('.snackbar_span').html('Size Chart is disabled ');
                         showSnackbar();
                    },
                    208: function() {
                         isSubmitting = false;
                         $('.snackbar_span').html("This action can't be completed because size chart is in use.")
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
                 tescoSizeChart();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }

/**
  * Function to Tesco Tax code Settings home page
  * @author <praveen>
  */
function tescoTaxCode() {
    try{
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.tesco-sidebar').show();
      $('.orchestra-sidebar').hide();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/tesco/settings/tax-code",
          type: "POST",
          dataType: "json",
          data: {
              _token: csrf_token
          },
          tryCount : 0,
          retryLimit : 2,
          beforeSend: function () {
              $(".loader").show();
          },
          success: function (data) {
                  var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoTaxCodesModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Tax Code</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Tax Codes</h3></div><table id="tesco-taxcodes-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Tax Type</th><th>Tax (%)</th><th>Tax Code</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.tesco_taxcodes, function(i, item){
                    var index = i+1;
                    tesco_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.tax_type+'</td><td>'+item.tax+'</td><td>'+item.tax_code+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-tesco-taxcodes filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                   });
                   tesco_metadata += '</tbody></table></div>';
                   $('.content').html(tesco_metadata)
                   $('.edit-tesco-taxcodes').tooltip('show')
                   $('.edit-tesco-taxcodes').tooltip('toggle')
                   $('.status').tooltip('show')
                   $('.status').tooltip('toggle')
                   $('#tesco-taxcodes-list-table').DataTable();
                   $("#tesco-taxcodes-list-table_filter input").addClass('form-control input-sm');
                   $("#tesco-taxcodes-list-table_filter input").attr('placeholder', 'Data Search');
                   $('#tesco-taxcodes-list-table_length select').addClass('form-control input-sm');
  
          },
          error: function(errorXHR, errorStatus, errorThrown) {
            this.tryCount++;
            if (this.tryCount <= this.retryLimit) {
                $.ajax(this);
                return;
            }
          },
          complete: function () {
              $(".loader").hide();
          }
      })
      } catch (e) {
       console.log("Error:"+e)
     }
  }

/**
  * Adds a tesco taxcode.
  * @author <praveen>
  */
  function addTescoTaxCodes()
  {
     try{
          var tax_type   = $('#tax_type').val();
          var tax_code   = $('#tax_code').val();
          var tax        = $('#tax').val();
          var csrf_token = $('meta[name="csrf-token"]').attr('content');
 
          $.ajax({
              url: base_url + "/admin/tesco/add-tesco-tax-code",
              method: 'POST',
              data: {_token:csrf_token,tax_type:tax_type,tax_code:tax_code,tax:tax},
              tryCount : 0,
              retryLimit : 2,
              beforeSend: function () {
                $(".loader").show();
              },
              statusCode: {
                   200: function() {
                        $('.snackbar_span').html("Tax Code Added Successfully");
                        showSnackbar();
                        $('#addTescoTaxCodesModal').modal('hide');
                        tescoTaxCode()
                   },
                   208: function() {
                        $('#tesco_taxcode_result').removeAttr('style');
                        $('#tesco_taxcode_result').html('Tax Code already exists.')
                        $("#tesco_taxcode_result").addClass("alert alert-warning");
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
                        $(".loader").hide();
              }
          })
      } catch (e) {
      console.log("Error:"+e)
    }
   }

/**
   * Function for get tesco tax code details for editing
   * @param id
   * @author <praveen>
   *
   */
  function getTescoTaxCodesById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/tesco/get-tesco-tax-code-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_tesco_tax_code_id').val(id);
                 $('#edit_tax_code').val(data.tesco_tax_codes[0].tax_code);
                 $('#edit_tax_type').val(data.tesco_tax_codes[0].tax_type);
                 $('#edit_tax').val(data.tesco_tax_codes[0].tax);
        
                 $('#editTescoTaxCodesModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_tesco_tax_codes_form').validator('validate');
                 $('#editTescoTaxCodesModal').modal('show');
             },
             error: function(errorXHR, errorStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
              },
             complete: function () {
                 $(".loader").hide();
             }
         })
   } catch (e) {
     console.log("Error:"+e)
   }
  }

/**
  * Function for edit tesco tax code details
  * @author <praveen>
  */
 function editTescoTaxCode()
 {
  try{
       var id        = $('#edit_tesco_tax_code_id').val();
       var tax_type  = $('#edit_tax_type').val();
       var tax_code  = $('#edit_tax_code').val();
       var tax       = $('#edit_tax').val();

       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/tesco/update-tesco-tax-code",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
           },
           method:"put",
           data:{_token:csrf_token,id:id,tax_type:tax_type,tax_code:tax_code,tax:tax},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Tax Code Updated Successfully");
                       showSnackbar();
                       $('#editTescoTaxCodesModal').modal('hide');
                       tescoTaxCode()
                },
                208: function() {
                     $('#edit_tesco_taxcode_result').removeAttr('style');
                     $('#edit_tesco_taxcode_result').html('Tax Code already exists.')
                     $("#edit_tesco_taxcode_result").addClass("alert alert-warning");
                },
           },
           error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
            },
           complete: function () {
                     $(".loader").hide();
           }
       })
   } catch (e) {
     console.log("Error:"+e)
   }
 }
 /**
  * Function to Tesco material Settings home page
  * @author <praveen>
  */
function tescoMaterialMasters() {
    try{
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.tesco-sidebar').show();
      $('.orchestra-sidebar').hide();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/tesco/settings/material",
          type: "POST",
          dataType: "json",
          data: {
              _token: csrf_token
          },
          tryCount : 0,
          retryLimit : 2,
          beforeSend: function () {
              $(".loader").show();
          },
          success: function (data) {
                  var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoMaterialModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Tax Code</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Materials</h3></div><table id="tesco-materials-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Material Name For Employee</th><th>Material Name For Production</th><th>Material Name For Purchase</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.tesco_materials, function(i, item){
                    var index = i+1;
                    tesco_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.material_name_employee+'</td><td>'+item.material_name_production+'</td><td>'+item.material_name_purchase+'</td>';
                    if(item.is_enabled === true)
                    {
                     tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
                    }
                    else
                    {
                     tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                    }
                    if(item.is_enabled === true)
                    {
                     tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_material_disabled" data-placement="left"></i></a>';
                    }
                    else
                    {
                     tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_material_enabled" data-placement="left"></i></a>';
                    }

                    tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-material filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                   });
                   tesco_metadata += '</tbody></table></div>';
                   $('.content').html(tesco_metadata)
                   $('.edit-tesco-material').tooltip('show')
                   $('.edit-tesco-material').tooltip('toggle')
                   $('.status').tooltip('show')
                   $('.status').tooltip('toggle')
                   $('#tesco-materials-list-table').DataTable();
                   $("#tesco-materials-list-table_filter input").addClass('form-control input-sm');
                   $("#tesco-materials-list-table_filter input").attr('placeholder', 'Data Search');
                   $('#tesco-materials-list-table_length select').addClass('form-control input-sm');
  
          },
          error: function(errorXHR, errorStatus, errorThrown) {
            this.tryCount++;
            if (this.tryCount <= this.retryLimit) {
                $.ajax(this);
                return;
            }
          },
          complete: function () {
              $(".loader").hide();
          }
      })
      } catch (e) {
       console.log("Error:"+e)
     }
  }

/**
  * Adds a tesco material.
  * @author <praveen>
  */
  function addTescoMaterial()
  {
     try{
          var emp_material        = $('#material_name_employee').val();
          var production_material = $('#material_name_production').val();
          var purchase_material   = $('#material_name_purchase').val();
          var csrf_token          = $('meta[name="csrf-token"]').attr('content');
 
          $.ajax({
              url: base_url + "/admin/tesco/add-tesco-material",
              method: 'POST',
              data: {_token:csrf_token,purchase_material:purchase_material,emp_material:emp_material,production_material:production_material},
              tryCount : 0,
              retryLimit : 2,
              beforeSend: function () {
                $(".loader").show();
              },
              statusCode: {
                   200: function() {
                        $('.snackbar_span').html("Material Added Successfully");
                        showSnackbar();
                        $('#addTescoMaterialModal').modal('hide');
                        tescoMaterialMasters()
                   },
                   208: function() {
                        $('#tesco_material_result').removeAttr('style');
                        $('#tesco_material_result').html('Material already exists.')
                        $("#tesco_material_result").addClass("alert alert-warning");
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
                 $(".loader").hide();
              }
          })
      } catch (e) {
      console.log("Error:"+e)
    }
   }

/**
   * Function for get tesco material details for editing
   * @param id
   * @author <praveen>
   *
   */
  function getTescoMaterialById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/tesco/get-tesco-material-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_tesco_material_id').val(id);
                 $('#edit_material_name_employee').val(data.tesco_material[0].material_name_employee);
                 $('#edit_material_name_production').val(data.tesco_material[0].material_name_production);
                 $('#edit_material_name_purchase').val(data.tesco_material[0].material_name_purchase);
        
                 $('#editTescoMaterialModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_tesco_material_form').validator('validate');
                 $('#editTescoMaterialModal').modal('show');
             },
             error: function(errorXHR, errorStatus, errorThrown) {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
              },
             complete: function () {
                 $(".loader").hide();
             }
         })
   } catch (e) {
     console.log("Error:"+e)
   }
  }

/**
  * Function for edit material code details
  * @author <praveen>
  */
 function editTescoMaterial()
 {
  try{
       var id                  = $('#edit_tesco_material_id').val();
       var emp_material        = $('#edit_material_name_employee').val();
       var production_material = $('#edit_material_name_production').val();
       var purchase_material   = $('#edit_material_name_purchase').val();
       var csrf_token          = $('meta[name="csrf-token"]').attr('content');

       $.ajax({
           url:base_url + "/admin/tesco/update-tesco-material",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
           },
           method:"put",
           data: {_token:csrf_token,purchase_material:purchase_material,emp_material:emp_material,production_material:production_material,id:id},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Material Updated Successfully");
                       showSnackbar();
                       $('#editTescoMaterialModal').modal('hide');
                       tescoMaterialMasters()
                },
                208: function() {
                     $('#edit_tesco_material_result').removeAttr('style');
                     $('#edit_tesco_material_result').html('Material already exists.')
                     $("#edit_tesco_material_result").addClass("alert alert-warning");
                },
           },
           error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
            },
           complete: function () {
                     $(".loader").hide();
           }
       })
   } catch (e) {
     console.log("Error:"+e)
   }
 }
 /**
   * Makes a Material Enabled.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeMaterialEnabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/tesco/make-material-enabled",
               dataType:"json",
               beforeSend: function () {
                 $(".loader").show();
               },
               method:"put",
               data:{_token:csrf_token,id:id},
               tryCount : 0,
               retryLimit : 2,
               statusCode: {
                    /** [Show response status based on HTTP status code] */
                    200: function() {
                        isSubmitting = false; 
                        $('.snackbar_span').html('Material is enabled ');
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
                 tescoMaterialMasters();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }

 /**
  * Makes a Material Diabled
  * @param id,csrf_token
  * @author <praveen>
  */
  function makeMaterialDisabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/tesco/make-material-disabled",
               dataType:"json",
               beforeSend: function () {
                 $(".loader").show();
               },
               method:"put",
               data:{_token:csrf_token,id:id},
               tryCount : 0,
               retryLimit : 2,
               statusCode: {
                    /** [Show response status based on HTTP status code] */
                    200: function() {
                         isSubmitting = false;
                         $('.snackbar_span').html('Material is disabled ');
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
                 tescoMaterialMasters();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }


   
   