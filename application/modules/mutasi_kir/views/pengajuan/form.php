<style media="screen">
  th,
  td {
    white-space: nowrap;
  }

  .select2-selection__rendered {
    margin-top: 1px !important
  }

  /* Disable form  */
  .input_disable {
    pointer-events: none;
    opacity: 0.7;
  }
</style>
<?php 
    $session = $this->session->userdata('session'); ?>
<section class="content">
  <h2 style="margin-top:0px"><?php echo $button ?> Barang Ruang</h2>
  <form action="<?php echo $action; ?>" method="post" id="form_ususlan" autocomplete="off">
   <div class="form-group">
      <label for="varchar">Ruang  Asal<?php /*echo form_error('kode_lokasi_baru')*/ ?></label>

      <select class="form-control" name="id_ruang_asal" id="id_ruang_asal" data-role="select2">
        <option value="0">Silahkan Pilih</option>
                  <?php foreach ($list_ruang as $key => $value) : ?>
                    <option value="<?= $value->id_ruang; ?>" ><?= $value->nama_ruang . ' - ' . $value->nama_gedung; ?></option>
                  <?php endforeach; ?>
        
      </select>
    </div>
    <div class="form-group">
      <label for="varchar">Pemilik Tujuan</label>
     
      <select class="form-control" name="pemilik" id="pemilik" data-role="select2" required>
                  <option value="">Silahkan Pilih</option>
                  <?php foreach ($pemilik as $key => $value) : ?>
                    <option value="<?= $value->id_pemilik; ?>" <?= ($value->id_pemilik == '2') ? 'selected' : ''; ?>><?= $value->kode . ' - ' . $value->nama; ?></option>
                  <?php endforeach; ?>
                </select>
    </div>
    <div class="form-group">
      <label for="varchar">SKPD Tujuan<?php echo form_error('id_pengguna') ?></label>

      <select class="form-control" name="id_pengguna" id="id_pengguna" data-role="select2">
                  <?php if ($session->id_role == 1) : ?>
                    <option value="">Silahkan Pilih</option>
                    <?php foreach ($pengguna_list as $key => $value) : ?>
                      <option value="<?= $value->pengguna; ?>" <?= ($value->pengguna == $pengguna->pengguna) ? 'selected' : ''; ?>><?= $value->pengguna . ' - ' . $value->instansi; ?></option>
                    <?php endforeach; ?>
                    <?php //elseif ( $session->id_role == 2 or $session->id_role == 3  ):
                    ?>
                  <?php else : ?>
                    <option value="<?= $pengguna->pengguna; ?>" selected><?= $pengguna->pengguna . ' - ' . $pengguna->instansi; ?></option>
                  <?php endif; ?>
                </select>
    </div>


    <div class="form-group">
      <label for="varchar">Lokasi  Tujuan<?php /*echo form_error('kode_lokasi_baru')*/ ?></label>

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
      <label for="varchar">Ruang  <?php /*echo form_error('kode_lokasi_baru')*/ ?></label>

      <select class="form-control" name="id_ruangx" id="id_ruang" placeholder="Ruang" data-role="select2">
        <option value="">Pilih Ruang</option>
        
      </select>
    </div>
    

    <?php /*
    <div class="form-group">
      <label for="varchar">Sub Lokasi Tujuan <?php echo form_error('kode_lokasi_baru') ?></label>
      <select class="form-control" name="kode_lokasi_baru" id="kode_lokasi_baru" placeholder="Kode Lokasi Pengguna Baru" data-role="select2">
        <option value="">Pilih Sub Lokasi</option>
        <?php if (!empty($lokasi)) : ?>
          <?php foreach ($lokasi as $key) : ?>
            <option value="<?php echo $key['id_kode_lokasi'] ?>" <?= ($key['id_kode_lokasi'] == $kode_lokasi_baru) ? 'selected' : '' ?>><?php echo remove_star($key['kode_lokasi']) . ' - ' . $key['instansi'] ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>
 
    <div class="form-group">
      <label for="varchar">Sub Lokasi Tujuan <?php echo form_error('kode_lokasi_baru') ?></label>

      <select class="form-control" name="id_sub_kuasa_pengguna" id="id_sub_kuasa_pengguna" placeholder="" data-role="select2">
        <option value="">Pilih Sub Lokasi</option>
        <?php if (!empty($sub_kuasa_pengguna_list)) : ?>
          <?php foreach ($sub_kuasa_pengguna_list as $key) : ?>
            <option value="<?php echo $key->id_sub_kuasa_pengguna ?>" <?php echo ($key->id_sub_kuasa_pengguna == $id_sub_kuasa_pengguna) ? 'selected' : '' ?>><?php echo remove_star($key->sub_kuasa_pengguna) . ' - ' . $key->instansi ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>*/ ?>



    <!-- <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="">Kode Lokasi Tujuan<?php echo form_error('kode_lokasi_baru') ?></label>
          <input type="text" class="form-control" name="kode_lokasi_baru" id="kode_lokasi_baru" placeholder="" value="<?php echo $kode_lokasi_baru; ?>" readonly />
        </div>
      </div>
    </div> -->

    <label for="varchar">Barang <?php echo form_error('barang') ?></label>

    <div class="row">
      <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
           
            <li class="active"><a href="#tab_kib_b" data-toggle="tab">KIB B</a></li>
            <li><a href="#tab_kib_e" data-toggle="tab">KIB E</a></li>
            <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
          </ul>
          <div class="tab-content">
            
            <div class="tab-pane active" id="tab_kib_b">
              <b>KIB B</b>
              <?php $this->load->view('pengajuan/list_kib_b'); ?>
            </div>
            
            <div class="tab-pane" id="tab_kib_e">
              <b>KIB E</b>
              <?php $this->load->view('pengajuan/list_kib_e'); ?>
            </div>

            
            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
      </div>
      <!-- /.col -->

    </div>
    <br>
    <div class="row">
      <div class="col-md-12">

        <div class="external-event bg-yellow ui-draggable ui-draggable-handle">
          <label for=""> Barang Yang di Pilih </label>
        </div>


        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="temp_kib">
            <thead>
              <tr>
                <th width="80px">No</th>
                <th width="200px">Aksi</th>
                <th>KIB</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Nomor Register</th>
                <th>Merk / Type</th>
                <!--<th>No. Sertifikat, No. Pabrik, No Chasis, No. Mesin</th>
                <th>Bahan</th>
                <th>Asal/Cara Perolehan Barang</th>-->
                <th>Tahun Perolehan</th>
                <!--<th>Ukuran Barang / konstrukdi (P,S,D)</th>-->
                <th>Satuan</th>
                <!--<th>Kondisi (B,RR,RB)</th>-->
                <th>Harga(Rp.)</th>
                <th>Keterangan</th>
                <!--<th>Kode Inventaris</th>-->

              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
    </div>

    <input type="hidden" name="id_ruang" value="<?php echo $id_ruang; ?>" />
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?php echo base_url('mutasi_kir/pengajuan') ?>" class="btn btn-default">Batalkan</a>
  </form>


