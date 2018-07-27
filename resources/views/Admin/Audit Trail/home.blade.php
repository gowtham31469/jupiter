@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
              <div class="top-content">
              </div>
              <div class="main-sub-menu">
                <div class="action-group pull-right sub-menu-button">
                </div>
              </div>
          <div class="clearfix"></div>
                <div class="main-content">
                    <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
                    <div class="col" id="audit-table">
                    <table id="audit-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
                      <thead>
                        <tr>
                          <th>Sl. No</th>
                          <th>User</th>
                          <th>Email</th>
                          <th>Old Values</th>
                          <th>New Values</th>
                          <th>IP address</th>
                          <th>User Agent</th>
                          <th>Date</th>
                          <th>Event</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr id="">
                          <td>1</td>
                          <td>Praveen</td>
                          <td>praveen@wizroots.com</td>
                          <td>{"price":1000}</td>
                          <td>{"price":260}</td>
                          <td>192.168.0.43</td>
                          <td>Mozilla Firefox-56.0</td>
                          <td>27-07-2018 11:08</td>
                          <td><span class="label label-success">Updated</span></td>
                         </tr>
                      </tbody>
                     </table>
                  </div>
              </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Snackbar html code --}}
    <div id="snackbar"><span class="snackbar_span"></span></div>
    @endsection
    @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif
    @section('jqueryIncludes')
    <script src="{{ asset('js/Admin/audit.js') }}"></script>
   <script type="text/javascript">
       $( document ).ready(function() {
         /* Show logo on left side*/
         $('.logo').html('Audit Trail')
         $('#audit-list-table').DataTable();
         $("#audit-list-table_filter input").addClass('form-control input-sm');
         $("#audit-list-table_filter input").attr('placeholder', 'Data Search');
         $('#audit-list-table_length select').addClass('form-control input-sm');
         $('#add_admin_form').trigger("reset");
         $('#add_admin_result').hide();
         $('.status').tooltip('show')
         $('.status').tooltip('toggle')
       });
       
    </script>
    @endsection
