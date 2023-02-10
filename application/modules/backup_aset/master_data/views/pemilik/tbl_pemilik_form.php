
  <section class="content">
    <h2 style="margin-top:0px"> <?php echo $button ?> Kode Pemilik</h2>
    <form action="<?php echo $action; ?>" method="post">
      <div class="form-group">
        <label for="varchar">Nama <?php echo form_error('nama') ?></label>
        <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama" value="<?php echo $nama; ?>" />
      </div>
      <div class="form-group">
        <label for="varchar">Kode <?php echo form_error('kode') ?></label>
        <div class="row">
          <div class="col-md-1">
            <input type="text" class="form-control" name="kode" id="kode" placeholder="00" value="<?php echo $kode; ?>" />
          </div>
        </div>
      </div>
      <input type="hidden" name="id_pemilik" value="<?php echo $id_pemilik; ?>" />
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="<?php echo base_url('master_data/pemilik') ?>" class="btn btn-default">Batal</a>
    </form>
  </section>
