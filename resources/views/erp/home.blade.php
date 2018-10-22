@extends('layouts.Erp.app')
@if (Auth::guard("erpuser")->user())
@section('content')
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
<div id="main-content">
        <div class="container">
            <div class="case-wrapper" title="Item" draggable="false">
                <div class="app-icon" style="background-color: #EF5350; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-shopping-cart" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Purchase</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
            </div>
            <div class="case-wrapper" title="Item" draggable="false">
                <div class="app-icon" style="background-color:#673AB7; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-cubes" title="Item" style=""></i></div>
                <div class="case-label ellipsis"> <span class="case-label-text">Inventory</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
            </div>
            <div class="case-wrapper" title="Item" draggable="false">
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-handshake-o" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Sales</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
            </div>
            <div class="case-wrapper" title="Item" draggable="false">
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-industry" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Production</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
            </div>
            <div class="case-wrapper" title="Item" draggable="false">
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-tag" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Template</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
            </div>
            <div class="case-wrapper" title="Item" draggable="false">
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-cogs" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Settings</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
            </div>
            <div class="case-wrapper" title="Item" draggable="false">
				<a href="{{ route('erp-users')}}">
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-users" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Users</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
					</a>
            </div>
            <div class="case-wrapper" title="Item" draggable="false">
                <a href="{{ url('/erp/invoice') }}">
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-file-text-o" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Invoice</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
                </a>
            </div>
            <div class="case-wrapper" title="Item" draggable="false">
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-line-chart" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Reports</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
            </div>
			<div class="case-wrapper" title="Item" draggable="false">
				 <a href="{{ url('/erp/po-manager') }}">
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-shopping-cart" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">PO Manager</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@else
    <script type="text/javascript">
        window.location = "{{ url('/erp') }}";
    </script>
@endif
@section('jqueryIncludes')

@endsection