<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <style type="text/css">
#wrapper {
  background: #ccc;
  overflow: hidden;
  transition: height 200ms;
}
  </style>
</head>
<body>
<button id="button">Toggle Expand/Collapse</button>
<div id="wrapper" class="open">
  <ul id="list">
    <li>Item</li>
    <li>Item</li>
    <li>Item</li>
    <li>Item</li>
  </ul>
</div>
<script type="text/javascript">
  $(function() {
  var b = $("#button");
  var w = $("#wrapper");
  var l = $("#list");
  
  w.height(l.outerHeight(true));

  b.click(function() {
  
    if(w.hasClass('open')) {
      w.removeClass('open');
      w.height(0);
    } else {
      w.addClass('open');
      w.height(l.outerHeight(true));
    }
  
  });
});
</script>
</body>
</html>
