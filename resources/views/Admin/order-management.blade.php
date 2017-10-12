@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<div class="col-sm-12 col-md-12 main">
    <div class="top-content">
        <ol class="breadcrumb">
  <li><a href="/admin/dashboard">Dashboard</a></li>
  <li class="active">Order Management</li>
</ol>
    <form class="navbar-form pull-right" style="margin-top:0px;">
        <label style="color: #333; margin-right: 10px;"><i class="ion-crop"></i>Select Buyer</label>
        <select class="js-example-responsive buyer-select">
                  <option></option>
                    @foreach($buyers as $buyer)
                    <option value="{{$buyer->id}}">{{$buyer->buyer_name}}</option>
                    @endforeach
              </select>
    </form>
    <h3><i class="ion-ios-browsers-outline"></i>Order Management</h3>
    <div class="main-sub-menu hidden">
        <div class="action-group pull-right">
            <!-- <a href="#" class=" btn btn-primary btn-sm"><i class="ion-upload"></i>Upload Data</a> -->
             <a href="#" id ="uploadModal" data-target="#uploadData" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-upload"></i>Upload Data</a>
        </div>
        <ul class="sub-nav-list">
             <li class="dashboard sub_modules"><a href="" ><i class="ion-grid"></i>Dashboard</a></li>
            <li class="sticker_orders sub_modules"><a href="#" onclick="sticker_orders()"><i class="ion-social-codepen"></i>Sticker Orders</a></li>
            <li class="label_orders sub_modules"><a class="" href="#" onclick="label_orders()"><i class="ion-ios-pricetag"></i>Label Orders</a></li>
        </ul><hr/>
    </div>
        </div>
    <div class="clearfix"></div>
    <div class="main-content">
        <div class="loader"></div>
        <div class="col-md-4">
            <div class="card">
                <h4 class="text-center">Total Orders</h4>
                <canvas id="myChart" width="100px" height="100px"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <h4 class="text-center">Total Orders</h4>
                <canvas id="myChart1" width="100px" height="100px"></canvas>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <h4 class="text-center">Total Orders</h4>
                <canvas id="myChart2" width="100px" height="100px"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <h4 class="text-center">Tabel Data</h4>
                <div class="table-responsive">
                    <table class="table table-striped text-center">

                        <thead>
                            <tr>
                                <th class="text-center">Product</th>
                                <th class="text-center">Payment Date</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>Product1</td>
                                <td>23/11/2013</td>
                                <td><label class="label label-warning">Pending</label></td>
                            </tr>

                            <tr>
                                <td>Product2</td>
                                <td>10/11/2013</td>
                                <td><label class="label label-success">Delivered</label></td>
                            </tr>

                            <tr>
                                <td>Product3</td>
                                <td>20/10/2013</td>
                                <td><label class="label label-info">In Call to confirm</label></td>
                            </tr>

                            <tr>
                                <td>Product4</td>
                                <td>20/10/2013</td>
                                <td><label class="label label-danger">Declined</label></td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="uploadData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Upload Data</h4>
            </div>
            <div class="modal-body" class="dropzone" id="dropzoneFileUpload">
                {!! Form::open([ 'route' => [ 'dropzone.store' ], 'files' => true, 'enctype' => 'multipart/form-data', 'class' => 'dropzone', 'id' => 'upload']) !!}
            </div>
            <div class="modal-footer">
                {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
            </div>
               {!! Form::close() !!}
        </div>
    </div>
</div>
<div class="modal fade" id="uploadMultiData" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Upload Multiple Data Files</h4>
        </div>
        <div class="modal-body" class="dropzone" id="dropzoneMultiFileUpload">
           {!! Form::open([ 'route' => [ 'dropzone.multi-store' ], 'files' => true, 'enctype' => 'multipart/form-data', 'class' => 'dropzone', 'id' => 'multi-upload','multiple'=>"true"]) !!}
        </div>
        <div class="modal-footer">
            {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
        </div>
           {!! Form::close() !!}
    </div>
</div>
</div>
<div class="modal fade" id="age_modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header alert-warning">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <p class="modal-title" id="myModalLabel">SUPPLIER IS SOLELY RESPONSIBLE TO CHOOSE CORRECT AGE GROUP. IN CASE OF ANY WRONG SELECTION, <strong>ORCHESTRA/JUPITER</strong> ARE NOT LAIBLE FOR CONSEQUENCES THERE OFF.</p>
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
<script>
      $('.buyer-select').change(function() {
          if($(this).val() != "")
          {
               sticker_orders()
          }
          else
          {
              location.reload()
          }
       });
</script>
<script src="{{ asset('js/dropzone.js') }}"></script>
<script type="text/javascript">
        Dropzone.options.imageUpload = {
            maxFilesize    :  10,
            maxFiles       :  1,
            acceptedFiles: ".csv"
        };
$('.modal').on('hidden.bs.modal', function (e) {
        $('#uploadMultiData').trigger("reset");
        $('#uploadData').trigger('reset');
       })
</script>
@endsection
