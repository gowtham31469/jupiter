@extends('layouts.Admin.app') @if (Auth::guard("web")->user()) @section('content')
<style>
    .jstree-leaf>.jstree-anchor>.fa-folder:before {
        content: "\f02b";
    }

    .force-overflow {
        min-height: 450px;
    }

    #style-4::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        background-color: #F5F5F5;
    }

    #style-4::-webkit-scrollbar {
        width: 5px;
        background-color: #F5F5F5;
    }

    #style-4::-webkit-scrollbar-thumb {
        background-color: #000000;
        border: 1px solid #555555;
    }

    p.delta {
        display: inline-block;
        margin-right: 30px;
    }
    .buyer-selection-modal-dialog {
          width: 100%;
          height: calc(100% - 20px);
          padding: 0;
          margin:0;
          overflow-y: initial !important
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
    <div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>
    <div class="main-content">
         <div class="select-buyer">
            
        </div>

    </div>

    <div class="modal fade" id="age_modal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header alert-warning">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <p class="modal-title" id="myModalLabel">SUPPLIER IS SOLELY RESPONSIBLE TO CHOOSE CORRECT AGE GROUP. IN CASE OF ANY WRONG SELECTION, <strong>ORCHESTRA/JUPITER</strong> ARE NOT LAIBLE FOR CONSEQUENCES THERE OFF.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal needed_modal fade" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header alert-danger">
                    <button type="button" class="close modal_close1" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <p>Use this column only if needed</p>
                </div>
            </div>
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

     {{-- More Details modal --}}
<div class="modal fade" id="moreDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        {!! Form::open(['class' => 'form-horizontal', 'method' => 'post', 'id' => 'update-er-package', 'enctype' => 'multipart/form-data', 'url' => 'admin/tesco/update-notes']) !!}
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">More Details</h4>
            </div>
            <div class="modal-body">
                <h5>
                    <div class="row">
                       <input name="template_id" id="template_id" value="" class="form-control hidden" >
                        <div class="col-sm-12 col-md-12">
                            <div class="col-md-10 featuresDiv">
                             
                             
                            </div>
                            <div class="col-md-2">
                              <button type="button" class="btn btn-primary raise_btn addNew"> + Add</button>
                            </div>
                        </div>
                      
                    </div>
                </h5>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default btn-sm">Cancel</button>
                {{ Form::submit('Save', array('class' => 'btn btn-primary btn-sm pull-right')) }}
            </div>
        </div>
          {!! Form::close() !!}
    </div>
</div>
    @endsection @else
    <script type="text/javascript">
        window.location = "{{ url('/admin') }}";
    </script>
    @endif @section('jqueryIncludes')
    <script src="{{ asset('js/Admin/template.js') }}"></script>
    <script>
        $(document).ready(function() {
            /* Show logo on left side */
            $('.logo').html('Templates');

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
                  template_list();
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
                template_list();
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
                  template_list();
              }
             })
            
        });

         $(document).on('click', '.addNew', function() {
        $(".featuresDiv").append(`<div class="form-group">
                {{ Form::textarea('edit_details[]', null, ['class' => 'form-control input-sm','rows' => '3', 'cols'=>'25', 'placeholder' => 'Enter details', 'style' => 'resize:none;']) }}
        </div>`);
        });
         $('.modal').on('hidden.bs.modal', function (e) {
            $(this)
                .find("input[type=textarea]").val('').end();
            
        })
     </script>
    @endsection