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


<script type="text/javascript">
  $(document).ready(function() {
    // draw_penghapusan_b();
    var button = "<input type='button' class='btn btn-success pengajuan' value='Pilih'>";

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

        $('#mytable_kib_b').on('click', '.pengajuan', function(e) {
          var data = api.row($(this).parents('tr')).data();
          var kode_jenis = '02';
          var id_kib = data['id_kib_b'];
          var _id = kode_jenis + "_" + id_kib;
          var no = $('#temp_kib_b tr').length;
          window.location.href = base_url + 'kapitalisasi/pengajuan/form_kapitalisasi/' + kode_jenis + '/' + id_kib;
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
        // "url": base_url + "penghapusan/global_penghapusan/json_kib_b/list_barang",
        "url": base_url + "kapitalisasi/global_kapitalisasi/json_kib_b/list_barang",
        "type": "POST"
      },
      columns: [{
          "data": "id_kib_b",
          "orderable": false
        },
        {
          "data": null,
          "orderable": false,
          "className": "text-center",
          'width': '4%',
          "defaultContent": button,
          "searchable": false,
        }

        , {
          "data": "kode_barang"
        }, {
          "data": "nama_barang",
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
        }, //{"data": "kode_lokasi"},
        {
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
      /*drawCallback: function( settings ) {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });
      }*/
    });
    <?php /*
  $('#temp_kib_b').on('click','.pengajuan', function(e){
    var attr_id = $(this).attr('attr_id');
    var arr_temp=JSON.parse(sessionStorage.getItem("arr_kib_b"));
    delete arr_temp[attr_id];
    sessionStorage.setItem("arr_kib_b", JSON.stringify(arr_temp));
    draw_penghapusan_b()
  })
*/ ?>
    <?php /*
  function draw_penghapusan_b(){
    var arr_temp=JSON.parse(sessionStorage.getItem("arr_kib_b"));
    var no=0;
    $('.hapus_temp_kib_b').remove(); //kosongkan temporary
    $.each(arr_temp, function( index, data ) {
      no=no+1;
      var kode_jenis = '2';
      var id_kib = data['id_kib_b'];
      var _id=kode_jenis+"_"+id_kib;

      var button_hapus= "<input type='button' class='btn btn-danger pengajuan' value='Hapus' attr_id='"+_id+"'>";
      var input_lampiran = '<input class="'+_id+' hapus_temp_kib_a" type="hidden" name="pengajuan['+kode_jenis+'][]" value="'+id_kib+'">';
      var tr =
        "<tr class='"+_id+" hapus_temp_kib_b'>"+
          "<td>"+no+"</td>"+
          "<td>"+data['kode_barang']+"</td>"+
          "<td>"+data['nama_barang']+"</td>"+
          "<td>"+data['nomor_register']+"</td>"+
          "<td>"+data['merk_type']+"</td>"+
          "<td>"+data['ukuran_cc']+"</td>"+
          "<td>"+data['bahan']+"Bahan</td>"+
          "<td>"+data['tahun_pembelian']+"</td>"+
          "<td>"+data['nomor_pabrik']+"</td>"+
          "<td>"+data['nomor_rangka']+"</td>"+
          "<td>"+data['nomor_mesin']+"</td>"+
          "<td>"+data['nomor_polisi']+"</td>"+
          "<td>"+data['nomor_bpkb']+"</td>"+
          "<td>"+data['asal_usul']+"</td>"+
          "<td>"+data['harga']+"</td>"+
          "<td>"+data['keterangan']+"</td>"+
          "<td widtd='200px' class='text-center'>"+button_hapus+" "+input_lampiran+"</td>"+
        "</tr>";

        $(tr).appendTo('#temp_kib_b');

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });
    });
  }
*/ ?>
  });
</script>