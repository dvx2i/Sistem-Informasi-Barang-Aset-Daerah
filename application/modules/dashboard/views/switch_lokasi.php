<style media="screen">
  .select2-selection__rendered {
    margin-top: 1px !important
  }

  /* Disable form  */
  .input_disable {
    pointer-events: none;
    opacity: 0.7;
  }
</style>

<section class="content">
  <!-- Small boxes (Stat box) -->




  <form action="<?php echo $action; ?>" method="post" id="form_ususlan" autocomplete="off">
    <div class="row">
      <div class="col-md-8">
        <div class="box box-default">
          <div class="box-header with-border">
            <i class="fa fa-info"></i>

            <h3 class="box-title">Pindah Lokasi</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="form-group">
              <label for="varchar">SKPD <?php echo form_error('id_pengguna') ?></label>

              <select class="form-control" name="id_pengguna" id="id_pengguna" placeholder="SKPD" data-role="select2">
                <option value="">Pilih SKPD</option>
                <?php foreach ($pengguna_list as $key) : ?>
                  <option value="<?php echo $key->id_pengguna ?>" <?= ($key->id_pengguna == $id_pengguna) ? 'selected' : '' ?>><?php echo remove_star($key->pengguna) . ' - ' . $key->instansi ?></option>
                <?php endforeach; ?>
              </select>
            </div>


            <div class="form-group">
              <label for="varchar">Lokasi <?php /*echo form_error('kode_lokasi_baru')*/ ?></label>

              <select class="form-control" name="id_kuasa_pengguna" id="id_kuasa_pengguna" placeholder="Kode Lokasi" data-role="select2">
                <option value="">Pilih Lokasi</option>
                <?php if (!empty($kuasa_pengguna_list)) : ?>
                  <?php foreach ($kuasa_pengguna_list as $key) : ?>
                    <option value="<?php echo $key->id_kuasa_pengguna ?>" <?php echo ($key->id_kuasa_pengguna == $id_kuasa_pengguna) ? 'selected' : '' ?>><?php echo remove_star($key->kuasa_pengguna) . ' - ' . $key->instansi ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>


            <div class="form-group">
              <label for="varchar">Sub Lokasi <?php /*echo form_error('kode_lokasi_baru')*/ ?></label>

              <select class="form-control" name="id_sub_kuasa_pengguna" id="id_sub_kuasa_pengguna" placeholder="" data-role="select2">
                <option value="">Pilih Sub Lokasi</option>
                <?php if (!empty($sub_kuasa_pengguna_list)) : ?>
                  <?php foreach ($sub_kuasa_pengguna_list as $key) : ?>
                    <option value="<?php echo $key->id_sub_kuasa_pengguna ?>" <?php echo ($key->id_sub_kuasa_pengguna == $id_sub_kuasa_pengguna) ? 'selected' : '' ?>><?php echo remove_star($key->sub_kuasa_pengguna) . ' - ' . $key->instansi ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>

            <div class="form-group ">
              <label for="varchar">Kode Lokasi <?php echo form_error('kode_lokasi_baru') ?></label>
              <div class="input_disable">
                <select class="form-control " name="kode_lokasi_baru" id="kode_lokasi_baru" placeholder="Kode Lokasi Pengguna Baru" data-role="select2" readonly required>
                  <!-- <option value="">Pilih Sub Lokasi</option> -->
                  <?php if (!empty($lokasi)) : ?>
                    <?php foreach ($lokasi as $key) : ?>
                      <option value="<?php echo $key['id_kode_lokasi'] ?>" <?= ($key['id_kode_lokasi'] == $kode_lokasi_baru) ? 'selected' : '' ?>><?php echo remove_star($key['kode_lokasi']) . ' - ' . $key['instansi'] ?></option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
            <a href="<?php echo base_url('dashboard') ?>" class="btn btn-default">Batalkan</a>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

  </form>





</section>



<script>
  $(document).ready(function() {

    $('select[data-role=select2]').select2({
      theme: 'bootstrap',
      width: '100%'
    });


    //  pengguna
    $('#id_pengguna').change(function(e) {
      $('#id_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
      $('#id_sub_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
      // $('#kode_lokasi_baru').html('<option value="">Silahkan Pilih</option>');
      var id_pengguna = $(this).val();
      // url = base_url + 'mutasi/pengajuan/get_lokasi/';
      url = base_url + 'dashboard/switch_lokasi/get_kuasa_pengguna/';
      $.post(url, {
        id_pengguna: id_pengguna,
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        $('#id_kuasa_pengguna').html(obj_respon.option);
      });
    });


    // kuasa pengguna
    $('#id_kuasa_pengguna').change(function(e) {
      $('#sub_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
      var id_pengguna = $('#id_pengguna').val();
      var id_kuasa_pengguna = $(this).val();
      url = base_url + 'dashboard/switch_lokasi/get_sub_kuasa_pengguna/';
      $.post(url, {
        id_pengguna: id_pengguna,
        id_kuasa_pengguna: id_kuasa_pengguna,
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        $('#id_sub_kuasa_pengguna').html(obj_respon.option);
        set_lokasi(id_kuasa_pengguna);
      });
    });


    // kuasa pengguna
    $('#id_sub_kuasa_pengguna').change(function(e) {
      var id_sub_kuasa_pengguna = $(this).val();
      set_lokasi(id_sub_kuasa_pengguna);
    });


    function set_lokasi(id_kode_lokasi) {
      $('#kode_lokasi_baru').html('<option value="">Kode Lokasi</option>');
      url = base_url + 'dashboard/switch_lokasi/get_lokasi/';
      $.post(url, {
        id_kode_lokasi: id_kode_lokasi,
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        $('#kode_lokasi_baru').html(obj_respon.option);
      });
    }


  }); //ready function
</script>