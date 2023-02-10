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
  <h2 style="margin-top:0px"><?= $breadcrumb[1]['label'] ?></h2>

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
        <form class="form-horizontal" action="<?= $url ?>" method="post" target="_blank">
        

          <div class="box-body">
            
            
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
            </div> --><div class="form-group form-inline">
              <label for="" class="col-sm-2 control-label">Bulan Transaksi </label>
              
              <div class="col-md-10">
                  <div class="input-group m-t-3">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input id="bulan_tahun" type="text" class="form-control pull-right bulan_tahun_input" name="bulan_tahun" placeholder=""  required>
                    <?php /*<input id="tahun" type="text" class="form-control pull-right tahun_input" name="tahun" placeholder="" value="<?= $tahun; ?>" required> */ ?>
                  </div>
                  -
                  <div class="input-group m-t-3">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input id="last_bulan_tahun" type="text" class="form-control pull-right bulan_tahun_input" name="last_bulan_tahun" placeholder=""  required>
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
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Format </label>
              <div class="col-sm-10" style="padding-top:7px;">
                <label>
                  <input type="radio" name="format" value="excel" checked class="flat-red"> Excel
                </label>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <?php //echo anchor(base_url('laporan/kib/excel'), 'Unduh', 'class="btn btn-primary pull-right"'); 
            ?>
            <?php /*<input type="submit" name="submit" value="proses" class='btn btn-primary pull-right'>*/ ?>
            <button type="submit" name="submit" class='btn btn-primary pull-right'> <span class="fa fa-download"></span> Unduh</button>
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
        // beforeShowMonth: function(date) {
        //   if ((date.getMonth() == 5) || (date.getMonth() == 11)) {
        //     return true;
        //   } else {
        //     return false;
        //   }
        // }
      });
  });
</script>