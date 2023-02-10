<?php
$session = $this->session->userdata('session');
// die(json_encode($session));
?>
<style type="text/css">
  .m-t-3 {
    margin-top: 3px;
    margin-bottom: 3px;
  }
</style>
<style media="screen">
  .select2-selection__rendered {
    margin-top: 1px !important
  }

  /* Disable form  */
  .input_disable {
    pointer-events: none;
    opacity: 0.7;
  }
</style>

<section class="content">

  <section class="content">
    <h2 style="margin-top:0px">Proses Penyusutan</h2>
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
          <form class="form-horizontal" action="<?= base_url('penyusutan/generate/action'); ?>" method="post">
            <div class="box-body">
            <div class="row form-group">
              <div class="col-md-10">
                <label>Pemilik </label>
                <select class="form-control" name="pemilik" id="pemilik" data-role="select2" required>
                  <option value="">Silahkan Pilih</option>
                  <?php foreach ($pemilik as $key => $value) : ?>
                    <option value="<?= $value->id_pemilik; ?>" <?= ($value->id_pemilik == '2') ? 'selected' : ''; ?>><?= $value->kode . ' - ' . $value->nama; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="row form-group">
              <div class="col-md-10">
                <label for="">SKPD </label>
                <select class="form-control" name="id_pengguna" id="id_pengguna" data-role="select2">
                  <?php if ($session->id_role == 1) : ?>
                    <option value="">Silahkan Pilih</option>
                    <?php foreach ($pengguna_list as $key => $value) : ?>
                      <option value="<?= $value->pengguna; ?>" <?= ($value->pengguna == $pengguna->pengguna) ? 'selected' : ''; ?>><?= $value->pengguna . ' - ' . $value->instansi; ?></option>
                    <?php endforeach; ?>
                    <?php //elseif ( $session->id_role == 2 or $session->id_role == 3  ):
                    ?>
                  <?php else : ?>
                    <option value="<?= $pengguna->pengguna; ?>" selected><?= $pengguna->pengguna . ' - ' . $pengguna->instansi; ?></option>
                  <?php endif; ?>
                </select>
              </div>
            </div>

            <div class="row form-group">
              <div class="col-md-10">
                <label for="">Lokasi  </label>
                <select class="form-control" name="id_kuasa_pengguna[]" id="id_kuasa_pengguna" data-role="select2" multiple>
                  <?php if ($session->id_role == 1) : ?>
                    <!-- <option value="">Silahkan Pilih</option> -->
                    <?php if ($kuasa_pengguna) : ?>
                      <?php foreach ($kuasa_pengguna_list as $key => $value) : ?>
                        <option value="<?= $value->id_kuasa_pengguna; ?>" <?= ($value->id_kuasa_pengguna == $kuasa_pengguna->id_kuasa_pengguna) ? 'selected' : ''; ?>><?= $value->kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <!-- <option value="">Silahkan Pilih</option> -->
                      <?php foreach ($kuasa_pengguna_list as $key => $value) : ?>
                        <option value="<?= $value->id_kuasa_pengguna; ?>"><?= $value->kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                    <?php //elseif ( $session->id_role == 2 or $session->id_role == 3 ):
                    ?>
                  <?php else : ?> <?php //selain super admin 
                                  ?>

                    <?php if ($kuasa_pengguna->kuasa_pengguna != '0001' && $kuasa_pengguna->kuasa_pengguna != '*') : ?> <?php //jika kuasa_pengguna ada, maka kunci 
                                                                                                                        ?>
                      <option value="<?= $kuasa_pengguna->id_kuasa_pengguna; ?>" selected><?= $kuasa_pengguna->kuasa_pengguna . ' - ' . $kuasa_pengguna->instansi; ?></option>
                    <?php else : ?> <?php //jika kuasa_pengguna tidak ada, tampilkan list 
                                    ?>
                      <!-- <option value="">Silahkan Pilih</option> -->
                      <?php foreach ($kuasa_pengguna_list as $key => $value) : ?>
                        <option value="<?= $value->id_kuasa_pengguna; ?>"><?= $value->kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>

                  <?php endif; ?>
                </select>
              </div>
                <div class="col-md-3">
                  <!-- <button class="btn btn-xs btn-default" id="checkbox" type="button">
                    Pilih Semua
                  </button> -->
                <input type="checkbox" id="checkbox"> Pilih Semua
                </div>
            </div>
              <div class="row form-group">
              <div class="col-md-10">
                <label>Bulan Penyusutan </label>
                  <div class="input-group m-t-3">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <input id="bulan_tahun" type="text" class="form-control pull-right bulan_tahun_input" name="bulan_tahun" placeholder="" value="<?= $bulan_tahun; ?>" required>
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
              <div class="col-md-12" style="margin-top: 25px;">
                  <input type="hidden" name="tetap_proses" value="<?= $tetap_proses; ?>">
                  <input id="submit" type="submit" name="submit" value="Proses" class='btn btn-primary'>
              </div>
              </div>

            </div>
            
        <div class="box-body">
          <div class="col-xs-10">
            <table class="table table-bordered table-striped" id="mytable">
              <thead>
                <tr>
                  <th width="80px">No</th>
                  <th>Unit Kerja</th>
                  <th>Bulan</th>
                  <th>Tahun</th>
                </tr>
              </thead>

            </table>
          </div>
        </div>
            <?php /*
            <!-- /.box-body -->
            <div class="box-footer">
              <input type="submit" name="submit" value="Proses" class='btn btn-primary pull-left'>
            </div>
            <!-- /.box-footer -->
            */ ?>
          </form>
        </div>

      </div>
    </div>
  </section>

  <script type="text/javascript">
    $(document).ready(function() {
      $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
      return {
        "iStart": oSettings._iDisplayStart,
        "iEnd": oSettings.fnDisplayEnd(),
        "iLength": oSettings._iDisplayLength,
        "iTotal": oSettings.fnRecordsTotal(),
        "iFilteredTotal": oSettings.fnRecordsDisplay(),
        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
      };
    };

    var t = $("#mytable").dataTable({
      initComplete: function() {
        var api = this.api();
        $('#mytable_filter input')
          .off('.DT')
          .on('keyup.DT', function(e) {
            if (e.keyCode == 13) {
              api.search(this.value).draw();
            }
          });
      },
      // scrollX: true,
      oLanguage: {
        sEmptyTable: "Tidak ada data yang tersedia pada tabel ini",
        sProcessing: "Sedang memproses...",
        sLengthMenu: "Tampilkan _MENU_ entri",
        sZeroRecords: "Tidak ditemukan data yang sesuai",
        sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
        sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
        sInfoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
        sInfoPostFix: "",
        sSearch: "Cari:",
        sUrl: "",
        oPaginate: {
          sFirst: "Pertama",
          sPrevious: "Sebelumnya",
          sNext: "Selanjutnya",
          sLast: "Terakhir"
        }
      },
      processing: true,
      serverSide: true,
      ajax: {
        "url": "<?= site_url('penyusutan/generate/json') ?>",
        "type": "POST",
        "data": function(data) {
          data.id_pemilik = $('#pemilik').val();
          data.id_pengguna = $('#id_pengguna').val();
          data.id_kuasa_pengguna = $('#id_kuasa_pengguna').val();
        }
      },
      columns: [{
          "data": "id_penyusutan",
          "orderable": false
        },
        {
          "data": "instansi"
        },
        {
          "data": "bulan"
        },
        {
          "data": "tahun"
        },
        // {
        //   "data": "action",
        //   "orderable": false,
        //   // "className" : "text-center",
        // },
      ],
      order: [
        [0, 'desc']
      ],
      rowCallback: function(row, data, iDisplayIndex) {
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        $('td:eq(0)', row).html(index);
      }
    });

      <?= isset($notiv) ? $notiv : ''; ?>


      $('select[data-role=select2]').select2({
        theme: 'bootstrap',
        width: '25%'
      });

      //Date picker

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

      


      // var today = new Date();
      // var startDate = new Date(today.getFullYear(), 6, 1);
      // var endDate = new Date(today.getFullYear(), 6, 31);



      //Date picker
      $('.tahun_input').datepicker({
        language: "id",
        format: "yyyy",
        // minViewMode: "months",
        minViewMode: "years",
        autoclose: true,
        todayHighlight: true,
        endDate: '12-' + (new Date).getFullYear(), //hanya bulan dan tahun
      });

    //  pengguna
    $('#id_pengguna').change(function(e) {
      $('#id_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
      $('#id_sub_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
      var id_pengguna = $(this).val();
      url = base_url + 'global_controller/get_kuasa_pengguna_by_pengguna/';
      $.post(url, {
        pengguna: id_pengguna,
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        $('#id_kuasa_pengguna').html(obj_respon.option);
        table = $('#mytable').DataTable();
        table.ajax.reload();
      });
    });

    //  pengguna
    $('#id_kuasa_pengguna').change(function(e) {
      table = $('#mytable').DataTable();
      table.ajax.reload();
    });

    $('select[data-role=select2]').select2({
      theme: 'bootstrap',
      width: '100%',
    });
    
    $("#checkbox").click(function(){
        if($("#checkbox").is(':checked') ){
            $("#id_kuasa_pengguna > option").prop("selected","selected");
            $("#id_kuasa_pengguna").trigger("change");
        }else{
            // $("#kolom > option").removeAttr("selected");
            $("#id_kuasa_pengguna").val(null).trigger("change"); 
        }
    });

    });
  </script>