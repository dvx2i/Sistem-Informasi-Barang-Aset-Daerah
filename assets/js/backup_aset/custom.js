$(document).ready(function(){
  // treeview open / close
  var treeview_array = JSON.parse(sessionStorage.getItem("treeview"));
  var i;
  for (i = 0; i < treeview_array.length; i++) {
    $('li#'+treeview_array[i]).addClass('menu-open');
    $('#'+treeview_array[i]+'>ul').css('display', 'block');
  }
  // active menu
  var menu = sessionStorage.getItem("menu");
  $('li#'+menu).addClass('active');
});

function setTreeView(data){
  treeview=$(data).attr('id');
  var treeview_array = JSON.parse(sessionStorage.getItem("treeview"));
  if (treeview_array==null) treeview_array=['start']; // bila belum ada array

  menu_open=$('li#'+treeview).hasClass('menu-open');
  if (!menu_open) {
    treeview_array=['start']
    treeview_array.push(treeview);
  }else {
    treeview_array = jQuery.grep(treeview_array, function(value) {
      return value != treeview;
    });
  }
  sessionStorage.setItem("treeview", JSON.stringify(treeview_array));
}

function setMenu(data){
  menu=$(data).attr('id');
  sessionStorage.setItem("menu", menu);
}
