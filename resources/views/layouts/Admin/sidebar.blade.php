 <!-- left-sidebar-1 -->
            <div class="left-sidebar left-sidebar-1">
                <div class="wrapper">
                    <div class="content">
                        <!-- sidebar-heading-1 -->
                        <div class="sidebar-heading sidebar-heading-1">
                            <!-- sidebar-heading-image -->
                            <div class="sidebar-heading-image">
                                <span class="badge badge-rounded badge-danger">8</span>
                                <img src="{{ asset('delta/assets/faces/m7.png') }}" class="rounded-circle" alt="image" />
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
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                             <i class="sli-power"></i>
                                          <span class="title">Logout</span>
                                   </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">

                                         {{ csrf_field() }}

                                       </form>
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
                                {{$top_mod}} </div>
                            <!-- end section-title -->
                            <!-- menu -->
                            <ul class="list-unstyled">
                                  @foreach($modules as $mod)
                                        <li>
                                           <a href="{{$mod->url_path}}" class="btn btn-default btn-flat btn-sidebar btn-sidebar-1">
                                            <i class="{{$mod->image}}"></i>
                                            <span class="title">{{$mod->module_name}}</span>
                                            </a>
                                        </li>
                                @endforeach
                            </ul>
                            <!-- end menu -->
                        </div>
                        <!-- end sidebar-widget-2 -->
                    </div>
                </div>
            </div>
            <!-- end left-sidebar-1 -->