<style media="screen">
  th, td { white-space: nowrap; }
</style>

  <section class="content">
    <h2 style="margin-top:0px">Form Lampiran</h2>
    <form action="<?php echo $action; ?>" method="post" id="form_penghapusan">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="date">Tanggal Lampiran <?php echo form_error('tanggal_lampiran') ?></label>
            <input type="text" class="form-control date_input" name="tanggal_lampiran" id="tanggal_lampiran" placeholder="Tanggal Lampiran" value="<?php echo $tanggal_lampiran; ?>" />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="date">Keterangan <?php echo form_error('keterangan') ?></label>
            <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" value="<?php echo $keterangan; ?>" />
          </div>
        </div>
      </div>

      <label for="varchar">Barang <?php echo form_error('barang') ?></label>

      <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_kib_a" data-toggle="tab">KIB A</a></li>
              <li><a href="#tab_kib_b" data-toggle="tab">KIB B</a></li>
              <li><a href="#tab_kib_c" data-toggle="tab">KIB C</a></li>
              <li><a href="#tab_kib_d" data-toggle="tab">KIB D</a></li>
              <li><a href="#tab_kib_e" data-toggle="tab">KIB E</a></li>
              <?php /*<li><a href="#tab_kib_f" data-toggle="tab">KIB F</a></li>*/ ?>
              <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_kib_a">
                <b>KIB A</b>
                <?php $this->load->view('laporan/list_kib_a'); ?>
              </div>
              <div class="tab-pane " id="tab_kib_b">
                <b>KIB B</b>
                <?php $this->load->view('laporan/list_kib_b'); ?>
              </div>
              <div class="tab-pane" id="tab_kib_c">
                <b>KIB C</b>
                <?php $this->load->view('laporan/list_kib_c'); ?>
              </div>
              <div class="tab-pane" id="tab_kib_d">
                <b>KIB D</b>
                <?php $this->load->view('laporan/list_kib_d'); ?>
              </div>
              <div class="tab-pane" id="tab_kib_e">
                <b>KIB E</b>
                <?php $this->load->view('laporan/list_kib_e'); ?>
              </div>
              <div class="tab-pane" id="tab_kib_f">
                <b>KIB F</b>
                <?php //$this->load->view('pengajuan/list_kib_f'); ?>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->

      </div>

      <?php /*<input type="hidden" name="id_penghapusan" value="<?php echo $id_penghapusan; ?>" />*/ ?>
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="<?php echo base_url('penghapusan/laporan') ?>" class="btn btn-default">Batalkan</a>
    </form>


  </section>

<script type="text/javascript">
//var id_penghapusan = $('input[name=id_penghapusan]').val();
$('#form_penghapusan').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) {
    e.preventDefault();
    return false;
  }
});

$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
   $($.fn.dataTable.tables(true)).DataTable()
      .columns.adjust();
});


</script>
