<style media="screen">
  th, td { white-space: nowrap; }
</style>

  <section class="content">
    <div class="row" style="margin-bottom: 10px">
      <div class="col-md-8">
        <h2 style="margin-top:0px">Detail Barang</h2>
      </div>
    </div>
<?php /*
    <table class="table" id="keterangan">
      <tr><td>Tanggal Pengajuan</td><td>: <?=$penghapusan->tanggal_pengajuan?tgl_indo($penghapusan->tanggal_pengajuan):''; ?></td></tr>
    </table>

    <div class="row" style="margin-bottom:10px;">
      <div class="col-md-12">
        <?php foreach ($picture as $key => $value): ?>
          <div class="<?= 'remove_'.$value->id_penghapusan_picture; ?>" style="display: inline-block; margin: 5px;">
            <a id="single_image" href="<?php echo base_url().$value->url ;?>">
              <img src="<?php echo base_url().$value->url ;?>" alt="" style="height: 100px;width: 150px; border:2px solid;" />
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
*/ ?>
    <table class="table table-bordered table-striped" id="mytable">
      <thead>
        <tr>
          <th width="80px">No</th>
          <th>KIB</th>
          <th>Kode Barang Lama</th>
          <th>Nama Barang</th>
          <!-- <th>Barang Baru</th> -->
          <!-- <th>Nama Barang Baru</th> -->
          <th>Nomor Register</th>
          <th>ID Inventaris</th>
          <th>ID KIB</th>
          <th>Status Pengecekan</th>
        </tr>
      </thead>

    </table>
  </section>
<input type="hidden" name="" value="<?=$id_penghapusan; ?>" id='id_penghapusan'>
<script type="text/javascript">
$(document).ready(function() {
  var id_penghapusan=$('#id_penghapusan').val();
  $("a#single_image").fancybox();
  $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings){
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
      sEmptyTable:	 "Tidak ada data yang tersedia pada tabel ini",
      sProcessing:   "Sedang memproses...",
      sLengthMenu:   "Tampilkan _MENU_ entri",
      sZeroRecords:  "Tidak ditemukan data yang sesuai",
      sInfo:         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
      sInfoEmpty:    "Menampilkan 0 sampai 0 dari 0 entri",
      sInfoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
      sInfoPostFix:  "",
      sSearch:       "Cari:",
      sUrl:          "",
      oPaginate: {
        sFirst:    "Pertama",
        sPrevious: "Sebelumnya",
        sNext:     "Selanjutnya",
        sLast:     "Terakhir"
      }
    },
    processing: true,
    serverSide: true,
    ajax: {"url": base_url+"/penghapusan/pengecekan_barang/json_detail/"+id_penghapusan, "type": "POST"},
    columns: [
      {
        "data": "id_kib",
        "orderable": false,
        'width' :'5%'
      },
      {"data": "kib_name","orderable": false,},
      {"data": "kode_barang","orderable": false,},
      {"data": "nama_barang","orderable": false,},
      // {"data": "barang_baru","orderable": false,},
      {"data": "nomor_register","orderable": false,},
        {
          "data": "id_inventaris",
          "orderable": false,
        },{
        "data": "id_kib",
        "orderable": false
      },
      {
        "data" : 'status_penghapusan',
        "orderable": false,
        'class':'text-center'
      },

    ],
    order: [[0, 'desc']],
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
