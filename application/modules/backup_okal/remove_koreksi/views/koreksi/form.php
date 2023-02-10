<style media="screen">
  th, td { white-space: nowrap; }
  .select2-selection__rendered { margin-top: 1px!important }
</style>

  <section class="content">
    <h2 style="margin-top:0px"><?php echo $button ?> Pengajuan</h2>
    <form action="<?php echo $action; ?>" method="post" id="form_ususlan">
      <label for="varchar">Barang <?php echo form_error('barang') ?></label>

      <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_kib_b" data-toggle="tab">KIB B</a></li>
              <li><a href="#tab_kib_c" data-toggle="tab">KIB C</a></li>
              <li><a href="#tab_kib_d" data-toggle="tab">KIB D</a></li>
              <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_kib_b">
                <b>KIB B</b>
                <?php $this->load->view('/koreksi/list_kib_b'); ?>
              </div>
              <div class="tab-pane" id="tab_kib_c">
                <b>KIB C</b>
                <?php $this->load->view('/koreksi/list_kib_c'); ?>
              </div>
              <div class="tab-pane" id="tab_kib_d">
                <b>KIB D</b>
                <?php $this->load->view('/koreksi/list_kib_d'); ?>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->

      </div>

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

$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
   $($.fn.dataTable.tables(true)).DataTable()
      .columns.adjust();
});


</script>
