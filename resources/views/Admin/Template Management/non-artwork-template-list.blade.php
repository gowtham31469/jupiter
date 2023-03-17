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
        <div class="buyer-nav-div hidden">
           <a class="dropdown-toggle profile-dropdown-admin selected-buyer" style="text-transform:capitalize;cursor: pointer;">Select Buyer</a>
        </div>
        {{ Form::hidden('invisible', $buyer_count, array('id' => 'invisible__count_id')) }}
        <div class="clearfix"></div>
    </div>
    {{-- <div class="main-sub-menu">
        <div class="action-group pull-right sub-menu-button">
          <a href="#" data-target="#addTemplateModal" class=" btn btn-primary btn-sm" data-toggle="modal" data-backdrop="static" data-keyboard="false">
            <i class="fa fa-plus"></i> Add Template</a>
        </div>
      </div> --}}
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

    {{-- Add a new template --}}
    <!-- Modal -->
    <div class="modal fade" id="addTemplateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addTemplateModal"><b>Add Template</b></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="">
              <div class="row">
                  {!! Form::open(['id' => 'add_template_form','data-toggle' => 'validator','role' => 'form']) !!}
                  <div class="modal-body">
                      <!-- <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback">
                        {!! Form::label('template_name', 'Template Name :', ['class' => 'form_label pull-left']) !!}
                        {!! Form::text('template_name',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Name','required','maxlength' => '25', 'style' => 'text-transform: capitalize;')) !!}
                        <div class="help-block with-errors"></div>
                      </div> -->
                      <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback">
                        {!! Form::label('reference_number', 'Reference Number :', ['class' => 'form_label pull-left']) !!}
                          {!! Form::text('reference_number',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Ref.No', 'maxlength' => '50')) !!}
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback">
                        {!! Form::label('company_new', 'Select Company :', ['class' => 'form_label pull-left']) !!}
                        {!! Form::select('company_new',  $value=$company, null, ['class' => 'form-control input-sm']) !!}
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback">
                        {!! Form::label('tax_new', 'Taxation Value (%) :', ['class' => 'form_label pull-left']) !!}
                          {!! Form::select('tax_new', $value= $tax, null, ['class' => 'form-control input-sm']) !!}
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback">
                        {!! Form::label('material_new', 'Material :', ['class' => 'form_label pull-left']) !!}
                          {!! Form::select('material_new', $value= $material, null, ['class' => 'form-control input-sm']) !!}
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback">
                        {!! Form::label('gl_text_new', 'GL Text :', ['class' => 'form_label pull-left']) !!}
                        {!! Form::text('gl_text_new',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'GL Text','required','maxlength' => '25', 'style' => 'text-transform: capitalize;')) !!}
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback">
                        {!! Form::label('gl_code_new', 'GL Code :', ['class' => 'form_label pull-left']) !!}
                        {!! Form::number('gl_code_new',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'GL Code','required','maxlength' => '25', 'style' => 'text-transform: capitalize;', 'onkeypress'=> 'return isNumber(event)')) !!}
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback">
                        {!! Form::label('color_new', 'Color :', ['class' => 'form_label pull-left']) !!}
                        {!! Form::text('color_new',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Color','required','maxlength' => '25', 'style' => 'text-transform: capitalize;')) !!}
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback">
                        {!! Form::label('hsn_code_new', 'HSN Code :', ['class' => 'form_label pull-left']) !!}
                        {!! Form::number('hsn_code_new',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'HSN Code','required','maxlength' => '25', 'style' => 'text-transform: capitalize;')) !!}
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback">
                        {!! Form::label('dimension_height_new', 'Height (mm): ', ['class' => 'form_label pull-left']) !!}
                        {!! Form::number('dimension_height_new',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Height','required','maxlength' => '25', 'style' => 'text-transform: capitalize;')) !!}
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback">
                        {!! Form::label('dimension_width_new', 'Width (mm) :', ['class' => 'form_label pull-left']) !!}
                        {!! Form::number('dimension_width_new',$value = '', array('class' => 'form-control input-sm ','placeholder' => 'Width','required','maxlength' => '25', 'style' => 'text-transform: capitalize;')) !!}
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="form-group col-xs-10 col-sm-4 col-md-4 col-lg-4 has-feedback">
                        {!! Form::label('name_in_reports_new', 'Name in reports :', ['class' => 'form_label pull-left']) !!}
                          {!! Form::select('name_in_reports_new', $value=$name_in_reports, null, ['class' => 'form-control input-sm']) !!}
                        <div class="help-block with-errors"></div>
                      </div>
                      <div class="clearfix"></div>
                  </div>
                  <div class="modal-footer">
                      {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                      {!! Form::button('Submit', ['class' => 'btn btn-primary btn-sm','type' => 'submit'] ) !!}
                  </div>
                  {!! Form::close() !!}
              </div>
            </div>
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
                             <div class="buyers-card elevation-2dp active-buyer" data-name ="{{$buyer->buyer_name}}"  data-value ="{{$buyer->id}}" id="{{ strtolower($buyer->buyer_name) }}">
                               <img src="{{$buyer->logo}}" class="img-fluid" alt="Responsive image" height="120" class="img-fluid float-none">
                             </div>
                         </a>
                        @endforeach
                     </div>
                     <!-- <div class="modal-footer buyer-selection-modal-footer">
                         {!! Form::button('Close', ['class' => 'btn btn-default btn-sm','data-dismiss' => 'modal'] ) !!}
                     </div> -->
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



{{-- Edit Template details popup --}}

<div class="modal" id="tempModal">
  <div class="modal-dialog">
    <div class="modal-content" style="width:160%;">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title text-center">Template Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <label>Template_name : </label><input type="text" class="form-control" id="caption1">
          </div>
           <div class="col-md-12">
            <label>Template_description : </label><input type="text" class="form-control" id="caption2">
          </div>
        </div>
        <div class="row">
          <div class="col-md-11">
            <label>Storage Path</label>
            <input type="text" class="form-control" id="_path">
          </div>
          <div class="col-md-1">
            <button class="btn btn-info" id="preview" style="position: absolute;top: 22px;right: 14px;">Test</button>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12" id="img_preview">
            
          </div>
        </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" id="_update">Update</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="spectrraMetadataModal" role="dialog">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Update Metadata</h4>
          </div>
          <div class="modal-body spectrra-metadata-modal-body">
            <div class="container-fluid">
              <span id="spectrra_template_result"></span>
              {!! Form::open(['method' => 'post', 'id' => 'spectrra_metadata_form','data-toggle' => 'validator','role' => 'form']) !!}
              @include('Admin.Template Management.spectrra-metadata')
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              {{ Form::submit('Save', array('class' => 'btn btn-primary btn-sm pull-right', 'id' => 'update_spectrra_metadata_btn')) }}
          </div>
          {!! Form::close() !!}
      </div>
  </div>
</div>


  <!--  Modal -->
  <div class="modal new-buyer-selection-modal fade" id="newBuyerSelectionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog  new-buyer-selection-modal-dialog" role="document">
        <div class="modal-content  new-buyer-selection-modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title buyer-modal-title" id="myModalLabel">Select a different buyer</h4>
                </div>
                <div class="modal-body  new-buyer-selection-modal-body">
                    <div class="alert alert-warning" role="alert" id="hidden-buyer-div">
                      <strong>Warning!</strong> Non artwork template isn't present for this buyer.
                    </div>
                </div>
                <div class="modal-footer  new-buyer-selection-modal-footer">
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
    <script src="{{ asset('js/Admin/non-artwork-template.js') }}"></script>
    <script src="{{ asset('js/Admin/spectrra-template-metadata.js') }}"></script>
    <script src="{{ asset('js/common/spectrra-metadata-dropdown.js') }}"></script>
    <script>
      $(document).on('click', '#preview', function(event) {
        event.preventDefault();
        /* Act on the event */
        $("#img_preview").html('<hr><img src="'+$("#_path").val()+'" alt="Incorrect Path" style="height: 250px;width: 200px;margin-left: 36%;">')
      });

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
          var id_buyer = localStorage.getItem("buyer").replace("&", "\\&");
                    $('#'+id_buyer).addClass('active');
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
        $(".featuresDiv").append(`<div class="row form-group">
                {{ Form::textarea('edit_details[]', null, ['class' => 'form-control input-sm','rows' => '3', 'cols'=>'25', 'placeholder' => 'Enter details', 'style' => 'resize:none;' ,'required']) }}
                  
        </div>`);
        });
        $('.modal').on('hidden.bs.modal', function (e) {
            $(this)
                .find("input[type=textarea]").val('').end();
        })

      /** Add template bootstrap form validation **/
      $('#add_template_form').validator().on('submit', function (e) {
        e.preventDefault();
        selectedIndex = $("#tree1").jstree("get_selected", true);
        add_template(selectedIndex[0].id);
      })
     </script>
    @endsection