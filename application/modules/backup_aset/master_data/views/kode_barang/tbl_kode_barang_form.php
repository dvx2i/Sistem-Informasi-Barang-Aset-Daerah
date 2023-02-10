<section class="content">
  <h2 style="margin-top:0px"> <?php echo $button ?> Kode Barang</h2>
  <form action="<?php echo $action; ?>" method="post">
    <?php //=json_encode($kode)
    ?>
    <div class="form-group">
      <label for="char">Akun <?php echo form_error('kode_akun') ?></label>
      <div class="row">
        <div class="col-md-6">
          <?php /*<input type="text" class="form-control" name="kode_akun" id="kode_akun" placeholder="1" value="<?php echo $kode_akun; ?>" /> */ ?>
          <select class="form-control" name="kode_akun" id="kode_akun" data-role="select2" onchange="setKodeBarang()">
            <!-- <option value="">== Silahkan Pilih ==</option> -->
            <?php foreach ($kode['kode_akun'] as $key => $value) : ?>
              <option value="<?= $value['kode_akun']; ?>" <?= ($value['kode_akun'] == $kode_akun) ? 'selected' : ''; ?>><?= $value['kode_akun'] . " - " . $value['nama_barang']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="char">Kelompok <?php echo form_error('kode_kelompok') ?></label>
      <div class="row">
        <div class="col-md-6">
          <?php /*<input type="text" class="form-control" name="kode_kelompok" id="kode_kelompok" placeholder="3" value="<?php echo $kode_kelompok; ?>" /> */ ?>
          <select class="form-control" name="kode_kelompok" id="kode_kelompok" data-role="select2" onchange="setKodeBarang()">
            <option value="">== Silahkan Pilih ==</option>
            <?php foreach ($kode['kode_kelompok'] as $key => $value) : ?>
              <option value="<?= $value['kode_kelompok']; ?>" <?= ($value['kode_kelompok'] == $kode_kelompok) ? 'selected' : ''; ?>><?= $value['kode_kelompok'] . " - " . $value['nama_barang']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="char">Jenis <?php echo form_error('kode_jenis') ?></label>
      <div class="row">
        <div class="col-md-6">
          <?php /*<input type="text" class="form-control" name="kode_jenis" id="kode_jenis" placeholder="1" value="<?php echo $kode_jenis; ?>" /> */ ?>
          <select class="form-control" name="kode_jenis" id="kode_jenis" data-role="select2" onchange="setKodeBarang()">

            <option value="">== Silahkan Pilih ==</option>
            <?php foreach ($kode['kode_jenis'] as $key => $value) : ?>
              <option value="<?= $value['kode_jenis']; ?>" <?= ($value['kode_jenis'] == $kode_jenis) ? 'selected' : ''; ?>><?= $value['kode_jenis'] . " - " . $value['nama_barang']; ?></option>
            <?php endforeach; ?>

          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <? //=json_encode($kode['kode_objek'])
                            ?>
      <label for="char">Objek <?php echo form_error('kode_objek') ?></label>
      <div class="row">
        <div class="col-md-6">
          <?php /*<input type="text" class="form-control" name="kode_objek" id="kode_objek" placeholder="01" value="<?php echo $kode_objek; ?>" /> */ ?>
          <select class="form-control" name="kode_objek" id="kode_objek" data-role="select2" onchange="setKodeBarang()">

            <option value="">== Silahkan Pilih ==</option>
            <?php foreach ($kode['kode_objek'] as $key => $value) : ?>
              <option value="<?= $value['kode_objek']; ?>" <?= ($value['kode_objek'] == $kode_objek) ? 'selected' : ''; ?>><?= $value['kode_objek'] . " - " . $value['nama_barang']; ?></option>
            <?php endforeach; ?>

          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="char">Rincian Objek <?php echo form_error('kode_rincian_objek') ?></label>
      <div class="row">
        <div class="col-md-6">
          <?php /*<input type="text" class="form-control" name="kode_rincian_objek" id="kode_rincian_objek" placeholder="01" value="<?php echo $kode_rincian_objek; ?>" /> */ ?>
          <select class="form-control" name="kode_rincian_objek" id="kode_rincian_objek" data-role="select2" onchange="setKodeBarang()">

            <option value="">== Silahkan Pilih ==</option>
            <?php foreach ($kode['kode_rincian_objek'] as $key => $value) : ?>
              <option value="<?= $value['kode_rincian_objek']; ?>" <?= ($value['kode_rincian_objek'] == $kode_rincian_objek) ? 'selected' : ''; ?>><?= $value['kode_rincian_objek'] . " - " . $value['nama_barang']; ?></option>
            <?php endforeach; ?>

          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="char">Sub Rincian Objek <?php echo form_error('kode_sub_rincian_objek') ?></label>
      <div class="row">
        <div class="col-md-6">
          <?php /*<input type="text" class="form-control" name="kode_sub_rincian_objek" id="kode_sub_rincian_objek" placeholder="001" value="<?php echo $kode_sub_rincian_objek; ?>" />*/ ?>

          <select class="form-control" name="kode_sub_rincian_objek" id="kode_sub_rincian_objek" data-role="select2" onchange="setKodeBarang()">

            <option value="">== Silahkan Pilih ==</option>
            <?php foreach ($kode['kode_sub_rincian_objek'] as $key => $value) : ?>
              <option value="<?= $value['kode_sub_rincian_objek']; ?>" <?= ($value['kode_sub_rincian_objek'] == $kode_sub_rincian_objek) ? 'selected' : ''; ?>><?= $value['kode_sub_rincian_objek'] . " - " . $value['nama_barang']; ?></option>
            <?php endforeach; ?>

          </select>

        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="char">Sub Sub Rincian Objek <?php echo form_error('kode_sub_sub_rincian_objek') ?></label>
      <div class="row">
        <div class="col-md-6">
          <input type="text" class="form-control" name="kode_sub_sub_rincian_objek" id="kode_sub_sub_rincian_objek" placeholder="000" value="<?php echo $kode_sub_sub_rincian_objek; ?>" onkeyup="setKodeBarang()" readonly />
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="varchar">Kode Barang <?php echo form_error('kode_barang') ?></label>
      <div class="row">
        <div class="col-md-4">
          <input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="0.0.0.00.00.00.000.000" value="<?php echo $kode_barang; ?>" readonly />
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="varchar">Nama Barang / Uraian <?php echo form_error('nama_barang') ?></label>
      <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Uraian" value="<?php echo $nama_barang; ?>" />
    </div>

    <div class="form-group">
      <label for="int">Umur Ekonomis <?php echo form_error('umur_ekonomis') ?></label>
      <div class="row">
        <div class="col-md-4">
          <input type="text" class="form-control" name="umur_ekonomis" id="umur_ekonomis" placeholder="Umur Ekonomis" value="<?php echo $umur_ekonomis; ?>" />
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="int">Nilai Residu <?php echo form_error('nilai_residu') ?></label>
      <div class="row">
        <div class="col-md-4">
          <input type="text" class="form-control" name="nilai_residu" id="nilai_residu" placeholder="Nilai Residu" value="<?php echo $nilai_residu; ?>" />
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="int">Kelompok Manfaat <?php echo form_error('kelompok_manfaat') ?></label>
      <div class="row">
        <div class="col-md-4">
          <?php /*<input type="text" class="form-control" name="kelompok_manfaat" id="kelompok_manfaat" placeholder="Kelompok Manfaat" value="<?php echo $kelompok_manfaat; ?>" />*/ ?>
          <select class="form-control" name="kelompok_manfaat" id="kelompok_manfaat" data-role="select2">
            <option value="0">== Silahkan Pilih ==</option>
            <?php foreach ($list_master_kapitalisasi as $key => $value) : ?>
              <option value="<?= $value->Kelompok_Manfaat; ?>" <?= ($value->Kelompok_Manfaat == $kelompok_manfaat) ? 'selected' : ''; ?>><?= $value->Bidang_Barang." - ".$value->Uraian; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>
    <input type="hidden" name="id_kode_barang" value="<?php echo $id_kode_barang; ?>" />
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?php echo base_url('master_data/kode_barang') ?>" class="btn btn-default">Batal</a>
  </form>
