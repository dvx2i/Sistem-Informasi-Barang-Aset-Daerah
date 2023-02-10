
  <section class="content">
    <div class="row" style="margin-bottom: 10px">
      <div class="col-md-4">
        <h2 style="margin-top:0px">Daftar User </h2>
      </div>
      <div class="col-md-4 text-center">
        <div style="margin-top: 4px"  id="message">
          <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
        </div>
      </div>
      <div class="col-md-4 text-right">
        <?php echo anchor(base_url('User/create'), 'Pengaturan Pengguna', 'class="btn btn-primary"'); ?>
      </div>
    </div>
    <table class="table table-bordered table-striped" id="mytable">
      <thead>
        <tr>
          <th width="80px">No</th>
          <th>Nama</th>
          <th>NIP</th>
		  <th style="display:none"></th>
          <th>Lokasi</th>
          <!-- <th>Jabatan</th> -->
          <th>Hak Akses</th>
		  <th>Freze</th>
		  <th style="display:none"></th>
          <th width="200px">Action</th>
        </tr>
      </thead>

    </table>
<script src="<?= base_url() ?>/assets/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/datatables/dataTables.checkbox.js"></script>
<script src="<?= base_url() ?>assets/sweetalert/dist/sweetalert.min.js"></script>
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
        ajax: {"url": "User/json", "type": "POST"},
        columns: [
        {
          "data": "id_user",
          "orderable": false,
		  "searching": true,
		  "checkboxes": {
					'selectRow': true
				},
			'width': '5%'	
        },{"data": "nama",'className': "text-nowrap"},{"data": "nip"},
		{"data": "instansi",visible:false},{"data": "lokasi",'className': "text-nowrap"},
        // {"data": "jabatan_desc",'className': "text-nowrap"},
        {"data": "role_desc",'className': "text-nowrap"},{"data": "status_freze"},{"data": "freze",visible:false},
        {
          "data" : "action",
          "orderable": false,
          "className" : "text-center text-nowrap",
          // "width":"10%"
        }
        ],
        order: [
				[0, 'asc']
			],
		
			autoFill: true,
			colReorder: true,
			keys: true,
			rowReorder: true,
			select: "multiple",
        rowCallback: function(row, data, iDisplayIndex) {
          //var info = this.fnPagingInfo();
          //var page = info.iPage;
          //var length = info.iLength;
          //var index = page * length + (iDisplayIndex + 1);
         // $('td:eq(0)', row).html(index);
        }
      });
	   var button_all = "<button id='verifBtn' class='btn btn-sm btn-primary ' style='margin-left: 20px;'><i class='fa fa-check'></i> Freze</button>";
  var tolak_all = "<button id='batalBtn' class='btn btn-sm btn-danger ' style='margin-left: 20px;'><i class='fa fa-times'></i> Un Freze</button>";

  $('div#mytable_length>label').append(button_all);
  $('div#mytable_length>label').append(tolak_all);
  //iCheck for checkbox and radio inputs
  
  
$(document).on('click', '#verifBtn', function() {

			var table = $("#mytable").DataTable();
			var rows_selected = table.column(0).checkboxes.selected();
			ids = [];
			$.each(rows_selected, function(index, rowId) {
				ids.push(rowId);
			})

			if (ids.length > 0) {
				swal({
					title: "",
					text: "Freze data terpilih?",
					icon: "info",
					buttons: [
						'Batal',
						'Ya'
					],
					dangerMode: false,
				}).then(function(isConfirm) {
					if (isConfirm) {
						$.ajax({
							type: "POST", // Method pengiriman data bisa dengan GET atau POST
							url: "<?php echo site_url("user/update/freze"); ?>",
							data: {
								user_id: ids,
								//jaminan_verifikasi_tgl: $('#jaminan_verifikasi_tgl').val()
							}, // data yang akan dikirim ke file yang dituju
							dataType: "json",
							beforeSend: function(e) {
								if (e && e.overrideMimeType) {
									e.overrideMimeType("application/json;charset=UTF-8");
								}
							},
							success: function(response) {

								table.ajax.reload();
								if (response.success == true) {
									swal({
										title: '',
										text: response.message,
										icon: 'success'
									});
								} else {

									swal("Error", response.message, "error");
								}

							},
							error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
								alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
							}
						});
					} else {
						swal("Dibatalkan", "", "error");
					}
				})
			} else {
				$.gritter.add({
					title: 'Pilih data terlebih dahulu!',
					// text: data,
					// sticky: true,
					time: '3000',
				}, 1000);
				return false;
			}
			// alert(ids.length);
			// return false;
		});
		
		$(document).on('click', '#batalBtn', function() {

			var table = $("#mytable").DataTable();
			var rows_selected = table.column(0).checkboxes.selected();
			ids = [];
			$.each(rows_selected, function(index, rowId) {
				ids.push(rowId);
			})

			if (ids.length > 0) {
				swal({
					title: "",
					text: "UnFreze data terpilih?",
					icon: "info",
					buttons: [
						'Batal',
						'Ya'
					],
					dangerMode: false,
				}).then(function(isConfirm) {
					if (isConfirm) {
						$.ajax({
							type: "POST", // Method pengiriman data bisa dengan GET atau POST
							url: "<?php echo site_url("user/update/unfreze"); ?>",
							data: {
								user_id: ids,
								//jaminan_verifikasi_tgl: $('#jaminan_verifikasi_tgl').val()
							}, // data yang akan dikirim ke file yang dituju
							dataType: "json",
							beforeSend: function(e) {
								if (e && e.overrideMimeType) {
									e.overrideMimeType("application/json;charset=UTF-8");
								}
							},
							success: function(response) {

								table.ajax.reload();
								if (response.success == true) {
									swal({
										title: '',
										text: response.message,
										icon: 'success'
									});
								} else {

									swal("Error", response.message, "error");
								}

							},
							error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
								alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
							}
						});
					} else {
						swal("Dibatalkan", "", "error");
					}
				})
			} else {
				$.gritter.add({
					title: 'Pilih data terlebih dahulu!',
					// text: data,
					// sticky: true,
					time: '3000',
				}, 1000);
				return false;
			}
			// alert(ids.length);
			// return false;
		});
    });
  </script>
  </section>
