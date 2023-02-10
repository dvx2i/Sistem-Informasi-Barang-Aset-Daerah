//VALIDASI
var kode_jenis = null;
$(document).ready(function () {
  var api = $("#mytable").DataTable();
  kode_jenis = $('#kode_jenis').val();
  var button_all = "<button id='select_all' class='btn btn-sm btn-primary ' style='margin-left: 20px;'>Validasi Semua</button>";

  $('div#mytable_length>label').append(button_all);
  //iCheck for checkbox and radio inputs
  $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
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
  var list_id = $('#check_all').val();
  // console.log(list_id);
  // return false;
  if (!list_id) {
    swal('Data Kosong');
    return false;
  }
  swal({
    title: "Apakah kamu yakin?",
    text: "Semua data yang ditampilkan akan di validasi!",
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
      $.post(url, { list_id: list_id, }, function (respon) {
        $("#loadMe").modal("hide");
        obj_respon = jQuery.parseJSON(respon);
        swal(
          'Berhasil!',
          'Semua data tervalidasi!',
          'success'
        )
        $('#mytable_wrapper').find('li.paginate_button.active>a').click();
        // get_request_validasi();
      });
    } else {
    }
  })
})

$(document).on('click', '.live', function () {

  
  $("#loadMe").modal({
    backdrop: "static", //remove ability to close modal with click
    keyboard: false, //remove option to close with keyboard
    show: true //Display loader!
  });
  

  var id_kib = $(this).attr('id');

  url = base_url + 'global_controller/validasi/' + kode_jenis;
  $.post(url, { id_kib: id_kib, }, function (respon) {
    $("#loadMe").modal("hide");
    obj_respon = jQuery.parseJSON(respon);
    swal(
      'Berhasil!',
      'Data tervalidasi!',
      'success'
    )
    $('#mytable_wrapper').find('li.paginate_button.active>a').click();
    // get_request_validasi();
  });

});

$(document).on('click', '.reject', async function () {
  const { value: text } = await swal({
    input: 'textarea',
    inputPlaceholder: 'Alasan Penolakan!',
    inputValue: $(this).attr('note'),
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

    var id_kib = $(this).attr('id_kib')
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
      // get_request_validasi();
    });
  }
});
