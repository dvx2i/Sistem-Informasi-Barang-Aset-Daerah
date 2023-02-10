<style media="screen">
  th,
  td {
    white-space: nowrap;
  }

  .select2-selection__rendered {
    margin-top: 1px !important
  }

  /* Disable form  */
  .input_disable {
    pointer-events: none;
    opacity: 0.7;
  }
</style>
<?php 
    $session = $this->session->userdata('session'); ?>
<section class="content">
  <h2 style="margin-top:0px"><?php echo $button ?> Barang Ruang</h2>
  <form action="<?php echo $action; ?>" method="post" id="form_ususlan" autocomplete="off">
   <!-- <div class="form-group">
      <label for="varchar">Ruang Asal</label>

      <select class="form-control" name="id_ruang_lama" id="id_ruang_lama" placeholder="Ruang" data-role="select2">
        <option value="">Silahkan Pilih</option>
                  <?php foreach ($ruang_lama as $key => $value) : ?>
                    <option value="<?= $value->id_ruang; ?>" <?= ($value->id_ruang_lama == '2') ? 'selected' : ''; ?>><?= $value->kode . ' - ' . $value->nama; ?></option>
                  <?php endforeach; ?>
                </select>
    </div> -->
    <div class="form-group">
      <label for="varchar">Pemilik</label>

      <select class="form-control" name="pemilik" id="pemilik" data-role="select2" required>
                  <option value="">Silahkan Pilih</option>
                  <?php foreach ($pemilik as $key => $value) : ?>
                    <option value="<?= $value->id_pemilik; ?>" <?= ($value->id_pemilik == '2') ? 'selected' : ''; ?>><?= $value->kode . ' - ' . $value->nama; ?></option>
                  <?php endforeach; ?>
                </select>
    </div>
    <div class="form-group">
      <label for="varchar">SKPD <?php echo form_error('id_pengguna') ?></label>

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


    <div class="form-group">
      <label for="varchar">Lokasi  <?php /*echo form_error('kode_lokasi_baru')*/ ?></label>

      <select class="form-control" name="id_kuasa_pengguna" id="id_kuasa_pengguna" placeholder="Kode Lokasi" data-role="select2">
        <option value="">Pilih Lokasi</option>
        <?php if (!empty($kuasa_pengguna_list)) : ?>
          <?php foreach ($kuasa_pengguna_list as $key) : ?>
            <option value="<?php echo $key->id_kuasa_pengguna ?>" <?php echo ($key->id_kuasa_pengguna == $id_kuasa_pengguna) ? 'selected' : '' ?>><?php echo remove_star($key->kuasa_pengguna) . ' - ' . $key->instansi ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>
	 <div class="form-group">
      <label for="varchar">Ruang  <?php /*echo form_error('kode_lokasi_baru')*/ ?></label>

      <select class="form-control" name="id_ruangx" id="id_ruang" placeholder="Ruang" data-role="select2">
        <option value="">Pilih Ruang</option>
        
      </select>
    </div>

    <?php /*
    <div class="form-group">
      <label for="varchar">Sub Lokasi Tujuan <?php echo form_error('kode_lokasi_baru') ?></label>
      <select class="form-control" name="kode_lokasi_baru" id="kode_lokasi_baru" placeholder="Kode Lokasi Pengguna Baru" data-role="select2">
        <option value="">Pilih Sub Lokasi</option>
        <?php if (!empty($lokasi)) : ?>
          <?php foreach ($lokasi as $key) : ?>
            <option value="<?php echo $key['id_kode_lokasi'] ?>" <?= ($key['id_kode_lokasi'] == $kode_lokasi_baru) ? 'selected' : '' ?>><?php echo remove_star($key['kode_lokasi']) . ' - ' . $key['instansi'] ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>
 
    <div class="form-group">
      <label for="varchar">Sub Lokasi Tujuan <?php echo form_error('kode_lokasi_baru') ?></label>

      <select class="form-control" name="id_sub_kuasa_pengguna" id="id_sub_kuasa_pengguna" placeholder="" data-role="select2">
        <option value="">Pilih Sub Lokasi</option>
        <?php if (!empty($sub_kuasa_pengguna_list)) : ?>
          <?php foreach ($sub_kuasa_pengguna_list as $key) : ?>
            <option value="<?php echo $key->id_sub_kuasa_pengguna ?>" <?php echo ($key->id_sub_kuasa_pengguna == $id_sub_kuasa_pengguna) ? 'selected' : '' ?>><?php echo remove_star($key->sub_kuasa_pengguna) . ' - ' . $key->instansi ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>*/ ?>



    <!-- <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="">Kode Lokasi Tujuan<?php echo form_error('kode_lokasi_baru') ?></label>
          <input type="text" class="form-control" name="kode_lokasi_baru" id="kode_lokasi_baru" placeholder="" value="<?php echo $kode_lokasi_baru; ?>" readonly />
        </div>
      </div>
    </div> -->

    <label for="varchar">Barang <?php echo form_error('barang') ?></label>

    <div class="row">
      <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
           
            <li class="active"><a href="#tab_kib_b" data-toggle="tab">KIB B</a></li>
            <li><a href="#tab_kib_e" data-toggle="tab">KIB E</a></li>
            <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
          </ul>
          <div class="tab-content">

            <div class="tab-pane active" id="tab_kib_b">
              <b>KIB B</b><span id="span_2"></span>

              <table class="table table-bordered table-striped" id="mytable_kib_b">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>ID KIB</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Nomor Register</th>
                    <th>Merk Type</th>
                    <th>Ukuran (CC)</th>
                    <!--<th>Bahan</th>-->
                    <th>Tahun Pembelian</th>
                    <!--<th>Nomor Pabrik</th>
      <th>Nomor Rangka</th>
      <th>Nomor Mesin</th>-->
                    <th>Nomor Polisi</th>
                    <th>Nomor Bpkb</th>
                    <!--<th>Asal Usul</th>-->
                    <th>Harga</th>
                    <th>Keterangan</th>
                    <!--<th>ID Inventaris</th>-->
                    <!-- <th>Kode Lokasi</th> -->

                  </tr>
                </thead>
              </table>
            </div>

            <div class="tab-pane" id="tab_kib_e">
              <b>KIB E</b><span id="span_5"></span>

              <table class="table table-bordered table-striped" id="mytable_kib_e">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>ID KIB</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Nomor Register</th>
                    <th>Judul Pencipta</th>
                    <!--<th>Spesifikasi</th>
      <th>Kesenian (Asal Daerah)</th>
      <th>Kesenian (Pencipta)</th>
      <th>Kesenian (Bahan)</th>
      <th>Hewan/Tumbuhan (Jenis)</th>
      <th>Hewan/Tumbuhan (Ukuran)</th>-->
                    <th>Tahun Pembelian</th>
                    <!--<th>Asal Usul</th>-->
                    <th>Harga</th>
                    <th>Keterangan</th>
                    <!--<th>ID Inventaris</th>-->

                  </tr>
                </thead>
              </table>
            </div>




            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
      </div>
      <!-- /.col -->

    </div>
    

    <input type="hidden" name="id_ruang" value="<?php echo $id_ruang; ?>" />
    <button type="button" id="simpan" class="btn btn-primary">Simpan</button>
    <a href="<?php echo base_url('master_ruang/pengajuan') ?>" class="btn btn-default">Batalkan</a>
  </form>


