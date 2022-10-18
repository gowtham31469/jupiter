
/**
 * This Function is used to get label order details.
 *  
 * @return View
 */

function label_order_details(uvc,user_type,season_code) {
    var search_string = $('.dataTables_filter input').val();
    localStorage.setItem("search_string", search_string);
    // var buyer = $('.buyer-select').find("option:selected").text().toLowerCase();
    // var buyer_id=$('.buyer-select').val()
    var buyer = localStorage.getItem("buyer");
    var buyer_id = localStorage.getItem("buyer_id");
    
    $.ajax({
        url: base_url + "/"+user_type+"/" + buyer + "/order-management/label-order-details",
        type: "Post",
        dataType: "json",
        data: {
            _token: csrf_token,
            uvc: uvc,
            season_code:season_code
        },
        beforeSend: function () {
            $(".loader").show();
        },
        success: function (data) {

            var Activity_Label = data.order_details[0].Activity_Label;
			if(data.customers.length > 0)
			{
                $('.season_code-nav-menu').css('display','none')
                $('li.buyer-nav-menu').addClass('hidden');
				$('.main-content').html('<div class="loader"><div class=" fa-3x"><i class="fa fa-spinner fa-pulse"></i></div></div>');
				localStorage.setItem("label_order_data", JSON.stringify(data));
                orchestra_label_order_details(buyer, buyer_id, uvc, data)
                hangtagmodal(data,user_type);
                specialtags(data,user_type);
                heatTransfer(data,user_type);
                mainlabels(data,user_type);
                sizelabels(data,user_type);
                var wcl_already_exists=[];
                var check_count=0;
			
            if (data.orchestra_wash_care_label_orders.length > 0) {
                $.each(data.orchestra_wash_care_label_orders, function (index, value) {
					
						if(wcl_already_exists.includes(value.color_code) == true)
						{
							var wash_enable = "display:none !important";
						}
						else
						{
							var wash_enable = "";
							wcl_already_exists.push(value.color_code);	
						}
						
                        if(data.orchestra_wash_care_label_orders != "")
                        {
                            var str=data.orchestra_wash_care_label_orders[index].status;
                            $wash_status=str.replace(/ /g, "");
                            $wash_status_text=str;
                        }
                        else
                        {
                            $wash_status="";
                        }
					//<p class="pull-right delete-wash' + index + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p>
                        $('#add_modal').append('<div class="col-md-4" ><input type="hidden" value="'+value.id +'" id="delete_id'+index+'"><div class="card add-card wash-card'+index+'" style="height: 280px;" data-toggle="modal" data-target=".washcare_label_' + index + '" data-backdrop="static" data-keyboard="false">  <span class="label label-'+$wash_status+' label-status">'+$wash_status_text+'</span>   <div class="card-title"><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + index + '>View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i> </p> <h5><b class="washlabelname' + index + '">' + value.template_name + " - Washcare Label" + '</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="washcare_label_card'+index+'" style="'+wash_enable+';cursor:pointer;display: inline-block;"><i class="fa fa-plus" aria-hidden="true"></i> Add washcare label</p><p id="clone_washcare_label_card'+index+'" style="display: inline-block;float: right;cursor:pointer"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
                        
					
    					$('#washcare_label_card'+index).on('click', function (e) {

        if ($('.washcarename').val() != "") {
          
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"> <span></span>   <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare,value.color_code,user_type,Activity_Label);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare,user_type)

                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
				
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label added successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare,value.color_code,"new",user_type);
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
		
    });
                                 
    					$('#clone_washcare_label_card'+index).on('click', function (e) {

        if ($('.washcarename').val() != "") {
          
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"><span></span>    <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare,value.color_code,user_type,Activity_Label);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare,user_type)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
           		
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label cloned successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare,value.color_code,"new",user_type);
            $('#washcarelabelform'+washcare).find('#wcl_name'+washcare).val($('#washcarelabelform'+index).find('#wcl_name'+index).val())
            $('#washcarelabelform'+washcare).find('#ocn_number_wash'+washcare).val($('#washcarelabelform'+index).find('#ocn_number_wash'+index).val())
           
            
            $('.washing_select'+washcare).selectpicker('val',$('.washing_select'+index).selectpicker('val'));
            $('.whitening_select'+washcare).selectpicker('val',$('.whitening_select'+index).selectpicker('val'));
            $('.dryer_select'+washcare).selectpicker('val',$('.dryer_select'+index).selectpicker('val'));
            $('.ironing_select'+washcare).selectpicker('val',$('.ironing_select'+index).selectpicker('val'));
            $('.cleaning_select'+washcare).selectpicker('val',$('.cleaning_select'+index).selectpicker('val'));
            $('.made_in_select'+washcare).selectpicker('val',$('.made_in_select'+index).selectpicker('val'));
			
			//$('#washcarelabelform' + washcare+' .washonesizein').val($('#washcarelabelform' + index+' .washonesizein').val())
			//$('#washcarelabelform' + washcare+' .washonesizecm').val($('#washcarelabelform' + index+' .washonesizecm').val())
			$('.care_instruction_select'+washcare).selectpicker('val',$('.care_instruction_select'+index).selectpicker('val'));

			$('.hiddencis'+ index + ' option:selected').each(function () {

				$('select.hiddencis' + washcare).append('<option data-id="' + $(this).data('id') + '" data-french="' + $(this).data('french') + '" title="'+$(this).data('english')+'" data-english="' + $(this).data('english') + '" data-spanish="' + $(this).data('spanish') + '" data-portuguese="' + $(this).data('portuguese') + '" data-italian="' + $(this).data('italian') + '" data-dutch="' + $(this).data('dutch') + '" data-german="' + $(this).data('german')  + '" data-arabic="' + $(this).data('arabic') + '" data-greek="' + $(this).data('greek') + '" value="' + $(this).data('id') + '" selected>' + $(this).data('english') + '</option>')
				$('.hiddencis' + washcare).selectpicker('refresh');

			});
			
			//$('.hiddencis'+washcare).selectpicker('val',$('.hiddencis'+index).selectpicker('val'));
            //alert()
            $('#composition_remove_qty'+washcare).html($('#composition_remove_qty'+index).html())
            
            $('#composition_remove_qty'+index+' tr').each(function (ind,val) {
                var values= $(this).find('select').find(':selected').val()
                var v= $(this).attr('id');
                $('#composition_remove_qty'+washcare+' tr').each(function (inds,vals) {
                    if($(this).attr('id') == v)
                    {
                        $(this).find('select').val(values)
                        $(this).find('a').removeAttr('onclick')
                        $(this).find('a').attr('onclick','deleteComposition(this,'+washcare+')')
                    }
                });
            }); 
            
            $('#washcarelabelform'+index+' table tbody tr').each(function (ind,val) {
                var values= $(this).find('.tot_qty_tablewash').val()
                var v= $(this).find('.uvc_tco').text();
                $('#washcarelabelform'+washcare+' table tbody tr').each(function (inds,vals) {
                    if($(this).find('.uvc_tco').text() == v)
                    {
                        $(this).find('.tot_qty_tablewash').val()
                        
                    }
                });
            });
            
            $('#washcarelabelform'+washcare+' table tbody').find('.tot_qty_tablewash_fin'+washcare).text($('#washcarelabelform'+index+' table tbody').find('.tot_qty_tablewash_fin'+index).text())
            var mySVG = document.getElementById("washcare_svg_front"+washcare);
            var svgDoc;
			
			var mySVG_back = document.getElementById("washcare_svg_back"+washcare);
            var svgDocBack;

            var mySVG_middle = document.getElementById("washcare_svg_middle" + washcare);
            var svgDoc_middle;
            
            mySVG_middle.addEventListener("load", function () {
                svgDoc_middle = mySVG_middle.contentDocument;
                svgDoc_middle.getElementById("parts_composition").innerHTML ="";
                $('#composition_remove_qty' + washcare + ' tr').each(function () {
                    if($(this).find('select').find(':selected').val() != "Select Option")
                    {
                        svgDoc_middle.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german') + "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek') + '</p>';
                    }
                   
                    var id=$(this).attr('id')
                    var compo =$(this).find('.compositions_hidden').val();
                    var compss = compo.split(',');
                    $(compss).each(function (index1,value1) {
                        var english = value1.split('_');
  
                        svgDoc_middle.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
                  });
                })
              $('.composition_error_hidden').addClass('hidden')
              var washcomp=[];
              $('#composition_remove_qty' + washcare + ' tr').each(function () {
                    var washcomp1=[];
                    var id=$(this).attr('id')
                    var value=$(this).find('select').find(':selected').val();
                    var compo =$(this).find('.compositions_hidden').val();
                    var compss = compo.split(',');
                      $(compss).each(function (index1,value1){ 
                          var english = value1.split('_');
                          washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                      });
                  washcomp.push(washcomp1)
              })
              var wash_finals = JSON.stringify(washcomp);
              localStorage.setItem('wash_composition_final'+washcare, wash_finals);
            })
			
            mySVG.addEventListener("load", function () {
                svgDoc=mySVG.contentDocument;
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('astm'));
                if(Activity_Label == "PUERICULTURE")
                {
                    var text_for_mad_in = '<p>' + $('select.made_in_select' + washcare).find(':selected').data('french') + "-" + $('select.made_in_select' + washcare).find(':selected').data('english') + "-" + $('select.made_in_select' + washcare).find(':selected').data('dutch') + "-" + $('select.made_in_select' + washcare).find(':selected').data('german') + "-" + $('select.made_in_select' + washcare).find(':selected').data('spanish') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('greek') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('arabic')  + '</p>';
                }
                else
                {
                    var text_for_mad_in = '<p>' + $('select.made_in_select' + washcare).find(':selected').data('french') + "-" + $('select.made_in_select' + washcare).find(':selected').data('english') + "-" + $('select.made_in_select' + washcare).find(':selected').data('spanish') + "-" + $('select.made_in_select' + washcare).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + washcare).find(':selected').data('italian') + "-" + $('select.made_in_select' + washcare).find(':selected').data('dutch') + "-" + $('select.made_in_select' + washcare).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('greek')  + '</p>';
                }
                
                
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;
            });
			
            mySVG_back.addEventListener("load", function () {
				svgDocBack=mySVG_back.contentDocument;
				svgDocBack.getElementById("text_care_instruction").innerHTML = "";
                $('.hiddencis' + washcare + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek')  + '</p>';
                    svgDocBack.getElementById("text_care_instruction").innerHTML += value;
                });
                
                
			});
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
        
    });
                        
                        $('.delete-wash' + index).on('click', function (e) {
                            deletewash.push(value.id)
                            localStorage.setItem("deletewash", JSON.stringify(deletewash));
                            removeWashLabel(index,user_type)
                            $('#washcarelabelform' + index).remove()
                            
                            $(this).parent().parent().parent().remove();


                        });
                        washcarelabels(data, index,value.color_code,value.card_flag,user_type);
						washcare++;
						check_count++;
                });
				
				if(check_count == data.orchestra_wash_care_label_orders.length)
				{
					//console.log(wcl_already_exists)
					 $(data.color_wcl).each(function (index1, value1) {
						// console.log(value1.color_code)
					 if(wcl_already_exists.includes(value1.Color_Code) != true)
					 {
						$wash_status = "";
						$wash_status_text = "";
						var wash_enable = "display:none !important";
						var wash_name = "";
						$('#add_modal').append('<div class="col-md-4"><input type="hidden"  id="delete_id'+washcare+'"> <div class="card add-card wash-card'+washcare+'" style="height: 280px;" data-toggle="modal" data-target=".wash_care_label_main" data-backdrop="static" data-keyboard="false"><span class="label label-' + $wash_status + ' label-status">' + $wash_status_text + '</span> <div class="card-title"> <p class="pull-right" id="clickme'+washcare+'" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b class="washlabelname'+washcare+'">' + wash_name + 'Washcare Label</b></h5> </div><div class="row"> <div class="col-md-6 col-md-offset-3"> <div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="washcare_label_card'+washcare+'" style="'+wash_enable+';cursor:pointer;display: inline-block;"><i class="fa fa-plus" aria-hidden="true"></i> Add washcare label</p><p id="clone_washcare_label_card'+washcare+'" style="'+wash_enable+';cursor:pointer;    display: inline-block;float: right;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
						var old_id=washcare;
						$('#washcare_label_card'+old_id).on('click', function (e) {

        if ($('.washcarename').val() != "") {
          
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"> <span></span>   <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare,value1.Color_Code,user_type,Activity_Label);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare,user_type)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
				
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label added successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare,value.color_code,"new",user_type);
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
		
    });
                                 
    					$('#clone_washcare_label_card'+old_id).on('click', function (e) {

        if ($('.washcarename').val() != "") {
        
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"><span></span>    <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare,value1.Color_Code,user_type,Activity_Label);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare,user_type)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
           		
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label cloned successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare,value1.Color_Code,"new",user_type);
            $('#washcarelabelform'+washcare).find('#wcl_name'+washcare).val($('#washcarelabelform'+old_id).find('#wcl_name'+old_id).val())
            $('#washcarelabelform'+washcare).find('#ocn_number_wash'+washcare).val($('#washcarelabelform'+old_id).find('#ocn_number_wash'+old_id).val())
           
            
            $('.washing_select'+washcare).selectpicker('val',$('.washing_select'+old_id).selectpicker('val'));
            $('.whitening_select'+washcare).selectpicker('val',$('.whitening_select'+old_id).selectpicker('val'));
            $('.dryer_select'+washcare).selectpicker('val',$('.dryer_select'+old_id).selectpicker('val'));
            $('.ironing_select'+washcare).selectpicker('val',$('.ironing_select'+old_id).selectpicker('val'));
            $('.cleaning_select'+washcare).selectpicker('val',$('.cleaning_select'+old_id).selectpicker('val'));
            $('.made_in_select'+washcare).selectpicker('val',$('.made_in_select'+old_id).selectpicker('val'));
			
			//$('#washcarelabelform' + washcare+' .washonesizein').val($('#washcarelabelform' + old_id+' .washonesizein').val())
			//$('#washcarelabelform' + washcare+' .washonesizecm').val($('#washcarelabelform' + old_id+' .washonesizecm').val())
			$('.care_instruction_select'+washcare).selectpicker('val',$('.care_instruction_select'+old_id).selectpicker('val'));

			$('.hiddencis'+ old_id + ' option:selected').each(function () {

				$('select.hiddencis' + washcare).append('<option data-id="' + $(this).data('id') + '" data-french="' + $(this).data('french') + '" title="'+$(this).data('english')+'" data-english="' + $(this).data('english') + '" data-spanish="' + $(this).data('spanish') + '" data-portuguese="' + $(this).data('portuguese') + '" data-italian="' + $(this).data('italian') + '" data-dutch="' + $(this).data('dutch') + '" data-german="' + $(this).data('german')  + '" data-arabic="' + $(this).data('arabic') + '" data-greek="' + $(this).data('greek') + '" value="' + $(this).data('id') + '" selected>' + $(this).data('english') + '</option>')
				$('.hiddencis' + washcare).selectpicker('refresh');

			});

			//$('.hiddencis'+washcare).selectpicker('val',$('.hiddencis'+old_id).selectpicker('val'));
            //alert()
            $('#composition_remove_qty'+washcare).html($('#composition_remove_qty'+old_id).html())
            
            $('#composition_remove_qty'+old_id+' tr').each(function (ind,val) {
                var values= $(this).find('select').find(':selected').val()
                var v= $(this).attr('id');
                $('#composition_remove_qty'+washcare+' tr').each(function (inds,vals) {
                    if($(this).attr('id') == v)
                    {
                        $(this).find('select').val(values)
                        $(this).find('a').removeAttr('onclick')
                        $(this).find('a').attr('onclick','deleteComposition(this,'+washcare+')')
                    }
                });
            }); 
            
            $('#washcarelabelform'+old_id+' table tbody tr').each(function (ind,val) {
                var values= $(this).find('.tot_qty_tablewash').val()
                var v= $(this).find('.uvc_tco').text();
                $('#washcarelabelform'+washcare+' table tbody tr').each(function (inds,vals) {
                    if($(this).find('.uvc_tco').text() == v)
                    {
                        $(this).find('.tot_qty_tablewash').val()
                        
                    }
                });
            });
            
            $('#washcarelabelform'+washcare+' table tbody').find('.tot_qty_tablewash_fin'+washcare).text($('#washcarelabelform'+old_id+' table tbody').find('.tot_qty_tablewash_fin'+old_id).text())
            var mySVG = document.getElementById("washcare_svg_front"+washcare);
            var svgDoc;
			
			var mySVG_back = document.getElementById("washcare_svg_back"+washcare);
            var svgDocBack;

            var mySVG_middle = document.getElementById("washcare_svg_middle" + washcare);
            var svgDoc_middle;
			
            mySVG.addEventListener("load", function () {
                svgDoc=mySVG.contentDocument;
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('astm'));

                if(Activity_Label == "PUERICULTURE")
                {
                    var text_for_mad_in = '<p>' + $('select.made_in_select' + washcare).find(':selected').data('french') + "-" + $('select.made_in_select' + washcare).find(':selected').data('english') + "-" + $('select.made_in_select' + washcare).find(':selected').data('dutch') + "-" + $('select.made_in_select' + washcare).find(':selected').data('german') + "-" + $('select.made_in_select' + washcare).find(':selected').data('spanish') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('greek') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('arabic')  + '</p>';
                }
                else
                {
                    var text_for_mad_in = '<p>' + $('select.made_in_select' + washcare).find(':selected').data('french') + "-" + $('select.made_in_select' + washcare).find(':selected').data('english') + "-" + $('select.made_in_select' + washcare).find(':selected').data('spanish') + "-" + $('select.made_in_select' + washcare).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + washcare).find(':selected').data('italian') + "-" + $('select.made_in_select' + washcare).find(':selected').data('dutch') + "-" + $('select.made_in_select' + washcare).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('greek')  + '</p>';
                }
                
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;
            });

            mySVG_middle.addEventListener("load", function () {
                svgDoc_middle = mySVG_middle.contentDocument;
                svgDoc_middle.getElementById("parts_composition").innerHTML ="";
              $('#composition_remove_qty' + washcare + ' tr').each(function () {
                  if($(this).find('select').find(':selected').val() != "Select Option")
                  {
                    svgDoc_middle.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german') + "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek') + '</p>';
                  }
                 
                  var id=$(this).attr('id')
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                  $(compss).each(function (index1,value1) {
                      var english = value1.split('_');

                      svgDoc_middle.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
                });
              })
            $('.composition_error_hidden').addClass('hidden')
            var washcomp=[];
            $('#composition_remove_qty' + washcare + ' tr').each(function () {
                  var washcomp1=[];
                  var id=$(this).attr('id')
                  var value=$(this).find('select').find(':selected').val();
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                    $(compss).each(function (index1,value1){ 
                        var english = value1.split('_');
                        washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                    });
                washcomp.push(washcomp1)
            })
            var wash_finals = JSON.stringify(washcomp);
            localStorage.setItem('wash_composition_final'+washcare, wash_finals);
            })
			
            mySVG_back.addEventListener("load", function () {
				svgDocBack=mySVG_back.contentDocument;
				svgDocBack.getElementById("text_care_instruction").innerHTML = "";
                $('.hiddencis' + washcare + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek')  + '</p>';
                    svgDocBack.getElementById("text_care_instruction").innerHTML += value;
                });
                
                 
			});
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
        
    });
                        
                        $('.delete-wash' + old_id).on('click', function (e) {
                            deletewash.push(value.id)
                            localStorage.setItem("deletewash", JSON.stringify(deletewash));
                            removeWashLabel(old_id,user_type)
                            $('#washcarelabelform' + old_id).remove()
                            
                            $(this).parent().parent().parent().remove();


                        });
						washcarelabels(data, old_id,value1.Color_Code,"",user_type);
						washcare++;
					 }
					});
				}
				
				
            } else {
               $(data.color_wcl).each(function (index1, value1) {
		
						if (data.orchestra_wash_care_label_orders != "") {
							var str = data.orchestra_wash_care_label_orders[0].status;
							$wash_status = str.replace(/ /g, "");
							$wash_status_text = data.orchestra_wash_care_label_orders[0].status;
							var wash_enable = "";
							var wash_name = data.orchestra_wash_care_label_orders[0].template_name + " - ";
							update_disable=1;
						} else {
							$wash_status = "";
							$wash_status_text = "";
							var wash_enable = "display:none !important";
							var wash_name = "";
						}

						$('#add_modal').append('<div class="col-md-4"> <input type="hidden"  id="delete_id'+washcare+'"><div class="card add-card wash-card'+washcare+'" style="height: 280px;" data-toggle="modal" data-target=".wash_care_label_main" data-backdrop="static" data-keyboard="false"><span class="label label-' + $wash_status + ' label-status">' + $wash_status_text + '</span> <div class="card-title"> <p class="pull-right" id="clickme'+washcare+'" style="cursor: pointer;">View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i></p><h5><b class="washlabelname'+washcare+'">' + wash_name + 'Washcare Label</b></h5> </div><div class="row"> <div class="col-md-6 col-md-offset-3"> <div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="washcare_label_card'+washcare+'" style="'+wash_enable+';cursor:pointer;display: inline-block;"><i class="fa fa-plus" aria-hidden="true"></i> Add washcare label</p><p id="clone_washcare_label_card'+washcare+'" style="'+wash_enable+';cursor:pointer;    display: inline-block;float: right;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				   		var old_id=washcare;
				   		$('#washcare_label_card'+old_id).on('click', function (e) {

        if ($('.washcarename').val() != "") {
          
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"> <span></span>   <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare,value1.Color_Code,user_type,Activity_Label);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare,user_type)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
				
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label added successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare,value1.Color_Code,"new",user_type);
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
		
    });
                                 
    					$('#clone_washcare_label_card'+old_id).on('click', function (e) {

        if ($('.washcarename').val() != "") {
          
                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"><span></span>    <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')
				
				//var index=washcare;
				
				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare,value1.Color_Code,user_type,Activity_Label);
				});
                
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare,user_type)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
           		
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label cloned successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare,value1.Color_Code,"new",user_type);
            $('#washcarelabelform'+washcare).find('#wcl_name'+washcare).val($('#washcarelabelform'+old_id).find('#wcl_name'+old_id).val())
            $('#washcarelabelform'+washcare).find('#ocn_number_wash'+washcare).val($('#washcarelabelform'+old_id).find('#ocn_number_wash'+old_id).val())
           
            
            $('.washing_select'+washcare).selectpicker('val',$('.washing_select'+old_id).selectpicker('val'));
            $('.whitening_select'+washcare).selectpicker('val',$('.whitening_select'+old_id).selectpicker('val'));
            $('.dryer_select'+washcare).selectpicker('val',$('.dryer_select'+old_id).selectpicker('val'));
            $('.ironing_select'+washcare).selectpicker('val',$('.ironing_select'+old_id).selectpicker('val'));
            $('.cleaning_select'+washcare).selectpicker('val',$('.cleaning_select'+old_id).selectpicker('val'));
            $('.made_in_select'+washcare).selectpicker('val',$('.made_in_select'+old_id).selectpicker('val'));
			
			//$('#washcarelabelform' + washcare+' .washonesizein').val($('#washcarelabelform' + old_id+' .washonesizein').val())
			//$('#washcarelabelform' + washcare+' .washonesizecm').val($('#washcarelabelform' + old_id+' .washonesizecm').val())
			$('.care_instruction_select'+washcare).selectpicker('val',$('.care_instruction_select'+old_id).selectpicker('val'));

			$('.hiddencis'+ old_id + ' option:selected').each(function () {

				$('select.hiddencis' + washcare).append('<option data-id="' + $(this).data('id') + '" data-french="' + $(this).data('french') + '" title="'+$(this).data('english')+'" data-english="' + $(this).data('english') + '" data-spanish="' + $(this).data('spanish') + '" data-portuguese="' + $(this).data('portuguese') + '" data-italian="' + $(this).data('italian') + '" data-dutch="' + $(this).data('dutch') + '" data-german="' + $(this).data('german')  + '" data-arabic="' + $(this).data('arabic') + '" data-greek="' + $(this).data('greek') + '" value="' + $(this).data('id') + '" selected>' + $(this).data('english') + '</option>')
				$('.hiddencis' + washcare).selectpicker('refresh');

			});
			

			//$('.hiddencis'+washcare).selectpicker('val',$('.hiddencis'+old_id).selectpicker('val'));
            //alert()
            $('#composition_remove_qty'+washcare).html($('#composition_remove_qty'+old_id).html())
            
            $('#composition_remove_qty'+old_id+' tr').each(function (ind,val) {
                var values= $(this).find('select').find(':selected').val()
                var v= $(this).attr('id');
                $('#composition_remove_qty'+washcare+' tr').each(function (inds,vals) {
                    if($(this).attr('id') == v)
                    {
                        $(this).find('select').val(values)
                        $(this).find('a').removeAttr('onclick')
                        $(this).find('a').attr('onclick','deleteComposition(this,'+washcare+')')
                    }
                });
            }); 
            
            $('#washcarelabelform'+old_id+' table tbody tr').each(function (ind,val) {
                var values= $(this).find('.tot_qty_tablewash').val()
                var v= $(this).find('.uvc_tco').text();
                $('#washcarelabelform'+washcare+' table tbody tr').each(function (inds,vals) {
                    if($(this).find('.uvc_tco').text() == v)
                    {
                        $(this).find('.tot_qty_tablewash').val()
                        
                    }
                });
            });
            
            $('#washcarelabelform'+washcare+' table tbody').find('.tot_qty_tablewash_fin'+washcare).text($('#washcarelabelform'+old_id+' table tbody').find('.tot_qty_tablewash_fin'+old_id).text())
            var mySVG = document.getElementById("washcare_svg_front"+washcare);
            var svgDoc;
			
			var mySVG_back = document.getElementById("washcare_svg_back"+washcare);
            var svgDocBack;

            var mySVG_middle = document.getElementById("washcare_svg_middle" + washcare);
            var svgDoc_middle;
			
            mySVG.addEventListener("load", function () {
                svgDoc=mySVG.contentDocument;
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('astm'));
                
                if(Activity_Label == "PUERICULTURE")
                {
                    var text_for_mad_in = '<p>' + $('select.made_in_select' + washcare).find(':selected').data('french') + "-" + $('select.made_in_select' + washcare).find(':selected').data('english') + "-" + $('select.made_in_select' + washcare).find(':selected').data('dutch') + "-" + $('select.made_in_select' + washcare).find(':selected').data('german') + "-" + $('select.made_in_select' + washcare).find(':selected').data('spanish') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('greek') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('arabic')  + '</p>';
                }
                else
                {
                    var text_for_mad_in = '<p>' + $('select.made_in_select' + washcare).find(':selected').data('french') + "-" + $('select.made_in_select' + washcare).find(':selected').data('english') + "-" + $('select.made_in_select' + washcare).find(':selected').data('spanish') + "-" + $('select.made_in_select' + washcare).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + washcare).find(':selected').data('italian') + "-" + $('select.made_in_select' + washcare).find(':selected').data('dutch') + "-" + $('select.made_in_select' + washcare).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('greek')  + '</p>';
                }
                
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;
            });

            mySVG_middle.addEventListener("load", function () {
                svgDoc_middle = mySVG_middle.contentDocument;

                svgDoc_middle.getElementById("parts_composition").innerHTML ="";
                $('#composition_remove_qty' + washcare + ' tr').each(function () {
                    if($(this).find('select').find(':selected').val() != "Select Option")
                    {
                        svgDoc_middle.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german') + "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek') + '</p>';
                    }
                    
                    var id=$(this).attr('id')
                    var compo =$(this).find('.compositions_hidden').val();
                    var compss = compo.split(',');
                    $(compss).each(function (index1,value1) {
                        var english = value1.split('_');

                        svgDoc_middle.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
                    });
                })
                $('.composition_error_hidden').addClass('hidden')
                var washcomp=[];
                $('#composition_remove_qty' + washcare + ' tr').each(function () {
                    var washcomp1=[];
                    var id=$(this).attr('id')
                    var value=$(this).find('select').find(':selected').val();
                    var compo =$(this).find('.compositions_hidden').val();
                    var compss = compo.split(',');
                        $(compss).each(function (index1,value1){ 
                            var english = value1.split('_');
                            washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                        });
                    washcomp.push(washcomp1)
                })
                var wash_finals = JSON.stringify(washcomp);
                localStorage.setItem('wash_composition_final'+washcare, wash_finals);
            });
			
            mySVG_back.addEventListener("load", function () {
				svgDocBack=mySVG_back.contentDocument;
				svgDocBack.getElementById("text_care_instruction").innerHTML = "";
                $('.hiddencis' + washcare + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek')  + '</p>';
                    svgDocBack.getElementById("text_care_instruction").innerHTML += value;
                });
                
                 
			});
        } else {
            if (washcare < 5) {
                $('.washnameerror').removeClass('hidden')
            }
        }
        
    });
                        
                        $('.delete-wash' + old_id).on('click', function (e) {
                            deletewash.push(value.id)
                            localStorage.setItem("deletewash", JSON.stringify(deletewash));
                            removeWashLabel(old_id,user_type)
                            $('#washcarelabelform' + old_id).remove()
                            
                            $(this).parent().parent().parent().remove();


                        });
						washcarelabels(data, old_id,value1.Color_Code,"",user_type);
						washcare++;
					});
            }
			}
			else
			{
				$('.snackbar_span').html('Company Details Missing.Please Contact Administrator...!');
                showSnackbar();
			}
           


            //  washcarelabels(data,"");

        },
        error: function (data) {

        },
        complete: function () {
            $(".loader").hide();
        }
    })
}

