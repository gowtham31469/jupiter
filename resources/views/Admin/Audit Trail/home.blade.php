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
                          <th>Module</th>
                          <th>URL</th>
                          <th>Event</th>
                          <th>User</th>
                          <th>User Email</th>
                          <th>IP address</th>
                          <th>User Agent</th>
                          <th>Date & Time</th>
                          <th>Compare</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($audits as $value)
                        <tr id="{{$value->id}}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $value->module }}</td>
                        <td>{{ $value->url }}</td>
                        <td><span class="label label-success">{{ $value->event }}</span></td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->email }}</td>
                        <td>{{ $value->ip_address }}</td>
                        <td>{{ $value->user_agent }}</td>
                        <td>{{ $value->created_at }}</td>
                        <td><a href="#"><i class="fa fa-eye filterbtn options fa-button compare-audit" aria-hidden="true" title="View Changes" id="compare"></i></a></td>
                       </tr>
                       @endforeach
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

    <!-- View Changes Modal  -->
    <div class="modal view-changes-modal fade" id="ViewChangesModalCenter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         <div class="modal-dialog view-changes-modal-dialog" role="document">
             <div class="modal-content view-changes-modal-content">
                    <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title buyer-modal-title" id="myModalLabel">View Changes</h4>
                     </div>
                     <div class="modal-body view-changes-modal-body">
                      <div class="container-fluid">
                          <div class="row">
                              <!-- Card Projects -->
                              <div class="col-md-6">
                                  <div class="card">
                                      <h4 class="card-title">Old Values</h4>
                                      <hr>
                                      <div class="card-content">
                                          <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><span><strong class="old-company-name">Company Name:</strong> </span></li>
                                            <li class="list-group-item"><span><strong class="old-buyer-name">Buyer Name:</strong> </span></li>
                                            <li class="list-group-item"><span><strong class="old-template-name">Template Name:</strong> </span></li>
                                            <li class="list-group-item"><span><strong class="old-custome-name">Custom Name:</strong> </span></li>
                                            <li class="list-group-item"><span><strong class="old-moq">MOQ value:</strong></span></li>
                                          </ul>
                                          <table class="table">
                                            <thead>
                                              <tr>
                                                <th scope="col" class="">Sl. No</th>
                                                <th scope="col" class="old-volume-from">Volume From</th>
                                                <th scope="col" class="old-volume-to">Volume To</th>
                                                <th scope="col" class="old-price">Price</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              <tr>
                                                <th scope="row">1</th>
                                                <td>Mark</td>
                                                <td>Otto</td>
                                                <td>@mdo</td>
                                              </tr>
                                              <tr>
                                                <th scope="row">2</th>
                                                <td>Jacob</td>
                                                <td>Thornton</td>
                                                <td>@fat</td>
                                              </tr>
                                              <tr>
                                                <th scope="row">3</th>
                                                <td>Larry</td>
                                                <td>the Bird</td>
                                                <td>@twitter</td>
                                              </tr>
                                            </tbody>
                                          </table>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="card">
                                      <h4 class="card-title">New Values</h4>
                                      <hr>
                                      <div class="card-content">
                                          <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><span><strong class="new-company-name">Company Name:</strong> </span></li>
                                            <li class="list-group-item"><span><strong class="new-buyer-name">Buyer Name:</strong> </span></li>
                                            <li class="list-group-item"><span><strong class="new-template-name">Template Name:</strong> </span></li>
                                            <li class="list-group-item"><span><strong class="new-custome-name">Custom Name:</strong> </span></li>
                                            <li class="list-group-item"><span><strong class="new-moq">MOQ value:</strong></span></li>
                                          </ul>
                                      </div>
                                      <table class="table">
                                        <thead>
                                          <tr>
                                            <th scope="col">Sl. No</th>
                                            <th scope="col" class="new-volume-from">Volume From</th>
                                            <th scope="col" class="new-volume-to">Volume To</th>
                                            <th scope="col" class="new-price">Price</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <th scope="row">1</th>
                                            <td>Mark</td>
                                            <td>Otto</td>
                                            <td>@mdo</td>
                                          </tr>
                                          <tr>
                                            <th scope="row">2</th>
                                            <td>Jacob</td>
                                            <td>Thornton</td>
                                            <td>@fat</td>
                                          </tr>
                                          <tr>
                                            <th scope="row">3</th>
                                            <td>Larry</td>
                                            <td>the Bird</td>
                                            <td>@twitter</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                  </div>
                              </div>
                           
                          </div>
                      </div>
                     </div>
                     <div class="modal-footer view-changes-modal-footer">
                         {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                     </div>
                {!! Form::close() !!}
             </div>
         </div>
     </div>
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
         $('.compare-audit').tooltip('show')
         $('.compare-audit').tooltip('toggle')

         /** Get edit buyer details on click**/
         $(document).on('click','.compare-audit',function(e){
             var id = $(this).closest('tr').attr('id');
             e.preventDefault();
             /* call getComparisonData function from audit.js*/
             getComparisonData(id);
         });
       });
       
    </script>
    @endsection
