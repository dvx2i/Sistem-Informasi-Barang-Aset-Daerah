<span id="span_5"></span>

<table class="table table-bordered table-striped" id="mytable_kib_e">
  <thead>
    <tr>
      <th width="80px">No</th>
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
      <th>Keterangan</th>
      <!-- <th>kode_lokasi</th> -->
      <th width="10%">Aksi</th>
    </tr>
  </thead>
</table>

<br>
<div class="external-event bg-yellow ui-draggable ui-draggable-handle">
  <label for=""> Barang Yang di Pilih </label>
</div>

<div class="table-responsive">
  <table class="table table-bordered table-striped" id="temp_kib_e">
    <thead>
      <tr>
        <th width="80px">No</th>
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
        <th>Tahun Pembelian</th>
        <th>Asal Usul</th>
        <th>Harga</th>
        <th>Keterangan</th>
        <th width="10%">Aksi</th>
      </tr>
    </thead>
    <tbody>

    </tbody>
  </table>
</div>

<script type="text/javascript">
$(document).ready(function() {
  draw_penghapusan_e();
  var button = "<input type='button' class='btn btn-success pengajuan' value='Pilih'>";

  $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
  {
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

      $('#mytable_kib_e').on('click','.pengajuan', function(e){
        var data = api.row( $(this).parents('tr') ).data();
        var kode_jenis = '5';
        var id_kib = data['id_kib_e'];
        var _id=kode_jenis+"_"+id_kib;
        var no = $('#temp_kib_e tr').length;

        var arr_kib_e={};
        var temp = JSON.parse(sessionStorage.getItem("arr_kib_e"));

        if (temp!=null) {// bila belum ada array
          arr_kib_e=temp;
          arr_kib_e[_id]=data;
        }else{
          arr_kib_e[_id]=data;
        }

        sessionStorage.setItem("arr_kib_e", JSON.stringify(arr_kib_e));
        draw_penghapusan_e();
      });

    },
    scrollX: true,
    oLanguage: {
      sEmptyTable:	 "Tidak ada data yang tersedia pada tabel ini",
      sProcessing:   "Sedang memproses...",
      sLengthMenu:   "Tampilkan _MENU_ entri",
      sZeroRecords:  "Tidak ditemukan data yang sesuai",
      sInfo:         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
      sInfoEmpty:    "Menampilkan 0 sampai 0 dari 0 entri",
      sInfoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
      sInfoPostFix:  "",
      sSearch:       "Cari:",
      sUrl:          "",
      oPaginate: {
        sFirst:    "Pertama",
        sPrevious: "Sebelumnya",
        sNext:     "Selanjutnya",
        sLast:     "Terakhir"
      }
    },
    processing: true,
    serverSide: true,
    ajax: {"url": base_url+"penghapusan/global_penghapusan/json_kib_e/list_barang", "type": "POST"},
    columns: [
    {
      "data": "id_kib_e",
      "orderable": false
    },{"data": "kode_barang"},{"data": "nama_barang", 'width':'20%'},{"data": "nomor_register"},
    {"data": "judul_pencipta", 'width':'20%'},{"data": "spesifikasi"},
    {"data": "kesenian_asal_daerah", 'width':'20%'},{"data": "kesenian_pencipta"},
    {"data": "kesenian_bahan"},{"data": "hewan_tumbuhan_jenis"},{"data": "hewan_tumbuhan_ukuran"},
    <?php /*{"data": "jumlah"},*/ ?>{"data": "tahun_pembelian"},
    {"data": "asal_usul", 'width':'5%'},{"data": "harga"},{"data": "keterangan"},
    // {"data": "kode_lokasi"},
    {
      "data" : null,
      "orderable": false,
      "className" : "text-center",
      'width':'4%',
       "defaultContent": button,
       "searchable": false,
    }
    ],

    order: [[0, 'desc']],
    rowCallback: function(row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      var index = page * length + (iDisplayIndex + 1);
      $('td:eq(0)', row).html(index);
    },
    drawCallback: function( settings ) {
      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass   : 'iradio_minimal-blue'
      });
    }
  });

  $('#temp_kib_e').on('click','.pengajuan', function(e){
    var attr_id = $(this).attr('attr_id');
    var arr_temp=JSON.parse(sessionStorage.getItem("arr_kib_e"));
    delete arr_temp[attr_id];
    sessionStorage.setItem("arr_kib_e", JSON.stringify(arr_temp));
    draw_penghapusan_e();
  })


  function draw_penghapusan_e(){
    var arr_temp=JSON.parse(sessionStorage.getItem("arr_kib_e"));
    var no=0;
    $('.hapus_temp_kib_e').remove(); //kosongkan temporary
    $.each(arr_temp, function( index, data ) {
      no=no+1;
      var kode_jenis = '5';
      var id_kib = data['id_kib_e'];
      var _id=kode_jenis+"_"+id_kib;

      var button_hapus= "<input type='button' class='btn btn-danger pengajuan' value='Hapus' attr_id='"+_id+"'>";
      var input_lampiran = '<input class="'+_id+' hapus_temp_kib_a" type="hidden" name="pengajuan['+kode_jenis+'][]" value="'+id_kib+'">';
      var tr =
      "<tr class='"+_id+" hapus_temp_kib_e'>"+
      "<td>"+no+"</td>"+
      "<td>"+data['kode_barang']+"</td>"+
      "<td>"+data['nama_barang']+"</td>"+
      "<td>"+data['nomor_register']+"</td>"+
      "<td>"+data['judul_pencipta']+"</td>"+
      "<td>"+data['spesifikasi']+"</td>"+
      "<td>"+data['kesenian_asal_daerah']+"</td>"+
      "<td>"+data['kesenian_pencipta']+"</td>"+
      "<td>"+data['kesenian_bahan']+"</td>"+
      "<td>"+data['hewan_tumbuhan_jenis']+"</td>"+
      "<td>"+data['hewan_tumbuhan_ukuran']+"</td>"+
      "<td>"+data['tahun_pembelian']+"</td>"+
      "<td>"+data['asal_usul']+"</td>"+
      "<td>"+data['Harga']+"</td>"+
      "<td>"+data['keterangan']+"</td>"+
      "<td widtd='200px' class='text-center'>"+button_hapus+" "+input_lampiran+"</td>"+
      "</tr>";

      $(tr).appendTo('#temp_kib_e');

      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass   : 'iradio_minimal-blue'
      });
    });
  }

});
</script>
