<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
<B>Dear User,</B>
<br>
<table>
	<thead>
		<tr>
			<th>Customer Name </th>
			<th>ERP ID</th>
			<th>PO Number</th>
			<th>Reason</th>
		</tr>
	</thead>
	<tbody>
		@if($po != "false")
		<tr>
			<td>{{$customer_name}}</td>
			<td>{{$erp_order_id}}</td>
			<td>{{$purchase_order_number}}</td>
			<td>PO Mismatch</td>
		</tr>
		@endif
		@if($style_no != "false")
		<tr>
			<td>{{$customer_name}}</td>
			<td>{{$erp_order_id}}</td>
			<td>{{$purchase_order_number}}</td>
			<td>Style No Mismatch</td>
		</tr>
		@endif @if($reference != "false")
		<tr>
			<td>{{$customer_name}}</td>
			<td>{{$erp_order_id}}</td>
			<td>{{$purchase_order_number}}</td>
			<td>Reference No Mismatch</td>
		</tr>
		@endif @if($qty != "false")
		<tr>
			<td>{{$customer_name}}</td>
			<td>{{$erp_order_id}}</td>
			<td>{{$purchase_order_number}}</td>
			<td>Quantity Mismatch</td>
		</tr>
		@endif @if($price != "false")
		<tr>
			<td>{{$customer_name}}</td>
			<td>{{$erp_order_id}}</td>
			<td>{{$purchase_order_number}}</td>
			<td>Price Mismatch</td>
		</tr>
		@endif @if($po_complete != "false")
		<tr>
			<td>{{$customer_name}}</td>
			<td>{{$erp_order_id}}</td>
			<td>{{$purchase_order_number}}</td>
			<td>Partial PO</td>
		</tr>
		@endif @if($comment != "false")
		<tr>
			<td>{{$customer_name}}</td>
			<td>{{$erp_order_id}}</td>
			<td>{{$purchase_order_number}}</td>
			<td>{{ $commenttext }}</td>
		</tr>
		@endif
	</tbody>
</table>

