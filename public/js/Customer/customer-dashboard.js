/* Customer Order Management Script*/
$(document).ready(function() {
      var buyer_count = $('#invisible__count_id').val();
      if(buyer_count <= 1) {
        $('.buyer-select option:first').remove();
        localStorage.setItem("pricing_buyer", $('.buyer-select').find("option:selected").text().toLowerCase());
        localStorage.setItem("pricing_buyer_id", $('.buyer-select').find("option:selected").val());
      }

      $('.select-buyer').click(function() {
        $('.select-buyer').addClass('hidden');
      })

      $('.buyer-nav-select').html($('.buyer-div').html())
      if (localStorage.getItem("pricing_buyer") === null) {
        $('.select-buyer').html('<img src="../../images/select-buyer.png" class="img-responsive">');
      } else {
        //price_template_list();
      }
      $('.buyer-select').val(localStorage.getItem("pricing_buyer_id"));
      $('.buyer-select').selectpicker({
        style: 'btn-default',
        size: 4
      });

      $('.buyer-select.bootstrap-select').on('changed.bs.select', function() {
        if ($('.buyer-nav-select .buyer-select option:selected').val() != "") {
            localStorage.setItem("pricing_buyer", $('.buyer-nav-select .buyer-select').find("option:selected").text().toLowerCase());
            localStorage.setItem("pricing_buyer_id", $('.buyer-nav-select .buyer-select').find("option:selected").val());
        } else {
            localStorage.removeItem("pricing_buyer");
            localStorage.removeItem("pricing_buyer_id");
            location.reload()
        }
      });
});