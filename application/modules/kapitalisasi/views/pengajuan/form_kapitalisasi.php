<style media="screen">
  th,
  td {
    white-space: nowrap;
  }

  .select2-selection__rendered {
    margin-top: 1px !important
  }
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
          <input type="text" class="form-control " name="" id="nilai_perolehan" placeholder="0" value="<?= $kode_jenis != '06' ? str_replace('.', ',', $kib->harga) : str_replace('.', ',', $kib->nilai_kontrak); ?>" readonly />
        </div>
      </div>
      
    </div>

    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="date">Tanggal Pengajuan <?php echo form_error('tanggal_pengajuan') ?></label>
          <input type="text" class="form-control date_input" autocomplete="off" name="tanggal_pengajuan" id="tanggal_pengajuan" placeholder="Tanggal Pengajuan" value="<?php echo $tanggal_pengajuan; ?>" />
        </div>
      </div><div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Sumber Dana / Anggaran <?php echo form_error('sumber_dana') ?></label>
            <select class="form-control" name="sumber_dana" id="sumber_dana">
              <option value="">Silahkan Pilih</option>
              <?php foreach ($lis_sumber_dana as $key => $value) : ?>
                <option value="<?= $value['id_sumber_dana']; ?>" <?= ($value['id_sumber_dana'] == $sumber_dana) ? 'selected' : ''; ?>><?= $value['nama_sumber_dana']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Kode Anggaran <?php echo form_error('kode_rekening') ?></label>
            <?php /*<input type="text" class="form-control" name="rekening" id="rekening" placeholder="" value="<?php echo $rekening; ?>" /> */ ?>

            <select class="form-control" name="rekening" id="rekening" data-role="select2">
              <option value="">Silahkan Pilih</option>
              <?php foreach ($lis_rekening as $key => $value) : ?>
                <option value="<?= $value['id_rekening']; ?>" <?= $value['id_rekening'] == $rekening ? 'selected' : ''; ?>> <?= $value['kode_rekening'] . ' - ' . $value['nama_rekening']; ?></option>;
              <?php endforeach; ?>
            </select>

          </div>
        </div>
    </div>



    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <?php if (empty($data_reklas)) : ?>
        <li class="active"><a href="#kapitalisasi" data-toggle="tab">Kapitalisasi</a></li>
        <?php endif; ?>
          <li><a href="#koreksi" data-toggle="tab">Koreksi</a></li>
        <!-- <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li> -->
      </ul>
      <div class="tab-content">

        <div class="tab-pane <?= empty($data_reklas) ? 'active' : ''; ?> " id="kapitalisasi">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Nilai Kapitalisasi <?php echo form_error('nilai_kapitalisasi') ?></label>
                  <input type="text" class="form-control" name="nilai_kapitalisasi" id="nilai_kapitalisasi" placeholder="0" value="" />
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
        </div>
        <div class="tab-pane <?= !empty($data_reklas) ? 'active' : ''; ?>" id="koreksi">
          <?php if (empty($data_reklas)) : ?>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="" style="color:green">Tambah <?php echo form_error('koreksi_tambah') ?></label>
                <input type="text" class="form-control " name="koreksi_tambah" id="koreksi_tambah" placeholder="0" value="" />
              </div>
            </div>
            <?php if($this->session->userdata('session')->id_role == '1') : ?>
            <div class="col-md-3">
              <div class="form-group">
                <label for="" style="color:red">Kurang <?php echo form_error('koreksi_kurang') ?></label>
                <input type="text" class="form-control" name="koreksi_kurang" id="koreksi_kurang" placeholder="0" value="" />
              </div>
            </div>
            <?php endif; ?>
          </div>
          <?php else : ?>
            <!-- ======================================================================-->
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Nomor Register</label>
                  <b> <input type="text" class="form-control" name="" placeholder="" value="<?php echo $kib_asal['nomor_register']; ?>" disabled /></b>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="">Kode Barang</label>
                  <b> <input type="text" class="form-control" name="" placeholder="" value="<?php echo $kode_barang_asal['kode_barang']; ?>" disabled /></b>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label for="">Nama Barang</label>
                  <b><input type="text" class="form-control" name="" placeholder="" value="<?php echo $kode_barang_asal['kode_sub_sub_rincian_objek'] == '000' ? $kode_barang_asal['nama_barang'] : $kode_barang_asal['nama_barang_simbada'] ?>" disabled /></b>
                </div>
              </div>
            </div>

            <div class="row">
              <!-- <div class="col-md-3">
                <div class="form-group">
                  <label for="">Nilai Kapitalisasi <?php echo form_error('nilai_kapitalisasi') ?></label>
                  <input type="text" class="form-control" name="nilai_kapitalisasi" id="nilai_kapitalisasi" placeholder="0" value="<?php echo str_replace(".", ",",  $kib_asal['harga']); ?>" />
                </div>
              </div> -->
              <div class="col-md-3">
                <div class="form-group">
                  <label for="" style="color:green">Tambah <?php echo form_error('koreksi_tambah') ?></label>
                  <input type="text" class="form-control " name="koreksi_tambah" id="koreksi_tambah" placeholder="0" value="<?= $kode_jenis_asal != '06' ?  str_replace('.', ',', $kib_asal['harga']) : str_replace('.', ',', $kib_asal['nilai_kontrak']); ?>" />
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>


        <!-- /.tab-pane -->
      </div>
      <!-- /.tab-content -->
    </div>
    <!-- nav-tabs-custom -->


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

  $(document).ready(function() {
    $("#nilai_kapitalisasi").keyup();
    // $("#koreksi_tambah").keyup();
    // $("#koreksi_kurang").keyup();
    
    var rupiah = document.getElementById("nilai_kapitalisasi");
    var perolehan = document.getElementById("nilai_perolehan");
    var koreksi_tambah = document.getElementById("koreksi_tambah");
    var koreksi_kurang = document.getElementById("koreksi_kurang");
    rupiah.value = formatRupiah(rupiah.value, undefined);
    perolehan.value = formatRupiah(perolehan.value, undefined);
    koreksi_tambah.value = formatRupiah(koreksi_tambah.value, undefined);

    //Date picker
    $('.date_input').datepicker({
      format: "dd MM yyyy",
      autoclose: true,
      language: "id",
      locale: 'id',
      todayHighlight: true,
      endDate: '31-12-' + (new Date).getFullYear(),
    });

    
  rupiah.addEventListener("keyup", function(e) {
  // tambahkan 'Rp.' pada saat form di ketik
  // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
  rupiah.value = formatRupiah(this.value, undefined);
  });
  
  koreksi_tambah.addEventListener("keyup", function(e) {
  // tambahkan 'Rp.' pada saat form di ketik
  // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
  koreksi_tambah.value = formatRupiah(this.value, undefined);
  });
  
  <?php if($this->session->userdata('session')->id_role == '1') : ?>
  koreksi_kurang.addEventListener("keyup", function(e) {
  // tambahkan 'Rp.' pada saat form di ketik
  // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
  koreksi_kurang.value = formatRupiah(this.value, undefined);
  });
  <?php endif; ?>

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

    $('.format_number').mask("#.##0", {
      reverse: true,
    });
    

    $("ul.nav>li").click(function() {
      $('#nilai_kapitalisasi').val('');
      $('#koreksi_tambah').val('');
      $('#koreksi_kurang').val('');
    });

    $("#nilai_kapitalisasi").keydown(function() {
      $('#penambahan_umur_ekonomis').val(0);
    });

    $("#nilai_kapitalisasi").keyup(function() {
      var nilai_perolehan = $('#nilai_perolehan').val().split('.').join('');
      var nilai_kapitalisasi = $(this).val().split('.').join('');
      var nilai_perolehan = nilai_perolehan.split(',').join('.');
      var nilai_kapitalisasi = nilai_kapitalisasi.split(',').join('.');
      if (nilai_kapitalisasi == 0 || nilai_kapitalisasi == '') {
        $('#penambahan_umur_ekonomis').val(0);
        return false;
      }
      var hasil = (Number(nilai_kapitalisasi) / Number(nilai_perolehan)) * 100;
      console.log(hasil)
      console.log(batas_b)
      if ((hasil/100) > batas_a && (hasil/100) < batas_b) {
        $('#penambahan_umur_ekonomis').val(role_kapitalisasi.Tambah_A)
      } else if ((hasil/100) >= batas_b && (hasil/100) < batas_c) {
        $('#penambahan_umur_ekonomis').val(role_kapitalisasi.Tambah_B)
      } else if ((hasil/100) >= batas_c && (hasil/100) < batas_d) {
        $('#penambahan_umur_ekonomis').val(role_kapitalisasi.Tambah_C)
      } else if ((hasil/100) >= batas_d && (hasil/100) < batas_e) {
        $('#penambahan_umur_ekonomis').val(role_kapitalisasi.Tambah_D)
      } 
      // else if ((hasil/100) >= batas_e && (hasil/100) < batas_f) {
      //   $('#penambahan_umur_ekonomis').val(role_kapitalisasi.Tambah_E)
      // } 
      else if ((hasil/100) > batas_e) {
        $('#penambahan_umur_ekonomis').val(Math.max(role_kapitalisasi.Tambah_A, role_kapitalisasi.Tambah_B, role_kapitalisasi.Tambah_C, role_kapitalisasi.Tambah_D, role_kapitalisasi.Tambah_E))
      }

    });

    $("#koreksi_kurang").keydown(function() {
      if ($('#koreksi_tambah').val()) {
        $('#koreksi_tambah').val('');
      }
    });
    $("#koreksi_tambah").keydown(function() {
      if ($('#koreksi_kurang').val()) {
        $('#koreksi_kurang').val('');
      }
    });

    
  $('select[data-role=select2]').select2({
    theme: 'bootstrap',
    width: '100%',
  });
    
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
});

  });
</script>