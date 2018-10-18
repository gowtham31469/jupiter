@extends('layouts.Erp.app')
@section('navbar') 

<ul class="nav navbar-nav dynamic-menu">
    <li><a  href="#" class="active">Po Verify</a></li>
</ul>

@endsection
@section('content')
<div class="col-sm-12 col-md-12 main">
	 <div class="main-content">
        
		 
		  <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
                  <div class="col" id="customer-table">
                    <table id="po-list-table" class="table table-hover table-striped table-bordered table-responsive" style="width:100%">
                      <thead>
                        <tr>
                          <th>Sl. No</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Company</th>
                          <th>Role</th>
						  <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr id="11" role="row" class="odd">
                          	<td class="">1</td>
							  <td>Gowtham</td>
							  <td>praveen@wizroots.com</td>
							  <td>HONEYWELLCREATION</td>
							  <td>Super User</td>
							                                 <td class="sorting_1"><span class="label label-success">Active</span></td>
                                                                                      <td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Inactive" id="make_inactive" data-placement="left"></i></a>
                                                             <a href="#">
                                  <i class="fa fa-pencil filterbtn options edit-customer fa-button" title="Edit" data-placement="left"></i>
                                 </a>
                               </td>
                          </tr> <tr id="11" role="row" class="odd">
                          	<td class="">1</td>
							  <td>Gowtham</td>
							  <td>praveen@wizroots.com</td>
							  <td>HONEYWELLCREATION</td>
							  <td>Super User</td>
							                                 <td class="sorting_1"><span class="label label-success">Active</span></td>
                                                                                      <td><a href="#"><i class="fa fa-ban filterbtn options fa-button status" aria-hidden="true" title="Make Inactive" id="make_inactive" data-placement="left"></i></a>
                                                             <a href="#">
                                  <i class="fa fa-pencil filterbtn options edit-customer fa-button" title="Edit" data-placement="left"></i>
                                 </a>
                               </td>
                          </tr>
                         </tbody>
                     </table>
                  </div>
        
    </div>
</div>
@endsection
@section('jqueryIncludes')
 <script>
    
 var table =   $('#po-list-table').DataTable();
	
</script>

@endsection