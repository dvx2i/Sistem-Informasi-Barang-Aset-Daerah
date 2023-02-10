<table class="table table-bordered table-striped" style="display: none;"  id="mytable_kib_c">
  <thead>
    <tr>
      <th width="80px">No</th>
      <th width="200px">Aksi</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
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
    </tr>
  </thead>
</table>

<div class="external-event bg-yellow ui-draggable ui-draggable-handle">
  <label for=""> Barang Yang di Pilih </label>
</div>

<div class="table-responsive">
  <table class="table table-bordered table-striped" id="temp_kib_c">
    <thead>
      <tr>
        <th width="80px">No</th>
        <th width="200px">Aksi</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
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
      </tr>
    </thead>
    <tbody>

    </tbody>
  </table>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    draw_lampiran_c();
    var button = "<input type='button' class='btn btn-success lampiran' value='Pilih'>";

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

    var t = $("#mytable_kib_c").dataTable({
      initComplete: function() {
        var api = this.api();
        $('#mytable_kib_c_filter input')
          .off('.DT')
          .on('keyup.DT', function(e) {
            if (e.keyCode == 13) {
              api.search(this.value).draw();
            }
          });

        $('#mytable_kib_c').on('click', '.lampiran', function(e) {
          var data = api.row($(this).parents('tr')).data();
          var kode_jenis = '03';
          var id_penghapusan = data['id_penghapusan'];
          var id_penghapusan_barang = data['id_penghapusan_barang'];
          var _id = id_penghapusan + "_" + id_penghapusan_barang;
          var no = $('#temp_kib_c tr').length;

          var arr_kib_c = {};
          var temp = JSON.parse(sessionStorage.getItem("arr_kib_c"));

          if (temp != null) { // bila belum ada array
            arr_kib_c = temp;
            arr_kib_c[_id] = data;
          } else {
            arr_kib_c[_id] = data;
          }

          sessionStorage.setItem("arr_kib_c", JSON.stringify(arr_kib_c));
          draw_lampiran_c();
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
      "searching": false,
      "paging": false,
      "info": false,
      ajax: {
        "url": base_url + "penghapusan/global_penghapusan/json_kib_c/laporan",
        "type": "POST",
        "data": function(data) {
					data.id_penghapusan = '<?= $id_penghapusan ?>';
				}
      },
      columns: [{
          "data": "id_kib_c",
          "orderable": false
        },
        {
          "data": null,
          "orderable": false,
          "className": "text-center",
          'width': '4%',
          "defaultContent": button,
          "searchable": false,
        }, {
          "data": "kode_barang",
        }, {
          "data": "nama_barang",
          'width': '15%',
        }, {
          "data": "nomor_register"
        }, {
          "data": "kondisi_bangunan"
        }, {
          "data": "bangunan_bertingkat"
        }, {
          "data": "bangunan_beton"
        }, {
          "data": "luas_lantai_m2",
          'width': '7%',
        }, {
          "data": "lokasi_alamat",
          'width': '10%',
        }, {
          "data": "gedung_tanggal",
          'width': '10%',
        }, {
          "data": "gedung_nomor"
        }, {
          "data": "luas_m2"
        }, {
          "data": "status",
          'width': '15%',
        }, {
          "data": "nomor_kode_tanah",
          'width': '7%',
        }, {
          "data": "asal_usul"
        }, {
          "data": "harga"
        }, {
          "data": "keterangan"
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

        var kode_jenis = '03';
        var id_penghapusan = data['id_penghapusan'];
        var id_penghapusan_barang = data['id_penghapusan_barang'];
        var _id = id_penghapusan + "_" + id_penghapusan_barang;
        var no = $('#temp_kib_c tr').length;

        var arr_kib_c = {};
        var temp = JSON.parse(sessionStorage.getItem("arr_kib_c"));

        if (temp != null) { // bila belum ada array
          arr_kib_c = temp;
          arr_kib_c[_id] = data;
        } else {
          arr_kib_c[_id] = data;
        }

        sessionStorage.setItem("arr_kib_c", JSON.stringify(arr_kib_c));
        draw_lampiran_c();
      },
      drawCallback: function(settings) {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        
          
      }
    });

    $('#temp_kib_c').on('click', '.lampiran', function(e) {
      var attr_id = $(this).attr('attr_id');
      var arr_temp = JSON.parse(sessionStorage.getItem("arr_kib_c"));
      delete arr_temp[attr_id];
      sessionStorage.setItem("arr_kib_c", JSON.stringify(arr_temp));
      draw_lampiran_c()
    })


    function draw_lampiran_c() {
      var arr_temp = JSON.parse(sessionStorage.getItem("arr_kib_c"));
      var no = 0;
      $('.hapus_temp_kib_c').remove(); //kosongkan temporary
      $.each(arr_temp, function(index, data) {
        no = no + 1;
        var kode_jenis = '03';
        var id_penghapusan = data['id_penghapusan'];
        var id_penghapusan_barang = data['id_penghapusan_barang'];
        var _id = id_penghapusan + "_" + id_penghapusan_barang;

        var button_hapus = "<input type='button' class='btn btn-danger lampiran' value='Hapus' attr_id='" + _id + "'>";
        var input_lampiran = '<input class="' + _id + ' hapus_temp_kib_c" type="hidden" name="lampiran[' + id_penghapusan + '][]" value="' + id_penghapusan_barang + '">';
        var tr =
          "<tr class='" + _id + " hapus_temp_kib_c'>" +
          "<td>" + no + "</td>" +
          "<td widtd='200px' class='text-center'>" + button_hapus + " " + input_lampiran + "</td>" +
          "<td>" + data['kode_barang'] + "</td>" +
          "<td>" + data['nama_barang'] + "</td>" +
          "<td>" + data['nomor_register'] + "</td>" +
          "<td>" + data['kondisi_bangunan'] + "</td>" +
          "<td>" + data['bangunan_bertingkat'] + "</td>" +
          "<td>" + data['bangunan_beton'] + "</td>" +
          "<td>" + data['luas_lantai_m2'] + "</td>" +
          "<td>" + data['lokasi_alamat'] + "</td>" +
          "<td>" + data['gedung_tanggal'] + "</td>" +
          "<td>" + data['gedung_nomor'] + "</td>" +
          "<td>" + data['luas_m2'] + "</td>" +
          "<td>" + data['status'] + "</td>" +
          "<td>" + data['nomor_kode_tanah'] + "</td>" +
          "<td>" + data['asal_usul'] + "</td>" +
          "<td>" + data['harga'] + "</td>" +
          "<td>" + data['keterangan'] + "</td>" +

          "</tr>";

        $(tr).appendTo('#temp_kib_c');

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
      });
    }

  });
</script>