$(document).ready(function(){
  $('select[data-role=select2]').select2({
    theme: 'bootstrap',
    width: '100%'
  });

  //Flat red color scheme for iCheck
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass   : 'iradio_flat-green'
  })

});

$(document).on('click','#cari_jss', function(e){
  var id_upik=$('#id_upik').val();
  url=base_url+'user/get_jss';
  $.post(url,{id_upik:id_upik,}, function(respon){
      obj_respon      =jQuery.parseJSON(respon);
      if (obj_respon.status) {
        $('#nama').val(obj_respon.nama);
        // $('#nik').val(obj_respon.nik);
        $('#nip').val(obj_respon.nip);
        $('#email').val(obj_respon.email);
        $('#nomor_telepone').val(obj_respon.no_telp);
        // $('#alamat').val(obj_respon.alamat);
        $('#id_user_jss').val(obj_respon.id_user);
        $('#username').val(obj_respon.username);
        swal ( "Berhasil" ,  "Data ditemukan" ,  "success" );
      }else {
        swal ( "Gagal" ,  obj_respon.message ,  "error" );
      }

  });
});
