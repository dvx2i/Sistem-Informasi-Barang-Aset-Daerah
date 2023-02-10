<section class="content">
  <div class="row" style="margin-bottom: 10px">
    <div class="col-md-12">
      <h2 class='text-success' style="margin-top:0px">Validasi <?= $menu['nama'] ?></h2>
    </div>
  </div>
  <table class="table table-bordered table-striped" id="mytable">
    <thead>
      <tr>
        <th></th>
        <th>Tanggal Transaksi</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
		 <th>Deskripsi</th>
        <!-- <th>Nomor Register</th> -->
        <th>Lokasi Alamat</th>
        <th>Gedung Tanggal</th>
        <th>Gedung Nomor</th>
        <th>Nomor Kode Tanah</th>
        <th>Harga</th>
        <?php if ($show_lokasi) : ?>
          <th>Lokasi</th>
        <?php endif ?>
        <th width="200px">Aksi</th>
      </tr>
    </thead>

  </table>
</section>

<input type="hidden" id='kode_jenis' value="<?= $kode_jenis; ?>">
<input type="hidden" id='kode_lokasi' value="<?= $id_kode_lokasi; ?>">
<input type="hidden" id='check_all' value="">
<input type="hidden" id='checkbox_validasi_status' value="false">

<div class="modal" id="modal-message">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Validasi</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="">Tanggal Validasi</label>
          <input type="text" name="tanggal_validasi" class="form-control date_input" id="tanggal_validasi">
        </div>
      </div>
      <div class="modal-footer">
        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Tutup</a>
        <a href="javascript:;" id="btn-validasi" class="btn btn-primary">Validasi</a>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="modal-message-all">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Validasi</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="">Tanggal Validasi</label>
          <input type="text" name="tanggal_validasi" class="form-control date_input" id="tanggal_validasi_all">
        </div>
      </div>
      <div class="modal-footer">
        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Tutup</a>
        <a href="javascript:;" id="select_all" class="btn btn-primary">Validasi</a>
      </div>
    </div>
  </div>
</div>

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
        "url": base_url + "kib_c/validasi/json/" + $('#kode_lokasi').val(),
        "type": "POST",
        "data": function(data) {
          data.validasi = $('#checkbox_validasi_status').val();
        }
      },
      columns: [{
          "data": "id_kib_c",
          "orderable": false,
          "checkboxes": {
            'selectRow': true
          },
          'width': '5%'
        },{
          "data": "tanggal_transaksi"
        },{
          "data": "kode_barang",
        }, {
          "data": "nama_barang",
          'width': '20%',
        }, {
          "data": "deskripsi",
          'width': '20%',
        }, {
          "data": "lokasi_alamat",
          'width': '10%',
        }, {
          "data": "gedung_tanggal",
          'width': '10%',
        }, {
          "data": "gedung_nomor"
        }, {
          "data": "nomor_kode_tanah",
          'width': '7%',
        }, {
          "data": "harga"
        },
        <?php if ($show_lokasi) : ?> {
            "data": "instansi"
          },
        <?php endif; ?>
        {
          "data": "action",
          "orderable": false,
          "className": "text-center",
          // 'width': '15%',
        },
      ],
      order: [
        [0, 'desc']
      ],
      rowCallback: function(row, data, iDisplayIndex) {
        // var info = this.fnPagingInfo();
        // var page = info.iPage;
        // var length = info.iLength;
        // var index = page * length + (iDisplayIndex + 1);
        // $('td:eq(0)', row).html(index);
      }
    });
  });
</script>
<script src="<?= base_url('assets/js/validasi.js'); ?>" charset="utf-8"></script>