<style media="screen">
  th, td { white-space: nowrap; }
  .select2-selection__rendered { margin-top: 1px!important }
</style>

  <section class="content">
    <h2 style="margin-top:0px"><?php echo $button ?> Pengajuan</h2>
    <form action="<?php echo $action; ?>" method="post" id="form_ususlan">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="date">Tanggal Pengajuan <?php echo form_error('tanggal_pengajuan') ?></label>
            <input type="text" autocomplete="off" class="form-control date_input" name="tanggal_pengajuan" id="tanggal_pengajuan" placeholder="Tanggal Pengajuan" value="<?php echo $tanggal; ?>" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label style="margin-right:7%">
              <input type="radio" name="jenis" value="nk" class="flat-red" > Non Kendaraan
            </label>
            <label style="margin-right:7%">
              <input type="radio" name="jenis" value="k" class="flat-red" > Kendaraan
            </label>
            <label style="margin-right:7%">
              <input type="radio" name="jenis" value="al" class="flat-red" > Aset Lainnya
            </label>
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
              <li><a href="#tab_kib_f" data-toggle="tab">KIB F</a></li>
			  <li><a href="#tab_kib_atb" data-toggle="tab">KIB ATB</a></li>
              <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane " id="tab_kib_b">
                <b>KIB B</b>
                <?php $this->load->view('pengajuan/list_kib_b'); ?>
              </div>
              <div class="tab-pane active" id="tab_kib_a">
                <b>KIB A</b>
                <?php $this->load->view('pengajuan/list_kib_a'); ?>
              </div>
              <div class="tab-pane" id="tab_kib_c">
                <b>KIB C</b>
                <?php $this->load->view('pengajuan/list_kib_c'); ?>
              </div>
              <div class="tab-pane" id="tab_kib_d">
                <b>KIB D</b>
                <?php $this->load->view('pengajuan/list_kib_d'); ?>
              </div>
              <div class="tab-pane" id="tab_kib_e">
                <b>KIB E</b>
                <?php $this->load->view('pengajuan/list_kib_e'); ?>
              </div>
              <div class="tab-pane" id="tab_kib_f">
                <b>KIB F</b>
                <?php $this->load->view('pengajuan/list_kib_f'); ?>
              </div>
			  <div class="tab-pane" id="tab_kib_atb">
                <b>KIB ATB</b>
                <?php $this->load->view('pengajuan/list_kib_atb'); ?>
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
                <th>ID KIB</th>
                <th>KIB</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Nomor Register</th>
                <th>Merk / Type</th>
                <th>No. Sertifikat, No. Pabrik, No Chasis, No. Mesin</th>
                <th>Bahan</th>
                <th>Asal/Cara Perolehan Barang</th>
                <th>Tahun Perolehan</th>
                <th>Ukuran Barang / konstrukdi (P,S,D)</th>
                <th>Satuan</th>
                <th>Kondisi (B,RR,RB)</th>
                <th>Harga(Rp.)</th>
                <th>Keterangan</th>
                <th>Kode Inventaris</th>

              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
    </div>

      <?php /*<input type="hidden" name="id_penghapusan" value="<?php echo $id_penghapusan; ?>" />*/ ?>
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="<?php echo base_url('penghapusan/pengajuan') ?>" class="btn btn-default">Batalkan</a>
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
