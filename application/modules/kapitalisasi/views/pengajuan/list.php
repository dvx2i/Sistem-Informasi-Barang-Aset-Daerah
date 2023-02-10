<section class="content">
  <div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
      <h2 style="margin-top:0px">Daftar Pengajuan Kapitalisasi</h2>
    </div>

    <div class="col-md-4 text-right">
      <?php echo anchor(base_url('kapitalisasi/pengajuan/create'), 'Buat', 'class="btn btn-primary"'); ?>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-body">
          <table class="table table-bordered table-striped" id="mytable">
            <thead>
              <tr>
                <th width="80px">No</th>
                <th>Jenis</th>
                <th>Tanggal Pengajuan</th>
                <th>Barang</th>
                <th>Nilai Kapitalisasi</th>
                <th>Umur Ekonomis(+)</th>
                <?php if($this->session->userdata('session')->id_role == '1') : ?>
                  <th>Lokasi</th>
                <?php endif; ?>
                <th width="200px">Aksi</th>
              </tr>
            </thead>

          </table>
        </div>
      </div>
    </div>
  </div>
</section>


<script type="text/javascript">
  $(document).ready(function() {
    var arr_kib_name = ['kib_a', 'kib_b', 'kib_c', 'kib_d', 'kib_e', 'kib_f'];
    $.each(arr_kib_name, function(index, value) {
      sessionStorage.removeItem("arr_" + value);
    });
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
        "url": "pengajuan/json_list",
        "type": "POST"
      },
      columns: [{
          "data": "id_kapitalisasi",
          "orderable": false,
          'width': '5%'
        },
        {
          "data": 'jenis',
          "orderable": false,
        },
        {
          "data": "tanggal_pengajuan"
        },

        {
          "data": 'barang',
          "orderable": false,
        },
        {
          "data": 'nilai_kapitalisasi',
          "orderable": false,
        },
        {
          "data": 'penambahan_umur_ekonomis',
          "orderable": false,
        },
                <?php if($this->session->userdata('session')->id_role == '1') : ?>
        {
          "data": 'instansi',
        },
                <?php endif; ?>
        {
          "data": "action",
          "orderable": false,
          // "className" : "text-center"
          'width': '10%'
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