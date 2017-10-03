 <!-- left-sidebar-1 -->
            <div class="left-sidebar left-sidebar-1">
                <div class="wrapper">
                    <div class="content">
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
                                           <a href="#" onclick="{{$mod->url_path}}" class="btn btn-default btn-flat btn-sidebar btn-sidebar-1">
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
