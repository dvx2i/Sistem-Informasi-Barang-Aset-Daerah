<style media="screen">
  /* .ui-autocomplete-input {
  border: none;
  font-size: 14px;
  width: 300px;
  height: 24px;
  margin-bottom: 5px;
  padding-top: 2px;
  border: 1px solid #DDD !important;
  padding-top: 0px !important;
  z-index: 1511;
  position: relative;
}
.ui-menu .ui-menu-item a {
  font-size: 12px;
}
.ui-autocomplete {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1510 !important;
  float: left;
  display: none;
  min-width: 160px;
  width: 160px;
  padding: 4px 0;
  margin: 2px 0 0 0;
  list-style: none;
  background-color: #ffffff;
  border-color: #ccc;
  border-color: rgba(0, 0, 0, 0.2);
  border-style: solid;
  border-width: 1px;
  -webkit-border-radius: 2px;
  -moz-border-radius: 2px;
  border-radius: 2px;
  -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  -webkit-background-clip: padding-box;
  -moz-background-clip: padding;
  background-clip: padding-box;
  *border-right-width: 2px;
  *border-bottom-width: 2px;
}
.ui-menu-item > a.ui-corner-all {
    display: block;
    padding: 3px 15px;
    clear: both;
    font-weight: normal;
    line-height: 18px;
    color: #555555;
    white-space: nowrap;
    text-decoration: none;
}
.ui-state-hover, .ui-state-active {
      color: #ffffff;
      text-decoration: none;
      background-color: #0088cc;
      border-radius: 0px;
      -webkit-border-radius: 0px;
      -moz-border-radius: 0px;
      background-image: none;
}
#modalIns{
    width: 500px;
}   */
</style>

<section class="content">
  <div class="row" style="margin-bottom: 10px">
    <div class="col-md-4">
      <h2 style="margin-top:0px">Daftar Hak Akses </h2>
    </div>
    <div class="col-md-4 text-center">
      <div style="margin-top: 4px" id="message">
        <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
      </div>
    </div>
    <div class="col-md-12 text-left">
      <?php //echo anchor(base_url('master_data/hak_akses/create'), 'Buat', 'class="btn btn-primary"'); 
      ?>
      <button type="button" name="button" class="btn btn-primary btn_create">Buat</button>
    </div>
  </div>
  <table class="table table-bordered table-striped" id="mytable">
    <thead>
      <tr>
        <th width="80px">No</th>
        <th>Role</th>
        <?php /*
          <th>Menu</th>
          <th>Sub Menu</th>
          <th>Buat</th>
          <th>Perbarui</th>
          <th>Hapus</th>
          */ ?>
        <th width="200px">Action</th>
      </tr>
    </thead>

  </table>
</section>


<div id="classModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="classInfo" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <?= form_open(base_url('master_data/hak_akses/create_action')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
          ×
        </button>
        <h4 class="modal-title" id="classModalLabel">
          Buat Hak Akses
        </h4>
      </div>
      <div class="row">
        <div class="col-md-7">
          <div class="modal-body">
            <div class="form-group">
              <input type="text" name="description" value="" class="form-control" id='description' pattern="[a-zA-Z0-9\s]+" required>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="text-align:left;">
        <button type="submit" class="btn btn-primary" <?php /*data-dismiss="modal"*/ ?>>
          Buat
        </button>
      </div>
      <?= form_close(); ?>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    $('.btn_create').click(function() {
      $('#classModal').modal('show');
    });

    $(function() {
      var jabatan = [<?= $list_jabatan; ?>];
      $("#description").autocomplete({
        source: jabatan,
        appendTo: $('.modal-body')
      });
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
      searching: false,
      paging: false,
      info: false,
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
        "url": "hak_akses/json",
        "type": "POST"
      },
      columns: [{
          "data": "id_role",
          "orderable": false,
          "width": "5%"
        }, {
          "data": "description",
          "width": "30%",
          "orderable": false
        },
        {
          "data": "action",
          "orderable": false,
          "className": "text-center",
          "orderable": false,
          "width": "15%"
        }
      ],
      // order: [[0, 'desc']],
      rowCallback: function(row, data, iDisplayIndex) {
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        //console.log(row); //data.role_desc
        $('td:eq(0)', row).html(index);
      }
    });
  });
</script>