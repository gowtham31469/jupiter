/* Settings script 
 * @author <praveen>
 */

var isSubmitting = false;
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
/**  Validation for numeric values with dot**/
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
  * @author <praveen>
  */
function tescoBrands() {
  try{
    $('.main-content').html('<div class="loader"></div>');
    /** tesco metadata sidebar */
    var tesco_metadata_sidebar = '<div class="sidebar style-4"><h4>Tesco Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="tescoBrands()"><a class="active">Brands</a></li><li id="2" onclick="tescoDepartment()"><a>Departments</a></li><li id="3" onclick="tescoSuppliers()"><a>Suppliers</a></li><li id="4"  onclick="tescoSizeChart()"><a>Size Chart</a></li></ul></div>';
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/tesco/settings/brands",
        type: "POST",
        dataType: "json",
        data: {
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        success: function (data) {
                var tesco_metadata = tesco_metadata_sidebar+'<div class="loader"></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoBrandsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Brand</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Brands</h3></div><table id="tesco-brands-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Brand Name</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
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
                    tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button" aria-hidden="true" title="Make Disabled" id="make_disabled" data-placement="left"></i></a>';
                   }
                   else
                   {
                    tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button" aria-hidden="true" title="Make Enabled" id="make_enabled" data-placement="left"></i></a>';
                   }


                tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-brands filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-tesco-brands" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteTescoBrandsModal" data-placement="left"></i></a></td></tr>';
	             });
	             tesco_metadata += '</tbody></table></div>';
	             $('.main-content').html(tesco_metadata)
                $('.edit-tesco-brands').tooltip('show')
                $('.edit-tesco-brands').tooltip('toggle')
                $('.delete-tesco-brands').tooltip('show')
                $('.delete-tesco-brands').tooltip('toggle')
	              $('#tesco-brands-list-table').DataTable();
	              $("#tesco-brands-list-table_filter input").addClass('form-control input-sm');
	              $("#tesco-brands-list-table_filter input").attr('placeholder', 'Data Search');
	              $('#tesco-brands-list-table_length select').addClass('form-control input-sm');

        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })
    } catch (e) {
     console.log("Error:"+e)
   }
}
/**
  * Function to show snackbar
  * @author <praveen>
  */
function showSnackbar() {
    // Get the snackbar DIV
    var x = document.getElementById("snackbar")

    // Add the "show" class to DIV
    x.className = "show";

    // After 3 seconds, remove the show class from DIV
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}
/**
  * Adds a tesco brands.
  * @author <praveen>
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
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Brand Added Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#addTescoBrandsModal').modal('hide');
                       tescoBrands()
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
   * Function for get tesco brands details for editing
   * @param id
   * @author <praveen>
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
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
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
  * Function for edit tesco brands details
  * @author <praveen>
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
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data:{_token:csrf_token,id:id,name:name},
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Brand Updated Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#editTescoBrandsModal').modal('hide');
                       tescoBrands()
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
  * To delete tesco brands
  * @param id
  * @author <praveen>
  *
  */
  function deleteTescoBrand(id)
  {
  try{
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/tesco/delete-tesco-brand",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"delete",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     $('.snackbar_span').html('Brand Deleted Successfully');
                     $('#snackbar').css('background-color','#1abb9c');
                     showSnackbar();
                     $('#deleteTescoBrandsModal').modal('hide');
                     tescoBrands();
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

  /**
  * Makes a Brand Enabled.
  * @param id,csrf_token
  * @author <praveen>
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
                $(".loader").css('margin-top','12%');
              },
              method:"put",
              data:{_token:csrf_token,id:id},
              statusCode: {
                   /** [Show response status based on HTTP status code] */
                   200: function() {
                       isSubmitting = false; 
                       $('.snackbar_span').html('Brand is enabled ');
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                   }
              },
              error: function (data) {
                 console.log(data);
              },
              complete: function () {
                tescoBrands();
                $(".loader").hide();
                $(".loader").css('margin-top','0%');
              }
          })
   } catch (e) {
    console.log("Error:"+e)
   }
  }
