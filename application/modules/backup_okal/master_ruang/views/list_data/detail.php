<style media="screen">
  table#keterangan>tbody>tr>td {
    border-bottom: 1px solid gainsboro;
  }
</style>


<style media="screen">
  th,
  td {
    white-space: nowrap;
  }

  .switch {
    position: relative;
    display: inline-block;
    width: 40px;
    height: 20px;
  }

  .switch input {
    display: none;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 4px;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 3px;
    bottom: 2px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked+.slider {
    background-color: #2196F3;
  }

  input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked+.slider:before {
    -webkit-transform: translateX(15px);
    -ms-transform: translateX(15px);
    transform: translateX(15px);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  }
</style>



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




  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Detail Barang</h3>
        </div>
        <!-- <div class="row" style="margin-bottom: 10px">
          <div class="col-md-8">
            <h2 style="margin-top:0px">Detail Barang</h2>
          </div>
        </div> -->
        <table class="table" id="keterangan">
          <tr>
            <td>Tanggal Pengajuan</td>
            <td>: <?= $mutasi->tanggal ? tgl_indo($mutasi->tanggal) : ''; ?></td>
          </tr>
          <tr>
            <td>Tanggal BAST</td>
            <td>: <?= $mutasi->tanggal_bast ? tgl_indo($mutasi->tanggal_bast) : ''; ?></td>
          </tr>
          <tr>
            <td>Nomor BAST</td>
            <td>: <?= $mutasi->nomor_bast; ?></td>
          </tr>
          <tr>
            <td>Tanggal Validasi</td>
            <td>: <?= $mutasi->tanggal_validasi ? tgl_indo($mutasi->tanggal_validasi) : ''; ?></td>
          </tr>
          <tr>
            <td>Lokasi Lama</td>
            <td>: <?= $mutasi->pengguna_lama; ?></td>
          </tr>
          <tr>
            <td>Lokasi Baru</td>
            <td>: <?= $mutasi->pengguna_baru; ?></td>
          </tr>
        </table>

        <div class="row" style="margin-bottom:10px;">
          <div class="col-md-12">
            <?php foreach ($mutasi_picture as $key => $value) : ?>
              <div class="<?= 'remove_' . $value->id_mutasi_picture; ?>" style="display: inline-block; margin: 5px;">
                <a id="single_image" href="<?php echo base_url() . $value->url; ?>">
                  <img src="<?php echo base_url() . $value->url; ?>" alt="" style="height: 100px;width: 150px; border:2px solid;" />
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>







  <div class="row">
    <div class="col-md-12">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Daftar Barang</h3>
        </div>
        <table class="table table-bordered table-striped" id="mytable">
          <thead>
            <tr>
              <th width="80px">No</th>
              <th>KIB</th>
              <th>Kode Barang</th>
              <th>Nama Barang</th>
              <th>Nomor Register</th>
              <th>Status Diterima</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

  <?php $_status_validasi = $mutasi->status_validasi == '2' ? 'checked ' : ''; ?>
  <?php if ($mutasi->status_proses == "4") : ?>
    <div class="row">
      <div class="col-md-2">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Validasi Mutasi</h3>
            <label class='switch' style="margin:15px;">
            <input class='live' type='checkbox' id_mutasi='<?= $id_mutasi; ?>' <?= $_status_validasi; ?>">
            <span class='slider round'></span>
          </label>
          </div>
        </div>
      </div>
    </div>
    
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
  <?php endif; ?>
  







</section>

<input type="hidden" name="" value="<?= $id_mutasi; ?>" id="id_mutasi">

<script type="text/javascript">
  $(document).ready(function() {
    var id_mutasi = $('#id_mutasi').val();
    $("a#single_image").fancybox();
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







    <?php /*
    $('input.live').change(async function(e) {
      var id_mutasi = $(this).attr('id_mutasi');
      var input_chek = $(this);
      const {
        dismiss,
        value: text
      } = await swal({
        input: 'text',
        inputPlaceholder: 'Nomor BAST',
        inputValue: '',
        showCancelButton: true,
        confirmButtonText: 'Validasi',
        cancelButtonText: 'Batalkan'
      });

      if (dismiss) { //jika cancel
        // console.log('cancel');
        $(this).prop('checked', false);
        return false;
      };

      if (text.length == 0) {
        swal({
          type: 'error',
          title: '!!!',
          text: 'Nomor BAST Tidak Boleh Kosong',
          // footer: '<a href>Why do I have this issue?</a>'
        })
        $(this).prop('checked', false); // Checks it
        return false;
      }
      // return false;
      // e.preventDefault();
      var link = base_url + 'mutasi/validasi_register/mutasi_action/' + id_mutasi;
      $.ajax({
        url: link,
        type: "POST",
        data: {
          nomor_bast: text
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
            input_chek.prop('checked', false); // Checks it
          } else {

            swal({
              title: 'Berhasil',
              text: "Mutasi Berhasil",
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
    });
     */ ?>

    //Date picker
    $('.date_input').datepicker({
      format: "dd MM yyyy",
      autoclose: true,
      language: "id",
      locale: 'id',
      todayHighlight: true,
      endDate: '31-12-' + (new Date).getFullYear(),
    });
    
    $(document).on('click', 'input.live', function() {
      $('#modal-message').modal('show');
    });
    
    $('#modal-message').on('hidden.bs.modal', function () {
      var input_chek = $('input.live');
      input_chek.prop('checked', false); // Checks it
    })

    $('#btn-validasi').on('click', function(e) {
      var id_mutasi = $('input.live').attr('id_mutasi');
      var input_chek = $('input.live');
      var tanggal_validasi = $('#tanggal_validasi').val();

      // return false;
      // e.preventDefault();
      var link = base_url + 'mutasi/validasi_register/mutasi_action/' + id_mutasi;
      $.ajax({
        url: link,
        type: "POST",
        data: {
          nomor_bast: "",
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
            input_chek.prop('checked', false); // Checks it
          } else {

            swal({
              title: 'Berhasil',
              text: "Mutasi Berhasil",
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
    });













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

        <?php /*
        $('input.live').change(function(e) {
          var id_mutasi = $(this).attr('id_mutasi');

          e.preventDefault();
          var link = base_url + 'mutasi/validasi_register/mutasi_action/' + id_mutasi;
          $.ajax({
            url: link,
            type: "POST",
            data: '',
            success: function(respon) {
              var obj = jQuery.parseJSON(respon);
              // console.log(obj);
              swal({
                title: "Berhasil",
                text: "Mutasi Berhasil",
                type: "success",
                // showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya",
                closeOnConfirm: false,
                html: false
              }, function() {
                location.reload();
              });
            }
          });

        });
*/ ?>

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
        "url": base_url + "mutasi/list_data/json_detail/" + id_mutasi,
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