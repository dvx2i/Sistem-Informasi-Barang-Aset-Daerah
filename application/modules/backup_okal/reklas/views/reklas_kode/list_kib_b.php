
<b>KIB B</b>
<span id="span_2"></span>

<table class="table table-bordered table-striped" id="mytable_kib_b">
  <thead>
    <tr>
      <th width="80px">No</th>
      <th width="200px">Aksi</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Nomor Register</th>
      <th>Merk Type</th>
      <th>Ukuran (CC)</th>
      <th>Bahan</th>
      <th>Tahun Pembelian</th>
      <th>Nomor Pabrik</th>
      <th>Nomor Rangka</th>
      <th>Nomor Mesin</th>
      <th>Nomor Polisi</th>
      <th>Nomor Bpkb</th>
      <th>Asal Usul</th>
      <th>Harga</th>
      <th>Keterangan</th>
      <th>ID Inventaris</th>
      <!-- <th>Kode Lokasi</th> -->

    </tr>
  </thead>
</table>

<br>
<?php /*
<div class="external-event bg-yellow ui-draggable ui-draggable-handle">
  <label for=""> Barang Yang di Pilih </label>
</div>

<div class="table-responsive">
  <table class="table table-bordered table-striped" id="temp_kib_b">
    <thead>
      <tr>
        <th width="80px">No</th>
        <th width="200px">Aksi</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Nomor Register</th>
        <th>Merk Type</th>
        <th>Ukuran (CC)</th>
        <th>Bahan</th>
        <th>Tahun Pembelian</th>
        <th>Nomor Pabrik</th>
        <th>Nomor Rangka</th>
        <th>Nomor Mesin</th>
        <th>Nomor Polisi</th>
        <th>Nomor Bpkb</th>
        <th>Asal Usul</th>
        <th>Harga</th>
        <th>Keterangan</th>

      </tr>
    </thead>
    <tbody>

    </tbody>
  </table>
</div>
 */ ?>
