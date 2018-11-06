@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<div class="col-sm-12 col-md-12 main">
    <div class="top-content">
        <div class="buyer-nav-div hidden">
           <a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Buyer</a>
        </div>
        {{ Form::hidden('invisible', $buyer_count, array('id' => 'invisible__count_id')) }}
        <div class="clearfix"></div>
    </div>
    <div class="main-content">
        <div class="sidebar style-4">
            <h4>Select Customer</h4>
            <ul class="customer-select">
            </ul>
        </div>
    <div class="template-block"></div>
        <div class="select-buyer">
        </div>
    </div>
    <div id="snackbar"><span class="snackbar_span"></span></div>
    <!-- Buyer selection Modal -->
    <div class="modal buyer-selection-modal fade" id="buyerSelectionModalCenter" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
         <div class="modal-dialog buyer-selection-modal-dialog" role="document">
             <div class="modal-content buyer-selection-modal-content">
                    <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title buyer-modal-title" id="myModalLabel">Select a Buyer</h4>
                     </div>
                     <div class="modal-body buyer-selection-modal-body">
                        <div class="alert alert-warning" role="alert" id="hidden-buyer-div" style="display: none;margin-bottom: 5px;">
                          <strong>Warning!</strong> Select a buyer to proceed.
                        </div>
                        @foreach($buyers as $buyer)
                         <a class="buyer-card-link" style="cursor: pointer;">
                             <div class="card buyers-card elevation-2dp active-buyer" data-name ="{{$buyer->buyer_name}}"  data-value ="{{$buyer->id}}" id="{{ strtolower($buyer->buyer_name) }}">
                               <img src="{{$buyer->logo}}" class="img-fluid" alt="Responsive image" height="120" class="img-fluid float-none">
                             </div>
                         </a>
                        @endforeach
                     </div>
                     <div class="modal-footer buyer-selection-modal-footer">
                         {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                     </div>
                {!! Form::close() !!}
             </div>
         </div>
     </div>

    @endsection @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif @section('jqueryIncludes')
    <script src="{{ asset('js/Admin/pricing.js') }}"></script>
    <script>
        $(document).ready(function() {
            /* Show logo on left side */
            $('.logo').html('Customers');

            /** [ Make buyers card active ] */
            $('div.buyers-card').click(function(){
              $('div.buyers-card').removeClass("active");
              $(this).addClass("active");
              $('#hidden-buyer-div').hide();

              /* Save selected buyer into localdb */
              if ($('.buyers-card.active-buyer.active').length != 0) 
              {
                  $('#hidden-buyer-div').hide();
                  localStorage.setItem("buyer", $('.buyers-card.active-buyer.active').data("name").toLowerCase());
                  localStorage.setItem("buyer_id", $('.buyers-card.active-buyer.active').data("value"));
                  $('.selected-buyer').text('Selected Buyer : '+$('.buyers-card.active-buyer.active').data("name"))
                  $('#buyerSelectionModalCenter').modal('hide');
                  price_template_list();
              } else {
                  localStorage.removeItem("buyer");
                  localStorage.removeItem("buyer_id");
                  $('#hidden-buyer-div').show();
              }
            });

            /** [ Show already selected(active) buyer on clicking buyer menu] */
            $('.buyer-nav-menu').on('click', function() {
                if (localStorage.getItem("buyer") === null) 
                {
                   $("#buyerSelectionModalCenter").modal("show");
                   
                } 
                else 
                {
                    $('#'+localStorage.getItem("buyer")).addClass('active');
                    $("#buyerSelectionModalCenter").modal("show");
                }
            });

            /** [If only 1 buyer , auto select that buyer] */
            var buyer_count = $('#invisible__count_id').val();
            if(buyer_count <= 1) 
            {
                $('#hidden-buyer-div').hide();
                $('div.buyers-card').addClass("active");
                localStorage.setItem("buyer", $('.buyers-card.active-buyer.active').data("name").toLowerCase());
                localStorage.setItem("buyer_id", $('.buyers-card.active-buyer.active').data("value"));
                $('.selected-buyer').text('Selected Buyer : '+$('.buyers-card.active-buyer.active').data("name"))
            }   
            
            /** add buyer selection menu into navbar */
            $('.buyer-nav-menu').html($('.buyer-nav-div').html());

            /** Load selected buyer from local-storage on pageload */
            if (localStorage.getItem("buyer") === null) 
            {
                $(document).ready(function() {
                   $("#buyerSelectionModalCenter").modal("show");
                })
            } 
            else 
            {
                $('.selected-buyer').text('Selected Buyer : '+localStorage.getItem("buyer"));
                price_template_list();
            }

            /** To refresh modal while hidden **/
            $('#buyerSelectionModalCenter').on('hidden.bs.modal', function (e) {
              $('div.buyers-card').removeClass("active");
              if (localStorage.getItem("buyer") === null) 
              {
                  $(document).ready(function() {
                     $("#buyerSelectionModalCenter").modal("show");
                  })
              } 
              else 
              {
                  $('.selected-buyer').text('Selected Buyer : '+localStorage.getItem("buyer"));
                  price_template_list();
              }
             })
            
        });
    </script>
    @endsection