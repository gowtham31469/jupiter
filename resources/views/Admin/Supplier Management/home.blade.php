@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
              <div class="top-content">
                  <ol class="breadcrumb">
                    <li><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="active">Supplier Management</li>
                  </ol>
                <h3><i class="ion-ios-browsers-outline"></i>Supplier Management</h3>
                <ul class="sub-nav-list">
                    <li><a class="active" href="#" onClick="window.location.reload()"><i class="ion-grid"></i>Dashboard</a></li>
                    <li class="suppliers-list-view"><a class="" href="#" onclick="suppliersListView()"><i class="ion-ios-pricetag"></i>Suppliers</a></li>
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
    <div class="modal fade bd-example-modal-lg" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Supplier</h4>
                </div>
                {!! Form::open(['id' => 'add_role_form','data-toggle' => 'validator','role' => 'form']) !!}
                    <div class="modal-body">
                      <div id="role_result"></div>
                      <div class="row">
                          <div class="col-md-6">
                            <div class="col-md-12">
                              <div class="form-group has-feedback">
                                  {!! Form::label('role_id', 'Role ID', ['class' => 'form_label']) !!}
                                  {!! Form::text('role_id',$value = '', array('class' => 'form-control input-sm','placeholder' => 'Role ID','readonly')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="form-group has-feedback">
                                  {!! Form::label('role_name', 'Role Name', ['class' => 'form_label']) !!}
                                  {!! Form::text('role_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Role Name','required')) !!}
                                  <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                  <div class="help-block with-errors"></div>
                              </div>
                            </div>
                            <div class="col-md-12">{!! Form::hidden('user_edit_id',$value = null, array('id'=>'user_edit_id','class' => 'form-control input-sm')) !!}
                              <div class="form-group has-feedback">
                                {!! Form::label('role_description', 'Description', ['class' => 'form_label']) !!}
                                {!! Form::textarea('role_description',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Description','required','style'=>'resize:none;','rows'=>3)) !!}
                                <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                                <div class="help-block with-errors"></div>
                              </div>
                            </div>
                      </div>
                      <div class="col-md-6 modules_div">
                        <div class="row">
                            {!! Form::label('role_permissions', 'Role Permissions', ['class' => ' form_label']) !!}
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button('Close', ['class' => 'btn btn-default','data-dismiss' => 'modal'] ) !!}
                        {!! Form::submit('Add Role', ['class' => 'btn btn-primary'] ) !!}
                    </div>
               {!! Form::close() !!}
            </div>
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
    @endsection
