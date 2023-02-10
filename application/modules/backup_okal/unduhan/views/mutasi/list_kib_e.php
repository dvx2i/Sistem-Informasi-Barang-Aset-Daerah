<span id="span_5"></span>

<table class="table table-bordered table-striped" id="mytable_kib_e">
  <thead>
    <tr>
      <th width="80px">No</th>
      <th width="200px">Terima</th>
      <th width="200px">Tolak</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Nomor Register</th>
      <th>Judul Pencipta</th>
      <th>Spesifikasi</th>
      <th>Kesenian (Asal Daerah)</th>
      <th>Kesenian (Pencipta)</th>
      <th>Kesenian (Bahan)</th>
      <th>Hewan/Tumbuhan (Jenis)</th>
      <th>Hewan/Tumbuhan (Ukuran)</th>
      <?php /*<th>Jumlah</th>*/ ?>
      <th>Tahun Pembelian</th>
      <th>Asal Usul</th>
      <th>Harga</th>
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

    var t = $("#mytable_kib_e").dataTable({
      initComplete: function() {
        var api = this.api();
        $('#mytable_kib_e_filter input')
          .off('.DT')
          .on('keyup.DT', function(e) {
            if (e.keyCode == 13) {
              api.search(this.value).draw();
            }
          });

        // $('#mytable_kib_e').on('ifClicked','.pengajuan', function(e){
        //   var status_checked=$(this).iCheck('update')[0].checked;
        //   var id_mutasi_barang = $(this).attr('id_mutasi_barang');
        //   var status_pengajuan=null;
        //   if (status_checked) status_pengajuan='1';
        //   else status_pengajuan='2';
        //   $('#input_'+id_mutasi_barang).val(status_pengajuan);
        // });

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
        "url": base_url + "mutasi/global_mutasi/json_kib_e/pengecekan_barang",
        "type": "POST",
        "data": {
          'id_mutasi': id_mutasi,
        },
      },
      columns: [{
          "data": "id_kib_e",
          "orderable": false
        },
        {
          "data": "radio_terima"
        },
        {
          "data": "radio_tolak"
        },
        {
          "data": "kode_barang"
        }, {
          "data": "nama_barang",
          'width': '20%'
        }, {
          "data": "nomor_register"
        },
        {
          "data": "judul_pencipta",
          'width': '20%'
        }, {
          "data": "spesifikasi"
        },
        {
          "data": "kesenian_asal_daerah",
          'width': '20%'
        }, {
          "data": "kesenian_pencipta"
        },
        {
          "data": "kesenian_bahan"
        }, {
          "data": "hewan_tumbuhan_jenis"
        }, {
          "data": "hewan_tumbuhan_ukuran"
        },
        {
          "data": "tahun_pembelian"
        }, {
          "data": "asal_usul",
          'width': '5%'
        }, {
          "data": "harga"
        },
        {
          "data": "keterangan"
        },{
          "data": "id_inventaris"
        }, {
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