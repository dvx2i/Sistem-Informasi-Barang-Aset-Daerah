<style media="screen">
  /* th,
  td {
    white-space: nowrap;
  } */

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
  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-body">
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
        </div>
      </div>
    </div>
  </div>
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
            html: 
              '<b>Jenis SK</b><br><label class="radio-inline"><input type="radio" name="jenis_sk" value="1" checked>Penghapusan</label>' +
              '<label class="radio-inline"><input type="radio" name="jenis_sk" value="2">Urai Catat</label><br><br>' +
              '<label class="radio-inline"><input type="radio" name="jenis_sk" value="3">Lebih Catat</label><br><br>' +
              '<label class="radio-inline"><input type="radio" name="jenis_sk" value="4">Urai Catat Tahun Berjalan</label><br><br>' +
              '<b>Nomor SK</b><input type="text" id="nomor_sk" class="swal2-input">' +
              '<b>Tanggal SK</b><input type="text" id="tanggal_sk" class="swal2-input date_input swal2-overflow">' +
              '<b>File SK</b><br><small>*Tipe File PDF/Image (JPG,JPEG,PNG) max 2mb</small><br><input type="file" id="file_sk" class="swal2-input">' +
              '<b>Tanggal Validasi</b><input type="text" id="tanggal_validasi" class="swal2-input  date_input swal2-overflow">',
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


              // $(".date_input").datepicker().on('changeDate', function (e) {
              //     var pickedMonth = new Date(e.date).getMonth() + 1;
              //     var pickedYear = new Date(e.date).getFullYear();    
              //     // console.log(pickedMonth);
              //     // console.log(pickedYear);      
              //     url = base_url + 'global_controller/cek_stock_opname/';
              //     $.post(url, { bulan: pickedMonth, tahun: pickedYear  }, function (respon) {
              //       obj_respon = jQuery.parseJSON(respon);
              //       // console.log(obj_respon.status);
              //       if(!obj_respon.status){
              //         $(".date_input").datepicker('setDate', new Date()); 
              //         swal({
              //           type: 'error',
              //           title: '!!!',
              //           text: obj_respon.message,
              //           // footer: '<a href>Why do I have this issue?</a>'
              //         });
              //         // return false;
              //       }
              //       // getNoRegister();
              //     });     
              // });
            },
            focusConfirm: false,
            showCancelButton: true,
            cancelButtonText: 'Batalkan',
            confirmButtonText: 'Validasi',
            preConfirm: () => {
              return [
                document.getElementById('tanggal_sk').value,
                document.getElementById('nomor_sk').value,
                document.getElementById('file_sk').files[0],
                document.getElementById('tanggal_sk').value
              ]
            }
          });
          if (dismiss) { //jika cancel
            $(this).prop('checked', false);
            return false;
          };


          var jenis_sk = $("input[type='radio']:checked").val();
          var tanggal_sk = $('#tanggal_sk').val();
          var nomor_sk = $('#nomor_sk').val();
          var file = $('#file_sk')[0].files[0];
          var tanggal_validasi = $('#tanggal_validasi').val();
          formData = new FormData();

          formData.append("jenis_sk", jenis_sk);
          formData.append("file_sk", file);
          formData.append("tanggal_sk", tanggal_sk);
          formData.append("nomor_sk", nomor_sk);
          formData.append("tanggal_validasi", tanggal_validasi);

          // for (var pair of formData.entries()) {
          //   console.log(pair[0] + ', ' + pair[1]);
          // }
          // return false;


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
          if (tanggal_validasi.length == 0 || file_sk == 'undefined') {
            swal({
              type: 'error',
              title: '!!!',
              text: 'Tanggal Validasi / File SK Tidak Boleh Kosong',
              // footer: '<a href>Why do I have this issue?</a>'
            })
            $(this).prop('checked', false); // Checks it
            return false;
          }

          $("#loadMe").modal({
            backdrop: "static", //remove ability to close modal with click
            keyboard: false, //remove option to close with keyboard
            show: true //Display loader!
          });

          var link = base_url + 'penghapusan/penghapusan_barang/validasi_action/' + id_penghapusan_lampiran;
          $.ajax({
            url: link,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function(respon) {
              var obj = jQuery.parseJSON(respon);
              // console.log(obj);

              if (!obj.status) {
                swal({
                  type: 'error',
                  title: '!!!',
                  text: obj.message + ' ' + obj.error_message,
                  // footer: '<a href>Why do I have this issue?</a>'
                })
                input_chek.prop('checked', false); // Checks it
                return false;
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


    $(document).on('click', '.delete', function() {

      var id_penghapusan_lampiran = $(this).attr('id_penghapusan_lampiran');
      // return false;
      swal({
        title: "Apakah kamu yakin?",
        text: "Data yang dipilih akan dihapus!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batalkan'
      }).then((result) => {
        if (result.value) {

          $("#loadMe").modal({
            backdrop: "static", //remove ability to close modal with click
            keyboard: false, //remove option to close with keyboard
            show: true //Display loader!
          });

          url = base_url + 'penghapusan/penghapusan_barang/delete';
          $.post(url, {
            id_penghapusan_lampiran: id_penghapusan_lampiran
          }, function(respon) {
            $("#loadMe").modal("hide");
            obj = jQuery.parseJSON(respon);
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
                text: "Berhasil dihapus!",
                type: 'success',
                // showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya'
              }).then((result) => {
                if (result.value) {
                  location.reload()
                }
              })
            }
            $('#mytable_wrapper').find('li.paginate_button.active>a').click();
            // get_request_validasi();
          });
        } else {}
      })
    })

  });
</script>