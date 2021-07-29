/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */

function jsbarcodesolidpoly_orchestra(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.7,
		height: 370,
		fontSize: 50
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
	svg.setAttribute('font-size', '55.50px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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


function jsbarcodesolidcartonA(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.4,
		height: 347,
		fontSize: 50
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
	svg.setAttribute('font-size', '55.75px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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

function jsbarcodesolidcartonB(code, id, svgDoc) {	
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 6.5,
		height: 345,
		fontSize: 50
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
	svg.setAttribute('font-size', '55.75px');
	svg.setAttribute('font-family', 'calibrib');
	svg.setAttribute('letter-spacing', '15px');

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




function printHangDiv(front,back,disney,name,style_no,buyer_no,id,order_type,created_at,status,buyer) {
	var a = document.getElementById(front)
	var svgDoc = a.contentDocument;
	var svgRoot = svgDoc.documentElement;

	var a1 = document.getElementById(back)
	var svgDoc1 = a1.contentDocument;
	var svgRoot1 = svgDoc1.documentElement;
	console.log(svgDoc1)
	var index_arr=[];
	var svgarr = [];
	var svgarr_front = [];
	var i=0;
	var ajax=0;

	$.ajax({
				url: base_url + "/erp/job-manager/get-order-details",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					ref_name: name,
					style_no: style_no,
					buyer_no: buyer_no,
					id: id,
					created_at: created_at,
					order_type:order_type,
					buyer:buyer,
				},
				beforeSend: function() {

				},
				success: function(data) {
					console.log("-------")
					console.log(data)
						console.log("-------")
					details = data.order_details[0].hangtagqty;
					var node = name;
//					alert(disney)
//					alert(node)
					//alert(data.reference_name)
					$.each(details, function(index, val) {
						
							var size_txt = val.size;
							var uvc_tco = val.uvc_tco;
							var size = val.size.split(" ");
						
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
						
							var code = val.barcode;
						
						
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
								if(val.cm != null && val.cm !=null)
								{
									svgDoc1.getElementById("cm").innerHTML =  val.cm+" CM";
									
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
						

							$.each(val.prices, function(index,value){
								switch(value.Currency)
								{
										case "EUR":
										if(value.Price != "" && value.Price != null)
										{
											var svg_price =	value.Price.split(',');
											var svg_club_price = value.Club_Price.split(',');
											
											svgDoc.getElementById("price1").innerHTML = svg_price[0];
											svgDoc.getElementById("price2").innerHTML = ","+svg_price[1];
											svgDoc.getElementById("club_price1").innerHTML = svg_club_price[0];
											svgDoc.getElementById("club_price2").innerHTML = ","+ 	svg_club_price[1];	
										}
										else
										{
											svgDoc.getElementById("price1").innerHTML = "";
											svgDoc.getElementById("price2").innerHTML = "";
											svgDoc.getElementById("club_price1").innerHTML = "";
											svgDoc.getElementById("club_price2").innerHTML = "";	
										}
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
							});
							

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
					});

					if(details.length == ajax)
					{

						var svg_fin = svgarr.join(',_');
						var svg_fin_front = svgarr_front.join(',_');
						//PopupWash(svg_fin,svgRoot1_back,svgRoot1_middle, dataid,svgDoc1);
						
						PopupHang(svg_fin_front, svg_fin,svgDoc,svgDoc1,disney,name,id,style_no,buyer_no,created_at);
					}
				},
			});



	

	
	
}



/**
 * This Function is used Print Wash Care Labels.
 *  
 * @return Pdf
 */

function printWashDiv(data,data_back,data_middle,name,style_no,buyer_no,id,order_type,created_at,status,buyer) {

	var a1 = document.getElementById(data);
	var svgDoc1 = a1.contentDocument;
	var svgRoot1 = svgDoc1.documentElement;

	var a1_back = document.getElementById(data_back);
	var svgDoc1_back = a1_back.contentDocument;
	var svgRoot1_back = svgDoc1_back.documentElement;
	
	var a1_middle = document.getElementById(data_middle);
	var svgDoc1_middle = a1_middle.contentDocument;
	var svgRoot1_middle = svgDoc1_middle.documentElement;

	var index_arr=[];
	var svgarr = [];
	var svgarr_front = [];
	var i=0;
	var ajax=0;

	$.ajax({
				url: base_url + "/erp/job-manager/get-order-details",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					ref_name: name,
					style_no: style_no,
					buyer_no: buyer_no,
					id: id,
					created_at: created_at,
					order_type:order_type,
					buyer:buyer,
				},
				beforeSend: function() {

				},
				success: function(data) {
					
		
					details = data.order_details[0].washcarelabel_qty;
					Refname =data.reference_name;
					parentId= data.order_details[0].parentId;
					res = data.order_details[0] 	

					$.each(details, function(index, val) {
						
						var size_txt = val.size;
						var uvc_tco = val.uvc_tco;
						var size = val.size.split(" ")

						if (size[1] == "ANS") {
							var size1 = "years";
						} else {
							var size1 = "months";
						}

						var code = val.barcode;

						if(val.cm != null && val.cm != "")
						{
							svgDoc1.getElementById("size_in_fr_en1").innerHTML = val.cm+" cm ";
							svgDoc1.getElementById("size_in_fr_en2").innerHTML = val.cm+" cm ";
						}
						else
						{
							svgDoc1.getElementById("size_in_fr_en1").innerHTML = "";
							svgDoc1.getElementById("size_in_fr_en2").innerHTML = "";
						}

						svgDoc1.getElementById("size1").innerHTML = size_txt.replace(" ", "").toLowerCase() + "/" + size1;
						svgDoc1.getElementById("size2").innerHTML = size_txt.replace(" ", "").toLowerCase() + "/" + size1;
						svgDoc1.getElementById("parcel_code1").innerHTML = uvc_tco;
						svgDoc1.getElementById("parcel_code2").innerHTML = uvc_tco;
						svgDoc1.getElementById("barcode").setAttribute('transform', 'matrix(0.8899 0 0 1.1092 -40 135) rotate(270)');
						jsbarcodewash(code, 'barcode', svgDoc1)

						svgDoc1.getElementById("cofreet_symbol1").setAttribute('xlink:href',res.cofreet_symbol1);
						svgDoc1.getElementById("cofreet_symbol2").setAttribute('xlink:href',res.cofreet_symbol2);
						svgDoc1.getElementById("cofreet_symbol3").setAttribute('xlink:href',res.cofreet_symbol3);
						svgDoc1.getElementById("cofreet_symbol4").setAttribute('xlink:href',res.cofreet_symbol4);
						svgDoc1.getElementById("cofreet_symbol5").setAttribute('xlink:href',res.cofreet_symbol5);

						svgDoc1.getElementById("astm_symbol1").setAttribute('xlink:href',res.astm_symbol1);
						svgDoc1.getElementById("astm_symbol2").setAttribute('xlink:href',res.astm_symbol2);
						svgDoc1.getElementById("astm_symbol3").setAttribute('xlink:href',res.astm_symbol3);
						svgDoc1.getElementById("astm_symbol4").setAttribute('xlink:href',res.astm_symbol4);
						svgDoc1.getElementById("astm_symbol5").setAttribute('xlink:href',res.astm_symbol5);


						var s = new XMLSerializer();
						var d = svgRoot1;
						var str = s.serializeToString(d);
						var svg = encodeURI(str)
						svgarr.push(svg)

						// var s_front = new XMLSerializer();
						// var d_front = svgRoot;
						// var str_front = s_front.serializeToString(d_front);
						// var svg_front = encodeURI(str_front)
						// svgarr_front.push(svg_front)

						ajax++;
					});

					if(details.length == ajax)
					{

						var svg_fin = svgarr.join(',_');
						// var svg_fin_front = svgarr_front.join(',_');
						
						// PopupHang(svg_fin, svg_fin,svgDoc,svgDoc1,disney);
						PopupWash(svg_fin,svgRoot1_back,svgRoot1_middle,svgDoc1,parentId,name,id,style_no,buyer_no,created_at);
					}
				},
			});
	
	// if(i == ajax)
	// {
	// 	PopupWash(svgRoot1,svgRoot1_back,svgRoot1_middle,svgDoc1);
	// }
	
}


function PopupWash(data,data_back,data_middle,svgDoc1,pid,name,id,style_no,buyer_no,created_at) {

	var s = new XMLSerializer();
	// var d = data;
	// var str = s.serializeToString(d);
	// var svg = encodeURI(str)


	var d_back = data_back;
	var str_back = s.serializeToString(d_back);
	var svg_back = encodeURI(str_back)

	var d_middle = data_middle;
	var str_middle = s.serializeToString(d_middle);
	var svg_middle = encodeURI(str_middle)

    var success = false;
    var url = "";

    

    $.ajax({

        url: base_url + "/erp/job-manager/orchestra/print-wash-pdf",
        type: "post",
        data: {
            _token: csrf_token,
            svg: data,
            svg_back: svg_back,
            svg_middle: svg_middle,
            parentId : pid,
			name:name,
			id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
        },
        async: false,
        success: function (data) {
			svgDoc1.getElementById("barcode").setAttribute('transform', 'matrix(0.8899 0 0 1.1092 -21 125.454) rotate(270)');
            success = true;
            url = data.file_path;
        },
        error: function (data) {

        },
    })
    if (success) { //AND THIS CHANGED
        window.open(url)
    }
}


/**
 * This Function is used to invoke JSBarcode for Wash Care Labels.
 * 
 * @return barcode
 */

function jsbarcodewash(code, id, svgDoc) {
    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 5.99,
        height: 290,
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
    svg.setAttribute('width', '92.1px');
    svg.setAttribute('height', '133px');
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
function PopupHang(svgRoot, svgRoot1,svgDoc,svgDoc1,disney,name,id,style_no,buyer_no,created_at) {
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
		url: base_url + "/erp/job-manager/print-hang-pdf",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svgRoot,
			svgRoot: svgRoot1,
            id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
		},
		async: false,
		success: function (data) {
			
			success = true;
			url = data.file_path;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}


function DownloadArtwork(modal,i,template_file,style_no,buyer_no,id,order_type,created_at,status,type,template_id,disney) {
	
	switch(status) {
	  case "proof":
			if(type == "stickers")
			{
				genarateArtworkProofManager("tesco_svg_modal" + i, modal,style_no,buyer_no,id,order_type,created_at,status,"tesco",template_id);
			}
			else
			{
				genarateArtworkLabelManager("tesco_svg_modal" + i, modal,style_no,buyer_no,id,order_type,created_at,status,"tesco",template_id,"tesco_svg_back_modal" + i,"tesco_svg_back_text_modal" + i,"tesco_svg_front_modal" + i,"proof");
			}
	    break;

	  case "print_orchestra":
	    if(type == "hang_tag")
	    {
	    	printHangDiv('orchestra_modal_front'+ i,'orchestra_modal_back'+ i,disney,modal,style_no,buyer_no,id,order_type,created_at,status,"orchestra")
	  	}
	  	else if(type == "wash_care")
	  	{
   			printWashDiv('washcare_front'+ i,'washcare_back'+ i,'washcare_middle'+i,modal,style_no,buyer_no,id,order_type,created_at,status,"orchestra")

	  	}
	  	else if(type == "stickers")
	  	{

	   		genarateArtworkOrchestra("orchestra_svg_modal" + i, modal,style_no,buyer_no,id,order_type,created_at,status,"orchestra");
	  
	  	}
		else if(type == "stickers_proof")
		{
			genarateArtworkOrchestraProof("orchestra_svg_modal" + i, modal,style_no,buyer_no,id,order_type,created_at,status,"orchestra");
		}
		    break;

	  case "print":
			if(type == "stickers")
			{
				genarateArtworkPrintManager("tesco_svg_modal" + i, modal,style_no,buyer_no,id,order_type,created_at,status,"tesco",template_id);
			}
			else
			{
				genarateArtworkLabelManager("tesco_svg_modal" + i, modal,style_no,buyer_no,id,order_type,created_at,status,"tesco",template_id,"tesco_svg_back_modal" + i,"tesco_svg_back_text_modal" + i,"tesco_svg_front_modal" + i,"print");
			}
	   		
	    break;

	  default:
	    // code block
	}
	
}


function genarateArtworkOrchestra(data, name,style_no,buyer_no,id,order_type,created_at,status,buyer)
{
	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	// var name2=name;
	var zone = localStorage.getItem("zone");
	var svgarr = [];
	var ajax_count = 0;
	var name_count=0;
	// var b = " ";
	// var position = 5;
// console.log(name)
		// var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();
		$.ajax({
				url: base_url + "/erp/job-manager/get-order-details",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					ref_name: name,
					style_no: style_no,
					buyer_no: buyer_no,
					id: id,
					created_at: created_at,
					order_type:order_type,
					buyer:buyer,
				},
				beforeSend: function() {

				},
				success: function(data) {
				
					var svgRoot = svgDoc.documentElement;
					var zone= localStorage.getItem("zone");
					localStorage.setItem("zone", zone);

					details = data['order_details'][0];
					// console.log(details)
					switch(details.reference_name) 
					{


						// case "Orchestra WashCare":
						
						// 	printWashDiv('washcare_svg_front1','washcare_svg_back1','washcare_svg_middle1',details['washcarelabel_qty']);

						// break

						case "Std Carton Stickers":
							$(details['stdcartonstickerqty']).each(function(index,value){
								svgDoc.getElementById("po").innerHTML = details.po;
								svgDoc.getElementById("parcelcode").innerHTML = details.parcelCode
								// svgDoc.getElementById("size").innerHTML = value.size
								svgDoc.getElementById("theme").innerHTML = details.theme
								

								// if(details.age_group!=null)
								// {
								// 	svgDoc.getElementById("color").innerHTML = details.age_group
								// }

								if(details.height != null )
								{
									var num = 600 * details.width * details.height
									var val = num / (1000 * 1000 * 1000)
									svgDoc.getElementById("height").innerHTML = details.height
									svgDoc.getElementById("volume").innerHTML = val.toFixed(3)
								}
								else{
									svgDoc.getElementById("volume").innerHTML = ''
									svgDoc.getElementById("height").innerHTML =""
								}
								
								if(details.width != null )
								{
									svgDoc.getElementById("width").innerHTML = details.width
								}
								else{
									svgDoc.getElementById("volume").innerHTML = ''
								}
								svgDoc.getElementById("color").innerHTML = details.color_code
								jsbarcodesolidcartonA(value.barcode, 'barcode1', svgDoc)
								jsbarcodesolidcartonB(value.barcode, 'barcode2', svgDoc)
								// var s = new XMLSerializer();
								// var d = svgRoot;
								// var str = s.serializeToString(d);
								// svgarr.push(str)
							})
							// var svg_fin = svgarr.join(',_');
							 
							  PopupOrchestra(svgDoc,"STD-CARTON",id,style_no,buyer_no,created_at); 
							
							

						break;


						case "Std Poly Stickers":
							$(details['stdpolystickerqty']).each(function(index,value){
								console.log(value)

								if(details.group_id != 6)
								{
									svgDoc.getElementById("round").style.fill = details.age_group
									svgDoc.getElementById("pmm").setAttribute('visibility','hidden');
									svgDoc.getElementById("pmm").innerHTML = "";
								}
								else
								{
									svgDoc.getElementById("round").style.fill = "#FFFFFF"
									svgDoc.getElementById("pmm").removeAttribute('visibility','hidden');
									svgDoc.getElementById("pmm").innerHTML = "PMM";
								}

								//svgDoc.getElementById("round").style.fill = details.age_group
								svgDoc.getElementById("parcel_code").innerHTML = details.parcelCode
								svgDoc.getElementById("theme").innerHTML = details.theme
								// svgDoc.getElementById("size").innerHTML = value.size
								// svgDoc.getElementById("color").innerHTML = details.color_code
								jsbarcodesolidpoly_orchestra(value.barcode, 'barcode', svgDoc)
								var s = new XMLSerializer();
								var d = svgRoot;
								var str = s.serializeToString(d);
								svgarr.push(str)
							})
							var svg_fin = svgarr.join(',_');
							PopupTableOrchestra(svgDoc, "STD-POLY",details.sizeArr,details.qtyArr,details.sizBrkAr,id,style_no,buyer_no,created_at);
						break;



						case "Solid Polybag Stickers":
							$(details['solidpolystickerqty']).each(function(index,value){

								if(details.group_id != 6)
								{
									svgDoc.getElementById("round").style.fill = details.age_group
									svgDoc.getElementById("pmm").setAttribute('visibility','hidden');
									svgDoc.getElementById("pmm").innerHTML = "";
								}
								else
								{
									svgDoc.getElementById("round").style.fill = "#FFFFFF"
									svgDoc.getElementById("pmm").removeAttribute('visibility','hidden');
									svgDoc.getElementById("pmm").innerHTML = "PMM";
								}

								//svgDoc.getElementById("round").style.fill = details.age_group
								svgDoc.getElementById("parcel_code").innerHTML = value.uvc_tco
								svgDoc.getElementById("size").innerHTML = value.size
								if(value.size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").style.fontSize = "14px"
									svgDoc.getElementById("size").setAttribute('transform','matrix(1 0 0 1 172.625 28.7874)');
									svgDoc.getElementById("color").setAttribute('transform','matrix(1 0 0 1 173.625 45.3324)');
								}
								svgDoc.getElementById("color").innerHTML = details.color_code
								jsbarcodesolidpoly_orchestra(value.barcode, 'barcode', svgDoc)
								var s = new XMLSerializer();
								var d = svgRoot;
								var str = s.serializeToString(d);
								svgarr.push(str)
							})
							var svg_fin = svgarr.join(',_');
							PopupOrchestrasmall(svg_fin, "SOL-PS",svgDoc,id,style_no,buyer_no,created_at);
						break;


						case "Solid Carton Stickers":
							$(details['solidcartonstickerqty']).each(function(index,value){
								svgDoc.getElementById("po").innerHTML = details.po;
								svgDoc.getElementById("parcelcode").innerHTML = value.uvc_tco
								svgDoc.getElementById("size").innerHTML = value.size
								svgDoc.getElementById("theme").innerHTML = details.theme
								

								if(details.age_group!=null)
								{
									svgDoc.getElementById("color").innerHTML = details.age_group
								}

								if(details.height!=null)
								{
									var num = 600 * details.width * details.height
									var val = num / (1000 * 1000 * 1000)
									svgDoc.getElementById("height").innerHTML = details.height
									svgDoc.getElementById("volume").innerHTML = val.toFixed(3)
								}
								else{
									svgDoc.getElementById("volume").innerHTML = ''
									svgDoc.getElementById("height").innerHTML =""
								}
								
								if(details.width != null )
								{
									svgDoc.getElementById("width").innerHTML = details.width
								}
								else{
									svgDoc.getElementById("volume").innerHTML = ''
								}
								if(value.size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = '<tspan xmlns="http://www.w3.org/2000/svg" dx="-12" dy="-5" text-anchor="start">TAILLE </tspan><tspan xmlns="http://www.w3.org/2000/svg" dx="-14" dy="9" text-anchor="middle">UNIQUE</tspan>';
								}
								svgDoc.getElementById("color").innerHTML = details.color_code
								jsbarcodesolidcartonA(value.barcode, 'barcode1', svgDoc)
								jsbarcodesolidcartonB(value.barcode, 'barcode2', svgDoc)
								var s = new XMLSerializer();
								var d = svgRoot;
								var str = s.serializeToString(d);
								svgarr.push(str)
							})
							var svg_fin = svgarr.join(',_');
							PopupArrOrchestra(svg_fin, "SOL-CS",svgDoc,id,style_no,buyer_no,created_at);
						break;
						default:
						// code block
					}
				},
			});
}
function genarateArtworkOrchestraProof(data, name,style_no,buyer_no,id,order_type,created_at,status,buyer)
{
	
	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	// var name2=name;
	var zone = localStorage.getItem("zone");
	var svgarr = [];
	var ajax_count = 0;
	var name_count=0;
	// var b = " ";
	// var position = 5;
// console.log(name)
		// var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();
		$.ajax({
				url: base_url + "/erp/job-manager/get-order-details",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					ref_name: name,
					style_no: style_no,
					buyer_no: buyer_no,
					id: id,
					created_at: created_at,
					order_type:order_type,
					buyer:buyer,
				},
				beforeSend: function() {

				},
				success: function(data) {
				
					var svgRoot = svgDoc.documentElement;
					var zone= localStorage.getItem("zone");
					localStorage.setItem("zone", zone);

					details = data['order_details'][0];
					// console.log(details)
					switch(details.reference_name) 
					{


						// case "Orchestra WashCare":
						
						// 	printWashDiv('washcare_svg_front1','washcare_svg_back1','washcare_svg_middle1',details['washcarelabel_qty']);

						// break

						case "Std Carton Stickers":
							$(details['stdcartonstickerqty']).each(function(index,value){
								svgDoc.getElementById("po").innerHTML = details.po;
								svgDoc.getElementById("parcelcode").innerHTML = details.parcelCode
								// svgDoc.getElementById("size").innerHTML = value.size
								svgDoc.getElementById("theme").innerHTML = details.theme
								

								// if(details.age_group!=null)
								// {
								// 	svgDoc.getElementById("color").innerHTML = details.age_group
								// }

								if(details.height != null )
								{
									var num = 600 * details.width * details.height
									var val = num / (1000 * 1000 * 1000)
									svgDoc.getElementById("height").innerHTML = details.height
									svgDoc.getElementById("volume").innerHTML = val.toFixed(3)
								}
								else{
									svgDoc.getElementById("volume").innerHTML = ''
									svgDoc.getElementById("height").innerHTML =""
								}
								
								if(details.width != null )
								{
									svgDoc.getElementById("width").innerHTML = details.width
								}
								else{
									svgDoc.getElementById("volume").innerHTML = ''
								}
								svgDoc.getElementById("standardcolor").style.fill = "#FFFFFF"
								svgDoc.getElementById("color").innerHTML = details.color_code
								jsbarcodesolidcartonA(value.barcode, 'barcode1', svgDoc)
								jsbarcodesolidcartonB(value.barcode, 'barcode2', svgDoc)
								// var s = new XMLSerializer();
								// var d = svgRoot;
								// var str = s.serializeToString(d);
								// svgarr.push(str)
							})
							// var svg_fin = svgarr.join(',_');
							 
							  ProofPopupOrchestra(svgDoc,"STD-CARTON",id,style_no,buyer_no,created_at); 
							
							

						break;


						case "Std Poly Stickers":
							$(details['stdpolystickerqty']).each(function(index,value){
								console.log(value)

								if(details.group_id != 6)
								{
									svgDoc.getElementById("round").style.fill = details.age_group
									svgDoc.getElementById("pmm").setAttribute('visibility','hidden');
									svgDoc.getElementById("pmm").innerHTML = "";
								}
								else
								{
									svgDoc.getElementById("round").style.fill = "#FFFFFF"
									svgDoc.getElementById("pmm").removeAttribute('visibility','hidden');
									svgDoc.getElementById("pmm").innerHTML = "PMM";
								}

								//svgDoc.getElementById("round").style.fill = details.age_group
								svgDoc.getElementById("parcel_code").innerHTML = details.parcelCode
								svgDoc.getElementById("theme").innerHTML = details.theme
								svgDoc.getElementById("round").style.fill = "#FFFFFF"
								// svgDoc.getElementById("size").innerHTML = value.size
								// svgDoc.getElementById("color").innerHTML = details.color_code
								jsbarcodesolidpoly_orchestra(value.barcode, 'barcode', svgDoc)
								var s = new XMLSerializer();
								var d = svgRoot;
								var str = s.serializeToString(d);
								svgarr.push(str)
							})
							var svg_fin = svgarr.join(',_');
							ProofPopupTableOrchestra(svgDoc, "STD-POLY",details.sizeArr,details.qtyArr,details.sizBrkAr,id,style_no,buyer_no,created_at);
						break;



						case "Solid Polybag Stickers":
							$(details['solidpolystickerqty']).each(function(index,value){

								if(details.group_id != 6)
								{
									svgDoc.getElementById("round").style.fill = details.age_group
									svgDoc.getElementById("pmm").setAttribute('visibility','hidden');
									svgDoc.getElementById("pmm").innerHTML = "";
								}
								else
								{
									svgDoc.getElementById("round").style.fill = "#FFFFFF"
									svgDoc.getElementById("pmm").removeAttribute('visibility','hidden');
									svgDoc.getElementById("pmm").innerHTML = "PMM";
								}

								//svgDoc.getElementById("round").style.fill = details.age_group
								svgDoc.getElementById("parcel_code").innerHTML = value.uvc_tco
								svgDoc.getElementById("size").innerHTML = value.size
								svgDoc.getElementById("round").style.fill = "#FFFFFF"
								if(value.size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").style.fontSize = "14px"
									svgDoc.getElementById("size").setAttribute('transform','matrix(1 0 0 1 172.625 28.7874)');
									svgDoc.getElementById("color").setAttribute('transform','matrix(1 0 0 1 173.625 45.3324)');
								}
								svgDoc.getElementById("color").innerHTML = details.color_code
								jsbarcodesolidpoly_orchestra(value.barcode, 'barcode', svgDoc)
								var s = new XMLSerializer();
								var d = svgRoot;
								var str = s.serializeToString(d);
								svgarr.push(str)
							})
							var svg_fin = svgarr.join(',_');
							ProofPopupOrchestrasmall(svg_fin, "SOL-PS",svgDoc,id,style_no,buyer_no,created_at);
						break;


						case "Solid Carton Stickers":
							$(details['solidcartonstickerqty']).each(function(index,value){
								svgDoc.getElementById("po").innerHTML = details.po;
								svgDoc.getElementById("parcelcode").innerHTML = value.uvc_tco
								svgDoc.getElementById("size").innerHTML = value.size
								svgDoc.getElementById("theme").innerHTML = details.theme
								

								if(details.age_group!=null)
								{
									svgDoc.getElementById("color").innerHTML = details.age_group
								}

								if(details.height!=null)
								{
									var num = 600 * details.width * details.height
									var val = num / (1000 * 1000 * 1000)
									svgDoc.getElementById("height").innerHTML = details.height
									svgDoc.getElementById("volume").innerHTML = val.toFixed(3)
								}
								else{
									svgDoc.getElementById("height").innerHTML =""
									svgDoc.getElementById("volume").innerHTML = ''
								}
								
								if(details.width != null )
								{
									svgDoc.getElementById("width").innerHTML = details.width
								}
								else{
									svgDoc.getElementById("volume").innerHTML = ''
								}
								if(value.size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = '<tspan xmlns="http://www.w3.org/2000/svg" dx="-12" dy="-5" text-anchor="start">TAILLE </tspan><tspan xmlns="http://www.w3.org/2000/svg" dx="-14" dy="9" text-anchor="middle">UNIQUE</tspan>';
								}
								svgDoc.getElementById("color").innerHTML = details.color_code
								jsbarcodesolidcartonA(value.barcode, 'barcode1', svgDoc)
								jsbarcodesolidcartonB(value.barcode, 'barcode2', svgDoc)
								var s = new XMLSerializer();
								var d = svgRoot;
								var str = s.serializeToString(d);
								svgarr.push(str)
							})
							var svg_fin = svgarr.join(',_');
							ProofPopupArrOrchestra(svg_fin, "SOL-CS",svgDoc,id,style_no,buyer_no,created_at);
						break;
						default:
						// code block
					}
				},
			});
}

function genarateArtworkPrintManager(data, name,style_no,buyer_no,id,order_type,created_at,status,buyer,template_id) {

	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	var name2=name;
	var zone = localStorage.getItem("zone");
	var name = name + "-sticker";
	if (name == "TSL1-sticker" || name == "TSL3-sticker") {
		var svgarr = [];
		var ajax_count = 0;
		var name_count=0;
		var b = " ";
		var position = 3;
		var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();
		$.ajax({
				url: base_url + "/erp/job-manager/get-order-details",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					ref_name: ref_name,
					style_no: style_no,
					buyer_no: buyer_no,
					id: id,
					created_at: created_at,
					order_type:order_type
				},
				beforeSend: function() {

				},
				success: function(data) {
					var generate_data = data;
		$(data.order_details).each(function (key, val) {
			var svgRoot = svgDoc.documentElement;
			var zone=val.zone;
			localStorage.setItem("zone", zone);

			if ((name == "TSL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + val.orms_tpn;
			} else if ((name == "TSL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + val.orms_tpn;
			} else if ((name == "TSL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + val.tpnd;
			} else if ((name == "TSL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + val.tpnd;
			}



			if ((name == "TSL3-sticker")) {
				if (zone == "CE") {
					var barcode_change = val.ean_barcode
				} else {
					var barcode_change = val.ean_barcode
				}
				
				jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
				
				
				
	
				$.ajax({
						url: base_url + "/erp/job-manager/generatebarcodetsl3",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 1,
							name:"barcode_"+name_count,
							_token: csrf_token,
						},
						success: function (data) {
							ajax_count++;
							if (ajax_count == generate_data.order_details.length) {
								var svg_fin = svgarr.join(',_');
								PopupTescoGenerateSmallPrintManager(svg_fin, name,svgDoc);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
				
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else {
				if (zone == "CE") {
					var barcode_change = val.itf_barcode
				} else {
					var barcode_change = val.itf_barcode
				}
				
				//jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
				
				
	
				$.ajax({
						url: base_url + "/erp/job-manager/generatebarcodetsl1",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 2,
							name:"barcode_"+name_count,
							_token: csrf_token,
						},
						success: function (data) {
							
							ajax_count++;
							if (ajax_count == generate_data.order_details.length) {
								var svg_fin = svgarr.join(',_');
								PopupTescoGenerateSmallPrintManager(svg_fin, name,svgDoc);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
				
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			}

			if (zone == "CE") {
				var size = val.size;
			} else {
				var size = val.size;
			}

			var description = val.short_description.replace(/&/g,'&#038;');
			if ((name == "TSL3-sticker")) {
				if (size != '') {
					$.ajax({
						url: base_url + "/erp/job-manager/tesco/get-tsl-size",
						type: 'GET',
						async: false,
						data: {
							size: size,
							zone: zone
						},
						tryCount: 0,
						retryLimit: 2,
						success: function (data) {
								/** [ Dynamically change svg short description ] */
							if (description != '' && data.size_chart.length != 0) {
								var str = (description.toUpperCase()) + ' ' + data.size_chart[0].size;

								
								if (str.length > 24) {
									if (/\S/.test(str.charAt(24))) {
										//No Space
										if (str.indexOf(" ") == "-1") {
											svgDoc.getElementById("description").innerHTML = str.substr(0, 24).toUpperCase();
											svgDoc.getElementById("description1").innerHTML = str.substr(24).toUpperCase();
										} else {
											svgDoc.getElementById("description").innerHTML = str.substr(0, str.substr(0, 24).lastIndexOf(" ")).toUpperCase();
											svgDoc.getElementById("description1").innerHTML = str.substr(str.substr(0, 24).lastIndexOf(" ")).toUpperCase();
										}

									} else {
										//Space
										svgDoc.getElementById("description").innerHTML = str.substr(0, 24).toUpperCase();
										svgDoc.getElementById("description1").innerHTML = str.substr(24).toUpperCase();
									}
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
								} else {
									svgDoc.getElementById("description").innerHTML = str.toUpperCase();
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
									svgDoc.getElementById("description1").innerHTML = "";
								}
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
								svgDoc.getElementById("description1").innerHTML = '';
							} else if (description != '') {
								if (description.length > 24) {
									if (/\S/.test(description.charAt(24))) {
										//No Space
										if (description.indexOf(" ") == "-1") {
											svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
											svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
										} else {
											svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
											svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
										}

									} else {
										//Space
										svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
										svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
									}
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
								} else {
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
									svgDoc.getElementById("description").innerHTML = description.toUpperCase();
									svgDoc.getElementById("description1").innerHTML = "";
								}
							} else {
								svgDoc.getElementById("description").innerHTML = '';
								svgDoc.getElementById("description1").innerHTML = '';
							}
							var s = new XMLSerializer();
							var d = svgRoot;
							var str = s.serializeToString(d);
							var svg = encodeURI(str)
							svgarr.push(svg)
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
						},
						complete: function () {

						}
					})
				} else {
					/** [ Dynamically change svg short description ] */
					if (description != '') {
						
							if (description.length > 24) {
								if (/\S/.test(description.charAt(24))) {
									//No Space
									if (description.indexOf(" ") == "-1") {
										svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
										svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
									} else {
										svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
										svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
									}

								} else {
									//Space
									svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
									svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
								}
								svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
							} else {
								svgDoc.getElementById("description").innerHTML = description.toUpperCase();
								svgDoc.getElementById("description1").innerHTML = "";
								svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
							}
					} else {
						svgDoc.getElementById("description").innerHTML = '';
					}
					var s = new XMLSerializer();
					var d = svgRoot;
					var str = s.serializeToString(d);
					var svg = encodeURI(str)
					svgarr.push(svg)

				}
			} else {
				var description =val.short_description.replace(/&/g,'&#038;');
				if (description.length > 24) {
					if (/\S/.test(description.charAt(24))) {
						//No Space
						if (description.indexOf(" ") == "-1") {
							svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
							svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
						} else {
							svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
							svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
						}

					} else {
						//Space
						svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
						svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
					}
					svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
				} else {
					svgDoc.getElementById("description").innerHTML = description.toUpperCase();
					svgDoc.getElementById("description1").innerHTML = "";
					svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
				}
				var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);
				var svg = encodeURI(str)
				svgarr.push(svg)
			}
			name_count++;
		})
		},
		error: function(data) {

		},
		complete: function() {

		}
	})

		

	} else {
		var svgarr = [];
		var qr_code_arr = [];
		var qr_code_check_arr = [];
		var ajax_count = 0;
		var name_count=0;
		var b = " ";
		var position = 3;
		var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();
		$.ajax({
				url: base_url + "/erp/job-manager/get-order-details",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					ref_name: ref_name,
					style_no: style_no,
					buyer_no: buyer_no,
					id: id,
					created_at: created_at,
					order_type:order_type
				},
				beforeSend: function() {

				},
				success: function(data) {
					var generate_data = data;
					$(data.order_details).each(function (key, val) {
						var zone=val.zone;
						localStorage.setItem("zone", zone);
			var svgRoot = svgDoc.documentElement;
						
		if (val.supplier_name != '') {
		    svgDoc.getElementById("supplier").innerHTML = val.supplier_name.toUpperCase().replace('&', '&amp;');
		 } else {
		 	svgDoc.getElementById("supplier").innerHTML = "";
		 }
						
		if (val.po_number != '') {
			svgDoc.getElementById("po_number").innerHTML = val.po_number.toUpperCase();
		} else {
			svgDoc.getElementById("po_number").innerHTML = '';
		}

		/** [ Dynamically change svg CE style reference number ] */
		if (val.ce_style_ref != '') {
			svgDoc.getElementById("ce_style").innerHTML = val.ce_style_ref.toUpperCase();
		} else {
			svgDoc.getElementById("ce_style").innerHTML = '';
		}
		/** [ Dynamically change svg UK style reference number ] */
		if (val.uk_style_ref != '') {
			svgDoc.getElementById("uk_style").innerHTML = val.uk_style_ref.toUpperCase();
		} else {
			svgDoc.getElementById("uk_style").innerHTML = '';
		}
		/** [ Dynamically change svg EQOS code ] */
		if (val.eqos_site_code != '') {
			svgDoc.getElementById("eqos").innerHTML = val.eqos_site_code.toUpperCase();
		} else {
			svgDoc.getElementById("eqos").innerHTML = '';
		}
		
					
						/** [ Dynamically change  svg department ] */
		if (val.department != '') {
			/** [Give font-size for department] */
			if (val.department.length < 17) {
				svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
			} else {
				svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
			}
			/** [ Dynamically change svg department] */
			svgDoc.getElementById("department").innerHTML = val.department.toUpperCase().replace('&', '&amp;');
		} else {
			svgDoc.getElementById("department").innerHTML = '';
		}
		/** [ Dynamically change svg brand ] */
		if (val.brand != '') {
			/** [Give font-size for brand] */
			if (val.brand.length < 17) {
				svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
			} else {
				svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
			}
			svgDoc.getElementById("brand").innerHTML = val.brand.toUpperCase().replace('&', '&amp;');
		} else {
			svgDoc.getElementById("brand").innerHTML = '';
		}
						
										
		var tag_at_source = val.tag_at_source == 0 ? "no" : "yes";
		var rfid = val.rfid_compliant == 0 ? "no" : "yes";
						
		/** [ Dynamically svg RFID ] */
		if (rfid != '') {
			svgDoc.getElementById("rfid").innerHTML = rfid.toUpperCase();
		} else {
			svgDoc.getElementById("rfid").innerHTML = '';
		}
		/** [ Dynamically change svg tag at source ] */
		if (tag_at_source != '') {
			svgDoc.getElementById("tag_at_source").innerHTML = tag_at_source.toUpperCase();
		} else {
			svgDoc.getElementById("tag_at_source").innerHTML = '';
		}
		/** [ Dynamically change svg tag at source ] */
		if (val.season_code != '') {
			svgDoc.getElementById("season_code").innerHTML = val.season_code.toUpperCase();
		} else {
			svgDoc.getElementById("season_code").innerHTML = '';
		}
		/** [ Dynamically change svg manufacture date ] */
		if (val.manufacture_date != '') {
			svgDoc.getElementById("manufactured_date").innerHTML = val.manufacture_date.toUpperCase();
		} else {
			svgDoc.getElementById("manufactured_date").innerHTML = '';
		}
						
			if ((name == "TCL1-sticker" && zone == "CE") || (name == "TCL2-sticker" && zone == "CE") || (name == "TSL1-sticker" && zone == "CE") || (name == "THL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = val.cases_per_carton;
				//alert(data.tesco_item_master[0])
				svgDoc.getElementById("tpnd").innerHTML = val.orms_tpn;
				if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
				{
					svgDoc.getElementById("tpnd_last_digit").innerHTML = val.orms_tpn.substr(val.orms_tpn.length - 3);
				}
			} else if ((name == "TCL4-sticker" && zone == "CE") || (name == "THL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("unit_per_case").innerHTML = val.case_size;

				svgDoc.getElementById("tpnd").innerHTML =val.orms_tpn;
				
				if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
				{
					svgDoc.getElementById("tpnd_last_digit").innerHTML = val.orms_tpn.substr(val.orms_tpn.length - 3);
				}
			} else if ((name == "TCL4-sticker" && zone == "UK") || (name == "THL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("unit_per_case").innerHTML = val.case_size;

				svgDoc.getElementById("tpnd").innerHTML = val.tpnd;
			} else if ((name == "TCL1-sticker" && zone == "UK") || (name == "TCL2-sticker" && zone == "UK") || (name == "TSL1-sticker" && zone == "UK") || (name == "THL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = val.units_per_carton;

				svgDoc.getElementById("tpnd").innerHTML = val.tpnd;
			}
			if ((name == "THL3-sticker")) {

				if (zone == "CE") {
					var barcode_change = val.ean_barcode
				} else {
					var barcode_change = val.ean_barcode
				}
					//jsbarcodetescoean(barcode_change, 'barcode', svgDoc)
				
				
	
				$.ajax({
						url: base_url + "/erp/job-manager/generatebarcodeeanbig",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 1,
							name:"barcode_"+name_count,
							_token: csrf_token,
						},
						success: function (data) {
							ajax_count++;
							if (ajax_count == generate_data.order_details.length) {
								var svg_fin = svgarr.join(',_');
								var qr_code_fin = qr_code_arr.join(',_');
								var qr_code_check_fin = qr_code_check_arr.join(',_');
								PopupTescoGeneratePrintManager(svg_fin, name,svgDoc,template_id,generate_data.tesco_item_master[0].is_qrcode_required,qr_code_fin,qr_code_check_fin);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
				
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else if ((name == "TCL1-sticker") || (name == "THL1-sticker")) {

				if (zone == "CE") {
					var barcode_change = val.itf_barcode
				} else {
					var barcode_change = val.itf_barcode
				}
					//jsbarcodetesco(barcode_change, 'barcode', svgDoc)
				
	
				$.ajax({
						url: base_url + "/erp/job-manager/generatebarcodeitfbig",
						type: 'post',
						data: {
							barcode: barcode_change,
							type: 2,
							name:"barcode_"+name_count,
							_token: csrf_token,
						},
						success: function (data) {
							ajax_count++;
							if (ajax_count == generate_data.order_details.length) {
								var svg_fin = svgarr.join(',_');
								var qr_code_fin = qr_code_arr.join(',_');
								var qr_code_check_fin = qr_code_check_arr.join(',_');
								PopupTescoGeneratePrintManager(svg_fin, name,svgDoc,template_id,generate_data.tesco_item_master[0].is_qrcode_required,qr_code_fin,qr_code_check_fin);
							}
						},
						complete: function () {

						}
					})
				//svgDoc.getElementById("barcodeimage").setAttributeNS('http://www.w3.org/1999/xlink','xlink:href',"storage/app/template/prince.png");
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:hidden");
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			}

			if (zone == "CE") {
				var size = val.size;
			} else {
				var size = val.size;
			}

			var description = val.short_description.replace(/&/g,'&#038;');
			if ((name == "TCL4-sticker") || (name == "TSL3-sticker") || (name == "THL3-sticker")) {
				if (size != '') {
					$.ajax({
						url: base_url + "/erp/job-manager/tesco/get-tsl-size",
						type: 'GET',
						async: false,
						data: {
							size: size,
							zone: zone
						},
						tryCount: 0,
						retryLimit: 2,
						success: function (data) {
							/** [ Dynamically change svg short description ] */
							if (description != '' && data.size_chart.length != 0) {
								var desc_size = (description.toUpperCase()) + ' ' + data.size_chart[0].size;
								if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = oswordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
							} else if (description != '') {
								var desc_size = description.toUpperCase();
								
								if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = oswordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
								
							} else {
								svgDoc.getElementById("description").innerHTML = '';
							}
							var s = new XMLSerializer();
							var d = svgRoot;
							var str = s.serializeToString(d);
							svgarr.push(str)
							qr_code_arr.push(val.qr_code)
							qr_code_check_arr.push(val.buyer_po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
						},
						complete: function () {

						}
					})
				} 
				else {
					/** [ Dynamically change svg short description ] */
					if (description != '') {
							if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
							{
								var desc_size = description.toUpperCase();
								if(desc_size.length <= 22)
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
									if(name2 === "THL3")
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");

									}
									else
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 150)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
									}
								}
								else
								{
									str = wordWrap(desc_size, 22);
									svgDoc.getElementById("description").innerHTML = str;

									if(name2 === "THL3")
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");

									}
									else
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 163)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
									}
								}
							}
							else
							{
								/** [ Dynamically change svg short description ] */
								svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							}					
						} else {
							svgDoc.getElementById("description").innerHTML = '';
						}
					var s = new XMLSerializer();
					var d = svgRoot;
					var str = s.serializeToString(d);

					svgarr.push(str)
					qr_code_arr.push(val.qr_code)
					qr_code_check_arr.push(val.buyer_po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)

				}
			} else {
				if (description != '') {
					if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
					{
						if(description.length <= 20)
						{
							svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							if (name2 === "TCL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 125)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 137)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 125)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 137)");
							}
							else if (name2 === "TCL2")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.87, 0, 0, 1, 91.1544, 150)");
								svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
							}
							else if(name2 === "THL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 125)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 137)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 125)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 137)");
							}
							
						}
						else
						{
							str = oswordWrap(description, 23);
							svgDoc.getElementById("description").innerHTML = str;
							if (name2 === "TCL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 133)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 133)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 146)");
							}
							else if (name2 === "TCL2")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
								svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.87, 0, 0, 1, 91.1544, 163)");
								svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
							}
							else if(name2 === "THL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
							}
						}
					}
					else
					{
						/** [ Dynamically change svg short description ] */
						svgDoc.getElementById("description").innerHTML = description.toUpperCase();
					}					
				} else {
					svgDoc.getElementById("description").innerHTML = '';
				}
				var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);

				svgarr.push(str)
				qr_code_arr.push(val.qr_code)
				qr_code_check_arr.push(val.buyer_po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)
			}


	
			if((name == "TCL2-sticker") || (name == "TCL4-sticker"))
			{
				ajax_count++;
			}
			name_count++;
		})
			if((name == "TCL2-sticker") || (name == "TCL4-sticker"))
			{
				if (ajax_count == data.order_details.length) {
					var svg_fin = svgarr.join(',_');
					var qr_code_fin = qr_code_arr.join(',_');
					var qr_code_check_fin = qr_code_check_arr.join(',_');
					var zone = localStorage.getItem("zone");

					  PopupTescoGeneratePrintManager(svg_fin, name,svgDoc,template_id,generate_data.tesco_item_master[0].is_qrcode_required,qr_code_fin,qr_code_check_fin);

				}
			}
				},
				error: function(data) {

				},
				complete: function() {

				}
			});
		
		
	}


}

function genarateArtworkProofManager(data, name,style_no,buyer_no,id,order_type,created_at,status,buyer,template_id) {

	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	var name2=name;
	var name = name + "-sticker";
	if (name == "TSL1-sticker" || name == "TSL3-sticker") {
		var svgarr = [];
		var ajax_count = 0;
		var b = " ";
		var position = 3;
		var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();
	
		
			$.ajax({
				url: base_url + "/erp/job-manager/get-order-details",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					ref_name: ref_name,
					style_no: style_no,
					buyer_no: buyer_no,
					id: id,
					created_at: created_at,
					order_type:order_type
				},
				beforeSend: function() {

				},
				success: function(data) {
					
		$(data.order_details).each(function (key, val) {
			var svgRoot = svgDoc.documentElement;
			var zone=val.zone;
			localStorage.setItem("zone", zone);

			if ((name == "TSL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + val.orms_tpn;
			} else if ((name == "TSL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("tpnd").innerHTML = "ORMS TPN: " + val.orms_tpn;
			} else if ((name == "TSL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + val.tpnd;
			} else if ((name == "TSL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("tpnd").innerHTML = "TPND: " + val.tpnd;
			}



			if ((name == "TSL3-sticker")) {
				if (zone == "CE") {
					var barcode_change = val.ean_barcode
				} else {
					var barcode_change = val.ean_barcode
				}
				
				jsbarcodetescotslean(barcode_change, 'barcode', svgDoc)
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else {
				if (zone == "CE") {
					var barcode_change = val.itf_barcode
				} else {
					var barcode_change = val.itf_barcode
				}
				
				jsbarcodetescotsl(barcode_change, 'barcode', svgDoc)
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");
				
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			}

			if (zone == "CE") {
				var size = val.size;
			} else {
				var size = val.size;
			}

			var description = val.short_description.replace(/&/g,'&#038;');
			if ((name == "TSL3-sticker")) {
				if (size != '') {
					$.ajax({
						url: base_url + "/erp/job-manager/tesco/get-tsl-size",
						type: 'GET',
						async: false,
						data: {
							size: size,
							zone: zone
						},
						tryCount: 0,
						retryLimit: 2,
						success: function (data) {
								/** [ Dynamically change svg short description ] */
							if (description != '' && data.size_chart.length != 0) {
								var str = (description.toUpperCase()) + ' ' + data.size_chart[0].size;

								
								if (str.length > 24) {
									if (/\S/.test(str.charAt(24))) {
										//No Space
										if (str.indexOf(" ") == "-1") {
											svgDoc.getElementById("description").innerHTML = str.substr(0, 24).toUpperCase();
											svgDoc.getElementById("description1").innerHTML = str.substr(24).toUpperCase();
										} else {
											svgDoc.getElementById("description").innerHTML = str.substr(0, str.substr(0, 24).lastIndexOf(" ")).toUpperCase();
											svgDoc.getElementById("description1").innerHTML = str.substr(str.substr(0, 24).lastIndexOf(" ")).toUpperCase();
										}

									} else {
										//Space
										svgDoc.getElementById("description").innerHTML = str.substr(0, 24).toUpperCase();
										svgDoc.getElementById("description1").innerHTML = str.substr(24).toUpperCase();
									}
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
								} else {
									svgDoc.getElementById("description").innerHTML = str.toUpperCase();
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
									svgDoc.getElementById("description1").innerHTML = "";
								}
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
								svgDoc.getElementById("description1").innerHTML = '';
							} else if (description != '') {
								if (description.length > 24) {
									if (/\S/.test(description.charAt(24))) {
										//No Space
										if (description.indexOf(" ") == "-1") {
											svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
											svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
										} else {
											svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
											svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
										}

									} else {
										//Space
										svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
										svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
									}
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
								} else {
									svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
									svgDoc.getElementById("description").innerHTML = description.toUpperCase();
									svgDoc.getElementById("description1").innerHTML = "";
								}
							} else {
								svgDoc.getElementById("description").innerHTML = '';
								svgDoc.getElementById("description1").innerHTML = '';
							}
							var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);

				svgarr.push(str)
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
						},
						complete: function () {

						}
					})
				} else {
					/** [ Dynamically change svg short description ] */
					if (description != '') {
						
							if (description.length > 24) {
								if (/\S/.test(description.charAt(24))) {
									//No Space
									if (description.indexOf(" ") == "-1") {
										svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
										svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
									} else {
										svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
										svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
									}

								} else {
									//Space
									svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
									svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
								}
								svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
							} else {
								svgDoc.getElementById("description").innerHTML = description.toUpperCase();
								svgDoc.getElementById("description1").innerHTML = "";
								svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
							}
					} else {
						svgDoc.getElementById("description").innerHTML = '';
					}
					var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);

				svgarr.push(str)

				}
			} else {
				var description =val.short_description.replace(/&/g,'&#038;');
				if (description.length > 24) {
					if (/\S/.test(description.charAt(24))) {
						//No Space
						if (description.indexOf(" ") == "-1") {
							svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
							svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
						} else {
							svgDoc.getElementById("description").innerHTML = description.substr(0, description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
							svgDoc.getElementById("description1").innerHTML = description.substr(description.substr(0, 24).lastIndexOf(" ")).toUpperCase();
						}

					} else {
						//Space
						svgDoc.getElementById("description").innerHTML = description.substr(0, 24).toUpperCase();
						svgDoc.getElementById("description1").innerHTML = description.substr(24).toUpperCase();
					}
					svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 63.6)");
				} else {
					svgDoc.getElementById("description").innerHTML = description.toUpperCase();
					svgDoc.getElementById("description1").innerHTML = "";
					svgDoc.getElementById("description").setAttribute('transform', "matrix(1, 0, 0, 1, 56.5, 64.8)");
				}
				
				var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);

				svgarr.push(str)
			}
			ajax_count++;
		})
					if (ajax_count == data.order_details.length) {
						var svg_fin = svgarr.join(',_');
						var zone = localStorage.getItem("zone");
						
					 
						  PopupTescoSmallPrintManager(svg_fin, name,zone,style_no,buyer_no,id,created_at);
					  
						
					}
				},
				error: function(data) {

				},
				complete: function() {

				}
			})

		
		


	} else {
		var svgarr = [];
		var qr_code_arr = [];
		var qr_code_check_arr = [];
		var ajax_count = 0;
		var b = " ";
		var position = 3;
		var ref_name = [name2.slice(0, position), b, name2.slice(position)].join('').trim();
		
			$.ajax({
				url: base_url + "/erp/job-manager/get-order-details",
				type: "Post",
				dataType: "json",
				data: {
					_token: csrf_token,
					ref_name: ref_name,
					style_no: style_no,
					buyer_no: buyer_no,
					id: id,
					created_at: created_at,
					order_type:order_type
				},
				beforeSend: function() {

				},
				success: function(data) {
					var generate_data = data;
					$(data.order_details).each(function (key, val) {
						var zone=val.zone;
						localStorage.setItem("zone", zone);
			var svgRoot = svgDoc.documentElement;
						
		if (val.supplier_name != '') {
		    svgDoc.getElementById("supplier").innerHTML = val.supplier_name.toUpperCase().replace('&', '&amp;');
		 } else {
		 	svgDoc.getElementById("supplier").innerHTML = "";
		 }
						
		if (val.po_number != '') {
			svgDoc.getElementById("po_number").innerHTML = val.po_number.toUpperCase();
		} else {
			svgDoc.getElementById("po_number").innerHTML = '';
		}

		/** [ Dynamically change svg CE style reference number ] */
		if (val.ce_style_ref != '') {
			svgDoc.getElementById("ce_style").innerHTML = val.ce_style_ref.toUpperCase();
		} else {
			svgDoc.getElementById("ce_style").innerHTML = '';
		}
		/** [ Dynamically change svg UK style reference number ] */
		if (val.uk_style_ref != '') {
			svgDoc.getElementById("uk_style").innerHTML = val.uk_style_ref.toUpperCase();
		} else {
			svgDoc.getElementById("uk_style").innerHTML = '';
		}
		/** [ Dynamically change svg EQOS code ] */
		if (val.eqos_site_code != '') {
			svgDoc.getElementById("eqos").innerHTML = val.eqos_site_code.toUpperCase();
		} else {
			svgDoc.getElementById("eqos").innerHTML = '';
		}
		
					
						/** [ Dynamically change  svg department ] */
		if (val.department != '') {
			/** [Give font-size for department] */
			if (val.department.length < 17) {
				svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
			} else {
				svgDoc.getElementById("department").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
			}
			/** [ Dynamically change svg department] */
			svgDoc.getElementById("department").innerHTML = val.department.toUpperCase().replace('&', '&amp;');
		} else {
			svgDoc.getElementById("department").innerHTML = '';
		}
		/** [ Dynamically change svg brand ] */
		if (val.brand != '') {
			/** [Give font-size for brand] */
			if (val.brand.length < 17) {
				svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:11px;");
			} else {
				svgDoc.getElementById("brand").setAttribute("style", "font-family:'FranklinGothicBT-RomanCondensed', FrankGoth Cn BT; font-size:9px;");
			}
			svgDoc.getElementById("brand").innerHTML = val.brand.toUpperCase().replace('&', '&amp;');
		} else {
			svgDoc.getElementById("brand").innerHTML = '';
		}
						
										
		var tag_at_source = val.tag_at_source == 0 ? "no" : "yes";
		var rfid = val.rfid_compliant == 0 ? "no" : "yes";
						
		/** [ Dynamically svg RFID ] */
		if (rfid != '') {
			svgDoc.getElementById("rfid").innerHTML = rfid.toUpperCase();
		} else {
			svgDoc.getElementById("rfid").innerHTML = '';
		}
		/** [ Dynamically change svg tag at source ] */
		if (tag_at_source != '') {
			svgDoc.getElementById("tag_at_source").innerHTML = tag_at_source.toUpperCase();
		} else {
			svgDoc.getElementById("tag_at_source").innerHTML = '';
		}
		/** [ Dynamically change svg tag at source ] */
		if (val.season_code != '') {
			svgDoc.getElementById("season_code").innerHTML = val.season_code.toUpperCase();
		} else {
			svgDoc.getElementById("season_code").innerHTML = '';
		}
		/** [ Dynamically change svg manufacture date ] */
		if (val.manufacture_date != '') {
			svgDoc.getElementById("manufactured_date").innerHTML = val.manufacture_date.toUpperCase();
		} else {
			svgDoc.getElementById("manufactured_date").innerHTML = '';
		}
						
			if ((name == "TCL1-sticker" && zone == "CE") || (name == "TCL2-sticker" && zone == "CE") || (name == "TSL1-sticker" && zone == "CE") || (name == "THL1-sticker" && zone == "CE")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = val.cases_per_carton;

				svgDoc.getElementById("tpnd").innerHTML = val.orms_tpn;
				if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
				{
					svgDoc.getElementById("tpnd_last_digit").innerHTML = val.orms_tpn.substr(val.orms_tpn.length - 3);
				}
			} else if ((name == "TCL4-sticker" && zone == "CE") || (name == "THL3-sticker" && zone == "CE")) {
				svgDoc.getElementById("unit_per_case").innerHTML = val.case_size;

				svgDoc.getElementById("tpnd").innerHTML =val.orms_tpn;
				if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
				{
					svgDoc.getElementById("tpnd_last_digit").innerHTML = val.orms_tpn.substr(val.orms_tpn.length - 3);
				}
			} else if ((name == "TCL4-sticker" && zone == "UK") || (name == "THL3-sticker" && zone == "UK")) {
				svgDoc.getElementById("unit_per_case").innerHTML = val.case_size;

				svgDoc.getElementById("tpnd").innerHTML = val.tpnd;
			} else if ((name == "TCL1-sticker" && zone == "UK") || (name == "TCL2-sticker" && zone == "UK") || (name == "TSL1-sticker" && zone == "UK") || (name == "THL1-sticker" && zone == "UK")) {
				svgDoc.getElementById("pieces_in_ratio").innerHTML = val.units_per_carton;

				svgDoc.getElementById("tpnd").innerHTML = val.tpnd;
			}
			if ((name == "THL3-sticker")) {

				if (zone == "CE") {
					var barcode_change = val.ean_barcode
				} else {
					var barcode_change = val.ean_barcode
				}
					jsbarcodetescoean(barcode_change, 'barcode', svgDoc)
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;
			} else if ((name == "TCL1-sticker") || (name == "THL1-sticker")) {

				if (zone == "CE") {
					var barcode_change = val.itf_barcode
				} else {
					var barcode_change = val.itf_barcode
				}
					jsbarcodetesco(barcode_change, 'barcode', svgDoc)
				
				svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");
				svgDoc.getElementById("barcode_number").innerHTML = barcode_change;

			}

			if (zone == "CE") {
				var size = val.size;
			} else {
				var size = val.size;
			}

			var description = val.short_description.replace(/&/g,'&#038;');
			if ((name == "TCL4-sticker") || (name == "TSL3-sticker") || (name == "THL3-sticker")) {
				if (size != '') {
					$.ajax({
						url: base_url + "/erp/job-manager/tesco/get-tsl-size",
						type: 'GET',
						async: false,
						data: {
							size: size,
							zone: zone
						},
						tryCount: 0,
						retryLimit: 2,
						success: function (data) {
							/** [ Dynamically change svg short description ] */
							if (description != '' && data.size_chart.length != 0) {
								var desc_size = (description.toUpperCase()) + ' ' + data.size_chart[0].size;
								if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = oswordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
							} else if (data.size_chart.length != 0) {
								svgDoc.getElementById("description").innerHTML = data.size_chart[0].size;
							} else if (description != '') {
								var desc_size = description.toUpperCase();
								
								if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
								{
									if(desc_size.length <= 22)
									{
										svgDoc.getElementById("description").innerHTML = desc_size;
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
										}
									}
									else
									{
										str = oswordWrap(desc_size, 22);
										svgDoc.getElementById("description").innerHTML = str;
										
										if(name2 === "THL3")
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
											
										}
										else
										{
											svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
											svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
											svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
											svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
											svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 163)");
											svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
										}
									}
								}
								else
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
								}
								
							} else {
								svgDoc.getElementById("description").innerHTML = '';
							}
							var s = new XMLSerializer();
							var d = svgRoot;
							var str = s.serializeToString(d);
							svgarr.push(str)
							qr_code_arr.push(val.qr_code)
							qr_code_check_arr.push(val.buyer_po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							this.tryCount++;
							if (this.tryCount <= this.retryLimit) {
								$.ajax(this);
								return;
							}
						},
						complete: function () {

						}
					})
				} else {
					/** [ Dynamically change svg short description ] */
					if (description != '') {
							if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
							{
								var desc_size = description.toUpperCase();
								if(desc_size.length <= 22)
								{
									svgDoc.getElementById("description").innerHTML = desc_size;
									if(name2 === "THL3")
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 124)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 136)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 124)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 136)");

									}
									else
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 150)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
									}
								}
								else
								{
									str = wordWrap(desc_size, 22);
									svgDoc.getElementById("description").innerHTML = str;

									if(name2 === "THL3")
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");

									}
									else
									{
										svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
										svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
										svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
										svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
										svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.92, 0, 0, 1, 91.1544, 163)");
										svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
									}
								}
							}
							else
							{
								/** [ Dynamically change svg short description ] */
								svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							}					
						} else {
							svgDoc.getElementById("description").innerHTML = '';
						}
					var s = new XMLSerializer();
					var d = svgRoot;
					var str = s.serializeToString(d);

					svgarr.push(str)
					qr_code_arr.push(val.qr_code)
					qr_code_check_arr.push(val.buyer_po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)

				}
			} else {
				if (description != '') {
					if(generate_data.tesco_item_master[0].is_qrcode_required == 1)
					{
						if(description.length <= 20)
						{
							svgDoc.getElementById("description").innerHTML = description.toUpperCase();
							if (name2 === "TCL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 125)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 137)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 125)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 137)");
							}
							else if (name2 === "TCL2")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 138)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 160)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 138)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.87, 0, 0, 1, 91.1544, 150)");
								svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 160)");
							}
							else if(name2 === "THL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 125)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 137)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 125)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 137)");
							}
							
						}
						else
						{
							str = oswordWrap(description, 23);
							svgDoc.getElementById("description").innerHTML = str;
							if (name2 === "TCL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 133)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 146)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 133)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 88.1544, 146)");
							}
							else if (name2 === "TCL2")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 150)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 163)");
								svgDoc.getElementById("packaging_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 176)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 150)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(0.87, 0, 0, 1, 91.1544, 163)");
								svgDoc.getElementById("packaging_value").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 176)");
							}
							else if(name2 === "THL1")
							{
								svgDoc.getElementById("eqos_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 135)");
								svgDoc.getElementById("supplier_text").setAttribute('transform', "matrix(1, 0, 0, 1, 5.4283, 148)");
								svgDoc.getElementById("eqos").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 135)");
								svgDoc.getElementById("supplier").setAttribute('transform', "matrix(1, 0, 0, 1, 91.1544, 148)");
							}
						}
					}
					else
					{
						/** [ Dynamically change svg short description ] */
						svgDoc.getElementById("description").innerHTML = description.toUpperCase();
					}					
				} else {
					svgDoc.getElementById("description").innerHTML = '';
				}
				var s = new XMLSerializer();
				var d = svgRoot;
				var str = s.serializeToString(d);

				svgarr.push(str)
				qr_code_arr.push(val.qr_code)
				qr_code_check_arr.push(val.buyer_po_number+','+val.ean_barcode+','+val.itf_barcode+','+val.orms_tpn)
			}


	
			ajax_count++;
		})
			if (ajax_count == data.order_details.length) {
				var svg_fin = svgarr.join(',_');
				var qr_code_fin = qr_code_arr.join(',_');
				var qr_code_check_fin = qr_code_check_arr.join(',_');
				var zone = localStorage.getItem("zone");
				
				  PopupTescoPrintManager(svg_fin, name,zone,style_no,buyer_no,id,created_at,template_id,generate_data.tesco_item_master[0].is_qrcode_required,qr_code_fin,qr_code_check_fin);
			   
			}
				},
				error: function(data) {

				},
				complete: function() {

				}
			});
		
		

	}


}



