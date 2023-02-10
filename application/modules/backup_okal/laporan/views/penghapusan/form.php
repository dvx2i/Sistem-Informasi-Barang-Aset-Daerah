<style media="screen">
  th,
  td {
    white-space: nowrap;
  }
</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap.min.css">

<section class="content">
  <h2 style="margin-top:0px">Form Lampiran</h2>
  <div class="box box-primary">
    <div class="box-body">
      <form action="<?php echo $action; ?>" method="post" id="form_penghapusan">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="date">Tanggal Lampiran <?php echo form_error('tanggal_lampiran') ?></label>
              <input type="text" class="form-control date_input" autocomplete="off" name="tanggal_lampiran" id="tanggal_lampiran" placeholder="Tanggal Lampiran" value="<?php echo $tanggal_lampiran; ?>" />
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

        <label for="varchar">Paket Barang <?php echo form_error('barang') ?></label>

        <div class="row">
          <div class="col-md-12">
            <table class="table table-bordered table-striped" id="mytable">
              <thead>
                <tr>
                  <th width="80px"></th>
                  <th>Detail</th>
                  <th>Tanggal Pengajuan</th>
                  <th>Nama Paket</th>
                  <th>Lokasi</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <?php /*
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_kib_a" data-toggle="tab">KIB A</a></li>
              <li><a href="#tab_kib_b" data-toggle="tab">KIB B</a></li>
              <li><a href="#tab_kib_c" data-toggle="tab">KIB C</a></li>
              <li><a href="#tab_kib_d" data-toggle="tab">KIB D</a></li>
              <li><a href="#tab_kib_e" data-toggle="tab">KIB E</a></li>
              <li><a href="#tab_kib_f" data-toggle="tab">KIB F</a></li>
              <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
            </ul>
            <div class="tab-content">
              <div  class="tab-pane active" id="tab_kib_a">
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
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
            */ ?>
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <div id="input_lampiran"></div>
              <?php /*<input type="hidden" name="id_penghapusan" value="<?php echo $id_penghapusan; ?>" />*/ ?>
              <button type="button" id="simpan" class="btn btn-primary">Simpan</button>
              <a href="<?php echo base_url('penghapusan/laporan') ?>" class="btn btn-default">Batalkan</a>
            </div>
          </div>
        </div>

    </div>

    </form>
  </div>
  </div>

  <div id="classModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" id="modal-header">

        </div>
        <div class="modal-body table-responsive">

          <table id="detailtable" class="table table-bordered">
            <thead>
              <tr class="no_wrap">
                <th width="80px">No</th>
                <th>KIB</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Nomor Register</th>
                <th>Merk/Type</th>
                <th>Harga</th>
                <th>No.Sertifikat/ No.Pabrik/ No.Chasis/ No.Mesin/ No.Polisi/ No.BPKB</th>
                <th>ID Inventaris</th>
                <th>Asal Usul</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">
            Kembali
          </button>
        </div>
      </div>
    </div>
  </div>

</section>

<script type="text/javascript">
  var table;
  var dataId;
  var detailtable;
  //var id_penghapusan = $('input[name=id_penghapusan]').val();
  $('#form_penghapusan').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });
</script>
<script src="<?= base_url() ?>/assets/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.checkbox.js"></script>
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
      "searching": true,
      // "paging": false,
      "info": true,
      "pageLength": 100,
      "lengthMenu": [
        [50, 100, -1],
        [50, 100, "All"]
      ],
      ajax: {
        "url": base_url + "penghapusan/laporan/json_paket",
        "type": "POST"
      },
      columns: [{
          "data": "id_penghapusan",
          "orderable": false,
          "checkboxes": {
            'selectRow': true
          },
          'width': '5%'
        },

        {
          "data": "action",
          "orderable": false,
          // "className" : "text-center"
        },
        {
          "data": "tanggal"
        },
        {
          "data": 'nama_paket',
          // "orderable": false,
        },
        {
          "data": "instansi"
        }
      ],
      order: [
        [0, 'desc']
      ],
      select: "multiple",
      rowCallback: function(row, data, iDisplayIndex) {
        // var info = this.fnPagingInfo();
        // var page = info.iPage;
        // var length = info.iLength;
        // var index = page * length + (iDisplayIndex + 1);
        // $('td:eq(0)', row).html(index);
      }
    });

    $(document).on('click', '#simpan', function() {

      var table = $("#mytable").DataTable();
      var rows_selected = table.column(0).checkboxes.selected();
      // ids = [];
      $.each(rows_selected, function(index, rowId) {
        var input_lampiran = '<input type="hidden" name="lampiran[]" value="' + rowId + '">';

        $(input_lampiran).appendTo('#input_lampiran');
      })
      // console.log(rows_selected)
      $('#form_penghapusan').submit();

    });

    var detailtable = $('#detailtable').DataTable();

    $(document).on('click', '.detail', function() {
      var id_penghapusan = $(this).data('id');
      var nama_paket = $(this).data('paket');

      detailtable.clear().destroy();

      // console.log(id_penghapusan); return false;
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

      detailtable = $("#detailtable").DataTable({
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
        scrollX: true,
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
          "url": base_url + "/penghapusan/pengajuan/json_detail/" + id_penghapusan,
          "type": "POST"
        },
        columns: [{
            "data": "id_kib",
            "orderable": false,
            'width': '5%'
          },
          {
            "data": "kib_name",
            "orderable": false,
          },
          {
            "data": "kode_barang",
            "orderable": false,
          },
          {
            "data": "nama_barang",
            "orderable": false,
          },
          {
            "data": "nomor_register",
            "orderable": false,
          },
          {
            "data": "merk_type",
            "orderable": false,
          },
          {
            "data": "harga",
            "orderable": false,
          },
          {
            "data": "nomor_nomor",
            "orderable": false,
          },
          {
            "data": "id_inventaris",
            "orderable": false,
          },
          {
            "data": "asal_usul",
            "orderable": false,
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
        }
      });
      
      $('#wkwk').remove();

      var modal_header = '<div id="wkwk"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
      modal_header += '<h4 class="modal-title" id="classModalLabel">Daftar Barang ' + nama_paket + ' </h4></div>';

      $(modal_header).appendTo('#modal-header');
      $('#classModal').modal('show');
    });

  });
</script>