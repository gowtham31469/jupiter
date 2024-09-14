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
                var table = '<table style="font-size:12px" class="table table-bordered"><thead><tr><th style="text-align:left">UVC TCO</th><th style="text-align:left">Size(ANS)</th><th style="text-align:left">Qty(Nos)</th></tr></thead><tbody>';

                $.each(data.wcl_orders_qty, function(i, item){
                  var index = i+1;
                  table += '<tr id='+id+'><td style="text-align:left">'+item.uvc_tco+'</td><td style="text-align:left">'+item.size+'</td><td style="text-align:left">'+item.quantity+'</td></tr>';
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
/**
 * Function to approve wash care label
 * @author <praveen>
 */
function approve()
  {
  	 try{
     var comment    = $('#comment').val();
     var id         = $('#wcl_id').val();
     
    
     $.ajax({
         url: base_url + "/merchandiser/set-wcl-merchandiser-approve-comment",
         method: 'POST',
         data: {_token:csrf_token,comment:comment,id:id},
         beforeSend: function () {
           $(".loader").show();
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
     
    
     $.ajax({
         url: base_url + "/merchandiser/set-wcl-merchandiser-reject-comment",
         method: 'POST',
         data: {_token:csrf_token,comment:comment,id:id},
         beforeSend: function () {
           $(".loader").show();
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
  svg.setAttribute('width', '36.1px');
  svg.setAttribute('height', '77px');
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
