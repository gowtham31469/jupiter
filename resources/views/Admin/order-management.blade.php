@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<style>
    label.select-label {
        font-weight: bold;
    }
    
    .slideUp {
        margin-top: 0%;
        transition: 0.5s ease;
    }
    .loader {
       display: none;
        margin: 0 auto;
    border: 8px solid #f3f3f3; /* Light grey */
    border-top: 8px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 80px;
    height: 80px;
    animation: spin 1.5s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<body id="pages-empty-page" data-layout="top-navigation-1" data-background="light" data-sidebar="dark" data-navbar="custom" data-top-navigation="dark" data-controller="pages" data-view="empty-page" data-collapsed="false" data-fullscreen="false" data-logo="light">

    <div class="specific-menu">
        <div class="row">
            <div class="col-md-6 slideUp" style="display: block;margin: 0 auto; margin-top:20%;">
                <div class="row">
                <div class="col-md-4">
                    <label class="select-label"><img style="height: 25px;" src="https://image.flaticon.com/icons/svg/138/138236.svg" class="img-responsive"><span>Select Buyer to Proceed</span></label>
                </div>
                <div class="col-md-8">
                    <select class="custom-select form-control">
                    <option value="default">Select Buyer</option>
                    <option value="1">Tesco</option>
                    <option value="2">Orchestra</option>
                    </select>
                </div>
                    </div>
            </div>
        </div>
        <div class="btn-group sub_modules" style="display:none">
            @foreach($sub_modules as $mod)

            <a href="#" onclick="{{$mod->url_path}}" class="btn btn-transparent">
        <i class="{{$mod->image}}"></i>
        <span class="title">{{$mod->module_name}}</span>
        </a> @endforeach
        </div>
    </div>

    <div class="col main">
        <div class="loader"></div>
    </div>
</body>
@endsection @else
<script type="text/javascript">
    window.location = "{{ url('/admin') }}";
</script>
@endif @section('jqueryIncludes')
<script>
    $('.custom-select').change(function() {
        orchestra_sticker_orders($(this).find("option:selected").text().toLowerCase())
    });
</script>
@endsection