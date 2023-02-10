<span id="span_4"></span>

<table class="table table-bordered table-striped" id="mytable_kib_d">
  <thead>
    <tr>
      <th width="80px">ID KIB</th>
      <th width="200px">Aksi</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Nomor Register</th>
      <th>Konstruksi</th>
      <th>Panjang(KM)</th>
      <th>Lebar(M)</th>
      <th>Luas(M2)</th>
      <th>Letak/Lokasi</th>
      <th>Dokumen Tanggal</th>
      <th>Dokumen Nomor</th>
      <th>Status Tanah</th>
      <th>Kode Tanah</th>
      <th>Asal Usul</th>
      <th>Harga</th>
      <th>Kondisi</th>
      <th>Keterangan</th>
      <th>ID Inventaris</th>
      <!-- <th>Kode Lokasi</th> -->

    </tr>
  </thead>
</table>

<script type="text/javascript">
  $(document).ready(function() {

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

    var t = $("#mytable_kib_d").dataTable({
      initComplete: function() {
        var api = this.api();
        $('#mytable_kib_d_filter input')
          .off('.DT')
          .on('keyup.DT', function(e) {
            if (e.keyCode == 13) {
              api.search(this.value).draw();
            }
          });

        $('#mytable_kib_d').on('click', '.pengajuan', function(e) {
          var data = api.row($(this).parents('tr')).data();
          var kode_jenis = '04';
          var id_kib = data['id_kib_d'];
          var _id = kode_jenis + "_" + id_kib;
          var no = $('#temp_kib_d tr').length;
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
        "url": base_url + "kapitalisasi/global_kapitalisasi/json_kib_d/list_barang",
        "type": "POST"
      },
      columns: [{
          "data": "id_kib_d",
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
          "data": "kode_barang"
        }, {
          "data": "nama_barang",
          'width': '20%'
        }, {
          "data": "nomor_register"
        },
        {
          "data": "konstruksi",
          "searchable": false,
        }, {
          "data": "panjang_km",
          "searchable": false,
        }, {
          "data": "lebar_m",
          "searchable": false,
        }, {
          "data": "luas_m2",
          "searchable": false,
        },
        {
          "data": "letak_lokasi",
          'width': '20%',
          "searchable": false,
        }, {
          "data": "dokumen_tanggal",
          'width': '10%',
          "searchable": false,
        },
        {
          "data": "dokumen_nomor"
        }, {
          "data": "status_tanah",
          'width': '20%',
          "searchable": false,
        }, {
          "data": "kode_tanah"
        },
        {
          "data": "asal_usul",
          "searchable": false,
        }, {
          "data": "harga"
        }, {
          "data": "kondisi",
          "searchable": false,
        }, {
          "data": "keterangan",
          "searchable": false,
        },{
              "data": "id_inventaris"
            }
        // {"data": "kode_lokasi"},

      ],

      order: [
        [0, 'desc']
      ],
      // rowCallback: function(row, data, iDisplayIndex) {
      //   var info = this.fnPagingInfo();
      //   var page = info.iPage;
      //   var length = info.iLength;
      //   var index = page * length + (iDisplayIndex + 1);
      //   $('td:eq(0)', row).html(index);
      // },
      /*drawCallback: function( settings ) {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });
      }*/
    });

    $('#temp_kib_d').on('click', '.pengajuan', function(e) {
      var attr_id = $(this).attr('attr_id');
      var arr_temp = JSON.parse(sessionStorage.getItem("arr_kib_d"));
      delete arr_temp[attr_id];
      sessionStorage.setItem("arr_kib_d", JSON.stringify(arr_temp));
      <?php /*draw_penghapusan_d()*/ ?>
    })

    <?php /*
  function draw_penghapusan_d(){
    var arr_temp=JSON.parse(sessionStorage.getItem("arr_kib_d"));
    var no=0;
    $('.hapus_temp_kib_d').remove(); //kosongkan temporary
    $.each(arr_temp, function( index, data ) {
      no=no+1;
      var kode_jenis = '4';
      var id_kib = data['id_kib_d'];
      var _id=kode_jenis+"_"+id_kib;

      var button_hapus= "<input type='button' class='btn btn-danger pengajuan' value='Hapus' attr_id='"+_id+"'>";
      var input_lampiran = '<input class="'+_id+' hapus_temp_kib_a" type="hidden" name="pengajuan['+kode_jenis+'][]" value="'+id_kib+'">';
      var tr =
      "<tr class='"+_id+" hapus_temp_kib_d'>"+
      "<td>"+no+"</td>"+
      "<td>"+data['kode_barang']+"</td>"+
      "<td>"+data['nama_barang']+"</td>"+
      "<td>"+data['nomor_register']+"</td>"+
      "<td>"+data['konstruksi']+"</td>"+
      "<td>"+data['panjang_km']+"</td>"+
      "<td>"+data['lebar_m']+"</td>"+
      "<td>"+data['luas_m2']+"</td>"+
      "<td>"+data['letak_lokasi']+"</td>"+
      "<td>"+data['dokumen_tanggal']+"</td>"+
      "<td>"+data['dokumen_nomor']+"</td>"+
      "<td>"+data['status_tanah']+"</td>"+
      "<td>"+data['kode_tanah']+"</td>"+
      "<td>"+data['asal_usul']+"</td>"+
      "<td>"+data['harga']+"</td>"+
      "<td>"+data['keterangan']+"</td>"+
      "<td widtd='200px' class='text-center'>"+button_hapus+" "+input_lampiran+"</td>"+
      "</tr>";

      $(tr).appendTo('#temp_kib_d');

      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass   : 'iradio_minimal-blue'
      });
    });
  }
*/ ?>
  });
</script>