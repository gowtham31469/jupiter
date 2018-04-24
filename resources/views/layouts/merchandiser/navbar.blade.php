<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
		  <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
            <a class="navbar-brand grid-menu" href="#"><i class="fa fa-th" aria-hidden="true"></i></a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			
          <span class="navbar-brand logo" href="#">JGA</span>
			<ul class="nav navbar-nav dynamic-menu">
             <li><a href="{{ url('/merchandiser/pending-orders') }}">Pending Orders</a></li>
             <li><a  href="{{ url('/merchandiser/past-orders') }}">Past Orders</a></li>
             </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown buyer-nav-select"></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle profile-dropdown-admin" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">JGA Merchandiser<span class="caret"></span></a>

                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="#">Profile</a></li>
                    </ul>    
                       <li> <a class="dropdown-toggle profile-dropdown-admin" href="{{ url('/merchandiser/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                        <form id="logout-form" action="{{ url('/merchandiser/logout') }}" method="POST" style="display: none;">

                            {{ csrf_field() }}

                        </form>
                    
                </li>

            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>