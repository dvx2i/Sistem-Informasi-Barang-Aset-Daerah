<?php 

$lokasi = explode('.', $this->session->userdata('session')->kode_lokasi);
$pengguna = $lokasi[3];
$kuasa_pengguna =  $lokasi[4];

?>
<!-- <style media="screen">
  th,
  td {
    white-space: nowrap;
  }
</style> -->

<section class="content">
  <div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
      <h2 style="margin-top:0px">Validasi Kapitalisasi</h2>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-body">
          <table class="table table-bordered table-striped" id="mytable">
            <thead>
              <tr>
              <tr>
                <th width="80px">No</th>
                <th>Jenis</th>
                <th>Tanggal Pengajuan</th>
                <th>Barang</th>
                <th>Nilai Perolehan</th>
                <th>Nilai Kapitalisasi</th>
                <th>Umur Ekonomis(+)</th>
                <?php if($kuasa_pengguna == '0001'  || $this->session->userdata('session')->id_role == '1') : ?>
                  <th>Lokasi</th>
                <?php endif; ?>
                <th width="200px">Aksi</th>
              </tr>
              </tr>
            </thead>

          </table>
        </div>
      </div>
    </div>
  </div>
</section>

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
        <button id="btn-validasi" class="btn btn-primary">Validasi</button>
      </div>
    </div>
  </div>
</div>

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





      }, // end enitComplete
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
        "url": "validasi/json",
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
          "data": 'nilai_awal',
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
                <?php if($kuasa_pengguna == '0001' || $this->session->userdata('session')->id_role == '1') : ?>
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


    //Date picker
    $('.date_input').datepicker({
      format: "dd MM yyyy",
      autoclose: true,
      language: "id",
      locale: 'id',
      todayHighlight: true,
      endDate: '31-12-' + (new Date).getFullYear(),
    });



    $(document).on('click', '.validasi_kapitalisasi', function() {
      swal({
        title: "Apakah kamu yakin?",
        text: "Data yang dipilih akan divalidasi!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Validasi',
        cancelButtonText: 'Batalkan'
      }).then((result) => {
        if (result.value) {

          var id_kapitalisasi = $('.validasi_kapitalisasi').attr('id_kapitalisasi');
          var tanggal_validasi = $('#tanggal_validasi').val();
          var link = base_url + 'kapitalisasi/validasi/validasi_action/' + id_kapitalisasi;
          $.ajax({
            url: link,
            type: "POST",
            data: {
              data_post: '',
              tanggal_validasi: tanggal_validasi
            },
            success: function(respon) {
              var obj = jQuery.parseJSON(respon);
              // console.log(obj);

              if (!obj.status) {
                swal({
                  type: 'error',
                  title: '!!!',
                  text: obj.message,
                  // footer: '<a href>Why do I have this issue?</a>'
                })

              } else {
                swal({
                  title: 'Berhasil',
                  text: "Validasi Berhasil",
                  type: 'success',
                  // showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Ya'
                }).then((result) => {
                  if (result.value) {
                    location.reload();
                  }
                })
              }
            }
          });
        }
      })

    });
  });
</script>