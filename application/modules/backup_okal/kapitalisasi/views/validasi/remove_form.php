<style media="screen">
  th, td { white-space: nowrap; }
</style>

  <section class="content">
    <h2 style="margin-top:0px">Pengecekan Barang</h2>
    <form action="<?php echo $action; ?>" method="post" id="form_mutasi">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="date">Tanggal Pengajuan<?php echo form_error('tanggal') ?></label>
            <input type="text" class="form-control date_input" name="tanggal" id="tanggal" placeholder="Tanggal" value="<?php echo $tanggal; ?>" disabled />
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="varchar">Lokasi <?php echo form_error('kode_lokasi') ?></label>
        <select class="form-control" name="kode_lokasi" id="kode_lokasi" disabled>
          <?php foreach ($lokasi as $key): ?>
            <option  value="<?php echo $key->id_kode_lokasi ?>" <?= ($key->id_kode_lokasi == $id_kode_lokasi ) ? 'selected' : ''?>><?php echo remove_star($key->kode).' - '.$key->instansi ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="row" style="margin-bottom:10px;">
        <div class="col-md-12">
          <?php foreach ($picture as $key => $value): ?>
            <div class="<?= 'remove_'.$value->id_penghapusan_picture; ?>" style="display: inline-block; margin: 5px;">
              <a id="single_image" href="<?php echo base_url().$value->url ;?>">
                <img src="<?php echo base_url().$value->url ;?>" alt="" style="height: 100px;width: 150px; border:2px solid;" />
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <label for="varchar">Barang <?php echo form_error('barang') ?></label>

      <div class="row">
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="<?= $kib_active[0]=='1'?'active':''; echo isset($kib_hidden['1'])?'hidden':'';?>"><a href="#tab_kib_a" data-toggle="tab">KIB A</a></li>
              <li class="<?= $kib_active[0]=='2'?'active':''; echo isset($kib_hidden['2'])?'hidden':'';?>"><a href="#tab_kib_b" data-toggle="tab">KIB B</a></li>
              <li class="<?= $kib_active[0]=='3'?'active':''; echo isset($kib_hidden['3'])?'hidden':'';?>"><a href="#tab_kib_c" data-toggle="tab">KIB C</a></li>
              <li class="<?= $kib_active[0]=='4'?'active':''; echo isset($kib_hidden['4'])?'hidden':'';?>"><a href="#tab_kib_d" data-toggle="tab">KIB D</a></li>
              <li class="<?= $kib_active[0]=='5'?'active':''; echo isset($kib_hidden['5'])?'hidden':'';?>"><a href="#tab_kib_e" data-toggle="tab">KIB E</a></li>
              <?php /*<li class="<?= $kib_active[0]=='6'?'active':''; echo isset($kib_hidden['6'])?'hidden':'';?>"><a href="#tab_kib_f" data-toggle="tab">KIB F</a></li>*/ ?>
              <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane <?= $kib_active[0]=='1'?'active':''; ?>" id="tab_kib_a">
                <b>KIB A</b>
                <?php $this->load->view('pengecekan_barang/list_kib_a'); ?>
              </div>
              <div class="tab-pane <?= $kib_active[0]=='2'?'active':''; ?>" id="tab_kib_b">
                <b>KIB B</b>
                <?php $this->load->view('pengecekan_barang/list_kib_b'); ?>
              </div>
              <div class="tab-pane <?= $kib_active[0]=='3'?'active':''; ?>" id="tab_kib_c">
                <b>KIB C</b>
                <?php $this->load->view('pengecekan_barang/list_kib_c'); ?>
              </div>
              <div class="tab-pane <?= $kib_active[0]=='4'?'active':''; ?>" id="tab_kib_d">
                <b>KIB D</b>
                <?php $this->load->view('pengecekan_barang/list_kib_d'); ?>
              </div>
              <div class="tab-pane <?= $kib_active[0]=='5'?'active':''; ?>" id="tab_kib_e">
                <b>KIB E</b>
                <?php $this->load->view('pengecekan_barang/list_kib_e'); ?>
              </div>
              <?php /*
              <div class="tab-pane <?= $kib_active[0]=='6'?'active':''; ?>" id="tab_kib_f">
                <b>KIB F</b>
                <?php $this->load->view('pengecekan_barang/list_kib_f'); ?>
              </div>
              */ ?>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->

      </div>

      <input type="hidden" name="id_penghapusan" value="<?php echo $id_penghapusan; ?>" />
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="<?php echo base_url('penghapusan/pengecekan_barang') ?>" class="btn btn-default">Batalkan</a>
    </form>


  </section>

<script type="text/javascript">
var id_penghapusan = $('input[name=id_penghapusan]').val();
$("a#single_image").fancybox();

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