/**
 * Makes a Brand Diabled
 * @param id,csrf_token
 * @author <praveen>
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
                $(".loader").css('margin-top','12%');
              },
              method:"put",
              data:{_token:csrf_token,id:id},
              statusCode: {
                   /** [Show response status based on HTTP status code] */
                   200: function() {
                        isSubmitting = false;
                        $('.snackbar_span').html('Brand is disabled ');
                        $('#snackbar').css('background-color','#1abb9c');
                        showSnackbar();
                   }
              },
              error: function (data) {
                 console.log(data);
              },
              complete: function () {
                tescoBrands();
                $(".loader").hide();
                $(".loader").css('margin-top','0%');
              }
          })
   } catch (e) {
    console.log("Error:"+e)
   }
  }

  /**
  * Function to Tesco Department home page
  * @author <praveen>
  */
function tescoDepartment() {
    try{
      $('.main-content').html('<div class="loader"></div>');
      /** tesco metadata sidebar */
      var tesco_metadata_sidebar = '<div class="sidebar style-4"><h4>Tesco Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="tescoBrands()"><a>Brands</a></li><li id="2" onclick="tescoDepartment()"><a class="active">Departments</a></li><li id="3" onclick="tescoSuppliers()"><a>Suppliers</a></li><li id="4"  onclick="tescoSizeChart()"><a>Size Chart</a></li></ul></div>';
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/tesco/settings/departments",
          type: "POST",
          dataType: "json",
          data: {
              _token: csrf_token
          },
          beforeSend: function () {
              $(".loader").show();
              $(".loader").css('margin-top', '12%');
          },
          success: function (data) {
                  var tesco_metadata = tesco_metadata_sidebar+'<div class="loader"></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoDepartmentsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Department</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Departments</h3></div><table id="tesco-departments-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Department Name</th><th>Pantone</th><th>RBG</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.tesco_department, function(i, item){
                    var index = i+1;
                    tesco_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.department+'</td><td>'+item.pantone+'</td><td>'+item.rbg+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-tesco-departments filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-tesco-departments" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteTescoDepartmentsModal" data-placement="left"></i></a></td></tr>';
                   });
                   tesco_metadata += '</tbody></table></div>';
                   $('.main-content').html(tesco_metadata)
                  $('.edit-tesco-departments').tooltip('show')
                  $('.edit-tesco-departments').tooltip('toggle')
                  $('.delete-tesco-departments').tooltip('show')
                  $('.delete-tesco-departments').tooltip('toggle')
                    $('#tesco-departments-list-table').DataTable();
                    $("#tesco-departments-list-table_filter input").addClass('form-control input-sm');
                    $("#tesco-departments-list-table_filter input").attr('placeholder', 'Data Search');
                    $('#tesco-departments-list-table_length select').addClass('form-control input-sm');
  
          },
          error: function (data) {
  
          },
          complete: function () {
              $(".loader").hide();
              $(".loader").css('margin-top', '0%');
          }
      })
      } catch (e) {
       console.log("Error:"+e)
     }
}

