<style>
    [data-background="light"] .navbar.navbar-1 a {
        color: #fff;
    }

    [data-layout="top-navigation-1"] .main {
        padding-top: 0px;
    }


    [data-layout="top-navigation-1"][data-collapsed="true"] .navbar,[data-top-navigation="dark"] .top-navigation.top-navigation-1 {
        background: #1791E8;
        color: #ffffff;
    }

    [data-navbar="custom"] .navbar.navbar-1 {
        background: #000F08;
        color: #ffffff;
    }

    [data-top-navigation="dark"] .top-navigation.top-navigation-1 .menu-bottom {
        background: #2f91e9;
        border-color: #2988de;
    }

    [data-layout="top-navigation-1"][data-collapsed="true"] .navbar, [data-layout="top-navigation-1"][data-collapsed="true"] .top-navigation {
    padding-left: 20px;
}
    [data-sidebar="dark"] .left-sidebar.left-sidebar-1 .btn-default.btn-flat.btn-sidebar:active, [data-sidebar="dark"] .left-sidebar.left-sidebar-1 .btn-default.btn-flat.btn-sidebar:focus, [data-sidebar="dark"] .left-sidebar.left-sidebar-1 .btn-default.btn-flat.btn-sidebar:visited, [data-sidebar="dark"] .left-sidebar.left-sidebar-1 .btn-default.btn-flat.btn-sidebar.focus {
    background: #f5f5f5;
    color: #000000;
    position: relative;
}

    a.btn.btn-transparent {
        background-color: transparent;
        border-bottom: 2px solid transparent;
        color: #000000;
    }

    a.btn.btn-transparent:hover {
        border-bottom: 2px solid #2f91e9!important;
    }

    a.btn.btn-transparent.active {
        border-bottom: 2px solid #2f91e9!important;
        color: #000000;
    }

[data-layout="top-navigation-1"] .left-sidebar {
    position: fixed;
    top: 115px;
    background: #f5f5f5 !important;
    bottom: 0;
    left: 0;
    height: calc(100% - 120px);
    width: 240px;
    z-index: 1037;
    -webkit-transform: translate3d(-240px, 0, 0);
    transform: translate3d(-240px, 0, 0);
   border-right: 1px solid #e3e3e3;
}
    [data-sidebar="light"] .left-sidebar.left-sidebar-1 .btn-default.btn-flat.btn-sidebar{
        background: #f5f5f5 !important;
    }
    .specific-menu{
        padding-top:90px;
    }
    .btn-group,
    .btn-group-vertical {
        position: relative;
        text-align: center;
        align-items: center;
        margin: auto 0;
        display: block;
    }

    [data-top-navigation="dark"] ul.menu-bottom.active.list-unstyled {
        background: #030f07;
    }

</style>
<!-- navbar-1 -->
<nav class="navbar navbar-1 navbar-fixed-top">
    <ul class="nav nav-inline navbar-left-1">
        <li class="nav-item">
            <a class="nav-link toggle-layout" href="#">
                <i class="fa fa-chevron-left"></i>
            </a>
        </li>
    </ul>
    <div class="logo">
        <img style="height: 45px; margin-right: 10px;" src="{{ asset('delta/assets/images/logo.png') }}">
        <span class="title">Jupiter Garments Accessories </span>
    </div>


    <!-- end toggle layout -->
    <!-- search form -->
    <!--
        <div class="navbar-search-form">
            <form>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
            </form>
        </div>
-->

    <div class="separator"></div>
    <!-- navbar-right -->
    <ul class="nav nav-inline navbar-right-1">
        <!-- dropdown -->
        <li class="nav-item dropdown dropdown-user">
            <a class="nav-link" href="#" data-toggle="dropdown" data-target="dropdown-user">
                <!--                    <span class="badge badge-sm badge-rounded badge-warning">1</span>-->
                <img src="http://reflectivetech.in/elpfa/images/avatar.png" class="rounded-circle" alt="avatar" />
                <span style="font-weight: 600;display: inline-block;margin-left:10px;">JGA Admin</span> </a>
            <div class="dropdown-menu dropdown-menu-right from-right" id="dropdown-user">
                <h6 class="dropdown-header dropdown-title">My account</h6>
                <a class="dropdown-item" href="#">
                    <i class="fa fa-user"></i>
                    <span class="title">Profile</span>
                </a>
                <a class="dropdown-item" href="{{ url('/customer/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out"></i>
                    <span class="title">Logout</span>
                </a>
                <form id="logout-form" action="{{ url('/customer/logout') }}" method="POST" style="display: none;">

                    {{ csrf_field() }}

                </form>
            </div>
        </li>
        <!-- end dropdown -->
    </ul>
    <!-- end navbar-right -->
    <!-- navbar-right -->
    <ul class="nav nav-inline navbar-right-2">
        <li class="nav-item">
            <a class="nav-link toggle-layout">
                <i class="sli-menu"></i>
            </a>
        </li>
    </ul>
    <!-- end navbar-right -->
</nav>
<!-- end navbar-1 -->
<div class="top-navigation top-navigation-1">

    <ul class="menu-bottom list-unstyled">
        <li class="level-1">
            <a href="#">
                <span class="modName">Order Management</span>
            </a>

        </li>
    </ul>
    <ul class="menu-bottom list-unstyled">
        <li class="level-1">
            <a href="#">
                <span class="modName">Sales Order Management</span>
            </a>

        </li>
    </ul>

</div>
