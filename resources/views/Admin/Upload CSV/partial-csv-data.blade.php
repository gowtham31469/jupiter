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
                    <div class="col-md-12" id="csv-partial-table">
                    <table id="csv-partial-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
                      <thead>
                        <tr>
                          <th>Sl. No</th>
                          <th>UVC</th>
                          <th>UVC TCO</th>
                          <th>Buyer BO</th>
                          <th>Season Code</th>
                          <th>Supplier Name</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
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
    $(function () {
    $('.logo').html('Partial CSV Data');
    var table = $('#csv-partial-list-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('partial-csv-data') }}",
        columns: [
            { data: 'DT_Row_Index', name: 'DT_Row_Index', orderable: false, searchable: false },
            {data: 'UVC', name: 'UVC'},
            {data: 'UVC_TCO', name: 'UVC_TCO'},
            {data: 'BuyerPONo', name: 'BuyerPONo'},
            {data: 'Season_Code', name: 'Season_Code'},
            {data: 'SupplierName', name: 'SupplierName'},
            {data: 'Status', name: 'Status', orderable: false, searchable: false}
        ]
    });

    });
</script>
@endsection