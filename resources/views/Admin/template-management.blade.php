@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
 <div class="col-sm-12 col-md-12 main">
                <h3><i class="ion-ios-flower-outline"></i>Template Management</h3>

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
                    <div class = "table-responsive">
   <table class = "table table-striped text-center">
      
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
@endsection @else
<script type="text/javascript">
    window.location = "{{ url('/admin') }}";
</script>
@endif @section('jqueryIncludes')
<script>
</script>
@endsection