@extends('layouts.Admin.app')
@if (Auth::guard("web")->user())
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-md-12 main">
              <div class="top-content">
<!--
                  <ol class="breadcrumb">
                    <li class="active">Dashboard</li>
                  </ol>
-->
              </div>
          <div class="clearfix"></div>
                <div class="main-content">
                  <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
                  <div class="col-md-3">
                      <div class="card">
                          <h4>Total Orders</h4>
                          <span class="numbers">26004</span>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="card">
                          <h4>Open Orders</h4>
                          <span class="numbers">20</span><span class="sub-number">/140</span>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="card">
                          <h4>Total Orders</h4>
                          <span class="numbers">26004</span>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="card">
                          <h4>Total Orders</h4>
                           <span class="numbers">20</span><span class="sub-number">/140</span>
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
    <script src="{{ asset('js/Admin/buyers.js') }}"></script>
   <script type="text/javascript">
       $( document ).ready(function() {
        /* Show logo on left side */
         $('.logo').html('Buyers & Merchandisers')
         $('#add_buyer_form').trigger("reset");
         $('#edit_buyer_form').trigger('reset');
         $('#add_merchandiser_form').trigger('reset');
         $('#edit_merchandiser_form').trigger('reset');
         $('#buyer_result').hide();
         $('#edit_buyer_result').hide();
         $('#delete_buyer_result').hide();
         $('#add_merchandiser_result').hide();
         $('#edit_merchandiser_result').hide();
       });
    </script>
    @endsection
