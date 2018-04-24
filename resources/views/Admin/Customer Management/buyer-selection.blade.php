@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<style>
   
    
</style>
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
        {{ Form::hidden('invisible', $buyer_count, array('id' => 'invisible__count_id')) }}
        <div class="clearfix"></div>
    </div>
    <div class="main-content">
        <div class="sidebar style-4">
            <h4>Select Customer</h4>
            <ul class="customer-select">
            </ul>
        </div>
    <div class="template-block"></div>
        <div class="select-buyer">
        </div>
    </div>
    <div id="snackbar">Saved</div>
    @endsection @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif @section('jqueryIncludes')
    <script src="{{ asset('js/Admin/pricing.js') }}"></script>
    <script>
        $(document).ready(function() {
                $('.select-buyer').click(function() {
                $('.select-buyer').addClass('hidden');
            })
            /** [If only 1 buyer , auto select that buyer] */
            var buyer_count = $('#invisible__count_id').val();
            if(buyer_count <= 1) 
            {
                $('.buyer-select option:first').remove();
                localStorage.setItem("buyer", $('.buyer-select').find("option:selected").text().toLowerCase());
                localStorage.setItem("buyer_id", $('.buyer-select').find("option:selected").val());
            }   
            //$('.js-card').niceScroll()
            /* Show logo on left side */
            $('.logo').html('Customers');
            $('.buyer-nav-select').html($('.buyer-div').html())
            if (localStorage.getItem("buyer") === null) {
                $(document).ready(function() {
                   $('.select-buyer').html('<img src="../../images/select-buyer.png" class="img-responsive">');
                })
            } else {
                price_template_list();
            }
            $('.buyer-select').val(localStorage.getItem("buyer_id"));
            $('.buyer-nav-select .buyer-select').selectpicker({
                style: 'btn-default',
                size: 4
            });

            $('.buyer-select.bootstrap-select').on('changed.bs.select', function() {
                if ($('.buyer-nav-select .buyer-select option:selected').val() != "") {
                    localStorage.setItem("buyer", $('.buyer-nav-select .buyer-select').find("option:selected").text().toLowerCase());
                    localStorage.setItem("buyer_id", $('.buyer-nav-select .buyer-select').find("option:selected").val());
                    price_template_list();
                } else {
                    localStorage.removeItem("buyer");
                    localStorage.removeItem("buyer_id");
                    location.reload()
                }
            });
        });
    </script>
    @endsection