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
                    <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
                    <div class="col-md-12" id="csv-partial-table">
                    <table id="csv-partial-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
                      <thead>
                        <tr>
                          <th>Sl. No</th>
                          <th>UVC</th>
                          <th>UVC TCO</th>
                          <th>Buyer BO</th>
                          <th>Collection</th>
                          <th>Supplier Name</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                            @foreach($result_array as $key => $value)
                            <tr>
                               <td>{{ $loop->iteration }}</td>
                               <td>{{ $value['UVC'] ?? '-' }}</td>
                               <td>{{ $value['UVC_TCO'] ?? '-' }}</td>
                               <td>{{ $value['BuyerPONo'] ?? '-' }}</td>
                               <td>{{ $value['Season_Code'] ?? '-' }}</td>
                               <td>{{ $value['SupplierName'] ?? '-' }}</td>
                               @if($value['file_status'] === 'PO Details Missing')
                                   <td><span class="label label-primary">{{ $value['file_status'] }}</span></td>
                               @else
                                  <td><span class="label label-warning">{{ $value['file_status'] }}</span></td>
                               @endif
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

@endsection @else
<script type="text/javascript">
    window.location = "{{ url('/admin') }}";
</script>
<script src="{{ asset('js/Admin/orchestra-csv-upload.js') }}"></script>
@endif @section('jqueryIncludes')

<script type="text/javascript">
    $(document).ready(function() {
        $('.logo').html('Partial CSV Data');
    })

     /** [ Data Tabeles scripts ] */
     $('#csv-partial-list-table').DataTable();
     $("#csv-partial-list-table_filter input").addClass('form-control input-sm');
     $("#csv-partial-list-table_filter input").attr('placeholder', 'Data Search');
     $('#csv-partial-list-table_length select').addClass('form-control input-sm');
</script>
@endsection