/**
 * This Function is Sub Function of #label_order_details.
 * To integrate Wash care Information
 * @return Wash care View
 */

function washcarelabels(data, dataid,color_code,new_flag,user_type) {
    var washing_option = "<option value=''>Select Option</option>";
    var whitening_option = "<option value=''>Select Option</option>";
    var dryer_option = "<option value=''>Select Option</option>";
    var ironing_option = "<option value=''>Select Option</option>";
    var cleaning_option = "<option value=''>Select Option</option>";
    var made_option = "<option value=''>Select Option</option>";
    var instruction_option = "";
    var parts_option = "<option>Select Option</option>";
    var composition_option = "<option>Select Option</option>";


    $.each(data.orchestra_countries, function (index, value) {
        made_option += '<option data-id="' + value.id + '"  data-french="' + value.french + '" data-english="' + value.english + '" data-spanish="' + value.spanish + '" data-portuguese="' + value.portuguese + '" data-italian="' + value.italian + '" data-dutch="' + value.dutch + '" data-german="' + value.german + '" data-arabic="' + value.arabic + '" data-greek="' + value.greek + '" value="' + value.id + '">' + value.english + '</option>';
    });
    $.each(data.orchestra_care_instruction, function (index, value) {
		if(value.english.length > 31)
		{
			var txtEng = value.english.slice(0,30)+"...";
		}
		else
		{
			var txtEng = value.english;
		}
        instruction_option += '<option data-id="' + value.id + '" data-french="' + value.french + '" title="'+value.english+'" data-english="' + value.english + '" data-spanish="' + value.spanish + '" data-portuguese="' + value.portuguese + '" data-italian="' + value.italian + '" data-dutch="' + value.dutch + '" data-german="' + value.german  + '" data-arabic="' + value.arabic + '" data-greek="' + value.greek + '" value="' + value.id + '">' + txtEng + '</option>';
    });
    $.each(data.orchestra_parts, function (index, value) {
        parts_option += '<option data-french="' + value.french + '" data-english="' + value.english + '" data-spanish="' + value.spanish + '" data-portuguese="' + value.portuguese + '" data-italian="' + value.italian + '" data-dutch="' + value.dutch + '" data-german="' + value.german + '" data-arabic="' + value.arabic + '" data-greek="' + value.greek + '" value="' + value.id + '">' + value.english + '</option>';
    });
    $.each(data.orchestra_composition, function (index, value) {
        composition_option += '<option data-french="' + value.french + '" data-english="' + value.english + '" data-spanish="' + value.spanish + '" data-portuguese="' + value.portuguese + '" data-italian="' + value.italian + '" data-dutch="' + value.dutch + '" data-german="' + value.german  + '" data-arabic="' + value.arabic + '" data-greek="' + value.greek + '" value="' + value.id + '">' + value.english + '</option>';
    });
    $.each(data.orchestra_care_symbols, function (index, value) {
		//console.log(value.care_instruction.length)
		if(value.care_instruction.length > 15)
		{
			var carEng = value.care_instruction.slice(0,15)+"...";
		}
		else
		{
			var carEng = value.care_instruction;
		}
		
        if (value.category_id == 1) {
            washing_option += "<option title='"+value.care_instruction+"' data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + carEng + "</option>";
        } else if (value.category_id == 2) {
            whitening_option += "<option title='"+value.care_instruction+"' data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + "  value=" + value.id + ">" + carEng + "</option>";
        } else if (value.category_id == 3) {
            dryer_option += "<option title='"+value.care_instruction+"' data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + carEng + "</option>";
        } else if (value.category_id == 4) {
            ironing_option += "<option title='"+value.care_instruction+"' data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + carEng + "</option>";
        } else if (value.category_id == 5) {
            cleaning_option += "<option title='"+value.care_instruction+"' data-id='" + value.id + "' data-content='<img src=" + value.cofreet_symbols + "> &nbsp; " + carEng + "' value=" + value.id + "  data-cofreet=" + value.cofreet_symbols + "  data-astm=" + value.astms_symbol + ">" + carEng + "</option>";
        }

    });
	
	if(new_flag != "new" && new_flag != undefined)
	{
		var card_flag = new_flag;
	}
	else
	{
		var card_flag = "card_"+dataid;
	}

	

    var tr = "";
    $qu = 0;
    var ttots = 0;
	var totaloq = 0;
    if (data.orchestra_wash_care_label_orders != "") {
        if (dataid != "") {
            dataid1 = dataid;
        } else {
            dataid1 = 0;
        }
        if (data.orchestra_wash_care_label_orders.length > dataid1) {
			$time_flag = data.orchestra_wash_care_label_orders[dataid1].card_flag;
            $exist_id = data.orchestra_wash_care_label_orders[dataid1].id;
			
			if(data.barcode[0].BuyerPONo == data.orchestra_wash_care_label_orders[dataid1].po)
			{
				if(data.orchestra_wash_care_label_orders[dataid1].merchandiser_comment != "")
				{
					$comment = "<h4>Comments :</h4><p id='comment_section_"+dataid1+"'>"+data.orchestra_wash_care_label_orders[dataid1].merchandiser_comment+"</p><hr>";
				}
				else
				{
					$comment = "";
				}
			}
			else
			{
				$comment = "";
			}
        
            
            var str=data.orchestra_wash_care_label_orders[dataid1].status;
			if(data.customers[0].factory_code == "HF-FREEUSD")
		    {
				if(data.barcode[0].BuyerPONo == data.orchestra_wash_care_label_orders[dataid1].po)
				{
					$save_button_class = "hidden";
					$print_button_class = "";
					$disable_option_select = "disabled";
					$disable_qty_select = "disabled";
				}
				else
				{
					$save_button_class = "";
					$print_button_class = "hidden";
					$disable_option_select = "disabled";
					$disable_qty_select = "";
				}
		    }
			else
			{
				$save_button_class = "hidden";
				$print_button_class = "";
				$disable_option_select = "disabled";
				$disable_qty_select = "disabled";
			}
			
			//$save_button_class = "hidden";
            $status=str.replace(/ /g, "");
            $status_text=str;
            $wcl_name=data.orchestra_wash_care_label_orders[dataid1].template_name;
            $template_id = data.orchestra_wash_care_label_orders[dataid1].template_id;   
		    $ocn_number_wash = data.orchestra_wash_care_label_orders[dataid1].ocn_number;
			
			//$print_button_class = "";
        } else {
            $time_flag = Date.now();
            $exist_id = "";
            $comment = "";
            $status="";
            $status_text="";
            $wcl_name="";
			$template_id = "122";
			$print_button_class = "hidden";
			$save_button_class = "";
			$disable_option_select = "";
            $disable_qty_select = "";
            $ocn_number_wash = "";
        }

    } else {
		
		$time_flag = Date.now();
        
        $exist_id = "";
        $comment = "";
        $status="";
        $status_text="";
        $wcl_name="";
		$template_id = "122";
		$print_button_class = "hidden";
		$save_button_class = "";
		$disable_option_select = "";
		$disable_qty_select = "";
        $ocn_number_wash = "";
    }
	var arr=[];
	var options="";
	var input_hidden="";
	$.each(data.barcode, function (index, value) {
		
	   if(arr.includes(value.BuyerPONo) == false)
		{
			arr.push(value.BuyerPONo)
			options += "<option>" + value.BuyerPONo + "</option>";
			input_hidden += '<input type="hidden" value="" id="'+value.BuyerPONo +'_'+ dataid + '">';
		}
	});
	//alert(data.customers[0].factory_code);
   if(data.customers[0].factory_code == "HF-FREEUSD")
   {
   		var po_select = '<select style="width: 135px;display: inline-block;margin-left: 15px;" id="po_select'+dataid+'" class="form-control">' + options + '</select>';
   }
   else
   {
	  	var po_select = '';
   }
	
    $.each(data.order_details, function (index, value) {
		//alert(color_code)
        if(color_code == value.Color_Code)
		{
				if (data.orchestra_wash_care_label_orders != "") {
					if (dataid != "") {
						dataid1 = dataid;
					} else {
						dataid1 = 0;
					}
					if (data.orchestra_wash_care_label_orders.length > dataid1) {
						
					   if(data.customers[0].factory_code == "HF-FREEUSD")
					   {
						   if(data.barcode[0].BuyerPONo == data.orchestra_wash_care_label_orders[dataid1].po)
							{
								$wash_label_count = data.orchestra_wash_care_label_orders[dataid1]['qty'][$qu].quantity;
							}
							else
							{
								$wash_label_count = "";
							}
					   }
					   else
					   {
						   $wash_label_count = data.orchestra_wash_care_label_orders[dataid1]['qty'][$qu].quantity;
						   
					   }
						
						
					} else {
						$wash_label_count = "";
					}

				} else {
					$wash_label_count = "";
				}
			   tr += '<tr><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + (value.Size).replace("ANS", '') + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)" type="number" min="'+data.wash_moq+'" data-required-error="Required" '+ $disable_qty_select +' class="form-control input-sm tot_qty_tablewash" value="' + $wash_label_count + '" required placeholder="WCL Count"><div class="help-block with-errors"></div></div></td></tr>';
			 	$qu++;
			    ttots += parseInt($wash_label_count);
				totaloq += parseInt(value.Sum);
		}

    });
	
	if(data.order_details[0].Size == "TAILLE UNIQUE")
	{
		if (dataid != "") {
            dataid1 = dataid;
        } else {
            dataid1 = 0;
        }
		if (data.orchestra_wash_care_label_orders != "") {
			 if (data.orchestra_wash_care_label_orders.length > dataid1) {
                $wash_cm = data.orchestra_wash_care_label_orders[dataid1].cm;
				$wash_in = data.orchestra_wash_care_label_orders[dataid1].in;
            } else {
                $wash_cm = ""; 
				$wash_in = "";
            }
			
            
        } else {
            $wash_cm = "";
			$wash_in = "";
        }
		//<div class="form-group has-feedback"><label>Enter Size in Cm</label><input onkeypress="return checkNumbersOnly(event)" type="text" maxlength="3" class="form-control input-sm washonesizecm" value="'+$wash_cm+'" required="" placeholder="Enter Size in Cm"><div class="help-block with-errors"></div></div>
		var sizebox='';
	}
	else
	{
		var sizebox="";
	}
	
    if(isNaN(ttots))
        {
            ttots=0;
        }
    else
        {
            ttots=ttots;
        }
	//alert(data.order_details[0].Licence_Code)
	if(data.order_details[0].Licence_Code === 'DI')
	{
		//<label id="hang_template_error" class="hidden" style="color:red">Please Select the Template</label>  <div><input type="text" id="tree_wash'+dataid+'_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div id="tree_wash'+dataid+'"></div>
		var treestruct='';
		var disney_flag="1";
	}
	else
	{
		var treestruct="";
		var disney_flag="0";
	}
	
    $('.top-content').append('<form id="washcarelabelform' + dataid + '" data-toggle= "validator"><input type="hidden" value="' + $exist_id + '" id="exist_id' + dataid + '"><input type="hidden" value="' + color_code + '" id="color_id' + dataid + '"><input type="hidden" value="' + card_flag + '" id="card_flag' + dataid + '"><input type="hidden" value="' + $time_flag + '" id="time_flag' + dataid + '"><input type="hidden" value="' + $disable_option_select + '" id="composition_flag' + dataid + '">'+input_hidden+'<div class="left-panelorc col-md-8"> <div class="div-max"> <div class="col-md-3"> <h4>Options</h4> <hr><div class="scroll"><div class="form-group has-feedback has-success"><label for="ocn_number"class="form_label">OCN Number</label><input type="text" id="ocn_number_wash' + dataid + '" '+$disable_option_select+' value="'+$ocn_number_wash+'" class="form-control input-sm" placeholder="OCN Number"></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">WCL Name</label><input type="text" data-required-error="Required" '+$disable_option_select+' class="form-control input-sm" required="" placeholder="WCL Name" value="' + $wcl_name + '" id="wcl_name' + dataid + '"><div class="help-block with-errors"></div></div>  <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Washing</label><select '+$disable_option_select+' data-required-error="Required" data-live-search="true" class="form-control selectpicker input-sm washing_select' + dataid + '" required>' + washing_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Whitening</label><select '+$disable_option_select+' data-required-error="Required" data-live-search="true" class="form-control selectpicker input-sm whitening_select' + dataid + '" required>' + whitening_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Dryer</label><select '+$disable_option_select+' data-required-error="Required" data-live-search="true" class="form-control selectpicker input-sm dryer_select' + dataid + '" required>' + dryer_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Ironing</label><select '+$disable_option_select+' data-required-error="Required" data-live-search="true" class="form-control selectpicker input-sm ironing_select' + dataid + '" required>' + ironing_option + '</select><div class="help-block with-errors"></div></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Cleaning</label><select '+$disable_option_select+' data-required-error="Required" data-live-search="true" class="form-control selectpicker input-sm cleaning_select' + dataid + '" required>' + cleaning_option + '</select><div class="help-block with-errors"></div></div>'+sizebox+'  </div></div> <div class="col-md-5"> <h4>Art Work</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span> <hr> <div class="section-1 sections"> <div class="sample-artwork"><object style="height: calc( 100vh - 125px );"  id="washcare_svg_front' + dataid + '" data="' + data.orchestra_wash_template_front + '" type="image/svg+xml"></object> <object style="height: calc( 100vh - 125px );margin-left: 10px;"  id="washcare_svg_back' + dataid + '"  data="' + data.orchestra_wash_template_back + '" type="image/svg+xml"></object><object style="height: calc( 100vh - 125px );width: 145px;margin-left: 10px;"  id="washcare_svg_middle' + dataid + '" data="' + data.orchestra_wash_template_middle + '" type="image/svg+xml"></object></div><input type="hidden" value="' + disney_flag + '" id="wash_label_id"></div> </div> <div class="col-md-4 " style="padding-left:0px"> <h4>Options</h4> <hr> <div class="scroll"><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Made In</label><select '+$disable_option_select+' data-required-error="Required" data-live-search="true" class="form-control selectpicker input-sm made_in_select' + dataid + '" required>' + made_option + '</select><div class="help-block with-errors"></div></div> <div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer" class="form_label">Select Care Instructions</label><select '+$disable_option_select+' data-required-error="Required" data-live-search="true" data-done-button="true" class="form-control selectpicker input-sm care_instruction_select' + dataid + '" required multiple>' + instruction_option + '</select><select data-required-error="Required" data-live-search="true" class="form-control hiddencis_div selectpicker input-sm hiddencis' + dataid + '" multiple></select><div class="help-block with-errors"></div></div> <div class="section-1 sections"> <h5>Compositions</h5> <select '+$disable_option_select+' class="selectpicker composition_select' + dataid + '" data-live-search="true">  ' + composition_option + '  </select><p class="hidden" style="color:red;float:right" id="composition_error_required' + dataid + '">Please Select Composition</p> </div> <h5>Percentage</h5> <input  '+$disable_option_select+' type="number" min="1" max="100" class="form-control input-sm composition_percent' + dataid + '"><p class="hidden" style="color:red;text-align:right" id="percentage_error_required' + dataid + '">Please Enter Percentage</p>  <button '+$disable_option_select+' type="button" class="btn  btn-primary pull-right input-sm add_to_list' + dataid + '" onclick="add_to_list(' + dataid + ')">Add To List</button> <table class="table table-striped table-hover table-condensed"><thead><th>Composition</th><th>Quantity</th></thead><tbody id="composition_table_qty' + dataid + '"></tbody></table><p id="wash_composition_error' + dataid + '" class="hidden" style="color:red;text-align: right;margin-right: 10px;">Please Select composition</p><p id="wash_composition_page_error' + dataid + '" class="hidden" style="color:red;text-align: right;margin-right: 10px;">Please Add Composition to Page</p><div style="height: 30px;"><button class="btn  btn-primary pull-right input-sm add_to_composition' + dataid + '"  type="button" disabled onclick="add_to_composition(' + dataid + ')">Add To Composition</button></div><div><h4>Compositions :</h4><div><table class="table table-striped table-hover table-condensed"><thead><tr><th>Composition Name</th><th>Parts</th><th>Actions</th></tr></thead><tbody id="composition_remove_qty' + dataid + '"></tbody></table><div><p class="hidden composition_error_hidden" style="color:red;text-align: right;margin-right: 10px;">Please select parts</p></div><div><button '+$disable_option_select+' class="btn  btn-primary pull-right input-sm add_to_page' + dataid + '"  style="position:relative;" type="button" onclick="add_to_page(' + dataid + ')">Add To Page</button></div></div></div><div class="parts_none'+dataid+'" style="display:none"><select class="form-control">' + parts_option + '</select></div> </div> </div> </div></div> <div class="right-panel col-md-4"> <div class="div-max scroll"> <h4 style="display: inline-block;">Quantity Info for <span class="washlabelnameqty' + dataid + '"> WashCare Label</span></h4> '+po_select+' <hr> <table class="table table-striped table-hover table-condensed table-responsive qty-table-modal" id="wash_care_label_table_'+dataid+'" style="font-size:11px"> <thead> <tr> <th>UVC TCO#</th> <th>Size in ANS</th> <th>Barcode</th> <th>Qty</th> <th><input type="text" class="form-control input-sm extra_percentagewash" '+$disable_qty_select+' onkeypress="return checkNumbersOnly(event)" placeholder="Extra Qty %" style="float:left;height:20px;"></th> </tr></thead> <tbody style="font-size: 11px">' + tr + '  <tfoot><tr class="strong"> <td class="text-right" colspan="3">Total:</td><td>' + totaloq + '</td><td class="text-center tot_qty_tablewash_fin' + dataid + '">' + ttots + '</td></tr> </tfoot></tbody></table><hr>'+ $comment +' <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_wash_button_'+dataid+'" style="margin-left:10px">Save</button> <button type="button" class="btn input-sm btn-danger '+$print_button_class+'" id="cancel_wash_button_'+dataid+'" style="float: right;margin-left: 10px;" onclick=cancelWashDiv("'+dataid+'","'+data.customers[0].factory_code+'","'+user_type+'")>Cancel</button><button class="btn btn-default pull-right input-sm" id="close" type="button">Close</button> <button type="button" class="btn btn-primary '+$print_button_class+'" id="print_wash_button_'+dataid+'" onclick=printWashDiv("washcare_svg_front'+dataid+'","washcare_svg_back'+dataid+'","washcare_svg_middle'+dataid+'","'+dataid+'","'+user_type+'","'+data.order_details[0].Activity_Label+'")>Print</button></div> </div>  <div class="backdrop"></div></form>');
   //$('.left').removeClass('disabled')
	
	if(new_flag != "new")
	{
		if (data.orchestra_wash_care_label_orders_po != "") {
		$(data.orchestra_wash_care_label_orders_po).each(function (keys, vals) {
			if(vals.card_flag == $("#card_flag"+dataid).val())
			{
				$('#'+vals.po+'_'+dataid).val("1");
				$('#'+vals.po+'_'+dataid).attr("data-exist",vals.id);
			}
            
        })
		}
	}
	
	

    $('#washcarelabelform' + dataid + ' .tot_qty_tablewash').keyup(function () {
        var tot_qty_tablewash_carton_final = 0;
        $('#washcarelabelform' + dataid + ' tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablewash').val() != undefined) {
                tot_qty_tablewash_carton_final += parseInt($(this).find('.tot_qty_tablewash').val());
            }
        })
        $('#washcarelabelform' + dataid + ' .tot_qty_tablewash_fin'+ dataid).text(tot_qty_tablewash_carton_final);
    });
	
	treestructurewash(data.treeviewwash,dataid, "tree_wash"+dataid, $template_id,data,user_type);
	
    $('#washcarelabelform' + dataid).validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setWashCareLabelStatus(dataid,user_type)
        }
    })
	
