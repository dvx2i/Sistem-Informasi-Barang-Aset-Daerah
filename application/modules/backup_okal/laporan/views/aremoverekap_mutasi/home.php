<?php
  $session = $this->session->userdata('session');
  // die(json_encode($session));
 ?>
<style type="text/css">
  .m-t-3{margin-top: 3px;}
</style>

  <section class="content">
    <h2 style="margin-top:0px">Laporan Rekap Mutasi</h2>

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
          <form class="form-horizontal" action="<?=base_url('laporan/rekap_mutasi/download');?>" method="post" target="_blank">

            <div class="box-body">
              <div class="form-group">
                <label for="" class="col-sm-2 control-label">Pemilik </label>
                <div class="col-sm-4">
                  <select class="form-control" name="pemilik" id="pemilik" data-role="select2" required>
                    <option  value=""  >Silahkan Pilih</option>
                    <?php foreach ($pemilik as $key => $value) :?>
                      <option  value="<?= $value->id_pemilik; ?>" <?= ($value->id_pemilik=='2')?'selected':''; ?> ><?= $value->kode.' - '.$value->nama; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="" class="col-sm-2 control-label">SKPD </label>
                <div class="col-sm-8">
                  <select class="form-control" name="id_pengguna" id="id_pengguna" data-role="select2"  required>
                    <?php if ( $session->id_role == 1    ):?>
                      <?php foreach ($pengguna_list as $key => $value): ?>
                        <option  value="<?=$value->id_pengguna; ?>" <?=($value->id_pengguna == $pengguna->id_pengguna)?'selected':''; ?> ><?=$value->pengguna.' - '.$value->instansi; ?></option>
                      <?php endforeach; ?>
                    <?php //elseif ( $session->id_role == 2 or $session->id_role == 3  ):?>
                    <?php else:?>
                      <option  value="<?=$pengguna->id_pengguna; ?>" selected ><?=$pengguna->pengguna.' - '.$pengguna->instansi; ?></option>
                    <?php endif; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="" class="col-sm-2 control-label">Lokasi </label>
                <div class="col-sm-8">
                  <select class="form-control" name="id_kuasa_pengguna" id="id_kuasa_pengguna" data-role="select2" >
                    <?php if ( $session->id_role == 1 ):?>
                      <?php if ($kuasa_pengguna): ?>
                        <?php foreach ($kuasa_pengguna_list as $key => $value): ?>
                          <option  value="<?=$value->id_kuasa_pengguna; ?>" <?=($value->id_kuasa_pengguna == $kuasa_pengguna->id_kuasa_pengguna)?'selected':''; ?> ><?=$value->kuasa_pengguna.' - '.$value->instansi; ?></option>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <option  value=""  >Silahkan Pilih</option>
                        <?php foreach ($kuasa_pengguna_list as $key => $value): ?>
                          <option  value="<?=$value->id_kuasa_pengguna; ?>" ><?=$value->kuasa_pengguna.' - '.$value->instansi; ?></option>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    <?php //elseif ( $session->id_role == 2 or $session->id_role == 3 ):?>
                    <?php else:?> <?php //selain super admin ?>

                      <?php if ($kuasa_pengguna): ?> <?php //jika kuasa_pengguna ada, maka kunci ?>
                        <option  value="<?=$kuasa_pengguna->id_kuasa_pengguna; ?>" selected ><?=$kuasa_pengguna->kuasa_pengguna.' - '.$kuasa_pengguna->instansi; ?></option>
                      <?php else : ?> <?php //jika kuasa_pengguna tidak ada, tampilkan list ?>
                        <option  value=""  >Silahkan Pilih</option>
                        <?php foreach ($kuasa_pengguna_list as $key => $value): ?>
                          <option  value="<?=$value->id_kuasa_pengguna; ?>" ><?=$value->kuasa_pengguna.' - '.$value->instansi; ?></option>
                        <?php endforeach; ?>
                      <?php endif; ?>

                    <?php endif; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="" class="col-sm-2 control-label">Sub Lokasi </label>
                <div class="col-sm-8">
                  <select class="form-control" name="id_sub_kuasa_pengguna" id="id_sub_kuasa_pengguna" data-role="select2" >
                    <?php if ( $session->id_role == 1 ):?>
                      <?php if ($sub_kuasa_pengguna): ?>
                        <?php foreach ($sub_kuasa_pengguna_list as $key => $value): ?>
                          <option  value="<?=$value->id_sub_kuasa_pengguna; ?>" <?=($value->id_sub_kuasa_pengguna == $sub_kuasa_pengguna->id_sub_kuasa_pengguna)?'selected':''; ?> ><?=$value->sub_kuasa_pengguna.' - '.$value->instansi; ?></option>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <option  value=""  >Silahkan Pilih</option>
                        <?php foreach ($sub_kuasa_pengguna_list as $key => $value): ?>
                          <option  value="<?=$value->id_sub_kuasa_pengguna; ?>"  ><?=$value->sub_kuasa_pengguna.' - '.$value->instansi; ?></option>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    <?php //elseif ( $session->id_role == 2 or $session->id_role == 3 ):?>

                    <?php else:?>  <?php //selain super admin ?>
                      <?php if ($sub_kuasa_pengguna): ?> <?php //jika sub_kuasa_pengguna ada, maka kunci ?>
                        <option  value="<?=$sub_kuasa_pengguna->id_sub_kuasa_pengguna; ?>" selected ><?=$sub_kuasa_pengguna->sub_kuasa_pengguna.' - '.$sub_kuasa_pengguna->instansi; ?></option>
                      <?php else: ?> <?php //jika sub_kuasa_pengguna tidak ada, tampilkan list ?>
                        <option  value=""  >Silahkan Pilih</option>
                        <?php foreach ($sub_kuasa_pengguna_list as $key => $value): ?>
                          <option  value="<?=$value->id_sub_kuasa_pengguna; ?>"  ><?=$value->sub_kuasa_pengguna.' - '.$value->instansi; ?></option>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <div class="form-group form-inline">
                <label for="" class="col-sm-2 control-label">Tanggal Perolehan </label>
                <div class="col-sm-10">
                  <div class="input-group m-t-3">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input id="start_date" type="text" class="form-control pull-right date_input" name="start_date" placeholder="Tanggal Mulai" value="<?= $this->input->get('start_date'); ?>" >
                  </div>
                   -
                  <div class="input-group m-t-3">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input id="last_date" type="text" class="form-control pull-right date_input" name="last_date" placeholder="Tanggal Selesai" value="<?= $this->input->get('last_date'); ?>" >
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="" class="col-sm-2 control-label">Intra / Ekstra </label>
                <div class="col-sm-4">
                  <select class="form-control" name="intra_ekstra" id="intra_ekstra" data-role="select2">
                    <?php foreach ($intra_ekstra as $key => $value) :?>
                      <option  value="<?= $key; ?>" <?= ($key == '00')?'selected':''; ?> ><?= $key.' - '.$value; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="" class="col-sm-2 control-label">Jenis Rekap </label>
                <div class="col-sm-10" style="padding-top:7px;">
                  <label style="width:11%">
                    <input type="radio" name="jenis_rekap" value="objek" class="flat-red" checked> Objek
                  </label>
                  <label>
                    <input type="radio" name="jenis_rekap" value="rincian_objek" class="flat-red"> Rincian Objek
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label for="" class="col-sm-2 control-label">Format </label>
                <div class="col-sm-10" style="padding-top:7px;">
                  <label style="width:11%">
                    <input type="radio" name="format" value="pdf" class="flat-red" checked> Pdf
                  </label>
                  <label>
                    <input type="radio" name="format" value="excel" class="flat-red"> Excel
                  </label>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <?php //echo anchor(base_url('laporan/kib/excel'), 'Unduh', 'class="btn btn-primary pull-right"'); ?>
              <?php /*<input type="submit" name="submit" value="proses" class='btn btn-primary pull-right'>*/ ?>
              <button type="submit" name="submit" class='btn btn-primary pull-right'><span class="fa fa-download"></span>&nbspUnduh</button>
            </div>
            <!-- /.box-footer -->
          </form>
        </div>

      </div>
    </div>

  </section>

<script type="text/javascript">
  $(document).ready(function(){
    //Date picker
    $('.date_input').datepicker({
      format: "dd MM yyyy",
      autoclose :true,
    });
  });
</script>
