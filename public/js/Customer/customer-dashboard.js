/* Customer Order Management Script*/
$(document).ready(function() {
       /** [ Make buyers card active ] */
       $('div.buyers-card').click(function(){
         $('div.buyers-card').removeClass("active");
         $(this).addClass("active");
         $('#hidden-buyer-div').hide();
       });

       /** [ Show already selected(active) buyer on clicking buyer menu] */
       $('.buyer-nav-menu').on('click', function() {
           if (localStorage.getItem("buyer") === null) 
           {
              $("#buyerSelectionModalCenter").modal("show");
              
           } 
           else 
           {
               $('#'+localStorage.getItem("buyer")).addClass('active');
               $("#buyerSelectionModalCenter").modal("show");
           }
       });

       /** [If only 1 buyer , auto select that buyer] */
       var buyer_count = $('#invisible__count_id').val();
       if(buyer_count <= 1) 
       {
           $('#hidden-buyer-div').hide();
           $('div.buyers-card').addClass("active");
           localStorage.setItem("buyer", $('.buyers-card.active-buyer.active').data("name").toLowerCase());
           localStorage.setItem("buyer_id", $('.buyers-card.active-buyer.active').data("value"));
           $('.selected-buyer').text($('.buyers-card.active-buyer.active').data("name"))
       }   
       
       /** add buyer selection menu into navbar */
       $('.buyer-nav-menu').html($('.buyer-nav-div').html());

       /** Load selected buyer from local-storage on pageload */
       if (localStorage.getItem("buyer") === null) 
       {
           $(document).ready(function() {
              $("#buyerSelectionModalCenter").modal("show");
           })
       } 
       else 
       {
           $('.selected-buyer').text(localStorage.getItem("buyer"));
           var buyer = localStorage.getItem("buyer");
           // if (buyer === 'orchestra') {
           //     orderStatus();
           // } else {
           //     tescoOrderStatus();
           // }
       }

       /** [ Select buyer on clicking proceed button] */
       $('.proceed-btn').on('click', function() {
           
           if ($('.buyers-card.active-buyer.active').length != 0) 
           {
               $('#hidden-buyer-div').hide();
               localStorage.setItem("buyer", $('.buyers-card.active-buyer.active').data("name").toLowerCase());
               localStorage.setItem("buyer_id", $('.buyers-card.active-buyer.active').data("value"));
               $('.selected-buyer').text($('.buyers-card.active-buyer.active').data("name"))
               $('#buyerSelectionModalCenter').modal('hide');
               var buyer = localStorage.getItem("buyer");
               // if (buyer === 'orchestra') {
               //     orderStatus();
               // } else {
               //     tescoOrderStatus();
               // }
           } else {
               localStorage.removeItem("buyer");
               localStorage.removeItem("buyer_id");
               $('#hidden-buyer-div').show();
           }
       });

       /** To refresh modal while hidden **/
       $('#buyerSelectionModalCenter').on('hidden.bs.modal', function (e) {
         $('div.buyers-card').removeClass("active");
         if (localStorage.getItem("buyer") === null) 
         {
             $(document).ready(function() {
                $("#buyerSelectionModalCenter").modal("show");
             })
         } 
         else 
         {
             $('.selected-buyer').text(localStorage.getItem("buyer"));
             var buyer = localStorage.getItem("buyer");
             // if (buyer === 'orchestra') {
             //     orderStatus();
             // } else {
             //     tescoOrderStatus();
             // }
         }
        })
    });