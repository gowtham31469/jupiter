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
  * Function to Settings home page
  * @author <praveen>
  */
function settings() {
  try{
    $('.main-content').html('<div class="loader"></div>');
    /** Orchestra metadata sidebar */
    var orchestra_metadata_sidebar = '<div class="sidebar style-4"><h4>Orchestra Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a class="active">Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a>Countries</a></li><li id="4" onclick="productFamily()"><a>Product Family</a></li><li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="compositions()"><a>Compositions</a></li><li id="7" onclick="parts()"><a>Parts</a></li><li id="8"  onclick="sizeChart()"><a>Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li></ul></div>';
    /** Tesco metadata sidebar */
    var tesco_metadata_sidebar = '<div class="sidebar style-4"><h4>Tesco Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a class="active">Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a>Countries</a></li><li id="4" onclick="productFamily()"><a>Product Family</a></li><li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="compositions()"><a>Compositions</a></li><li id="7" onclick="parts()"><a>Parts</a></li><li id="8"  onclick="sizeChart()"><a>Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li><li id="14" onclick="department()"><a>Department</a></li><li id="15" onclick="brand()"><a>Brand</a></li></ul></div>';
    var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/settings",
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
            if (buyer == "orchestra") 
            {
                var orchestra_metadata = orchestra_metadata_sidebar+'<div class="loader"></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraGarmentsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Garment</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Garments</h3></div><table id="orchestra-garments-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Garment Name</th><th>Actions</th></tr></thead><tbody>';
	             $.each(data.orchestra_garments, function(i, item){
	              var index = i+1;
	              orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.garment_name+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-orchestra-garments filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-orchestra-garments" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteOrchestraGarmentsModal" data-placement="left"></i></a></td></tr>';
	             });
	             orchestra_metadata += '</tbody></table></div>';
	             $('.main-content').html(orchestra_metadata)
                $('.edit-orchestra-garments').tooltip('show')
                $('.edit-orchestra-garments').tooltip('toggle')
                $('.delete-orchestra-garments').tooltip('show')
                $('.delete-orchestra-garments').tooltip('toggle')
	              $('#orchestra-garments-list-table').DataTable();
	              $("#orchestra-garments-list-table_filter input").addClass('form-control input-sm');
	              $("#orchestra-garments-list-table_filter input").attr('placeholder', 'Data Search');
	              $('#orchestra-garments-list-table_length select').addClass('form-control input-sm');
            } 
            else 
            {
                $('.main-content').html(tesco_metadata)
            }

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
  * Adds a orchestra garments.
  * @author <praveen>
  */
 function addOrchestraGarments()
 {
    try{
         var name       = $('#garment_name').val();
         var csrf_token = $('meta[name="csrf-token"]').attr('content');

         $.ajax({
             url: base_url + "/admin/orchestra/add-orchestra-garments",
             method: 'POST',
             data: {_token:csrf_token,name:name},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Garment Added Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#addOrchestraGarmentsModal').modal('hide');
                       settings()
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
   * Function for get orchestra garments details for editing
   * @param id
   * @author <praveen>
   *
   */
  function getOrchestraGarmentById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/orchestra/get-orchestra-garments-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_orchestra_garments_id').val(id);
                 $('#edit_garment_name').val(data.orchestra_garments[0].garment_name);
                 $('#editOrchestraGarmentsModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_orchestra_garments_form').validator('validate');
                 $('#editOrchestraGarmentsModal').modal('show');
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
  * Function for edit orchestra garments details
  * @author <praveen>
  */
 function editOrchestraGarments()
 {
  try{
       var id         = $('#edit_orchestra_garments_id').val();
       var name       = $('#edit_garment_name').val();
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/update-orchestra-garments",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data:{_token:csrf_token,id:id,name:name},
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Garment Updated Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#editOrchestraGarmentsModal').modal('hide');
                       settings()
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
  * To delete orchestra garment
  * @param id
  * @author <praveen>
  *
  */
  function deleteOrchestraGarment(id)
  {
  try{
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/delete-orchestra-garment",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"delete",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     $('.snackbar_span').html('Garment Deleted Successfully');
                     $('#snackbar').css('background-color','#1abb9c');
                     showSnackbar();
                     $('#deleteOrchestraGarmentsModal').modal('hide');
                     settings();
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
  * To delete orchestra garment
  * @param id
  * @author <praveen>
  *
  */
  function ageGroup() {
    try{
      $('.main-content').html('<div class="loader"></div>');
      /** Orchestra metadata sidebar */
      var orchestra_metadata_sidebar = '<div class="sidebar style-4"><h4>Orchestra Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a>Garments</a></li><li id="2" onclick="ageGroup()"><a class="active">Age Group</a></li><li id="3" onclick="countries()"><a>Countries</a></li><li id="4" onclick="productFamily()"><a>Product Family</a></li><li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="compositions()"><a>Compositions</a></li><li id="7" onclick="parts()"><a>Parts</a></li><li id="8"  onclick="sizeChart()"><a>Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li></ul></div>';
      /** Tesco metadata sidebar */
      var tesco_metadata_sidebar = '<div class="sidebar style-4"><h4>Tesco Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a class="active">Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a>Countries</a></li><li id="4" onclick="productFamily()"><a>Product Family</a></li><li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="compositions()"><a>Compositions</a></li><li id="7" onclick="parts()"><a>Parts</a></li><li id="8"  onclick="sizeChart()"><a>Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li></ul></div>';
      var buyer = localStorage.getItem("buyer");
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/" + buyer + "/settings/age-group",
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
              if (buyer == "orchestra") 
              {
                  var orchestra_metadata = orchestra_metadata_sidebar+'<div class="loader"></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraAgeGroupModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Age Group</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Age Groups</h3></div><table id="orchestra-age-group-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Age Group Name</th><th>Color</th><th>Actions</th></tr></thead><tbody>';
                 $.each(data.orchestra_agegroups, function(i, item){
                  var index = i+1;
                  orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.age_group_name+'</td><td>'+item.color+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-orchestra-age-group filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-orchestra-age-group" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteOrchestraAgeGroupModal" data-placement="left" ></i></a></td></tr>';
                 });
                 orchestra_metadata += '</tbody></table></div>';
                 $('.main-content').html(orchestra_metadata)
                 $('.edit-orchestra-age-group').tooltip('show')
                 $('.edit-orchestra-age-group').tooltip('toggle')
                 $('.delete-orchestra-age-group').tooltip('show')
                 $('.delete-orchestra-age-group').tooltip('toggle')
                  $('#orchestra-age-group-list-table').DataTable();
                  $("#orchestra-age-group-list-table_filter input").addClass('form-control input-sm');
                  $("#orchestra-age-group-list-table_filter input").attr('placeholder', 'Data Search');
                  $('#orchestra-age-group-list-table_length select').addClass('form-control input-sm');
              } 
              else 
              {
                  $('.main-content').html(tesco_metadata)
              }
              
              /** make side bar active  */
               $('.meta-data-list li').on('click', function () {
                $(this).siblings().removeClass('active');
                $(this).addClass('active');
               });

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
  * Adds a orchestra age group.
  * @author <praveen>
  */
 function addOrchestraAgegroup()
 {
    try{
         var name       = $('#age_group_name').val();
         var color       = $('#color_name').val();
         var csrf_token = $('meta[name="csrf-token"]').attr('content');

         $.ajax({
             url: base_url + "/admin/orchestra/add-orchestra-agegroup",
             method: 'POST',
             data: {_token:csrf_token,name:name,color:color},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Age Group Added Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#addOrchestraAgeGroupModal').modal('hide');
                       ageGroup()
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
   * Function for get orchestra age group details for editing
   * @param id
   * @author <praveen>
   *
   */
  function getOrchestraAgeGroupById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/orchestra/get-orchestra-agegroup-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_orchestra_agegroup_id').val(id);
                 $('#edit_age_group_name').val(data.orchestra_age_group[0].age_group_name); 
                 $('#edit_color_name').val(data.orchestra_age_group[0].color);
                 $('#editOrchestraAgeGroupModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_orchestra_agegroup_form').validator('validate');
                 $('#editOrchestraAgeGroupModal').modal('show');
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
  * Function for edit orchestra age-group details
  * @author <praveen>
  */
 function editOrchestraAgeGroup()
 {
  try{
       var id         = $('#edit_orchestra_agegroup_id').val();
       var name       = $('#edit_age_group_name').val();
       var color      = $('#edit_color_name').val();
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/update-orchestra-agegroup",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data:{_token:csrf_token,id:id,name:name,color:color},
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Age Group Updated Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#editOrchestraAgeGroupModal').modal('hide');
                       ageGroup()
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
  * To delete orchestra age-group
  * @param id
  * @author <praveen>
  *
  */
  function deleteOrchestraAgeGroup(id)
  {
  try{
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/delete-orchestra-agegroup",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"delete",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     $('.snackbar_span').html('Age Group Deleted Successfully');
                     $('#snackbar').css('background-color','#1abb9c');
                     showSnackbar();
                     $('#deleteOrchestraAgeGroupModal').modal('hide');
                     ageGroup();
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
* Function to countries home page
* @author <praveen>
*/
function countries() {
  try{
    $('.main-content').html('<div class="loader"></div>');
    /** Orchestra metadata sidebar */
    var orchestra_metadata_sidebar = '<div class="sidebar style-4"><h4>Orchestra Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a >Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a class="active">Countries</a></li><li id="4" onclick="productFamily()"><a>Product Family</a></li><li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="compositions()"><a>Compositions</a></li><li id="7" onclick="parts()"><a>Parts</a></li><li id="8"  onclick="sizeChart()"><a>Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li></ul></div>';
    /** Tesco metadata sidebar */
    var tesco_metadata_sidebar = '<div class="sidebar style-4"><h4>Tesco Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a >Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a class="active">Countries</a></li><li id="4" onclick="productFamily()"><a>Product Family</a></li><li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="compositions()"><a>Compositions</a></li><li id="7" onclick="parts()"><a>Parts</a></li><li id="8"  onclick="sizeChart()"><a>Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li><li id="14" onclick="department()"><a>Department</a></li><li id="15" onclick="brand()"><a>Brand</a></li></ul></div>';
    var buyer = localStorage.getItem("buyer");
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/" + buyer + "/settings/countries",
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
            if (buyer == "orchestra") 
            {
                var orchestra_metadata = orchestra_metadata_sidebar+'<div class="loader"></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraCountryModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Country Name</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Countries</h3></div><table id="orchestra-countries-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>English</th><th>French</th><th>Spanish</th><th>Portuguese</th><th>Italian</th><th>Dutch</th><th>German</th><th>Russian</th><th>Chinese</th><th>Arabic</th><th>Greek</th><th>Turkish</th><th>Actions</th></tr></thead><tbody>';
               $.each(data.orchestra_countries, function(i, item){
                var index = i+1;
                orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.english+'</td><td>'+item.french+'</td><td>'+item.spanish+'</td><td>'+item.portuguese+'</td><td>'+item.italian+'</td><td>'+item.dutch+'</td><td>'+item.german+'</td><td>'+item.russian+'</td><td>'+item.chinese+'</td><td>'+item.arabic+'</td><td>'+item.greek+'</td><td>'+item.turkish+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-orchestra-country filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-orchestra-country" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteOrchestraCountryModal" data-placement="left"></i></a></td></tr>';
               });
               orchestra_metadata += '</tbody></table></div>';
               $('.main-content').html(orchestra_metadata)
               
                $('#orchestra-countries-list-table').DataTable({
                  'scrollX':true
                });
                 $('.edit-orchestra-country').tooltip('show')
                 $('.edit-orchestra-country').tooltip('toggle')
                 $('.delete-orchestra-country').tooltip('show')
                 $('.delete-orchestra-country').tooltip('toggle')
                $("#orchestra-countries-list-table_filter input").addClass('form-control input-sm');
                $("#orchestra-countries-list-table_filter input").attr('placeholder', 'Data Search');
                $('#orchestra-countries-list-table_length select').addClass('form-control input-sm');
            } 
            else 
            {
                $('.main-content').html(tesco_metadata)
            }

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
  * Adds orchestra countries.
  * @author <praveen>
  */
 function addOrchestraCountries()
 {
    try{
         var english    = $('#english').val();
         var french     = $('#french').val();
         var spanish    = $('#spanish').val();
         var portuguese = $('#portuguese').val();
         var italian    = $('#italian').val();
         var dutch      = $('#dutch').val();
         var german     = $('#german').val();
         var russian    = $('#russian').val();
         var chinese    = $('#chinese').val();
         var arabic     = $('#arabic').val();
         var greek      = $('#greek').val();
         var turkish    = $('#turkish').val();
         var csrf_token = $('meta[name="csrf-token"]').attr('content');

         $.ajax({
             url: base_url + "/admin/orchestra/add-orchestra-country",
             method: 'POST',
             data: {_token:csrf_token,english:english,french:french,spanish:spanish,portuguese:portuguese,italian:italian,dutch:dutch,german:german,russian:russian,chinese:chinese,arabic:arabic,greek:greek,turkish:turkish},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Country Name Added Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#addOrchestraCountryModal').modal('hide');
                       countries()
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
   * Function for get orchestra country details for editing
   * @param id
   * @author <praveen>
   *
   */
  function getOrchestraCountryById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/orchestra/get-orchestra-country-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_orchestra_country_id').val(id);
                 $('#edit_english').val(data.orchestra_countries[0].english); 
                 $('#edit_french').val(data.orchestra_countries[0].french); 
                 $('#edit_spanish').val(data.orchestra_countries[0].spanish); 
                 $('#edit_portuguese').val(data.orchestra_countries[0].portuguese); 
                 $('#edit_italian').val(data.orchestra_countries[0].italian); 
                 $('#edit_dutch').val(data.orchestra_countries[0].dutch); 
                 $('#edit_german').val(data.orchestra_countries[0].german); 
                 $('#edit_russian').val(data.orchestra_countries[0].russian); 
                 $('#edit_chinese').val(data.orchestra_countries[0].chinese); 
                 $('#edit_arabic').val(data.orchestra_countries[0].arabic); 
                 $('#edit_greek').val(data.orchestra_countries[0].greek); 
                 $('#edit_turkish').val(data.orchestra_countries[0].turkish); 
                 $('#editOrchestraCountryModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_orchestra_country_form').validator('validate');
                 $('#editOrchestraCountryModal').modal('show');
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
  * Function for edit orchestra country name
  * @author <praveen>
  */
 function editOrchestraCountry()
 {
  try{
       var id         = $('#edit_orchestra_country_id').val();
       var english    = $('#edit_english').val();
       var french     = $('#edit_french').val();
       var spanish    = $('#edit_spanish').val();
       var portuguese = $('#edit_portuguese').val();
       var italian    = $('#edit_italian').val();
       var dutch      = $('#edit_dutch').val();
       var german     = $('#edit_german').val();
       var russian    = $('#edit_russian').val();
       var chinese    = $('#edit_chinese').val();
       var arabic     = $('#edit_arabic').val();
       var greek      = $('#edit_greek').val();
       var turkish    = $('#edit_turkish').val();
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/update-orchestra-country",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data: {_token:csrf_token,id:id,english:english,french:french,spanish:spanish,portuguese:portuguese,italian:italian,dutch:dutch,german:german,russian:russian,chinese:chinese,arabic:arabic,greek:greek,turkish:turkish},
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Country Name Updated Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#editOrchestraCountryModal').modal('hide');
                       countries()
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
  * To delete orchestra country
  * @param id
  * @author <praveen>
  *
  */
  function deleteOrchestraCountry(id)
  {
  try{
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/delete-orchestra-country",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"delete",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     $('.snackbar_span').html('Country Name Deleted Successfully');
                     $('#snackbar').css('background-color','#1abb9c');
                     showSnackbar();
                     $('#deleteOrchestraCountryModal').modal('hide');
                     countries();
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
  * Function to show product family home page
  * @author <praveen>
  */
  function productFamily() {
    try{
      $('.main-content').html('<div class="loader"></div>');
      /** Orchestra metadata sidebar */
      var orchestra_metadata_sidebar = '<div class="sidebar style-4"><h4>Orchestra Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a >Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a>Countries</a></li><li id="4" onclick="productFamily()"><a class="active">Product Family</a></li><li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="compositions()"><a>Compositions</a></li><li id="7" onclick="parts()"><a>Parts</a></li><li id="8"  onclick="sizeChart()"><a>Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li></ul></div>';
      /** Tesco metadata sidebar */
      var tesco_metadata_sidebar = '<div class="sidebar style-4"><h4>Tesco Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a >Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a >Countries</a></li><li id="4" onclick="productFamily()"><a class="active">Product Family</a></li><li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="compositions()"><a>Compositions</a></li><li id="7" onclick="parts()"><a>Parts</a></li><li id="8"  onclick="sizeChart()"><a>Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li><li id="14" onclick="department()"><a>Department</a></li><li id="15" onclick="brand()"><a>Brand</a></li></ul></div>';
      var buyer = localStorage.getItem("buyer");
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/" + buyer + "/settings/productfamily",
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
              if (buyer == "orchestra") 
              {
                  var orchestra_metadata = orchestra_metadata_sidebar+'<div class="loader"></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraProductFamilyModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Product Family</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Product Families</h3></div><table id="orchestra-productfamily-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Product Family Code</th><th>English</th><th>French</th><th>Arabic</th><th>Actions</th></tr></thead><tbody>';
                 $.each(data.orchestra_product_family, function(i, item){
                  var index = i+1;
                  orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.code+'</td><td>'+item.english+'</td><td>'+item.french+'</td><td>'+item.arabic+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-orchestra-productfamily filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-orchestra-productfamily" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteOrchestraProductFamilyModal" data-placement="left" ></i></a></td></tr>';
                 });
                 orchestra_metadata += '</tbody></table></div>';
                 $('.main-content').html(orchestra_metadata)
                 
                  $('#orchestra-productfamily-list-table').DataTable({
                    'scrollX':true
                  });
                  $('.edit-orchestra-productfamily').tooltip('show')
                  $('.edit-orchestra-productfamily').tooltip('toggle')
                  $('.delete-orchestra-productfamily').tooltip('show')
                  $('.delete-orchestra-productfamily').tooltip('toggle')
                  $("#orchestra-productfamily-list-table_filter input").addClass('form-control input-sm');
                  $("#orchestra-productfamily-list-table_filter input").attr('placeholder', 'Data Search');
                  $('#orchestra-productfamily-list-table_length select').addClass('form-control input-sm');
              } 
              else 
              {
                  $('.main-content').html(tesco_metadata)
              }

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
  * Adds orchestra Product Family.
  * @author <praveen>
  */
   function addOrchestraProductFamily()
   {
      try{
           var english    = $('#english_pf').val();
           var french     = $('#french_pf').val();
           var code       = $('#code').val();
           var arabic     = $('#arabic_pf').val();
          
           var csrf_token = $('meta[name="csrf-token"]').attr('content');

           $.ajax({
               url: base_url + "/admin/orchestra/add-orchestra-productfamily",
               method: 'POST',
               data: {_token:csrf_token,english:english,french:french,code:code,arabic:arabic},
               beforeSend: function () {
                 $(".loader").show();
                 $(".loader").css('margin-top','12%');
               },
               statusCode: {
                    200: function() {
                         $('.snackbar_span').html("Product Family Added Successfully");
                         $('#snackbar').css('background-color','#1abb9c');
                         showSnackbar();
                         $('#addOrchestraProductFamilyModal').modal('hide');
                         productFamily()()
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
   * Function for get orchestra product family details for editing
   * @param id
   * @author <praveen>
   *
   */
  function getOrchestraProductFamilyById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/orchestra/get-orchestra-productfamily-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_orchestra_productfamily_id').val(id);
                 $('#edit_english_pf').val(data.orchestra_product_family[0].english); 
                 $('#edit_french_pf').val(data.orchestra_product_family[0].french); 
                 $('#edit_code').val(data.orchestra_product_family[0].code); 
                 $('#edit_arabic_pf').val(data.orchestra_product_family[0].arabic); 
                 
                 $('#editOrchestraProductFamilyModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_orchestra_productfamily_form').validator('validate');
                 $('#editOrchestraProductFamilyModal').modal('show');
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
  * Function for edit orchestra country name
  * @author <praveen>
  */
 function editOrchestraProductFamily()
 {
  try{
       var id         = $('#edit_orchestra_productfamily_id').val();
       var english    = $('#edit_english_pf').val();
       var french     = $('#edit_french_pf').val();
       var arabic     = $('#edit_arabic_pf').val();
       var code       = $('#edit_code').val();
       
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/update-orchestra-productfamily",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data: {_token:csrf_token,id:id,english:english,french:french,arabic:arabic,code:code},
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Product Family Updated Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#editOrchestraProductFamilyModal').modal('hide');
                       productFamily()()
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
  * To delete orchestra product family
  * @param id
  * @author <praveen>
  *
  */
  function deleteOrchestraProductFamily(id)
  {
  try{
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/delete-orchestra-productfamily",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"delete",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     $('.snackbar_span').html('Product Family Deleted Successfully');
                     $('#snackbar').css('background-color','#1abb9c');
                     showSnackbar();
                     $('#deleteOrchestraProductFamilyModal').modal('hide');
                     productFamily();
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
    * Function to special instructions home page
    * @author <praveen>
    */
    function specialInstruction() {
      try{
        $('.main-content').html('<div class="loader"></div>');
        /** Orchestra metadata sidebar */
        var orchestra_metadata_sidebar = '<div class="sidebar style-4"><h4>Orchestra Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a >Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a>Countries</a></li><li id="4" onclick="productFamily()"><a>Product Family</a></li><li id="5" onclick="specialInstruction()"><a class="active">Special Instructions</a></li><li id="6" onclick="compositions()"><a>Compositions</a></li><li id="7" onclick="parts()"><a>Parts</a></li><li id="8"  onclick="sizeChart()"><a>Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li></ul></div>';
        /** Tesco metadata sidebar */
        var tesco_metadata_sidebar = '<div class="sidebar style-4"><h4>Tesco Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a >Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a>Countries</a></li><li id="4" onclick="productFamily()"><a>Product Family</a></li><li id="5" onclick="specialInstruction()"><a class="active">Special Instructions</a></li><li id="6" onclick="compositions()"><a>Compositions</a></li><li id="7" onclick="parts()"><a>Parts</a></li><li id="8"  onclick="sizeChart()"><a>Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li><li id="14" onclick="department()"><a>Department</a></li><li id="15" onclick="brand()"><a>Brand</a></li></ul></div>';
        var buyer = localStorage.getItem("buyer");
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: base_url + "/admin/" + buyer + "/settings/special-instructions",
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
                if (buyer == "orchestra") 
                {
                    var orchestra_metadata = orchestra_metadata_sidebar+'<div class="loader"></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraSpecialInstructionModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Special Instruction</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Special Instructions</h3></div><table id="orchestra-special-instruction-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>English</th><th>French</th><th>Spanish</th><th>Portuguese</th><th>Italian</th><th>Dutch</th><th>German</th><th>Russian</th><th>Chinese</th><th>Arabic</th><th>Greek</th><th>Turkish</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.orchestra_specialinstructions, function(i, item){
                    var index = i+1;
                    orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.english+'</td><td>'+item.french+'</td><td>'+item.spanish+'</td><td>'+item.portuguese+'</td><td>'+item.italian+'</td><td>'+item.dutch+'</td><td>'+item.german+'</td><td>'+item.russian+'</td><td>'+item.chinese+'</td><td>'+item.arabic+'</td><td>'+item.greek+'</td><td>'+item.turkish+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-orchestra-special-instruction filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-orchestra-special-instruction" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteOrchestraSpecialInstructionModal" data-placement="left"></i></a></td></tr>';
                   });
                   orchestra_metadata += '</tbody></table></div>';
                   $('.main-content').html(orchestra_metadata)
                   
                    $('#orchestra-special-instruction-list-table').DataTable({
                      'scrollX':true
                    });
                    $('.edit-orchestra-special-instruction').tooltip('show')
                    $('.edit-orchestra-special-instruction').tooltip('toggle')
                    $('.delete-orchestra-special-instruction').tooltip('show')
                    $('.delete-orchestra-special-instruction').tooltip('toggle')
                    $("#orchestra-special-instruction-list-table_filter input").addClass('form-control input-sm');
                    $("#orchestra-special-instruction-list-table_filter input").attr('placeholder', 'Data Search');
                    $('#orchestra-special-instruction-list-table_length select').addClass('form-control input-sm');
                } 
                else 
                {
                    $('.main-content').html(tesco_metadata)
                }

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
  * Adds orchestra special-instruction.
  * @author <praveen>
  */
 function addOrchestraSpecialInstruction()
 {
    try{
         var english    = $('#english_si').val();
         var french     = $('#french_si').val();
         var spanish    = $('#spanish_si').val();
         var portuguese = $('#portuguese_si').val();
         var italian    = $('#italian_si').val();
         var dutch      = $('#dutch_si').val();
         var german     = $('#german_si').val();
         var russian    = $('#russian_si').val();
         var chinese    = $('#chinese_si').val();
         var arabic     = $('#arabic_si').val();
         var greek      = $('#greek_si').val();
         var turkish    = $('#turkish_si').val();
         var csrf_token = $('meta[name="csrf-token"]').attr('content');

         $.ajax({
             url: base_url + "/admin/orchestra/add-orchestra-special-instructions",
             method: 'POST',
             data: {_token:csrf_token,english:english,french:french,spanish:spanish,portuguese:portuguese,italian:italian,dutch:dutch,german:german,russian:russian,chinese:chinese,arabic:arabic,greek:greek,turkish:turkish},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Special Instruction Added Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#addOrchestraSpecialInstructionModal').modal('hide');
                       specialInstruction() 
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
   * Function for get orchestra Special instruction details for editing
   * @param id
   * @author <praveen>
   *
   */
  function getOrchestraSpecialInstructionById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/orchestra/get-orchestra-special-instruction-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_orchestra_special_instruction_id').val(id);
                 $('#edit_english_si').val(data.orchestra_special_instruction[0].english); 
                 $('#edit_french_si').val(data.orchestra_special_instruction[0].french); 
                 $('#edit_spanish_si').val(data.orchestra_special_instruction[0].spanish); 
                 $('#edit_portuguese_si').val(data.orchestra_special_instruction[0].portuguese); 
                 $('#edit_italian_si').val(data.orchestra_special_instruction[0].italian); 
                 $('#edit_dutch_si').val(data.orchestra_special_instruction[0].dutch); 
                 $('#edit_german_si').val(data.orchestra_special_instruction[0].german); 
                 $('#edit_russian_si').val(data.orchestra_special_instruction[0].russian); 
                 $('#edit_chinese_si').val(data.orchestra_special_instruction[0].chinese); 
                 $('#edit_arabic_si').val(data.orchestra_special_instruction[0].arabic); 
                 $('#edit_greek_si').val(data.orchestra_special_instruction[0].greek); 
                 $('#edit_turkish_si').val(data.orchestra_special_instruction[0].turkish); 
                 $('#editOrchestraSpecialInstructionModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_orchestra_special_instruction_form').validator('validate');
                 $('#editOrchestraSpecialInstructionModal').modal('show');
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
  * Function for edit orchestra special instruction
  * @author <praveen>
  */
 function editOrchestraSpecialInstruction()
 {
  try{
       var id         = $('#edit_orchestra_special_instruction_id').val();
       var english    = $('#edit_english_si').val();
       var french     = $('#edit_french_si').val();
       var spanish    = $('#edit_spanish_si').val();
       var portuguese = $('#edit_portuguese_si').val();
       var italian    = $('#edit_italian_si').val();
       var dutch      = $('#edit_dutch_si').val();
       var german     = $('#edit_german_si').val();
       var russian    = $('#edit_russian_si').val();
       var chinese    = $('#edit_chinese_si').val();
       var arabic     = $('#edit_arabic_si').val();
       var greek      = $('#edit_greek_si').val();
       var turkish    = $('#edit_turkish_si').val();
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/update-orchestra-special-instruction",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data: {_token:csrf_token,id:id,english:english,french:french,spanish:spanish,portuguese:portuguese,italian:italian,dutch:dutch,german:german,russian:russian,chinese:chinese,arabic:arabic,greek:greek,turkish:turkish},
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Special Instruction Updated Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#editOrchestraSpecialInstructionModal').modal('hide');
                       specialInstruction()
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
  * To delete orchestra special instruction
  * @param id
  * @author <praveen>
  *
  */
  function deleteOrchestraSpecialInstruction(id)
  {
  try{
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/delete-orchestra-special-instruction",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"delete",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     $('.snackbar_span').html('Special Instruction Deleted Successfully');
                     $('#snackbar').css('background-color','#1abb9c');
                     showSnackbar();
                     $('#deleteOrchestraSpecialInstructionModal').modal('hide');
                     specialInstruction();
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
    * Function to compositions home page
    * @author <praveen>
    */
    function compositions() {
      try{
        $('.main-content').html('<div class="loader"></div>');
        /** Orchestra metadata sidebar */
        var orchestra_metadata_sidebar = '<div class="sidebar style-4"><h4>Orchestra Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a >Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a>Countries</a></li><li id="4" onclick="productFamily()"><a>Product Family</a></li><li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="compositions()"><a class="active">Compositions</a></li><li id="7" onclick="parts()"><a>Parts</a></li><li id="8"  onclick="sizeChart()"><a>Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li></ul></div>';
        /** Tesco metadata sidebar */
        var tesco_metadata_sidebar = '<div class="sidebar style-4"><h4>Tesco Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a >Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a>Countries</a></li><li id="4" onclick="productFamily()"><a>Product Family</a></li><li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="compositions()"><a class="active">Compositions</a></li><li id="7" onclick="parts()"><a>Parts</a></li><li id="8"  onclick="sizeChart()"><a>Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li><li id="14" onclick="department()"><a>Department</a></li><li id="15" onclick="brand()"><a>Brand</a></li></ul></div>';
        var buyer = localStorage.getItem("buyer");
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: base_url + "/admin/" + buyer + "/settings/compositions",
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
                if (buyer == "orchestra") 
                {
                    var orchestra_metadata = orchestra_metadata_sidebar+'<div class="loader"></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraCompositionModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Composition</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Compositions</h3></div><table id="orchestra-composition-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>English</th><th>French</th><th>Spanish</th><th>Portuguese</th><th>Italian</th><th>Dutch</th><th>German</th><th>Russian</th><th>Chinese</th><th>Arabic</th><th>Greek</th><th>Turkish</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.orchestra_compositions, function(i, item){
                    var index = i+1;
                    orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.english+'</td><td>'+item.french+'</td><td>'+item.spanish+'</td><td>'+item.portuguese+'</td><td>'+item.italian+'</td><td>'+item.dutch+'</td><td>'+item.german+'</td><td>'+item.russian+'</td><td>'+item.chinese+'</td><td>'+item.arabic+'</td><td>'+item.greek+'</td><td>'+item.turkish+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-orchestra-composition filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-orchestra-composition" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteOrchestraCompositionModal" data-placement="left"></i></a></td></tr>';
                   });
                   orchestra_metadata += '</tbody></table></div>';
                   $('.main-content').html(orchestra_metadata)
                   
                    $('#orchestra-composition-list-table').DataTable({
                      'scrollX':true
                    });
                    $('.edit-orchestra-composition').tooltip('show')
                    $('.edit-orchestra-composition').tooltip('toggle')
                    $('.delete-orchestra-composition').tooltip('show')
                    $('.delete-orchestra-composition').tooltip('toggle')
                    $("#orchestra-composition-list-table_filter input").addClass('form-control input-sm');
                    $("#orchestra-composition-list-table_filter input").attr('placeholder', 'Data Search');
                    $('#orchestra-composition-list-table_length select').addClass('form-control input-sm');
                } 
                else 
                {
                    $('.main-content').html(tesco_metadata)
                }

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
  * Adds orchestra composition.
  * @author <praveen>
  */
 function addOrchestraComposition()
 {
    try{
         var english    = $('#english_co').val();
         var french     = $('#french_co').val();
         var spanish    = $('#spanish_co').val();
         var portuguese = $('#portuguese_co').val();
         var italian    = $('#italian_co').val();
         var dutch      = $('#dutch_co').val();
         var german     = $('#german_co').val();
         var russian    = $('#russian_co').val();
         var chinese    = $('#chinese_co').val();
         var arabic     = $('#arabic_co').val();
         var greek      = $('#greek_co').val();
         var turkish    = $('#turkish_co').val();
         var csrf_token = $('meta[name="csrf-token"]').attr('content');

         $.ajax({
             url: base_url + "/admin/orchestra/add-orchestra-compositions",
             method: 'POST',
             data: {_token:csrf_token,english:english,french:french,spanish:spanish,portuguese:portuguese,italian:italian,dutch:dutch,german:german,russian:russian,chinese:chinese,arabic:arabic,greek:greek,turkish:turkish},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Composition Added Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#addOrchestraCompositionModal').modal('hide');
                       compositions() 
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
   * Function for get orchestra Composition details for editing
   * @param id
   * @author <praveen>
   *
   */
  function getOrchestraCompositionById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/orchestra/get-orchestra-composition-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_orchestra_composition_id').val(id);
                 $('#edit_english_co').val(data.orchestra_composition[0].english); 
                 $('#edit_french_co').val(data.orchestra_composition[0].french); 
                 $('#edit_spanish_co').val(data.orchestra_composition[0].spanish); 
                 $('#edit_portuguese_co').val(data.orchestra_composition[0].portuguese); 
                 $('#edit_italian_co').val(data.orchestra_composition[0].italian); 
                 $('#edit_dutch_co').val(data.orchestra_composition[0].dutch); 
                 $('#edit_german_co').val(data.orchestra_composition[0].german); 
                 $('#edit_russian_co').val(data.orchestra_composition[0].russian); 
                 $('#edit_chinese_co').val(data.orchestra_composition[0].chinese); 
                 $('#edit_arabic_co').val(data.orchestra_composition[0].arabic); 
                 $('#edit_greek_co').val(data.orchestra_composition[0].greek); 
                 $('#edit_turkish_co').val(data.orchestra_composition[0].turkish); 
                 $('#editOrchestraCompositionModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_orchestra_composition_form').validator('validate');
                 $('#editOrchestraCompositionModal').modal('show');
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
  * Function for edit Composition instruction
  * @author <praveen>
  */
 function editOrchestraComposition()
 {
  try{
       var id         = $('#edit_orchestra_composition_id').val();
       var english    = $('#edit_english_co').val();
       var french     = $('#edit_french_co').val();
       var spanish    = $('#edit_spanish_co').val();
       var portuguese = $('#edit_portuguese_co').val();
       var italian    = $('#edit_italian_co').val();
       var dutch      = $('#edit_dutch_co').val();
       var german     = $('#edit_german_co').val();
       var russian    = $('#edit_russian_co').val();
       var chinese    = $('#edit_chinese_co').val();
       var arabic     = $('#edit_arabic_co').val();
       var greek      = $('#edit_greek_co').val();
       var turkish    = $('#edit_turkish_co').val();
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/update-orchestra-composition",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data: {_token:csrf_token,id:id,english:english,french:french,spanish:spanish,portuguese:portuguese,italian:italian,dutch:dutch,german:german,russian:russian,chinese:chinese,arabic:arabic,greek:greek,turkish:turkish},
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Composition Updated Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#editOrchestraCompositionModal').modal('hide');
                       compositions()
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
  * To delete orchestra Composition
  * @param id
  * @author <praveen>
  *
  */
  function deleteOrchestraComposition(id)
  {
  try{
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/delete-orchestra-composition",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"delete",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     $('.snackbar_span').html('Composition Deleted Successfully');
                     $('#snackbar').css('background-color','#1abb9c');
                     showSnackbar();
                     $('#deleteOrchestraCompositionModal').modal('hide');
                     compositions();
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
    * Function to parts home page
    * @author <praveen>
    */
    function parts() {
      try{
        $('.main-content').html('<div class="loader"></div>');
        /** Orchestra metadata sidebar */
        var orchestra_metadata_sidebar = '<div class="sidebar style-4"><h4>Orchestra Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a >Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a>Countries</a></li><li id="4" onclick="productFamily()"><a>Product Family</a></li><li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="compositions()"><a>Compositions</a></li><li id="7" onclick="parts()"><a class="active">Parts</a></li><li id="8"  onclick="sizeChart()"><a>Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li></ul></div>';
        /** Tesco metadata sidebar */
        var tesco_metadata_sidebar = '<div class="sidebar style-4"><h4>Tesco Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a >Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a>Countries</a></li><li id="4" onclick="productFamily()"><a>Product Family</a></li><li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="compositions()"><a>Compositions</a></li><li id="7" onclick="parts()"><a class="active">Parts</a></li><li id="8"  onclick="sizeChart()"><a>Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li><li id="14" onclick="department()"><a>Department</a></li><li id="15" onclick="brand()"><a>Brand</a></li></ul></div>';
        var buyer = localStorage.getItem("buyer");
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: base_url + "/admin/" + buyer + "/settings/parts",
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
                if (buyer == "orchestra") 
                {
                    var orchestra_metadata = orchestra_metadata_sidebar+'<div class="loader"></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraPartModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Part</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Parts</h3></div><table id="orchestra-part-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>English</th><th>French</th><th>Spanish</th><th>Portuguese</th><th>Italian</th><th>Dutch</th><th>German</th><th>Russian</th><th>Chinese</th><th>Arabic</th><th>Greek</th><th>Turkish</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.orchestra_parts, function(i, item){
                    var index = i+1;
                    orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.english+'</td><td>'+item.french+'</td><td>'+item.spanish+'</td><td>'+item.portuguese+'</td><td>'+item.italian+'</td><td>'+item.dutch+'</td><td>'+item.german+'</td><td>'+item.russian+'</td><td>'+item.chinese+'</td><td>'+item.arabic+'</td><td>'+item.greek+'</td><td>'+item.turkish+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-orchestra-part filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-orchestra-part" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteOrchestraPartModal" data-placement="left"></i></a></td></tr>';
                   });
                   orchestra_metadata += '</tbody></table></div>';
                   $('.main-content').html(orchestra_metadata)
                   
                    $('#orchestra-part-list-table').DataTable({
                      'scrollX':true
                    });
                    $('.edit-orchestra-part').tooltip('show')
                    $('.edit-orchestra-part').tooltip('toggle')
                    $('.delete-orchestra-part').tooltip('show')
                    $('.delete-orchestra-part').tooltip('toggle')
                    $("#orchestra-part-list-table_filter input").addClass('form-control input-sm');
                    $("#orchestra-part-list-table_filter input").attr('placeholder', 'Data Search');
                    $('#orchestra-part-list-table_length select').addClass('form-control input-sm');
                } 
                else 
                {
                    $('.main-content').html(tesco_metadata)
                }

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
  * Adds orchestra part.
  * @author <praveen>
  */
 function addOrchestraPart()
 {
    try{
         var english    = $('#english_pa').val();
         var french     = $('#french_pa').val();
         var spanish    = $('#spanish_pa').val();
         var portuguese = $('#portuguese_pa').val();
         var italian    = $('#italian_pa').val();
         var dutch      = $('#dutch_pa').val();
         var german     = $('#german_pa').val();
         var russian    = $('#russian_pa').val();
         var chinese    = $('#chinese_pa').val();
         var arabic     = $('#arabic_pa').val();
         var greek      = $('#greek_pa').val();
         var turkish    = $('#turkish_pa').val();
         var csrf_token = $('meta[name="csrf-token"]').attr('content');

         $.ajax({
             url: base_url + "/admin/orchestra/add-orchestra-parts",
             method: 'POST',
             data: {_token:csrf_token,english:english,french:french,spanish:spanish,portuguese:portuguese,italian:italian,dutch:dutch,german:german,russian:russian,chinese:chinese,arabic:arabic,greek:greek,turkish:turkish},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Part Added Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#addOrchestraPartModal').modal('hide');
                       parts(); 
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
   * Function for get orchestra Part details for editing
   * @param id
   * @author <praveen>
   *
   */
  function getOrchestraPartById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/orchestra/get-orchestra-part-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_orchestra_part_id').val(id);
                 $('#edit_english_pa').val(data.orchestra_part[0].english); 
                 $('#edit_french_pa').val(data.orchestra_part[0].french); 
                 $('#edit_spanish_pa').val(data.orchestra_part[0].spanish); 
                 $('#edit_portuguese_pa').val(data.orchestra_part[0].portuguese); 
                 $('#edit_italian_pa').val(data.orchestra_part[0].italian); 
                 $('#edit_dutch_pa').val(data.orchestra_part[0].dutch); 
                 $('#edit_german_pa').val(data.orchestra_part[0].german); 
                 $('#edit_russian_pa').val(data.orchestra_part[0].russian); 
                 $('#edit_chinese_pa').val(data.orchestra_part[0].chinese); 
                 $('#edit_arabic_pa').val(data.orchestra_part[0].arabic); 
                 $('#edit_greek_pa').val(data.orchestra_part[0].greek); 
                 $('#edit_turkish_pa').val(data.orchestra_part[0].turkish); 
                 $('#editOrchestraPartModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_orchestra_part_form').validator('validate');
                 $('#editOrchestraPartModal').modal('show');
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
  * Function for edit Part instruction
  * @author <praveen>
  */
 function editOrchestraPart()
 {
  try{
       var id         = $('#edit_orchestra_part_id').val();
       var english    = $('#edit_english_pa').val();
       var french     = $('#edit_french_pa').val();
       var spanish    = $('#edit_spanish_pa').val();
       var portuguese = $('#edit_portuguese_pa').val();
       var italian    = $('#edit_italian_pa').val();
       var dutch      = $('#edit_dutch_pa').val();
       var german     = $('#edit_german_pa').val();
       var russian    = $('#edit_russian_pa').val();
       var chinese    = $('#edit_chinese_pa').val();
       var arabic     = $('#edit_arabic_pa').val();
       var greek      = $('#edit_greek_pa').val();
       var turkish    = $('#edit_turkish_pa').val();
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/update-orchestra-part",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data: {_token:csrf_token,id:id,english:english,french:french,spanish:spanish,portuguese:portuguese,italian:italian,dutch:dutch,german:german,russian:russian,chinese:chinese,arabic:arabic,greek:greek,turkish:turkish},
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Part Updated Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#editOrchestraPartModal').modal('hide');
                       parts();
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
  * To delete orchestra Part
  * @param id
  * @author <praveen>
  *
  */
  function deleteOrchestraPart(id)
  {
  try{
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/delete-orchestra-part",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"delete",
           data:{_token:csrf_token,id:id},
           statusCode: {
                200: function() {
                     $('.snackbar_span').html('Part Deleted Successfully');
                     $('#snackbar').css('background-color','#1abb9c');
                     showSnackbar();
                     $('#deleteOrchestraPartModal').modal('hide');
                     parts();
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
    * Function to size chart  home page
    * @author <praveen>
    */
    function sizeChart() {
      try{
        $('.main-content').html('<div class="loader"></div>');
        /** Orchestra metadata sidebar */
        var orchestra_metadata_sidebar = '<div class="sidebar style-4"><h4>Orchestra Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a >Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a>Countries</a></li><li id="4" onclick="productFamily()"><a>Product Family</a></li><li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="compositions()"><a>Compositions</a></li><li id="7" onclick="parts()"><a>Parts</a></li><li id="8"  onclick="sizeChart()"><a class="active">Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li></ul></div>';
        /** Tesco metadata sidebar */
        var tesco_metadata_sidebar = '<div class="sidebar style-4"><h4>Tesco Metadata</h4><ul class="customer-select meta-data-list"><li id="1" onclick="settings()"><a >Garments</a></li><li id="2" onclick="ageGroup()"><a>Age Group</a></li><li id="3" onclick="countries()"><a>Countries</a></li><li id="4" onclick="productFamily()"><a>Product Family</a></li><li id="5" onclick="specialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="compositions()"><a>Compositions</a></li><li id="7" onclick="parts()"><a>Parts</a></li><li id="8"  onclick="sizeChart()"><a class="active">Size Chart</a></li><li id="9" onclick="season()"><a>Season</a></li><li id="10" onclick="region()"><a>Region</a></li><li id="11" onclick="flags()"><a>Flags</a></li><li id="12" onclick="items()"><a>Items</a></li><li id="13" onclick="cylinderRadius()"><a>Cylinder Radius</a></li><li id="14" onclick="department()"><a>Department</a></li><li id="15" onclick="brand()"><a>Brand</a></li></ul></div>';
        var buyer = localStorage.getItem("buyer");
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: base_url + "/admin/" + buyer + "/settings/size-chart",
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
                if (buyer == "orchestra") 
                {
                    var orchestra_metadata = orchestra_metadata_sidebar+'<div class="loader"></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraSizeChartModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Size Chart</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Size Charts</h3></div><table id="orchestra-size-chart-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Age</th><th>Centimeter(cm)</th><th>Inches(inch)</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.orchestra_size_charts, function(i, item){
                    var index = i+1;
                    orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.age+'</td><td>'+item.cm+'</td><td>'+item.in+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-orchestra-size-chart filterbtn options" title="Edit" data-placement="left"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options delete-orchestra-size-chart" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteOrchestraSizeChartModal" data-placement="left"></i></a></td></tr>';
                   });
                   orchestra_metadata += '</tbody></table></div>';
                   $('.main-content').html(orchestra_metadata)
                   
                    $('#orchestra-size-chart-list-table').DataTable({
                      'scrollX':true
                    });
                    $('.edit-orchestra-size-chart').tooltip('show')
                    $('.edit-orchestra-size-chart').tooltip('toggle')
                    $('.delete-orchestra-size-chart').tooltip('show')
                    $('.delete-orchestra-size-chart').tooltip('toggle')
                    $("#orchestra-size-chart-list-table_filter input").addClass('form-control input-sm');
                    $("#orchestra-size-chart-list-table_filter input").attr('placeholder', 'Data Search');
                    $('#orchestra-size-chart-list-table_length select').addClass('form-control input-sm');
                } 
                else 
                {
                    $('.main-content').html(tesco_metadata)
                }

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
  * Adds orchestra size chart.
  * @author <praveen>
  */
 function addOrchestraSizeChart()
 {
    try{
         var age    = $('#age').val();
         var cm     = $('#cm').val();
         var inch    = $('#inch').val();
         var csrf_token = $('meta[name="csrf-token"]').attr('content');

         $.ajax({
             url: base_url + "/admin/orchestra/add-orchestra-size-chart",
             method: 'POST',
             data: {_token:csrf_token,age:age,in:inch,cm:cm},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             statusCode: {
                  200: function() {
                       $('.snackbar_span').html("Size Chart Added Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#addOrchestraSizeChartModal').modal('hide');
                       sizeChart(); 
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
   * Function for get orchestra Size Chart details for editing
   * @param id
   * @author <praveen>
   *
   */
  function getOrchestraSizeChartById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/orchestra/get-orchestra-size-chart-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             beforeSend: function () {
               $(".loader").show();
               $(".loader").css('margin-top','12%');
             },
             success: function(data){
                 isSubmitting = false;
                 $('#edit_orchestra_size_chart_id').val(id);
                 $('#edit_age').val(data.orchestra_size_chart[0].age); 
                 $('#edit_cm').val(data.orchestra_size_chart[0].cm); 
                 $('#edit_inch').val(data.orchestra_size_chart[0].in); 
                 $('#editOrchestraSizeChartModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_orchestra_size-chart_form').validator('validate');
                 $('#editOrchestraSizeChartModal').modal('show');
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
  * Function for edit Size Chart instruction
  * @author <praveen>
  */
 function editOrchestraSizeChart()
 {
  try{
       var id         = $('#edit_orchestra_size_chart_id').val();
       var age        = $('#edit_age').val();
       var cm         = $('#edit_cm').val();
       var inch       = $('#edit_inch').val();
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/update-orchestra-size-chart",
           dataType:"json",
           beforeSend: function () {
             $(".loader").show();
             $(".loader").css('margin-top','12%');
           },
           method:"put",
           data: {_token:csrf_token,id:id,age:age,cm:cm,in:inch},
           statusCode: {
                200: function() {
                       $('.snackbar_span').html("Size Chart Updated Successfully");
                       $('#snackbar').css('background-color','#1abb9c');
                       showSnackbar();
                       $('#editOrchestraSizeChartModal').modal('hide');
                       sizeChart();
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
  * To delete orchestra Part
  * @param id
  * @author <praveen>
  *
  */
  function deleteOrchestraSizeChart(id)
  {
  try{
       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/delete-orchestra-size-chart",
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
                     $('#deleteOrchestraSizeChartModal').modal('hide');
                     sizeChart();
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