<?php
$session = $this->session->userdata('session');
// die(json_encode($session));
?>
<section class="content-header">
  <div class="row" style="margin-bottom: 10px">
    <div class="col-md-5 text-success">
      <h2 style="margin-top:0px">Daftar <?= $menu ?></h2>
    </div>
    <div class="col-md-2 text-center">
      <div style="margin-top: 4px" id="message">
        <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12  table-responsive ">
      <div class="box box-primary">
        <div class="box-body">
          <div style="margin-bottom: 10px">
            <div class="row form-group">
              <div class="col-md-5">
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
                <label for="">Lokasi </label>
                <select class="form-control" name="id_kuasa_pengguna" id="id_kuasa_pengguna" data-role="select2">
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

                    <?php if ($kuasa_pengguna->kuasa_pengguna != '0001' && $kuasa_pengguna->kuasa_pengguna != '*'|| $session->id_kode_lokasi == '252') : ?> <?php //jika kuasa_pengguna ada, maka kunci 
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
            </div>
            <div class="row form-group">
              <div class="col-md-5">
                <label>Bulan </label>
                <select name="bulan" id="bulan" class="form-control pull-right ">
                  <!-- <option value="">Silahkan Pilih</option> -->
                  <?php
                  $bulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                  for ($a = 0; $a <= 12; $a++) {
                    if ($a == date("m")) {
                      $pilih = "selected";
                    } else {
                      $pilih = "";
                    }
                    if ($a != 0) {
                      echo ("<option value=\"$a\" $pilih>$bulan[$a]</option>" . "\n");
                    }
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-5">
                <label>Tahun </label>
                <select name="tahun" id="tahun" class="form-control pull-right ">
                  <?php
                  for ($a = date('Y') - 1; $a <= date('Y'); $a++) {
                    if ($a == date('Y')) {
                      $pilih = "selected";
                    } else {
                      $pilih = "";
                    }
                    echo ("<option value=\"$a\" $pilih>$a</option>" . "\n");
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-5">
                <label>Tahap </label>
                <select name="tahap" id="tahap" class="form-control" data-role="select2">
                  <option value="2">2</option>
                  <option value="21">21</option>
                  <option value="4">4</option>
                  <option value="27">27</option>
                  <option value="6">6</option>
                </select>
              </div>
              <div class="col-md-12" style="margin-top: 25px;">
                <button type="button" id="btn-filter" class="btn btn-primary">Filter</button>
                <button type="button" id="btn-sinkron" class="btn btn-warning"><i class="fa fa-sync"></i> Sinkron SIKPD</button>
              </div>
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
                  <th>Nama Kegiatan</th>
                  <th>Kode Rekening</th>
                  <th>Nama Rekening</th>
                  <th>Nilai DPA</th>
                </tr>
              </thead>

            </table>
          </div>
        </div>
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
        "url": "sumber_dana/json",
        "type": "POST",
        "data": function(data) {
          data.id_pemilik = $('#pemilik').val();
          data.id_pengguna = $('#id_pengguna').val();
          data.id_kuasa_pengguna = $('#id_kuasa_pengguna').val();
          data.bulan = $('#bulan').val();
          data.tahun = $('#tahun').val();
        }
      },
      columns: [{
          "data": "id",
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
        {
          "data": "nama_kegiatan"
        },
        {
          "data": "kode_rekening"
        },
        {
          "data": "nama_rekening"
        },
        {
          "data": "nilai_dpa"
        },
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
        // if (data['validasi'] == '1') $('td', row).css('background-color', 'white');
        // else if (data['validasi'] == '2') $('td', row).css('background-color', 'ghostwhite');
        // else if (data['validasi'] == '3') $('td', row).css('background-color', '#F5B7B1  ');
      }
    });

    $('#btn-filter').on('click', function() {
      table = $('#mytable').DataTable();
      table.ajax.reload();

    });


    $("#checkbox").click(function() {
      if ($("#checkbox").is(':checked')) {
        $("#id_kuasa_pengguna > option").prop("selected", "selected");
        $("#id_kuasa_pengguna").trigger("change");
      } else {
        // $("#kolom > option").removeAttr("selected");
        $("#id_kuasa_pengguna").val(null).trigger("change");
      }
    });

    $(document).on('click', '#btn-add', function() {
      $('#modal-message').modal('show');
    });


    $(document).on('click', '#btn-sinkron', function(e) {
      var bulan = $("#bulan").val();
      var tahun = $("#tahun").val();
      var tahap = $("#tahap").val();

      swal({
        title: "Sinkron data sumber dana?",
        text: "Apabila sudah melakukan sinkron pada bulan & tahun yang sama, maka data akan ditimpa.",
        icon: "info",
        buttons: [
          'Batal',
          'Ya'
        ],
        dangerMode: false,
      }).then(function(isConfirm) {
        if (isConfirm) {

          var form = "<form id='hidden-form' action='<?= site_url('laporan/sumber_dana/sinkron') ?>' method='post'>";

              form += "<input type='hidden' name='bulan' value='" + bulan + "'/>";
              form += "<input type='hidden' name='tahun' value='" + tahun + "'/>";
              form += "<input type='hidden' name='tahap' value='" + tahap + "'/>";

          $(form + "</form>").appendTo($(document.body)).submit();
        } else {
          swal("Dibatalkan", "", "error");
        }
      })


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
  });
</script>