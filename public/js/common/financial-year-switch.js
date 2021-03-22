$(document).ready (function(){

    /** Fiscal Year Select box begin */
    $('.year-nav-menu').html('');
    // add year selection menu into navbar
    $('.year-nav-menu').html(' <select class="selectpicker selected-year" id="financialYear" name="financialYear" data-live-search="true">Select Year</select>');
    
    // Populate 30 years in selectbox options
        var yearsLength = 30;
        var currentYear = new Date().getFullYear();
        for(var i = 0; i < yearsLength; i++){
            var next = currentYear+1;
            var year_text = currentYear + '-' + next.toString().slice(-2);
            var year_value = currentYear + '-' + next;
            option = $('<option value="' + year_value + '">' + year_text + '</option>');
            $('#financialYear').append(option);
            currentYear--;
        }

    // Make current year as selected if fiscal year not selected
        $("#financialYear").val(fiscal_year); 

        $('#financialYear').selectpicker('refresh');
        
    // [ Trigger event on selectbox change, to change fiscal year value in local storage]
        $('#financialYear').on('change', function(e){
            $.ajax({
                url:base_url  + "/jga/set-financial-year-session",
                dataType:"json",
                method:'post',
                data: { year: this.value,  _token: csrf_token },
                success:function(data){
                    location.reload(); 
                }
            })
        });
 });