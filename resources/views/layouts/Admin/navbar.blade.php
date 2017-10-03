
    <!-- navbar-1 -->
    <nav class="navbar navbar-1">
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
                    <span style="padding-top: 17px;font-weight: 600;display: inline-block;margin-left:10px;">JGA Admin</span>                </a>
                <div class="dropdown-menu dropdown-menu-right from-right" id="dropdown-user">
                    <h6 class="dropdown-header dropdown-title">My account</h6>
                    <a class="dropdown-item" href="#">
                        <i class="fa fa-user"></i>
                        <span class="title">Profile</span>
                    </a>
                   
                   <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                          <i class="fa fa-sign-out"></i>
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
                <ul class="menu-bottom list-unstyled">
                @foreach($modules as $mod)
                @if($mod->parent_id == $varia)
                <li>
                    <a href="{{$mod->url_path}}">
                        <span>{{$mod->module_name}}</span>
                    </a>
                </li>
                @endif
                @endforeach
                </ul>
            </li>
        </ul>
      @endif
      @endforeach
</div>