@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                 {{--    <div class="col-md-12" class="dropzone" id="dropzoneFileUpload">
                         {!! Form::open(['files' => true, 'enctype' => 'multipart/form-data', 'class' => 'dropzone', 'id' => 'image-upload' ]) !!}
                         {!! Form::close() !!}
                    </div> --}}
                    <form action="/" method="post" class="dropzone" id="my-awesome-dropzone"></form>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('jqueryIncludes')
<script type="text/javascript">
    // var csrf_token        = $('meta[name="csrf-token"]').attr('content');
    // var myAwesomeDropzone = new Dropzone("#dropzoneFileUpload", {
    //      url: "multi-upload",
    //      params: {
    //         _token: csrf_token,
    //         acceptedFiles:".csv"
    //       }
    // });
    // Dropzone.options.imageUpload = {
    //   maxFilesize    :  10,
    //   maxFiles       :  1,
    //   acceptedFiles  : 'text/csv'
    // };

     Dropzone.options.myAwesomeDropzone = {
        maxFilesize: 10,
        maxFiles:1,
        //acceptedFiles: ".csv, .xlsx, .xls",
        init: function() {
          this.on("maxfilesexceeded", function(file) {
            this.removeAllFiles();
            this.addFile(file);
          });
        } 
      };
</script>
@endsection
