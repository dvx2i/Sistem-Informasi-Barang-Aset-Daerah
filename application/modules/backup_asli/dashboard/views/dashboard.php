<section class="content">
  <!-- Small boxes (Stat box) -->



  <!-- <div class="alert alert-success alert-dismissible">
    <a href="https://simulasi-aset.jogjakota.go.id">
      <i class="fa fa-plane"></i><h4>Simulasi Aplikasi ASET V2 </h4>
    </a>
  </div> -->


  <div class="row">
    <div class="col-md-6">
      <?php if((!empty($request_validasi[0][0]['jumlah']) || !empty($request_validasi[1][0]['jumlah']) || !empty($request_validasi[2][0]['jumlah']) || !empty($request_validasi[3][0]['jumlah']) || !empty($request_validasi[4][0]['jumlah']) || !empty($request_validasi[5][0]['jumlah']) || !empty($request_validasi[6][0]['jumlah'])) && in_array($this->session->userdata('session')->id_role, array('10', '1','16'))) : ?>
      <div class="box box-default">
        <div class="box-header with-border">
          <i class="fa fa-info"></i>

          <h3 class="box-title">Notifikasi KIB</h3>
        </div>
        <!-- /.box-header -->
        <!-- <div class="box-body">
          <?php //die(json_encode($request_validasi)) 
          ?>
          <?php if ($validasi) : ?>
            <?php
            if (array_sum($request_validasi) > 0) {
              foreach ($request_validasi as $key => $value) : ?>
                <div class="alert alert-info alert-dismissible">
                  <a href="<?= base_url($value->controller_name . '/validasi'); ?>">
                    <h4><i class="icon fa fa-info"></i> <?= $jenis[$value->kode_jenis]['nama']; ?></h4>
                    <b> <?= $value->jumlah; ?> </b>data yang belum di validasi.
                  </a>
                </div>
            <?php endforeach;
            } ?>
          <?php endif; ?>
        </div> -->

        <div class="box-body">
          <?php 
          //  print_r($request_validasi); die; ?>
          <?php foreach ($request_validasi as $row => $innerArray) { ?>
            <?php foreach ($innerArray as $innerRow => $value) { ?>
              <!-- <?php print_r($value['kode_jenis']) ?> -->
              <?php if (isset($jenis[$value['kode_jenis']])) : ?>
                <?php if($value['jumlah'] > 0) : ?>
                  <div class="alert alert-info alert-dismissible">
                    <a href="<?= base_url($value['controller_name'] . '/validasi'); ?>">
                      <h4><i class="icon fa fa-info"></i> <?= $jenis[$value['kode_jenis']]['nama']; ?></h4>
                      <b> <?= $value['jumlah']; ?> </b>data yang belum di validasi.
                    </a>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            <?php } ?>
          <?php } ?>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
      <?php endif; ?>

      <?php if (($mutasi_penerimaan > 0 && in_array($this->session->userdata('session')->id_role, array('11','12','13', '1'))) || (in_array($this->session->userdata('session')->id_role, array('10', '1','16')) && $mutasi_validasi > 0)) : ?>
      <div class="box box-default">
        <div class="box-header with-border">
          <i class="fa fa-info"></i>

          <h3 class="box-title">Notifikasi Mutasi</h3>
        </div>
        <!-- /.box-header -->

        <div class="box-body">
          <?php //die(json_encode($request_validasi)) 
          ?>
          <?php if ($mutasi_penerimaan > 0) : ?>
            <div class="alert alert-info alert-dismissible">
              <a href="<?= site_url('mutasi/pengecekan_barang'); ?>">
                <h4><i class="icon fa fa-info"></i> Penerimaan Barang</h4>
                <b> <?= $mutasi_penerimaan; ?> </b> data belum dilakukan penerimaan mutasi barang.
              </a>
            </div>
          <?php endif; ?>
          <?php if (in_array($this->session->userdata('session')->id_role, array('10', '1','16'))) : ?>
            <?php if ($mutasi_validasi > 0) : ?>
              <div class="alert alert-info alert-dismissible">
                <a href="<?= site_url('mutasi/validasi_register'); ?>">
                  <h4><i class="icon fa fa-info"></i> Validasi Mutasi</h4>
                  <b> <?= $mutasi_validasi; ?> </b> data belum divalidasi.
                </a>
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
          <?php endif; ?>

      <?php if ($this->session->userdata('session')->id_role == '1') : ?>
        <?php if ($penghapusan_pengecekan > 0) : ?>
          <div class="box box-default">
            <div class="box-header with-border">
              <i class="fa fa-info"></i>

              <h3 class="box-title">Notifikasi Penghapusan</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
              <?php //die(json_encode($request_validasi)) 
              ?>
              <div class="alert alert-info alert-dismissible">
                <a href="<?= site_url('penghapusan/pengecekan_barang'); ?>">
                  <h4><i class="icon fa fa-info"></i> Pengecekan Barang</h4>
                  <b> <?= $penghapusan_pengecekan; ?> </b> data belum dilakukan pengecekan.
                </a>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        <?php endif; ?>
      <?php endif; ?>

      <?php if (in_array($this->session->userdata('session')->id_role, array('10', '1','16'))) : ?>
        <?php if ($kapitalisasi > 0) : ?>
          <div class="box box-default">
            <div class="box-header with-border">
              <i class="fa fa-info"></i>

              <h3 class="box-title">Notifikasi Kapitalisasi</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
              <?php //die(json_encode($request_validasi)) 
              ?>
              <div class="alert alert-info alert-dismissible">
                <a href="<?= site_url('kapitalisasi/validasi'); ?>">
                  <h4><i class="icon fa fa-info"></i> Validasi </h4>
                  <b> <?= $kapitalisasi; ?> </b> data belum divalidasi.
                </a>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        <?php endif; ?>
      <?php endif; ?>

      <?php if (in_array($this->session->userdata('session')->id_role, array('10', '1','16'))) : ?>
        <?php if ($reklas > 0) : ?>
          <div class="box box-default">
            <div class="box-header with-border">
              <i class="fa fa-info"></i>

              <h3 class="box-title">Notifikasi Reklas</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
              <?php //die(json_encode($request_validasi)) 
              ?>
              <div class="alert alert-info alert-dismissible">
                <a href="<?= site_url('reklas/reklas_kode'); ?>">
                  <h4><i class="icon fa fa-info"></i> Validasi </h4>
                  <b> <?= $reklas; ?> </b> data belum divalidasi.
                </a>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        <?php endif; ?>
      <?php endif; ?>
    </div>
    <!-- /.col -->


    <div class='col-md-6'>
      <div class="box box-default">
        <div class="box-header with-border">
          <i class="fa fa-info"></i>
          <h3 class="box-title">Daftar Nominal Data Per KIB </h3>
          <!-- <p>The .table-striped class adds zebra-stripes to a table:</p> -->
        </div>

        <div class="box-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>NO</th>
                <th>KIB</th>
                <th>NOMINAL</th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($nominal_kib as $key => $value) { ?>
                <tr>
                  <td><?php echo 1 + $key; ?></td>
                  <td><?php echo $value['nama_jenis']; ?></td>
                  <td style="text-align: right;"><?php echo number_format($value['harga'], 2); ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>




  <?php /*
  <div class='row'>
    <div class='col-md-12'>
      <figure class="highcharts-figure">
        <div id="container"></div>
        <p class="highcharts-description">
          Chart showing browser market shares. Clicking on individual columns
          brings up more detailed data. This chart makes use of the drilldown
          feature in Highcharts to easily switch between datasets.
        </p>
      </figure>
    </div>
  </div>
 */ ?>








</section>



<script type="text/javascript">
</script>