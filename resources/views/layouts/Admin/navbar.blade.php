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
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <!--                    <li><a>Buyer : </a></li>-->
                <li class="dropdown buyer-nav-select"></li>
                <li class="region-hidden"><a href="" class="label-text" disabled="disabled">Select Region</a></li>
                <li class="radio region-hidden">
                    <label class="radio-inline">
            <input type="radio" name="gr" value="0" onchange="val(this)">UK</label>
                    <label class="radio-inline">
            <input type="radio" name="gr" value="1" onchange="val(this)">CE</label>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle profile-dropdown-admin" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="well">{{ Auth::user()->name[0] }}</span> {{ Auth::user()->name }}<span class="caret"></span></a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="#">Profile</a></li>

                        <li><a class="dropdown-item logout-button" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
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