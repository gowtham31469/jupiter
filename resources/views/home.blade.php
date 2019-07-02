 @extends('layouts.Admin.app') @section('content')
<style>
    .navbar-default {
        background-color: transparent!important;
        border-color: transparent!important;
    }
.grid-menu{
    display: none;
}
    .dynamic-menu{
        display: none;
    }
    a.dropdown-toggle.profile-dropdown-admin:hover {
    background: transparent !important;
}
</style>
<div class="col-sm-12 col-md-12 main wallpaper" style="height:100vh">
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="main-menu">
                <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
                @foreach($modules as $mod) 
                  @if (($mod->parent_id == 0 ) && ($mod->module_name != "Dashboard" ))
                    <div class="case-wrapper" title="Item" draggable="false">
                        <a href="{{$mod->url_path}}">
                            <div class="app-icon" style="background-color:{{$mod->bg_color}}; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="{{$mod->image}}" title="Item" style=""></i></div>
                            <div class="case-label ellipsis"> <span class="case-label-text">{{$mod->module_name}}</span> </div>
                            <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                                <div class="circle-text">
                                    <b>×</b>
                                </div>
                            </div>
                        </a>
                    </div>
                  @endif 
                @endforeach
            </div>
        </div>
    </div>

</div>
    @endsection

    @section('jqueryIncludes')
<script>
 $( document ).ready(function() {
    $(".logo").html('Jupiter Garments Accessories');
 })
</script>
@endsection
