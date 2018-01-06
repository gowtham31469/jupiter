@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

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
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <script src="{{ asset('js/Admin/order.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.logo').html('Order Status');
            $('.select-buyer').click(function() {
                $('.select-buyer').addClass('hidden');
            })
            $('.buyer-nav-select').html($('.buyer-div').html())
            if (localStorage.getItem("status_buyer") === null) {

            } else {
                orderStatus();
            }
            $('.buyer-select').val(localStorage.getItem("status_buyer_id"))
            $('.buyer-nav-select .buyer-select').selectpicker({
                style: 'btn-default',
                size: 4
            });
            $('.buyer-select.bootstrap-select').on('changed.bs.select', function() {
                if ($('.buyer-nav-select .buyer-select option:selected').val() != "") {
                    localStorage.setItem("status_buyer", $('.buyer-nav-select .buyer-select').find("option:selected").text().toLowerCase());
                    localStorage.setItem("status_buyer_id", $('.buyer-nav-select .buyer-select').find("option:selected").val());
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

                            orderStatus();
                        },
                        error: function(data) {

                        },
                        complete: function() {
                            $(".loader").hide();
                            $(".loader").css('margin-top', '0%');
                        }
                    })
                } else {
                    localStorage.removeItem("status_buyer");
                    localStorage.removeItem("status_buyer_id");
                    location.reload()
                }
            });
        });
    </script>

    @endsection