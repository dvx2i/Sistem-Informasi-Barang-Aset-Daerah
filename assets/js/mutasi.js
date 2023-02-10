$(document).ready(function(){
  //Date picker
  $('.date_input').datepicker({
      format: "dd MM yyyy",
      autoclose: true,
      language: "id",
      locale: 'id',
      todayHighlight: true,
      endDate:'31-12-'+(new Date).getFullYear(),
  });
  

  $(".date_input").datepicker().on('changeDate', function (e) {
    var pickedMonth = new Date(e.date).getMonth() + 1;
    var pickedYear = new Date(e.date).getFullYear();    
    // console.log(pickedMonth);
    // console.log(pickedYear);      
    url = base_url + 'global_controller/cek_stock_opname/';
    $.post(url, { bulan: pickedMonth, tahun: pickedYear  }, function (respon) {
      obj_respon = jQuery.parseJSON(respon);
      // console.log(obj_respon.status);
      if(!obj_respon.status){
        $(".date_input").datepicker('setDate', new Date()); 
        swal({
          type: 'error',
          title: '!!!',
          text: obj_respon.message,
          // footer: '<a href>Why do I have this issue?</a>'
        });
        return false;
      }
      // getNoRegister();
    });     
});

  $('select[data-role=select2]').select2({
    theme: 'bootstrap',
    width: '100%'
  });

  //Initialize Select2 Elements
  $('#kode_lokasi_penerima').select2();
  $('#barang').select2()

  //  BIDANG CLICK
  $('#kib').change(function(e){
    $('#barang').html('');

    var kode_gol=$('option:selected',this).attr('kode_gol');
    url=base_url+'global_controller/get_barang_kib/';
    $.post(url,{kode_gol:kode_gol,}, function(respon){
        obj_respon      =jQuery.parseJSON(respon);
        $('#barang').html(obj_respon.option);
    });
  });
}); //ready function
