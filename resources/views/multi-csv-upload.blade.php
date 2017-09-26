@extends('layouts.app')

@section('content')
<style type="text/css">
  .dropzone {
    border: none !important;
    border-radius: 5px;
    padding: 0 !important;
    background: white !important;
    min-height: 200px !important;
    width: 100%;
    margin-bottom: 20px;
    line-height: 200px;
    vertical-align: middle;
    color: #263238 ;
    text-align: center !important;
}
</style>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
              
                <div class="panel-body">
                <div class="dropzone" id="dropzoneFileUpload">
                </div>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('jqueryIncludes')
  <script type="text/javascript">
            var csrf_token     = $('meta[name="csrf-token"]').attr('content');
            Dropzone.autoDiscover = false;
             var myDropzone = new Dropzone("div#dropzoneFileUpload", { 
                 url: "multi-upload",
                 params: {
                    _token: csrf_token
                  }
             });
             Dropzone.options.myAwesomeDropzone = {
                paramName: "file", // The name that will be used to transfer the file
                maxFilesize: 2, // MB
                addRemoveLinks: true,
                acceptedFiles: ".csv",
                uploadMultiple: true,
                clickable: ".dropzone",
                // accept: function(file, done)
                // {
                //   var re = /(?:\.([^.]+))?$/;
                //   var ext = re.exec(file.name)[1];
                //   ext = ext.toUpperCase();
                //   if ( ext == "CSV" || ext == "csv") 
                //   {
                //      done();
                //   }
                //   else { done("select csv files."); }
                // },
              };
         </script>
@endsection