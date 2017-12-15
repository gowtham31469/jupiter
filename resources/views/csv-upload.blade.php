@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    <div class="col-md-12" class="dropzone" id="dropzoneFileUpload">
                         {!! Form::open(['files' => true, 'enctype' => 'multipart/form-data', 'class' => 'dropzone', 'id' => 'image-upload' ]) !!}
                         {!! Form::close() !!}
                    </div>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('jqueryIncludes')
<script type="text/javascript">
    var csrf_token        = $('meta[name="csrf-token"]').attr('content');
    var myAwesomeDropzone = new Dropzone("div#dropzoneFileUpload", {
         url: "multi-upload",
         params: {
            _token: csrf_token
          }
    });
    Dropzone.options.imageUpload = {
      maxFilesize    :  10,
      maxFiles       :  1,
      acceptedFiles  : ".csv"
    };
</script>
@endsection
