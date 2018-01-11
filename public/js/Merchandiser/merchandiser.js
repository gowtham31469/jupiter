/**
 * Merchandiser page script
 */
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
