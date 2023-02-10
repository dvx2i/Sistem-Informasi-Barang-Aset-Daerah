<span id="span_3"></span>

<table class="table table-bordered table-striped" id="mytable_kib_c">
  <thead>
    <tr>
      <th width="80px">No</th>
      <th width="200px">Aksi</th>
      <th>ID KIB</th>
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
      <th>Deskripsi</th>
      <th>Keterangan</th>
      <th>ID Inventaris</th>
    </tr>
  </thead>
</table>

<script type="text/javascript">
  $(document).ready(function() {
    // draw_penghapusan_c();
    var button = "<input type='button' class='btn btn-success pengajuan_c' value='Pilih'>";

    $(document).on('click', '.flat-red', function() {
      var jenis = $(this).val();
      var mytable_kib_c = $('#mytable_kib_c').DataTable();
      mytable_kib_c.clear().destroy();
      sessionStorage.clear()
      // console.log(jenis);
      if (jenis != 'k') {
        
      $("#loadMe").modal({
        backdrop: "static", //remove ability to close modal with click
        keyboard: false, //remove option to close with keyboard
        show: true //Display loader!
      });

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

            $('#mytable_kib_c').on('click', '.pengajuan_c', function(e) {
              var data = api.row($(this).parents('tr')).data();
              var kode_jenis = '03';
              var id_kib = data['id_kib_c'];
              var _id = kode_jenis + "_" + id_kib;
              var no = $('#temp_kib tr').length;
              
          //////////////////////////
          set_list_id_kib_c(id_kib);
          //////////////////////////

              var arr_kib_c = {};
              var temp = JSON.parse(sessionStorage.getItem("arr_kib_c"));

              if (temp != null) { // bila belum ada array
                arr_kib_c = temp;
                arr_kib_c[_id] = data;
              } else {
                arr_kib_c[_id] = data;
              }

              sessionStorage.setItem("arr_kib_c", JSON.stringify(arr_kib_c));
              draw_penghapusan_c();
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
            "url": base_url + "penghapusan/global_penghapusan/json_kib_c/list_barang",
            "type": "POST",
        "data": function(data) {
            // data.status = $('#status').val();
            data.jenis = jenis;
          var list_id_kib_c = sessionStorage.getItem("list_id_kib_c");
          data.list_id_kib_c = list_id_kib_c;
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
            },{
              "data": "id_kib_c"
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
            },  {
            "data": "deskripsi"
          },{
              "data": "keterangan"
            },{
              "data": "id_inventaris"
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
          },
          drawCallback: function(settings) {
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
              checkboxClass: 'icheckbox_minimal-blue',
              radioClass: 'iradio_minimal-blue'
            });
          }
        });
      }
    });

    $('#temp_kib').on('click', '.pengajuan_c', function(e) {
      var attr_id = $(this).attr('attr_id');
      
      var id_kib_c = attr_id.split("_")[1];
      remove_list_id_kib_c(id_kib_c);

      var arr_temp = JSON.parse(sessionStorage.getItem("arr_kib_c"));
      delete arr_temp[attr_id];
      sessionStorage.setItem("arr_kib_c", JSON.stringify(arr_temp));
      draw_penghapusan_c()
    })


    function draw_penghapusan_c() {
      var arr_temp = JSON.parse(sessionStorage.getItem("arr_kib_c"));
      var no = 0;
      $('.hapus_temp_kib_c').remove(); //kosongkan temporary
      $.each(arr_temp, function(index, data) {
        no = no + 1;
        var kode_jenis = '03';
        var id_kib = data['id_kib_c'];
        var _id = kode_jenis + "_" + id_kib;

        var button_hapus = "<input type='button' class='btn btn-danger pengajuan_c' value='Hapus' attr_id='" + _id + "'>";
        var input_lampiran = '<input class="' + _id + ' hapus_temp_kib_a" type="hidden" name="pengajuan[' + kode_jenis + '][]" value="' + id_kib + '">';
        var tr =
          "<tr class='" + _id + " hapus_temp_kib_c'>" +
          "<td>" + no + "</td>" +
          "<td widtd='200px' class='text-center'>" + button_hapus + " " + input_lampiran + "</td>" +
          "<td>" + data['id_kib_c'] + "</td>" +
          "<td>KIB C</td>" +
          "<td>" + data['kode_barang'] + "</td>" +
          "<td>" + data['nama_barang'] + "</td>" +
          "<td>" + data['nomor_register'] + "</td>" +
          "<td>-</td>" +
          "<td>" + data['gedung_nomor'] + "," + data['gedung_tanggal'] + "</td>" +
          "<td>-</td>" +
          "<td>" + data['asal_usul'] + "</td>" +
          "<td>" + data['tahun_perolehan'] + "</td>" +
          "<td>Bertingkat: " + data['bangunan_bertingkat'] + ",Beton: " + data['bangunan_beton'] +  "</td>" +
          "<td>-</td>" +
          "<td>" + data['kondisi_bangunan'] + "</td>" +
          "<td>" + data['harga'] + "</td>" +
          "<td>" + data['keterangan'] + "</td>" +
          "<td>" + data['id_inventaris'] + "</td>" +

          "</tr>";

        $(tr).appendTo('#temp_kib');

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
      });
    }

    function set_list_id_kib_c(id_kib_c) {
      // sessionStorage.removeItem("list_id_kib_c");
      var list_id_kib_c = JSON.parse(sessionStorage.getItem("list_id_kib_c"));

      if (list_id_kib_c != null) { // bila belum ada array
        var axist = list_id_kib_c.includes(id_kib_c);
        if (!axist) {
          list_id_kib_c.push(id_kib_c);
        }

      } else {
        list_id_kib_c = [id_kib_c];
      }

      sessionStorage.setItem("list_id_kib_c", JSON.stringify(list_id_kib_c));
      // var temp = JSON.parse(sessionStorage.getItem("list_id_kib_c"));
      // console.log(temp);
      var table = $('#mytable_kib_c').DataTable();
      table.ajax.reload();

    }


    function remove_list_id_kib_c(id_kib_c) {
      var tag_story = JSON.parse(sessionStorage.getItem("list_id_kib_c"));
      var id_tag = id_kib_c;
      tag_story = tag_story.filter(function(x) {
        if (x !== id_tag) {
          return x;
        }
      });

      sessionStorage.setItem("list_id_kib_c", JSON.stringify(tag_story));
      var table = $('#mytable_kib_c').DataTable();
      table.ajax.reload();
    }

  });
</script>