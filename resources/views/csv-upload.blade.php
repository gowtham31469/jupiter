@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <table class="table table-condensed add-brand-form">
                        <tr>
                            <td class="col-md-4">
                                <input type="file" name="file" id="file"/>
                            </td>
                            <td class="col-md-4">
                                <button class="btn btn-primary" id="upload_btn_id">Import CSV File</button>
                            </td>
                        </tr>
                    {{ csrf_field() }}
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('jqueryIncludes')
<script type="text/javascript">
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
  </script>
<script>
    $("#upload_btn_id").click(function ()
    {

        var file=$("#file").prop("files")[0];
            var form_data = new FormData();
            form_data.append('file', file);
            $.ajax({
                url: "/upload"
              , data: form_data
              , cache: false
              , contentType: false
              , processData: false
              , type: 'POST'
              , success: function (result)
              {      
                console.log(result);
              }
            });
    });
</script>
@endsection