<style media="screen">
  th,
  td {
    white-space: nowrap;
  }

  .swal2-overflow {
    overflow-x: visible;
    overflow-y: visible;
  }
</style>

<section class="content">
  <div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
      <h2 style="margin-top:0px">Daftar Penghapusan Barang</h2>
    </div>
    <?php /*
      <div class="col-md-4 text-right">
        <?php echo anchor(base_url('penghapusan/penghapusan_barang/create'), 'Hapus Barang', 'class="btn btn-primary"'); ?>
      </div>
      */ ?>
  </div>
  <table class="table table-bordered table-striped" id="mytable">
    <thead>
      <?php /*
        <tr>
          <th width="80px">No</th>
          <th>Tanggal Surat Keterangan(SK)</th>
          <th>Nomor Surat Keterangan(SK)</th>
          <th>Aksi</th>
        </tr>
        */ ?>
      <tr>
        <th width="80px">No</th>
        <th>Tanggal Lampiran</th>
        <th>Keterangan</th>
        <th>Aksi</th>
      </tr>
    </thead>

  </table>
</section>


<script type="text/javascript">
  $(document).ready(function() {
    //remove sessionStorage

    // var arr_kib_name=['kib_a','kib_b','kib_c','kib_d','kib_e','kib_f'];
    // $.each(arr_kib_name, function(index,value){
    //   sessionStorage.removeItem("penghapusan_"+value);
    // });

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


        $('.validasi_penghapusan').click(async function() {
          var a_this = $(this);
          var id_penghapusan_lampiran = a_this.attr('id_penghapusan_lampiran');


          /*const {value: text} = await swal({
            input: 'text',
            inputPlaceholder: 'Nomor BA',
            inputValue: '',
            showCancelButton: true,
            confirmButtonText: 'Validasi',
            cancelButtonText: 'Batalkan'
          })*/

          const {
            dismiss,
            value: formValues
          } = await swal({
            title: 'Validasi Penghapusan',
            html: '<b>Nomor SK</b><input type="text" id="nomor_sk" class="swal2-input">' +
              '<b>Tanggal SK</b><input type="text" id="tanggal_sk" class="swal2-input date_input swal2-overflow">',
            onOpen: function() {
              //Date picker
              $('.date_input').datepicker({
                format: "dd MM yyyy",
                autoclose: true,
                language: "id",
                locale: 'id',
                todayHighlight: true,
                endDate: '31-12-' + (new Date).getFullYear(),
              });
            },
            focusConfirm: false,
            showCancelButton: true,
            cancelButtonText: 'Batalkan',
            confirmButtonText: 'Validasi',
            preConfirm: () => {
              return [
                document.getElementById('tanggal_sk').value,
                document.getElementById('nomor_sk').value
              ]
            }
          });
          if (dismiss) { //jika cancel
            $(this).prop('checked', false);
            return false;
          };

          var tanggal_sk = $('#tanggal_sk').val();
          var nomor_sk = $('#nomor_sk').val();

          if (tanggal_sk.length == 0 || nomor_sk.length == 0) {
            swal({
              type: 'error',
              title: '!!!',
              text: 'Tanggal / Nomor SK Tidak Boleh Kosong',
              // footer: '<a href>Why do I have this issue?</a>'
            })
            $(this).prop('checked', false); // Checks it
            return false;
          }

          var link = base_url + 'penghapusan/penghapusan_barang/validasi_action/' + id_penghapusan_lampiran;
          $.ajax({
            url: link,
            type: "POST",
            data: {
              nomor_sk: nomor_sk,
              tanggal_sk: tanggal_sk
            },
            success: function(respon) {
              var obj = jQuery.parseJSON(respon);
              console.log(obj);

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
                  text: "Penghapusan Berhasil",
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
        "url": "penghapusan_barang/json",
        "type": "POST"
      },
      columns: [{
          "data": "id_penghapusan_lampiran",
          "orderable": false,
          'width': '5%'
        },
        {
          "data": "tanggal_lampiran",
          "orderable": false,
        },
        {
          "data": "keterangan",
          "orderable": false,
        },
        {
          "data": "action",
          "orderable": false,
          // "className" : "text-center",
          'width': '15%'
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
        $('td:eq(0)', row).html(index);
      }
    });
  });
</script>