
  <section class="content">
    <h2 style="margin-top:0px"> Kode Rekening<?php echo $button ?></h2>
    <form action="<?php echo $action; ?>" method="post">
	<div class="form-group">
      <label for="char"> Sumber dana <?php echo form_error('id_sumber_dana') ?></label>
      <div class="row">
        <div class="col-md-6">
          <?php /*<input type="text" class="form-control" name="kode_akun" id="kode_akun" placeholder="1" value="<?php echo $kode_akun; ?>" /> */ ?>
          <select class="form-control" name="id_sumber_dana" id="id_sumber_dana" >
            <!-- <option value="">== Silahkan Pilih ==</option> -->
            <?php foreach ($list_seumber_dana as $key => $value) : ?>
              <option value="<?= $value['id_sumber_dana']; ?>" <?= ($value['id_sumber_dana'] == $id_sumber_dana) ? 'selected' : ''; ?>><?= $value['id_sumber_dana'] . " - " . $value['nama_sumber_dana']; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>
      <div class="form-group">
        <label for="varchar">Kode Rekening <?php echo form_error('kode_rekening') ?></label>
        <input type="text" class="form-control" name="kode_rekening" id="kode_rekening" placeholder="kode_rekening" value="<?php echo $kode_rekening; ?>" />
      </div>
	  <div class="form-group">
        <label for="varchar">Nama Rekening <?php echo form_error('nama_rekening') ?></label>
        <input type="text" class="form-control" name="nama_rekening" id="nama_rekening" placeholder="nama_rekening" value="<?php echo $nama_rekening; ?>" />
      </div>
	  
      <input type="hidden" name="id_rekening" value="<?php echo $id_rekening; ?>" />
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="<?php echo base_url('master_data/master_rekening') ?>" class="btn btn-default">Batal</a>
    </form>
  </section>
