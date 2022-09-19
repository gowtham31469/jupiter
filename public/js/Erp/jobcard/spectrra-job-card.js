$(function () {
  var form = $("#create_spectrra_jc_form");

  form.validator().on("submit", function (e) {
    if (e.isDefaultPrevented()) {
    } else {
      e.preventDefault();
      alert()
    //   storeSpectrraMetadata(form);
    }
  });
});
