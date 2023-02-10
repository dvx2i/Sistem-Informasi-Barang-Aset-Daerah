<section class="content">
  <div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
      <h2 style="margin-top:0px">Daftar Barang Ruang</h2>
    </div>

    <div class="col-md-4 text-right">
      <?php echo anchor(base_url('master_ruang/pengajuan/create'), 'Buat', 'class="btn btn-primary"'); ?>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-body">
          <table class="table table-bordered table-striped" id="mytable">
            <thead>
              <tr>
                <th width="80px">No</th>
                <!--<th>Tanggal Pengajuan</th>-->
                <th>Ruang </th>
                 <th>Gedung </th>
                <th>Lantai </th>
                <th>Barang</th>
                <!-- <th>Barang</th> 
                <th width="80px">Aksi</th>-->
                <th width="200px">Keterangan</th>
              </tr>
            </thead>

          </table>
        </div>
      </div>
    </div>
  </div>
</section>


<script src="<?= base_url() ?>/assets/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.checkbox.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    var arr_kib_name = ['kib_b',  'kib_e'];
    $.each(arr_kib_name, function(index, value) {
      sessionStorage.removeItem("arr_" + value);
      sessionStorage.removeItem("list_id_" + value);
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
      processing: false,
      serverSide: true,
      ajax: {
        "url": "pengajuan/json",
        "type": "POST"
      },
      columns: [{
          "data": "id_ruang_barang",
          "orderable": false,
          'width': '5%'
        },
        /*{
          "data": "tanggal"
        },*/
       {
                 
                   data: "deskripsi", 
                    render: function(data, type, row, meta) { 
                if(data==''){
                     return row['nama_ruang'] 
                }else
                {
                  return row['nama_ruang']+ ' (' + row['deskripsi'] +')'
                   
                 }
                }

                },
                {
          "data": "nama_gedung",
          
        },
        {
          "data": "lantai",
          
        },

         {
                 
                   data: "nama_barang", 
                    render: function(data, type, row, meta) { 
                
                   return row['nama_barang']+ '  ' + row['merk_type'] 

                }

                },
         
        
        /*
        {
          "data": "action",
          "orderable": false,
          // "className" : "text-center"
        },*/
        {
          "data": "deskripsi_kib",
          // "orderable": false,
          // "className" : "text-info"
        },
        // {
        //   "data" : 'barang',
        //   // "orderable": false,
        // },
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
      }
    });
  });
</script>