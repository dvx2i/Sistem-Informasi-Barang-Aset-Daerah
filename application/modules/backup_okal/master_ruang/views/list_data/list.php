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
  <div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
      <h2 style="margin-top:0px">Daftar Barang Ruang</h2>
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
        <th>Nama Barang</th>
        <th>Lokasi </th>
        <th>Ruang</th>
        <th></th>
        <?php if($this->session->userdata('session')->id_role == '1') : ?>
        <th></th>
        <?php endif; ?>
        <!-- <th>Aksi</th> -->
      </tr>
    </thead>
          <tbody></tbody>
  </table>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?= base_url() ?>/assets/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.checkbox.js"></script>

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
        <?php /*
      $('input.live').change(async function(e){
        var id_mutasi=$(this).attr('id_mutasi');
        var input_chek=$(this);
        const {dismiss,value: text} = await swal({
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
        var link = base_url+'mutasi/validasi_register/mutasi_action/'+id_mutasi;
        $.ajax({
            url: link,
            type: "POST",
            data: {nomor_bast:text},
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
                }else {
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
        "url": "list_data/json",
        "type": "POST"
      },
      columns: [{
          "data": "id_ruang_barang",
          "orderable": false,
          'width': '5%'
        },
        {
          "data": "tanggal"
        },
        {
          "data": "pengguna_lama"
        },
        {
          "data": "pengguna_lama"
        },
        {
          "data": 'detail',
          "orderable": false,
          "className": "text-center",
        },
        <?php if($this->session->userdata('session')->id_role == '1') : ?>
        {
          "data": 'action',
          "orderable": false,
          "className": "text-center",
        },
        <?php endif; ?>
        // {
        //   "data" : 'status_validasi_checkbox',
        //   "orderable": false,
        //   "className" : "text-center",
        // },

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