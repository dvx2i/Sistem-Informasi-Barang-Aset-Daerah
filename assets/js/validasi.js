//VALIDASI
var kode_jenis = null;
$(document).ready(function () {
  var api = $("#mytable").DataTable();
  kode_jenis = $('#kode_jenis').val();
  var button_all = "<button id='select_all' class='btn btn-sm btn-primary ' style='margin-left: 20px;'><i class='fa fa-check'></i> Validasi</button>";
  var tolak_all = "<button id='tolak_all' class='btn btn-sm btn-danger ' style='margin-left: 20px;'><i class='fa fa-times'></i> Tolak</button>";
  var hapus_all = "<button id='hapus_all' class='btn btn-sm btn-danger ' style='margin-left: 20px;'><i class='fa fa-trash'></i> Hapus</button>";

  $('div#mytable_length>label').append(button_all);
  $('div#mytable_length>label').append(tolak_all);
  $('div#mytable_length>label').append(hapus_all);
  //iCheck for checkbox and radio inputs
  $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
  });

  
  //Date picker
  $('.date_input').datepicker({
    format: "dd MM yyyy",
    autoclose: true,
    language: "id",
    locale: 'id',
    todayHighlight: true,
    endDate: '31-12-' + (new Date).getFullYear(),
  });



  api.on('xhr', function () {
    $('#check_all').val('');
    var data = api.ajax.json()['data'];
    $.each(data, function (index, value) {
      var id_kib = Object.keys(value)[0];

      var check = $('#check_all').val();
      check += value[id_kib] + ','
      $('#check_all').val(check);
    })
  });

  $(document).on('ifClicked', '#checkbox_validasi', function (e) {
    var checkbox_validasi = !$(this).prop('checked');
    $('#checkbox_validasi_status').val(checkbox_validasi);
    api.ajax.reload();
  });

});


$(document).on('click', '#select_all', function () {
  
  var table = $("#mytable").DataTable();
  var rows_selected = table.column(0).checkboxes.selected();
  list_id  = [];
  $.each(rows_selected, function(index, rowId) {
    list_id .push(rowId);
  })

  var tanggal_validasi = $('#tanggal_validasi_all').val();
  // console.log(list_id);
  // return false;
  if (list_id.length < 1) {
    swal('Data Belum Dipilih!');
    return false;
  }
  // return false;
  swal({
    title: "Apakah kamu yakin?",
    text: "Data yang dipilih akan divalidasi!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Validasi',
    cancelButtonText: 'Batalkan'
  }).then((result) => {
    if (result.value) {
      
      $("#loadMe").modal({
        backdrop: "static", //remove ability to close modal with click
        keyboard: false, //remove option to close with keyboard
        show: true //Display loader!
      });

      url = base_url + 'global_controller/check_all/' + kode_jenis;
      // $.post(url, { list_id: list_id,tanggal_validasi: tanggal_validasi }, function (respon) {

        $.ajax({
          url: url,
          type: "POST",
          data: {
            list_id: list_id,tanggal_validasi: tanggal_validasi
          },
          dataType: "json",
          success: function(respon) {

        $("#loadMe").modal("hide");
        // obj = jQuery.parseJSON(respon);
        obj = respon;
        if (!obj.status) {
          swal({
            type: 'error',
            title: '!!!',
            text: obj.message,
            // footer: '<a href>Why do I have this issue?</a>'
          })
    
        } else {
          swal({
            title: 'Berhasil',
            text: "Validasi Berhasil",
            type: 'success',
            // showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya'
          }).then((result) => {
            if (result.value) {
              var api = $("#mytable").DataTable();
              api.ajax.reload();
            }
          })
        }
        $('#mytable_wrapper').find('li.paginate_button.active>a').click();
        // get_request_validasi();
      },
      error: function(xhr, textStatus, errorThrown) {
        console.log(xhr);
        if (xhr.responseText == 'session timeout') {
            alert('Sesi anda telah habis. Silahkan LOGIN kembali');
              window.location = "<?= site_url('Akun') ?>";
        } else {
            swal({
                  type: 'error',
                  title: '',
                  text: 'Terjadi Kesalahan Sistem',
                  // footer: '<a href>Why do I have this issue?</a>'
              })
        }
  }
      });
    } else {
    }
  })
})

// $(document).on('click', '.live', function() {
//   $('#modal-message').modal('show');
// });
// $(document).on('click', '#select_all_modal', function() {
//   $('#modal-message-all').modal('show');
// });

// $('#modal-message').on('hidden.bs.modal', function () {
//   var api = $("#mytable").DataTable();
//   api.ajax.reload();
// })

