<style media="screen">
   .select2-selection__rendered { margin-top: 1px!important }
</style>

  <section class="content">
    <h2 style="margin-top:0px"> Pengaturan Tanda Tangan</h2>
    <form action="<?php echo $action; ?>" method="post">

      <div class="form-group">
        <label for="varchar">Kode Lokasi <?php echo form_error('id_kode_lokasi') ?></label>
        <div class="row">
          <div class="col-md-12">
            <!-- <input type="text" class="form-control col-md-5" name="" id="" placeholder="" value="<?php echo $id_kode_lokasi; ?>" /> -->
            <select class="form-control select2" name="id_kode_lokasi" id="id_kode_lokasi" placeholder="Kode Lokasi" data-role="select2">
              <option value="">Silahkan Pilih</option>
              <?php foreach ($list_lokasi as $key): ?>
                <option data_kode_lokasi="<?= remove_star($key->kode) ?>" data-instansi="<?php echo $key->instansi ?>" value="<?php echo $key->id_kode_lokasi; ?>" <?= ($key->id_kode_lokasi == $id_kode_lokasi) ? 'selected' : ''?>><?php echo remove_star($key->kode).' - '.$key->instansi ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <?php // print_r($tanda_tangan); ?>
      <?php foreach ($list_jabatan as $key => $value): ?>
        <div class="box"  style="padding: 10px; border: 3px solid #d2d6de; background-color:transparent;">
          <div class="form-group">
            <label for="varchar"><?=$value->description ?> <?php echo form_error("user[<?=$value->id_jabatan; ?>]") ?></label>

            <div class="row">
              <div class="col-md-12">
                <select class="option_user form-control select2" name="user[<?=$value->id_jabatan; ?>]" placeholder="" data-role="select2">
                  <option value="">Silahkan Pilih</option>
                  <?php foreach ($list_user as $key2): ?>

                    <option  value="<?php echo $key2->id_user; ?>" <?= ($key2->id_user == $tanda_tangan[$value->id_jabatan]) ? 'selected' : ''?>><?php echo $key2->nama ?></option>

                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>



      <input type="hidden" name="id_tanda_tangan" value="<?php echo $id_kode_lokasi; ?>" />
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="<?php echo base_url('master_data/tanda_tangan') ?>" class="btn btn-default">Batal</a>
    </form>
  </section>

<script>
$(document).ready(function(){
  $('select[data-role=select2]').select2({
    theme: 'bootstrap',
    width: '100%'
  });

  //  BIDANG CLICK
  $('#id_kode_lokasi').change(function(e){

    var kode_lokasi = $('option:selected',this).attr('data_kode_lokasi');

    $('.option_user').html('<option value="">Silahkan Pilih</option>');

    url=base_url+'master_data/tanda_tangan/get_user';
    $.post(url,{kode_lokasi:kode_lokasi,}, function(respon){
        obj_respon      =jQuery.parseJSON(respon);
        console.log(obj_respon);
        $('.option_user').html(obj_respon.option);
    });
  });

});
</script>
