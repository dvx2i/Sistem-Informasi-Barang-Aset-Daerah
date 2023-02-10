$(document).ready(function(){
  $('.format_number').mask("#.##0", {
    reverse : true,
  });

  $('.format_float').mask("#.###,##", {
    reverse : true,
  });
});
