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
							 //console.log(data)
							 if(size[0] == "TAILLE")
							 {
								 svgDoc1.getElementById("size").innerHTML = "";
								 svgDoc1.getElementById("size_french").innerHTML = "";
								 svgDoc1.getElementById("size_english").innerHTML = "";
								 svgDoc1.getElementById("uvc_tco").innerHTML = uvc_tco;
								 svgDoc1.getElementById("size_list").innerHTML = "TAILLE UNIQUE";
								 svgDoc1.getElementById("cm").innerHTML = "ONE SIZE";
								 svgDoc1.getElementById("cm").setAttribute('font-size', '6px');
								 
								 svgDoc1.getElementById("tu_one_size").removeAttribute("visibility");
								 svgDoc.getElementById("size").innerHTML = "";
								 svgDoc.getElementById("size_french").innerHTML = "";
								 svgDoc.getElementById("size_english").innerHTML = "";
								 svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
								 svgDoc.getElementById("one_size").removeAttribute("visibility");
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
								 svgDoc1.getElementById("uvc_tco").innerHTML = uvc_tco;svgDoc1.getElementById("cm").setAttribute('font-size', '7.7px');
								 
								 svgDoc.getElementById("size").innerHTML = size[0];
								 //console.log(size[0].length)
								 if(size[0].length == 1)
								 {
									 svgDoc.getElementById("size").setAttribute('transform', 'matrix(1.0074 0 0 1 14 297.5785)');
									 svgDoc.getElementById("size_french").setAttribute('transform', 'matrix(1.0074 0 0 1 23.4422 291.033)');
									 svgDoc.getElementById("size_english").setAttribute('transform', 'matrix(1.0074 0 0 1 23.4422 297.5544)');
									 
								 }
								 else
								 {
									 svgDoc.getElementById("size").setAttribute('transform', 'matrix(1.0074 0 0 1 13 297.5785)');
									 svgDoc1.getElementById("size").setAttribute('transform', 'matrix(1.0074 0 0 1 92 36.4142)');
									 svgDoc.getElementById("size_french").setAttribute('transform', 'matrix(1.0074 0 0 1 30 291.033)');
									 svgDoc.getElementById("size_english").setAttribute('transform', 'matrix(1.0074 0 0 1 30 297.5544)');
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
										 // svgDoc1.getElementById("canada_price").innerHTML = value.Price;
										 // svgDoc1.getElementById("canada_club_price").innerHTML = value.Club_Price;
										 break;
										 case "CHF":
										 svgDoc1.getElementById("swiss_price").innerHTML = value.Price;
										 svgDoc1.getElementById("swiss_club_price").innerHTML = value.Club_Price;
										 break;
										 case "DZD":
										 // svgDoc1.getElementById("algeria_price").innerHTML = value.Price;
										 // svgDoc1.getElementById("algeria_club_price").innerHTML = value.Club_Price;
										 break;
										 case "MAD":
										 svgDoc1.getElementById("morocco_price").innerHTML = value.Price;
										 svgDoc1.getElementById("morroco_club_price").innerHTML = value.Club_Price;
										 break;
										 case "MUR":
										 // svgDoc1.getElementById("morris_price").innerHTML = value.Price;
										 // svgDoc1.getElementById("morris_club_price").innerHTML = value.Club_Price;
										 break;
										 case "SAR":
										 // svgDoc1.getElementById("saudi_club_price").innerHTML = value.Price;
										 break;
										 case "USD":
										 // svgDoc1.getElementById("us_price").innerHTML = value.Price;
										 // svgDoc1.getElementById("us_club_price").innerHTML = value.Club_Price;
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
								 // svgDoc1.getElementById("canada_price").innerHTML = "";
								 // svgDoc1.getElementById("canada_club_price").innerHTML = "";
								 svgDoc1.getElementById("swiss_price").innerHTML = "";
								 svgDoc1.getElementById("swiss_club_price").innerHTML = "";
								 // svgDoc1.getElementById("algeria_price").innerHTML = "";
								 // svgDoc1.getElementById("algeria_club_price").innerHTML = "";
								 svgDoc1.getElementById("morocco_price").innerHTML = "";
								 svgDoc1.getElementById("morroco_club_price").innerHTML = "";
								 // svgDoc1.getElementById("morris_price").innerHTML = "";
								 // svgDoc1.getElementById("morris_club_price").innerHTML = "";
								 // svgDoc1.getElementById("saudi_club_price").innerHTML = "";
								 // svgDoc1.getElementById("us_price").innerHTML = "";
								 // svgDoc1.getElementById("us_club_price").innerHTML = "";
							 }
			 
							 var node = $("#tree1").jstree('get_selected', true)[0].text;
							 //alert(node)
							 if(disney != "DI")
							 {
								 jsbarcode_hang(code, "barcode1", svgDoc1)
								 svgDoc1.getElementById("barcode1").setAttribute('transform', 'matrix(1.0971 0 0 1 27 45)');
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
 
 