const spectrraMetadata = (obj) => {
  // spectrra prints id 2
  obj.value == 2
    ? $(".spectrra-metadata-btn").removeClass("hidden")
    : $(".spectrra-metadata-btn").addClass("hidden");
};

const getSpectrraErpMetadata = (temp_id) => {
    var buyer = localStorage.getItem("buyer");
    
    $.ajax({
        url: base_url + "/admin/template-management/get-spectrra-company-metadata",
        type: "GET",
        dataType: "json",
        data: {
            template_id: temp_id,
            buyer: buyer
        },
        beforeSend: function () {
            $(".loader").show();
            $(".loader").css('margin-top', '12%');
        },
        success: function (data) {
        
            
        },
        error: function (data) {
            $('.snackbar_span').html('Something went wrong!!');
            showSnackbar();
        },
        complete: function () {
            $(".loader").hide();
            $(".loader").css('margin-top', '0%');
        }
    });

    $('#spectrraMetadataModal').modal("show");
    $('#spectrraMetadataModal').removeData('.modal');
};
