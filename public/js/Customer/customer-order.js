var washcare = 0;
var mainlabel = 0;
var composition_percentage_global = 0;
var add_page_composition = 0;
var main_label = {};
var wash_label = {};
var wash_composition = {};
var deletemain = [];
var deletewash = [];

//$('body').css("background","#f3f3f3")
/**
 * method to check the input value is a number or not
 * @param  input data
 * @return {Boolean}
 */
function isNumber(evt) {
	try {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	} catch (e) {
		console.log("Error:" + e)
	}
}

/**
 * method to show the PO file upload window
 * @param  {[type]} id         [description]
 * @param  {[type]} table_name [description]
 * @param  {[type]} uvc        [description]
 * @param  {[type]} qntytable  [description]
 * @return {[type]}            [description]
 */
function showPOUpload(id,table_name,uvc,qntytable)
{
    if (po_file!= "null")
    {
        $('#po_upload').addClass('hidden')
        $('.upload-po').addClass('hidden')
        $('#po_file').removeClass('hidden')
    }
    else
    {
        $('#po_upload').removeClass('hidden')
        $('.upload-po').removeClass('hidden')
        $('#po_file').addClass('hidden')
    }
    var po_link = base_url+po_file
    $('#po_link').attr("href",po_link);
    $('#rowID').val(id);
    $('#tableName').val(tableName);
    $('#po-file-upload').modal('show')
}
/**
 * method pass the po number for
 * @param {[type]} obj         [description]
 * @param {[type]} uvc         [description]
 * @param {[type]} po          [description]
 * @param {[type]} template_id [description]
 * @param {[type]} table_name  [description]
 */
function setPO(obj,id,table_name)
{
   $('.po').css('border-color', '');
   var po_number  = $(obj).closest('tr').find('.po').val();
   
   if (po_number!="")
   {
    $.ajax({
        url: base_url + "/customer/orchestra/order-management/set-po-number",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            table_name: table_name,
            po_number:po_number,
            id:id
        },
        beforeSend: function () {
            $(".loader").show();
        },
        statusCode:
        {
            /** [Show response status based on HTTP status code] */
            200: function(data)
            {
               $('.snackbar_span').html('Purchase Order Number added Successfully');
               $(obj).closest('tr').find('.po').val(po_number);
               showSnackbar();
               if (po_number==""){
                 $(obj).closest('tr').find('.po').attr('disabled');
                 $(obj).closest('tr').find('.po').attr('placeholder', "Enter PO");
               }              
            },
            400: function()
            {
               $('.snackbar_span').html('Something went wrong');
               showSnackbar();
            }
        },
        error: function (data){
            console.log(data);
        },
        complete: function () {
            $(".loader").hide();
        }
    })
   }
   else{
    $(obj).closest('tr').find('.po').focus();
    $(obj).closest('tr').find('.po').css('border-color', '#F44336');
   }
   
}
/** [showSnackbar function] */
function showSnackbar() {
	try {
		// Get the snackbar DIV
		var x = document.getElementById("snackbar")

		// Add the "show" class to DIV
		x.className = "show";

		// After 3 seconds, remove the show class from DIV
		setTimeout(function () {
			x.className = x.className.replace("show", "");
		}, 3000);
	} catch (e) {
		console.log("Error:" + e)
	}
}

/**
 * This Function is used to select the order is label order or sticker order.
 * Depends upon the decision it will show label order details page or sticker details page. 
 * @return View
 */

function order_details(uvc, po,obj) {

	var season_code = $(obj).data('season_code')

	$(obj).css('pointer-events','none')
	$(obj).css('cursor','default')
    if($('#status_flag').val() == "Orders")
    {
        localStorage.setItem("page_navigation", "orders");
    }
    else
    {
        localStorage.setItem("page_navigation", "order-status");
    }
    //localStorage.setItem("page_navigation", search_string);
    if (po != "-") {
        sticker_order_details(uvc, po,"customer")
    } else {
        label_order_details(uvc,"customer",season_code)
    }
}

function cancelHangDiv(){
	var id = $('#hangtagform').find('#exist_id').val();
	
	var buyer = localStorage.getItem("buyer");
	$.ajax({
		url: base_url + "/customer/" + buyer + "/order-management/remove_hang_tag",
		type: "Post",
		data: {
			id: id,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			if(data.update_po_disable == 0)
			{
				$('#po-file-upload-button').css('display','none')
			}
			$('#hangtagform').find('#exist_id').val("")
			$('#hang_template_error').addClass('hidden')
			$("#hangtagform .left-panelorc").removeClass('active');
			$("#hangtagform .right-panel").removeClass('active');
			$("#hangtagform .backdrop").removeClass('active');
			$('#hangtagform .scalable').removeClass('active');
			$('#print_hang_button').addClass('hidden');
			$('#cancel_hang_button').addClass('hidden');
			$('#save_hang_button').removeClass('hidden');
			$('.extra_percentagehang').removeAttr('disabled');
			$('.tot_qty_tablehang').removeAttr('disabled');
			$('.tot_qty_tablehang').val('');
			$('.extra_percentagehang').val('');
			$('#hangtagform').find('.sample-artwork').html('');
			$('.hang-card').find('span').text('');
			$('.hang-card').find('span').removeClass('label-OrderApproved');
			$('#hangtagform').find('span').text('');
			$('#hangtagform').find('span').removeClass('label-OrderApproved');
			$('#hangtagform').find('.sample_back').html('');
			$('#tree1 >ul > li').each( function() {
			   enable( 'tree1',this.id );        
			})
			$("#tree1").jstree(true).deselect_all();
			$('#tree1').jstree('close_all');
		},
		error: function (data) {

		}
	})
}
/**
 * This Function is used Cancel Special Tag .
 *  
 * @return Boolean Value
 */

function cancelSpecialDiv(){
	var id = $('#specialtagform').find('#exist_id').val();
	
	var buyer = localStorage.getItem("buyer");
	$.ajax({
		url: base_url + "/customer/" + buyer + "/order-management/remove_special_tag",
		type: "Post",
		data: {
			id: id,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			if(data.update_po_disable == 0)
			{
				$('#po-file-upload-button').css('display','none')
			}
			$('#specialtagform').find('#exist_id').val("")
			$('#special_template_error').addClass('hidden')
			$("#specialtagform .left-panelorc").removeClass('active');
			$("#specialtagform .right-panel").removeClass('active');
			$("#specialtagform .backdrop").removeClass('active');
			$('#specialtagform .scalable').removeClass('active');
			$('#cancel_special_button').addClass('hidden');
			$('#save_special_button').removeClass('hidden');
			$('.special_qty').removeAttr('disabled');
			$('.special_qty').val('');
			$('#specialtagform').find('.sample-artwork').html('');
			$('.special-card').find('span').text('');
			$('.special-card').find('span').removeClass('label-OrderApproved');
			$('#specialtagform').find('span').text('');
			$('#specialtagform').find('span').removeClass('label-OrderApproved');
			$('#specialtagform').find('.sample_back').html('');
			$('#tree2 >ul > li').each( function() {
			   enable( 'tree2',this.id );        
			})
			$("#tree2").jstree(true).deselect_all();
			$('#tree2').jstree('close_all');
		},
		error: function (data) {

		}
	})
}
/**
 * This Function is used Cancel Heat Transfer Label .
 *  
 * @return Boolean Value
 */

function cancelHeatDiv(){
	var id = $('#heat_transferform').find('#exist_id').val();
	
	var buyer = localStorage.getItem("buyer");
	$.ajax({
		url: base_url + "/customer/" + buyer + "/order-management/remove_heat_transfer",
		type: "Post",
		data: {
			id: id,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			if(data.update_po_disable == 0)
			{
				$('#po-file-upload-button').css('display','none')
			}
			$('#heat_transferform').find('#exist_id').val("")
			$('#heat_transfer_template_error').addClass('hidden')
			$("#heat_transferform .left-panelorc").removeClass('active');
			$("#heat_transferform .right-panel").removeClass('active');
			$("#heat_transferform .backdrop").removeClass('active');
			$('#heat_transferform .scalable').removeClass('active');
			$('#cancel_heat_button').addClass('hidden');
			$('#save_heat_transfer_button').removeClass('hidden');
			$('.tot_qty_tableheat').removeAttr('disabled');
			$('.tot_qty_tableheat').val('');
			$('.extra_percentageheat').removeAttr('disabled');
			$('.extra_percentageheat').val('');
			$('#heat_transferform').find('.sample-artwork').html('');
			$('.heat_transfer-card').find('span').text('');
			$('.heat_transfer-card').find('span').removeClass('label-OrderApproved');
			$('#heat_transferform').find('span').text('');
			$('#heat_transferform').find('span').removeClass('label-OrderApproved');
			$('#heat_transferform').find('.sample_back').html('');
			$('#treeht >ul > li').each( function() {
			   enable( 'treeht',this.id );        
			})
			$("#treeht").jstree(true).deselect_all();
			$('#treeht').jstree('close_all');
		},
		error: function (data) {

		}
	})
}
/**
 * This Function is used Cancel Main Label.
 *  
 * @return Boolean Value
 */

