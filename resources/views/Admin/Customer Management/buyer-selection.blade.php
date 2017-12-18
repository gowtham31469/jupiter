@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<style>
    .jstree-leaf>.jstree-anchor>.fa-folder:before {
        content: "\f02b";
    }

    .force-overflow {
        min-height: 450px;
    }

    .style-4::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 2px rgba(0, 0, 0, 0.3);
        background-color: #F5F5F5;
    }

    .style-4::-webkit-scrollbar {
        width: 1px;
        background-color: #F5F5F5;
    }

    .style-4::-webkit-scrollbar-thumb {
        background-color: #000000;
        border: 1px solid #555555;
    }

    .descriptionDiv {
        background: #fff;
        padding: 10px;
        margin-left: -13px;
        text-align: center;
        margin-bottom: 11px;
        position: absolute;
        bottom: 0;
        width: 97%;
        border: 1px dashed #333;
    }

    .descriptionDiv span {
        font-weight: 400;
    }

    .descriptionDiv h4 {
        color: #333;
        border-bottom: 1px solid #cacaca;
        padding-bottom: 8px;
    }

    p.delta {
        display: inline-block;
        margin-right: 30px;
    }

    .sidebar {
        position: fixed;
        top: 40px;
        bottom: 0;
        left: 0;
        z-index: 997;
        display: block;
        overflow-x: hidden;
        overflow-y: auto;
        width: 200px;
        /* Scrollable contents if viewport is shorter than content. */
        background-color: #f5f5f5;
        border-right: 1px solid #eee;
    }
    ul.customer-select{
        padding-left: 0px;
    }

    ul.customer-select li>a {
        position: relative;
        display: block;
        padding: 10px 15px;
        color: #333;
        cursor: pointer;
        padding-right: 40px;
        padding-left: 20px;
    }
    ul.customer-select li{
        list-style-type: none;
        
    }
    ul.customer-select>li>a:focus, ul.customer-select>li>a:hover {
    text-decoration: none;
    background-color: #eee;
}
    .template-block{
        margin-left: 200px;
    }
    .sidebar h4{
            padding-left: 20px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 8px;
    }
    
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

            //$('.js-card').niceScroll()
            $('.logo').html('Customers');
            $('.buyer-nav-select').html($('.buyer-div').html())
            if (localStorage.getItem("pricing_buyer") === null) {
                $('.select-buyer').html('<img src="../../images/select-buyer.png" class="img-responsive">');
            } else {
                price_template_list();
            }
            $('.buyer-select').val(localStorage.getItem("pricing_buyer_id"));
            $('.buyer-nav-select .buyer-select').selectpicker({
                style: 'btn-default',
                size: 4
            });

            $('.buyer-select.bootstrap-select').on('changed.bs.select', function() {
                if ($('.buyer-nav-select .buyer-select option:selected').val() != "") {
                    localStorage.setItem("pricing_buyer", $('.buyer-nav-select .buyer-select').find("option:selected").text().toLowerCase());
                    localStorage.setItem("pricing_buyer_id", $('.buyer-nav-select .buyer-select').find("option:selected").val());
                    price_template_list();
                } else {
                    localStorage.removeItem("pricing_buyer");
                    localStorage.removeItem("pricing_buyer_id");
                    location.reload()
                }
            });
        });
    </script>
    @endsection