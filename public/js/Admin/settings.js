/* Settings script 
 * @author <praveen>
 */


var isSubmitting = false;
/**
  * Function to Settings home page
  * @author <praveen>
  */
function settings() {
  try{
    $('.main-content').html('<div class="loader"></div>');
    /** Orchestra metadata sidebar */
    var orchestra_metadata = '<div class="sidebar style-4"><h4>Orchestra Metadata</h4><ul class="customer-select meta-data-list"><li id="1"><a class="active">Garments</a></li><li id="2"  onclick="orchestraSizeChart()"><a>size chart</a></li><li id="3" onclick="orchestraCountries()"><a>countries</a></li><li id="4" onclick="orchestraProductFamily()"><a>Product family</a></li><li id="5" onclick="orchestraSpecialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="orchestraCompositions()"><a>Compositions</a></li><li id="7" onclick="orchestraParts()"><a>Parts</a></li><li id="8" onclick="orchestraAgeGroup()"><a>age group</a></li><li id="9" onclick="orchestraSeason()"><a>Season</a></li><li id="10" onclick="orchestraRegion()"><a>Region</a></li><li id="11" onclick="orchestraFlags()"><a>Flags</a></li><li id="12" onclick="orchestraItems()"><a>Items</a></li><li id="13" onclick="orchestraCylinderRadius()"><a>Cylinder Radius</a></li></ul></div>';
    /** Tesco metadata sidebar */
    var tesco_metadata = '<div class="sidebar style-4"><h4>Tesco Metadata</h4><ul class="customer-select meta-data-list"><li id="1"><a class="active">Garments</a></li><li id="2"  onclick="tescoSizeChart()"><a>size chart</a></li><li id="3" onclick="tescoCountries()"><a>countries</a></li><li id="4" onclick="tescoProductFamily()"><a>Product family</a></li><li id="5" onclick="tescoSpecialInstruction()"><a>Special Instructions</a></li><li id="6" onclick="tescoCompositions()"><a>Compositions</a></li><li id="7" onclick="tescoParts()"><a>Parts</a></li><li id="8" onclick="tescoAgeGroup()"><a>age group</a></li><li id="9" onclick="tescoSeason()"><a>Season</a></li><li id="10" onclick="tescoRegion()"><a>Region</a></li><li id="11" onclick="tescoFlags()"><a>Flags</a></li><li id="12" onclick="tescoItems()"><a>Items</a></li><li id="13" onclick="tescoCylinderRadius()"><a>Cylinder Radius</a></li></ul></div>';
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
                orchestra_metadata += '<div class="loader"></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraGarmentsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Garments</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Garments</h3></div><table id="orchestra-garments-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Id</th><th>Garment Name</th><th>Actions</th></tr></thead><tbody>';
	             $.each(data.orchestra_garments, function(i, item){
	              var index = i+1;
	              orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.garment_name+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-orchestra-garments filterbtn options" title="Edit"></i></a><a href="#"><i class="fa fa-trash fa-button filterbtn options" data-userid="'+item.id+'" title="Delete" data-toggle="modal" data-target="#deleteOrchestraGarmentsModal" ></i></a></td></tr>';
	             });
	             orchestra_metadata += '</tbody></table></div>';
	             $('.main-content').html(orchestra_metadata)
	              $('#orchestra-garments-list-table').DataTable();
	              $("#orchestra-garments-list-table_filter input").addClass('form-control input-sm');
	              $("#orchestra-garments-list-table_filter input").attr('placeholder', 'Data Search');
	              $('#orchestra-garments-list-table_length select').addClass('form-control input-sm');
            } 
            else 
            {
                $('.main-content').html(tesco_metadata)
            }
            /** make side bar active  */
    //         $('.meta-data-list').on('click', 'li', function() {
    //             $('.meta-data-list li a').removeClass('active');
    //             localStorage.setItem("metadata_id", this.id);
    //             $(this).find('a').addClass('active');
    //             var meta_data = localStorage.getItem("metadata_id");
				// switch (meta_data) { 
				// 	case '1': 
				// 		alert('jQuery Wins!');
				// 		break;
				// 	case '2': 
				// 		alert('prototype Wins!');
				// 		break;
				// 	case '3': 
				// 		alert('mootools Wins!');
				// 		break;		
				// 	case '4': 
				// 		alert('dojo Wins!');
				// 		break;
				// 	default:
				// 		alert('Nobody Wins!');
				// }
    //         })

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
                       $('.snackbar_span').html("Garments Added Successfully");
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
                       $('.snackbar_span').html("Garments Updated Successfully");
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
                     $('.snackbar_span').html('Garment deleted Successfully');
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
function orchestraSizeChart() {
  try{
    alert('orchestraSizeChart')
    
    } catch (e) {
     console.log("Error:"+e)
   }
}
function orchestraCountries() {
  try{
    alert('orchestraCountries')
    
    } catch (e) {
     console.log("Error:"+e)
   }
}
function orchestraProductFamily() {
  try{
    alert('orchestraProductFamily')
    
    } catch (e) {
     console.log("Error:"+e)
   }
}
