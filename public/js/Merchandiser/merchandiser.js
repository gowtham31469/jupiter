/**
 * Merchandiser page script
 */
/**
 * Function to show wcl panel
 * @author <praveen>
 */
var add_page_composition=0;
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
				 $('.sample-artwork').html('<object id="washcare_svg" style=width:100%" data="' + data.template + '" type="image/svg+xml"></object>');
				 var mySVG = document.getElementById("washcare_svg");
				 var svgDoc;
			     mySVG.addEventListener("load", function () {
					 svgDoc = mySVG.contentDocument;
					 
				$.each(data.care_instruction, function (index, value) {
					var value = '<p class="text_pragraph">' + value.french + "-" +  value.english  + "-" +  value.spanish + "-" + value.portuguese + "-" + value.italian + "-" + value.dutch + "-" + value.german + "-" +
						value.russian + "-" +
						value.chinese + "-" +
						value.arabic + "-" +
						value.greek + "-" +
						value.turkish + '</p>';
					svgDoc.getElementById("text_care_instruction").innerHTML += value;
				});
					 
				
				var text_for_mad_in = '<p>' + data.made_in[0].french + " - " + data.made_in[0].english  + " - " + data.made_in[0].spanish + " - " + data.made_in[0].portuguese + " - " + data.made_in[0].italian + " - " + data.made_in[0].dutch + " - " + data.made_in[0].german + " - " +
					data.made_in[0].russian + " - " +
					data.made_in[0].chinese + " - " +
					data.made_in[0].arabic + " - " +
					data.made_in[0].greek + " - " +
					data.made_in[0].turkish+ '</p>';
				svgDoc.getElementById("country").innerHTML = text_for_mad_in;
					 
					var page_id = 0;
				$.each(data.orchestra_wash_care_label_orders[0].composition, function (index, value) {
					if (value.page_id != page_id) {
						add_page_composition += 1;
						
						if (value.parts_english != null) {
							svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + add_page_composition + '" id="composition_' + add_page_composition + '">' + value.parts_french + "-" + value.parts_english + "-" + value.parts_spanish + "-" + value.parts_portuguese + "-" + value.parts_italian + "-" + value.parts_dutch + "-" + value.parts_german + "-" + value.parts_russian + "-" + value.parts_chinese + "-" + value.parts_arabic + "-" + value.parts_greek + "-" + value.parts_turkish + '</p>';
						}

					}

					svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + add_page_composition + '" id="composition_' + add_page_composition + '">' + value.percentage + "% " + value.french + "-" + value.english + "-" + value.spanish + "-" + value.portuguese + "-" + value.italian + "-" + value.dutch + "-" + value.german + "-" + value.russian + "-" + value.chinese + "-" + value.arabic + "-" + value.greek + "-" + value.turkish + '</p>';

					page_id = value.page_id;

				});

					var size = data.order_details[0].Size.split(" ")
					if (size[1] == "ANS") {
						var size1 = "years";
					} else {
						var size1 = "months";
					}
					svgDoc.getElementById("parcel_code1").innerHTML = data.order_details[0].UVC_TCO;
					svgDoc.getElementById("product_family").innerHTML = data.product_family[0].arabic;
					svgDoc.getElementById("parcel_code2").innerHTML = data.order_details[0].UVC_TCO;
					svgDoc.getElementById("size_in_fr_en1").innerHTML = data.order_details[0].cm + "cm / " + data.order_details[0].in + "in";
					svgDoc.getElementById("size_in_fr_en2").innerHTML = data.order_details[0].cm + "cm / " + data.order_details[0].in + "in";
					svgDoc.getElementById("size1").innerHTML = data.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
					svgDoc.getElementById("size2").innerHTML = data.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;


					var code = data.order_details[0].BarcodeForPricesTags.toString();
					jsbarcode(code, 'barcode', svgDoc)

				}, false);
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
/**
 * Function to approve wash care label
 * @author <praveen>
 */
function approve()
  {
  	 try{
     var comment    = $('#comment').val();
     var id         = $('#wcl_id').val();
     var csrf_token = $('meta[name="csrf-token"]').attr('content');
    
     $.ajax({
         url: base_url + "/merchandiser/set-wcl-merchandiser-approve-comment",
         method: 'POST',
         data: {_token:csrf_token,comment:comment,id:id},
         beforeSend: function () {
           $(".loader").show();
           $(".loader").css('margin-top','12%');
         },
         statusCode: {
              200: function() {
              	   location.reload();
              }
         },
         error: function (data) {
            console.log(data);
         },
         complete: function () {
                   $(".loader").hide();
                   $(".loader").css('margin-top','0%');
         }
	         })
	     } catch (e) {
	     console.log("Error:"+e)
	   }
  }
/**
 * Function to reject wash care label
 * @author <praveen>
 */
function reject()
  {
  	 try{
     var comment    = $('#comment').val();
     var id         = $('#wcl_id').val();
     var csrf_token = $('meta[name="csrf-token"]').attr('content');
    
     $.ajax({
         url: base_url + "/merchandiser/set-wcl-merchandiser-reject-comment",
         method: 'POST',
         data: {_token:csrf_token,comment:comment,id:id},
         beforeSend: function () {
           $(".loader").show();
           $(".loader").css('margin-top','12%');
         },
         statusCode: {
              200: function() {
              	   location.reload();
              }
         },
         error: function (data) {
            console.log(data);
         },
         complete: function () {
                   $(".loader").hide();
                   $(".loader").css('margin-top','0%');
         }
	         })
	     } catch (e) {
	     console.log("Error:"+e)
	   }
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
	svg.setAttribute('font-size', '50px');
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
	console.log(img.src);

	//remove temporary canvas
	canvasTmp.remove();

	//Check in browsers
	//
	//                var csrf_token = $('meta[name="csrf-token"]').attr('content');
	//                        $.ajax({
	//                            url: base_url + "/admin/template-management/base64_to_jpeg",
	//                            type: "Post",
	//                            data: {
	//                                src: img.src,
	//                                _token: csrf_token
	//                            },
	//                            beforeSend: function () {
	//                                $(".loader").show();
	//                                $(".loader").css('margin-top', '12%');
	//                            },
	//                            success: function (data) {
	//                               svgDoc.getElementById(id).setAttribute('xlink:href',data);
	//                                
	//                            },
	//                            error: function (data) {
	//                               
	//                            }
	//                        })
	svgDoc.getElementById(id).innerHTML = svg_xml;
}
