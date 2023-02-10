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

  <form action="<?php echo $action; ?>" method="post" autocomplete="off">

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
      <?php if($tanggal_transaksi == '-' || ($this->session->userdata('session')->id_role != '1' && $validasi == '2' )) : ?>
      <div class="col-md-6">
        <div class="form-group">
          <label for="varchar">Tanggal Transaksi / Entri<?php echo form_error('tanggal_transaksi') ?></label>
          <input type="text" class="form-control date_input" name="tanggal_transaksi" id="tanggal_transaksi" placeholder="Tanggal Transaksi / Entri" value="<?php echo $tanggal_transaksi; ?>" disabled />
        </div>
      </div>
      <?php else : ?>
        
      <div class="col-md-6">
        <div class="form-group">
          <label for="varchar">Tanggal Transaksi / Entri<?php echo form_error('tanggal_transaksi') ?></label>
          <input type="text" class="form-control date_input" name="tanggal_transaksi" id="tanggal_transaksi" placeholder="Tanggal Transaksi / Entri" value="<?php echo $tanggal_transaksi; ?>" onchange="show_input();" />
        </div>
      </div>
      <?php endif; ?>

      <div class="col-md-6">
        <div class="form-group">
          <label for="varchar">Nomor Transaksi (SP2D, BAST, kwitansi, dll.) <?php echo form_error('nomor_transaksi') ?></label>
          <input type="text" class="form-control" name="nomor_transaksi" id="nomor_transaksi" placeholder="Nomor Transaksi" value="<?php echo $nomor_transaksi; ?>" onkeyup="show_input();" />
        </div>
      </div>
    </div>
    <div <?= $id_kib_d == '' ? ' id="input_disable" class="input_disable" ' : ''; ?>>


      <div class="row">
        <div class="col-md-6">
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
        <div class="col-md-6">
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

      <div class="box" style="padding: 10px; border: 3px solid #d2d6de; background-color:transparent;">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="enum">Status Pemilik</label>
              <select class="form-control" name="status_pemilik" id="status_pemilik">
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
                <input id="checkbox_kib_f" type='checkbox' class='minimal barang_kib_f' attr_kode_jenis='4' name="kib_f" value="2" <?= $kib_f == 2 ? "checked" : ""; ?>>
                <div class="" style="display: inline-block; position: absolute">
                  <label style="font-size:17px; padding-left:5px;">Konstruksi Dalam Pengerjaan</label>
                </div>
              </div>
            </div>
          </div>
        </div>
        */ ?>

        <input type="hidden" name="validasi" value="<?php echo $validasi; ?>">
        <div id='input_disable_barang' class="<?php echo (($validasi == 2) or (!empty($data_reklas))) ? "input_disable" : ""; ?>">

          <div class="row">
            <div class="col-md-6">
              <a style="margin-bottom: 5px;" class="btn btn-success btn-sm" href="https://aset.jogjakota.go.id/assets/files/JALAN_JARINGAN_DAN_IRIGASI.pdf" target="_blank">Referensi Kode Barang</a>
          
              
              <div class="form-group">
                <label for="enum">Kode Objek</label>
                <select class="form-control" name="kode_objek" id="kode_objek" data-role="select2">
                  <option value="">Silahkan Pilih</option>
                  <?php foreach ($objek as $key => $value) : ?>
                    <option value=<?= $value->kode_barang; ?> <?= $kode_objek == $value->kode_objek ? 'selected' : ''; ?>><?= $value->kode_objek . ' - ' . $value->nama_barang; ?></option>
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
                  <option value="">Silahkan Pilih</option>
                  <?php foreach ($rincian_objek as $key => $value) : ?>
                    <option value=<?= $value->kode_barang; ?> <?= $kode_rincian_objek == $value->kode_barang ? 'selected' : ''; ?>><?= $value->kode_rincian_objek . ' - ' . $value->nama_barang; ?></option>
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
                  <option value="">Silahkan Pilih</option>
                  <?php foreach ($sub_rincian_objek as $key => $value) : ?>
                    <option value="<?= $value->kode_barang; ?>" <?= $kode_sub_rincian_objek == $value->kode_barang ? 'selected' : ''; ?>> <?= $value->kode_sub_rincian_objek . ' - ' . $value->nama_barang; ?></option>;
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
                  <option value="">Silahkan Pilih</option>
                  <?php foreach ($sub_sub_rincian_objek as $key => $value) : ?>
                    <option value="<?= $value->kode_barang; ?>" <?= $kode_sub_sub_rincian_objek == $value->kode_barang ? 'selected' : ''; ?>> <?= $value->kode_sub_sub_rincian_objek . ' - ' . $value->nama_barang_simbada; ?></option>;
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

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
            <label for="varchar">Konstruksi <?php echo form_error('konstruksi') ?></label>

            <select class="form-control " name="konstruksi" id="konstruksi" data-role="select2">
              <option value="">Silahkan Pilih</option>
              <?php foreach ($master_konstruksi as $key => $value) : ?>
                <option value="<?= $value->description; ?>" <?= ($konstruksi == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="float">Panjang (M) <?php echo form_error('panjang_km') ?></label>
            <input type="text" class="form-control format_number" name="panjang_km" id="panjang_km" placeholder="Panjang (M)" value="<?php echo $panjang_km; ?>" />
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="float">Lebar (M) <?php echo form_error('lebar_m') ?></label>
            <input type="text" class="form-control format_number" name="lebar_m" id="lebar_m" placeholder="Lebar (M)" value="<?php echo $lebar_m; ?>" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="float">Luas (M2) <?php echo form_error('luas_m2') ?></label>
            <input type="text" class="form-control format_number" name="luas_m2" id="luas_m2" placeholder="Luas (M2)" value="<?php echo $luas_m2; ?>" />
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="varchar">Letak/Lokasi <?php echo form_error('letak_lokasi') ?></label>
        <input type="text" class="form-control" name="letak_lokasi" id="letak_lokasi" placeholder="Letak / Lokasi" value="<?php echo $letak_lokasi; ?>" />
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="date">Tanggal Dokumen (SPK/IMB/Kontrak) <?php echo form_error('dokumen_tanggal') ?></label>
            <input type="text" class="form-control date_input" name="dokumen_tanggal" id="tahun_pengadaan" placeholder="<?= tgl_indo(date('Y-m-d')); ?>" value="<?php echo $dokumen_tanggal; ?>" />
            <input type="hidden" id="tahun_pengadaan_old" value="<?= ((!empty($dokumen_tanggal)) and ($dokumen_tanggal != '-')) ? date('Y', tgl_inter($dokumen_tanggal)) : ''; ?>" />
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Nomor Dokumen  (SPK/IMB/Kontrak) <?php echo form_error('dokumen_nomor') ?></label>
            <input type="text" class="form-control" name="dokumen_nomor" id="dokumen_nomor" placeholder="Dokumen Nomor" value="<?php echo $dokumen_nomor; ?>" />
          </div>
        </div>
      </div>
      <div class="box" style="padding: 10px; border: 3px solid #d2d6de; background-color:transparent;">
        <div class="form-group">
          <button id='referensi_kib_a' type="button" name="button" class="btn btn-success">Referensi KIB A</button>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="varchar">Kode Tanah <?php echo form_error('kode_tanah') ?></label>
              <input type="text" class="form-control" name="kode_tanah" id="kode_tanah" placeholder="Kode Tanah" value="<?php echo $kode_tanah; ?>" />
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label for="varchar">Status Tanah <?php echo form_error('status_tanah') ?></label>
              <select class="form-control " name="status_tanah" id="status_tanah" data-role="select2">
                <option value="">Silahkan Pilih</option>

                <?php foreach ($master_hak_tanah as $key => $value) : ?>
                  <option value="<?= $value->description; ?>" <?= ($status_tanah == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          
          <div class="col-md-4">
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
        <div class="col-md-6">
          <div class="form-group">
            <label for="int">Harga <?php echo form_error('harga') ?></label>
            <input type="text" class="form-control format_float" name="harga" id="harga" placeholder="Harga" value="<?php echo str_replace(".", ",", $harga); ?>" <?php echo $validasi == 2 ? "readonly" : ""; ?> />
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Kondisi <?php echo form_error('kondisi') ?></label>
            <select class="form-control " name="kondisi" id="kondisi" data-role="select2">
              <option value="">Silahkan Pilih</option>
              <?php foreach ($master_kondisi_bangunan as $key => $value) : ?>
                <option value="<?= $value->description; ?>" <?= ($kondisi == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>





      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="varchar">Latitude <?php echo form_error('latitute') ?></label>
            <input type="text" class="form-control" name="latitute" id="latitute" placeholder="-7.78278" value="<?php echo $latitute; ?>" />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="varchar">Longitute <?php echo form_error('longitute') ?></label>
            <input type="text" class="form-control" name="longitute" id="longitute" placeholder="110.36083" value="<?php echo $longitute; ?>" />
          </div>
        </div>
      </div>



      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Satuan <?php echo form_error('satuan') ?></label>

            <select class="form-control " name="satuan" id="satuan" data-role="select2">
              <option value="">Silahkan Pilih</option>
              <?php foreach ($master_satuan as $key => $value) : ?>
                  <?php if($value->description != 'DLL') : ?>
                  <option value="<?= $value->description; ?>" <?= ($satuan == $value->description) ? 'selected' : ''; ?>><?= $value->description; ?></option>
                  <?php endif; ?>
                  <?php endforeach; ?>
              <option value="DLL">DLL</option>
            </select>
          </div>
        </div>
        <?php if($session->id_role == '1') : ?>
        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Umur Ekonomis <?php echo form_error('umur_ekonomis') ?></label>
            <input type="text" class="form-control format_number" value="0" name="umur_ekonomis" id="umur_ekonomis" placeholder="Umur Ekonomis" value="<?php echo $umur_ekonomis; ?>" />
          </div>
        </div>
        <?php endif; ?>
      </div>



      <div class="form-group">
        <label for="keterangan">Keterangan <?php echo form_error('keterangan') ?></label>
        <textarea class="form-control" rows="3" name="keterangan" id="keterangan" placeholder="Keterangan"><?php echo $keterangan; ?></textarea>
      </div>
      <div class="form-group">
        <label for="deskripsi">Deskripsi (Spesifikasi)<?php echo form_error('deskripsi') ?></label>
        <textarea class="form-control" rows="3" name="deskripsi" id="deskripsi" placeholder="Deskripsi"><?php echo $deskripsi; ?></textarea>
      </div>
      <div class="row">
        
      <?php if($validasi == '2' && $this->session->userdata('session')->id_role == '1' && $tanggal_perolehan != '-' ) : ?>
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Tanggal Perolehan <?php echo form_error('tanggal_perolehan') ?></label>
            <input type="text" class="form-control date_input" name="tanggal_perolehan" id="tanggal_perolehan" placeholder="Tanggal Perolehan" value="<?php echo $tanggal_perolehan; ?>" />
          </div>
        </div>
        <?php else : ?>
        <div class="col-md-6">
          <div class="form-group">
            <label for="varchar">Tanggal Perolehan <?php echo form_error('tanggal_perolehan') ?></label>
            <input type="text" class="form-control <?php echo $validasi == 2 ? "" : "date_input"; ?>" name="tanggal_perolehan" id="tanggal_perolehan" placeholder="Tanggal Perolehan" value="<?php echo $tanggal_perolehan; ?>" <?php echo $validasi == 2 ? "readonly" : ""; ?> />
          </div>
        </div>
        <?php endif; ?>
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
      <input type="hidden" name="id_kib_d" value="<?php echo $id_kib_d; ?>" />
      <button type="submit" class="btn btn-primary">Simpan</button>


      <?php if (empty($data_reklas)) : ?>
        <a href="<?php echo base_url('kib_d') ?>" class="btn btn-default">Batalkan</a>
      <?php else : ?>
        <a href="<?php echo base_url('reklas/reklas_kode/index/batal_entri') ?>" class="btn btn-default">Batalkan</a>
      <?php endif; ?>

    </div>
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
    // var luas = $('.checkbox_kib_a').iCheck().attr('attr_luas');
    $('#kode_tanah').val(kode_barang);
    $('#status_tanah').val(status_hak).trigger('change');
    $('#asal_usul').val(asal_usul).trigger('change');
    // $('#luas_m2').val(luas);

  });
</script>