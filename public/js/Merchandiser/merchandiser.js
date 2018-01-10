/**
 * Merchandiser page script
 */
			$('#buyer-select').selectpicker({
				style: 'btn-default',
				size: 4
			});
			var $tableSel = $('#datatable-example-2');
			$tableSel.DataTable({
				"pageLength": 25,
				initComplete: function() {
					$('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first').append(' <button class="filterbtn" type="button" onClick="showFilter()"><i class="fa fa-filter" aria-hidden="true"></i></button>');
					$('.main-content > .dataTables_wrapper > .row > .col-sm-12 > .pull-right:first .dataTables_filter').css('display', 'inline-block');
					$('.main-content > .dataTables_wrapper > .row > .col-sm-12:first').append('<div class="filter-window"><h5><strong>Order Status Filter</strong></h5> <div class="row"> <div class="col-md-3"><input type="text" class="form-control" style="width:100%" name="daterange" /></div><div class="col-md-3"><select class="selectpicker" id="supplierName"> <option disabled selected>Select Supplier</option></select></div><div class="col-md-3"><select class="selectpicker" id="uvcNumber"> <option disabled selected>Select UVC</option></select></div><div class="col-md-3"><select class="selectpicker" id="selectDeliveryDate"> <option disabled selected>Select Delivery Date</option> </select></div></div><div class="filter-actions"><button class="btn btn-primary input-sm pull-right" id="reset">Clear Filter</button><button id="closeFilter" class="btn btn-default input-sm pull-right" onclick="showFilter()">Close</button><div class="clearfix"></div></div></div>');

					//For Custom Filters
					this.api().columns().every(function(i) {
						var column = this;
						if (i === 0 || i === 2 || i === 6) {
							var select;
							switch (i) {
								case 0:
									select = $('#uvcNumber')
									break;
								case 2:
									select = $('#supplierName')
									break;
								case 6:
									select = $('#selectDeliveryDate')
									break;
							}
							select.on('change', function() {
								var val = $.fn.dataTable.util.escapeRegex(
									$(this).val()
								);

								column
									.search(val ? '^' + val + '$' : '', true, false)
									.draw();
							});
							select.append('<option value=""> </option>');
							column.data().unique().sort().each(function(d, j) {
								cleanText = d.replace(/<\/?[^>]+(>|$)/g, "");
								select.append('<option value="' + cleanText + '">' + cleanText + '</option>')
							});
						}

					});


				}
			});

			
			$("#reset").on("click", function() {
				$('#uvcNumber').val('').change();
				$('#supplierName').val('').change();
				$('#selectDeliveryDate').val('').change();
				$.fn.dataTableExt.afnFiltering.length = 0;
				$tableSel.dataTable().fnDraw();
				$('input[name="daterange"]').val('Filter by Order Date');
				$('input[name="daterange"]').datepicker("refresh");
				$('.selectpicker').selectpicker('val', 1);
				$('.selectpicker').selectpicker('refresh');

			});
			$("#datatable-example-2_filter input").addClass('form-control input-sm');
			$("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
			$('#datatable-example-2_length select').addClass('form-control input-sm');
			$('.selectpicker').selectpicker();


			$('input[name="daterange"]').daterangepicker({

				},
				function(start, end, label) {
					// alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
					filterByDate(4, start, end); // We call our filter function
					$tableSel.dataTable().fnDraw(); // Manually redraw the table after filtering
				});

			$('input[name="daterange"]').val('Filter by Order Date');


			// Clear the filter. Unlike normal filters in Datatables,
			// custom filters need to be removed from the afnFiltering array.
			$('.cancelBtn').on('click', function(e) {
				e.preventDefault();
				$.fn.dataTableExt.afnFiltering.length = 0;
				$tableSel.dataTable().fnDraw();
			});
			function showFilter() {
			$('.filter-window').toggle();
		}

		/* Our main filter function
		 * We pass the column location, the start date, and the end date
		 */
		var filterByDate = function(column, startDate, endDate) {
			// Custom filter syntax requires pushing the new filter to the global filter array
			$.fn.dataTableExt.afnFiltering.push(
				function(oSettings, aData, iDataIndex) {
					var rowDate = normalizeDate(aData[column]),
						start = normalizeDate(startDate),
						end = normalizeDate(endDate);

					// If our date from the row is between the start and end
					if (start <= rowDate && rowDate <= end) {
						return true;
					} else if (rowDate >= start && end === '' && start !== '') {
						return true;
					} else if (rowDate <= end && start === '' && end !== '') {
						return true;
					} else {
						return false;
					}
				}
			);
		};

		// converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220
		var normalizeDate = function(dateString) {
			var date = new Date(dateString);
			var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
			return normalized;
		}
		// $(function() {
		// 	var url = window.location;
		// 	$('.dynamic-menu a[href="' + url + '"]').parent('li').addClass('current-page');
		// 	$('.dynamic-menu a').filter(function() {
		// 		return this.href == url;
		// 	}).parent('li').addClass('current-page').find('a').addClass('active');
		// });
/**
 * Function to show wcl panel
 * @author <praveen>
 */
function viewWcl(id){
	try{
         $.ajax({
             url:base_url + "/merchandiser/get-wcl-details",
             dataType:"json",
             method:"get",
             data:{id:id},
             success: function(data){
             	$('#wcl_id').val(id);
             	var table = '<table class="table table-bordered"><thead><tr><th>UVC TCO</th><th>Size(ANS)</th><th>Qty(Nos)</th></tr></thead><tbody>';
	             $.each(data.wcl_orders_qty, function(i, item){
	              var index = i+1;
	              table += '<tr id='+id+'><td>'+item.uvc_tco+'</td><td>'+item.size+'</td><td>'+item.quantity+'</td></tr>';
	             });
	             table += '</tbody></table>';
	             $('.quantity').html(table);
             },
             error: function (data) {
                  console.log(data);
             },
             complete: function () {
             	 $('.left-panel').addClass('active');
             }
         })
 } catch (e) {
     console.log("Error:"+e)
   }
}

