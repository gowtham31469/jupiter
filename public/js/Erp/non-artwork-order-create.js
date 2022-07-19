	$(document).ready(function()
	{   
		$('.datepicker').datepicker({
			format: 'dd-mm-yyyy'
		}); 

		var getDetailsURL = "<?php echo url('erp/get-non-artwork-order-details'); ?>";
		postURL = "<?php echo url('erp/save-non-artwork'); ?>"
		var row_index=1;  

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		function reset_input()
		{
			$(".rate_value").val('')
			$(".rates").val('')
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
					if($(this).hasClass('selectpicker_td'))
					{
						var $item = $(this).clone()
						$item.attr("id", "item_select" + index);
             				//$item.attr("name", "item_select" + index);

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
							$("#"+row_id).append($(this).clone())
						}
						else{
							$("#"+row_id).append($(this).clone().find("input").val("").end())
						}
						
					}
				});                       
    		}
    		set_row_index()
		}

		function set_row_index()
		{
			$('#dynamic_field tr ').each(function(key,val)
			{	
				$(this).attr('id','addr'+key).find("td:eq(0)").html('<div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input select_row_to_clone" id="customCheck1"><label class="custom-control-label" for="customCheck1">&nbsp;&nbsp;'+(key+1)+'</label></div>')
			})
		}

		function printErrorMsg (msg) 
		{
			$(".print-error-msg").find("ul").html('');
			window.scrollTo(0, 0);
			$(".print-error-msg").css('display','block').delay(1000).fadeOut('slow/1000/fast', function() {
			});
			$(".print-success-msg").css('display','none');
			$.each( msg, function( key, value ) {
			$(".print-error-msg").find("ul").append('<li>'+value+'</li>');
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
			$('input[type=checkbox]:checked').each(function () 
			{
				if($('#dynamic_field tr ').length > 1)
				{
					$(this).parents("tr").remove()
				}
				else
				{
					$('.snackbar_span').html('System will not delete the first row !!!');
					showSnackbar();
				}
			});
			set_row_index()
		})


		$(document).on("change","#buyer",function(){	

			$.ajax({
				method:'POST',
				type:'json',
				url: "/erp/get-item-nat",
				data: { 'buyer_id' : $("#buyer").val() },
				success:function(data)
				{
					$("#item_td").html('')
					$("#item_td").html(data.items)
					$(".reference_select").find('option').remove();
					reset_input()
					$(".selectpicker").selectpicker('refresh')
				}
			});
		});


		$(document).on("change",".aw_status",function(e)
		{	
			if($('.aw_status option:selected').val() == 0)
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
			{
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

				if($(this).closest('tr').find('td.quantity_td input[type=number]').val() > 0)
				{
					calculate_rate($(this).val(),$(this).closest('tr').find('td.quantity_td input[type=number]').val(),$(this).closest('tr').find('td.rate_td input[type=number]'),$(this).closest('tr').find('td.value_td input[type=number]'))	
				}
				else
				{
					$(this).closest('tr').find('td.value_td input[type=number]').val(0)
				}
			});

		$(document).on("keyup change",".quantity",function()
		{	
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


		function calculate_rate(ref,qty,rate_td,value_td)
		{
			$.ajax({
				method:'POST',
				type:'json',
				url: "/erp/calculate-nat-rate",
				data: { 'buyer_id' : $("#buyer").val() ,'template_id' : ref ,'customer_id' : $("#customer").val(),'quantity' : qty},
				success:function(data)
				{
					rate_td.val(data.rate)
					value_td.val(Math.round(data.rate * qty))
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
					$("#tax_value").val(Math.round(.18 * total_value))
					$("#tax_value_total").val(Math.round((.18 * total_value) + total_value))
				}
			});
		}


		$('#non_artwork_orders').validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {

			} else {
				e.preventDefault();
				submitNonArtworkOrder();
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
					if(data.error){
						printErrorMsg(data.error);
					}else{
						row_index=1;
						$('.dynamic-added').remove();
						$('#non_artwork_orders')[0].reset();
						$("#nat_tab_logic").find("tr:gt(1)").remove();
						$(".print-success-msg").find("ul").html('');
						$(".print-error-msg").css('display','none');
						$(".print-success-msg").find("ul").append('<li>Record Inserted Successfully.</li>');
						$('.print-success-msg').css('display','block').delay(1000).fadeOut('slow/400/fast', function() {
							location.reload(true);
						});
					}
				}  
			});  
		}


	$("#getOrderDetails").on('click', function () 
	{

		$("#save_data").html('<i class="fa fa-save" aria-hidden="true"></i> Update')
		$("#save_data").attr('data-action', 'update');

		$.ajax({
			type:'POST',
			url: "/erp/get-non-artwork-order-details",
			data:{id:$("#order_id").val()},
			success:function(data)
			{
				// console.log(data)
				row_index= data.last_row_index
				$('select[name=buyer]').val(data.row['buyer_id']);
				$('select[name=customer]').val(data.row['supplier_id']);
				$("#tesco_po_number").val(data.row['purchase_order_no'])
				$("#order_to_edit").val(data.row['id'])
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
				$("#value_total").val(total_value)
				$("#tax_value").val(Math.round(.18 * total_value))
				$("#tax_value_total").val(Math.round((.18 * total_value) + total_value))
			}
		});
	});
});  
