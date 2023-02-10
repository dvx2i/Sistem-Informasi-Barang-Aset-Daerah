<span id="span_5"></span>

<table class="table table-bordered table-striped" id="mytable_kib_e">
  <thead>
    <tr>
      <th>No</th>
      <th>ID KIB</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Nomor Register</th>
      <th>Judul Pencipta</th>
      <!--<th>Spesifikasi</th>
      <th>Kesenian (Asal Daerah)</th>
      <th>Kesenian (Pencipta)</th>
      <th>Kesenian (Bahan)</th>
      <th>Hewan/Tumbuhan (Jenis)</th>
      <th>Hewan/Tumbuhan (Ukuran)</th>-->
      <th>Tahun Pembelian</th>
      <!--<th>Asal Usul</th>-->
      <th>Harga</th>
      <th>Keterangan</th>
      <!--<th>ID Inventaris</th>-->

    </tr>
  </thead>
</table>

<script src="<?= base_url() ?>/assets/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.checkbox.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    var button = "<input type='button' class='btn btn-success mutasi_e' value='Pilih'>";

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
    
    var table_e = $("#mytable_kib_e").dataTable({
      destroy: true,
      initComplete: function() {
        var api = this.api();
        $('#mytable_kib_e_filter input')
          .off('.DT')
          .on('keyup.DT', function(e) {
            if (e.keyCode == 13) {
              api.search(this.value).draw();
            }
          });

        // $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        //   checkboxClass: 'icheckbox_minimal-blue',
        //   radioClass   : 'iradio_minimal-blue'
        // });

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
        "url": base_url + "laporan/cetak_qrcode/json_e",
        "type": "POST",
        "data": function(data) {
          data.id_ruang_asal = $('#id_ruang').val();
        }
      },
      columns: [{
          "data": "id_kib_e",
          "orderable": false,
          "checkboxes": {
            'selectRow': true
          },
          'width': '5%'
        },{
          "data": "id_kib_e",
          "orderable": false
        }, {
          "data": "kode_barang"
        }, {
          "data": "nama_barang_desc",
          "searchable": false,
          'width': '15%',
        }, {
          "data": "nomor_register"
        },
        {
          "data": "judul_pencipta",
          'width': '20%'
        }, /*{
          "data": "spesifikasi"
        },
         {
          "data": "kesenian_pencipta"
        },
        {
          "data": "kesenian_bahan"
        }, {
          "data": "hewan_tumbuhan_jenis"
        }, {
          "data": "hewan_tumbuhan_ukuran"
        },*/
        <?php /*{"data": "jumlah"},*/ ?> {
          "data": "tahun_pembelian"
        },
         {
          "data": "harga"
        }, {
          "data": "keterangan"
        },  {
          "data": "nama_barang",
          "visible": false,
        }, {
          "data": "nama_barang_migrasi",
          "visible": false,
        }

      ],

      order: [
        [0, 'desc']
      ],
      rowCallback: function(row, data, iDisplayIndex) {
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        // $('td:eq(0)', row).html(index);
      }
    })

// $('#id_ruang').change(function(e){
//   table_e.ajax.reload();
// });

  });
</script>