<?php 
    $session = $this->session->userdata('session'); ?>
<section class="content">
  <h2 class="text-success" style="margin-top:0px"><?php echo $button . ' ' . $menu; ?></h2>


  <form action="<?php echo $action; ?>" method="post" autocomplete="off">


    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="varchar">Tanggal Transaksi <?php echo form_error('tanggal_transaksi') ?></label>
          <input type="text" class="form-control date_input" name="tanggal_transaksi" id="tanggal_transaksi" placeholder="Tanggal Transaksi" value="<?php echo $tanggal_transaksi; ?>" onchange="show_input();" disabled />
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="varchar">Nomor Transaksi (SP2D, BAST, kwitansi, dll.) <?php echo form_error('nomor_transaksi') ?></label>
          <input type="text" class="form-control" name="nomor_transaksi" id="nomor_transaksi" placeholder="Nomor Transaksi" value="<?php echo $nomor_transaksi; ?>" onkeyup="show_input();" disabled />
        </div>
      </div>
    </div>




    <div id='input_disable' class="input_disable">


      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Sumber Dana / Anggaran <?php echo form_error('sumber_dana') ?></label>
            <select class="form-control" name="sumber_dana" id="sumber_dana" disabled>
              <option value="">Silahkan Pilih</option>
              <?php foreach ($lis_sumber_dana as $key => $value) : ?>
                <option value="<?= $value['id_sumber_dana']; ?>" <?= ($value['id_sumber_dana'] == $sumber_dana) ? 'selected' : ''; ?>><?= $value['nama_sumber_dana']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Kode Anggaran <?php echo form_error('rekening') ?></label>
            <?php /*<input type="text" class="form-control" name="rekening" id="rekening" placeholder="" value="<?php echo $rekening; ?>" /> */ ?>

            <select class="form-control" name="rekening" id="rekening" data-role="select2" disabled>
              <option value="">Silahkan Pilih</option>
              <?php foreach ($lis_rekening as $key => $value) : ?>
                <option value="<?= $value['id_rekening']; ?>" <?= $value['id_rekening'] == $rekening ? 'selected' : ''; ?>> <?= $value['kode_rekening'] . ' - ' . $value['nama_rekening']; ?></option>;
              <?php endforeach; ?>
            </select>

          </div>
        </div>
      </div>

      <div class="box" style="padding: 10px; border: 3px solid #d2d6de; background-color:transparent;">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="enum">Status Pemilik</label>
              <select class="form-control" name="status_pemilik" id="status_pemilik" disabled>
                <?php foreach ($pemilik as $key => $value) : ?>
                  <option value="<?= $value->id_pemilik; ?>" <?= ($value->id_pemilik == $status_pemilik) ? 'selected' : ''; ?>><?= $value->kode . ' - ' . $value->nama; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="varchar">Kode Lokasi <?php echo form_error('kode_lokasi') ?></label>
              <input type="text" class="form-control" name="kode_lokasi" id="kode_lokasi" placeholder="Kode Lokasi" value="<?php echo $kode_lokasi; ?>" readonly />
              <input type="hidden" class="form-control" id="kode_lokasi_old" value="<?php echo $kode_lokasi; ?>" readonly />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="varchar">Nama Lokasi </label>
              <input type="text" class="form-control" name="nama_lokasi" id="nama_lokasi" placeholder="Nama Lokasi" value="<?php echo $nama_lokasi; ?>" readonly />
            </div>
          </div>
        </div>

      </div>

      <div class="box" style="padding: 10px; border: 3px solid #d2d6de;">
        <?php /*
        <div class="row">
          <div class="col-md-6">
            <div class="alert alert-warning alert-dismissible">
              <div class="form-group" style="margin-bottom:0px;">
                <input id="checkbox_kib_f" type='checkbox' class='minimal barang_kib_f' attr_kode_jenis='1' name="kib_f" value="2" <?= $kib_f == 2 ? "checked" : ""; ?>>
                <div class="" style="display: inline-block; position: absolute">
                  <label style="font-size:17px; padding-left:5px;">Konstruksi Dalam Pengerjaan</label>
                </div>

              </div>
            </div>
          </div>
        </div>
         */ ?>
        <input type="hidden" name="validasi" value="<?php echo $validasi; ?>">
        
        <div class="row">
          <div class="col-md-6">
              <div class="form-group">
              <label for="varchar">Kode Barang <?php echo form_error('kode_barang') ?></label>
              <input type="hidden" id="kode_barang_old" value="<?php echo $kode_barang; ?>" />
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
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="float">Luas (M2) <?php echo form_error('luas') ?></label>
            <input readonly type="text" class="form-control format_number" name="luas" id="luas" placeholder="Luas" value="<?php echo $luas; ?>" />
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Tahun Pengadaan <?php echo form_error('tahun_pengadaan') ?></label>
            <input type="hidden" id="tahun_pengadaan_old" value="<?php echo $tahun_pengadaan; ?>" />
            <input readonly type="text" class="form-control tahun_input" name="tahun_pengadaan" id="tahun_pengadaan" placeholder="Tahun Pengadaan" value="<?php echo $tahun_pengadaan; ?>" />
          </div>
        </div>
      </div>



      <div class="form-group">
        <label for="varchar">Letak/Alamat <?php echo form_error('letak_alamat') ?></label>
        <input readonly type="text" class="form-control" name="letak_alamat" id="letak_alamat" placeholder="Letak Alamat" value="<?php echo $letak_alamat; ?>" />
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Status Hak <?php echo form_error('status_hak') ?></label>
            <select disabled class="form-control " name="status_hak" id="status_hak" data-role="select2">
              <option value="">Silahkan Pilih</option>
              <?php foreach ($master_hak_tanah as $key => $value) : ?>
                <option value="<?= $value->description; ?>" <?= ($status_hak == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="date">Tanggal Sertifikat <?php echo form_error('sertifikat_tanggal') ?></label>
            <input readonly type="text" class="form-control date_input" name="sertifikat_tanggal" id="sertifikat_tanggal" placeholder="Tanggal Sertifikat"  value="<?php echo $sertifikat_tanggal; ?>" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Nomor Sertifikat <?php echo form_error('sertifikat_nomor') ?></label>
            <input readonly type="text" class="form-control" name="sertifikat_nomor" id="sertifikat_nomor" placeholder="Nomor Sertifikat" value="<?php echo $sertifikat_nomor; ?>" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="varchar">Penggunaan / Fungsi <?php echo form_error('penggunaan') ?></label>
            <input readonly type="text" class="form-control" name="penggunaan" id="penggunaan" placeholder="Penggunaan" value="<?php echo $penggunaan; ?>" />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Kondisi <?php echo form_error('kondisi') ?></label>

            <select disabled class="form-control " name="kondisi" id="kondisi" data-role="select2">
              <option value="">Silahkan Pilih</option>
              <?php foreach ($master_kondisi_bangunan as $key => $value) : ?>
                <option value="<?= $value->description; ?>" <?= ($kondisi == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Asal Usul <?php echo form_error('asal_usul') ?></label>
            <input readonly type="text" id="asal_usul_text" readonly class="form-control" value="">
            <input type="hidden" name="asal_usul" id="asal_usul" value="">
            <?php /*<select class="form-control" name="asal_usul" id="asal_usul" data-role="select2">
              <option value="">Silahkan Pilih</option>
              <?php foreach ($master_asal_usul as $key => $value) : ?>
                <option value="<?= $value->description; ?>" <?= ($asal_usul == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
              <?php endforeach; ?>
              
            </select> */ ?>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="int">Harga <?php echo form_error('harga') ?></label>
            <input readonly type="text" class="form-control format_float " name="harga" id="harga" placeholder="Harga" value="<?php echo str_replace(".", ",", $harga); ?>" <?php echo $validasi == 2 ? "readonly" : ""; ?> />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="varchar">Latitude <?php echo form_error('latitute') ?></label>
            <input readonly type="text" class="form-control" name="latitute" id="latitute" placeholder="Latitude" value="<?php echo $latitute; ?>" />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="varchar">Longitute <?php echo form_error('longitute') ?></label>
            <input readonly type="text" class="form-control" name="longitute" id="longitute" placeholder="Longitute" value="<?php echo $longitute; ?>" />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Satuan <?php echo form_error('satuan') ?></label>

            <select disabled class="form-control " name="satuan" id="satuan" data-role="select2">
              <option value="">Silahkan Pilih</option>
              <?php foreach ($master_satuan as $key => $value) : ?>
                <option value="<?= $value->description; ?>" <?= ($satuan == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <?php if($session->id_role == '1') : ?>
        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Umur Ekonomis <?php echo form_error('umur_ekonomis') ?></label>
            <input readonly type="text" class="form-control format_number" value="0" name="umur_ekonomis" id="umur_ekonomis" placeholder="Umur Ekonomis" value="<?php echo $umur_ekonomis; ?>" />
          </div>
        </div>
        <?php endif; ?>
      </div>


      <div class="form-group">
        <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
        <textarea readonly class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
      </div>
      <div class="form-group">
        <label for="deskripsi">Deskripsi <?php echo form_error('deskripsi') ?></label>
        <textarea readonly class="form-control" rows="3" name="deskripsi" id="deskripsi" placeholder="Deskripsi"><?php echo $deskripsi; ?></textarea>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Tanggal Pembelian <?php echo form_error('tanggal_pembelian') ?></label>
            <input readonly type="text" class="form-control date_input" name="tanggal_pembelian" id="tanggal_pembelian" placeholder="Tanggal Pembelian" value="<?php echo $tanggal_pembelian; ?>" />
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Tanggal Perolehan <?php echo form_error('tanggal_perolehan') ?></label>
            <input disabled type="text" class="form-control <?php echo $validasi == 2 ? "" : "date_input"; ?>" name="tanggal_perolehan" id="tanggal_perolehan" placeholder="Tanggal Perolehan" value="<?php echo $tanggal_perolehan; ?>" <?php echo $validasi == 2 ? "readonly" : ""; ?> />
          </div>
        </div>
      </div>

      <input type="hidden" name="id_kib_a" value="<?php echo $id_kib_a; ?>" />
      <button data-id="<?php echo $id_kib_a; ?>" type="button" class="btn btn-success btn-sm validasi" title="Validasi"><span class="fa fa-check"><span> Validasi</button>
      <button data-id="<?php echo $id_kib_a; ?>" type="button" class="btn btn-danger btn-sm reject" title="Tolak"><span class="fa fa-times"><span> Tolak</button>
      <a href="#" onclick="history.go(-1)" class="btn btn-default">Kembali</a>

    </div>
  </form>
</section>

<script>
  
var kode_jenis = '01';
  
$(document).on('click', '.validasi', function () {

var id_kib = $(this).data('id');
var tanggal_validasi = $('#tanggal_validasi').val();

// console.log(id_kib); return false;

swal({
  title: "Apakah kamu yakin?",
  text: "Data yang dipilih akan divalidasi!",
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Validasi',
  cancelButtonText: 'Batalkan'
}).then((result) => {
  if (result.value) {

    url = base_url + 'global_controller/validasi/' + kode_jenis;
    $.post(url, { id_kib: id_kib,tanggal_validasi: tanggal_validasi  }, function (respon) {
      $("#loadMe").modal("hide");
      obj = jQuery.parseJSON(respon);
      // swal(
      //   'Berhasil!',
      //   'Data tervalidasi!',
      //   'success'
      // )
      if (!obj.status) {
        swal({
          type: 'error',
          title: '!!!',
          text: obj.message,
          // footer: '<a href>Why do I have this issue?</a>'
        })

      } else {
        swal({
          title: 'Berhasil',
          text: "Validasi Berhasil",
          type: 'success',
          // showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya'
        }).then((result) => {
          if (result.value) {
            // location.reload();
            location.href = "<?= site_url('kib_a/validasi') ?>";
          }
        })
      }
      $('#mytable_wrapper').find('li.paginate_button.active>a').click();
      // get_request_validasi();
    });

  }
  else{
  
 }
})

});

$(document).on('click', '.reject', async function () {
const { value: text } = await swal({
  input: 'textarea',
  inputPlaceholder: 'Alasan Penolakan!',
  // inputValue: $(this).attr('note'),
  showCancelButton: true,
  confirmButtonText: 'Simpan',
  cancelButtonText: 'Batalkan'
})

if (text) {
  
  $("#loadMe").modal({
    backdrop: "static", //remove ability to close modal with click
    keyboard: false, //remove option to close with keyboard
    show: true //Display loader!
  });

  var id_kib = $(this).data('id');
  url = base_url + 'global_controller/reject_kib/' + kode_jenis;

  $.post(url, { id_kib: id_kib, note: text }, function (respon) {
    $("#loadMe").modal("hide");
    obj_respon = jQuery.parseJSON(respon);
    swal(
      'Berhasil!',
      'Data ditolak!',
      'success'
    )
    $('#mytable_wrapper').find('li.paginate_button.active>a').click();
    location.href = "<?= site_url('kib_a/validasi') ?>";
    // get_request_validasi();
  });
}
});
</script>