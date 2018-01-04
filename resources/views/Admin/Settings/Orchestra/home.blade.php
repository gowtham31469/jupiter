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
        <!--metadata design - Akshay Bhat -->
        <div class="sidebar style-4">
            <h4>Metadata</h4>
            <ul class="customer-select">
                <li><a class="active">Garments</a></li>
                <li><a>size chart</a></li>
                <li><a>countries</a></li>
                <li><a>Product family</a></li>
                <li><a>Special Instructions</a></li>
                <li><a>Compositions</a></li>
                <li><a>Parts</a></li>
                <li><a>age group</a></li>
                <li><a>Season</a></li>
                <li><a>Region</a></li>
                <li><a>Flags</a></li>
                <li><a>Items</a></li>
                <li><a>Cylinder Radius</a></li>
            </ul>
        </div>
        <div class="template-block">
            <div class="top-content">
                <h3 class="content-heading">Garments - Metadata</h3>
            </div>
            <div class="table-responsive">
               
                <table class="table table-bordered">
                    <caption><span>Some Sample text</span>  <button class="btn btn-primary btn-sm pull-right">Add data</button></caption>

                    <thead>
                        <tr>
                            <th>Data 1</th>
                            <th>Data 2</th>
                            <th>Data 3</th>
                            <th>Data 4</th>
                            <th>Data 5</th>
                            <th>Operations</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Data 1</td>
                            <td>Data 2</td>
                            <td>Data 3</td>
                            <td>Data 4</td>
                            <td>Data 5</td>
                            <td><button class="filterbtn options" data-toggle="tooltip" data-placement="right" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                <button class="filterbtn options" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button> </td>
                        </tr>
                        <tr>
                            <td>Data 1</td>
                            <td>Data 2</td>
                            <td>Data 3</td>
                            <td>Data 4</td>
                            <td>Data 5</td>
                            <td><button class="filterbtn options" data-toggle="tooltip" data-placement="right" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                <button class="filterbtn options" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button> </td>
                        </tr>
                        <tr>
                            <td>Data 1</td>
                            <td>Data 2</td>
                            <td>Data 3</td>
                            <td>Data 4</td>
                            <td>Data 5</td>
                            <td><button class="filterbtn options" data-toggle="tooltip" data-placement="right" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                <button class="filterbtn options" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button> </td>
                        </tr>
                        <tr>
                            <td>Data 1</td>
                            <td>Data 2</td>
                            <td>Data 3</td>
                            <td>Data 4</td>
                            <td>Data 5</td>
                            <td><button class="filterbtn options" data-toggle="tooltip" data-placement="right" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                <button class="filterbtn options" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button> </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>

        <!--metadata design - Akshay Bhat -->
    </div>
    @endsection @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif @section('jqueryIncludes')
    <script src="{{ asset('js/Admin/settings.js') }}"></script>
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
        $(document).ready(function() {
            $('.select-buyer').click(function() {
                $('.select-buyer').addClass('hidden');
            })
            $('.logo').html('Settings')
            $('.buyer-nav-select').html($('.buyer-div').html())
            if (localStorage.getItem("buyer") === null) {

            } else {
                // orders();
            }
            $('.buyer-select').val(localStorage.getItem("buyer_id"))
            $('.buyer-nav-select .buyer-select').selectpicker({
                style: 'btn-default',
                size: 4
            });
            $('.buyer-select.bootstrap-select').on('changed.bs.select', function() {
                if ($('.buyer-nav-select .buyer-select option:selected').val() != "") {
                    localStorage.setItem("buyer", $('.buyer-nav-select .buyer-select').find("option:selected").text().toLowerCase());
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
            $(".order_type").change(function() {
                var selectedType = $(".order_type option:selected").val();
                if (selectedType === '1') {
                    $("#single_file").removeClass("hidden");
                    $("#multiple_file").removeClass("hidden");
                } else {
                    $("#single_file").addClass("hidden");
                    $("#multiple_file").addClass("hidden");
                }

            });
        });
    </script>

    @endsection