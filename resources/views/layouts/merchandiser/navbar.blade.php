<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <span class="navbar-brand logo" href="#">Merchandiser</span>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown buyer-nav-select"></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle profile-dropdown-admin" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="well">JGA Merchandiser</span> <span class="caret"></span></a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="#">Profile</a></li>

                       <li> <a class="dropdown-item" href="{{ url('/merchandiser/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                        <form id="logout-form" action="{{ url('/merchandiser/logout') }}" method="POST" style="display: none;">

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