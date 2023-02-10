<?php
  $location=$this->session->userdata('session');
 ?>


  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-xs-12">
        <?= form_open(); ?>
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Pilih Lokasi</h3>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label for="enum">Pemilik</label>
              <select class="form-control" name="pemilik" id="pemilik">
                <?php foreach ($list_pemilik as $key => $value) :?>
                  <option  value="<?= $value->id_pemilik; ?>" <?= ($value->id_pemilik == $location->id_pemilik)?'selected':''; ?> ><?= $value->kode.' - '.$value->nama; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="varchar">Kode Lokasi <?php echo form_error('kode_lokasi') ?></label>
              <select class="form-control" name="kode_lokasi" id="kode_lokasi" placeholder="Kode Lokasi">
                <?php foreach ($list_lokasi as $key): ?>
                  <option  value="<?php echo $key->kode ?>" <?= ($key->kode == substr($location->kode_lokasi,3) ) ? 'selected' : ''?>><?php echo $key->kode.' - '.$key->instansi ?></option>
                <?php endforeach; ?>
              </select>
            </div>

          </div>
          <div class="box-footer">
            <input type="button" value="Pilih Lokasi" class="btn btn-success pull-right" id='btn_kode_lokasi'>
          </div>
        </div>
      <?= form_close(); ?>
      </div>
    </div>


  </section>
  <!-- /.content -->

<script>
$(document).ready(function(){
  $('#kode_lokasi').select2();
  $("#btn_kode_lokasi").click(function() {
    var url=base_url+'global_controller/set_location';
    var kode_lokasi=$('#kode_lokasi').val();
    var pemilik=$('#pemilik').val();
    $.post(url,{kode_lokasi:kode_lokasi,pemilik:pemilik}, function(respon){
        obj_respon      =jQuery.parseJSON(respon);
        if (obj_respon.status) swal("Berhasil", 'Berhasil melilih lokasi', "success");
    });
  });
});
</script>