//	$('#washcarelabelform' + dataid+' .washonesizein').keyup(function () {
//		
//		//var inches=$(this).val();
//		var cm=$('#washcarelabelform' + dataid+' .washonesizecm').val();
//        var mySVG = document.getElementById("washcare_svg_front" + dataid);
//		var svgDoc;
//		svgDoc = mySVG.contentDocument;
//		svgDoc.getElementById("size_in_fr_en1").innerHTML = cm+"cm";
//		svgDoc.getElementById("size_in_fr_en2").innerHTML = cm+"cm";
//
//		
//    });
//	$('#washcarelabelform' + dataid+' .washonesizecm').keyup(function () {
//		//alert(dataid)
//		var cm=$(this).val();
//		//var inches=$('#washcarelabelform' + dataid+' .washonesizein').val();
//        var mySVG = document.getElementById("washcare_svg_front" + dataid);
//		var svgDoc;
//		svgDoc = mySVG.contentDocument;
//		svgDoc.getElementById("size_in_fr_en1").innerHTML = cm+"cm";
//		svgDoc.getElementById("size_in_fr_en2").innerHTML = cm+"cm";
//		
//    });
	
	$('#po_select'+ dataid).change(function(){
		var po = $(this).val();
		var parcel_id = $('#parcel_id').val();
		var season_code = $('#season_code').val();
		var uvc_id = $('#uvc_id').val();
		var card_flag = $('#time_flag' + dataid).val();
		
		$.ajax({
			url: base_url + "/"+user_type+"/" + buyer + "/order-management/po_select_wcl",
			type: "post",
			data: {
				_token: csrf_token,
				po: po,
				parcel_id:parcel_id,
				season_code:season_code,
				uvc_id:uvc_id,
				card_flag:card_flag,
			},
            dataType: "json",
			success: function (data) {
				if($('#'+po+'_'+dataid).val() == 1)
				{
					$('#save_wash_button_'+dataid).addClass('hidden')
					$('#print_wash_button_'+dataid).removeClass('hidden')
					$('#cancel_wash_button_'+dataid).removeClass('hidden')
					$("#wash_care_label_table_"+dataid+" :input").prop("disabled", true);
					$i=0;
					$qty=0;
					$('#wash_care_label_table_'+dataid+' tbody tr').each(function () {
						$(this).find('.tot_qty_tablewash').val(data.orchestra_wash_care_label_orders_qty[$i].quantity)
						$qty+=data.orchestra_wash_care_label_orders_qty[$i].quantity;
						$i++;
					});

					$('.extra_percentagewash').val("")
					$('.tot_qty_tablewash_fin'+ dataid).text($qty)
					if(data.orchestra_wash_care_label_orders[0].merchandiser_comment != "" && data.orchestra_wash_care_label_orders[0].merchandiser_comment != null)
					{
						$('#comment_section_'+dataid).text(data.orchestra_wash_care_label_orders[0].merchandiser_comment)
					}
					else
					{
						$('#comment_section_'+dataid).text("null")
					}
					

				}
				else
				{
					$('#save_wash_button_'+dataid).removeClass('hidden')
					$('#print_wash_button_'+dataid).addClass('hidden')
					$('#cancel_wash_button_'+dataid).addClass('hidden')
					$("#wash_care_label_table_"+dataid+" :input").prop("disabled", false);
					$('#wash_care_label_table_'+dataid+' tbody tr').each(function () {
						$(this).find('.tot_qty_tablewash').val("")
					});

					$('.extra_percentagewash').val("")
					$('.tot_qty_tablewash_fin'+ dataid).text(0)
					$('#comment_section_'+dataid).text("null")
				}
				
			},
			error: function (data) {

			},
		})
		
		
		});
	
    $('.care_instruction_select'+dataid).parent().removeClass('has-error')
    $('.help-block').html('')
    $('.extra_percentagewash').keyup(function () {
		
        
        var tot_qty_tablewash_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablewash').val(tot_qty);
                if ($(this).find('.tot_qty_tablewash').val() != undefined) {
                    tot_qty_tablewash_carton_final += parseInt($(this).find('.tot_qty_tablewash').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablewash_fin'+ dataid).text(tot_qty_tablewash_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablewash').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablewash_fin'+ dataid).text("0");
        }

    })

    selectpicker()
	
	var title = [];
	$('.care_instruction_select'+dataid+' option').each(function(){
		title.push($(this).attr('title'));
	});

	$(".care_instruction_select"+dataid+" ul li").each(function(i){
	  $(this).attr('title',title[i]).tooltip();
	})
	
	var title = [];
	$('.care_instruction_select'+dataid+' option').each(function(){
		title.push($(this).attr('title'));
	});

	$(".care_instruction_select"+dataid+" ul li").each(function(i){
	  $(this).attr('title',title[i]).tooltip();
	})
	
	var washing_title = [];
	$('.washing_select'+dataid+' option').each(function(){
		washing_title.push($(this).attr('title'));
	});

	$(".washing_select"+dataid+" ul li").each(function(i){
	  $(this).attr('title',washing_title[i]).tooltip();
	})

	var whitening_select = [];
	$('.whitening_select'+dataid+' option').each(function(){
		whitening_select.push($(this).attr('title'));
	});

	$(".whitening_select"+dataid+" ul li").each(function(i){
	  $(this).attr('title',whitening_select[i]).tooltip();
	})
	
	var dryer_select = [];
	$('.dryer_select'+dataid+' option').each(function(){
		dryer_select.push($(this).attr('title'));
	});

	$(".dryer_select"+dataid+" ul li").each(function(i){
	  $(this).attr('title',dryer_select[i]).tooltip();
	})
	
	var ironing_select = [];
	$('.ironing_select'+dataid+' option').each(function(){
		ironing_select.push($(this).attr('title'));
	});

	$(".ironing_select"+dataid+" ul li").each(function(i){
	  $(this).attr('title',ironing_select[i]).tooltip();
	})
	
	var cleaning_select = [];
	$('.cleaning_select'+dataid+' option').each(function(){
		cleaning_select.push($(this).attr('title'));
	});

	$(".cleaning_select"+dataid+" ul li").each(function(i){
	  $(this).attr('title',cleaning_select[i]).tooltip();
	})
	
    var date = new Date();
    date.setDate(date.getDate() + 7);

    var dateMsg = (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();

    $("#clickme" + dataid).click(function () {
        
       if( $('.merchandisers option:selected').val() != "")
       {
            composition_percentage_global = 0;
		    if($('#composition_flag'+ dataid).val() != "disabled")
			{
				$('.composition_select' + dataid).prop('disabled', false)
				$('.composition_percent' + dataid).removeAttr('disabled')
            	$('.add_to_list' + dataid).removeAttr('disabled')
			}
            $('.parts_select' + dataid).prop('disabled', false)
            $(".parts_select" + dataid).val('');
            $('.parts_select' + dataid).selectpicker('refresh');
            $(".composition_select" + dataid).val('');
            $('.composition_select' + dataid).selectpicker('refresh');
            $(".composition_percent" + dataid).val('');
            $('#composition_table_qty' + dataid).html("")
            

            //$('.washlabelnameqty' + dataid).text($('.washlabelname' + dataid).text())
            $("#washcarelabelform" + dataid + " .backdrop").toggleClass('active');
            $("#washcarelabelform" + dataid + " .right-panel").toggleClass('active');
            $('#washcarelabelform' + dataid + ' .scalable').toggleClass('active');
            $("#washcarelabelform" + dataid + " .left-panelorc").toggleClass('active');
            $('.merchandiser_error').addClass('hidden')
       }
       else
       {
                $('.snackbar_span').html('Please Select Merchandiser');
                showSnackbar();
       }
		//alert()
		$('.tot_qty_tablewash').parent().removeClass('has-error').removeClass('has-danger')
        
    })

    $(".backdrop, #close").click(function () {
        $("#washcarelabelform" + dataid + " .left-panelorc").removeClass('active');
        $("#washcarelabelform" + dataid + " .right-panel").removeClass('active');
        $("#washcarelabelform" + dataid + " .backdrop").removeClass('active');
        $('#washcarelabelform' + dataid + ' .scalable').removeClass('active');
    })
	
	var mySVG_back = document.getElementById("washcare_svg_back" + dataid);
    var svgDoc_back;
    mySVG_back.addEventListener("load", function () {
		//alert()
        svgDoc_back = mySVG_back.contentDocument;
	
	if (data.orchestra_wash_care_label_orders != "") {
            if (dataid != "") {
                dataid1 = dataid;
            } else {
                dataid1 = 0;
            }

            if (data.orchestra_wash_care_label_orders.length > dataid1) {
				//alert()
                $('.care_instruction_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].care_instruction);
				svgDoc_back.getElementById("text_care_instruction").innerHTML = "";
				//$('.hiddencis' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].care_instruction);

				$(data.orchestra_wash_care_label_orders[dataid1].care_instruction).each(function (index,value) {
					//alert($('.care_instruction_select' + dataid + ' option[value="'+value+'"]').data('french'))

					$('select.hiddencis' + dataid).append('<option data-id="' + $(".care_instruction_select" + dataid + " option[value='"+value+"']").data('id') + '" data-french="' + $(".care_instruction_select" + dataid + " option[value='"+value+"']").data('french') + '" title="'+$(".care_instruction_select" + dataid + " option[value='"+value+"']").data('english')+'" data-english="' + $(".care_instruction_select" + dataid + " option[value='"+value+"']").data('english') + '" data-spanish="' + $(".care_instruction_select" + dataid + " option[value='"+value+"']").data('spanish') + '" data-portuguese="' + $(".care_instruction_select" + dataid + " option[value='"+value+"']").data('portuguese') + '" data-italian="' + $(".care_instruction_select" + dataid + " option[value='"+value+"']").data('italian') + '" data-dutch="' + $(".care_instruction_select" + dataid + " option[value='"+value+"']").data('dutch') + '" data-german="' + $(".care_instruction_select" + dataid + " option[value='"+value+"']").data('german')  + '" data-arabic="' + $(".care_instruction_select" + dataid + " option[value='"+value+"']").data('arabic') + '" data-greek="' + $(".care_instruction_select" + dataid + " option[value='"+value+"']").data('greek') + '" value="' + $(".care_instruction_select" + dataid + " option[value='"+value+"']").data('id') + '" selected>' + $(".care_instruction_select" + dataid + " option[value='"+value+"']").data('english') + '</option>')
					$('.hiddencis' + dataid).selectpicker('refresh');

				});


				
                svgDoc_back.getElementById("text_care_instruction").innerHTML = "";
                $('.hiddencis' + dataid + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german') + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek') + '</p>';
                    svgDoc_back.getElementById("text_care_instruction").innerHTML += value;
                });
            }
		
			svgDoc_back.getElementById("product_family").innerHTML = data.product_family.length > 0 ? data.product_family[0].arabic : "";

        }
		
		 $('.care_instruction_select' + dataid + '.bootstrap-select').on('changed.bs.select', function (e, clickedIndex, newValue, oldValue) {
			//alert()
			var hidden_id = $(this).find('option').eq(clickedIndex).val();


			var arr = $('.hiddencis' + dataid).selectpicker('val')

			//alert(hidden_id)

			if(arr.includes(hidden_id) == true)
			{
				$('.hiddencis' + dataid + ' option[value="'+hidden_id+'"]').remove();
				$('.hiddencis' + dataid).selectpicker('refresh');
			}
			else
			{
				$('select.hiddencis' + dataid).append('<option data-id="' + hidden_id + '" data-french="' + $(this).find('option').eq(clickedIndex).data('french') + '" title="'+$(this).find('option').eq(clickedIndex).data('english')+'" data-english="' + $(this).find('option').eq(clickedIndex).data('english') + '" data-spanish="' + $(this).find('option').eq(clickedIndex).data('spanish') + '" data-portuguese="' + $(this).find('option').eq(clickedIndex).data('portuguese') + '" data-italian="' + $(this).find('option').eq(clickedIndex).data('italian') + '" data-dutch="' + $(this).find('option').eq(clickedIndex).data('dutch') + '" data-german="' + $(this).find('option').eq(clickedIndex).data('german')  + '" data-arabic="' + $(this).find('option').eq(clickedIndex).data('arabic') + '" data-greek="' + $(this).find('option').eq(clickedIndex).data('greek') + '" value="' + hidden_id + '" selected>' + $(this).find('option').eq(clickedIndex).data('english') + '</option>')
				$('.hiddencis' + dataid).selectpicker('refresh');
			}

			//alert($(this).find('option').eq(clickedIndex).prop("checked") )
			//alert($(this).find('option').eq(clickedIndex).data('french'))
			 
            if ($('.hiddencis' + dataid + ' option:selected').val() != "") {
                svgDoc_back.getElementById("text_care_instruction").innerHTML = "";
                $('.hiddencis' + dataid + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek') + '</p>';
                    svgDoc_back.getElementById("text_care_instruction").innerHTML += value;
				});

				

            } else {

            }


        });

        var size = data.order_details[0].Size.split(" ")

        if (size[1] == "ANS") {
            var size1 = "years";
        } else {
            var size1 = "months";
        }

        if(size[0] == "TAILLE")
		{
        	svgDoc_back.getElementById("size_in_fr_en2").innerHTML = "";
			svgDoc_back.getElementById("size2").innerHTML = size;
		}
		else
		{
            svgDoc_back.getElementById("size_in_fr_en2").innerHTML = data.order_details[0].cm + "cm";
			svgDoc_back.getElementById("size2").innerHTML = data.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
		}

        svgDoc_back.getElementById("parcel_code2").innerHTML = data.order_details[0].UVC_TCO;
        svgDoc_back.getElementById("season_code").innerHTML = data.order_details[0].Season_Code

        svgDoc_back.getElementById("product_family").innerHTML = data.product_family[0].arabic;

        var code = data.order_details[0].BarcodeForPricesTags.toString();
        jsbarcodewash(code, 'barcode', svgDoc_back)
		
		}, false);

    var mySVG_middle = document.getElementById("washcare_svg_middle" + dataid);
    var svgDoc_middle;
    
    mySVG_middle.addEventListener("load", function () {

        svgDoc_middle = mySVG_middle.contentDocument;

        if (data.orchestra_wash_care_label_orders != "") {
            if (dataid != "") {
                dataid1 = dataid;
            } else {
                dataid1 = 0;
            }

            if (data.orchestra_wash_care_label_orders.length > dataid1) {
                var page_id = 0;
                var s_no =1;
        //var comps="";
        $.each(data.orchestra_wash_care_label_orders[dataid1].composition, function (index, value) {
            if (value.page_id != page_id) {
                var comps="";
                $.each(data.orchestra_wash_care_label_orders[dataid1].composition, function (index1, value1) {
                    if(value.page_id == value1.page_id)
                    {
                        if(comps != "")
                        {
                            comps+=","+value1.french+"_"+value1.english+"_"+value1.spanish+"_"+value1.portuguese+"_"+value1.italian+"_"+value1.dutch+"_"+value1.german+"_"+value1.arabic+"_"+value1.greek+"_"+value1.percentage;
                        }
                        else
                        {
                            comps+=value1.french+"_"+value1.english+"_"+value1.spanish+"_"+value1.portuguese+"_"+value1.italian+"_"+value1.dutch+"_"+value1.german+"_"+value1.arabic+"_"+value1.greek+"_"+value1.percentage;
                        }
                        
                    }
                    
                });
                
                add_page_composition += 1;
                $('#composition_remove_qty' + dataid).append('<tr id="' + add_page_composition + '"><td><label id="label_s_no_'+add_page_composition+'">'+ s_no +'</label>. Composition</td><td><input type="hidden" value="'+comps+'" class="compositions_hidden"><select class="form-control select_parts'+add_page_composition+'">' + parts_option + '</select></td><td><a style="cursor:pointer" onclick="deleteComposition(this,' + dataid + ')"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>')
                
                if(value.parts_id != "")
                {
                    $('.select_parts'+add_page_composition).val(value.parts_id)
                }
                
                
                var washcomp=[];
                $('#composition_remove_qty' + dataid + ' tr').each(function () {
                      var washcomp1=[];
                      var id=$(this).attr('id')
                      var value=$(this).find('select').find(':selected').val();
                      var compo =$(this).find('.compositions_hidden').val();
                      var compss = compo.split(',');
                        $(compss).each(function (index1,value1){ 
                            var english = value1.split('_');
                            washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                        });
                    washcomp.push(washcomp1)
                })
                
                
                var wash_finals = JSON.stringify(washcomp);
                localStorage.setItem('wash_composition_final'+dataid, wash_finals);
                
                if (value.parts_english != null) {
                    svgDoc_middle.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + add_page_composition + '" id="composition_' + add_page_composition + '">' + value.parts_french + "-" + value.parts_english + "-" + value.parts_spanish + "-" + value.parts_portuguese + "-" + value.parts_italian + "-" + value.parts_dutch + "-" + value.parts_german + "-" + value.parts_arabic + "-" + value.parts_greek + '</p>';
                }
                
                s_no++;
            }
            svgDoc_middle.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + add_page_composition + '" id="composition_' + add_page_composition + '">' + value.percentage + "% " + value.french + "-" + value.english + "-" + value.spanish + "-" + value.portuguese + "-" + value.italian + "-" + value.dutch + "-" + value.german  + "-" + value.arabic + "-" + value.greek + '</p>';

            page_id = value.page_id;

        });
            }
        }

        

    }, false);

    var mySVG = document.getElementById("washcare_svg_front" + dataid);
    var svgDoc;
    mySVG.addEventListener("load", function () {
        svgDoc = mySVG.contentDocument;

        //$('.washing_select'+dataid+' option[data-id="1"]').prop('selected', true)

        if (data.orchestra_wash_care_label_orders != "") {
            if (dataid != "") {
                dataid1 = dataid;
            } else {
                dataid1 = 0;
            }

            if (data.orchestra_wash_care_label_orders.length > dataid1) {
                $('.made_in_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].made_in_id);
                if(data.order_details[0].Activity_Label == "PUERICULTURE")
                {
                    var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('greek') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('arabic') + '</p>';
                }
                else
                {
                    var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" + $('select.made_in_select' + dataid).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + dataid).find(':selected').data('italian') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('greek') + '</p>';
                }
                
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;
				
                $('.washing_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].washing_id);

                $('.merchandisers').selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].merchandisers_id);

                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('astm'));
                $('.whitening_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].whitening_id);
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('astm'));
                $('.dryer_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].dryer_id);
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('astm'));
                $('.ironing_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].ironing_id);
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('astm'));
                $('.cleaning_select' + dataid).selectpicker('val', data.orchestra_wash_care_label_orders[dataid1].cleaning_id);
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('astm'));

                
            }

        }


        $('.washing_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.washing_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', "");
            }


        });
        $('.whitening_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.whitening_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', "");
            }
        });
        $('.dryer_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.dryer_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', "");
            }

        });
        $('.ironing_select' + dataid + '.bootstrap-select').change(function () {
            if ($('.ironing_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', "");
            }

        });
        $('.cleaning_select' + dataid + '.bootstrap-select').change(function () {
            if ($('.cleaning_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', "");
            }

        });
        $('.made_in_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.made_in_select' + dataid + ' option:selected').val() != "") {

                if(data.order_details[0].Activity_Label == "PUERICULTURE")
                {
                    var text_for_mad_in =  $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('greek') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('arabic');
                }
                else
                {
                    var text_for_mad_in =  $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" + $('select.made_in_select' + dataid).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + dataid).find(':selected').data('italian') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('greek');
                }
                
				//console.log(text_for_mad_in);
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;

            } else {

            }


        });

       


        var size = data.order_details[0].Size.split(" ")

        if (size[1] == "ANS") {
            var size1 = "years";
        } else {
            var size1 = "months";
        }
        svgDoc.getElementById("parcel_code1").innerHTML = data.order_details[0].UVC_TCO;
        //svgDoc.getElementById("product_family").innerHTML = data.product_family[0].arabic;
		
		//alert(data.order_details[0].Size)
		
		
		if(size[0] == "TAILLE")
		{
			svgDoc.getElementById("size_in_fr_en1").innerHTML = "";
			svgDoc.getElementById("size1").innerHTML = size;
		}
		else
		{
			svgDoc.getElementById("size_in_fr_en1").innerHTML = data.order_details[0].cm + "cm";
			svgDoc.getElementById("size1").innerHTML = data.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
		}
    }, false);
}


