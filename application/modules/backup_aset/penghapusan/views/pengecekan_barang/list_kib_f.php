<span id="span_6"></span>

<table class="table table-bordered table-striped" id="mytable_kib_f">
  <thead>
    <tr>
      <th width="80px">No</th>
      <th width="200px">Terima</th>
      <th width="200px">Tolak</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Bangunan</th>
      <th>Kontruksi Bertingkat</th>
      <th>Kontruksi Beton</th>
      <th>Luas(M2)</th>
      <th>Lokasi/Alamat</th>
      <th>Dokumen (Tanggal)</th>
      <th>Dokumen (Nomor)</th>
      <th>Tanggal Mulai</th>
      <th>Status Tanah</th>
      <th>Nomor Kode Tanah</th>
      <th>Asal Usul</th>
      <th>Nilai Kontrak</th>
      <th>Deskripsi</th>
      <th>Keterangan</th>
      <th>ID Inventaris</th>
      <th>Kode Lokasi</th>

    </tr>
  </thead>
</table>
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

    var t = $("#mytable_kib_f").dataTable({
      initComplete: function() {
        var api = this.api();
        $('#mytable_kib_f_filter input')
          .off('.DT')
          .on('keyup.DT', function(e) {
            if (e.keyCode == 13) {
              api.search(this.value).draw();
            }
          });

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });

      },
      searching: false,
      paging: false,
      info: false,
      autoWidth: false,
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
        "url": base_url + "penghapusan/global_penghapusan/json_kib_f/pengecekan_barang",
        "type": "POST",
        "data": {
          'id_penghapusan': id_penghapusan,
        },
      },
      columns: [{
          "data": "id_kib_f",
          "orderable": false
        }, {
          "data": "radio_terima"
        },
        {
          "data": "radio_tolak"
        }, {
          "data": "kode_barang",
          'width': '5%'
        }, {
          "data": "nama_barang",
          'width': '20%'
        },
        {
          "data": "bangunan"
        }, {
          "data": "kontruksi_bertingkat"
        }, {
          "data": "kontruksi_beton"
        },
        {
          "data": "luas_m2"
        }, {
          "data": "lokasi_alamat"
        }, {
          "data": "dokumen_tanggal",
          'width': '10%'
        },
        {
          "data": "dokumen_nomor"
        }, {
          "data": "tanggal_mulai",
          'width': '10%'
        },
        {
          "data": "status_tanah",
          'width': '20%'
        }, {
          "data": "nomor_kode_tanah",
          'width': '10%'
        },
        {
          "data": "asal_usul"
        }, {
          "data": "nilai_kontrak"
        },  {
            "data": "deskripsi"
          },{
          "data": "keterangan"
        },{
          "data": "id_inventaris"
        },
        {
          "data": "kode_lokasi"
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
      },
      drawCallback: function(settings) {
        $('input[type="checkbox"].minimal, input[type="radio"].radio_terima').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_flat-green'
        });
        $('input[type="checkbox"].minimal, input[type="radio"].radio_tolak').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_flat-red'
        });
      }

    });
  });
</script>