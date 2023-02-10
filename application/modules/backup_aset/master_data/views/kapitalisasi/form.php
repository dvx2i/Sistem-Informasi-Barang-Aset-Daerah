
  <section class="content">
    <h2 style="margin-top:0px"> Kapitalisasi</h2>
    <form action="<?php echo $action; ?>" method="post">
      <div class="form-group">
        <label for="varchar">Bidang Barang <?php echo form_error('bidang_barang') ?></label>
        <div class="row">
          <div class="col-md-4">
            <?php /*<input type="text" class="form-control" name="bidang_barang" id="bidang_barang" placeholder="" value="<?php echo $Bidang_Barang; ?>" />*/ ?>
            <select class="form-control" name="bidang_barang" id="bidang_barang">
              <?php foreach ($list_bidang_barang as $key => $value) :?>
                <option value="<?= $value->nama_barang; ?>"  <?= ($value->nama_barang==$Bidang_Barang)?'selected':''; ?> ><?= $value->nama_barang; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="varchar">Batas A <?php echo form_error('batas_a') ?></label>
        <div class="row">
          <div class="col-md-4">
            <input type="text" class="form-control" name="batas_a" id="batas_a" placeholder="0" value="<?php echo $Batas_A; ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="varchar">Batas B <?php echo form_error('batas_b') ?></label>
        <div class="row">
          <div class="col-md-4">
            <input type="text" class="form-control" name="batas_b" id="batas_b" placeholder="0" value="<?php echo $Batas_B; ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="varchar">Batas C <?php echo form_error('batas_c') ?></label>
        <div class="row">
          <div class="col-md-4">
            <input type="text" class="form-control" name="batas_c" id="batas_c" placeholder="0" value="<?php echo $Batas_C; ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="varchar">Batas D <?php echo form_error('batas_d') ?></label>
        <div class="row">
          <div class="col-md-4">
            <input type="text" class="form-control" name="batas_d" id="batas_d" placeholder="0" value="<?php echo $Batas_D; ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="varchar">Batas E <?php echo form_error('batas_e') ?></label>
        <div class="row">
          <div class="col-md-4">
            <input type="text" class="form-control" name="batas_e" id="batas_e" placeholder="0" value="<?php echo $Batas_E; ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="varchar">Batas F <?php echo form_error('batas_f') ?></label>
        <div class="row">
          <div class="col-md-4">
            <input type="text" class="form-control" name="batas_f" id="batas_f" placeholder="0" value="<?php echo $Batas_F; ?>" />
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="varchar">Tambah A <?php echo form_error('tambah_a') ?></label>
        <div class="row">
          <div class="col-md-4">
            <input type="text" class="form-control" name="tambah_a" id="tambah_a" placeholder="0" value="<?php echo $Tambah_A; ?>" />
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="varchar">Tambah B <?php echo form_error('tambah_b') ?></label>
        <div class="row">
          <div class="col-md-4">
            <input type="text" class="form-control" name="tambah_b" id="tambah_b" placeholder="0" value="<?php echo $Tambah_B; ?>" />
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="varchar">Tambah C <?php echo form_error('tambah_c') ?></label>
        <div class="row">
          <div class="col-md-4">
            <input type="text" class="form-control" name="tambah_c" id="tambah_c" placeholder="0" value="<?php echo $Tambah_C; ?>" />
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="varchar">Tambah D <?php echo form_error('tambah_d') ?></label>
        <div class="row">
          <div class="col-md-4">
            <input type="text" class="form-control" name="tambah_d" id="tambah_d" placeholder="0" value="<?php echo $Tambah_D; ?>" />
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="varchar">Tambah E <?php echo form_error('tambah_e') ?></label>
        <div class="row">
          <div class="col-md-4">
            <input type="text" class="form-control" name="tambah_e" id="tambah_e" placeholder="0" value="<?php echo $Tambah_E; ?>" />
          </div>
        </div>
      </div>

      <input type="hidden" name="id_kode_barang" value="<?php echo $Kelompok_Manfaat; ?>" />
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="<?php echo base_url('master_data/kapitalisasi') ?>" class="btn btn-default">Batal</a>

<?php /*
      <div class="form-group">
        <label for="varchar">Kode Barang <?php echo form_error('kode_barang') ?></label>
        <div class="row">
          <div class="col-md-4">
            <input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="1.3.1.01.01.01.001" value="<?php echo $kode_barang; ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="varchar">Nama Branga / Uraian <?php echo form_error('nama_barang') ?></label>
        <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Uraian" value="<?php echo $nama_barang; ?>" />
      </div>
      <div class="form-group">
        <label for="char">Akun <?php echo form_error('kode_akun') ?></label>
        <div class="row">
          <div class="col-md-1">
            <input type="text" class="form-control" name="kode_akun" id="kode_akun" placeholder="1" value="<?php echo $kode_akun; ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="char">Kelompok <?php echo form_error('kode_kelompok') ?></label>
        <div class="row">
          <div class="col-md-1">
            <input type="text" class="form-control" name="kode_kelompok" id="kode_kelompok" placeholder="3" value="<?php echo $kode_kelompok; ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="char">Jenis <?php echo form_error('kode_jenis') ?></label>
        <div class="row">
          <div class="col-md-1">
            <input type="text" class="form-control" name="kode_jenis" id="kode_jenis" placeholder="1" value="<?php echo $kode_jenis; ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="char">Objek <?php echo form_error('kode_objek') ?></label>
        <div class="row">
          <div class="col-md-1">
            <input type="text" class="form-control" name="kode_objek" id="kode_objek" placeholder="01" value="<?php echo $kode_objek; ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="char">Rincian Objek <?php echo form_error('kode_rincian_objek') ?></label>
        <div class="row">
          <div class="col-md-1">
            <input type="text" class="form-control" name="kode_rincian_objek" id="kode_rincian_objek" placeholder="01" value="<?php echo $kode_rincian_objek; ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="char">Sub Rincian Objek <?php echo form_error('kode_sub_rincian_objek') ?></label>
        <div class="row">
          <div class="col-md-1">
            <input type="text" class="form-control" name="kode_sub_rincian_objek" id="kode_sub_rincian_objek" placeholder="01" value="<?php echo $kode_sub_rincian_objek; ?>" />
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="char">Sub Sub Rincian Objek <?php echo form_error('kode_sub_sub_rincian_objek') ?></label>
        <div class="row">
          <div class="col-md-1">
            <input type="text" class="form-control" name="kode_sub_sub_rincian_objek" id="kode_sub_sub_rincian_objek" placeholder="001" value="<?php echo $kode_sub_sub_rincian_objek; ?>" />
          </div>
        </div>
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
            <input type="text" class="form-control" name="kelompok_manfaat" id="kelompok_manfaat" placeholder="Kelompok Manfaat" value="<?php echo $kelompok_manfaat; ?>" />
          </div>
        </div>
      </div>

      <input type="hidden" name="id_kode_barang" value="<?php echo $id_kode_barang; ?>" />
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="<?php echo base_url('master_data/kode_barang') ?>" class="btn btn-default">Batal</a>
*/ ?>
    </form>
  </section>
<?php /*
<script type="text/javascript">
  $(document).ready(function(){
    $('select[data-role=select2]').select2({
      theme: 'bootstrap',
      width: '100%'
    });
  })
</script>
*/ ?>
