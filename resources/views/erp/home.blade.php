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
    .erp-dynamic-menu{
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
				@if(Auth::guard('erpuser')->user()->is_admin)
				<a href="{{ route('erp-users')}}">
				@endif	
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-users" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Admin</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
				@if(Auth::guard('erpuser')->user()->is_admin)
				</a>
				@endif
            </div>
            <div class="case-wrapper" title="Item" draggable="false">
				<?php if(in_array('INVOICE MANAGER', $roles) || Auth::guard('erpuser')->user()->is_admin) { ?>
						<a href="{{ url('/erp/create-invoice') }}">
				<?php } ?>
                			
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-file-text-o" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Invoice</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
                <?php if(in_array('INVOICE MANAGER', $roles) || Auth::guard('erpuser')->user()->is_admin) { ?>
                		</a>
				<?php } ?>
				
            </div>
            <div class="case-wrapper" title="Item" draggable="false">
				<?php if(in_array('JOB CARD MANAGER', $roles) || Auth::guard('erpuser')->user()->is_admin) { ?>
						<a href="{{ url('/erp/job-manager') }}">
				<?php } ?>
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-suitcase" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Job Card Manager</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
				<?php if(in_array('JOB CARD MANAGER', $roles) || Auth::guard('erpuser')->user()->is_admin) { ?>
                		</a>
				<?php } ?>
            </div>
			<div class="case-wrapper" title="Item" draggable="false">
				<?php if(in_array('PO MANAGER', $roles) || Auth::guard('erpuser')->user()->is_admin) { ?>
						<a href="{{ url('/erp/po-manager') }}">
				<?php } ?>
				 
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-shopping-cart" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">PO Manager</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
				<?php if(in_array('PO MANAGER', $roles) || Auth::guard('erpuser')->user()->is_admin) { ?>
                		</a>
				<?php } ?>
            </div>
			<div class="case-wrapper" title="Item" draggable="false">
				<?php if(in_array('PRINT MANAGER', $roles) || Auth::guard('erpuser')->user()->is_admin) { ?>
						<a href="{{ url('/erp/print-manager') }}">
				<?php } ?>
				 
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-shopping-cart" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Print Manager</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
				<?php if(in_array('PRINT MANAGER', $roles) || Auth::guard('erpuser')->user()->is_admin) { ?>
                		</a>
				<?php } ?>
            </div>
            <div class="case-wrapper" title="Item" draggable="false">
				@if(Auth::guard('erpuser')->user()->is_admin)
				<a href="{{ route('erp-settings')}}">
				@endif	
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-cogs" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Settings</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
				@if(Auth::guard('erpuser')->user()->is_admin)
				</a>
				@endif
            </div>

                 <div class="case-wrapper" title="Item" draggable="false">
                {{-- @if(Auth::guard('erpuser')->user()->is_admin) --}}
                <a href="{{ route('erp/report-generator')}}">
                {{-- @endif   --}}
                <div class="app-icon" style="background-color: #2196F3; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-file-pdf-o" title="Item" style=""></i></div>
                <div class="case-label ellipsis">
                    <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Reports</span> </div>
                <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                    <div class="circle-text">
                        <b>×</b>
                    </div>
                </div>
                @if(Auth::guard('erpuser')->user()->is_admin)
                </a>
                @endif
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