<B>HI,</B>
<p>The following things are the mismatch found by the po verifier :</p>
@if($po != "false")
<p> PO Mismatch </p>
@endif	

@if($style_no != "false")
<p> Style No Mismatch </p>
@endif	

@if($reference != "false")
<p> Reference No Mismatch </p>
@endif	

@if($qty != "false")
<p> Quantity Mismatch </p>
@endif	

@if($price != "false")
<p> Price Mismatch </p>
@endif	

@if($po_complete != "false")
<p> Partial Po </p>
@endif	

@if($comment != "false")
<p> {{ $commenttext }} </p>
@endif	