<style media="screen">
  th,
  td {
    white-space: nowrap;
  }
</style>

<section class="content">
  

  <h2 style="margin-top:0px">Pengecekan Barang</h2>
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

                $url  = '#';
                $url2 = '';
                
                if($key == 1){
                  $url = site_url('mutasi/pengecekan_barang/form_pengecekan/'. encrypt_url($id_status_mutasi->id_mutasi));
                }
                elseif($key == 2){
                  if($id_status_mutasi->status_proses == '3'){
                  $url = site_url('mutasi/pengecekan_barang/bast/'. encrypt_url($id_status_mutasi->id_mutasi));
                  $url2= site_url('mutasi/pengecekan_barang/form_pengajuan_register/'. encrypt_url($id_status_mutasi->id_mutasi));
                  }
                }
                elseif($key == 4){
                  $url = site_url('mutasi/list_data');
                }
              ?>
                <li class="progress-step <?= $class ?>">
                  <div class="progress-text">
                    <h4 class="progress-title">Step <?= $key + 1; ?></h4>
                   <?= $key <> 2 ?  '<a  href="'.$url.'"><b>'.$value['deskripsi'].'</a></b>' : '<a   href="'.$url.'"><b>Cetak BAST</b></a> / <a   href="'.$url2.'"><b>Upload BAST</b></a>';  ?> 
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
          <input type="text" class="form-control date_input" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" disabled />
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
      <label for="varchar">Lokasi Baru / Penerima <?php echo form_error('kode_lokasi_lama') ?></label>
      <select class="form-control" name="kode_lokasi_baru" id="kode_lokasi_baru" disabled>
        <?php foreach ($lokasi as $key) : ?>
          <option value="<?php echo $key->id_kode_lokasi ?>" <?= ($key->id_kode_lokasi == $id_kode_lokasi_baru) ? 'selected' : '' ?>><?php echo remove_star($key->kode) . ' - ' . $key->instansi ?></option>
        <?php endforeach; ?>
      </select>
    </div>


    <label for="varchar">Barang <?php echo form_error('barang') ?></label>

    <div class="row">
      <div class="col-md-12">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="<?= $kib_active[0] == '1' ? 'active' : '';
                        echo isset($kib_hidden['1']) ? 'hidden' : ''; ?>"><a href="#tab_kib_a" data-toggle="tab">KIB A</a></li>
            <li class="<?= $kib_active[0] == '2' ? 'active' : '';
                        echo isset($kib_hidden['2']) ? 'hidden' : ''; ?>"><a href="#tab_kib_b" data-toggle="tab">KIB B</a></li>
            <li class="<?= $kib_active[0] == '3' ? 'active' : '';
                        echo isset($kib_hidden['3']) ? 'hidden' : ''; ?>"><a href="#tab_kib_c" data-toggle="tab">KIB C</a></li>
            <li class="<?= $kib_active[0] == '4' ? 'active' : '';
                        echo isset($kib_hidden['4']) ? 'hidden' : ''; ?>"><a href="#tab_kib_d" data-toggle="tab">KIB D</a></li>
            <li class="<?= $kib_active[0] == '5' ? 'active' : '';
                        echo isset($kib_hidden['5']) ? 'hidden' : ''; ?>"><a href="#tab_kib_e" data-toggle="tab">KIB E</a></li>
            <?php /*<li class="<?= $kib_active[0]=='6'?'active':''; echo isset($kib_hidden['6'])?'hidden':'';?>"><a href="#tab_kib_f" data-toggle="tab">KIB F</a></li>*/ ?>
            <li class="<?= $kib_active[0] == '5.03' ? 'active' : '';
                        echo isset($kib_hidden['5.03']) ? 'hidden' : ''; ?>"><a href="#tab_kib_atb" data-toggle="tab">KIB ATB</a></li>
            <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane <?= $kib_active[0] == '1' ? 'active' : ''; ?>" id="tab_kib_a">
              <b>KIB A</b>
              <?php $this->load->view('pengecekan_barang/list_kib_a'); ?>
            </div>
            <div class="tab-pane <?= $kib_active[0] == '2' ? 'active' : ''; ?>" id="tab_kib_b">
              <b>KIB B</b>
              <?php $this->load->view('pengecekan_barang/list_kib_b'); ?>
            </div>
            <div class="tab-pane <?= $kib_active[0] == '3' ? 'active' : ''; ?>" id="tab_kib_c">
              <b>KIB C</b>
              <?php $this->load->view('pengecekan_barang/list_kib_c'); ?>
            </div>
            <div class="tab-pane <?= $kib_active[0] == '4' ? 'active' : ''; ?>" id="tab_kib_d">
              <b>KIB D</b>
              <?php $this->load->view('pengecekan_barang/list_kib_d'); ?>
            </div>
            <div class="tab-pane <?= $kib_active[0] == '5' ? 'active' : ''; ?>" id="tab_kib_e">
              <b>KIB E</b>
              <?php $this->load->view('pengecekan_barang/list_kib_e'); ?>
            </div>
            <?php /*
              <div class="tab-pane <?= $kib_active[0]=='6'?'active':''; ?>" id="tab_kib_f">
                <b>KIB F</b>
                <?php $this->load->view('pengecekan_barang/list_kib_f'); ?>
              </div>
*/ ?>
            <div class="tab-pane <?= $kib_active[0] == '5.03' ? 'active' : ''; ?>" id="tab_kib_atb">
              <b>KIB ATB</b>
              <?php $this->load->view('pengecekan_barang/list_kib_atb'); ?>
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
    <a href="<?php echo base_url('mutasi/pengecekan_barang') ?>" class="btn btn-default">Batalkan</a>
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
</script>