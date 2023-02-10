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
  <h2 style="margin-top:0px"><?php echo $button ?> pengajuan</h2>
  <form action="<?php echo $action; ?>" method="post" id="form_ususlan" autocomplete="off">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="date">Tanggal Pengajuan<?php echo form_error('tanggal') ?></label>
          <input type="text" class="form-control date_input" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" required />
        </div>
      </div>
    </div>
    <?php /*
    <div class="form-group">
      <label for="varchar">SKPD Tujuan<?php echo form_error('id_pengguna') ?></label>

      <select class="form-control" name="id_pengguna" id="id_pengguna" placeholder="SKPD" data-role="select2">
        <option value="">Pilih SKPD</option>
        <?php foreach ($pengguna as $key) : ?>
          <option value="<?php echo $key->id_pengguna ?>" <?= ($key->id_pengguna == $id_pengguna) ? 'selected' : '' ?>><?php echo remove_star($key->pengguna) . ' - ' . $key->instansi ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="varchar">Lokasi Tujuan <?php echo form_error('kode_lokasi_baru') ?></label>

      <select class="form-control" name="kode_lokasi_baru" id="kode_lokasi_baru" placeholder="Kode Lokasi Pengguna Baru" data-role="select2">
        <option value="">Pilih Lokasi</option>
        <?php if (!empty($lokasi)) : ?>
          <?php foreach ($lokasi as $key) : ?>
            <option value="<?php echo $key['id_kode_lokasi'] ?>" <?= ($key['id_kode_lokasi'] == $kode_lokasi_baru) ? 'selected' : '' ?>><?php echo remove_star($key['kode_lokasi']) . ' - ' . $key['instansi'] ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>
 */ ?>

    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="date">Jenis KIB <?php echo form_error('kode_jenis') ?></label>
          <select class="form-control" name="kode_jenis" id="kode_jenis" data-role="select2" required>
            <option value="">Silahkan Pilih </option>
            <?php foreach ($list_kib as $key => $value) : ?>
              <?php /*echo $kode_jenis . "==" . $key*/ ?>
              <option value="<?= $key; ?>" <?= $kode_jenis == $key ? 'selected' : ''; ?>><?= $value['nama']; ?></option>
            <?php endforeach;  ?>
          </select>
        </div>
      </div>
    </div>



    <label for="varchar">Barang <?php echo form_error('id_kib') ?></label>

    <div class="row">
      <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
          <?php /*
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_kib_a" data-toggle="tab">KIB A</a></li>
            <li><a href="#tab_kib_b" data-toggle="tab">KIB B</a></li>
            <li><a href="#tab_kib_c" data-toggle="tab">KIB C</a></li>
            <li><a href="#tab_kib_d" data-toggle="tab">KIB D</a></li>
            <li><a href="#tab_kib_e" data-toggle="tab">KIB E</a></li>
            <li><a href="#tab_kib_atb" data-toggle="tab">KIB ATB</a></li>
            <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
          </ul>
           */ ?>
          <div class="tab-content">
            <div class="tab-pane" id="tab_kib_a">
              <b>KIB A</b>
              <?php $this->load->view('reklas_kode/list_kib_a'); ?>
            </div>

            <div class="tab-pane " id="tab_kib_b">
              <b>KIB B</b>
              <?php $this->load->view('reklas_kode/list_kib_b'); ?>
            </div>
            <div class="tab-pane" id="tab_kib_c">
              <b>KIB C</b>
              <?php $this->load->view('reklas_kode/list_kib_c'); ?>
            </div>
            <div class="tab-pane" id="tab_kib_d">
              <b>KIB D</b>
              <?php $this->load->view('reklas_kode/list_kib_d'); ?>
            </div>
            <div class="tab-pane" id="tab_kib_e">
              <b>KIB E</b>
              <?php $this->load->view('reklas_kode/list_kib_e'); ?>
            </div>
            <div class="tab-pane" id="tab_kib_f">
              <b>KIB F</b>
              <?php $this->load->view('reklas_kode/list_kib_f'); ?>
            </div>
            <div class="tab-pane" id="tab_kib_atb">
              <b>KIB ATB</b>
              <?php $this->load->view('reklas_kode/list_kib_atb'); ?>
            </div>

            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
      </div>
      <!-- /.col -->

    </div>

    <div class="row" id="jenis_reklas">
      <div class="col-md-6">
        <div class="form-group">
          <label for="varchar">Jenis Reklas </label><br>
          <input type="checkbox" name="jenis_reklas" id="kapitalisasi" value="kapitalisasi" class=" jenis_reklas" style="width: 15px;height: 15px;"> <label for="kapitalisasi">Kapitalisasi</label>&nbsp;&nbsp;&nbsp;
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="date">Kelompok KIB Tujuan<?php echo form_error('') ?></label>
          <select class="form-control" name="kode_kelompok_tujuan" id="kode_kelompok_tujuan" data-role="select2" required>
            <option value="">Silahkan Pilih</option>
            <?php foreach ($list_kelompok as $key => $value) : ?>
              <option value="<?= $value['kode_barang']; ?>" <?= $kode_kelompok_tujuan == $value['kode_barang'] ? 'selected' : ''; ?>><?= $value['kode_barang'] . '-' . $value['nama_barang']; ?></option>
            <?php endforeach;  ?>
          </select>
        </div>
      </div>
    </div>

    <?php /*
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="date">Jenis Aset Lainya<?php echo form_error('kode_jenis_aset_lainya') ?></label>
          <select class="form-control" name="kode_jenis_aset_lainya" id="kode_jenis_aset_lainya" data-role="select2" required>
            <option value="">Silahkan Pilih</option>
            <?php foreach ($jenis_aset_lainya as $key => $value) : ?>
              <option value="<?= $value['id_kode_barang']; ?>"><?= $value['kode_barang'] . '-' . $value['nama_barang']; ?></option>
            <?php endforeach;  ?>
          </select>
        </div>
      </div>
    </div>
 */ ?>
    <?php /*
    <div class="row">
      <div class="col-md-8">
        <div class="form-group">

          <table width="100%" border="0">
            <tr>
              <td style="width: 30%;">
                <label for="date">Kode Aset Lainya</label>
                <input class="form-control" type="text" name="kode_barang_aset_lainya" id="kode_barang_aset_lainya" readonly></td>
              <input type="hidden" name="id_kode_barang_aset_lainya" id="id_kode_barang_aset_lainya">
              <td style="width: 70%; padding-left: 3%;">
                <label for="date">Nama Aset Lainya</label>
                <input class="form-control" type="text" name="nama_barang_aset_lainya" id="nama_barang_aset_lainya" readonly></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
     */ ?>

    <div class="box" style="padding: 10px; border: 3px solid #d2d6de;">
      <div id='input_disable_barang' class="<?php /*echo $validasi == 2 ? "input_disable" : "";*/ ?>">

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="enum">Kode Jenis</label>
              <select class="form-control select_readonly" name="kode_jenis_tujuan" id="kode_jenis_tujuan" data-role="select2">
                <!-- <option value="">Silahkan Pilih</option> -->
                <?php foreach ($jenis_tujuan as $key => $value) : ?>
                  <?php /*echo $kode_objek . "==" . $value->kode_objek*/ ?>
                  <option value=<?= $value->kode_barang; ?> <?= $kode_objek == $value->kode_barang ? 'selected' : ''; ?>><?= $value->kode_objek . ' - ' . $value->nama_barang; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="enum">Kode Objek</label>
              <select class="form-control select_readonly" name="kode_objek" id="kode_objek" data-role="select2">
                <!-- <option value="">Silahkan Pilih</option> -->
                <?php foreach ($objek as $key => $value) : ?>
                  <?php /*echo $kode_objek . "==" . $value->kode_objek*/ ?>
                  <option value=<?= $value->kode_barang; ?> <?= $kode_objek == $value->kode_barang ? 'selected' : ''; ?>><?= $value->kode_objek . ' - ' . $value->nama_barang; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="enum">Kode Rincian Objek</label>
              <select class="form-control" name="kode_rincian_objek" id="kode_rincian_objek" data-role="select2">
                <!-- <option value="">Silahkan Pilih</option> -->
                <?php foreach ($rincian_objek as $key => $value) : ?>
                  <option value=<?= $value->kode_barang; ?> <?= $kode_rincian_objek == $value->kode_rincian_objek ? 'selected' : ''; ?>><?= $value->kode_rincian_objek . ' - ' . $value->nama_barang; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="enum">Kode Sub Rincian Objek</label>
              <select class="form-control" name="kode_sub_rincian_objek" id="kode_sub_rincian_objek" data-role="select2">
                <!-- <option value="">Silahkan Pilih</option> -->
                <?php foreach ($sub_rincian_objek as $key => $value) : ?>
                  <option value="<?= $value->kode_barang; ?>" <?= $kode_sub_rincian_objek == $value->kode_sub_rincian_objek ? 'selected' : ''; ?>> <?= $value->kode_sub_rincian_objek . ' - ' . $value->nama_barang; ?></option>;
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="enum">Kode Sub Sub Rincian Objek</label>
              <select class="form-control" name="kode_sub_sub_rincian_objek" id="kode_sub_sub_rincian_objek" data-role="select2">
                <!-- <option value="">Silahkan Pilih</option> -->
                <?php foreach ($sub_sub_rincian_objek as $key => $value) : ?>
                  <option value="<?= $value->kode_barang; ?>" <?= $kode_sub_sub_rincian_objek == $value->kode_sub_sub_rincian_objek ? 'selected' : ''; ?>> <?= $value->kode_sub_sub_rincian_objek . ' - ' . $value->nama_barang_simbada; ?></option>;
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Kode Barang <?php echo form_error('kode_barang') ?></label>
            <input type="hidden" id="kode_barang_old" value="<?php /*echo $kode_barang;*/ ?>" />
            <input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="Kode Barang" value="<?php echo $kode_barang; ?>" readonly />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Nama Barang <?php echo form_error('nama_barang') ?></label>
            <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Nama Barang" value="<?php echo $nama_barang; ?>" readonly />
          </div>
        </div>
      </div>


      <div class="row hidden" id="tujuan">
        <?php /*
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Tujuan </label><br>
            <input type="radio" name="jenis_reklas" id="entri" value="entri" class="flat-red jenis_reklas"> <label for="entri">Entri</label>&nbsp;&nbsp;&nbsp;
          </div>
        </div>
         */ ?>
        <?php /*
        <div class="form-group">
          
        <label for="" class="col-sm-2 control-label">Tujuan </label>
        <div class="col-sm-10" style="padding-top:7px;">
          <label style="margin-right:10%">
            <input type="radio" name="format" value="entri" class="flat-red" checked> Entri
          </label>
          <label>
            <input type="radio" name="format" value="kapitalisasi" class="flat-red" checked> Kapitalisasi
          </label>
        </div>
    
        </div>*/ ?>
      </div>

    </div>




    <input type="hidden" name="id_reklas_kode" value="<?php echo $id_reklas_kode; ?>" />
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?php echo base_url('reklas/reklas_kode') ?>" class="btn btn-default">Batalkan</a>
  </form>


