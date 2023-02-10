<style media="screen">
  th,
  td {
    white-space: nowrap;
  }
</style>

<section class="content">
  

  <h2 style="margin-top:0px">Detail Barang ruang</h2>
  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        

        <div class="fullwidth">
          
        </div>

      </div>

    </div>
  </div>
  <form action="<?php echo $action; ?>" method="post" id="form_mutasi" autocomplete="off">
    
   

    <div class="form-group">
      <label for="varchar">Lokasi  <?php echo form_error('kode_lokasi') ?></label>
      <select class="form-control" name="kode_lokasi" id="kode_lokasi" disabled>
        <?php foreach ($lokasi as $key) : ?>
          <option value="<?php echo $key->id_kode_lokasi ?>" <?= ($key->id_kode_lokasi == $id_kode_lokasi) ? 'selected' : '' ?>><?php echo remove_star($key->kode) . ' - ' . $key->instansi ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    


    <label for="varchar">Barang <?php echo form_error('barang') ?></label>

    <div class="row">
      <div class="col-md-12">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            
            <li class="<?= $kib_active[0] == '2' ? 'active' : '';
                        echo isset($kib_hidden['2']) ? 'hidden' : ''; ?>"><a href="#tab_kib_b" data-toggle="tab">KIB B</a></li>
            
            
            <li class="<?= $kib_active[0] == '5' ? 'active' : '';
                        echo isset($kib_hidden['5']) ? 'hidden' : ''; ?>"><a href="#tab_kib_e" data-toggle="tab">KIB E</a></li>
            
           
            <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
          </ul>
          <div class="tab-content">
            
            <div class="tab-pane <?= $kib_active[0] == '2' ? 'active' : ''; ?>" id="tab_kib_b">
              <b>KIB B</b>
              <?php $this->load->view('list_data/list_kib_b'); ?>
            </div>
            
           
            <div class="tab-pane <?= $kib_active[0] == '5' ? 'active' : ''; ?>" id="tab_kib_e">
              <b>KIB E</b>
              <?php $this->load->view('list_data/list_kib_e'); ?>
            </div>
            
            
            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
      </div>
      <!-- /.col -->

    </div>

    <input type="hidden" name="id_ruang_barang" value="<?php echo $id_ruang_barang; ?>" />
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?php echo base_url('list_data/list_data') ?>" class="btn btn-default">Batalkan</a>
  </form>


</section>

<script type="text/javascript">
  var id_mutasi = $('input[name=id_ruang]').val();
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

  //Date picker
  $('.date_input').datepicker({
      format: "dd MM yyyy",
      autoclose: true,
      language: "id",
      locale: 'id',
      todayHighlight: true,
      endDate:'31-12-'+(new Date).getFullYear(),
  });
  
  //Date picker
  $('.date_input_lock').datepicker({
      format: "dd MM yyyy",
      autoclose: true,
      language: "id",
      locale: 'id',
      todayHighlight: true,
      endDate:'31-12-'+(new Date).getFullYear(),
  });
  

  $(".date_input").datepicker().on('changeDate', function (e) {
    var pickedMonth = new Date(e.date).getMonth() + 1;
    var pickedYear = new Date(e.date).getFullYear();    
    // console.log(pickedMonth);
    // console.log(pickedYear);      
    url = base_url + 'global_controller/cek_stock_opname/';
      $.post(url, { bulan: pickedMonth, tahun: pickedYear  }, function (respon) {
        obj_respon = jQuery.parseJSON(respon);
        // console.log(obj_respon.status);
        if(!obj_respon.status){
          $(".date_input").datepicker('setDate', null); 
          swal({
            type: 'error',
            title: '!!!',
            text: obj_respon.message,
            // footer: '<a href>Why do I have this issue?</a>'
          });
          return false;
        }
        // getNoRegister();
      });     
  });
</script>