</section>

<script type="text/javascript">
  $('#form_ususlan').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });

  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
    $($.fn.dataTable.tables(true)).DataTable()
      .columns.adjust();
  });

  $(document).ready(function() {
    //  pengguna
    $('#id_pengguna').change(function(e) {
      $('#id_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
      $('#id_sub_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
      // $('#kode_lokasi_baru').html('<option value="">Silahkan Pilih</option>');
      var id_pengguna = $(this).val();
      // url = base_url + 'mutasi/pengajuan/get_lokasi/';
      url = base_url + 'laporan/get_kuasa_pengguna/';
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
      url = base_url + 'mutasi_kir/pengajuan/get_lokasi_ruang/';
      $.post(url, {
        id_pengguna: id_pengguna,
        id_kuasa_pengguna: id_kuasa_pengguna,
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        $('#id_ruang').html(obj_respon.option);
        //set_lokasi(id_kuasa_pengguna);
      });
/*
      if(id_kuasa_pengguna == '<?= $this->session->userdata('session')->id_kode_lokasi ?>'){
              swal({
                  type: 'error',
                  title: '!!!',
                  text: 'Tidak dapat melakukan mutasi pada lokasi yang sama',
                  // footer: '<a href>Why do I have this issue?</a>'
                })
                $('#id_kuasa_pengguna').select2("val", "");
                $('#kode_lokasi_baru').prop('selectedIndex',0);
      }*/
    });


    // kuasa pengguna
    /*$('#id_sub_kuasa_pengguna').change(function(e) {
      var id_sub_kuasa_pengguna = $(this).val();
      url = base_url + 'master_ruang/pengajuan/get_lokasi_ruang/';
      $.post(url, {
        id_kode_lokasi: id_sub_kuasa_pengguna,
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        $('#id_ruang').html(obj_respon.option);
      });
    });
*/

    /*function set_lokasi(id_kode_lokasi) {
      $('#kode_lokasi_baru').html('<option value="">Kode Lokasi Tujuan</option>');
      url = base_url + 'master_ruang/pengajuan/get_lokasi_ruang/';
      $.post(url, {
        id_kode_lokasi: id_kode_lokasi,
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        $('#kode_lokasi').html(obj_respon.option);
      });
    }*/




  })
</script>