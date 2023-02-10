<span id="span_2"></span>

<table class="table table-bordered table-striped" id="mytable_kib_b">
  <thead>
    <tr>
      <th>No</th>
      <th>ID KIB</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Nomor Register</th>
      <th>Merk Type</th>
      <th>Ukuran (CC)</th>
      <!--<th>Bahan</th>-->
      <th>Tahun Pembelian</th>
      <!--<th>Nomor Pabrik</th>
      <th>Nomor Rangka</th>
      <th>Nomor Mesin</th>-->
      <th>Nomor Polisi</th>
      <th>Nomor Bpkb</th>
      <!--<th>Asal Usul</th>-->
      <th>Harga</th>
      <th>Keterangan</th>
      <!--<th>ID Inventaris</th>-->
      <!-- <th>Kode Lokasi</th> -->

    </tr>
  </thead>
</table>

<script src="<?= base_url() ?>/assets/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.checkbox.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    // $('#id_ruang_asal').change(function(e) {
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

    var table_b = $("#mytable_kib_b").dataTable({
      initComplete: function() {
        var api = this.api();
        $('#mytable_kib_b_filter input')
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
        "url": base_url + "laporan/cetak_qrcode/json_b",
        "type": "POST",
        "data": function(data) {
          //var list_id_kib_b = sessionStorage.getItem("list_id_kib_b");
          //data.list_id_kib_b = list_id_kib_b;
          data.id_ruang_asal = $('#id_ruang').val();
          
        }
      },
      columns: [{
          "data": "id_kib_b",
          "orderable": false,
          "checkboxes": {
            'selectRow': true
          },
          'width': '5%'
        },
        {
          "data": "id_kib_b",
          "orderable": false
        },
        {
          "data": "kode_barang"
        }, {
          "data": "nama_barang_desc",
          "searchable": false,
          'width': '15%',
        }, {
          "data": "nomor_register"
        },
        {
          "data": "merk_type"
        }, {
          "data": "ukuran_cc"
        }, /*{
          "data": "bahan"
        }, */{
          "data": "tahun_pembelian"
        },
        /*{
          "data": "nomor_pabrik"
        }, {
          "data": "nomor_rangka"
        }, {
          "data": "nomor_mesin"
        },*/
        {
          "data": "nomor_polisi"
        }, {
          "data": "nomor_bpkb"
        },/* {
          "data": "asal_usul"
        }, */{
          "data": "harga"
        },
        {
          "data": "keterangan"
        }, /*{
          "data": "id_inventaris"
        },*/ {
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
        // console.log(data['id_kib_b']);
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        // $('td:eq(0)', row).html(index);

      },
    })

    
$('#id_ruang').change(function(e){
  // alert('aa')
  table_b.api().ajax.reload();
});


  });
</script>