/**
 * This Function is Sub Function of #washcarelabels.
 * To add a compoistion percent into list
 * @return composition list
 */

function add_to_list(dataid) {
	
	
	if (dataid === undefined) {
		dataid = "";
	} else {
		dataid = dataid;
	}

	if ($('.composition_select' + dataid + ' option:selected').val() != "Select Option" && $('.composition_percent' + dataid).val() != "") {

		composition_percentage_global += parseInt($('.composition_percent' + dataid).val());

		if (composition_percentage_global <= "100") {

			composition_percentage_global = composition_percentage_global;
			$('#composition_table_qty' + dataid).append("<tr><td class='comp'><input type='hidden' class='list_comp' value='" + $('.composition_select' + dataid + ' option:selected').attr('data-french') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-english') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-spanish') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-portuguese') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-italian') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-dutch') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-german') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-arabic') + "_" + $('.composition_select' + dataid + ' option:selected').attr('data-greek') + "_" + parseInt($('.composition_percent' + dataid).val()) + "'>" + $('.composition_select' + dataid + ' option:selected').text() + "</td><td class='percent'>" + parseInt($('.composition_percent' + dataid).val()) + "</td></tr>")
		} else {
			composition_percentage_global -= parseInt($('.composition_percent' + dataid).val());
		}

		$('.parts_select' + dataid).prop('disabled', 'true')
		$('#composition_error_required' + dataid).addClass('hidden')
		$('#percentage_error_required' + dataid).addClass('hidden')
	}
	else if ($('.composition_select' + dataid + ' option:selected').val() == "Select Option")
	{
		$('#composition_error_required' + dataid).removeClass('hidden')
		$('#percentage_error_required' + dataid).addClass('hidden')
		
	}
	else if($('.composition_percent' + dataid).val() == "")
	{
		$('#percentage_error_required' + dataid).removeClass('hidden')
		$('#composition_error_required' + dataid).addClass('hidden')
	}
	if (composition_percentage_global == "100") {
		$('.composition_select' + dataid).prop('disabled', 'true')
		$('.composition_percent' + dataid).prop('disabled', 'true')
		$('.add_to_list' + dataid).prop('disabled', 'true')
		$('.add_to_composition' + dataid).removeAttr('disabled')
	}
	$('#wash_composition_error' + dataid).addClass('hidden')

}

/**
 * This Function is Sub Function of #washcarelabels.
 * To add a compoistion into tables
 * @return view
 */

function add_to_composition(dataid) {
    add_page_composition += 1;
    $('#wash_composition_error' + dataid).addClass('hidden')
    if (dataid === undefined) {
        dataid = "";
    } else {
        dataid = dataid;
    }
    composition_percentage_global = 0;
    var mySVG = document.getElementById("washcare_svg_middle" + dataid);
    var svgDoc = mySVG.contentDocument;
    var a = [];
    var com = [];
     //var com_ajax = [];
    $('#composition_table_qty' + dataid + ' tr').each(function () {
        var comp = $(this).find('.list_comp').val();
        var comp1 = comp.split('_');
        var percent = $(this).find('.percent').text();
        var parts = $('.parts_select' + dataid + ' option:selected').val()
        a.push(comp1[0]+"_"+comp1[1]+"_"+comp1[2]+"_"+comp1[3]+"_"+comp1[4]+"_"+comp1[5]+"_"+comp1[6]+"_"+comp1[7]+"_"+comp1[8]+"_"+comp1[9])
       
    })
    comps = a.join(',');
    if (localStorage.getItem('wash_composition') != null) {
        var str = localStorage.getItem('wash_composition');
        var n = str.includes('wash_composition' + dataid);
        if (n == false) {
            wash_composition['wash_composition' + dataid] = {};
        }
    } else {
        wash_composition['wash_composition' + dataid] = {};
    }

    wash_composition['wash_composition' + dataid][add_page_composition] = com;
    var wash_comp = JSON.stringify(wash_composition);
    localStorage.setItem('wash_composition', wash_comp);

    
    var buyer = localStorage.getItem("buyer");
    if($('#composition_remove_qty'+dataid+' tr:last td:first label').text() != null && $('#composition_remove_qty'+dataid+' tr:last td:first label').text() != "")
    {
        var s_no = parseInt($('#composition_remove_qty'+dataid+' tr:last td:first label').text()) + 1;
    }
    else
    {
        var s_no = 1;
    }


            $('#composition_remove_qty' + dataid).append('<tr id="' + add_page_composition + '"><td><label id="label_s_no_'+add_page_composition+'">'+ s_no +'</label>. Composition</td><td><input type="hidden" value="'+comps+'" class="compositions_hidden">'+$('.parts_none'+ dataid).html()+'</td><td><a style="cursor:pointer" onclick="deleteComposition(this,' + dataid + ')"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>')

            $(".add_to_list" + dataid).prop("disabled", false);
            $('.composition_select' + dataid).prop('disabled', false)
            $('.composition_percent' + dataid).prop('disabled', false)
            $('#composition_table_qty' + dataid).html("")
            $('.parts_select' + dataid).prop('disabled', false)
            $('.add_to_composition' + dataid).attr('disabled', 'disabled')
            $('.composition_percent' + dataid).val("");
            $(".composition_select" + dataid).val('');
            $('.composition_select' + dataid).selectpicker('refresh');
            $(".parts_select" + dataid).val('');
            $('.parts_select' + dataid).selectpicker('refresh');
			$('#wash_composition_error' + dataid).addClass('hidden')

}

/**
 * This Function is Sub Function of #washcarelabels.
 * To add a compoistion percent into artwork
 * @return view
 */

function add_to_page(dataid) {
  if (dataid === undefined) {
    dataid = "";
  } else {
    dataid = dataid;
  } 
    var mySVG = document.getElementById("washcare_svg_middle" + dataid);
    var svgDoc = mySVG.contentDocument;
    
    var check=0;
    var check1=0;
     $('#composition_remove_qty' + dataid + ' tr').each(function () {
         
         if($(this).find('select').find(':selected').text() == "Select Option")
             {
                 check++;
             }
         check1++;
     });
    
    if(check1 <2 )
    {
        check=0;
    }
    
    if(check == 0 )
        {
              svgDoc.getElementById("parts_composition").innerHTML ="";
              $('#composition_remove_qty' + dataid + ' tr').each(function () {
                  if($(this).find('select').find(':selected').val() != "Select Option")
                  {
                       svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german') + "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek')  + '</p>';
                  }
                 
                  var id=$(this).attr('id')
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                  $(compss).each(function (index1,value1) {
                      var english = value1.split('_');
					  

                    svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
                });
              })
            $('.composition_error_hidden').addClass('hidden')
            var washcomp=[];
            $('#composition_remove_qty' + dataid + ' tr').each(function () {
                  var washcomp1=[];
                  var id=$(this).attr('id')
                  var value=$(this).find('select').find(':selected').val();
                  var compo =$(this).find('.compositions_hidden').val();
                  var compss = compo.split(',');
                    $(compss).each(function (index1,value1){ 
                        var english = value1.split('_');
                        washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                    });
                washcomp.push(washcomp1)
            })
            var wash_finals = JSON.stringify(washcomp);
            localStorage.setItem('wash_composition_final'+dataid, wash_finals);
        }
    else
        {
            $('.composition_error_hidden').removeClass('hidden')
        }
		$('#wash_composition_page_error' + dataid).addClass('hidden')
}


/**
 * This Function is Sub Function of #washcarelabels.
 * To delete compoistion percent into artwork
 * @return view
 */

function deleteComposition(obj, dataid) {

	// add_page_composition-=1;
	if (dataid === undefined) {
		dataid = "";
	} else {
		dataid = dataid;
	}
	$(obj).parent().parent().remove();
	var classname = $(obj).parent().parent().attr('id');
	var mySVG = document.getElementById("washcare_svg_middle" + dataid);
	var svgDoc = mySVG.contentDocument;
	var list = svgDoc.getElementsByClassName("composition_" + classname);
	for (var i = list.length - 1; 0 <= i; i--) {
		list[i].remove();
	}
	var wash = localStorage.getItem('wash_composition');
	var wash_fin = JSON.parse(wash);
	//alert(add_page_composition)
	delete wash_fin['wash_composition' + dataid][classname];
	delete wash_composition['wash_composition' + dataid][classname];
	var wash_final = JSON.stringify(wash_fin);
	localStorage.setItem('wash_composition', wash_final);
}


/**
 * This Function is used Print Wash Care Labels.
 *  
 * @return Pdf
 */

function printWashDiv(data,data_back,data_middle,dataid,user_type,activity_label) {
	var a1 = document.getElementById(data);
	var svgDoc1 = a1.contentDocument;
	var svgRoot1 = svgDoc1.documentElement;

    var a1_back = document.getElementById(data_back);
    var svgDoc1_back = a1_back.contentDocument;
    var svgRoot1_back = svgDoc1_back.documentElement;

	var index_arr=[];
	var svgarr = [];
	var svgarr_back = [];
	var i=0;
	var ajax=0;
		//alert('wash_care_label_table_'+dataid)
	$('#wash_care_label_table_'+dataid+' tbody tr').each(function (key, val) {
		if(index_arr.includes($(this).find('.size').text()) != true)
		{
			if($(this).find('.size').text() != "")
				{
					var size_txt = $(this).find('.size').text();
					var uvc_tco = $(this).find('.uvc_tco').text();
					index_arr.push($(this).find('.size').text())
					var size = $(this).find('.size').text().split(" ")

					if (size[1] == "ANS") {
						var size1 = "years";
					} else {
						var size1 = "months";
					}
					
					var code = $(this).find('.barcode').text();
					
					$.ajax({
						url: base_url + "/"+user_type+"/order-management/get-tsl-size",
						type: 'GET',
						async: false,
						data: {
							size: $(this).find('.size').text()
						},
						tryCount: 0,
						retryLimit: 2,
						success: function (data) {
							//console.log(data)

                            //console.log(uvc_tco)
							if(size[0] == "TAILLE")
							{
								svgDoc1.getElementById("size1").innerHTML = size_txt;
								svgDoc1_back.getElementById("size2").innerHTML = size_txt;
								svgDoc1.getElementById("size_in_fr_en1").innerHTML = "";
								svgDoc1_back.getElementById("size_in_fr_en2").innerHTML = "";
							}
							else
							{
								if(data.size_chart.length > 0 )
								{
									svgDoc1.getElementById("size_in_fr_en1").innerHTML = data.size_chart[0].size+" cm ";
									svgDoc1_back.getElementById("size_in_fr_en2").innerHTML = data.size_chart[0].size+" cm ";
								}
								else
								{
									svgDoc1.getElementById("size_in_fr_en1").innerHTML = "";
									svgDoc1_back.getElementById("size_in_fr_en2").innerHTML = "";
								}
	
								svgDoc1.getElementById("size1").innerHTML = size_txt.replace(" ", "").toLowerCase() + "/" + size1;
								svgDoc1_back.getElementById("size2").innerHTML = size_txt.replace(" ", "").toLowerCase() + "/" + size1;
							}
							
							svgDoc1.getElementById("parcel_code1").innerHTML = uvc_tco;
							svgDoc1_back.getElementById("parcel_code2").innerHTML = uvc_tco;

                            svgDoc1.getElementById("cofreet_symbol1").setAttribute('xlink:href', "");
                            svgDoc1.getElementById("astm_symbol1").setAttribute('xlink:href', "");
                            svgDoc1.getElementById("cofreet_symbol2").setAttribute('xlink:href', "");
                            svgDoc1.getElementById("astm_symbol2").setAttribute('xlink:href', "");
                            svgDoc1.getElementById("cofreet_symbol3").setAttribute('xlink:href', "");
                            svgDoc1.getElementById("astm_symbol3").setAttribute('xlink:href', "");
                            svgDoc1.getElementById("cofreet_symbol4").setAttribute('xlink:href', "");
                            svgDoc1.getElementById("astm_symbol4").setAttribute('xlink:href', "");
                            svgDoc1.getElementById("cofreet_symbol5").setAttribute('xlink:href', "");
                            svgDoc1.getElementById("astm_symbol5").setAttribute('xlink:href', "");


                            if(activity_label == "PUERICULTURE")
                            {
                                svgDoc1_back.getElementById("barcode").setAttribute('transform', 'matrix(0.8899 0 0 1.1092 -37.5 144) rotate(270)');
                            }
                            else
                            {
                                svgDoc1_back.getElementById("barcode").setAttribute('transform', 'matrix(0.8899 0 0 1.1092 -37.5 154) rotate(270)');
                            }

                            
                            jsbarcodewash(code, 'barcode', svgDoc1_back)


                            var s_back = new XMLSerializer();
							var d_back = svgRoot1_back;
							var str_back = s_back.serializeToString(d_back);
							var svg_back = encodeURI(str_back)
							svgarr_back.push(svg_back)
							


							var s = new XMLSerializer();
							var d = svgRoot1;
							var str = s.serializeToString(d);
							var svg = encodeURI(str)
							svgarr.push(svg)
							ajax++;
						},
						error: function (errorXHR, errorStatus, errorThrown) {
							console.log(errorThrown);
						},
						complete: function () {

						}
					})
					
				i++;	
				}
			
		}
	})

    var washing_select = $('.washing_select' + dataid + ' option:selected').val();
    var whitening_select = $('.whitening_select' + dataid + ' option:selected').val();
    var dryer_select = $('.dryer_select' + dataid + ' option:selected').val();
    var ironing_select = $('.ironing_select' + dataid + ' option:selected').val();
    var cleaning_select = $('.cleaning_select' + dataid + ' option:selected').val();
	
	//alert(i)
	//console.log(index_arr)
	
	
	
	
	var a1_middle = document.getElementById(data_middle);
	var svgDoc1_middle = a1_middle.contentDocument;
	var svgRoot1_middle = svgDoc1_middle.documentElement;
	var svg_fin = svgarr.join(',_');
	var svg_fin_back = svgarr_back.join(',_');
	
	if(i == ajax)
	{
		PopupWash(svg_fin,svgRoot1_back,svgRoot1_middle, dataid,svgDoc1,user_type,svg_fin_back,svgDoc1_back,washing_select,whitening_select,dryer_select,ironing_select,cleaning_select,activity_label);
	}
	
}


/**
 * This Function is used Cancel Wash Care Label.
 *  
 * @return Boolean Value
 */

function cancelWashDiv(dataid,factorycode,user_type){
	//var id = $('#size_labelform').find('#exist_id').val();
	
	var po_select 	  = $('#po_select' + dataid).val();
	if(factorycode == "HF-FREEUSD")
	{
		var id 	  = $('#'+po_select+'_'+dataid).attr("data-exist");
	}
	else
	{
		var id 	  = $('#washcarelabelform'+dataid).find('#exist_id'+dataid).val();
	}
	
	
	
	var buyer = localStorage.getItem("buyer");
	$.ajax({
		url: base_url + "/"+user_type+"/" + buyer + "/order-management/remove_wcl",
		type: "Post",
		data: {
			id: id,
			_token: csrf_token
		},
		beforeSend: function () {
			$(".loader").show();
		},
		success: function (data) {
			if(data.update_po_disable == 0)
			{
				$('#po-file-upload-button').css('display','none')
			}
			
			if(factorycode == "HF-FREEUSD")
			{
				$('#'+po_select+'_'+dataid).val("0")
				if(data.orchestra_wcl.length == 0)
				{
					$('#wcl_name'+dataid).removeAttr('disabled');
                    $('#wcl_name'+dataid).val('');
                    $('#ocn_number_wash'+dataid).removeAttr('disabled');
					$('#ocn_number_wash'+dataid).val('');
					$('.washing_select'+dataid).removeAttr('disabled');
					$('.washing_select'+dataid).val('');
					$('.whitening_select'+dataid).removeAttr('disabled');
					$('.whitening_select'+dataid).val('');
					$('.dryer_select'+dataid).removeAttr('disabled');
					$('.dryer_select'+dataid).val('');
					$('.ironing_select'+dataid).removeAttr('disabled');
					$('.ironing_select'+dataid).val('');
					$('.cleaning_select'+dataid).removeAttr('disabled');
					$('.cleaning_select'+dataid).val('');
					$('.made_in_select'+dataid).removeAttr('disabled');
					$('.made_in_select'+dataid).val('');
					$('.care_instruction_select'+dataid).removeAttr('disabled');
					$('.care_instruction_select'+dataid).val('');
					$('.hiddencis'+dataid).val('');
					$('.hiddencis'+dataid).find('option').remove()
					$('.composition_select'+dataid).removeAttr('disabled');
					$('.composition_select'+dataid).val('');
					$('.composition_percent'+dataid).removeAttr('disabled');
					$('.composition_percent'+dataid).val('');
					$('.add_to_list'+dataid).removeAttr('disabled');
					$('.add_to_page'+dataid).removeAttr('disabled');
					$('#composition_remove_qty'+dataid).html('');
					$('.wash-card'+dataid).find('span').text('');
					$('.wash-card'+dataid).find('.washlabelname'+dataid).text('Washcare Label');
					$('.wash-card'+dataid).find('span').removeClass('label-OrderApproved');
					$('#washcarelabelform'+dataid).find('span').text('');
					$('#washcarelabelform'+dataid).find('span').removeClass('label-OrderApproved');
					$('.selectpicker').selectpicker('refresh');
					$('#washcare_label_card'+dataid).addClass('hidden');
					$('#clone_washcare_label_card'+dataid).addClass('hidden');
				}
			}
			else
			{
				$('#wcl_name'+dataid).removeAttr('disabled');
                $('#wcl_name'+dataid).val('');
                $('#ocn_number_wash'+dataid).removeAttr('disabled');
				$('#ocn_number_wash'+dataid).val('');
				$('.washing_select'+dataid).removeAttr('disabled');
				$('.washing_select'+dataid).val('');
				$('.whitening_select'+dataid).removeAttr('disabled');
				$('.whitening_select'+dataid).val('');
				$('.dryer_select'+dataid).removeAttr('disabled');
				$('.dryer_select'+dataid).val('');
				$('.ironing_select'+dataid).removeAttr('disabled');
				$('.ironing_select'+dataid).val('');
				$('.cleaning_select'+dataid).removeAttr('disabled');
				$('.cleaning_select'+dataid).val('');
				$('.made_in_select'+dataid).removeAttr('disabled');
				$('.made_in_select'+dataid).val('');
				$('.care_instruction_select'+dataid).removeAttr('disabled');
				$('.care_instruction_select'+dataid).val('');
				$('.hiddencis'+dataid).val('');
				$('.hiddencis'+dataid).find('option').remove()
				$('.composition_select'+dataid).removeAttr('disabled');
				$('.composition_select'+dataid).val('');
				$('.composition_percent'+dataid).removeAttr('disabled');
				$('.composition_percent'+dataid).val('');
				$('.add_to_list'+dataid).removeAttr('disabled');
				$('.add_to_page'+dataid).removeAttr('disabled');
				$('#composition_remove_qty'+dataid).html('');
				$('.wash-card'+dataid).find('span').text('');
				$('.wash-card'+dataid).find('.washlabelname'+dataid).text('Washcare Label');
				$('.wash-card'+dataid).find('span').removeClass('label-OrderApproved');
				$('#washcarelabelform'+dataid).find('span').text('');
				$('#washcarelabelform'+dataid).find('span').removeClass('label-OrderApproved');
				$('.selectpicker').selectpicker('refresh');
				$('#washcare_label_card'+dataid).addClass('hidden');
				$('#clone_washcare_label_card'+dataid).addClass('hidden');
			}
			$("#washcarelabelform"+dataid+" .left-panelorc").removeClass('active');
			$("#washcarelabelform"+dataid+" .right-panel").removeClass('active');
			$("#washcarelabelform"+dataid+" .backdrop").removeClass('active');
			$('#washcarelabelform'+dataid+' .scalable').removeClass('active');
			$('#cancel_wash_button_'+dataid).addClass('hidden');
			$('#print_wash_button_'+dataid).addClass('hidden');
			$('#save_wash_button_'+dataid).removeClass('hidden');
			$('#washcarelabelform'+dataid).find('.tot_qty_tablewash').removeAttr('disabled');
			$('#washcarelabelform'+dataid).find('.tot_qty_tablewash').val('');
			$('#washcarelabelform'+dataid).find('.extra_percentagewash').removeAttr('disabled');
			$('#washcarelabelform'+dataid).find('.extra_percentagewash').val('');
			
		},
		error: function (data) {

		}
	})
}


