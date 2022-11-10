	$(document).ready(function()
	{   
		var getDetailsURL = "<?php echo url('erp/get-non-artwork-order-details'); ?>";
		postURL = "<?php echo url('erp/save-non-artwork'); ?>"
		var row_index=1;  
		var hsn_arr = [];

		$('.table-responsive').on('show.bs.dropdown', function () {
			$('body').css( "overflow-x", "hidden" );
		// $('.table-responsive').css( "overflow", "inherit" );
		});

		$('.table-responsive').on('hide.bs.dropdown', function () {
			$('body').css( "overflow-x", "auto" );	
		// $('.table-responsive').css( "overflow", "auto" );
		})

		$('.reference_select').selectpicker({
		dropupAuto: false
		});

		
		$("#current_date").datepicker("setDate", new Date());
	

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		function reset_input()
		{
			$(".rate_value").val('')
			$(".rates").val('')
			$(".quantity").val('')
			sync_calculations()
		}

		function sync_calculations()
		{

			total_qty = 0
					total_value = 0
					$(".quantity").each(function(i,v){
					total_qty+=+$(this).val()
					});
					$(".rate_value").each(function(i,v){
					total_value+=+$(this).val()
					});
					$("#qty_total").val(total_qty)
					$("#value_total").val(total_value)
					$("#tax_value").val((($("#_tax_value").val()/100) * total_value))
					$("#tax_value_total").val(Math.round((($("#_tax_value").val()/100) * total_value) + total_value))
		}

		function clone_row()
    	{
    		var index = $(".main-content select").length + 1;
    		new_tr = ''

    		for (var i = 0; i < row_ids.length; i++) 
    		{
    			var row_id = "addr" + ++index  
    			$('tbody').append('<tr id='+row_id+' class="clonable"></tr>')
				$("#"+row_ids[i]).find('td').each (function() 
				{	
					var $item = $(this).clone()
					if($(this).hasClass('selectpicker_td'))
					{	
						// $item.attr("id", "item_select" + index);

						var selectedValue = $(this).find('option:selected').val()
						$("#"+row_id).append($item)

						var remove = $item.find(".bootstrap-select")
						$(remove).replaceWith($(remove).contents('.selectpicker'));
						if(action[0] == "clone")
						{
							$item.find("option[value = '" + selectedValue + "']").attr("selected", "selected");
						}
						
						$item.find('select').selectpicker('refresh');
					}
					else{
						if(action[0] == "clone")
						{
							$("#"+row_id).append($item).find(".no_clone").val('')
						}
						else
						{
							$("#"+row_id).append($item).find(":input").val('')
							$item.closest('.aw_td').css('pointer-events','auto')
							$("#"+row_id).find('.aw_status').selectpicker('val','1');
							$("#"+row_id).find('.reference_select').find("option").remove()
						}

						$("#"+row_id).find('.selectpicker').selectpicker('refresh');
					}
				});                       
    		}
    		set_row_index()
    		sync_calculations()
		}

		function set_row_index()
		{
			$('#dynamic_field tr ').each(function(key,val)
			{	
				$(this).attr('id','addr'+key).find("td:eq(0)").html('<div class="custom-control custom-checkbox"><input type="checkbox" name="customCheck'+(key+1)+'"class="custom-control-input select_row_to_clone" id="customCheck'+(key+1)+'"><label class="custom-control-label" for="customCheck'+(key+1)+'">&nbsp;&nbsp;'+(key+1)+'</label></div>')
			})
		}

		function printErrorMsg (msg) 
		{
			$(".error-bag").html('')
			window.scrollTo(0, 0);
			$li = ''
			var filtered_messages = msg.filter(function(elem, index, self) {
				return index === self.indexOf(elem);
			});
			$.each( filtered_messages, function( key, value ) {
				$li+='<li>'+value+'</li>'
			});

			$(".error-bag").html('<div class="alert alert-danger print-error-msg alert-dismissable"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> <ul>'+$li+'</ul> </div>')
		}

		function getHsn(item_id,ref_td)
		{
			
			$.ajax({
				method:'POST',
				type:'json',
				url: "/erp/get-hsn-tax",
				data: { 'item_id' : item_id ,'buyer_id' : $("#buyer").val()},
				success:function(data)
				{
					if(data.hsn)
					{	
						if($(ref_td).closest('tr').attr('id') == "addr0")
						{
							$("#hsn_code").val(data.hsn)
							$("#_tax_value").val(data.tax)
							$("#tax").html(data.tax+" %")
						}
						temp_hsn = data.hsn
						hsn_arr.push(data.hsn)
						if(hsn_arr.length > 1)
						{
							if(hsn_arr[0]!=hsn_arr[hsn_arr.length-1])
							{	
								if($(ref_td).closest('tr').attr('id') == "addr0")
								{	
									$("#dynamic_field").find("tr:gt(0) .reference_td").each(function(i,v)
										{
											$(this).find('select').val('')
										});	
										hsn_arr=[]
										hsn_arr.push(temp_hsn)
										
								}
								else
								{	
									if(hsn_arr.length > 1)
									{
										hsn_arr.pop()
									}
									$(ref_td).find('select').val('')
									$('.snackbar_span').html("Hsn code doesn't match for this selection,Please try again!!!");
									showSnackbar();
								}
								
								$('.selectpicker').selectpicker('refresh')
							}
						}
					}
					else
					{
						$('.snackbar_span').html('Hsn code not found!!!');
						showSnackbar();
					}
				}
			});

		}

		$('#add_single_row').click(function()
		{  	
			row_ids = ['addr0'];
			action = ['create'];
 			clone_row.apply(this, row_ids, action);
		});   

		$(document).on("click","#clone_selected",function()
		{
			rows = $(".clonable input:checked").parents("tr")
			row_ids = [];
			action = ['clone'];
			rows.each(function(i,v){
				row_ids.push($(this).attr('id'))
				
			})
			clone_row.apply(this, row_ids, action);
		})


		$(document).on("click","#delete_selected",function()
		{
			$('#dynamic_field input[type=checkbox]:checked').each(function () 
			{
				if($(this).closest('tr').attr('id') != "addr0")
				{
					$(this).parents("tr").remove()
					$('.snackbar_span').html('Row removed !!!');
					showSnackbar();
				}
				else
				{
					$('.snackbar_span').html('System will not delete the first row !!!');
					showSnackbar();
				}
			});
			sync_calculations()
			set_row_index()
		})


		$(document).on("change","#buyer",function()
		{	
			$.ajax({
				method:'POST',
				type:'json',
				url: "/erp/get-item-nat",
				data: { 'buyer_id' : $("#buyer").val() },
				success:function(data)
				{
					
					$("#customer").empty();
					$.each(data.customers, function(key, value) {
						$('#customer')
						.append($("<option></option>")
						.attr("value",key)
						.text(value));
					});

					$(".item_td").each(function(i,v)
					{
						$(this).html(data.items)
					});
					reset_input()
					resetSelect('reference_select')
					$(".selectpicker").selectpicker('refresh')
				}
			});
		});


		$(document).on("changed.bs.select",".aw_status",function(e)
		{	e.stopImmediatePropagation()
			if($(this).val() == 0)
			{
				$(this).closest('tr').find('td.aw_td').css("pointer-events",'none')
				$(this).closest('tr').find('td.aw_td .aw_date').val('')
			}
			else
			{
				$(this).closest('tr').find('td.aw_td').css("pointer-events",'auto')
			}
		});

		$(document).on("changed.bs.select",".item_select",function(e){	
			e.stopImmediatePropagation()
			if($("#buyer").val().length == 0)
			{	$(this).val('')
				$(".selectpicker").selectpicker('refresh')
				$('.snackbar_span').html('Please select the buyer first !!!');
					showSnackbar();
			}
			else{
			$target = $(this).closest('td').next()
			item_id = $(this).val()
			$.ajax({
				method:'POST',
				type:'json',
				url: "/erp/get-reference-nat",
				data: { 'buyer_id' : $("#buyer").val() ,'item_id' : item_id },
				success:function(data)
				{	
					$target.html('')
					$target.html(data.reference)
					$(".selectpicker").selectpicker('refresh')
				}
			});
			}
		});

		$(document).on("changed.bs.select",".customer_select",function(e)
			{	
				reset_input()
			});

		$(document).on("changed.bs.select",".reference_select",function(e)
			{	e.stopImmediatePropagation()

				var _item = $(this).val()
				var _target = $(this).closest('td')
				if($(this).closest('tr').find('td.quantity_td input[type=number]').val() > 0)
				{
					calculate_rate($(this).val(),$(this).closest('tr').find('td.quantity_td input[type=number]').val(),$(this).closest('tr').find('td.rate_td input[type=number]'),$(this).closest('tr').find('td.value_td input[type=number]'))	
				}
				else
				{
					$(this).closest('tr').find('td.value_td input[type=number]').val(0)
				}

				getHsn(_item,_target);
			});

		$(document).on("keyup change",".quantity",function()
		{	
			if($(this).val().length > 8)
			{
				qty = $(this).val().substring(0, 8)
				$(this).val(qty)
			}
			if($("#customer").val().length == 0)
			{
				$('.snackbar_span').html('Please select a customer!!!');
			    showSnackbar();
			    $(this).val('')
			}
			if($(this).closest('tr').find('td.reference_td .selectpicker option:selected').val().length == 0)
			{
				$('.snackbar_span').html('Please select a reference!!!');
			    showSnackbar();
			    $(this).val('')
			}
			if($(this).val() > 0)
			{
				calculate_rate($(this).closest('tr').find('td.reference_td .selectpicker option:selected').val(),$(this).val(),$(this).closest('tr').find('td.rate_td input[type=number]'),$(this).closest('tr').find('td.value_td input[type=number]'))
			}
			else
			{
				$(this).closest('tr').find('td.value_td input[type=number]').val(0)
			}

		});

		$(".reload-btn").on('click', function () {
			location.reload();
		});


		$(document).on("click","#cancel_order",function(e){
			e.preventDefault();
			$('#confirmCancellation').modal({
				backdrop: 'static',
				keyboard: false
			})
			.on('click', '#confirm', function(e) {
				confirmOrderCancellation()
			});
			$("#cancel_cancellation").on('click',function(e){
				e.preventDefault();
				$('#confirmCancellation').modal('hide');
			});
		});

		function confirmOrderCancellation()
		{
			$.ajax({
				method:'POST',
				type:'json',
				url: "/erp/cancel-order",
				data: {'order_id':$("#order_id").val() },
				success:function(data)
				{
					$("#save_data").addClass('hidden')
					$("#cancel_order").addClass("hidden")
					$("#_order_status").html(data.order_status);
					$('.snackbar_span').html('Order cancelled successfully!!!');
					showSnackbar();
				}
			});
		}

		function resetSelect(select_class)
		{
			$("."+select_class).val('')
			$("."+select_class).find("option").remove()
			$("."+select_class).selectpicker("refresh");
		}


		function calculate_rate(ref,qty,rate_td,value_td)
		{
			$.ajax({
				method:'POST',
				type:'json',
				url: "/erp/calculate-nat-rate",
				data: { 'buyer_id' : $("#buyer").val() ,'template_id' : ref ,'customer_id' : $("#customer").val(),'quantity' : qty},
				success:function(data)
				{
					if(qty >= data.moq)
					{
					rate_td.val(data.rate)
					value_td.val((data.rate * qty).toFixed(2))
					total_qty = 0
					total_value = 0
					$(".quantity").each(function(i,v){
						total_qty+=+$(this).val()
					});
					$(".rate_value").each(function(i,v){
						total_value+=+$(this).val()
					});
					$("#qty_total").val(total_qty)
					$("#value_total").val(total_value.toFixed(2))
					$("#tax_value").val((($("#_tax_value").val()/100) * total_value).toFixed(2))
					$("#tax_value_total").val(Math.round((($("#_tax_value").val()/100) * total_value) + total_value).toFixed(2))
					}
					else
					{
						$('.snackbar_span').html('Quantity cannot be less than '+data.moq+'(Moq)');
			   	 		showSnackbar();
			   	 		rate_td.val('')
					}
				}
			});
		}


		$('#non_artwork_orders').validator().on('submit', function (e) {
				if (e.isDefaultPrevented()) {

				} else {
					e.preventDefault();

				var can_submit = 1;
				var artwork_dates_filled = 1;

				$(".rate_value").each(function(i,v){

					if($(this).val() == 0)
					{
						can_submit = 0
						$('.snackbar_span').html('Please make sure values provided are non-zero !!!');
						showSnackbar();
					}
				});

				$(".aw_date").each(function(i,v)
				{
					if($(this).css('pointer-events') == 'auto' && $(this).val() == "")
						{
							$('.snackbar_span').html('Please make sure that approved dates are provided if Aw status is aproved !!!');
							showSnackbar();
							can_submit = 0
						}
				});


				if(can_submit && artwork_dates_filled)
				{
					submitNonArtworkOrder();
				}
			}
		});

		function submitNonArtworkOrder()
		{
			$("#save_data").attr('data-action') == "save" ? action ="save" : action ="update"
			order_id = $("#order_id").val()
			$.ajax({  
				url: "/erp/save-non-artwork",
				method:"POST",  
				data:$('#non_artwork_orders').serialize()+ '&action=' + action+ '&order_id=' + order_id,
				type:'json',
				success:function(data)  
				{
					console.log(data)
					if(data.error){
						printErrorMsg(data.error);
					}else{
						if(data.action == "save")
						{
							$("#order_id").prepend('<option value='+data.order_id+'>'+data.order_id+'</option>');
							$("#order_id").val(data.order_id);
							$("#order_id").selectpicker("refresh");
						}
						
						$("#_order_status").html("Moved To JC Queue");

						$("#save_data").removeClass('hidden')
						$("#save_data").html('<i class="fa fa-save" aria-hidden="true"></i> Update')
						$("#save_data").attr('data-action', 'update');
						if(data.jc_progress == 0)
						{
							$("#cancel_order").removeClass("hidden")
						}
						
						// row_index=1;
						// $('.dynamic-added').remove();
						// $('#non_artwork_orders')[0].reset();
						// $("#nat_tab_logic").find("tr:gt(1)").remove();
						$(".print-success-msg").find("ul").html('');
						$(".print-error-msg").css('display','none');
						if(data.action=="update")
						{
							$('.snackbar_span').html('Order updated successfully !!!');
						}
						else{
							$('.snackbar_span').html('Order created successfully !!!');
						}
						showSnackbar();
						// setTimeout(function () {
						// 	location.reload(true);
						// }, 2500);
					}
				}  
			}); 
		}


	// $("#getOrderDetails").on('click', function () 
	$(document).on("changed.bs.select","#order_id",function(e)
	{
		$.ajax({
			type:'POST',
			url: "/erp/get-non-artwork-order-details",
			data:{id:$(this).val()},
			success:function(data)
			{
				if(data.result)
				{	
					hsn_arr = [];
					hsn_arr.push(data.hsn)
					row_index= data.last_row_index
					// $("#customer").empty();
					$.each(data.customers, function(key, value) {
						$('#customer')
						.append($("<option></option>")
						.attr("value",key)
						.text(value));
					});
					$("#customer").val(data.row['supplier_id']);
					$('select[name=buyer]').val(data.row['buyer_id']);
					
					$("#tesco_po_number").val(data.row['purchase_order_no'])
					$("#order_to_edit").val(data.row['id'])
					$("#_order_status").html(data.current_order_status)
					$("#hsn_code").val(data.hsn)
					$("#tax").html(data.tax+" %")
					$("#_tax_value").val(data.tax)
					$("#dynamic_field").find("tr:gt(0)").remove();
					$("#dynamic_field").html('')
					$("#dynamic_field").html(data.td)
					$('.selectpicker').selectpicker('refresh')
					$('#current_date').datepicker("setDate", new Date(data.row['order_created_at']) );
					$('#due_date').datepicker("setDate", new Date(data.row['delivery_date']) );

					$(".aw_date").each(function(i,v)
					{	
						if(data.approved_dates[i] == null)
						{
							$(this).datepicker("setDate", null);
							$(this).parent().parent().css("pointer-events",'none')
						}
						else
						{
							$(this).datepicker("setDate", new Date(data.approved_dates[i]))
						}
						
					})

					total_qty = 0
					total_value = 0
					$(".quantity").each(function(i,v){
					total_qty+=+$(this).val()
					});
					$(".rate_value").each(function(i,v){
					total_value+=+$(this).val()
					});
					$("#qty_total").val(total_qty)
					$("#value_total").val(total_value.toFixed(2))
					$("#tax_value").val((($("#_tax_value").val()/100) * total_value).toFixed(2))
					$("#tax_value_total").val(Math.round((($("#_tax_value").val()/100) * total_value) + total_value).toFixed(2))

					if(data.is_editable)
					{
						if(data.jc_progress == 0)
						{
							$("#cancel_order").removeClass("hidden")
						}
						else{
							$(".common_entry").addClass('readonly_class')
							$("#cancel_order").addClass("hidden")
							$('.snackbar_span').html(data.message);
							showSnackbar();
						}
						$("#save_data").removeClass('hidden')
						$("#save_data").html('<i class="fa fa-save" aria-hidden="true"></i> Update')
						$("#save_data").attr('data-action', 'update');
						$(".row_btns").prop('disabled',false)
						
					}
					else
					{
						$("#save_data").addClass('hidden')
						$("#cancel_order").addClass("hidden")
						$(".row_btns").prop('disabled',true)
						$('.snackbar_span').html(data.message);
						showSnackbar();
					}

					$(".readonly_class").each(function(i,v)
					{	
						$(this).parent().css("pointer-events",'none')
						if($(this).hasClass('date_entry'))
						{
							$(this).css({'background-color':'#cccccc6b','border-color':'#ccc'})
							$(this).next('div').css({'border-color':'#cccccc6b'})
						}
						else{
							$(this).find('button').css({'background-color':'#cccccc6b','border-color':'#ccc'})
						}
						
					})
			}
			else
			{
				$('.snackbar_span').html('No entry found for this order-id, Please try a different one!!!');
				showSnackbar();
			}
		}
		});
	});
});  
