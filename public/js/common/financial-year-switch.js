$(document).ready (function(){

    /** Fiscal Year Select box begin */
    $('.year-nav-menu').html('');
    // add year selection menu into navbar
    $('.year-nav-menu').html(' <select class="selectpicker selected-year" id="financialYear" name="financialYear" data-live-search="true">Select Year</select>');
    
    // Populate 30 years in selectbox options
        var yearsLength = 30;
        var currentYear = new Date().getFullYear();
        for(var i = 0; i < 30; i++){
            var next = currentYear+1;
            var year_text = currentYear + '-' + next.toString().slice(-2);
            var year_value = currentYear + '-' + next;
            option = $('<option value="' + year_value + '">' + year_text + '</option>');
            $('#financialYear').append(option);
            currentYear--;
        }

    // Make current year as selected if fiscal year not selected
        $fiscal_year = getCookie("fiscal-year");
        if ($fiscal_year === "") {
            document.cookie = "fiscal-year="+$('#financialYear option:first').val()+"; path=/;";
            $("#financialYear").val(getCookie("fiscal-year"));
        } else {
            $("#financialYear").val(getCookie("fiscal-year"));
        } 
        $('#financialYear').selectpicker('refresh');
        
    // [ Trigger event on selectbox change, to change fiscal year value in local storage]
        $('#financialYear').on('change', function(e){
            document.cookie = "fiscal-year="+this.value+"; path=/;";
            location.reload(); 
        });
    
    /** Fiscal year select box end */

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
            }
            return "";
        }
 });