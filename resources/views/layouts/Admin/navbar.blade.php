
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
                   <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                          <i class="sli-power"></i>
                          <span class="title">Logout</span>
                   </a>
                   <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">

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
      @foreach($modules as $mod)
      @if($mod->parent_id == 0)
        <ul class="menu-bottom list-unstyled">
           
                    <li class="level-1">
                       <a href="{{$mod->url_path}}">
                        <span class="modName">{{$mod->module_name}}</span>
                        <?php $varia = $mod->module_id?>
                        </a>
                    </li>
        </ul>
      @endif
            @endforeach
</div>