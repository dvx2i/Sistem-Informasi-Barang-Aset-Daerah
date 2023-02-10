
  <section  class="content">
    <h2 style="margin-top:0px"><?php echo $button ?> Intra Extra </h2>
    <form action="<?php echo $action; ?>" method="post">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="int">Kode Jenis <?php echo form_error('kode_jenis') ?></label>
            <input type="text" class="form-control" value="<?php echo $kib; ?>" readonly />
            <input type="hidden" class="form-control" name="kode_jenis" id="kode_jenis" placeholder="Kode Jenis" value="<?php echo $kode_jenis; ?>" readonly />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-2">
          <div class="form-group">
            <label for="int">Value <?php echo form_error('value') ?></label>
            <input type="text" class="form-control format_number" name="value" id="value" placeholder="Value" value="<?php echo $value; ?>" />
          </div>
        </div>
      </div>

      <input type="hidden" name="id_master_intra_extra" value="<?php echo $id_master_intra_extra; ?>" />
      <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
      <a href="<?php echo base_url('master_data/master_intra_extra') ?>" class="btn btn-default">Batal</a>
    </form>
  </section>