function PopupOrchestra(data,name,id,style_no,buyer_no,created_at) {
	
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/print-pdf",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			name:name,
			id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
		},
		async: false,
		success: function (data) {
			//window.location.href=data;
			success = true;
			url = data.file_path;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}



function PopupArrOrchestra(data,name,svgDoc,id,style_no,buyer_no,created_at) {
	
//	var s = new XMLSerializer();
//	var d = data;
//	var str = s.serializeToString(d);
//	var svg = encodeURI(str)
	var success = false;
//	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/print-pdf-arr",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name,
			id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
		},
		async: false,
		success: function (data) {
			//window.location.href=data;
			success = true;
			url = data.file_path;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}



function PopupTableOrchestra(data,name,size,qty,sizebrk,id,style_no,buyer_no,created_at) {
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);d
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	// var size = [];
	// var qty = [];
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);

	// $('#solidform tbody tr').each(function () {
	// 	size.push($(this).find('.size').text());
	// 	qty.push($(this).find('.qty').text());
	// })
	// size=['3 ANS','4 ANS','5 ANS','6 ANS','8 ANS'];
	// qty=['3','4','5','6','8'];

	var sizefin = size.join(',')
	var qtyfin = qty.join(',')
	var sizebrkfin = sizebrk.join(',')

	
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/print-pdf-table",

		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			size: sizefin,
			qty: qtyfin,
			sizebrk: sizebrkfin,
			name:name,
			id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
		},
		async: false,
		success: function (data) {
			success = true;
			url = data.file_path;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}



