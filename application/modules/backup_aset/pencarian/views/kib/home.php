<?php
$session = $this->session->userdata('session');
// die(json_encode($session));
?>
<style type="text/css">
  .m-t-3 {
    margin-top: 3px;
  }

  .select2-selection__rendered {
    margin-top: 1px !important
  }

  .d-none {
    display: none;
  }
</style>

<section class="content">
  <h2 style="margin-top:0px">Pencarian KIB</h2>
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
        <form id="form_pencarian" class="form-horizontal" action="<?=site_url('pencarian/kib/excel');?>" method="post">
          <div class="box-body">



            <div class="box" style="padding: 10px; border: 3px solid #d2d6de;">

              <div class="form-group">
                <label for="" class="col-sm-2 control-label">Jenis <span class="text-red">*</span></label>
                <div class="col-sm-4">
                  <select class="form-control" name="kode_jenis" id="kode_jenis" data-role="select2" required>
                    <option value="">Silahkan Pilih</option>
                    <?php foreach ($kib as $key => $value) : ?>
                      <?php //if ($key != "6") : 
                      ?>
                      <option value="<?= $key; ?>"><?= $value['nama']; ?></option>
                      <?php
                      // endif; 
                      ?>
                    <?php endforeach;  ?>
                  </select>
                </div>
                <?php /*
<div class="col-sm-2">
  <div class="checkbox">
    <label><input type="checkbox" name="checkbox_kib_f" id="checkbox_kib_f"> KIB F</label>
  </div>
</div>
*/ ?>

              </div>


              <div class="form-group">
                <label class="col-sm-2 control-label " for="enum">Kode Objek</label>
                <div class="col-sm-5">
                  <select class="form-control" name="kode_objek" id="kode_objek" data-role="select2">
                    <option value="">Silahkan Pilih</option>
                    <?php if (!empty($objek)) : ?>
                      <?php foreach ($objek as $key => $value) : ?>
                        <option value=<?= $value->kode_barang; ?> <?= $kode_objek == $value->kode_barang ? 'selected' : ''; ?>><?= $value->kode_objek . ' - ' . $value->nama_barang; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label " for="enum">Kode Rincian Objek</label>
                <div class="col-sm-5">
                  <select class="form-control" name="kode_rincian_objek" id="kode_rincian_objek" data-role="select2">
                    <option value="">Silahkan Pilih</option>
                    <?php if (!empty($rincian_objek)) : ?>
                      <?php foreach ($rincian_objek as $key => $value) : ?>
                        <option value=<?= $value->kode_barang; ?> <?= $kode_rincian_objek == $value->kode_barang ? 'selected' : ''; ?>><?= $value->kode_rincian_objek . ' - ' . $value->nama_barang; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>


              <div class="form-group">
                <label class="col-sm-2 control-label " for="enum">Kode Sub Rincian Objek</label>
                <div class="col-sm-5">
                  <select class="form-control" name="kode_sub_rincian_objek" id="kode_sub_rincian_objek" data-role="select2">
                    <option value="">Silahkan Pilih</option>
                    <?php if (!empty($sub_rincian_objek)) : ?>
                      <?php foreach ($sub_rincian_objek as $key => $value) : ?>
                        <option value="<?= $value->kode_barang; ?>" <?= $kode_sub_rincian_objek == $value->kode_barang ? 'selected' : ''; ?>> <?= $value->kode_sub_rincian_objek . ' - ' . $value->nama_barang; ?></option>;
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>


              <div class="form-group">
                <label class="col-sm-2 control-label " for="enum">Kode Sub Sub Rincian Objek</label>
                <div class="col-sm-5">
                  <select class="form-control" name="kode_sub_sub_rincian_objek" id="kode_sub_sub_rincian_objek" data-role="select2">
                    <option value="">Silahkan Pilih</option>
                    <?php if (!empty($sub_sub_rincian_objek)) : ?>
                      <?php foreach ($sub_sub_rincian_objek as $key => $value) : ?>
                        <option value="<?= $value->kode_barang; ?>" <?= $kode_sub_sub_rincian_objek == $value->kode_barang ? 'selected' : ''; ?>> <?= $value->kode_sub_sub_rincian_objek . ' - ' . $value->nama_barang; ?></option>;
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="varchar">Barang <?php echo form_error('kode_barang') ?></label>
                <div class="col-sm-3">
                  <input type="hidden" id="kode_barang_old" value="<?php //echo $kode_barang; 
                                                                    ?>" />
                  <input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="Kode Barang" value="<?php //echo $kode_barang; 
                                                                                                                                ?>" readonly />
                </div>
                <div class="col-sm-5">
                  <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Nama Barang" value="<?php //echo $nama_barang; 
                                                                                                                                ?>" readonly />
                </div>
              </div>
            </div>


            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Kata Kunci </label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Kata Kunci" value="<?php //echo $nomor_transaksi; 
                                                                                                                              ?>" />
              </div>
            </div>


            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Pemilik <span class="text-red">*</span></label>
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
                  <?php if ($session->id_role == 1 || $session->id_kode_lokasi == '261' || $kuasa_pengguna->kuasa_pengguna == '*') : ?>
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
            
            <?php /*
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Sub Lokasi </label>
              <div class="col-sm-8">
                <select class="form-control" name="id_sub_kuasa_pengguna" id="id_sub_kuasa_pengguna" data-role="select2">
                  <?php if ($session->id_role == 1) : ?>
                    <?php if ($sub_kuasa_pengguna) : ?>
                      <?php foreach ($sub_kuasa_pengguna_list as $key => $value) : ?>
                        <option value="<?= $value->id_sub_kuasa_pengguna; ?>" <?= ($value->id_sub_kuasa_pengguna == $sub_kuasa_pengguna->id_sub_kuasa_pengguna) ? 'selected' : ''; ?>><?= $value->sub_kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <option value="">Silahkan Pilih</option>

                      <?php
                      if (!empty($sub_kuasa_pengguna_list)) :
                        foreach ($sub_kuasa_pengguna_list as $key => $value) : ?>
                          <option value="<?= $value->id_sub_kuasa_pengguna; ?>"><?= $value->sub_kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                      <?php
                        endforeach;
                      endif; ?>

                    <?php endif; ?>
                    <?php //elseif ( $session->id_role == 2 or $session->id_role == 3 ):
                    ?>

                  <?php else : ?> <?php //selain super admin 
                                  ?>
                    <?php if ($sub_kuasa_pengguna) : ?> <?php //jika sub_kuasa_pengguna ada, maka kunci 
                                                        ?>
                      <option value="<?= $sub_kuasa_pengguna->id_sub_kuasa_pengguna; ?>" selected><?= $sub_kuasa_pengguna->sub_kuasa_pengguna . ' - ' . $sub_kuasa_pengguna->instansi; ?></option>
                    <?php else : ?> <?php //jika sub_kuasa_pengguna tidak ada, tampilkan list 
                                    ?>
                      <option value="">Silahkan Pilih</option>
                      <?php foreach ($sub_kuasa_pengguna_list as $key => $value) : ?>
                        <option value="<?= $value->id_sub_kuasa_pengguna; ?>"><?= $value->sub_kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  <?php endif; ?>
                </select>
              </div>
            </div>
            */ ?>

            <div class="form-group form-inline">
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
            </div>
            <?php /*
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
      */ ?>
            <?php  /*
              <div class="form-group">
                <label for="" class="col-sm-2 control-label">Format </label>
                <div class="col-sm-10" style="padding-top:7px;">
                  <label style="margin-right:10%">
                    <input type="radio" name="format" value="pdf" class="flat-red" checked> Pdf
                  </label>
                  <label>
                    <input type="radio" name="format" value="excel" class="flat-red"> Excel
                  </label>
                </div>
              </div>
               */ ?>



          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <?php //echo anchor(base_url('pencarian/kib/excel'), 'Unduh', 'class="btn btn-primary pull-right"'); 
            ?>  
            <button id="excel" type="submit" class='btn btn-primary pull-right'><span class="fa fa-file"></span>&nbsp&nbspUnduh</button>
          
            <?php /*<input type="submit" name="submit" value="proses" class='btn btn-primary pull-right'>*/ ?>
            <button id="submit" type="button" name="submit" class='btn btn-primary pull-left'><span class="fa fa-refresh"></span>&nbsp&nbspProses</button>
          </div>
          <!-- /.box-footer -->
        </form>


      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">List Data</h3>
        </div>

        <div id="list_a" class="d-none">
          <?php $this->load->view('kib/list_kib_a'); ?>
        </div>
        <div id="list_b" class="d-none">
          <?php $this->load->view('kib/list_kib_b'); ?>
        </div>
        <div id="list_c" class="d-none">
          <?php $this->load->view('kib/list_kib_c'); ?>
        </div>
        <div id="list_d" class="d-none">
          <?php $this->load->view('kib/list_kib_d'); ?>
        </div>
        <div id="list_e" class="d-none">
          <?php $this->load->view('kib/list_kib_e'); ?>
        </div>
        <div id="list_f" class="d-none">
          <?php $this->load->view('kib/list_kib_f'); ?>
        </div>

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

    var var_kode_jenis = document.getElementById("kode_jenis");
    var_kode_jenis.checkValidity()

  });

  $('#submit').on('click', function(e) {
    var var_form = $('#form_pencarian');
    var_form[0].reportValidity();
    e.preventDefault();
    // var var_kode_jenis = $("#kode_jenis");
    // // console.log(var_kode_jenis);
    // var_kode_jenis.event.target.checkValidity();

    $('#list_a').addClass('d-none');
    $('#list_b').addClass('d-none');
    $('#list_c').addClass('d-none');
    $('#list_d').addClass('d-none');
    $('#list_e').addClass('d-none');
    $('#list_f').addClass('d-none');

    if ($('#kode_jenis').val() == '01') {
      $("#mytable_kib_a").DataTable().draw();
      $('#list_a').removeClass('d-none');
    } else if ($('#kode_jenis').val() == '02') {
      $("#mytable_kib_b").DataTable().draw();
      $('#list_b').removeClass('d-none');
    } else if ($('#kode_jenis').val() == '03') {
      $("#mytable_kib_c").DataTable().draw();
      $('#list_c').removeClass('d-none');
    } else if ($('#kode_jenis').val() == '04') {
      $("#mytable_kib_d").DataTable().draw();
      $('#list_d').removeClass('d-none');
    } else if ($('#kode_jenis').val() == '05') {
      $("#mytable_kib_e").DataTable().draw();
      $('#list_e').removeClass('d-none');
    } else if ($('#kode_jenis').val() == '06') {
      $("#mytable_kib_f").DataTable().draw();
      $('#list_f').removeClass('d-none');
    }
  });

  $('#kode_jenis').on('change', function() {
    // console.log('hallo');
    $('#kode_objek').html('<option value="">Please Select</option>');
    $('#kode_rincian_objek').html('<option value="">Please Select</option>');
    $('#kode_sub_rincian_objek').html('<option value="">Please Select</option>');
    $('#kode_sub_sub_rincian_objek').html('<option value="">Please Select</option>');
    var kode_jenis = $(this).val();
    url = base_url + 'global_controller/get_objek/';
    $.post(url, {
      kode_jenis: kode_jenis,
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      // console.log(obj_respon);
      $('#kode_objek').html(obj_respon.option);
    });
    setKodeBarang(this);
  })


  //  BIDANG CLICK
  $('#kode_objek').on('change', function(e) {
    $('#kode_rincian_objek').html('<option value="">Please Select</option>');
    $('#kode_sub_rincian_objek').html('<option value="">Please Select</option>');
    $('#kode_sub_sub_rincian_objek').html('<option value="">Please Select</option>');
    var kode_barang = $(this).val();
    url = base_url + 'global_controller/get_rincian_objek/';
    $.post(url, {
      kode_barang: kode_barang,
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_rincian_objek').html(obj_respon.option);
    });
    setKodeBarang(this);
  });

  $('#kode_rincian_objek').on('change', function(e) {
    $('#kode_sub_rincian_objek').html('<option value="">Please Select</option>');
    $('#kode_sub_sub_rincian_objek').html('<option value="">Please Select</option>');
    var kode_barang = $(this).val();
    url = base_url + 'global_controller/get_sub_rincian_objek/';
    $.post(url, {
      kode_barang: kode_barang,
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_sub_rincian_objek').html(obj_respon.option);
    });
    setKodeBarang(this);
  });

  $('#kode_sub_rincian_objek').on('change', function(e) {
    $('#kode_sub_sub_rincian_objek').html('<option value="">Please Select</option>');
    var kode_barang = $(this).val();
    url = base_url + 'global_controller/get_sub_sub_rincian_objek/';
    $.post(url, {
      kode_barang: kode_barang,
    }, function(respon) {
      obj_respon = jQuery.parseJSON(respon);
      $('#kode_sub_sub_rincian_objek').html(obj_respon.option);
    });
    setKodeBarang(this);
  });

  $('#kode_sub_sub_rincian_objek').on('change', function(e) {
    setKodeBarang(this);
  });


  function setKodeBarang(data) {
    var kode = $(data).val();
    var text = $('option:selected', data).text();
    arr_text = text.split(' - ')[1];
    var length = kode.length;
    if (length == 21) {
      $('#kode_barang').val(kode);
      $('#nama_barang').val(arr_text);
    } else {
      $('#kode_barang').val('');
      $('#nama_barang').val('');
    }
    // getNoRegister();
  }
</script>