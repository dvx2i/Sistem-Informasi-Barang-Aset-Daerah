<?php
$session = $this->session->userdata('session');
// die(json_encode($session));
    $lokasi = explode('.', $session->kode_lokasi);
    $session_pengguna = $lokasi[3];
    $session_kuasa_pengguna =  $lokasi[4];
?>
<style type="text/css">
  .m-t-3 {
    margin-top: 3px;
  }
</style>

<section class="content">
  <h2 style="margin-top:0px">Laporan Penyusutan (Koreksi Tanggal Perolehan)</h2>
  <!-- right column -->
  <div class="row">
    <div class="col-md-12">
      <!-- Horizontal Form -->
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Form</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" action="<?= base_url('unduhan/penyusutan/ndownload'); ?>" method="post" target="_blank">
          <div class="box-body">
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Pemilik </label>
              <div class="col-sm-4">
                <select class="form-control" name="pemilik" id="pemilik" data-role="select2" required>
                  <option value="">Silahkan Pilih</option>
                  <?php foreach ($pemilik as $key => $value) : ?>
                    <option value="<?= $value->id_pemilik; ?>" <?= ($value->id_pemilik == '2') ? 'selected' : ''; ?>><?= $value->kode . ' - ' . $value->nama; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-2 control-label">SKPD </label>
              <div class="col-sm-8">
                <select class="form-control" name="id_pengguna" id="id_pengguna" data-role="select2">
                  <?php if ($session->id_role == 1 || $session->id_kode_lokasi == '261') : //261 = pengawas (bisa lihat semua opd meski bukan superadmin) ?> 
                    <option value="">Silahkan Pilih</option>
                    
                    <?php if ($pengguna) : ?>
                      <?php foreach ($pengguna_list as $key => $value) : ?>
                        <option value="<?= $value->id_pengguna; ?>" <?= ($value->id_pengguna == $pengguna->id_pengguna) ? 'selected' : ''; ?>><?= $value->pengguna . ' - ' . $value->instansi; ?></option>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <?php foreach ($pengguna_list as $key => $value) : ?>
                        <option value="<?= $value->id_pengguna; ?>"><?= $value->pengguna . ' - ' . $value->instansi; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                    <?php //elseif ( $session->id_role == 2 or $session->id_role == 3  ):
                    ?>
                  <?php else : ?>
                    <option value="<?= $pengguna->id_pengguna; ?>" selected><?= $pengguna->pengguna . ' - ' . $pengguna->instansi; ?></option>
                  <?php endif; ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Lokasi </label>
              <div class="col-sm-8">
                <select class="form-control" name="id_kuasa_pengguna" id="id_kuasa_pengguna" data-role="select2">
                  <?php if ($session->id_role == 1 || $session->id_kode_lokasi == '261' || $kuasa_pengguna->kuasa_pengguna == '*'|| $session->id_kode_lokasi == '252') : ?>
                    <option value="">Silahkan Pilih</option>
                    <?php if ($kuasa_pengguna) : ?>
                      <?php foreach ($kuasa_pengguna_list as $key => $value) : ?>
                        <option value="<?= $value->id_kuasa_pengguna; ?>" <?= ($value->id_kuasa_pengguna == $kuasa_pengguna->id_kuasa_pengguna) ? 'selected' : ''; ?>><?= $value->kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <?php foreach ($kuasa_pengguna_list as $key => $value) : ?>
                        <option value="<?= $value->id_kuasa_pengguna; ?>"><?= $value->kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                    <?php //elseif ( $session->id_role == 2 or $session->id_role == 3 ):
                    ?>
                  <?php else : ?> <?php //selain super admin 
                                  ?>

                    <?php if ($session_kuasa_pengguna != '0001' && $session_kuasa_pengguna != '*') : ?> <?php //jika kuasa_pengguna ada, maka kunci 
                                                                                                                        ?>
                      <option value="<?= $kuasa_pengguna->id_kuasa_pengguna; ?>" selected><?= $kuasa_pengguna->kuasa_pengguna . ' - ' . $kuasa_pengguna->instansi; ?></option>
                    <?php else : ?> <?php //jika kuasa_pengguna tidak ada, tampilkan list 
                                    ?>
                      <option value="">Silahkan Pilih</option>
                      <?php foreach ($kuasa_pengguna_list as $key => $value) : ?>
                        <option value="<?= $value->id_kuasa_pengguna; ?>"><?= $value->kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>

                  <?php endif; ?>
                </select>
              </div>
            </div>
            
            <!-- <div class="form-group form-inline">
              <label for="" class="col-sm-2 control-label">Tanggal Perolehan </label>
              <div class="col-sm-10">
                <div class="input-group m-t-3">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input id="start_date" type="text" class="form-control pull-right date_input" name="start_date" placeholder="Tanggal Mulai" value="<?= $this->input->get('start_date'); ?>">
                </div>
                -
                <div class="input-group m-t-3">
                  <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                  <input id="last_date" type="text" class="form-control pull-right date_input" name="last_date" placeholder="Tanggal Selesai" value="<?= $this->input->get('last_date'); ?>">
                </div>
              </div>
            </div> -->
            
            <div class="form-group form-inline" >
              <label for="" class="col-sm-2 control-label">Jenis </label>
              
              <div class="col-md-10">
                  <select name="filter" id="filter" class="form-control" data-role="select2">
                    <option value="">Aset Tetap</option>
                    <option value="kajian">Kajian</option>
                  </select>
                </div>
            </div>
            <div class="form-group form-inline">
              <label for="" class="col-sm-2 control-label">Bulan Perolehan </label>
              
              <div class="col-md-10">
                  <div class="input-group m-t-3">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input id="bulan_tahun" type="text" class="form-control pull-right bulan_tahun_input" name="bulan_tahun" placeholder=""  required>
                    <?php /*<input id="tahun" type="text" class="form-control pull-right tahun_input" name="tahun" placeholder="" value="<?= $tahun; ?>" required> */ ?>
                  </div>

                  <?php /*
                  <select name="tanggal" id="tanggal" class="form-control" data-role="select2">
                    <option value="">Pilih Tanggal</option>
                    <?php foreach ($list_tanggal as $key => $value) : ?>
                      <option value="<?= $value; ?>"><?= $value; ?></option>
                    <?php endforeach; ?>
                  </select>
                  */ ?>
                </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Intra / Ekstra </label>
              <div class="col-sm-4">
                <select class="form-control" name="intra_ekstra" id="intra_ekstra" data-role="select2">
                  <?php foreach ($intra_ekstra as $key => $value) : ?>
                    <option value="<?= $key; ?>" <?= ($key == '00') ? 'selected' : ''; ?>><?= $key . ' - ' . $value; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="form-group form-inline">
              <label for="" class="col-sm-2 control-label">Limit</label>
              <div class="col-sm-10">
                <div class="input-group m-t-3">
                  <!-- <input id="mulai" type="text" class="form-control pull-right" name="mulai" placeholder="isikan tanpa tanda baca"> -->
                  <!-- <label style="margin-right:10%">
                    <input type="radio" name="mulai" value="0" class="flat-red" checked> 1 sampai 30000
                  </label>
                  <label style="margin-right:10%">
                    <input type="radio" name="mulai" value="30000" class="flat-red"> 30001 sampai 60000
                  </label>
                  <label style="margin-right:10%">
                    <input type="radio" name="mulai" value="60000" class="flat-red"> 60001 sampai 90000
                  </label> -->
                  
                  <select name="mulai" id="mulai" class="form-control">
                    <option selected value="0">1 sampai 30000</option>
                    <option value="30000">30001 sampai 60000</option>
                    <option value="60000">60001 sampai 90000</option>
                    <option value="90000">90001 sampai 120000</option>
                    <option value="120000">120001 sampai 150000</option>
                    <option value="150000">150001 sampai 180000</option>
                    <option value="180000">180001 sampai 210000</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Format </label>
              <div class="col-sm-10" style="padding-top:7px;">
                <!-- <label style="margin-right:10%">
                  <input type="radio" name="format" value="pdf" class="flat-red" checked> Pdf
                </label> -->
                <label>
                  <input checked type="radio" name="format" value="excel" class="flat-red"> Excel
                </label>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <?php /*echo anchor(base_url('laporan/kib/excel'), 'Unduh', 'class="btn btn-primary pull-right"');
              <input type="submit" name="submit" value="Unduh" class='btn btn-primary pull-right'> */ ?>
            <button type="submit" name="submit" class='btn btn-primary pull-right'><span class="fa fa-download"></span>&nbspUnduh</button>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>

    </div>
  </div>
</section>

<script type="text/javascript">
  $(document).ready(function() {
    //Date picker
    $('.date_input').datepicker({
      format: "dd MM yyyy",
      autoclose: true,
    });

    
    $('.bulan_tahun_input').datepicker({
        language: "id",
        format: "MM yyyy",
        minViewMode: "months",
        autoclose: true,
        todayHighlight: true,
        endDate: '12-' + (new Date).getFullYear(), //hanya bulan dan tahun
        beforeShowMonth: function(date) {
          if ((date.getMonth() == 5) || (date.getMonth() == 11)) {
            return true;
          } else {
            return false;
          }
        }
      });

  });
</script>