/**
 * This Function is Sub Function of #printWashDiv.
 *  
 * @return Pdf
 */

function PopupWash(data,data_back,data_middle,dataid,svgDoc1,user_type,data_fin_back,svgDoc1_back,washing_select,whitening_select,dryer_select,ironing_select,cleaning_select,activity_label) {
	
    if (dataid != undefined) {
        dataid=dataid;
    }
    else
    {
        dataid="";
    }
      var s = new XMLSerializer();
//    var d = data;
//    var str = s.serializeToString(d);
//    var svg = encodeURI(str)
	
	
	var d_back = data_back;
    var str_back = s.serializeToString(d_back);
    var svg_back = encodeURI(str_back)
	
	var d_middle = data_middle;
    var str_middle = s.serializeToString(d_middle);
	var svg_middle = encodeURI(str_middle)
	
	var id = $('#exist_id'+dataid).val()
	
    var success = false;
    var url = "";
    var made_in_select = $('.made_in_select' + dataid + ' option:selected').attr('data-id');
    
    var care_instruction = $('.hiddencis' + dataid + ' option:selected');
        var care_instruction_select = [];
        $(care_instruction).each(function (index, brand) {
            care_instruction_select.push([$(this).val()]);
        });
        var care_instruction_select_fin = care_instruction_select.join(',');
    var wash_finals = localStorage.getItem('wash_composition_final'+dataid);
    
    $.ajax({
        url: base_url + "/"+user_type+"/order-management/print-wash-pdf",
        type: "post",
        data: {
            _token: csrf_token,
			made_in_select:made_in_select,
			id:id,
            care_instruction_select_fin:care_instruction_select_fin,
            composition:wash_finals,
            svg: data,
            svg_back: svg_back,
            svg_middle: svg_middle,
            data_fin_back:data_fin_back,
            washing_select:washing_select,
            whitening_select:whitening_select,
            dryer_select:dryer_select,
            ironing_select:ironing_select,
            cleaning_select:cleaning_select,
            activity_label:activity_label
        },
        async: false,
        success: function (data) {
			svgDoc1_back.getElementById("barcode").setAttribute('transform', 'matrix(0.8899 0 0 1.1092 -21 138.454) rotate(270)');
            success = true;
            url = data;
        },
        error: function (data) {

        },
    })
    if (success) { //AND THIS CHANGED
        window.open(url)
    }
}

/**
 * This Function is used to invoke Jstree.
 * 
 * @return tree
 */

function treestructurewash(data,dataid, tree, template_id,datas,user_type) {
	
	$('#' + tree).jstree({
		'core': {
			'data': data
		},
		"types": {
			"default": {
				"icon": "fa fa-folder"
			}
		},
		"plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
	});
	$('#expand').bind("click", function () {
		$('#' + tree).jstree("open_all");
	});
	$('#collapse').bind("click", function () {
		$('#' + tree).jstree("close_all");
	});
	var to = false;
	$('#' + tree + '_q').keyup(function () {
		if (to) {
			clearTimeout(to);
		}
		to = setTimeout(function () {
			var v = $('#' + tree + '_q').val();
			$('#' + tree).jstree(true).search(v);
		}, 250);
	});

	$("#" + tree).on("loaded.jstree", function (e, data) {

		$('#' + tree).jstree(true).select_node(template_id);
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (template_id != "") {
			var buyer = localStorage.getItem("buyer");
			
			$.ajax({
				url: base_url + "/"+user_type+"/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					if (data.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<object style="height: calc( 100vh - 125px );" id="washcare_svg_front'+dataid+'" class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
						
							var mySVG = document.getElementById("washcare_svg_front"+dataid);
							var svgDoc;
							mySVG.addEventListener("load", function () {
								svgDoc = mySVG.contentDocument;
								
								
								var size = datas.order_details[0].Size.split(" ")

								if (size[1] == "ANS") {
									var size1 = "years";
								} else {
									var size1 = "months";
								}
								
								svgDoc.getElementById("parcel_code1").innerHTML = datas.order_details[0].UVC_TCO;
								svgDoc.getElementById("product_family").innerHTML = datas.product_family.length > 0 ? datas.product_family[0].arabic : "";
								svgDoc.getElementById("parcel_code2").innerHTML = datas.order_details[0].UVC_TCO;
								
								
		if(size[0] == "TAILLE")
		{
			//var inches=$('#washcarelabelform' + dataid+' .washonesizein').val()
			//var cm=$('#washcarelabelform' + dataid+' .washonesizecm').val()
			
			svgDoc.getElementById("size_in_fr_en1").innerHTML = "";
        svgDoc.getElementById("size_in_fr_en2").innerHTML = "";
			svgDoc.getElementById("size1").innerHTML = size;
			svgDoc.getElementById("size2").innerHTML = size;
		}
		else
		{
			svgDoc.getElementById("size_in_fr_en1").innerHTML = datas.order_details[0].cm + "cm / " + datas.order_details[0].in + "in";
			svgDoc.getElementById("size_in_fr_en2").innerHTML = datas.order_details[0].cm + "cm / " + datas.order_details[0].in + "in";
			svgDoc.getElementById("size1").innerHTML = datas.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
			svgDoc.getElementById("size2").innerHTML = datas.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
		}
								

								var code = datas.order_details[0].BarcodeForPricesTags.toString();
								jsbarcodewash(code, 'barcode', svgDoc)
								
								//svgDoc.getElementById("size1").innerHTML = "text_for_mad_in";
								if ($('.washing_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.whitening_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.dryer_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.ironing_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.cleaning_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.made_in_select' + dataid + ' option:selected').val() != "") {

                                    if(datas.order_details[0].Activity_Label == "PUERICULTURE")
                                    {
                                        var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german')  + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" +
										$('select.made_in_select' + dataid).find(':selected').data('greek') + "-" +
										$('select.made_in_select' + dataid).find(':selected').data('arabic') + '</p>';
                                    }
                                    else
                                    {
                                        var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" + $('select.made_in_select' + dataid).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + dataid).find(':selected').data('italian') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german')  + "-" +
										$('select.made_in_select' + dataid).find(':selected').data('arabic') + "-" +
										$('select.made_in_select' + dataid).find(':selected').data('greek') + '</p>';
                                    }
									 
									svgDoc.getElementById("country").innerHTML = text_for_mad_in;
								}

									svgDoc.getElementById("text_care_instruction").innerHTML = "";
									$('.hiddencis' + dataid + ' option:selected').each(function () {
										var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german') + "-" +
											$(this).data('arabic') + "-" +
											$(this).data('greek')  + '</p>';
										svgDoc.getElementById("text_care_instruction").innerHTML += value;
									});

									 svgDoc.getElementById("parts_composition").innerHTML ="";
								  $('#composition_remove_qty' + dataid + ' tr').each(function () {
									  if($(this).find('select').find(':selected').val() != "Select Option")
									  {
										   svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german')+ "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek') + '</p>';
									  }

									  var id=$(this).attr('id')
									  var compo =$(this).find('.compositions_hidden').val();
									  var compss = compo.split(',');
									  $(compss).each(function (index1,value1) {
										  var english = value1.split('_');

										svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
									});
								  })
								  
        $('.washing_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.washing_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', "");
            }


        });
        $('.whitening_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.whitening_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', "");
            }
        });
        $('.dryer_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.dryer_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', "");
            }

        });
        $('.ironing_select' + dataid + '.bootstrap-select').change(function () {
            if ($('.ironing_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', "");
            }

        });
        $('.cleaning_select' + dataid + '.bootstrap-select').change(function () {
            if ($('.cleaning_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', "");
            }

        });
        $('.made_in_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.made_in_select' + dataid + ' option:selected').val() != "") {

                if(datas.order_details[0].Activity_Label == "PUERICULTURE")
                {
                    var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german')  + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('greek') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('arabic')  + '</p>';
                }
                else
                {
                    var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" + $('select.made_in_select' + dataid).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + dataid).find(':selected').data('italian') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german')  + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('greek')  + '</p>';
                }
                
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;

            } else {

            }


        });

        $('.care_instruction_select' + dataid + '.bootstrap-select').on('changed.bs.select', function (e, clickedIndex, newValue, oldValue) {

			var hidden_id = $(this).find('option').eq(clickedIndex).val();


			var arr = $('.hiddencis' + dataid).selectpicker('val')

			//alert(hidden_id)

			if(arr.includes(hidden_id) == true)
			{
				$('.hiddencis' + dataid + ' option[value="'+hidden_id+'"]').remove();
				$('.hiddencis' + dataid).selectpicker('refresh');
			}
			else
			{
				$('select.hiddencis' + dataid).append('<option data-id="' + hidden_id + '" data-french="' + $(this).find('option').eq(clickedIndex).data('french') + '" title="'+$(this).find('option').eq(clickedIndex).data('english')+'" data-english="' + $(this).find('option').eq(clickedIndex).data('english') + '" data-spanish="' + $(this).find('option').eq(clickedIndex).data('spanish') + '" data-portuguese="' + $(this).find('option').eq(clickedIndex).data('portuguese') + '" data-italian="' + $(this).find('option').eq(clickedIndex).data('italian') + '" data-dutch="' + $(this).find('option').eq(clickedIndex).data('dutch') + '" data-german="' + $(this).find('option').eq(clickedIndex).data('german')  + '" data-arabic="' + $(this).find('option').eq(clickedIndex).data('arabic') + '" data-greek="' + $(this).find('option').eq(clickedIndex).data('greek') + '" value="' + hidden_id + '" selected>' + $(this).find('option').eq(clickedIndex).data('english') + '</option>')
				$('.hiddencis' + dataid).selectpicker('refresh');
			}
			
            if ($('.hiddencis' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("text_care_instruction").innerHTML = "";
                $('.hiddencis' + dataid + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german') + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek')  + '</p>';
                    svgDoc.getElementById("text_care_instruction").innerHTML += value;
                });


            } else {

            }


        });
							});
						
						
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});

	$('#' + tree).on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		if (data.instance.is_leaf(data.node)) {
			var buyer = localStorage.getItem("buyer");
			var template_id = data.node.id;
			
			$.ajax({
				url: base_url + "/"+user_type+"/" + buyer + "/template-management/template-view",
				type: "Post",
				dataType: "json",
				data: {
					template_id: template_id,
					_token: csrf_token
				},
				beforeSend: function () {
					$(".loader").show();
				},
				success: function (data) {
					
					if (data.length > 0) {
						$('#' + ids).find('.sample-artwork').html('<object style="height: calc( 100vh - 125px );"  id="washcare_svg_front'+dataid+'"  class="center-block svg2" data="' + data[0].template_file + '" type="image/svg+xml"></object>')
						
						
						var mySVG = document.getElementById("washcare_svg_front"+dataid);
							var svgDoc;
							mySVG.addEventListener("load", function () {
								svgDoc = mySVG.contentDocument;
								
								
								var size = datas.order_details[0].Size.split(" ")

								if (size[1] == "ANS") {
									var size1 = "years";
								} else {
									var size1 = "months";
								}
								
								svgDoc.getElementById("parcel_code1").innerHTML = datas.order_details[0].UVC_TCO;
								svgDoc.getElementById("product_family").innerHTML = datas.product_family.length > 0 ? datas.product_family[0].arabic : "";
								svgDoc.getElementById("parcel_code2").innerHTML = datas.order_details[0].UVC_TCO;
								if(size[0] == "TAILLE")
								{
									//var inches=$('#washcarelabelform' + dataid+' .washonesizein').val()
									//var cm=$('#washcarelabelform' + dataid+' .washonesizecm').val()

									svgDoc.getElementById("size_in_fr_en1").innerHTML = "";
								svgDoc.getElementById("size_in_fr_en2").innerHTML = "";
									svgDoc.getElementById("size1").innerHTML = size;
									svgDoc.getElementById("size2").innerHTML = size;
								}
								else
								{
									svgDoc.getElementById("size_in_fr_en1").innerHTML = datas.order_details[0].cm + "cm";
									svgDoc.getElementById("size_in_fr_en2").innerHTML = datas.order_details[0].cm + "cm";
									svgDoc.getElementById("size1").innerHTML = datas.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
									svgDoc.getElementById("size2").innerHTML = datas.order_details[0].Size.replace(" ", "").toLowerCase() + "/" + size1;
								}


								var code = datas.order_details[0].BarcodeForPricesTags.toString();
								jsbarcodewash(code, 'barcode', svgDoc)
								
								//svgDoc.getElementById("size1").innerHTML = "text_for_mad_in";
								if ($('.washing_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.whitening_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.dryer_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.ironing_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.cleaning_select' + dataid + ' option:selected').val() != "") {
									svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('cofreet'));
									svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('astm'));
								}
								if ($('.made_in_select' + dataid + ' option:selected').val() != "") {

                                    if(datas.order_details[0].Activity_Label == "PUERICULTURE")
                                    {
                                        var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" +
										$('select.made_in_select' + dataid).find(':selected').data('greek') + "-" +
										$('select.made_in_select' + dataid).find(':selected').data('arabic')  + '</p>';
                                    }
                                    else
                                    {
                                        var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" + $('select.made_in_select' + dataid).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + dataid).find(':selected').data('italian') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german') + "-" +
										$('select.made_in_select' + dataid).find(':selected').data('arabic') + "-" +
										$('select.made_in_select' + dataid).find(':selected').data('greek')  + '</p>';
                                    }
									 
									svgDoc.getElementById("country").innerHTML = text_for_mad_in;
								}

									svgDoc.getElementById("text_care_instruction").innerHTML = "";
									$('.hiddencis' + dataid + ' option:selected').each(function () {
										var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
											$(this).data('arabic') + "-" +
											$(this).data('greek') + '</p>';
										svgDoc.getElementById("text_care_instruction").innerHTML += value;
									});

									 svgDoc.getElementById("parts_composition").innerHTML ="";
								  $('#composition_remove_qty' + dataid + ' tr').each(function () {
									  if($(this).find('select').find(':selected').val() != "Select Option")
									  {
										   svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german')  + "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek')  + '</p>';
									  }

									  var id=$(this).attr('id')
									  var compo =$(this).find('.compositions_hidden').val();
									  var compss = compo.split(',');
									  $(compss).each(function (index1,value1) {
										  var english = value1.split('_');

										svgDoc.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
									});
								  })
								  
        $('.washing_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.washing_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', "");
            }


        });
        $('.whitening_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.whitening_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', "");
            }
        });
        $('.dryer_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.dryer_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', "");
            }

        });
        $('.ironing_select' + dataid + '.bootstrap-select').change(function () {
            if ($('.ironing_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', "");
            }

        });
        $('.cleaning_select' + dataid + '.bootstrap-select').change(function () {
            if ($('.cleaning_select' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + dataid + ' option:selected').data('astm'));
            } else {
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', "");
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', "");
            }

        });
        $('.made_in_select' + dataid + '.bootstrap-select').on('changed.bs.select', function () {
            if ($('.made_in_select' + dataid + ' option:selected').val() != "") {

                if(datas.order_details[0].Activity_Label == "PUERICULTURE")
                {
                    var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german')  + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('greek') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('arabic')  + '</p>';
                }
                else
                {
                    var text_for_mad_in = '<p>' + $('select.made_in_select' + dataid).find(':selected').data('french') + "-" + $('select.made_in_select' + dataid).find(':selected').data('english') + "-" + $('select.made_in_select' + dataid).find(':selected').data('spanish') + "-" + $('select.made_in_select' + dataid).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + dataid).find(':selected').data('italian') + "-" + $('select.made_in_select' + dataid).find(':selected').data('dutch') + "-" + $('select.made_in_select' + dataid).find(':selected').data('german')  + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + dataid).find(':selected').data('greek')  + '</p>';
                }

                
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;

            } else {

            }


        });

        $('.care_instruction_select' + dataid + '.bootstrap-select').on('changed.bs.select', function (e, clickedIndex, newValue, oldValue) {

			var hidden_id = $(this).find('option').eq(clickedIndex).val();


			var arr = $('.hiddencis' + dataid).selectpicker('val')

			//alert(hidden_id)

			if(arr.includes(hidden_id) == true)
			{
				$('.hiddencis' + dataid + ' option[value="'+hidden_id+'"]').remove();
				$('.hiddencis' + dataid).selectpicker('refresh');
			}
			else
			{
				$('select.hiddencis' + dataid).append('<option data-id="' + hidden_id + '" data-french="' + $(this).find('option').eq(clickedIndex).data('french') + '" title="'+$(this).find('option').eq(clickedIndex).data('english')+'" data-english="' + $(this).find('option').eq(clickedIndex).data('english') + '" data-spanish="' + $(this).find('option').eq(clickedIndex).data('spanish') + '" data-portuguese="' + $(this).find('option').eq(clickedIndex).data('portuguese') + '" data-italian="' + $(this).find('option').eq(clickedIndex).data('italian') + '" data-dutch="' + $(this).find('option').eq(clickedIndex).data('dutch') + '" data-german="' + $(this).find('option').eq(clickedIndex).data('german')  + '" data-arabic="' + $(this).find('option').eq(clickedIndex).data('arabic') + '" data-greek="' + $(this).find('option').eq(clickedIndex).data('greek') + '" value="' + hidden_id + '" selected>' + $(this).find('option').eq(clickedIndex).data('english') + '</option>')
				$('.hiddencis' + dataid).selectpicker('refresh');
			}
            if ($('.hiddencis' + dataid + ' option:selected').val() != "") {
                svgDoc.getElementById("text_care_instruction").innerHTML = "";
                $('.hiddencis' + dataid + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek')  + '</p>';
                    svgDoc.getElementById("text_care_instruction").innerHTML += value;
                });


            } else {

            }


        });
							});
						
					} else {
						$('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
					}


				},
				error: function (data) {

				},
				complete: function () {
					$(".loader").hide();
				}
			})
		}
	});
}


/**
 * This Function is used to invoke JSBarcode for Wash Care Labels.
 * 
 * @return barcode
 */

function jsbarcodewash(code, id, svgDoc) {
    JsBarcode("#ean-13", code, {
        format: "ean13",
        width: 5.99,
        height: 290,
		lineColor: "cmyk(0%, 0%, 0%, 100%)",
        fontSize: 55
    });
    var svg = document.getElementById('svgdiv').querySelector('svg'),
        canvas = document.createElement('canvas'),
        body = document.getElementsByTagName('body')[0],
        canvasTmp,
        svg_xml,
        ctx,
        img;
    // if our "svg" doesn't have 'xmlns' attribute we must add it
    //    svg.setAttribute('width', '188.931023622px');
    svg.setAttribute('font-size', '70px');
    svg.setAttribute('font-family', 'ocr-b-10bt');
    svg.setAttribute('width', '36.1px');
    svg.setAttribute('height', '77px');
    canvas.id = 'canvas';
    canvas.width = svg.getBoundingClientRect().width;
    canvas.height = svg.getBoundingClientRect().height;

    body.appendChild(canvas);
    canvasTmp = document.getElementById('canvas');

    /* Time to play ;) */

    // https://developer.mozilla.org/en/XMLSerializer
    svg_xml = (new XMLSerializer()).serializeToString(svg);
    ctx = canvasTmp.getContext('2d');

    // JavaScript (HTML) image
    img = new Image();

    img.onload = function () {
        ctx.drawImage(img, 0, 0);
    };

    // https://developer.mozilla.org/en/DOM/window.btoa
    img.src = 'data:image/svg+xml;base64,' + btoa(svg_xml);

    // img.src - contains base64 URI from svg

    //remove temporary canvas
    canvasTmp.remove();

    svgDoc.getElementById(id).innerHTML = svg_xml;
}


/**
 * This Function is used to set wash care labels in local storage.
 * 
 * @return void
 */

function setWashCareLabelStatus(dataid,user_type) {

    if ($('#washcarelabelform' + dataid).find('#exist_id' + dataid).val() != "") {
       var value = $('#washcarelabelform' + dataid).find('#exist_id' + dataid).val();
    } else {
        var value = "null";
    }

    var wash_lab = JSON.stringify(wash_label);
    var i = 0
    $('#composition_remove_qty' + dataid + ' tr').each(function (key, val) {
        i++;
    })
    if (i != 0) {
        if( localStorage.getItem("wash_composition_final"+ dataid) != null)
        {
            $('#wash_composition_error' + dataid).addClass('hidden')
            $('#wash_composition_page_error' + dataid).addClass('hidden')
            localStorage.setItem("wash_label", wash_lab);
            setWashLabel('washcarelabelform' + dataid, dataid, value,user_type)
            $("#washcarelabelform" + dataid + " .left-panelorc").removeClass('active');
            $("#washcarelabelform" + dataid + " .right-panel").removeClass('active');
            $("#washcarelabelform" + dataid + " .backdrop").removeClass('active');
            $('#washcarelabelform' + dataid + ' .scalable').removeClass('active');
        }
        else {
            $('#wash_composition_page_error' + dataid).removeClass('hidden')
        }
        
    } else {
        $('#wash_composition_error' + dataid).removeClass('hidden')
    }


}



/**
 * This Function is used to delete wash care labels from db.
 * 
 * @return void
 */

function removeWashLabel(dataid,user_type) {
	var id=$('#delete_id'+dataid).val()	
	if(id != null && id !="")
	{
		var buyer = localStorage.getItem("buyer");
		
		$.ajax({
			url: base_url + "/"+user_type+"/" + buyer + "/order-management/remove_wash_label",
			type: "Post",
			data: {
				id: id,
				_token: csrf_token
			},
			beforeSend: function () {
				$(".loader").show();
			},
			success: function (data) {

			},
			error: function (data) {

			}
		})
	}
}

/**
 * This Function is used to set wash care labels to db.
 * 
 * @return void
 */

