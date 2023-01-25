
$(function() {
    /** Filter jobcard list form validation **/
    $('#nat_jobcard_list_filter_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            getNatJobcardsList();
        }
    });
});

/**
 * Get customers list based on buyer selection
 * @param buyer_id
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */

const getNATCustomersList = (evt) => {

    let selected_buyer_id = evt.value;
    if (selected_buyer_id != '') 
    {
        $.ajax({
            type: "post",
            url: base_url + "/erp/non-artwork-job-manager/get-customers-filter",
            data: {
                'buyer_id': selected_buyer_id,
                _token: csrf_token,
                'jobcard_created' : true
            },
            dataType: "json",
            beforeSend:  () => {
                $(".loader").show();
            },
            statusCode: {
                200: function(data) {
                    var html = '<option value="">SELECT A CUSTOMER</option>';
                    /** [ customers list selectpicker value populating ] */
                    $.each(data.customers, function (key, item) {
                        html += '<option value="' + item.id + '">' + item.company_name + '</option>';
                    });
                    $('#customer_filter').html(html);
                    $('#customer_filter').selectpicker('refresh');
                },
                400: function() {
                    $('.snackbar_span').html("Parameter is missing !!!");
                    showSnackbar();
                },
            },
            error: function (errorXHR, errorStatus, errorThrown) {
                console.log(errorThrown);
            },
            complete: function () {
                $(".loader").hide();
            }
        });
    }
    else
    {
        $('#customer_filter').html('<option value="">SELECT A CUSTOMER</option>');
        $('#customer_filter').selectpicker('refresh');
        $('#jobcard_id_filter').html('<option value="">SELECT A JOBCARD ID</option>');
        $('#jobcard_id_filter').selectpicker('refresh');
    }
    
}

/**
 * Get jobcard id list based on buyer and customer selection
 * @param buyer_id, customer_id
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */
const getNatJobcardIDList = (evt) => {
     try {
         let selected_customer_id = evt.value;
         let selected_buyer_id    = $('#buyer_filter option:selected').val();
         if (selected_customer_id != '') 
         {
             $.ajax({
                 url: base_url + "/erp/non-artwork-job-manager/get-nat-jobcard-ids",
                 type: 'GET',
                 data: {
                     selected_customer_id: selected_customer_id,
                     selected_buyer_id:selected_buyer_id
                 },
                 dataType: "json",
                 beforeSend: function () {
                     $(".loader").show();
                 },
                 statusCode: {
                     200: function(data) {
                         var html = '<option value="">SELECT A JOBCARD ID</option>';
                         /** [ jobcard id list selectpicker value populating ] */
                         $.each(data.jobcard_id_list, function (key, item) {
                             html += '<option value="' + key + '">' + item + '</option>';
                         });
                         $('#jobcard_id_filter').html(html);
                         $('#jobcard_id_filter').selectpicker('refresh');
                     },
                     400: function() {
                         $('.snackbar_span').html("Parameter is missing !!!");
                         showSnackbar();
                     },
                },
                 error: function (errorXHR, errorStatus, errorThrown) {
                     console.log(errorThrown);
                 },
                 complete: function () {
                     $(".loader").hide();
                 }
             })
         }
         else
         {
             $('#jobcard_id_filter').html('<option value="">SELECT A JOBCARD ID</option>');
             $('#jobcard_id_filter').selectpicker('refresh');
         }
     } catch (e) {
         console.log("Error:"+e)
     }
 }

  /** Filter jobcard list form validation **/
  $('#nat_jobcard_list_filter_form').validator().on('submit', function (e) {
      if (e.isDefaultPrevented()) {

      } else {
          e.preventDefault();
          getNatJobcardsList();
      }
  })

  /**
 * Get jobcard list based on buyer,customer and jobcard id selection
 * @param buyer_id, customer_id and jobcard_id
 * @author <Praveen> [praveen@wizroots.com]
 * 
 */

