<?php 
    $session = $this->session->userdata('session'); ?>
<style>
  .modal-body {
    overflow-x: auto;
  }

  .modal-lg {
    width: 80%;
  }

  .no_wrap {
    white-space: nowrap;
  }
</style>

<section class="content">
  <h2 class="text-success" style="margin-top:0px"><?php echo $button . ' ' . $menu; ?></h2>
  <form action="<?php echo $action; ?>" method="post">

    <?php if ($data_reklas) : ?>
      <div class="box" style="padding: 10px; border: 3px solid #d2d6de; background-color:transparent;">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="varchar">KIB Asal : <?php echo $kode_jenis_asal['nama']; ?></label><br>
              <label for="varchar">No Register : <?php echo $kib_asal['nomor_register']; ?></label>
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="varchar">Kode Barang <?php /*echo form_error('kode_barang')*/ ?></label>
              <input type="text" class="form-control" name="" id="" placeholder="" value="<?php echo $kode_barang_asal['kode_barang']; ?>" readonly />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="varchar">Nama Barang <?php /*echo form_error('nama_barang')*/ ?></label>
              <input type="text" class="form-control" name="" id="" placeholder="Nama Barang" value="<?php echo $kode_barang_asal['nama_barang_simbada'] ? $kode_barang_asal['nama_barang_simbada'] : $kode_barang_asal['nama_barang']; ?>" readonly />
            </div>
          </div>
        </div>

      </div>
    <?php endif; ?>

    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="varchar">Tanggal Transaksi <?php echo form_error('tanggal_transaksi') ?></label>
          <input disabled type="text" class="form-control date_input" name="tanggal_transaksi" id="tanggal_transaksi" placeholder="<?= tgl_indo(date('Y-m-d')) ?>" value="<?php echo $tanggal_transaksi; ?>" onchange="show_input();" />
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="varchar">Nomor Transaksi (SP2D, BAST, kwitansi, dll.) <?php echo form_error('nomor_transaksi') ?></label>
          <input readonly type="text" class="form-control" name="nomor_transaksi" id="nomor_transaksi" placeholder="Nomor Transaksi" value="<?php echo $nomor_transaksi; ?>" onkeyup="show_input();" />
        </div>
      </div>
    </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Sumber Dana / Anggaran <?php echo form_error('sumber_dana') ?></label>
            <select disabled class="form-control" name="sumber_dana" id="sumber_dana">
              <option value="">Silahkan Pilih</option>
              <?php foreach ($lis_sumber_dana as $key => $value) : ?>
                <option value="<?= $value['id_sumber_dana']; ?>" <?= ($value['id_sumber_dana'] == $sumber_dana) ? 'selected' : ''; ?>><?= $value['nama_sumber_dana']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Kode Anggaran <?php echo form_error('kode_rekening') ?></label>
            <?php /*<input type="text" class="form-control" name="rekening" id="rekening" placeholder="" value="<?php echo $rekening; ?>" /> */ ?>

            <select disabled class="form-control" name="rekening" id="rekening" data-role="select2">
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
          <div class="col-md-4">
            <div class="form-group">
              <label for="enum">Referensi</label>
              <select disabled class="form-control" name="referensi_id" id="referensi_id" data-role="select2">
                <option value="">Silahkan Pilih</option>
                <?php foreach ($referensi as $key => $value) : ?>
                  <option value="<?= $value->id_kib_f; ?>" <?= ($value->id_kib_f == $referensi_id) ? 'selected' : ''; ?>><?= 'Kode Lokasi :' . $value->kode_lokasi . ' || Kode Barang :' . $value->kode_barang . '  ||  Tgl Mulai : ' . tgl_indo($value->tanggal_mulai); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="box" style="padding: 10px; border: 3px solid #d2d6de; background-color:transparent;">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="enum">Status Pemilik</label>
              <select disabled class="form-control" name="status_pemilik" id="status_pemilik">
                <?php foreach ($pemilik as $key => $value) : ?>
                  <option value="<?= $value->id_pemilik; ?>" <?= ($value->id_pemilik == $status_pemilik) ? 'selected' : ''; ?>><?= $value->kode . ' - ' . $value->nama; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="varchar">Kode Lokasi <?php echo form_error('kode_lokasi') ?></label>
              <input type="text" class="form-control" name="kode_lokasi" id="kode_lokasi" placeholder="Kode Lokasi" value="<?php echo $kode_lokasi; ?>" readonly />
              <input type="hidden" class="form-control" id="kode_lokasi_old" value="<?php echo $kode_lokasi; ?>" readonly />
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-group">
              <label for="varchar">Nama Lokasi </label>
              <input type="text" class="form-control" name="nama_lokasi" id="nama_lokasi" placeholder="Nama Lokasi" value="<?php echo $nama_lokasi; ?>" readonly />
            </div>
          </div>
        </div>
      </div>

      <div class="box" style="padding: 10px; border: 3px solid #d2d6de;">
        <input type="hidden" name="validasi" value="<?php echo $validasi; ?>">
        <div id='input_disable_barang' class="<?php echo (($validasi == 2) or (!empty($data_reklas))) ? "input_disable" : ""; ?>">


          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="varchar">Kode Barang <?php echo form_error('kode_barang') ?></label>
                <input type="hidden" id="kode_barang_old" value="<?php echo $kode_barang; ?>" />
                <input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="Kode Barang" value="<?php echo $kode_barang; ?>" readonly />
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label for="varchar">Nama Barang <?php echo form_error('nama_barang') ?></label>
                <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Nama Barang" value="<?php echo $nama_barang; ?>" readonly />
              </div>
            </div>
          </div>

        </div>

      </div>


      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Bangunan <?php echo form_error('bangunan') ?></label>
            <?php /*<input type="text" class="form-control" name="bangunan" id="bangunan" placeholder="Bangunan" value="<?php echo $bangunan; ?>" />*/ ?>
            <select class="form-control" name="bangunan" id="bangunan" data-role="select2">
              <option disabled value="">Silahkan Pilih</option>
              <?php foreach ($master_bangunan as $key => $value) : ?>
                <option value="<?= $value->description; ?>" <?= ($bangunan == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="enum">Kontruksi (Bertingkat) <?php echo form_error('kontruksi_bertingkat') ?></label>
            <select disabled class="form-control" name="kontruksi_bertingkat" id="kontruksi_bertingkat">
              <option value="">Silahkan Pilih</option>
              <option value="bertingkat" <?= $kontruksi_bertingkat == 'bertingkat' ? 'selected' : ''; ?>>Bertingkat</option>
              <option value="tidak" <?= $kontruksi_bertingkat == 'tidak' ? 'selected' : ''; ?>>Tidak</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="enum">Kontruksi (Beton) <?php echo form_error('kontruksi_beton') ?></label>
            <select disabled class="form-control" name="kontruksi_beton" id="kontruksi_beton">
              <option value="">Silahkan Pilih</option>
              <option value="beton" <?= $kontruksi_beton == 'beton' ? 'selected' : ''; ?>>Beton</option>
              <option value="tidak" <?= $kontruksi_beton == 'tidak' ? 'selected' : ''; ?>>Tidak</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-2">
          <div class="form-group">
            <label for="float">Luas(M2) <?php echo form_error('luas_m2') ?></label>
            <input readonly type="text" class="form-control format_number" name="luas_m2" id="luas_m2" placeholder="Luas M2" value="<?php echo $luas_m2; ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="varchar">Lokasi/Alamat <?php echo form_error('lokasi_alamat') ?></label>
        <input readonly type="text" class="form-control" name="lokasi_alamat" id="lokasi_alamat" placeholder="Lokasi Alamat" value="<?php echo $lokasi_alamat; ?>" />
      </div>
      <div class="row">
        <div class="col-md-2">
          <div class="form-group">
            <label for="date">Tanggal Dokumen  (SPK/IMB/Kontrak) <?php echo form_error('dokumen_tanggal') ?></label>
            <input disabled type="text" class="form-control date_input" name="dokumen_tanggal" id="dokumen_tanggal" placeholder="Dokumen Tanggal" value="<?php echo $dokumen_tanggal; ?>" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Nomor Dokumen (SPK/IMB/Kontrak) <?php echo form_error('dokumen_nomor') ?></label>
            <input readonly type="text" class="form-control" name="dokumen_nomor" id="dokumen_nomor" placeholder="Dokumen Nomor" value="<?php echo $dokumen_nomor; ?>" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-2">
          <div class="form-group">
            <label for="date">Tanggal Mulai Pekerjaan <?php echo form_error('tanggal_mulai') ?></label>
            <input disabled type="text" class="form-control date_input" name="tanggal_mulai" id="tanggal_mulai" placeholder="Tanggal Mulai" value="<?php echo $tanggal_mulai; ?>" />
          </div>
        </div>
      </div>

      <div class="box" style="padding: 10px; border: 3px solid #d2d6de; background-color:transparent;">
        

        <div class="form-group">
          <label for="varchar">Nomor Kode Tanah <?php echo form_error('nomor_kode_tanah') ?></label>
          <input readonly type="text" class="form-control" name="nomor_kode_tanah" id="nomor_kode_tanah" placeholder="Nomor Kode Tanah" value="<?php echo $nomor_kode_tanah; ?>" />
        </div>

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="varchar">Status Tanah <?php echo form_error('status_tanah') ?></label>
              <select disabled class="form-control " name="status_tanah" id="status_tanah" data-role="select2">
                <option value="">Silahkan Pilih</option>
                <?php /*foreach ($master_status_tanah as $key => $value): ?>
                  <option  value="<?=$value->description; ?>" <?= ($status_tanah==$value->description)?'selected':''; ?>><?=$value->description; ?></option>
                  <?php endforeach;*/ ?>
                <?php foreach ($master_hak_tanah as $key => $value) : ?>
                  <option value="<?= $value->description; ?>" <?= ($status_tanah == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          
          <div class="col-md-3">
            <div class="form-group">
              <label for="varchar">Asal Usul <?php echo form_error('asal_usul') ?></label>
              <input type="text" id="asal_usul_text" readonly class="form-control" value="">
              <input type="hidden" name="asal_usul" id="asal_usul" value="">
              <?php /*<select class="form-control" name="asal_usul" id="asal_usul" data-role="select2">
                <option value="">Silahkan Pilih</option>
                <?php foreach ($master_asal_usul as $key => $value) : ?>
                  <option value="<?= $value->description; ?>" <?= ($asal_usul == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
                <?php endforeach; ?>
                
              </select> */ ?>
            </div>
          </div>
        </div>

      </div>



      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="int">Nilai Kontrak / Harga <?php echo form_error('nilai_kontrak') ?></label>
            <input readonly type="text" class="form-control format_float" name="nilai_kontrak" id="harga" placeholder="Nilai Kontrak" value="<?php echo str_replace(".", ",", $nilai_kontrak); ?>" <?php echo $validasi == 2 ? "readonly" : ""; ?> />
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

      <div class="row">
        <div class="col-md-2">
          <div class="form-group">
            <label for="varchar">Tanggal Perolehan <?php echo form_error('tanggal_perolehan') ?></label>
            <input disabled type="text" class="form-control <?php echo $validasi == 2 ? "" : "date_input"; ?>" name="tanggal_perolehan" id="tanggal_perolehan" placeholder="<?= tgl_indo(date('Y-m-d')) ?>" value="<?php echo $tanggal_perolehan; ?>" <?php echo $validasi == 2 ? "readonly" : ""; ?> />
          </div>
        </div>
      </div>
      <?php /*
        <div class="row">
          <div class="col-md-1">
            <div class="form-group"  <?= $jumlah_barang==0?'hidden':''; ?>>
              <label for="varchar">Jumlah Barang</label>
              <input type="text" class="form-control format_number" name="jumlah_barang" id="jumlah_barang" value=" <?= $jumlah_barang; ?>" />
            </div>
          </div>
        </div>
        */ ?>
      <input type="hidden" name="id_kib_f" value="<?php echo $id_kib_f; ?>" />
      <button data-id="<?php echo $id_kib_f; ?>" type="button" class="btn btn-success btn-sm validasi" title="Validasi"><span class="fa fa-check"><span> Validasi</button>
      <button data-id="<?php echo $id_kib_f; ?>" type="button" class="btn btn-danger btn-sm reject" title="Tolak"><span class="fa fa-times"><span> Tolak</button>
      <a href="#" onclick="history.go(-1)" class="btn btn-default">Kembali</a>

  </form>
</section>

<?php //echo json_encode($kib_a); 
?>
<div id="classModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
          ×
        </button>
        <h4 class="modal-title" id="classModalLabel">
          Data KIB A (Tanah)
        </h4>
      </div>
      <div class="modal-body">

        <table id="classTable" class="table table-bordered">
          <thead>
            <tr class="no_wrap">
              <th>Pilih</th>
              <th>Kode Barang</th>
              <th>Nama Barang</th>
              <th>Nomor Register</th>
              <th>LuasTanah(M2)</th>
              <th>Tahun Pengadaan</th>
              <th>Letak/Alamat</th>
              <th>Status Hak</th>
              <th>Sertifikat Tanggal</th>
              <th>Sertifikat Nomor</th>
              <th>Penggunaan</th>
              <th>Asal Usul</th>
              <th>Harga(Rp.)</th>
              <th>Keterangan</th>
              <th>Kode Lokasi</th>

            </tr>
          </thead>
          <tbody>
            <?php foreach ($kib_a as $key => $value) : ?>
              <tr class="no_wrap">
                <td>
                  <input type="radio" name="checkbox_kib_a" value="<?= $value->kode_barang; ?>" class="checkbox_kib_a flat-red" <?= $key == 0 ? 'checked' : ''; ?> attr_kode_barang='<?= $value->kode_barang; ?>' attr_status_hak='<?= $value->status_hak; ?>' attr_asal_usul='<?= $value->asal_usul; ?>' attr_luas='<?= my_format_number($value->luas); ?>'>
                </td>
                <td><?= $value->kode_barang; ?></td>
                <td><?= $value->nama_barang; ?></td>
                <td><?= $value->nomor_register; ?></td>
                <td><?= my_format_number($value->luas); ?></td>
                <td><?= $value->tahun_pengadaan; ?></td>
                <td><?= $value->letak_alamat; ?></td>
                <td><?= $value->status_hak; ?></td>
                <td><?= tgl_indo($value->sertifikat_tanggal); ?></td>
                <td><?= $value->sertifikat_nomor; ?></td>
                <td><?= $value->penggunaan; ?></td>
                <td><?= $value->asal_usul; ?></td>
                <td><?= my_format_number($value->harga); ?></td>
                <td><?= $value->keterangan; ?></td>
                <td><?= $value->kode_lokasi; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn_pilih btn btn-primary" data-dismiss="modal">
          Pilih
        </button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    // $('#classModal').modal('show');
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    })

  });

  $(document).on('click', '#referensi_kib_a', function() {
    $('#classModal').modal('show');
  })

  $(document).on('click', '.btn_pilih', function() {
    var kode_barang = $('.checkbox_kib_a').iCheck().attr('attr_kode_barang');
    var status_hak = $('.checkbox_kib_a').iCheck().attr('attr_status_hak');
    var asal_usul = $('.checkbox_kib_a').iCheck().attr('attr_asal_usul');
    var luas = $('.checkbox_kib_a').iCheck().attr('attr_luas');
    $('#nomor_kode_tanah').val(kode_barang);
    $('#status_tanah').val(status_hak).trigger('change');
    $('#asal_usul').val(asal_usul).trigger('change');
    // $('#luas_m2').val(luas);

  });
  
var kode_jenis = '06';
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
            location.href = "<?= site_url('kib_f/validasi') ?>";
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
    location.href = "<?= site_url('kib_f/validasi') ?>";
    // get_request_validasi();
  });
}
});
</script>