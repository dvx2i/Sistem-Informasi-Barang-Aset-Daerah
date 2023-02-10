<style media="screen">
  th,
  td {
    white-space: nowrap;
  }
</style>

<section class="content">
  <div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
      <h2 style="margin-top:0px">Daftar Detail Lampiran</h2>
    </div>
  </div>
  <table class="table table-bordered table-striped" id="mytable">
    <thead>
      <tr>
        <th width="80px">No</th>
        <th>KIB</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Nomor Register</th>
        <th>ID Inventaris</th>
      </tr>
    </thead>

  </table>
  <div class="row">
    <div class="col-md-12">
      <a href="<?= base_url('penghapusan/laporan/laporan_detail/' . $id_pengahapusan_lampiran); ?>" class="btn btn-success"> Laporan Excel </a>
      <a href="<?= base_url('penghapusan/laporan/laporan_rekap/' . $id_pengahapusan_lampiran); ?>" class="btn btn-success"> Laporan Rekap Excel </a>
    </div>
  </div>
</section>

<input type="hidden" name="" value="<?= $id_pengahapusan_lampiran; ?>" id="id_pengahapusan_lampiran">



<script type="text/javascript">
  $(document).ready(function() {
    var id_pengahapusan_lampiran = $('#id_pengahapusan_lampiran').val();
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
        "url": base_url + "/penghapusan/laporan/json_detail/" + id_pengahapusan_lampiran,
        "type": "POST",
      },

      columns: [{
          "data": "kode_jenis",
          "orderable": false,
          'width': '5%'
        },
        {
          "data": "kib_name"
        },
        {
          "data": "kode_barang"
        },
        {
          "data": "nama_barang"
        },
        {
          "data": "nomor_register"
        },{
          "data": "id_inventaris"
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
  });
</script>