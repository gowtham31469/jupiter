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
        if (localStorage.getItem("fiscal-year") === null) {
            localStorage.setItem("fiscal-year", $('#financialYear option:first').val());
            $("#financialYear").val(localStorage.getItem("fiscal-year"));
        } else {
            $("#financialYear").val(localStorage.getItem("fiscal-year"));
        } 
        $('#financialYear').selectpicker('refresh');
        
    // [ Trigger event on selectbox change, to change fiscal year value in local storage]
        $('#financialYear').on('change', function(e){
            
            localStorage.setItem("fiscal-year", this.value);
            location.reload(); 
        });
    
    /** Fiscal year select box end */
 });