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
function tescoSettings() {
  try{
    $('.main-content').html('<div class="loader"></div>');
    /** tesco metadata sidebar */
    var tesco_metadata_sidebar = '<div class="sidebar style-4"><h4>Tesco Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="tescoSettings()"><a class="active">Brands</a></li><li id="2" onclick="department()"><a>Departments</a></li><li id="3" onclick="suppliers()"><a>Suppliers</a></li><li id="4"  onclick="sizeChart()"><a>Size Chart</a></li></ul></div>';
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/tesco/settings",
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
                if(item.is_active === 1)
                   {
                    tesco_metadata += '<td><span class="label label-success">Enabled</span></td>';
                   }
                   else
                   {
                    tesco_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                   }
                   if(item.is_active === 1)
                   {
                    tesco_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button" aria-hidden="true" title="Make Disabled" id="make_disabled" data-placement="left"></i></a>';
                   }
                   else
                   {
                    tesco_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button" aria-hidden="true" title="Make Enabled" id="make_enabled" data-placement="left"></i></a>';
                   }


                tesco_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-tesco-brands filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-tesco-brands" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deletetescobrandsModal" data-placement="left"></i></a></td></tr>';
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
         var name       = $('#garment_name').val();
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
                       tescoSettings()
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
                       tescoSettings()
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
  * To delete tesco garment
  * @param id
  * @author <praveen>
  *
  */
  function deleteTescoBrand(id)
  {
  try{
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/tesco/delete-tesco-garment",
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
                     tescoSettings();
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
