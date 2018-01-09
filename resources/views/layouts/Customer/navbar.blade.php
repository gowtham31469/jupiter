<nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand grid-menu" href="/customer/dashboard"><i class="fa fa-th" aria-hidden="true"></i></a>   
                    <span class="navbar-brand logo" href="#">JGA</span>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav dynamic-menu">
                        <li><a  href="{{ url('/customer/dashboard') }}">Dashboard</a></li>
                        <li><a  href="{{ url('/customer/create-order') }}">Create Order</a></li>
                        <li><a  href="{{ url('/customer/order-status') }}">Order Status</a></li>
                        <li><a  href="{{ url('/customer/reorder') }}">ReOrder</a></li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown buyer-nav-select"></li>
                        <!-- Authentication Links -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle profile-dropdown-admin" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="well">{{ Auth::guard('web_customer')->user()->company_name[0] }}</span> {{ Auth::guard('web_customer')->user()->company_name }}<span class="caret"></span></a>

                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Profile</a></li>
                                    <li>
                                        <a href="{{ url('/customer/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                    Logout
                                        </a>

                                        <form id="logout-form" action="{{ url('/customer/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                    </ul>
                </div>
            </div>
        </nav>