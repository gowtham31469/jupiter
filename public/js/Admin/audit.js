/**
 * Audit Trail JS file
 * @author <praveen>
 */

/**
 * Function for get audits changes by id
 * @param id
 * @author <praveen>
 *
 */
function getComparisonData(id)
{
  try{
       $.ajax({
           url:base_url + "/admin/audit-trail/get-comparison-details",
           dataType:"json",
           method:"get",
           data:{id:id},
           tryCount : 0,
           retryLimit : 2,
           beforeSend: function () {
             $(".loader").show();
           },
           success: function(data){
                 $('#view-changes-modal-body').html('');
                 switch (data.comparison_data[0].module) 
                 {
                   case 'Pricing':
                     viewPricingChanges(data);
                     break;
                   default:
                     console.log('default');
                     break;
                 }
                 /** [ Backdrop for bootstrap modal ] */
                 $('#ViewChangesModalCenter').modal({
                      backdrop: 'static',
                      keyboard: false
                 })
                 $('#ViewChangesModalCenter').modal('show');
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
 * Function for showing pricing changes.
 * @param json object
 * @author <praveen>
 *
 */
function viewPricingChanges(data)
{
  var old_data = $.parseJSON(data.comparison_data[0].old_values);
  var new_data = $.parseJSON(data.comparison_data[0].new_values);
  var old_html='';
  if(old_data.length != 0)
  {
    old_html = '<ul class="list-group list-group-flush"><li class="list-group-item"><strong>Company Name &nbsp;&nbsp;&nbsp;:</strong><span class="old-company-name" style="margin-left: 21px;">'+old_data.company+'</span></li><li class="list-group-item"><strong>Buyer Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong><span class="old-buyer-name" style="margin-left: 22px;">'+old_data.buyer+'</span> </li><li class="list-group-item"><strong>Template Name&nbsp;&nbsp;&nbsp; :</strong><span class="old-template-name" style="margin-left: 22px;">'+old_data.template+'</span> </li><li class="list-group-item"><strong>Custom Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</strong><span class="old-custome-name" style="margin-left: 24px;">'+old_data.custom_name+'</span> </li><li class="list-group-item"><strong>MOQ value&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</strong><span class="old-moq" style="margin-left: 25px;">'+old_data.moq+'</span></li></ul><h5><strong>Price Range</strong></h5><table class="table"><thead><tr><th scope="col">Sl. No</th><th scope="col" class="old-volume-from">Volume From</th><th scope="col" class="old-volume-to">Volume To</th><th scope="col" class="old-price">Price (&#x20b9;)</th></tr></thead><tbody>';
      $.each(old_data.price_range, function (key, item) {
         var index = (key+1);
         old_html += '<tr><th scope="row">'+index+'</th><td>'+item.volume_from+'</td><td>'+item.volume_to+'</td><td>'+item.price+'</td></tr>';
      });

      old_html += '</tbody></table>';
  }
  else
  {
     old_html = '<div class="error-template"><h4><i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i></h4><h4>Data not found</h4></div>';
  }
  
  $('.old-values-card-content').html(old_html);
  
  var new_html = '<ul class="list-group list-group-flush"><li class="list-group-item"><strong>Company Name &nbsp;&nbsp;:</strong><span class="new-company-name" style="margin-left: 23px;">'+new_data.company+'</span></li><li class="list-group-item"><strong>Buyer Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong><span class="new-buyer-name" style="margin-left: 21px;">'+new_data.buyer+'</span> </li><li class="list-group-item"><strong>Template Name&nbsp;&nbsp; :</strong><span class="new-template-name" style="margin-left: 23px;">'+new_data.template+'</span> </li><li class="list-group-item"><strong>Custom Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</strong><span class="new-custome-name" style="margin-left: 23px;">'+new_data.custom_name+'</span> </li><li class="list-group-item"><strong>MOQ value&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</strong><span class="new-moq" style="margin-left: 23px;">'+new_data.moq+'</span></li></ul><h5><strong>Price Range</strong></h5><table class="table"><thead><tr><th scope="col">Sl. No</th><th scope="col" class="new-volume-from">Volume From</th><th scope="col" class="new-volume-to">Volume To</th><th scope="col" class="new-price">Price (&#x20b9;)</th></tr></thead><tbody>';
    $.each(new_data.price_range, function (key, item) {
       var index = (key+1);
       new_html += '<tr><th scope="row">'+index+'</th><td>'+item.volume_from+'</td><td>'+item.volume_to+'</td><td>'+item.price+'</td></tr>';
    });

    new_html += '</tbody></table>';

    $('.new-values-card-content').html(new_html);
}