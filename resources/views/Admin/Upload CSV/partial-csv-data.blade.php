@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
                <div class="top-content">
                </div>
                <div class="main-content">
                    <strong>Page 2</strong>
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
</script>
@endsection