function cancelMainDiv(){
	var id = $('#main_labelform').find('#exist_id').val();
	
	var buyer = localStorage.getItem("buyer");
	$.ajax({
		url: base_url + "/customer/" + buyer + "/order-management/remove_main_label",
		type: "Post",
		data: {
			id: id,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			if(data.update_po_disable == 0)
			{
				$('#po-file-upload-button').css('display','none')
			}
			$('#main_labelform').find('#exist_id').val("")
			$('#main_label_template_error').addClass('hidden')
			$("#main_labelform .left-panelorc").removeClass('active');
			$("#main_labelform .right-panel").removeClass('active');
			$("#main_labelform .backdrop").removeClass('active');
			$('#main_labelform .scalable').removeClass('active');
			$('#cancel_main_button').addClass('hidden');
			$('#save_main_label_button').removeClass('hidden');
			$('.main_label_qty').removeAttr('disabled');
			$('.main_label_qty').val('');
			$('#main_labelform').find('.sample-artwork').html('');
			$('.main_label-card').find('span').text('');
			$('.main_label-card').find('span').removeClass('label-OrderApproved');
			$('#main_labelform').find('span').text('');
			$('#main_labelform').find('span').removeClass('label-OrderApproved');
			$('#main_labelform').find('.sample_back').html('');
			$('#treeml >ul > li').each( function() {
			   enable( 'treeml',this.id );        
			})
			$("#treeml").jstree(true).deselect_all();
			$('#treeml').jstree('close_all');
		},
		error: function (data) {

		}
	})
}
/**
 * This Function is used Cancel Size Label.
 *  
 * @return Boolean Value
 */

function cancelSizeDiv(){
	var id = $('#size_labelform').find('#exist_id').val();
	
	var buyer = localStorage.getItem("buyer");
	$.ajax({
		url: base_url + "/customer/" + buyer + "/order-management/remove_size_label",
		type: "Post",
		data: {
			id: id,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			if(data.update_po_disable == 0)
			{
				$('#po-file-upload-button').css('display','none')
			}
			$('#size_labelform').find('#exist_id').val("")
			$('#size_label_template_error').addClass('hidden')
			$("#size_labelform .left-panelorc").removeClass('active');
			$("#size_labelform .right-panel").removeClass('active');
			$("#size_labelform .backdrop").removeClass('active');
			$('#size_labelform .scalable').removeClass('active');
			$('#cancel_size_button').addClass('hidden');
			$('#save_size_label_button').removeClass('hidden');
			$('.tot_qty_tablesize').removeAttr('disabled');
			$('.tot_qty_tablesize').val('');
			$('.extra_percentagesize').removeAttr('disabled');
			$('.extra_percentagesize').val('');
			$('#size_labelform').find('.sample-artwork').html('');
			$('.size_label-card').find('span').text('');
			$('.size_label-card').find('span').removeClass('label-OrderApproved');
			$('#size_labelform').find('span').text('');
			$('#size_labelform').find('span').removeClass('label-OrderApproved');
			$('#size_labelform').find('.sample_back').html('');
			$('#treesl >ul > li').each( function() {
			   enable( 'treesl',this.id );        
			})
			$("#treesl").jstree(true).deselect_all();
			$('#treesl').jstree('close_all');
		},
		error: function (data) {

		}
	})
}
/**
 * This Function is used Print Hang Tag .
 *  
 * @return Pdf
 */

function printHangDiv(data,disney) {

	var a = document.getElementById('svg_hang_front');
	var svgDoc = a.contentDocument;
	var svgRoot = svgDoc.documentElement;

	var a1 = document.getElementById(data);
	var svgDoc1 = a1.contentDocument;
	
	var svgRoot1 = svgDoc1.documentElement;
	
	var index_arr=[];
	var svgarr = [];
	var svgarr_front = [];
	var i=0;
	var ajax=0;
	$('#hang_tag_table tbody tr').each(function (key, val) {
		if(index_arr.includes($(this).find('.size').text()) != true)
		{
			if($(this).find('.size').text() != "")
				{
					var size_txt = $(this).find('.size').text();
					var uvc_tco = $(this).find('.uvc_tco').text();
					index_arr.push($(this).find('.size').text())
					var size = $(this).find('.size').text().split(" ")

					if (size[1] == "ANS") {
						if(size_txt.split(' ')[0] == 1)
						{
							var size1 = "YEAR";
						}
						else
						{
							var size1 = "YEARS";
						}
						
					} else {
						if(size_txt.split(' ')[0] == 1)
						{
							var size1 = "MONTH";
						}
						else
						{
							var size1 = "MONTHS";
						}
					}
					
					var code = $(this).find('.barcode').text();
					
					$.ajax({
						url: base_url + "/customer/order-management/get-tsl-size",
						type: 'GET',
						async: false,
						data: {
							size: $(this).find('.size').text(),
							uvc_tco:uvc_tco
						},
						tryCount: 0,
						retryLimit: 2,
						success: function (data) {
							if(size[0] == "TAILLE")
							{
								svgDoc1.getElementById("size").innerHTML = "";
								svgDoc1.getElementById("size_french").innerHTML = "";
								svgDoc1.getElementById("size_english").innerHTML = "";
								svgDoc1.getElementById("uvc_tco").innerHTML = uvc_tco;
								svgDoc1.getElementById("size_list").innerHTML = "TAILLE UNIQUE";
								svgDoc1.getElementById("cm").innerHTML = "ONE SIZE";
								
								svgDoc1.getElementById("tu_one_size").removeAttribute("visibility");
								svgDoc.getElementById("size").innerHTML = "";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
								svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
							}
							else
							{
								if(data.size_chart.length > 0)
								{
									svgDoc1.getElementById("cm").innerHTML =  data.size_chart[0].size+" CM";
									
								}
								else
								{
									svgDoc1.getElementById("cm").innerHTML = "";
								}

								svgDoc1.getElementById("size").innerHTML = size[0];
								svgDoc1.getElementById("size_french").innerHTML = size[1];
								svgDoc1.getElementById("size_english").innerHTML = size1;
								svgDoc1.getElementById("uvc_tco").innerHTML = uvc_tco;
								
								svgDoc.getElementById("size").innerHTML = size[0];
								if(size[0].length == 1)
								{
									svgDoc.getElementById("size").setAttribute('transform', 'matrix(1.0074 0 0 1 21.0324 291.266)');
									
								}
								else
								{
									svgDoc.getElementById("size").setAttribute('transform', 'matrix(1.0074 0 0 1 16.0324 291.266)');
								}
								svgDoc.getElementById("size_french").innerHTML = size[1];
								svgDoc.getElementById("size_english").innerHTML = size1;
							}
							
							
							if(data.opqt.length > 0)
							{
								$(data.opqt).each(function(index,value){
								switch(value.Currency)
								{
										case "EUR":
										var svg_price =	value.Price.split(',');
										var svg_club_price = value.Club_Price.split(',');
										svgDoc.getElementById("price1").innerHTML = svg_price[0];
										svgDoc.getElementById("price2").innerHTML = ","+svg_price[1];
										svgDoc.getElementById("club_price1").innerHTML = svg_club_price[0];
										svgDoc.getElementById("club_price2").innerHTML = ","+ 	svg_club_price[1];
										break;
										case "CAD":
										svgDoc1.getElementById("canada_price").innerHTML = value.Price;
										svgDoc1.getElementById("canada_club_price").innerHTML = value.Club_Price;
										break;
										case "CHF":
										svgDoc1.getElementById("swiss_price").innerHTML = value.Price;
										svgDoc1.getElementById("swiss_club_price").innerHTML = value.Club_Price;
										break;
										case "DZD":
										svgDoc1.getElementById("algeria_price").innerHTML = value.Price;
										svgDoc1.getElementById("algeria_club_price").innerHTML = value.Club_Price;
										break;
										case "MAD":
										svgDoc1.getElementById("morocco_price").innerHTML = value.Price;
										svgDoc1.getElementById("morroco_club_price").innerHTML = value.Club_Price;
										break;
										case "MUR":
										svgDoc1.getElementById("morris_price").innerHTML = value.Price;
										svgDoc1.getElementById("morris_club_price").innerHTML = value.Club_Price;
										break;
										case "SAR":
										svgDoc1.getElementById("saudi_club_price").innerHTML = value.Price;
										break;
										case "USD":
										svgDoc1.getElementById("us_price").innerHTML = value.Price;
										svgDoc1.getElementById("us_club_price").innerHTML = value.Club_Price;
										break;
										default:
										console.log("default");
										break;
									}
								})
							}
							else
							{
								svgDoc.getElementById("price1").innerHTML = "";
								svgDoc.getElementById("price2").innerHTML = "";
								svgDoc.getElementById("club_price1").innerHTML = "";
								svgDoc.getElementById("club_price2").innerHTML = "";
								svgDoc1.getElementById("canada_price").innerHTML = "";
								svgDoc1.getElementById("canada_club_price").innerHTML = "";
								svgDoc1.getElementById("swiss_price").innerHTML = "";
								svgDoc1.getElementById("swiss_club_price").innerHTML = "";
								svgDoc1.getElementById("algeria_price").innerHTML = "";
								svgDoc1.getElementById("algeria_club_price").innerHTML = "";
								svgDoc1.getElementById("morocco_price").innerHTML = "";
								svgDoc1.getElementById("morroco_club_price").innerHTML = "";
								svgDoc1.getElementById("morris_price").innerHTML = "";
								svgDoc1.getElementById("morris_club_price").innerHTML = "";
								svgDoc1.getElementById("saudi_club_price").innerHTML = "";
								svgDoc1.getElementById("us_price").innerHTML = "";
								svgDoc1.getElementById("us_club_price").innerHTML = "";
							}
			
							if(data.size_chart.length > 0)
							{
								svgDoc1.getElementById("cm").innerHTML =  data.size_chart[0].size+" CM";

//								if(data.size_chart[0].size.length == 2)
//								{
//								   svgDoc1.getElementById("cm").setAttribute('transform', 'matrix(1.0057 0 0 1 106.096 52.0822)');
//								}
//								else
//								{
//								   svgDoc1.getElementById("cm").setAttribute('transform', 'matrix(1.0057 0 0 1 102.096 52.0822)');
//								}
								
							}
							else
							{
								svgDoc1.getElementById("cm").innerHTML = "";
							}
							var node = $("#tree1").jstree('get_selected', true)[0].text;
							//alert(node)
							if(disney != "DI")
							{
								jsbarcode_hang(code, "barcode1", svgDoc1)
								svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 10 50)');
							}
							else
							{
								
                               jsbarcode_hang_disney(code, "barcode1", svgDoc1)
								if(node == "COD2")
								{
						   			svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 -5 -15)');
								}
								else if(node == "DBB1")
								{
									svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 0 -20)');
									//svgDoc1.getElementById("cm").setAttribute('transform', 'matrix(1.0057 0 0 1 92.096 47.0822)');
								}
								else
								{
									svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 0 -15)');
								}
							}


							var s = new XMLSerializer();
							var d = svgRoot1;
							var str = s.serializeToString(d);
							var svg = encodeURI(str)
							svgarr.push(svg)
							
							var s_front = new XMLSerializer();
							var d_front = svgRoot;
							var str_front = s_front.serializeToString(d_front);
							var svg_front = encodeURI(str_front)
							svgarr_front.push(svg_front)
							
							ajax++;
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							console.log(errorThrown);
						},
						complete: function () {

						}
					})
					
				i++;	
				}
			
		}
	});
	
	var svg_fin = svgarr.join(',_');
	var svg_fin_front = svgarr_front.join(',_');
	
	if(i == ajax)
	{
		//PopupWash(svg_fin,svgRoot1_back,svgRoot1_middle, dataid,svgDoc1);
		
		PopupHang(svg_fin_front, svg_fin,svgDoc,svgDoc1,disney);
	}
	
	
}