</section>

<script type="text/javascript">
  $(document).ready(function() {
    $('select[data-role=select2]').select2({
      theme: 'bootstrap',
      width: '100%'
    });

    // $('#kode_sub_sub_rincian_objek').keyup(function(){
    //   setKodeBarang();
    // })
  })

  $(document).on('change', '#kode_kelompok', function(e) {
    // $('#kode_jenis').html('<option value="">Silahkan Pilih</option>');
    $('#kode_jenis').html('');
    $('#kode_objek').html('');
    $('#kode_rincian_objek').html('');
    $('#kode_sub_rincian_objek').html('');
    $('#kode_sub_sub_rincian_objek').val('');
    $('#kode_barang').val('');

    var kode_kelompok = $(this).val();
    url = base_url + 'master_data/kode_barang/get_jenis/';
    $.post(url, {
      kode_kelompok: kode_kelompok,
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_jenis').html(obj_respon.option);
    });
  });

  // GET OBJEK
  $(document).on('change', '#kode_jenis', function(e) {
    // $('#rekening').html('<option value="">Silahkan Pilih</option>');

    var kode_kelompok = $("#kode_kelompok").val();
    var kode_jenis = $(this).val();
    url = base_url + 'master_data/kode_barang/get_objek/';
    $.post(url, {
      kode_kelompok: kode_kelompok,
      kode_jenis: kode_jenis
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_objek').html(obj_respon.option);
    });
  });

  // GET RINCIAN OBJEK
  $(document).on('change', '#kode_objek', function(e) {
    // $('#rekening').html('<option value="">Silahkan Pilih</option>');

    var kode_kelompok = $("#kode_kelompok").val();
    var kode_jenis = $("#kode_jenis").val();
    var kode_objek = $(this).val();
    // console.log(kode_objek);

    url = base_url + 'master_data/kode_barang/get_rincian_objek/';
    $.post(url, {
      kode_kelompok: kode_kelompok,
      kode_jenis: kode_jenis,
      kode_objek: kode_objek
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_rincian_objek').html(obj_respon.option);
    });
  });

  // GET SUB RINCIAN OBJEK
  $(document).on('change', '#kode_rincian_objek', function(e) {
    // $('#rekening').html('<option value="">Silahkan Pilih</option>');

    var kode_kelompok = $("#kode_kelompok").val();
    var kode_jenis = $("#kode_jenis").val();
    var kode_objek = $("#kode_objek").val();
    var kode_rincian_objek = $(this).val();
    url = base_url + 'master_data/kode_barang/get_sub_rincian_objek/';
    $.post(url, {
      kode_kelompok: kode_kelompok,
      kode_jenis: kode_jenis,
      kode_objek: kode_objek,
      kode_rincian_objek: kode_rincian_objek
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_sub_rincian_objek').html(obj_respon.option);
    });
  });


  // GET SUB SUB RINCIAN OBJEK
  $(document).on('change', '#kode_sub_rincian_objek', function(e) {
    // $('#rekening').html('<option value="">Silahkan Pilih</option>');

    var kode_kelompok = $("#kode_kelompok").val();
    var kode_jenis = $("#kode_jenis").val();
    var kode_objek = $("#kode_objek").val();
    var kode_rincian_objek = $("#kode_rincian_objek").val();
    var kode_sub_rincian_objek = $(this).val();
    url = base_url + 'master_data/kode_barang/get_sub_sub_rincian_objek/';
    $.post(url, {
      kode_kelompok: kode_kelompok,
      kode_jenis: kode_jenis,
      kode_objek: kode_objek,
      kode_rincian_objek: kode_rincian_objek,
      kode_sub_rincian_objek: kode_sub_rincian_objek
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_sub_sub_rincian_objek').val(obj_respon.next_kode_sub_sub_rincian_objek);
      setKodeBarang();
    });
  });


  function setKodeBarang() {
    var kode_akun = $('#kode_akun').val();
    var kode_kelompok = $('#kode_kelompok').val();
    var kode_jenis = $('#kode_jenis').val();
    var kode_objek = $('#kode_objek').val();
    var kode_rincian_objek = $('#kode_rincian_objek').val();
    var kode_sub_rincian_objek = $('#kode_sub_rincian_objek').val();
    var kode_sub_sub_rincian_objek = $('#kode_sub_sub_rincian_objek').val();
    if ((kode_akun) && (kode_kelompok) && (kode_jenis) && (kode_objek) && (kode_rincian_objek) && (kode_sub_rincian_objek) && (kode_sub_sub_rincian_objek)) {
      $('#kode_barang').val(kode_akun + '.' + kode_kelompok + '.' + kode_jenis + '.' + kode_objek + '.' + kode_rincian_objek + '.' + kode_sub_rincian_objek + '.' + kode_sub_sub_rincian_objek);
    } else {
      $('#kode_barang').val('');
    }

  }
</script>