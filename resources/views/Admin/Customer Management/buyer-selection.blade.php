@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<style type="text/css">
.buyer-selection-modal-dialog {
      width: 100%;
      height: calc(100% - 20px);
      padding: 0;
      margin:0;
      overflow-y: initial !important
}

.buyer-selection-modal-content {    
      height: calc(100% - 20px);
      border-radius: 0;
      color:white;
      overflow:auto;
      margin: 15px 15px 15px 15px;
}

.buyer-selection-modal-footer {
  border-radius: 0;
  bottom:0px;
  position:absolute;
  width:100%;
}
.buyer-selection-modal-body{
    max-height: calc(100vh - 157px);
    overflow-y: auto;
}
/* 2, 3, 4, 6, 8, or 16 */

.elevation-2dp {
    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .14), 0 3px 1px -2px rgba(0, 0, 0, .2), 0 1px 5px 0 rgba(0, 0, 0, .12);
}

.buyers-card {
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    font-size: 16px;
    font-weight: 400;
    min-height: 150px;
    overflow: hidden;
    width: 283px;
    z-index: 1;
    position: relative;
    background: #fff;
    border-radius: 2px;
    box-sizing: border-box;
    margin: 0 20px 40px;
    float: left;
    height: 150px;
}
.buyer-modal-title
{
    font-weight: bold;
    font-size: 16px;
    color: black;
}

/* Style the active class (and buttons on mouse-over) */
.active-buyer.active {
  border: solid;
  border-color: #273339;
}
.buyers-card:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 20px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06);
    transition: transform 0.7s;
    }
.buyers-card.active-buyer.active:hover {
    transform: scale(1.00);
    transition: transform 0.7s;
}
</style>
<div class="col-sm-12 col-md-12 main">
    <div class="top-content">
        <div class="buyer-nav-div">
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
                         {!! Form::button('Proceed', ['class' => 'btn btn-primary btn-sm proceed-btn','type' => 'submit'] ) !!}
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
                $('.selected-buyer').text($('.buyers-card.active-buyer.active').data("name"))
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
                $('.selected-buyer').text(localStorage.getItem("buyer"));
                price_template_list();
            }

            /** [ Select buyer on clicking proceed button] */
            $('.proceed-btn').on('click', function() {
                
                if ($('.buyers-card.active-buyer.active').length != 0) 
                {
                    $('#hidden-buyer-div').hide();
                    localStorage.setItem("buyer", $('.buyers-card.active-buyer.active').data("name").toLowerCase());
                    localStorage.setItem("buyer_id", $('.buyers-card.active-buyer.active').data("value"));
                    $('.selected-buyer').text($('.buyers-card.active-buyer.active').data("name"))
                    $('#buyerSelectionModalCenter').modal('hide');
                    price_template_list();
                } else {
                    localStorage.removeItem("buyer");
                    localStorage.removeItem("buyer_id");
                    $('#hidden-buyer-div').show();
                }
            });

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
                  $('.selected-buyer').text(localStorage.getItem("buyer"));
                  price_template_list();
              }
             })
            
        });
    </script>
    @endsection