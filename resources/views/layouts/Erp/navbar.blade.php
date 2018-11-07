 <nav class="navbar navbar-default navbar-fixed-top">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/erp') }}">JGA</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1"  style="margin-right: 10px;">
                @yield('navbar')
                <ul class="nav navbar-nav navbar-right">
						
					 <li class="dropdown company-nav-menu"></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle profile-dropdown-admin" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <span class="well navWell">{{ Auth::guard('erpuser')->user()->name[0] }}</span>{{ Auth::guard('erpuser')->user()->name}}<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a id="showProfileModal" href="#" data-target="#profileModal" data-toggle="modal" data-backdrop="static" data-keyboard="false">Profile</a></li>
                  
                        </ul>
                    </li>
                    <li>
                        <a class="dropdown-toggle profile-dropdown-admin" href="{{ url('/erp/logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                                    Logout
                        </a>

                        <form id="logout-form" action="{{ url('/erp/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </div>
			
            <!-- /.navbar-collapse -->
    </nav>
