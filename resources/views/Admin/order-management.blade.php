@extends('layouts.Admin.app')

@section('content')
<body id="pages-empty-page" data-layout="top-navigation-1" data-background="light" data-sidebar="dark" data-navbar="dark" data-top-navigation="dark" data-controller="pages" data-view="empty-page" data-collapsed="false" data-fullscreen="false" data-logo="dark" class="pace-done">
    <div id="fakeloader"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="left-sidebar-placeholder"></div>
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
                                <h5>Empty Page1</h5>
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
@endsection

