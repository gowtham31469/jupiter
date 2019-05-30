/**
 * CSV Upload JS file
 * @author <praveen>
 */


 /**
  * Get order details for UVC
  * @author praveen
  */
  function get_uvc_data(obj)
  {
    var table_name = obj.dataset.table;
    var uvc = obj.text;
    try{
            $.ajax({
                url:base_url + "/admin/get-uvc-data",
                dataType:"json",
                method:"get",
                data:{table_name:table_name,uvc:uvc},
                tryCount : 0,
                retryLimit : 2,
                beforeSend: function () {
                  $(".loader").show();
                },
                success: function(data){
                    //<h4 class="pull-left"><strong>Confirm Changed Orders for UVC '+data.uvc+'</strong></h4>\
                    $('.top-content').html('<h4 class="pull-left"><strong>Changed Orders for UVC '+data.uvc+'</strong></h4><br><br><hr>');

                    $('.sub-menu-button').html('<button class="btn btn-xs btn-default pull-right" type="button" onclick="" data-insert="true"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>\
                    <button class="btn btn-xs btn-success " type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i> Confirm</button>\
                    <button class="btn btn-xs btn-danger" type="button" onclick = "" style="margin-right: 5px;"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>');

                    var html = '<div class="loader"><div class="fa fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>\
                    <div class="col-md-12"" id="uvc-data-table">\
                    <table id="uvc-data-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">\
                      <thead>\
                        <tr>\
                          <th>Sl. No</th>\
                          <th>PO</th>\
                          <th>UVC TCO</th>\
                          <th>Collection</th>\
                          <th>Size</th>\
                          <th>Order Qty.</th>\
                          <th>PO Status</th>\
                          <th>Changes</th>\
                        </tr>\
                      </thead>\
                      <tbody>';
                      $.each(data.data, function (key, item) {
                        html += '<tr>\
                                <td>'+(key+1)+'</td>\
                                <td><a href="javaScript:void(0);" title="view more" class="view-more" data-placement="bottom"  data-backdrop="static" data-keyboard="false" onclick="get_uvc_data(this)" data-uvc="'+data.uvc+'" data-table="'+table_name+'" data-uvc-tco="'+item.UVC_TCO+'">'+item.BuyerPONo+'</a></td>\
                                <td>'+item.UVC_TCO+'</td>\
                                <td>'+item.Season_Code+'</td>\
                                <td>'+item.Size+'</td>\
                                <td>'+item.Quantity+'</td>\
                                <td><span class="label label-success">'+item.Po_Status_Code_Description+'</span></td>\
                                <td>'+item.Changes+'</td>\
                               </tr>';
                      });
                      html +='</tbody>\
                     </table>\
                  </div>';

                  $('.main-content').html(html);

                  /** [ Data Tabeles scripts ] */
                  $('#uvc-data-list-table').DataTable();
                  $("#uvc-data-list-table_filter input").addClass('form-control input-sm');
                  $("#uvc-data-list-table_filter input").attr('placeholder', 'Data Search');
                  $('#uvc-data-list-table_length select').addClass('form-control input-sm');
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