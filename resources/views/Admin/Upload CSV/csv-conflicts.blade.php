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
                <div class="col-md-12" id="csv-conflicts-table">
                <table id="csv-conflicts-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
                  <thead>
                    <tr>
                      <th>Sl. No</th>
                      <th>UVC</th>
                      <th>Collection</th>
                      <th>Supplier Name</th>
                      <th>Theme</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach($result_array as $key => $value)
                         <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><a href="javaScript:void(0);" title="view more" class="view-more" data-placement="bottom"  data-backdrop="static" data-keyboard="false" onclick="get_uvc_data(this)" data-table="{{ $value['table'] }}">{{ $value['UVC'] }}</a></td>
                            <td>{{ $value['Season_Code'] }}</td>
                            <td>{{ $value['SupplierName'] }}</td>
                            <td>{{ $value['Theme'] }}</td>
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