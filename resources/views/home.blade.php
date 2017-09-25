@extends('layouts.Admin.app')

@section('content')
<body id="pages-empty-page" data-layout="top-navigation-1" data-background="light" data-sidebar="light" data-navbar="light" data-top-navigation="light" data-controller="pages" data-view="empty-page" data-collapsed="false" data-fullscreen="false" data-logo="light">
    <div id="fakeloader"></div>
    
    <!-- navbar-1 -->
    <nav class="navbar navbar-1">
        <div class="logo">
            <i class="sli-compass"></i>
            <span class="title">JGA</span>
        </div>
        <!-- toggle layout -->
        <ul class="nav nav-inline navbar-left-1">
            <li class="nav-item">
                <a class="nav-link toggle-layout" href="#">
                    <i class="sli-arrow-right"></i>
                </a>
            </li>
        </ul>
        <!-- end toggle layout -->
        <!-- search form -->
        <div class="navbar-search-form">
            <form>
                <div class="input-group">
                    <span class="input-group-addon"><i class="sli-magnifier"></i></span>
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
            </form>
        </div>
        
        <div class="separator"></div>
        <!-- navbar-right -->
        <ul class="nav nav-inline navbar-right-1">
            <!-- dropdown -->
            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link" href="#" data-toggle="dropdown" data-target="dropdown-user">
                    <span class="badge badge-sm badge-rounded badge-warning">1</span>
                    <img src="../../assets/faces/m7.png" class="rounded-circle" alt="avatar" />
                </a>
                <div class="dropdown-menu dropdown-menu-right from-right" id="dropdown-user">
                    <h6 class="dropdown-header dropdown-title">My account</h6>
                    <a class="dropdown-item" href="#">
                        <i class="sli-envelope"></i>
                        <span class="title">Inbox</span>
                        <span class="badge badge-pill badge-raised badge-danger badge-sm">New</span>
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="sli-star"></i>
                        <span class="title">Messages</span>
                        <span class="badge badge-info badge-rounded badge-sm">5</span>
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="sli-settings"></i>
                        <span class="title">Profile</span>
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="sli-clock"></i>
                        <span class="title">Lock screen</span>
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="sli-power"></i>
                        <span class="title">Logout</span>
                    </a>
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
    <div class="container-fluid">
        <div class="row">
            <div class="left-sidebar-placeholder"></div>
            <!-- left-sidebar-1 -->
            <div class="left-sidebar left-sidebar-1">
                <div class="wrapper">
                    <div class="content">
                        <!-- sidebar-heading-1 -->
                        <div class="sidebar-heading sidebar-heading-1">
                            <!-- sidebar-heading-image -->
                            <div class="sidebar-heading-image">
                                <span class="badge badge-rounded badge-danger">8</span>
                                <img src="../../assets/faces/m7.png" class="rounded-circle" alt="image" />
                            </div>
                            <!-- end sidebar-heading-image -->
                            <!-- dropdown -->
                            <div class="dropdown">
                                <!-- dropdown-toggle -->
                                <a class="btn btn-default btn-flat dropdown-toggle no-after" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="name">
                                        Eric Simpson </div>
                                    <div class="description">
                                        Lead developer
                                    </div>
                                    <div class="location">
                                        <i class="sli-globe"></i>
                                        <span>Los Angeles, CA</span>
                                    </div>
                                </a>
                                <!-- end dropdown-toggle -->
                                <!-- dropdown-menu -->
                                <div class="dropdown-menu dropdown-menu-center from-center">
                                    <a class="dropdown-item" href="#">
                                        <i class="sli-envelope"></i>
                                        <span class="title">Inbox</span>
                                        <div class="separator"></div>
                                        <span class="badge badge-pill badge-raised badge-danger badge-sm">New</span>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="sli-star"></i>
                                        <span class="title">Messages</span>
                                        <div class="separator"></div>
                                        <span class="badge badge-info badge-rounded badge-sm">5</span>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="sli-settings"></i>
                                        <span class="title">Profile</span>
                                        <div class="separator"></div>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="sli-clock"></i>
                                        <span class="title">Lock screen</span>
                                        <div class="separator"></div>
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="sli-power"></i>
                                        <span class="title">Logout</span>
                                        <div class="separator"></div>
                                    </a>
                                </div>
                                <!-- end dropdown-menu -->
                            </div>
                            <!-- end dropdown -->
                            <!-- icons -->
                            <div class="row text-center icons">
                                <div class="col col-xl-4">
                                    <div class="row flex-items-xs-middle">
                                        <div class="col">
                                            <i class="sli-social-twitter color-twitter"></i>
                                        </div>
                                    </div>
                                    <div class="row flex-items-xs-middle">
                                        <div class="col">
                                            <div class="title">
                                                54
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-xl-4">
                                    <div class="row flex-items-xs-middle">
                                        <div class="col">
                                            <i class="sli-social-facebook color-facebook"></i>
                                        </div>
                                    </div>
                                    <div class="row flex-items-xs-middle">
                                        <div class="col">
                                            <div class="title">
                                                98
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-xl-4">
                                    <div class="row flex-items-xs-middle">
                                        <div class="col">
                                            <i class="sli-social-google color-google-plus"></i>
                                        </div>
                                    </div>
                                    <div class="row flex-items-xs-middle">
                                        <div class="col">
                                            <div class="title">
                                                14
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end icons -->
                        </div>
                        <!-- end sidebar-heading-1 -->
                        <!-- section -->
                        <div class="section">
                            <!-- section-title -->
                            <div class="section-title">
                                Menu </div>
                            <!-- end section-title -->
                            <!-- menu -->
                            <ul class="list-unstyled">
                                <li>
                                    <a href="../dashboards/dashboard.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-1">
                                        <i class="sli-chart"></i>
                                        <span class="title">Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" data-target="#widgets" class="btn btn-default btn-flat btn-sidebar btn-sidebar-1 has-children" data-toggle="collapse">
                                        <i class="sli-puzzle"></i>
                                        <span class="title">Widgets</span>
                                    </a>
                                    <!-- sub-menu -->
                                    <ul class="list-unstyled collapse" id="widgets">
                                        <li>
                                            <a href="../widgets/activity-widgets.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Activity widgets</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../widgets/area-chart-widgets.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Area chart widgets</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../widgets/bar-chart-widgets.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Bar chart widgets</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../widgets/donut-chart-widgets.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Donut chart widgets</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../widgets/icon-widgets.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Icon widgets</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../widgets/line-chart-widgets.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Line chart widgets</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../widgets/pie-chart-widgets.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Pie chart widgets</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../widgets/table-widgets.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Table widgets</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../widgets/text-widgets.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Text widgets</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../widgets/timeline-widgets.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Timeline widgets</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../widgets/user-widgets.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">User widgets</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- end sub-menu -->
                                </li>
                                <li>
                                    <a href="#" data-target="#layouts" class="btn btn-default btn-flat btn-sidebar btn-sidebar-1 has-children" data-toggle="collapse">
                                        <i class="sli-organization"></i>
                                        <span class="title">Layouts</span>
                                    </a>
                                    <!-- sub-menu -->
                                    <ul class="list-unstyled collapse" id="layouts">
                                        <li>
                                            <a href="../../collapsed-sidebar-1/pages/empty-page.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Collapsed sidebar</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../../default-sidebar-1/pages/empty-page.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Default sidebar</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../../off-canvas-1/pages/empty-page.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Off canvas</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../../sidebar-over-1/pages/empty-page.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Sidebar over</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="empty-page.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Top navigation 1</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../../top-navigation-2/pages/empty-page.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Top navigation 2</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- end sub-menu -->
                                </li>
                                <li>
                                    <a href="#" data-target="#starter-packages" class="btn btn-default btn-flat btn-sidebar btn-sidebar-1 has-children" data-toggle="collapse">
                                        <i class="sli-plane"></i>
                                        <span class="title">Starter packages</span>
                                    </a>
                                    <!-- sub-menu -->
                                    <ul class="list-unstyled collapse" id="starter-packages">
                                        <li>
                                            <a href="../../collapsed-sidebar-1/starter-packages/index.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Collapsed sidebar</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../../default-sidebar-1/starter-packages/index.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Default sidebar</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../../empty-view-1/starter-packages/index.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Empty view</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../../off-canvas-1/starter-packages/index.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Off canvas</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../../sidebar-over-1/starter-packages/index.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Sidebar over</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../starter-packages/index.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Top navigation 1</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="../../top-navigation-2/starter-packages/index.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-2">
                                                <i class="sli-star"></i>
                                                <span class="title">Top navigation 2</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- end sub-menu -->
                                </li>
                                <li>
                                    <a href="../apps/calendar.html" class="btn btn-default btn-flat btn-sidebar btn-sidebar-1">
                                        <i class="sli-calendar"></i>
                                        <span class="title">Calendar</span>
                                    </a>
                                </li>
                            </ul>
                            <!-- end menu -->
                        </div>
                        <!-- end sidebar-widget-2 -->
                    </div>
                </div>
            </div>
            <!-- end left-sidebar-1 -->
            <div class="col main">
                <!-- jumbotron-1 -->
                <div class="jumbotron jumbotron-1">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <a class="btn btn-success btn-rounded btn-icon" href="#">
                                    <i class="sli-wrench"></i>
                                    <span class="btn-text">Settings</span>
                                </a>
                                <a class="btn btn-info btn-rounded btn-icon" href="#">
                                    <i class="sli-support"></i>
                                    <span class="btn-text">Support</span>
                                </a>
                                <h4>Pages</h4>
                                <h5>Empty Page</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end jumbotron-1 -->
                <div class="page">
                    <!-- pages/empty-page -->
                    <div class="row">
                        <div class="col">
                            <div class="widget">
                                <div class="row">
                                    <div class="col">
                                        <div class="title">Empty page</div>
                                        <div class="description">This is an empty page</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end pages/empty-page -->
                </div>
            </div>
        </div>
    </div>
    <!-- left sidebar backdrop -->
    <div class="left-sidebar-backdrop"></div>
    <!-- end left sidebar backdrop -->
    <!-- right sidebar backdrop -->
    <div class="right-sidebar-backdrop"></div>
    <!-- end right sidebar backdrop -->
</body>
@endsection
