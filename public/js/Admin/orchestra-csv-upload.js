/**
 * CSV Upload JS file
 * @author <praveen>
 */

var csrf_token = $('meta[name="csrf-token"]').attr('content');
 /**
  * Get order details for UVC
  * @param obj
  * @author praveen
  */
  function getUvcData(table_name, uvc)
  {
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
                    $('.top-content').html('<h4 class="pull-left"><strong>Changed Orders for UVC '+data.uvc+'</strong></h4><br><br><hr>');

                    $('.sub-menu-button').html('<button class="btn btn-xs btn-default pull-right" type="button" onclick="goBack()" data-insert="true"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button>\
                    <button class="btn btn-xs btn-success " type="button" onclick="confirmUVCOrders()"><i class="fa fa-floppy-o" aria-hidden="true"></i> Confirm</button>\
                    <button class="btn btn-xs btn-danger" type="button" onclick="cancelUVCOrders()" style="margin-right: 5px;"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>');

                    var html = '<div class="loader"><div class="fa fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>\
                    <div class="col-md-12"" id="uvc-data-table">\
                    <table id="uvc-data-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">\
                      <thead>\
                        <tr>\
                          <th>#</th>\
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
                                <td data-uvc-value="'+data.uvc+'" data-table-name="'+table_name+'" data-uvc-tco-value="'+item.UVC_TCO+'"></td>\
                                <td>'+(key+1)+'</td>\
                                <td id="po_num"><a href="javaScript:void(0);" title="view more" class="view-more" data-placement="bottom"  data-backdrop="static" data-keyboard="false" onclick="getOrderComparisonData(this)" data-uvc="'+data.uvc+'" data-table="'+table_name+'" data-uvc-tco="'+item.UVC_TCO+'">'+item.BuyerPONo+'</a></td>\
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
                  var listTable = $('#uvc-data-list-table').DataTable({
                    'columnDefs': [
                       {
                          'targets': 0,
                          'checkboxes': {
                             'selectRow': true
                          }
                       }
                    ],
                    'select': {
                       'style': 'multi'
                    },
                    'order': [[1, 'asc']]
                 });
                 
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

  /**
   * Confirm changed orders for UVC
   * @param {*} obj 
   * @author praveen
   */
  function confirmUVCOrders()
  {
    if ($("input[class='dt-checkboxes']:checked").length === 0)
    {
        $('.snackbar_span').html("Select atleast one order.");
		    showSnackbar();
    }
    else
    {
     var my_array = [];
      /** [ Create array of selected rows data] */
      $('.dt-checkboxes').each(function (i, item) {
          if ($(this).is(':checked')) {
              var myObject = {}
              myObject.po = $(this).closest('tr').find('td[id="po_num"]').text();
              myObject.uvc = $(this).closest('td').data('uvc-value');
              myObject.table = $(this).closest('td').data('table-name');
              myObject.uvc_tco = $(this).closest('td').data('uvc-tco-value');
              my_array.push(myObject);
          }
      });
      
      $.ajax({
        url:base_url + "/admin/confirm-uvc-data",
        dataType:"json",
        method:"POST",
        data:{data_array:my_array,_token: csrf_token,},
        tryCount : 0,
        retryLimit : 2,
        beforeSend: function () {
          $(".loader").show();
        },
        success: function(data){
          $('.snackbar_span').html("Order Changes Has Been Confirmed.");
          showSnackbar();
          getUvcData(data.table, data.uvc)
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
     
    }
  }

 /**
   * Cancel changed orders for UVC
   * @author praveen
   */
  function cancelUVCOrders()
  {
    if ($("input[class='dt-checkboxes']:checked").length === 0)
    {
        $('.snackbar_span').html("Select atleast one order.");
		    showSnackbar();
    }
    else
    {
     var my_array = [];
      /** [ Create array of selected rows data] */
      $('.dt-checkboxes').each(function (i, item) {
          if ($(this).is(':checked')) {
              var myObject = {}
              myObject.po = $(this).closest('tr').find('td[id="po_num"]').text();
              myObject.uvc = $(this).closest('td').data('uvc-value');
              myObject.table = $(this).closest('td').data('table-name');
              myObject.uvc_tco = $(this).closest('td').data('uvc-tco-value');
              my_array.push(myObject);
          }
      });
      
      $.ajax({
        url:base_url + "/admin/cancel-uvc-data",
        dataType:"json",
        method:"POST",
        data:{data_array:my_array,_token: csrf_token,},
        tryCount : 0,
        retryLimit : 2,
        beforeSend: function () {
          $(".loader").show();
        },
        success: function(data){
          $('.snackbar_span').html("Order Changes Has Been Cancelled.");
          showSnackbar();
          getUvcData(data.table, data.uvc)
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
     
    }
  }

  /**
   * Back button functionality
   * @author praveen
   */
  function goBack()
  {
      $.ajax({
        url:base_url + "/admin/csv-conflicts-page",
        dataType:"json",
        method:"GET",
        tryCount : 0,
        retryLimit : 2,
        beforeSend: function () {
          $(".loader").show();
        },
        success: function(data){
          var html_content = '<div class="col-md-12" id="csv-conflicts-table">\
          <table id="csv-conflicts-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">\
            <thead>\
              <tr>\
                <th>Sl. No</th>\
                <th>UVC</th>\
                <th>Collection</th>\
                <th>Supplier Name</th>\
                <th>Theme</th>\
                <th>Changes</th>\
              </tr>\
            </thead>\
            <tbody>';
            $.each(data.result_array, function (key, item) {
              var table = item.table;
              var uvc = item.UVC;
              
              html_content += '<tr>\
                        <td>'+(key+1)+'</td>\
                        <td><a href="javaScript:void(0);" title="view more" class="view-more" data-placement="bottom"  data-backdrop="static" data-keyboard="false" onclick="getUvcData(\''+table+'\',\''+uvc+'\')" data-table="'+table+'">'+uvc+'</a></td>\
                        <td>'+item.Season_Code+'</td>\
                        <td>'+item.SupplierName+'</td>\
                        <td>'+item.Theme+'</td>\
                        <td>'+item.Changes+'</td>\
                      </tr>';
            });
            html_content += '</tbody>\
                            </table>\
                          </div>';
            $('.main-content').html(html_content);
            $('.top-content').html('');
            $('.sub-menu-button').html('');
            /** [ Data Tabeles scripts ] */
            $('#csv-conflicts-list-table').DataTable();
            $("#csv-conflicts-list-table_filter input").addClass('form-control input-sm');
            $("#csv-conflicts-list-table_filter input").attr('placeholder', 'Data Search');
            $('#csv-conflicts-list-table_length select').addClass('form-control input-sm');
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
  }

/**
 * Function for get csv changes for UVC
 * @author <praveen>
 *
 */
function getOrderComparisonData(obj)
{
  var table = obj.dataset.table;
  var uvc = obj.dataset.uvc;
  var uvc_tco = obj.dataset.uvcTco;
  var po = obj.text;

  try{
       $.ajax({
           url:base_url + "/admin/get-order-changes-data",
           dataType:"json",
           method:"get",
           data:{table:table, uvc:uvc, uvc_tco:uvc_tco, po:po},
           tryCount : 0,
           retryLimit : 2,
           beforeSend: function () {
             $(".loader").show();
           },
           success: function(data){
                 $('#view-changes-modal-body').html('');
                 var country_array = [{'EU' : 'Europe', 'CA' : 'Canada','CH' : 'Switzerland','DZ' : 'Algeria','MA' : 'Morocco','SA' : 'Arabia','MU' : 'Mauritius','US' : 'USA'}]
                 
                 var old_html='';
                  if(data.data.main_data.length != 0)
                  {
                    var po  = data.data.main_data[0].BuyerPONo === null?"-":data.data.main_data[0].BuyerPONo;
                    var uvc = data.data.main_data[0].UVC === null?"-":data.data.main_data[0].UVC;
                    var quantity = data.data.main_data[0].Quantity === null?"-":data.data.main_data[0].Quantity;
                    var barcode = data.data.main_data[0].BarcodeForPricesTags === null?"-":data.data.main_data[0].BarcodeForPricesTags;

                    old_html = '<ul class="list-group list-group-flush"><li class="list-group-item"><strong>PO Number &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong><span class="old-po-number" style="margin-left: 21px;">'+po+'</span></li><li class="list-group-item"><strong>UVC &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong><span class="old-uvc" style="margin-left: 22px;">'+uvc+'</span> </li><li class="list-group-item"><strong>Total Quantity&nbsp;&nbsp;&nbsp; :</strong><span class="old-quantity" style="margin-left: 22px;">'+quantity+'</span> </li><li class="list-group-item"><strong>Barcode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</strong><span class="old-barcode" style="margin-left: 24px;">'+barcode+'</span> </li></ul><h5><strong>Price Details</strong></h5><table class="table"><thead><tr><th scope="col">Sl. No</th><th scope="col" class="old-country">Country</th><th scope="col" class="old-price">Price</th><th scope="col" class="old-club-price">Club Price</th></tr></thead><tbody>';
                    $.each(data.data.main_data, function (key, item) {
                      var index = (key+1);
                      if(item.price_details === "true")
                      {
                        let Price = item.Price === null?'-':item.Price;
                        let Club_Price = item.Club_Price === null?'-':item.Club_Price;
                        let Price_Currency = item.Price === null?'':item.Currency;
                        let Club_Currency = item.Club_Price === null?'':item.Currency;
  
                        old_html += '<tr><th scope="row">'+index+'</th><td>'+country_array[0][item.Country_Code]+'</td><td>'+Price+' '+Price_Currency+'</td><td>'+Club_Price+' '+Club_Currency+'</td></tr>';
                      }
                      
                    });

                    old_html += '</tbody></table>';
                  }
                  else
                  {
                    old_html = '<div class="error-template"><h4><i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i></h4><h4>Data not found</h4></div>';
                  }
                  
                  $('.old-values-card-content').html(old_html);
                  
                  var new_po  = data.data.main_data[0].BuyerPONo === null?"-":data.data.main_data[0].BuyerPONo;
                  var new_uvc = data.data.main_data[0].UVC === null?"-":data.data.main_data[0].UVC;
                  var new_quantity = data.data.main_data[0].Quantity === null?"-":data.data.main_data[0].Quantity;
                  var new_barcode = data.data.main_data[0].BarcodeForPricesTags === null?"-":data.data.main_data[0].BarcodeForPricesTags;

                  var new_html = '<ul class="list-group list-group-flush"><li class="list-group-item"><strong>PO Number &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong><span class="new-po-number" style="margin-left: 23px;">'+new_po+'</span></li><li class="list-group-item"><strong>UVC &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</strong><span class="new-uvc" style="margin-left: 21px;">'+new_uvc+'</span> </li><li class="list-group-item"><strong>Total Quantity&nbsp;&nbsp; :</strong><span class="new-quantity" style="margin-left: 23px;">'+new_quantity+'</span> </li><li class="list-group-item"><strong>Barcode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :</strong><span class="new-barcode" style="margin-left: 23px;">'+new_barcode+'</span> </li></ul><h5><strong>Price Details</strong></h5><table class="table"><thead><tr><th scope="col">Sl. No</th><th scope="col" class="new-country">Country</th><th scope="col" class="new-price">Price</th><th scope="col" class="new-club-price">Club Price</th></tr></thead><tbody>';

                  $.each(data.data.temp_data, function (key, item) {
                    var index = (key+1);
                    if(item.price_details === "true")
                    {
                      let Price = item.Price === null?'-':item.Price;
                      let Club_Price = item.Club_Price === null?'-':item.Club_Price;
                      let Price_Currency = item.Price === null?'':item.Currency;
                      let Club_Currency = item.Club_Price === null?'':item.Currency;

                      new_html += '<tr><th scope="row">'+index+'</th><td>'+country_array[0][item.Country_Code]+'</td><td>'+Price+' '+Price_Currency+'</td><td>'+Club_Price+' '+Club_Currency+'</td></tr>';
                    }
                  });

                  new_html += '</tbody></table>';

                  $('.new-values-card-content').html(new_html);
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
