@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                   <p id="msg"></p>
                    {!! Form::file('files[]', array('multiple'=>true)) !!}
                    <button id="upload">Upload</button>
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
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript">
            $(document).ready(function (e) {
                $('#upload').on('click', function () {
                    var form_data = new FormData();
                    var ins = document.getElementById('multiFiles').files.length;
                    for (var x = 0; x < ins; x++) {
                        form_data.append("files[]", document.getElementById('multiFiles').files[x]);
                    }
                      alert(form_data)
                    $.ajax({
                        url: 'multi-upload', // point to server-side PHP script 
                        dataType: 'text', // what to expect back from the PHP script
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        type: 'post',
                        success: function (result) {
                            console.log(result)
                        },
                        error: function (response) {
                            alert()
                        }
                    });
                });
            });
        </script>
@endsection