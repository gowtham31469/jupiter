@extends('layouts.Admin.app') @section('content')
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
</body>
@endsection
