<?php
$session = $this->session->userdata('session');
// die(json_encode($session));
?>
<section class="content-header">
  <div class="row" style="margin-bottom: 10px">
    <div class="col-md-5 text-success">
      <h2 style="margin-top:0px">Daftar <?= $menu['nama'] ?></h2>
    </div>
    <div class="col-md-2 text-center">
      <div style="margin-top: 4px" id="message">
        <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
      </div>
    </div>
    <div class="col-md-5 text-right">
      <?php echo anchor(base_url('kib_c/create'), 'Buat', 'class="btn btn-primary"'); ?>
      <?php //echo anchor(base_url('kib_a/excel'), 'Excel', 'class="btn btn-primary"'); 
      ?>
    </div>
  </div>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-body">
          <div style="margin-bottom: 10px">
            <div class="row form-group">
              <div class="col-md-5">
                <label>Pemilik </label>
                <select class="form-control" name="pemilik" id="pemilik" data-role="select2" required>
                  <option value="">Silahkan Pilih</option>
                  <?php foreach ($pemilik as $key => $value) : ?>
                    <option value="<?= $value->id_pemilik; ?>" <?= ($value->id_pemilik == '2') ? 'selected' : ''; ?>><?= $value->kode . ' - ' . $value->nama; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="row form-group">
              <div class="col-md-10">
                <label for="">SKPD </label>
                <select class="form-control" name="id_pengguna" id="id_pengguna" data-role="select2">
                  <?php if ($session->id_role == 1) : ?>
                    <option value="">Semua</option>
                    <?php foreach ($pengguna_list as $key => $value) : ?>
                      <option value="<?= $value->pengguna; ?>" <?= ($value->pengguna == $pengguna->pengguna) ? 'selected' : ''; ?>><?= $value->pengguna . ' - ' . $value->instansi; ?></option>
                    <?php endforeach; ?>
                    <?php //elseif ( $session->id_role == 2 or $session->id_role == 3  ):
                    ?>
                  <?php else : ?>
                    <option value="<?= $pengguna->pengguna; ?>" selected><?= $pengguna->pengguna . ' - ' . $pengguna->instansi; ?></option>
                  <?php endif; ?>
                </select>
              </div>
            </div>

            <div class="row form-group">
              <div class="col-md-10">
                <label for="">Lokasi </label>
                <select class="form-control" name="id_kuasa_pengguna" id="id_kuasa_pengguna" data-role="select2">
                  <?php if ($session->id_role == 1) : ?>
                    <option value="">Silahkan Pilih</option>
                    <?php if ($kuasa_pengguna) : ?>
                      <?php foreach ($kuasa_pengguna_list as $key => $value) : ?>
                        <option value="<?= $value->id_kuasa_pengguna; ?>" <?= ($value->id_kuasa_pengguna == $kuasa_pengguna->id_kuasa_pengguna) ? 'selected' : ''; ?>><?= $value->kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <option value="">Silahkan Pilih</option>
                      <?php foreach ($kuasa_pengguna_list as $key => $value) : ?>
                        <option value="<?= $value->id_kuasa_pengguna; ?>"><?= $value->kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                    <?php //elseif ( $session->id_role == 2 or $session->id_role == 3 ):
                    ?>
                  <?php else : ?> <?php //selain super admin 
                                  ?>

                    <?php if ($kuasa_pengguna->kuasa_pengguna != '0001' && $kuasa_pengguna->kuasa_pengguna != '*') : ?> <?php //jika kuasa_pengguna ada, maka kunci 
                                                                                                                        ?>
                      <option value="<?= $kuasa_pengguna->id_kuasa_pengguna; ?>" selected><?= $kuasa_pengguna->kuasa_pengguna . ' - ' . $kuasa_pengguna->instansi; ?></option>
                    <?php else : ?> <?php //jika kuasa_pengguna tidak ada, tampilkan list 
                                    ?>
                      <option value="">Silahkan Pilih</option>
                      <?php foreach ($kuasa_pengguna_list as $key => $value) : ?>
                        <option value="<?= $value->id_kuasa_pengguna; ?>"><?= $value->kuasa_pengguna . ' - ' . $value->instansi; ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>

                  <?php endif; ?>
                </select>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-10">
                <label>Nama Barang </label>
                <select class="form-control" name="id_kode_barang" id="id_kode_barang" data-role="select2" required>
                  <option value="">Silahkan Pilih</option>
                  <?php foreach ($barang as $key => $value) : ?>
                    <option value="<?= $value->id_kode_barang; ?>"><?= $value->kode_barang . ' - ' . $value->nama_barang; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-5">
                <label>Keberadaan Barang </label>
                <select class="form-control" name="status_keberadaan_barang" id="status_keberadaan_barang" data-role="select2" required>
                  <option value="">Silahkan Pilih</option>
                  <option value="1">Ada</option>
                  <option value="0">Tidak Ada</option>
                </select>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-5">
                <label>Kondisi Barang </label>
                <select class="form-control" name="kondisi" id="kondisi" data-role="select2" required>
                  <option value="">Silahkan Pilih</option>
                  <option value="1">Baik</option>
                  <option value="2">Rusak Ringan</option>
                  <option value="3">Rusak Berat</option>
                </select>
              </div>
              <div class="col-md-12" style="margin-top: 25px;">
                <button type="button" id="btn-filter" class="btn btn-primary">Filter</button>
              </div>
            </div>
          </div>
        </div>
        <div class="box-body">
          <table class="table table-bordered table-striped" id="mytable">
            <thead>
              <tr>
                <th width="80px">No</th>
                <th>Keberadaan Barang</th>
                <th>Kondisi Barang</th>
                <th>ID KIB</th>
                <!-- <th>Jenis KIB</th> -->
                <th>Status Validasi</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
				<th>Deskripsi</th>
                <th>Nomor Register</th>
                <th>Kondisi Bangunan</th>
                <th>Bangunan Bertingkat</th>
                <th>Bangunan Beton</th>
                <th>Luas Lantai (M2)</th>
                <th>Lokasi Alamat</th>
                <th>Gedung Tanggal</th>
                <th>Gedung Nomor</th>
                <th>Luas (M2)</th>
                <th>Status</th>
                <th>Nomor Kode Tanah</th>
                <th>Asal Usul</th>
                <th>Harga</th>
                <th>Keterangan</th>
                <th>ID Inventaris</th>
                <?php if ($show_lokasi) : ?>
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

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" action="<?= site_url('pegawai/PegawaiKgb/update/verif') ?>" method="post">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Input Status Barang</h4>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label>Keberadaan Barang</label>
            <select class="form-control select2" style="width: 100%;" id="status_keberadaan">
              <option value="">--Pilih--</option>
              <option value="1">Ada</option>
              <option value="0">Tidak Ada</option>
            </select>
          </div>
          <div class="form-group" style="display: none;" id="input-ket">
            <label>Keterangan Tidak Ada</label>
            <input type="text" class="form-control" id="keterangan_tdk_ada" autocomplete="off" />


          </div>
          <div class="form-group" style="display: none;" id="input-kon">
            <label>Kondisi Barang</label>
            <select class="form-control select2" style="width: 100%;" id="kondisi_barang">
              <option value="">--Pilih--</option>
              <option value="1">Baik</option>
              <option value="2">Rusak Ringan</option>
              <option value="3">Rusak Berat</option>
            </select>
          </div>
          <input type="hidden" id="list_id" value="">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
          <button type="button" id="update-sensus" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

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

          var button_all = "<button id='sensus' class='btn  btn-success ' style='margin-left: 20px;'><i class='fa fa-cubes'></i> Input Status Barang</button>";

        $('div#mytable_length>label').append(button_all);
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
        "url": "kib_c/json",
        "type": "POST",
        "data": function(data) {
          data.id_pemilik = $('#pemilik').val();
          data.id_pengguna = $('#id_pengguna').val();
          data.id_kuasa_pengguna = $('#id_kuasa_pengguna').val();
          data.id_kode_barang = $('#id_kode_barang').val();
          data.status_keberadaan = $('#status_keberadaan_barang').val();
          data.kondisi_barang = $('#kondisi').val();
        }
      },
      columns: [{
          "data": "id_kib_c",
          "orderable": false,
          "checkboxes": {
            'selectRow': true
          },
          "width": "5px"
        },
        {
          "data": "keberadaan",
          "searchable": false
        },
        {
          "data": "kondisi",
          "searchable": false
        },{
          "data": "id_kib_c"
        },
        // {
        //   "data": "jenis"
        // },
        {
          "data": "validasi"
        },
        {
          "data": "kode_barang",
        }, {
          "data": "nama_barang_desc",
          "searchable": false,
          'width': '15%',
        }, {
          "data": "deskripsi",
          "searchable": false,
          'width': '15%',
        },
        {
          "data": "nomor_register"
        }, {
          "data": "kondisi_bangunan"
        }, {
          "data": "bangunan_bertingkat"
        }, {
          "data": "bangunan_beton"
        }, {
          "data": "luas_lantai_m2",
          // 'width': '7%',
        }, {
          "data": "lokasi_alamat",
          // 'width': '10%',
        }, {
          "data": "gedung_tanggal",
          // 'width': '10%',
        }, {
          "data": "gedung_nomor",
          // 'width': '5%',
        }, {
          "data": "luas_m2"
        }, {
          "data": "status",
          // 'width': '15%',
        }, {
          "data": "nomor_kode_tanah",
          // 'width': '7%',
        }, {
          "data": "asal_usul"
        }, {
          "data": "harga"
        }, {
          "data": "keterangan",
          // 'width': '20%'
        }, {
          "data": "id_inventaris",
          // 'width': '20%'
        },
        <?php if ($show_lokasi) : ?> {
            "data": "instansi",
            // 'width': '20%'
          },
        <?php endif; ?> {
          "data": "action",
          "orderable": false,
          // "className" : "text-center",
          'width': '15%',
        }, {
          "data": "nama_barang",
          "visible": false,
        }, {
          "data": "nama_barang2",
          "visible": false,
        },  {
          "data": "nama_barang_migrasi",
          "visible": false,
        }
      ],
      order: [
        [0, 'desc']
      ],
      rowCallback: function(row, data, iDisplayIndex) {
        // var info = this.fnPagingInfo();
        // var page = info.iPage;
        // var length = info.iLength;
        // var index = page * length + (iDisplayIndex + 1);
        // $('td:eq(0)', row).html(index);
        if (data['status_validasi'] == '1') $('td', row).css('background-color', 'white');
        else if (data['status_validasi'] == '2') $('td', row).css('background-color', 'ghostwhite');
        else if (data['status_validasi'] == '3') $('td', row).css('background-color', '#F5B7B1  ');
      }
    });
    $('#btn-filter').on('click', function() {
      table = $('#mytable').DataTable();
      table.ajax.reload();

    });


    //  pengguna
    $('#id_pengguna').change(function(e) {
      $('#id_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
      $('#id_sub_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
      var id_pengguna = $(this).val();
      url = base_url + 'global_controller/get_kuasa_pengguna_by_pengguna/';
      $.post(url, {
        pengguna: id_pengguna,
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        $('#id_kuasa_pengguna').html(obj_respon.option);
      });
    });

    $('select[data-role=select2]').select2({
      theme: 'bootstrap',
      width: '100%',
    });
  });
  

  $(document).on('click', '#sensus', function() {
    var table = $("#mytable").DataTable();
    var rows_selected = table.column(0).checkboxes.selected();
    list_id = [];
    $.each(rows_selected, function(index, rowId) {
      list_id.push(rowId);
    })

    // console.log(list_id);
    // return false;
    if (list_id.length < 1) {
      swal('Data Belum Dipilih!');
      return false;
    }


    $('#myModal').modal('toggle');
    $('#myModal').modal('show');
  })


  $(document).on('click', '#update-sensus', function() {
    var table = $("#mytable").DataTable();
    var rows_selected = table.column(0).checkboxes.selected();
    list_id = [];
    $.each(rows_selected, function(index, rowId) {
      list_id.push(rowId);
    })

    // console.log(list_id);
    // return false;
    if (list_id.length < 1) {
      swal('Data Belum Dipilih!');
      return false;
    }

    
    $('#myModal').modal('toggle');
    $('#myModal').modal('hide');

    $("#loadMe").modal({
      backdrop: "static", //remove ability to close modal with click
      keyboard: false, //remove option to close with keyboard
      show: true //Display loader!
    });

    url = base_url + 'sensus/kib_c/update_sensus/';
    // $.post(url, { list_id: list_id,tanggal_validasi: tanggal_validasi }, function (respon) {

    $.ajax({
      url: url,
      type: "POST",
      data: {
        list_id: list_id,
        status_keberadaan: $('#status_keberadaan').val() ,
        keterangan_tdk_ada: $('#keterangan_tdk_ada').val(),
        kondisi_barang: $('#kondisi_barang').val()
      },
      dataType: "json",
      success: function(respon) {

        $("#loadMe").modal("hide");
        // obj = jQuery.parseJSON(respon);
        obj = respon;
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
              var api = $("#mytable").DataTable();
              api.ajax.reload();
            }
          })
        }
        $('#mytable_wrapper').find('li.paginate_button.active>a').click();
        // get_request_validasi();
      },
      error: function(xhr, textStatus, errorThrown) {
        console.log(xhr);
        if (xhr.responseText == 'session timeout') {
          alert('Sesi anda telah habis. Silahkan LOGIN kembali');
          window.location = "<?= site_url('Akun') ?>";
        } else {
          swal({
            type: 'error',
            title: '',
            text: 'Terjadi Kesalahan Sistem',
            // footer: '<a href>Why do I have this issue?</a>'
          })
        }
      }
    });
  })


  $('#status_keberadaan').change(function(e) {

    if ($(this).val() == '1') {

      $('#input-kon').show()
      $('#input-ket').hide()
    } else {

      $('#input-kon').hide()
      $('#input-ket').show()
    }
  })

  $(document).on('click', '#delete', function() {

    var table = $("#mytable").DataTable();
    var rows_selected = table.column(0).checkboxes.selected();
    list_id = [];
    $.each(rows_selected, function(index, rowId) {
      list_id.push(rowId);
    })

    // console.log(list_id);
    // return false;
    if (list_id.length < 1) {
      swal('Data Belum Dipilih!');
      return false;
    }
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

        url = base_url + 'kib_c/delete_all/';
        $.post(url, {
          list_id: list_id
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
</script>