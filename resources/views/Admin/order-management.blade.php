@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')

<body id="pages-empty-page" data-layout="top-navigation-1" data-background="light" data-sidebar="dark" data-navbar="dark" data-top-navigation="dark" data-controller="pages" data-view="empty-page" data-collapsed="false" data-fullscreen="false" data-logo="light">
    <div class="specific-menu">
        <div class="dropdown-select">
            <select class="custom-select form-control">
        <option value="default">Select Buyer</option>
        <option value="1">Tesco</option>
        <option value="2">Orchestra</option>
        </select>
        </div>
        <div class="btn-group sub_modules" style="display:none">
            @foreach($sub_modules as $mod)

            <a href="#" onclick="{{$mod->url_path}}" class="btn btn-transparent">
        <i class="{{$mod->image}}"></i>
        <span class="title">{{$mod->module_name}}</span>
        </a> @endforeach
        </div>
    </div>
    <div class="col main"></div>
</body>
@endsection @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif @section('jqueryIncludes')
    <script>
        $('.custom-select').change(function() {
            $('.sub_modules').css('display','block')
            orchestra_sticker_orders($(this).find("option:selected").text().toLowerCase())
        });
    </script>
    @endsection