function getNatJobcardsList()
{
    try {
        let selected_buyer_id    = $('#buyer_filter option:selected').val();
        let selected_customer_id = $('#customer_filter option:selected').val();
        let selected_jobcard_id  = $('#jobcard_id_filter option:selected').val();
        if (selected_customer_id != '') 
        {
            $.ajax({
                url: base_url + "/erp/non-artwork-job-manager/get-nat-jobcard-list",
                type: 'GET',
                data: {
                    selected_customer_id: selected_customer_id,
                    selected_buyer_id:selected_buyer_id,
                    selected_jobcard_id:selected_jobcard_id
                },
                dataType: "json",
                beforeSend: function () {
                    $(".loader").show();
                },
                statusCode: {
                    200: function(data) {
                        var jc_id = data.jobcards_list.jobcard_id;
                        var html = '<tr id="'+jc_id+'">\
                        <td>1</td>\
                        <td>'+data.jobcards_list.work_order_id+'</td>\
                        <td>'+data.jobcards_list.company_name+'</td>\
                        <td>'+data.jobcards_list.buyer_name+'</td>\
                        <td><a href="#"><i class="fa fa-pencil fa-button filterbtn options edit-nat-jobcard" title="Edit Jobcard" data-placement="left"></i></a></td>\
                        </tr>';
                        $('#jobcard_list_table_body').html(html);            
                    },
                    400: function() {
                        $('.snackbar_span').html("Parameter is missing !!!");
                        showSnackbar();
                    },
               },
                error: function (errorXHR, errorStatus, errorThrown) {
                    console.log(errorThrown);
                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
        else
        {
            $('#jobcard_id_filter').html('<option value="">SELECT A JOBCARD ID</option>');
            $('#jobcard_id_filter').selectpicker('refresh');
        }
    } catch (e) {
        console.log("Error:"+e)
    }
}

/** Get edit jobcard details on click**/
$(document).on('click','.edit-nat-jobcard',function(e){
    var id = $(this).closest('tr').attr('id');
    e.preventDefault();
    
    if($('.buyers-card.active-buyer.active').attr("id") === 'spectrraprints'){
        getSpectrraJobcardDetails(id);
    } else {
        getNatJobcardDetails(id);
    }
    
});

/**
   * Function for get jobcard details for editing(for JGA company)
   * @param id
   * @author <praveen>
   *
   */
 function getNatJobcardDetails(id)
 {
   try{
        $.ajax({
            url:base_url + "/erp/non-artwork-job-manager/get-nat-jobcard-details",
            dataType:"json",
            method:"get",
            data:{id:id},
            beforeSend: function () {
              $(".loader").show();
            },
            success: function(data){

               $('.single-list-table-div').html('');

                jcPageSelectbox(data);
                $('.nat-jobcard-modal-title').text(data.modal_title);

                //hidden jobcard id for edit functionality
                $('#jobcard_id').val(id);
                $('#jc_company_name').text(data.jobcard_data.company);
                $('#company_address').text(data.jobcard_data.company_address);
                $('#company_logo').attr('src', data.jobcard_data.company_logo);
                $('#jc_company_name').attr('data-company-id', data.jobcard_data.company_id);
                $('#work_order').text(data.jobcard_data.work_order_id);
                $('#today_date').text(data.jobcard_data.created_date);
                $('#article').text(data.jobcard_data.article);
                $('#material').val(data.jobcard_data.material);
                $('#customer').text(data.jobcard_data.customer);
                $('#customer').attr('data-customer-id', data.jobcard_data.customer_id);
                $('#buyer').text(data.jobcard_data.buyer_name);
                $('#buyer').attr('data-buyer-id', data.jobcard_data.buyer_id);
                $('#reference').val(data.jobcard_data.reference);
                $('#order_type').text(data.jobcard_data.order_type);
                $('#disp_mode').val(data.jobcard_data.dispatch_mode);
                $('#order_date').text(data.jobcard_data.order_date);
                $('#length').val(data.jobcard_data.length);
                $('#repeat_gap').val(data.jobcard_data.repeat_gap);
                $('#machine').val(data.jobcard_data.machine);
                $('#cus_del_date').val(data.jobcard_data.customer_del_date);
                $('#aw_app_date').val(data.jobcard_data.aw_approve_date);
                $('#width').val(data.jobcard_data.width);
                $('#roll_width').val(data.jobcard_data.roll_width);
                $('#film_no').val(data.jobcard_data.film_no);
                $('#length_side').val(data.jobcard_data.length_side);
                $('#excess').text(data.jobcard_data.excess_percentage);
                $('#teeth_no').val(data.jobcard_data.teeth_no);
                
                $('#finish_type').val(data.jobcard_data.finish_type);
                $('#print_color').val(data.jobcard_data.print_color);
                $('#compiled_by').val(data.jobcard_data.compiled_by);
                $('#barcode_read').val(data.jobcard_data.barcode_read);
                $('#operator').val(data.jobcard_data.operator);
                $('#checker').val(data.jobcard_data.checker);
                $('#author').val(data.jobcard_data.authorized_by);
                $('#axicon_verifier').val(data.jobcard_data.axicon_verifier);
                $('#approved_by').val(data.jobcard_data.approved_by);
                $('#packer').val(data.jobcard_data.packer);
                $('#approval_date').val(data.jobcard_data.approval_date);
                $('#roll_issued_by').val(data.jobcard_data.roll_issued_by);
                $('#cutter').val(data.jobcard_data.cutter);
                $('#cartons').val(data.jobcard_data.cartons);
                $('#invoice_date').val(data.jobcard_data.invoice_date);
                $('#inv_no').val(data.jobcard_data.invoice_num);
                $('#act_del_date').val(data.jobcard_data.act_del_date);
                $('#no_repeats').val(data.jobcard_data.no_of_repeats);
                $('#crud_operation_type').text('updated_at');

                 $.each(JSON.parse(data.jobcard_data.template_quantity_json), function(template_type, template_type_data){
                    if(template_type === 'single')
                    {
                        if (!($.isEmptyObject(template_type_data)))
                        {
                            var table_row = 0;
                            var table_count = 0;
                            var single_table = '';
                            $.each(template_type_data, function(table, table_data){
                                var lastIndex = table.lastIndexOf("_");

                                var template = table.substring(0, lastIndex).replace(',','_');
                                
                                single_table += '<table class="table table-bordered table-responsive single-list-table" style="font-size: 12px;" id="single_list_table_'+table_count+'" border="0.2">\
                                        <tbody id="single_list_table_body">\
                                        <tr>\
                                            <th width="80px">ID.NO</th>';
                                            $.each(table_data.erp_id, function(key, value){
                                                single_table += '<td colspan="2" data-template-id="'+table_data.temp_id[key]+'" class="single-erpid-'+key+'">'+value+'</td>';
                                            });
                                            single_table += '<td colspan="4" class="highlight_row" rowspan="6" data-single-ref-name="'+template.replace('_',' ')+'"></td>\
                                        </tr>\
                                        <tr>\
                                            <th width="80px">StyleNo/UVC</th>';
                                            $.each(table_data.style, function(key, value){
                                                single_table += '<td colspan="2" class="single-style-no-'+key+'">'+value+'</td>';
                                            });
                                            single_table += '</tr>\
                                        <tr>\
                                            <th width="80px">B.PO.NO</th>';
                                            $.each(table_data.buyer_po, function(key, value){
                                                single_table += '<td colspan="2" class="single-buyer-po-'+key+'">'+value+'</td>';
                                            });
                                            single_table += '</tr>\
                                        <tr>\
                                            <th width="80px">OCN</th>';
                                            $.each(table_data.ocn, function(key, value){
                                                single_table += '<td colspan="2" class="single-ocn-'+key+'">'+value+'</td>';
                                            });
                                            single_table += '</tr>\
                                        <tr>\
                                            <th width="80px">Color</th>';
                                            $.each(table_data.color, function(key, value){
                                                single_table += '<td colspan="2" class="single-color-'+key+'">'+value+'</td>';
                                            });
                                            single_table += '</tr>\
                                        <tr>\
                                            <th width="80px">Composition</th>';
                                            $.each(table_data.compositions, function(key, value){
                                                single_table += '<td colspan="2" class="single-composition-'+key+'">'+value+'</td>';
                                            });
                                            single_table += '</tr>\
                                        <tr>\
                                          <th width="80px"><strong>SIZE /PRICE</strong></th>';
                                        $.each(table_data.erp_id, function(key, value){
                                            single_table += '<th><strong>O.QNT</strong></th>\
                                                             <th><strong>P.QNT</strong></th>';
                                        });
                                        single_table += '<th><strong>TOTAL</strong></th>\
                                                         <th width="180px"><strong>EXCESS</strong></th>\
                                                         <th><strong>MTRS</strong></th>\
                                                         <th><strong>STOCK</strong></th></tr>';
                                                         
                                        var total_quantity_final = 0;
                                        var total_excess_final = 0;
                                        var qty_col_sum = [];
                                        $.each(table_data.size, function(size, quantities){
                                            var total_qty_sizewise = 0;
                                            var col = 0;
                                            single_table += '<tr>\
                                                <td class="single-size-row">'+size+'</td>';
                                                $.each(quantities, function(key, qty){
                                                    single_table += '<td class="quantity-'+key+' quantity-td" data-size-value="'+size+'">'+qty+'</td>\
                                                            <td></td>';
                                                    total_qty_sizewise += parseInt(qty);
                                                    /** Column wise total quantity calculation */
                                                    qty_col_sum[col] = parseInt(qty) + (qty_col_sum[col] || 0);
                                                    col++;
                                                });
                                                single_table += '<td class="total-quantity-class total-quantity-row-'+table_row+'"><strong>'+parseInt(total_qty_sizewise)+'</strong></td>\
                                                <td class="total-excess-td">\
                                                    <div class="form-group has-feedback">\
                                                        <input type="number" data-step-error="Decimal values not allowed." min="0" data-min-error="Value must be >= 0" class="form-control input-sm total-excess-row-table-'+table_count+'"  id="edit_total_excess_row_table_'+table_row+'" data-required-error="Mandatory Field." name="edit_total_excess_row_table_'+table_row+'" "="" placeholder="Excess %" onkeyup="return editTotalExcess()" value="'+table_data.excess[size]+'">\
                                                        <div class="help-block with-errors"></div>\
                                                    </div>\
                                                </td>\
                                                <td></td>\
                                                <td></td>\
                                                </tr>';
                                          total_quantity_final += parseInt(total_qty_sizewise);
                                          total_excess_final   += parseInt(table_data.excess[size]);
                                          table_row++;
                                        });
                                        single_table += '<tr>\
                                                            <th class="highlight_row total-text" data-ref-name="'+template.replace(/ |\//g, '_')+'">TOTAL</th>';
                                        
                                        var qty_total_col = 0;
                                        $.each(table_data.erp_id, function(key, value){
                                            single_table += '<td class="total-quantity-col-'+qty_total_col+'"><strong>'+qty_col_sum[qty_total_col]+'</strong></td>\
                                            <td></td>';
                                            qty_total_col++;
                                        });
                                                            
                                        single_table += '<td><strong class="total-qty">'+parseInt(total_quantity_final)+'</strong></td>\
                                                <td class="total-excess-col-table-'+table_count+' total-excess"><strong>'+Math.round(isNaN(total_excess_final) ? '0' : total_excess_final)+'</strong></td>\
                                                <td></td>\
                                                <td></td>\
                                            </tr>\
                                    </tbody>\
                                </table>';
                                table_count++;
                            });
                        $('.single-list-table-div').html(single_table);
                        }
                    }
                    
                 });

               // excess, meters and settings calculation values.
                edit_meters_div_html ='<div class="col-md-12">\
                                    <div class="col-md-3">\
                                        <label for="act_excess" >ACT EXCESS % :</label>\
                                        <span class="act-excess" data-act-excess-ref="'+data.jobcard_data.ref_name+'" id="act_excess">'+data.jobcard_data.actual_excess+'</span>\
                                    </div>\
                                    <div class="col-md-3">\
                                        <label for="sticker_mtr" >STKRS MTRS :</label>\
                                        <span class="sticker-mtrs" id="sticker_mtr">'+data.jobcard_data.mtrs_for_stickers+'</span>\
                                    </div>\
                                    <div class="col-md-3">\
                                    <div class="form-group has-feedback">\
                                        <label for="settings" >SETTINGS :</label>\
                                        <input type="number" min="0" data-step-error="Decimal values not allowed." data-min-error="Value must be >= 0" max="99999" data-max-error="Value should be <= 99999" class="form-control input-sm settings" name="settings" placeholder="SETTINGS" id="settings" onkeyup="return totalMetersEdit()" value="'+data.jobcard_data.settings+'" >\
                                        <div class="help-block with-errors"></div>\
                                    </div>\
                                    </div>\
                                </div>';
                  
                $('.meter-calculation-div').html(edit_meters_div_html);
               
               /** [ Backdrop for bootstrap modal ] */
               $('#natJobcardCreationModal').modal({
                   backdrop: 'static',
                   keyboard: false
               })

               $('.selectpicker').selectpicker('refresh');

               /** [ Validate create_nat_jobcard form using bootstrap validator ] */
               $('#create_nat_jobcard').validator('validate');
               $('#natJobcardCreationModal').modal('show');
               $('.datepicker').datepicker({
                   format: 'dd-mm-yyyy'
               });
                $('#aw_app_date').datepicker({
                    format: 'dd-mm-yyyy',
                    endDate: new Date()
                });
            },
            error: function(errorXHR, errorStatus, errorThrown) {
               console.log(errorThrown);
             },
            complete: function () {
                $(".loader").hide();
            }
        })
  } catch (e) {
    console.log("Error:"+e)
  }
 }