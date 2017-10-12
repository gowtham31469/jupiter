@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<style>
.main-module {
    border: 1px solid #e5e5e5;
    display: inline-block;
    padding: 5px 10px;
    margin: 2px 0px 12px;
    min-width: 350px;
    cursor: pointer;
    background: #fff;
    position: relative;
}
    .sub-module {
    display: none;
    padding: 5px 10px;
    border: 1px solid #e5e5e5;
    margin: 2px 50px 12px;
    width: 290px;
    cursor: pointer;
    border-left: 2px solid #1abb9c;
    position: relative;
}
    .sub-module2 {
    display: none;
    padding: 5px 10px;
    border: 1px solid #e5e5e5;
    margin: 2px 102px 12px;
    width: 290px;
    cursor: pointer;
    border-left: 2px solid #1abb9c;
    position: relative;
}
    .sub-module:after {
    content: "";
    position: absolute;
    border-top: 1px solid #dddddd;
    width: 22px;
    right: calc(100% + 2px);
    TOP: 50%;
}
      .sub-module2:after {
    content: "";
    position: absolute;
    border-top: 1px solid #dddddd;
    width: 22px;
    right: calc(100% + 2px);
    TOP: 50%;
}
    .modules_div:before {
    position: absolute;
    content: "";
    border-left: 1px solid #ddd;
    height: calc(100% - 53px);
    top: 26px;
    left: 27px;
    z-index: 0;
}
     .modules_div1:after {
     position: absolute;
    content: "";
    border-left: 1px solid #ddd;
    height: calc(100% - 53px);
    top: 30px;
    left: 80px;
    z-index: 0;
}
</style>
 <div class="col-sm-12 col-md-12 main">
      <div class="top-content">
        <ol class="breadcrumb">
  <li><a href="/admin/dashboard">Dashboard</a></li>
  <li class="active">Template Management</li>
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
     <h3><i class="ion-ios-flower-outline"></i>Template Management</h3>
    <div class="main-sub-menu hidden">
        
        <ul class="sub-nav-list">
             <li class="dashboard sub_modules"><a href="" ><i class="ion-grid"></i>Dashboard</a></li>
            <li class="template sub_modules"><a href="#" onclick="template_list()"><i class="ion-social-codepen"></i>Template List</a></li>
        </ul><hr/>
    </div>
        </div>
    <div class="clearfix"></div>
    <div class="main-content">
              

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
</div>
@endsection @else
<script type="text/javascript">
    window.location = "{{ url('/admin') }}";
</script>
@endif @section('jqueryIncludes')
<script>
     $('.buyer-select').change(function() {
          if($(this).val() != "")
          {
               var csrf_token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: base_url + "/admin/template-management/sub_modules",
                    type: "Post",
                    dataType:"json",
                    data: {_token:csrf_token,buyer:$(this).find("option:selected").text().toLowerCase()},
                    beforeSend: function () {
                        $(".loader").show();
                        $(".loader").css('margin-top','12%');
                    },
                    success: function (data) {
                         $('.sub-nav-list').html('<li class="dashboard sub_modules"><a href="" ><i class="ion-grid"></i>Dashboard</a></li>')
                         $.each(data, function (index, value) {
                            $('.sub-nav-list').append('<li class="'+value.module_name.toLowerCase().replace(" ", "_")+' sub_modules"><a href="#" onclick="'+value.url_path+'"><i class="'+value.image+'"></i>'+value.module_name+'</a></li>')
                        });
                         $('.main-sub-menu').removeClass('hidden');
                        
                        if(data[0].module_name == "Template List")
                            {
                                template_list();
                            }
                        
                        
                    },
                    error: function (data) {

                    },
                    complete: function () {
                        $(".loader").hide();
                        $(".loader").css('margin-top','0%');
                    }
                })
          }
          else
          {
              location.reload()
          }
     });
</script>
@endsection