/* Customer Order Management Script*/
$(document).ready(function() {
       /** [ Make buyers card active ] */
       $('div.buyers-card').click(function(){
         $('div.buyers-card').removeClass("active");
         $(this).addClass("active");
         $('#hidden-buyer-div').hide();

         /* Save selected buyer into localdb */
         if ($('.buyers-card.active-buyer.active').length != 0) 
         {
             $('#hidden-buyer-div').hide();
             localStorage.setItem("buyer", $('.buyers-card.active-buyer.active').data("name").toLowerCase());
             localStorage.setItem("buyer_id", $('.buyers-card.active-buyer.active').data("value"));
             $('.selected-buyer').text('Selected Buyer : '+$('.buyers-card.active-buyer.active').data("name"))
             $('#buyerSelectionModalCenter').modal('hide');
             var buyer = localStorage.getItem("buyer");
             // if (buyer === 'orchestra') {
             //     orderStatus();
             // } else {
             //     tescoOrderStatus();
             // }
         } else {
             localStorage.removeItem("buyer");
             localStorage.removeItem("buyer_id");
             $('#hidden-buyer-div').show();
         }

       });

       /** [ Show already selected(active) buyer on clicking buyer menu] */
       $('.buyer-nav-menu').on('click', function() {
           if (localStorage.getItem("buyer") === null) 
           {
              $("#buyerSelectionModalCenter").modal("show");
              
           } 
           else 
           {
               $('#'+localStorage.getItem("buyer")).addClass('active');
               $("#buyerSelectionModalCenter").modal("show");
           }
       });

       /** [If only 1 buyer , auto select that buyer] */
       var buyer_count = $('#invisible__count_id').val();
       if(buyer_count <= 1) 
       {
           $('#hidden-buyer-div').hide();
           $('div.buyers-card').addClass("active");
         //  localStorage.setItem("buyer", $('.buyers-card.active-buyer.active').data("name").toLowerCase());
           localStorage.setItem("buyer_id", $('.buyers-card.active-buyer.active').data("value"));
           $('.selected-buyer').text('Selected Buyer : '+$('.buyers-card.active-buyer.active').data("name"))
       }   
       
       /** add buyer selection menu into navbar */
       $('.buyer-nav-menu').html($('.buyer-nav-div').html());

       /** Load selected buyer from local-storage on pageload */
       if (localStorage.getItem("buyer") === null) 
       {
           $(document).ready(function() {
              $("#buyerSelectionModalCenter").modal("show");
           })
       } 
       else 
       {
           $('.selected-buyer').text('Selected Buyer : '+localStorage.getItem("buyer"));
           var buyer = localStorage.getItem("buyer");
           // if (buyer === 'orchestra') {
           //     orderStatus();
           // } else {
           //     tescoOrderStatus();
           // }
       }

       /** To refresh modal while hidden **/
       $('#buyerSelectionModalCenter').on('hidden.bs.modal', function (e) {
         $('div.buyers-card').removeClass("active");
         if (localStorage.getItem("buyer") === null) 
         {
             $(document).ready(function() {
                $("#buyerSelectionModalCenter").modal("show");
             })
         } 
         else 
         {
             $('.selected-buyer').text('Selected Buyer : '+localStorage.getItem("buyer"));
             var buyer = localStorage.getItem("buyer");
             // if (buyer === 'orchestra') {
             //     orderStatus();
             // } else {
             //     tescoOrderStatus();
             // }
         }
        })
    });

  /**
   * method to set all the check boxes by clicking select all button for the form formname
   * @param {[type]} source   [checkbox]
   * @param {[type]} formname [form name]
   * @param {[type]} table    [data table]
   */
  function setSelectAll(source,formname,table)
  {
      // var rows = table.rows({ 'search': 'applied' }).nodes();
      // $('input[type="checkbox"]', rows).prop('checked');
      var $checkboxes = $('#'+formname+' td input[type="checkbox"]');
      for (var i = 0,n=$checkboxes.length;i<n;i++) {
          $checkboxes[i].checked = source.checked;
      }
      var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
      if (countCheckedCheckboxes==0) {
      $('.mark-selected').addClass("hidden")
      }
      else{
      $('.mark-selected').removeClass("hidden")
      }
  }

  /**
   * method to set the checkbox
   * @param  {[type]} obj      [checkbox]
   * @param  {[type]} formname [form name]
   * @param  {[type]} table    [data table]
   */
  function setcheckBox(obj,formname,table)
  {
      var $checkboxes = $('#'+formname+' td input[type="checkbox"]');
      var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
    
      var ischecked= $(obj).is(':checked');
      if (ischecked) {
          $('.mark-selected').removeClass("hidden")

      }
      else if(countCheckedCheckboxes==0){
          $('.mark-selected').addClass("hidden")
      }
      
      //If checkbox is not checked
      if(!this.checked){
         var el = $('#select-all').get(0);
         // If "Select all" control is checked and has 'indeterminate' property
         if(el && el.checked && ('indeterminate' in el)){
            // Set visual state of "Select all" control 
            // as 'indeterminate'
            el.indeterminate = true;
         }
      }
  }

  /**
   * method to call the controller method to set a particular approved order as viewed
   * @param  {[type]} id         [row id]
   * @param  {[type]} table_name [order table name]
   */
  function  viewedApproved(id,table_name) 
  {
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/customer/tesco/order-management/viewed-approved",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            id:id,
            table_name:table_name
        },
        beforeSend: function() {

        },
        success: function(data) {
            displayApprovedTable(data)
            $('.mark-selected').on('click', function(e){
                viewedSelectedApproved()
               e.preventDefault();
           });
        },
        error: function(data) {

        },
        complete: function() {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    })
  }

  /**
   * method to call the controller method to set the selected approved order as viewed
   * @return {[type]} [description]
   */
  function viewedSelectedApproved()
  {
      var formdata = $("#frm-example").serialize();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      var selected = [];
      $('#frm-example input[type=checkbox]').each(function () {
          if (this.checked) {
              selected.push($(this).attr('id'));
          }
      });
      var formdata = selected.join(',');
      $.ajax({
          url: base_url + "/customer/tesco/order-management/viewed-selected-approved",
          type: "Post",
          dataType: "json",
          data: {
              _token: csrf_token,
              formdata:formdata
          },
          beforeSend: function() {

          },
          success: function(data) {
              displayApprovedTable(data)                        
              $('.mark-selected').on('click', function(e){
                  viewedSelectedApproved()
                 e.preventDefault();
             });  
         
          },
          error: function(data) {

          },
          complete: function() {
              $(".loader").hide();
              $(".loader").css('margin-top', '0%');
          }
      })
  }

  /**
   * method to view the approved orders data-table
   * @param  {[type]} data [order list]
   * @return {[type]}      [table view]
   */
  function displayApprovedTable(data)
  {
      var orders = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3']))))));
      var html = "";
      $(orders).each(function(index, value)
      {
          if(value.zone == "CE")
          {
              var style_no = value.ce_style_ref;
          }
          else
          {
              var style_no = value.uk_style_ref;
          }   
          html += '<tr><td><input type="checkbox" class="form-control" value="'+value.tablename+'_'+value.id+'" id="'+value.tablename+'_'+value.id+'" name="row_id" data-id="'+value.tablename+'"/></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>'+value.TemplateName+'</td><td>'+value.order_created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+value.order_approved_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+value.purchase_order_no+'</td><td><label onclick=viewedApproved("'+value.id+'","'+value.tablename+'") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
      });
      $('#approvedModal .modal-body').html('<form id="frm-example"><table id ="approved-orders" class="table approved-orders "><thead><tr><th style="width: 0px;padding-right: 8px !important;"><input name="select_all" value="1" id="select-all" type="checkbox" class="form-control" /></th><th>Customer Name</th><th>Buyer</th><th>Style No</th><th>Product</th><th>Reference</th><th>Order Date</th><th>Approval Date</th><th>PO/PI Number</th><th>Actions</th></tr></thead><tbody>'+html+'</tbody></table></form>');
      $('.approved-count').text(data.approved_count)
      var table =  $('.approved-orders').DataTable({
          dom: 'Bfrtip',
          buttons: [
                'excelHtml5',
                {
                  text: 'Mark as viewed',
                  className : 'mark-selected hidden',
                }
          ],
          'columnDefs': [{
             'targets': 0,
             'searchable':false,
             'orderable':false,
             'className': 'dt-body-center'
           }],
           'order': [1, 'asc']
      });
      $(".modal-body input").addClass('form-control');
      $('.dataTables_length select').addClass('form-control');
      $('#approvedModal').modal("show");
      $(".modal-body input").attr('placeholder', 'Data Search');
       // Handle click on "Select all" control
      $('#select-all').click(function () {
      // Check/uncheck all checkboxes in the table
        setSelectAll(this,"frm-example",table)
        
      });
      
      $('#approved-orders tbody').on('change', 'input[type="checkbox"]', function(){
          
          setcheckBox(this,"frm-example",table)                           
     });
  }

  /**
   * method to call the controller method to set a particular approval pending order as viewed
   * @param  {[type]} id         [row id]
   * @param  {[type]} table_name [order table name]
   */
  function  viewedPending(id,table_name) 
  {
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
          url: base_url + "/customer/tesco/order-management/viewed-pending",
          type: "Post",
          dataType: "json",
          data: {
              _token: csrf_token,
              id:id,
              table_name:table_name
          },
          beforeSend: function() {

          },
          success: function(data) {
              displayPendingTable(data)
              $('.mark-selected').on('click', function(e){
                  viewedSelectedPending()
                 e.preventDefault();
              });
           },
          error: function(data) {

          },
          complete: function() {
              $(".loader").hide();
              $(".loader").css('margin-top', '0%');
          }
      })
  }

 /**
 * method to call the controller method to set the selected appproval-pending order as viewed
 * @return {[type]} [description]
 */
  function viewedSelectedPending()
  {
      var formdata = $("#frm-pending").serialize();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      var selected = [];
      $('#frm-pending input[type=checkbox]').each(function () {
          if (this.checked) {
              selected.push($(this).attr('id'));
          }
      });
      var formdata = selected.join(',');
      $.ajax({
          url: base_url + "/customer/tesco/order-management/viewed-selected-pending",
          type: "Post",
          dataType: "json",
          data: {
              _token: csrf_token,
              formdata:formdata
          },
          beforeSend: function() {

          },
          success: function(data) {
              displayPendingTable(data)                        
              $('.mark-selected').on('click', function(e){
                  viewedSelectedPending()
                 e.preventDefault();
              });  
         
          },
          error: function(data) {

          },
          complete: function() {
              $(".loader").hide();
              $(".loader").css('margin-top', '0%');
          }
      })
  }

    /**
   * method to view the approval pending orders data-table
   * @param  {[type]} data [order list]
   * @return {[type]}      [table view]
   */
  function displayPendingTable(data)
  {
      var orders = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3']))))));
      var html = "";
      $(orders).each(function(index, value)
      {
          if(value.zone == "CE")
          {
              var style_no = value.ce_style_ref;
          }
          else
          {
              var style_no = value.uk_style_ref;
          }
          var date1 = new Date();
          var date2 = new Date(value.order_created_at.split(' ')[0]);
          var timeDiff = Math.abs(date2.getTime() - date1.getTime());
          var pending_days = Math.ceil(timeDiff / (1000 * 3600 * 24));  
          html += '<tr><td><input type="checkbox" class="form-control" value="'+value.tablename+'_'+value.id+'" id="'+value.tablename+'_'+value.id+'" name="row_id" data-id="'+value.tablename+'"/></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>'+value.TemplateName+'</td><td>'+value.order_created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+pending_days+'</td><td><label onclick=viewedPending("'+value.id+'","'+value.tablename+'") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
      });
      $('#appPendingModal .modal-body').html('<form id="frm-pending"><table class="table pending-orders" id="pending-orders"><thead><tr><th style="width: 0px;padding-right: 8px !important;"><input name="select_all" value="1" id="select-all" type="checkbox" class="form-control" /></th><th>Customer Name</th><th>Buyer</th><th>Style No</th><th>Product</th><th>Reference</th><th>Order Date</th><th>Pending Days</th><th>Actions</th></tr></thead><tbody>'+html+'</tbody></table></form>');
      $('.pending-count').text(data.pending_count)
      var table = $('.pending-orders').DataTable({
          dom: 'Bfrtip',
          buttons: [
                'excelHtml5',
                {
                  text: 'Mark as viewed',
                  className : 'mark-selected hidden',
                }
          ],
          'columnDefs': [{
             'targets': 0,
             'searchable':false,
             'orderable':false,
             'className': 'dt-body-center'
           }],
           'order': [1, 'asc']
      }); 
     $(".modal-body input").addClass('form-control');
     $('.dataTables_length select').addClass('form-control');
     $('#appPendingModal').modal("show");
     $(".modal-body input").attr('placeholder', 'Data Search');
     $('#select-all').click(function () {
      // Check/uncheck all checkboxes in the table
        setSelectAll(this,"frm-pending",table)
        
      });
      
      $('#pending-orders tbody').on('change', 'input[type="checkbox"]', function(){
          
          setcheckBox(this,"frm-pending",table)                           
     });
  }

   /**
   * method to view the cancelled orders data-table
   * @param  {[type]} data [order list]
   * @return {[type]}      [table view]
   */
  function displayCancelledTable(data)
  {
    var orders = data.data['tcl1'].concat(data.data['tcl2'].concat(data.data['tcl4'].concat(data.data['thl1'].concat(data.data['thl3'].concat(data.data['tsl1'].concat(data.data['tsl3']))))));
                 
    var html = "";
    $(orders).each(function(index, value)
    {
        if(value.zone == "CE")
        {
            var style_no = value.ce_style_ref;
        }
        else
        {
            var style_no = value.uk_style_ref;
        }   
        html += '<tr><td><input type="checkbox" class="form-control" value="'+value.tablename+'_'+value.id+'" id="'+value.tablename+'_'+value.id+'" name="row_id" data-id="'+value.tablename+'"/></td><td>'+value.company_name+'</td><td>Tesco</td><td>'+style_no+'</td><td>Sticker</td><td>'+value.TemplateName+'</td><td>'+value.order_created_at.split(' ')[0].split('-').reverse().join('-')+'</td><td>'+value.order_cancelled_at.split(' ')[0].split('-').reverse().join('-')+'</td><td><label onclick= viewedCancelled("'+value.id+'","'+value.tablename+'") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
    });
    $('#cancelledOrdersModal .modal-body').html('<form id="frm-cancelled"><table class="table cancelled-orders" id="cancelled-orders"><thead><tr><th style="width: 0px;padding-right: 8px !important;"><input name="select_all" value="1" id="select-all" type="checkbox" class="form-control" /></th><th>Customer Name</th><th>Buyer</th><th>Style No</th><th>Product</th><th>Reference</th><th>Order Date</th><th>Cancelled Date</th><th>Actions</th></tr></thead><tbody>'+html+'</tbody></table></form>');
    $('.cancelled-count').text(data.cancelled_count)
    var table = $('.cancelled-orders').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5',
                {
                  text: 'Mark as viewed',
                  className : 'mark-selected hidden',
                }
            ],
            'columnDefs': [{
                 'targets': 0,
                 'searchable':false,
                 'orderable':false,
                 'className': 'dt-body-center'
            }],     
            'order': [1, 'asc']
        });
      $(".modal-body input").addClass('form-control');
      $('.dataTables_length select').addClass('form-control');
      $(".modal-body input").attr('placeholder', 'Data Search');

      $('#cancelledOrdersModal').modal("show")
      $('#select-all').click(function () {
      // Check/uncheck all checkboxes in the table
        setSelectAll(this,"frm-cancelled",table)
        
      });
      
      $('#cancelled-orders tbody').on('change', 'input[type="checkbox"]', function(){
          
          setcheckBox(this,"frm-cancelled",table)                           
     });
  }

  /**
   * method to call controller method to set the selected cancelled orders as viewed
   * @return {[type]} [description]
   */
  function viewedSelectedCancelled()
  {
      var formdata = $("#frm-cancelled").serialize();
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      var selected = [];
      $('#frm-cancelled input[type=checkbox]').each(function () {
          if (this.checked) {
              selected.push($(this).attr('id'));
          }
      });
      var formdata = selected.join(',');
      $.ajax({
          url: base_url + "/customer/tesco/order-management/viewed-selected-cancelled",
          type: "Post",
          dataType: "json",
          data: {
              _token: csrf_token,
              formdata:formdata
          },
          beforeSend: function() {

          },
          success: function(data) {
              displayCancelledTable(data)                        
              $('.mark-selected').on('click', function(e){
                viewedSelectedCancelled()
                e.preventDefault();
              }); 
         
          },
          error: function(data) {

          },
          complete: function() {
              $(".loader").hide();
              $(".loader").css('margin-top', '0%');
          }
      })
  }

  /**
   * method to call the controller method to set a particular canceled order as viewed
   * @param  {[type]} id        [description]
   * @param  {[type]} tablename [description]
   * @return {[type]}           [description]
   */
  function  viewedCancelled(id,tablename) 
  {
      var csrf_token = $('meta[name="csrf-token"]').attr('content');
      var table_name = tablename;
      $.ajax({
          url: base_url + "/customer/tesco/order-management/viewed-cancelled",
          type: "Post",
          dataType: "json",
          data: {
              _token: csrf_token,
              id:id,
              table_name:table_name
          },
          beforeSend: function() {

          },
          success: function(data) {
              displayCancelledTable(data)                        
              $('.mark-selected').on('click', function(e){
                viewedSelectedCancelled()
                e.preventDefault();
              });
          },
          error: function(data) {

          },
          complete: function() {
              $(".loader").hide();
              $(".loader").css('margin-top', '0%');
          }
      })
  }