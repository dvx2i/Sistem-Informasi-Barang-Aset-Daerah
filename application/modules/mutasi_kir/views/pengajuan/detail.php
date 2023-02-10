<section class="content">

  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Progress Mutasi</h3>
        </div>

        <div class="fullwidth">
          <div class="container separator">

            <ul class="progress-tracker progress-tracker--text progress-tracker--text-top" style="opacity: 0.9; margin-top: 5px; margin-bottom: 15px;">
              <?php
              // $class = array('1' => 'is-complete', '2' => 'is-active',  '3' => '',);
              foreach ($list_status_mutasi as $key => $value) :
                // echo $key . "==" . (string)$id_status_mutasi->status_proses;
                if ($key < ($id_status_mutasi->status_proses - 1) or ($id_status_mutasi->status_proses == 5)) :        $class =  'is-complete';
                elseif ($key == ($id_status_mutasi->status_proses - 1)) :    $class =  'is-active';
                else : $class =  '';
                endif;
              ?>
                <li class="progress-step <?= $class ?>">
                  <div class="progress-text">
                    <h4 class="progress-title">Step <?= $key + 1; ?></h4>
                    <?= $value['deskripsi']; ?>
                  </div>
                  <div class="progress-marker"></div>
                </li>
              <?php
              endforeach; ?>

            </ul>

          </div>
        </div>

      </div>

    </div>
  </div>





  <div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
      <h2 style="margin-top:0px">Daftar Pengajuan</h2>
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
        <th>Status Diterima</th>
      </tr>
    </thead>

  </table>
</section>

<input type="hidden" name="" value="<?= $id_mutasi; ?>" id="id_mutasi">
<script type="text/javascript">
  $(document).ready(function() {
    var id_mutasi = $('#id_mutasi').val();
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
        "url": base_url + "mutasi/pengajuan/json_detail/" + id_mutasi,
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
          "data": "id_inventaris",
          "orderable": false,
        },
        {
          "data": 'status_diterima',
          "orderable": false,
          'class': 'text-center'
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