</section>

<script src="<?= base_url() ?>/assets/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.checkbox.js"></script>
<script type="text/javascript">
  $('#form_ususlan').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });

  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
    $($.fn.dataTable.tables(true)).DataTable()
      .columns.adjust();
  });

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

    
    $('#simpan').click(function(e) {
      var table = $("#mytable_kib_e").DataTable();
      var rows_selected = table.column(0).checkboxes.selected();
      list_id_e = [];
      $.each(rows_selected, function(index, rowId) {
        list_id_e.push(rowId);
      })


      var table2 = $("#mytable_kib_b").DataTable();
      var rows_selected2 = table2.column(0).checkboxes.selected();
      list_id_b = [];
      $.each(rows_selected2, function(index, rowId) {
        list_id_b.push(rowId);
      })

      // console.log(list_id);
      // return false;
      if (list_id_b.length < 1 && list_id_e.length < 1) {
        swal('Data Belum Dipilih!');
        return false;
      }

      var id_ruang = $('#id_ruang').val();
      var id_pengguna = $('#id_pengguna').val();
      var id_kuasa_pengguna = $('#id_kuasa_pengguna').val();
      var form = "<form id='hidden-form' action='<?php echo $action; ?>' method='post' target='_blank'>";

      form += "<input type='hidden' name='list_id_b' value='" + list_id_b + "'/>";
      form += "<input type='hidden' name='list_id_e' value='" + list_id_e + "'/>";
      form += "<input type='hidden' name='id_ruang' value='" + id_ruang + "'/>";
      form += "<input type='hidden' name='id_pengguna' value='" + id_pengguna + "'/>";
      form += "<input type='hidden' name='id_kuasa_pengguna' value='" + id_kuasa_pengguna + "'/>";

      $(form + "</form>").appendTo($(document.body)).submit();

    });

    //  pengguna
    $('#id_pengguna').change(function(e) {
      $('#id_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
      $('#id_sub_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
      // $('#kode_lokasi_baru').html('<option value="">Silahkan Pilih</option>');
      var id_pengguna = $(this).val();
      // url = base_url + 'mutasi/pengajuan/get_lokasi/';
      url = base_url + 'laporan/get_kuasa_pengguna/';
      $.post(url, {
        id_pengguna: id_pengguna,
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        $('#id_kuasa_pengguna').html(obj_respon.option);
      });
    });


    // kuasa pengguna
    $('#id_kuasa_pengguna').change(function(e) {
      $('#sub_kuasa_pengguna').html('<option value="">Silahkan Pilih</option>');
      var id_pengguna = $('#id_pengguna').val();
      var id_kuasa_pengguna = $(this).val();
      url = base_url + 'master_ruang/pengajuan/get_lokasi_ruang/';
      $.post(url, {
        id_pengguna: id_pengguna,
        id_kuasa_pengguna: id_kuasa_pengguna,
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        $('#id_ruang').html(obj_respon.option);
        //set_lokasi(id_kuasa_pengguna);
      });
/*
      if(id_kuasa_pengguna == '<?= $this->session->userdata('session')->id_kode_lokasi ?>'){
              swal({
                  type: 'error',
                  title: '!!!',
                  text: 'Tidak dapat melakukan mutasi pada lokasi yang sama',
                  // footer: '<a href>Why do I have this issue?</a>'
                })
                $('#id_kuasa_pengguna').select2("val", "");
                $('#kode_lokasi_baru').prop('selectedIndex',0);
      }*/
    });


    // kuasa pengguna
    /*$('#id_sub_kuasa_pengguna').change(function(e) {
      var id_sub_kuasa_pengguna = $(this).val();
      url = base_url + 'master_ruang/pengajuan/get_lokasi_ruang/';
      $.post(url, {
        id_kode_lokasi: id_sub_kuasa_pengguna,
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        $('#id_ruang').html(obj_respon.option);
      });
    });
*/

    /*function set_lokasi(id_kode_lokasi) {
      $('#kode_lokasi_baru').html('<option value="">Kode Lokasi Tujuan</option>');
      url = base_url + 'master_ruang/pengajuan/get_lokasi_ruang/';
      $.post(url, {
        id_kode_lokasi: id_kode_lokasi,
      }, function(respon) {
        obj_respon = jQuery.parseJSON(respon);
        $('#kode_lokasi').html(obj_respon.option);
      });
    }*/

var table_b = $("#mytable_kib_b").dataTable({
      initComplete: function() {
        var api = this.api();
        $('#mytable_kib_b_filter input')
          .off('.DT')
          .on('keyup.DT', function(e) {
            if (e.keyCode == 13) {
              api.search(this.value).draw();
            }
          });

        // $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        //   checkboxClass: 'icheckbox_minimal-blue',
        //   radioClass   : 'iradio_minimal-blue'
        // });

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
        "url": base_url + "master_ruang/json_b",
        "type": "POST",
        "data": function(data) {
          //var list_id_kib_b = sessionStorage.getItem("list_id_kib_b");
          data.id_pengguna = $('#id_pengguna').val();
          data.id_kuasa_pengguna= $('#id_kuasa_pengguna').val();
          data.id_ruang = $('#id_ruang').val();
          
        }
      },
      columns: [{
          "data": "id_kib_b",
          "orderable": false,
          "checkboxes": {
            'selectRow': true
          },
          'width': '5%'
        },
        {
          "data": "id_kib_b",
          "orderable": false
        },
        {
          "data": "kode_barang"
        }, {
          "data": "nama_barang_desc",
          "searchable": false,
          'width': '15%',
        }, {
          "data": "nomor_register"
        },
        {
          "data": "merk_type"
        }, {
          "data": "ukuran_cc"
        }, /*{
          "data": "bahan"
        }, */{
          "data": "tahun_pembelian"
        },
        /*{
          "data": "nomor_pabrik"
        }, {
          "data": "nomor_rangka"
        }, {
          "data": "nomor_mesin"
        },*/
        {
          "data": "nomor_polisi"
        }, {
          "data": "nomor_bpkb"
        },/* {
          "data": "asal_usul"
        }, */{
          "data": "harga"
        },
        {
          "data": "keterangan"
        }, /*{
          "data": "id_inventaris"
        },*/ {
          "data": "nama_barang",
          "visible": false,
        }, {
          "data": "nama_barang_migrasi",
          "visible": false,
        }


      ],

      order: [
        [0, 'desc']
      ],
      rowCallback: function(row, data, iDisplayIndex) {
        // console.log(data['id_kib_b']);
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        // $('td:eq(0)', row).html(index);

      },
    })

    var table_e = $("#mytable_kib_e").dataTable({
      destroy: true,
      initComplete: function() {
        var api = this.api();
        $('#mytable_kib_e_filter input')
          .off('.DT')
          .on('keyup.DT', function(e) {
            if (e.keyCode == 13) {
              api.search(this.value).draw();
            }
          });

        // $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        //   checkboxClass: 'icheckbox_minimal-blue',
        //   radioClass   : 'iradio_minimal-blue'
        // });

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
        "url": base_url + "master_ruang/json_e",
        "type": "POST",
        "data": function(data) {
          data.id_pengguna = $('#id_pengguna').val();
          data.id_kuasa_pengguna= $('#id_kuasa_pengguna').val();
          data.id_ruang = $('#id_ruang').val();
        }
      },
      columns: [{
          "data": "id_kib_e",
          "orderable": false,
          "checkboxes": {
            'selectRow': true
          },
          'width': '5%'
        },{
          "data": "id_kib_e",
          "orderable": false
        }, {
          "data": "kode_barang"
        }, {
          "data": "nama_barang_desc",
          "searchable": false,
          'width': '15%',
        }, {
          "data": "nomor_register"
        },
        {
          "data": "judul_pencipta",
          'width': '20%'
        }, /*{
          "data": "spesifikasi"
        },
         {
          "data": "kesenian_pencipta"
        },
        {
          "data": "kesenian_bahan"
        }, {
          "data": "hewan_tumbuhan_jenis"
        }, {
          "data": "hewan_tumbuhan_ukuran"
        },*/
        <?php /*{"data": "jumlah"},*/ ?> {
          "data": "tahun_pembelian"
        },
         {
          "data": "harga"
        }, {
          "data": "keterangan"
        },  {
          "data": "nama_barang",
          "visible": false,
        }, {
          "data": "nama_barang_migrasi",
          "visible": false,
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
        // $('td:eq(0)', row).html(index);
      }
    })
    
    $('#id_kuasa_pengguna').change(function(e){
      // alert('aa')
      table_b.api().ajax.reload();
      table_e.api().ajax.reload();
    });


  })
</script>