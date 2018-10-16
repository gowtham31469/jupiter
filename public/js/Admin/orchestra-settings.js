/* Orchestra Settings script 
 * @author <praveen>
 */

var isSubmitting = false;
/**  
 * Validation for numeric values *
 * @param    evt 
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
 * Validation for numeric values with dot*
 * @param  evt
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
  * Function to Orchestra Settings home page
  * @author <praveen>
  */
function orchestraSettings() {
  try{
    $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.tesco-sidebar').hide();
    $('.orchestra-sidebar').show();
    /** Orchestra metadata sidebar */
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/orchestra/settings/garments",
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
                var orchestra_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraGarmentsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Garment</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Garments</h3></div><table id="orchestra-garments-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Garment Name</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
	             $.each(data.orchestra_garments, function(i, item){
	              var index = i+1;
	              orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.garment_name+'</td>';
                if(item.is_enabled === 1)
                {
                 orchestra_metadata += '<td><span class="label label-success">Enabled</span></td>';
                }
                else
                {
                 orchestra_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                }
                if(item.is_enabled === 1)
                {
                 orchestra_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_garment_disabled" data-placement="left"></i></a>';
                }
                else
                {
                 orchestra_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_garment_enabled" data-placement="left"></i></a>';
                }
                orchestra_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-orchestra-garments filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
	             });
	             orchestra_metadata += '</tbody></table></div>';
	              $('.content').html(orchestra_metadata)
                $('.edit-orchestra-garments').tooltip('show')
                $('.edit-orchestra-garments').tooltip('toggle')
                $('.status').tooltip('show')
                $('.status').tooltip('toggle')
	              $('#orchestra-garments-list-table').DataTable();
	              $("#orchestra-garments-list-table_filter input").addClass('form-control input-sm');
	              $("#orchestra-garments-list-table_filter input").attr('placeholder', 'Data Search');
	              $('#orchestra-garments-list-table_length select').addClass('form-control input-sm');

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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             statusCode: {

                  /** [Show response status based on HTTP status code] */
                  200: function() {
                       $('.snackbar_span').html("Garment Added Successfully");
                       showSnackbar();
                       $('#addOrchestraGarmentsModal').modal('hide');
                       orchestraSettings()
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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
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
           },
           method:"put",
           data:{_token:csrf_token,id:id,name:name},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {

                /** [Show response status based on HTTP status code] */
                200: function() {
                       $('.snackbar_span').html("Garment Updated Successfully");
                       showSnackbar();
                       $('#editOrchestraGarmentsModal').modal('hide');
                       orchestraSettings()
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
   * Makes a Garment Enabled.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeGarmentEnabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-garment-enabled",
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
                        $('.snackbar_span').html('Garment is enabled ');
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
                 orchestraSettings();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }

 /**
  * Makes a Garment Diabled
  * @param id,csrf_token
  * @author <praveen>
  */
  function makeGarmentDisabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-garment-disabled",
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
                         $('.snackbar_span').html('Garment is disabled ');
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
                 orchestraSettings();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }
  /**
  * To Show Orchestra age group.
  * @param id
  * @author <praveen>
  *
  */
  function ageGroup() {
    try{
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.tesco-sidebar').hide();
      $('.orchestra-sidebar').show();
      
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/orchestra/settings/age-group",
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
                  var orchestra_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraAgeGroupModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Age Group</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Age Groups</h3></div><table id="orchestra-age-group-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Age Group Name</th><th>Color</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                 $.each(data.orchestra_agegroups, function(i, item){
                  var index = i+1;
                  orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.age_group_name+'</td><td>'+item.color+'</td>';
                  if(item.is_enabled === 1)
                  {
                   orchestra_metadata += '<td><span class="label label-success">Enabled</span></td>';
                  }
                  else
                  {
                   orchestra_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                  }
                  if(item.is_enabled === 1)
                  {
                   orchestra_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_age_group_disabled" data-placement="left"></i></a>';
                  }
                  else
                  {
                   orchestra_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_age_group_enabled" data-placement="left"></i></a>';
                  }
                  orchestra_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-orchestra-age-group filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                 });
                 orchestra_metadata += '</tbody></table></div>';
                 $('.content').html(orchestra_metadata)
                 $('.edit-orchestra-age-group').tooltip('show')
                 $('.edit-orchestra-age-group').tooltip('toggle')
                 $('.status').tooltip('show')
                 $('.status').tooltip('toggle')
                 $('#orchestra-age-group-list-table').DataTable();
                 $("#orchestra-age-group-list-table_filter input").addClass('form-control input-sm');
                 $("#orchestra-age-group-list-table_filter input").attr('placeholder', 'Data Search');
                 $('#orchestra-age-group-list-table_length select').addClass('form-control input-sm');
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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             statusCode: {
 
                  /** [Show response status based on HTTP status code] */
                  200: function() {
                       $('.snackbar_span').html("Age Group Added Successfully");
                       showSnackbar();
                       $('#addOrchestraAgeGroupModal').modal('hide');
                       ageGroup()
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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
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
           },
           method:"put",
           data:{_token:csrf_token,id:id,name:name,color:color},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {

                /** [Show response status based on HTTP status code] */
                200: function() {
                       $('.snackbar_span').html("Age Group Updated Successfully");
                       showSnackbar();
                       $('#editOrchestraAgeGroupModal').modal('hide');
                       ageGroup()
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
   * Makes a AgeGroup Enabled.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeAgeGroupEnabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-agegroup-enabled",
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
                        $('.snackbar_span').html('AgeGroup is enabled ');
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
                 ageGroup();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }

 /**
  * Makes a AgeGroup Diabled
  * @param id,csrf_token
  * @author <praveen>
  */
  function makeAgeGroupDisabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-agegroup-disabled",
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
                         $('.snackbar_span').html('AgeGroup is disabled ');
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
                 ageGroup();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }
/**
* Function to Orchestra countries home page
* @author <praveen>
*/
function countries() {
  try{
    $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
    $('.tesco-sidebar').hide();
    $('.orchestra-sidebar').show();
    
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/admin/orchestra/settings/countries",
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
                var orchestra_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraCountryModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Country Name</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Countries</h3></div><table id="orchestra-countries-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>English</th><th>French</th><th>Spanish</th><th>Portuguese</th><th>Italian</th><th>Dutch</th><th>German</th><th>Russian</th><th>Chinese</th><th>Arabic</th><th>Greek</th><th>Turkish</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
               $.each(data.orchestra_countries, function(i, item){
                var index = i+1;
                orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.english+'</td><td>'+item.french+'</td><td>'+item.spanish+'</td><td>'+item.portuguese+'</td><td>'+item.italian+'</td><td>'+item.dutch+'</td><td>'+item.german+'</td><td>'+item.russian+'</td><td>'+item.chinese+'</td><td>'+item.arabic+'</td><td>'+item.greek+'</td><td>'+item.turkish+'</td>';
                if(item.is_enabled === 1)
                {
                 orchestra_metadata += '<td><span class="label label-success">Enabled</span></td>';
                }
                else
                {
                 orchestra_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                }
                if(item.is_enabled === 1)
                {
                 orchestra_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_country_disabled" data-placement="left"></i></a>';
                }
                else
                {
                 orchestra_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_country_enabled" data-placement="left"></i></a>';
                }
               orchestra_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-orchestra-country filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
               });
               orchestra_metadata += '</tbody></table></div>';
               $('.content').html(orchestra_metadata)
                
                /** [ Enable horizontal scroll for table ] */
                $('#orchestra-countries-list-table').DataTable({
                  'scrollX':true
                });
                $('.edit-orchestra-country').tooltip('show')
                $('.edit-orchestra-country').tooltip('toggle')
                $('.status').tooltip('show')
                $('.status').tooltip('toggle')
                $("#orchestra-countries-list-table_filter input").addClass('form-control input-sm');
                $("#orchestra-countries-list-table_filter input").attr('placeholder', 'Data Search');
                $('#orchestra-countries-list-table_length select').addClass('form-control input-sm');

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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             statusCode: {

                  /** [ Show response status based on HTTP status code ] */
                  200: function() {
                       $('.snackbar_span').html("Country Name Added Successfully");
                       showSnackbar();
                       $('#addOrchestraCountryModal').modal('hide');
                       countries()
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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
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
           },
           method:"put",
           data: {_token:csrf_token,id:id,english:english,french:french,spanish:spanish,portuguese:portuguese,italian:italian,dutch:dutch,german:german,russian:russian,chinese:chinese,arabic:arabic,greek:greek,turkish:turkish},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {

                /** [ Show response status based on HTTP status code ] */
                200: function() {
                       $('.snackbar_span').html("Country Name Updated Successfully");
                       showSnackbar();
                       $('#editOrchestraCountryModal').modal('hide');
                       countries()
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
   * Makes a Country Enabled.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeCountryEnabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-country-enabled",
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
               complete: function () {
                 countries();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }

 /**
  * Makes a Country Diabled
  * @param id,csrf_token
  * @author <praveen>
  */
  function makeCountryDisabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-country-disabled",
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
               complete: function () {
                 countries();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }
  /**
  * Function to show Orchestra product family home page
  * @author <praveen>
  */
  function productFamily() {
    try{
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.tesco-sidebar').hide();
      $('.orchestra-sidebar').show();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/orchestra/settings/productfamily",
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
                  var orchestra_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraProductFamilyModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Product Family</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Product Families</h3></div><table id="orchestra-productfamily-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Product Family Code</th><th>English</th><th>French</th><th>Arabic</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                 $.each(data.orchestra_product_family, function(i, item){
                    var index = i+1;
                    orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.code+'</td><td>'+item.english+'</td><td>'+item.french+'</td><td>'+item.arabic+'</td>';
                    if(item.is_enabled === 1)
                    {
                     orchestra_metadata += '<td><span class="label label-success">Enabled</span></td>';
                    }
                    else
                    {
                     orchestra_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                    }
                    if(item.is_enabled === 1)
                    {
                     orchestra_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_productfamily_disabled" data-placement="left"></i></a>';
                    }
                    else
                    {
                     orchestra_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_productfamily_enabled" data-placement="left"></i></a>';
                    }
                    orchestra_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-orchestra-productfamily filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                 });
                 orchestra_metadata += '</tbody></table></div>';
                 $('.content').html(orchestra_metadata)
                 
                  $('#orchestra-productfamily-list-table').DataTable({
                    'scrollX':true
                  });
                  $('.edit-orchestra-productfamily').tooltip('show')
                  $('.edit-orchestra-productfamily').tooltip('toggle')
                  $('.status').tooltip('show')
                  $('.status').tooltip('toggle')
                  $("#orchestra-productfamily-list-table_filter input").addClass('form-control input-sm');
                  $("#orchestra-productfamily-list-table_filter input").attr('placeholder', 'Data Search');
                  $('#orchestra-productfamily-list-table_length select').addClass('form-control input-sm');

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
               tryCount : 0,
               retryLimit : 2,
               beforeSend: function () {
                 $(".loader").show();
               },
               statusCode: {

                    /** [ Show response status based on HTTP status code ] */
                    200: function() {
                         $('.snackbar_span').html("Product Family Added Successfully");
                         showSnackbar();
                         $('#addOrchestraProductFamilyModal').modal('hide');
                         productFamily()()
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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
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
           },
           method:"put",
           data: {_token:csrf_token,id:id,english:english,french:french,arabic:arabic,code:code},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {

                /** [ Show response status based on HTTP status code ] */
                200: function() {
                       $('.snackbar_span').html("Product Family Updated Successfully");
                       showSnackbar();
                       $('#editOrchestraProductFamilyModal').modal('hide');
                       productFamily()()
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
   * Makes a ProductFamily Enabled.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeProductFamilyEnabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-productfamily-enabled",
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
                        $('.snackbar_span').html('Product Family is enabled ');
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
                 productFamily();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }

 /**
  * Makes a ProductFamily Diabled
  * @param id,csrf_token
  * @author <praveen>
  */
  function makeProductFamilyDisabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-productfamily-disabled",
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
                         $('.snackbar_span').html('Product Family is disabled ');
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
                 productFamily();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }
  /**
    * Function to Orchestra special instructions home page
    * @author <praveen>
    */
    function specialInstruction() {
      try{
        $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
        $('.tesco-sidebar').hide();
        $('.orchestra-sidebar').show();
        
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: base_url + "/admin/orchestra/settings/special-instructions",
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
                    var orchestra_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraSpecialInstructionModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Special Instruction</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Special Instructions</h3></div><table id="orchestra-special-instruction-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>English</th><th>French</th><th>Spanish</th><th>Portuguese</th><th>Italian</th><th>Dutch</th><th>German</th><th>Russian</th><th>Chinese</th><th>Arabic</th><th>Greek</th><th>Turkish</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.orchestra_specialinstructions, function(i, item){
                    var index = i+1;
                    orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.english+'</td><td>'+item.french+'</td><td>'+item.spanish+'</td><td>'+item.portuguese+'</td><td>'+item.italian+'</td><td>'+item.dutch+'</td><td>'+item.german+'</td><td>'+item.russian+'</td><td>'+item.chinese+'</td><td>'+item.arabic+'</td><td>'+item.greek+'</td><td>'+item.turkish+'</td>';
                    if(item.is_enabled === 1)
                    {
                     orchestra_metadata += '<td><span class="label label-success">Enabled</span></td>';
                    }
                    else
                    {
                     orchestra_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                    }
                    if(item.is_enabled === 1)
                    {
                     orchestra_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_special_instruction_disabled" data-placement="left"></i></a>';
                    }
                    else
                    {
                     orchestra_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_special_instruction_enabled" data-placement="left"></i></a>';
                    }

                    orchestra_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-orchestra-special-instruction filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                   });
                    orchestra_metadata += '</tbody></table></div>';
                    $('.content').html(orchestra_metadata)
                   
                    $('#orchestra-special-instruction-list-table').DataTable({
                      'scrollX':true
                    });
                    $('.edit-orchestra-special-instruction').tooltip('show')
                    $('.edit-orchestra-special-instruction').tooltip('toggle')
                    $('.status').tooltip('show')
                    $('.status').tooltip('toggle')
                    $("#orchestra-special-instruction-list-table_filter input").addClass('form-control input-sm');
                    $("#orchestra-special-instruction-list-table_filter input").attr('placeholder', 'Data Search');
                    $('#orchestra-special-instruction-list-table_length select').addClass('form-control input-sm');

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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             statusCode: {

                  /** [ Show response status based on HTTP status code ] */
                  200: function() {
                       $('.snackbar_span').html("Special Instruction Added Successfully");
                       showSnackbar();
                       $('#addOrchestraSpecialInstructionModal').modal('hide');
                       specialInstruction() 
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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
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
           },
           method:"put",
           data: {_token:csrf_token,id:id,english:english,french:french,spanish:spanish,portuguese:portuguese,italian:italian,dutch:dutch,german:german,russian:russian,chinese:chinese,arabic:arabic,greek:greek,turkish:turkish},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {

                /** [ Show response status based on HTTP status code ] */
                200: function() {
                       $('.snackbar_span').html("Special Instruction Updated Successfully");
                       showSnackbar();
                       $('#editOrchestraSpecialInstructionModal').modal('hide');
                       specialInstruction()
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
   * Makes a SpecialInstruction Enabled.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeSpecialInstructionEnabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-special-instruction-enabled",
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
                        $('.snackbar_span').html('Special Instruction is enabled ');
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
                 specialInstruction();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }

 /**
  * Makes a SpecialInstruction Diabled
  * @param id,csrf_token
  * @author <praveen>
  */
  function makeSpecialInstructionDisabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-special-instruction-disabled",
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
                         $('.snackbar_span').html('Special Instruction is disabled ');
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
                 specialInstruction();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }
  /**
    * Function to Orchestra compositions home page
    * @author <praveen>
    */
    function compositions() {
      try{
        $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
        $('.tesco-sidebar').hide();
        $('.orchestra-sidebar').show();
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: base_url + "/admin/orchestra/settings/compositions",
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
                    var orchestra_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraCompositionModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Composition</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Compositions</h3></div><table id="orchestra-composition-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>English</th><th>French</th><th>Spanish</th><th>Portuguese</th><th>Italian</th><th>Dutch</th><th>German</th><th>Russian</th><th>Chinese</th><th>Arabic</th><th>Greek</th><th>Turkish</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.orchestra_compositions, function(i, item){
                    var index = i+1;
                    orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.english+'</td><td>'+item.french+'</td><td>'+item.spanish+'</td><td>'+item.portuguese+'</td><td>'+item.italian+'</td><td>'+item.dutch+'</td><td>'+item.german+'</td><td>'+item.russian+'</td><td>'+item.chinese+'</td><td>'+item.arabic+'</td><td>'+item.greek+'</td><td>'+item.turkish+'</td>';
                    if(item.is_enabled === 1)
                    {
                     orchestra_metadata += '<td><span class="label label-success">Enabled</span></td>';
                    }
                    else
                    {
                     orchestra_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                    }
                    if(item.is_enabled === 1)
                    {
                     orchestra_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_composition_list_disabled" data-placement="left"></i></a>';
                    }
                    else
                    {
                     orchestra_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_composition_list_enabled" data-placement="left"></i></a>';
                    }

                    orchestra_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-orchestra-composition filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                   });
                   orchestra_metadata += '</tbody></table></div>';
                   $('.content').html(orchestra_metadata)
                   
                    $('#orchestra-composition-list-table').DataTable({
                      'scrollX':true
                    });
                    $('.edit-orchestra-composition').tooltip('show')
                    $('.edit-orchestra-composition').tooltip('toggle')
                    $('.status').tooltip('show')
                    $('.status').tooltip('toggle')
                    $("#orchestra-composition-list-table_filter input").addClass('form-control input-sm');
                    $("#orchestra-composition-list-table_filter input").attr('placeholder', 'Data Search');
                    $('#orchestra-composition-list-table_length select').addClass('form-control input-sm');

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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             statusCode: {

                  /** [ Show response status based on HTTP status code ] */
                  200: function() {
                       $('.snackbar_span').html("Composition Added Successfully");
                       showSnackbar();
                       $('#addOrchestraCompositionModal').modal('hide');
                       compositions() 
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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
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
           },
           method:"put",
           data: {_token:csrf_token,id:id,english:english,french:french,spanish:spanish,portuguese:portuguese,italian:italian,dutch:dutch,german:german,russian:russian,chinese:chinese,arabic:arabic,greek:greek,turkish:turkish},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {

                /** [ Show response status based on HTTP status code ] */
                200: function() {
                       $('.snackbar_span').html("Composition Updated Successfully");
                       showSnackbar();
                       $('#editOrchestraCompositionModal').modal('hide');
                       compositions()
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
   * Makes a Composition Enabled.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeCompositionEnabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-composition-enabled",
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
                        $('.snackbar_span').html('Composition is enabled ');
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
                 compositions();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }

 /**
  * Makes a Composition Diabled
  * @param id,csrf_token
  * @author <praveen>
  */
  function makeCompositionDisabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-composition-disabled",
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
                         $('.snackbar_span').html('Composition is disabled ');
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
                 compositions();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }
  /**
    * Function to Orchestra parts home page
    * @author <praveen>
    */
    function parts() {
      try{
        $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
        $('.tesco-sidebar').hide();
        $('.orchestra-sidebar').show(); 
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: base_url + "/admin/orchestra/settings/parts",
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
                    var orchestra_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraPartModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Part</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Parts</h3></div><table id="orchestra-part-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>English</th><th>French</th><th>Spanish</th><th>Portuguese</th><th>Italian</th><th>Dutch</th><th>German</th><th>Russian</th><th>Chinese</th><th>Arabic</th><th>Greek</th><th>Turkish</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.orchestra_parts, function(i, item){
                    var index = i+1;
                    orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.english+'</td><td>'+item.french+'</td><td>'+item.spanish+'</td><td>'+item.portuguese+'</td><td>'+item.italian+'</td><td>'+item.dutch+'</td><td>'+item.german+'</td><td>'+item.russian+'</td><td>'+item.chinese+'</td><td>'+item.arabic+'</td><td>'+item.greek+'</td><td>'+item.turkish+'</td>';
                    if(item.is_enabled === 1)
                    {
                     orchestra_metadata += '<td><span class="label label-success">Enabled</span></td>';
                    }
                    else
                    {
                     orchestra_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                    }
                    if(item.is_enabled === 1)
                    {
                     orchestra_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_part_disabled" data-placement="left"></i></a>';
                    }
                    else
                    {
                     orchestra_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_part_enabled" data-placement="left"></i></a>';
                    }

                    orchestra_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-orchestra-part filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                   });
                   orchestra_metadata += '</tbody></table></div>';
                   $('.content').html(orchestra_metadata)
                   
                    $('#orchestra-part-list-table').DataTable({
                      'scrollX':true
                    });
                    $('.edit-orchestra-part').tooltip('show')
                    $('.edit-orchestra-part').tooltip('toggle')
                    $('.status').tooltip('show')
                    $('.status').tooltip('toggle')
                    $("#orchestra-part-list-table_filter input").addClass('form-control input-sm');
                    $("#orchestra-part-list-table_filter input").attr('placeholder', 'Data Search');
                    $('#orchestra-part-list-table_length select').addClass('form-control input-sm');

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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             statusCode: {

                  /** [ Show response status based on HTTP status code ] */
                  200: function() {
                       $('.snackbar_span').html("Part Added Successfully");
                       showSnackbar();
                       $('#addOrchestraPartModal').modal('hide');
                       parts(); 
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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
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
           },
           method:"put",
           data: {_token:csrf_token,id:id,english:english,french:french,spanish:spanish,portuguese:portuguese,italian:italian,dutch:dutch,german:german,russian:russian,chinese:chinese,arabic:arabic,greek:greek,turkish:turkish},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {

                /** [ Show response status based on HTTP status code ] */
                200: function() {
                       $('.snackbar_span').html("Part Updated Successfully");
                       showSnackbar();
                       $('#editOrchestraPartModal').modal('hide');
                       parts();
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
   * Makes a Part Enabled.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makePartEnabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-part-enabled",
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
                        $('.snackbar_span').html('Part is enabled ');
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
                 parts();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }

 /**
  * Makes a Part Diabled
  * @param id,csrf_token
  * @author <praveen>
  */
  function makePartDisabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-part-disabled",
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
                         $('.snackbar_span').html('Part is disabled ');
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
                 parts();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }
  /**
    * Function to Orchestra size chart  home page
    * @author <praveen>
    */
    function sizeChart() {
      try{
        $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
        $('.tesco-sidebar').hide();
        $('.orchestra-sidebar').show();
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: base_url + "/admin/orchestra/settings/size-chart",
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
                    var orchestra_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraSizeChartModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Size Chart</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Size Charts</h3></div><table id="orchestra-size-chart-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Age</th><th>Centimeter(cm)</th><th>Inches(inch)</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.orchestra_size_charts, function(i, item){
                    var index = i+1;
                    orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.age+'</td><td>'+item.cm+'</td><td>'+item.in+'</td>';
                    if(item.is_enabled === 1)
                    {
                     orchestra_metadata += '<td><span class="label label-success">Enabled</span></td>';
                    }
                    else
                    {
                     orchestra_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                    }
                    if(item.is_enabled === 1)
                    {
                     orchestra_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_size_chart_disabled" data-placement="left"></i></a>';
                    }
                    else
                    {
                     orchestra_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_size_chart_enabled" data-placement="left"></i></a>';
                    }
                    orchestra_metadata += '<a href="#"><i class="fa fa-pencil fa-button edit-orchestra-size-chart filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                   });
                   orchestra_metadata += '</tbody></table></div>';
                   $('.content').html(orchestra_metadata)
                   
                    $('#orchestra-size-chart-list-table').DataTable({
                      'scrollX':true
                    });
                    $('.edit-orchestra-size-chart').tooltip('show')
                    $('.edit-orchestra-size-chart').tooltip('toggle')
                    $('.status').tooltip('show')
                    $('.status').tooltip('toggle')
                    $("#orchestra-size-chart-list-table_filter input").addClass('form-control input-sm');
                    $("#orchestra-size-chart-list-table_filter input").attr('placeholder', 'Data Search');
                    $('#orchestra-size-chart-list-table_length select').addClass('form-control input-sm');
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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
             },
             statusCode: {

                  /** [ Show response status based on HTTP status code ] */
                  200: function() {
                       $('.snackbar_span').html("Size Chart Added Successfully");
                       showSnackbar();
                       $('#addOrchestraSizeChartModal').modal('hide');
                       sizeChart(); 
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
             tryCount : 0,
             retryLimit : 2,
             beforeSend: function () {
               $(".loader").show();
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
           },
           method:"put",
           data: {_token:csrf_token,id:id,age:age,cm:cm,in:inch},
           tryCount : 0,
           retryLimit : 2,
           statusCode: {

                /** [ Show response status based on HTTP status code ] */
                200: function() {
                       $('.snackbar_span').html("Size Chart Updated Successfully");
                       showSnackbar();
                       $('#editOrchestraSizeChartModal').modal('hide');
                       sizeChart();
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
   * Makes a SizeChart Enabled.
   * @param id,csrf_token
   * @author <praveen>
   */
   function makeOrchestraSizeChartEnabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-size-chart-enabled",
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
                 sizeChart();
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
  function makeOrchestraSizeChartDisabled(id,csrf_token)
   {
    try{
          if(isSubmitting) 
          {
              return;
          }
          isSubmitting = true;
          $.ajax({
               url:base_url + "/admin/orchestra/make-size-chart-disabled",
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
                 sizeChart();
                 $(".loader").hide();
               }
           })
    } catch (e) {
     console.log("Error:"+e)
    }
   }
  /**
  * Function to Orchestra orchestra Symbols
  * @author <praveen>
  */
function orchestraSymbols() {
    try{
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.tesco-sidebar').hide();
      $('.orchestra-sidebar').show();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/orchestra/settings/symbols",
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
                  var orchestra_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraSymbolsModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Symbol</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Symbols</h3></div><table id="orchestra-symbols-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Cofreet Symbols</th><th>Astms Symbol</th><th>Care Instruction</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.orchestra_care_symbols, function(i, item){
                    var index = i+1;
                    orchestra_metadata += '<tr data-category="'+item.category_id+'" id="'+item.id+'"><td>'+index+'</td><td class="td_cofreet_symbols"><img width="30px" src="'+item.cofreet_symbols+'"></td><td class="td_astms_symbol"><img width="30px" src="'+item.astms_symbol+'"></td><td class="td_care_instruction">'+item.care_instruction+'</td>';
                    if(item.is_enabled === 1)
                    {
                     orchestra_metadata += '<td><span class="label label-success">Enabled</span></td>';
                    }
                    else
                    {
                     orchestra_metadata += '<td><span class="label label-danger">Disabled</span></td>';
                    }
                    if(item.is_enabled === 1)
                    {
                     orchestra_metadata += '<td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Disabled" id="make_symbols_disabled" data-placement="left"></i></a>';
                    }
                    else
                    {
                     orchestra_metadata += '<td><a href="#"><i class="fa fa-check filterbtn options fa-button status" aria-hidden="true" title="Make Enabled" id="make_symbols_enabled" data-placement="left"></i></a>';
                    }
                    orchestra_metadata += '</td></tr>';
                   });
                   orchestra_metadata += '</tbody></table></div>';
                   $('.content').html(orchestra_metadata)
                   $('.edit-orchestra-symbols').tooltip('show')
                   $('.edit-orchestra-symbols').tooltip('toggle')
                   $('.status').tooltip('show')
                   $('.status').tooltip('toggle')
                   $('#orchestra-symbols-list-table').DataTable();
                   $("#orchestra-symbols-list-table_filter input").addClass('form-control input-sm');
                   $("#orchestra-symbols-list-table_filter input").attr('placeholder', 'Data Search');
                   $('#orchestra-symbols-list-table_length select').addClass('form-control input-sm');
  
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
  * Function to Orchestra Settings Symbols
  * @author <praveen>
  */
function addOrchestraSymbols(cofreet_symbols, astms_symbol, category_id, care_instruction) {
    try{
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        var form_data = new FormData();

        form_data.append('cofreet_symbols', cofreet_symbols);
        form_data.append('astms_symbol', astms_symbol);
        form_data.append('category_id', category_id);
        form_data.append('care_instruction', care_instruction);
        form_data.append('_token', csrf_token);
       

        $.ajax({
            url: base_url + "/admin/orchestra/settings/addOrchestraSymbols",
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
                /** [ Show response status based on HTTP status code ] */
                200: function() {
                        $('.snackbar_span').html('Symbols Added Successfully');
                        showSnackbar();
                        $('#addOrchestraSymbolsModal').modal('hide');
                        orchestraSymbols();
                },
                400: function() {
                        $('.snackbar_span').html('Something went wrong');
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
        });
    } catch (e) {
    console.log("Error:"+e)
  }
}

/**
  * Function to edit Orchestra Settings Symbols
  * @author <Sumanth>
  */
  function editOrchestraSymbols(id, cofreet_symbols, astms_symbol, category_id, care_instruction) {
    try{
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        var form_data = new FormData();
        form_data.append('id', id);
        form_data.append('cofreet_symbols', cofreet_symbols);
        form_data.append('astms_symbol', astms_symbol);
        form_data.append('category_id', category_id);
        form_data.append('care_instruction', care_instruction);
        form_data.append('_token', csrf_token);
        $.ajax({
            url: base_url + "/admin/orchestra/settings/editOrchestraSymbols",
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
                /** [ Show response status based on HTTP status code ] */
                200: function() {
                        $('.snackbar_span').html('Symbols Updated Successfully');
                        showSnackbar();
                        $('#editOrchestraSymbolsModal').modal('hide');
                        orchestraSymbols();
                },
                400: function() {
                        $('.snackbar_span').html('Something went wrong');
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
        });
    } catch (e) {
    console.log("Error:"+e)
  }
}

  /**
  * Makes a Symbol Enabled.
  * @param id,csrf_token
  * @author <praveen>
  */
  function makeSymbolEnabled(id,csrf_token)
  {
   try{
         if(isSubmitting) 
         {
             return;
         }
         isSubmitting = true;
         $.ajax({
              url:base_url + "/admin/orchestra/make-symbol-enabled",
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
              complete: function () {
                orchestraSymbols();
                $(".loader").hide();
              }
          })
   } catch (e) {
    console.log("Error:"+e)
   }
  }

/**
 * Makes a Symbol Diabled
 * @param id,csrf_token
 * @author <praveen>
 */
 function makeSymbolDisabled(id,csrf_token)
  {
   try{
         if(isSubmitting) 
         {
             return;
         }
         isSubmitting = true;
         $.ajax({
              url:base_url + "/admin/orchestra/make-symbol-disabled",
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
              complete: function () {
                orchestraSymbols();
                $(".loader").hide();
              }
          })
   } catch (e) {
    console.log("Error:"+e)
   }
  } 

// edit-orchestra-symbols
/**
  * Function to edit Orchestra Settings Symbols
  * @author <sumanth>
  */
  $(document).on("click", ".edit-orchestra-symbols", function(){
    
    var trid = $(this).closest('tr').attr('id');
    var cat_id = $(this).closest('tr').attr('data-category');
    var cat_text = $(this).closest('tr').find('.td_care_instruction').text();
    var cofreet_img = $(this).closest('tr').find('.td_cofreet_symbols').find('img').attr('src');
    var astms_img = $(this).closest('tr').find('.td_astms_symbol').find('img').attr('src');


    // $('#myModal').modal('toggle');
    $('#editOrchestraSymbolsModal').modal('show');
    // $('#myModal').modal('hide');
    $('#edit_orchestra_symbols_id').val(trid);
    $('#edit_symbol_category').val(cat_id);
    $('#edit_care_instruction').val(cat_text);
    $('#astms_image').attr('src', astms_img);
    $('#cofreet_image').attr('src', cofreet_img);
});

//  preview selected image
/**
  * Function to preview selected image in Orchestra Settings Symbols
  * @author <sumanth>
  */

function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
    
        reader.onload = function(e) {
          $('#add_'+input.id).attr('src', e.target.result);
        }
    
        reader.readAsDataURL(input.files[0]);
      }
    }
    
    $("#cofreet_symbol").add("#astms_symbol").add("#edit_cofreet_symbol").add("#edit_astms_symbol").change(function() {
            readURL(this);
            if(".hidden_"+this.id != "undefined"){
                $(".hidden_"+this.id).removeClass('hidden')
            }

    });

/**
  * Function to orchestra Tax code Settings home page
  * @author <praveen>
  */
function orchestraTaxCode() {
    try{
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.tesco-sidebar').hide();
      $('.orchestra-sidebar').show();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/admin/orchestra/settings/tax-code",
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
                  var orchestra_metadata = '<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div><div class="main-sub-menu"><div class="action-group pull-right sub-menu-button"><a href="#" data-target="#addOrchestraTaxCodesModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-plus"></i> Add Tax Code</a></div></div><div class="template-block"><div class="top-content"><h3 class="content-heading">Tax Codes</h3></div><table id="orchestra-taxcodes-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%"><thead><tr><th>Sl. No</th><th>Tax Type</th><th>Tax (%)</th><th>Tax Code</th><th>Actions</th></tr></thead><tbody>';
                   $.each(data.orchestra_taxcodes, function(i, item){
                    var index = i+1;
                    orchestra_metadata += '<tr id="'+item.id+'"><td>'+index+'</td><td>'+item.tax_type+'</td><td>'+item.tax+'</td><td>'+item.tax_code+'</td><td><a href="#"><i class="fa fa-pencil fa-button edit-orchestra-taxcodes filterbtn options" title="Edit" data-placement="left"></i></a></td></tr>';
                   });
                   orchestra_metadata += '</tbody></table></div>';
                   $('.content').html(orchestra_metadata)
                   $('.edit-orchestra-taxcodes').tooltip('show')
                   $('.edit-orchestra-taxcodes').tooltip('toggle')
                   $('.status').tooltip('show')
                   $('.status').tooltip('toggle')
                   $('#orchestra-taxcodes-list-table').DataTable();
                   $("#orchestra-taxcodes-list-table_filter input").addClass('form-control input-sm');
                   $("#orchestra-taxcodes-list-table_filter input").attr('placeholder', 'Data Search');
                   $('#orchestra-taxcodes-list-table_length select').addClass('form-control input-sm');
  
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
  * Adds a orchestra taxcode.
  * @author <praveen>
  */
  function addOrchestraTaxCodes()
  {
     try{
          var tax_type   = $('#orch_tax_type').val();
          var tax_code   = $('#orch_tax_code').val();
          var tax        = $('#orch_tax').val();
          var csrf_token = $('meta[name="csrf-token"]').attr('content');
 
          $.ajax({
              url: base_url + "/admin/orchestra/add-orchestra-tax-code",
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
                        $('#addOrchestraTaxCodesModal').modal('hide');
                        orchestraTaxCode()
                   },
                   208: function() {
                        $('#orchestra_taxcode_result').removeAttr('style');
                        $('#orchestra_taxcode_result').html('Tax Code already exists.')
                        $("#orchestra_taxcode_result").addClass("alert alert-warning");
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
   * Function for get orchestra tax code details for editing
   * @param id
   * @author <praveen>
   *
   */
  function getOrchestraTaxCodesById(id)
  {
    try{
         if(isSubmitting) {
              return;
          }
          isSubmitting = true;
         $.ajax({
             url:base_url + "/admin/orchestra/get-orchestra-tax-code-details",
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
                 $('#edit_orchestra_tax_code_id').val(id);
                 $('#edit_orch_tax_code').val(data.orchestra_tax_codes[0].tax_code);
                 $('#edit_orch_tax_type').val(data.orchestra_tax_codes[0].tax_type);
                 $('#edit_orch_tax').val(data.orchestra_tax_codes[0].tax);
        
                 $('#editOrchestraTaxCodesModal').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#edit_orchestra_tax_codes_form').validator('validate');
                 $('#editOrchestraTaxCodesModal').modal('show');
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
  * Function for edit orchestra tax code details
  * @author <praveen>
  */
 function editOrchestraTaxCode()
 {
  try{
       var id        = $('#edit_orchestra_tax_code_id').val();
       var tax_type  = $('#edit_orch_tax_type').val();
       var tax_code  = $('#edit_orch_tax_code').val();
       var tax       = $('#edit_orch_tax').val();

       var csrf_token = $('meta[name="csrf-token"]').attr('content');
       $.ajax({
           url:base_url + "/admin/orchestra/update-orchestra-tax-code",
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
                       $('#editOrchestraTaxCodesModal').modal('hide');
                       orchestraTaxCode()
                },
                208: function() {
                     $('#edit_orchestra_taxcode_result').removeAttr('style');
                     $('#edit_orchestra_taxcode_result').html('Tax Code already exists.')
                     $("#edit_orchestra_taxcode_result").addClass("alert alert-warning");
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
   