function setWashLabel(index, dataid, id,user_type) {
	$('.washlabelname'+dataid).text($('#wcl_name' + dataid).val()+" - Washcare Label");
    var check=0;
    var check1=0;
    $('#composition_remove_qty' + dataid + ' tr').each(function () {
         
         if($(this).find('select' + dataid).find(':selected').text() == "Select Option")
             {
                 check++;
             }
         check1++;
    });
    if(check1 <2 )
    {
        check=0;
    }
    if(check == 0 )
    {
        var composition = localStorage.getItem('wash_composition');

        var wash_fins = JSON.parse(composition);
        var wash_finals = localStorage.getItem('wash_composition_final'+dataid);

        
        var merchandisers = $('.merchandisers option:selected').val();
      	
		
		//alert($('#wash_label_id').val())
        if($('#wash_label_id').val() != 0)
		{
			//var template_id = $('#' + index).find('#tree_wash' + dataid).jstree('get_selected', true);
			template_front_id = 5;
		}
		else
		{
			template_front_id=4;
		}
		//var washonesizecm = $('#washcarelabelform' + dataid+' .washonesizecm').val();
		//var washonesizein = $('#washcarelabelform' + dataid+' .washonesizein').val();
		var po_select 	  = $('#po_select' + dataid).val();
		$('#'+po_select+'_'+dataid).val("1");
        var template_name = $('#wcl_name' + dataid).val();
        var ocn_number = $('#ocn_number_wash' + dataid).val();
        var card_flag = $('#card_flag' + dataid).val();
        var time_flag = $('#time_flag' + dataid).val();
        var washing_select = $('.washing_select' + dataid + ' option:selected').attr('data-id');
        var whitening_select = $('.whitening_select' + dataid + ' option:selected').attr('data-id');
        var dryer_select = $('.dryer_select' + dataid + ' option:selected').attr('data-id');
        var ironing_select = $('.ironing_select' + dataid + ' option:selected').attr('data-id');
        var cleaning_select = $('.cleaning_select' + dataid + ' option:selected').attr('data-id');
        var made_in_select = $('.made_in_select' + dataid + ' option:selected').attr('data-id');
        // var care_instruction_select = $('.care_instruction_select'+dataid+' option:selected').val();

        var care_instruction = $('.hiddencis' + dataid + ' option:selected');
        var care_instruction_select = [];
        $(care_instruction).each(function (index, brand) {
            care_instruction_select.push([$(this).val()]);
        });
        var care_instruction_select_fin = care_instruction_select.join(',');

        //template_front_id = template_id[0].id;
        var tot_qty_tablewash = [];
        var uvc_tco = [];
        var size = [];
        var barcode = [];
        $('#' + index + ' tbody tr').each(function () {
            if ($(this).find('.tot_qty_tablewash').val() != undefined) {
                tot_qty_tablewash.push($(this).find('.tot_qty_tablewash').val())
                uvc_tco.push($(this).find('.uvc_tco').text())
                size.push($(this).find('.size').text())
                barcode.push($(this).find('.barcode').text())
            }

        })
        tot_qty_tablewash_fin = tot_qty_tablewash.join(',');
        uvc_tco_fin = uvc_tco.join(',');
        size_fin = size.join(',');
        barcode_fin = barcode.join(',');
        var buyer = localStorage.getItem("buyer");
        
        $.ajax({
            url: base_url + "/"+user_type+"/" + buyer + "/order-management/set_wash_label",
            type: "Post",
            data: {
                
                template_front_id: template_front_id,
                id: id,
                template_name: template_name,
                washing_select: washing_select,
                whitening_select: whitening_select,
                ocn_number:ocn_number,
                po_select: po_select,
				//in:washonesizein,
				//cm:washonesizecm,
                dryer_select: dryer_select,
                ironing_select: ironing_select,
                card_flag: time_flag,
                cleaning_select: cleaning_select,
				parcel_id: $('#parcel_id').val(),
				season_code: $('#season_code').val(),
                made_in_select: made_in_select,
                care_instruction_select: care_instruction_select_fin,
                composition: wash_finals,
                tot_qty_table: tot_qty_tablewash_fin,
                uvc_tco: uvc_tco_fin,
                barcode: barcode_fin,
				color_code: $('#color_id'+dataid).val(),
                merchandisers: merchandisers,
                uvc: $('#uvc_id').val(),
                size: size_fin,
                _token: csrf_token
            },
            beforeSend: function () {
                $(".loader").show();
            },
            success: function (data) {
				
				$('#'+po_select+'_'+dataid).attr("data-exist",parseInt(data.trim()));
				$('#exist_id'+dataid).val(parseInt(data.trim()))
				
				$('#print_wash_button_'+dataid).removeClass('hidden')
				$('#cancel_wash_button_'+dataid).removeClass('hidden')
				$('#save_wash_button_'+dataid).addClass('hidden')
				$('#delete_id'+dataid).val(data)
				$('.delete-wash'+dataid).addClass('hidden')
				$('#clickme'+dataid).html('View & Edit <i class="fa fa-chevron-right" aria-hidden="true"></i>')
				$('#wcl_name'+dataid).attr('disabled','disabled')
				$('#ocn_number_wash'+dataid).attr('disabled','disabled')
				$('.washing_select'+dataid).attr('disabled','disabled')
				$('.washing_select'+dataid).css("background-color","#eee")
				$('.whitening_select'+dataid).attr('disabled','disabled')
				$('.dryer_select'+dataid).attr('disabled','disabled')
				$('.ironing_select'+dataid).attr('disabled','disabled')
				$('.cleaning_select'+dataid).attr('disabled','disabled')
				$('.made_in_select'+dataid).attr('disabled','disabled')
				$('.care_instruction_select'+dataid).attr('disabled','disabled')
				$('.composition_select'+dataid).attr('disabled','disabled')
				$('.composition_percent'+dataid).attr('disabled','disabled')
				$('.add_to_list'+dataid).attr('disabled','disabled')
				$('.add_to_page'+dataid).attr('disabled','disabled')
				$('#composition_flag'+dataid).val('disabled')
				
				$("#wash_care_label_table_"+dataid+" :input").prop("disabled", true);
				//alert(dataid)
				
            },
            error: function (data) {

            }
        })
        $('.composition_error_hidden').addClass('hidden')
        $('#washcare_label_card'+dataid).css('display','inline-block') 
		$('#clone_washcare_label_card'+dataid).css('display','inline-block')
		$('#clone_washcare_label_card'+dataid).css('float','right')
		$('#washcare_label_card'+dataid).removeClass('hidden')
		$('#clone_washcare_label_card'+dataid).removeClass('hidden')
		$('.wash-card'+dataid+' span').text('Buyer Approval Pending')
		$('.wash-card'+dataid+' span').addClass('label label-BuyerApprovalPending label-status')
		$('.wash-card'+dataid+' span').removeClass('label-OrderRejected')
		$('#po-file-upload-button').css('display','block')
    }
    else
    {
        $('.composition_error_hidden').removeClass('hidden')
    }
    

}

/**
 * This Function is used to place the order.
 * 
 * @return void
 */

function save_label_orders(user_type) {
	var finish = 0;
	if (localStorage.getItem("wash_label") != null) {

        if ($('.merchandisers').find("option:selected").val() != "") {
            var wash_json = JSON.parse(localStorage.getItem("wash_label"));

            $.each(wash_json, function (index, value) {
                var data_array = index.split('washcarelabelform');
                setWashLabel(index, data_array[1], value,user_type)
            });

        } else {
            return false;


        }
        finish++;
        //setMainLabel()
    } else {
        finish++;
    }
    if (localStorage.getItem("hang_tag") != null) {
        setHangTag(localStorage.getItem("hang_tag"))
        finish++;
    } else {
        finish++;
    }
    if (localStorage.getItem("special_tag") != null) {
        setSpecialTag(localStorage.getItem("special_tag"))
        finish++;
    } else {
        finish++;
    }
    if (localStorage.getItem("main_label") != null) {
        var main_json = JSON.parse(localStorage.getItem("main_label"));

        $.each(main_json, function (index, value) {
            var data_array = index.split('mainlabelsform');
            setMainLabel(index, data_array[1], value)
        });
        finish++;
        //setMainLabel()
    } else {
        finish++;
    }
    if (localStorage.getItem("deletemain") != null) {
        var del_main_json = JSON.parse(localStorage.getItem("deletemain"));

        $.each(del_main_json, function (index, value) {
            removeMainLabel(value)
        });
        finish++;
    } else {
        finish++;
    }
    if (localStorage.getItem("deletewash") != null) {
        var del_wash_json = JSON.parse(localStorage.getItem("deletewash"));

        $.each(del_wash_json, function (index, value) {
            removeWashLabel(value,user_type)
        });
        finish++;

    } else {
        finish++;
    }
    if (finish == 6) {
        location.reload();
    }
    //location.reload();

}

/**
 * This Function is used to clone wash care labels.
 * 
 * @return void
 */
function clonewashcarelabels(index,color_code,user_type,Activity_Label) 
{

                washcare += 1;
                $('#add_modal').append('<div class="col-md-4" ><input type="hidden"  id="delete_id'+washcare+'"><div  style="border:1px dashed #333;height: 280px;" class="card add-card wash-card'+washcare+'" data-toggle="modal" data-target=".washcare_label_' + washcare + '"  data-backdrop="static" data-keyboard="false"><span></span>    <div class="card-title"><p class="pull-right delete-wash' + washcare + '" id="' + washcare + '" style="cursor: pointer;"> Delete <i class="fa fa-trash" aria-hidden="true"></i></p><p class="pull-right" style="cursor: pointer;margin-right:5px" id=clickme' + washcare + ' >Edit <i class="fa fa-pencil" aria-hidden="true"></i> </p>  <h5><b class="washlabelname' + washcare + '">Washcare label</b></h5></div><div class="row"><div class="col-md-6 col-md-offset-3"><div class="img_artwork"><img src="' + base_url + '/images/wcl_default.png" class="img-responsive center-block"></div></div></div><p id="clone_washcare_label_card' + washcare + '" style=";cursor:pointer;display: none;"><i class="fa fa-clone" aria-hidden="true"></i> Clone washcare label</p></div></div>')

				$('#clone_washcare_label_card'+washcare).on('click', function (e) {
					clonewashcarelabels(washcare,color_code,user_type,Activity_Label);
				});
		 
                $('.delete-wash' + washcare).on('click', function (e) {

                    if (localStorage.getItem('wash_label') != null) {
                        var opop = JSON.parse(localStorage.getItem('wash_label'));
                        if (opop['washcarelabelform' + $(this).attr('id')] === undefined) {

                        } else {
                            delete opop['washcarelabelform' + $(this).attr('id')];
                        }


                        localStorage.setItem('wash_label', JSON.stringify(opop))
                    }
					removeWashLabel(washcare,user_type)
                    $('#washcarelabelform' + $(this).attr('id')).remove()
                    
                    $(this).parent().parent().parent().remove();


                });
           		
				//toastr.success("Wash care label cloned successfully");
				$('.snackbar_span').html('Wash care label cloned successfully');
				showSnackbar();
				window.scrollTo(0, document.body.scrollHeight);
			
         
            $('.washcarename').val("")
            $('.washnameerror').addClass('hidden')
            var data = localStorage.getItem("label_order_data");
            washcarelabels(JSON.parse(data), washcare,color_code,"new",user_type);
            $('#washcarelabelform'+washcare).find('#wcl_name'+washcare).val($('#washcarelabelform'+index).find('#wcl_name'+index).val())
            $('#washcarelabelform'+washcare).find('#ocn_number_wash'+washcare).val($('#washcarelabelform'+index).find('#ocn_number_wash'+index).val())
           
            
            $('.washing_select'+washcare).selectpicker('val',$('.washing_select'+index).selectpicker('val'));
            $('.whitening_select'+washcare).selectpicker('val',$('.whitening_select'+index).selectpicker('val'));
            $('.dryer_select'+washcare).selectpicker('val',$('.dryer_select'+index).selectpicker('val'));
            $('.ironing_select'+washcare).selectpicker('val',$('.ironing_select'+index).selectpicker('val'));
            $('.cleaning_select'+washcare).selectpicker('val',$('.cleaning_select'+index).selectpicker('val'));
            $('.made_in_select'+washcare).selectpicker('val',$('.made_in_select'+index).selectpicker('val'));
	
			//$('#washcarelabelform' + washcare+' .washonesizein').val($('#washcarelabelform' + index+' .washonesizein').val())
			//$('#washcarelabelform' + washcare+' .washonesizecm').val($('#washcarelabelform' + index+' .washonesizecm').val())
			
			
	
	
	
			$('.care_instruction_select'+washcare).selectpicker('val',$('.care_instruction_select'+index).selectpicker('val'));

			$('.hiddencis'+ index + ' option:selected').each(function () {

				$('select.hiddencis' + washcare).append('<option data-id="' + $(this).data('id') + '" data-french="' + $(this).data('french') + '" title="'+$(this).data('english')+'" data-english="' + $(this).data('english') + '" data-spanish="' + $(this).data('spanish') + '" data-portuguese="' + $(this).data('portuguese') + '" data-italian="' + $(this).data('italian') + '" data-dutch="' + $(this).data('dutch') + '" data-german="' + $(this).data('german')  + '" data-arabic="' + $(this).data('arabic') + '" data-greek="' + $(this).data('greek') + '" value="' + $(this).data('id') + '" selected>' + $(this).data('english') + '</option>')
				$('.hiddencis' + washcare).selectpicker('refresh');

			});
			
			//$('.hiddencis'+washcare).selectpicker('val',$('.hiddencis'+index).selectpicker('val'));
            //alert()
            $('#composition_remove_qty'+washcare).html($('#composition_remove_qty'+index).html())
            
            $('#composition_remove_qty'+index+' tr').each(function (ind,val) {
                var values= $(this).find('select').find(':selected').val()
                var v= $(this).attr('id');
                $('#composition_remove_qty'+washcare+' tr').each(function (inds,vals) {
                    if($(this).attr('id') == v)
                    {
                        $(this).find('select').val(values)
                        $(this).find('a').removeAttr('onclick')
                        $(this).find('a').attr('onclick','deleteComposition(this,'+washcare+')')
                    }
                });
            }); 
            
            $('#washcarelabelform'+index+' table tbody tr').each(function (ind,val) {
                var values= $(this).find('.tot_qty_tablewash').val()
                var v= $(this).find('.uvc_tco').text();
                $('#washcarelabelform'+washcare+' table tbody tr').each(function (inds,vals) {
                    if($(this).find('.uvc_tco').text() == v)
                    {
                        $(this).find('.tot_qty_tablewash').val()
                        
                    }
                });
            });
            
            $('#washcarelabelform'+washcare+' table tbody').find('.tot_qty_tablewash_fin'+washcare).text($('#washcarelabelform'+index+' table tbody').find('.tot_qty_tablewash_fin'+index).text())
            var mySVG = document.getElementById("washcare_svg_front"+washcare);
            var svgDoc;
	
	
			var mySVG_back = document.getElementById("washcare_svg_back"+washcare);
            var svgDocBack;

            var mySVG_middle = document.getElementById("washcare_svg_middle" + washcare);
            var svgDoc_middle;
			
            mySVG.addEventListener("load", function () {
                svgDoc=mySVG.contentDocument;
                svgDoc.getElementById("cofreet_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol1").setAttribute('xlink:href', $('.washing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol2").setAttribute('xlink:href', $('.whitening_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol3").setAttribute('xlink:href', $('.dryer_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol4").setAttribute('xlink:href', $('.ironing_select' + washcare + ' option:selected').data('astm'));
                svgDoc.getElementById("cofreet_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('cofreet'));
                svgDoc.getElementById("astm_symbol5").setAttribute('xlink:href', $('.cleaning_select' + washcare
 + ' option:selected').data('astm'));


                if(Activity_Label == "PUERICULTURE")
                {
                    var text_for_mad_in = '<p>' + $('select.made_in_select' + washcare).find(':selected').data('french') + "-" + $('select.made_in_select' + washcare).find(':selected').data('english') + "-" + $('select.made_in_select' + washcare).find(':selected').data('dutch') + "-" + $('select.made_in_select' + washcare).find(':selected').data('german') + "-" + $('select.made_in_select' + washcare).find(':selected').data('spanish') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('greek') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('arabic')  + '</p>';
                }
                else
                {
                    var text_for_mad_in = '<p>' + $('select.made_in_select' + washcare).find(':selected').data('french') + "-" + $('select.made_in_select' + washcare).find(':selected').data('english') + "-" + $('select.made_in_select' + washcare).find(':selected').data('spanish') + "-" + $('select.made_in_select' + washcare).find(':selected').data('portuguese') + "-" + $('select.made_in_select' + washcare).find(':selected').data('italian') + "-" + $('select.made_in_select' + washcare).find(':selected').data('dutch') + "-" + $('select.made_in_select' + washcare).find(':selected').data('german') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('arabic') + "-" +
                    $('select.made_in_select' + washcare).find(':selected').data('greek')  + '</p>';
                }
                
                
                svgDoc.getElementById("country").innerHTML = text_for_mad_in;
                
            });

            mySVG_middle.addEventListener("load", function () {
                svgDoc_middle = mySVG_middle.contentDocument;

                svgDoc_middle.getElementById("parts_composition").innerHTML ="";
                $('#composition_remove_qty' + washcare + ' tr').each(function () {
                    if($(this).find('select').find(':selected').val() != "Select Option")
                    {
                        svgDoc_middle.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + $(this).attr('id') + '" id="composition_' + $(this).attr('id') + '">' +$(this).find('select').find(':selected').data('french')+ "-" + $(this).find('select').find(':selected').data('english')+ "-" +$(this).find('select').find(':selected').data('spanish')+ "-" + $(this).find('select').find(':selected').data('portuguese') + "-" + $(this).find('select').find(':selected').data('italian')  + "-" + $(this).find('select' ).find(':selected').data('dutch')  + "-" + $(this).find('select' ).find(':selected').data('german') + "-" +$(this).find('select' ).find(':selected').data('arabic') + "-" + $(this).find('select' ).find(':selected').data('greek') + '</p>';
                    }
                    
                    var id=$(this).attr('id')
                    var compo =$(this).find('.compositions_hidden').val();
                    var compss = compo.split(',');
                    $(compss).each(function (index1,value1) {
                        var english = value1.split('_');

                        svgDoc_middle.getElementById("parts_composition").innerHTML += '<p class="text_pragraph composition_' + id + '" id="composition_' + id + '">' + english[9] + "% " + english[0]  + "-" +english[1] + "-" + english[2]+ "-" +english[3] + "-" + english[4] + "-" + english[5] + "-" +english[6] + "-" + english[7] + "-" + english[8]+ '</p>';
                    });
                })
                $('.composition_error_hidden').addClass('hidden')
                var washcomp=[];
                $('#composition_remove_qty' + washcare + ' tr').each(function () {
                    var washcomp1=[];
                    var id=$(this).attr('id')
                    var value=$(this).find('select').find(':selected').val();
                    var compo =$(this).find('.compositions_hidden').val();
                    var compss = compo.split(',');
                        $(compss).each(function (index1,value1){ 
                            var english = value1.split('_');
                            washcomp1.push(value+"_"+english[1]+"_"+english[9]) 
                        });
                    washcomp.push(washcomp1)
                })
                var wash_finals = JSON.stringify(washcomp);
                localStorage.setItem('wash_composition_final'+washcare, wash_finals);

            });
	
	        mySVG_back.addEventListener("load", function () {
				svgDocBack=mySVG_back.contentDocument;
				svgDocBack.getElementById("text_care_instruction").innerHTML = "";
                $('.hiddencis' + washcare + ' option:selected').each(function () {
                    var value = '<p class="text_pragraph">' + $(this).data('french') + "-" + $(this).data('english') + "-" + $(this).data('spanish') + "-" + $(this).data('portuguese') + "-" + $(this).data('italian') + "-" + $(this).data('dutch') + "-" + $(this).data('german')  + "-" +
                        $(this).data('arabic') + "-" +
                        $(this).data('greek')  + '</p>';
                    svgDocBack.getElementById("text_care_instruction").innerHTML += value;
                });
                
                 
			});
}

/**
 * This Function is Sub Function of #label_order_details.
 * To integrate Hang Tag Information
 * @return Hang Tag View
 */

function hangtagmodal(data,user_type) {
    var order_details = data.order_details;
    var orchestra_hang_tag_orders_data = data.orchestra_hang_tag_orders;
    var tr = "";
    $qu = 0;
    if (data.orchestra_hang_tag_orders != "") {
       

        $template_front_id = data.orchestra_hang_tag_orders[0].template_front_id;
        $exist_id = data.orchestra_hang_tag_orders[0].id;
        
        var str=data.orchestra_hang_tag_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
		$print_button_class="";
		$save_button_class="hidden";
		$ocn_number_hang = data.orchestra_hang_tag_orders[0].ocn_number;
    } else {
       
        $template_front_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
		$print_button_class="hidden";
		$save_button_class="";
		$ocn_number_hang="";
    }
    var ttots = 0;
    var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_hang_tag_orders != "") {

            $hang_tag_count = data.orchestra_hang_tag_orders_qty[$qu].quantity;
        } else {
            $hang_tag_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)"  type="number" class="form-control  input-sm tot_qty_tablehang" data-required-error="Required" value="' + $hang_tag_count + '" required placeholder="Hang Tag Count"><div class="help-block with-errors"></div></div></td></tr>';
        ttots += parseInt($hang_tag_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
	
	if(order_details[0].Size == "TAILLE UNIQUE")
	{
		if (data.orchestra_hang_tag_orders != "") {
            $hang_cm = data.orchestra_hang_tag_orders[0].cm;
			$hang_in = data.orchestra_hang_tag_orders[0].in;
        } else {
            $hang_cm = "";
			$hang_in = "";
        }
		//<div class="form-group has-feedback"><label>Enter Size in Cm</label><input onkeypress="return checkNumbersOnly(event)" type="text" maxlength="3" class="form-control input-sm onesizecm" value="'+$hang_cm+'" required="" placeholder="Enter Size in Cm"><div class="help-block with-errors"></div></div>
		var sizebox='';
	}
	else
	{
		var sizebox="";
	}
    
    if(isNaN(ttots))
        {
            ttots=0;
        }
    else
        {
            ttots=ttots;
        }
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });

    $('.top-content').append('<form id="hangtagform" data-toggle= "validator"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="left-panelorc col-md-8"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="ocn_number"class="form_label">OCN Number</label><input type="text" value="'+ $ocn_number_hang +'" id="ocn_number_hang" value="" class="form-control input-sm" placeholder="OCN Number"></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="hang_template_error" class="hidden" style="color:red;display: inherit;">Please Select the Template</label>  <div><input type="text" id="tree1_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree1" style="height: calc(100vh - 260px);overflow-y: scroll;margin-top: 15px;"></div><div class="help-block with-errors"></div></div>'+sizebox+'</div><div class="col-md-4"><h4>Front</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr><div class="section-1 sections"><div class="sample-artwork" style="text-align:center"></div></div></div><div class="col-md-4"><h4>Back</h4><hr><div class="section-1 sections text-center" ><input type="hidden" value="' + data.orchestra_template_id + '" id="hang_tag_id"><div class="sample_back"></div><label style="margin-top:15px">Hang Tag Print Date : ' + data.order_details[0].HangTag_Print_Date + '</label><div id="svgdiv" style="visibility:hidden"><svg id="ean-13"></svg></div></div></div></div></div><div class="right-panel col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Hang Tag</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="hang_tag_table"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty</th><th><label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentagehang" onkeypress="return checkNumbersOnly(event)"  style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots_sum + '</td><td class="text-center tot_qty_tablehang_fin">' + ttots + '</td></tr></tbody></table></div><button type="button" class="btn btn-danger hidden input-sm '+$print_button_class+'" id="cancel_hang_button" style="float: right;margin-left:10px" onclick=cancelHangDiv()>Cancel</button> <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_hang_button" style="margin-left:10px">Save</button> <button class="btn btn-default pull-right input-sm" type="button" id="hangclose">Close</button><button type="button" class="btn btn-primary '+$print_button_class+'" id="print_hang_button" onclick=printHangDiv("svg2","'+data.order_details[0].Licence_Code+'")>Print</button> </div> </div>  <div class="backdrop"></div></form>');



//$('.left').removeClass('disabled')
    $('#hangtagform .tot_qty_tablehang').keyup(function () {
        var tot_qty_tablehang_carton_final = 0;
        $('#hangtagform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablehang').val() != undefined) {
                tot_qty_tablehang_carton_final += parseInt($(this).find('.tot_qty_tablehang').val());
            }
        })
        $('#hangtagform .tot_qty_tablehang_fin').text(tot_qty_tablehang_carton_final);
    });
	
	 $('.extra_percentagehang').keyup(function () {
		
        
        var tot_qty_tablehang_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablehang').val(tot_qty);
                if ($(this).find('.tot_qty_tablehang').val() != undefined) {
                    tot_qty_tablehang_carton_final += parseInt($(this).find('.tot_qty_tablehang').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablehang_fin').text(tot_qty_tablehang_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablehang').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablehang_fin').text("0");
        }

    })
	
