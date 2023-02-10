<?php
$session = $this->session->userdata('session');
// die(json_encode($session));
?>
<section class="content-header">
  <div class="row" style="margin-bottom: 10px">
    <div class="col-md-5 text-success">
      <h2 style="margin-top:0px">Daftar <?= $menu ?></h2>
    </div>
    <div class="col-md-2 text-center">
      <div style="margin-top: 4px" id="message">
        <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12  table-responsive ">
      <div class="box box-primary">
        <div class="box-body">
          <div style="margin-bottom: 10px">
            <!-- <div class="row form-group">
              <div class="col-md-5">
                <label>KIB </label>
                <select name="kode_jenis" id="kode_jenis" class="form-control pull-right ">
                  <option value="">Silahkan Pilih</option>
                  <option value="01">KIB A</option>
                  <option value="02">KIB B</option>
                  <option value="03">KIB C</option>
                  <option value="04">KIB D</option>
                  <option value="05">KIB E</option>
                  <option value="06">KIB F</option>
                  <option value="5.03">KIB ATB</option>
                </select>
              </div>
            </div> -->
            <div class="row form-group">
              <div class="col-md-10">
                <label for="">SKPD </label>
                <select class="form-control" name="id_pengguna" id="id_pengguna" data-role="select2">
                  <?php if ($session->id_role == 1) : ?>
                    <option value="">Silahkan Pilih</option>
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
              <div class="col-md-5">
                <label>Semester </label>
                <select name="semester" id="semester" class="form-control pull-right ">
                  <option value="">Silahkan Pilih</option>
                  <option value="1">Semester 1</option>
                  <option value="2">Semester 2</option>
                </select>
              </div>
            </div>
            <div class="row form-group">
              <div class="col-md-5">
                <label>Tahun </label>
                <select name="tahun" id="tahun" class="form-control pull-right ">
                  <?php
                  for ($a = date('Y') - 1; $a <= date('Y'); $a++) {
                    if ($a == date('Y')) {
                      $pilih = "selected";
                    } else {
                      $pilih = "";
                    }
                    echo ("<option value=\"$a\" $pilih>$a</option>" . "\n");
                  }
                  ?>
                </select>
              </div>
              <div class="col-md-12" style="margin-top: 25px;">
                <button type="button" id="btn-save" class="btn btn-primary">Buat</button>
              </div>
            </div>
          </div>
        </div>
        <div class="box-body">
          <div class="col-xs-10">
            <table class="table table-bordered table-striped" id="mytable">
              <thead>
                <tr>
                  <th width="80px">No</th>
                  <th>Unit Kerja</th>
                  <th>Semester</th>
                  <th>Tahun</th>
                  <th width="200px">Aksi</th>
                </tr>
              </thead>

            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal" id="modal-message">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Buat Stock Opname</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="">Bulan</label>
          <select name="bulan" id="bulan" class="form-control pull-right ">
            <?php
            $bulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
            for ($a = 0; $a <= 12; $a++) {
              if ($a == date("m")) {
                $pilih = "selected";
              } else {
                $pilih = "";
              }
              if ($a != 0) {
                echo ("<option value=\"$a\" $pilih>$bulan[$a]</option>" . "\n");
              }
            }
            ?>
          </select>
        </div>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="">Tahun</label>
          <select name="tahun" id="tahun" class="form-control pull-right ">
            <?php
            for ($a = date('Y') - 1; $a <= date('Y'); $a++) {
              if ($a == date('Y')) {
                $pilih = "selected";
              } else {
                $pilih = "";
              }
              echo ("<option value=\"$a\" $pilih>$a</option>" . "\n");
            }
            ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Tutup</a>
        <a href="javascript:;" id="btn-save" class="btn btn-primary">Simpan</a>
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
      },
      // scrollX: true,
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
        "url": "stock_opname_kib/json",
        "type": "POST",
        "data": function(data) {
          data.semester = $('#semester').val();
          data.tahun = $('#tahun').val();
          data.id_pengguna = $('#id_pengguna').val();
        }
      },
      columns: [{
          "data": "id",
          "orderable": false
        },
        {
          "data": "instansi"
        },
        {
          "data": "semester"
        },
        {
          "data": "tahun"
        },
        {
          "data": "action",
          "orderable": false,
          // "className" : "text-center",
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
        if (data['validasi'] == '1') $('td', row).css('background-color', 'white');
        else if (data['validasi'] == '2') $('td', row).css('background-color', 'ghostwhite');
        else if (data['validasi'] == '3') $('td', row).css('background-color', '#F5B7B1  ');
      }
    });

    $('#btn-filter').on('click', function() {
      table = $('#mytable').DataTable();
      table.ajax.reload();

    });

    
    $("#checkbox").click(function(){
        if($("#checkbox").is(':checked') ){
            $("#id_kuasa_pengguna > option").prop("selected","selected");
            $("#id_kuasa_pengguna").trigger("change");
        }else{
            // $("#kolom > option").removeAttr("selected");
            $("#id_kuasa_pengguna").val(null).trigger("change"); 
        }
    });

    $(document).on('click', '#btn-add', function() {
      $('#modal-message').modal('show');
    });
    $(document).on('click', '#btn-save', function() {
      
  if ($('#semester').val() == '') {
    swal('Semester Belum Dipilih!');
    return false;
  }

      swal({
        title: "Buat stock opname KIB?",
        text: "Data KIB akan distockopname",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batalkan'
      }).then((result) => {
        if (result.value) {

          var table = $("#mytable").DataTable();
          semester = $('#semester').val();
          tahun = $('#tahun').val();
          id_pengguna = $('#id_pengguna').val();
          // console.log(TGL_PERMOHONAN);
          
            $.ajax({
              type: "POST", // Method pengiriman data bisa dengan GET atau POST
              url: "<?php echo site_url("laporan/stock_opname_kib/create_action"); ?>",
              data: {
                semester: semester,
                tahun: tahun,
                id_pengguna: id_pengguna
              }, // data yang akan dikirim ke file yang dituju
              dataType: "json",
              beforeSend: function(e) {
                if (e && e.overrideMimeType) {
                  e.overrideMimeType("application/json;charset=UTF-8");
                }
              },
              success: function(response) {

                if (response.status == true) {
                  swal({
                    title: '',
                    text: response.message,
                    icon: 'success'
                  });
                  $('#modal-message').modal('hide');
                  table.ajax.reload();
                } else {
                  swal({
                    title: '',
                    text: response.message,
                    icon: 'warning'
                  });
                }

              },
              error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
              }
            });
        } else {
        }
      })
      

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
        table = $('#mytable').DataTable();
        table.ajax.reload();
      });
    });

    //  pengguna
    $('#id_kuasa_pengguna').change(function(e) {
      table = $('#mytable').DataTable();
      table.ajax.reload();
    });

    $('select[data-role=select2]').select2({
      theme: 'bootstrap',
      width: '100%',
    });
  });
</script>