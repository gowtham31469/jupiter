@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')

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
                <div class="loader" style="display:none;">
                    <div class=" fa-3x">
                        <i class="fa fa-spinner fa-pulse"></i>
                    </div>
                </div>
                <div class="col-md-12" id="csv-conflicts-table">
                <table id="csv-conflicts-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
                  <thead>
                    <tr>
                      <th>Sl. No</th>
                      <th>UVC</th>
                      <th>Collection</th>
                      <th>Supplier Name</th>
                      <th>Theme</th>
                      <th>Changes</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach($result_array as $key => $value)
                         <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><a href="javaScript:void(0);" title="view more" class="view-more" data-placement="bottom"  data-backdrop="static" data-keyboard="false" onclick="getUvcData('{{ $value['table'] }}', '{{ $value['UVC'] }}')" data-table="{{ $value['table'] }}">{{ $value['UVC'] ?? '-' }}</a></td>
                            <td>{{ $value['Season_Code'] ?? '-' }}</td>
                            <td>{{ $value['SupplierName'] ?? '-' }}</td>
                            <td>{{ $value['Theme'] ?? '-' }}</td>
                            <td>{{ $value['Changes'] ?? '-' }}</td>
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
                              <div class="card old-values-card">
                                  <h4 class="card-title">Old Values</h4>
                                  <hr>
                                  <div class="card-content old-values-card-content">
                                      
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="card new-values-card">
                                  <h4 class="card-title">New Values</h4>
                                  <hr>
                                  <div class="card-content new-values-card-content">
                                      
                                  </div>
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
@endsection @else
<script type="text/javascript">
    window.location = "{{ url('/admin') }}";
</script>
@endif 
@section('jqueryIncludes')
<script src="{{ asset('js/Admin/orchestra-csv-upload.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.logo').html('CSV Conflicts');

        /** [ Data Tabeles scripts ] */
        $('#csv-conflicts-list-table').DataTable();
        $("#csv-conflicts-list-table_filter input").addClass('form-control input-sm');
        $("#csv-conflicts-list-table_filter input").attr('placeholder', 'Data Search');
        $('#csv-conflicts-list-table_length select').addClass('form-control input-sm');
    })
</script>
@endsection