/**
 * This Function is used Print Special Tag .
 *  
 * @return Pdf
 */

function printSpecialDiv(data) {

	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	var svgRoot = svgDoc.documentElement;

	PopupSpecial(svgRoot);
}

/**
 * This Function is Sub Function of #printSpecialDiv.
 *  
 * @return Pdf
 */

function PopupSpecial(data) {
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	// window.open(base_url + "/customer/order-management/print-pdf?svg="+svg);
	
	$.ajax({
		url: base_url + "/customer/order-management/print-special-pdf",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg
		},
		async: false,
		success: function (data) {
			success = true;
			url = data;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}


/**
 * This Function is Sub Function of #printHangDiv.
 *  For print preview of 150 * 100 Hang Tag
 * @return Pdf
 */

function PopupHang(svgRoot, svgRoot1,svgDoc,svgDoc1,disney) {
//	var s = new XMLSerializer();
//	var d = svgRoot;
//	var str = s.serializeToString(d);
//	var svg = encodeURI(str)

//	var s1 = new XMLSerializer();
//	var d1 = svgRoot1;
//	var str1 = s1.serializeToString(d1);
//	var svg1 = encodeURI(str1)

	var success = false;
	var url = "";
	
	$.ajax({
		url: base_url + "/customer/order-management/print-hang-pdf",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svgRoot,
			svgRoot: svgRoot1
		},
		async: false,
		success: function (data) {
			if(disney != "DI")
			{
				svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 10.6793 50.8835)');
			}
			else
			{	var node = $("#tree1").jstree('get_selected', true)[0].text;
				 if(node == "COD2")
				 {
					 svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 32.6793 139.8835)');
				 }
			 	else if(node == "DBB1")
				{
					svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 10.6793 51.8835)');
				}
				 else
				 {

					svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 10.6793 57.8835)');
				 }
			}
			
			success = true;
			url = data;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}

/**
 * This Function is used to restrict alpha numeric and special characters.
 * 
 * @return boolean
 */

function checkNumbersOnly(e) {

	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		return false;
	}
}

function disable(id,node_id) {
  var node = $("#"+id).jstree().get_node( node_id );
  $("#"+id).jstree().disable_node(node); 
  node.children.forEach( function(child_id) {            
    disable( id,child_id );
  })
} 

function enable(id,node_id) {
  var node = $("#"+id).jstree().get_node( node_id );
  $("#"+id).jstree().enable_node(node); 
  node.children.forEach( function(child_id) {            
    enable( id,child_id );
  })
} 

/**
 * This Function is Sub Function of #label_order_details.
 * To Create the cards in label order details page
 * @return View
 */

