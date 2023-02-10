$(document).ready(function () {
  //Date picker
  $('.date_input').datepicker({
    format: "dd MM yyyy",
    autoclose: true,
    language: "id",
    locale: 'id',
    todayHighlight: true,
    endDate: '31-12-' + (new Date).getFullYear(),
  });

  $('select[data-role=select2]').select2({
    theme: 'bootstrap',
    width: '100%'
  });

  // Laporan KIB
  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass: 'iradio_flat-green'
  })


  //  Golongan
  /*
  $('#kode_jenis').change(function(e){
    $('#id_pengguna').html('<option value="">Silahkan Pilih</option>');
    $('#id_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
    $('#id_sub_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
    var kode_jenis = $(this).val();
    url=base_url+'laporan/get_pengguna/';
    $.post(url,{kode_jenis:kode_jenis,}, function(respon){
        obj_respon      =jQuery.parseJSON(respon);
        $('#id_pengguna').html(obj_respon.option);
    });
  });*/


  $('#kode_kelompok').change(function (e) {
    $('#kode_jenis').html('<option value="">Silahkan Pilih</option>');
    // $('#id_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
    // $('#id_sub_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
    var kode_kelompok = $(this).val();
    url = base_url + 'laporan/get_kode_jenis/';
    $.post(url, { kode_kelompok: kode_kelompok, }, function (respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_jenis').html(obj_respon.option);
    });
  });


  //  pengguna
  $('#id_pengguna').change(function (e) {
    $('#id_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
    $('#id_sub_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
    var id_pengguna = $(this).val();
    url = base_url + 'laporan/get_kuasa_pengguna/';
    $.post(url, { id_pengguna: id_pengguna, }, function (respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#id_kuasa_pengguna').html(obj_respon.option);
    });
  });

  //  pengguna
  $('#id_kuasa_pengguna').change(function (e) {
    $('#sub_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
    var id_pengguna = $('#id_pengguna').val();
    var id_kuasa_pengguna = $(this).val();
    url = base_url + 'laporan/get_sub_kuasa_pengguna/';
    $.post(url, { id_pengguna: id_pengguna, id_kuasa_pengguna: id_kuasa_pengguna, }, function (respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#id_sub_kuasa_pengguna').html(obj_respon.option);
    });
  });

});