/**
  * Adds a tesco Department.
  * @author <praveen>
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
              beforeSend: function () {
                $(".loader").show();
                $(".loader").css('margin-top','12%');
              },
              statusCode: {
                   200: function() {
                        $('.snackbar_span').html("Department Added Successfully");
                        $('#snackbar').css('background-color','#1abb9c');
                        showSnackbar();
                        $('#addTescoDepartmentsModal').modal('hide');
                        tescoDepartment()
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
   * Function for get tesco department details for editing
   * @param id
   * @author <praveen>
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
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
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
  * Function for edit tesco department details
  * @author <praveen>
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
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data:{_token:csrf_token,id:id,department:department,pantone:pantone,rbg:rbg},
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Department Updated Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#editTescoDepartmentsModal').modal('hide');
                       tescoDepartment()
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
  * To delete tesco department
  * @param id
  * @author <praveen>
  *
  */
  function deleteTescoDepartment(id)
  {
  try{
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/tesco/delete-tesco-department",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"delete",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     $('.snackbar_span').html('Department Deleted Successfully');
                     $('#snackbar').css('background-color','#1abb9c');
                     showSnackbar();
                     $('#deleteTescoDepartmentsModal').modal('hide');
                     tescoDepartment();
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

  /**
  * Function to Tesco Supplier Settings home page
  * @author <praveen>
  */
function tescoSuppliers() {
    try{
      $('.main-content').html('<div class="loader"></div>');
      /** tesco metadata sidebar */
      var tesco_metadata_sidebar = '<div class="sidebar style-4"><h4>Tesco Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="tescoBrands()"><a>Brands</a></li><li id="2" onclick="tescoDepartment()"><a>Departments</a></li><li id="3" onclick="tescoSuppliers()"><a class="active">Suppliers</a></li><li id="4"  onclick="tescoSizeChart()"><a>Size Chart</a></li></ul></div>';
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/tesco/settings/suppliers",
          type: "POST",
          dataType: "json",
          data: {
              _token: csrf_token
          },
          beforeSend: function () {
              $(".loader").show();
              $(".loader").css('margin-top', '12%');
          },
          success: function (data) {
                  var tesco_metadata = tesco_metadata_sidebar+'<div class="loader"></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoSuppliersModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Supplier</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Suppliers</h3></div><table id="tesco-suppliers-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Supplier Name</th><th>Supplier Code</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
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
                      tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button" aria-hidden="true" title="Make Disabled" id="make_supplier_disabled" data-placement="left"></i></a>';
                     }
                     else
                     {
                      tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button" aria-hidden="true" title="Make Enabled" id="make_supplier_enabled" data-placement="left"></i></a>';
                     }
  
  
                  tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-suppliers filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-tesco-suppliers" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteTescoSuppliersModal" data-placement="left"></i></a></td></tr>';
                   });
                   tesco_metadata += '</tbody></table></div>';
                   $('.main-content').html(tesco_metadata)
                  $('.edit-tesco-suppliers').tooltip('show')
                  $('.edit-tesco-suppliers').tooltip('toggle')
                  $('.delete-tesco-suppliers').tooltip('show')
                  $('.delete-tesco-suppliers').tooltip('toggle')
                    $('#tesco-suppliers-list-table').DataTable();
                    $("#tesco-suppliers-list-table_filter input").addClass('form-control input-sm');
                    $("#tesco-suppliers-list-table_filter input").attr('placeholder', 'Data Search');
                    $('#tesco-suppliers-list-table_length select').addClass('form-control input-sm');
  
          },
          error: function (data) {
  
          },
          complete: function () {
              $(".loader").hide();
              $(".loader").css('margin-top', '0%');
          }
      })
      } catch (e) {
       console.log("Error:"+e)
     }
  }

  /**
  * Adds a tesco suppliers.
  * @author <praveen>
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
              beforeSend: function () {
                $(".loader").show();
                $(".loader").css('margin-top','12%');
              },
              statusCode: {
                   200: function() {
                        $('.snackbar_span').html("Supplier Added Successfully");
                        $('#snackbar').css('background-color','#1abb9c');
                        showSnackbar();
                        $('#addTescoSuppliersModal').modal('hide');
                        tescoSuppliers()
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
   * Function for get tesco suppliers details for editing
   * @param id
   * @author <praveen>
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
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
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
  * Function for edit tesco department details
  * @author <praveen>
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
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data:{_token:csrf_token,id:id,supplier_name:supplier_name,supplier_code:supplier_code},
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Supplier Updated Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#editTescoSuppliersModal').modal('hide');
                       tescoSuppliers()
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
  * To delete tesco supplier
  * @param id
  * @author <praveen>
  *
  */
  function deleteTescoSupplier(id)
  {
  try{
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/tesco/delete-tesco-supplier",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"delete",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     $('.snackbar_span').html('Supplier Deleted Successfully');
                     $('#snackbar').css('background-color','#1abb9c');
                     showSnackbar();
                     $('#deleteTescoSuppliersModal').modal('hide');
                     tescoSuppliers();
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

   /**
  * Makes a Supplier Enabled.
  * @param id,csrf_token
  * @author <praveen>
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
                $(".loader").css('margin-top','12%');
              },
              method:"put",
              data:{_token:csrf_token,id:id},
              statusCode: {
                   /** [Show response status based on HTTP status code] */
                   200: function() {
                       isSubmitting = false; 
                       $('.snackbar_span').html('Supplier is enabled ');
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                   }
              },
              error: function (data) {
                 console.log(data);
              },
              complete: function () {
                tescoSuppliers();
                $(".loader").hide();
                $(".loader").css('margin-top','0%');
              }
          })
   } catch (e) {
    console.log("Error:"+e)
   }
  }