function orchestra_label_order_details(buyer, buyer_id, uvc, data) {

	var tr = "";
	var barcode = "";
	$.each(data.order_details, function (index, value) {
		tr += '<tr><td>' + value.UVC_TCO + '</td><td>' + value.Size + '</td><td>' + value.BarcodeForPricesTags + '</td><td>' + value.Sum + '</td><td><input type="text" class="form-control input-sm" placeholder="Hangtag Count"><td><input type="text" class="form-control input-sm" placeholder="Specialtag Count"><td><input type="text" class="form-control input-sm" placeholder="Main & Size label Count"></td><td><input type="text" class="form-control input-sm" placeholder="WCL Count"></td></tr>';
	});
	var arr=[];
	$.each(data.barcode, function (index, value) {
		if(arr.includes(value.BuyerPONo) == false)
		{
			arr.push(value.BuyerPONo)
			barcode += value.BuyerPONo + " | ";
		}
		
	});
	//    $('.top-content').html("<ol class='breadcrumb'><li><a href='#'>Dashboard</a></li><li><a href='/customer/order-management/'>Orders</a></li><li><a href='#'>Order List</a></li><li class='active'>Order Detail for UVC " + uvc + "</li></ol></div>")
	$('.top-content').append('<h3 class="content-heading">Order Detail for UVC ' + uvc + '</h3><h5 class="poc_number"><strong>POs for this UVC : </strong><span id="poNo">' + barcode + '  </span></h5>');

	var merchandisersoption = "<option value=''>Select Option</option>";
	var update_disable=0;

	$.each(data.merchandisers, function (index, value) {
		merchandisersoption += "<option value=" + value.id + ">" + value.merchandiser_name + "</option>";
	});
	if (data.orchestra_hang_tag_orders != "") {
		var str = data.orchestra_hang_tag_orders[0].status;
		$hang_status = str.replace(/ /g, "");
		$hang_status_text = data.orchestra_hang_tag_orders[0].status;
		update_disable=1;
	} else {
		$hang_status = "";
		$hang_status_text = "";
	}
	if (data.orchestra_special_tag_orders != "") {
		var str = data.orchestra_special_tag_orders[0].status;
		$special_status = str.replace(/ /g, "");
		$special_status_text = data.orchestra_special_tag_orders[0].status;
		update_disable=1;
	} else {
		$special_status = "";
		$special_status_text = "";
	}
	if (data.orchestra_heat_transfer_orders != "") {
		var str = data.orchestra_heat_transfer_orders[0].status;
		$heat_transfer_status = str.replace(/ /g, "");
		$heat_transfer_status_text = data.orchestra_heat_transfer_orders[0].status;
		update_disable=1;
	} else {
		$heat_transfer_status = "";
		$heat_transfer_status_text = "";
	}
	if (data.orchestra_main_label_orders != "") {
		var str = data.orchestra_main_label_orders[0].status;
		$main_label_status = str.replace(/ /g, "");
		$main_label_status_text = data.orchestra_main_label_orders[0].status;
		update_disable=1;
	} else {
		$main_label_status = "";
		$main_label_status_text = "";
	}
	if (data.orchestra_size_label_orders != "") {
		var str = data.orchestra_size_label_orders[0].status;
		$size_label_status = str.replace(/ /g, "");
		$size_label_status_text = data.orchestra_size_label_orders[0].status;
		update_disable=1;
	} else {
		$size_label_status = "";
		$size_label_status_text = "";
	}
	if (data.orchestra_wash_care_label_orders != "") {
		var str = data.orchestra_wash_care_label_orders[0].status;
		$wash_status = str.replace(/ /g, "");
		$wash_status_text = data.orchestra_wash_care_label_orders[0].status;
		var wash_enable = "";
		var wash_name = data.orchestra_wash_care_label_orders[0].template_name + " - ";
		update_disable=1;
	} else {
		$wash_status = "";
		$wash_status_text = "";
		var wash_enable = "display:none !important";
		var wash_name = "";
	}

	if (localStorage.getItem("page_navigation") == "orders") {
		var onclick = "orders('customer')";
	} else {
		var onclick = "orderStatus('customer')";
	}
	if(update_disable == 0)
	{
		var update_disabel_button = "display:none";
	}
	else
	{
		var update_disabel_button = "";
	}

	$('.main-content').html('<input type="hidden" value="' + data.order_details[0].UVC + '" id="uvc_id"><input type="hidden" value="' + data.order_details[0].id + '" id="parcel_id"><input type="hidden" value="' + data.order_details[0].Season_Code + '" id="season_code"><input type="hidden" value="'+data.customers[0].id+'" id="customer_id"><hr/><div class="col-md-3"><h4>Quantity And Artworks</h4></div><div class="form-group col-md-offset-9 has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Merchandiser</label><select data-live-search="true" class="form-control selectpicker input-sm merchandisers" required>' + merchandisersoption + '</select><div class="help-block with-errors"></div><label class="hidden merchandiser_error" style="color:red">Please Select Merchandiser</label></div><div class="col-md-4"><div class="card add-card hang-card" data-toggle="modal" data-target=".bs-example-modal-lg" data-backdrop="static" data-keyboard="false"><span class="label label-' + $hang_status + ' label-status">' + $hang_status_text + '</span><div class="card-title"><p class="pull-right" id="clickmehang" style="cursor: pointer;">View &amp; Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p> <h5><b>Hangtag</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/ht_default.png" class="img-responsive center-block"></div></div><div class="col-md-6"><div class="text_artwork"></div></div></div></div></div><div class="col-md-4"><div class="card heat_transfer-card" style="min-height:279px"><span class="label label-' + $heat_transfer_status + ' label-status">' + $heat_transfer_status_text + '</span><div class="card-title"><p class="pull-right" id="clickmeheat_transfer" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b>Heat Transfer</b></h5></div><div class="row"><div class="col-md-10 col-md-offset-1"><div class="img_artwork"><img src="' +
		base_url + '/images/HT.jpg" style="width: 165px;" class="img-responsive center-block"></div></div></div></div></div><div class="col-md-4"><div class="card special-card" style="min-height:279px"><span class="label label-' + $special_status + ' label-status">' + $special_status_text + '</span><div class="card-title"><p class="pull-right" id="clickmespecial" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b>Specialtag</b></h5></div><div class="row"><div class="col-md-10 col-md-offset-1"><div class="img_artwork"><img src="' +
		base_url + '/images/st_default.png" class="img-responsive center-block"></div></div></div></div></div><div class="col-md-4"><div class="card main_label-card" style="min-height:279px"><span class="label label-' + $main_label_status + ' label-status">' + $main_label_status_text + '</span><div class="card-title"><p class="pull-right" id="clickmemain_label" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b>Main Label</b></h5></div><div class="row"><div class="col-md-10 col-md-offset-1"><div class="img_artwork"><img src="' +
		base_url + '/images/ML.png" class="img-responsive center-block"></div></div></div></div></div> <div class="col-md-4"><div class="card size_label-card" style="min-height:279px"><span class="label label-' + $size_label_status + ' label-status">' + $size_label_status_text + '</span><div class="card-title"><p class="pull-right" id="clickmesize_label" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b>Size Label</b></h5></div><div class="row"><div class="col-md-10 col-md-offset-1"><div class="img_artwork"><img src="' +
		base_url + '/images/SL.png" class="img-responsive center-block"></div></div></div></div></div><div id="add_modal"></div><div class="bottom-bar"><div class="modal-footer"><button onclick="' + onclick + '" class="btn btn-danger pull-right btn-xs"><i class="fa fa-times" aria-hidden="true"></i>Close</button><button data-toggle="modal" id="po-file-upload-button" style="'+update_disabel_button+'" class="btn btn-success pull-right btn-xs">Update PO</button> </div></div>');
	
	//Before add_modal
	
	//<div class="col-md-4"><div class="card add-card main-card" style="height: 280px;"><span class="label label-' + $main_status + ' label-status">' + $main_status_text + '</span><div class="card-title"><p class="pull-right" id="clickmemain" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b class="mainlabelname">' + main_name + 'Main & Size Label</b></h5> </div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/ml_default.png" class="img-responsive center-block"></div></div></div><p id="main_label_card" style="'+main_enable+';cursor:pointer"><i class="fa fa-plus" aria-hidden="true"></i> Add Main & Size Label</p></div></div></div>
	
	
	$('#po-file-upload-button').click(function () {
		$('#po-file-upload-group').modal("show")
		$('#new_po_div_status').addClass('hidden');
		$('#new_po1_status').addClass('hidden');
		$('#map_po_status').addClass('hidden');
		$('#update_new_po_status').addClass('hidden');
		$('#update_po_status').addClass('hidden');
		$('#new_pos_status').addClass('hidden');
		$('#map_po_div_status').addClass('hidden');
		$('#exist_artwork_div_status').removeClass('hidden')
		$('#new_po_no_status').val("")
        $('#new_po_file_status').val("")
		$('#new_po_error_status').addClass('hidden')
		$('#new_po_file_error_status').addClass('hidden')
		$('#new_po_error_length_status').addClass('hidden');
		$.ajax({
			url: base_url + "/customer/" + buyer + "/order-management/get-existing-label-orders-po",
			type: "get",
			dataType: "json",
			data: {
				uvc: $('#uvc_id').val()
			},
			beforeSend: function () {

			},
			success: function (data) {



				$('#check_error_status').addClass('hidden')
				$('#new_po_error_status').addClass('hidden')
				$('#new_po_file_error_status').addClass('hidden')
				$('#new_po_error_length_status').addClass('hidden');
				$('#po-file-upload-group').find('#exist_artwork_div_status ul').html("");
				var newObj = data.orchestra_hang_tag_orders.concat(data.orchestra_special_tag_orders.concat(data.orchestra_main_label_orders.concat(data.orchestra_wash_care_label_orders.concat(data.orchestra_size_label_orders.concat(data.orchestra_heat_transfer_orders)))));
				var coun = 0;
				var options = "<option>Select PO</option>";
				var unique_po = []
				var old_po = []
				var new_po_div_status = 0;
				$(newObj).each(function (index1, value1) {
					if (value1.po_number != "" && value1.po_number != null) {
							
						if(old_po.includes(value1.po_number) != true)
						{
							old_po.push(value1.po_number)
							options += "<option data-id='"+value1.po_file+"'>" + value1.po_number + "</option>";
						}
						//old_po = value1.po_number;
						coun++;
						$('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="'+value1.type+'-'+value1.id+'" data-company="'+value1.company_id+'" class="check-list">'+value1.color_code+' '+value1.TemplateName+" - "+value1.TemplateType+'</li>')
                         new_po_div_status++;
					}
					else {
						$('#po-file-upload-group').find('#exist_artwork_div_status ul').append('<li data-id="'+value1.type+'-'+value1.id+'" data-company="'+value1.company_id+'" class="list-group-item">'+value1.color_code+' '+value1.TemplateName+" - "+value1.TemplateType+'</li>')
					}
					
				});
				
				
				
				//Start bootstrap checkbox 
				
				
				//list Item Hidden By Gowtham 
				$('.list-group.checked-list-box .list-group-item').each(function () {

					// Settings
					var $widget = $(this),
						$checkbox = $('<input type="checkbox" class="hidden" />'),
						color = ($widget.data('color') ? $widget.data('color') : "primary"),
						style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
						settings = {
							on: {
								icon: 'glyphicon glyphicon-check'
							},
							off: {
								icon: 'glyphicon glyphicon-unchecked'
							}
						};

					$widget.css('cursor', 'pointer')
					$widget.append($checkbox);

					// Event Handlers
					$widget.on('click', function () {
						$checkbox.prop('checked', !$checkbox.is(':checked'));
						$checkbox.triggerHandler('change');
						updateDisplay();
					});
					$checkbox.on('change', function () {
						updateDisplay();
					});


					// Actions
					function updateDisplay() {
						var isChecked = $checkbox.is(':checked');

						// Set the button's state
						$widget.data('state', (isChecked) ? "on" : "off");

						// Set the button's icon
						$widget.find('.state-icon')
							.removeClass()
							.addClass('state-icon ' + settings[$widget.data('state')].icon);

						// Update the button's color
						if (isChecked) {
							$widget.addClass(style + color + ' active');
						} else {
							$widget.removeClass(style + color + ' active');
						}
					}

					// Initialization
					function init() {

						if ($widget.data('checked') == true) {
							$checkbox.prop('checked', !$checkbox.is(':checked'));
						}

						updateDisplay();

						// Inject the icon if applicable
						if ($widget.find('.state-icon').length == 0) {
							$widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
						}
					}
					init();
				});

				$('#get-checked-data').on('click', function (event) {
					event.preventDefault();
					var checkedItems = {},
						counter = 0;
					$("#check-list-box li.active").each(function (idx, li) {
						checkedItems[counter] = $(li).text();
						counter++;
					});
					$('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
				});
				
				//company wise
				/* 
				list Item Hidden By Gowtham 
				$('.list-group-item').click(function(){
					$count_li=0;
					$('.list-group li').each(function(key,val){
						if($(this).hasClass('active'))
						{
							$count_li++;
						}
					});

					if($count_li > 0)
					{
						var company_id=$(this).data('company');
						$('.list-group li').each(function(key,val){
							if($(this).data('company') != company_id)
							{
								$(this).addClass('hidden');
							}
						})
					}
					else
					{
						$('.list-group li').each(function(key,val){
							$(this).removeClass('hidden');
						})
					}
					
				})*/
				
				//End bootstrap checkbox 
				
		   $('#po_select_div').html('<label>Select PO</label><select id="select_po_status" class="form-control" style="width:96%">' + options + '</select>')
                                        if (coun == 0) {
                                            $('#map_po_status').addClass('hidden');
                                            $('#new_po1_status').addClass('hidden');
                                            $('#new_po_div_status').removeClass('hidden');
                                            $('#new_pos_status').removeClass('hidden');
                                        } else {
                                            $('#map_po_status').removeClass('hidden');
                                            $('#new_po1_status').removeClass('hidden');
                                            $('#new_pos_status').addClass('hidden');
                                        }
                                        $('#new_po1_status').click(function () {
                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {

                                                $('#update_new_po_status').removeClass('hidden')
                                                $('#update_po_status').addClass('hidden')
                                                $('#new_pos_status').addClass('hidden')
                                                $('#new_po_div_status').removeClass('hidden')
                                                $('#map_po_div_status').addClass('hidden')
                                                $('#exist_artwork_div_status').removeClass('hidden')
                                                $('#check_error_status').addClass('hidden')

                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }

                                        });
                                        $('#map_po_status').click(function () {

                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {
                                                $('#update_po_status').removeClass('hidden')
                                                $('#update_new_po_status').addClass('hidden')
                                                $('#new_pos_status').addClass('hidden')
                                                $('#map_po_div_status').removeClass('hidden')
                                                $('#new_po_div_status').addClass('hidden')
                                                $('#exist_artwork_div_status').removeClass('hidden')
                                                $('#check_error_status').addClass('hidden')

                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }

                                        }); 
				
				
				$('#update_new_po_status').click(function () {

                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {
                                                $('#check_error_status').addClass('hidden')
                                                if ($('#new_po_no_status').val() != "" && $('#new_po_file_status').val() != "") {

                                                    if ($('#new_po_no_status').val().length > 9) {
                                                        $('#new_po_error_length_status').removeClass('hidden');
                                                        $('#new_po_error_status').addClass('hidden');
                                                        $('#new_po_file_error_status').addClass('hidden');
                                                    } else {
                                                        var a = [];
                                                        $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                            if (this.checked) {
                                                                a.push($(this).parent().attr('data-id'));
                                                            }
                                                        });
                                                        var aa = a.join(',');
                                                        var new_po_file = $('#new_po_file_status').prop('files')[0];
                                                        var new_po_no = $('#new_po_no_status').val();
                                                        
                                                        var form_data = new FormData();
                                                        form_data.append('id', aa);
                                                        form_data.append('po_file', new_po_file);
                                                        form_data.append('po', new_po_no);
                                                        form_data.append('_token', csrf_token);
                                                        $.ajax({
                                                            url: base_url + "/customer/" + buyer + "/order-management/update-label-orders-po",
                                                            dataType: 'text',
                                                            cache: false,
                                                            contentType: false,
                                                            processData: false,
                                                            data: form_data,
                                                            type: 'post',
                                                            success: function (data) {
                                                                $('#po-file-upload-group').modal('hide')
                                                                $('.snackbar_span').html('PO Updated Successfully');
                                                                showSnackbar();
                                                            },
                                                            error: function () {
                                                                console.log('somethig is wrong, please try later');
                                                            }

                                                        });
                                                    }

                                                } else {
                                                    if ($('#new_po_no_status').val() == "" && $('#new_po_file_status').val() == "") {
                                                        $('#new_po_error_status').removeClass('hidden')
                                                        $('#new_po_file_error_status').removeClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    } else if ($('#new_po_file_status').val() == "") {
                                                        $('#new_po_file_error_status').removeClass('hidden')
                                                        $('#new_po_error_status').addClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    } else if ($('#new_po_no_status').val() == "") {
                                                        $('#new_po_error_status').removeClass('hidden')
                                                        $('#new_po_file_error_status').addClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    }

                                                }
                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }
                                        });

				
                                        $('#new_pos_status').click(function () {
                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {
                                                $('#check_error_status').addClass('hidden')
                                                if ($('#new_po_no_status').val() != "" && $('#new_po_file_status').val() != "") {
                                                    if ($('#new_po_no_status').val().length > 9) {
                                                        $('#new_po_error_length_status').removeClass('hidden');

                                                        $('#new_po_error_status').addClass('hidden');
                                                        $('#new_po_file_error_status').addClass('hidden');
                                                    } else {
                                                        var a = [];
                                                        $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                            if (this.checked) {
                                                                a.push($(this).parent().attr('data-id'));
                                                            }
                                                        });
                                                        var aa = a.join(',');
                                                        var new_po_file = $('#new_po_file_status').prop('files')[0];
                                                        var new_po_no = $('#new_po_no_status').val();
                                                        
                                                        var form_data = new FormData();
                                                        form_data.append('id', aa);
                                                        form_data.append('po_file', new_po_file);
                                                        form_data.append('po', new_po_no);
                                                        form_data.append('_token', csrf_token);
                                                        $.ajax({
                                                           url: base_url + "/customer/" + buyer + "/order-management/update-label-orders-po",
                                                            dataType: 'text',
                                                            cache: false,
                                                            contentType: false,
                                                            processData: false,
                                                            data: form_data,
                                                            type: 'post',
                                                            success: function (data) {
                                                                $('#po-file-upload-group').modal('hide')
                                                                $('.snackbar_span').html('PO Updated Successfully');
                                                                showSnackbar();
                                                            },
                                                            error: function () {
                                                                console.log('somethig is wrong, please try later');
                                                            }

                                                        });
                                                    }

                                                } else {
                                                    if ($('#new_po_no_status').val() == "" && $('#new_po_file_status').val() == "") {
                                                        $('#new_po_error_status').removeClass('hidden')
                                                        $('#new_po_file_error_status').removeClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    } else if ($('#new_po_file_status').val() == "") {
                                                        $('#new_po_file_error_status').removeClass('hidden')
                                                        $('#new_po_error_status').addClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    } else if ($('#new_po_no_status').val() == "") {
                                                        $('#new_po_error_status').removeClass('hidden')
                                                        $('#new_po_file_error_status').addClass('hidden')
                                                        $('#new_po_error_length_status').addClass('hidden');
                                                    }
                                                }

                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }
                                        });
				
                                        $('#update_po_status').click(function () {

                                            var sList = 0;
                                            $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                if (this.checked) {
                                                    sList++;
                                                }
                                            });

                                            if (sList > 0) {
                                                $('#check_error_status').addClass('hidden')

                                                if ($('#select_po_status').val() != "Select PO") {
                                                    var a = [];
                                                    $('#exist_artwork_div_status input[type=checkbox]').each(function () {
                                                        if (this.checked) {
                                                            a.push($(this).parent().attr('data-id'));
                                                        }
                                                    });
                                                    var aa = a.join(',');
                                                    
													//alert($('#select_po_status option:selected').attr('data-id'))
                                                    $.ajax({
                                                        url: base_url + "/customer/" + buyer + "/order-management/map-label-orders-po",
                                                        type: "Post",
                                                        dataType: "json",
                                                        data: {
                                                            _token: csrf_token,
                                                            id: aa,
                                                            po: $('#select_po_status').val(),
															po_file:$('#select_po_status option:selected').attr('data-id')
                                                        },
                                                        beforeSend: function () {

                                                        },
                                                        success: function (data) {
                                                            $('#po-file-upload-group').modal('hide')
                                                            $('.snackbar_span').html('PO Updated Successfully');
                                                            showSnackbar();$('#po-file-upload-div').modal('hide')
								$('.snackbar_span').html('PO Updated Successfully');
								showSnackbar();
                                                        },
                                                        error: function (data) {

                                                        },
                                                        complete: function () {
                                                            $(".loader").hide();
                                                        }
                                                    })
                                                } else {
                                                    $('#select_error_status').removeClass('hidden')
                                                }
                                            } else {
                                                $('#check_error_status').removeClass('hidden')
                                            }

                                        });
			},
			error: function (data) {

			},
			complete: function () {
				$(".loader").hide();
			}
		})
	});

    $.fn.capitalize = function () {
        $.each(this, function () {
            this.value = this.value.replace(/\b[a-z]/gi, function ($0) {
                return $0.toUpperCase();
            });
            this.value = this.value.replace(/@([a-z])([^.]*\.[a-z])/gi, function ($0, $1) {
                console.info(arguments);
                return '@' + $0.toUpperCase() + $1.toLowerCase();
            });

        });
    }

    $("input").keyup(function () {
        $(this).capitalize();
    }).capitalize();

    $('#washcare_label_card').on('click', function (e) {

        if ($('.washcarename').val() != "") {
          
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"> <span></span>   <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
				
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label added successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare);
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
//        $('.extra_percentage').keyup(function () {
//            if ($(this).val() != "") {
//                var per = $(this).val();
//                $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
//                    var sum = $(this).find('.sum').text();
//                    var per_qty = (parseInt(sum) / 100) * parseInt(per);
//                    var tot_qty = parseInt(sum) + parseInt(per_qty);
//                    $(this).find('.tot_qty_table').val(tot_qty);
//                })
//            } else {
//                $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
//                    $(this).find('.tot_qty_table').val("");
//                })
//            }
//
//        })
		
    });
    
    /**
        Washcare Label Cloning
    */
    $('#clone_washcare_label_card').on('click', function (e) {

        if ($('.washcarename').val() != "") {
          
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false">  <span></span>  <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();
                });
          
				
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label cloned successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
				
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare);
            $('#washcarelabelform'+washcare).find('#wcl_name'+washcare).val($('#washcarelabelform').find('#wcl_name').val())
            $('#washcarelabelform'+washcare).find('#ocn_number_wash'+washcare).val($('#washcarelabelform').find('#ocn_number_wash').val())
           
            
            $('.washing_select'+washcare).selectpicker('val',$('.washing_select').selectpicker('val'));
            $('.whitening_select'+washcare).selectpicker('val',$('.whitening_select').selectpicker('val'));
            $('.dryer_select'+washcare).selectpicker('val',$('.dryer_select').selectpicker('val'));
            $('.ironing_select'+washcare).selectpicker('val',$('.ironing_select').selectpicker('val'));
            $('.cleaning_select'+washcare).selectpicker('val',$('.cleaning_select').selectpicker('val'));
            $('.made_in_select'+washcare).selectpicker('val',$('.made_in_select').selectpicker('val'));
           
			//$('#washcarelabelform' + washcare+' .washonesizein').val($('#washcarelabelform .washonesizein').val())
			//$('#washcarelabelform' + washcare+' .washonesizecm').val($('#washcarelabelform .washonesizecm').val())
			$('.care_instruction_select'+washcare).selectpicker('val',$('.care_instruction_select').selectpicker('val'));

			$('.hiddencis option:selected').each(function () {

				$('select.hiddencis' + washcare).append("<option data-id='" + $(this).data('id') + "' data-french='" + $(this).data('french') + "' title='"+$(this).data('english')+"' data-english='" + $(this).data('english') + "' data-spanish='" + $(this).data('spanish') + "' data-portuguese='" + $(this).data('portuguese') + "' data-italian='" + $(this).data('italian') + "' data-dutch='" + $(this).data('dutch') + "' data-german='" + $(this).data('german')  + "' data-arabic='" + $(this).data('arabic') + "' data-greek='" + $(this).data('greek') + "' value=" + $(this).data('id') + " selected>" + $(this).data('english') + "</option>")
				$('.hiddencis' + washcare).selectpicker('refresh');

			});

			//$('.hiddencis'+washcare).selectpicker('val',$('.hiddencis').selectpicker('val'));
            //alert()
            $('#composition_remove_qty'+washcare).html($('#composition_remove_qty').html())
            
            $('#composition_remove_qty tr').each(function (ind,val) {
                var values= $(this).find('select').find(':selected').val()
                var v= $(this).attr('id');
                $('#composition_remove_qty'+washcare+' tr').each(function (inds,vals) {
                    if($(this).attr('id') == v)
                    {
                        $(this).find('select').val(values)
                        $(this).find('a').removeAttr('onclick')
                        $(this).find('a').attr('onclick','deleteComposition(this,'+washcare+')')
                    }
                });
            }); 
            
            $('#washcarelabelform table tbody tr').each(function (ind,val) {
                var values= $(this).find('.tot_qty_tablewash').val()
                var v= $(this).find('.uvc_tco').text();
                $('#washcarelabelform'+washcare+' table tbody tr').each(function (inds,vals) {
                    if($(this).find('.uvc_tco').text() == v)
                    {
                        $(this).find('.tot_qty_tablewash').val()
                        
                    }
                });
            });
            
            $('#washcarelabelform'+washcare+' table tbody').find('.tot_qty_tablewash_fin'+washcare).text($('#washcarelabelform table tbody').find('.tot_qty_tablewash_fin').text())
            var mySVG = document.getElementById("washcare_svg_front"+washcare);
            var svgDoc;
            mySVG.addEventListener("load", function () {
                svgDoc=mySVG.contentDocument;
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('astm'));
                
                var text_for_mad_in = '<p>' + $('select.made_in_select' + washcare).find(':selected').data('french') + "-" + $('select.made_in_select' + washcare).find(':selected').data('english') + "-" + $('select.made_in_select' + washcare).find(':selected').data('spanish') + "-" + $('select.made_in_select' + washcare).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + washcare).find(':selected').data('italian') + "-" + $('select.made_in_select' + washcare).find(':selected').data('dutch') + "-" + $('select.made_in_select' + washcare).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('greek')  + '</p>';
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;
                
                svgDoc.getElementById("text_care_instruction").innerHTML = "";
                $('.hiddencis' + washcare + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek')  + '</p>';
                    svgDoc.getElementById("text_care_instruction").innerHTML += value;
                });
                
                 svgDoc.getElementById("parts_composition").innerHTML ="";
              $('#composition_remove_qty' + washcare + ' tr').each(function () {
                  if($(this).find('select').find(':selected').val() != "Select Option")
                  {
                       svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german') + "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek') + '</p>';
                  }
                 
                  var id=$(this).attr('id')
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                  $(compss).each(function (index1,value1) {
                      var english = value1.split('_');

                    svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
                });
              })
            $('.composition_error_hidden').addClass('hidden')
            var washcomp=[];
            $('#composition_remove_qty' + washcare + ' tr').each(function () {
                  var washcomp1=[];
                  var id=$(this).attr('id')
                  var value=$(this).find('select').find(':selected').val();
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                    $(compss).each(function (index1,value1){ 
                        var english = value1.split('_');
                        washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                    });
                washcomp.push(washcomp1)
            })
            var wash_finals = JSON.stringify(washcomp);
            localStorage.setItem('wash_composition_final'+washcare, wash_finals);
                
            });
            
            
            
            
            
            //$('#washcarelabelform'+washcare).find('.washing_select'+washcare).selectpicker('val','1')
            
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
       
    });


}

