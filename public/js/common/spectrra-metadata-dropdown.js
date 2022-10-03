const board_category_dropdown = (board_category) => {
    var board_category_options =
      '<option value="" selected="selected">-- Select Board Category --</option>';
    $.each(board_category, function (key, item) {
      board_category_options +=
        '<option value="' + key + '">' + item + "</option>";
    });
    $("#board_category").html(board_category_options);
  }
  
  const boards_dropdown = (boards) => {
    var boards_options =
      '<option value="" selected="selected">-- Select Board Name --</option>';
    $.each(boards, function (key, item) {
      boards_options += '<option value="' + key + '">' + item + "</option>";
    });
    $("#board_name").html(boards_options);
  }
  
  const color_match_dropdown = (color_match) => {
    var color_match_options =``;
    $.each(color_match, function (key, item) {
      color_match_options += '<option value="' + key + '">' + item + "</option>";
    });
    $("#color_match").html(color_match_options);
  }