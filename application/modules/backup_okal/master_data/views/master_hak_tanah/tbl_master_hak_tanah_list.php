<div class="row" style="margin-bottom: 10px">
  <div class="col-md-4">
    <h3 style="margin-top:0px">Hak Tanah</h3>
  </div>
  <div class="col-md-4 text-center">
    <div style="margin-top: 4px"  id="message">
      <?php //echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
    </div>
  </div>
  <div class="col-md-4 text-right">
    <?php echo anchor(base_url('master_data/master_hak_tanah/create'), 'Buat', 'class="btn btn-primary"'); ?>
  </div>
</div>
<table class="table table-bordered table-striped" id="hak_tanah">
  <thead>
    <tr>
      <th width="80px">No</th>
      <th>Deskripsi</th>
      <th width="200px">Aksi</th>
    </tr>
  </thead>

</table>

<script type="text/javascript">
$(document).ready(function() {
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

  var t = $("#hak_tanah").dataTable({
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
    searching: false, paging: false, info: false,
    oLanguage: {
      sProcessing: "loading..."
    },
    processing: true,
    serverSide: true,
    ajax: {"url": "master_data/master_hak_tanah/json", "type": "POST"},
    columns: [
    {
      "data": "id_master_hak_tanah",
      "orderable": false
    },{"data": "description"},
    {
      "data" : "action",
      "orderable": false,
      "className" : "text-center"
    }
    ],
    order: [[0, 'desc']],
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