function PopupOrchestrasmall(data,name,svgDoc,id,style_no,buyer_no,created_at) {
//	var s = new XMLSerializer();
//	var d = data;
//	var str = s.serializeToString(d);
//	var svg = encodeURI(str)
	var success = false;
//	var url = "";
	
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/print-pdf-small",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name,
			id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
		},
		async: false,
		success: function (data) {
			success = true;
			url = data.file_path;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}


function ProofPopupOrchestra(data,name,id,style_no,buyer_no,created_at) {
	
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/proof-pdf",
		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			name:name,
			id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
		},
		async: false,
		success: function (data) {
			//window.location.href=data;
			success = true;
			url = data.file_path;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}



function ProofPopupArrOrchestra(data,name,svgDoc,id,style_no,buyer_no,created_at) {
	
//	var s = new XMLSerializer();
//	var d = data;
//	var str = s.serializeToString(d);
//	var svg = encodeURI(str)
	var success = false;
//	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/proof-pdf-arr",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name,
			id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
		},
		async: false,
		success: function (data) {
			//window.location.href=data;
			success = true;
			url = data.file_path;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}



function ProofPopupTableOrchestra(data,name,size,qty,sizebrk,id,style_no,buyer_no,created_at) {
	var s = new XMLSerializer();
	var d = data;
	var str = s.serializeToString(d);d
	var svg = encodeURI(str)
	var success = false;
	var url = "";
	// var size = [];
	// var qty = [];
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);

	// $('#solidform tbody tr').each(function () {
	// 	size.push($(this).find('.size').text());
	// 	qty.push($(this).find('.qty').text());
	// })
	// size=['3 ANS','4 ANS','5 ANS','6 ANS','8 ANS'];
	// qty=['3','4','5','6','8'];

	var sizefin = size.join(',')
	var qtyfin = qty.join(',')
	var sizebrkfin = sizebrk.join(',')

	
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/proof-pdf-table",

		type: "post",
		data: {
			_token: csrf_token,
			svg: svg,
			size: sizefin,
			qty: qtyfin,
			size_break: sizebrkfin,
			name:name,
			id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
		},
		async: false,
		success: function (data) {
			success = true;
			url = data.file_path;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}



