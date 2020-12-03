
$(document).ready(function($) {
	$("#_prefModal").modal()

	var d = new Date();
	var month = d.getMonth()+1;
	var day = d.getDate();
	date_today =  
	((''+day).length<2 ? '0' : '') + day + '-' +
	((''+month).length<2 ? '0' : '') + month + '-' + d.getFullYear();

	$( init );
	function init() 
	{
		$( ".droppable-area1, .droppable-area2" ).sortable({
		connectWith: ".connected-sortable",
		stack: '.connected-sortable ul'
		}).disableSelection();
	}
	$('.multiselect')
	.multiselect({
		allSelectedText: 'All',
		maxHeight: 200,
		buttonWidth: 123,
		includeSelectAllOption: true,
		enableFiltering: true
	})
	.multiselect('selectAll', false)
	.multiselect('updateButtonText');

	$("#txtFrom").datepicker({
		numberOfMonths: 3,
		defaultDate: '-1m',
		dateFormat: 'yy-mm-dd',
		startDate: "01/01/1900",
		endDate: "01/01/2100",
		autoclose: true,
		todayHighlight: true,
		onSelect: function (selected) {
			dt1 = new Date(selected);
			dt1.setDate(dt1.getDate() + 1);
			$("#txtTo").datepicker("option", "minDate", dt1);

			$(".filterLevel2").multiselect('clearSelection');
			$(".filterLevel3").multiselect('clearSelection');
			$("#filter2").attr('disabled', 'true');
			$("#filter3").attr('disabled', 'true');

			$("#moreOtions").addClass('hidden')
			$("#_preferences").css('display', 'block');
		}
	});
	$("#txtTo").datepicker({
		numberOfMonths: 3,
		defaultDate: '-1m',
		dateFormat: 'yy-mm-dd',
		startDate: "01/01/1900",
		endDate: "01/01/2100",
		maxDate:"0",
		autoclose: true,
		todayHighlight: true,
		onSelect: function (selected) {
			dt2 = new Date(selected);
			dt2.setDate(dt2.getDate() - 1);
			$("#txtFrom").datepicker("option", "maxDate", dt2);

			$(".filterLevel2").multiselect('clearSelection');
			$(".filterLevel3").multiselect('clearSelection');
			$("#filter2").attr('disabled', 'true');
			$("#filter3").attr('disabled', 'true');

			$("#moreOtions").addClass('hidden')
			$("#_preferences").css('display', 'block');
		}
	});

	$('.roleSelection').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {

		$("#tempSelect").addClass('hidden');
		// selectToOpen=$(this).val()
		// $(".select_Status").removeClass('hidden')

		// $("[class*="+$(this).val()+"]").removeClass("hidden").addClass('open');

		// $(".all_status").each(function(index, el) {
		// 	if(!($(this).hasClass(selectToOpen)))
		// 	{
		// 		$(this).addClass('hidden')
		// 	}
		// 	$(this).val('')
		// });
		switch($(this).val()) {
		
		case "1":

			$(".options_for_orders").removeClass('hidden')
			$(".options_for_delivery").addClass('hidden').val('')

		break;

		case "2":
			$(".options_for_delivery").removeClass('hidden')
			$(".options_for_orders").addClass('hidden').val('')

		break;

		default:
		// code block
		}

		

		$('.selectpicker').selectpicker('refresh');
		$("#moreOtions").addClass('hidden')
		$("#_preferences").css('display', 'block');

	});

	

	$(document).on('change', '.filterLevel1', function(event) {
			$(".filterLevel2").multiselect('clearSelection');
			$(".filterLevel3").multiselect('clearSelection');
			$("#filter2").attr('disabled', 'true');
			$("#filter3").attr('disabled', 'true');
	});

	$(document).on('change', '.filterLevel2', function(event) {
			$(".filterLevel3").multiselect('clearSelection');
			$("#filter3").attr('disabled', 'true');
	});

	$(document).on('click', '#filter1', function(event) {
		triggerLevel1()
	});

	$(document).on('click', '#filter2', function(event) {
		triggerLevel2()
	});

	$(document).on('click', '#filter3,#gen_report', function(event) {
		date1 = $("#txtFrom").val()
		date2 = $("#txtTo").val()
		triggerLevel3()
	});

	$(document).on('click', '#setFilters', function(event) {
		$("#txtTo").val(date2)
		$("#txtFrom").val(date1)
		$("#_prefModal").modal()
	});


	//The default and extra fields are updated and pushed to corresponding arrays
	$(document).on('click', '#update_table', function(event) {
		event.preventDefault();

		if($("#reference").val().length > 0 && $("#po").val().length > 0 && $("#buyer_po").val().length > 0 && $("#ocn").val().length > 0 && $("#customer").val().length > 0 && $("#material").val().length > 0 && $("#style").val().length > 0 && $("#mat_sub").val().length > 0 && $("#buyer").val().length > 0 && $("#season").val().length > 0)
		{
			var listItems1 = $(".checklist1 li");
			var listItems2 = $(".checklist2 li");

			if(listItems2.length > 0)
			{
				defaultFields=[]
				extraFields=[]
				listItems2.each(function(idx, vl) {
					defaultFields.push($(this).html())
				});

				listItems1.each(function(idx, vl) {
					extraFields.push($(this).html())
				});

				arr1 = defaultFields.slice(); 
				arr2 = extraFields.slice();
				$("#optionsModal").modal('hide');	
				create_table(arr1,arr2)
			}
			else{
				$('.snackbar_span').html("No fields are selected,please add some to display the table.");
				showSnackbar();
			}

		}
		else{
			$('.snackbar_span').html("Please make sure all the values are selected.");
			showSnackbar();
		}
	});


	//logic to make the default and extra fields draggable
	$(document).on('click', '#moreOtions', function(event) {
		event.preventDefault();
		def_list="";
		extra_list="";

		for (var i = 0; i < defaultFields.length ; i++)
		{
		def_list+=' <li class="draggable-item">'+defaultFields[i]+'</li>'
		}

		for (var i = 0; i < extraFields.length ; i++)
		{
		extra_list+=' <li class="draggable-item">'+extraFields[i]+'</li>'
		}

		$(".checklist1").html('')
		$(".checklist1").html(extra_list)
		$(".checklist2").html('')
		$(".checklist2").html(def_list)
		$("#optionsModal").modal();	
	});

});