<script type="text/javascript">
  $(document).ready(function() {
	  $("#loadMe").modal("hide");
	 $('.modal-backdrop').remove();
    draw_mutasi_b();
    var button = "<input type='button' class='btn btn-success mutasi' value='Pilih'>";
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

    var t = $("#mytable_kib_b").dataTable({
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
          $('.modal-backdrop').remove();

        $('#mytable_kib_b').on('click', '.mutasi', function(e) {
          var data = api.row($(this).parents('tr')).data();
          var kode_jenis = '02';
          var id_kib = data['id_kib_b'];
          var _id = kode_jenis + "_" + id_kib;
          var no = $('#temp_kib_b tr').length;

          //////////////////////////
          set_list_id_kib_b(id_kib);
          //////////////////////////

          var arr_kib_b = {};
          var temp = JSON.parse(sessionStorage.getItem("arr_kib_b"));

          if (temp != null) { // bila belum ada array
            arr_kib_b = temp;
            arr_kib_b[_id] = data;
          } else {
            arr_kib_b[_id] = data;
          }
          sessionStorage.setItem("arr_kib_b", JSON.stringify(arr_kib_b));
          draw_mutasi_b();
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
        "url": base_url + "reklas/global_reklas/json_kib_b/list_barang",
        "type": "POST",
        "data": function(data) {
          var list_id_kib_b = sessionStorage.getItem("list_id_kib_b");
          data.list_id_kib_b = list_id_kib_b;
        }
      },
      columns: [{
          "data": "id_kib_b",
          "orderable": false
        },
        // {
        //   "data": null,
        //   "orderable": false,
        //   "className": "text-center",
        //   'width': '4%',
        //   "defaultContent": button,
        //   "searchable": false,
        // },
        {
          "data": "aksi",
          "orderable": false,
          "searchable": false,
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
        }, {
          "data": "bahan"
        }, {
          "data": "tahun_pembelian"
        },
        {
          "data": "nomor_pabrik"
        }, {
          "data": "nomor_rangka"
        }, {
          "data": "nomor_mesin"
        },
        {
          "data": "nomor_polisi"
        }, {
          "data": "nomor_bpkb"
        }, {
          "data": "asal_usul"
        }, {
          "data": "harga"
        },
        {
          "data": "keterangan"
        },{
          "data": "id_inventaris"
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
        // console.log(data['id_kib_b']);
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        $('td:eq(0)', row).html(index);

      },
      drawCallback: function(settings) {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
      }
    });

    $('#temp_kib_b').on('click', '.mutasi', function(e) {
      var attr_id = $(this).attr('attr_id');

      var id_kib_b = attr_id.split("_")[1];
      remove_list_id_kib_b(id_kib_b);

      var arr_temp = JSON.parse(sessionStorage.getItem("arr_kib_b"));
      delete arr_temp[attr_id];
      sessionStorage.setItem("arr_kib_b", JSON.stringify(arr_temp));
      draw_mutasi_b()
    })


    function draw_mutasi_b() {
      var arr_temp = JSON.parse(sessionStorage.getItem("arr_kib_b"));
      var no = 0;
      $('.hapus_temp_kib_b').remove(); //kosongkan temporary
      $.each(arr_temp, function(index, data) {
        no = no + 1;
        var kode_jenis = '02';
        var id_kib = data['id_kib_b'];
        var _id = kode_jenis + "_" + id_kib;

        var button_hapus = "<input type='button' class='btn btn-danger mutasi' value='Hapus' attr_id='" + _id + "'>";
        var input_lampiran = '<input class="' + _id + ' hapus_temp_kib_a" type="hidden" name="pengajuan[' + kode_jenis + '][]" value="' + id_kib + '">';
        var tr =
          "<tr class='" + _id + " hapus_temp_kib_b'>" +
          "<td>" + no + "</td>" +
          "<td widtd='200px' class='text-center'>" + button_hapus + " " + input_lampiran + "</td>" +
          "<td>" + data['kode_barang'] + "</td>" +
          "<td>" + data['nama_barang'] + "</td>" +
          "<td>" + data['nomor_register'] + "</td>" +
          "<td>" + data['merk_type'] + "</td>" +
          "<td>" + data['ukuran_cc'] + "</td>" +
          "<td>" + data['bahan'] + "</td>" +
          "<td>" + data['tahun_pembelian'] + "</td>" +
          "<td>" + data['nomor_pabrik'] + "</td>" +
          "<td>" + data['nomor_rangka'] + "</td>" +
          "<td>" + data['nomor_mesin'] + "</td>" +
          "<td>" + data['nomor_polisi'] + "</td>" +
          "<td>" + data['nomor_bpkb'] + "</td>" +
          "<td>" + data['asal_usul'] + "</td>" +
          "<td>" + data['harga'] + "</td>" +
          "<td>" + data['keterangan'] + "</td>" +

          "</tr>";

        $(tr).appendTo('#temp_kib_b');

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
      });
    }




    function set_list_id_kib_b(id_kib_b) {
      // sessionStorage.removeItem("list_id_kib_b");
      var list_id_kib_b = JSON.parse(sessionStorage.getItem("list_id_kib_b"));

      if (list_id_kib_b != null) { // bila belum ada array
        var axist = list_id_kib_b.includes(id_kib_b);
        if (!axist) {
          list_id_kib_b.push(id_kib_b);
        }

      } else {
        list_id_kib_b = [id_kib_b];
      }

      sessionStorage.setItem("list_id_kib_b", JSON.stringify(list_id_kib_b));
      // var temp = JSON.parse(sessionStorage.getItem("list_id_kib_b"));
      // console.log(temp);
      var table = $('#mytable_kib_b').DataTable();
      table.ajax.reload();

    }


    function remove_list_id_kib_b(id_kib_b) {
      var tag_story = JSON.parse(sessionStorage.getItem("list_id_kib_b"));
      var id_tag = id_kib_b;
      tag_story = tag_story.filter(function(x) {
        if (x !== id_tag) {
          return x;
        }
      });

      sessionStorage.setItem("list_id_kib_b", JSON.stringify(tag_story));
      var table = $('#mytable_kib_b').DataTable();
      table.ajax.reload();
    }


  });
</script>