function ProofPopupOrchestrasmall(data,name,svgDoc,id,style_no,buyer_no,created_at) {
//	var s = new XMLSerializer();
//	var d = data;
//	var str = s.serializeToString(d);
//	var svg = encodeURI(str)
	var success = false;
//	var url = "";
	
	$.ajax({
		url: base_url + "/erp/job-manager/orchestra/proof-pdf-small",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name,
			id : id,
			style_no:style_no,
			buyer_no:buyer_no,
			created_at:created_at
		},
		async: false,
		success: function (data) {
			success = true;
			url = data.file_path;
		},
		error: function (data) {

		},
	})
	if (success) { //AND THIS CHANGED
		window.open(url)
	}
}


/**
 * This Function is Sub Function of #printDivTesco.
 *  For print preview of stickers
 * @return Pdf
 */

function PopupTescoPrintManager(data, name,zone,style_no,buyer_no,id,created_at,template_id,qr_flag,qr_code_fin,qr_code_check_fin) {

	//	var s = new XMLSerializer();
	//	var d = data;
	//	var str = s.serializeToString(d);
	//	var svg = encodeURI(str)
	//var success = false;
	
	if(name == "TCL1-sticker")
	{
		var db = "tesco_tcl1_orders";
	}
	else if(name == "TCL2-sticker")
	{
		var db = "tesco_tcl2_orders";
	}
	else if(name == "TCL4-sticker")
	{
		var db = "tesco_tcl4_orders";
	}
	else if(name == "THL1-sticker")
	{
		var db = "tesco_thl1_orders";
	}
	else if(name == "THL3-sticker")
	{
		var db = "tesco_thl3_orders";
	}
	var url = "";
	// window.open(base_url + "/admin/order-management/print-pdf?svg="+svg);
	
	$.ajax({
		url: base_url + "/erp/job-manager/tesco/print-div-tesco",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name:name,
			zone:zone,
			style:style_no,
			qr_code_fin: qr_code_fin,
			qr_code_check_fin:qr_code_check_fin,
			tesco_po_number:buyer_no,
			id:id,
			template_id:template_id,
			created_at:created_at,
			qr_flag:qr_flag,
			db:db
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {
			//window.location.href=data;
			//			success = true;
			//			url = data;

			var element = document.createElement('a');
			element.setAttribute('href', data.url);
			element.setAttribute('download', name);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}
/**
 * This Function is Sub Function of #printDivTesco.
 *  For print preview of small stickers
 * @return Pdf
 */

function PopupTescoSmallPrintManager(data, name,zone,style_no,buyer_no,id,created_at) {
	
		
	if(name == "TSL1-sticker")
	{
		var db = "tesco_tsl1_orders";
	}
	else if(name == "TSL3-sticker")
	{
		var db = "tesco_tsl3_orders";
	}
	
	var url = "";
	
	$.ajax({
		url: base_url + "/erp/job-manager/tesco/print-div-tesco-small",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name:name,
			zone:zone,
			style:style_no,
			tesco_po_number:buyer_no,
			id:id,
			created_at:created_at,
			db:db
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {

			var element = document.createElement('a');
			element.setAttribute('href', data.url);
			element.setAttribute('download', name);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}


/**
 * This Function is Sub Function of #printDivTesco.
 *  For print preview of stickers
 * @return Pdf
 */

function PopupTescoGeneratePrintManager(data, name,svgDoc,template_id,qr_flag,qr_code_fin,qr_code_check_fin) {
	
	var url = "";
	
	$.ajax({
		url: base_url + "/erp/job-manager/tesco/generate-div-tesco",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			qr_code_fin: qr_code_fin,
			qr_code_check_fin:qr_code_check_fin,
			qr_flag:qr_flag,
			template_id: template_id,
			name: name
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {

			var element = document.createElement('a');
			element.setAttribute('href', data);
			element.setAttribute('download', name);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);
			
			//svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}
/**
 * This Function is Sub Function of #printDivTesco.
 *  For print preview of small stickers
 * @return Pdf
 */

function PopupTescoGenerateSmallPrintManager(data, name,svgDoc) {
	

	var url = "";
	
	$.ajax({
		url: base_url + "/erp/job-manager/tesco/generate-div-tesco-small",
		type: "post",
		data: {
			_token: csrf_token,
			svg: data,
			name: name
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {

			var element = document.createElement('a');
			element.setAttribute('href', data);
			element.setAttribute('download', name);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);
			
			//svgDoc.getElementById("barcode").setAttribute("style", "visibility:none");

		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}


/**
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode
 */
function jsbarcodetesco(code, id, svgDoc) {

	JsBarcode("#ean-13", code, {
		format: "ITF14",
		width: 3.28148,
		height: 202,
		fontSize: 30,
		flat: true,
		displayValue: false
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
	svg.setAttribute('font-size', '30px');
	svg.setAttribute('font-family', 'frankgothcnbt');

	svg.setAttribute('width', '408.900px');
	svg.setAttribute('height', '328.02px');
	

	
	//svg.removeAttribute('version');
	//svg.removeAttribute('viewBox');
//	svg.removeAttribute('x');
//	svg.removeAttribute('y');
	//svg.setAttribute('viewBox','0 ,0 ,463 ,222');
	
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

	// img.src - contains base64 URI from svg
	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);



	//remove temporary canvas
	canvasTmp.remove();
	svgDoc.getElementById(id).innerHTML = svg_xml;
}






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
 * This Function is used to invoke Ean 13 JSBarcode.
 * 
 * @return barcode
 */
function jsbarcodetescoean(code, id, svgDoc) {
	//		format: "ean13",
	//		width: 3.45,
	//		height: 125,
	//		fontSize: 35,
	//		flat: true,
	//		displayValue: false
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 3.9260,
		height: 170,
		fontSize: 30,
		flat: true,
		displayValue: false
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
	svg.setAttribute('font-size', '50px');
	svg.setAttribute('font-family', 'frankgothcnbt');

	svg.setAttribute('width', '412.25px');
	svg.setAttribute('height', '378.02px');
	
//	svg.removeAttribute('version');
//	svg.removeAttribute('viewBox');
//	svg.removeAttribute('x');
//	svg.removeAttribute('y');
	//svg.setAttribute('viewBox','0, 0, 393 ,190');

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

	// img.src - contains base64 URI from svg
	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);


	//remove temporary canvas
	canvasTmp.remove();

	svgDoc.getElementById(id).innerHTML = svg_xml;
}
/**
 * This Function is used to invoke Ean 13 JSBarcode.
 * 
 * @return barcode
 */
function jsbarcodetescotslean(code, id, svgDoc) {
	//		format: "ean13",
	//		width: 3.45,
	//		height: 125,
	//		fontSize: 35,
	//		flat: true,
	//		displayValue: false
	JsBarcode("#ean-13", code, {
		format: "ean13",
		width: 3.9260,
		height: 167,
		fontSize: 30,
		flat: true,
		displayValue: false
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
	svg.setAttribute('font-size', '50px');
	svg.setAttribute('font-family', 'frankgothcnbt');

	svg.setAttribute('width', '419.075px');
	svg.setAttribute('height', '275.02px');

	
	
//	svg.removeAttribute('version');
//	svg.removeAttribute('viewBox');
//	svg.removeAttribute('x');
//	svg.removeAttribute('y');
	//svg.setAttribute('viewBox','0 ,0 ,393 ,187');
	
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

	// img.src - contains base64 URI from svg
	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);


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
 * This Function is used to invoke JSBarcode.
 * 
 * @return barcode 
 */


function jsbarcodetescotsl(code, id, svgDoc) {
	JsBarcode("#ean-13", code, {
		format: "ITF14",
		width: 3.28148,
		height: 199,
		fontSize: 30,
		flat: true,
		displayValue: false
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
	svg.setAttribute('font-size', '30px');
	svg.setAttribute('font-family', 'frankgothcnbt');

	svg.setAttribute('width', '415.100px');
	svg.setAttribute('height', '328.02px');
	
//	svg.removeAttribute('version');
//	svg.removeAttribute('viewBox');
//	svg.removeAttribute('x');
//	svg.removeAttribute('y');
	//svg.setAttribute('viewBox','0 0 231 109');
	
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

	// img.src - contains base64 URI from svg
	// https://developer.mozilla.org/en/DOM/window.btoa
	img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

	//remove temporary canvas
	canvasTmp.remove();

	svgDoc.getElementById(id).innerHTML = svg_xml;
}


function oswordWrap(str, maxWidth) {
    var newLineStr = ""; done = false; res = '';
    do {                    
        found = false;
        // Inserts new line at first whitespace of the line
        for (i = maxWidth - 1; i >= 0; i--) {
            if (testWhite(str.charAt(i))) {
                res = res + "<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;'  x='0' y='0'>" +[str.slice(0, i), newLineStr].join('') + "</tspan>";
                str = str.slice(i + 1);
                found = true;
                break;
            }
        }
        // Inserts new line at maxWidth position, the word is too long to wrap
        if (!found) {
            res +="<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;'>" + [str.slice(0, maxWidth), newLineStr].join('')+ "</tspan>";
            str = str.slice(maxWidth);
        }
        if (str.length < maxWidth)
            done = true;
    } while (!done);
    return res + "<tspan style='fill:cmyk(0%, 0%, 0%, 100%); font-family:FranklinGothicBT-RomanCondensed, FrankGoth Cn BT; font-size:10.0001px;' x='0' y='12'>"+str+"</tspan>";
}

function genarateArtworkLabelManager(data, name,style_no,buyer_no,id,order_type,created_at,status,buyer,template_id,data_ba,data_bate,data_fr,print_type)
{
	var a = document.getElementById(data);
	var svgDoc = a.contentDocument;
	
	var a_back = document.getElementById(data_ba);
    var svgDoc_back = a_back.contentDocument;

    var a_back_text = document.getElementById(data_bate);
    var svgDoc_back_text = a_back_text.contentDocument;

    var a_front_text = document.getElementById(data_fr);
    var svgDoc_front_text = a_front_text.contentDocument;
    
            var svgarr = [];
            var svgarr_back = [];
            var svgarr_back_text = [];
            var svgarr_front = [];
            ajax_count=0;
            $.ajax({
				url: base_url + "/erp/job-manager/get-order-details",
                type: "Post",
                dataType: "json",
                data: {
                    _token: csrf_token,
                    name: name,
                    id: id,
					table_name_print:"tesco_washcare_orders",
					ref_name: name,
					style_no: style_no,
					buyer_no: buyer_no,
					created_at: created_at,
					order_type:order_type
                },
				beforeSend: function() {

				},
				success: function(data) {
                    
                    var svgRoot_back =svgDoc_back.documentElement;
                    var svgRoot_back_text =svgDoc_back_text.documentElement;
                    var svgRoot_front_text =svgDoc_front_text.documentElement;

                    if(data.order_details[0].bs_en_declaration == 0)
                    {
                        console.log("hai")
                        svgDoc_front_text.getElementById("sleeping_bag_name").setAttribute('style', "visibility:hidden");
                    }

                    if(data.order_details[0].baby_sleeping_bag_symbol == 0)
                    {
                        svgDoc_front_text.getElementById("tog_image").setAttribute('style', "visibility:hidden");
                    }

                    if(data.order_details[0].tog == "" || data.order_details[0].tog == null)
                    {
                        svgDoc_front_text.getElementById("tog_name").setAttribute('style', "visibility:hidden");
                    }

                    $(data.order_details).each(function (key, val) {
                        svgDoc.getElementById("po_value").innerHTML = "PO:"+val.po_number;
                        svgDoc.getElementById("sn_value").innerHTML = "SN:"+val.eqos_site_code;
                        svgDoc.getElementById("style_value").innerHTML = "STYLE:"+ val.uk_style_ref;
                        if(val.fabric_batch_code != null && val.fabric_batch_code != "")
                        {
                            svgDoc.getElementById("batch_code").innerHTML = val.fabric_batch_code;
                        }
                        else
                        {
                            //alert()
                            svgDoc.getElementById("po_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 103)");
                            svgDoc.getElementById("sn_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 110)");
                            svgDoc.getElementById("style_value").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 35, 117)");
                            svgDoc.getElementById("batch_code").innerHTML = "";
                        }

                        if(val.internal_reference != "" && val.internal_reference != null)
                        {
                            svgDoc.getElementById("packaging_reference_number").innerHTML = val.internal_reference;
                        }
                        else
                        {
                            svgDoc.getElementById("packaging_reference_number").innerHTML = "";
                            svgDoc.getElementById("packaging_reference_number_1_").setAttribute('transform', "matrix(1.0039, 0, 0, 1, 6.7049, 160.7462)");
                            
                        }
                        
                        if(val.tog != "" && val.tog != null)
                        {
                            svgDoc.getElementById("tog_value_1_").innerHTML = val.tog;
                        }
                        else
                        {
                            //console.log("hai")
                            svgDoc.getElementById("tog_value_1_").innerHTML = "";
                        }


                        var selected_secondary_sizes = JSON.parse(val.selected_secondary_sizes)
                        var selected_secondary_sizes_text = "";
                        console.log(selected_secondary_sizes)
                        $size_y=0;
                        $(selected_secondary_sizes).each(function(index,selected_secondary_sizes_val){
                            if(selected_secondary_sizes_val['id'] != "" && selected_secondary_sizes_val['id'] != null)
                            {
                                selected_secondary_sizes_text += '<tspan x="10" y="'+$size_y+'">'+selected_secondary_sizes_val['value']+'</tspan>'
                                $size_y += 6;
                            }
                        })
                        svgDoc.getElementById("height_text").innerHTML = selected_secondary_sizes_text;

                        


                        svgDoc.getElementById("uk_ean_barcode").innerHTML = val.uk_ean;
                        svgDoc.getElementById("ce_ean_barcode").innerHTML = val.ce_ean;
                        
                        if(val.uk_size == "tiny baby")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");
                            

                            svgDoc.getElementById("small_english").innerHTML = "tiny baby";

                            var polish = data.tesco_tiny[0].polish
                            var czech = data.tesco_tiny[0].czech
                            var slovak = data.tesco_tiny[0].slovak
                            var hungarian = data.tesco_tiny[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish+" • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "Newborn")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");


                            svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").innerHTML = "Newborn";

                            var polish = data.tesco_nb[0].polish
                            var czech = data.tesco_nb[0].czech
                            var slovak = data.tesco_nb[0].slovak
                            var hungarian = data.tesco_nb[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish+" • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "up to 1M")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").innerHTML = "up to 1M";
                            
                            var polish = data.tesco_up1[0].polish
                            var czech = data.tesco_up1[0].czech
                            var slovak = data.tesco_up1[0].slovak
                            var hungarian = data.tesco_up1[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish+" • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "up to 3M")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").innerHTML = "up to 3M";
                            
                            var polish = data.tesco_up3[0].polish
                            var czech = data.tesco_up3[0].czech
                            var slovak = data.tesco_up3[0].slovak
                            var hungarian = data.tesco_up3[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish+" • "+czech;
                            svgDoc.getElementById("small_translation2").innerHTML =slovak+ " • "+hungarian;
                        }
                        else if(val.uk_size == "One size")
                        {
                            svgDoc.getElementById("uk_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").setAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").removeAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").innerHTML = "One size";
                            
                            var polish = data.tesco_onesize[0].polish
                            var czech = data.tesco_onesize[0].czech.split(' ')
                            var slovak = data.tesco_onesize[0].slovak
                            var hungarian = data.tesco_onesize[0].hungarian
                            svgDoc.getElementById("small_translation1").innerHTML = polish+" • "+czech[0];
                            svgDoc.getElementById("small_translation2").innerHTML =czech[1]+ " • "+slovak+ " • "+hungarian;
                        }
                        else
                        {
                            svgDoc.getElementById("uk_size").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("ce_size").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk").removeAttribute("visibility", "hidden");
                            svgDoc.getElementById("eur").removeAttribute("visibility", "hidden");

                            svgDoc.getElementById("small_english").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation1").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("small_translation2").setAttribute("visibility", "hidden");
                            svgDoc.getElementById("uk_size").innerHTML = val.uk_size;
                            svgDoc.getElementById("ce_size").innerHTML = val.ce_size;
                        }
                        svgDoc.getElementById("ce_style_number").innerHTML = val.ce_style_ref;
                        var country_of_origin_text = JSON.parse(val.country_of_origin_text)
                        svgDoc.getElementById("made_in_english").innerHTML = country_of_origin_text[0].english;
                        var svgRoot = svgDoc.documentElement;
                        var s = new XMLSerializer();
                        var d = svgRoot;
                        var str = s.serializeToString(d);
                        var svg = encodeURI(str)
                        svgarr.push(svg)
                        ajax_count++;
                    })

                    var country_of_origin_text = JSON.parse(data.order_details[0].country_of_origin_text)
                    svgDoc_back_text.getElementById("wyprod_text").innerHTML = '<tspan  x="2" y="0">'+country_of_origin_text[0].polish+'</tspan><tspan   x="2.53" y="5.94">'+country_of_origin_text[0].czech+'</tspan><tspan   x="2.53" y="12.88">'+country_of_origin_text[0].slovak+'</tspan><tspan  x="2" y="18.82">'+country_of_origin_text[0].hungarian+'</tspan>';

                    // svgDoc_back.getElementById("wash_iso").setAttribute('xlink:href',data.order_details[0].wash_iso);
                    // svgDoc_back.getElementById("clean_iso").setAttribute('xlink:href',data.order_details[0].clean_iso);
                    // svgDoc_back.getElementById("whit_iso").setAttribute('xlink:href',data.order_details[0].whit_iso);
                    // svgDoc_back.getElementById("iron_iso").setAttribute('xlink:href',data.order_details[0].iron_iso);
                    // svgDoc_back.getElementById("dry_iso").setAttribute('xlink:href',data.order_details[0].dry_iso);
                    // svgDoc_back.getElementById("wash_astm").setAttribute('xlink:href',data.order_details[0].wash_astm);
                    // svgDoc_back.getElementById("dry_astm").setAttribute('xlink:href',data.order_details[0].dry_astm);
                    // svgDoc_back.getElementById("whit_astm").setAttribute('xlink:href',data.order_details[0].whit_astm);
                    // svgDoc_back.getElementById("iron_astm").setAttribute('xlink:href',data.order_details[0].iron_astm);
                    // svgDoc_back.getElementById("clean_astm").setAttribute('xlink:href',data.order_details[0].clean_astm);

                    var additional_care_text_1 = JSON.parse(data.order_details[0].additional_care_text_1)
                    var additional_care_text_2 = JSON.parse(data.order_details[0].additional_care_text_2)
                    var additional_care_text_3 = JSON.parse(data.order_details[0].additional_care_text_3)
                    var degree =data.order_details[0].degree

                    var en_care_instructions = "";
                    if(additional_care_text_1 != "" && additional_care_text_1 != null)
                    {
                        en_care_instructions += additional_care_text_1[0].english
                    }
                    if(additional_care_text_2 != "" && additional_care_text_2 != null)
                    {
                        en_care_instructions += " / " + additional_care_text_2[0].english
                    }
                    if(additional_care_text_3 != "" && additional_care_text_3 != null)
                    {
                        en_care_instructions += " / " + additional_care_text_3[0].english
                    }
                    
                    if(degree >= 30)
                    {
                        en_care_instructions += " / " + data.degree_text[0].english
                    }
                    
                    $chunk_str = chunkSubstr(en_care_instructions, 28).split(/\r?\n/);

                    //console.log($chunk_str);
                    var chunks='<tspan  x="0" y="0">EN</tspan>';
                    $y=0;
                    $ind=1;
                    console.log("gy:"+$chunk_str.length)
                    $($chunk_str).each(function(index,chunk_val){
                        chunks += '<tspan  x="12" y="'+$y+'">'+chunk_val+'</tspan>';
                        if($chunk_str.length == $ind)
                        {
                            $y+=6;
                        }
                        else
                        {
                            $y+=6;
                        }
                        $ind++;
                        
                    })

                    var en_garment_component = "";
                    var pl_garment_component = "";
                    var cz_garment_component = "";
                    var sk_garment_component = "";
                    var hu_garment_component = "";
                    $(data.component).each(function(index,comp_val){
                        
                        var garment_component_text = JSON.parse(comp_val.garment_component_text)
                        if(index != 0)
                        {
                            en_garment_component += " / " +garment_component_text[0].english;
                            pl_garment_component += " / " +garment_component_text[0].polish;
                            cz_garment_component += " / " +garment_component_text[0].czech;
                            sk_garment_component += " / " +garment_component_text[0].slovak;
                            hu_garment_component += " / " +garment_component_text[0].hungarian;
                        }
                        else
                        {
                            en_garment_component += garment_component_text[0].english;
                            pl_garment_component += garment_component_text[0].polish;
                            cz_garment_component += garment_component_text[0].czech;
                            sk_garment_component += garment_component_text[0].slovak;
                            hu_garment_component += garment_component_text[0].hungarian;
                        }
                        
                        $(comp_val.fibre).each(function(index,fibre_val){
                            var fibre_text = JSON.parse(fibre_val.fibre_composition_text)
                            en_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].english;

                            pl_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].polish;

                            cz_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].czech;

                            sk_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].slovak;

                            hu_garment_component += " / " + fibre_val.fibre_composition_percentage+ "% " + fibre_text[0].hungarian;
                        });
                    })

                    var exclusive_trims_text = JSON.parse(data.order_details[0].exclusive_trims_text)
                    var contains_non_textile_part_text = JSON.parse(data.order_details[0].contains_non_textile_part_text)
                    var kids_warning_text = JSON.parse(data.order_details[0].kids_warning_text)
                    var part_of_set_text = JSON.parse(data.order_details[0].part_of_set_text)
                    

                    if(exclusive_trims_text != "" && exclusive_trims_text != null)
                    {
                        en_garment_component += " / " + exclusive_trims_text[0].english
                        pl_garment_component += " / " + exclusive_trims_text[0].polish
                        cz_garment_component += " / " + exclusive_trims_text[0].czech
                        sk_garment_component += " / " + exclusive_trims_text[0].slovak
                        hu_garment_component += " / " + exclusive_trims_text[0].hungarian
                    }

                    if(contains_non_textile_part_text != "" && contains_non_textile_part_text != null)
                    {
                        en_garment_component += " / " + contains_non_textile_part_text[0].english
                        pl_garment_component += " / " + contains_non_textile_part_text[0].polish
                        cz_garment_component += " / " + contains_non_textile_part_text[0].czech
                        sk_garment_component += " / " + contains_non_textile_part_text[0].slovak
                        hu_garment_component += " / " + contains_non_textile_part_text[0].hungarian
                    }

                    if(kids_warning_text != "" && kids_warning_text != null)
                    {
                        en_garment_component += " / " + kids_warning_text[0].english
                        pl_garment_component += " / " + kids_warning_text[0].polish
                        cz_garment_component += " / " + kids_warning_text[0].czech
                        sk_garment_component += " / " + kids_warning_text[0].slovak
                        hu_garment_component += " / " + kids_warning_text[0].hungarian
                    }

                    if(additional_care_text_1 != "" && additional_care_text_1 != null)
                    {
                        console.log("Polish : "+additional_care_text_1[0].polish)
                        pl_garment_component += " / " + additional_care_text_1[0].polish
                        cz_garment_component += " / " + additional_care_text_1[0].czech
                        sk_garment_component += " / " + additional_care_text_1[0].slovak
                        hu_garment_component += " / " + additional_care_text_1[0].hungarian
                    }
                    if(additional_care_text_2 != "" && additional_care_text_2 != null)
                    {
                        pl_garment_component += " / " + additional_care_text_2[0].polish
                        cz_garment_component += " / " + additional_care_text_2[0].czech
                        sk_garment_component += " / " + additional_care_text_2[0].slovak
                        hu_garment_component += " / " + additional_care_text_2[0].hungarian
                    }
                    if(additional_care_text_3 != "" && additional_care_text_3 != null)
                    {
                        pl_garment_component += " / " + additional_care_text_3[0].polish
                        cz_garment_component += " / " + additional_care_text_3[0].czech
                        sk_garment_component += " / " + additional_care_text_3[0].slovak
                        hu_garment_component += " / " + additional_care_text_3[0].hungarian
                    }

                    if(part_of_set_text != "" && part_of_set_text != null)
                    {
                        en_garment_component += " / " + part_of_set_text[0].english
                        pl_garment_component += " / " + part_of_set_text[0].polish
                        cz_garment_component += " / " + part_of_set_text[0].czech
                        sk_garment_component += " / " + part_of_set_text[0].slovak
                        hu_garment_component += " / " + part_of_set_text[0].hungarian
                    }

                    if(degree >= 30)
                    {
                        pl_garment_component += " / " + data.degree_text[0].polish
                        cz_garment_component += " / " + data.degree_text[0].czech
                        sk_garment_component += " / " + data.degree_text[0].slovak
                        hu_garment_component += " / " + data.degree_text[0].hungarian
                    }

                    $garment_str = chunkSubstr(en_garment_component, 28).split(/\r?\n/);
                    $pl_garment_str = chunkSubstr(pl_garment_component, 28).split(/\r?\n/);
                    $cz_garment_str = chunkSubstr(cz_garment_component, 28).split(/\r?\n/);
                    $sk_garment_str = chunkSubstr(sk_garment_component, 28).split(/\r?\n/);
                    $hu_garment_str = chunkSubstr(hu_garment_component, 28).split(/\r?\n/);

                    chunks += '<tspan x="0" y="'+$y+'">EN</tspan>';

                    $ind_en=1;
                    $z=0;
                    $($garment_str).each(function(index,garment_val){
                        chunks += '<tspan x="12" y="'+$y+'">'+garment_val+'</tspan>';
                        if($garment_str.length == $ind_en)
                        {
                            $y+=6;
                        }
                        else
                        {
                            $y+=6;
                        }
                        $ind_en++;
                    })

                    chunks += '<tspan x="0" y="'+$y+'">PL</tspan>';
                    var new_chunks = '';
                    $($pl_garment_str).each(function(index,pl_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            new_chunks += '<tspan x="12" y="'+$z+'">'+pl_garment_val+'</tspan>';
                        }
                        else
                        {
                            chunks += '<tspan x="12" y="'+$y+'">'+pl_garment_val+'</tspan>';   
                        }
                        
                        $y+=6;
                        $z+=6;
                    })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">CZ</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">CZ</tspan>';
                    }

                    $($cz_garment_str).each(function(index,cz_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            new_chunks += '<tspan x="12" y="'+$z+'">'+cz_garment_val+'</tspan>';
                        }
                        else
                        {
                            chunks += '<tspan x="12" y="'+$y+'">'+cz_garment_val+'</tspan>';   
                        }
                        
                        $y+=6;
                        $z+=6;
                    })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">SK</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">SK</tspan>';
                    }

                    $($sk_garment_str).each(function(index,sk_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            new_chunks += '<tspan x="12" y="'+$z+'">'+sk_garment_val+'</tspan>';
                        }
                        else
                        {
                            chunks += '<tspan x="12" y="'+$y+'">'+sk_garment_val+'</tspan>';   
                        }
                        
                        $y+=6;
                        $z+=6;
                    })

                    if($y > 96)
                    {
                        new_chunks += '<tspan x="0" y="'+$z+'">HU</tspan>';
                    }
                    else
                    {
                        chunks += '<tspan x="0" y="'+$y+'">HU</tspan>';
                    }

                    $($hu_garment_str).each(function(index,hu_garment_val){
                        if($y == 96)
                        {
                            $z=0;
                        }

                        if($y > 96)
                        {
                            new_chunks += '<tspan x="12" y="'+$z+'">'+hu_garment_val+'</tspan>';
                        }
                        else
                        {
                            chunks += '<tspan x="12" y="'+$y+'">'+hu_garment_val+'</tspan>';   
                        }
                        
                        $y+=6;
                        $z+=6;
                    })
                    
                    svgDoc_back_text.getElementById("wash_text").innerHTML = chunks;
                    svgDoc_back_text.getElementById("wash_text_2").innerHTML = new_chunks;


                    
                    // console.log(en_care_instructions)
                    // console.log(en_care_instructions)

                    var s_front = new XMLSerializer();
                    var d_front = svgRoot_front_text;
                    var str_front = s_front.serializeToString(d_front);
                    var svg_front = encodeURI(str_front)
                    svgarr_front.push(svg_front)
                    
                    var s_back = new XMLSerializer();
                    var d_back = svgRoot_back;
                    var str_back = s_back.serializeToString(d_back);
                    var svg_back = encodeURI(str_back)
                    svgarr_back.push(svg_back)

                    var s_back_text = new XMLSerializer();
                    var d_back_text = svgRoot_back_text;
                    var str_back_text = s_back_text.serializeToString(d_back_text);
                    var svg_back_text = encodeURI(str_back_text)
                    svgarr_back_text.push(svg_back_text)
                   
                    if (ajax_count == data.order_details.length) {
                        var svg_fin = svgarr.join(',_');
                        var svg_fin_back = svgarr_back.join(',_');
                        var svg_fin_back_text = svgarr_back_text.join(',_');
						var svg_fin_front = svgarr_front.join(',_');
						if(print_type == "print")
						{
							PopupTescoLabelsGeneratePrintManager(svg_fin,svg_fin_back,svg_fin_back_text,svg_fin_front,data.order_details[0].po_number,data.order_details[0].uk_style_ref+"/"+data.order_details[0].ce_style_ref,data.tesco_item_master[0].item_name,data.order_details[0].created_at,data.order_details[0].customer_name,data.order_details[0].wash_astm,data.order_details[0].wash_iso,data.order_details[0].whit_astm,data.order_details[0].whit_iso,data.order_details[0].dry_astm,data.order_details[0].dry_iso,data.order_details[0].iron_astm,data.order_details[0].iron_iso,data.order_details[0].clean_astm,data.order_details[0].clean_iso);
						}
						else
						{
							PopupTescoLabelsGenerateProofManager(svg_fin,svg_fin_back,svg_fin_back_text,svg_fin_front,data.order_details[0].po_number,data.order_details[0].uk_style_ref+"/"+data.order_details[0].ce_style_ref,data.tesco_item_master[0].item_name,data.order_details[0].created_at,data.order_details[0].customer_name,data.order_details[0].wash_astm,data.order_details[0].wash_iso,data.order_details[0].whit_astm,data.order_details[0].whit_iso,data.order_details[0].dry_astm,data.order_details[0].dry_iso,data.order_details[0].iron_astm,data.order_details[0].iron_iso,data.order_details[0].clean_astm,data.order_details[0].clean_iso);
						}
                    }
				
				},
				error: function(data) {

				},
				complete: function() {

				}
			});
}



function chunkSubstr(str, maxWidth) {
    var newLineStr = "\n"; done = false; res = '';
    while (str.length > maxWidth) {                 
        found = false;
        // Inserts new line at first whitespace of the line
        for (i = maxWidth - 1; i >= 0; i--) {
            if (testWhite(str.charAt(i))) {
                res = res + [str.slice(0, i), newLineStr].join('');
                str = str.slice(i + 1);
                found = true;
                break;
            }
        }
        // Inserts new line at maxWidth position, the word is too long to wrap
        if (!found) {
            res += [str.slice(0, maxWidth), newLineStr].join('');
            str = str.slice(maxWidth);
        }

    }

    return res + str;
}

function testWhite(x) {
    var white = new RegExp(/^\s$/);
    return white.test(x.charAt(0));
}


function PopupTescoLabelsGenerateProofManager(data_front,data_back,data_back_text,data_front_image,po_number,style,template_name,created_at,customer_name,wash_astm,wash_iso,whit_astm,whit_iso,dry_astm,dry_iso,iron_astm,iron_iso,clean_astm,clean_iso) {
	
	var url = "";
	
	$.ajax({
		url: base_url + "/erp/job-manager/tesco/proof-div-tescolabelos",
		type: "post",
		data: {
			_token: csrf_token,
			data_front: data_front,
            data_back: data_back,
            data_back_text: data_back_text,
            data_front_image: data_front_image,
            tesco_po_number:po_number,
            style:style,
            template_name:template_name,
            created_date:created_at,
            customer_name:customer_name,
            wash_astm:wash_astm,
            wash_iso:wash_iso,
            whit_astm:whit_astm,
            whit_iso:whit_iso,
            dry_astm:dry_astm,
            dry_iso:dry_iso,
            iron_astm:iron_astm,
            iron_iso:iron_iso,
            clean_astm:clean_astm,
            clean_iso:clean_iso
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {

			var element = document.createElement('a');
			element.setAttribute('href', data.file_path);
			element.setAttribute('download', name);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);
		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}

function PopupTescoLabelsGeneratePrintManager(data_front,data_back,data_back_text,data_front_image,po_number,style,template_name,created_at,customer_name,wash_astm,wash_iso,whit_astm,whit_iso,dry_astm,dry_iso,iron_astm,iron_iso,clean_astm,clean_iso) {
	
	var url = "";
	
	$.ajax({
		url: base_url + "/erp/job-manager/tesco/print-div-tescolabelos",
		type: "post",
		data: {
			_token: csrf_token,
			data_front: data_front,
            data_back: data_back,
            data_back_text: data_back_text,
            data_front_image: data_front_image,
            tesco_po_number:po_number,
            style:style,
            template_name:template_name,
            created_date:created_at,
            customer_name:customer_name,
            wash_astm:wash_astm,
            wash_iso:wash_iso,
            whit_astm:whit_astm,
            whit_iso:whit_iso,
            dry_astm:dry_astm,
            dry_iso:dry_iso,
            iron_astm:iron_astm,
            iron_iso:iron_iso,
            clean_astm:clean_astm,
            clean_iso:clean_iso
		},
		tryCount: 0,
		retryLimit: 2,
		async: false,
		success: function (data) {

			var element = document.createElement('a');
			element.setAttribute('href', data.file_path);
			element.setAttribute('download', name);

			element.style.display = 'none';
			document.body.appendChild(element);

			element.click();

			document.body.removeChild(element);
		},
		error: function (errorXHR, errorStatus, errorThrown) {
			this.tryCount++;
			if (this.tryCount <= this.retryLimit) {
				$.ajax(this);
				return;
			}
		},
	})
}