//	$('#hangtagform .onesizein').keyup(function () {
//		
//		var inches=$(this).val();
//        var mySVG = document.getElementById("svg2");
//		var svgDoc;
//		svgDoc = mySVG.contentDocument;
//		svgDoc.getElementById("in").innerHTML = inches;
//
//		
//    });
//	$('#hangtagform .onesizecm').keyup(function () {
//		
//		var cm=$(this).val();
//        var mySVG = document.getElementById("svg2");
//		var svgDoc;
//		svgDoc = mySVG.contentDocument;
//		svgDoc.getElementById("cm").innerHTML = cm;
//		
//    });

    $("#clickmehang").click(function () {
        // $('.sample-artwork').html("")
        $("#hangtagform .backdrop").toggleClass('active');
        $("#hangtagform .right-panel").toggleClass('active');
        $('#hangtagform .scalable').toggleClass('active');
        $("#hangtagform .left-panelorc").toggleClass('active');
    })

    $(".backdrop, #hangclose").click(function () {
        $("#hangtagform .left-panelorc").removeClass('active');
        $("#hangtagform .right-panel").removeClass('active');
        $("#hangtagform .backdrop").removeClass('active');
        $('#hangtagform .scalable').removeClass('active');
    })

    $('#tree1').jstree({
        'core': {
            'data': data.treefinalData
        },
        "types": {
            "default": {
                "icon": "fa fa-folder"
            }
        },
        "plugins": ["types", "search", "conditionalselect"],
		 conditionalselect: function (node) {
                        return this.is_leaf(node);
         }
    });
    $('#expand').bind("click", function () {
        $('#tree1').jstree("open_all");
    });
    $('#collapse').bind("click", function () {
        $('#tree1').jstree("close_all");
    });
    var to = false;
    $('#tree1_q').keyup(function () {
        if (to) {
            clearTimeout(to);
        }
        to = setTimeout(function () {
            var v = $('#tree1_q').val();
            $('#tree1').jstree(true).search(v);
        }, 250);
    });
    //Show Template when tree loads 
    $("#tree1").on("loaded.jstree", function (e, data) {
		$('#tree1').jstree(true).select_node($template_front_id);
		//var node = $("#tree1").jstree('get_selected', true)[0].text;
        var ids = $(this).parent().parent().parent().parent().parent().attr('id');
        if ($template_front_id != "") {
            var buyer = localStorage.getItem("buyer");
            var template_id = $template_front_id;
            
            $.ajax({
                url: base_url + "/"+user_type+"/" + buyer + "/template-management/template-view",
                type: "Post",
                dataType: "json",
                data: {
                    template_id: template_id,
                    customer_id: $('#customer_id').val(),
                    _token: csrf_token
                },
                beforeSend: function () {
                    $(".loader").show();
                },
                success: function (data) {
					$('.tot_qty_tablehang').attr('min',data.moq)
					$('#hangtagform').validator('update')
                    if (data.treeviewData.length > 0) {
                        $('#' + ids).find('.sample-artwork').html('<object id="svg_hang_front" style="height: 486px;" class="center-block svg2" data="' + data.treeviewData[0].template_file + '" type="image/svg+xml"></object>')
                        $('#' + ids).find('.sample_back').html('<object class="center-block" id="svg2" style="height:486px" data="' + data.treeviewData[1].template_file + '" type="image/svg+xml"></object>')

                        if (data.treeviewData[0].disney != "DI") {
                            var mySVG_hang_disney = document.getElementById("svg_hang_front");
                            var svgDoc;
                            mySVG_hang_disney.addEventListener("load", function () {
                                svgDoc = mySVG_hang_disney.contentDocument;
                                var pri = order_details[0].PrixEurope.split(",")
								
								var size = order_details[0].Size.split(" ")
							
								if(order_details[0].Size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = "";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
									svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
								}
								else if(order_details[0].Size == "TAILLE L")
								{
									svgDoc.getElementById("size").innerHTML = "TAILLE L";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else
								{
									//alert(size[0])
									svgDoc.getElementById("size").innerHTML = size[0];
									if (size[1] == "ANS") {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "years";

									} else {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "months";
									}
								}

                                if (pri.length == 1) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = innerHTML = order_details[0].PrixEurope;
                                    svgDoc.getElementById("price2").innerHTML = "";
                                } else if (pri.length == 2) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = innerHTML = pri[0];
                                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                                }
                                var pri1 = order_details[0].PrixClubEurope.toString().split(",")
                                if (pri1.length == 1) {
                                    svgDoc.getElementById("club_price1").innerHTML = order_details[0].PrixClubEurope;
                                    svgDoc.getElementById("club_price2").innerHTML = "";
                                } else if (pri1.length == 2) {
                                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                                }

                            }, false);
                        } else {
                            var mySVG_hang = document.getElementById("svg2");
                            var svgDoc;
                            mySVG_hang.addEventListener("load", function () {
                                svgDoc = mySVG_hang.contentDocument;
                                var pri = order_details[0].PrixEurope.split(",")
								
								var size = order_details[0].Size.split(" ")
							
								if(order_details[0].Size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = "";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
								
								svgDoc.getElementById("size_list").innerHTML = "TAILLE UNIQUE";
								svgDoc.getElementById("cm").innerHTML = "ONE SIZE";
								
								svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
								}
								else if(order_details[0].Size == "TAILLE L")
								{
									svgDoc.getElementById("size").innerHTML = "TAILLE L";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else
								{
									//alert(size[0])
									svgDoc.getElementById("size").innerHTML = size[0];
									if (size[1] == "ANS") {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "years";

									} else {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "months";
									}
								}
								
                                if (pri.length == 1) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = order_details[0].PrixEurope;
                                    svgDoc.getElementById("price2").innerHTML = "";
                                } else if (pri.length == 2) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = pri[0];
                                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                                }
                                var pri1 = order_details[0].PrixClubEurope.toString().split(",")
                                if (pri1.length == 1) {
                                    svgDoc.getElementById("club_price1").innerHTML = order_details[0].PrixClubEurope;
                                    svgDoc.getElementById("club_price2").innerHTML = "";
                                } else if (pri1.length == 2) {
                                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                                }

                            }, false);
                        }

                        var mySVG = document.getElementById("svg2");
                        var svgDoc;
                        mySVG.addEventListener("load", function () {
                            svgDoc = mySVG.contentDocument;
							
							//svgDoc.getElementById("cm").innerHTML = $('.onesizecm').val();
							//svgDoc.getElementById("in").innerHTML = $('.onesizein').val();
							
                            var svgItem = svgDoc.getElementById("uvc_tco");
                            svgDoc.getElementById("algeria_club_price").innerHTML = order_details[0].Prix_Vente_ALGERIE != null ? order_details[0].Prix_Vente_ALGERIE.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("algeria_price").innerHTML = order_details[0].Prix_Club_ALGERIE != null ? order_details[0].Prix_Club_ALGERIE.toString().replace(".", ",") .replace(" ", ""): "";
                            svgDoc.getElementById("saudi_club_price").innerHTML = order_details[0].Prix_Vente_Arabie_Saoudite != null ? order_details[0].Prix_Vente_Arabie_Saoudite.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morroco_club_price").innerHTML = order_details[0].Prix_Vente_Maroc != null ? order_details[0].Prix_Vente_Maroc.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("swiss_club_price").innerHTML = order_details[0].Prix_Vente_Suisse != null ? order_details[0].Prix_Vente_Suisse.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("canada_club_price").innerHTML = order_details[0].Prix_Vente_Canada != null ? order_details[0].Prix_Vente_Canada.toString().replace(".", ",").replace(" ", "") : "";
                            //svgDoc.getElementById("turkey_price").innerHTML = order_details[0].Prix_Vente_Turquie != null ? order_details[0].Prix_Vente_Turquie.toString().replace(".", ",") : "";
                            svgDoc.getElementById("us_club_price").innerHTML = order_details[0].Prix_Vente_USA != null ? order_details[0].Prix_Vente_USA.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("us_price").innerHTML = order_details[0].Prix_Club_USA != null ? order_details[0].Prix_Club_USA.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morris_club_price").innerHTML = order_details[0].Prix_Vente_Ile_Maurice != null ? order_details[0].Prix_Vente_Ile_Maurice.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morris_price").innerHTML = order_details[0].Prix_Club_Ile_Maurice != null ? order_details[0].Prix_Club_Ile_Maurice.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morocco_price").innerHTML = order_details[0].Prix_Club_Maroc != null ? order_details[0].Prix_Club_Maroc.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("swiss_price").innerHTML = order_details[0].Prix_Club_Suisse != null ? order_details[0].Prix_Club_Suisse.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("canada_price").innerHTML = order_details[0].Prix_Club_Canada != null ? order_details[0].Prix_Club_Canada.toString().replace(".", ",").replace(" ", "") : "";
                           // svgDoc.getElementById("turkey_club_price").innerHTML = order_details[0].Prix_Club_Turquie != null ? order_details[0].Prix_Club_Turquie.toString().replace(".", ",") : "";
                            svgItem.innerHTML = order_details[0].UVC_TCO;
                            var size = order_details[0].Size.split(" ")
							var string = order_details[0].Theme.toLowerCase();
                            svgDoc.getElementById("theme").innerHTML = string.charAt(0).toUpperCase() + string.slice(1); 
							
							if(order_details[0].Size == "TAILLE UNIQUE")
							{
								svgDoc.getElementById("size").innerHTML = "";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
								
								svgDoc.getElementById("size_list").innerHTML = "TAILLE UNIQUE";
								svgDoc.getElementById("cm").innerHTML = "ONE SIZE";
								
								svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
							}
							else if(order_details[0].Size == "TAILLE L")
							{
								svgDoc.getElementById("size").innerHTML = "TAILLE L";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
							}
							else
							{
								svgDoc.getElementById("size").innerHTML = size[0];
								
								svgDoc.getElementById("cm").innerHTML = order_details[0].cm+" CM";
								//svgDoc.getElementById("in").innerHTML = order_details[0].in;
								if (size[1] == "ANS") {
									svgDoc.getElementById("size_french").innerHTML = size[1];
									if(size[0] == 1)
									{
										svgDoc.getElementById("size_english").innerHTML = "YEAR";
									}
									else
									{
										svgDoc.getElementById("size_english").innerHTML = "YEARS";
									}
									
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-").split(',')[0];

								} else {
									svgDoc.getElementById("size_french").innerHTML = size[1];
									if(size[0] == 1)
									{
										svgDoc.getElementById("size_english").innerHTML = "MONTH";
									}
									else
									{
										svgDoc.getElementById("size_english").innerHTML = "MONTHS";
									}
									//alert(order_details[0].Age);
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-").split(',')[0]+"M";
								}
							}
                           if (data.treeviewData[0].disney != 1) {
							   var code = order_details[0].BarcodeForPricesTags.toString();
                               jsbarcode_hang(code, "barcode1", svgDoc)
						   }
						   else{
							   var code = order_details[0].BarcodeForPricesTags.toString();
                               jsbarcode_hang_disney(code, "barcode1", svgDoc)
						   }
                            


                        }, false);



                    } else {
                        $('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
						$('#' + ids).find('.sample_back').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
                    }
					
					if (orchestra_hang_tag_orders_data != "") {
						 $('#tree1 >ul > li').each( function() {
						   disable( 'tree1',this.id );        
						})
						$('.extra_percentagehang').attr('disabled','disabled')
						$('.tot_qty_tablehang').attr('disabled','disabled')
						$('#ocn_number_hang').attr('disabled','disabled')
					}

                },
                error: function (data) {

                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
    });
    //Show Template when Click tree node 
    $('#tree1').on('activate_node.jstree', function (e, data) {
		var ids = $(this).parent().parent().parent().parent().parent().attr('id');
		//var node = $("#tree1").jstree('get_selected', true)[0].text;
        if (data.instance.is_leaf(data.node)) {
            var buyer = localStorage.getItem("buyer");
            var template_id = data.node.id;
            
            $.ajax({
                url: base_url + "/"+user_type+"/" + buyer + "/template-management/template-view",
                type: "Post",
                dataType: "json",
                data: {
                    template_id: template_id,
                    customer_id: $('#customer_id').val(),
                    _token: csrf_token
                },
                beforeSend: function () {
                    $(".loader").show();
                },
                success: function (data) {
					$('.tot_qty_tablehang').attr('min',data.moq)
					$('#hangtagform').validator('update')
                    if (data.treeviewData.length > 0) {
                        $('#' + ids).find('.sample-artwork').html('<object id="svg_hang_front" style="height: 486px;" class="center-block svg2" data="' + data.treeviewData[0].template_file + '" type="image/svg+xml"></object>')
                        $('#' + ids).find('.sample_back').html('<object class="center-block" id="svg2" style="height:486px" data="' + data.treeviewData[1].template_file + '" type="image/svg+xml"></object>')

                        if (data.treeviewData[0].disney != "DI") {
                            var mySVG_hang_disney = document.getElementById("svg_hang_front");
                            var svgDoc;
                            mySVG_hang_disney.addEventListener("load", function () {
                                svgDoc = mySVG_hang_disney.contentDocument;
                                var pri = order_details[0].PrixEurope.split(",")
								
								var size = order_details[0].Size.split(" ")
							
								if(order_details[0].Size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = "";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
									svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
								}
								else if(order_details[0].Size == "TAILLE L")
								{
									svgDoc.getElementById("size").innerHTML = "TAILLE L";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else
								{
									//alert(size[0])
									svgDoc.getElementById("size").innerHTML = size[0];
									if (size[1] == "ANS") {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "years";

									} else {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "months";
									}
								}

                                if (pri.length == 1) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = innerHTML = order_details[0].PrixEurope;
                                    svgDoc.getElementById("price2").innerHTML = "";
                                } else if (pri.length == 2) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = innerHTML = pri[0];
                                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                                }
                                var pri1 = order_details[0].PrixClubEurope.toString().split(",")
                                if (pri1.length == 1) {
                                    svgDoc.getElementById("club_price1").innerHTML = order_details[0].PrixClubEurope;
                                    svgDoc.getElementById("club_price2").innerHTML = "";
                                } else if (pri1.length == 2) {
                                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                                }

                            }, false);
                        } else {
                            var mySVG_hang = document.getElementById("svg2");
                            var svgDoc;
                            mySVG_hang.addEventListener("load", function () {
                                svgDoc = mySVG_hang.contentDocument;
                                var pri = order_details[0].PrixEurope.split(",")
								
								var size = order_details[0].Size.split(" ")
							
								if(order_details[0].Size == "TAILLE UNIQUE")
								{
									svgDoc.getElementById("size").innerHTML = "";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";

									svgDoc.getElementById("size_list").innerHTML = "TAILLE UNIQUE";
									svgDoc.getElementById("cm").innerHTML = "ONE SIZE";
									
								svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
								}
								else if(order_details[0].Size == "TAILLE L")
								{
									svgDoc.getElementById("size").innerHTML = "TAILLE L";
									svgDoc.getElementById("size_french").innerHTML = "";
									svgDoc.getElementById("size_english").innerHTML = "";
								}
								else
								{
									//alert(size[0])
									svgDoc.getElementById("size").innerHTML = size[0];
									if (size[1] == "ANS") {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "years";

									} else {
										svgDoc.getElementById("size_french").innerHTML = size[1];
										svgDoc.getElementById("size_english").innerHTML = "months";
									}
								}
								
                                if (pri.length == 1) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = order_details[0].PrixEurope;
                                    svgDoc.getElementById("price2").innerHTML = "";
                                } else if (pri.length == 2) {
                                    var svgItem = svgDoc.getElementById("price1");
                                    svgItem.innerHTML = pri[0];
                                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                                }
                                var pri1 = order_details[0].PrixClubEurope.toString().split(",")
                                if (pri1.length == 1) {
                                    svgDoc.getElementById("club_price1").innerHTML = order_details[0].PrixClubEurope;
                                    svgDoc.getElementById("club_price2").innerHTML = "";
                                } else if (pri1.length == 2) {
                                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                                }

                            }, false);
                        }

                        var mySVG = document.getElementById("svg2");
                        var svgDoc;
                        mySVG.addEventListener("load", function () {
                            svgDoc = mySVG.contentDocument;
							
							//svgDoc.getElementById("cm").innerHTML = $('.onesizecm').val();
							//svgDoc.getElementById("in").innerHTML = $('.onesizein').val();
							
                            var svgItem = svgDoc.getElementById("uvc_tco");
                            svgDoc.getElementById("algeria_club_price").innerHTML = order_details[0].Prix_Vente_ALGERIE != null ? order_details[0].Prix_Vente_ALGERIE.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("algeria_price").innerHTML = order_details[0].Prix_Club_ALGERIE != null ? order_details[0].Prix_Club_ALGERIE.toString().replace(".", ",") .replace(" ", ""): "";
                            svgDoc.getElementById("saudi_club_price").innerHTML = order_details[0].Prix_Vente_Arabie_Saoudite != null ? order_details[0].Prix_Vente_Arabie_Saoudite.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morroco_club_price").innerHTML = order_details[0].Prix_Vente_Maroc != null ? order_details[0].Prix_Vente_Maroc.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("swiss_club_price").innerHTML = order_details[0].Prix_Vente_Suisse != null ? order_details[0].Prix_Vente_Suisse.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("canada_club_price").innerHTML = order_details[0].Prix_Vente_Canada != null ? order_details[0].Prix_Vente_Canada.toString().replace(".", ",").replace(" ", "") : "";
                            //svgDoc.getElementById("turkey_price").innerHTML = order_details[0].Prix_Vente_Turquie != null ? order_details[0].Prix_Vente_Turquie.toString().replace(".", ",") : "";
                            svgDoc.getElementById("us_club_price").innerHTML = order_details[0].Prix_Vente_USA != null ? order_details[0].Prix_Vente_USA.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("us_price").innerHTML = order_details[0].Prix_Club_USA != null ? order_details[0].Prix_Club_USA.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morris_club_price").innerHTML = order_details[0].Prix_Vente_Ile_Maurice != null ? order_details[0].Prix_Vente_Ile_Maurice.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morris_price").innerHTML = order_details[0].Prix_Club_Ile_Maurice != null ? order_details[0].Prix_Club_Ile_Maurice.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("morocco_price").innerHTML = order_details[0].Prix_Club_Maroc != null ? order_details[0].Prix_Club_Maroc.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("swiss_price").innerHTML = order_details[0].Prix_Club_Suisse != null ? order_details[0].Prix_Club_Suisse.toString().replace(".", ",").replace(" ", "") : "";
                            svgDoc.getElementById("canada_price").innerHTML = order_details[0].Prix_Club_Canada != null ? order_details[0].Prix_Club_Canada.toString().replace(".", ",").replace(" ", "") : "";
                           // svgDoc.getElementById("turkey_club_price").innerHTML = order_details[0].Prix_Club_Turquie != null ? order_details[0].Prix_Club_Turquie.toString().replace(".", ",") : "";
                            svgItem.innerHTML = order_details[0].UVC_TCO;
                            var size = order_details[0].Size.split(" ")
							
							var string = order_details[0].Theme.toLowerCase();
                            svgDoc.getElementById("theme").innerHTML = string.charAt(0).toUpperCase() + string.slice(1); 
							
							if(order_details[0].Size == "TAILLE UNIQUE")
							{
								svgDoc.getElementById("size").innerHTML = "";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
								
								svgDoc.getElementById("size_list").innerHTML = "TAILLE UNIQUE";
								svgDoc.getElementById("cm").innerHTML = "ONE SIZE";
							
								svgDoc.getElementById("tu_one_size").removeAttribute("visibility");
							}
							else if(order_details[0].Size == "TAILLE L")
							{
								svgDoc.getElementById("size").innerHTML = "TAILLE L";
								svgDoc.getElementById("size_french").innerHTML = "";
								svgDoc.getElementById("size_english").innerHTML = "";
							}
							else
							{
								svgDoc.getElementById("size").innerHTML = size[0];
								
								svgDoc.getElementById("cm").innerHTML = order_details[0].cm+" CM";
								//svgDoc.getElementById("in").innerHTML = order_details[0].in;
								if (size[1] == "ANS") {
									svgDoc.getElementById("size_french").innerHTML = size[1];
									if(size[0] == 1)
									{
										svgDoc.getElementById("size_english").innerHTML = "YEAR";
									}
									else
									{
										svgDoc.getElementById("size_english").innerHTML = "YEARS";
									}
									//alert(order_details[0].Age.match(/[0-9]+/g).toString());
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-").split(',')[0];

								} else {
									svgDoc.getElementById("size_french").innerHTML = size[1];
									if(size[0] == 1)
									{
										svgDoc.getElementById("size_english").innerHTML = "MONTH";
									}
									else
									{
										svgDoc.getElementById("size_english").innerHTML = "MONTHS";
									}
									//alert(order_details[0].Age.match(/[0-9]+/g).toString());
									svgDoc.getElementById("size_list").innerHTML = order_details[0].Age.match(/[0-9]+/g).toString().replace(",", "-").split(',')[0]+"M";
								}
							}
                           if (data.treeviewData[0].disney != 1) {
							   var code = order_details[0].BarcodeForPricesTags.toString();
                               jsbarcode_hang(code, "barcode1", svgDoc)
						   }
						   else{
							   var code = order_details[0].BarcodeForPricesTags.toString();
                               jsbarcode_hang_disney(code, "barcode1", svgDoc)
						   }
                            


                        }, false);



                    } else {
                        $('#' + ids).find('.sample-artwork').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
						$('#' + ids).find('.sample_back').html('<i class="fa fa-file-text-o fa-4x" aria-hidden="true" style="object-fit:cover; font-size: 100px;"></i>')
                    }

                },
                error: function (data) {

                },
                complete: function () {
                    $(".loader").hide();
                }
            })
        }
    });

	
	
	
    $('#hangtagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setHangTagStatus(data.color_wcl)
        }
    })


    selectpicker()
    $('.template-select.bootstrap-select').on('changed.bs.select', function () {
        if ($('.template-select option:selected').val() != "") {
            $('.sample-artwork').html('<object id="svg1" data="' + $('.template-select option:selected').val() + '" style="width:233px;height:500px" type="image/svg+xml"></object>')
            var mySVG = document.getElementById("svg1");
            var svgDoc;
            mySVG.addEventListener("load", function () {
                svgDoc = mySVG.contentDocument;
                var pri = data.order_details[0].PrixEurope.split(",")

                if (pri.length == 1) {
                    var svgItem = svgDoc.getElementById("price1");
                    svgItem.innerHTML = innerHTML = data.order_details[0].PrixEurope;
                    svgDoc.getElementById("price2").innerHTML = "";
                } else if (pri.length == 2) {
                    var svgItem = svgDoc.getElementById("price1");
                    svgItem.innerHTML = innerHTML = pri[0];
                    svgDoc.getElementById("price2").innerHTML = "," + pri[1];
                }
                var pri1 = data.order_details[0].PrixClubEurope.toString().split(",")
                if (pri1.length == 1) {
                    svgDoc.getElementById("club_price1").innerHTML = data.order_details[0].PrixClubEurope;
                    svgDoc.getElementById("club_price2").innerHTML = "";
                } else if (pri1.length == 2) {
                    svgDoc.getElementById("club_price1").innerHTML = pri1[0];
                    svgDoc.getElementById("club_price2").innerHTML = "," + pri1[1];
                }

            }, false);
            $('.sample-artwork').css('background', '#fff');
        } else {
            $('.sample-artwork').html('');
            $('.sample-artwork').css('background', '#eee');
        }

    });
}

