@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
              <div class="top-content">
                  <ol class="breadcrumb">
                    <li><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="active">Buyer Management</li>
                  </ol>
                <h3><i class="ion-ios-browsers-outline"></i>Buyer Management</h3>
                <ul class="sub-nav-list">
                    <li><a class="active" href="#" onClick="window.location.reload()"><i class="ion-social-codepen"></i>Dashboard</a></li>
                    <li class="suppliers-list-view"><a class="" href="#" onclick="buyers()"><i class="ion-ios-pricetag"></i>Buyers</a></li>
                    <li class="suppliers-list-view"><a class="" href="#" onclick="merchandiser()"><i class="ion-ios-pricetag"></i>Merchandiser</a></li>
                </ul>
                <hr/>
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
        </div>
    </div>
     <div class="modal fade" id="addbuyerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Buyer</h4>
                </div>
                {!! Form::open(['id' => 'add_buyer_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div id="result"></div>
                        <div class="form-group has-feedback">
                          {!! Form::label('name', 'Buyer Name', ['class' => 'form_label']) !!}
                          {!! Form::text('name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required')) !!}
                          <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('email', 'Buyer Email', ['class' => 'form_label']) !!}
                          <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">@</span>
                            {!! Form::email('email',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Email','required')) !!}
                            <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                          </div>
                          <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('phone', 'Buyer Phone', ['class' => 'form_label']) !!}
                          <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">+91</span>
                            {!! Form::text('phone',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Phone','data-minlength' => '10','data-maxlength' => '10','required','onkeypress' => 'return isNumber(event)')) !!}
                            <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                         </div>
                         <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                          {!! Form::label('address', 'Buyer Address', ['class' => 'form_label']) !!}
                          {!! Form::textarea('address',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Address','required','style'=>'resize:none;','rows'=>3)) !!}
                          <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                          <div class="help-block with-errors"></div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Add Buyer', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
        </div>
    </div>
    @endsection
    @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif
    @section('jqueryIncludes')
   <script type="text/javascript">
        function buyers()
        {
           $('.main-sub-menu').removeClass('hidden');
           $( ".sub_modules a" ).removeClass( "active" )
           $('.sticker_orders a').addClass("active")
           $('.main-content').html('<div class="loader"></div>');
           var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
           var csrf_token = $('meta[name="csrf-token"]').attr('content');
           $.ajax({
                url: base_url + "/admin/buyer-management/buyers",
                type: "get",
                dataType:"json",
                data: {_token:csrf_token},
                beforeSend: function () {
                    $(".loader").show();
                    $(".loader").css('margin-top','12%');
                },
                success: function (data) {
                    $('.top-content').html('<ol class="breadcrumb"><li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">Buyer Management</a></li><li class="active">Users</li></ol><h3><i class="ion-ios-browsers-outline"></i>Buyer Management</h3><div class="main-sub-menu"><div class="action-group pull-right"><a href="#" data-target="#addbuyerModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false"><i class="ion-person-add"></i>Add Buyer</a></div><ul class="sub-nav-list"><li><a href=""><i class="ion-social-codepen"></i>Dashboard</a></li><li class="users-list-view"><a class="active" href="#" onclick="buyers()"><i class="ion-ios-pricetag"></i>Buyers</a></li><li class="roles-list-view"><a class="" href="#" onclick="merchandiser()"><i class="ion-ios-pricetag"></i>Merchandiser</a></li></ul><hr/></div>');
                     $('.main-content').html(table(data));
                     $('#datatable-example-2').DataTable();
                     $("#datatable-example-2_filter input").addClass('form-control input-sm');
                     $("#datatable-example-2_filter input").attr('placeholder', 'Data Search');
                      $('#datatable-example-2_length select').addClass('form-control input-sm');
                     $('.breadcrumb').html('<li><a href="/admin/dashboard">Dashboard</a></li><li><a href="">Buyer Management</a></li><li class="active">Buyers</li>')
                },
                error: function (data) {

                },
                complete: function () {
                    $(".loader").hide();
                    $(".loader").css('margin-top','0%');
                }
            })
        }
        function merchandiser()
        {
            alert()
        }
        $('#add_buyer_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented())
        {

        }
        else
        {
           e.preventDefault();
           addBuyer();
        }
      })
    </script>
    @endsection