/**
 * Makes a Supplier Diabled
 * @param id,csrf_token
 * @author <praveen>
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
                $(".loader").css('margin-top','12%');
              },
              method:"put",
              data:{_token:csrf_token,id:id},
              statusCode: {
                   /** [Show response status based on HTTP status code] */
                   200: function() {
                        isSubmitting = false;
                        $('.snackbar_span').html('Supplier is disabled ');
                        $('#snackbar').css('background-color','#1abb9c');
                        showSnackbar();
                   }
              },
              error: function (data) {
                 console.log(data);
              },
              complete: function () {
                tescoSuppliers();
                $(".loader").hide();
                $(".loader").css('margin-top','0%');
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
      $('.main-content').html('<div class="loader"></div>');
      /** tesco metadata sidebar */
      var tesco_metadata_sidebar = '<div class="sidebar style-4"><h4>Tesco Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="tescoBrands()"><a>Brands</a></li><li id="2" onclick="tescoDepartment()"><a>Departments</a></li><li id="3" onclick="tescoSuppliers()"><a >Suppliers</a></li><li id="4"  onclick="tescoSizeChart()"><a class="active">Size Chart</a></li></ul></div>';
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/tesco/settings/sizechart",
          type: "POST",
          dataType: "json",
          data: {
              _token: csrf_token
          },
          beforeSend: function () {
              $(".loader").show();
              $(".loader").css('margin-top', '12%');
          },
          success: function (data) {
                  var tesco_metadata = tesco_metadata_sidebar+'<div class="loader"></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addTescoSizeChartsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add SizeChart</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">SizeCharts</h3></div><table id="tesco-sizecharts-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>PO_Size</th><th>UK</th><th>CE</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.tesco_sizecharts, function(i, item){
                    var index = i+1;
                    tesco_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.po_size+'</td><td>'+item.uk+'<td>'+item.ce+'</td>';
                 
  
  
                  tesco_metadata += '<td><a href="#"><i class="fa fa-pencil fa-button edit-tesco-sizecharts filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-tesco-sizecharts" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteTescoSizeChartsModal" data-placement="left"></i></a></td></tr>';
                   });
                   tesco_metadata += '</tbody></table></div>';
                   $('.main-content').html(tesco_metadata)
                  $('.edit-tesco-sizecharts').tooltip('show')
                  $('.edit-tesco-sizecharts').tooltip('toggle')
                  $('.delete-tesco-sizecharts').tooltip('show')
                  $('.delete-tesco-sizecharts').tooltip('toggle')
                    $('#tesco-sizecharts-list-table').DataTable();
                    $("#tesco-sizecharts-list-table_filter input").addClass('form-control input-sm');
                    $("#tesco-sizecharts-list-table_filter input").attr('placeholder', 'Data Search');
                    $('#tesco-sizecharts-list-table_length select').addClass('form-control input-sm');
  
          },
          error: function (data) {
  
          },
          complete: function () {
              $(".loader").hide();
              $(".loader").css('margin-top', '0%');
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
              beforeSend: function () {
                $(".loader").show();
                $(".loader").css('margin-top','12%');
              },
              statusCode: {
                   200: function() {
                        $('.snackbar_span').html("Size Chart Added Successfully");
                        $('#snackbar').css('background-color','#1abb9c');
                        showSnackbar();
                        $('#addTescoSizeChartsModal').modal('hide');
                        tescoSizeChart()
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
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
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
  * Function for edit tesco department details
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
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data:{_token:csrf_token,id:id,po_size:po_size,uk:uk,ce:ce},
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Size Chart Updated Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#editTescoSizeChartsModal').modal('hide');
                       tescoSizeChart()
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
  * To delete tesco supplier
  * @param id
  * @author <praveen>
  *
  */
  function deleteTescoSizeChart(id)
  {
  try{
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/tesco/delete-tesco-sizecharts",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"delete",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     $('.snackbar_span').html('Size Chart Deleted Successfully');
                     $('#snackbar').css('background-color','#1abb9c');
                     showSnackbar();
                     $('#deleteTescoSizeChartsModal').modal('hide');
                     tescoSizeChart();
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
   
   