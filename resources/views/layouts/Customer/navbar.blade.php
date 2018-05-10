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
                        <li><a class="notification" href="#" data-target="#sendFeedbackModal" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i style="font-size: 18px; margin-top: -1px;" class="fa fa-comment fa-1x" aria-hidden="true"></i></a></li>
                        <!-- Authentication Links -->
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle profile-dropdown-admin" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="well">{{ Auth::guard('web_customer')->user()->company_name[0] }}</span> {{ Auth::guard('web_customer')->user()->company_name }}<span class="caret"></span></a>

                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Profile</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="dropdown-toggle profile-dropdown-admin" href="{{ url('/customer/logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                            Logout
                                </a>

                                <form id="logout-form" action="{{ url('/customer/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </ul>
                </div>
            </div>
        </nav>
{{-- Send Feedback modal --}}
 <div class="modal fade" id="sendFeedbackModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Send Feedback</h4>
            </div>
            {!! Form::open(['id' => 'send_feedback','data-toggle' => 'validator','role' => 'form']) !!}
                <div class="modal-body">
                    <div class="form-group has-feedback">
                     {!! Form::label('feedback_email', 'Your Email', ['class' => 'form_label']) !!}
                     <div class="input-group">
                       <span class="input-group-addon input-sm" id="basic-addon1">@</span>
                       {!! Form::email('feedback_email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required')) !!}
                     </div>
                     <div class="help-block with-errors"></div>
                   </div>
                   <div class="form-group has-feedback">
                     {!! Form::label('feedback', 'Description', ['class' => 'form_label']) !!}
                     {!! Form::textarea('feedback',$value = '', array('class' => 'form-control','placeholder' => 'Describe your issue or share your ideas','required','style'=>'resize:vertical;','rows'=>10)) !!}
                     <div class="help-block with-errors"></div>
                   </div>
                    <div class="form-group has-feedback">
                        {!! Form::label('image[]', 'Attach Screenshot', ['class' => 'form_label']) !!}
                        {!! Form::file('image[]', array('multiple'=>true,'accept'=>'image/*','class'=>'form-control TheFiles'))  !!}
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                    {!! Form::button('Send', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
                </div>
           {!! Form::close() !!}
        </div>
    </div>
</div>