<style media="screen">
  th,
  td {
    white-space: nowrap;
  }
</style>

<section class="content">
  

  <h2 style="margin-top:0px">Detail Mutasi KIR</h2>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Progress Mutasi</h3>
        </div>

        <div class="fullwidth">
          <div class="container separator">

            <ul class="progress-tracker progress-tracker--text progress-tracker--text-top" style="opacity: 0.9; margin-top: 5px; margin-bottom: 15px;">
              <?php
              // $class = array('1' => 'is-complete', '2' => 'is-active',  '3' => '',);
              foreach ($list_status_mutasi as $key => $value) :
                // echo $key . "==" . (string)$id_status_mutasi->status_proses;
                if ($key < ($id_status_mutasi->status_proses - 1) or ($id_status_mutasi->status_proses == 5)) :        $class =  'is-complete';
                elseif ($key == ($id_status_mutasi->status_proses - 1)) :    $class =  'is-active';
                else : $class =  '';
                endif;
              ?>
                <li class="progress-step <?= $class ?>">
                  <div class="progress-text">
                    <h4 class="progress-title">Step <?= $key + 1; ?></h4>
                    <?= $value['deskripsi']; ?>
                  </div>
                  <div class="progress-marker"></div>
                </li>
              <?php
              endforeach; ?>

            </ul>

          </div>
        </div>

      </div>

    </div>
  </div>
  <form action="<?php echo $action; ?>" method="post" id="form_mutasi" autocomplete="off">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="date">Tanggal Pengajuan<?php echo form_error('tanggal') ?></label>
          <input type="text" class="form-control date_input_lock" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" disabled/>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="date">Tanggal Penerimaan<?php echo form_error('tanggal_diterima') ?></label>
          <input type="text" class="form-control date_input" name="tanggal_validasi" id="tanggal_validasi" placeholder="Tanggal Penerimaan" value="<?php echo $tanggal_validasi; ?>" />
        </div>
      </div>
    </div>

    <div class="form-group">
      <label for="varchar">Lokasi Lama / Pengusul <?php echo form_error('kode_lokasi_lama') ?></label>
      <select class="form-control" name="kode_lokasi_lama" id="kode_lokasi_lama" disabled>
        <?php foreach ($lokasi as $key) : ?>
          <option value="<?php echo $key->id_kode_lokasi ?>" <?= ($key->id_kode_lokasi == $id_kode_lokasi_lama) ? 'selected' : '' ?>><?php echo remove_star($key->kode) . ' - ' . $key->instansi ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label for="varchar">Ruang Lama</label>
    <select class="form-control" name="id_ruang_lama" id="id_ruang_lama" disabled>
        <?php foreach ($list_ruang as $key) : ?>
          <option value="<?php echo $key->id_ruang ?>" <?= ($key->id_ruang == $id_ruang_lama) ? 'selected' : '' ?>><?php echo remove_star($key->nama_ruang) . ' - ' . $key->nama_gedung ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label for="varchar">Lokasi Baru / Penerima <?php echo form_error('kode_lokasi_lama') ?></label>
      <select class="form-control" name="kode_lokasi_baru" id="kode_lokasi_baru" disabled>
        <?php foreach ($lokasi as $key) : ?>
          <option value="<?php echo $key->id_kode_lokasi ?>" <?= ($key->id_kode_lokasi == $id_kode_lokasi_baru) ? 'selected' : '' ?>><?php echo remove_star($key->kode) . ' - ' . $key->instansi ?></option>
        <?php endforeach; ?>
      </select>
    </div>
 <div class="form-group">
      <label for="varchar">Ruang Baru</label>
     <select class="form-control" name="id_ruang_baru" id="id_ruang_lama" disabled>
        <?php foreach ($list_ruang as $key) : ?>
          <option value="<?php echo $key->id_ruang ?>" <?= ($key->id_ruang == $id_ruang_baru) ? 'selected' : '' ?>><?php echo remove_star($key->nama_ruang) . ' - ' . $key->nama_gedung ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <label for="varchar">Barang <?php echo form_error('barang') ?></label>

    <div class="row">
      <div class="col-md-12">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
           
            <li class="<?= $kib_active[0] == '2' ? 'active' : '';
                        echo isset($kib_hidden['2']) ? 'hidden' : ''; ?>"><a href="#tab_kib_b" data-toggle="tab">KIB B</a></li>
           
            <li class="<?= $kib_active[0] == '5' ? 'active' : '';
                        echo isset($kib_hidden['5']) ? 'hidden' : ''; ?>"><a href="#tab_kib_e" data-toggle="tab">KIB E</a></li>
            
          </ul>
          <div class="tab-content">
            
            <div class="tab-pane <?= $kib_active[0] == '2' ? 'active' : ''; ?>" id="tab_kib_b">
              <b>KIB B</b>
              <?php $this->load->view('list_data/list_kib_b'); ?>
            </div>
            
            <div class="tab-pane <?= $kib_active[0] == '5' ? 'active' : ''; ?>" id="tab_kib_e">
              <b>KIB E</b>
              <?php $this->load->view('list_data/list_kib_e'); ?>
            </div>
            
            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
      </div>
      <!-- /.col -->

    </div>

    <input type="hidden" name="id_mutasi" value="<?php echo $id_mutasi; ?>" />
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?php echo base_url('mutasi_kir/list_data') ?>" class="btn btn-default">Batalkan</a>
  </form>


</section>

<script type="text/javascript">
  var id_mutasi = $('input[name=id_mutasi]').val();
  $('#form_mutasi').on('keyup keypress', function(e) {
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

  //Date picker
  $('.date_input').datepicker({
      format: "dd MM yyyy",
      autoclose: true,
      language: "id",
      locale: 'id',
      todayHighlight: true,
      endDate:'31-12-'+(new Date).getFullYear(),
  });
  
  //Date picker
  $('.date_input_lock').datepicker({
      format: "dd MM yyyy",
      autoclose: true,
      language: "id",
      locale: 'id',
      todayHighlight: true,
      endDate:'31-12-'+(new Date).getFullYear(),
  });
  

  $(".date_input").datepicker().on('changeDate', function (e) {
    var pickedMonth = new Date(e.date).getMonth() + 1;
    var pickedYear = new Date(e.date).getFullYear();    
    // console.log(pickedMonth);
    // console.log(pickedYear);      
    url = base_url + 'global_controller/cek_stock_opname/';
      $.post(url, { bulan: pickedMonth, tahun: pickedYear  }, function (respon) {
        obj_respon = jQuery.parseJSON(respon);
        // console.log(obj_respon.status);
        if(!obj_respon.status){
          $(".date_input").datepicker('setDate', null); 
          swal({
            type: 'error',
            title: '!!!',
            text: obj_respon.message,
            // footer: '<a href>Why do I have this issue?</a>'
          });
          return false;
        }
        // getNoRegister();
      });     
  });
</script>