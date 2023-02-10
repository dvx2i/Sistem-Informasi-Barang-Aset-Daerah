<section class="content">
  <h2 style="margin-top:0px"> <?php echo $button ?> Kode Lokasi</h2>
  <form action="<?php echo $action; ?>" method="post">

    <div class="form-group" style="display: none">
      <label for="varchar">Intra Kom Ekstra Kom <?php echo form_error('intra_kom_ekstra_kom') ?></label>
      <div class="row">
        <div class="col-md-1">
          <input type="text" class="form-control col-md-5" name="intra_kom_ekstra_kom" id="intra_kom_ekstra_kom" placeholder="00" value="00<?php //echo $intra_kom_ekstra_kom; 
                                                                                                                                            ?>" />
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="varchar">Propinsi <?php echo form_error('propinsi') ?></label>
      <div class="row">
        <div class="col-md-1">
          <input type="text" class="form-control" name="propinsi" id="propinsi" placeholder="00" value="34<?php //echo $propinsi; 
                                                                                                          ?>" readonly />
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="varchar">Kabupaten <?php echo form_error('kabupaten') ?></label>
      <div class="row">
        <div class="col-md-1">
          <input type="text" class="form-control" name="kabupaten" id="kabupaten" placeholder="00" value="71<?php //echo $kabupaten; 
                                                                                                            ?>" readonly />
        </div>
      </div>
    </div>


    <div class="form-group">
      <label for="varchar">Pengguna Urusan Unsur</label>
      <div class="row">
        <div class="col-md-2">
          <input type="text" class="form-control pengguna" name="pengguna_urusan_unsur" id="pengguna_urusan_unsur" placeholder="00" maxlength="2" value="<?php echo $pengguna_urusan_unsur; ?>" />
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="varchar">Pengguna Bidang</label>
      <div class="row">
        <div class="col-md-2">
          <input type="text" class="form-control pengguna" name="pengguna_bidang" id="pengguna_bidang" placeholder="00" maxlength="2" value="<?php echo $pengguna_bidang; ?>" />
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="varchar">Pengguna OPD</label>
      <div class="row">
        <div class="col-md-2">
          <input type="text" class="form-control pengguna" name="pengguna_opd" id="pengguna_opd" placeholder="00" maxlength="2" value="<?php echo $pengguna_opd; ?>" />
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="varchar">Pengguna <?php echo form_error('pengguna') ?></label>
      <div class="row">
        <div class="col-md-2">
          <input type="text" class="form-control" name="pengguna" id="pengguna" placeholder="00000" value="<?php echo $pengguna; ?>" readonly />
        </div>
      </div>
    </div>


    <div class="form-group">
      <label for="varchar">Kuasa Pengguna <?php echo form_error('kuasa_pengguna') ?></label>
      <div class="row">
        <div class="col-md-2">
          <input type="text" class="form-control" name="kuasa_pengguna" id="kuasa_pengguna" placeholder="00000" value="<?php echo $kuasa_pengguna; ?>" />
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="varchar">Sub Kuasa Pengguna <?php echo form_error('sub_kuasa_pengguna') ?></label>
      <div class="row">
        <div class="col-md-2">
          <input type="text" class="form-control" name="sub_kuasa_pengguna" id="sub_kuasa_pengguna" placeholder="00000" value="<?php echo $sub_kuasa_pengguna; ?>" />
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="varchar">Instansi <?php echo form_error('instansi') ?></label>
      <input type="text" class="form-control" name="instansi" id="instansi" placeholder="Instansi" value="<?php echo $instansi; ?>" />
    </div>
    <div class="form-group">
      <label for="varchar">Keterangan <?php echo form_error('keterangan') ?></label>
      <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" value="<?php echo $keterangan; ?>" />
    </div>
    <input type="hidden" name="id_kode_lokasi" value="<?php echo $id_kode_lokasi; ?>" />
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?php echo base_url('master_data/kode_lokasi') ?>" class="btn btn-default">Batal</a>
  </form>
</section>


<script>
  $(document).on('keyup', '.pengguna', function() {
    // console.log("ok");
    var var_urusan_unsur = $("#pengguna_urusan_unsur").val();
    var var_bidang = $("#pengguna_bidang").val();
    var var_opd = $("#pengguna_opd").val();
    $("#pengguna").val(var_urusan_unsur + var_bidang + var_opd);

  })
</script>