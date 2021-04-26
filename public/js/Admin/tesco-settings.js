/* Settings script 
 * @author <Sumanth>
 */

var isSubmitting = false;

/**
  * Function to Tesco Settings home page
  * @author <Sumanth>
  */
function tescoBrands() {
  try{
    $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.tesco-sidebar').show();
    $('.orchestra-sidebar').hide();
    
    
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
                var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoBrandsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Brand</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Brands</h3></div><table id="tesco-brands-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Brand Name</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
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
  * Adds a tesco brands.
  * @author <Sumanth>
  */
 function addTescoBrands()
 {
    try{
         var name       = $('#brand_name').val();
         

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
                  var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoDepartmentsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Department</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Departments</h3></div><table id="tesco-departments-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Department Name</th><th>Pantone</th><th>RBG</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
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
                  var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoSuppliersModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Customer</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Customers</h3></div><table id="tesco-suppliers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Customer Name</th><th>Customer Code</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
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
                  var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoSizeChartsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add SizeChart</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">SizeCharts</h3></div><table id="tesco-sizecharts-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>PO Size</th><th>UK</th><th>CE</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
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
  * Function to Tesco/Orchestra Tax code Settings home page
  * @author <praveen>
  */
function taxCode() {
    try{
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.tesco-sidebar').show();
      $('.orchestra-sidebar').hide();
      
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
                  var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTaxCodesModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Tax Code</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Tax Codes</h3></div><table id="taxcodes-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Tax Type</th><th>Tax (%)</th><th>Tax Code</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.taxcodes, function(i, item){
                    var index = i+1;
                    tesco_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.tax_type+'</td><td>'+item.tax+'</td><td>'+item.tax_code+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-taxcodes filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                   });
                   tesco_metadata += '</tbody></table></div>';
                   $('.content').html(tesco_metadata)
                   $('.edit-taxcodes').tooltip('show')
                   $('.edit-taxcodes').tooltip('toggle')
                   $('.status').tooltip('show')
                   $('.status').tooltip('toggle')
                   $('#taxcodes-list-table').DataTable();
                   $("#taxcodes-list-table_filter input").addClass('form-control input-sm');
                   $("#taxcodes-list-table_filter input").attr('placeholder', 'Data Search');
                   $('#taxcodes-list-table_length select').addClass('form-control input-sm');
  
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
  * Adds a Tesco/Orchestra taxcode.
  * @author <praveen>
  */
  function addTaxCodes()
  {
     try{
          var tax_type   = $('#tax_type').val();
          var tax_code   = $('#tax_code').val();
          var tax        = $('#tax').val();
          
 
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
                        $('#addTaxCodesModal').modal('hide');
                        taxCode()
                   },
                   208: function() {
                        $('#taxcode_result').removeAttr('style');
                        $('#taxcode_result').html('Tax Code already exists.')
                        $("#taxcode_result").addClass("alert alert-warning");
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
   * Function for get Tesco/Orchestra tax code details for editing
   * @param id
   * @author <praveen>
   *
   */
  function getTaxCodesById(id)
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
                 $('#edit_tax_code_id').val(id);
                 $('#edit_tax_code').val(data.tax_codes[0].tax_code);
                 $('#edit_tax_type').val(data.tax_codes[0].tax_type);
                 $('#edit_tax').val(data.tax_codes[0].tax);
        
                 $('#editTaxCodesModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_tax_codes_form').validator('validate');
                 $('#editTaxCodesModal').modal('show');
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
  * Function for edit Tesco/Orchestra tax code details
  * @author <praveen>
  */
 function editTaxCode()
 {
  try{
       var id        = $('#edit_tax_code_id').val();
       var tax_type  = $('#edit_tax_type').val();
       var tax_code  = $('#edit_tax_code').val();
       var tax       = $('#edit_tax').val();

       
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
                       $('#editTaxCodesModal').modal('hide');
                       taxCode()
                },
                208: function() {
                     $('#edit_taxcode_result').removeAttr('style');
                     $('#edit_taxcode_result').html('Tax Code already exists.')
                     $("#edit_taxcode_result").addClass("alert alert-warning");
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
                  var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoMaterialModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Material</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Material Master</h3></div><table id="tesco-materials-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Material Name For Employee</th><th>Material Name For Production</th><th>Material Name For Purchase</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.raw_materials, function(i, item){
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
 
          $.ajax({
              url: base_url + "/admin/tesco/add-raw-material",
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
                        $('#tesco_material_result').html('This material names already exists.')
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
             url:base_url + "/admin/tesco/get-raw-material-details",
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
                 $('#edit_material_name_employee').val(data.raw_material[0].material_name_employee);
                 $('#edit_material_name_production').val(data.raw_material[0].material_name_production);
                 $('#edit_material_name_purchase').val(data.raw_material[0].material_name_purchase);
        
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

       $.ajax({
           url:base_url + "/admin/tesco/update-raw-material",
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
                     $('#edit_tesco_material_result').html('This material names already exists.')
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
                    },
                    208: function() {
                        isSubmitting = false;
                        $('.snackbar_span').html("This action can't be completed because material is in use.")
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
  * Function to Tesco error reporting email addess list home page
  * @author <praveen>
  */
function tescoErrorReportEmail() {
    try{
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.tesco-sidebar').show();
      $('.orchestra-sidebar').hide();
      
      $.ajax({
          url: base_url + "/admin/tesco/settings/error-report-emails",
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
                  var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoErrorReportEmailModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Error Report Email</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Error Report Emails</h3></div><table id="tesco-error-report-emails-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Error Case</th><th>Contact Person Name</th><th>Contact Email </th><th>Company</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.error_report_data, function(i, item){
                    var index = i+1;
                    tesco_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.error_case+'</td><td>'+item.contact_person+'</td><td>'+item.email_address+'</td><td>'+item.company+'</td>';
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
                     tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_error_report_email_disabled" data-placement="left"></i></a>';
                    }
                    else
                    {
                     tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_error_report_email_enabled" data-placement="left"></i></a>';
                    }

                    tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-error-report-email filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                   });
                   tesco_metadata += '</tbody></table></div>';
                   $('.content').html(tesco_metadata)
                   $('.edit-tesco-error-report-email').tooltip('show')
                   $('.edit-tesco-error-report-email').tooltip('toggle')
                   $('.status').tooltip('show')
                   $('.status').tooltip('toggle')
                   $('#tesco-error-report-emails-list-table').DataTable();
                   $("#tesco-error-report-emails-list-table_filter input").addClass('form-control input-sm');
                   $("#tesco-error-report-emails-list-table_filter input").attr('placeholder', 'Data Search');
                   $('#tesco-error-report-emails-list-table_length select').addClass('form-control input-sm');
  
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
  * Adds a tesco error report email.
  * @author <praveen>
  */
  function addTescoErrorReportEmail()
  {
     try{
          var company        = $('#company option:selected').val();
          var error_case     = $('#error_case option:selected').text();
          var contact_person = $('#contact_person').val();
          var email          = $('#error_email').val();
 
          $.ajax({
              url: base_url + "/admin/tesco/add-tesco-error-report-email",
              method: 'POST',
              data: {_token:csrf_token,company:company,error_case:error_case,contact_person:contact_person,email:email},
              tryCount : 0,
              retryLimit : 2,
              beforeSend: function () {
                $(".loader").show();
              },
              statusCode: {
                   200: function() {
                        $('.snackbar_span').html("Email Details Added Successfully");
                        showSnackbar();
                        $('#addTescoErrorReportEmailModal').modal('hide');
                        tescoErrorReportEmail()
                   },
                   208: function() {
                        $('#tesco_error_report_email_result').removeAttr('style');
                        $('#tesco_error_report_email_result').html('This email,error case and email combination already exists.')
                        $("#tesco_error_report_email_result").addClass("alert alert-warning");
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
      * Function for get tesco error report email details for editing
      * @param id
      * @author <praveen>
      *
      */
     function getTescoErrorReportEmailById(id)
     {
       try{
            if(isSubmitting) {
                 return;
             }
             isSubmitting = true;
            $.ajax({
                url:base_url + "/admin/tesco/get-tesco-error-report-email-details",
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
                    $('#edit_tesco_error_report_email_id').val(id);
                    $('#edit_company').val(data.error_report_data[0].company_id);
                    $('#edit_error_case').val(data.error_report_data[0].error_case);
                    $('#edit_contact_person').val(data.error_report_data[0].contact_person);
                    $('#edit_error_email').val(data.error_report_data[0].email_address);
           
                    $('#editTescoErrorReportEmailModal').modal({
                         backdrop: 'static',
                         keyboard: false
                    })
                    $('#edit_tesco_error_report_email_form').validator('validate');
                    $('#editTescoErrorReportEmailModal').modal('show');
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
  * Edit a tesco error report email.
  * @author <praveen>
  */
  function editTescoErrorReportEmail()
  {
     try{
          var id             = $('#edit_tesco_error_report_email_id').val();
          var company        = $('#edit_company option:selected').val();
          var error_case     = $('#edit_error_case option:selected').text();
          var contact_person = $('#edit_contact_person').val();
          var email          = $('#edit_error_email').val();
 
          $.ajax({
              url: base_url + "/admin/tesco/update-tesco-error-report-email",
              method: 'PUT',
              data: {_token:csrf_token,company:company,error_case:error_case,contact_person:contact_person,email:email,id:id},
              tryCount : 0,
              retryLimit : 2,
              beforeSend: function () {
                $(".loader").show();
              },
              statusCode: {
                   200: function() {
                        $('.snackbar_span').html("Email Details Updated Successfully");
                        showSnackbar();
                        $('#editTescoErrorReportEmailModal').modal('hide');
                        tescoErrorReportEmail()
                   },
                   208: function() {
                        $('#edit_tesco_error_report_email_result').removeAttr('style');
                        $('#edit_tesco_error_report_email_result').html('This email,error case and email combination already exists.')
                        $("#edit_tesco_error_report_email_result").addClass("alert alert-warning");
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
   * Makes a Error Report Email Enabled.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeErrorReportEmailEnabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/tesco/make-error-report-email-enabled",
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
                        $('.snackbar_span').html('Email Deatils is enabled ');
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
                 tescoErrorReportEmail();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }

 /**
  * Makes a Error Report Email Diabled
  * @param id,csrf_token
  * @author <praveen>
  */
  function makeErrorReportEmailDisabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/tesco/make-error-report-email-disabled",
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
                         $('.snackbar_span').html('Email Deatils  is disabled ');
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
                 tescoErrorReportEmail();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }

/**
 * Function to tesco Symbols
 */

function tescoSymbols() {
  try{
    $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.orchestra-sidebar').hide();
    $('.tesco-sidebar').show();
    $.ajax({
      url: base_url + "/admin/tesco/settings/symbols",
      type: "POST",
      dataType: "json",
      data: {
          _token: csrf_token
      },
      tryCount: 0,
      retryLimit: 2,
      beforeSend: function() {
          $(".loader").show();
      },
      success: function(data) {
        var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoSymbolsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Symbol</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Symbols</h3></div><table id="tesco-symbols-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>ISO Symbols</th><th>Astm Symbol</th><th>Care Instruction</th><th>Degree</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
        $.each(data.tesco_care_symbols, function(i, item) {
          var index = i + 1;
          tesco_metadata += '<tr data-category="' + item.category_id + '" id="' + item.id + '"><td>' + index + '</td><td class="td_iso_symbols"><img width="30px" src="' + item.iso_symbols + '"></td><td class="td_astms_symbol"><img width="30px" src="' + item.astms_symbol + '"></td><td class="td_care_instruction">' + item.care_instruction + '</td>';
          tesco_metadata += '<td class="td_care_degree">' + item.degree + '</td>';
          if (item.is_enabled) {
            tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
          } else {
            tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
          }
          if (item.is_enabled) {
            tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="disable_tesco_symbol" data-placement="left"></i></a>';
          } else {
            tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="enable_tesco_symbol" data-placement="left"></i></a>';
          }
          tesco_metadata += '</td></tr>';
        });
        tesco_metadata += '</tbody></table></div>';
        $('.content').html(tesco_metadata)
        $('.edit-tesco-symbols').tooltip('show')
        $('.edit-tesco-symbols').tooltip('toggle')
        $('.status').tooltip('show')
        $('.status').tooltip('toggle')
        $('#tesco-symbols-list-table').DataTable();
        $("#tesco-symbols-list-table_filter input").addClass('form-control input-sm');
        $("#tesco-symbols-list-table_filter input").attr('placeholder', 'Data Search');
        $('#tesco-symbols-list-table_length select').addClass('form-control input-sm');
      },
      error: function(errorXHR, errorStatus, errorThrown) {
          this.tryCount++;
          if (this.tryCount <= this.retryLimit) {
              $.ajax(this);
              return;
          }
      },
      complete: function() {
          $(".loader").hide();
      }
    })
  } catch (e) {
    console.log("Error:" + e)
  }
}


/**
 * Function to Tesco Settings Symbols
 */
function addTescoSymbols(iso_symbols, astms_symbol, category_id, care_instruction, care_degree) {
  try {
      
      var form_data = new FormData();

      form_data.append('iso_symbols', iso_symbols);
      form_data.append('astms_symbol', astms_symbol);
      form_data.append('category_id', category_id);
      form_data.append('care_instruction', care_instruction);
      form_data.append('care_degree', care_degree);
      form_data.append('_token', csrf_token);


      $.ajax({
          url: base_url + "/admin/tesco/settings/addTescoSymbols",
          type: "POST",
          data: form_data,
          cache: false,
          contentType: false,
          processData: false,
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          statusCode: {
              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html('Symbols Added Successfully');
                  showSnackbar();
                  $('#addTescoSymbolsModal').modal('hide');
                  tescoSymbols();
              },
              422: function() {
                  $('.hidden-error').html('<div class="alert alert-warning">\
                      Invalid File Format\
                    </div>')
              }
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      });
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Makes a Symbol Enabled.
 * @param id,csrf_token
 * @author <praveen>
 */
function makeSymbolEnabled(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/make-symbol-enabled",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { _token: csrf_token, id: id },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {
              /** [Show response status based on HTTP status code] */
              200: function() {
                  isSubmitting = false;
                  $('.snackbar_span').html('Symbol is enabled ');
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
          complete: function() {
              orchestraSymbols();
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}
   
/**
 * Makes a tesco Symbol Enabled.
 * @param id,csrf_token
 */
function enableTescoSymbol(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/enable-tesco-symbol",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { _token: csrf_token, id: id },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {
              /** [Show response status based on HTTP status code] */
              200: function() {
                  isSubmitting = false;
                  $('.snackbar_span').html('Symbol is enabled ');
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
          complete: function() {
              tescoSymbols();
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}


/**
 * Makes a Symbol Diabled
 * @param id,csrf_token
 */
function disableTescoSymbol(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/disable-tesco-symbol",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { _token: csrf_token, id: id },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {
              /** [Show response status based on HTTP status code] */
              200: function() {
                  isSubmitting = false;
                  $('.snackbar_span').html('Symbol is disabled ');
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
          complete: function() {
              tescoSymbols();
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Function to Tesco countries home page
 */
function tescoCountries() {
  try {
    $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.orchestra-sidebar').hide();
    $('.tesco-sidebar').show();

    $.ajax({
      url: base_url + "/admin/tesco/settings/countries",
      type: "POST",
      dataType: "json",
      data: {
          _token: csrf_token
      },
      tryCount: 0,
      retryLimit: 2,
      beforeSend: function() {
          $(".loader").show();
      },
      success: function(data) {
        var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>'+
        '<div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoCountryModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Countries </a></div></div>'+
        '<div class="template-block"><div class="top-content"><h3 class="content-heading"> Countries </h3></div>'+
        '<table id="tesco-countries-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead>'+
        '<tr><th>Sl. No</th><th>English</th><th>Polish</th><th>Czech</th><th>Slovak</th><th>Hungarian</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
        $.each(data.tesco_countries, function(i, item) {
          var index = i + 1;
          tesco_metadata += '<tr id="' + item.id + '">'+
          '<td>' + index + '</td>'+
          '<td>' + item.english + '</td>'+
          '<td>' + item.polish + '</td>'+
          '<td>' + item.czech + '</td>'+
          '<td>' + item.slovak + '</td>'+
          '<td>' + item.hungarian + '</td>';
          if (item.is_enabled) {
            tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
          } else {
            tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
          }
          if (item.is_enabled) {
            tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="disableTescoCountry" data-placement="left"></i></a>';
          } else {
            tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="enableTescoCountry" data-placement="left"></i></a>';
          }
          tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-country filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
        })
        tesco_metadata += '</tbody></table></div>';
        $('.content').html(tesco_metadata)
        $('#tesco-countries-list-table').DataTable();
        $('.edit-tesco-country').tooltip('show')
        $('.edit-tesco-country').tooltip('toggle')
        $('.status').tooltip('show')
        $('.status').tooltip('toggle')
        $("#tesco-countries-list-table_filter input").addClass('form-control input-sm');
        $("#tesco-countries-list-table_filter input").attr('placeholder', 'Data Search');
        $('#tesco-countries-list-table_length select').addClass('form-control input-sm');
      
      },
      error: function(errorXHR, errorStatus, errorThrown) {
        this.tryCount++;
        if (this.tryCount <= this.retryLimit) {
            $.ajax(this);
            return;
        }
      },
      complete: function() {
        $(".loader").hide();
      }
    })
  }catch(e) {
    console.log("Error:" + e)
  }
}

/**
 * Function to add Tesco countries
 */
function addTescoCountries() {
  var english = $('#english').val();
  var polish = $('#polish').val();
  var czech = $('#czech').val();
  var slovak = $('#slovak').val();
  var hungarian = $('#hungarian').val();

  try {
    $.ajax({
      url: base_url + "/admin/tesco/add-tesco-country",
      method: 'POST',
      data: {
        _token: csrf_token,
        english: english,
        polish: polish,
        czech: czech,
        slovak: slovak,
        hungarian: hungarian,

      },
      tryCount: 0,
      retryLimit: 2,
      beforeSend: function () {
        $(".loader").show();
      },
      statusCode: {

        /** [ Show response status based on HTTP status code ] */
        200: function () {
          $('.snackbar_span').html("Country Name Added Successfully");
          showSnackbar();
          $('#addTescoCountryModal').modal('hide');
          tescoCountries()
        },
        208: function () {
          $('#country_error').removeClass('hidden');
          $('#country_error').removeAttr('style');
          $('#country_error').html('Country Name[English] exists.')
          $("#country_error").addClass("alert alert-warning");
        },
        400: function () {
          $('.snackbar_span').html("Something went wrong!!");
          showSnackbar();
        }
      },
      error: function (errorXHR, errorStatus, errorThrown) {
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
    console.log("Error:" + e)
  }
}

/**
 * Function for get tesco country details for editing
 * @param id
 */
function getTescoCountryById(id) {
  try {
    if (isSubmitting) { return; }

    isSubmitting = true;
    $.ajax({
        url: base_url + "/admin/tesco/get-tesco-country-details",
        dataType: "json",
        method: "get",
        data: { id: id },
        tryCount: 0,
        retryLimit: 2,
        beforeSend: function() {
            $(".loader").show();
        },
        success: function(data) {
            isSubmitting = false;
            $('#edit_tesco_country_id').val(id);
            $('#edit_english').val(data.tesco_countries[0].english);
            $('#edit_polish').val(data.tesco_countries[0].polish);
            $('#edit_czech').val(data.tesco_countries[0].czech);
            $('#edit_slovak').val(data.tesco_countries[0].slovak);
            $('#edit_hungarian').val(data.tesco_countries[0].hungarian);

            $('#editTescoCountryModal').modal({
                backdrop: 'static',
                keyboard: false
            })
            $('#edit_tesco_country_form').validator('validate');
            $('#editTescoCountryModal').modal('show');
        },
        error: function(errorXHR, errorStatus, errorThrown) {
            this.tryCount++;
            if (this.tryCount <= this.retryLimit) {
                $.ajax(this);
                return;
            }
        },
        complete: function() {
            $(".loader").hide();
        }
    })
  } catch (e) {
    console.log("Error:" + e)
  }
}

/**
 * Function for edit tesco country name
 */
function editTescoCountry() {
  try {
      var id = $('#edit_tesco_country_id').val();
      var english = $('#edit_english').val();
      var polish = $('#edit_polish').val();
      var czech = $('#edit_czech').val();
      var slovak = $('#edit_slovak').val();
      var hungarian = $('#edit_hungarian').val();
      
      $.ajax({
          url: base_url + "/admin/tesco/update-tesco-country",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { 
            _token: csrf_token,
            id: id,
            english: english,
            polish: polish,
            czech: czech,
            slovak: slovak,
            hungarian: hungarian
          },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Country Name Updated Successfully");
                  showSnackbar();
                  $('#editTescoCountryModal').modal('hide');
                  tescoCountries()
              },
              208: function() {
                  $('#country_update_error').removeClass('hidden');
                  $('#country_update_error').removeAttr('style');
                  $('#country_update_error').html('Country Name[English] exists.')
                  $("#country_update_error").addClass("alert alert-warning");
              },
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Makes a Country Enabled.
 * @param id,csrf_token
 */
function enableTescoCountry(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/enable-tesco-country",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { _token: csrf_token, id: id },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {
              /** [Show response status based on HTTP status code] */
              200: function() {
                  isSubmitting = false;
                  $('.snackbar_span').html('Country is enabled ');
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
          complete: function() {
              tescoCountries();
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
* Makes a Country Diabled
* @param id,csrf_token
*/
function disableTescoCountry(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/disable-tesco-country",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { _token: csrf_token, id: id },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {
              /** [Show response status based on HTTP status code] */
              200: function() {
                  isSubmitting = false;
                  $('.snackbar_span').html('Country is disabled ');
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
          complete: function() {
              tescoCountries();
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Function to Tesco Components home page
 */
function tescoComponents() {
  try {
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.orchestra-sidebar').hide();
      $('.tesco-sidebar').show();
      
      $.ajax({
          url: base_url + "/admin/tesco/settings/components",
          type: "POST",
          dataType: "json",
          data: {
              _token: csrf_token
          },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          success: function(data) {
            var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>'+
            '<div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescocomponentsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Components </a></div></div>'+
            '<div class="template-block"><div class="top-content"><h3 class="content-heading"> Components </h3></div>'+
            '<table id="tesco-components-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead>'+
            '<tr><th>Sl. No</th><th>English</th><th>Polish</th><th>Czech</th><th>Slovak</th><th>Hungarian</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
            $.each(data.tesco_components, function(i, item) {
              var index = i + 1;
              tesco_metadata += '<tr id="' + item.id + '">'+
              '<td>' + index + '</td>'+
              '<td>' + item.english + '</td>'+
              '<td>' + item.polish + '</td>'+
              '<td>' + item.czech + '</td>'+
              '<td>' + item.slovak + '</td>'+
              '<td>' + item.hungarian + '</td>';
              if (item.is_enabled) {
                tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
              } else {
                tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
              }
              if (item.is_enabled) {
                tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="disableTescoComponents" data-placement="left"></i></a>';
              } else {
                tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="enableTescoComponents" data-placement="left"></i></a>';
              }
              tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-components filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
            })
            tesco_metadata += '</tbody></table></div>';
            $('.content').html(tesco_metadata)
            $('#tesco-components-list-table').DataTable();
            $('.edit-tesco-components').tooltip('show')
            $('.edit-tesco-components').tooltip('toggle')
            $('.status').tooltip('show')
            $('.status').tooltip('toggle')
            $("#tesco-components-list-table_filter input").addClass('form-control input-sm');
            $("#tesco-components-list-table_filter input").attr('placeholder', 'Data Search');
            $('#tesco-components-list-table_length select').addClass('form-control input-sm');
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}


/**
 * Adds tesco components.
 */
function addTescoComponents() {
  try {
    var english = $('#comp_english').val();
    var polish = $('#comp_polish').val();
    var czech = $('#comp_czech').val();
    var slovak = $('#comp_slovak').val();
    var hungarian = $('#comp_hungarian').val();
      

    $.ajax({
          url: base_url + "/admin/tesco/add-tesco-components",
          method: 'POST',
          data: {
            _token: csrf_token,
            english: english,
            polish: polish,
            czech: czech,
            slovak: slovak,
            hungarian: hungarian,
          },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Components Added Successfully");
                  showSnackbar();
                  $('#addTescocomponentsModal').modal('hide');
                  tescoComponents()
              },
              208: function() {
                  $('#components_error').removeClass('hidden');
                  $('#components_error').removeAttr('style');
                  $('#components_error').html('Component[English] already excists.')
                  $("#components_error").addClass("alert alert-warning");
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
          complete: function() {
              $(".loader").hide();
          }
    })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Function for get tesco Components details for editing
 * @param id
 */
function getTescoComponentsById(id) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/get-tesco-components-details",
          dataType: "json",
          method: "get",
          data: { id: id },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          success: function(data) {
              isSubmitting = false;
              $('#edit_tesco_components_id').val(id);
              $('#comp_edit_english').val(data.tesco_components[0].english);
              $('#comp_edit_polish').val(data.tesco_components[0].polish);
              $('#comp_edit_czech').val(data.tesco_components[0].czech);
              $('#comp_edit_slovak').val(data.tesco_components[0].slovak);
              $('#comp_edit_hungarian').val(data.tesco_components[0].hungarian);
              $('#editTescoComponentsModal').modal({
                  backdrop: 'static',
                  keyboard: false
              })
              $('#edit_tesco_components_form').validator('validate');
              $('#editTescoComponentsModal').modal('show');
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}


/**
 * Function for edit Tesco Components instruction
 */
function editTescoComponents() {
  try {
      var id = $('#edit_tesco_components_id').val();
      var english = $('#comp_edit_english').val();
      var polish = $('#comp_edit_polish').val();
      var czech = $('#comp_edit_czech').val();
      var slovak = $('#comp_edit_slovak').val();
      var hungarian = $('#comp_edit_hungarian').val();
      
      $.ajax({
          url: base_url + "/admin/tesco/update-tesco-components",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
              $('#components_update_error').addClass('hidden');
          },
          method: "put",
          data: { 
            _token: csrf_token,
            id : id,
            english: english,
            polish: polish,
            czech: czech,
            slovak: slovak,
            hungarian: hungarian,
          },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Components Updated Successfully");
                  showSnackbar();
                  $('#editTescoComponentsModal').modal('hide');
                  tescoComponents()
              },
              208: function() {
                  $('#components_update_error').removeClass('hidden');
                  $('#components_update_error').removeAttr('style');
                  $('#components_update_error').html('Component[English] already excists.')
                  $("#components_update_error").addClass("alert alert-warning");
              },
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}


/**
 * Makes a Components Enabled.
 * @param id,csrf_token
 */
function enableTescoComponents(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/enable-tesco-components",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { _token: csrf_token, id: id },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {
              /** [Show response status based on HTTP status code] */
              200: function() {
                  isSubmitting = false;
                  $('.snackbar_span').html('Components is enabled ');
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
          complete: function() {
            tescoComponents();
            $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}


/**
 * Makes a Components Diabled
 * @param id,csrf_token
 */
function disableTescoComponents(id, csrf_token) {
    try {
        if (isSubmitting) {
            return;
        }
        isSubmitting = true;
        $.ajax({
            url: base_url + "/admin/tesco/disable-tesco-components",
            dataType: "json",
            beforeSend: function() {
                $(".loader").show();
            },
            method: "put",
            data: { _token: csrf_token, id: id },
            tryCount: 0,
            retryLimit: 2,
            statusCode: {
                /** [Show response status based on HTTP status code] */
                200: function() {
                    isSubmitting = false;
                    $('.snackbar_span').html('Components is disabled ');
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
            complete: function() {
                tescoComponents();
                $(".loader").hide();
            }
        })
    } catch (e) {
        console.log("Error:" + e)
    }
}


/**
 * Function to Tesco Fabric List home page
 */
function tescoFabricList() {
  try {
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.orchestra-sidebar').hide();
      $('.tesco-sidebar').show();
      
      $.ajax({
          url: base_url + "/admin/tesco/settings/fabric-lists",
          type: "POST",
          dataType: "json",
          data: {
              _token: csrf_token
          },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          success: function(data) {
            var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>'+
            '<div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoFabricsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Fabric </a></div></div>'+
            '<div class="template-block"><div class="top-content"><h3 class="content-heading"> Fabrics </h3></div>'+
            '<table id="tesco-fabric-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead>'+
            '<tr><th>Sl. No</th><th>English</th><th>Polish</th><th>Czech</th><th>Slovak</th><th>Hungarian</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
            $.each(data.tesco_fabriclists, function(i, item) {
              var index = i + 1;
              tesco_metadata += '<tr id="' + item.id + '">'+
              '<td>' + index + '</td>'+
              '<td>' + item.english + '</td>'+
              '<td>' + item.polish + '</td>'+
              '<td>' + item.czech + '</td>'+
              '<td>' + item.slovak + '</td>'+
              '<td>' + item.hungarian + '</td>';
              if (item.is_enabled) {
                tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
              } else {
                tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
              }
              if (item.is_enabled) {
                tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="disableTescoFabric" data-placement="left"></i></a>';
              } else {
                tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="enableTescoFabric" data-placement="left"></i></a>';
              }
              tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-fabric filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
            })
            tesco_metadata += '</tbody></table></div>';
            $('.content').html(tesco_metadata)
            $('#tesco-fabric-list-table').DataTable();
            $('.edit-tesco-fabric').tooltip('show')
            $('.edit-tesco-fabric').tooltip('toggle')
            $('.status').tooltip('show')
            $('.status').tooltip('toggle')
            $("#tesco-fabric-list-table_filter input").addClass('form-control input-sm');
            $("#tesco-fabric-list-table_filter input").attr('placeholder', 'Data Search');
            $('#tesco-fabric-list-table_length select').addClass('form-control input-sm');
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Adds tesco fabric.
 */
function addTescoFabric() {
  try {
    var english = $('#fabric_english').val();
    var polish = $('#fabric_polish').val();
    var czech = $('#fabric_czech').val();
    var slovak = $('#fabric_slovak').val();
    var hungarian = $('#fabric_hungarian').val();
      

    $.ajax({
          url: base_url + "/admin/tesco/add-tesco-fabric",
          method: 'POST',
          data: {
            _token: csrf_token,
            english: english,
            polish: polish,
            czech: czech,
            slovak: slovak,
            hungarian: hungarian,
          },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Fabric Added Successfully");
                  showSnackbar();
                  $('#addTescoFabricsModal').modal('hide');
                  tescoFabricList()
              },
              208: function() {
                  $('#fabric_error').removeClass('hidden');
                  $('#fabric_error').removeAttr('style');
                  $('#fabric_error').html('Fabric[English] already excists.')
                  $("#fabric_error").addClass("alert alert-warning");
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
          complete: function() {
              $(".loader").hide();
          }
    })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Function for get tesco Components details for editing
 * @param id
 */
function getTescoFabricById(id) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/get-tesco-fabric-details",
          dataType: "json",
          method: "get",
          data: { id: id },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          success: function(data) {
              isSubmitting = false;
              $('#edit_tesco_fabric_id').val(id);
              $('#edit_fabric_english').val(data.tesco_fabriclists[0].english);
              $('#edit_fabric_polish').val(data.tesco_fabriclists[0].polish);
              $('#edit_fabric_czech').val(data.tesco_fabriclists[0].czech);
              $('#edit_fabric_slovak').val(data.tesco_fabriclists[0].slovak);
              $('#edit_fabric_hungarian').val(data.tesco_fabriclists[0].hungarian);
              $('#editTescoFabricModal').modal({
                  backdrop: 'static',
                  keyboard: false
              })
              $('#edit-tesco-fabric-form').validator('validate');
              $('#editTescoFabricModal').modal('show');
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Function for update Tesco fabric
 */
function updateTescoFabric() {
  try {
      var id = $('#edit_tesco_fabric_id').val();
      var english = $('#edit_fabric_english').val();
      var polish = $('#edit_fabric_polish').val();
      var czech = $('#edit_fabric_czech').val();
      var slovak = $('#edit_fabric_slovak').val();
      var hungarian = $('#edit_fabric_hungarian').val();
      
      $.ajax({
          url: base_url + "/admin/tesco/update-tesco-fabric-details",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
              $('#fabric_update_error').addClass('hidden');
          },
          method: "put",
          data: { 
            _token: csrf_token,
            id : id,
            english: english,
            polish: polish,
            czech: czech,
            slovak: slovak,
            hungarian: hungarian,
          },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Fabric Updated Successfully");
                  showSnackbar();
                  $('#editTescoFabricModal').modal('hide');
                  tescoFabricList()
              },
              208: function() {
                  $('#fabric_update_error').removeClass('hidden');
                  $('#fabric_update_error').removeAttr('style');
                  $('#fabric_update_error').html('Fabric[English] already excists.')
                  $("#fabric_update_error").addClass("alert alert-warning");
              },
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Makes a Fabric Enabled.
 * @param id,csrf_token
 */
function enableTescoFabric(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/enable-tesco-fabric",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { _token: csrf_token, id: id },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {
              /** [Show response status based on HTTP status code] */
              200: function() {
                  isSubmitting = false;
                  $('.snackbar_span').html('Fabric is enabled ');
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
          complete: function() {
            tescoFabricList();
            $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Makes a fabric Diabled
 * @param id,csrf_token
 */
function disableTescoFabric(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/disable-tesco-fabric",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { _token: csrf_token, id: id },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {
              /** [Show response status based on HTTP status code] */
              200: function() {
                  isSubmitting = false;
                  $('.snackbar_span').html('Components is disabled ');
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
          complete: function() {
              tescoFabricList();
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}


/**
 * Function to tescoKidsWarningStmt List home page
 */
function tescoKidsWarningStmt() {
  try {
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.orchestra-sidebar').hide();
      $('.tesco-sidebar').show();
      
      $.ajax({
          url: base_url + "/admin/tesco/settings/get-warn-stmts-lists",
          type: "POST",
          dataType: "json",
          data: {
              _token: csrf_token
          },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          success: function(data) {
            var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>'+
            '<div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoKidsStatementModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Statements </a></div></div>'+
            '<div class="template-block"><div class="top-content"><h3 class="content-heading"> Kids Warning Statements </h3></div>'+
            '<table id="tesco-kids-stmt-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead>'+
            '<tr><th>Sl. No</th><th>English</th><th>Polish</th><th>Czech</th><th>Slovak</th><th>Hungarian</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
            $.each(data.tesco_warninglists, function(i, item) {
              var index = i + 1;
              tesco_metadata += '<tr id="' + item.id + '">'+
              '<td>' + index + '</td>'+
              '<td>' + item.english + '</td>'+
              '<td>' + item.polish + '</td>'+
              '<td>' + item.czech + '</td>'+
              '<td>' + item.slovak + '</td>'+
              '<td>' + item.hungarian + '</td>';
              if (item.is_enabled) {
                tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
              } else {
                tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
              }
              if (item.is_enabled) {
                tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="disableTescoKidsStatement" data-placement="left"></i></a>';
              } else {
                tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="enableTescoKidsStatement" data-placement="left"></i></a>';
              }
              tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-kids-stmt filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
            })
            tesco_metadata += '</tbody></table></div>';
            $('.content').html(tesco_metadata)
            $('#tesco-kids-stmt-list-table').DataTable();
            $('.edit-tesco-kids-stmt').tooltip('show')
            $('.edit-tesco-kids-stmt').tooltip('toggle')
            $('.status').tooltip('show')
            $('.status').tooltip('toggle')
            $("#tesco-kids-stmt-list-table_filter input").addClass('form-control input-sm');
            $("#tesco-kids-stmt-list-table_filter input").attr('placeholder', 'Data Search');
            $('#tesco-kids-stmt-list-table_length select').addClass('form-control input-sm');
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Adds tesco Kids Statement.
 */
function addTescoKidsStatement() {
  try {
    var english = $('#kids_stmt_english').val();
    var polish = $('#kids_stmt_polish').val();
    var czech = $('#kids_stmt_czech').val();
    var slovak = $('#kids_stmt_slovak').val();
    var hungarian = $('#kids_stmt_hungarian').val();
      

    $.ajax({
          url: base_url + "/admin/tesco/add-tesco-warn-stmt",
          method: 'POST',
          data: {
            _token: csrf_token,
            english: english,
            polish: polish,
            czech: czech,
            slovak: slovak,
            hungarian: hungarian,
          },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Statement Added Successfully");
                  showSnackbar();
                  $('#addTescoKidsStatementModal').modal('hide');
                  tescoKidsWarningStmt()
              },
              208: function() {
                  $('#kids_stmt_error').removeClass('hidden');
                  $('#kids_stmt_error').removeAttr('style');
                  $('#kids_stmt_error').html('Statement[English] already excists.')
                  $("#kids_stmt_error").addClass("alert alert-warning");
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
          complete: function() {
              $(".loader").hide();
          }
    })
  } catch (e) {
      console.log("Error:" + e)
  }
}


/**
 * Function for get tesco KidsStmt ById details for editing
 * @param id
 */
function getTescoKidsStmtById(id) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/get-tesco-warn-stmt-details",
          dataType: "json",
          method: "get",
          data: { id: id },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          success: function(data) {
              isSubmitting = false;
              $('#edit_tesco_kids_stmt_id').val(id);
              $('#edit_kids_stmt_english').val(data.tesco_warninglists[0].english);
              $('#edit_kids_stmt_polish').val(data.tesco_warninglists[0].polish);
              $('#edit_kids_stmt_czech').val(data.tesco_warninglists[0].czech);
              $('#edit_kids_stmt_slovak').val(data.tesco_warninglists[0].slovak);
              $('#edit_kids_stmt_hungarian').val(data.tesco_warninglists[0].hungarian);
              $('#editTescoKidsStatementModal').modal({
                  backdrop: 'static',
                  keyboard: false
              })
              $('#edit-tesco-kids-statement-form').validator('validate');
              $('#editTescoKidsStatementModal').modal('show');
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Function for update Tesco fabric
 */
function updateTescoKidsStmt() {
  try {
      var id = $('#edit_tesco_kids_stmt_id').val();
      var english = $('#edit_kids_stmt_english').val();
      var polish = $('#edit_kids_stmt_polish').val();
      var czech = $('#edit_kids_stmt_czech').val();
      var slovak = $('#edit_kids_stmt_slovak').val();
      var hungarian = $('#edit_kids_stmt_hungarian').val();
      
      $.ajax({
          url: base_url + "/admin/tesco/update-tesco-warn-stmt-details",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
              $('#kids_stmt_update_error').addClass('hidden');
          },
          method: "put",
          data: { 
            _token: csrf_token,
            id : id,
            english: english,
            polish: polish,
            czech: czech,
            slovak: slovak,
            hungarian: hungarian,
          },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Statement Updated Successfully");
                  showSnackbar();
                  $('#editTescoKidsStatementModal').modal('hide');
                  tescoKidsWarningStmt()
              },
              208: function() {
                  $('#kids_stmt_update_error').removeClass('hidden');
                  $('#kids_stmt_update_error').removeAttr('style');
                  $('#kids_stmt_update_error').html('Statement [English] already excists.')
                  $("#kids_stmt_update_error").addClass("alert alert-warning");
              },
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Makes a Kids Warning Stmt Enabled.
 * @param id,csrf_token
 */
function enableTescoKidsStatement(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/enable-tesco-warn-stmt",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { _token: csrf_token, id: id },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {
              /** [Show response status based on HTTP status code] */
              200: function() {
                  isSubmitting = false;
                  $('.snackbar_span').html('Statement is enabled ');
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
          complete: function() {
            tescoKidsWarningStmt();
            $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Makes a Kids Warning Stmt Enabled.
 * @param id,csrf_token
 */
function disableTescoKidsStatement(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/disable-tesco-warn-stmt",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { _token: csrf_token, id: id },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {
              /** [Show response status based on HTTP status code] */
              200: function() {
                  isSubmitting = false;
                  $('.snackbar_span').html('Statement is disabled ');
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
          complete: function() {
              tescoKidsWarningStmt();
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}


/**
 * Function to tescoTranslations List home page
 */
 function tescoTranslations() {
  try {
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.orchestra-sidebar').hide();
      $('.tesco-sidebar').show();
      
      $.ajax({
          url: base_url + "/admin/tesco/settings/get-tesco-translation-lists",
          type: "POST",
          dataType: "json",
          data: {
              _token: csrf_token
          },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          success: function(data) {
            var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>'+
            '<div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoTranslationModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Translation </a></div></div>'+
            '<div class="template-block"><div class="top-content"><h3 class="content-heading"> Translations </h3></div>'+
            '<table id="tesco-translations-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead>'+
            '<tr><th>Sl. No</th><th>English</th><th>Polish</th><th>Czech</th><th>Slovak</th><th>Hungarian</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
            $.each(data.tesco_translationlist, function(i, item) {
              var index = i + 1;
              tesco_metadata += '<tr id="' + item.id + '">'+
              '<td>' + index + '</td>'+
              '<td>' + item.english + '</td>'+
              '<td>' + item.polish + '</td>'+
              '<td>' + item.czech + '</td>'+
              '<td>' + item.slovak + '</td>'+
              '<td>' + item.hungarian + '</td>';
              if (item.is_enabled) {
                tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
              } else {
                tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
              }
              if (item.is_enabled) {
                tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="disableTescoTranslation" data-placement="left"></i></a>';
              } else {
                tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="enableTescoTranslation" data-placement="left"></i></a>';
              }
              tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-translations filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
            })
            tesco_metadata += '</tbody></table></div>';
            $('.content').html(tesco_metadata)
            $('#tesco-translations-list-table').DataTable();
            $('.edit-tesco-translations').tooltip('show')
            $('.edit-tesco-translations').tooltip('toggle')
            $('.status').tooltip('show')
            $('.status').tooltip('toggle')
            $("#tesco-translations-list-table_filter input").addClass('form-control input-sm');
            $("#tesco-translations-list-table_filter input").attr('placeholder', 'Data Search');
            $('#tesco-translations-list-table_length select').addClass('form-control input-sm');
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Adds tescoTranslations
 */
 function addTescoTranslation() {
  try {
    var english = $('#translation_english').val();
    var polish = $('#translation_polish').val();
    var czech = $('#translation_czech').val();
    var slovak = $('#translation_slovak').val();
    var hungarian = $('#translation_hungarian').val();
      

    $.ajax({
          url: base_url + "/admin/tesco/add-tesco-translation",
          method: 'POST',
          data: {
            _token: csrf_token,
            english: english,
            polish: polish,
            czech: czech,
            slovak: slovak,
            hungarian: hungarian,
          },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Translation Added Successfully");
                  showSnackbar();
                  $('#addTescoTranslationModal').modal('hide');
                  tescoTranslations()
              },
              208: function() {
                  $('#translation_error').removeClass('hidden');
                  $('#translation_error').removeAttr('style');
                  $('#translation_error').html('Translation[English] already excists.')
                  $("#translation_error").addClass("alert alert-warning");
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
          complete: function() {
              $(".loader").hide();
          }
    })
  } catch (e) {
      console.log("Error:" + e)
  }
}


/**
 * Function for get tesco translation ById details for editing
 * @param id
 */
 function getTescoTranslationById(id) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/get-tesco-translation-details",
          dataType: "json",
          method: "get",
          data: { id: id },
          tryCount: 0,
          retryLimit: 2,
          beforeSend: function() {
              $(".loader").show();
          },
          success: function(data) {
              isSubmitting = false;
              $('#edit_tesco_translation_id').val(id);
              $('#edit_translation_english').val(data.tesco_translationlists[0].english);
              $('#edit_translation_polish').val(data.tesco_translationlists[0].polish);
              $('#edit_translation_czech').val(data.tesco_translationlists[0].czech);
              $('#edit_translation_slovak').val(data.tesco_translationlists[0].slovak);
              $('#edit_translation_hungarian').val(data.tesco_translationlists[0].hungarian);
              $('#editTescoTranslationModal').modal({
                  backdrop: 'static',
                  keyboard: false
              })
              $('#edit-tesco-translation-form').validator('validate');
              $('#editTescoTranslationModal').modal('show');
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Function for update Tesco translation
 */
 function updateTescoTranslation() {
  try {
      var id = $('#edit_tesco_translation_id').val();
      var english = $('#edit_translation_english').val();
      var polish = $('#edit_translation_polish').val();
      var czech = $('#edit_translation_czech').val();
      var slovak = $('#edit_translation_slovak').val();
      var hungarian = $('#edit_translation_hungarian').val();
      
      $.ajax({
          url: base_url + "/admin/tesco/update-tesco-translation-details",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
              $('#translation_update_error').addClass('hidden');
          },
          method: "put",
          data: { 
            _token: csrf_token,
            id : id,
            english: english,
            polish: polish,
            czech: czech,
            slovak: slovak,
            hungarian: hungarian,
          },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {

              /** [ Show response status based on HTTP status code ] */
              200: function() {
                  $('.snackbar_span').html("Translation Updated Successfully");
                  showSnackbar();
                  $('#editTescoTranslationModal').modal('hide');
                  tescoTranslations()
              },
              208: function() {
                  $('#translation_update_error').removeClass('hidden');
                  $('#translation_update_error').removeAttr('style');
                  $('#translation_update_error').html('Translation [English] already excists.')
                  $("#translation_update_error").addClass("alert alert-warning");
              },
          },
          error: function(errorXHR, errorStatus, errorThrown) {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.ajax(this);
                  return;
              }
          },
          complete: function() {
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Makes a Kids Warning Stmt Enabled.
 * @param id,csrf_token
 */
 function enableTescoTranslation(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/enable-tesco-translation",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { _token: csrf_token, id: id },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {
              /** [Show response status based on HTTP status code] */
              200: function() {
                  isSubmitting = false;
                  $('.snackbar_span').html('Translation is enabled ');
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
          complete: function() {
            tescoTranslations();
            $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Makes a Kids Warning Stmt Enabled.
 * @param id,csrf_token
 */
function disableTescoTranslation(id, csrf_token) {
  try {
      if (isSubmitting) {
          return;
      }
      isSubmitting = true;
      $.ajax({
          url: base_url + "/admin/tesco/disable-tesco-translation",
          dataType: "json",
          beforeSend: function() {
              $(".loader").show();
          },
          method: "put",
          data: { _token: csrf_token, id: id },
          tryCount: 0,
          retryLimit: 2,
          statusCode: {
              /** [Show response status based on HTTP status code] */
              200: function() {
                  isSubmitting = false;
                  $('.snackbar_span').html('Translation is disabled ');
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
          complete: function() {
            tescoTranslations();
              $(".loader").hide();
          }
      })
  } catch (e) {
      console.log("Error:" + e)
  }
}

/**
 * Function for tesco label size chart home page
 */

function tescoLabelSizeChart() {
  try {
    $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.orchestra-sidebar').hide();
    $('.tesco-sidebar').show();
    $.ajax({
      url: base_url + "/admin/tesco/settings/get-tesco-label-size-chart",
      type: "POST",
      dataType: "json",
      data: {
          _token: csrf_token
      },
      tryCount: 0,
      retryLimit: 2,
      beforeSend: function() {
          $(".loader").show();
      },
      success: function(data) {
        var tesco_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>'+
        '<div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoLabelSizeChartModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Add Label Size </a></div></div>'+
        '<div class="template-block"><div class="top-content"><h3 class="content-heading"> Label Size Chart </h3></div>'+
        '<table id="tesco-label-sizechart-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead>'+
        '<tr><th>Sl. No</th><th>Department Title</th><th>UK Size</th><th>CE Size</th><th>Height</th><th>Weight</th><th>Chest</th><th>Bust</th><th>Waist</th>'+
        // '<th>Height</th><th>Weight</th><th>Chest</th><th>Bust</th><th>Waist</th>'+
        '<th>Status</th><th>Actions</th></tr></thead><tbody>';
        $.each(data.tesco_translationlist, function(i, item) {
          var index = i + 1;
          tesco_metadata += '<tr id="' + item.id + '">'+
          '<td>' + index + '</td>'+
          '<td>' + item.department_title + '</td>'+
          '<td>' + item.uk_size + '</td>'+
          '<td>' + item.ce_size + '</td>'+
          '<td>' + item.Height + '</td>'+
          '<td>' + item.Weight + '</td>'+
          '<td>' + item.Chest + '</td>'+
          '<td>' + item.Bust + '</td>'+
          '<td>' + item.Waist + '</td>';
          

          if (item.is_flag) {
            tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
          } else {
            tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
          }
          if (item.is_flag) {
            tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="disableTescoTranslation" data-placement="left"></i></a>';
          } else {
            tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="enableTescoTranslation" data-placement="left"></i></a>';
          }
          tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-translations filterbtn options" title="Edit" data-placement="left" id="editEntry" ></i></a></td></tr>';
        })
        tesco_metadata += '</tbody></table></div>';
        $('.content').html(tesco_metadata)
        $('#tesco-label-sizechart-list-table').DataTable();
        $('.edit-tesco-labelsizechart').tooltip('show')
        $('.edit-tesco-labelsizechart').tooltip('toggle')
        $('.status').tooltip('show')
        $('.status').tooltip('toggle')
        $("#tesco-label-sizechart-list-table_filter input").addClass('form-control input-sm');
        $("#tesco-label-sizechart-list-table_filter input").attr('placeholder', 'Data Search');
        $('#tesco-label-sizechart-list-table_length select').addClass('form-control input-sm');
      },
      error: function(errorXHR, errorStatus, errorThrown) {
          this.tryCount++;
          if (this.tryCount <= this.retryLimit) {
              $.ajax(this);
              return;
          }
      },
      complete: function() {
          $(".loader").hide();
      }
    
    
    
    
    })
  } catch (e) {
    console.log("Error:" + e)
  }
  

}

function getLabelSizeModalData() {
  $.ajax({
    url: base_url + "/admin/tesco/settings/get-label-category-data",
    dataType: "json",
    method: "get",
    tryCount: 0,
    retryLimit: 2,
    beforeSend: function() {
        $(".loader").show();
    },
    success: function(data) {
      let categories = data.result['label_categories'];
      let departments = data.result['size_departments'];
      
      delete data.result['label_categories'];
      delete data.result['size_departments'];

      var optionsDept = "";
      for(var i = 0; i < departments.length; i++) {
        optionsDept += "<option value='" + departments[i].id + "'>" + departments[i].department_name + "</option>";
      }
      $('select[name="size_department"]').append(optionsDept);
      
      var checkboxDiv = '';
      for(var i = 0; i < categories.length; i++) {
        checkboxDiv += '<div class="form-check form-check-inline col-md-2">'+
        '<input class="form-check-input" type="checkbox" value="" data-id="'+categories[i].id+'" id="chart-label-'+categories[i].category_name.toLowerCase()+'" data-name="'+categories[i].category_name+'">'+
        '<label class="form-check-label" for="'+categories[i].category_name.toLowerCase()+'">  '+categories[i].category_name+'</label></div>';
      }
      $('.secondary-categories').html(checkboxDiv)
    },
    error: function(errorXHR, errorStatus, errorThrown) {
          this.tryCount++;
          if (this.tryCount <= this.retryLimit) {
              $.ajax(this);
              return;
          }
    },
    complete: function() {
      $(".loader").hide();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
      checkboxToTextbox()
    }
  })
}

function checkboxToTextbox() {
  $('#add-tesco-labelsize-chart-form input[type="checkbox"]').change(function() {
    var data_id = $(this).attr('data-id');
    var data_name = $(this).attr('data-name');
    if ($(this).prop('checked')) {

      var inputBox = '<div class="col-md-6 removeLabelSizeItem sizechart-'+data_name+'"><div class="form-group has-feedback">'+
      '<label class="form_label" for="category_'+data_id+'"> '+data_name+'</label>'+
      '<input class="form-control input-sm" placeholder="'+data_name+' Value" required="required" name="category_'+data_id+'" type="text" id="labelsize_'+data_name.toLowerCase()+'" data-id="'+data_id+'" >'+
      '<div class="help-block with-errors"></div></div></div>';
    
      $('#addTescoLabelSizeChartModal').find('div.col-md-6:last').after(inputBox)
      $("#add-tesco-labelsize-chart-form").validator('update');  

    } else {
      $('#addTescoLabelSizeChartModal').find('.sizechart-'+data_name+'').remove()
      $("#add-tesco-labelsize-chart-form").validator('update');
    }
  });
}

function categories_data(raw_data){
  var primary = {};
  var secondary = {};
  var serializedData = {};
  for (var key in raw_data) {
    var name = raw_data[key]['name'];
    if (name.startsWith('category_')) {
      secondary[name] = raw_data[key]['value']
    } else {
      primary[name] = raw_data[key]['value']  
    }
  }
  serializedData['primary'] = primary;
  serializedData['secondary'] = secondary;
  return serializedData
}

function addTescoLabelSizeChart() {
  try {
      var raw_data = $('#add-tesco-labelsize-chart-form').serializeArray();
      var serializedData = categories_data(raw_data)
      
      $.ajax({
        url: base_url + "/admin/tesco/settings/add-tesco-label-size-chart",
        method: 'POST',
        data: {
          _token: csrf_token,
          customData: serializedData,
        },
        tryCount: 0,
        retryLimit: 2,
        beforeSend: function() {
            $(".loader").show();
        },
        success: function () { 
          alert('Success'); 
        },
        error: function (xhr, status, error) { 
          alert('Error:'); 
        },
        complete: function() {
          $(".loader").hide();
        }
      });


  } catch (e) {
      console.log("Error:" + e)
  }
}

$(document).on('click', '#editEntry', function(event) {
  event.preventDefault();
  /* Act on the event */
  alert()
});