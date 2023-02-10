<span id="span_5"></span>

<table class="table table-bordered table-striped" id="mytable_kib_e">
  <thead>
    <tr>
      <th width="80px">No</th>
      <th width="10%">Aksi</th>
      <th>ID KIB </th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Nomor Register</th>
      <th>Judul Pencipta</th>
      <th>Spesifikasi</th>
      <th>Kesenian (Asal Daerah)</th>
      <th>Kesenian (Pencipta)</th>
      <th>Kesenian (Bahan)</th>
      <th>Hewan/Tumbuhan (Jenis)</th>
      <th>Hewan/Tumbuhan (Ukuran)</th>
      <?php /*<th>Jumlah</th>*/ ?>
      <th>Tahun Pembelian</th>
      <th>Asal Usul</th>
      <th>Harga</th>
      <th>Deskripsi</th>
      <th>Keterangan</th>
      <th>ID Inventaris</th>
      <!-- <th>kode_lokasi</th> -->

    </tr>
  </thead>
</table>


<script type="text/javascript">
  $(document).ready(function() {
    // draw_penghapusan_e();
    var button = "<input type='button' class='btn btn-success pengajuan_e' value='Pilih'>";

    $(document).on('click', '.flat-red', function() {
      var jenis = $(this).val();
      var mytable_kib_e = $('#mytable_kib_e').DataTable();
      mytable_kib_e.clear().destroy();
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

        var t = $("#mytable_kib_e").dataTable({
          initComplete: function() {
            var api = this.api();
            $('#mytable_kib_e_filter input')
              .off('.DT')
              .on('keyup.DT', function(e) {
                if (e.keyCode == 13) {
                  api.search(this.value).draw();
                }
              });

            $('#mytable_kib_e').on('click', '.pengajuan_e', function(e) {
              var data = api.row($(this).parents('tr')).data();
              var kode_jenis = '05';
              var id_kib = data['id_kib_e'];
              var _id = kode_jenis + "_" + id_kib;
              var no = $('#temp_kib tr').length;
          
          //////////////////////////
          set_list_id_kib_e(id_kib);
          //////////////////////////

              var arr_kib_e = {};
              var temp = JSON.parse(sessionStorage.getItem("arr_kib_e"));

              if (temp != null) { // bila belum ada array
                arr_kib_e = temp;
                arr_kib_e[_id] = data;
              } else {
                arr_kib_e[_id] = data;
              }

              sessionStorage.setItem("arr_kib_e", JSON.stringify(arr_kib_e));
              draw_penghapusan_e();
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
            "url": base_url + "penghapusan/global_penghapusan/json_kib_e/list_barang",
            "type": "POST",
        "data": function(data) {
            // data.status = $('#status').val();
            data.jenis = jenis;
          var list_id_kib_e = sessionStorage.getItem("list_id_kib_e");
          data.list_id_kib_e = list_id_kib_e;
        }
          },
          columns: [{
              "data": "id_kib_e",
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
              "data": "id_kib_e",
              "orderable": false
            }, {
              "data": "kode_barang",
              "searchable": false,
            }, {
              "data": "nama_barang",
              'width': '20%'
            }, {
              "data": "nomor_register",
              "searchable": false,
            },
            {
              "data": "judul_pencipta",
              'width': '20%',
              "searchable": false,
            }, {
              "data": "spesifikasi",
              "searchable": false,
            },
            {
              "data": "kesenian_asal_daerah",
              'width': '20%',
              "searchable": false,
            }, {
              "data": "kesenian_pencipta",
              "searchable": false,
            },
            {
              "data": "kesenian_bahan",
              "searchable": false,
            }, {
              "data": "hewan_tumbuhan_jenis",
              "searchable": false,
            }, {
              "data": "hewan_tumbuhan_ukuran",
              "searchable": false,
            },
            <?php /*{"data": "jumlah"},*/ ?> {
              "data": "tahun_pembelian",
              "searchable": false,
            },
            {
              "data": "asal_usul",
              'width': '5%',
              "searchable": false,
            }, {
              "data": "harga",
              "searchable": false,
            }, {
            "data": "deskripsi",
              "searchable": false,
          }, {
              "data": "keterangan",
              "searchable": false,
            },{
              "data": "id_inventaris"
            }
            // {"data": "kode_lokasi"}
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

    $('#temp_kib').on('click', '.pengajuan_e', function(e) {
      var attr_id = $(this).attr('attr_id');
      
      var id_kib_e = attr_id.split("_")[1];
      remove_list_id_kib_e(id_kib_e);
      var arr_temp = JSON.parse(sessionStorage.getItem("arr_kib_e"));
      delete arr_temp[attr_id];
      sessionStorage.setItem("arr_kib_e", JSON.stringify(arr_temp));
      draw_penghapusan_e();
    })


    function draw_penghapusan_e() {
      var arr_temp = JSON.parse(sessionStorage.getItem("arr_kib_e"));
      var no = 0;
      $('.hapus_temp_kib_e').remove(); //kosongkan temporary
      $.each(arr_temp, function(index, data) {
        no = no + 1;
        var kode_jenis = '05';
        var id_kib = data['id_kib_e'];
        var _id = kode_jenis + "_" + id_kib;

        var button_hapus = "<input type='button' class='btn btn-danger pengajuan_e' value='Hapus' attr_id='" + _id + "'>";
        var input_lampiran = '<input class="' + _id + ' hapus_temp_kib_a" type="hidden" name="pengajuan[' + kode_jenis + '][]" value="' + id_kib + '">';
        var tr =
          "<tr class='" + _id + " hapus_temp_kib_e'>" +
          "<td>" + no + "</td>" +
          "<td widtd='200px' class='text-center'>" + button_hapus + " " + input_lampiran + "</td>" +
          "<td>" + data['id_kib_e'] + "</td>" +
          "<td>KIB E</td>" +
          "<td>" + data['kode_barang'] + "</td>" +
          "<td>" + data['nama_barang'] + "</td>" +
          "<td>" + data['nomor_register'] + "</td>" +
          "<td>" + data['judul_pencipta'] + "</td>" +
          "<td>" + data['spesifikasi'] + "</td>" +
          "<td>" + data['kesenian_bahan'] + "</td>" +
          "<td>" + data['asal_usul'] + "</td>" +
          "<td>" + data['tahun_pembelian'] + "</td>" +
          "<td>" + data['hewan_tumbuhan_ukuran'] + "</td>" +
          "<td>-</td>" +
          "<td>" + data['kondisi'] + "</td>" +
          "<td>" + data['Harga'] + "</td>" +
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
    
    function set_list_id_kib_e(id_kib_e) {
      // sessionStorage.removeItem("list_id_kib_e");
      var list_id_kib_e = JSON.parse(sessionStorage.getItem("list_id_kib_e"));

      if (list_id_kib_e != null) { // bila belum ada array
        var axist = list_id_kib_e.includes(id_kib_e);
        if (!axist) {
          list_id_kib_e.push(id_kib_e);
        }

      } else {
        list_id_kib_e = [id_kib_e];
      }

      sessionStorage.setItem("list_id_kib_e", JSON.stringify(list_id_kib_e));
      // var temp = JSON.parse(sessionStorage.getItem("list_id_kib_e"));
      // console.log(temp);
      var table = $('#mytable_kib_e').DataTable();
      table.ajax.reload();

    }


    function remove_list_id_kib_e(id_kib_e) {
      var tag_story = JSON.parse(sessionStorage.getItem("list_id_kib_e"));
      var id_tag = id_kib_e;
      tag_story = tag_story.filter(function(x) {
        if (x !== id_tag) {
          return x;
        }
      });

      sessionStorage.setItem("list_id_kib_e", JSON.stringify(tag_story));
      var table = $('#mytable_kib_e').DataTable();
      table.ajax.reload();
    }

  });
</script>