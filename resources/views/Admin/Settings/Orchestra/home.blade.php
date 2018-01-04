@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<div class="col-sm-12 col-md-12 main">
    <div class="top-content">
        <div class="buyer-div hidden">
             <select class="js-example-responsive buyer-select show-tick show-menu-arrow" data-live-search="true">
                <option value="">Select Buyer</option>
                    @foreach($buyers as $buyer)
                    <option title="Selected Buyer : {{$buyer->buyer_name}}" value="{{$buyer->id}}">{{$buyer->buyer_name}}</option>
                    @endforeach
              </select>
        </div>
    <div class="clearfix"></div>
</div>
    <div class="main-content">
        <div class="select-buyer">
            <img src="../../images/select-buyer.png" class="img-responsive">
        </div>
    </div>
@endsection @else
<script type="text/javascript">
    window.location = "{{ url('/admin') }}";
</script>
@endif @section('jqueryIncludes')      
<script src="{{ asset('js/Admin/settings.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select-buyer').click(function(){
            $('.select-buyer').addClass('hidden');
        })
         $('.logo').html('Settings')
         $('.buyer-nav-select').html($('.buyer-div').html())
         if (localStorage.getItem("buyer") === null) {
                
         }
        else
        {
             // orders();
        }
        $('.buyer-select').val(localStorage.getItem("buyer_id"))
          $('.buyer-nav-select .buyer-select').selectpicker({
               style: 'btn-default',
               size: 4
            });
        $('.buyer-select.bootstrap-select').on('changed.bs.select', function() {
            if ($('.buyer-nav-select .buyer-select option:selected').val() != "") {
                localStorage.setItem("buyer",$('.buyer-nav-select .buyer-select').find("option:selected").text().toLowerCase());
                localStorage.setItem("buyer_id", $('.buyer-nav-select .buyer-select').find("option:selected").val());
                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: base_url + "/admin/order-management/order_sub_modules",
                    type: "Post",
                    dataType: "json",
                    data: {
                        _token: csrf_token,
                        buyer: $(this).find("option:selected").text().toLowerCase()
                    },
                    beforeSend: function() {

                    },
                    success: function(data) {
                      
                       orders();                    
                    },
                    error: function(data) {

                    },
                    complete: function() {
                        $(".loader").hide();
                        $(".loader").css('margin-top', '0%');
                    }
                })
            } else {
                localStorage.removeItem("buyer");
                localStorage.removeItem("buyer_id");
                location.reload()
            }
        });
        $(".order_type").change(function(){
            var selectedType = $(".order_type option:selected").val();
            if (selectedType === '1') 
            {
                $("#single_file").removeClass("hidden");
                $("#multiple_file").removeClass("hidden");
            }
            else
            {
                $("#single_file").addClass("hidden");
                $("#multiple_file").addClass("hidden");
            }
            
        });
    });
</script>

@endsection