$(document).on('click', '.validasi', function () {

  var id_kib = $(this).data('id');
  var tanggal_validasi = $('#tanggal_validasi').val();

  // console.log(id_kib); return false;
  
  swal({
    title: "Apakah kamu yakin?",
    text: "Data yang dipilih akan divalidasi!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Validasi',
    cancelButtonText: 'Batalkan'
  }).then((result) => {
    if (result.value) {

      url = base_url + 'global_controller/validasi/' + kode_jenis;
      $.post(url, { id_kib: id_kib,tanggal_validasi: tanggal_validasi  }, function (respon) {
        $("#loadMe").modal("hide");
        obj = jQuery.parseJSON(respon);
        // swal(
        //   'Berhasil!',
        //   'Data tervalidasi!',
        //   'success'
        // )
        if (!obj.status) {
          swal({
            type: 'error',
            title: '!!!',
            text: obj.message,
            // footer: '<a href>Why do I have this issue?</a>'
          })

        } else {
          swal({
            title: 'Berhasil',
            text: "Validasi Berhasil",
            type: 'success',
            // showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya'
          }).then((result) => {
            if (result.value) {
              // location.reload();
              var api = $("#mytable").DataTable();
              api.ajax.reload();
            }
          })
        }
        $('#mytable_wrapper').find('li.paginate_button.active>a').click();
        // get_request_validasi();
      });

    }
    else{
    
   }
  })

});

$(document).on('click', '.reject', async function () {
  const { value: text } = await swal({
    input: 'textarea',
    inputPlaceholder: 'Alasan Penolakan!',
    // inputValue: $(this).attr('note'),
    showCancelButton: true,
    confirmButtonText: 'Simpan',
    cancelButtonText: 'Batalkan'
  })

  if (text) {
    
    $("#loadMe").modal({
      backdrop: "static", //remove ability to close modal with click
      keyboard: false, //remove option to close with keyboard
      show: true //Display loader!
    });

    var id_kib = $(this).data('id');
    url = base_url + 'global_controller/reject_kib/' + kode_jenis;

    $.post(url, { id_kib: id_kib, note: text }, function (respon) {
      $("#loadMe").modal("hide");
      obj_respon = jQuery.parseJSON(respon);
      swal(
        'Berhasil!',
        'Data ditolak!',
        'success'
      )
      $('#mytable_wrapper').find('li.paginate_button.active>a').click();
      var api = $("#mytable").DataTable();
      api.ajax.reload();
      // get_request_validasi();
    });
  }
});


$(document).on('click', '#tolak_all', async function () {

  var table = $("#mytable").DataTable();
  var rows_selected = table.column(0).checkboxes.selected();
  list_id  = [];
  $.each(rows_selected, function(index, rowId) {
    list_id .push(rowId);
  })
  
  if (list_id.length < 1) {
    swal('Data Belum Dipilih!');
    return false;
  }

  const { value: text } = await swal({
    input: 'textarea',
    inputPlaceholder: 'Alasan Penolakan!',
    // inputValue: $(this).attr('note'),
    showCancelButton: true,
    confirmButtonText: 'Simpan',
    cancelButtonText: 'Batalkan'
  })

  if (text) {
    
    $("#loadMe").modal({
      backdrop: "static", //remove ability to close modal with click
      keyboard: false, //remove option to close with keyboard
      show: true //Display loader!
    });

    var id_kib = list_id;
    url = base_url + 'global_controller/reject_all/' + kode_jenis;

    $.post(url, { id_kib: id_kib, note: text }, function (respon) {
      $("#loadMe").modal("hide");
      obj_respon = jQuery.parseJSON(respon);
      swal(
        'Berhasil!',
        'Data ditolak!',
        'success'
      )
      $('#mytable_wrapper').find('li.paginate_button.active>a').click();
      var api = $("#mytable").DataTable();
      api.ajax.reload();
      // get_request_validasi();
    });
  }
});

$(document).on('click', '#hapus_all', function() {

  var table = $("#mytable").DataTable();
  var rows_selected = table.column(0).checkboxes.selected();
  list_id = [];
  $.each(rows_selected, function(index, rowId) {
    list_id.push(rowId);
  })

  // console.log(list_id);
  // return false;
  if (list_id.length < 1) {
    swal('Data Belum Dipilih!');
    return false;
  }
  // return false;
  swal({
    title: "Apakah kamu yakin?",
    text: "Data yang dipilih akan dihapus!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Hapus',
    cancelButtonText: 'Batalkan'
  }).then((result) => {
    if (result.value) {

      $("#loadMe").modal({
        backdrop: "static", //remove ability to close modal with click
        keyboard: false, //remove option to close with keyboard
        show: true //Display loader!
      });

      url = base_url + 'global_controller/delete_all/' + kode_jenis;
      $.post(url, {
        list_id: list_id
      }, function(respon) {
        $("#loadMe").modal("hide");
        obj = jQuery.parseJSON(respon);
        if (!obj.status) {
          swal({
            type: 'error',
            title: '!!!',
            text: obj.message,
            // footer: '<a href>Why do I have this issue?</a>'
          })

        } else {
          swal({
            title: 'Berhasil',
            text: "Berhasil dihapus!",
            type: 'success',
            // showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya'
          }).then((result) => {
            if (result.value) {
              // location.reload()
              table.ajax.reload();
            }
          })
        }
        $('#mytable_wrapper').find('li.paginate_button.active>a').click();
        // get_request_validasi();
      });
    } else {}
  })
})
