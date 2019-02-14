/**
  * Function to ERP Settings home page
  * @author <Praveen>
  */
 function tescoBrands() {
    try{
      $('.content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
      $('.erp-sidebar-main').show();
      
      $.ajax({
          url: base_url + "/erp/settings",
          type: "GET",
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