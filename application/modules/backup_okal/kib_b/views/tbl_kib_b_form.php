<?php 
    $session = $this->session->userdata('session'); ?>
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
          <label for="varchar">Nomor  Transaksi (SP2D, BAST, kwitansi, dll.) <?php echo form_error('nomor_transaksi') ?></label>
          <input type="text" class="form-control" name="nomor_transaksi" id="nomor_transaksi" placeholder="Nomor Transaksi" value="<?php echo $nomor_transaksi; ?>" onkeyup="show_input();" />
        </div>
      </div>
    </div>
    <div <?= $id_kib_b == '' ? ' id="input_disable" class="input_disable" ' : ''; ?>>


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
            <label for="varchar">Kode Anggaran <?php echo form_error('rekening') ?></label>
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
          <div class="col-md-6">
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
                <input id="checkbox_kib_f" type='checkbox' class='minimal barang_kib_f' attr_kode_jenis='2' name="kib_f" value="2" <?= $kib_f == 2 ? "checked" : ""; ?>>
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
              <a style="margin-bottom: 5px;" class="btn btn-success btn-sm" href="https://aset.jogjakota.go.id/assets/files/PERALATAN_DAN_MESIN.pdf" target="_blank">Referensi Kode Barang</a>
          
              
              <div class="form-group">
                <label for="enum">Kode Objek</label>
                <select class="form-control" name="kode_objek" id="kode_objek" data-role="select2">
                  <option value="">Silahkan Pilih</option>
                  <?php foreach ($objek as $key => $value) : ?>
                    <?php /*echo $kode_objek . "==" . $value->kode_barang;*/ ?>
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
                  <option value="">Silahkan Pilih</option>
                  <?php foreach ($rincian_objek as $key => $value) : ?>
                    <?php /*echo $kode_rincian_objek . "==" . $value->kode_barang;*/ ?>
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
      </div>
      <div class="row">
        <input type="hidden" id="tahun_pengadaan_old" value="<?php echo $tahun_pembelian; ?>" />
        <!-- <div class="col-md-4">

          <div class="form-group">
            <label for="varchar">Tahun Pembelian <?php echo form_error('tahun_pembelian') ?></label>
            <input type="text" class="form-control tahun_input" name="tahun_pembelian" id="tahun_pengadaan" placeholder="Tahun Pembelian" value="<?php echo $tahun_pembelian; ?>" />
          </div>
        </div> -->
        <div class="col-md-2">
          <div class="form-group">
            <label for="varchar">Tanggal Pembelian <?php echo form_error('tanggal_pembelian') ?></label>
            <input type="text" class="form-control date_input" name="tanggal_pembelian" id="tanggal_pembelian" placeholder="Tanggal Pembelian" value="<?php echo $tanggal_pembelian; ?>" />
          </div>
        </div>

          
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

        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Merk Type <?php echo form_error('merk_type') ?></label>
            <input type="text" class="form-control" name="merk_type" id="merk_type" placeholder="Merk Type" value="<?php echo $merk_type; ?>" />
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Ukuran (CC / Panjang & Lebar) <?php echo form_error('ukuran_cc') ?></label>
            <input type="text" class="form-control" name="ukuran_cc" id="ukuran_cc" placeholder="Ukuran" value="<?php echo $ukuran_cc; ?>" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Bahan <?php echo form_error('bahan') ?></label>
            <input type="text" class="form-control" name="bahan" id="bahan" placeholder="Bahan" value="<?php echo $bahan; ?>" />
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Nomor Pabrik <?php echo form_error('nomor_pabrik') ?></label>
            <input type="text" class="form-control" name="nomor_pabrik" id="nomor_pabrik" placeholder="Nomor Pabrik" value="<?php echo $nomor_pabrik; ?>" />
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Nomor Rangka <?php echo form_error('nomor_rangka') ?></label>
            <input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka" value="<?php echo $nomor_rangka; ?>" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Nomor Mesin <?php echo form_error('nomor_mesin') ?></label>
            <input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin" value="<?php echo $nomor_mesin; ?>" />
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Nomor Polisi <?php echo form_error('nomor_polisi') ?></label>
            <input type="text" class="form-control" name="nomor_polisi" id="nomor_polisi" placeholder="Nomor Polisi" value="<?php echo $nomor_polisi; ?>" />
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="varchar">Nomor BPKB <?php echo form_error('nomor_bpkb') ?></label>
            <input type="text" class="form-control" name="nomor_bpkb" id="nomor_bpkb" placeholder="Nomor Bpkb" value="<?php echo $nomor_bpkb; ?>" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
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

        <div class="col-md-4">
          <div class="form-group">
            <label for="int">Harga (Satuan) <?php echo form_error('harga') ?></label>
            <input type="text" class="form-control format_float" name="harga" id="harga" placeholder="Harga" value="<?php echo str_replace(".", ",", $harga); ?>" <?php echo $validasi == 2 ? "readonly" : ""; ?> />
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

        <?php if (!$data_reklas) : ?>
          <div class="col-md-4">
            <div class="form-group" <?= $jumlah_barang == 0 ? 'hidden' : ''; ?>>
              <label for="varchar">Jumlah Barang</label>
              <input type="text" class="form-control format_number" name="jumlah_barang" id="jumlah_barang" value=" <?= $jumlah_barang; ?>" <?php echo $validasi == 2 ? "readonly" : ""; ?> />
            </div>
          </div>
        <?php endif; ?>

      </div>

      <input type="hidden" name="id_kib_b" value="<?php echo $id_kib_b; ?>" />
      <button type="submit" class="btn btn-primary">Simpan</button>
      <?php if (empty($data_reklas)) : ?>
        <a href="<?php echo base_url('kib_b') ?>" class="btn btn-default">Batalkan</a>
      <?php else : ?>
        <a href="<?php echo base_url('reklas/reklas_kode/index/batal_entri') ?>" class="btn btn-default">Batalkan</a>
      <?php endif; ?>
    </div>
  </form>
</section>