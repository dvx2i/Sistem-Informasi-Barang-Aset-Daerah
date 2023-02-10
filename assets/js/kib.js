$(document).ready(function () {
  var des = $("#sumber_dana option:selected").text();

  $('#asal_usul').val(des);
  $('#asal_usul_text').val(des);
  
  var rupiah = document.getElementById("harga");
  rupiah.value = formatRupiah(rupiah.value, undefined);
  
  var tanggal_transaksi = $('#tanggal_transaksi').val();
  var nomor_transaksi = $('#nomor_transaksi').val();

  if (tanggal_transaksi.length != 0 && nomor_transaksi.length != 0){
    $('#input_disable').removeClass('input_disable');
  }
  else{
    $('#input_disable').addClass('input_disable');
  }
  
  

  $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
  });

  show_input();
  $('.format_number').mask("#.##0", {
    reverse: true,
  });
  
  var kode_pemilik = $('#status_pemilik').val();
  url = base_url + 'global_controller/get_kode_lokasi/';
  $.post(url, { kode_pemilik: kode_pemilik, }, function (respon) {
    obj_respon = jQuery.parseJSON(respon);
    $('#kode_lokasi').val(obj_respon.kode_lokasi);
    $('#nama_lokasi').val(obj_respon.nama_lokasi);
    // getNoRegister();
  });

  // $('.format_float').mask("#.###,##", { 5 agustus 2020
  // $('.format_float').mask("#.##0,00", {
  //   // reverse: true,
  // });
  var rupiah = document.getElementById("harga");
  rupiah.addEventListener("keyup", function(e) {
  // tambahkan 'Rp.' pada saat form di ketik
  // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
  rupiah.value = formatRupiah(this.value, undefined);
  });

  /* Fungsi formatRupiah */
  function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, "").toString(),
      split = number_string.split(","),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
      separator = sisa ? "." : "";
      rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
  }

  $('#jumlah_barang').change(function () {
    if ($(this).val() < 1) {
      $(this).val(1);
    }
  })

  //Date picker
  $('.tahun_input').datepicker({
    format: "yyyy",
    minViewMode: "years",
    autoclose: true,
    todayHighlight: true,
    endDate: '31-12-' + (new Date).getFullYear(),
  });

  //Date picker
  $('.date_input').datepicker({
    format: "dd MM yyyy",
    autoclose: true,
    language: "id",
    locale: 'id',
    todayHighlight: true,
    endDate: '31-12-' + (new Date).getFullYear(),
  });

  $("#tanggal_transaksi").datepicker().on('changeDate', function (e) {
      var pickedMonth = new Date(e.date).getMonth() + 1;
      var pickedYear = new Date(e.date).getFullYear();    
      // console.log(pickedMonth);
      // console.log(pickedYear);      
      url = base_url + 'global_controller/cek_stock_opname/';
      $.post(url, { bulan: pickedMonth, tahun: pickedYear  }, function (respon) {
        obj_respon = jQuery.parseJSON(respon);
        // console.log(obj_respon.status);
        if(!obj_respon.status){
          $("#tanggal_transaksi").datepicker('setDate', new Date()); 
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
    width: '100%',
  });

  // $("#checkbox_kib_f").click(function(){
  //   console.log("===");
  // })

  //  BIDANG CLICK
  $('#kode_objek').change(function (e) {
    $('#kode_rincian_objek').html('<option value="">Please Select</option>');
    $('#kode_sub_rincian_objek').html('<option value="">Please Select</option>');
    $('#kode_sub_sub_rincian_objek').html('<option value="">Please Select</option>');
    var kode_barang = $(this).val();
    url = base_url + 'global_controller/get_rincian_objek/';
    $.post(url, { kode_barang: kode_barang, }, function (respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_rincian_objek').html(obj_respon.option);
    });
    setKodeBarang(this);
  });

  $('#kode_rincian_objek').change(function (e) {
    $('#kode_sub_rincian_objek').html('<option value="">Please Select</option>');
    $('#kode_sub_sub_rincian_objek').html('<option value="">Please Select</option>');
    var kode_barang = $(this).val();
    url = base_url + 'global_controller/get_sub_rincian_objek/';
    $.post(url, { kode_barang: kode_barang, }, function (respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_sub_rincian_objek').html(obj_respon.option);
    });
    setKodeBarang(this);
  });

  $('#kode_sub_rincian_objek').change(function (e) {
    $('#kode_sub_sub_rincian_objek').html('<option value="">Please Select</option>');
    var kode_barang = $(this).val(); //console.log(kode_barang);

    url = base_url + 'global_controller/get_sub_sub_rincian_objek/';
    $.post(url, { kode_barang: kode_barang, }, function (respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_sub_sub_rincian_objek').html(obj_respon.option);
    });
    setKodeBarang(this);
  });

  $('#kode_sub_sub_rincian_objek').change(function (e) {
    setKodeBarang(this);
  });

  function setKodeBarang(data) {
    var kode = $(data).val();
    var text = $('option:selected', data).text();
    arr_text = text.split(' - ')[1];
    var length = kode.length;
    if (length == 21) {
      $('#kode_barang').val(kode);
      $('#nama_barang').val(arr_text);
    } else {
      $('#kode_barang').val('');
      $('#nama_barang').val('');
    }
    // getNoRegister();
  }


  // KIB ATB

  //  BIDANG CLICK
  $('#kode_objek_atb').change(function (e) {
    $('#kode_rincian_objek_atb').html('<option value="">Please Select</option>');
    $('#kode_sub_rincian_objek_atb').html('<option value="">Please Select</option>');
    $('#kode_sub_sub_rincian_objek_atb').html('<option value="">Please Select</option>');
    var kode_barang = $(this).val();
    url = base_url + 'global_controller/get_rincian_objek_atb/';
    $.post(url, { kode_barang: kode_barang, }, function (respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_rincian_objek_atb').html(obj_respon.option);
    });
    setKodeBarang(this);
  });

  $('#kode_rincian_objek_atb').change(function (e) {
    $('#kode_sub_rincian_objek_atb').html('<option value="">Please Select</option>');
    $('#kode_sub_sub_rincian_objek_atb').html('<option value="">Please Select</option>');
    var kode_barang = $(this).val();
    url = base_url + 'global_controller/get_sub_rincian_objek_atb/';
    $.post(url, { kode_barang: kode_barang, }, function (respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_sub_rincian_objek_atb').html(obj_respon.option);
    });
    setKodeBarang(this);
  });

  $('#kode_sub_rincian_objek_atb').change(function (e) {
    $('#kode_sub_sub_rincian_objek_atb').html('<option value="">Please Select</option>');
    var kode_barang = $(this).val();
    url = base_url + 'global_controller/get_sub_sub_rincian_objek_atb/';
    $.post(url, { kode_barang: kode_barang, }, function (respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_sub_sub_rincian_objek').html(obj_respon.option);
    });
    setKodeBarang(this);
  });






  $('#status_pemilik').change(function (e) {
    var kode_pemilik = $(this).val();
    url = base_url + 'global_controller/get_kode_lokasi/';
    $.post(url, { kode_pemilik: kode_pemilik, }, function (respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_lokasi').val(obj_respon.kode_lokasi);
      $('#nama_lokasi').val(obj_respon.nama_lokasi);
      // getNoRegister();
    });

  });

  /*
  $('div').on('ifClicked','.barang_kib_f', function(e){
    var input_disable_barang = $('#input_disable_barang').val();
    var kode_jenis = $(this).attr('attr_kode_jenis');
    // console.log("=======");
    if ($(this).prop('checked')){
      $("#input_kib_f").prop("disabled",false);
      $('#input_disable_barang').removeClass('input_disable');
      $('#kode_objek').val('');  $('#kode_objek').change();

      $('#kode_barang').val('');
      $('#nama_barang').val('');
    }
    else{
      $("#input_kib_f").prop("disabled",true);
      $('#input_disable_barang').addClass('input_disable');

      var url=base_url+'global_controller/get_kode_barang_kib_f/'+kode_jenis;
      $.post(url,{kode_jenis:'1',}, function(respon){
        obj_respon      =jQuery.parseJSON(respon);
        $('#kode_barang').val(obj_respon.kode_barang);
        $('#nama_barang').val(obj_respon.nama_barang);
      });
    }

  });
  */

  // $("select").select2("readonly", true);
  // $("select").select2({ readonly: 'readonly' });

});// end ready function



function show_input() {
  var tanggal_transaksi = $('#tanggal_transaksi').val();
  var nomor_transaksi = $('#nomor_transaksi').val();

  if (tanggal_transaksi.length != 0 && nomor_transaksi.length != 0)
    $('#input_disable').removeClass('input_disable');
  else
    $('#input_disable').addClass('input_disable');
}

//8 may 2020
//  BIDANG CLICK
$(document).on('change', '#sumber_dana', function (e) {
  $('#rekening').html('<option value="">Silahkan Pilih</option>');

  var id_sumber_dana = $(this).val();
  url = base_url + 'global_controller/get_rekening/';
  $.post(url, { id_sumber_dana: id_sumber_dana, }, function (respon) {
    obj_respon = jQuery.parseJSON(respon);
    $('#rekening').html(obj_respon.option);
  });

  // console.log($("#sumber_dana option:selected").text());
  var des = $("#sumber_dana option:selected").text();

  $('#asal_usul').val(des);
  $('#asal_usul_text').val(des);

});