</section>

<script type="text/javascript">
  $(document).ready(function() {
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    })
  });

  $('#form_ususlan').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });

  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
    $($.fn.dataTable.tables(true)).DataTable()
      .columns.adjust();
  });
  <?php /*
  $(document).ready(function() {
    //  pengguna
    $('#id_pengguna').change(function(e) {
      // $('#id_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
      // $('#id_sub_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
      $('#kode_lokasi_baru').html('<option value="">Silahkan Pilih</option>');
      var id_pengguna = $(this).val();
      url = base_url + 'mutasi/pengajuan/get_lokasi/';
      $.post(url, {
        id_pengguna: id_pengguna,
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        $('#kode_lokasi_baru').html(obj_respon.option);
      });
    });
  });
 */ ?>
  $(document).on('change', '#kode_jenis', function() {
    $(".tab-pane").removeClass('active');
    var kode_jenis = $(this).val();
    if (kode_jenis == "01") {
      $("#tab_kib_a").addClass('active');
      $("#mytable_kib_a").DataTable().ajax.reload(); // reload berfungsi untuk menyamakan tabel header dg body
    } else if (kode_jenis == "02") {
      $("#tab_kib_b").addClass('active');
      $("#mytable_kib_b").DataTable().ajax.reload();
    } else if (kode_jenis == "03") {
      $("#tab_kib_c").addClass('active');
      $("#mytable_kib_c").DataTable().ajax.reload();
    } else if (kode_jenis == "04") {
      $("#tab_kib_d").addClass('active');
      $("#mytable_kib_d").DataTable().ajax.reload();
    } else if (kode_jenis == "05") {
      $("#tab_kib_e").addClass('active');
      $("#mytable_kib_e").DataTable().ajax.reload();
    } else if (kode_jenis == "06") {
      $("#tab_kib_f").addClass('active');
      $("#mytable_kib_f").DataTable().ajax.reload();
    } else if (kode_jenis == "5.03") {
      $("#tab_kib_atb").addClass('active');
      $("#mytable_kib_atb").DataTable().ajax.reload();
    }
    // $('#kode_jenis_aset_lainya').change();
    // get_objek();
    // setKodeBarang(this);
    // set_tujuan();
  });

  $(document).on('change', '#kode_kelompok_tujuan', function() {
    // get_objek();
    // setKodeBarang(this);
    $('#kode_jenis_tujuan').html('<option value=""></option>');
    $('#kode_objek').html('<option value=""></option>');
    $('#kode_rincian_objek').html('<option value=""></option>');
    $('#kode_sub_rincian_objek').html('<option value=""></option>');
    $('#kode_sub_sub_rincian_objek').html('<option value=""></option>');
    var kode_barang = $(this).val();
    url = base_url + 'reklas/global_reklas/get_jenis_tujuan/';
    $.post(url, {
      // kode_kelompok: kode_kelompok,
      kode_barang: kode_barang
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_jenis_tujuan').html(obj_respon.option);
    });
    // set_tujuan();
  });


  $(document).on('change', '#kode_jenis_tujuan', function() {
    $('#kode_objek').html('<option value=""></option>');
    $('#kode_rincian_objek').html('<option value=""></option>');
    $('#kode_sub_rincian_objek').html('<option value=""></option>');
    $('#kode_sub_sub_rincian_objek').html('<option value=""></option>');
    // var kode_jenis_tujuan = $('#kode_jenis_tujuan').val();
    var kode_barang = $(this).val();
    url = base_url + 'reklas/global_reklas/get_objek/';
    $.post(url, {
      kode_barang: kode_barang,
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_objek').html(obj_respon.option);
    });
    // setKodeBarang(this);
    // set_tujuan();
  });


  /*
    function get_jenis_tujuan() {
      var var_kode_kelompok = $('#kode_kelompok_tujuan').val();
      if (var_kode_kelompok) {
        get_kode_jenis_tujuan(var_kode_kelompok);
      }
    }

    function get_objek() {
      var var_kode_jenis = $('#kode_jenis').val();
      var var_kode_kelompok = $('#kode_kelompok_tujuan').val();
      if ((var_kode_jenis) && (var_kode_kelompok)) {
        get_kode_objek(var_kode_kelompok, var_kode_jenis);
      }
    }
  */
  /*
  function get_kode_objek(kode_kelompok, kode_jenis) {
    $('#kode_objek').html('<option value=""></option>');
    $('#kode_rincian_objek').html('<option value=""></option>');
    $('#kode_sub_rincian_objek').html('<option value=""></option>');
    $('#kode_sub_sub_rincian_objek').html('<option value=""></option>');
    // var kode_barang = $(this).val();
    url = base_url + 'reklas/global_reklas/get_objek/';
    $.post(url, {
      kode_kelompok: kode_kelompok,
      kode_jenis: kode_jenis
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_objek').html(obj_respon.option);
    });

  }
*/
  //  BIDANG CLICK
  $(document).on('change', '#kode_objek', function(e) {
    $('#kode_rincian_objek').html('<option value=""></option>');
    $('#kode_sub_rincian_objek').html('<option value=""></option>');
    $('#kode_sub_sub_rincian_objek').html('<option value=""></option>');
    var kode_barang = $(this).val();
    url = base_url + 'reklas/global_reklas/get_rincian_objek/';
    $.post(url, {
      kode_barang: kode_barang,
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_rincian_objek').html(obj_respon.option);
    });
    setKodeBarang(this);
  });

  $('#kode_rincian_objek').change(function(e) {
    $('#kode_sub_rincian_objek').html('<option value=""></option>');
    $('#kode_sub_sub_rincian_objek').html('<option value=""></option>');
    var kode_barang = $(this).val();
    url = base_url + 'reklas/global_reklas/get_sub_rincian_objek/';
    $.post(url, {
      kode_barang: kode_barang,
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_sub_rincian_objek').html(obj_respon.option);
    });
    setKodeBarang(this);
  });


  $('#kode_sub_rincian_objek').change(function(e) {
    $('#kode_sub_sub_rincian_objek').html('<option value=""></option>');
    var kode_barang = $(this).val();
    url = base_url + 'reklas/global_reklas/get_sub_sub_rincian_objek/';
    $.post(url, {
      kode_barang: kode_barang,
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_sub_sub_rincian_objek').html(obj_respon.option);
    });
    setKodeBarang(this);
  });

  $('#kode_sub_sub_rincian_objek').change(function(e) {
    if($('#kapitalisasi').is(':checked')){
      var kode_jenis = $('#kode_jenis_tujuan').val();
      var kode_barang= $(this).val();
      url = base_url + 'reklas/global_reklas/cek_barang_tujuan/';
      $.post(url, {
        kode_barang: kode_barang,
        kode_jenis: kode_jenis
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        if(obj_respon.status != true){
          swal('barang tidak ada di lokasi tujuan')
          return false;
        }
      });
    }
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


  <?php /*
  function set_tujuan() {
    $('.jenis_reklas').iCheck("uncheck");
    var var_kode_jenis = $('#kode_jenis').val();
    var var_kode_kelompok_tujuan = $('#kode_kelompok_tujuan').val();
    var var_kode_jenis_tujuan = $('#kode_jenis_tujuan').val();
    if ((var_kode_jenis) && (var_kode_kelompok_tujuan) && (var_kode_jenis_tujuan)) {
      var obj_jenis_tujuan = var_kode_jenis_tujuan.split('.');
      // console.log(obj_jenis_tujuan[2]);

      if ((var_kode_kelompok_tujuan != '1.5.') && (var_kode_jenis != obj_jenis_tujuan[2])) {
        $('#tujuan').removeClass('hidden');
      } else {
        $('#tujuan').addClass('hidden');
      }
    }
  }
   */ ?>

  $(document).on('click', '#kapitalisasi', function() {

    $('#kode_kelompok_tujuan').html('<option value=""></option>');
    $('#kode_jenis_tujuan').html('<option value=""></option>');
    $('#kode_objek').html('<option value=""></option>');
    $('#kode_rincian_objek').html('<option value=""></option>');
    $('#kode_sub_rincian_objek').html('<option value=""></option>');
    $('#kode_sub_sub_rincian_objek').html('<option value=""></option>');



    url = base_url + 'reklas/global_reklas/get_kode_kelompok_tujuan/';
    if (this.checked) {
      $.post(url, {
        status: 'checked',
      }, function(respon) {
        // console.log(respon);

        obj_respon = jQuery.parseJSON(respon);
        $('#kode_kelompok_tujuan').html(obj_respon.option);
      });
    } else {
      $.post(url, {
        status: 'no_checked',
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        $('#kode_kelompok_tujuan').html(obj_respon.option);
      });
    }
  })
</script>