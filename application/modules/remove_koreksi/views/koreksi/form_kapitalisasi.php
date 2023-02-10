<style media="screen">
  th, td { white-space: nowrap; }
  .select2-selection__rendered { margin-top: 1px!important }
</style>

  <section class="content">
    <h2 style="margin-top:0px"> Kapitalisasi</h2>
    <form action="<?php echo $action; ?>" method="post" id="form_ususlan">

      <input type="hidden" name="id_kode_barang" value="<?= $kib->id_kode_barang; ?>">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="">Nomor Register</label>
              <b> <input type="text" class="form-control" name="" placeholder="" value="<?= $kib->nomor_register; ?>" disabled /></b>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="">Kode Barang</label>
            <b> <input type="text" class="form-control" name="" placeholder="" value="<?= $kib->kode_barang; ?>" disabled /></b>
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            <label for="">Nama Barang</label>
            <b><input type="text" class="form-control" name="" placeholder="" value="<?= $kib->nama_barang; ?>" disabled /></b>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="">Nilai Perolehan</label>
            <input type="text" class="form-control format_number" name="" id="nilai_perolehan" placeholder="0" value="<?= $kib->harga; ?>" readonly />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="date">Tanggal Pengajuan <?php echo form_error('tanggal_pengajuan') ?></label>
            <input type="text" class="form-control date_input" name="tanggal_pengajuan" id="tanggal_pengajuan" placeholder="Tanggal Pengajuan" value="<?php echo $tanggal_pengajuan; ?>" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="">Nilai Kapitalisasi <?php echo form_error('nilai_kapitalisasi') ?></label>
            <input type="text" class="form-control format_number" name="nilai_kapitalisasi" id="nilai_kapitalisasi" placeholder="0" value="" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="">Penambahan Umur Ekonomis (Bulan) </label>
            <input type="text" class="form-control format_number" name="penambahan_umur_ekonomis" id="penambahan_umur_ekonomis" placeholder="0" value="" readonly />
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="<?php echo base_url('kapitalisasi/pengajuan') ?>" class="btn btn-default">Batalkan</a>
    </form>


  </section>

<script type="text/javascript">
var role_kapitalisasi = <?= $role_kapitalisasi; ?>;
var batas_a = role_kapitalisasi.Batas_A;
var batas_b = role_kapitalisasi.Batas_B;
var batas_c = role_kapitalisasi.Batas_C;
var batas_d = role_kapitalisasi.Batas_D;
var batas_e = role_kapitalisasi.Batas_E;
var batas_f = role_kapitalisasi.Batas_F;
// $.each(role_kapitalisasi, function(index, value){
//   // console.log(index+' = '+value);
// })
// console.log();

$('#form_ususlan').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
    return false;
  }
});

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

  $('.format_number').mask("#.##0", {
    reverse : true,
  });

  $("#nilai_kapitalisasi").keydown(function(){
    $('#penambahan_umur_ekonomis').val(0);
  });
  $("#nilai_kapitalisasi").keyup(function(){
    var nilai_perolehan = $('#nilai_perolehan').val().split('.').join('');
    var nilai_kapitalisasi = $(this).val().split('.').join('');
    if (nilai_kapitalisasi == 0 || nilai_kapitalisasi == '') {
      $('#penambahan_umur_ekonomis').val(0);return false;
    }
    var hasil = (nilai_kapitalisasi/nilai_perolehan)*100;
    if (hasil > batas_a && hasil<batas_b) {
      $('#penambahan_umur_ekonomis').val(role_kapitalisasi.Tambah_A)
    }
    else if (hasil >= batas_b && hasil<batas_c) {
      $('#penambahan_umur_ekonomis').val(role_kapitalisasi.Tambah_B)
    }
    else if (hasil >= batas_c && hasil<batas_d) {
      $('#penambahan_umur_ekonomis').val(role_kapitalisasi.Tambah_C)
    }
    else if (hasil >= batas_d && hasil<batas_e) {
      $('#penambahan_umur_ekonomis').val(role_kapitalisasi.Tambah_D)
    }
    else if (hasil >= batas_e && hasil<batas_f) {
      $('#penambahan_umur_ekonomis').val(role_kapitalisasi.Tambah_E)
    }
    else if (hasil > batas_f) {
      $('#penambahan_umur_ekonomis').val(role_kapitalisasi.Tambah_E)
    }

    // console.log(role_kapitalisasi.kelompok_manfaat);

  });

  /*url=base_url+'global_kapitalisasi/get_rincian_objek/';
  $.post(url,{kode_barang:kode_barang,}, function(respon){
      obj_respon      =jQuery.parseJSON(respon);
      $('#kode_rincian_objek').html(obj_respon.option);
  });*/


});
</script>
