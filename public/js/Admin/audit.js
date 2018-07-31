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
               console.log(data)
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
  
}