/**
 * This Function is Sub Function of #label_order_details.
 * To integrate Main Label Information
 * @return Main Label View
 */

function mainlabels(data,user_type) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_main_label_orders != "") {

      
        $template_id = data.orchestra_main_label_orders[0].template_id;
        $exist_id = data.orchestra_main_label_orders[0].id;
        
        var str=data.orchestra_main_label_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
        $cancel_button_class="";
		$ocn_number_main = data.orchestra_main_label_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
        $save_button_class="";
        $cancel_button_class="hidden";
		$ocn_number_main = "";
    }
    
    var option = "<option value=''>Select Option</option>";
	var ml="";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
	$c=0;
	$.each(data.color_wcl, function (index, value) {
		if (data.orchestra_main_label_orders != "") {
			$main_label_count=data.orchestra_main_label_orders_qty[$c].quantity;
		}else{
			 $main_label_count="";
		}
		
        ml += '<label for="edit_merchandiser_buyer" class="form_label">Final order qty for colour '+value.Color_Code+'</label><input class="form-control input-sm main_label_qty" value="' + $main_label_count + '" onkeypress="return checkorcMOQ(this)" placeholder="Main Label Count" type="number" required>';
		$c++;
    });
	

    $('.top-content').append('<form id="main_labelform" data-toggle= "validator"><div class="left-panelorc col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-5"><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="ocn_number"class="form_label">OCN Number</label><input type="text" id="ocn_number_main" value="'+$ocn_number_main+'" class="form-control input-sm" placeholder="OCN Number"></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="main_label_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label>  <div><input type="text" id="treeml_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treeml" style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-7"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-4"> <div class="div-max"> <h4>Quantity Info for Main Label</h4><label style="color:#ff9800">This is only to place main label orders. Size label orders you want to place seperately</label> <hr> <div class="scroll"><div class="form-group has-feedback has-success">'+ml+'<div class="help-block with-errors"></div></div><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_main_label_button" style="margin-left:10px">Save</button> <button type="button" class="btn btn-danger hidden input-sm '+$cancel_button_class+'" id="cancel_main_button" style="float: right;margin-left: 10px;" onclick=cancelMainDiv()>Cancel</button><button class="btn btn-default pull-right input-sm" type="button" id="main_labelclose">Close</button>  </div></div> </div>  <div class="backdrop"></div></form>');

	//$('.left').removeClass('disabled')
    $('#main_labelform .tot_qty_table').keyup(function () {
        var tot_qty_table_carton_final = 0;
        $('#main_labelform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_table').val() != undefined) {
                tot_qty_table_carton_final += parseInt($(this).find('.tot_qty_table').val());
            }
        })
        $('#main_labelform .tot_qty_table_fin').text(tot_qty_table_carton_final);
    });

    $("#clickmemain_label").click(function () {
        // $('.sample-artwork').html("")
        $("#main_labelform .backdrop").toggleClass('active');
        $("#main_labelform .right-panel").toggleClass('active');
        $('#main_labelform .scalable').toggleClass('active');
        $("#main_labelform .left-panelorc").toggleClass('active');
    })

    $(".backdrop, #main_labelclose").click(function () {
        $("#main_labelform .left-panelorc").removeClass('active');
        $("#main_labelform .right-panel").removeClass('active');
        $("#main_labelform .backdrop").removeClass('active');
        $('#main_labelform .scalable').removeClass('active');
    })



    treestructuremain_label(data.treeviewmain_label, "treeml", $template_id,data.orchestra_main_label_orders);

    $('#main_labelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setMainLabelStatus(data.color_wcl)
        }
    })
}
/**
 * This Function is Sub Function of #label_order_details.
 * To integrate Main Label Information
 * @return Main Label View
 */

function sizelabels(data,user_type) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_size_label_orders != "") {

      
        $template_id = data.orchestra_size_label_orders[0].template_id;
        $exist_id = data.orchestra_size_label_orders[0].id;
        $size_label_count=data.orchestra_size_label_orders[0].final_qty;
        
        var str=data.orchestra_size_label_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
        $cancel_button_class="";
		$ocn_number_size = data.orchestra_size_label_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $size_label_count="";
        $status="";
        $status_text="";
        $save_button_class="";
        $cancel_button_class="hidden";
		$ocn_number_size = "";
    }
    var ttots = 0;
    var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_size_label_orders != "") {

            $size_label_count = data.orchestra_size_label_orders_qty[$qu].quantity;
        } else {
            $size_label_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)"  type="number" class="form-control  input-sm tot_qty_tablesize" data-required-error="Required" value="' + $size_label_count + '" required placeholder="Size Label Count"><div class="help-block with-errors"></div></div></td></tr>'; 
        ttots += parseInt($size_label_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
	
	 if(isNaN(ttots))
        {
            ttots=0;
        }
    else
        {
            ttots=ttots;
        }
    
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });

    $('.top-content').append('<form id="size_labelform" data-toggle= "validator"><div class="left-panelorc col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-5 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="ocn_number"class="form_label">OCN Number</label><input type="text" id="ocn_number_size" value="'+$ocn_number_size+'" class="form-control input-sm" placeholder="OCN Number"></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="size_label_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label>  <div><input type="text" id="treesl_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treesl" style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-7"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Size Label</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="size_label_table"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty</th><th><label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentagesize" onkeypress="return checkNumbersOnly(event)"  style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots_sum + '</td><td class="text-center tot_qty_tablesize_fin">' + ttots + '</td></tr></tbody></table></div> <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_size_label_button" style="margin-left:10px">Save</button> <button type="button" class="btn input-sm hidden btn-danger '+$cancel_button_class+'" id="cancel_size_button" style="float: right;margin-left: 10px;" onclick=cancelSizeDiv()>Cancel</button><button class="btn btn-default pull-right input-sm" type="button" id="size_labelclose">Close</button> </div> </div>  <div class="backdrop"></div></form>');

	//$('.left').removeClass('disabled')
    $('#size_labelform .tot_qty_tablesize').keyup(function () {
        var tot_qty_tablesize_carton_final = 0;
        $('#size_labelform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablesize').val() != undefined) {
                tot_qty_tablesize_carton_final += parseInt($(this).find('.tot_qty_tablesize').val());
            }
        })
        $('#size_labelform .tot_qty_tablesize_fin').text(tot_qty_tablesize_carton_final);
    });

	 $('.extra_percentagesize').keyup(function () {
		
        
        var tot_qty_tablesize_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tablesize').val(tot_qty);
                if ($(this).find('.tot_qty_tablesize').val() != undefined) {
                    tot_qty_tablesize_carton_final += parseInt($(this).find('.tot_qty_tablesize').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablesize_fin').text(tot_qty_tablesize_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tablesize').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tablesize_fin').text("0");
        }

    })
	
	
    $("#clickmesize_label").click(function () {
        // $('.sample-artwork').html("")
        $("#size_labelform .backdrop").toggleClass('active');
        $("#size_labelform .right-panel").toggleClass('active');
        $('#size_labelform .scalable').toggleClass('active');
        $("#size_labelform .left-panelorc").toggleClass('active');
    })

    $(".backdrop, #size_labelclose").click(function () {
        $("#size_labelform .left-panelorc").removeClass('active');
        $("#size_labelform .right-panel").removeClass('active');
        $("#size_labelform .backdrop").removeClass('active');
        $('#size_labelform .scalable').removeClass('active');
    })



    treestructuresize_label(data.treeviewsize_label, "treesl", $template_id,data.orchestra_size_label_orders);

    $('#size_labelform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSizeLabelStatus(data.color_wcl)
        }
    })
}
/**
 * This Function is Sub Function of #label_order_details.
 * To integrate Size Labels Information
 * @return Special Tag View
 */

function specialtags(data,user_type) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_special_tag_orders != "") {

      
        $template_id = data.orchestra_special_tag_orders[0].template_id;
        $exist_id = data.orchestra_special_tag_orders[0].id;
        
        var str=data.orchestra_special_tag_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
        $cancel_button_class="";
		$ocn_number_special = data.orchestra_special_tag_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $status="";
        $status_text="";
        $save_button_class="";
        $cancel_button_class="hidden";
		$ocn_number_special = "";
    }
    var st="";
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });
	$c=0;
	$.each(data.color_wcl, function (index, value) {
		if (data.orchestra_special_tag_orders != "") {
			$special_tag_count=data.orchestra_special_tag_orders_qty[$c].quantity;
		}else{
			 $special_tag_count="";
		}
		
        st += '<label for="edit_merchandiser_buyer" class="form_label">Final order qty for colour '+value.Color_Code+'</label><input class="form-control input-sm special_qty" value="' + $special_tag_count + '" onkeypress="return checkorcMOQ(this)" placeholder="Special Tag Count" type="number" required>';
		$c++;
    });
    $('.top-content').append('<form id="specialtagform" data-toggle= "validator"><div class="left-panelorc col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="ocn_number"class="form_label">OCN Number</label><input type="text" id="ocn_number_special" value="'+$ocn_number_special+'" class="form-control input-sm" placeholder="OCN Number"></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="special_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label>  <div><input type="text" id="tree2_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="tree2" style="height: calc(100vh - 338px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-4"> <div class="div-max"> <h4>Quantity Info for Special Tag</h4> <hr> <div class="scroll"><div class="form-group has-feedback has-success">'+st+'<div class="help-block with-errors"></div></div><button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_special_button" style="margin-left:10px">Save</button> <button type="button" class="btn hidden input-sm btn-danger '+$cancel_button_class+'" id="cancel_special_button" style="float: right;margin-left: 10px;" onclick=cancelSpecialDiv()>Cancel</button><button class="btn btn-default pull-right input-sm" type="button" id="specialclose">Close</button>  </div></div> </div>  <div class="backdrop"></div></form>');
	//$('.left').removeClass('disabled')
    $('#specialtagform .tot_qty_tablespecial').keyup(function () {
        var tot_qty_tablespecial_carton_final = 0;
        $('#specialtagform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tablespecial').val() != undefined) {
                tot_qty_tablespecial_carton_final += parseInt($(this).find('.tot_qty_tablespecial').val());
            }
        })
        $('#specialtagform .tot_qty_tablespecial_fin').text(tot_qty_tablespecial_carton_final);
    });

    $("#clickmespecial").click(function () {
        // $('.sample-artwork').html("")
        $("#specialtagform .backdrop").toggleClass('active');
        $("#specialtagform .right-panel").toggleClass('active');
        $('#specialtagform .scalable').toggleClass('active');
        $("#specialtagform .left-panelorc").toggleClass('active');
    })

    $(".backdrop, #specialclose").click(function () {
        $("#specialtagform .left-panelorc").removeClass('active');
        $("#specialtagform .right-panel").removeClass('active');
        $("#specialtagform .backdrop").removeClass('active');
        $('#specialtagform .scalable').removeClass('active');
    })



    treestructurespecial(data.treeviewspecial, "tree2", $template_id,data.orchestra_special_tag_orders);

    $('#specialtagform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setSpecialTagStatus(data.color_wcl)
        }
    })
}
/**
 * This Function is Sub Function of #label_order_details.
 * To integrate heatTransfer Information
 * @return Special Tag View
 */

function heatTransfer(data,user_type) {

    var tr = "";
    $qu = 0;
    if (data.orchestra_heat_transfer_orders != "") {

      
        $template_id = data.orchestra_heat_transfer_orders[0].template_id;
        $exist_id = data.orchestra_heat_transfer_orders[0].id;
        $heat_transfer_count=data.orchestra_heat_transfer_orders[0].final_qty;
        
        var str=data.orchestra_heat_transfer_orders[0].status;
        $status=str.replace(/ /g, "");
        $status_text=str;
        $save_button_class="hidden";
		$cancel_button_class="";
		$ocn_number_heat = data.orchestra_heat_transfer_orders[0].ocn_number;
    } else {
     
        $template_id = "";
        $exist_id = "";
        $heat_transfer_count="";
        $status="";
        $status_text="";
        $save_button_class="";
		$cancel_button_class="hidden";
		$ocn_number_heat = "";
    }
    var ttots = 0;
	var ttots_sum = 0;
    $.each(data.order_details, function (index, value) {
        if (data.orchestra_heat_transfer_orders != "") {

            $heat_transfer_count = data.orchestra_heat_transfer_orders_qty[$qu].quantity;
        } else {
            $heat_transfer_count = "";
        }
        tr += '<tr><input type="hidden" value="' + value.Color_Code + '" class="size_id"><input type="hidden" value="' + value.id + '" class="main_id"><td class="uvc_tco">' + value.UVC_TCO + '</td><td class="size">' + value.Size + '</td><td class="barcode">' + value.BarcodeForPricesTags + '</td><td class="sum">' + value.Sum + '</td><td> <div class="form-group has-feedback has-success"><input onkeypress="return checkorcMOQ(this)"  type="number" class="form-control  input-sm tot_qty_tableheat" data-required-error="Required" value="' + $heat_transfer_count + '" required placeholder="HT Count"><div class="help-block with-errors"></div></div></td></tr>';
        ttots += parseInt($heat_transfer_count);
		ttots_sum += parseInt(value.Sum);
        $qu++;
    });
	 if(isNaN(ttots))
        {
            ttots=0;
        }
    else
        {
            ttots=ttots;
        }
    
    var option = "<option value=''>Select Option</option>";
    $.each(data.orchestra_template_front, function (index, value) {
        option += "<option value=" + value.template_file + ">" + value.template_name + "</option>";
    });

    $('.top-content').append('<form id="heat_transferform" data-toggle= "validator"><div class="left-panelorc col-md-8" id="HandTag"><input type="hidden" value="' + $exist_id + '" id="exist_id"><div class="div-max"><div class="col-md-4 "><h4>Options</h4><hr><div class="form-group has-feedback has-success"><label for="ocn_number"class="form_label">OCN Number</label><input type="text" id="ocn_number_heat" value="'+$ocn_number_heat+'" class="form-control input-sm" placeholder="OCN Number"></div><div class="form-group has-feedback has-success"><label for="edit_merchandiser_buyer"class="form_label">Select Style</label><label id="heat_transfer_template_error" style="color:red;display: inherit;" class="hidden">Please select the template</label>  <div><input type="text" id="treeht_q" value="" class="form-control input-sm" placeholder="Search for Awesomeness"> </div><div class="scroll" id="treeht" style="height: calc(100vh - 205px);overflow-y: scroll;margin-top:15px"></div><div class="help-block with-errors"></div></div></div> <div class="col-md-8"><h4>ArtWork</h4><span class="label label-'+$status+'" style="position: absolute;top: 30px;left: 15px;">'+$status_text+'</span><hr> <div class="section-1 sections scroll"><div class="sample-artwork" style="text-align:center"></div></div></div></div></div><div class="right-panel col-md-4"> <div class="div-max scroll"> <h4>Quantity Info for Heat Transfer</h4> <hr> <div class="table-responsive"><table class="table table-striped table-hover table-condensed qty-table-modal" id="heat_transfer_table"><thead><tr><th>UVC TCO#</th><th>Size</th><th>Barcode</th><th>Qty</th><th><label style="float: left; line-height: 2">Extra %:</label> <input type="text" class="form-control input-sm extra_percentageheat" onkeypress="return checkNumbersOnly(event)"  style="width:60px; float: left; margin-left: 5px;"></th></tr> </thead><tbody>' + tr + '<tr class="strong"><td class="text-right" colspan="3">Total:</td><td >' + ttots_sum + '</td><td class="text-center tot_qty_tableheat_fin">' + ttots + '</td></tr></tbody></table></div> <button class="btn btn-primary pull-right input-sm left '+$save_button_class+'" type="submit" id="save_heat_transfer_button" style="margin-left:10px">Save</button> <button type="button" class="btn btn-danger hidden input-sm '+$cancel_button_class+'" id="cancel_heat_button" style="float: right;margin-left:10px" onclick=cancelHeatDiv()>Cancel</button><button class="btn btn-default pull-right input-sm" type="button" id="heat_transferclose">Close</button></div> </div>  <div class="backdrop"></div></form>');

	//$('.left').removeClass('disabled')
    $('#heat_transferform .tot_qty_tableheat').keyup(function () {
        var tot_qty_tableheat_carton_final = 0;
        $('#heat_transferform tbody tr').each(function (keys, vals) {
            if ($(this).find('.tot_qty_tableheat').val() != undefined) {
                tot_qty_tableheat_carton_final += parseInt($(this).find('.tot_qty_tableheat').val());
            }
        })
        $('#heat_transferform .tot_qty_tableheat_fin').text(tot_qty_tableheat_carton_final);
    });
	
	 $('.extra_percentageheat').keyup(function () {
		
        
        var tot_qty_tableheat_carton_final = 0;
        if ($(this).val() != "") {
            var per = $(this).val();
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                var sum = $(this).find('.sum').text();
                var per_qty = (parseInt(sum) / 100) * parseInt(per);
                var tot_qty = parseInt(sum) + parseInt(per_qty);
                $(this).find('.tot_qty_tableheat').val(tot_qty);
                if ($(this).find('.tot_qty_tableheat').val() != undefined) {
                    tot_qty_tableheat_carton_final += parseInt($(this).find('.tot_qty_tableheat').val());
                }
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tableheat_fin').text(tot_qty_tableheat_carton_final);
        } else {
            
            $(this).parent().parent().parent().parent().parent().find('.qty-table-modal tbody tr').each(function () {
                $(this).find('.tot_qty_tableheat').val("");
            })
            $(this).parent().parent().parent().parent().parent().find('.tot_qty_tableheat_fin').text("0");
        }

    })

    $("#clickmeheat_transfer").click(function () {
        // $('.sample-artwork').html("")
        $("#heat_transferform .backdrop").toggleClass('active');
        $("#heat_transferform .right-panel").toggleClass('active');
        $('#heat_transferform .scalable').toggleClass('active');
        $("#heat_transferform .left-panelorc").toggleClass('active');
    })

    $(".backdrop, #heat_transferclose").click(function () {
        $("#heat_transferform .left-panelorc").removeClass('active');
        $("#heat_transferform .right-panel").removeClass('active');
        $("#heat_transferform .backdrop").removeClass('active');
        $('#heat_transferform .scalable').removeClass('active');
    })



    treestructureheat_transfer(data.treeviewheat_transfer, "treeht", $template_id,data.orchestra_heat_transfer_orders);

    $('#heat_transferform').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            e.preventDefault();
            setheat_transferStatus(data.color_wcl)
        }
    })
}