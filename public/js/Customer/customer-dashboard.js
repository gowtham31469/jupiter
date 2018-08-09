/* Customer Order Management Script*/
$(document).ready(function() {
       /** [ Make buyers card active ] */
       $('div.buyers-card').click(function(){
         $('div.buyers-card').removeClass("active");
         $(this).addClass("active");
         $('#hidden-buyer-div').hide();
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

       /** [ Select buyer on clicking proceed button] */
       $('.proceed-btn').on('click', function() {
           
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
  function showApprovedOrders()
  {
    var csrf_token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: base_url + "/customer/tesco/order-management/approved-orders",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token
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
      console.log(formdata)
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
          html += '<tr><td><input type="checkbox" class="form-control" value="'+value.tablename+'_'+value.id+'" id="'+value.tablename+'_'+value.id+'" name="row_id" data-id="'+value.tablename+'"/></td><td></td><td></td><td>'+style_no+'</td><td></td><td>'+value.TemplateName+'</td><td>'+value.order_created_at+'</td><td>'+value.order_approved_at+'</td><td></td><td><label onclick=viewedApproved("'+value.id+'","'+value.tablename+'") style="cursor:pointer" class="label label-warning">Mark as viewed</label></td></tr>';
      });
      $('#approvedModal .modal-body').html('<form id="frm-example"><table id ="approved-orders" class="table approved-orders "><thead><tr><th style="width: 0px;padding-right: 8px !important;"><input name="select_all" value="1" id="select-all" type="checkbox" class="form-control" /></th><th>Customer Name</th><th>Buyer</th><th>Style No</th><th>Product</th><th>Reference</th><th>Order Date</th><th>Approval Date</th><th>PO/PI Number</th><th>Actions</th></tr></thead><tbody>'+html+'</tbody></table><button style="height: 20px;"class="label label-warning mark-selected hidden">Mark Selected as Viewed</button></form>');
      $('.approved-count').text(data.approved_count)
      var table =  $('.approved-orders').DataTable({
          dom: 'Bfrtip',
          
          buttons: [
              'excelHtml5'
          ],
         
        'order': [1, 'asc'],
         "createdRow": function(row, data, dataIndex){
           $(row).attr("id", "tblRow_" + data[0]);
        }
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

