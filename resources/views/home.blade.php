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
</style>
<div class="col-sm-12 col-md-12 main wallpaper">
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="main-menu">
                <div class="case-wrapper" title="Item" draggable="false">
                    <a href="/admin/user-management">
                        <div class="app-icon" style="background-color: #2ecc71; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-archive" title="Item" style=""></i></div>
                        <div class="case-label ellipsis">
                            <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">User</span> </div>
                        <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                            <div class="circle-text">
                                <b>×</b>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="case-wrapper" title="Item" draggable="false">
                    <a href="/admin/buyer-management">
                        <div class="app-icon" style="background-color:#3498db; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-file-text-o" title="Item" style=""></i></div>
                        <div class="case-label ellipsis"> <span class="case-label-text">Buyer</span> </div>
                        <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                            <div class="circle-text">
                                <b>×</b>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="case-wrapper" title="Item" draggable="false">
                    <a href="/admin/supplier-management">
                        <div class="app-icon" style="background-color: #8e44ad; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-tag" title="Item" style=""></i></div>
                        <div class="case-label ellipsis">
                            <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Supplier</span> </div>
                        <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                            <div class="circle-text">
                                <b>×</b>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="case-wrapper" title="Item" draggable="false">
                    <a href="/admin/template-management">
                        <div class="app-icon" style="background-color: #34495e; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-archive" title="Item" style=""></i></div>
                        <div class="case-label ellipsis">
                            <div class="circle module-count-list-item hidden" data-doctype="Item" style=""> <span class="circle-text">1</span> </div> <span class="case-label-text">Templates</span> </div>
                        <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                            <div class="circle-text">
                                <b>×</b>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="case-wrapper" title="Item" draggable="false">
                    <a href="/admin/order-management">
                        <div class="app-icon" style="background-color:#e74c3c; position: relative; left: 0px; top: 0px; opacity: 1; transform: rotate(0deg);" title="Item"><i class="fa fa-file-text-o" title="Item" style=""></i></div>
                        <div class="case-label ellipsis"> <span class="case-label-text">Orders</span> </div>
                        <div class="circle module-remove" style="background-color: rgb(224, 224, 224); color: rgb(33, 33, 33); display: none;">
                            <div class="circle-text">
                                <b>×</b>
                            </div>
                        </div>
                    </a>
                </div>


            </div>
        </div>
    </div>

</div>
    @endsection

    @section('jqueryIncludes')
<script>
 $( document ).ready(function() {
    $(".logo").html('Jupiter Knitting Accessories');
 })
</script>
@endsection
