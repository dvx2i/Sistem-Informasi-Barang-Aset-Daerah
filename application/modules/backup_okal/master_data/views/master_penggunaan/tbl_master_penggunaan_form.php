
  <section class="content">
    <h2 style="margin-top:0px">Penggunaan <?php echo $button ?></h2>
    <form action="<?php echo $action; ?>" method="post">
      <div class="form-group">
        <label for="varchar">Deskripsi <?php echo form_error('description') ?></label>
        <input type="text" class="form-control" name="description" id="description" placeholder="Deskripsi" value="<?php echo $description; ?>" />
      </div>
      <input type="hidden" name="id_master_penggunaan" value="<?php echo $id_master_penggunaan; ?>" />
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="<?php echo base_url('master_data') ?>" class="btn btn-default">Batal</a>
    </form>
  </section>
