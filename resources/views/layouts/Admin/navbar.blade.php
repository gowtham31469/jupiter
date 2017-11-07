<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>    
      <a class="navbar-brand grid-menu" href="/admin/dashboard"><i class="fa fa-th" aria-hidden="true"></i></a>
      <span class="navbar-brand logo" href="#"></span>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav dynamic-menu">
                     @foreach($sub_modules as $mod) @if($mod->parent_id != 0)
                        <li><a class="" href="{{$mod->url_path}}">{{$mod->module_name}}</a></li>
                    @endif @endforeach
<!--
                <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Users <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>
-->
                </ul>

                <ul class="nav navbar-nav navbar-right">
<!--                    <li><a>Buyer : </a></li>-->
                    <li class="dropdown buyer-nav-select"></li>
                    
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="well">{{ Auth::user()->name[0] }}</span> {{ Auth::user()->name }}<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Profile</a></li>
                            <li role="separator" class="divider"></li>
                             <li> <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">

                            {{ csrf_field() }}

                        </form>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
<!--
<div class="top-navigation hidden-xs hidden-sm">
    <ul class="nav-list ">
        @foreach($modules as $mod) @if($mod->parent_id == 0)
        <li class="parent-menu"><a class="" href="{{$mod->url_path}}"><i class="{{$mod->image}}"></i>{{$mod->module_name}}</a></li>
        @endif @endforeach
    </ul>
</div>
-->

<!--

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
            <a class="navbar-brand" href="#"><img src="{{ asset('images/logo.png') }}"></a>
            Jupiter Garments Accessories
        </div>
        <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
            <ul class="nav navbar-nav hidden-md hidden-lg hidden-sm">
                 @foreach($modules as $mod) @if($mod->parent_id == 0)
    
                 <li class="parent-menu"><a class="" href="{{$mod->url_path}}"><i class="{{$mod->image}}"></i>{{$mod->module_name}}</a></li>
                 @endif @endforeach
            </ul>
            <ul class="nav navbar-nav navbar-right">

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="ion-person"></i> {{ Auth::user()->name }}<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Profile</a></li>
                        <li><a href="#">Settings</a></li>
                        <li role="separator" class="divider"></li>
                        <li> <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">

                            {{ csrf_field() }}

                        </form>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="top-navigation hidden-xs hidden-sm">
    <ul class="nav-list ">
        @foreach($modules as $mod) @if($mod->parent_id == 0)
        <li class="parent-menu"><a class="" href="{{$mod->url_path}}"><i class="{{$mod->image}}"></i>{{$mod->module_name}}</a></li>
        @endif @endforeach
    </ul>
</div>
-->