/**
 * This Function is used to invoke select picker.
 * 
 * @return select
 */

function selectpicker() {
	$('.selectpicker').selectpicker({
		style: 'btn-default',
		size: 4
	});

}

/**
 * This Function is used to invoke Jstree.
 * 
 * @return tree
 */

function treestructurespecial(data, tree, template_id,orchestra_special_tag_orders) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			
			$.ajax({
				url: base_url + "/customer/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.special_qty').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}
					if (orchestra_special_tag_orders != "") {
						 $('#'+tree+' >ul > li').each( function() {
						   disable( tree,this.id );        
						})
						$('.special_qty').attr('disabled','disabled')
						$('#ocn_number_special').attr('disabled','disabled')
					}

				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			
			$.ajax({
				url: base_url + "/customer/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.special_qty').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
/**
 * This Function is used to invoke Jstree For Heat Transfer.
 * 
 * @return tree
 */
function treestructureheat_transfer(data, tree, template_id,orchestra_heat_transfer_orders_data) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			
			$.ajax({
				url: base_url + "/customer/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tableheat').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:300px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}
					
					if (orchestra_heat_transfer_orders_data != "") {
						 $('#'+tree+' >ul > li').each( function() {
						   disable( tree,this.id );        
						})
						$('.extra_percentageheat').attr('disabled','disabled')
						$('.tot_qty_tableheat').attr('disabled','disabled')
						$('#ocn_number_heat').attr('disabled','disabled')
					}

				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			
			$.ajax({
				url: base_url + "/customer/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tableheat').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:300px;height:300px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
/**
 * This Function is used to invoke Jstree For Main Labels.
 * 
 * @return tree
 */
function treestructuremain_label(data, tree, template_id,orchestra_main_label_orders) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			
			$.ajax({
				url: base_url + "/customer/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.main_label_qty').attr('min',data.moq)
					$('#main_labelform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:150px;object-fit:cover">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}
					
					if (orchestra_main_label_orders != "") {
						 $('#'+tree+' >ul > li').each( function() {
						   disable( tree,this.id );        
						})
						$('.main_label_qty').attr('disabled','disabled')
						$('#ocn_number_main').attr('disabled','disabled')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			
			$.ajax({
				url: base_url + "/customer/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.main_label_qty').attr('min',data.moq)
					$('#main_labelform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
/**
 * This Function is used to invoke Jstree For Size Labels.
 * 
 * @return tree
 */
function treestructuresize_label(data, tree, template_id,orchestra_size_label_orders) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			
			$.ajax({
				url: base_url + "/customer/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tablesize').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}
					
					if (orchestra_size_label_orders != "") {
						 $('#'+tree+' >ul > li').each( function() {
						   disable( tree,this.id );        
						})
						$('.extra_percentagesize').attr('disabled','disabled')
						$('.tot_qty_tablesize').attr('disabled','disabled')
						$('#ocn_number_size').attr('disabled','disabled')
					}

				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			
			$.ajax({
				url: base_url + "/customer/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					$('.tot_qty_tablesize').attr('min',data.moq)
					$('#specialtagform').validator('update')
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<img src="' + data.treeviewData[0].template_file + '" style="width:150px;height:400px;">')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}
/**
 * This Function is used to invoke Jstree.
 * 
 * @return tree
 */

function treestructuremain(data, tree, template_id) {

	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			
			$.ajax({
				url: base_url + "/customer/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<object id="svg2" style="max-height: 350px;" class="center-block svg2" data="' + data.treeviewData[0].template_file + '" type="image/svg+xml"></object>')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			
			$.ajax({
				url: base_url + "/customer/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
                    customer_id: $('#customer_id').val(),
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					if (data.treeviewData.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<object id="svg2" style="max-height: 350px;" class="center-block svg2" data="' + data.treeviewData[0].template_file + '" type="image/svg+xml"></object>')
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}


/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcode(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 5.99,
		height: 343,
		fontSize: 60
	});
	var svg = document.getElementById('svgdiv').querySelector('svg'),
		canvas = document.createElement('canvas'),
		body = document.getElementsByTagName('body')[0],
		canvasTmp,
		svg_xml,
		ctx,
		img;
	// if our "svg" doesn't have 'xmlns' attribute we must add it
	//    svg.setAttribute('width', '188.931023622px');
	svg.setAttribute('font-size', '70px');
	svg.setAttribute('font-family', 'calibrib');

	canvas.id = 'canvas';
	canvas.width = svg.getBoundingClientRect().width;
	canvas.height = svg.getBoundingClientRect().height;

	body.appendChild(canvas);
	canvasTmp = document.getElementById('canvas');

	/* Time to play ;) */

	// https://developer.mozilla.org/en/XMLSerializer
	svg_xml = (new XMLSerializer()).serializeToString(svg);
	ctx = canvasTmp.getContext('2d');

	// JavaScript (HTML) image
	img = new Image();

	img.onload = function () {
		ctx.drawImage(img, 0, 0);
	};

	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

	// img.src - contains base64 URI from svg

	//remove temporary canvas
	canvasTmp.remove();

	svgDoc.getElementById(id).innerHTML = svg_xml;
}


/**
 * This Function is used to invoke Hang Tag JSBarcode.
 * 
 * @return barcode
 */

function jsbarcode_hang_disney(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 5.99,
        height: 286,
		lineColor: "cmyk(0%, 0%, 0%, 100%)",
        fontSize: 55
    });
    var svg = document.getElementById('svgdiv').querySelector('svg'),
        canvas = document.createElement('canvas'),
        body = document.getElementsByTagName('body')[0],
        canvasTmp,
        svg_xml,
        ctx,
        img;
    // if our "svg" doesn't have 'xmlns' attribute we must add it
    //    svg.setAttribute('width', '188.931023622px');
    svg.setAttribute('font-size', '70px');
    svg.setAttribute('font-family', 'ocr-b-10bt');
    svg.setAttribute('width', '88px');
    svg.setAttribute('height', '80px');
    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;

	body.appendChild(canvas);
	canvasTmp = document.getElementById('canvas');

	/* Time to play ;) */

	// https://developer.mozilla.org/en/XMLSerializer
	svg_xml = (new XMLSerializer()).serializeToString(svg);
	ctx = canvasTmp.getContext('2d');

	// JavaScript (HTML) image
	img = new Image();

	img.onload = function () {
		ctx.drawImage(img, 0, 0);
	};

	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

	// img.src - contains base64 URI from svg

	//remove temporary canvas
	canvasTmp.remove();

	var allGs = document.getElementsByTagName('g');
	var firstG = allGs[0];
	var firstChild = firstG.childNodes[0];
	
	firstChild.setAttribute("x","20")
	
	var xchil = document.getElementById('svgdiv')

	svgDoc.getElementById(id).innerHTML = xchil.innerHTML;
}
/**
 * This Function is used to invoke Hang Tag JSBarcode.
 * 
 * @return barcode
 */

function jsbarcode_hang(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 5.99,
        height: 320,
		lineColor: "cmyk(0%, 0%, 0%, 100%)",
        fontSize: 55
    });
    var svg = document.getElementById('svgdiv').querySelector('svg'),
        canvas = document.createElement('canvas'),
        body = document.getElementsByTagName('body')[0],
        canvasTmp,
        svg_xml,
        ctx,
        img;
    // if our "svg" doesn't have 'xmlns' attribute we must add it
    //    svg.setAttribute('width', '188.931023622px');
    svg.setAttribute('font-size', '70px');
    svg.setAttribute('font-family', 'ocr-b-10bt');
    svg.setAttribute('width', '80px');
    svg.setAttribute('height', '100px');
    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;

	body.appendChild(canvas);
	canvasTmp = document.getElementById('canvas');

	/* Time to play ;) */

	// https://developer.mozilla.org/en/XMLSerializer
	svg_xml = (new XMLSerializer()).serializeToString(svg);
	ctx = canvasTmp.getContext('2d');

	// JavaScript (HTML) image
	img = new Image();

	img.onload = function () {
		ctx.drawImage(img, 0, 0);
	};

	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);


	//remove temporary canvas
	canvasTmp.remove();
	
	
	var allGs = document.getElementsByTagName('g');
	var firstG = allGs[0];
	var firstChild = firstG.childNodes[0];
	
	firstChild.setAttribute("x","20")
	
	var xchil = document.getElementById('svgdiv')

	svgDoc.getElementById(id).innerHTML = xchil.innerHTML;
}

/**
 * This Function is used to set hang tag in local storage.
 * 
 * @return void
 */

function setHangTagStatus(colour) {
    if ($('#hangtagform').find('#exist_id').val() != "") {
        var exist_id = $('#hangtagform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }

    var template_id = $('#hangtagform').find('#tree1').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("hang_tag", exist_id);
        setHangTag(localStorage.getItem("hang_tag"),colour)
        $('#hang_template_error').addClass('hidden')
        $("#hangtagform .left-panelorc").removeClass('active');
        $("#hangtagform .right-panel").removeClass('active');
        $("#hangtagform .backdrop").removeClass('active');
        $('#hangtagform .scalable').removeClass('active');
    } else {
        $('#hang_template_error').removeClass('hidden')
    }
}

/**
 * This Function is used to set special tag in local storage.
 * 
 * @return void
 */

function setSpecialTagStatus(colour) {

    if ($('#specialtagform').find('#exist_id').val() != "") {
        var exist_id = $('#specialtagform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#specialtagform').find('#tree2').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("special_tag", exist_id);
        setSpecialTag(localStorage.getItem("special_tag"),colour)
        $('#special_template_error').addClass('hidden')
        $("#specialtagform .left-panelorc").removeClass('active');
        $("#specialtagform .right-panel").removeClass('active');
        $("#specialtagform .backdrop").removeClass('active');
        $('#specialtagform .scalable').removeClass('active');
    } else {
        $('#special_template_error').removeClass('hidden')
    }
}

/**
 * This Function is used to set Heat Transfer in local storage.
 * 
 * @return void
 */

function setheat_transferStatus(colour) {

    if ($('#heat_transferform').find('#exist_id').val() != "") {
        var exist_id = $('#heat_transferform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#heat_transferform').find('#treeht').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("heat_transfer", exist_id);
        setHeatTransfer(localStorage.getItem("heat_transfer"),colour)
        $('#heat_transfer_template_error').addClass('hidden')
        $("#heat_transferform .left-panelorc").removeClass('active');
        $("#heat_transferform .right-panel").removeClass('active');
        $("#heat_transferform .backdrop").removeClass('active');
        $('#heat_transferform .scalable').removeClass('active');
    } else {
        $('#heat_transfer_template_error').removeClass('hidden')
    }
}
/**
 * This Function is used to set Main Label in local storage.
 * 
 * @return void
 */

function setMainLabelStatus(colour) {

    if ($('#main_labelform').find('#exist_id').val() != "") {
        var exist_id = $('#main_labelform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#main_labelform').find('#treeml').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("main_label", exist_id);
        setMainLabel(localStorage.getItem("main_label"),colour)
        $('#main_label_template_error').addClass('hidden')
        $("#main_labelform .left-panelorc").removeClass('active');
        $("#main_labelform .right-panel").removeClass('active');
        $("#main_labelform .backdrop").removeClass('active');
        $('#main_labelform .scalable').removeClass('active');
    } else {
        $('#main_label_template_error').removeClass('hidden')
    }
}
/**
 * This Function is used to set Size Labels in local storage.
 * 
 * @return void
 */

function setSizeLabelStatus(colour) {

    if ($('#size_labelform').find('#exist_id').val() != "") {
        var exist_id = $('#size_labelform').find('#exist_id').val();
    } else {
        var exist_id = "null";
    }
    var template_id = $('#size_labelform').find('#treesl').jstree('get_selected', true);
    if (template_id != "") {
        localStorage.setItem("size_label", exist_id);
        setSizeLabel(localStorage.getItem("size_label"),colour)
        $('#size_label_template_error').addClass('hidden')
        $("#size_labelform .left-panelorc").removeClass('active');
        $("#size_labelform .right-panel").removeClass('active');
        $("#size_labelform .backdrop").removeClass('active');
        $('#size_labelform .scalable').removeClass('active');
    } else {
        $('#size_label_template_error').removeClass('hidden')
    }
}

/**
 * This Function is used to set hang tag in db.
 * 
 * @return void
 */

function setHangTag(id,colour) {
	
	var template_id = $('#hangtagform').find('#tree1').jstree('get_selected', true);
	template_front_id = template_id[0].id;
	var template_back_id = $('#hang_tag_id').val();
	var ocn_number = $('#ocn_number_hang').val();
	//var onesizecm = $('.onesizecm').val();
	//var onesizein = $('.onesizein').val();
	
	var tot_qty_tablehang = [];
	var uvc_tco = [];
	var size = [];
	var size_id = [];
	var main_id = [];
	var barcode = [];
	$('#hangtagform tbody tr').each(function () {
		if ($(this).find('.tot_qty_tablehang').val() != undefined) {
			tot_qty_tablehang.push($(this).find('.tot_qty_tablehang').val())
			uvc_tco.push($(this).find('.uvc_tco').text())
			barcode.push($(this).find('.barcode').text())
			size.push($(this).find('.size').text())
			size_id.push($(this).find('.size_id').val())
			main_id.push($(this).find('.main_id').val())
		}

	})
	tot_qty_tablehang_fin = tot_qty_tablehang.join(',');
	uvc_tco_fin = uvc_tco.join(',');
	size_fin = size.join(',');
	size_id_fin = size_id.join(',');
	main_id_fin = main_id.join(',');
	barcode_fin = barcode.join(',');
	var buyer = localStorage.getItem("buyer");
//	alert()
//	alert($('#parcel_id').val());
	
	$.ajax({
		url: base_url + "/customer/" + buyer + "/order-management/set_hang_tag",
		type: "Post",
		data: {
			
			template_front_id: template_front_id,
			template_back_id: template_back_id,
			tot_qty_table: tot_qty_tablehang_fin,
			id: id,
			ocn_number:ocn_number,
			//cm:onesizecm,
			//in:onesizein,
			colour: colour,
			size_id: size_id_fin,
			main_id: main_id_fin,
			uvc_tco: uvc_tco_fin,
			barcode: barcode_fin,
			uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
			size: size_fin,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			
			$('#hangtagform').find('#exist_id').val(data.id)
			$('#print_hang_button').removeClass('hidden')
			//$('#cancel_hang_button').removeClass('hidden')
			$('#save_hang_button').addClass('hidden')
			$('#tree1 >ul > li').each( function() {
			   disable( 'tree1',this.id );        
			})
			$('.extra_percentagehang').attr('disabled','disabled')
			$('.tot_qty_tablehang').attr('disabled','disabled')
		},
		error: function (data) {

		}
	})
		$('.hang-card span').text('Order Approved')
		$('.hang-card span').addClass('label label-OrderApproved label-status')
		$('#po-file-upload-button').css('display','block')
}

/**
 * This Function is used to set special tag in db.
 * 
 * @return void
 */


function setSpecialTag(id,colour) {
	var myElements = $(".special_qty");
    var special_qty = [];
    for (var i=0;i<myElements.length;i++) {
        special_qty.push(myElements.eq(i).val());
    }
    
    var template_id = $('#specialtagform').find('#tree2').jstree('get_selected', true);
    template_front_id = template_id[0].id;
	var ocn_number = $('#ocn_number_special').val();
    var tot_qty_table = [];
    var uvc_tco = [];
    var size = [];
    $('#specialtagform tbody tr').each(function () {
        if ($(this).find('.tot_qty_table').val() != undefined) {
            tot_qty_table.push($(this).find('.tot_qty_table').val())
            uvc_tco.push($(this).find('.uvc_tco').text())
            size.push($(this).find('.size').text())
        }

    })
    tot_qty_table_fin = tot_qty_table.join(',');
    uvc_tco_fin = uvc_tco.join(',');
    size_fin = size.join(',');
    var buyer = localStorage.getItem("buyer");
    
    $.ajax({
        url: base_url + "/customer/" + buyer + "/order-management/set_special_tag",
        type: "Post",
        data: {
            
            template_front_id: template_front_id,
            id: id,
            tot_qty_table: special_qty,
			ocn_number:ocn_number,
            uvc_tco: uvc_tco_fin,
			colour: colour,
            uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
            size: size_fin,
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
			$('#specialtagform').find('#exist_id').val(data.id)
			$('#save_special_button').addClass('hidden')
			//$('#cancel_special_button').removeClass('hidden');
			$('#tree2 >ul > li').each( function() {
			   disable( 'tree2',this.id );        
			})
			$('.special_qty').attr('disabled','disabled')
        },
        error: function (data) {

        }
    })
	$('.special-card span').text('Order Approved')
	$('.special-card span').addClass('label label-OrderApproved label-status')
	$('#po-file-upload-button').css('display','block')
}
/**
 * This Function is used to set HeatTransfer in db.
 * 
 * @return void
 */


function setHeatTransfer(id,colour) {
  
    var template_id = $('#heat_transferform').find('#treeht').jstree('get_selected', true);
	template_id = template_id[0].id;
	var ocn_number = $('#ocn_number_heat').val();
	
	var tot_qty_tableheat = [];
	var uvc_tco = [];
	var size = [];
	var size_id = [];
	var main_id = [];
	$('#heat_transferform tbody tr').each(function () {
		if ($(this).find('.tot_qty_tableheat').val() != undefined) {
			tot_qty_tableheat.push($(this).find('.tot_qty_tableheat').val())
			uvc_tco.push($(this).find('.uvc_tco').text())
			size.push($(this).find('.size').text())
			size_id.push($(this).find('.size_id').val())
			main_id.push($(this).find('.main_id').val())
		}

	})
	tot_qty_tableheat_fin = tot_qty_tableheat.join(',');
	uvc_tco_fin = uvc_tco.join(',');
	size_fin = size.join(',');
	size_id_fin = size_id.join(',');
	main_id_fin = main_id.join(',');
	var buyer = localStorage.getItem("buyer");
	
	$.ajax({
		url: base_url + "/customer/" + buyer + "/order-management/set_heat_transfer",
		type: "Post",
		data: {
			
			template_id: template_id,
			tot_qty_table: tot_qty_tableheat_fin,
			ocn_number:ocn_number,
			id: id,
			colour: colour,
			size_id: size_id_fin,
			main_id: main_id_fin,
			uvc_tco: uvc_tco_fin,
			uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
			size: size_fin,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			$('#heat_transferform').find('#exist_id').val(data.id)
			$('#save_heat_transfer_button').addClass('hidden')
			//$('#cancel_heat_button').removeClass('hidden');
			$('#treeht >ul > li').each( function() {
			   disable( 'treeht',this.id );        
			})
			$('.extra_percentageheat').attr('disabled','disabled')
			$('.tot_qty_tableheat').attr('disabled','disabled')
		},
		error: function (data) {

		}
	})
		$('.heat_transfer-card span').text('Order Approved')
		$('.heat_transfer-card span').addClass('label label-OrderApproved label-status')
		$('#po-file-upload-button').css('display','block')
}
/**
 * This Function is used to set Main Label in db.
 * 
 * @return void
 */


function setMainLabel(id,colour) {
  
	var myElements = $(".main_label_qty");
    var main_label_qty = [];
    for (var i=0;i<myElements.length;i++) {
        main_label_qty.push(myElements.eq(i).val());
    }
	
    
    var template_id = $('#main_labelform').find('#treeml').jstree('get_selected', true);
    template_id = template_id[0].id;
	var ocn_number = $('#ocn_number_main').val();
    var tot_qty_table = [];
    var uvc_tco = [];
    var size = [];
    $('#main_labelform tbody tr').each(function () {
        if ($(this).find('.tot_qty_table').val() != undefined) {
            tot_qty_table.push($(this).find('.tot_qty_table').val())
            uvc_tco.push($(this).find('.uvc_tco').text())
            size.push($(this).find('.size').text())
        }

    })
    tot_qty_table_fin = tot_qty_table.join(',');
    uvc_tco_fin = uvc_tco.join(',');
    size_fin = size.join(',');
    var buyer = localStorage.getItem("buyer");
    
    $.ajax({
        url: base_url + "/customer/" + buyer + "/order-management/set_main_label",
        type: "Post",
        data: {
            
            template_id: template_id,
            id: id,
            tot_qty_table: main_label_qty,
			ocn_number:ocn_number,
            uvc_tco: uvc_tco_fin,
            colour: colour,
            uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
            size: size_fin,
            _token: csrf_token
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {
			$('#main_labelform').find('#exist_id').val(data.id)
			$('#save_main_label_button').addClass('hidden')
			//$('#cancel_main_button').removeClass('hidden');
			$('.snackbar_span').html('This is only for to place main label. Size label you want to place seperately');
			showSnackbar();
			$('#treeml >ul > li').each( function() {
			   disable( 'treeml',this.id );        
			})
			$('.main_label_qty').attr('disabled','disabled')
        },
        error: function (data) {

        }
    })
	$('.main_label-card span').text('Order Approved')
	$('.main_label-card span').addClass('label label-OrderApproved label-status')
	$('#po-file-upload-button').css('display','block')
}
/**
 * This Function is used to set Size Label in db.
 * 
 * @return void
 */


function setSizeLabel(id,colour) {
  
    var template_id = $('#size_labelform').find('#treesl').jstree('get_selected', true);
	template_id = template_id[0].id;
	var ocn_number = $('#ocn_number_size').val();
	
	var tot_qty_tablesize = [];
	var uvc_tco = [];
	var size = [];
	var size_id = [];
	var main_id = [];
	$('#size_labelform tbody tr').each(function () {
		if ($(this).find('.tot_qty_tablesize').val() != undefined) {
			tot_qty_tablesize.push($(this).find('.tot_qty_tablesize').val())
			uvc_tco.push($(this).find('.uvc_tco').text())
			size.push($(this).find('.size').text())
			size_id.push($(this).find('.size_id').val())
			main_id.push($(this).find('.main_id').val())
		}

	})
	tot_qty_tablesize_fin = tot_qty_tablesize.join(',');
	uvc_tco_fin = uvc_tco.join(',');
	size_fin = size.join(',');
	size_id_fin = size_id.join(',');
	main_id_fin = main_id.join(',');
	var buyer = localStorage.getItem("buyer");
	
	$.ajax({
		url: base_url + "/customer/" + buyer + "/order-management/set_size_label",
		type: "Post",
		data: {
			
			template_id: template_id,
			tot_qty_table: tot_qty_tablesize_fin,
			ocn_number:ocn_number,
			id: id,
			colour: colour,
			size_id: size_id_fin,
			main_id: main_id_fin,
			uvc_tco: uvc_tco_fin,
			uvc: $('#uvc_id').val(),
			parcel_id: $('#parcel_id').val(),
			season_code: $('#season_code').val(),
			size: size_fin,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			$('#size_labelform').find('#exist_id').val(data.id)
			$('#save_size_label_button').addClass('hidden')
			//$('#cancel_size_button').removeClass('hidden');
			$('#treesl >ul > li').each( function() {
			   disable( 'treesl',this.id );        
			})
			$('.extra_percentagesize').attr('disabled','disabled')
			$('.tot_qty_tablesize').attr('disabled','disabled')
		},
		error: function (data) {

		}
	})
		$('.size_label-card span').text('Order Approved')
		$('.size_label-card span').addClass('label label-OrderApproved label-status')
		$('#po-file-upload-button').css('display','block')
}

function artwork_modal(uvc,po)
{
	alert(uvc)
	alert(po)
}

function wordWrap(str, maxWidth) {
    var newLineStr = ""; done = false; res = '';
    do {                    
        found = false;
        // Inserts new line at first whitespace of the line
        for (i = maxWidth - 1; i >= 0; i--) {
            if (testWhite(str.charAt(i))) {
				
				var arabic = /[\u0600-\u06FF]/;
				
				if(arabic.test([str.slice(0, i), newLineStr].join('')) == true)
				{
					var join_text="";
					var split_text = [str.slice(0, i), newLineStr].join('').split(' - ')
					$(split_text).each(function(index,value){
						if(arabic.test(value) == true)
						{
							join_text += "<tspan style='font-family:Dejavu Sans' text-anchor='start'> - "+value+"</tspan>";
						}
						else
						{
							join_text += "<tspan text-anchor='end'  x='0' dy='7'> - "+value+"</tspan>";
						}
					})
					res = res + join_text;
				}
				else
				{
					res = res + "<tspan  x='0' dy='7'>" +[str.slice(0, i), newLineStr].join('') + "</tspan>";
				}
				
                
                str = str.slice(i + 1);
                found = true;
                break;
            }
        }
        // Inserts new line at maxWidth position, the word is too long to wrap
        if (!found) {
            res +="<tspan>" + [str.slice(0, maxWidth), newLineStr].join('')+ "</tspan>";
            str = str.slice(maxWidth);
        }

        if (str.length < maxWidth)
            done = true;
    } while (!done);
	
	if(arabic.test(str) == true)
	{
		var join_text="";
		var split_text = str.split(' - ')
		$(split_text).each(function(index,value){
			if(arabic.test(value) == true)
			{
				join_text += "<tspan dy='7' x='0' text-anchor='end' style='font-family:Dejavu Sans'> - "+value+" </tspan>";
			}
			else
			{
				join_text += "<tspan text-anchor='start'  x='0'> "+value+"</tspan>";
			}
		})
		return res + join_text;
	}
	else
	{
		return res + "<tspan  x='0' dy='7'>" + str + "</tspan>";
	}

}

function testWhite(x) {
    var white = new RegExp(/^\s$/);
    return white.test(x.charAt(0));
}
/**
 * [MOQ validation function]
 * @author <praveen>
 */
function checkMOQ(obj) {
	try {
		//alert()
			var moq =$(obj).attr("min"); 
			$(obj).data("min-error","Qty should be >= "+moq); 
	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}
function checkorcMOQ(obj) {
	try {
			var moq =$(obj).attr("min"); 
			$(obj).data("min-error","Qty should be >= "+moq); 
	} catch (e) {
		/** [error statements] */
		console.error("Error:" + e)
	}
}