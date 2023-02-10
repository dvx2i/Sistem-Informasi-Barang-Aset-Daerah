<span id="span_5"></span>

<table class="table table-bordered table-striped" id="mytable_kib_e">
  <thead>
    <tr>
      <th width="80px">ID KIB</th>
      <th width="10%">Aksi</th>
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
      <!-- <th>kode_lokasi</th> -->

    </tr>
  </thead>
</table>


<script type="text/javascript">
  $(document).ready(function() {
    // draw_penghapusan_e();
    var button = "<input type='button' class='btn btn-success pengajuan' value='Pilih'>";

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

              $('#mytable_kib_e').on('click', '.pengajuan', function(e) {
                var data = api.row($(this).parents('tr')).data();
                var kode_jenis = '05';
                var id_kib = data['id_kib_e'];
                var _id = kode_jenis + "_" + id_kib;
                var no = $('#temp_kib_e tr').length;
                window.location.href = base_url + 'kapitalisasi/pengajuan/form_kapitalisasi/' + kode_jenis + '/' + id_kib;
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
            "url": base_url + "penghapusan/global_penghapusan/json_kib_e/list_barang",
            "type": "POST"
          },
          columns: [{
              "data": "id_kib_e",
              "orderable": false
            },
            {
              "data": null,
              "orderable": false,
              "className": "text-center",
              'width': '4%',
              "defaultContent": button,
              "searchable": false,
            }, {
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
              "data": "spesifikasi",
          "searchable": false,
            },
            {
              "data": "kesenian_asal_daerah",
              'width': '20%',
          "searchable": false,
            }, {
              "data": "kesenian_pencipta"
            },
            {
              "data": "kesenian_bahan",
          "searchable": false,
            }, {
              "data": "hewan_tumbuhan_jenis"
            }, {
              "data": "hewan_tumbuhan_ukuran",
          "searchable": false,
            },
            <?php /*{"data": "jumlah"},*/ ?> {
              "data": "tahun_pembelian",
          "searchable": false,
            },
            {
              "data": "asal_usul",
              'width': '5%',
          "searchable": false,
            }, {
              "data": "harga"
            }, {
              "data": "keterangan",
          "searchable": false,
            },{
              "data": "id_inventaris"
            },
            // {"data": "kode_lokasi"}
          ],

          order: [
            [0, 'desc']
          ],
          // rowCallback: function(row, data, iDisplayIndex) {
          //   var info = this.fnPagingInfo();
          //   var page = info.iPage;
          //   var length = info.iLength;
          //   var index = page * length + (iDisplayIndex + 1);
          //   $('td:eq(0)', row).html(index);
          // },
          // drawCallback: function(settings) {
          //   $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          //     checkboxClass: 'icheckbox_minimal-blue',
          //     radioClass: 'iradio_minimal-blue'
          //   });
          // }
        });
      

  });
</script>