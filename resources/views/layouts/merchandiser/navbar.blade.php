 <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
                <a class="navbar-brand" href="#"><img src="{{ asset('images/logo.png') }}">Jupiter Garments Accessories</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
                <ul class="nav navbar-nav navbar-right">

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="ion-person"></i>JGA Merchandiser <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Profile</a></li>
                            <li><a href="#">Settings</a></li>
                            <li role="separator" class="divider"></li>
                            <li> <a class="dropdown-item" href="{{ url('/merchandiser/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                        <form id="logout-form" action="{{ url('/merchandiser/logout') }}" method="POST" style="display: none;">

                            {{ csrf_field() }}

                        </form>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
 <div class="top-navigation">
        <ul class="nav-list">
            <li class="parent-menu"><a class="" href="/merchandiser/dashboard"><i class="ion-grid"></i>Dashboard</a></li>
            <li class="parent-menu"><a class="" href="/merchandiser/order-managemnt"><i class="ion-ios-browsers-outline"></i>Order Management</a></li>
        </ul>
    </div>
